<?php
$aupost_shipping_id = $_REQUEST['aupost_shipping_id'];
if(trim($aupost_shipping_id) == ''){
    echo 'error';die();
}else{
    echo get_connote_id($aupost_shipping_id);
}

function get_connote_id ($aupost_shipping_id){
     //auto create shipments auspost
     $tmpfname = dirname(__FILE__).'/cookie.auspost.txt';
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/cssoapi/v2/session');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"accounts@pure-electric.com.au","password":"aPureandTrue2018*"}');
     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
     $headers = array();
     $headers[] = 'Connection: keep-alive';
     $headers[] = 'Pragma: no-cache';
     $headers[] = 'Cache-Control: no-cache';
     $headers[] = 'Accept: application/json, text/plain, */*';
     $headers[] = 'Origin: https://auspost.com.au';
     $headers[] = 'Ap_app_id: MYPOST';
     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
     $headers[] = 'Content-Type: application/json';
     $headers[] = 'Sec-Fetch-Site: same-site';
     $headers[] = 'Sec-Fetch-Mode: cors';
     $headers[] = 'Referer: https://auspost.com.au/mypost-business/auth/';
     $headers[] = 'Accept-Encoding: gzip, deflate, br';
     $headers[] = 'Accept-Language: en-US,en;q=0.9';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     curl_close($ch);

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/accessone/v1/session');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
     $headers = array();
     $headers[] = 'Connection: keep-alive';
     $headers[] = 'Pragma: no-cache';
     $headers[] = 'Cache-Control: no-cache';
     $headers[] = 'Accept: application/json, text/plain, */*';
     $headers[] = 'Origin: https://auspost.com.au';
     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
     $headers[] = 'Sec-Fetch-Site: same-site';
     $headers[] = 'Sec-Fetch-Mode: cors';
     $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
     $headers[] = 'Accept-Encoding: gzip, deflate, br';
     $headers[] = 'Accept-Language: en-US,en;q=0.9';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

     $result = curl_exec($ch);
     curl_close($ch);

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
     curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
     curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
     $headers = array();
     $headers[] = 'Connection: keep-alive';
     $headers[] = 'Pragma: no-cache';
     $headers[] = 'Cache-Control: no-cache';
     $headers[] = 'Access-Control-Request-Method: POST';
     $headers[] = 'Origin: https://auspost.com.au';
     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
     $headers[] = 'Access-Control-Request-Headers: account-number,auspost-partner-id,content-type';
     $headers[] = 'Accept: */*';
     $headers[] = 'Sec-Fetch-Site: same-site';
     $headers[] = 'Sec-Fetch-Mode: cors';
     $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
     $headers[] = 'Accept-Encoding: gzip, deflate, br';
     $headers[] = 'Accept-Language: en-US,en;q=0.9';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     curl_close($ch);


    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments?shipment_ids='.$aupost_shipping_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);

    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36';
    $headers[] = 'Auspost-Partner-Id: SENDAPARCEL-UI';
    $headers[] = 'Origin: https://auspost.com.au';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'Account-Number: 62ff9f94f4534eb3b93080c9a3edcd9c';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://auspost.com.au/';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $json_result = json_decode($result);
    $connote_id = $json_result->shipments[0]->items[0]->tracking_details->article_id;
    return $connote_id;
}