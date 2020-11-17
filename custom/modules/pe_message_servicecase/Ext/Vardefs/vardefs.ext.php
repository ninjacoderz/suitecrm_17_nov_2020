<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2020-04-21 07:51:29
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'module' => 'pe_service_case',
  'bean_name' => 'pe_service_case',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'id_name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
);
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1_name"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'save' => true,
  'id_name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
  'link' => 'pe_service_case_pe_message_servicecase_1',
  'table' => 'pe_service_case',
  'module' => 'pe_service_case',
  'rname' => 'name',
);
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1pe_service_case_ida"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
);


 // created: 2020-04-21 08:47:43
$dictionary['pe_message_servicecase']['fields']['message']['inline_edit']=true;
$dictionary['pe_message_servicecase']['fields']['message']['comments']='Message content';
$dictionary['pe_message_servicecase']['fields']['message']['merge_filter']='disabled';

 

 // created: 2020-06-15 07:08:36
$dictionary['pe_message_servicecase']['fields']['quote_type_c']['inline_edit']='1';
$dictionary['pe_message_servicecase']['fields']['quote_type_c']['labelValue']='Product type';

 

 // created: 2020-06-15 07:09:34
$dictionary['pe_message_servicecase']['fields']['sanden_equipment_type_c']['inline_edit']='1';
$dictionary['pe_message_servicecase']['fields']['sanden_equipment_type_c']['labelValue']='Sanden Equipment Type';

 
?>