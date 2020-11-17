<?php
 // created: 2018-12-05 18:20:56
$layout_defs["pe_warehouse"]["subpanel_setup"]['pe_warehouse_pe_stock_items_1'] = array (
  'order' => 100,
  'module' => 'pe_stock_items',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_STOCK_ITEMS_TITLE',
  'get_subpanel_data' => 'pe_warehouse_pe_stock_items_1',
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
