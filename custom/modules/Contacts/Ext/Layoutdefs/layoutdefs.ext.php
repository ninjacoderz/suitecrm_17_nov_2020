<?php 
 //WARNING: The contents of this file are auto-generated


 //custom get call from contact
$layout_defs["Contacts"]["subpanel_setup"]['subpanel_custom_call_contacts'] = 
  array ('order' => 100,
    'module' => 'Calls',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_call_for_contacts',
    'generate_select' => true,
    'title_key' => 'Calls',
    'sort_order' => 'desc',
    'sort_by' => 'date_modified',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Contacts/getServiceCaseForContacts_Subpanel.php',
        'contact_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
);

 // created: 2018-07-04 09:14:55
$layout_defs["Contacts"]["subpanel_setup"]['pe_smsmanager_contacts'] = array (
  'order' => 100,
  'module' => 'pe_smsmanager',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_SMSMANAGER_CONTACTS_FROM_PE_SMSMANAGER_TITLE',
  'get_subpanel_data' => 'pe_smsmanager_contacts',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 //VUT- Create subpanel "SERVICE CASE" in Contacts >> 2020/06/09
$layout_defs["Contacts"]["subpanel_setup"]['subpanel_pe_servicecase_contacts'] = 
  array ('order' => 100,
    'module' => 'pe_service_case',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_servicecase_for_contacts',
    'generate_select' => true,
    'title_key' => 'SERVICE CASE',
    'sort_order' => 'desc',
    'sort_by' => 'date_modified',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Contacts/getServiceCaseForContacts_Subpanel.php',
        'contact_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
);

//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['pe_smsmanager_contacts']['override_subpanel_name'] = 'Contact_subpanel_pe_smsmanager_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contact_aos_quotes']['override_subpanel_name'] = 'Contact_subpanel_contact_aos_quotes';

?>