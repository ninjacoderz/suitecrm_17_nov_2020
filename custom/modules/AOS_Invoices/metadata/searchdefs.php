<?php
$module_name = 'AOS_Invoices';
$_module_name = 'aos_invoices';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'number' => 
      array (
        'type' => 'int',
        'label' => 'LBL_INVOICE_NUMBER',
        'default' => true,
        'width' => '10%',
        'name' => 'number',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'billing_contact' => 
      array (
        'name' => 'billing_contact',
        'default' => true,
        'width' => '10%',
      ),
      'billing_account' => 
      array (
        'name' => 'billing_account',
        'default' => true,
        'width' => '10%',
      ),
      'number' => 
      array (
        'name' => 'number',
        'default' => true,
        'width' => '10%',
      ),
      'solargain_invoices_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SOLARGAIN_INVOICES_NUMBER_C',
        'width' => '10%',
        'name' => 'solargain_invoices_number_c',
      ),
      'quote_type_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_QUOTE_TYPE',
        'width' => '10%',
        'name' => 'quote_type_c',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'total_amount' => 
      array (
        'name' => 'total_amount',
        'default' => true,
        'width' => '10%',
      ),
      'due_date' => 
      array (
        'name' => 'due_date',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'name' => 'status',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'plumber_contact_c' => 
      array (
        'type' => 'relate',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PLUMBER_CONTACT',
        'id' => 'CONTACT_ID4_C',
        'link' => true,
        'width' => '10%',
        'name' => 'plumber_contact_c',
      ),
      'electrician_contact_c' => 
      array (
        'type' => 'relate',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ELECTRICIAN_CONTACT',
        'id' => 'CONTACT_ID_C',
        'link' => true,
        'width' => '10%',
        'name' => 'electrician_contact_c',
      ),
      'install_address_city_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_INSTALL_ADDRESS_CITY',
        'width' => '10%',
        'name' => 'install_address_city_c',
      ),
      'install_address_state_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_INSTALL_ADDRESS_STATE',
        'width' => '10%',
        'name' => 'install_address_state_c',
      ),
      'install_address_postalcode_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_INSTALL_ADDRESS_POSTALCODE',
        'width' => '10%',
        'name' => 'install_address_postalcode_c',
      ),
      'xero_invoice_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_XERO_INVOICE',
        'width' => '10%',
        'name' => 'xero_invoice_c',
      ),
      'xero_stc_rebate_invoice_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_XERO_STC_REBATE_INVOICE',
        'width' => '10%',
        'name' => 'xero_stc_rebate_invoice_c',
      ),
      'xero_veec_rebate_invoice_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_XERO_VEEC_REBATE_INVOICE',
        'width' => '10%',
        'name' => 'xero_veec_rebate_invoice_c',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
    'maxColumnsBasic' => '3',
  ),
);
;
?>
