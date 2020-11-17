<?php 
if ( isset($_GET['type_module_delivery']) &&  $_GET['type_module_delivery'] == "PO_purchase_order" ) {
    $contact = new Contact();
    $contact->retrieve($_GET["receiver_id"]);
    $return_array = array();
    $return_array['supplier_email'] = $contact->email1;
    print(json_encode($return_array));
}else if($record = $_GET["record_id"]){
     
    $bean = BeanFactory::getBean("AOS_Invoices", $record);
    $return_array = array();
    $return_array['billing_account_id'] =   $bean->billing_account_id;
    $return_array['billing_account'] =   $bean->billing_account;
    
    $return_array['plumber_account_id'] =   $bean->account_id1_c;
    $return_array['plumber_account'] =   $bean->plumber_c;


    $return_array['install_address'] = $bean->install_address_c;
    $return_array['install_address_city']= $bean->install_address_city_c;
    $return_array['install_address_postalcode'] = $bean->install_address_postalcode_c;
    $return_array['install_address_state'] = $bean->install_address_state_c;

    $return_array['shipping_address_street'] = $bean->shipping_address_street;
    $return_array['shipping_address_city']= $bean->shipping_address_city;
    $return_array['shipping_address_state'] = $bean->shipping_address_state;
    $return_array['shipping_address_postalcode'] = $bean->shipping_address_postalcode;

    // Supplier address 
    $supplier =  BeanFactory::getBean("Accounts", $bean->account_id1_c);
    if($supplier->id){
        $return_array['supplier_address'] = $supplier->billing_address_street;
        $return_array['supplier_address_city']= $supplier->billing_address_city;
        $return_array['supplier_address_postalcode'] = $supplier->billing_address_postalcode;
        $return_array['supplierl_address_state'] = $supplier->billing_address_state;
    }

    //

    $db = DBManagerFactory::getInstance();
    $quote_id = $db->getOne("SELECT id FROM aos_quotes WHERE number = '".$bean->quote_number."'");
    $supplier =  BeanFactory::getBean("AOS_Quotes", $quote_id);

    $return_array['quote_name'] = $supplier->name;
    $return_array['quote_id'] = $quote_id;

    $return_array['invoice_name'] = $bean->name;
    
    // Group
    $sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND deleted = 0";
  	$result = $db->query($sql);
	$invoiceGroupIds = array();
	while ($row = $db->fetchByAssoc($result)) {
        $invoiceGroupIds[$row['id']] = $row['name'];
	}
    // Line Items
    //Setting Line Items
	$sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND deleted = 0";
    $result = $db->query($sql);
    $line_items = array();
    while ($row = $db->fetchByAssoc($result)) {
        $item = array();
        $item['product_currency'] = $row['currency_id'];
        $item['product_item_description'] = $row['item_description'];
        $item['product_name'] = $row['name'];
        $item['product_part_number'] = $row['part_number'];
        $item['product_product_cost_price'] = $row['product_cost_price'];
        $item['product_product_id'] = $row['product_id'];
        $item['product_product_list_price'] = $row['product_cost_price'];
        $item['product_qty'] = $row['product_qty'];
        $line_items[$invoiceGroupIds[$row['group_id']]][] = $item;
    }
    $return_array['line_items'] = $line_items;
    /*$part_numners_implode = implode("','", $part_numners);
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
        $product['product_product_oduct_id'] = $row['id'];
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
    }*/
    print(json_encode($return_array));
}
die();
?>