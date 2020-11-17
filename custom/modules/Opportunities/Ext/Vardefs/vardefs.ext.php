<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2017-05-02 16:55:21
$dictionary["Opportunity"]["fields"]["opportunities_aos_invoices_1"] = array (
  'name' => 'opportunities_aos_invoices_1',
  'type' => 'link',
  'relationship' => 'opportunities_aos_invoices_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE',
);


// created: 2017-05-09 13:31:10
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1"] = array (
  'name' => 'opportunities_opportunities_1',
  'type' => 'link',
  'relationship' => 'opportunities_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE',
  'id_name' => 'opportunities_opportunities_1opportunities_ida',
);
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1_name"] = array (
  'name' => 'opportunities_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE',
  'save' => true,
  'id_name' => 'opportunities_opportunities_1opportunities_ida',
  'link' => 'opportunities_opportunities_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1opportunities_ida"] = array (
  'name' => 'opportunities_opportunities_1opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_R_TITLE',
);


 // created: 2016-01-12 11:21:35
$dictionary['Opportunity']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Opportunity']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Opportunity']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Opportunity']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

 // created: 2018-08-20 02:55:10
$dictionary['Opportunity']['fields']['solar_monitoring_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['solar_monitoring_c']['labelValue']='Solar Monitoring';

 

 // created: 2019-06-26 12:45:02
$dictionary['Opportunity']['fields']['test_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['test_c']['labelValue']='test';

 
?>