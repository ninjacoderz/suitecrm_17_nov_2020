<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2019-05-02 11:28:19
$dictionary['Email']['fields']['schedule_timestamp_c']['inline_edit']='1';
$dictionary['Email']['fields']['schedule_timestamp_c']['labelValue']='Schedule Timestamp';

 

 // created: 2019-04-26 21:12:42
$dictionary['Email']['fields']['status']['merge_filter']='disabled';

 

// created: 2020-10-12 03:59:07
$dictionary["Email"]["fields"]["po_purchase_order_emails_1"] = array (
  'name' => 'po_purchase_order_emails_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_emails_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_EMAILS_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_emails_1po_purchase_order_ida',
);
$dictionary["Email"]["fields"]["po_purchase_order_emails_1_name"] = array (
  'name' => 'po_purchase_order_emails_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_EMAILS_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_emails_1po_purchase_order_ida',
  'link' => 'po_purchase_order_emails_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["Email"]["fields"]["po_purchase_order_emails_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_emails_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_emails_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_EMAILS_1_FROM_EMAILS_TITLE',
);


// created: 2020-10-12 04:21:01
$dictionary["Email"]["fields"]["emails_po_purchase_order_1"] = array (
  'name' => 'emails_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'emails_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_EMAILS_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
);


 // created: 2020-11-11 02:33:52
$dictionary['Email']['fields']['number_receive_sms']['default']='matthew_paul_client';
$dictionary['Email']['fields']['number_receive_sms']['merge_filter']='disabled';

 
?>