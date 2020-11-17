<?php 
array_push($job_strings, 'custom_sendmailsgdesignrequest');

function custom_sendmailsgdesignrequest(){
    $db = DBManagerFactory::getInstance();
    $query = $sql = "SELECT * FROM leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c 
                    WHERE  email_send_design_request_id_c !='' AND email_send_design_status_c = 'pending' AND deleted != 1
                    AND leads.primary_address_street IS NOT NULL 
                    AND leads.primary_address_city IS NOT NULL 
                    AND leads.primary_address_state IS NOT NULL 
                    AND leads.primary_address_postalcode IS NOT NULL 
                    AND leads_cstm.time_completed_job_c IS NULL
                    ";
    $ret = $db->query($sql);

    while($row = $db->fetchByAssoc($ret)){
        $record_id = $row['id'];
        $primary_address_street = $row['primary_address_street'];
        $primary_address_city = $row['primary_address_city'];
        $primary_address_state = $row['primary_address_state'];
        $primary_address_postalcode = $row['primary_address_postalcode'];

        $lead = new Lead();
        $lead-> retrieve($row['id']);
         //check time
        if($primary_address_street == "" || $primary_address_city == "" || $primary_address_state == "" || $primary_address_postalcode == ""){continue;}else{

            global $timedate;
            $time_zone = $timedate->getInstance()->userTimezone();
            date_default_timezone_set($time_zone);

            $date_created = strtotime($row['date_entered']);
            $timeAgo = time() - $date_created;
            $timeAgo = $timeAgo / 3600;

            if($timeAgo > 24){
                
                $emailBean = sendmailsg_sendDesignRequestToAdmin($record_id);
                $emailBean->mailbox_id = '6f2dae95-c53d-179f-2bc2-59f1c63b5e7e';
                if ($emailBean->send()) {
                    $emailBean->status = 'sent';
                    $emailBean->save();
                    $lead->email_send_design_status_c = 'sent';
                    //dung code- update time sent email Request Design
                    date_default_timezone_set('Australia/Melbourne');
                    $dateAUS = date('Y-m-d H:i:s', time());
                    $lead->time_request_design_c = $dateAUS;
                    $lead->save();
                    //dung code- update status for quote
                    $quote_id =  $lead->create_solar_quote_num_c;
                    $quote =  new AOS_Quotes();
                    $quote->retrieve($quote_id);
                    if($quote->id != ''){
                        $quote->stage = 'Request_Designs';
                        $quote->save();
                    }
                    
                } else {
                    if ($emailBean->status !== 'draft') {
                        $emailBean->status = 'send_error';
                        $emailBean->save();
                    } else {
                        $emailBean->status = 'send_error';
                    }
                }        
            }else{
                continue;
            }
        }
    }
    return;
}

function sendmailsg_getLatLong($address) {
   
    $array = array();
    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
 
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
 
    // If everything is cool
    if ($geo['status'] = 'OK') {
       $latitude = $geo['results'][0]['geometry']['location']['lat'];
       $longitude = $geo['results'][0]['geometry']['location']['lng'];
       $array = array('lat'=> $latitude ,'lng'=>$longitude);
    }
 
    return $array;
}

function sendmailsg_handleMultipleFileAttachments( $request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if (/*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
    
        //require_once('modules/Notes/Note.php');
        $note = new Note();
        $where = "notes.parent_id = '".$request["emails_email_templates_idb"]."' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template

        $attachments = array();

        $attachments = array_merge($attachments, $attach_list);

        foreach ($attachments as $noteId) {

            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            //$noteTemplate->parent_id = $this->id;
            //$noteTemplate->parent_type = $this->module_dir;
            $noteTemplate->parent_id = $email->id;
            $noteTemplate->parent_type = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();

            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
        //$email->attachments = array_merge($email->attachments, $noteArray);
    }
}
function sendmailsg_replaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();

    $focusName = $request['parent_type'];
    $focus = BeanFactory::getBean($focusName, $request['parent_id']);

    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        isset($request['emails_email_templates_idb']) ?
            $request['emails_email_templates_idb'] :
            null
    );
    $email->name = $emailTemplate->subject;
    $body_html_tpl = $emailTemplate->body_html;
    $body_tpl = $emailTemplate->body;

    //custom body
    $full_name = $request['parent_name'];
    $body_html_tpl = str_replace('$first_name $last_name',trim($full_name),$body_html_tpl);

    $email_1 = $focus->email1;
    $body_html_tpl = str_replace('$email1',trim($email_1),$body_html_tpl);

    $record_id_rq = $request['parent_id'];
    $body_html_tpl = str_replace('$record_id',trim($record_id_rq),$body_html_tpl);

    $quote_number = $request['quote_number'];
    $body_html_tpl = str_replace('$quote_number',trim($quote_number),$body_html_tpl);

    $lat_long_rq = $request['lat_long'];
    $body_html_tpl = str_replace('$lat,$lng',$lat_long_rq['lat'].','.$lat_long_rq['lng'],$body_html_tpl);

    $address_rq = $request['address'];
    $body_html_tpl = str_replace('$address',trim($address_rq),$body_html_tpl);

    $address_arr = explode(',',$address_rq);

    $body_html_tpl = str_replace('$primary_address_street',trim($address_arr[0]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_city',trim($address_arr[1]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_state',trim($address_arr[2]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_postalcode',trim($address_arr[3]),$body_html_tpl);

    $body_html_tpl = str_replace('$description',$request['description'],$body_html_tpl);
    //dung code- convert url correct
    $body_html_tpl = str_replace('index.php?action=EditView','https://suitecrm.pure-electric.com.au/index.php?action=EditView',$body_html_tpl);
    $body_html_tpl = str_replace('index.php?entryPoint','https://suitecrm.pure-electric.com.au/index.php?entryPoint',$body_html_tpl);

    //end
    $email->description_html = $body_html_tpl;
    $email->description = $body_tpl;

    $templateData = $emailTemplate->parse_email_template(
        array(
            'subject' => $email->name,
            'body_html' => $email->description_html,
            'body' => $email->description,
        ),
        $focusName,
        $focus,
        $macro_nv
    );

    $email->name = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description = $templateData['body'];

    return $email;
}

function sendmailsg_sendDesignRequestToAdmin($record_id){

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $quote_id =  $lead->create_solar_quote_num_c;
    $quote =  new AOS_Quotes();
    $quote->retrieve($quote_id);

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = sendmailsg_getLatLong($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "AOS_Quotes",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $quote_id,
        "quote_number" => $quote->number,
        "from_addr" => $from_address,
        "to_addrs_names" => "admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" =>"binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean =  $emailBean->retrieve($lead->email_send_design_request_id_c);
    
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    //$emailBean->save();
    //$emailBean->saved_attachments = sendmailsg_handleMultipleFileAttachments($temp_request, $emailBean);
    
    // parse and replace bean variables
    $emailBean = sendmailsg_replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();
    return $emailBean;
}
