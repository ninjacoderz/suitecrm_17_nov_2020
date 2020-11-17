<?php

array_push($job_strings, 'custom_autosendemailschedule');

function custom_autosendemailschedule(){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id FROM emails WHERE `status` = 'email_schedule' AND deleted = 0";
    $result_email = $db->query($sql);
    if($result_email->num_rows > 0){
        while ($email_row = $db->fetchByAssoc($result_email))
        {
            $email_info = new Email();
            $email_info = $email_info->retrieve($email_row['id']);
            $date = new DateTime();
            if($email_info->schedule_timestamp_c < $date->getTimestamp()){
                send_email_schedule($email_info);
            }
        }
    }
}
function send_email_schedule($emailBean){  
    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = trim(str_replace(',','',$emailBean->from_addr_name));

    if($mail->From == 'paul.szuster@pure-electric.com.au'){
        $mail->FromName = "Paul Szuster";
    }else if($mail->From == 'matthew.wright@pure-electric.com.au'){
        $mail->FromName = "Matthew Wright";
    }else{
        $mail->FromName ="";
    }

    $mail->Subject = $emailBean->name;    
    $mail->Body =  $emailBean->description_html;  
    
    $note = new Note();
    $where = "notes.parent_id = '".$emailBean->id."'";
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

    $mail->IsHTML(true);
    
    $to = explode(", ",$emailBean->to_addrs_names);
    if(count($to)>1){
        foreach($to as $res){
            $mail->AddAddress(trim($res));
        }
    }else{
        $mail->AddAddress(trim($emailBean->to_addrs_names));
    }

    $cc = explode(", ",$emailBean->cc_addrs_names);
    if(count($cc)>1){
        foreach($cc as $res){
            $mail->AddCC(trim($res));
        }    
    }else{
        $mail->AddCC(trim($emailBean->cc_addrs_names));
    }
    
    $bcc = explode(", ",$emailBean->bcc_addrs_names);
    if(count($cc)>1){
        foreach($bcc as $res){
            $mail->AddBCC(trim($res));
        }
    }else{
        $mail->AddBCC(trim($emailBean->bcc_addrs_names));
    }
    
    $mail->prepForOutbound();
    if($mail->Send()){
        $emailBean->status = 'sent';
        $emailBean->save();
    }
}

