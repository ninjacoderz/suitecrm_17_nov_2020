<?php
$module_name = 'AOS_Quotes';
$_module_name = 'aos_quotes';
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
      'solargain_quote_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
        'width' => '10%',
        'name' => 'solargain_quote_number_c',
      ),
      'solargain_tesla_quote_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
        'width' => '10%',
        'name' => 'solargain_tesla_quote_number_c',
      ),
      'total_amount' => 
      array (
        'name' => 'total_amount',
        'default' => true,
        'width' => '10%',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'expiration' => 
      array (
        'name' => 'expiration',
        'default' => true,
        'width' => '10%',
      ),
      'stage' => 
      array (
        'name' => 'stage',
        'default' => true,
        'width' => '10%',
      ),
      'term' => 
      array (
        'name' => 'term',
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
      'lead_source_co_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_LEAD_SOURCE_CO_C',
        'width' => '10%',
        'name' => 'lead_source_co_c',
      ),
      'lead_source_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_LEAD_SOURCE',
        'width' => '10%',
        'name' => 'lead_source_c',
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
