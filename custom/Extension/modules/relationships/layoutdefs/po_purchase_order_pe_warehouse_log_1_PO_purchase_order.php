<?php
 // created: 2018-12-05 20:14:27
$layout_defs["PO_purchase_order"]["subpanel_setup"]['po_purchase_order_pe_warehouse_log_1'] = array (
  'order' => 100,
  'module' => 'pe_warehouse_log',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE',
  'get_subpanel_data' => 'po_purchase_order_pe_warehouse_log_1',
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
