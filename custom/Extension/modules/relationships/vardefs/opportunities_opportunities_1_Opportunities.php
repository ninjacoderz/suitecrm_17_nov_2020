<?php
// created: 2017-05-09 13:31:10
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1"] = array (
  'name' => 'opportunities_opportunities_1',
  'type' => 'link',
  'relationship' => 'opportunities_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE',
  'id_name' => 'opportunities_opportunities_1opportunities_ida',
);
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1_name"] = array (
  'name' => 'opportunities_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE',
  'save' => true,
  'id_name' => 'opportunities_opportunities_1opportunities_ida',
  'link' => 'opportunities_opportunities_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["opportunities_opportunities_1opportunities_ida"] = array (
  'name' => 'opportunities_opportunities_1opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_R_TITLE',
);
