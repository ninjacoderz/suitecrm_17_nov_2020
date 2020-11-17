<?php
// created: 2018-07-19 08:35:42
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse',
  'source' => 'non-db',
  'module' => 'pe_warehouse',
  'bean_name' => 'pe_warehouse',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE',
  'id_name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehouse_name"] = array (
  'name' => 'pe_warehouse_log_pe_warehouse_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
  'link' => 'pe_warehouse_log_pe_warehouse',
  'table' => 'pe_warehouse',
  'module' => 'pe_warehouse',
  'rname' => 'name',
);
$dictionary["pe_warehouse_log"]["fields"]["pe_warehouse_log_pe_warehousepe_warehouse_ida"] = array (
  'name' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_log_pe_warehouse',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE',
);
