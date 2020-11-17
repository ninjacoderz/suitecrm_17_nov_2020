<?php
// created: 2020-04-21 07:51:29
$dictionary["pe_service_case_pe_message_servicecase_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'relationships' => 
  array (
    'pe_service_case_pe_message_servicecase_1' => 
    array (
      'lhs_module' => 'pe_service_case',
      'lhs_table' => 'pe_service_case',
      'lhs_key' => 'id',
      'rhs_module' => 'pe_message_servicecase',
      'rhs_table' => 'pe_message_servicecase',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'pe_service_case_pe_message_servicecase_1_c',
      'join_key_lhs' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
      'join_key_rhs' => 'pe_service7c1eicecase_idb',
    ),
  ),
  'table' => 'pe_service_case_pe_message_servicecase_1_c',
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
      'name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'pe_service7c1eicecase_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'pe_service_case_pe_message_servicecase_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'pe_service_case_pe_message_servicecase_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'pe_service_case_pe_message_servicecase_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'pe_service7c1eicecase_idb',
      ),
    ),
  ),
);