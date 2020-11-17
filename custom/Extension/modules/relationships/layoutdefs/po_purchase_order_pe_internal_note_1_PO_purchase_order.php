<?php
 // created: 2020-09-28 07:09:11
$layout_defs["PO_purchase_order"]["subpanel_setup"]['po_purchase_order_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'pe_internal_note',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
  'get_subpanel_data' => 'po_purchase_order_pe_internal_note_1',
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
