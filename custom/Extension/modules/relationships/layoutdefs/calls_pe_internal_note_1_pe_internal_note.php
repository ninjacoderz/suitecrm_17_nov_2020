<?php
 // created: 2020-07-23 03:31:32
$layout_defs["pe_internal_note"]["subpanel_setup"]['calls_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'Calls',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_CALLS_TITLE',
  'get_subpanel_data' => 'calls_pe_internal_note_1',
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
