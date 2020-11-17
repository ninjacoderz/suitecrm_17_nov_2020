<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2019-05-24 12:28:45
$dictionary["pe_internal_note"]["fields"]["aos_invoices_pe_internal_note_1"] = array (
  'name' => 'aos_invoices_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_PE_INTERNAL_NOTE_1_FROM_AOS_INVOICES_TITLE',
);


// created: 2019-05-24 12:31:00
$dictionary["pe_internal_note"]["fields"]["aos_quotes_pe_internal_note_1"] = array (
  'name' => 'aos_quotes_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'aos_quotes_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'AOS_Quotes',
  'bean_name' => 'AOS_Quotes',
  'vname' => 'LBL_AOS_QUOTES_PE_INTERNAL_NOTE_1_FROM_AOS_QUOTES_TITLE',
);


// created: 2019-06-18 17:50:10
$dictionary["pe_internal_note"]["fields"]["leads_pe_internal_note_1"] = array (
  'name' => 'leads_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'leads_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_PE_INTERNAL_NOTE_1_FROM_LEADS_TITLE',
);


// created: 2019-10-17 15:24:12
$dictionary["pe_internal_note"]["fields"]["pe_warehouse_log_pe_internal_note_1"] = array (
  'name' => 'pe_warehouse_log_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse_log',
  'bean_name' => 'pe_warehouse_log',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_INTERNAL_NOTE_1_FROM_PE_WAREHOUSE_LOG_TITLE',
);


 // created: 2019-05-27 19:26:21
$dictionary['pe_internal_note']['fields']['email_c']['inline_edit']='1';
$dictionary['pe_internal_note']['fields']['email_c']['labelValue']='Email';

 

 // created: 2019-05-27 19:26:21
$dictionary['pe_internal_note']['fields']['email_id_c']['inline_edit']=1;

 

 // created: 2019-05-24 19:10:01
$dictionary['pe_internal_note']['fields']['name']['required']=false;
$dictionary['pe_internal_note']['fields']['name']['inline_edit']=true;
$dictionary['pe_internal_note']['fields']['name']['duplicate_merge']='disabled';
$dictionary['pe_internal_note']['fields']['name']['duplicate_merge_dom_value']='0';
$dictionary['pe_internal_note']['fields']['name']['merge_filter']='disabled';
$dictionary['pe_internal_note']['fields']['name']['unified_search']=false;

 

 // created: 2019-05-17 12:36:59
$dictionary['pe_internal_note']['fields']['type_inter_note_c']['inline_edit']='1';
$dictionary['pe_internal_note']['fields']['type_inter_note_c']['labelValue']='Type ';

 

// created: 2020-09-28 07:09:11
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
);
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1_name"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
  'link' => 'po_purchase_order_pe_internal_note_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_internal_note_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);


// created: 2020-07-23 04:06:58
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1"] = array (
  'name' => 'calls_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'calls_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_pe_internal_note_1calls_ida',
);
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1_name"] = array (
  'name' => 'calls_pe_internal_note_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_pe_internal_note_1calls_ida',
  'link' => 'calls_pe_internal_note_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1calls_ida"] = array (
  'name' => 'calls_pe_internal_note_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_pe_internal_note_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);


 // created: 2020-01-22 16:04:58
$dictionary['pe_internal_note']['fields']['type_inter_note_c']['inline_edit']='1';
$dictionary['pe_internal_note']['fields']['type_inter_note_c']['labelValue']='Type';

 
?>