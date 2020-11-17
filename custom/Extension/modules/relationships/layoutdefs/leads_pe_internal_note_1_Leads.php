<?php
 // created: 2019-06-18 17:50:10
$layout_defs["Leads"]["subpanel_setup"]['leads_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'pe_internal_note',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
  'get_subpanel_data' => 'leads_pe_internal_note_1',
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
