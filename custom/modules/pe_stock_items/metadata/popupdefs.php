<?php
$popupMeta = array (
    'moduleMain' => 'pe_stock_items',
    'varName' => 'pe_stock_items',
    'orderBy' => 'pe_stock_items.name',
    'whereClauses' => array (
  'name' => 'pe_stock_items.name',
  'part_number' => 'pe_stock_items.part_number',
  'serial_number' => 'pe_stock_items.serial_number',
  'assigned_user_id' => 'pe_stock_items.assigned_user_id',
  'invoice_c' => 'pe_stock_items.invoice_c',
  'pe_warehouse_pe_stock_items_1_name' => 'pe_stock_items.pe_warehouse_pe_stock_items_1_name',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'part_number',
  5 => 'serial_number',
  6 => 'assigned_user_id',
  7 => 'invoice_c',
  8 => 'pe_warehouse_pe_stock_items_1_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'part_number' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PART_NUMBER',
    'width' => '10%',
    'name' => 'part_number',
  ),
  'serial_number' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRODUCT_SERIAL_NUMBER',
    'width' => '10%',
    'name' => 'serial_number',
  ),
  'invoice_c' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_INVOICE',
    'id' => 'AOS_INVOICES_ID_C',
    'link' => true,
    'width' => '10%',
    'name' => 'invoice_c',
  ),
  'pe_warehouse_pe_stock_items_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
    'id' => 'PE_WAREHOUSE_PE_STOCK_ITEMS_1PE_WAREHOUSE_IDA',
    'width' => '10%',
    'name' => 'pe_warehouse_pe_stock_items_1_name',
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
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'PART_NUMBER' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_PART_NUMBER',
    'width' => '10%',
    'name' => 'part_number',
  ),
  'SERIAL_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRODUCT_SERIAL_NUMBER',
    'width' => '10%',
    'default' => true,
    'name' => 'serial_number',
  ),
  'INVOICE_C' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_INVOICE',
    'id' => 'AOS_INVOICES_ID_C',
    'link' => true,
    'width' => '10%',
    'name' => 'invoice_c',
  ),
  'PE_WAREHOUSE_PE_STOCK_ITEMS_1_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
    'id' => 'PE_WAREHOUSE_PE_STOCK_ITEMS_1PE_WAREHOUSE_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
    'name' => 'assigned_user_name',
  ),
),
);
