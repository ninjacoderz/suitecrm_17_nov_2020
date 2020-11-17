<?php
$dashletData['AOS_QuotesDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'billing_contact' => 
  array (
    'default' => '',
  ),
  'billing_account' => 
  array (
    'default' => '',
  ),
  'number' => 
  array (
    'default' => '',
  ),
  'solargain_quote_number_c' => 
  array (
    'default' => '',
  ),
  'solargain_tesla_quote_number_c' => 
  array (
    'default' => '',
  ),
  'total_amount' => 
  array (
    'default' => '',
  ),
  'date_entered' => 
  array (
    'default' => '',
  ),
  'expiration' => 
  array (
    'default' => '',
  ),
  'stage' => 
  array (
    'default' => '',
  ),
  'term' => 
  array (
    'default' => '',
  ),
  'assigned_user_name' => 
  array (
    'default' => '',
  ),
  'lead_source_c' => 
  array (
    'default' => '',
  ),
  'quote_type_c' => 
  array (
    'default' => '',
  ),
);
$dashletData['AOS_QuotesDashlet']['columns'] = array (
  'number' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_NUM',
    'default' => true,
    'name' => 'number',
  ),
  'name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'stage' => 
  array (
    'width' => '15%',
    'label' => 'LBL_STAGE',
    'default' => true,
    'name' => 'stage',
  ),
  'total_amount' => 
  array (
    'width' => '15%',
    'label' => 'LBL_GRAND_TOTAL',
    'currency_format' => true,
    'default' => true,
    'name' => 'total_amount',
  ),
  'expiration' => 
  array (
    'width' => '15%',
    'label' => 'LBL_EXPIRATION',
    'default' => true,
    'name' => 'expiration',
  ),
  'billing_account' => 
  array (
    'width' => '20%',
    'label' => 'LBL_BILLING_ACCOUNT',
    'name' => 'billing_account',
    'default' => false,
  ),
  'billing_contact' => 
  array (
    'width' => '15%',
    'label' => 'LBL_BILLING_CONTACT',
    'name' => 'billing_contact',
    'default' => false,
  ),
  'opportunity' => 
  array (
    'width' => '25%',
    'label' => 'LBL_OPPORTUNITY',
    'name' => 'opportunity',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'name' => 'date_entered',
    'default' => false,
  ),
  'quote_type_c' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_QUOTE_TYPE',
    'width' => '10%',
    'name' => 'quote_type_c',
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
  'next_action_date_c' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_NEXT_ACTION_DATE',
    'width' => '10%',
  ),
);
