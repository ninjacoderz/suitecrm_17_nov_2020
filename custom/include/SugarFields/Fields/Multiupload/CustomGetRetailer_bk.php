<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 7/4/17
 * Time: 6:04 PM
 */

date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

header('Content-Type: application/json; charset=utf-8');
header('Content-Encoding: gzip');
$nmi = $_GET['nmi'];
if( isset($nmi)  && $nmi != '' ){
    $curl = curl_init();

    $array_post = array(
        "token" => "",
        "nmi" => $nmi,
    );
    $addressJSONEncode = json_encode($array_post);
    $url = "https://signup.globirdenergy.com.au/api/Quote/Nmi/";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $addressJSONEncode);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: signup.globirdenergy.com.au",
            "Origin:https://signup.globirdenergy.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: 	gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($addressJSONEncode),
            "Referer: https://signup.globirdenergy.com.au/yourproperty",
        )
    );

    $result = curl_exec($curl);
    echo $result ;
    curl_close ( $curl );

    die();
}

$curl = curl_init();
$address = $_GET['address'];
$address = explode("/", $address);
$address_array = array();
if(count($address) == 0) return;
foreach($address as $add){
    $adds = explode("=",$add);
    if(count($adds == 2))
        $address_array[$adds[0]] = $adds[1];
}

// GET NMI
$addresses = explode("/", $address);
$array_post = array(
    "token" => "",
    "address" => array(
        "stateOrTerritory" => $address_array['state'],
        "buildingOrPropertyName" => "",
        "flatOrUnitNumber" => $address_array['unit_num'],
        "flatOrUnitType" => $address_array['unit'],
        "floorOrLevelNumber" => "",
        "houseNumber" => $address_array['streetNumber'],
        "streetName" => strtoupper($address_array['streetName']),
        "streetType" => "",
        "streetSuffix" => "",
        "postcode" => $address_array["postcode"],
        "suburbOrPlaceOrLocality" => $address_array['city'],
        "lotNumber" => ""
    )
);
$addressJSONEncode = json_encode($array_post);
$url = "https://signup.globirdenergy.com.au/api/Quote/AddressSearch/";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $addressJSONEncode);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: signup.globirdenergy.com.au",
        "Origin:https://signup.globirdenergy.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($addressJSONEncode),
        "Referer: https://signup.globirdenergy.com.au/yourproperty",
    )
);

$result = curl_exec($curl);
echo $result ;
curl_close ( $curl );
die();

// Using Originery
$address = str_replace("/","&", $address);
$url = "https://plans.api.odcdn.com.au/api/v1/plans?".$address;
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0); 
curl_setopt($curl, CURLOPT_TIMEOUT, 400);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: plans.api.odcdn.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Referer: https://www.originenergy.com.au/for-home/electricity-and-gas/plans/energy-plans.html",
        "Origin: https://www.originenergy.com.au",

        "Connection: keep-alive",
    )
);

$result = curl_exec($curl);
print( $result);

die();