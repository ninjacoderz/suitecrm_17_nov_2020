<?php
 // created: 2017-12-27 14:27:35
$layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_invoices_pe_service_case_1'] = array (
  'order' => 100,
  'module' => 'pe_service_case',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'get_subpanel_data' => 'aos_invoices_pe_service_case_1',
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
