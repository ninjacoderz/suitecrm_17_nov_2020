<?php

if (!(ACLController::checkAccess('AOS_Invoices', 'edit', true))) {
    ACLController::displayNoAccess();
    die;
}

global $timedate;
//Setting values in PO
$PO_purchase_order = BeanFactory::newBean('PO_purchase_order');
$PO_purchase_order->retrieve($_REQUEST['record']);
$PO_purchase_order->total_amt = format_number($PO_purchase_order->total_amt);
$PO_purchase_order->discount_amount = format_number($PO_purchase_order->discount_amount);
$PO_purchase_order->subtotal_amount = format_number($PO_purchase_order->subtotal_amount);
$PO_purchase_order->tax_amount = format_number($PO_purchase_order->tax_amount);
if ($PO_purchase_order->shipping_amount != null) {
    $PO_purchase_order->shipping_amount = format_number($PO_purchase_order->shipping_amount);
}
$PO_purchase_order->total_amount = format_number($PO_purchase_order->total_amount);
$PO_purchase_order->save();

$Bill = BeanFactory::newBean('pe_bills');
// $Bill->id = create_guid();
$Bill->save();
$array_field_ignore = ["id",'number'];
if ($PO_purchase_order->id != "") {
    foreach ($Bill->field_name_map as $key => $value) {
        if (!in_array($key, $array_field_ignore)) {
            $Bill->$key = $PO_purchase_order->$key;
        }
    }
}


//Setting Group Line Items
$db = DBManagerFactory::getInstance();
$sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'PO_purchase_order' AND parent_id = '" . $PO_purchase_order->id . "' AND deleted = 0";
$result = $db->query($sql);
$GroupLineItems = array();
while ($row = $db->fetchByAssoc($result)) {
    $GroupLineItemsID = $row['id'];
    $row['id'] = '';
    $row['parent_id'] = $Bill->id;
    $row['parent_type'] = 'pe_bills';
    if ($row['total_amt'] != null) {
        $row['total_amt'] = format_number($row['total_amt']);
    }
    if ($row['discount_amount'] != null) {
        $row['discount_amount'] = format_number($row['discount_amount']);
    }
    if ($row['subtotal_amount'] != null) {
        $row['subtotal_amount'] = format_number($row['subtotal_amount']);
    }
    if ($row['tax_amount'] != null) {
        $row['tax_amount'] = format_number($row['tax_amount']);
    }
    if ($row['subtotal_tax_amount'] != null) {
        $row['subtotal_tax_amount'] = format_number($row['subtotal_tax_amount']);
    }
    if ($row['total_amount'] != null) {
        $row['total_amount'] = format_number($row['total_amount']);
    }
    $Group_Line_Item_Group = BeanFactory::newBean('AOS_Line_Item_Groups');
    $Group_Line_Item_Group->populateFromRow($row);
    $Group_Line_Item_Group->save();
    $GroupLineItems[$GroupLineItemsID] = $Group_Line_Item_Group->id;
}

//Setting Line Items
$sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND parent_id = '" . $PO_purchase_order->id . "' AND deleted = 0";
$result = $db->query($sql);
while ($row = $db->fetchByAssoc($result)) {
    $row['id'] = '';
    $row['parent_id'] = $Bill->id;
    $row['parent_type'] = 'pe_bills';
    $row['group_id'] = $GroupLineItems[$row['group_id']];
    if ($row['product_cost_price'] != null) {
        $row['product_cost_price'] = format_number($row['product_cost_price']);
    }
    $row['product_list_price'] = format_number($row['product_list_price']);
    if ($row['product_discount'] != null) {
        $row['product_discount'] = format_number($row['product_discount']);
        $row['product_discount_amount'] = format_number($row['product_discount_amount']);
    }
    $row['product_unit_price'] = format_number($row['product_unit_price']);
    $row['vat_amt'] = format_number($row['vat_amt']);
    $row['product_total_price'] = format_number($row['product_total_price']);
    $row['product_qty'] = format_number($row['product_qty']);
    $AOS_Products_Quotes = BeanFactory::newBean('AOS_Products_Quotes');
    $AOS_Products_Quotes->populateFromRow($row);
    $AOS_Products_Quotes->save();
}

$Bill->save();
header('Location: index.php?module=pe_bills&action=EditView&record=' . $Bill->id);
return true;


