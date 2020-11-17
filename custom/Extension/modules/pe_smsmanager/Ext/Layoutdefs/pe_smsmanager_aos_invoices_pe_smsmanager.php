<?php
 // created: 2018-07-04 09:14:55
$layout_defs["pe_smsmanager"]["subpanel_setup"]['pe_smsmanager_aos_invoices'] = array (
  'order' => 100,
  'module' => 'AOS_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_SMSMANAGER_AOS_INVOICES_FROM_AOS_INVOICES_TITLE',
  'get_subpanel_data' => 'pe_smsmanager_aos_invoices',
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
