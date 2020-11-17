<?php 
array_push($job_strings, 'custom_autosendmail');
require_once('modules/Emails/Email.php');
require_once('include/SugarPHPMailer.php');
function custom_autosendmail(){
    
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * from leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c WHERE email_send_status_c = 'pending' AND email_send_id_c !='' AND deleted != 1";
    $ret = $db->query($sql);
    while($row = $db->fetchByAssoc($ret)){
        $record_id = $row['id'];
        $primary_address_street = $row['primary_address_street'];
        $primary_address_city = $row['primary_address_city'];
        $primary_address_state = $row['primary_address_state'];
        $primary_address_postalcode = $row['primary_address_postalcode'];
         //check time
        if($primary_address_street == "" || $primary_address_city == "" || $primary_address_state == "" || $primary_address_postalcode == ""){

            $emailBean = new Email();
            $emailBean-> retrieve($row['email_send_id_c']);
            if($emailBean->id == "") return;

            $lead = new Lead();
            $lead-> retrieve($row['id']); 
            if($lead->id == "") return;

            
            $random_number = rand(0,100);

            if ($random_number <= 80) {
                $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
            } else {
                $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
            }
            
            /*$matthew_inbound_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
            $paul_inbound_id    = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
            */
            $temp_request        = array(
                "module" => "Emails",
                "action" => "send",
                "record" => "",
                "type" => "out",
                "send" => 1,
                "inbound_email_id" => ($random_number < 70) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                "parent_type" => "Leads",
                "parent_name" => $row['first_name'] .' '. $row['last_name'],
                "parent_id" => $row['id'],
                "from_addr" => $from_address,
                "to_addrs_names" => $lead->email1, //$lead->email1, //"binhdigipro@gmail.com",
                "cc_addrs_names" => "info@pure-electric.com.au",
                "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au,",
                "is_only_plain_text" => false
            );

            //$emailBean           = new Email();
            $emailBean           = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                
            // Signature
            /*
            $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
            $paul_id    = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
            $user       = new User();
            $user->retrieve($matthew_id);
            if ($random_number <= 80) { // Matthew 
                $emailSignatureId = "6f14eb50-e31f-b1de-194e-5ad439e971fa"; // Lee signature
            } elseif (80 < $random_number && $random_number <= 100) {
                $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
                //$emailSignatureId = "7ac5a4fd-b086-2bcc-aa40-5a741cf9baca";
            } else {
                $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
            }
            
            $signature = $user->getSignature($emailSignatureId);
            $emailBean->description .= $signature["signature"];
            $emailBean->description_html .= $signature["signature_html"];
            $emailBean->description .= $live_chat_text;
            $emailBean->description_html .= $live_chat_text;
            
            $emailBean->save();
            */
           
            //check time
            
            global $timedate;
            $time_zone = $timedate->getInstance()->userTimezone();
            date_default_timezone_set($time_zone);
            
            $date_created = strtotime($row['date_entered']);
            $timeAgo = time() - $date_created;
            $timeAgo = $timeAgo / 3600;

            if($lead->status == "Assigned"){
                if($timeAgo > 24){
                    $lead->email_send_status_c = 'sent';
                    autosendmail_config_email($lead,$emailBean);
                    $lead->save();
                }else{
                    continue;
                }
            }else{
                continue;
            }
            
        }else{
            continue;
        }
    }
    return;
}

function autosendmail_config_email($lead,$emailBean){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $emailBean->from_addr;
    $mail->FromName = $emailBean->from_name;
    $mail->IsHTML(true);

    //get email template and replace Email Variables
    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve("58230a56-82cd-03ae-1d60-59eec0f8582d");

    $emailtemplate->parsed_entities = null;
    $macro_nv = array();
    $focusName = $emailBean->parent_type;
    $focus = BeanFactory::getBean($focusName, $lead->id);

    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => $emailtemplate->body_html,
            "body" => $emailtemplate->body
            ),
            'Leads',
            $focus,
            $temp
        );
    $email_body = str_replace('$lead_first_name',$lead->first_name,$template_data["body_html"]);
    $email_subject = str_replace('$lead_first_name',$lead->first_name,$template_data["subject"]);
    $email_subject = str_replace('$lead_primary_address_city',$lead->primary_address_city, $email_subject);
    
    //get and add attachment from template

    //require_once('module/Notes/Note.php');
    $note = new Note();
    $where = "notes.parent_id = '58230a56-82cd-03ae-1d60-59eec0f8582d'";
    $attachments = $note->get_full_list("", $where, true);
    $all_attachments = array();
    $all_attachments = array_merge($all_attachments, $attachments);
    foreach($all_attachments as $attachment) {
        $file_name = $attachment->filename;
        global $sugar_config;
        $location = $sugar_config['upload_dir'].$attachment->id;
        $mime_type = $attachment->file_mime_type;
        // Add attachment to email
        $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
    }
    
    $mail->Subject = $email_subject;
    $mail->Body = $email_body."\n".$emailBean->description_html;

    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress($emailBean->to_addrs_names);
    $mailcc = explode(',',$emailBean->cc_addrs_names);
    
    if(count($mailcc)>0){
        foreach($mailcc as $res){
            $mail->AddCC(trim($res));
        }
    }
    
    $mailbcc = explode(',',$emailBean->bcc_addrs_names);
    if(count($mailbcc)>0){
        foreach($mailbcc as $res){
            $mail->AddBCC(trim($res));
        }
    }

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();

    if ($sent) {
        $emailBean->status = 'sent';
        $emailBean->save();
    } else {
        if ($emailBean->status !== 'draft') {
            $emailBean->status = 'send_error';
            $emailBean->save();
        } else {
            $emailBean->status = 'send_error';
        }
    }
}
