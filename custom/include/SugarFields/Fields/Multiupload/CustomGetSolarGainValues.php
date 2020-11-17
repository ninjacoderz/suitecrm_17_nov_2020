<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
$record = urldecode($_GET['record']) ;

$db = DBManagerFactory::getInstance();
$ret = $db->query(
    "
    SELECT leads.id FROM `opportunities` 
    LEFT JOIN accounts_opportunities ON accounts_opportunities.opportunity_id = opportunities.id
    LEFT JOIN leads ON leads.account_id = accounts_opportunities.account_id
    WHERE opportunities.id LIKE '".$record."'"

);
while ( $row = $db->fetchByAssoc($ret) ) {
    $lead = new Lead();
    $lead = $lead->retrieve($row['id']);
    echo '{"lead":'.$lead->solargain_lead_number_c.',"quote":'.$lead->solargain_quote_number_c.'}';
}

die();