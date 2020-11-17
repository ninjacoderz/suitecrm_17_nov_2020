<?php
// created: 2019-03-05 21:01:41
$dictionary["AOS_Quotes"]["fields"]["aos_quotes_leads_1"] = array (
  'name' => 'aos_quotes_leads_1',
  'type' => 'link',
  'relationship' => 'aos_quotes_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'aos_quotes_leads_1leads_idb',
);
$dictionary["AOS_Quotes"]["fields"]["aos_quotes_leads_1_name"] = array (
  'name' => 'aos_quotes_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'aos_quotes_leads_1leads_idb',
  'link' => 'aos_quotes_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["AOS_Quotes"]["fields"]["aos_quotes_leads_1leads_idb"] = array (
  'name' => 'aos_quotes_leads_1leads_idb',
  'type' => 'link',
  'relationship' => 'aos_quotes_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE',
);
