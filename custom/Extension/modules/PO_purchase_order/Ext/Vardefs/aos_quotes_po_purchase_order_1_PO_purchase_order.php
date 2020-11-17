<?php
// created: 2017-12-25 05:36:06
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1"] = array (
  'name' => 'aos_quotes_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'aos_quotes_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'AOS_Quotes',
  'bean_name' => 'AOS_Quotes',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE',
  'id_name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
);
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1_name"] = array (
  'name' => 'aos_quotes_po_purchase_order_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE',
  'save' => true,
  'id_name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
  'link' => 'aos_quotes_po_purchase_order_1',
  'table' => 'aos_quotes',
  'module' => 'AOS_Quotes',
  'rname' => 'name',
);
$dictionary["PO_purchase_order"]["fields"]["aos_quotes_po_purchase_order_1aos_quotes_ida"] = array (
  'name' => 'aos_quotes_po_purchase_order_1aos_quotes_ida',
  'type' => 'link',
  'relationship' => 'aos_quotes_po_purchase_order_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
);
