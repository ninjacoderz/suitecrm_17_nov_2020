<?php

$quote_id = $_GET['quote_solorgain'];
 $assined_name = $_GET['assigned_name'];
    if(  $assined_name == "Paul Szuster"){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else {
        $username = "paul.szuster@solargain.com.au";
        $password = "Baited@42";
    };

//1. login and get json quotes
$url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$result,
        "Cache-Control: max-age=0"
    )
);

$quote = curl_exec($curl);
$quote_decode = json_decode($quote);
    if( $assined_name == "Matthew Wright"){
        $assigneduser = array(
            "ID" => 475,
            "Name" => "Matthew Wright",
            "Enabled"=>false,
            "Administrator"=>false,
            "IsDealership"=>false
        );
    }else if( $assined_name == "Paul Szuster"){
        $assigneduser = array(
            "ID" => 730,
            "Name" => "Paul Szuster",
            "Enabled"=>false,
            "Administrator"=>false,
            "IsDealership"=>false
        );
    }
$quote_decode->QuotedByUser = $assigneduser;

$data_string = json_encode($quote_decode);

$url = "https://crm.solargain.com.au/APIv2/quotes/";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Content-Type: application/json";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);
    curl_close($curl);

?>


