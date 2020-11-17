<?php
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