<?php

array_push($job_strings, 'custom_emailReportGEOIssued');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_reportforgeoissued($email_content){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);

    $mail->Subject = "Report Email GEO Issued";
    $mail_body = "List GEO Issued:<br/>";
    
    $mail->Body = $mail_body.$email_content;

    $mail->prepForOutbound(); 
    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");

    $sent = $mail->send();
}

function custom_emailReportGEOIssued(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $db = DBManagerFactory::getInstance();
    
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

    //get list assignments inProgess
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

        $mail_content = '';
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
                $address = $assignment_byID->commonSection->activityAddress->displayAddress;
                $activityDate = $assignment_byID->commonSection->activityDate;
                foreach($assignment_byID->agreements as $agreement){
                    if($agreement->status == 'issued'){

                        //Invoice link
                        $sql = "SELECT id_c FROM aos_invoices_cstm WHERE (stc_aggregator_serial_2_c = '$assignment' OR stc_aggregator_serial_c = '$assignment' OR stc_aggregator_c = '$assignment')";
                        $result = $db->query($sql);
                        $invoice_link = '';
                        if($result->num_rows > 0){
                            $row = $db->fetchByAssoc($result);
                            $invoice_link = '<a href="https://loc.suitecrm.com/index.php?module=AOS_Invoices&action=DetailView&record='.$row['id_c'].'">[Invoice Link]</a>';
                        }
                        $mail_content .=  '+ '.$invoice_link.' <a href="https://geocreation.com.au/assignments/'.$assignment.'/edit">['.$assignment_byID->displayName.']</a>. <Strong>Address:</strong> <span style="font-style:italic;">'.$address.'</span>. <strong>Installer Date:</strong> <span style="font-style:italic;">'.date('M jS Y',strtotime($activityDate)).'</span><br />';
                        continue;
                    }
                }
            }
        }
        autosendmail_reportforgeoissued($mail_content);
    }
}