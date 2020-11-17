<?php
// created: 2020-04-14 03:46:00
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_pe_service_case_1"] = array (
  'name' => 'aos_invoices_pe_service_case_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'module' => 'pe_service_case',
  'bean_name' => 'pe_service_case',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'id_name' => 'aos_invoices_pe_service_case_1pe_service_case_idb',
);
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_pe_service_case_1_name"] = array (
  'name' => 'aos_invoices_pe_service_case_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_pe_service_case_1pe_service_case_idb',
  'link' => 'aos_invoices_pe_service_case_1',
  'table' => 'pe_service_case',
  'module' => 'pe_service_case',
  'rname' => 'name',
);
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_pe_service_case_1pe_service_case_idb"] = array (
  'name' => 'aos_invoices_pe_service_case_1pe_service_case_idb',
  'type' => 'link',
  'relationship' => 'aos_invoices_pe_service_case_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
);
