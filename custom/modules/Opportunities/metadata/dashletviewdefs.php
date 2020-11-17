<?php
$dashletData['OpportunitiesDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'opportunity_type' => 
  array (
    'default' => '',
  ),
  'sales_stage' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
  'date_closed' => 
  array (
    'default' => '',
  ),
);
$dashletData['OpportunitiesDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '35',
    'label' => 'LBL_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
  ),
  'account_name' => 
  array (
    'width' => '35',
    'label' => 'LBL_ACCOUNT_NAME',
    'default' => true,
    'link' => false,
    'id' => 'account_id',
    'ACLTag' => 'ACCOUNT',
  ),
  'amount_usdollar' => 
  array (
    'width' => '15',
    'label' => 'LBL_AMOUNT_USDOLLAR',
    'default' => true,
    'currency_format' => true,
  ),
  'date_closed' => 
  array (
    'width' => '15',
    'label' => 'LBL_DATE_CLOSED',
    'default' => true,
    'defaultOrderColumn' => 
    array (
      'sortOrder' => 'ASC',
    ),
  ),
  'opportunity_type' => 
  array (
    'width' => '15',
    'label' => 'LBL_TYPE',
  ),
  'lead_source' => 
  array (
    'width' => '15',
    'label' => 'LBL_LEAD_SOURCE',
  ),
  'sales_stage' => 
  array (
    'width' => '15',
    'label' => 'LBL_SALES_STAGE',
  ),
  'probability' => 
  array (
    'width' => '15',
    'label' => 'LBL_PROBABILITY',
  ),
  'date_entered' => 
  array (
    'width' => '15',
    'label' => 'LBL_DATE_ENTERED',
  ),
  'date_modified' => 
  array (
    'width' => '15',
    'label' => 'LBL_DATE_MODIFIED',
  ),
  'created_by' => 
  array (
    'width' => '8',
    'label' => 'LBL_CREATED',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8',
    'label' => 'LBL_LIST_ASSIGNED_USER',
  ),
  'next_step' => 
  array (
    'width' => '10',
    'label' => 'LBL_NEXT_STEP',
  ),
  'phone_office' => 
  array (
    'width' => '35',
    'label' => 'LBL_PHONE_OFFICE',
    'default' => true,
    'link' => false,
    'id' => 'phone_office_id',
  ),
  'billing_address_street' => 
  array (
    'width' => '35',
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'default' => true,
    'link' => false,
    'id' => 'billing_address_street_id',
  ),
  'email_address' => 
  array (
    'width' => '35',
    'label' => 'LBL_EMAIL_ADDRESS',
    'default' => true,
    'link' => false,
    'name' => 'email_address',
    'customCode' => '{$CUSTOM_EMAIL_LINK}',
  ),
  'solargain_lead_number' => 
  array (
    'width' => '35',
    'label' => 'SG Link',
    'default' => true,
    'link' => false,
    'name' => 'solargain_lead_number',
    'customCode' => '{$SOLARGAIN_LINK}',
  ),
  'phone_info' => 
  array (
    'width' => '35',
    'label' => 'Phone Number',
    'default' => true,
    'link' => false,
    'name' => 'phone_info',
  ),
  'lead_link' => 
  array (
    'width' => '35',
    'label' => 'Lead Link',
    'default' => true,
    'link' => false,
    'name' => 'lead_link',
  ),
  'quote_link' => 
  array (
    'width' => '35',
    'label' => 'Quote Link',
    'default' => true,
    'link' => false,
    'name' => 'quote_link',
  ),
  'map_link' => 
  array (
    'width' => '35',
    'label' => 'Map Link',
    'default' => true,
    'link' => false,
    'name' => 'map_link',
  ),
  'distance_to_sg' => array('width'  => '35', 
    'label'   => 'Distance To SG',
    'default' => true,
    'link' => false,
    'name' => 'distance_to_sg',
  ),

  'sg_office' => array('width'  => '35', 
    'label'   => 'SG Office',
    'default' => true,
    'link' => false,
    'name' => 'sg_office',
    'customCode' => '{$SOLARGAIN_OFFICE}',
  ),
  'description' => 
  array (
    'width' => '8',
    'label' => 'LBL_DESCRIPTION',
  ),
);
