<?php

$filename = "";
function readHeader($ch, $header)
{
    // read headers
    global $filename;

    if ( strpos($header, "Content-disposition: attachment; filename=") !== false ) {
        $filename = str_replace("Content-disposition: attachment; filename=","",$header);
        $filename = trim(str_replace('"','',$filename));
    }
    return strlen($header);
}

function downloadPDFFile($uid="9bff5445-2426-5ecc-94bf-59013c3b70c3", $installation_type_c = "whInstallation")
{
    global $filename;
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    //http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid=9bff5445-2426-5ecc-94bf-59013c3b70c3
    $url = "https://suitecrm.pure-electric.com.au/index.php";

    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    $url = 'https://suitecrm.pure-electric.com.au/index.php';
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

    $result = explode("\r\n\r\n", $result, 2);
    $response = json_decode($result[1]);
    $session_id = $response->id;
    //$result = explode("\r\n\r\n", $result, 2);
    //$response = json_decode($result[1]);
    if($installation_type_c == "whInstallation"){
        $template_id = "f2e70b70-bc22-409f-c7bf-5e4bb50e3113";
    }
    else {
        $template_id = "83a77470-c8b1-a174-3132-58df1804c777";
    }
    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$template_id."&task=pdf&module=AOS_Invoices&uid=".$uid;
    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, "readHeader");
    $curl_response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($curl_response, 0, $header_size);
    $body = substr($curl_response, $header_size);

    $destination = dirname(__FILE__)."/files/". $filename;
    $file = fopen($destination, "w+");
    fputs($file, $body);
    fclose($file);
    curl_close($curl);

    //return $response;

}

date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');

$curl = curl_init();
$tmpfname = dirname(__FILE__).'/cookiegeo.txt';


//LOGIC LOGIN (verify user and accesstoken)
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
    curl_close($ch);

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

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.geocreation.com.au/api/users/58e18e9b79c887010004f715');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Authorization: token '.$IdToken;
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $result_json  = json_decode($result);
    $clientRef = $result_json->user->result->clients[0]->reference;
//END LOGIC LOGIN

// CREAT ASSIGNMENT
    $curl = curl_init();
    $url = 'https://api.greenenergytrading.com.au/api/assignments/';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "User-Agent: ".$_SERVER['HTTP_USER_AGENT'],
            "Accept: */*",
            'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5',
            "Accept-Encoding:   gzip, deflate, br",
            "Access-Control-Request-Method: POST",
            "Access-Control-Request-Headers: authorization,content-type",
            "Connection: keep-alive",
            'Referer: https://geocreation.com.au/',
            'Origin: https://geocreation.com.au'

        )
    );
    $result = curl_exec($curl);

    //set variable
    $benefitProvidedVEEC = urldecode($_GET['benefitProvidedVEEC']);
    $yourReference = urldecode($_GET['your_reference']);
    $lastName = urldecode($_GET['last_name']) ;
    $surName = urldecode($_GET['sur_name']) ;
    $systemOwnerType = urldecode($_GET['system_owner_type']) ;
    $installation_type_c = urldecode($_GET['installation_type_c']) ;
    $date = urldecode($_GET['date']);
    $abn = urldecode($_GET['abn']);
    $companyName = urldecode($_GET['companyname']);
    $benefitInvoiceTotal = urldecode($_GET['benefitInvoiceTotal']);
    $gstRegistered = urldecode($_GET['registered_for_gst_c']);
    $gstRegistered = (urldecode($_GET['registered_for_gst_c']) == "true") ? true:false;
    $data = array("assignment" => array(
            "clientReference" => $clientRef,
            "yourReference"=> $yourReference,
            "commonSection" => array(
                "clientIsSystemOwner" => false,
                "entityType" => $systemOwnerType,
                "firstName" => $lastName,
                "surname" =>$surName,
                "companyName" => $companyName,
                "abn" => $abn,
                "position" => 'Director',
                "benefitInvoiceTotal" => $benefitInvoiceTotal,
                "gstRegistered"=> $gstRegistered,
            ),
            "type" => $installation_type_c,
        ),
    );
    $data_string = json_encode($data);

    //post create geo
    $url = 'https://api.greenenergytrading.com.au/api/assignments/';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($data_string),
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);

    $result_json = json_decode($result);
    // reference = geo number
    $reference = $result_json->assignment->result->reference;
// END CREATE ASSIGNMENT

// UPDATE ASSIGNMENT
    // UPDATE ADDRESS
        $addressName  = urldecode($_GET['install_address']) ;
        $addressName = str_replace(" ", "+", $addressName);
        $url = "https://api.greenenergytrading.com.au/api/c1/addresses/address?name=&q=".$addressName;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(

                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: GET",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $url = "https://api.greenenergytrading.com.au/api/c1/addresses/address?name=&q=".$addressName;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $result_object = json_decode($result);
        $search_result_id = "";
        if(count($result_object->results) >= 1){
            $search_result_id = $result_object->results[0]->search_result_id;
        }

        if($search_result_id != ""){
            $data = array("searchResultId" => $search_result_id,
                "addressType" => "activityAddress",
            );
        }
        
        $data_string = json_encode($data);

        $url = 'https://api.greenenergytrading.com.au/api/assignments/address/'.$reference;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
    // END UPDATE ADDRESS

    // UPDATE FORM INSTALLATION DATE
        if(isset($date) && $date!="") {
            $data = array("assignment" => array(
                "commonSection" => array(
                    "activityDate" => $date,
                    "activityAddress"=> array(
                        "postalDeliveryType" => NULL,
                        "inputMethod" => "streetAddress"
                    ),
                ),
                "reference" => $reference,
            ),
            );
            $data_string = json_encode($data);

            $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$reference;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(

                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " .strlen($data_string),
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);
        }
    // END
    
    // UPDATE FROM
        $installType = urldecode($_GET['installType']);
        if($installation_type_c=="whInstallation"){
            $data = array("assignment" => array(
                    "whSection" => array(
                        "installType" => $installType,
                    ),
                    "whSectionVEEC" => array(
                        "installedSystemType" => "heatPump",
                        "benefitAmountProvided" =>$benefitProvidedVEEC,
                    ),
                    "whSectionSTC" => array(
                        "active" => true
                    ),
                    "reference" => $reference,
                )
            );
            $stateVic = urldecode($_GET['state']);
            if((strtolower($stateVic) == "victoria" || strtolower($stateVic) == "vic") && $installType == "replacedElectricHeater"){
                $data["assignment"]["whSectionVEEC"]["active"] = true;
                //over120L  true
                $data["assignment"]["whSectionVEEC"]["over120L"] = true;
            }
        }else { //if ($installType=="spaceHeater"){
            $data = array("assignment" => array(
                "shSection" => array(
                    "installedSystemType" => "spaceAirToAirHeatPump",
                ),
                "reference" => $reference,
            )
            );
        }
        $data_string = json_encode($data);

        $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$reference;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
    // END

    // UPDATE PRODUCT AND PRODUCT NUMBER
        $sanden_model = urldecode($_GET['sanden_model_c']) ;
        $veec_model = urldecode($_GET['veec_model']);
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        if(isset($veec_model) && $veec_model != ""){
            $veec_model = str_replace(" ", "+", $veec_model);
            //https://api.geocreation.com.au/api/products/search?q=FTXZ25N+/+RXZ25N&productType=spaceHeaterVeet&filters[date]=2017-05-18&filters[status]=approved&filters[activityCode]=10
            $url = "https://api.greenenergytrading.com.au/api/products/search?q=".$veec_model."&productType=spaceHeaterVeet&filters[date]=".$yesterday."&filters[status]=approved&filters[activityCode]=10";
        }else{
            $url = "https://api.greenenergytrading.com.au/api/products/search?q=".$sanden_model."&productType=waterHeaterRet&filters[date]=".$yesterday."&filters[status]=approved&filters[heatPump]=true";
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],

                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: GET",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $json_decode = json_decode($result);
        if(isset($veec_model) && $veec_model != ""){
            $product_json_object = $json_decode->spaceHeaterVeet[0]; //spaceHeaterVeet
        }else {
            $product_json_object = $json_decode->waterHeaterRet[0]; //spaceHeaterVeet
        }
        if(isset($veec_model) && $veec_model != "") {
            //thienpb fix
            $geo_product_product_total_price = $_REQUEST['geo_product_product_total_price'];
            $data = array("assignment" => array(
                    "shSection" => array(
                        "productSlug" => $product_json_object ->slug,
                        "productBrand" => $product_json_object -> brand,
                        "productModel" => $product_json_object -> model,
                        "benefitAmountProvided" => $geo_product_product_total_price
                    ),
                    "reference" => $reference,
                ),
            );
        }else {
            $data = array("assignment" => array(
                "whSection" => array(
                    "numberOfTanks" => 1,
                ),
                "whSectionSTC" => array(
                    "whProductSlug" => $product_json_object ->slug,
                    "whProductBrand" => $product_json_object -> brand,
                    "whProductModel" => $product_json_object -> model,
                    "capacityOver700L" => false,
                    "productsHeatPump" => true,
                    "benefitAmountProvided" => "1"
                ),
                "reference" => $reference,
            ),
            );
        }

        $vba_pic_cert_c = urldecode($_GET['vba_pic_cert_c']);
        $ces_cert_c = urldecode($_GET['ces_cert_c']);
        $plumber_id = urldecode($_GET['plumber_id']);
        $electrical_id = urldecode($_GET['electrical_id']);

        if((strtolower($stateVic) == "victoria" || strtolower($stateVic) == "vic")  && $installType == "replacedElectricHeater"){
            $data["assignment"]["whSectionVEEC"]["whProductSlug"] = $product_json_object ->slug;
            $data["assignment"]["whSectionVEEC"]["whProductBrand"] = $product_json_object -> brand;
            $data["assignment"]["whSectionVEEC"]["whProductModel"] = $product_json_object -> model;
            /* 
                decomRemoval "remainedAtPremises"
                decomSystemLocation "external"
                decomMethod "heatingElementRemoved"
                complianceCertificateNumber "123"
                electricalComplianceNumber  "123"
            */
            //whSectionVEEC
            $data["assignment"]["whSectionVEEC"]["decomSystemLocation"] = urldecode($_GET["decommissioning_system_locat_c"]);
            $data["assignment"]["whSectionVEEC"]["decomRemoval"] = urldecode($_GET["removal_of_decommissioned_pr_c"]);
            $data["assignment"]["whSectionVEEC"]["decomMethod"] = urldecode($_GET["decommissioning_method_c"]);
            $data["assignment"]["whSectionVEEC"]["complianceCertificateNumber"] = $vba_pic_cert_c;
            $data["assignment"]["whSectionVEEC"]["electricalComplianceNumber"] = $ces_cert_c;
            $data["assignment"]["whSectionVEEC"]["installerIsElectrician"] = ($plumber_id==$electrical_id)?true:false;
            //$data["assignment"]["whSectionVEEC"]["benefitAmountProvided"] = "1";
        }

        $data_string = json_encode($data);

        $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$reference;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $number_of_installations_c = (urldecode($_GET['number_of_installations_c']) == "true") ? true:false;
        $property_type_c = urldecode($_GET['property_type_c']);
        $number_of_storeys_c = (urldecode($_GET['number_of_storeys_c']) == "true") ? true:false;
        $lvWiring = false;
        if (isset($ces_cert_c) && $ces_cert_c!="") $lvWiring = true;
        $sanden_tank_serial_c = urldecode($_GET['sanden_tank_serial_c']);

        if(isset($veec_model) && $veec_model != "") {
            $data = array("assignment" => array(
                "shSection" => array(
                    "propertyType" => $property_type_c,
                    "electricalWork" => $lvWiring,
                    "complianceCertificateNumber" => $vba_pic_cert_c,
                    "electricalComplianceNumber" => $ces_cert_c?$ces_cert_c:"Not required",
                    "installerIsElectrician" => ($plumber_id==$electrical_id)?true:false,
                ),
                "reference" => $reference,
            ),
            );
        }else{
            $data = array("assignment" => array(
                "whSection" => array(
                    "multipleInstallations" => false, //$number_of_installations_c,
                    "propertyType" => $property_type_c,
                    "lvWiring" => $lvWiring,
                    "tankSerialNumbers" => array($sanden_tank_serial_c),
                    "installerIsElectrician" => ($plumber_id==$electrical_id)?true:false,
                ),

                "whSectionSTC" => array(
                    "multiStory" => $number_of_storeys_c,
                ),
                "reference" => $reference,
            ),
            );
        }

        $data_string = json_encode($data);

        $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$reference;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
    // END

    // FOR SAVING INSTALLER

        $installer_origin  = urldecode($_GET['installer']) ;
        $installer = str_replace(" ", "+", $installer_origin);
        $url = "https://api.greenenergytrading.com.au/api/contacts/search?q=".$installer."&filters[whInstaller]=true&clientReference=".$clientRef;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],

                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: GET",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        
        if($installation_type_c=="whInstallation") {
            //https://api.geocreation.com.au/api/contacts/search?q=PJT+Green+Plumbing&filters[whInstaller]=true&clientReference=C23091
            $url = "https://api.greenenergytrading.com.au/api/contacts/search?q=" . $installer . "&filters[whInstaller]=true&clientReference=" . $clientRef;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: api.geocreation.com.au",
                    "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token " . $IdToken,
                    "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);

            $result_object = json_decode($result);
            if (isset($result_object->contact) && count($result_object->contact)) {
                foreach ($result_object->contact as $key => $value) {
                    if($value->companyName == $installer_origin) {
                        $contact = $result_object->contact[$key];
                    }
                }
                if ($contact->companyName == $installer_origin) {
                    $contact_id = $contact->_id;
                    $data = array("assignment" => array(
                        "whSection" => array(
                            "installerId" => $contact_id
                        ),
                        "reference" => $reference,
                    ),
                    );
                    $data_string = json_encode($data);

                    $url = 'https://api.greenenergytrading.com.au/api/assignments/' . $reference;
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                           
                            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding:   gzip, deflate, br",
                            "Connection: keep-alive",
                            "Content-Length: " . strlen($data_string),
                            "Authorization: token " . $IdToken,
                            "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                            "Origin: https://geocreation.com.au",
                        )
                    );
                    $result = curl_exec($curl);
                }
            }
            // add electrican for status installation_type_c=="whInstallation"
            if($_GET['electrician_name'] != '') {
                $installer = $_GET['electrician_name'];
                $installer_search = str_replace(" ", "+", $installer);
                $url = "https://api.greenenergytrading.com.au/api/contacts/search?q=" . $installer_search . "&filters[electrician]=true&clientReference=" . $clientRef;
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                       
                        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                        "Accept: */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding:   gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: token " . $IdToken,
                        "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                        "Origin: https://geocreation.com.au",
                    )
                );
                $result = curl_exec($curl);
    
                $result_object = json_decode($result);
    
                if (isset($result_object->contact) && count($result_object->contact)) {
                    $contact = $result_object->contact[0];
                    //if ($contact->companyName == $installer_origin) {
                    $contact_id = $contact->_id;
                    $data = array("assignment" => array(
                            "whSectionVEEC" => array(
                                "electricianId" => $contact_id
                            ),
                            "reference" => $reference,
                        ),
                    );
                    $data_string = json_encode($data);
                    $url = 'https://api.greenenergytrading.com.au/api/assignments/' . $reference;
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                           
                            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding:   gzip, deflate, br",
                            "Connection: keep-alive",
                            "Content-Length: " . strlen($data_string),
                            "Authorization: token " . $IdToken,
                            "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                            "Origin: https://geocreation.com.au",
                        )
                    );
                    $result = curl_exec($curl);
                }
            }

        }else{
            $installer = "";

            if($plumber_id == $electrical_id){
                $installer = $_GET['plumber_name'];
                $installer_search = str_replace(" ", "+", $installer);
                $url = "https://api.greenenergytrading.com.au/api/contacts/search?q=" . $installer_search . "&filters[whInstaller]=true&clientReference=" . $clientRef;
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        
                        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                        "Accept: */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding:   gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: token " . $IdToken,
                        "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                        "Origin: https://geocreation.com.au",
                    )
                );
                $result = curl_exec($curl);

                $result_object = json_decode($result);

                if (isset($result_object->contact) && count($result_object->contact)) {
                    $contact = $result_object->contact[0];
                    //if ($contact->companyName == $installer_origin) {
                    $contact_id = $contact->_id;
                    $data = array("assignment" => array(
                        "shSection" => array(
                            "installerId" => $contact_id
                        ),
                        "reference" => $reference,
                    ),
                    );
                    $data_string = json_encode($data);

                    $url = 'https://api.greenenergytrading.com.au/api/assignments/' . $reference;
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            
                            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding:   gzip, deflate, br",
                            "Connection: keep-alive",
                            "Content-Length: " . strlen($data_string),
                            "Authorization: token " . $IdToken,
                            "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                            "Origin: https://geocreation.com.au",
                        )
                    );
                    $result = curl_exec($curl);
                }
            }else{
                $installer = $_GET['electrician_name'];
                $installer_search = str_replace(" ", "+", $installer);
                $url = "https://api.greenenergytrading.com.au/api/contacts/search?q=" . $installer_search . "&filters[electrician]=true&clientReference=" . $clientRef;
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                       
                        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                        "Accept: */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding:   gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: token " . $IdToken,
                        "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                        "Origin: https://geocreation.com.au",
                    )
                );
                $result = curl_exec($curl);

                $result_object = json_decode($result);

                if (isset($result_object->contact) && count($result_object->contact)) {
                    $contact = $result_object->contact[0];
                    //if ($contact->companyName == $installer_origin) {
                    $contact_id = $contact->_id;
                    $data = array("assignment" => array(
                            "shSection" => array(
                                "electricianId" => $contact_id
                            ),
                            "reference" => $reference,
                        ),
                    );
                    $data_string = json_encode($data);
                    $url = 'https://api.greenenergytrading.com.au/api/assignments/' . $reference;
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            
                            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding:   gzip, deflate, br",
                            "Connection: keep-alive",
                            "Content-Length: " . strlen($data_string),
                            "Authorization: token " . $IdToken,
                            "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                            "Origin: https://geocreation.com.au",
                        )
                    );
                    $result = curl_exec($curl);
                }

                $plumber_installer = $_GET['plumber_name'];
                $plumber_search = str_replace(" ", "+", $plumber_installer);
                $plumber_url = "https://api.greenenergytrading.com.au/api/contacts/search?q=" . $plumber_search . "&filters[whInstaller]=true&clientReference=" . $clientRef;
                curl_setopt($curl, CURLOPT_URL, $plumber_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        
                        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                        "Accept: */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding:   gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: token " . $IdToken,
                        "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                        "Origin: https://geocreation.com.au",
                    )
                );
                $result = curl_exec($curl);

                $result_object = json_decode($result);
                if (isset($result_object->contact) && count($result_object->contact)) {
                    $contact = $result_object->contact[0];
                    //if ($contact->companyName == $installer_origin) {
                    $contact_id = $contact->_id;
                    $data = array("assignment" => array(
                        "shSection" => array(
                            "installerId" => $contact_id
                        ),
                        "reference" => $reference,
                    ),
                    );
                    $data_string = json_encode($data);

                    $url = 'https://api.greenenergytrading.com.au/api/assignments/' . $reference;
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            
                            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding:   gzip, deflate, br",
                            "Connection: keep-alive",
                            "Content-Length: " . strlen($data_string),
                            "Authorization: token " . $IdToken,
                            "Referer: https://geocreation.com.au/assignments/" . $reference . "/edit",
                            "Origin: https://geocreation.com.au",
                        )
                    );
                    $result = curl_exec($curl);
                }
            }
        }

        $phone = urldecode($_GET['owner_phone']) ;
        $email = urldecode($_GET['owner_email']) ;

        $data = array("assignment" => array(
                "commonSection" => array(
                    "phone" => $phone,
                    "email" => $email,
                    "activityAddressIsPostalAddress" => true,
                ),
                "reference" => $reference,
            ),
        );
        $registered_for_gst_c = (urldecode($_GET['registered_for_gst_c']) == "true") ? true:false;
        if ($registered_for_gst_c == false){
            $data["assignment"]["commonSection"]["gstRegistered"] = false;
        }

        $payment_for_cert_c = (urldecode($_GET['payment_for_cert_c ']) == "true") ? true:false;
        if ($payment_for_cert_c == false){
            $data["assignment"]["commonSection"]["paySystemOwner"] = false;
        }

        $data_string = json_encode($data);
        $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$reference;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $result_object = json_decode($result);
        $parent_id = $result_object->assignment->result->_id;
    // END

    // UPDATE DOCUMENT
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.greenenergytrading.com.au/api/assignments/'.$reference);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        
        $result_object = json_decode($result);
        
        $requirement_installation_equipment_invoice = "";
        $plumbing_compliance_certificate = "";
        $electric_instal_compliance_document = "";
        $Geo_tagged_photo_evidence = "";
        $system_owner_tax_invoice = "";
        $complain_date_id = "";
        $recycling_receipt_id = "";
        $requirements = $result_object->assignment->result->audits[0]->requirements;
        foreach($requirements as $requirement){
            if($requirement->title == "Installation / Equipment Invoice"){
                $requirement_installation_equipment_invoice = $requirement->id;
            }

            if($requirement->title == "Plumbing compliance certificate"){
                $plumbing_compliance_certificate = $requirement->id;
            }

            if($requirement->title == "Electrical compliance certificate"){
                $electric_instal_compliance_document = $requirement->id;
            }
            if($requirement->title == "Geo-tagged photo evidence"){
                $Geo_tagged_photo_evidence = $requirement->id;
            }
            if($requirement->title == "System Owner Tax Invoice"){
                $system_owner_tax_invoice = $requirement->id;
            }
            if($requirement->title == "Date of installation"){
                $complain_date_id = $requirement->id;
            }
            if($requirement->title == "Recycling receipt"){
                $recycling_receipt_id = $requirement->id;
            }
        }
        //$requirement_installation_equipment_invoice =   $session_object[$reference]["assignment"]["result"]["audits"][0]["requirements"][0]["id"];
        //$plumbing_compliance_certificate =              $session_object[$reference]["assignment"]["result"]["audits"][0]["requirements"][1]["id"];
        //$electric_instal_compliance_document =          $session_object[$reference]["assignment"]["result"]["audits"][0]["requirements"][2]["id"];
        //$electrical_installation_compliance_document =  $session_object[$reference]["assignment"]["result"]["audits"][0]["requirements"][3]["id"];
        $audit_id =                                        $result_object->assignment->result->audits[0]->_id;
        //$last_element =                                 end($session_object[$reference]["assignment"]["result"]["audits"][0]["requirements"]);
        //$complain_date_id_veet =                        $last_element["id"];
        //$complain_date_id =                             $last_element["id"];

        /* ============== */
        // UPLOADING FILE

        $url = "https://api.greenenergytrading.com.au/api/documents/";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization,content-type",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $data = array("document" => array(
                "name" => "Installation / Equipment Invoice",
                "parentType" => "Assignment",
                "parentId" => $parent_id,
            ),
        );
        $data_string = json_encode($data);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($data_string),
                "Content-Type: application/json",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $result_object = json_decode($result);
        $document_id = $result_object->document->result->_id;

        // upload file
        $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        // initialise the curl request
        //$file_name_with_full_path = dirname(__FILE__).'/Invoice_Peter_Howard_Bundoora_315L_Sanden_Heat_pump_(1).pdf';

        $record = urldecode($_GET['record']) ;
        downloadPDFFile($record, $installation_type_c);

        global $filename;
        $filecontent =  file_get_contents(dirname(__FILE__)."/files/".$filename);
        $eol = "\r\n";
        $BOUNDARY = md5(time());
        $BODY="";
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
        $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
        $BODY .= $filename . $eol;
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
        $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;

        $BODY.= filesize (dirname(__FILE__)."/files/".$filename). $eol;

        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,

        $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
        $BODY.= 'Content-Type: application/pdf' . $eol. $eol; //Same before row image/png
        //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"

        $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
        $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.

        /*$header[] = 'Authorization: WRAP access_token="'.$this->accesstoken.'"';
        $header[] = 'Content-Type: multipart/form-data; boundary='.$BOUNDARY;

        $header[] = 'Authorization: WRAP access_token="'.$this->accesstoken.'"';
        $header[] = 'Content-Type: multipart/form-data; boundary='.$BOUNDARY;
        */
        //upload_file
        $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: application/json",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($BODY),
                "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $data = array("response" => array(
            "requirementId" => $requirement_installation_equipment_invoice,
            "documentId" => $document_id,
        )
        );
        $data_string = json_encode($data);

        $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        // VBA PIC change new name PCOC
        // Save the document to Assignemnt
        $vba_links = json_decode(htmlspecialchars_decode($_GET['vba_link']));
        $url = "https://api.greenenergytrading.com.au/api/documents/";
        //$vba_link = urldecode($_GET['vba_link']);
        // Response
        $data = array("document" => array(
                "name" => "Plumbing compliance certificate",
                "parentType" => "Assignment",
                "parentId" => $parent_id,
            ),
        );

        $data_string = json_encode($data);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($data_string),
                "Content-Type: application/json",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $result_object = json_decode($result);
        $document_id = $result_object->document->result->_id;

        foreach($vba_links as  $vba_link){
            $filename = basename($vba_link);
            $filecontent =  file_get_contents($vba_link);
            $eol = "\r\n";
            $BOUNDARY = md5(time());
            $BODY="";
            $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
            $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
            $BODY .= $filename . $eol;
            $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
            $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;

            $BODY.= strlen ($filecontent). $eol;

            $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,

            $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
            $BODY.= 'Content-Type: application/pdf' . $eol. $eol; //Same before row image/png
            //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"

            $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
            $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.
            //upload_file

            $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                   
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Accept: application/json",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                    "Content-Length: " .strlen($BODY),
                    "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);

            $data = array("response" => array(
                "requirementId" => $plumbing_compliance_certificate,
                "documentId" => $document_id,
                )
            );

            $data_string = json_encode($data);

            $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " .strlen($data_string),
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);
        }
    // END
// END

//Dung code - upload file System Owner Tax Invoice
    if($_GET['System_Owver_Tax_Invoice_link'] !== '') {
        $url = "https://api.greenenergytrading.com.au/api/documents/";
        // Response
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization,content-type",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        
        $data = array("document" => array(
            "name" => "System Owner Tax Invoice",
            "parentType" => "Assignment",
            "parentId" => $parent_id,
            )
        );
        $data_string = json_encode($data);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($data_string),
                "Content-Type: application/json",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        $result_object = json_decode($result);
        $document_id = $result_object->document->result->_id;
        $requirementID = $result_object->document->result->parentId;
        
        $System_Owver_Tax_Invoice_link = urldecode($_GET['System_Owver_Tax_Invoice_link']);
        $filename = basename($System_Owver_Tax_Invoice_link);
        $filecontent =  file_get_contents($System_Owver_Tax_Invoice_link);
        $eol = "\r\n";
        $BOUNDARY = md5(time());
        $BODY="";
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
        $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
        $BODY .= $filename . $eol;
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
        $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;
        
        $BODY.= strlen ($filecontent). $eol;
        
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
        
        $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
        $BODY.= 'Content-Type: application/pdf' . $eol. $eol; //Same before row image/png
        //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"
        
        $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
        $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.
        //upload_file
        
        $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: application/json",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$accesstoken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($BODY),
                "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
                "Origin: https://geocreation.com.au",
            )
        );
        
        $result = curl_exec($curl);
        
        $data = array("response" => array(
            "requirementId" => $system_owner_tax_invoice,
            "documentId" => $document_id,
        )
        );
        
        $data_string = json_encode($data);
        $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);   
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type:application/json",
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
    }
//End Dung code - upload file System Owner Tax Invoice

//thien code upload Recycling Receipt
    if($_GET['recycling_receipt_link'] !='' && $installType == 'replacedElectricHeater' && (strtolower($stateVic) == "victoria" || strtolower($stateVic) == "vic") ){
        $url = "https://api.greenenergytrading.com.au/api/documents/";
        // Response
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization,content-type",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        
        $data = array("document" => array(
            "name" => "Recycling receipt",
            "parentType" => "Assignment",
            "parentId" => $parent_id,
            )
        );
        $data_string = json_encode($data);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($data_string),
                "Content-Type: application/json",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        $result_object = json_decode($result);
        $document_id = $result_object->document->result->_id;
        $requirementID = $result_object->document->result->parentId;
        
        $recycling_receipt_link = urldecode($_GET['recycling_receipt_link']);
        $filename = basename($recycling_receipt_link);
        //thien fix push txt file to geo recycling receipt
        $full_name = $lastName.$surName;
        $filename = $full_name.'_'.$filename;
        $filecontent = $full_name." - Tank was disposed by the installer. Decommissioned via heating element removed as per geo tagged photos";
        //$filecontent =  file_get_contents($recycling_receipt_link);
        $eol = "\r\n";
        $BOUNDARY = md5(time());
        $BODY="";
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
        $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
        $BODY .= $filename . $eol;
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
        $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;
        
        $BODY.= strlen ($filecontent). $eol;
        
        $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
        
        $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
        $BODY.= 'Content-Type: application/pdf' . $eol. $eol; //Same before row image/png
        //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"
        
        $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
        $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.
        //upload_file
        
        $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: application/json",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($BODY),
                "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
                "Origin: https://geocreation.com.au",
            )
        );
        
        $result = curl_exec($curl);
        
        $data = array("response" => array(
            "requirementId" => $recycling_receipt_id,
            "documentId" => $document_id,
        )
        );
        
        $data_string = json_encode($data);
        $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type:application/json",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($data_string),
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
            "Origin: https://geocreation.com.au",
        )
        );
        
        $result = curl_exec($curl);
    }
// end

// CES CERTIFICATE
    $url = "https://api.greenenergytrading.com.au/api/documents/";
    // Response

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Access-Control-Request-Method: POST",
            "Access-Control-Request-Headers: authorization,content-type",
            "Connection: keep-alive",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);

    $data = array("document" => array(
        "name" => "Electrical installation compliance document",
        "parentType" => "Assignment",
        "parentId" => $parent_id,
        )
    );
    $data_string = json_encode($data);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
            "Content-Length: " .strlen($data_string),
            "Content-Type: application/json",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);
    $result_object = json_decode($result);
    $document_id = $result_object->document->result->_id;

    $ces_link = urldecode($_GET['ces_link']);
    $filename = basename($ces_link);
    $filecontent =  file_get_contents($ces_link);
    $eol = "\r\n";
    $BOUNDARY = md5(time());
    $BODY="";
    $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
    $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
    $BODY .= $filename . $eol;
    $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
    $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;

    $BODY.= strlen ($filecontent). $eol;

    $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,

    $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
    $BODY.= 'Content-Type: text/plain'. $eol. $eol; //Same before row image/png
    //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"

    $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
    $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.
    //upload_file

    $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Access-Control-Request-Method: POST",
            "Access-Control-Request-Headers: authorization",
            "Connection: keep-alive",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Accept: application/json",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
            "Content-Length: " .strlen($BODY),
            "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);

    $data = array("response" => array(
            "requirementId" => $electric_instal_compliance_document,
            "documentId" => $document_id,
        )
    );

    $data_string = json_encode($data);
    $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
         
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($data_string),
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);
// END

//Geo tagged photo evidence
    if((strtolower($stateVic) == "victoria" || strtolower($stateVic) == "vic") && $installType == "replacedElectricHeater"){
        $url = "https://api.greenenergytrading.com.au/api/documents/";
        // Response

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Access-Control-Request-Method: POST",
                "Access-Control-Request-Headers: authorization,content-type",
                "Connection: keep-alive",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);

        $data = array("document" => array(
            "name" => "Geo-tagged photo evidence",
            "parentType" => "Assignment",
            "parentId" => $parent_id,
            )
        );
        $data_string = json_encode($data);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: token ".$IdToken,
                "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                "Content-Length: " .strlen($data_string),
                "Content-Type: application/json",
                "Origin: https://geocreation.com.au",
            )
        );
        $result = curl_exec($curl);
        $result_object = json_decode($result);
        $document_id = $result_object->document->result->_id;

        $geo_tag_links = json_decode(htmlspecialchars_decode($_GET['geo_tag_link']));
        foreach($geo_tag_links as  $geo_tag_link){
            $filename = basename($geo_tag_link);
            $filecontent =  file_get_contents($geo_tag_link);
            $eol = "\r\n";
            $BOUNDARY = md5(time());
            $BODY="";
            $BODY.= '-----------------------------'.$BOUNDARY. $eol; //start param header
            $BODY .= 'Content-Disposition: form-data; name="name"' . $eol . $eol; // last Content with 2 $eol, in this case is only 1 content.
            $BODY .= $filename . $eol;
            $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,
            $BODY.= 'Content-Disposition: form-data;  name="size"'.$eol . $eol;

            $BODY.= strlen ($filecontent). $eol;

            $BODY.= '-----------------------------'.$BOUNDARY. $eol; // start 2nd param,

            $BODY.= 'Content-Disposition: form-data; name="file"; filename="'.$filename.'"'.$eol;
            $BODY.= 'Content-Type: application/pdf' . $eol. $eol; //Same before row image/png
            //Content-Disposition: form-data; name="file"; filename="Screen Shot 2017-06-12 at 10.30.04 AM.png"

            $BODY.= $filecontent . $eol; // we write the Base64 File Content and the $eol to finish the data,
            $BODY.= '-----------------------------'.$BOUNDARY .'--' . $eol; // we close the param and the post width "--" and 2 $eol at the end of our boundary header.
            //upload_file

            $url = "https://api.greenenergytrading.com.au/api/documents/".$document_id."/upload_file";

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $BODY);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Accept: application/json",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/".$reference."/edit/documents",
                    "Content-Length: " .strlen($BODY),
                    "Content-Type: multipart/form-data; boundary=---------------------------".$BOUNDARY,
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);

            $data = array("response" => array(
                "requirementId" => $Geo_tagged_photo_evidence,
                "documentId" => $document_id,
                )
            );

            $data_string = json_encode($data);

            $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " .strlen($data_string),
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
                    "Origin: https://geocreation.com.au",
                )
            );
            $result = curl_exec($curl);
        }
    }
// END
//END

//====================////
//$plumber_cert_date = $_GET['plumber_cert_date'];
// if($plumber_cert_date != $date (install date) ) can't save
    $plumber_cert_date = $date;
    $ces_cert_date_par = $date;
    $proof_of_date_par = $date;
    $data = array(
        array(
            "name" => "Plumbing Certificate of Compliance",
            "date" => $plumber_cert_date,
            "id" => 0,
        ),
        // array(
        //     "name" => "Electrical Safety Documentation",
        //     "date" => $ces_cert_date_par,
        //     "id" => 1,
        // )
        // ,
        // array(
        //     "name" => "Proof of Installation Documentation",
        //     "date" => $proof_of_date_par,
        //     "id" => 2,
        // )
    );

    $json_d = json_encode($data);

    $newData = array("response" => array(
        "requirementId"=> $complain_date_id,
        "json" => $json_d,
        )
    );

    $data_string = json_encode($newData);
    //[{"name":"Plumbing Certificate of Compliance","date":"2017-05-31","id":0},{"name":"Electrical Safety Documentation","date":"2017-06-19","id":1}]

    $url = "https://api.greenenergytrading.com.au/api/audits/".$audit_id."/respond";
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($data_string),
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);
    curl_close($curl);

    // store number
    $number_of_assignment = urldecode($_GET['number_of_assignment']);
    $bean = BeanFactory::getBean("AOS_Invoices", $record);
    if($number_of_assignment == 1){
        $bean -> stc_aggregator_serial_c = $reference;
        echo '{"reference1":"'.$reference.'"}';
        // Write to file
        $tmpfname = dirname(__FILE__).'/assignment/'.$reference.'.txt';
        $file = fopen($tmpfname, "w+");
        fputs($file, $reference);
        fclose($file);
    }

    if($number_of_assignment == 2){
        $bean -> stc_aggregator_serial_2_c = $reference;
        echo '{"reference2":"'.$reference.'"}';

        // Write to file
        $tmpfname = dirname(__FILE__).'/assignment/'.$reference.'.txt';
        $file = fopen($tmpfname, "w+");
        fputs($file, $reference);

        fclose($file);
    }

    $bean->save();
die();
