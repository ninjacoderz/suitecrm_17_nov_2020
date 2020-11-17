<?php
// created: 2019-03-07 19:54:17
$subpanel_layout['list_fields'] = array (
  'number' => 
  array (
    'width' => '5%',
    'vname' => 'LBL_LIST_NUM',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '25%',
    'default' => true,
  ),
  'solargain_tesla_quote_number_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
    'width' => '10%',
  ),
  'solargain_lead_number_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_SOLARGAIN_LEAD_NUMBER',
    'width' => '10%',
  ),
  'solargain_quote_number_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
    'width' => '10%',
  ),
  'billing_account' => 
  array (
    'width' => '20%',
    'vname' => 'LBL_BILLING_ACCOUNT',
    'default' => true,
  ),
  'total_amount' => 
  array (
    'type' => 'currency',
    'width' => '15%',
    'currency_format' => true,
    'vname' => 'LBL_GRAND_TOTAL',
    'default' => true,
  ),
  'expiration' => 
  array (
    'width' => '15%',
    'vname' => 'LBL_EXPIRATION',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'link' => 'assigned_user_link',
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_USER',
    'width' => '15%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'edit_button' => 
  array (
    'widget_class' => 'SubPanelEditButton',
    'module' => 'AOS_Quotes',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'AOS_Quotes',
    'width' => '5%',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'usage' => 'query_only',
  ),
);