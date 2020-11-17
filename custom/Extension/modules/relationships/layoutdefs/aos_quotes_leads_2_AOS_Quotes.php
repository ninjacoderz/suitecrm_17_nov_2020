<?php
 // created: 2019-03-06 14:05:29
$layout_defs["AOS_Quotes"]["subpanel_setup"]['aos_quotes_leads_2'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_AOS_QUOTES_LEADS_2_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'aos_quotes_leads_2',
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
