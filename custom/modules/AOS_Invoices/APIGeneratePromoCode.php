<?php
$invoiceID = $_REQUEST['invoiceID'];
$invoiceNum = $_REQUEST['invoiceNum'];
$promo_1 = $_REQUEST['promo_1'];
$promo_2 = $_REQUEST['promo_2'];
$promo_3 = $_REQUEST['promo_3'];
$method = $_REQUEST['method'];
if($method == 'customize'){
    $fields = $_REQUEST;
    $url = "https://pure-electric.com.au/pepromotion/APIv1?method=customize";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);//count($fields)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
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
    GenerateJsonPromoCodeCustom ($data_json,$fields);
    curl_close ($ch);
    echo $result;
}else{
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
}

function GenerateJsonPromoCodeCustom ($data_in,$fields){
    if($data_in['message'] == 'Generate Promo Code Success!') {
        $invoice = new AOS_Invoices();
        $invoice->retrieve($fields['invoiceID']);
        if($invoice->id != ''){
            $json_promo_code_custom_c = $invoice->json_promo_code_custom_c;
            if($json_promo_code_custom_c == ''){
                 $json_promo_code_custom_c = [];
            }else{
                 $json_promo_code_custom_c = json_decode(str_replace("&quot;",'"',$invoice->json_promo_code_custom_c),true);
            }
            
            $data_insert = [   
                'offer_type_promotion'=> $fields['offer_type_promotion'],
                'name_promotion'=> $fields['name_promotion'],
                'amount_off_promotion'=> $fields['amount_off_promotion'],
                'percentage_off_promotion'=> $fields['percentage_off_promotion'],
                'promo_code'=> $data_in['code_customize']
             ] ;
             $json_promo_code_custom_c[] = $data_insert;
             $invoice->json_promo_code_custom_c = json_encode($json_promo_code_custom_c);
             $invoice->save();
        }
    }
};