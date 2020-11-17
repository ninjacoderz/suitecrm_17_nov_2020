<?php
// created: 2017-05-02 16:55:21
$dictionary["AOS_Invoices"]["fields"]["opportunities_aos_invoices_1"] = array (
  'name' => 'opportunities_aos_invoices_1',
  'type' => 'link',
  'relationship' => 'opportunities_aos_invoices_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_aos_invoices_1opportunities_ida',
);
$dictionary["AOS_Invoices"]["fields"]["opportunities_aos_invoices_1_name"] = array (
  'name' => 'opportunities_aos_invoices_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportunities_aos_invoices_1opportunities_ida',
  'link' => 'opportunities_aos_invoices_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["AOS_Invoices"]["fields"]["opportunities_aos_invoices_1opportunities_ida"] = array (
  'name' => 'opportunities_aos_invoices_1opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_aos_invoices_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE',
);
