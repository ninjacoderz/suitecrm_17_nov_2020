<?php
// created: 2020-09-28 07:09:11
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'PO_purchase_order',
  'bean_name' => 'PO_purchase_order',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'id_name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
);
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1_name"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'save' => true,
  'id_name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
  'link' => 'po_purchase_order_pe_internal_note_1',
  'table' => 'po_purchase_order',
  'module' => 'PO_purchase_order',
  'rname' => 'name',
);
$dictionary["pe_internal_note"]["fields"]["po_purchase_order_pe_internal_note_1po_purchase_order_ida"] = array (
  'name' => 'po_purchase_order_pe_internal_note_1po_purchase_order_ida',
  'type' => 'link',
  'relationship' => 'po_purchase_order_pe_internal_note_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PO_PURCHASE_ORDER_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);
