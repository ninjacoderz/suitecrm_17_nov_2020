<?php
// created: 2018-12-05 18:20:56
$dictionary["pe_warehouse_pe_stock_items_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'pe_warehouse_pe_stock_items_1' => 
    array (
      'lhs_module' => 'pe_warehouse',
      'lhs_table' => 'pe_warehouse',
      'lhs_key' => 'id',
      'rhs_module' => 'pe_stock_items',
      'rhs_table' => 'pe_stock_items',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'pe_warehouse_pe_stock_items_1_c',
      'join_key_lhs' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
      'join_key_rhs' => 'pe_warehouse_pe_stock_items_1pe_stock_items_idb',
    ),
  ),
  'table' => 'pe_warehouse_pe_stock_items_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'pe_warehouse_pe_stock_items_1pe_stock_items_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'pe_warehouse_pe_stock_items_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'pe_warehouse_pe_stock_items_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'pe_warehouse_pe_stock_items_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'pe_warehouse_pe_stock_items_1pe_stock_items_idb',
      ),
    ),
  ),
);