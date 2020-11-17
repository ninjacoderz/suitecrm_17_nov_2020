<?php 
$db =  DBManagerFactory::getInstance();
$sql = "
        SELECT id,time_completed_job_c,status,solargain_quote_number_c,solargain_tesla_quote_number_c ,date_entered
        FROM leads INNER JOIN leads_cstm  ON leads.id = leads_cstm.id_c 
        WHERE 
            (leads_cstm.time_completed_job_c IS NOT NULL AND leads_cstm.time_completed_job_c != '' )
            AND leads_cstm.time_completed_job_c   < date_sub(now(), interval 90 day)
            AND (leads_cstm.time_sent_to_client_c IS NULL OR leads_cstm.time_sent_to_client_c = '') 
            AND (leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain') )
            AND ( (leads_cstm.solargain_quote_number_c IS NOT NULL AND leads_cstm.solargain_quote_number_c != '') OR  (leads_cstm.solargain_tesla_quote_number_c IS NOT NULL AND leads_cstm.solargain_tesla_quote_number_c != '') )
            AND (leads_cstm.sent_follow_up_on_old_quote__c IS NULL OR leads_cstm.sent_follow_up_on_old_quote__c = '')
            AND leads.deleted = 0
            AND leads.date_entered > '2018-01-01 00:00:00'
        ORDER BY leads.date_entered ASC LIMIT 50
            ";
$ret = $db -> query($sql);
if($ret->num_rows > 0){
    while($row = $ret ->fetch_assoc()){
        //echo $row['id'] .': <br>';
        auto_send_email_convert_lead($row['id'],'22d9ed2d-c403-d59a-af09-5c1b288e6985');
        //echo '<br>';
    }
}

function auto_send_email_convert_lead($record_id ,$templete_id){
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    
    $mail->setMailerForSystem();
    $mail->IsHTML(true);

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);
    //get Signature  and address from sent
    $user_id = '';
    $emailSignatureId  ='';
        //Case: Matthew
    if($lead->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $user_id = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
        $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; 
        $mail->From = "matthew.wright@pure-electric.com.au";
        $mail->FromName = "Matthew Wright - PureElectric";
        $user = new User();
        $user->retrieve($user_id);
        $signature = $user->getSignature($emailSignatureId);
    } 
        //Case : Paul 
    elseif($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
        $user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
        $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
        $mail->From = "paul.szuster@pure-electric.com.au";
        $mail->FromName = "Paul Szuster - PureElectric";
        $user = new User();
        $user->retrieve($user_id);
        $signature = $user->getSignature($emailSignatureId);
    }
        //Case :defaul
    else{

        $mail->From = "accounts@pure-electric.com.au";
        $mail->FromName = "PureElectric";
    }     

    //get email template and replace Email Variables
    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve($templete_id);
    $emailtemplate->parsed_entities = null;
    $macro_nv = array();
    $focusName = 'Leads';
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
    $email_body = str_replace('$lead_date_entered',$lead->date_entered,$template_data["body_html"]);
    $email_subject = str_replace('$lead_first_name',$lead->first_name,$template_data["subject"]);

    //get and add attachment from template
    $note = new Note();
    $where = "notes.parent_id =  '" . $templete_id ."'";
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
    $mail->Body = $email_body . $signature["signature_html"];
    $mail->prepForOutbound();
    $mail->AddAddress('admin@pure-electric.com.au');
    $mail->AddCC('info@pure-electric.com.au');
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');

    if(isset($lead->email1)){
        $mail->AddAddress($lead->email1);
        if($mail->Send()){
            $sql_update = "UPDATE leads_cstm SET sent_follow_up_on_old_quote__c = 1 WHERE id_c= '$record_id'";
            $db =  DBManagerFactory::getInstance();
            $ret = $db -> query($sql_update);
        }
    }
    //return $lead->email1 .'<br>' .$mail->Body .'<br>' .$mail->Subject;
}
