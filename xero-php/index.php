<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
require_once(dirname(__FILE__).'/simple_html_dom.php');
$type = $_REQUEST['type'];
$supplyid = $_REQUEST['supplyID'];
$installid = $_REQUEST['installID'];
$revenue = $_REQUEST['revenueID'];
$stc_number = $_REQUEST['stc_number'];

include 'vendor/autoload.php';
use XeroPHP\Remote\URL;
use XeroPHP\Remote\Request;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Invoice;

// Start a session for the oauth session storage
$key = "
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCiuh3s3vOnvaAWz8TIzNGUtOMG3FqxPVltGQDlxwoXVtsk/ub/
qs5mO8yDwfwBfCAAecMN5Ei7eTrVOFv5TuAEfWWzN9j8x7AUf8rhytz+BgQXwF/a
y0Vbwq7jQU2GeVT2tM4CItaZ0rxFqPmsRTAkC7OnwCnRm1B9CsADuvJCIQIDAQAB
AoGAMQBouIaeyrlQdu4T7P+4cNZTsyIx8UNvJWotGgRo5oRSM37K4tx1kNWbDWYh
0/Sj0mDYOtuuhz3HWKPDFn0I+fYwWWJWRFwveeG9VOfGAqB5cQy3i9T7ytzIez+v
mCGk/ohfgO9yAZwqqkWL+/jXU+JNqFiiMpqcnN2mF/9Tax0CQQDQwDiCmEGV+yCH
e4bS2YaD7uD0UvwQQSJvRxceRXhl2UFTI3cmVTd/6DEBZNkE+kvqyfiB7Q5IoEBU
k+kXXtRPAkEAx48bvrbA2BLDxtHKzElUAsNUKdD1c0zDwRQGTPIVPxfSoNoKu+tP
zCaddApvOhjAfFGRYMmR0yVn6uP41382jwJBALP+mntYx2yIHdNUWrth3s/R4Nwq
1bc6QnPKy49JfXfsbZw/P1SpM/KxBdha2ZmmLGGlhwaYnbFXpECJTPnexZcCQQCi
5PZI3vTba7XTfTyFNPYWq0rwN1mkHG1OFgJunM0rC08rbdCFRLeGdZ7hMgNI8Rtu
X0bEMsWODWKeIijl/zmRAkAyas7vLAgHxA5kjWEgqVzGvJtbE8/PiMjVPOrHjXFp
dfdwKG5J4xHM59PiuX5BwpM3PBbvy0QTgohsPOYIqbeM
-----END RSA PRIVATE KEY-----
";
//These are the minimum settings - for more options, refer to examples/config.php
$config = [
    'oauth' => [
        'callback' => 'http://localhost/',
        'consumer_key' => 'GO4QD0KGVFV9SGJR6VRKYDLOUURFEO',
        'consumer_secret' => '1NKXG74CVQBPC5DGYSLHMNPUHJKQ4U',
        'rsa_private_key' => $key,
    ],
];
$arr =array();
$xero = new PrivateApplication($config);
if( $type == "daikin"){
    $invoice_supplyid = $xero->loadByGUID('Accounting\\Invoice',$supplyid);
    $subTotal_sup = $invoice_supplyid->SubTotal;
    if( $installid != "" ){
    $invoice_installid = $xero->loadByGUID('Accounting\\Invoice',$installid);
    $subTotal_ins = $invoice_installid->SubTotal;
    }else {
        $subTotal_ins ='0.00';
    }
    $invoice_revenue = $xero->loadByGUID('Accounting\\Invoice',$revenue);
    $subTotal_re = $invoice_revenue->SubTotal;
    $arr[] = array("supply" =>$subTotal_sup,"install"=>$subTotal_ins,"revenue"=>$subTotal_re);
    echo json_encode($arr);
}else if($type == "sanden"){
    
    if($stc_number != ''){
        // Login
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $result_data = json_decode($result);
        $accesstoken =  $result_data->AuthenticationResult->AccessToken;
        $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);


        $param = array (
            'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
            'AuthFlow' => 'REFRESH_TOKEN_AUTH',
            'AuthParameters' => 
            array (
            'REFRESH_TOKEN' => $RefreshToken,
            'DEVICE_KEY' => NULL,
            ),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $IdToken =  $result_data->AuthenticationResult->IdToken;

        $curl = curl_init();

        // Call Option method

        $url = 'https://api.geocreation.com.au/api/assignments/';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        //curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(

                "Host: api.geocreation.com.au",
                "User-Agent: ".$_SERVER['HTTP_USER_AGENT'],
                //"Content-Type: application/json",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization,content-type",
                "Connection: keep-alive",
                //"Content-Length: " .strlen($data_string),
                //"Authorization: token ".$accesstoken,
                //"Referer: https://geocreation.com.au/assignments/new",
                //"Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.geocreation.com.au/api/assignments/".$stc_number);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://geocreation.com.au";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Authorization: token ".$IdToken;
        $headers[] = "Accept: */*";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Authority: api.geocreation.com.au";
        $headers[] = "Referer: https://geocreation.com.au/assignments/" .$stc_number ."/edit";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $result = json_decode($result);
        $stc_price =$result->assignment->result->certificateBundles[0]->value;
    }
    //plumning
    $invoice_supplyid = $xero->loadByGUID('Accounting\\Invoice',$supplyid);
    $subTotal_plum = $invoice_supplyid->SubTotal;
    //electrician
    if( $installid != "" ){
        $invoice_installid = $xero->loadByGUID('Accounting\\Invoice',$installid);
        $subTotal_elec = $invoice_installid->SubTotal;
    }else {
        $subTotal_elec ='0.00';
    }
    //////////
    $invoice_revenue = $xero->loadByGUID('Accounting\\Invoice',$revenue);
    $sanden_total = $invoice_revenue->SubTotal/$invoice_revenue->LineItems[0]->Quantity;
    $subTotal_re = $invoice_revenue->SubTotal;
    $arr[] = array("plumbing_bill" =>$subTotal_plum,"electrician_bill"=>$subTotal_elec,"revenue"=>$subTotal_re,"sanden_stc"=>$stc_price,"sanden_bill_pro"=>$sanden_total);
    echo json_encode($arr);
}
