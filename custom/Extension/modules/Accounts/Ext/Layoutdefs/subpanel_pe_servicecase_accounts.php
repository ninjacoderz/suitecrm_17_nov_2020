<?php
 //VUT- Create subpanel "SERVICE CASE" in Accounts >> 2020/06/09
$layout_defs["Accounts"]["subpanel_setup"]['subpanel_pe_servicecase_accounts'] = 
  array ('order' => 100,
    'module' => 'pe_service_case',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_servicecase_for_accounts',
    'generate_select' => true,
    'title_key' => 'SERVICE CASE',
    'sort_order' => 'desc',
    'sort_by' => 'date_modified',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Accounts/getServiceCaseForAccounts_Subpanel.php',
        'account_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
);