<?php
 // created: 2019-05-24 12:28:45
$layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_invoices_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'pe_internal_note',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'date_entered',
  'title_key' => 'LBL_AOS_INVOICES_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
  'get_subpanel_data' => 'aos_invoices_pe_internal_note_1',
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
