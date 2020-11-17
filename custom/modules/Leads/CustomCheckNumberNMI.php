<?php
$data_post = array(
    'nmi' => trim($_REQUEST['nmi_c']),
    'token' => '',
);
$JSONEncode = json_encode($data_post);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://quote.globirdenergy.com.au/api/Quote/ElectricityQuoteByNmi/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONEncode);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Origin: https://quote.globirdenergy.com.au";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-AU";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
$headers[] = "Content-Type: application/json;charset=UTF-8";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Referer: https://quote.globirdenergy.com.au/yourproperty";
$headers[] = "Cookie: ARRAffinity=1a959afa17406ed97501373f7eae9b2b5eabdf872e5ecd9bbdabdaacb5ff65b8; ai_user=bYibG^|2018-09-26T03:37:17.343Z; _ga=GA1.3.778176486.1537933038; _gid=GA1.3.1988435643.1537933038; ai_session=o/jlN^|1537933038034^|1537933670159.3";
$headers[] = "Connection: keep-alive";
$headers[] = "Request-Id: ^|yH90P.zGkub";
$headers[] = "Request-Context: appId=cid-v1:d4d90ee1-3847-42dd-a744-d8c9dded0a89";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

$result_array = json_decode($result);
$json_result = array(
    'Quote For' => $result_array->electricityQuote->address,
    'Network Distributor' => $result_array->electricityQuote->identifier,
    'NMI' => $result_array->electricityQuote->network
);
echo json_encode($json_result);