<?php 
array_push($job_strings, 'custom_autogeotoissue');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_geoissue($assignment){
    
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
    $mail->Subject = "Geo issued notification";
    $mail->Body = "This assignment has been issued <br> <a href='https://geocreation.com.au/assignments/".$assignment."/edit/agreements'>https://geocreation.com.au/assignments/".$assignment."/edit/agreements</a>";

    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");
    $mail->AddCC("binh.nguyen@pure-electric.com.au");
    $mail->AddCC("paul.szuster@pure-electric.com.au");
    //$mail->AddCC("lee.andrewartha@pure-electric.com.au");
    $mail->AddCC("matthew.wright@pure-electric.com.au");
    

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function pushGeoToIssue($accesstoken,$assignment,$agreement_id){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.geocreation.com.au/api/agreements/$agreement_id/transition");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"status":"issued"}');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = "Origin: https://geocreation.com.au";
    $headers[] = "Accept-Language: en";
    $headers[] = "Authorization: token ".$accesstoken;
    $headers[] = "Content-Type: application/json";
    $headers[] = "Accept: */*";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Content-Length: 19";
    $headers[] = "Referer: https://geocreation.com.au/assignments/$assignment/edit/agreements";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $result = json_decode($result);
    $status_after_issued = $result->agreement->result->status;
    if($status_after_issued == "issued"){
        autosendmail_geoissue($assignment);
    }
    curl_close ($ch);
}

function custom_autogeotoissue(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    
    $fields['email'] = 'accounts@pure-electric.com.au';
    $fields['password'] = 'pureandtrue2016';

    $url = 'https://geocreation.com.au/login/';
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);

    $html = str_get_html($result);
    
    $session_script = $html->find('script#session')[0]->innertext;
    
    $session_object = json_decode($session_script);
    
    $clientRef = $session_object->user->clients[0]->reference;
    
    $accesstoken = $session_object->token->token;

    //get list assignments from hasReadyToIssueAgreements
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://api.geocreation.com.au/api/assignments/search?filters%5BhasReadyToIssueAgreements%5D=true&page=1");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Connection: keep-alive",
            "Authorization: token ".$accesstoken,
            "Referer: https://geocreation.com.au/assignments/?filters%5BhasReadyToIssueAgreements%5D=true",
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
                    "Authorization: token ".$accesstoken,
                    "Referer: https://geocreation.com.au/assignments/$assignment/edit",
                    "Origin: https://geocreation.com.au",
                )
            );

            $result = curl_exec($curl);
            curl_close ($curl);
            if($result != false){
                $result = json_decode($result);
                $assignment_byID = $result->assignment->result;
                $check_status_sh = false;
                
                $whSectionSTC_check = count(get_object_vars($assignment_byID->whSectionSTC->errorJson));
                $commonSection_check = count(get_object_vars($assignment_byID->commonSection->errorJson));
                $commonOfficeOnlySection_check = count(get_object_vars($assignment_byID->commonOfficeOnlySection->errorJson));
                $whSection_check = count(get_object_vars($assignment_byID->whSection->errorJson));
                $whSectionVEEC_check = count(get_object_vars($assignment_byID->whSectionVEEC->errorJson));
                $document_valid_pass = $assignment_byID->audits[0]->allPass;

                if($whSectionSTC_check == 0 && $commonSection_check == 0 && $commonOfficeOnlySection_check == 0 && $whSection_check == 0 && $whSectionVEEC_check ==0 && $document_valid_pass == true){
                    foreach($assignment_byID->agreements as $agreement){
                        $agreement_id = $agreement->_id;
                        $agreement_status = $agreement->status;
                        $agreement_name = $agreement->templateName;
    
                        if($agreement_name == 'SH Installer'){
                            $check_status_sh = true;
                            if($agreement_status == 'created'){
                                //when geo is SH
                                pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }
                        }else{
                            if($agreement_status == "accepted"){
                                //when geo is SH
                                pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }else if($check_status_sh == false && $agreement_status == 'created'){
                                 //when geo is WH
                                 pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }
                        }
                    }
                }
            }
        }
    }
}