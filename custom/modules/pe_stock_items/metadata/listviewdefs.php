<?php
$module_name = 'pe_stock_items';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'SERIAL_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRODUCT_SERIAL_NUMBER',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'PARENT_NAME' => 
  array (
    'type' => 'parent',
    'studio' => 'visible',
    'label' => 'LBL_FLEX_RELATE',
    'link' => true,
    'sortable' => false,
    'ACLTag' => 'PARENT',
    'dynamic_module' => 'PARENT_TYPE',
    'id' => 'PARENT_ID',
    'related_fields' => 
    array (
      0 => 'parent_id',
      1 => 'parent_type',
    ),
    'width' => '10%',
    'default' => false,
  ),
  'PRODUCT_COST_PRICE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_PRODUCT_COST_PRICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => false,
  ),
  'INVOICE_C' => 
  array (
    'type' => 'relate',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_INVOICE',
    'id' => 'AOS_INVOICES_ID_C',
    'link' => true,
    'width' => '10%',
  ),
);
;
?>
