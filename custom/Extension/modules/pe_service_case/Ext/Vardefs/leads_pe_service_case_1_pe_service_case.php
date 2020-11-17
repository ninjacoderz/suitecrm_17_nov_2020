<?php
// created: 2020-06-01 07:01:40
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1"] = array (
  'name' => 'leads_pe_service_case_1',
  'type' => 'link',
  'relationship' => 'leads_pe_service_case_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_pe_service_case_1leads_ida',
);
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1_name"] = array (
  'name' => 'leads_pe_service_case_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_pe_service_case_1leads_ida',
  'link' => 'leads_pe_service_case_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["pe_service_case"]["fields"]["leads_pe_service_case_1leads_ida"] = array (
  'name' => 'leads_pe_service_case_1leads_ida',
  'type' => 'link',
  'relationship' => 'leads_pe_service_case_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
);
