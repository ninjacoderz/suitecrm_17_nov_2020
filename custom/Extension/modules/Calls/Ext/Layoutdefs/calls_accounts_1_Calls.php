<?php
 // created: 2019-03-13 18:27:33
$layout_defs["Calls"]["subpanel_setup"]['calls_accounts_1'] = array (
  'order' => 100,
  'module' => 'Accounts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
  'get_subpanel_data' => 'calls_accounts_1',
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
