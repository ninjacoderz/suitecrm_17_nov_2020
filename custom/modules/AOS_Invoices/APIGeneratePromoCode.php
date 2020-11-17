<?php
$invoiceID = $_REQUEST['invoiceID'];
$invoiceNum = $_REQUEST['invoiceNum'];
$promo_1 = $_REQUEST['promo_1'];
$promo_2 = $_REQUEST['promo_2'];
$promo_3 = $_REQUEST['promo_3'];

$url = "https://pure-electric.com.au/pepromotion/APIv1?invoiceID=$invoiceID&invoiceNum=$invoiceNum&method=create";
$url .= '&promo_1='.$promo_1;
$url .= '&promo_2='.$promo_2;
$url .= '&promo_3='.$promo_3;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Connection: keep-alive";
$headers[] = "Cache-Control: no-cache";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
$data_json = json_decode($result,true);
$invoice = new AOS_Invoices();
$invoice->retrieve($invoiceID);
if($invoice->id != ''){
    $invoice->handheld_1_c =   $data_json['code1'];
    $invoice->handheld_2_c =   $data_json['code2'];
    $invoice->handheld_3_c =   $data_json['code3'];
    $invoice->save();
}
curl_close ($ch);
echo $result;