<?php
// created: 2018-07-04 09:14:55
$dictionary["pe_smsmanager_aos_invoices"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'pe_smsmanager_aos_invoices' => 
    array (
      'lhs_module' => 'pe_smsmanager',
      'lhs_table' => 'pe_smsmanager',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Invoices',
      'rhs_table' => 'aos_invoices',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'pe_smsmanager_aos_invoices_c',
      'join_key_lhs' => 'pe_smsmanager_aos_invoicespe_smsmanager_ida',
      'join_key_rhs' => 'pe_smsmanager_aos_invoicesaos_invoices_idb',
    ),
  ),
  'table' => 'pe_smsmanager_aos_invoices_c',
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
      'name' => 'pe_smsmanager_aos_invoicespe_smsmanager_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'pe_smsmanager_aos_invoicesaos_invoices_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'pe_smsmanager_aos_invoicesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'pe_smsmanager_aos_invoices_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'pe_smsmanager_aos_invoicespe_smsmanager_ida',
        1 => 'pe_smsmanager_aos_invoicesaos_invoices_idb',
      ),
    ),
  ),
);