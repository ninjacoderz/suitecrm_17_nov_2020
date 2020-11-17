<?php
// created: 2020-05-25 10:58:38
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1"] = array (
  'name' => 'aos_invoices_pe_service_case_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_AOS_INVOICES_TITLE',
  'id_name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
);
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1_name"] = array (
  'name' => 'aos_invoices_pe_service_case_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_AOS_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
  'link' => 'aos_invoices_pe_service_case_1',
  'table' => 'aos_invoices',
  'module' => 'AOS_Invoices',
  'rname' => 'name',
);
$dictionary["pe_service_case"]["fields"]["aos_invoices_pe_service_case_1aos_invoices_ida"] = array (
  'name' => 'aos_invoices_pe_service_case_1aos_invoices_ida',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
);
