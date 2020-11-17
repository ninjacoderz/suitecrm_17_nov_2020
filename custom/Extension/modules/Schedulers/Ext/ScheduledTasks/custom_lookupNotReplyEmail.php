<?php
    array_push($job_strings, 'custom_lookupNotReplyEmail');
    function send_listLookupNotReplyEmail($data){
        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);
        $mail->Subject = 'Lookup Not Reply Email';
        
        //Body Email

        $body_email = '<div dir="ltr">
        Dear ' . $assigned_user_name .',<br/>
        This is the list of email that you didn\'t reply: <br/>';
        foreach($data as $result){

            $lead = new Lead();
            $lead = $lead->retrieve($result['parent_id']);

            $email_lead = new Email();
            $email_lead =  $email_lead->retrieve($result['id']);
            
            $body_email .= '<span>+ </span><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $lead->id . '#subpanel_history">['.$lead->first_name.' '.$lead->last_name.']</a>';
            
            $assigned_by = '';
            if($lead->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew
                $assigned_by = "Matthew Wright";
            }elseif($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
                $assigned_by = "Paul Szuster";
            }
            $body_email .= ' <a target="_blank" href="https://mail.google.com/#search/'.$lead->email1.'">[GM Search]</a>';
            $body_email .= '. Assigned: '.$assigned_by.'. Date: ' .date("d/m/Y H:i:s", strtotime($result['date_entered']));

            $body_email .= ' <a href="#">Ignore</a><br/>';
            $email_content =  preg_replace('/CRM Links:(.+?)End CRM Links/s', '', strip_tags($email_lead->description_html));

            $body_email .= '<span>   - Content: </span><span style="font-size:12px;font-style: italic;">'.substr($email_content,0,150).trim().'...</span><br/>';
        
        }
        
        $body_email .= '</div>';

        $mail->Body = $body_email;
        //END  Body Email

        $mail->prepForOutbound();
        $mail->AddAddress('info@pure-electric.com.au');
        $sent = $mail->Send();
    }
    function custom_lookupNotReplyEmail(){
        date_default_timezone_set('Africa/Lagos');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $db = DBManagerFactory::getInstance();
        $query =   "SELECT emails.id, emails.date_entered, emails.parent_id, emails.deleted, emails.`status` 
    
        FROM `leads`
         JOIN emails ON emails.parent_id = leads.id
         JOIN leads_cstm ON emails.parent_id = leads_cstm.id_c
        
         JOIN 
        (
            SELECT MAX(emails.date_entered) AS date_entered , emails.parent_id
        
        FROM `leads`
         JOIN emails ON emails.parent_id = leads.id
         JOIN leads_cstm ON emails.parent_id = leads_cstm.id_c
         
        WHERE 1 = 1  
        AND emails.`parent_type` = 'Leads' 
        AND emails.date_entered <= DATE_ADD(CURDATE(), INTERVAL -1 DAY) 
        AND leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain')
        AND (leads_cstm.ignore_lead_c != 1 OR leads_cstm.ignore_lead_c IS NULL)   
        AND emails.deleted = 0
          
        GROUP BY emails.parent_id
        ORDER BY `emails`.`date_entered` DESC
            ) result_table ON result_table.parent_id = emails.parent_id AND result_table.date_entered = emails.date_entered
             
        WHERE 1 = 1 
        AND emails.`parent_type` = 'Leads' 
        AND emails.date_entered <= DATE_ADD(CURDATE(), INTERVAL -1 DAY) 
        AND leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain')
        AND (leads_cstm.ignore_lead_c != 1 OR leads_cstm.ignore_lead_c IS NULL)   
        AND emails.deleted = 0
        
        AND emails.status = 'received'
            
        ORDER BY `emails`.`date_entered` DESC LIMIT 0,50";
        $ret = $db->query($query);
        if($ret->num_rows > 0){
            $data = array();
            $data_leads = array();
            while($row = $db->fetchByAssoc($ret)){
                    $data[] = $row;
                    $data_leads[] = $row['parent_id'];
            }
            send_listLookupNotReplyEmail($data);
            $str_leads_id = "('".implode("','",$data_leads)."')";
            $sql_update = "UPDATE leads_cstm SET ignore_lead_c = 1 WHERE id_c IN $str_leads_id";
            $ret = $db->query($query);
        }else{
            die();
        }
    }

?>