<?php
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