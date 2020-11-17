<?php
// created: 2020-09-08 10:07:50
$dictionary["AOS_Products_Quotes"]["fields"]["po_purchase_order_aos_products_quotes_1"] = array (
  'name' => 'po_purchase_order_aos_products_quotes_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_aos_products_quotes_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_AOS_PRODUCTS_QUOTES_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
);
$dictionary["AOS_Products_Quotes"]["fields"]["po_purchase_order_aos_products_quotes_1_name"] = array (
  'name' => 'po_purchase_order_aos_products_quotes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_AOS_PRODUCTS_QUOTES_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
  'link' => 'po_purchase_order_aos_products_quotes_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["AOS_Products_Quotes"]["fields"]["po_purchase_order_aos_products_quotes_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_aos_products_quotes_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_aos_products_quotes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_AOS_PRODUCTS_QUOTES_1_FROM_AOS_PRODUCTS_QUOTES_TITLE',
);
