<?php
 // created: 2018-07-19 08:35:42
$layout_defs["pe_warehouse"]["subpanel_setup"]['pe_warehouse_log_pe_warehouse'] = array (
  'order' => 100,
  'module' => 'pe_warehouse_log',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE',
  'get_subpanel_data' => 'pe_warehouse_log_pe_warehouse',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
