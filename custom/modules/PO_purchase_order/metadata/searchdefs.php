<?php
$module_name = 'PO_purchase_order';
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
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'billing_account' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_BILLING_ACCOUNT',
        'id' => 'BILLING_ACCOUNT_ID',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'billing_account',
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
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
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
      'billing_account' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_BILLING_ACCOUNT',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'id' => 'BILLING_ACCOUNT_ID',
        'name' => 'billing_account',
      ),
      'status_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status_c',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'delivery_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_DELIVERY_DATE',
        'width' => '10%',
        'name' => 'delivery_date_c',
      ),
      'install_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_DUE_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'install_date',
      ),
      'dispatch_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_DISPATCH_DATE',
        'width' => '10%',
        'name' => 'dispatch_date_c',
      ),
      'supplier_order_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SUPPLIER_ORDER',
        'width' => '10%',
        'name' => 'supplier_order_c',
      ),
      'number' => 
      array (
        'type' => 'int',
        'label' => 'LBL_QUOTE_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'number',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
