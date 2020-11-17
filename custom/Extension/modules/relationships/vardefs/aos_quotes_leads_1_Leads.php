<?php
// created: 2019-03-05 21:01:41
$dictionary["Lead"]["fields"]["aos_quotes_leads_1"] = array (
  'name' => 'aos_quotes_leads_1',
  'type' => 'link',
  'relationship' => 'aos_quotes_leads_1',
  'source' => 'non-db',
  'module' => 'AOS_Quotes',
  'bean_name' => 'AOS_Quotes',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE',
  'id_name' => 'aos_quotes_leads_1aos_quotes_ida',
);
$dictionary["Lead"]["fields"]["aos_quotes_leads_1_name"] = array (
  'name' => 'aos_quotes_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE',
  'save' => true,
  'id_name' => 'aos_quotes_leads_1aos_quotes_ida',
  'link' => 'aos_quotes_leads_1',
  'table' => 'aos_quotes',
  'module' => 'AOS_Quotes',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["aos_quotes_leads_1aos_quotes_ida"] = array (
  'name' => 'aos_quotes_leads_1aos_quotes_ida',
  'type' => 'link',
  'relationship' => 'aos_quotes_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE',
);
