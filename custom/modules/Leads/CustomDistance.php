<?php
//dung code - new logic for button get distance in module quotes
if ($_REQUEST['module'] == 'quotes') {
    $account_id2_c = $_REQUEST['account_id2_c'];
    $accounts = new Account();
    $accounts->retrieve($account_id2_c);
    $from_address = $accounts->billing_address_street . ', ' .$accounts->billing_address_city .', ' 
    .$accounts->billing_address_state .', ' .$accounts->billing_address_postalcode;
    $from_address = html_entity_decode($from_address, ENT_QUOTES);
}else {
    $from_address = html_entity_decode($_REQUEST["address_from"], ENT_QUOTES);
}

$to_address =  html_entity_decode($_REQUEST["address_to"], ENT_QUOTES);
$to_name =  html_entity_decode($_REQUEST["name"], ENT_QUOTES);
$to_nearest =  html_entity_decode($_REQUEST["nearest"], ENT_QUOTES);
$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$from_address."&destination=".$to_address."&key=AIzaSyDcPlmWLNUZ4tbEeisTzu_8cuuxXZrH6H4";
$url =  str_replace(" ", "+", $url);
$geocodeTo = file_get_contents($url);
$geocodeTo = json_decode($geocodeTo);
$geocodeTo->toAddress = $to_address;
    if( $to_name != "" && $to_nearest != "" ){
        $geocodeTo->toName = $to_name;
        $geocodeTo->toNearest = $to_nearest;
    }
$geocodeTo = json_encode($geocodeTo);
echo $geocodeTo;
die();