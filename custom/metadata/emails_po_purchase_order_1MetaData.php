<?php
// created: 2020-10-12 04:21:01
$dictionary["emails_po_purchase_order_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'emails_po_purchase_order_1' => 
    array (
      'lhs_module' => 'Emails',
      'lhs_table' => 'emails',
      'lhs_key' => 'id',
      'rhs_module' => 'PO_purchase_order',
      'rhs_table' => 'po_purchase_order',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'emails_po_purchase_order_1_c',
      'join_key_lhs' => 'emails_po_purchase_order_1emails_ida',
      'join_key_rhs' => 'emails_po_purchase_order_1po_purchase_order_idb',
    ),
  ),
  'table' => 'emails_po_purchase_order_1_c',
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
      'name' => 'emails_po_purchase_order_1emails_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'emails_po_purchase_order_1po_purchase_order_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'emails_po_purchase_order_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'emails_po_purchase_order_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'emails_po_purchase_order_1emails_ida',
        1 => 'emails_po_purchase_order_1po_purchase_order_idb',
      ),
    ),
  ),
);