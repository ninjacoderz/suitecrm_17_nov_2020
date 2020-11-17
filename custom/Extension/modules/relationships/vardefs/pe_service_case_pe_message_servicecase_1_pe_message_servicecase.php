<?php
// created: 2020-04-21 07:51:29
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'module' => 'pe_service_case',
  'bean_name' => 'pe_service_case',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'id_name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
);
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1_name"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'save' => true,
  'id_name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
  'link' => 'pe_service_case_pe_message_servicecase_1',
  'table' => 'pe_service_case',
  'module' => 'pe_service_case',
  'rname' => 'name',
);
$dictionary["pe_message_servicecase"]["fields"]["pe_service_case_pe_message_servicecase_1pe_service_case_ida"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1pe_service_case_ida',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_SERVICE_CASE_TITLE',
);
