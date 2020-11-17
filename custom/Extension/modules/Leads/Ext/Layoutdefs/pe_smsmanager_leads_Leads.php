<?php
 // created: 2018-07-04 09:14:55
$layout_defs["Leads"]["subpanel_setup"]['pe_smsmanager_leads'] = array (
  'order' => 100,
  'module' => 'pe_smsmanager',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_SMSMANAGER_LEADS_FROM_PE_SMSMANAGER_TITLE',
  'get_subpanel_data' => 'pe_smsmanager_leads',
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
