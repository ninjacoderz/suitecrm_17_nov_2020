<?php
// created: 2020-11-03 03:20:27
$subpanel_layout['list_fields'] = array (
  'number' => 
  array (
    'type' => 'int',
    'vname' => 'Num',
    'width' => '1%',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '50%',
    'default' => true,
  ),
  'supplier_order_number_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_SUPPLIER_ORDER_NUMBER',
    'width' => '10%',
  ),
  'shipping_address_state' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_SHIPPING_ADDRESS_STATE',
    'width' => '10%',
    'default' => true,
  ),
  'freight_company_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_FREIGHT_COMPANY',
    'width' => '10%',
  ),
  'status_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'dispatch_date_c' => 
  array (
    'type' => 'date',
    'default' => true,
    'vname' => 'LBL_DISPATCH_DATE',
    'width' => '10%',
  ),
  'total_amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_GRAND_TOTAL',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'PO_purchase_order',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'PO_purchase_order',
    'width' => '5%',
    'default' => true,
  ),
);