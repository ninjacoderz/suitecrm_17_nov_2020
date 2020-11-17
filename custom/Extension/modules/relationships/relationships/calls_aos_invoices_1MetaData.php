<?php
// created: 2019-03-13 18:27:05
$dictionary["calls_aos_invoices_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'calls_aos_invoices_1' => 
    array (
      'lhs_module' => 'Calls',
      'lhs_table' => 'calls',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Invoices',
      'rhs_table' => 'aos_invoices',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'calls_aos_invoices_1_c',
      'join_key_lhs' => 'calls_aos_invoices_1calls_ida',
      'join_key_rhs' => 'calls_aos_invoices_1aos_invoices_idb',
    ),
  ),
  'table' => 'calls_aos_invoices_1_c',
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
      'name' => 'calls_aos_invoices_1calls_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'calls_aos_invoices_1aos_invoices_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'calls_aos_invoices_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'calls_aos_invoices_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'calls_aos_invoices_1calls_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'calls_aos_invoices_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'calls_aos_invoices_1aos_invoices_idb',
      ),
    ),
  ),
);