<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

$postcode = $_GET['post_code'];
$partnumber = $_GET['part_number'];
$response_array = get_value_stc_veec($postcode, $partnumber);
echo json_encode($response_array);
die;
//FUNCTION
function get_value_stc_veec ($postalcode,$partNumbers){

    $curl = curl_init();
    $url = 'https://www.rec-registry.gov.au/';

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);

    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);


    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);

    $result = curl_exec($curl);

    $url = 'https://www.rec-registry.gov.au/rec-registry/app/calculators/swh-stc-calculator';



    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);

    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);


    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: www.rec-registry.gov.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],

            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.8",
            "Accept-Encoding: gzip, deflate, br",
            "Referer: https://www.rec-registry.gov.au/rec-registry/app/home",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests:1",
        )
    );

    $result = curl_exec($curl);
    $html = str_get_html($result);
    if($scrf = $html->find('meta[name="_csrf"]')[0] == null) {
        $stcs_number = 32;
        $response_array["stcs_number"] = $stcs_number;
    } else {
        $scrf = $html->find('meta[name="_csrf"]')[0]->getAttribute("content");

        $fields = array();
        $fields['postcode'] = $postalcode;//'2000';
        $fields['systemBrand'] = 'Sanden';
        $fields['systemModel'] = $partNumbers;
        $fields['installationDate'] = date('Y-m-d').'T00:00:00.000Z';
        $post_field = json_encode($fields);
        $url = "https://www.rec-registry.gov.au/rec-registry/app/calculators/swh/stc";
    
        curl_setopt($curl, CURLOPT_URL, $url);
    
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, false);
    
        curl_setopt($curl, CURLOPT_POSTFIELDS,  $post_field);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    
                "Host: www.rec-registry.gov.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json; charset=UTF-8",
                "Accept: application/json, text/javascript, */*; q=0.01",
                "Accept-Language: en-US,en;q=0.8",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($post_field),
                "Origin: https://www.rec-registry.gov.au",
                "Referer: https://www.rec-registry.gov.au/rec-registry/app/calculators/swh-stc-calculator",
                "X-CSRF-TOKEN: ".$scrf,
                "X-Requested-With: XMLHttpRequest"
            )
        );
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result);
    
        //print($result);
        if(isset($result->status) && $result->status =="Completed"){
            $stcs_number = $result->result->numStc;
            $response_array["stcs_number"] = $stcs_number;
        }
    }
    
    // VEEC Number ===============

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $fields = array();
    $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
    $data = dlPage($url, $fields);

    $html = str_get_html($data);

    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }

    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate'] = date('d/m/Y');
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$hidParam'] = '{&quot;data&quot;:&quot;12|#|#&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$State'] = '{&quot;rawValue&quot;:&quot;'.(time ()*1000).'&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDDState']= '{&quot;windowsState&quot;:&quot;0:0:-1:350:-45:1:0:0:1:0:0:0&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDD$C']= '{&quot;visibleDate&quot;:&quot;'.date('m/d/Y').'&quot;,&quot;selectedDates&quot;:[&quot;'.date('m/d/Y').'&quot;]}';

    $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';

    $curl = curl_init($url);
    //set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

    // Step 2

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules';
    //1E - Water Heating - Electric Boosted Solar Replacing Electric
    // $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '652';
    //thienpb fix update
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '818';



    $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
    //set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

    // Step 3

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';



    $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
    //set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);
    //print $html;

    // Step 4

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }

    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$ExtraButton2';
    $fields['__EVENTARGUMENT'] = 'Click';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue351'] = $partNumbers;
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue352'] = $postalcode;
    $fields['ctl00$ctl00$ContentPlaceHolder1$ExtraButton2'] = 'Calculate';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';
    // $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue4107'] = 'Large';
    //thienpb fix update
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue7026'] = 'Medium';

    $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
    //set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    set_time_limit(0); 
    $result = curl_exec($curl);
    curl_close($curl);
    if($result == false) {
        $response_array["eligible_veecs"][$_GET['part_number']] = 35;
    } else {
        $html_inside = str_get_html($result);

        $quantites = $html_inside->find('#ContentPlaceHolder1_Content_Editor_VEECQuantityBox td');
        if(isset($quantites[1]) && $quantites[1]->plaintext!= "") $response_array["eligible_veecs"][$_GET['part_number']] = $quantites[1]->plaintext;
    }


    // STC + VEEC price

    $tmpfname = dirname(__FILE__).'/cookie.geocreation.txt';
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
    $url = 'https://api.geocreation.com.au/api/c1/price_feeds/';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    //curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: api.geocreation.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json; charset=utf-8",
        "Accept: */*",
        "Accept-Language: en-US,en;q=0.5",
        "Connection: keep-alive",
        "Authorization: token ".$IdToken,
        "Referer: https://geocreation.com.au/assignments/new",
        "Origin: https://geocreation.com.au",
    )
    );
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    $result_json = json_decode($result);
    // foreach($result_json->result as $re){
    //     $response_array[$re->reference] = $re->currentPrice;
    // }

    //Thienpb update code get price.
    foreach($result_json->priceFeed as $re){
        $response_array[$re->reference] = $re->currentPrice;
    }

    return $response_array;
}

function dlPage($href, $fields) {
    $fields_string = '';
    if (count($fields)) {
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, count($fields) ? count($fields) : 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $str = curl_exec($curl);
    curl_close($curl);
    return $str;
}
