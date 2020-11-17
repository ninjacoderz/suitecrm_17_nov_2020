<?php
// created: 2018-12-05 20:14:27
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_warehouse_log_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1_name"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
  'link' => 'po_purchase_order_pe_warehouse_log_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["po_purchase_order_pe_warehouse_log_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_warehouse_log_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE',
);
