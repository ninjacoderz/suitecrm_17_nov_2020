<?php
// Create three

// ini_set('display_errors',1);
$po_type = $_REQUEST["type"];
$action = $_REQUEST['action'];
$invoice_installation = $_REQUEST['invoice_installation'];
$purchase_installation = $_REQUEST['purchase_installation'];

if(isset($invoice->id) && $invoice->id != ""){
    // do nothing
}
else{
    $invoiceID = $_REQUEST["record_id"];
    $invoice = BeanFactory::getBean("AOS_Invoices", $invoiceID);
}

if($invoice->id == ""){
    return;
}
function createPO($po_type="", $invoice,$invoice_installation,$purchase_installation){
    $purchaseOrder = new PO_purchase_order();
    $purchaseOrder->name = $invoice->name;
    if($po_type == "plumber"){
        $purchaseOrder->name .= " Plumbing";
    }
    if($po_type == "electrical"){
        $purchaseOrder->name .= " Electrical";
    }

    if($po_type == "daikin"){
        $purchaseOrder->name = "PureElectric Daikin "; //VUT
        // $purchaseOrder->name .= " Supply";
    }
    $purchaseOrder->assigned_user_id = $invoice->assigned_user_id;
    $purchaseOrder->install_date = $invoice->plumber_install_date_c;
    if($po_type == "plumber" && $invoice->installation_date_c){
        $installation_date_explode = explode(" ", $invoice->installation_date_c);
        if(count($installation_date_explode) >= 2)
            $purchaseOrder->install_date = $installation_date_explode[0];
    }
    if(isset($invoice->id) && $invoice->id != ""){
        $quote_numer = $invoice->quote_number;
        
        $db = DBManagerFactory::getInstance();
        
        $sql = "SELECT * FROM aos_quotes WHERE 1=1 ";
        $sql .= " AND number = '" . $quote_numer . "'";
        $sql .= " AND deleted != 1 ";
        $ret = $db->query($sql);

        while ($row = $db->fetchByAssoc($ret)) {
            if (isset($row) && $row != null) {
                $quote_name = $row["name"];
                $quote_id = $row["id"];
            }
        }
    }
    $purchaseOrder->aos_quotes_po_purchase_order_1_name = $quote_name;
    $purchaseOrder->aos_quotes_po_purchase_order_1aos_quotes_ida = $quote_id;
    $purchaseOrder->aos_invoices_po_purchase_order_1aos_invoices_ida = $invoice->id;
    if($po_type == "plumber"){
        $purchaseOrder->billing_account_id       = $invoice->account_id1_c;
    } elseif ($po_type == "electrical"){
        $purchaseOrder->billing_account_id       = $_GET['electrical_account_id']?$_GET['electrical_account_id']:$invoice->account_id_c;
    } elseif ($po_type == "daikin"){
        $purchaseOrder->billing_account_id       = $_REQUEST["daikin_supplier"]?$_REQUEST["daikin_supplier"]: $invoice->account_id2_c;
    }
    elseif ($po_type == "sanden_supply"){
        $purchaseOrder->billing_account_id       = '86516ff6-0cd7-9ccc-4373-58ad559a8e12'; //Sanden International (Australia) Pty Ltd
    }
    else {
        // Todo
        $purchaseOrder->billing_account_id       = $invoice->account_id_c;
    }
    $purchaseOrder->shipping_account_id      = $invoice->billing_account_id;
    if($po_type == "plumber"){
        $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id1_c);
    } elseif ($po_type == "electrical"){
        $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id_c);
    } 
    //Dung code 
    elseif($po_type == "daikin"){
        $supplier = BeanFactory::getBean("Accounts", $_REQUEST["daikin_supplier"]?$_REQUEST["daikin_supplier"]: $invoice->account_id2_c);
    }    
    //End Dung code
    elseif ($po_type == "sanden_supply"){
        $supplier =  BeanFactory::getBean("Accounts", '86516ff6-0cd7-9ccc-4373-58ad559a8e12'); //Sanden International (Australia) Pty Ltd
    }
    else {
        // Todo
        $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id2_c);
    }
    if($supplier->id){
        $purchaseOrder->billing_address_street  = $supplier->billing_address_street;
        $purchaseOrder->billing_address_city  = $supplier->billing_address_city;
        $purchaseOrder->billing_address_postalcode = $supplier->billing_address_postalcode;
        $purchaseOrder->billing_address_state = $supplier->billing_address_state;
    }

    $purchaseOrder->shipping_address_street     = $invoice->install_address_c?$invoice->install_address_c:$invoice->shipping_address_street;
    $purchaseOrder->shipping_address_city       = $invoice->install_address_city_c?$invoice->install_address_city_c:$invoice->shipping_address_city;
    $purchaseOrder->shipping_address_state      = $invoice->install_address_state_c?$invoice->install_address_state_c:$invoice->shipping_address_state;
    $purchaseOrder->shipping_address_postalcode = $invoice->install_address_postalcode_c?$invoice->install_address_postalcode_c:$invoice->shipping_address_postalcode;

    $purchaseOrder->save();

    // For invoice sanden
    $is_sanden = false;
    $is_daikin = false;
    $sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
    $result = $db->query($sql);
    while ($row = $db->fetchByAssoc($result)) {
        if(strpos(strtolower($row['name']), 'sanden') !== false){
            $is_sanden = true;
        }
        if(strpos(strtolower($row['name']), 'daikin')!== false){
            $is_daikin = true;
        }
    }
                
    if($is_sanden && $po_type == "plumber"){

        $row['id'] = "";
        $row['name'] = 'Sanden Install';
        $row['currency_id'] = '-99';
        $row['number'] = '1';
        $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
        $row['parent_id'] = $purchaseOrder->id;
        $row['parent_type'] = 'PO_purchase_order';
        
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice-> save();

        $part_numners = array(
            "Sanden_Plb_Install_Std",
            "PB",
            "Sanden_Tank_Slab",
            "Sanden_HP_Pavers",
            
        );
        $part_numners_implode = implode("','", $part_numners);
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
        $ret = $db->query($sql);

        $products = array();
        while ($row = $db->fetchByAssoc($ret))
        {

            $product = array();
            $product['product_currency'] = $row['currency_id'];
            $product['product_item_description'] = $row['description'];
            $product['product_name'] = $row['name'];
            $product['product_part_number'] = $row['part_number'];
            $product['product_product_cost_price'] = $row['cost'];
            $product['product_product_id'] = $row['id'];
            $product['product_product_list_price'] = $row['price'];
            $products[$product['product_part_number']] = $product;

        }
        $ordered_products = array();
        foreach($part_numners as $part_number){
            $ordered_products[$part_number] = $products[$part_number];
        }
        $return_product = array();
        foreach($ordered_products as $product){

            $return_product[] = $product;
        }
        //print(json_encode($return_product));

        $number_items = 1;
        $total_price = 0;
        foreach($return_product as $product){
            $row = array();
            $row['id'] = '';
            $row['parent_id'] = $purchaseOrder->id;
            $row['parent_type'] = 'PO_purchase_order';
            //Sanden Standard Plumbing Install
            $row['name'] = $product['product_name'];
            $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
            $row['currency_id'] = -99;
            $row['part_number'] = $product['product_part_number'];
            $row['item_description'] = $product['product_item_description'];
            $row['number'] = $number_items;
            $row['product_qty'] = format_number(1);
            $row['product_cost_price'] = format_number($product['product_product_cost_price']);
            $row['product_list_price'] = format_number($product['product_product_cost_price']);
            $row['discount'] = "Percentage";
            $row['product_unit_price'] = format_number($product['product_product_cost_price']);
            $row['product_amt'] = 'vat_amt';
            $row['vat_amt'] = format_number($product['product_product_cost_price']/10);
            $row['product_total_price'] = format_number($product['product_product_cost_price']);
            $row['vat'] = "10.0";
            $row['group_id'] = $group_invoice->id;
            $row['product_id'] = $product['product_product_id'];
            $total_price += $product['product_product_cost_price'];
            $prod_invoice = new AOS_Products_Quotes();
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            $number_items ++;
        }
        
        /*$row['total_amt'] = format_number($total_price);
        $row['discount_amount'] = format_number(0.00);
        $row['subtotal_amount'] = format_number($total_price);
        $row['tax_amount'] = format_number($total_price/10);
        $row['total_amount'] = format_number($total_price + $total_price/10);
        */

        $purchaseOrder->total_amt = format_number($total_price);
        $purchaseOrder->subtotal_amount = format_number($total_price);
        $purchaseOrder->tax_amount = format_number($total_price/10);
        $purchaseOrder->total_amount = format_number($total_price + $total_price/10);
        $purchaseOrder->installation_pdf_c = $purchase_installation;
        $purchaseOrder->save();
    }

    if($is_daikin && $po_type == "plumber"){

        $row['id'] = "";
        $row['name'] = 'Daikin Install';
        $row['currency_id'] = '-99';
        $row['number'] = '1';
        $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
        $row['parent_id'] = $purchaseOrder->id;
        $row['parent_type'] = 'PO_purchase_order';
        
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice-> save();

        $part_numners = array(
            "STANDARD_AC_INSTALL",
            "PB",
        );
        $part_numners_implode = implode("','", $part_numners);
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
        $ret = $db->query($sql);

        $products = array();
        while ($row = $db->fetchByAssoc($ret))
        {

            $product = array();
            $product['product_currency'] = $row['currency_id'];
            $product['product_item_description'] = $row['description'];
            $product['product_name'] = $row['name'];
            $product['product_part_number'] = $row['part_number'];
            $product['product_product_cost_price'] = $row['cost'];
            $product['product_product_id'] = $row['id'];
            $product['product_product_list_price'] = $row['price'];
            $products[$product['product_part_number']] = $product;

        }
        $ordered_products = array();
        foreach($part_numners as $part_number){
            $ordered_products[$part_number] = $products[$part_number];
        }
        $return_product = array();
        foreach($ordered_products as $product){

            $return_product[] = $product;
        }
        //print(json_encode($return_product));

        $number_items = 1;
        $total_price = 0;
        foreach($return_product as $product){
            $row = array();
            $row['id'] = '';
            $row['parent_id'] = $purchaseOrder->id;
            $row['parent_type'] = 'PO_purchase_order';
            //Sanden Standard Plumbing Install
            $row['name'] = $product['product_name'];
            $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
            $row['currency_id'] = -99;
            $row['part_number'] = $product['product_part_number'];
            $row['item_description'] = $product['product_item_description'];
            $row['number'] = $number_items;
            $row['product_qty'] = format_number(1);
            $row['product_cost_price'] = format_number($product['product_product_cost_price']);
            $row['product_list_price'] = format_number($product['product_product_cost_price']);
            $row['discount'] = "Percentage";
            $row['product_unit_price'] = format_number($product['product_product_cost_price']);
            $row['product_amt'] = 'vat_amt';
            $row['vat_amt'] = format_number($product['product_product_cost_price']/10);
            $row['product_total_price'] = format_number($product['product_product_cost_price']);
            $row['vat'] = "10.0";
            $row['group_id'] = $group_invoice->id;
            $row['product_id'] = $product['product_product_id'];
            $total_price += $product['product_product_cost_price'];
            $prod_invoice = new AOS_Products_Quotes();
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            $number_items ++;
        }

        $purchaseOrder->total_amt = format_number($total_price);
        $purchaseOrder->subtotal_amount = format_number($total_price);
        $purchaseOrder->tax_amount = format_number($total_price/10);
        $purchaseOrder->total_amount = format_number($total_price + $total_price/10);
        $purchaseOrder->save();

    }
    // Electrical 
    if($is_sanden && $po_type == "electrical"){

        $row['id'] = "";
        $row['name'] = 'Sanden Install';
        $row['currency_id'] = '-99';
        $row['number'] = '1';
        $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
        $row['parent_id'] = $purchaseOrder->id;
        $row['parent_type'] = 'PO_purchase_order';
        
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice-> save();

        $part_numners = array(
            "Sanden_Elec_Install_Std",
            "PB",
        );

        $part_numners_implode = implode("','", $part_numners);
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
        $ret = $db->query($sql);

        $products = array();
        while ($row = $db->fetchByAssoc($ret))
        {

            $product = array();
            $product['product_currency'] = $row['currency_id'];
            $product['product_item_description'] = $row['description'];
            $product['product_name'] = $row['name'];
            $product['product_part_number'] = $row['part_number'];
            $product['product_product_cost_price'] = $row['cost'];
            $product['product_product_id'] = $row['id'];
            $product['product_product_list_price'] = $row['price'];
            $products[$product['product_part_number']] = $product;

        }
        $ordered_products = array();
        foreach($part_numners as $part_number){
            $ordered_products[$part_number] = $products[$part_number];
        }
        $return_product = array();
        foreach($ordered_products as $product){

            $return_product[] = $product;
        }
        //print(json_encode($return_product));

        $number_items = 1;
        $total_price = 0;
        foreach($return_product as $product){
            $row = array();
            $row['id'] = '';
            $row['parent_id'] = $purchaseOrder->id;
            $row['parent_type'] = 'PO_purchase_order';
            //Sanden Standard Plumbing Install
            $row['name'] = $product['product_name'];
            $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
            $row['currency_id'] = -99;
            $row['part_number'] = $product['product_part_number'];
            $row['item_description'] = $product['product_item_description'];
            $row['number'] = $number_items;
            $row['product_qty'] = format_number(1);
            $row['product_cost_price'] = format_number($product['product_product_cost_price']);
            $row['product_list_price'] = format_number($product['product_product_cost_price']);
            $row['discount'] = "Percentage";
            $row['product_unit_price'] = format_number($product['product_product_cost_price']);
            $row['product_amt'] = 'vat_amt';
            $row['vat_amt'] = format_number($product['product_product_cost_price']/10);
            $row['product_total_price'] = format_number($product['product_product_cost_price']);
            $row['vat'] = "10.0";
            $row['group_id'] = $group_invoice->id;
            $row['product_id'] = $product['product_product_id'];
            $total_price += $product['product_product_cost_price'];
            $prod_invoice = new AOS_Products_Quotes();
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            $number_items ++;
        }

        $purchaseOrder->total_amt = format_number($total_price);
        $purchaseOrder->subtotal_amount = format_number($total_price);
        $purchaseOrder->tax_amount = format_number($total_price/10);
        $purchaseOrder->total_amount = format_number($total_price + $total_price/10);
        $purchaseOrder->save();
        
    }
    // Sanden Supply 
    if($is_sanden && $po_type == "sanden_supply"){
        $purchaseOrder->po_type_c = 'sanden_supply';
        $purchaseOrder->name = 'Sanden';
        $group_total = 0;
        // save Group
        $row['id'] = '';
        $row['name'] = 'Sanden';
        $row['currency_id'] = '-99';
        $row['number'] = '2';
        $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
        $row['parent_id'] = $purchaseOrder->id;
        $row['parent_type'] = 'PO_purchase_order';
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice->save();
        $purchaseOrder->dispatch_date_c = explode(" ",$invoice->dispatch_date_c)[0];
        $dateInfos = explode("/", explode(" ",$invoice->dispatch_date_c)[0]);
        $inv_dispatch_date_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
        $string_dispatch_date = date("d M Y", strtotime($inv_dispatch_date_str));
        //Setting Group Line Items
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            if(strpos($row['part_number'], "GAUS-") !== false  || strpos($row['part_number'], "QIK15−HPUMP") !== false ){
                $purchaseOrder->name .= ' '.(int)$row['product_qty'].'x' .$row['part_number'];
                if(strpos($row['part_number'], "GAUS-") !== false ){
                    $row['number'] = '1';
                }
                $row['id'] = '';
                $row['parent_id'] = $purchaseOrder->id;
                $row['parent_type'] = 'PO_purchase_order';
                $row['group_id'] = $group_invoice->id;
                $part_number_product = $row['part_number'];
                $sql_pruduct = "SELECT * FROM aos_products WHERE part_number IN ('".$part_number_product."') LIMIT 1";
                $return_product = $db->query($sql_pruduct);
        
                $products = array();
                while ($row_pruduct = $db->fetchByAssoc($return_product))
                {
                    if($row['product_cost_price'] != null)
                    {
                        $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    }
                    $group_total += ((float)(str_replace(",","",$row_pruduct['cost']))) * $row['product_qty'];
                    $row['product_list_price'] = $row_pruduct['cost'];
                    if($row['product_discount'] != null)
                    {
                        $row['product_discount'] = format_number($row['cost']);
                        $row['product_discount_amount'] = format_number($row['cost']);
                    }
                    $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    $row['product_list_price'] = format_number($row_pruduct['cost']);
                    $row['discount'] = "Percentage";
                    $row['product_unit_price'] = format_number($row_pruduct['cost']);
                    $row['product_amt'] = 'vat_amt';
                    $row['product_total_price'] = format_number($row_pruduct['cost'])*format_number($row['product_qty']);
                    $row['vat_amt'] = format_number($row['product_total_price']/10);
                    $row['vat'] = "10.0";
                    $row['product_qty'] = format_number($row['product_qty']);
                }

                $prod_invoice = new AOS_Products_Quotes();
                $prod_invoice->populateFromRow($row);
                $prod_invoice->save();
            }
            // logic get product with part number GAUS
            if(strpos($row['part_number'], "GAUS-") !== false ){
                $row['number'] ++;
                $part_numbers = ["HPFT-1","GAU-A45HPC"];
                switch ($row['part_number']) {
                    case 'GAUS-315FQS':
                        $part_numbers[] = "SAN-315SAQA";
                        break;
                    case 'GAUS-315FQV':
                        $part_numbers[] = "SAN-315VE";
                        break;
                    case 'GAUS-250FQS':
                        $part_numbers[] = "SAN-250SAQA";
                        break;
                    default:
                        # code...
                        break;
                }
                $part_numbers_implode = implode("','", $part_numbers);
           
                $sql_pruduct = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numbers_implode."')";
                $return_product = $db->query($sql_pruduct);
        
                $products = array();
                while ($row_pruduct = $db->fetchByAssoc($return_product))
                {
                    $row['id'] = '';
                    $row['parent_id'] = $purchaseOrder->id;
                    $row['parent_type'] = 'PO_purchase_order';
                    $row['group_id'] = $group_invoice->id;
                    $row['part_number'] = $row_pruduct['part_number'];
                    $row['item_description'] = $row_pruduct['description'];
                    if($row['product_cost_price'] != null)
                    {
                        $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    }
                    $group_total += ((float)(str_replace(",","",$row_pruduct['cost']))) * $row['product_qty'];
                    $row['product_list_price'] = $row_pruduct['cost'];
                    if($row['product_discount'] != null)
                    {
                        $row['product_discount'] = format_number($row['cost']);
                        $row['product_discount_amount'] = format_number($row['cost']);
                    }
                    $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    $row['product_list_price'] = format_number($row_pruduct['cost']);
                    $row['discount'] = "Percentage";
                    $row['product_unit_price'] = format_number($row_pruduct['cost']);
                    $row['product_amt'] = 'vat_amt';
                    $row['product_total_price'] = format_number($row_pruduct['cost'])*format_number($row['product_qty']);
                    $row['vat_amt'] = format_number($row['product_total_price']/10);
                    $row['vat'] = "0.0";
                    $row['product_qty'] = format_number($row['product_qty']);
                    
                    $prod_invoice = new AOS_Products_Quotes();
                    $prod_invoice->populateFromRow($row);
                    $prod_invoice->save();
                }

            }

        }

   
        $group_total += $total_price;

        $group_invoice->total_amt = format_number($group_total);
        $group_invoice->discount_amount = format_number($group_total);
        $group_invoice->subtotal_amount = format_number($group_total);
        $group_invoice->tax_amount = format_number($group_total/10);
        $group_invoice->total_amount = format_number($group_total*1.1);
        $group_invoice->save();

        $purchaseOrder->total_amt = format_number($group_total);
        $purchaseOrder->subtotal_amount = format_number($group_total);
        $purchaseOrder->tax_amount = format_number($group_total/10);
        $purchaseOrder->total_amount = format_number($group_total*1.1);

        $purchaseOrder->name .= ' ' .$purchaseOrder->shipping_address_city 
        . ' ' .$purchaseOrder->shipping_address_state .' '.$string_dispatch_date;

        $purchaseOrder->save();
        
    }
    
    if($po_type == "daikin"){
        $group_total = 0;
        // save Group
        $row['id'] = '';
        $row['name'] = 'Daikin';
        $row['currency_id'] = '-99';
        $row['number'] = '1';
        $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
        $row['parent_id'] = $purchaseOrder->id;
        $row['parent_type'] = 'PO_purchase_order';
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice->save();
        // Logic for get only item that have "Daikin US7" in title

        //Setting Group Line Items
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            if(strpos($row['name'], "Nexura") !== false || strpos($row['name'], "Daikin US7") !== false || strpos($row['name'], "Daikin WiFi Controller") !== false ){
                $row['id'] = '';
                $row['parent_id'] = $purchaseOrder->id;
                $row['parent_type'] = 'PO_purchase_order';
                $row['group_id'] = $group_invoice->id;
                $part_number_product = $row['part_number'];
                $sql_pruduct = "SELECT * FROM aos_products WHERE part_number IN ('".$part_number_product."') LIMIT 1";
                $return_product = $db->query($sql_pruduct);
        
                $products = array();
                while ($row_pruduct = $db->fetchByAssoc($return_product))
                {
                    if($row['product_cost_price'] != null)
                    {
                        $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    }
                    $group_total += ((float)(str_replace(",","",$row_pruduct['cost']))) * $row['product_qty'];
                    $row['product_list_price'] = $row_pruduct['cost'];
                    if($row['product_discount'] != null)
                    {
                        $row['product_discount'] = format_number($row['cost']);
                        $row['product_discount_amount'] = format_number($row['cost']);
                    }
                    $row['product_cost_price'] = format_number($row_pruduct['cost']);
                    $row['product_list_price'] = format_number($row_pruduct['cost']);
                    $row['discount'] = "Percentage";
                    $row['product_unit_price'] = format_number($row_pruduct['cost']);
                    $row['product_amt'] = 'vat_amt';
                    $row['product_total_price'] = format_number($row_pruduct['cost'])*format_number($row['product_qty']);
                    $row['vat_amt'] = format_number($row['product_total_price']/10);
                    $row['vat'] = "10.0";
                    $row['product_qty'] = format_number($row['product_qty']);
                }

                $prod_invoice = new AOS_Products_Quotes();
                $prod_invoice->populateFromRow($row);
                $prod_invoice->save();
            }

        }
        //////////////////////

        $part_numners = array(
            "DAIKIN_MEL_METRO_DELIVERY",
        );

        $part_numners_implode = implode("','", $part_numners);
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
        $ret = $db->query($sql);

        $products = array();
        while ($row = $db->fetchByAssoc($ret))
        {

            $product = array();
            $product['product_currency'] = $row['currency_id'];
            $product['product_item_description'] = $row['description'];
            $product['product_name'] = $row['name'];
            $product['product_part_number'] = $row['part_number'];
            $product['product_product_cost_price'] = $row['cost'];
            $product['product_product_id'] = $row['id'];
            $product['product_product_list_price'] = $row['price'];
            $products[$product['product_part_number']] = $product;

        }
        $ordered_products = array();
        foreach($part_numners as $part_number){
            $ordered_products[$part_number] = $products[$part_number];
        }
        $return_product = array();
        foreach($ordered_products as $product){

            $return_product[] = $product;
        }
        //print(json_encode($return_product));

        $number_items = 1;
        $total_price = 0;
        foreach($return_product as $product){
            $row = array();
            $row['id'] = '';
            $row['parent_id'] = $purchaseOrder->id;
            $row['parent_type'] = 'PO_purchase_order';
            //Sanden Standard Plumbing Install
            $row['name'] = $product['product_name'];
            $row['assigned_user_id'] = $purchaseOrder->assigned_user_id;
            $row['currency_id'] = -99;
            $row['part_number'] = $product['product_part_number'];
            $row['item_description'] = $product['product_item_description'];
            $row['number'] = $number_items;
            $row['product_qty'] = format_number(1);
            $row['product_cost_price'] = format_number($product['product_product_cost_price']);
            $row['product_list_price'] = format_number($product['product_product_cost_price']);
            $row['discount'] = "Percentage";
            $row['product_unit_price'] = format_number($product['product_product_cost_price']);
            $row['product_amt'] = 'vat_amt';
            $row['vat_amt'] = format_number($product['product_product_cost_price']/10);
            $row['product_total_price'] = format_number($product['product_product_cost_price']);
            $row['vat'] = "10.0";
            $row['group_id'] = $group_invoice->id;
            $row['product_id'] = $product['product_product_id'];
            $total_price += $product['product_product_cost_price'];
            $prod_invoice = new AOS_Products_Quotes();
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            $number_items ++;
        }

        ///////////
        $group_total += $total_price;

        $group_invoice->total_amt = format_number($group_total);
        $group_invoice->discount_amount = format_number($group_total);
        $group_invoice->subtotal_amount = format_number($group_total);
        $group_invoice->tax_amount = format_number($group_total/10);
        $group_invoice->total_amount = format_number($group_total*1.1);
        $group_invoice->save();

        $purchaseOrder->total_amt = format_number($group_total);
        $purchaseOrder->subtotal_amount = format_number($group_total);
        $purchaseOrder->tax_amount = format_number($group_total/10);
        $purchaseOrder->total_amount = format_number($group_total*1.1);
        //VUT-S-Create subject for Daikin PO
        $daikin_product_infomation = $_REQUEST['daikin_product'];
        $daikin_products = html_entity_decode($daikin_product_infomation);
        $daikin_product = json_decode($daikin_products);
        $product_names = array();
        foreach ($daikin_product as $key => $value) {
            if (strpos(strtolower($value->product_name), 'small') !== false) { $value->product_name = str_ireplace('small','', $value->product_name);} 
            if (strpos(strtolower($value->product_name), 'medium') !== false) { $value->product_name = str_ireplace('medium','', $value->product_name);} 
            if (strpos(strtolower($value->product_name), 'large') !== false) { $value->product_name = str_ireplace('large','', $value->product_name);} 
            array_push($product_names, trim($value->product_name));
        }
        $array_product = array_count_values($product_names);        
        foreach ($array_product as $name => $quantity) {
            $purchaseOrder->name .= $quantity.'x'.$name.' ';
        }
        //**address + time */
        $invoice->delivery_contact_suburb_c = $_REQUEST["delivery_contact_suburb"];
        $invoice->delivery_contact_state_c = $_REQUEST["delivery_contact_state"];
        $invoice->delivery_date_c = $_REQUEST["delivery_date"];
        $purchaseOrder->install_date = $invoice->delivery_date_c; 


        $time = date("d M Y",strtotime(str_replace('/','-',$invoice->delivery_date_c))); 
        $purchaseOrder->name .= "to ".$invoice->delivery_contact_suburb_c." ".$invoice->delivery_contact_state_c." ".$time;
        //VUT-E-Create subject for Daikin PO
        $purchaseOrder->save();
    }
    // tuan code
    if($invoice_installation != ""){
        $purchase = new PO_purchase_order();
        $purchase->retrieve($purchaseOrder->id);
        $installation_pdf = $purchase->installation_pdf_c;
        $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
        get_all_file_invoice_to_po($path,$invoice_installation,$installation_pdf);
    }


    if($po_type == "plumber"){
        $invoice->plumber_po_c = $purchaseOrder->id;
    } elseif($po_type == "electrical"){
        $invoice->electrical_po_c = $purchaseOrder->id;
    } else {
        // todo daikin_po_c 
        $invoice->daikin_po_c = $purchaseOrder->id;
    }
    $invoice->save();
    echo $purchaseOrder->id;

}

function UpdatePO($invoice,$purchaseOrder){
    if($purchaseOrder->id == '') return;
    $purchaseOrder->install_date = $invoice->plumber_install_date_c;
    $purchaseOrder->save();
    return;
}

if($action == 'update'){
    $purchaseOrderID = $_REQUEST['ID_PurchacheOrder'];
    $purchaseOrder = BeanFactory::getBean("PO_purchase_order", $purchaseOrderID);
    UpdatePO($invoice,$purchaseOrder);
    die();
}

if($create_three_po){
    //createPO("plumber",$invoice);
    //createPO("electrical", $invoice);
   // createPO("daikin", $invoice);
}
else{
    createPO($po_type, $invoice,$invoice_installation,$purchase_installation);
    die();
}

function get_all_file_invoice_to_po($path,$invoice_installation,$installation_pdf){
    $get_all_photo = dirToArray_fromInvoice($path.$invoice_installation);
    foreach ($get_all_photo as $key => $value) 
    { 
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$invoice_installation.'/'.$value);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: i2.au.reastatic.net';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
    $headers[] = 'If-None-Match: ^^2b9ca-VKLgmK5DW5KYN32ORw3+mwobYhU^^\"\"';
    $headers[] = 'If-Modified-Since: Sat, 25 May 2013 08:01:21 GMT';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);
    $path_save_file = $_SERVER['DOCUMENT_ROOT']."/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$installation_pdf;
    $path_save_file_new_file = $path_save_file .'/'.$value;

    if(!file_exists ($path_save_file)) {
        mkdir($path_save_file);
    }
    file_put_contents($path_save_file_new_file ,$result);
    create_thumbnail($path_save_file_new_file,$value,$path_save_file);
    }
}
function dirToArray_fromInvoice($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = dirToArray_fromInvoice($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}

//function create thumbnail from source
function create_thumbnail($source,$file_name,$path_save_file){
    //$type = strtolower(substr(strrchr($file_name, '.'), 1));
    if (exif_imagetype($source) == 2) {
        $type = 'jpeg';
    }else if(exif_imagetype($source) == 3){
        $type = 'png';
    }else if(exif_imagetype($source) == 1){
        $type = 'gif';
    } else {
        $type = 'jpeg';
    }
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
        if(!file_exists ($path_save_file."/thumbnail/")) {
        mkdir($path_save_file."/thumbnail/");
        }
        $thumb =  $path_save_file."/thumbnail/".$file_name;
        switch ($type) {
        case 'jpg': // Both regular and progressive jpegs
        case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                $options['jpeg_quality'] : 75;
                break;
        case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
        case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                $options['png_quality'] : 9;
                break;
        default: $typeok = FALSE; break;
        }
        if ($typeok){
            list($w, $h) = getimagesize($source);

            $src = $src_func($source);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
    }         
}
