<?php

$sg_order_number = trim($_GET['sg_order_number']);
// $sg_order_number = "17673"; // ok 17673   no FB 52881 no Oder 69618
// $sg_order_number = "52881";



if($sg_order_number == '') die();
/**Log in account Matthew */
$username = "matthew.wright";
$password =  "MW@pure733";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Referer: https://crm.solargain.com.au/order/edit/$sg_order_number";
$headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
$headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
$headers[] = "Connection: keep-alive";
$headers[] = "Cache-Control: no-cache";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close ($ch);
$json_result = json_decode($result);

/**Log in account Paul */
if(!isset($json_result->ID)) {
    $username = 'paul.szuster@solargain.com.au';
    $password = 'Baited@42';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Referer: https://crm.solargain.com.au/order/edit/$sg_order_number";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);
    $json_result = json_decode($result);
}


$data_return = array (
    'formbayID'=> $json_result->FormBayID,
    'message'=> $json_result->ExceptionMessage,
    'user' => $json_result->Quote->QuotedByUser->Name,
    );
echo json_encode($data_return);
