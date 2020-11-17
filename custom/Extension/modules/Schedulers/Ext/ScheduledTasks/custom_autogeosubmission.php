<?php 
array_push($job_strings, 'custom_autogeosubmission');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_geosubmission($assignment){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "accounts@pure-electric.com.au";
    $mail->FromName = "PureElectric Accounts";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Geo submission notification";
    $mail->Body = "This assignment has been submitted <br> <a href='https://geocreation.com.au/assignments/".$assignment."/edit/submission'>https://geocreation.com.au/assignments/".$assignment."/edit/submission</a>";

    $mail->AddAddress("info@pure-electric.com.au");
    $mail->AddCC("binh.nguyen@pure-electric.com.au");
    $mail->AddCC("paul.szuster@pure-electric.com.au");
    //$mail->AddCC("lee.andrewartha@pure-electric.com.au");
    $mail->AddCC("matthew.wright@pure-electric.com.au");
    

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function custom_autogeosubmission(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    
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

    //get list assignments inProgess
    $curl = curl_init();
    $url = 'https://api.geocreation.com.au/api/assignments/search?filters%5Bstatus%5D=inProgress&page=1';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/?filters%5Bstatus%5D=inProgress",
            "Origin: https://geocreation.com.au",
        )
    );
    
    $result = curl_exec($curl);
    curl_close ($curl);
    if($result != false){
        $result = json_decode($result);
        $assignments = array();
        if(isset($result->assignment)){
            foreach($result->assignment as $ret){
                if($ret->certificateCount > 0){
                    array_push( $assignments,$ret->reference);
                }
                
            }
        }

        foreach ($assignments as $assignment){

            // Get JSON of assignment by assignment ID
            $curl = curl_init();
            $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: api.geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-type: application/json; charset=UTF-8",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/$assignment/edit",
                    "Origin: https://geocreation.com.au",
                )
            );

            $result = curl_exec($curl);
            curl_close ($curl);
            
            if($result != false){
                $result = json_decode($result);
                $assignment_byID = $result->assignment->result;
                $certificateBundles = $assignment_byID->certificateBundles;
                $check_null = true;
                $deal_id = array();

                //thien fix. Check certificateBundles
                foreach($assignment_byID->agreements as $agreement){
                    if(is_null($agreement->acceptedAt)){
                        $check_null = false;
                        break;
                    }
                }
                if(count($certificateBundles) == 1){
                    if($check_null){
                        if(count($assignment_byID->agreements) == 1){
                            array_push($deal_id,'12351');
                        }
                        // else{
                        //     array_push($deal_id,'12357');
                        // }
                    }
                }
                // else{
                //     if($check_null){
                //         array_push($deal_id,'12351','12354');
                //     }
                // }

                if(count($deal_id)>0){
                    foreach($certificateBundles as $res_bundles){
                        $claims = $res_bundles->dealBundle->claims;
                        for($i=0;$i<count($claims);$i++){
                            if(in_array($claims[$i]->dealId,$deal_id)){
                                $certificateBundles_id = $res_bundles->_id;
                                $dealID = $claims[$i]->dealId;
    
                                // Set active payment before submitted
                                $curl = curl_init();
                                $url = "https://api.geocreation.com.au/api/assignments/$certificateBundles_id/reserve/$dealID";
                                curl_setopt($curl, CURLOPT_URL, $url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($curl, CURLOPT_POST, TRUE);
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
                                        "Content-Length: 0",
                                        "Authorization: token ".$IdToken,
                                        "Referer: https://geocreation.com.au/assignments/$assignment/edit/submission",
                                        "Origin: https://geocreation.com.au",
                                    )
                                );
                                $result = curl_exec($curl);
                                curl_close ($curl);

                                $result = json_decode($result);
                                //get node readyToSubmit after set payment
                                $readyToSubmit  = $result->assignment->result->readyToSubmit;
                                $followUps = $result->assignment->result->followUps;

                                $check_followUps = true;
                                for($ii = 0;$ii < count($followUps); $ii++){
                                    if(is_null($followUps[$ii]->resolvedAt)){
                                        $check_followUps = false;
                                    }
                                }

                                if($readyToSubmit && $check_followUps){
                                    // Call action submitted assignment
                                    $ch = curl_init();

                                    curl_setopt($ch, CURLOPT_URL, "https://api.geocreation.com.au/api/assignments/$assignment/submit");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

                                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");

                                    $headers = array();
                                    $headers[] = "Host: api.geocreation.com.au";
                                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0";
                                    $headers[] = "Accept: */*";
                                    $headers[] = "Accept-Language: en-US";
                                    $headers[] = "Referer: https://geocreation.com.au/assignments/$assignment/edit/submission";
                                    $headers[] = "Authorization: token ".$IdToken;
                                    $headers[] = "Content-Type: application/json";
                                    $headers[] = "Content-Length: 2";
                                    $headers[] = "Origin: https://geocreation.com.au";
                                    $headers[] = "Connection: keep-alive";
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($ch);
                                    
                                    //Call function send mail when assignment submitted
                                    autosendmail_geosubmission($assignment);

                                    curl_close ($ch);
                                }
                            }
                        }
                        
                    }
                }
            }
        }
    }
}