<?php 
array_push($job_strings, 'custom_autosendgeoemail');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function custom_autosendgeoemail(){
    date_default_timezone_set('UTC');
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $db  = DBManagerFactory::getInstance();
    // Step 1: Login
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
    
    // Step 2: Get All GEO Form are inProgress
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.greenenergytrading.com.au/api/assignments/search?filters%5Bstatus%5D=inProgress&page=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: api.greenenergytrading.com.au';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Authorization: token '.$IdToken;
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result_filter = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $data_GEO_Form = array();
    if($result_filter != false){
        $result_filter = json_decode($result_filter);

        if(isset($result_filter->assignment)){
            foreach($result_filter->assignment as $ret){
                //filter issued has email assign to accounts@pure-electric.com.au
                if($ret->commonSection->email == 'accounts@pure-electric.com.au') {
                    continue;
                }
                $assignment_ID = $ret->reference;

                 // Get JSON of assignment by assignment ID
                 $curl = curl_init();
                 $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$assignment_ID;
                 curl_setopt($curl, CURLOPT_URL, $url);
                 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

                 curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
                 curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                 curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                         
                         "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                         "Content-type: application/json; charset=UTF-8",
                         "Accept: */*",
                         "Accept-Language: en-US,en;q=0.5",
                         "Accept-Encoding:   gzip, deflate, br",
                         "Connection: keep-alive",
                         "Authorization: token ".$IdToken,
                         "Referer: https://geocreation.com.au/assignments/$assignment_ID/edit",
                         "Origin: https://geocreation.com.au",
                     )
                 );
                 $result = curl_exec($curl);
                 curl_close ($curl);
                 $result = json_decode($result);
                 $assignment_byID = $result->assignment->result;
                 foreach ($assignment_byID->agreements  as $key => $agreement){
                    $data_GEO_Form[$assignment_ID][] = array(
                        'templateName' => $agreement->templateName,
                        'status' => $agreement->status,
                        'email' => $agreement->email
                    );

                 }
            
            }

            var_dump($data_GEO_Form);
            $data_GEO_Form = create_data_for_email($data_GEO_Form);
  
            send_email_report_GEO_Form($data_GEO_Form);
            
        }
    }
}


function create_data_for_email($data_GEO_Form){
    $array_GEO_ID = [];
    foreach ($data_GEO_Form as $key => $value){
        $array_GEO_ID[] = $key; 
    }

    $query = "SELECT aos_invoices.id, aos_invoices.name ,aos_invoices_cstm.stc_aggregator_serial_c,
    aos_invoices_cstm.geo_email_sent_date_c,aos_invoices_cstm.send_geo_email_status_c,
    aos_invoices_cstm.stc_aggregator_serial_2_c ,aos_invoices.number
    FROM aos_invoices INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
    WHERE (
        (aos_invoices_cstm.stc_aggregator_serial_c IN ('". implode("','",$array_GEO_ID) . "') ) 
        OR (aos_invoices_cstm.stc_aggregator_serial_2_c IN ('". implode("','",$array_GEO_ID) . "') ) 
        )
    AND  aos_invoices.deleted = 0";
    print($query);
    $db  = DBManagerFactory::getInstance();

    $ret = $db->query($query);
  
    if($ret->num_rows >0 ){
        while($row = $db->fetchByAssoc($ret)){
           
            $inv_GEO_ID = (!empty($row['stc_aggregator_serial_c']))? $row['stc_aggregator_serial_c'] : $row['stc_aggregator_serial_2_c'];
            //$inv_Email_GEO_ID = 
            if(!empty( $inv_GEO_ID)){
                foreach ($data_GEO_Form[$inv_GEO_ID] as $key => $value){
                    $data_GEO_Form[$inv_GEO_ID][$key]['id']= $row['id'];
                    $data_GEO_Form[$inv_GEO_ID][$key]['name']= $row['name'];
                    $data_GEO_Form[$inv_GEO_ID][$key]['number']= $row['number'];
                }
            }
        }
    }
    return $data_GEO_Form;
}

function table_content_report_GEO_Form($data_inv){

    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $GEO_domain = 'https://geocreation.com.au/assignments/';


    if(count($data_inv)>0){
        $html_content = '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="5%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="20%"><strong>Invoice Name </strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Agreement</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Template</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="7%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Email</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="12%"><strong>Link GEO</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="20%"><strong>Link Email</strong></td>
            </tr>';
        foreach($data_inv as $key => $value){
            foreach ($value as $index => $res) {
                $link_pe = '';
                $link_email_edit = '';
                $link_html_GEO =  '';
                if($res['templateName'] != '') {
                    
                    $link_pe = $pe_domain_crm . '/index.php?module=AOS_Invoices&action=EditView&record=' .$res['id'];
                    
                    $link_GEO =  $GEO_domain .$key.'/edit';
                    $link_html_GEO .= "<a target='_blank' href=".$link_GEO.">GEO Link</a><br>";
    
                    //create link email
    
                    if(strpos($res['templateName'],'Owner') !== false){
                        $link_email =  $pe_domain_crm . '/index.php?entryPoint=Create_Email_Draft&type=CreateEmailGEOFollowUp_System_Owner&module=AOS_Invoices&record=' .$res['id'];
                        $link_email_edit .=  "<a target='_blank' href=".$link_email.">Create Email GEO Follow Up System Owner</a><br><br>";
                    }
    
                    if(strpos($res['templateName'],'Installer') !== false){
                        $link_email =  $pe_domain_crm . '/index.php?entryPoint=Create_Email_Draft&type=CreateEmailGEOFollowUp_Installer&module=AOS_Invoices&record=' .$res['id'];
                        $link_email_edit .=  "<a target='_blank' href=".$link_email.">Create Email GEO Follow Up Installer</a><br><br>";
                    }
    
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' ><a target='_blank' href=".$link_pe.">Inv#". $res['number']."</a></td>
                        <td style='border: 1px solid black;' >".$res['name']."</td>
                        <td style='border: 1px solid black;' >".$key."</td>
                        <td style='border: 1px solid black;' >".$res['templateName']."</td>
                        <td style='border: 1px solid black;' >".$res['status']."</td>
                        <td style='border: 1px solid black;' >".$res['email']."</td>
                        <td style='border: 1px solid black;' >".$link_html_GEO."</td>
                        <td style='border: 1px solid black;' >".$link_email_edit."</td>
                    </tr>";
                }
            }

        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No Invoice</h4>";
    }
    return $html_content;
}

function send_email_report_GEO_Form($data_inv){
    $body = table_content_report_GEO_Form($data_inv);
    $today = date('d/m/Y', time());
    $subject = "<div><h1 'text-align:center;'>Pure-Electric Email GEO Form Follow UP - Daily Report - Date " . $today .'</h1></div>';
    //config mail
    global $current_user;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "Pure Electric Info";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject ="Pure-Electric Email GEO Form Follow UP - Daily Report - Date " . $today ;
    $mail->Body = $subject.$body;
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}