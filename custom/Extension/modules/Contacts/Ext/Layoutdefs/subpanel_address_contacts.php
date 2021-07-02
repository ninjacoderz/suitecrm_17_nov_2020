<?php
 //VUT- Create subpanel "ADDRESS" in Contacts 
$layout_defs["Contacts"]["subpanel_setup"]['subpanel_address_contacts'] = 
  array ('order' => 101,
    'module' => 'pe_address',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_address_for_contacts',
    'generate_select' => true,
    'title_key' => 'ADDRESS',
    'sort_order' => 'desc',
    'sort_by' => 'number',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Contacts/getServiceCaseForContacts_Subpanel.php',
        'return_as_array' => 'true'
    ),
);