<?php
 // created: 2020-09-08 10:07:50
$layout_defs["PO_purchase_order"]["subpanel_setup"]['po_purchase_order_aos_products_quotes_1'] = array (
  'order' => 100,
  'module' => 'AOS_Products_Quotes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PO_PURCHASE_ORDER_AOS_PRODUCTS_QUOTES_1_FROM_AOS_PRODUCTS_QUOTES_TITLE',
  'get_subpanel_data' => 'po_purchase_order_aos_products_quotes_1',
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
