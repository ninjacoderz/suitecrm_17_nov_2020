<?php 
 //WARNING: The contents of this file are auto-generated


 //VUT-override config 2020/03/12
$layout_defs["Accounts"]["subpanel_setup"]['account_aos_invoices'] = array (
  'order' => 100,
  'module' => 'AOS_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'AOS_Invoices',
  'get_subpanel_data' => 'aos_invoices',
);


 //VUT-override config 2020/03/12
$layout_defs["Accounts"]["subpanel_setup"]['account_aos_quotes'] = array (
  'order' => 100,
  'module' => 'AOS_Quotes',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'AOS_Quotes',
  'get_subpanel_data' => 'aos_quotes',
);


 //VUT-override config 2020/03/12
$layout_defs["Accounts"]["subpanel_setup"]['account_po_purchase_order'] = array (
  'order' => 100,
  'module' => 'PO_purchase_order',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'PO_purchase_order',
  'get_subpanel_data' => 'po_purchase_order',
);


 // created: 2016-01-12 15:13:34
$layout_defs["Accounts"]["subpanel_setup"]['accounts_aos_quotes_1'] = array (
  'order' => 100,
  'module' => 'AOS_Quotes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
  'get_subpanel_data' => 'accounts_aos_quotes_1',
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


 // created: 2018-07-04 09:14:55
$layout_defs["Accounts"]["subpanel_setup"]['pe_smsmanager_accounts'] = array (
  'order' => 100,
  'module' => 'pe_smsmanager',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_SMSMANAGER_ACCOUNTS_FROM_PE_SMSMANAGER_TITLE',
  'get_subpanel_data' => 'pe_smsmanager_accounts',
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

//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['account_aos_invoices']['override_subpanel_name'] = 'Account_subpanel_account_aos_invoices';


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['account_po_purchase_order']['override_subpanel_name'] = 'Account_subpanel_account_po_purchase_order';


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['contacts']['override_subpanel_name'] = 'Account_subpanel_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['pe_smsmanager_accounts']['override_subpanel_name'] = 'Account_subpanel_pe_smsmanager_accounts';

?>