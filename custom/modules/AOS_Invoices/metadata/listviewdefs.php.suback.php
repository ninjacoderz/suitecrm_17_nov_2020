<?php
$listViewDefs ['AOS_Invoices'] = 
array (
  'NUMBER' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_NUM',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_ACCOUNT_NAME',
    'link' => true,
    'default' => true,
  ),
  'STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'default' => true,
  ),
  'BILLING_CONTACT' => 
  array (
    'width' => '11%',
    'label' => 'LBL_BILLING_CONTACT',
    'default' => true,
    'module' => 'Contacts',
    'id' => 'BILLING_CONTACT_ID',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'billing_contact_id',
    ),
  ),
  //VUT-S-ADD COLUMN EMAIL OF CONTACT
  'EMAIL_CONTACT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EMAIL_CONTACT',
    'width' => '15%',
    'sortable' => false,
    'link' => true,
    'customCode' => '{$CUSTOM_EMAIL_CONTACT_LINK}',
    'default' => true,
  ),
  //VUT-E-ADD COLUMN EMAIL OF CONTACT

  'BILLING_ACCOUNT' => 
  array (
    'width' => '15%',
    'label' => 'LBL_BILLING_ACCOUNT',
    'default' => true,
    'module' => 'Accounts',
    'id' => 'BILLING_ACCOUNT_ID',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'billing_account_id',
    ),
  ),
  'QUOTE_TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_QUOTE_TYPE',
    'width' => '10%',
  ),
  'TOTAL_AMOUNT' => 
  array (
    'width' => '10%',
    'label' => 'LBL_GRAND_TOTAL',
    'default' => true,
    'currency_format' => true,
  ),
  'GROSS_PROFIT_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_GROSS_PROFIT_C',
    'width' => '10%',
  ),
  'GROSS_PROFIT_PERCENT_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_GROSS_PROFIT_PERCENT_C',
    'width' => '10%',
  ),
  'DUE_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DUE_DATE',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ASSIGNED_USER',
    'default' => true,
    'module' => 'Users',
    'id' => 'ASSIGNED_USER_ID',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'assigned_user_id',
    ),
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '5%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'INSTALLATION_DATE_C' => 
  array (
    'type' => 'datetimecombo',
    'default' => true,
    'label' => 'LBL_INSTALLATION_DATE',
    'width' => '10%',
  ),
  'SYSTEM_OWNER_TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SYSTEM_OWNER_TYPE',
    'width' => '10%',
  ),
  'REGISTERED_FOR_GST_C' => 
  array (
    'type' => 'radioenum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_REGISTERED_FOR_GST',
    'width' => '10%',
  ),
  'ANNUAL_REVENUE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ANNUAL_REVENUE',
    'default' => false,
  ),
  'BILLING_ADDRESS_STREET' => 
  array (
    'width' => '15%',
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'default' => false,
  ),
  'BILLING_ADDRESS_CITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CITY',
    'default' => false,
  ),
  'XERO_INVOICE_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_XERO_INVOICE',
    'width' => '10%',
  ),
  'SOLARGAIN_INVOICES_NUMBER_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_SOLARGAIN_INVOICES_NUMBER_C',
    'width' => '10%',
  ),
  'XERO_VEEC_REBATE_INVOICE_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_XERO_VEEC_REBATE_INVOICE',
    'width' => '10%',
  ),
  'XERO_STC_REBATE_INVOICE_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_XERO_STC_REBATE_INVOICE',
    'width' => '10%',
  ),
  'BILLING_ADDRESS_STATE' => 
  array (
    'width' => '7%',
    'label' => 'LBL_BILLING_ADDRESS_STATE',
    'default' => false,
  ),
  'BILLING_ADDRESS_POSTALCODE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_BILLING_ADDRESS_POSTALCODE',
    'default' => false,
  ),
  'BILLING_ADDRESS_COUNTRY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_STREET' => 
  array (
    'width' => '15%',
    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_CITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_SHIPPING_ADDRESS_CITY',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_STATE' => 
  array (
    'width' => '7%',
    'label' => 'LBL_SHIPPING_ADDRESS_STATE',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_POSTALCODE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
    'default' => false,
  ),
  'SHIPPING_ADDRESS_COUNTRY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
    'default' => false,
  ),
  'PHONE_ALTERNATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PHONE_ALT',
    'default' => false,
  ),
  'WEBSITE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_WEBSITE',
    'default' => false,
  ),
  'OWNERSHIP' => 
  array (
    'width' => '10%',
    'label' => 'LBL_OWNERSHIP',
    'default' => false,
  ),
  'EMPLOYEES' => 
  array (
    'width' => '10%',
    'label' => 'LBL_EMPLOYEES',
    'default' => false,
  ),
  'TICKER_SYMBOL' => 
  array (
    'width' => '10%',
    'label' => 'LBL_TICKER_SYMBOL',
    'default' => false,
  ),
  'STATUS_GEO_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_STATUS_GEO',
    'width' => '10%',
  ),
  'ELECTRICIAN_CONTACT_C' => 
  array (
    'type' => 'relate',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_ELECTRICIAN_CONTACT',
    'id' => 'CONTACT_ID_C',
    'link' => true,
    'width' => '10%',
  ),
  'PLUMBER_CONTACT_C' => 
  array (
    'type' => 'relate',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_PLUMBER_CONTACT',
    'id' => 'CONTACT_ID4_C',
    'link' => true,
    'width' => '10%',
  ),
);
;
?>
