<?php
// created: 2018-07-19 08:35:42
$dictionary["pe_warehouse_log_pe_warehouse"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'pe_warehouse_log_pe_warehouse' => 
    array (
      'lhs_module' => 'pe_warehouse',
      'lhs_table' => 'pe_warehouse',
      'lhs_key' => 'id',
      'rhs_module' => 'pe_warehouse_log',
      'rhs_table' => 'pe_warehouse_log',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'pe_warehouse_log_pe_warehouse_c',
      'join_key_lhs' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
      'join_key_rhs' => 'pe_warehouse_log_pe_warehousepe_warehouse_log_idb',
    ),
  ),
  'table' => 'pe_warehouse_log_pe_warehouse_c',
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
      'name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'pe_warehouse_log_pe_warehousepe_warehouse_log_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'pe_warehouse_log_pe_warehousespk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'pe_warehouse_log_pe_warehouse_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'pe_warehouse_log_pe_warehouse_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'pe_warehouse_log_pe_warehousepe_warehouse_log_idb',
      ),
    ),
  ),
);