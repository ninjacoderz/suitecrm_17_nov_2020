<?php
// created: 2019-03-13 18:27:33
$dictionary["Account"]["fields"]["calls_accounts_1"] = array (
  'name' => 'calls_accounts_1',
  'type' => 'link',
  'relationship' => 'calls_accounts_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_accounts_1calls_ida',
);
$dictionary["Account"]["fields"]["calls_accounts_1_name"] = array (
  'name' => 'calls_accounts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_accounts_1calls_ida',
  'link' => 'calls_accounts_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["Account"]["fields"]["calls_accounts_1calls_ida"] = array (
  'name' => 'calls_accounts_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_accounts_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
);
