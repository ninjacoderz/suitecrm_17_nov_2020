<?php
// created: 2018-12-05 18:20:56
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1',
  'type' => 'link',
  'relationship' => 'pe_warehouse_pe_stock_items_1',
  'source' => 'non-db',
  'module' => 'pe_warehouse',
  'bean_name' => 'pe_warehouse',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
  'id_name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
);
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1_name"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_WAREHOUSE_TITLE',
  'save' => true,
  'id_name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
  'link' => 'pe_warehouse_pe_stock_items_1',
  'table' => 'pe_warehouse',
  'module' => 'pe_warehouse',
  'rname' => 'name',
);
$dictionary["pe_stock_items"]["fields"]["pe_warehouse_pe_stock_items_1pe_warehouse_ida"] = array (
  'name' => 'pe_warehouse_pe_stock_items_1pe_warehouse_ida',
  'type' => 'link',
  'relationship' => 'pe_warehouse_pe_stock_items_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_STOCK_ITEMS_TITLE',
);
