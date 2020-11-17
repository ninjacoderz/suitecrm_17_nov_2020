<?php
$dashletData['LeadsDashlet']['searchFields'] = array (
  'last_name' => 
  array (
    'default' => '',
  ),
  'first_name' => 
  array (
    'default' => '',
  ),
  'email1' => 
  array (
    'default' => '',
  ),
  'phone_mobile' => 
  array (
    'default' => '',
  ),
  'primary_address_street' => 
  array (
    'default' => '',
  ),
  'primary_address_city' => 
  array (
    'default' => '',
  ),
  'primary_address_state' => 
  array (
    'default' => '',
  ),
  'date_entered' => 
  array (
    'default' => '',
  ),
  'solargain_lead_number_c' => 
  array (
    'default' => '',
  ),
  'solargain_quote_number_c' => 
  array (
    'default' => '',
  ),
  'time_accepted_job_c' => 
  array (
    'default' => '',
  ),
  'time_completed_job_c' => 
  array (
    'default' => '',
  ),
  'time_request_design_c' => 
  array (
    'default' => '',
  ),
  'time_sent_to_client_c' => 
  array (
    'default' => '',
  ),
  'account_name' => 
  array (
    'default' => '',
  ),
  'status' => 
  array (
    'default' => '',
  ),
  'lead_source_co_c' => 
  array (
    'default' => '',
  ),
  'assigned_user_name' => 
  array (
    'default' => '',
  ),
);
$dashletData['LeadsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '25%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
    'name' => 'name',
  ),
  'title' => 
  array (
    'width' => '20%',
    'label' => 'LBL_TITLE',
    'default' => true,
    'name' => 'title',
  ),
  'phone_work' => 
  array (
    'width' => '25%',
    'label' => 'LBL_OFFICE_PHONE',
    'default' => true,
    'name' => 'phone_work',
  ),
  'email1' => 
  array (
    'width' => '30%',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'customCode' => '{$EMAIL1_LINK}</a>',
    'default' => true,
    'name' => 'email1',
  ),
  'solargain_link' => 
  array (
    'width' => '35%',
    'label' => 'SG Link',
    'default' => true,
    'link' => false,
    'name' => 'solargain_link',
    'customCode' => '{$SOLARGAIN_LINK}',
    'inline_edit' => false,
  ),
  'convert_lead_link' => 
  array (
    'width' => '35%',
    'label' => 'Convert Link',
    'default' => true,
    'link' => false,
    'name' => 'convert_lead_link',
    'customCode' => '{$CONVERT_LIST_LINK}',
    'inline_edit' => false,
  ),
  'email_address' => 
  array (
    'width' => '35%',
    'label' => 'Custom Email Links',
    'default' => true,
    'link' => false,
    'name' => 'email_address',
    'customCode' => '{$CUSTOM_EMAIL_LINK}',
    'inline_edit' => false,
  ),
  'time_accepted_job_c' => 
  array (
    'type' => 'datetimecombo',
    'default' => false,
    'label' => 'LBL_TIME_ACCEPTED_JOB',
    'width' => '10%',
    'name' => 'time_accepted_job_c',
  ),
  'time_completed_job_c' => 
  array (
    'type' => 'datetimecombo',
    'default' => false,
    'label' => 'LBL_TIME_COMPLETED_JOB',
    'width' => '10%',
    'name' => 'time_completed_job_c',
  ),
  'designer_c' => 
  array (
    'type' => 'relate',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_DESIGNER',
    'id' => 'USER_ID_C',
    'link' => true,
    'width' => '10%',
    'name' => 'designer_c',
  ),
  'lead_source' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LEAD_SOURCE',
    'name' => 'lead_source',
    'default' => false,
  ),
  'status' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'name' => 'status',
    'default' => false,
  ),
  'solargain_lead_number_c' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_SOLARGAIN_LEAD_NUMBER',
    'width' => '10%',
    'name' => 'solargain_lead_number_c',
  ),
  'solargain_quote_number_c' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
    'width' => '10%',
    'name' => 'solargain_quote_number_c',
  ),
  'account_name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_ACCOUNT_NAME',
    'name' => 'account_name',
    'default' => false,
  ),
  'phone_home' => 
  array (
    'width' => '10%',
    'label' => 'LBL_HOME_PHONE',
    'name' => 'phone_home',
    'default' => false,
  ),
  'phone_mobile' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MOBILE_PHONE',
    'name' => 'phone_mobile',
    'default' => false,
  ),
  'phone_other' => 
  array (
    'width' => '10%',
    'label' => 'LBL_OTHER_PHONE',
    'name' => 'phone_other',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_DATE_ENTERED',
    'name' => 'date_entered',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
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
  'primary_address_street' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_STREET',
    'width' => '10%',
    'default' => false,
    'name' => 'primary_address_street',
  ),
  'distance_to_sg_c' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_DISTANCE_TO_SG',
    'width' => '10%',
    'name' => 'distance_to_sg_c',
  ),
  'primary_address_city' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_CITY',
    'width' => '10%',
    'default' => false,
  ),
  'primary_address_state' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_STATE',
    'width' => '10%',
    'default' => false,
  ),
  'primary_address_postalcode' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'width' => '10%',
    'default' => false,
  ),
);
