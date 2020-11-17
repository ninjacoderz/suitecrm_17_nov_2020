<?php
// created: 2020-09-08 10:07:50
$dictionary["po_purchase_order_aos_products_quotes_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'po_purchase_order_aos_products_quotes_1' => 
    array (
      'lhs_module' => 'PO_purchase_order',
      'lhs_table' => 'po_purchase_order',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Products_Quotes',
      'rhs_table' => 'aos_products_quotes',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'po_purchase_order_aos_products_quotes_1_c',
      'join_key_lhs' => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
      'join_key_rhs' => 'po_purchase_order_aos_products_quotes_1aos_products_quotes_idb',
    ),
  ),
  'table' => 'po_purchase_order_aos_products_quotes_1_c',
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
      'name' => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'po_purchase_order_aos_products_quotes_1aos_products_quotes_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'po_purchase_order_aos_products_quotes_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'po_purchase_order_aos_products_quotes_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'po_purchase_order_aos_products_quotes_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'po_purchase_order_aos_products_quotes_1aos_products_quotes_idb',
      ),
    ),
  ),
);