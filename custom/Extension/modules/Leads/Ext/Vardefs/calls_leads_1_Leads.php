<?php
// created: 2019-03-13 18:40:52
$dictionary["Lead"]["fields"]["calls_leads_1"] = array (
  'name' => 'calls_leads_1',
  'type' => 'link',
  'relationship' => 'calls_leads_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_LEADS_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_leads_1calls_ida',
);
$dictionary["Lead"]["fields"]["calls_leads_1_name"] = array (
  'name' => 'calls_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_LEADS_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_leads_1calls_ida',
  'link' => 'calls_leads_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["calls_leads_1calls_ida"] = array (
  'name' => 'calls_leads_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_LEADS_1_FROM_LEADS_TITLE',
);
