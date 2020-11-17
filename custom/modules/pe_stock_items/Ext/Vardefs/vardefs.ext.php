<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2018-12-05 18:20:56
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_pe_stock_items_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse',
  'bean_name' => 'pe_warehouse',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
  'id_name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
);
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1_name"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
  'link' => 'pe_warehouse_pe_stock_items_1',
  'table' => 'pe_warehouse',
  'module' => 'pe_warehouse',
  'rname' => 'name',
);
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1pe_warehouse_ida"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_pe_stock_items_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_STOCK_ITEMS_TITLE',
);


 // created: 2018-12-05 14:17:27
$dictionary['pe_stock_items']['fields']['aos_invoices_id_c']['inline_edit']=1;

 

 // created: 2018-10-18 17:55:41
$dictionary['pe_stock_items']['fields']['discount']['default']='';
$dictionary['pe_stock_items']['fields']['discount']['len']=100;
$dictionary['pe_stock_items']['fields']['discount']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['discount']['options']='product_type_dom';
$dictionary['pe_stock_items']['fields']['discount']['merge_filter']='disabled';

 

 // created: 2018-12-05 14:17:27
$dictionary['pe_stock_items']['fields']['invoice_c']['inline_edit']='1';
$dictionary['pe_stock_items']['fields']['invoice_c']['labelValue']='Invoice';

 

 // created: 2018-10-25 22:51:36
$dictionary['pe_stock_items']['fields']['parent_id']['required']=false;
$dictionary['pe_stock_items']['fields']['parent_id']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['parent_id']['merge_filter']='disabled';
$dictionary['pe_stock_items']['fields']['parent_id']['reportable']=true;
$dictionary['pe_stock_items']['fields']['parent_id']['len']=36;

 
 
 // created: 2018-10-25 05:30:01
$dictionary['pe_stock_items']['fields']['parent_name']['required']=true;
$dictionary['pe_stock_items']['fields']['parent_name']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['parent_name']['options']= array('pe_warehouse_log' => 'Warehouse Log');
$dictionary['pe_stock_items']['fields']['parent_name']['merge_filter']='disabled';
$dictionary['pe_stock_items']['fields']['parent_name']['reportable']=true;

 

 // created: 2018-10-25 22:51:36
$dictionary['pe_stock_items']['fields']['parent_type']['required']=false;
$dictionary['pe_stock_items']['fields']['parent_type']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['parent_type']['merge_filter']='disabled';
$dictionary['pe_stock_items']['fields']['parent_type']['reportable']=true;
$dictionary['pe_stock_items']['fields']['parent_type']['len']=255;

 

 // created: 2018-10-29 13:51:08
$dictionary['pe_stock_items']['fields']['pe_warehouse_id_c']['inline_edit']=1;

 

 // created: 2018-10-18 16:12:52
$dictionary['pe_stock_items']['fields']['serial_number']['required']=false;
$dictionary['pe_stock_items']['fields']['serial_number']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['serial_number']['merge_filter']='disabled';

 

 // created: 2018-10-18 16:00:18
$dictionary['pe_stock_items']['fields']['shipped']['required']=false;
$dictionary['pe_stock_items']['fields']['shipped']['inline_edit']=true;
$dictionary['pe_stock_items']['fields']['shipped']['merge_filter']='disabled';

 

 // created: 2018-10-29 13:51:08
$dictionary['pe_stock_items']['fields']['warehouse_c']['inline_edit']='1';
$dictionary['pe_stock_items']['fields']['warehouse_c']['labelValue']='Warehouse';

 
?>