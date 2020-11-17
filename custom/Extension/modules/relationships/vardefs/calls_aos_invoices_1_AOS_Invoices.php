<?php
// created: 2019-03-13 18:27:05
$dictionary["AOS_Invoices"]["fields"]["calls_aos_invoices_1"] = array (
  'name' => 'calls_aos_invoices_1',
  'type' => 'link',
  'relationship' => 'calls_aos_invoices_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_AOS_INVOICES_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_aos_invoices_1calls_ida',
);
$dictionary["AOS_Invoices"]["fields"]["calls_aos_invoices_1_name"] = array (
  'name' => 'calls_aos_invoices_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_AOS_INVOICES_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_aos_invoices_1calls_ida',
  'link' => 'calls_aos_invoices_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["AOS_Invoices"]["fields"]["calls_aos_invoices_1calls_ida"] = array (
  'name' => 'calls_aos_invoices_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_aos_invoices_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE',
);
