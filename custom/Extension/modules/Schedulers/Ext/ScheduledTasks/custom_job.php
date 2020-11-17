<?php

array_push($job_strings, 'custom_job');

function custom_job()
{

    $folder = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/assignment/';
    $file_array = scandir($folder);
    if(count($file_array) == 2 ) return true;

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    require_once( dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/'.'simple_html_dom.php');

    $curl = curl_init();
    $tmpfname = dirname(__FILE__).'/cookiegeo.txt';

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


    foreach ($file_array as $file) {
        if (!is_dir($file)) {
            $assignment = "WH-170037883";//file_get_contents($folder.$file);
            //https://geocreation.com.au/assignments/SH-170002368/edit/summary
            $url = "https://geocreation.com.au/assignments/".$assignment."/edit/summary";
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);

            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                    "Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                )
            );
            $result = curl_exec($curl);
            $html = str_get_html($result);
            $data_script = $html->find('script#data')[0]->innertext;
            $assignment_object = json_decode($data_script,true);
            $assignment_info = $assignment_object[$assignment]['assignment']['result']['certificateBundles'][0];
            if($assignment_info != null){
                $value = $assignment_info['value'];
                $price = $assignment_info['dealBundle']['claims'][0]['paymentTerms']['price'];
                $quantity = $assignment_info['dealBundle']['claims'][0]['quantity'];
                $rebate_type = "veec";
                if($assignment_info['certificateType'] == "STC"){
                    $rebate_type = "stc";
                }

                $GLOBALS['log']->info("Value: $value; Price: $price; Quantity: $quantity; Rebate: $rebate_type");

                $db = DBManagerFactory::getInstance();
                $sql = "SELECT * FROM aos_invoices_cstm WHERE stc_aggregator_serial_c = '".$assignment."' OR stc_aggregator_serial_2_c = '".$assignment."'";
                $ret = $db->query($sql);

                while ($row = $db->fetchByAssoc($ret)) {
                    if (isset($row) && $row != null) {
                        // the CURL need to have
                        // 1 the rebate type
                        // 2 the price
                        // 3 quantity
                        // 4 the xero invoice
                        // 5 the veec rebate
                        // 6 the stc rebate

                        // update xero invoice with rebate price // maybe need calling CURL
                        // 1 Login
                        $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
                        $fields = array();
                        $fields['user_name'] = 'admin';
                        $fields['username_password'] = 'pureandtrue2020*';
                        $fields['module'] = 'Users';
                        $fields['action'] = 'Authenticate';

                        $url = 'https://suitecrm.pure-electric.com.au';
                        $curl = curl_init();

                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

                        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);

                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
                        $result = curl_exec($curl);
                        // 2 Calling CURL
                        $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateXeroInvoice&invoice=1&method=put&record=".$row["id_c"]."&rebate_type=".$rebate_type;
                        $source .= "&rebate_price=".$price;
                        $source .= "&quantity=".$quantity;
                        if(isset($row['xero_invoice_c'])&& $row['xero_invoice_c'] != ""){
                            $source .= "&xero_invoice=".$row['xero_invoice_c'];
                        }
                        if(isset($row['xero_veec_rebate_invoice_c'])&& $row['xero_veec_rebate_invoice_c'] != ""){
                            $source .= "&rebate_xero_invoice=".$row['xero_veec_rebate_invoice_c'];
                        }

                        if(isset($row['xero_stc_rebate_invoice_c'])&& $row['xero_stc_rebate_invoice_c'] != ""){
                            $source .= "&rebate_xero_invoice=".$row['xero_stc_rebate_invoice_c'];

                        }

                        $GLOBALS['log']->info("CURL Response $source");
                        curl_setopt($curl, CURLOPT_URL, $source);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_HEADER, true);
                        curl_setopt($curl, CURLOPT_VERBOSE, 1);
                        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_HTTPGET, true);
                        $curl_response = curl_exec($curl);

                        curl_close($curl);
                        $GLOBALS['log']->info("CURL Response $curl_response");
                    }
                }

                if($value !== null ){
                    //unlink($folder.$file);
                }
            }
        }
    }
    return true;
}
