<?php
$module_name = 'pe_stock_items';
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
      'serial_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PRODUCT_SERIAL_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'serial_number',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'warehouse_c' => 
      array (
        'type' => 'relate',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_WAREHOUSE',
        'id' => 'PE_WAREHOUSE_ID_C',
        'link' => true,
        'width' => '10%',
        'name' => 'warehouse_c',
      ),
      'pe_warehouse_pe_stock_items_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
        'id' => 'PE_WAREHOUSE_PE_STOCK_ITEMS_1PE_WAREHOUSE_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'pe_warehouse_pe_stock_items_1_name',
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
      'serial_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PRODUCT_SERIAL_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'serial_number',
      ),
      'part_number' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_PART_NUMBER',
        'width' => '10%',
        'name' => 'part_number',
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
