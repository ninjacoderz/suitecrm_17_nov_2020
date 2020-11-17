<?php
// created: 2019-10-17 15:24:12
$dictionary["pe_warehouse_log_pe_internal_note_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'pe_warehouse_log_pe_internal_note_1' => 
    array (
      'lhs_module' => 'pe_warehouse_log',
      'lhs_table' => 'pe_warehouse_log',
      'lhs_key' => 'id',
      'rhs_module' => 'pe_internal_note',
      'rhs_table' => 'pe_internal_note',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'pe_warehouse_log_pe_internal_note_1_c',
      'join_key_lhs' => 'pe_warehouse_log_pe_internal_note_1pe_warehouse_log_ida',
      'join_key_rhs' => 'pe_warehouse_log_pe_internal_note_1pe_internal_note_idb',
    ),
  ),
  'table' => 'pe_warehouse_log_pe_internal_note_1_c',
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
      'name' => 'pe_warehouse_log_pe_internal_note_1pe_warehouse_log_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'pe_warehouse_log_pe_internal_note_1pe_internal_note_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'pe_warehouse_log_pe_internal_note_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'pe_warehouse_log_pe_internal_note_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'pe_warehouse_log_pe_internal_note_1pe_warehouse_log_ida',
        1 => 'pe_warehouse_log_pe_internal_note_1pe_internal_note_idb',
      ),
    ),
  ),
);