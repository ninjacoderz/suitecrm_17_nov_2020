<?php
// created: 2020-04-21 07:51:29
$dictionary["pe_service_case"]["fields"]["pe_service_case_pe_message_servicecase_1"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'module' => 'pe_message_servicecase',
  'bean_name' => 'pe_message_servicecase',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
  'id_name' => 'pe_service7c1eicecase_idb',
);
$dictionary["pe_service_case"]["fields"]["pe_service_case_pe_message_servicecase_1_name"] = array (
  'name' => 'pe_service_case_pe_message_servicecase_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
  'save' => true,
  'id_name' => 'pe_service7c1eicecase_idb',
  'link' => 'pe_service_case_pe_message_servicecase_1',
  'table' => 'pe_message_servicecase',
  'module' => 'pe_message_servicecase',
  'rname' => 'name',
);
$dictionary["pe_service_case"]["fields"]["pe_service7c1eicecase_idb"] = array (
  'name' => 'pe_service7c1eicecase_idb',
  'type' => 'link',
  'relationship' => 'pe_service_case_pe_message_servicecase_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_PE_SERVICE_CASE_PE_MESSAGE_SERVICECASE_1_FROM_PE_MESSAGE_SERVICECASE_TITLE',
);
