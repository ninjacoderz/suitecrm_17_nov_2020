<?php
//get address warehouse 
$destination_warehouse_id = $_REQUEST['destination_warehouse_id'];
if($destination_warehouse_id !== '' && $destination_warehouse_id !== null){
    $pe_warehouse_log = new pe_warehouse();
    $pe_warehouse_log->retrieve($destination_warehouse_id);
    $result = array (
        'id' => $pe_warehouse_log->id,
        'billing_account' => $pe_warehouse_log->billing_account,
        'billing_account_id' => $pe_warehouse_log->billing_account_id,
        'billing_address_street' => $pe_warehouse_log->shipping_address_street,
        'billing_address_city' => $pe_warehouse_log->shipping_address_city,
        'billing_address_state' => $pe_warehouse_log->shipping_address_state,
        'billing_address_postalcode' => $pe_warehouse_log->shipping_address_postalcode,
        'billing_address_country' => $pe_warehouse_log->shipping_address_country,
    );
    echo json_encode($result);
    die();
}
//get address shipping
$sold_to_invoice_id = $_REQUEST['sold_to_invoice_id'];
if($sold_to_invoice_id !== '' && $sold_to_invoice_id !== null){
    $AOS_Invoices = new AOS_Invoices();
    $AOS_Invoices->retrieve($sold_to_invoice_id);
    $result = array (
        'id' => $AOS_Invoices->id,
        'shipping_account' => $AOS_Invoices->billing_account,
        'shipping_account_id' => $AOS_Invoices->billing_account_id,
        'shipping_address_street' => $AOS_Invoices->shipping_address_street,
        'shipping_address_city' => $AOS_Invoices->shipping_address_city,
        'shipping_address_state' => $AOS_Invoices->shipping_address_state,
        'shipping_address_postalcode' => $AOS_Invoices->shipping_address_postalcode,
        'shipping_address_country' => $AOS_Invoices->shipping_address_country,
    );
    echo json_encode($result);
    die();
}

//get Warehouse Owner
$billing_account_id =  $_REQUEST['billing_account_id'];
if($billing_account_id != '' && $billing_account_id !== null){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * FROM pe_warehouse WHERE billing_account_id = '$billing_account_id' AND deleted = 0" ;
    $resutlt = $db->query($sql);
    $warehouse = array();
    while($row = $db->fetchByAssoc($resutlt)){
        $warehouse[] = $row;
    }

    echo json_encode($warehouse[0]);
    die();
}

//get Warehouse Owner
$shipping_account_id =  $_REQUEST['shipping_account_id'];
if($shipping_account_id != '' && $shipping_account_id !== null){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * FROM aos_invoices WHERE billing_account_id = '$shipping_account_id' AND deleted = 0 LIMIT 0,1" ;
    $resutlt = $db->query($sql);
    $invoice = array();
    while($row = $db->fetchByAssoc($resutlt)){
        $invoice[] = $row;
    }

    echo json_encode($invoice[0]);
    die();
}

//get destination_warehouse_id_new_logic
$destination_warehouse_id_new_logic =  $_REQUEST['destination_warehouse_id_new_logic'];
if($destination_warehouse_id_new_logic !== '' && $destination_warehouse_id_new_logic !== null){
    $pe_warehouse_log = new pe_warehouse();
    $pe_warehouse_log->retrieve($destination_warehouse_id_new_logic);
    $result = array (
        'id' => $pe_warehouse_log->id,
        'destination_warehouse_owner_c' => $pe_warehouse_log->billing_account,
        'account_id_c' => $pe_warehouse_log->billing_account_id,
        'destination_address_street' => $pe_warehouse_log->billing_address_street,
        'destination_address_city' => $pe_warehouse_log->billing_address_city,
        'destination_address_state' => $pe_warehouse_log->billing_address_state,
        'destination_address_postalcode' => $pe_warehouse_log->billing_address_postalcode,
        'destination_address_country' => $pe_warehouse_log->billing_address_country,
    );
    echo json_encode($result);
    die();
}