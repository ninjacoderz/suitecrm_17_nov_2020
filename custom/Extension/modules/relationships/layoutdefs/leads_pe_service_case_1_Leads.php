<?php
 // created: 2020-06-01 07:01:40
$layout_defs["Leads"]["subpanel_setup"]['leads_pe_service_case_1'] = array (
  'order' => 100,
  'module' => 'pe_service_case',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'get_subpanel_data' => 'leads_pe_service_case_1',
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
