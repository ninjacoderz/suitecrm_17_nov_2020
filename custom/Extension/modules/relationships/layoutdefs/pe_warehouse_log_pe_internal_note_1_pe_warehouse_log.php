<?php
 // created: 2019-10-17 15:24:12
$layout_defs["pe_warehouse_log"]["subpanel_setup"]['pe_warehouse_log_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'pe_internal_note',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_WAREHOUSE_LOG_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
  'get_subpanel_data' => 'pe_warehouse_log_pe_internal_note_1',
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
