<?php
// created: 2017-12-27 14:27:35
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1"] = array (
  'name' => 'aos_invoices_po_purchase_order_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_po_purchase_order_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE',
  'id_name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
);
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1_name"] = array (
  'name' => 'aos_invoices_po_purchase_order_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
  'link' => 'aos_invoices_po_purchase_order_1',
  'table' => 'aos_invoices',
  'module' => 'AOS_Invoices',
  'rname' => 'name',
);
$dictionary["PO_purchase_order"]["fields"]["aos_invoices_po_purchase_order_1aos_invoices_ida"] = array (
  'name' => 'aos_invoices_po_purchase_order_1aos_invoices_ida',
  'type' => 'link',
  'relationship' => 'aos_invoices_po_purchase_order_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
);
