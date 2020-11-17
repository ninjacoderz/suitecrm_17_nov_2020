<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2019-10-17 15:24:12
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_internal_note_1"] = array (
  'name' => 'pe_warehouse_log_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'pe_internal_note',
  'bean_name' => 'pe_internal_note',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);


// created: 2018-10-08 20:23:14
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse_log_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse_log',
  'bean_name' => 'pe_warehouse_log',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE',
  'id_name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse_log_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse_log',
  'bean_name' => 'pe_warehouse_log',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_R_TITLE',
  'id_name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1_name"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_R_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
  'link' => 'pe_warehouse_log_pe_warehouse_log_1',
  'table' => 'pe_warehouse_log',
  'module' => 'pe_warehouse_log',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1_name"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
  'link' => 'pe_warehouse_log_pe_warehouse_log_1',
  'table' => 'pe_warehouse_log',
  'module' => 'pe_warehouse_log',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse_log_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_R_TITLE',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse_log_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE',
);


// created: 2018-07-19 08:35:42
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse',
  'source' => 'non-db',
  'module' => 'pe_warehouse',
  'bean_name' => 'pe_warehouse',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE',
  'id_name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_name"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
  'link' => 'pe_warehouse_log_pe_warehouse',
  'table' => 'pe_warehouse',
  'module' => 'pe_warehouse',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehousepe_warehouse_ida"] = array (
  'name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE',
);


// created: 2018-12-05 20:14:27
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_warehouse_log_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1_name"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
  'link' => 'po_purchase_order_pe_warehouse_log_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_warehouse_log_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE',
);


 // created: 2018-12-03 14:18:38
$dictionary['pe_warehouse_log']['fields']['account_id_c']['inline_edit']=1;

 

 // created: 2018-12-03 19:30:02
$dictionary['pe_warehouse_log']['fields']['billing_address_street']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['billing_address_street']['comments']='The street address used for billing address';
$dictionary['pe_warehouse_log']['fields']['billing_address_street']['merge_filter']='disabled';

 

 // created: 2018-12-05 19:23:56
$dictionary['pe_warehouse_log']['fields']['connote']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['connote']['comments']='The city used for billing address';
$dictionary['pe_warehouse_log']['fields']['connote']['merge_filter']='disabled';

 

 // created: 2018-12-03 14:18:38
$dictionary['pe_warehouse_log']['fields']['destination_warehouse_owner_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['destination_warehouse_owner_c']['labelValue']='Destination Warehouse Owner';

 

 // created: 2018-10-26 13:37:48
$dictionary['pe_warehouse_log']['fields']['estimate_ship_date']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['estimate_ship_date']['merge_filter']='disabled';

 

 // created: 2019-03-22 16:04:32
$dictionary['pe_warehouse_log']['fields']['file_rename_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['file_rename_c']['labelValue']='File Rename';

 

 // created: 2018-10-03 02:32:03
$dictionary['pe_warehouse_log']['fields']['installation_pdf_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['installation_pdf_c']['labelValue']='Delivery Docket PDF';

 

 // created: 2018-08-07 03:03:01
$dictionary['pe_warehouse_log']['fields']['status_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['status_c']['labelValue']='Status';

 

 // created: 2018-12-05 19:25:26
$dictionary['pe_warehouse_log']['fields']['warehouse_order_number']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['warehouse_order_number']['comments']='The city used for billing address';
$dictionary['pe_warehouse_log']['fields']['warehouse_order_number']['merge_filter']='disabled';

 

 // created: 2018-10-16 15:20:21
$dictionary['pe_warehouse_log']['fields']['whlog_status']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['whlog_status']['merge_filter']='disabled';

 

 // created: 2019-06-25 17:45:31
$dictionary['pe_warehouse_log']['fields']['actual_ship_date']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['actual_ship_date']['merge_filter']='disabled';

 

 // created: 2019-11-06 18:27:31
$dictionary['pe_warehouse_log']['fields']['arrival_date_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['arrival_date_c']['labelValue']='Arrival Date';

 

 // created: 2019-10-28 12:31:08
$dictionary['pe_warehouse_log']['fields']['carrier']['inline_edit']=true;
$dictionary['pe_warehouse_log']['fields']['carrier']['comments']='The city used for billing address';
$dictionary['pe_warehouse_log']['fields']['carrier']['merge_filter']='disabled';

 

 // created: 2019-11-06 18:24:31
$dictionary['pe_warehouse_log']['fields']['dispatch_ship_date_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['dispatch_ship_date_c']['labelValue']='Dispatch Date';

 

 // created: 2019-11-05 19:45:33
$dictionary['pe_warehouse_log']['fields']['meeting_arrival_date_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['meeting_arrival_date_c']['labelValue']='Meeting Arrival';

 

 // created: 2019-11-05 19:45:02
$dictionary['pe_warehouse_log']['fields']['meeting_dispatch_date_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['meeting_dispatch_date_c']['labelValue']='Meeting Dispatch';

 

 // created: 2019-07-02 17:12:05
$dictionary['pe_warehouse_log']['fields']['pe_purchase_order_no_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['pe_purchase_order_no_c']['labelValue']='PE Purchase Order Number';

 

 // created: 2019-11-19 13:53:21
$dictionary['pe_warehouse_log']['fields']['shipping_product_type_c']['inline_edit']='1';
$dictionary['pe_warehouse_log']['fields']['shipping_product_type_c']['labelValue']='Shipping Product Type';

 
?>