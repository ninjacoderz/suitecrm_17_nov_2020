<?php
 // created: 2019-03-13 18:24:35
$layout_defs["Calls"]["subpanel_setup"]['calls_aos_quotes_1'] = array (
  'order' => 100,
  'module' => 'AOS_Quotes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CALLS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
  'get_subpanel_data' => 'calls_aos_quotes_1',
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
