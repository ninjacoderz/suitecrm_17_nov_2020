<?php
 // created: 2020-10-12 03:59:07
$layout_defs["PO_purchase_order"]["subpanel_setup"]['po_purchase_order_emails_1'] = array (
  'order' => 100,
  'module' => 'Emails',
  'subpanel_name' => 'ForQueues',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PO_PURCHASE_ORDER_EMAILS_1_FROM_EMAILS_TITLE',
  'get_subpanel_data' => 'po_purchase_order_emails_1',
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
