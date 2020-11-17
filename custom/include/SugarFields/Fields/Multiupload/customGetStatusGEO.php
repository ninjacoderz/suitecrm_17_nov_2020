<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');

//dung code- button get stauts geo in listview invoice
if(count($_POST) >0){

    $list_id = $_POST['list_id'];
    for($i=0; $i<count($list_id); $i++){
        $bean_invoice = new AOS_Invoices();
        $bean_invoice = $bean_invoice->retrieve($list_id[$i]);
        get_status_GEO($bean_invoice);
    }
    die();
}else{
    $stc_number =  urldecode($_GET['stc_seri']);
    get_status_GEO('',$stc_number);
}

function get_status_GEO($bean = '', $stc_number= ''){
    $curl = curl_init();
    $tmpfname = dirname(__FILE__).'/cookiegeo.txt';

    if($bean !== ''){
        $stc_number = $bean->stc_aggregator_serial_c;
        if($stc_number == '') return;    
    }

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
    $status_geo = $result->assignment->result->status;
    if($bean !== ''){
        if((trim($status_geo) == 'complete' || trim($status_geo) == 'received') && $status_geo != $bean->status_geo_c){
            $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND deleted = 0";
            $result_sql = $bean->db->query($sql);
            while ($row = $bean->db->fetchByAssoc($result_sql)) {
                if(isset($row) && $row != null ){
                    if($row["product_id"] == "431a9064-7cbb-6a44-e7ba-5d5b794137c7"){
                        $bean->status = 'Solar_VIC_Unpaid';
                    } 
                }
            }
        }
        $bean->status_geo_c = $status_geo;
        $bean->save();   
    }else{
        $status_geo = $result->assignment->result->status;
        echo $status_geo;
    }

}