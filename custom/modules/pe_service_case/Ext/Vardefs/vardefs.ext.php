<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2020-05-25 10:58:38
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1"] = array (
  'name' => 'aos_invoices_pe_service_case_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_AOS_INVOICES_TITLE',
  'id_name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
);
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1_name"] = array (
  'name' => 'aos_invoices_pe_service_case_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_AOS_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
  'link' => 'aos_invoices_pe_service_case_1',
  'table' => 'aos_invoices',
  'module' => 'AOS_Invoices',
  'rname' => 'name',
);
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1aos_invoices_ida"] = array (
  'name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
);


// created: 2020-06-01 07:01:40
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1"] = array (
  'name' => 'leads_pe_service_case_1',
  'type' => 'link',
  'relationship' => 'leads_pe_service_case_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_pe_service_case_1leads_ida',
);
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1_name"] = array (
  'name' => 'leads_pe_service_case_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_pe_service_case_1leads_ida',
  'link' => 'leads_pe_service_case_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1leads_ida"] = array (
  'name' => 'leads_pe_service_case_1leads_ida',
  'type' => 'link',
  'relationship' => 'leads_pe_service_case_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
);


// created: 2020-04-21 07:51:29
$dictionary["pe_service_case"]["fields"]["pe_service_case_pe_message_servicecase_1"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'module' => 'pe_message_servicecase',
  'bean_name' => 'pe_message_servicecase',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
  'id_name' => 'pe_service7c1eicecase_idb',
);
$dictionary["pe_service_case"]["fields"]["pe_service_case_pe_message_servicecase_1_name"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
  'save' => true,
  'id_name' => 'pe_service7c1eicecase_idb',
  'link' => 'pe_service_case_pe_message_servicecase_1',
  'table' => 'pe_message_servicecase',
  'module' => 'pe_message_servicecase',
  'rname' => 'name',
);
$dictionary["pe_service_case"]["fields"]["pe_service7c1eicecase_idb"] = array (
  'name' => 'pe_service7c1eicecase_idb',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
);


 // created: 2020-02-05 19:43:27
$dictionary['pe_service_case']['fields']['account_id_c']['inline_edit']=1;

 

 // created: 2020-02-05 19:43:27
$dictionary['pe_service_case']['fields']['accounts_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['accounts_c']['labelValue']='Accounts';

 

 // created: 2020-02-05 20:02:58
$dictionary['pe_service_case']['fields']['address_city_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_city_c']['labelValue']='City';

 

 // created: 2020-02-05 20:05:11
$dictionary['pe_service_case']['fields']['address_country_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_country_c']['labelValue']='Country';

 

 // created: 2020-02-05 20:04:40
$dictionary['pe_service_case']['fields']['address_postalcode_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_postalcode_c']['labelValue']='Postal Code';

 

 // created: 2020-02-05 19:44:46
$dictionary['pe_service_case']['fields']['address_service_case_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_service_case_c']['labelValue']='Address';

 

 // created: 2020-02-05 20:03:47
$dictionary['pe_service_case']['fields']['address_state_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_state_c']['labelValue']='State';

 

 // created: 2020-02-05 20:01:43
$dictionary['pe_service_case']['fields']['address_street_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['address_street_c']['labelValue']='Address Street';

 

 // created: 2020-02-05 20:56:57
$dictionary['pe_service_case']['fields']['brief_description_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['brief_description_c']['labelValue']='Brief Description';

 

 // created: 2020-02-05 19:43:56
$dictionary['pe_service_case']['fields']['contact_id_c']['inline_edit']=1;

 

 // created: 2020-02-05 19:43:56
$dictionary['pe_service_case']['fields']['contacts_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['contacts_c']['labelValue']='Contacts';

 

 // created: 2020-02-05 20:56:38
$dictionary['pe_service_case']['fields']['detailed_desciption_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['detailed_desciption_c']['labelValue']='Detailed Desciption';

 

 // created: 2020-02-05 19:38:15
$dictionary['pe_service_case']['fields']['email_service_case_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['email_service_case_c']['labelValue']='Email';

 

 // created: 2020-06-15 07:05:46
$dictionary['pe_service_case']['fields']['error_content_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['error_content_c']['labelValue']='Error Content';

 

 // created: 2020-02-05 20:39:24
$dictionary['pe_service_case']['fields']['fault_type_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['fault_type_c']['labelValue']='Fault Type';

 

 // created: 2020-02-05 20:47:36
$dictionary['pe_service_case']['fields']['fault_type_other_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['fault_type_other_c']['labelValue']='Fault Type (Other)';

 

 // created: 2020-06-15 06:58:33
$dictionary['pe_service_case']['fields']['id_error_code_sanden_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['id_error_code_sanden_c']['labelValue']='ID Error Code Sanden';

 

 // created: 2020-04-21 07:55:30
$dictionary['pe_service_case']['fields']['id_message_servicecase_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['id_message_servicecase_c']['labelValue']='id message servicecase';

 

 // created: 2020-06-01 07:18:43
$dictionary['pe_service_case']['fields']['installation_photos_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['installation_photos_c']['labelValue']='Installation photos';

 

 // created: 2020-06-01 07:06:50
$dictionary['pe_service_case']['fields']['is_error_code_sanden_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['is_error_code_sanden_c']['labelValue']='Is error code sanden';

 

 // created: 2020-06-01 07:08:04
$dictionary['pe_service_case']['fields']['manufacturer_diagnostic_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['manufacturer_diagnostic_c']['labelValue']='Manufacturer Diagnostic Recommended Next Steps';

 

 // created: 2020-06-01 07:08:39
$dictionary['pe_service_case']['fields']['manufacturer_judgement_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['manufacturer_judgement_c']['labelValue']='Manufacturer Judgement and Repair Methods Recommended Next Steps';

 

 // created: 2020-02-05 21:00:01
$dictionary['pe_service_case']['fields']['message_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['message_c']['labelValue']='Message';

 

 // created: 2020-02-05 19:37:18
$dictionary['pe_service_case']['fields']['phone_number_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['phone_number_c']['labelValue']='Phone';

 

 // created: 2020-06-15 07:00:23
$dictionary['pe_service_case']['fields']['possible_solution_sanden_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['possible_solution_sanden_c']['labelValue']='Possible Solution';

 

 // created: 2020-04-14 03:13:44
$dictionary['pe_service_case']['fields']['quote_type_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['quote_type_c']['labelValue']='Product type';

 

 // created: 2020-04-22 06:59:00
$dictionary['pe_service_case']['fields']['sanden_equipment_type_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['sanden_equipment_type_c']['labelValue']='Sanden Equipment Type';

 

 // created: 2020-08-19 01:51:00
$dictionary['pe_service_case']['fields']['status']['inline_edit']=true;
$dictionary['pe_service_case']['fields']['status']['merge_filter']='disabled';

 

 // created: 2020-08-24 01:12:25
$dictionary['pe_service_case']['fields']['test_service_c']['inline_edit']='1';
$dictionary['pe_service_case']['fields']['test_service_c']['labelValue']='Test timeStamp';

 
?>