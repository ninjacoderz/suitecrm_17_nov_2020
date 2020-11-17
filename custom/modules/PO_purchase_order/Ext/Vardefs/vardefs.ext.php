<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2020-10-12 03:59:07
$dictionary["PO_purchase_order"]["fields"]["po_purchase_order_emails_1"] = array (
  'name' => 'po_purchase_order_emails_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_emails_1',
  'source' => 'non-db',
  'module' => 'Emails',
  'bean_name' => 'Email',
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_EMAILS_1_FROM_EMAILS_TITLE',
);


// created: 2020-09-08 10:07:50
$dictionary["PO_purchase_order"]["fields"]["po_purchase_order_aos_products_quotes_1"] = array (
  'name' => 'po_purchase_order_aos_products_quotes_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_aos_products_quotes_1',
  'source' => 'non-db',
  'module' => 'AOS_Products_Quotes',
  'bean_name' => 'AOS_Products_Quotes',
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_AOS_PRODUCTS_QUOTES_1_FROM_AOS_PRODUCTS_QUOTES_TITLE',
);


// created: 2017-12-27 14:27:35
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1"] = array (
  'name' => 'aos_invoices_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE',
  'id_name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
);
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1_name"] = array (
  'name' => 'aos_invoices_po_purchase_order_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
  'link' => 'aos_invoices_po_purchase_order_1',
  'table' => 'aos_invoices',
  'module' => 'AOS_Invoices',
  'rname' => 'name',
);
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1aos_invoices_ida"] = array (
  'name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
  'type' => 'link',
  'relationship' => 'aos_invoices_po_purchase_order_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
);


// created: 2017-12-25 05:36:06
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1"] = array (
  'name' => 'aos_quotes_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'aos_quotes_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'AOS_Quotes',
  'bean_name' => 'AOS_Quotes',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE',
  'id_name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
);
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1_name"] = array (
  'name' => 'aos_quotes_po_purchase_order_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE',
  'save' => true,
  'id_name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
  'link' => 'aos_quotes_po_purchase_order_1',
  'table' => 'aos_quotes',
  'module' => 'AOS_Quotes',
  'rname' => 'name',
);
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1aos_quotes_ida"] = array (
  'name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
  'type' => 'link',
  'relationship' => 'aos_quotes_po_purchase_order_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
);


// created: 2018-12-05 20:14:27
$dictionary["PO_purchase_order"]["fields"]["po_purchase_order_pe_warehouse_log_1"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_warehouse_log_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse_log',
  'bean_name' => 'pe_warehouse_log',
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE',
);


 // created: 2019-06-13 19:31:06
$dictionary['PO_purchase_order']['fields']['installation_pdf_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['installation_pdf_c']['labelValue']='PDF PARSE';

 

 // created: 2019-02-11 12:12:16
$dictionary['PO_purchase_order']['fields']['seek_install_time_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['seek_install_time_c']['labelValue']='Seek Install Time :';

 

 // created: 2018-08-13 06:44:31
$dictionary['PO_purchase_order']['fields']['status_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['status_c']['labelValue']='Status';

 

 // created: 2018-10-10 19:16:51
$dictionary['PO_purchase_order']['fields']['supplier_order_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['supplier_order_c']['labelValue']='Supplier Order';

 

// created: 2020-09-28 07:09:11
$dictionary["PO_purchase_order"]["fields"]["po_purchase_order_pe_internal_note_1"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'pe_internal_note',
  'bean_name' => 'pe_internal_note',
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);


// created: 2020-10-12 04:21:01
$dictionary["PO_purchase_order"]["fields"]["emails_po_purchase_order_1"] = array (
  'name' => 'emails_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'emails_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'Emails',
  'bean_name' => 'Email',
  'vname' => 'LBL_EMAILS_PO_PURCHASE_ORDER_1_FROM_EMAILS_TITLE',
);


 // created: 2020-09-03 08:54:52
$dictionary['PO_purchase_order']['fields']['install_date']['inline_edit']=true;
$dictionary['PO_purchase_order']['fields']['install_date']['options']='date_range_search_dom';
$dictionary['PO_purchase_order']['fields']['install_date']['merge_filter']='disabled';

 

 // created: 2020-10-09 07:32:41
$dictionary['PO_purchase_order']['fields']['contact_id_c']['inline_edit']=1;

 

 // created: 2020-10-09 07:35:31
$dictionary['PO_purchase_order']['fields']['receiver_contact_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['receiver_contact_c']['labelValue']='Receiver Contact';

 

 // created: 2020-08-07 09:09:28
$dictionary['PO_purchase_order']['fields']['supplier_order_number_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['supplier_order_number_c']['labelValue']='supplier order number';

 

 // created: 2020-10-08 01:59:47
$dictionary['PO_purchase_order']['fields']['local_freight_company_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['local_freight_company_c']['labelValue']='Local Freight Company';

 
 
// created: 2019-11-05 thienpb
$dictionary["PO_purchase_order"]["fields"]["billing_account"]["rname"]="name";
$dictionary["PO_purchase_order"]["fields"]["shipping_account"]["rname"]="name";

 // created: 2020-09-28 07:24:45
$dictionary['PO_purchase_order']['fields']['status_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['status_c']['labelValue']='Status';

 

 // created: 2020-08-28 04:48:00
$dictionary['PO_purchase_order']['fields']['freight_company_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['freight_company_c']['labelValue']='Freight Company';

 

 // created: 2020-08-07 07:40:58
$dictionary['PO_purchase_order']['fields']['po_type_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['po_type_c']['labelValue']='PO Type';

 

 // created: 2020-08-25 02:59:38
$dictionary['PO_purchase_order']['fields']['delivery_date_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['delivery_date_c']['labelValue']='Delivery Date';

 

 // created: 2020-08-25 03:05:07
$dictionary['PO_purchase_order']['fields']['dispatch_date_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['dispatch_date_c']['labelValue']='Dispatch Date';

 

 // created: 2019-12-11 14:39:58
$dictionary['PO_purchase_order']['fields']['bill_status_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['bill_status_c']['labelValue']='Bill Status:';

 

 // created: 2020-05-11 03:38:51
$dictionary['PO_purchase_order']['fields']['xero_po_id_c']['inline_edit']='1';
$dictionary['PO_purchase_order']['fields']['xero_po_id_c']['labelValue']='Xero PO';

 

 // created: 2020-10-27 01:47:01
$dictionary['PO_purchase_order']['fields']['shipping_address_state']['inline_edit']=true;
$dictionary['PO_purchase_order']['fields']['shipping_address_state']['comments']='The state used for the shipping address';
$dictionary['PO_purchase_order']['fields']['shipping_address_state']['merge_filter']='disabled';

 
?>