<?php
// created: 2019-03-13 18:24:35
$dictionary["AOS_Quotes"]["fields"]["calls_aos_quotes_1"] = array (
  'name' => 'calls_aos_quotes_1',
  'type' => 'link',
  'relationship' => 'calls_aos_quotes_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_AOS_QUOTES_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_aos_quotes_1calls_ida',
);
$dictionary["AOS_Quotes"]["fields"]["calls_aos_quotes_1_name"] = array (
  'name' => 'calls_aos_quotes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_AOS_QUOTES_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_aos_quotes_1calls_ida',
  'link' => 'calls_aos_quotes_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["AOS_Quotes"]["fields"]["calls_aos_quotes_1calls_ida"] = array (
  'name' => 'calls_aos_quotes_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_aos_quotes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
);
