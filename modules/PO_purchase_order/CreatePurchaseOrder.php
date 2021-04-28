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
    $purchaseOrder->assigned_user_id = $invoice->assigned_user_id;
    
    switch ($po_type) {
        case 'plumber': case 'plumber_quote':
            $purchaseOrder->name .= " Plumbing";
            $purchaseOrder->install_date = $invoice->plumber_install_date_c;
            break;
        case 'electrical': case 'electrician_quote':
            $purchaseOrder->name .= " Electrical";
            $purchaseOrder->install_date = $invoice->electrician_install_date_c;
            break;
        case 'daikin':
            $purchaseOrder->name = "PureElectric Daikin ";
            $purchaseOrder->install_date = $invoice->electrician_install_date_c;
            break;        
        default:
            break;
    }

    if(!($purchaseOrder->install_date)&& $invoice->installation_date_c){
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
    if($po_type == "plumber" || $po_type == "plumber_quote"){
        $purchaseOrder->billing_account_id       = $invoice->account_id1_c;
    } elseif ($po_type == "electrical"  || $po_type == "electrician_quote"){
        $purchaseOrder->billing_account_id       = $_GET['electrical_account_id']?$_GET['electrical_account_id']:$invoice->account_id_c;
    } elseif ($po_type == "daikin"){
        $purchaseOrder->billing_account_id       = $_REQUEST["daikin_supplier"]?$_REQUEST["daikin_supplier"]: $invoice->account_id2_c;
    }
    elseif ($po_type == "sanden_supply"){
        $purchaseOrder->billing_account_id       = '86516ff6-0cd7-9ccc-4373-58ad559a8e12'; //Sanden International (Australia) Pty Ltd
    } elseif ($po_type == "solar_supply") {
        $purchaseOrder->billing_account_id = '5e8c03d5-5879-f939-3eef-5ccfe7d3a5f3'; //Sunpower Australia
    }
    else {
        // Todo
        $purchaseOrder->billing_account_id       = $invoice->account_id_c;
    }
    $purchaseOrder->shipping_account_id      = $invoice->billing_account_id;
    if($po_type == "plumber" || $po_type == "plumber_quote"){
        $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id1_c);
    } elseif ($po_type == "electrical" || $po_type == "electrician_quote"){
        $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id_c);
    } 
    //Dung code 
    elseif($po_type == "daikin"){
        $supplier = BeanFactory::getBean("Accounts", $_REQUEST["daikin_supplier"]?$_REQUEST["daikin_supplier"]: $invoice->account_id2_c);
    }    
    //End Dung code
    elseif ($po_type == "sanden_supply"){
        $supplier =  BeanFactory::getBean("Accounts", '86516ff6-0cd7-9ccc-4373-58ad559a8e12'); //Sanden International (Australia) Pty Ltd
    } elseif ($po_type == "solar_supply") {
        $supplier =  BeanFactory::getBean("Accounts", '5e8c03d5-5879-f939-3eef-5ccfe7d3a5f3');
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
    $meeting_installer = '';
    if($is_sanden && $po_type == "plumber"){
        // $purchaseOrder->po_type_c = 'installer';
        $purchaseOrder->po_type_c = 'sanden_plumber';
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
            "Sanden_Tank_Slab",
            "Sanden_HP_Pavers",
            "PB",
            "Photo_Upload_Bonus",
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
        $meeting_installer = createMettingForPOInstaller($invoice, $purchaseOrder, 'plumber');
        $invoice->meeting_plumber = $meeting_installer;
        $purchaseOrder->meeting_id = $meeting_installer;
        $purchaseOrder->total_amt = format_number($total_price);
        $purchaseOrder->subtotal_amount = format_number($total_price);
        $purchaseOrder->tax_amount = format_number($total_price/10);
        $purchaseOrder->total_amount = format_number($total_price + $total_price/10);
        $purchaseOrder->installation_pdf_c = $purchase_installation;
        $purchaseOrder->save();
    }

    //VUT - S - Create Plumber from Quote
    if($is_sanden && $po_type == "plumber_quote" && isset($quote_id)){
        $purchaseOrder->po_type_c = 'sanden_plumber';
        //get data Quote
        $quote = new AOS_Quotes();
        $quote->retrieve($quote_id);
        //get line group
        $db = DBManagerFactory::getInstance();
        $sql_grp = "    SELECT *
                        FROM aos_line_item_groups 
                        WHERE parent_include = '" . $quote->object_name . "' AND parent_id = '" . $quote->id . "' AND po_type = 'sanden_plumber' AND deleted = 0 
                        ORDER BY number ASC";
        $ret = $db->query($sql_grp);

        while ($grp = $db->fetchByAssoc($ret)) {
            $grp_old_id = '';
            $sql_line = '';
            if (isset($grp) && $grp != null) {
                $grp_old_id = $grp['id'];
                $group_quote = new AOS_Line_Item_Groups();

                $grp['id'] = "";
                $grp['assigned_user_id'] = $purchaseOrder->assigned_user_id;
                $grp['parent_id'] = $purchaseOrder->id;
                $grp['parent_type'] = 'PO_purchase_order';
                $grp['parent_include'] = '';
                $grp['po_type'] = '';
                $group_quote->populateFromRow($grp);
                $group_quote->save();
                //get line item of group_invoice
                $sql_line = "   SELECT *
                                FROM aos_products_quotes pg
                                WHERE group_id = '" . $grp_old_id . "' AND parent_include = '" . $quote->object_name . "' AND parent_id = '" . $quote->id . "' AND po_type = 'sanden_plumber' AND deleted = 0";
                $ret_line = $db->query($sql_line);
                $number_items = 1;
                // $total_price = 0;
                while ($line = $db->fetchByAssoc($ret_line)) {
                    $line['id'] = '';
                    $line['parent_id'] = $purchaseOrder->id;
                    $line['parent_type'] = 'PO_purchase_order';
                    //remove 
                    $line['parent_include'] = '';
                    $line['po_type'] = '';
                    //remove 
                    $line['assigned_user_id'] = $purchaseOrder->assigned_user_id;
                    $line['number'] = $number_items;
                    $line['group_id'] = $group_quote->id;
                    // $total_price += $line['product_cost_price'];
                    $prod_quote = new AOS_Products_Quotes();
                    $prod_quote->populateFromRow($line);
                    $prod_quote->save();
                    $number_items++;
                }

            }
        }
        
        $meeting_installer = createMettingForPOInstaller($invoice, $purchaseOrder, 'plumber');
        $invoice->meeting_plumber = $meeting_installer;
        $purchaseOrder->meeting_id = $meeting_installer;
        //populate fields price
        $purchaseOrder->total_amt           = $quote->plumber_total_amt;
        $purchaseOrder->discount_amount     = $quote->plumber_discount_amount;
        $purchaseOrder->subtotal_amount     = $quote->plumber_subtotal_amount;
        $purchaseOrder->shipping_amount     = $quote->plumber_shipping_amount;
        $purchaseOrder->shipping_tax_amt    = $quote->plumber_shipping_tax_amt;
        $purchaseOrder->tax_amount          = $quote->plumber_tax_amount;
        $purchaseOrder->total_amount        = $quote->plumber_total_amount;

        $purchaseOrder->installation_pdf_c = $purchase_installation;
        $purchaseOrder->save();
    }
    //VUT - E - Create Plumber from Quote

    //VUT - S - Create Electrician from Quote
    if($is_sanden && $po_type == "electrician_quote" && isset($quote_id)){
        $purchaseOrder->po_type_c = 'sanden_electrician';
        //get data Quote
        $quote = new AOS_Quotes();
        $quote->retrieve($quote_id);
        //get line group
        $db = DBManagerFactory::getInstance();
        $sql_grp = "    SELECT *
                        FROM aos_line_item_groups 
                        WHERE parent_include = '" . $quote->object_name . "' AND parent_id = '" . $quote->id . "' AND po_type = 'sanden_electrician' AND deleted = 0 
                        ORDER BY number ASC";
        $ret = $db->query($sql_grp);

        while ($grp = $db->fetchByAssoc($ret)) {
            $grp_old_id = '';
            $sql_line = '';
            if (isset($grp) && $grp != null) {
                $grp_old_id = $grp['id'];
                $group_quote = new AOS_Line_Item_Groups();

                $grp['id'] = "";
                $grp['assigned_user_id'] = $purchaseOrder->assigned_user_id;
                $grp['parent_id'] = $purchaseOrder->id;
                $grp['parent_type'] = 'PO_purchase_order';
                $grp['parent_include'] = '';
                $grp['po_type'] = '';
                $group_quote->populateFromRow($grp);
                $group_quote->save();
                //get line item of group_invoice
                $sql_line = "   SELECT *
                                FROM aos_products_quotes pg
                                WHERE group_id = '" . $grp_old_id . "' AND parent_include = '" . $quote->object_name . "' AND parent_id = '" . $quote->id . "' AND po_type = 'sanden_electrician' AND deleted = 0";
                $ret_line = $db->query($sql_line);
                $number_items = 1;
                // $total_price = 0;
                while ($line = $db->fetchByAssoc($ret_line)) {
                    $line['id'] = '';
                    $line['parent_id'] = $purchaseOrder->id;
                    $line['parent_type'] = 'PO_purchase_order';
                    //remove 
                    $line['parent_include'] = '';
                    $line['po_type'] = '';
                    //remove 
                    $line['assigned_user_id'] = $purchaseOrder->assigned_user_id;
                    $line['number'] = $number_items;
                    $line['group_id'] = $group_quote->id;
                    // $total_price += $line['product_cost_price'];
                    $prod_quote = new AOS_Products_Quotes();
                    $prod_quote->populateFromRow($line);
                    $prod_quote->save();
                    $number_items++;
                }

            }
        }
        
        $meeting_installer = createMettingForPOInstaller($invoice, $purchaseOrder, 'electrician');
        $invoice->meeting_plumber = $meeting_installer;
        $purchaseOrder->meeting_id = $meeting_installer;
        //populate fields price
        $purchaseOrder->total_amt           = $quote->electrician_total_amt;
        $purchaseOrder->discount_amount     = $quote->electrician_discount_amount;
        $purchaseOrder->subtotal_amount     = $quote->electrician_subtotal_amount;
        $purchaseOrder->shipping_amount     = $quote->electrician_shipping_amount;
        $purchaseOrder->shipping_tax_amt    = $quote->electrician_shipping_tax_amt;
        $purchaseOrder->tax_amount          = $quote->electrician_tax_amount;
        $purchaseOrder->total_amount        = $quote->electrician_total_amount;

        $purchaseOrder->installation_pdf_c = $purchase_installation;
        $purchaseOrder->save();
    }
    //VUT - E - Create Electrician from Quote


    if($is_daikin && $po_type == "plumber"){
        $purchaseOrder->po_type_c = 'installer';
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
            "Photo_Upload_Bonus",
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
        // $purchaseOrder->po_type_c = 'installer';
        $purchaseOrder->po_type_c = 'sanden_electrician';
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
            "Photo_Upload_Bonus",
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
            //if PO electrican and electrican = mike , $product['product_product_cost_price'] =215
            if($product['product_part_number'] == 'Sanden_Elec_Install_Std' && $invoice->contact_id_c == '3cde081c-117c-0e61-1081-5987d249b952'){
                $product['product_product_cost_price'] = '215.00';
            }

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

        $meeting_installer = createMettingForPOInstaller($invoice, $purchaseOrder, 'electrician');
        $invoice->meeting_electrician = $meeting_installer;
        $purchaseOrder->meeting_id = $meeting_installer;
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
        $inv_dispatch_date_str = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
        $string_dispatch_date = date("d M Y", strtotime($inv_dispatch_date_str));
        //Setting Group Line Items
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        $sanden_products = [
            'sanden_fqv_315' => '0',
            'sanden_fqs_315' => '0',
            'sanden_fqs_300' => '0',
            'sanden_fqs_250' => '0',
            'sanden_fqs_160' => '0',
            'QIK15_HPUMP' => '0',
            'QIK20_HPUMP' => '0',
        ];
        while ($row = $db->fetchByAssoc($result)) {
            if(strpos($row['part_number'], "GAUS-") !== false  || strpos($row['part_number'], "−HPUMP") !== false ){
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
                        $sanden_products['sanden_fqs_315'] = (int)$row['product_qty'];
                        break;
                    case 'GAUS-315FQV':
                        $part_numbers[] = "SAN-315 VE";
                        $sanden_products['sanden_fqv_315'] = (int)$row['product_qty'];
                        break;
                    case 'GAUS-250FQS':
                        $part_numbers[] = "SAN-250SAQA";
                        $sanden_products['sanden_fqs_250'] = (int)$row['product_qty'];
                        break;
                    case 'GAUS-160FQS':
                        $part_numbers[] = "SAN-160SAQA";
                        $sanden_products['sanden_fqs_160'] = (int)$row['product_qty'];
                        break;
                    case 'GAUS-300FQS':
                        $part_numbers[] = "SAN-300SAQA";
                        $sanden_products['sanden_fqs_300'] = (int)$row['product_qty'];
                        break;
                    default:
                        # code...
                        break;
                }
                $part_numbers_implode = implode("','", $part_numbers);
           
                $sql_pruduct = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numbers_implode."')";
                $return_product = $db->query($sql_pruduct);
        
                $products = array();
                // $name_tank ='';
                // $tank_array=[ "SAN-315SAQA", "SAN-315 VE", "SAN-250SAQA", "SAN-160SAQA", "SAN-300SAQA"];
                while ($row_pruduct = $db->fetchByAssoc($return_product))
                {
                    $row['id'] = '';
                    $row['parent_id'] = $purchaseOrder->id;
                    $row['name'] = $row_pruduct['name'];
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
                    // //VUT - S >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
                    // if (in_array($row_pruduct['part_number'], $tank_array)) {
                    //     $name_tank = (int)$row['product_qty'].'x'.preg_replace('/\s+/', '', $row_pruduct['part_number']);
                    // }
                    // //VUT - E >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
                    $prod_invoice = new AOS_Products_Quotes();
                    $prod_invoice->populateFromRow($row);
                    $prod_invoice->save();
                }

            }

            // get quantity with part number -HPUMP
            if (strpos($row['part_number'], "−HPUMP") !== false ) {
                switch ($row['part_number']) {
                    case 'QIK15−HPUMP':
                        $sanden_products['QIK15_HPUMP'] = (int)$row['product_qty'];
                        break;
                    case 'QIK20−HPUMP':
                        $sanden_products['QIK20_HPUMP'] = (int)$row['product_qty'];
                        break;
                    default:
                        break;
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

        //VUT - fix name $PO when create PO1-2-3 (delete GAUS- & -HPUMP)
        // //VUT - S >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
        // if ($name_tank != '') {
        //     $purchaseOrder->name .= ' '.$name_tank;
        // }
        // //VUT - E >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
        //VUT - S >> 
        $purchaseOrder->create_sanden_quote_fqs_c = json_encode($sanden_products);
        //VUT - E >> 
        $purchaseOrder->name = str_replace("GAUS-","",$purchaseOrder->name);
        $purchaseOrder->name = str_replace("−HPUMP","",$purchaseOrder->name);

        $purchaseOrder->name .= ' to ' .$purchaseOrder->shipping_address_city 
        . ' ' .$purchaseOrder->shipping_address_state .' '.$string_dispatch_date;
        
        $purchaseOrder->save();
        
    }
    
    if($po_type == "daikin"){
        $purchaseOrder->po_type_c = 'daikin_supply';
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
        $part_number_wifi = [];  //VUT << part_number wifi https://trello.com/c/XI0CzmPo/2888-daikin-alira-wifi-add-this-to-the-supply-po-requirement-when-ordering >>
        //Setting Group Line Items
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            if(strpos($row['name'], "Nexura") !== false || strpos($row['name'], "Daikin US7") !== false || strpos($row['name'], "Daikin Alira") !== false /**|| strpos($row['name'], "Daikin WiFi Controller") !== false*/ ){
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
            //VUT - Add wifi for daikin PO supply <<< https://trello.com/c/XI0CzmPo/2888-daikin-alira-wifi-add-this-to-the-supply-po-requirement-when-ordering
            if (strpos($row['name'], "Daikin US7") !== false) {
                array_push($part_number_wifi, "BRP072C42");
            } else if (strpos($row['name'], "Daikin Alira") !== false) {
                preg_match('/(\d+)/', $row['part_number'], $numberKW);
                if (intval($numberKW[0]) < 47) { //Daikin Alira 2-4.6kW	
                    array_push($part_number_wifi, "BRP072C42", "BRP067A42");
                } else { //Daikin Alira 5-7.1kW
                    array_push($part_number_wifi, "BRP072C42", "BRP980B42");
                }
            }
            //VUT - Add wifi for daikin PO supply >>> https://trello.com/c/XI0CzmPo/2888-daikin-alira-wifi-add-this-to-the-supply-po-requirement-when-ordering
        }

        //VUT - Add wifi for daikin PO supply <<< https://trello.com/c/XI0CzmPo/2888-daikin-alira-wifi-add-this-to-the-supply-po-requirement-when-ordering
        if (count($part_number_wifi) > 0) {
            $sql_partNumber = implode("','", $part_number_wifi);
            $sql_wifi = "SELECT * FROM aos_products WHERE part_number IN ('".$sql_partNumber."') AND deleted = 0";
            $res_wifi = $db->query($sql_wifi);
            $products_wifi = [];
            while ($row = $db->fetchByAssoc($res_wifi)) {
                $product_wifi = [];
                $product_wifi = array();
                $product_wifi['product_currency'] = $row['currency_id'];
                $product_wifi['product_item_description'] = $row['description'];
                $product_wifi['product_name'] = $row['name'];
                $product_wifi['product_part_number'] = $row['part_number'];
                $product_wifi['product_product_cost_price'] = $row['cost'];
                $product_wifi['product_product_id'] = $row['id'];
                $product_wifi['product_product_list_price'] = $row['price'];
                $products_wifi[$product_wifi['product_part_number']] = $product_wifi;
            }

        }
        //VUT - Add wifi for daikin PO supply >>> https://trello.com/c/XI0CzmPo/2888-daikin-alira-wifi-add-this-to-the-supply-po-requirement-when-ordering
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

        //VUT <<< merge product wifi
        if (count($products_wifi) > 0) {
            $products = array_merge($products,$products_wifi);
            $part_numners = array_merge($part_numners,$part_number_wifi);                ;
        }
        //VUT >>> merge product wifi

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
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND parent_id = '".$purchaseOrder->id."' AND deleted = 0";
        $result = $db->query($sql);
        $daikin_products = array();
        while ($row = $db->fetchByAssoc($result)) {
          $product_name = $row['name'];
          $partnumber = $row['part_number'];
          if (strpos($partnumber,'FTXM')!== false || strpos($partnumber,'FVXG')!== false || strpos($partnumber,'FTXZ')!== false || strpos($partnumber,'FTXJ')!== false ) {
            $product_name = trim(str_replace("Daikin", "",$product_name));
            array_push($daikin_products,$product_name);
          }
          if (strpos($partnumber,'BRP') !== false) {
            array_push($daikin_products, 'Wifi');
          }
        }
        $daikin_products = array_count_values($daikin_products);
        foreach ($daikin_products as $name => $quantity) {
            $purchaseOrder->name .= $quantity.'x'.$name.' ';
        }
                
        if (isset($_REQUEST["delivery_date"])) {
            $invoice->delivery_date_time_c = $_REQUEST["delivery_date"];
        }
        $purchaseOrder->delivery_date_c = explode(" ",$invoice->delivery_date_time_c)[0];
        $dateInfos = explode("/", explode(" ",$invoice->delivery_date_time_c)[0]);
        $inv_delivery_date_str = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
        $string_delivery_date = date("d M Y", strtotime($inv_delivery_date_str));


        $purchaseOrder->name .= ' to '.$purchaseOrder->shipping_address_city.' ' .$purchaseOrder->shipping_address_state .' '.$string_delivery_date;
        //VUT-E-Create subject for Daikin PO
        $purchaseOrder->save();
    }
    //VUT - Sunpower supply
    if ($po_type == "solar_supply") {
        $purchaseOrder->po_type_c = 'SPR_PV_Supply';
        $purchaseOrder->name = 'Sunpower';
        $group_total = 0;
        // save Group
        $row['id'] = '';
        $row['name'] = 'Sunpower';
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
        $inv_dispatch_date_str = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
        $string_dispatch_date = date("d M Y", strtotime($inv_dispatch_date_str));
        //Setting Group Line Items
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            if(strpos($row['part_number'], "SPR-") !== false ){
                $purchaseOrder->name .= ' '.(int)$row['product_qty'].'x' .$row['part_number'];
                // if(strpos($row['part_number'], "SPR-") !== false ){
                //     $row['number'] = '1';
                // }
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

   
        // $group_total += $total_price;

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

        //VUT - fix name $PO when create PO1-2-3 (delete GAUS- & -HPUMP)
        // //VUT - S >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
        // if ($name_tank != '') {
        //     $purchaseOrder->name .= ' '.$name_tank;
        // }
        // //VUT - E >> https://trello.com/c/q2DSrioE/3111-please-delete-the-marked-part-in-the-automatical-po-name-when-converting-from-quote-to-invoice
        $purchaseOrder->name = str_replace("SPR-","",$purchaseOrder->name);

        $purchaseOrder->name .= ' to ' .$purchaseOrder->shipping_address_city 
        . ' ' .$purchaseOrder->shipping_address_state .' '.$string_dispatch_date;

        $purchaseOrder->save();
        
    }
    // tuan code
    if($invoice_installation != ""){
        $purchase = new PO_purchase_order();
        $purchase->retrieve($purchaseOrder->id);
        if( empty($purchase->installation_pdf_c)){
            $purchase->installation_pdf_c =  create_guid();
            $purchase->save();
        }
        $installation_pdf = $purchase->installation_pdf_c;
        if($installation_pdf != ''){
            $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
            get_all_file_invoice_to_po($path,$invoice_installation,$installation_pdf);
        }
    }


    if($po_type == "plumber" || $po_type == "plumber_quote"){
        $invoice->plumber_po_c = $purchaseOrder->id;
    } elseif($po_type == "electrical" || $po_type == "electrician_quote"){
        $invoice->electrical_po_c = $purchaseOrder->id;
    } else {
        // todo daikin_po_c 
        $invoice->daikin_po_c = $purchaseOrder->id;
        if ($_REQUEST["button"] == 1) {
            $invoice->daikin_po_1_c = $purchaseOrder->id;
        } elseif ($_REQUEST["button"] == 2) {
            $invoice->daikin_po_2_c = $purchaseOrder->id;
        } elseif ($_REQUEST["button"] == 3) {
            $invoice->daikin_po_3_c = $purchaseOrder->id;
        }
    }
    $invoice->save();
    if ($_REQUEST["type"] != '') {
        if ($_REQUEST["type"] == 'plumber' || $_REQUEST["type"] == 'electrical') {
        $data_return = [
            'po_id' => $purchaseOrder->id,
            'meeting_id' => $meeting_installer,
            ];
          echo json_encode($data_return);
        } else {
            echo $purchaseOrder->id;
        }
    }

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
        $file_name = $value;

        $folderName_old  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$invoice_installation.'/'. $file_name ;
        $folderName_new  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$installation_pdf.'/';
      
        //check exists folder
        if(!file_exists ($folderName_new)) {
            mkdir($folderName_new);
        }
        copy($folderName_old, $folderName_new.$file_name);
        create_thumbnail($folderName_old,$file_name,$folderName_new);
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

/**
 * Create Meeting for PO Plumber/ELectrician Sanden
 * https://trello.com/c/JuKi7r4J/3116-purchase-orders-auto-generate-a-meeting-notice-for-sanden-plumber-and-sanden-electrician?menu=filter&filter=*
 */
function createMettingForPOInstaller($invoice, $purchaseOrder, $type='') {
    $meetings = new Meeting;
    $meetings->name = 'Meeting '.$type.' '.$purchaseOrder->name;
    $meetings->parent_type = "Accounts";
    $meetings->parent_id = $purchaseOrder->billing_account_id;
    $meetings->assigned_user_id = $invoice->assigned_user_id;
    $meetings->aos_invoices_id_c = $invoice->id;
    if ($type == 'plumber') {
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$purchaseOrder->install_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        // $meetings->date_start = $purchaseOrder->install_date.' 08:00:00';
    } else {
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$purchaseOrder->install_date.' 12:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        // $meetings->date_start = $purchaseOrder->install_date.' 12:00:00';
    } 
    if(empty($meetings->duration_hours)){
        $meetings->duration_hours  = 3;
        $meetings->duration_minutes  = 0;
    }
    $meetings->save();
    if ($type == 'plumber') {
        $invoice->meeting_plumber = $meetings->id;
    } else{
        $invoice->meeting_electrician = $meetings->id;
    } 

    $invoice->save();
    global $current_user;
    
    
    $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
    $meetings->saving_reminders_data = true;
    $reminderData = json_encode(
        $meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
    );
    Reminder::saveRemindersDataJson('Meetings', $meetings->id, $reminderData);
    $meetings->saving_reminders_data = false;
    
    
    $relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    
    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    }else if($current_user->id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
        $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    }else{
        $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    
        $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    }
    
    
    $relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
    
    if($meetings->update_vcal)
    {
        vCal::cache_sugar_vcal($user);
    }
    return $meetings->id;
}
