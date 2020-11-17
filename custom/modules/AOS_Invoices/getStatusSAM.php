<?php
$sg_order_number = trim($_GET['number_order']);
if($sg_order_number != ''){
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
    $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
    $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);
    $sg_order_json = json_decode($result);
    echo $sg_order_json->PVStatus;
}