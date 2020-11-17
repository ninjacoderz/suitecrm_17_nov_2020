<?php
$module_name = 'pe_warehouse_log';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
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
      'status_c' => 
      array (
        'name' => 'status_c',
        'label' => 'Status',
        'type' => 'enum',
        'studio' => 'visible',
        'options' => 
            array (
            'Collecting' => 'Collecting',
            'Collected' => 'Collected',
            'Delivering' => 'Delivering',
            'Allocated' => 'Allocated',
            'Collect' => 'Collect',
            'Pending' => 'Pending',
            'Delivered' => 'Delivered',
            'Proof of Delivery' => 'Proof of Delivery',
            ),
        'default' => true,
        'width' => '10%',
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
