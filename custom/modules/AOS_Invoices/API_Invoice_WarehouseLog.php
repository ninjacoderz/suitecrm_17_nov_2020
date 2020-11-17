<?php
//$invoiceID =  "2e3b54f3-0f56-01b4-2fa3-5dbf6cac5285";
// $warehouseID = "6012b50a-3c12-e82f-109c-5dd35051f386";
$invoiceID = $_REQUEST['invoiceID'];

if ($invoiceID != "") {
    $db = DBManagerFactory::getInstance();
    $query = "SELECT id 
                FROM pe_warehouse_log
                WHERE sold_to_invoice_id='".$invoiceID."' 
                AND deleted=0";

    $res = $db->query($query);

    $warehouseID = $db->fetchByAssoc($res);

    echo $warehouseID['id'];
}

$module = $_POST["module"];
$type = $_GET['type'];

if ($module == "AOS_Invoices" && $type == "invoice_checklist") {
    $module_id = $_POST["module_id"];
    $data_checklist = $_POST["data_checklist"];
    $bean = new AOS_Invoices();
    $bean->retrieve($module_id);
    if (!$bean) {
        sugar_die("Invalid Record");
    } else {
        $bean->data_checklist_invoice_c = $data_checklist;
        $bean->save();
    }
    die();
}
