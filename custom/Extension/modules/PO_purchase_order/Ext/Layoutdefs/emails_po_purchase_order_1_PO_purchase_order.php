<?php
 // created: 2020-10-12 04:21:01
$layout_defs["PO_purchase_order"]["subpanel_setup"]['emails_po_purchase_order_1'] = array (
  'order' => 100,
  'module' => 'Emails',
  'subpanel_name' => 'ForQueues',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_EMAILS_PO_PURCHASE_ORDER_1_FROM_EMAILS_TITLE',
  'get_subpanel_data' => 'emails_po_purchase_order_1',
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
