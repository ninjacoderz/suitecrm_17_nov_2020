<?php
// created: 2020-06-19 09:23:13
$searchdefs['AOS_Quotes'] = array (
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
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'name' => 'billing_contact',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'name' => 'billing_account',
        'default' => true,
        'width' => '10%',
      ),
      3 => 
      array (
        'name' => 'number',
        'default' => true,
        'width' => '10%',
      ),
      4 => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
        'width' => '10%',
        'name' => 'solargain_quote_number_c',
      ),
      5 => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
        'width' => '10%',
        'name' => 'solargain_tesla_quote_number_c',
      ),
      6 => 
      array (
        'name' => 'total_amount',
        'default' => true,
        'width' => '10%',
      ),
      7 => 
      array (
        'name' => 'expiration',
        'default' => true,
        'width' => '10%',
      ),
      8 => 
      array (
        'name' => 'stage',
        'default' => true,
        'width' => '10%',
      ),
      9 => 
      array (
        'name' => 'term',
        'default' => true,
        'width' => '10%',
      ),
      10 => 
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
      11 => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_LEAD_SOURCE_CO_C',
        'width' => '10%',
        'name' => 'lead_source_co_c',
      ),
      12 => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_LEAD_SOURCE',
        'width' => '10%',
        'name' => 'lead_source_c',
      ),
      13 => 
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
);