<?php
    require_once('include/SugarPHPMailer.php');
    $emailTemplateId = '';
    // $_REQUEST["product_info"]["choice_product_sanden"] = 'Sanden FQS';
    // $_REQUEST["quote_id"] = 'ecd384ca-d52a-8662-716c-5e706d65b8fd';
    if($_REQUEST['type_form'] == 'daikin_form') {
        $arrayDaikin = [];
        foreach($_REQUEST['list_infomation']['products'] as $product) {
            if (strpos($product["productName"], 'US7') == true) {
                array_push($arrayDaikin,'US7');
            } else if(strpos($product["productName"], 'Nexura') == true) {
                array_push($arrayDaikin,'Nexura');
            }
        }
        $array_tmp = array_unique($arrayDaikin);
        foreach($array_tmp as $tmp) {
            if($tmp == "US7") {
                $emailTemplateId = "8d9e9b2c-e05f-deda-c83a-59f97f10d06a";
                sendMailInfoPack($_REQUEST['list_infomation']["quote_daikin_id"], $emailTemplateId, $_REQUEST['list_infomation']['email_customer']);
            } else if($tmp == "Nexura") {
                $emailTemplateId = "5ad80115-b756-ea3e-ca83-5abb005602bf";
                sendMailInfoPack($_REQUEST['list_infomation']["quote_daikin_id"], $emailTemplateId, $_REQUEST['list_infomation']['email_customer']);
            }
        }

    }elseif($_REQUEST['type_form'] == 'solar_form') {
        $emailTemplateId = "3c143527-67a2-6190-1565-5d5b3809767e";
        sendMailInfoPack($_REQUEST["quote_id"], $emailTemplateId, $_REQUEST['info_pack']['your_email']);
    } else {
        if (strpos($_REQUEST["product_info"]["choice_product_sanden"], 'FQS') == true) {
            $emailTemplateId = "dbf622ae-bb45-cb79-eb97-5cd287c48ac3";
            sendMailInfoPack($_REQUEST["quote_id"], $emailTemplateId, $_REQUEST['product_info']['your_email']);
        } else {
            $emailTemplateId = "ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5";
            sendMailInfoPack($_REQUEST["quote_id"], $emailTemplateId, $_REQUEST['product_info']['your_email']);
        }
    }
    function sendMailInfoPack($quote_id, $emailTemplateId, $email)  {
        global $current_user;
        if($quote_id != ''){
            $quote = new AOS_Quotes();
            $quote->retrieve($quote_id);
    
            if(empty($quote->id))return;

            $link_upload_files = '';
            $string_link_upload_files = '';
            switch ($emailTemplateId) {
                case 'dbf622ae-bb45-cb79-eb97-5cd287c48ac3':
                    $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id=' . $quote->id;
                    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
                    break;
                case 'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5':
                    $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id=' . $quote->id;
                    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
                    break;
                case '8d9e9b2c-e05f-deda-c83a-59f97f10d06a':
                    $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=' . $quote->id;
                    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
                    break;   
                    
                case '5ad80115-b756-ea3e-ca83-5abb005602bf':
                    $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=' . $quote->id;
                    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
                    break;
                    
                case '3c143527-67a2-6190-1565-5d5b3809767e':
                    $link_upload_files = 'https://pure-electric.com.au/pesolarform/confirm?quote-id=' . $quote->id;
                    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
                    break; 
                           
                default:
                    # code...
                    break;
            }

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates', $emailTemplateId
            );
            $lead_name = $quote->account_firstname_c. " ".$quote->account_lastname_c;
            $body = str_replace('$lead_first_name', $quote->account_firstname_c, $emailTemplate->body_html);
            $body = str_replace("\$link_upload_files",$string_link_upload_files, $body);
            $subject = str_replace('$lead_name',$lead_name, $emailTemplate->subject);
            // $subject = str_replace('$lead_first_name', $quote->account_firstname_c, $emailTemplate->subject);
            // $subject = str_replace('$lead_last_name', $quote->account_lastname_c, $subject);
            $subject = str_replace('$lead_primary_address_city', $quote->billing_address_city, $subject);
            $subject = str_replace('$lead_primary_address_state', $quote->billing_address_state, $subject);
    
            // $this->bean->emails_email_templates_idb = $emailTemplateId;
            $account_id    = "a4d3c2c4-484e-8dfd-3d52-59f93249c95b";
            $current_user = new User();
            $current_user->retrieve($account_id);
            $defaultEmailSignature = $current_user->getSignature('1df22928-d247-afc1-15b8-5b222bb12089');
            if (empty($defaultEmailSignature)) {
                $defaultEmailSignature = array(
                    'html' => '<br>',
                    'plain' => '\r\n',
                );
                $defaultEmailSignature['no_default_available'] = true;
            } else {
                $defaultEmailSignature['no_default_available'] = false;
            }
            $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
            $body .= "<br><br><br>";
            $body .=  $defaultEmailSignature['signature_html'];
            
            $attachmentBeans = $emailTemplate->getAttachments();
    
            $mail = new SugarPHPMailer();  
            $mail->setMailerForSystem();  
            $mail->From = 'info@pure-electric.com.au';  
            $mail->FromName = 'Pure Electric';  
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->IsHTML(true);
            $mail->AddAddress($email);
            $array = array();
            foreach($attachmentBeans as $attachment) {
    
                $noteTemplate = clone $attachment;
                $noteTemplate->id = create_guid();
    
                $noteFile = new UploadFile();
                $noteFile->duplicate_file($attachment->id, $noteTemplate->id, $noteTemplate->filename);
    
                $noteTemplate->save();
    
                $file_name = $attachment->filename;
                $filename = $attachment->id . $attachment->filename;
                $file_location = "upload/".$attachment->id;
                $mime_type = $attachment->file_mime_type;
                $filename = substr($filename, 36, strlen($filename)); 
    
                $mail->AddAttachment($file_location, $file_name, 'base64', $mime_type);
            }
            $mail->AddCC('paul.szuster@pure-electric.com.au');
            $mail->AddCC('matthew.wright@pure-electric.com.au');
            $mail->AddCC('michael.golden@pure-electric.com.au');
            $mail->AddCC('info@pure-electric.com.au');
            $mail->prepForOutbound();
            $mail->setMailerForSystem();  
            $sent = $mail->Send();
        }
    }