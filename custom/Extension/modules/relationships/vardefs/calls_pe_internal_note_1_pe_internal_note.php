<?php
// created: 2020-07-23 04:06:58
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1"] = array (
  'name' => 'calls_pe_internal_note_1',
  'type' => 'link',
  'relationship' => 'calls_pe_internal_note_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_pe_internal_note_1calls_ida',
);
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1_name"] = array (
  'name' => 'calls_pe_internal_note_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_pe_internal_note_1calls_ida',
  'link' => 'calls_pe_internal_note_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["pe_internal_note"]["fields"]["calls_pe_internal_note_1calls_ida"] = array (
  'name' => 'calls_pe_internal_note_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_pe_internal_note_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
);
