<?php
 // created: 2017-12-25 05:36:06
$layout_defs["AOS_Quotes"]["subpanel_setup"]['aos_quotes_po_purchase_order_1'] = array (
  'order' => 100,
  'module' => 'PO_purchase_order',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'get_subpanel_data' => 'aos_quotes_po_purchase_order_1',
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
