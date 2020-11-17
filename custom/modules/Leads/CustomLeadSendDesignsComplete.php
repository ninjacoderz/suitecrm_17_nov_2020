<?php

function updateSolargainLead($leadID, $request, $email){
    $lead = new Lead();
    $lead->retrieve($leadID);
    if(!$lead->solargain_lead_number_c) {
        return;
    }
    $solargainLead = $lead->solargain_lead_number_c;
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    
    $username = "matthew.wright";
    $password =  "MW@pure733";
    
    // Get full json response for Leads

    $url = "https://crm.solargain.com.au/APIv2/leads/". $solargainLead;
    //set the url, number of POST vars, POST data

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: 	gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
            "Cache-Control: max-age=0"
        )
    );
    
    $leadJSON = curl_exec($curl);
    curl_close ( $curl );

    $leadSolarGain = json_decode($leadJSON);
    global $current_user;
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "";
    if(isset($email->from_name) && $email->from_name != ""){
        $note = $current_user->full_name. " : ". $email->from_name. " : ".$request["emails_email_templates_name"];
    }
    /*else {
        $note = $current_user->full_name. " : ".$request["emails_email_templates_name"];
    }*/
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>5,
            "Name"=>"E-Mail Out",
            "RequiresComment"=> true
        ),
        "Text"=> $note
    );

    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: 	gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($leadSolarGainJSONDecode),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
        )
    );
    
    $lead = json_decode(curl_exec($curl));
    curl_close ( $curl );
}

function handleMultipleFileAttachments( $request, $email)
{
    

    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if (/*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
    
        require_once('modules/Notes/Note.php');
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

function replaceEmailVariables(Email $email, $request)
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
    $email->description_html = $emailTemplate->body_html;
    $email->description = $emailTemplate->body;
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

function get_lat_long($address) {
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

$record_id = urldecode($_GET['record_id']);

$assigned_user_id = urldecode($_GET['assigned_user_id']);
$user = new User();
$user->retrieve($assigned_user_id);
$assigned_user_email = $user->email1;

$lead = new Lead();
$lead = $lead->retrieve($record_id);

$firstName = $lead->first_name;
$lastName = $lead->last_name;
$designerName = $lead->designer_c;
$email1 = $lead->email1;
if ($email1 != '')
{
    $email1 = '<a href="mailto:' . $email1 . '">[' . $email1 . ']</a> ';
}
$email2 = $lead->email2;
if ($email2 != '')
{
    $email2 = '<a href="mailto:' . $email2 . '">[' . $email2 . ']</a> ';
}

$sgnumber = $lead->solargain_lead_number_c;
if ($sgnumber != '' &&  $lead->solargain_quote_number_c == '')
{
    $sgnumber = '<a target="_blank" href="https://crm.solargain.com.au/lead/edit/' . $sgnumber . '">[S'.$sgnumber.']</a>';
}

if ($lead->solargain_quote_number_c != '')
{
    $sgquotenumber = '<a target="_blank" href="https://crm.solargain.com.au/quote/edit/' . $lead->solargain_quote_number_c . '">[SG'.  $lead->solargain_quote_number_c .']</a>';
}
date_default_timezone_set('Australia/Melbourne');
$dateAUS = date('Y-m-d H:i:s', time());
$lead->time_completed_job_c = $dateAUS;
if ($lead->time_accepted_job_c == '')
{
    $lead->time_accepted_job_c = $dateAUS;
}

global $current_user;
if ($lead->user_id_c == '')
{
    $lead->user_id_c = $current_user->id;
}

$lead->save();
$address = $_GET["primary_address_street"] . ", " . 
            $_GET["primary_address_city"] . ", " . 
            $_GET["primary_address_state"] . ", " . 
            $_GET["primary_address_postalcode"] ;
$lat_long = get_lat_long($address);

$assigned_user_name = $_GET["assigned_user_name"];

require_once('include/SugarPHPMailer.php');
$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->IsHTML(true);
$mail->Subject = 'Designs Complete - ' . $firstName . ' ' . $lastName . ' ' . ' ' . $address;

$body = '
<div dir="ltr">
Dear ' . $assigned_user_name . ',
<br>Your solar designs for ' . $firstName . ' ' . $lastName . ' ' . $email1 . $email2
. $_GET["primary_address_street"] . ' '
. $_GET["primary_address_city"]. ' '
. $_GET["primary_address_state"]. ' '
. $_GET["primary_address_postalcode"] . ' are ready.<br>Designed by ' . $designerName .
'.<br>
<a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">[Edit Lead]</a> '
. $sgnumber . $sgquotenumber.'<a target="_blank" href="https://mail.google.com/#search/'.$lead->email1.'">[GM Search]</a>';
'</div>';

$db = DBManagerFactory::getInstance();
$sql = "
SELECT id_c, time_completed_job_c FROM leads_cstm lcstm INNER JOIN leads lds ON lds.id = lcstm.id_c 
WHERE user_id_c != '' 
AND time_accepted_job_c != '' 
AND time_sent_to_client_c IS NULL 
AND (time_completed_job_c IS NOT NULL AND time_completed_job_c != '' )
AND lds.status NOT IN ('Lost_Competitor', 'Lost_Uncontactable', 'Lost_Unsuitable_Roof', 'Lost_Enquiry_Only', 'Lost_No_Longer_Interested', 'Lost_Outside_Service_Area', 'Lost_Duplicate', 'Lost_Council', 'Lost_Reassigned_To_Solorgain') 
AND ( (lcstm.solargain_quote_number_c != '' AND lcstm.solargain_quote_number_c IS NOT NULL) OR (lcstm.solargain_tesla_quote_number_c != '' AND lcstm.solargain_tesla_quote_number_c IS NOT NULL) ) 
ORDER BY time_accepted_job_c desc LIMIT 50
"; //(time_completed_job_c IS NULL OR 
$ret = $db->query($sql);

$bottom = '';

while ($row = $db->fetchByAssoc($ret))
{
    $leadId = $row['id_c'];
    $leadToSend = new Lead();
    $leadToSend = $leadToSend->retrieve($leadId);

    //$date = date("d/m/Y H:i:s", strtotime(str_replace('/', '-', $row['time_completed_job_c'])));

    // Thienpb code 
    $assigned_by = '';
    if($leadToSend->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew
        $assigned_by = "Matthew Wright";
    }elseif($leadToSend->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
        $assigned_by = "Paul Szuster";
    }
    $date =  $leadToSend->time_accepted_job_c;
    //end

    /*if ($leadToSend->time_sent_to_client_c != '' ||
        strtotime($date) < strtotime("2017-12-22 00:00"))
    {
        continue;
    }*/

    $sglink = '';

    if ($leadToSend->solargain_lead_number_c != '' && $leadToSend->solargain_quote_number_c == '' &&  $leadToSend->solargain_quote_number_c == '')
    {
        $sglink = '<a target="_blank" href="https://crm.solargain.com.au/lead/edit/' . $leadToSend->solargain_lead_number_c . '">[S'. $leadToSend->solargain_lead_number_c .']</a>';
    }

    $sgquote = '';

    if ($leadToSend->solargain_quote_number_c != '')
    {
        $sgquote = '<a target="_blank" href="https://crm.solargain.com.au/quote/edit/' . $leadToSend->solargain_quote_number_c . '">[S'. $leadToSend->solargain_quote_number_c .']</a>';
    }

    $sgteslaquote = '';

    if ($leadToSend->solargain_tesla_quote_number_c != '')
    {
        $sgteslaquote = '&nbsp;&nbsp;<a target="_blank" href="https://crm.solargain.com.au/quote/edit/' . $leadToSend->solargain_tesla_quote_number_c . '">[S'. $leadToSend->solargain_tesla_quote_number_c .']</a>';
    }
    $bottom = $bottom .
    '<br>' . $leadToSend->name .
    ' <a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $leadId . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">[Edit Lead]</a> '
    . $sglink . $sgquote . $sgteslaquote
     . '<a target="_blank" href="https://mail.google.com/#search/'.$leadToSend->email1.'">[GSearch] </a>'
     . $leadToSend->designer_c . '. Assigned: '.$assigned_by.'. Date: ' .str_replace("2019", "19", str_replace("2018","18",$date));// Thienpb fix
}

if ($bottom != '')
{
    $bottom = '<br>————————————<br>Solar Designs Not Sent Client ' . $bottom;
}

$mail->Body = $body . $bottom;

$mail->prepForOutbound();
$mail->AddAddress('binhdigipro@gmail.com');
$mail->AddAddress($assigned_user_email);
$mail->AddAddress('admin@pure-electric.com.au');
$mail->AddCC('info@pure-electric.com.au');
$sent = $mail->Send();

// thienpb fix Send design complete to lead 
if($lead->do_not_email_c != 1){
    $inbound_email_id = "";
    if($lead->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew
        $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
        $inbound_email_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
    }
    // elseif($lead->assigned_user_id == "625af0bf-fcd5-40b9-8447-5a725252b8fb"){
    //     $from_address = "Ross Munro - PureElectric &lt;ross.munro@pure-electric.com.au&gt;";
    //     $inbound_email_id = "501abe0a-5a45-ed9d-e033-5a741f34860e";
    // }
    else {
        // $from_address = "Lee Andrewartha - PureElectric &lt;lee.andrewartha@pure-electric.com.au&gt;";
        // $inbound_email_id = "acc160eb-02b9-4ad8-78cc-5ad4133b7406";
        $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
        $inbound_email_id = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
    }
    $design_complete_template_title = "Designs Complete First";
    $design_complete_template_id = "5a36a733-f6c1-39b3-a736-5a940feae542";
    if($lead->address_provided_c == 1){
        $design_complete_template_title = "Designs Complete Second";
        $design_complete_template_id = "4f86b77f-94a4-1523-5194-59ed8f28e5c0";
    }
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => $inbound_email_id,
        "emails_email_templates_name" => $design_complete_template_title,
        "emails_email_templates_idb" => $design_complete_template_id,
        "parent_type" => "Leads",
        "parent_name" => $lead->first_name + $lead->last_name,
        "parent_id" => $lead->id,
        "from_addr" => $from_address,
        "to_addrs_names" => $lead->email1,//"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    $emailBean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";// Thienpb fix relayuser
    $emailBean->save();
    $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = replaceEmailVariables($emailBean, $temp_request);
    
    // Signature
    $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
    $paul_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
    $user->retrieve($matthew_id);
    if($lead->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew 
    
        $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
    // } elseif($lead->assigned_user_id == "625af0bf-fcd5-40b9-8447-5a725252b8fb"){
    //     $emailSignatureId = "7ac5a4fd-b086-2bcc-aa40-5a741cf9baca";
    } else {
        //$user->retrieve($paul_id);
        // thienpb fix 
        $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
        // Lee signature
        //$emailSignatureId = "6f14eb50-e31f-b1de-194e-5ad439e971fa"; 
    }


    $signature = $user->getSignature($emailSignatureId);
    $emailBean->description .= $signature["signature"];
    $emailBean->description_html .= $signature["signature_html"];
    // File attachment have just finished
    $lead_file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead->installation_pictures_c ."/");
    $noteArray = array();
    if (count($lead_file_attachmens)>0) foreach ($lead_file_attachmens as $att){
        // Create Note
        if(strpos($att, "Bill") !== false) continue;
        if(strpos(strtolower($att), "design") !== false){
            $source =  realpath(dirname(__FILE__) . '/../../').'/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead->installation_pictures_c ."/" . $att ;
            if(!is_file($source)) continue;
            
            $noteTemplate = new Note();
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            $noteTemplate->parent_id = $this->id;
            $noteTemplate->parent_type = $this->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->filename = $att;
            
            $noteTemplate->save();

            $destination = realpath(dirname(__FILE__) . '/../../../').'/upload/'.$noteTemplate->id;
            //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
            copy( $source, $destination);
            $noteArray[] = $noteTemplate;
        }
    }
    $emailBean->saved_attachments = array_merge($emailBean->saved_attachments, $noteArray);

    if ($emailBean->send()) {
        $emailBean->status = 'sent';
        // Do extended things here
        // Save note to solargain
        if($temp_request["parent_type"] == "Leads"){
            $leadID = $temp_request["parent_id"];
            updateSolargainLead($leadID, $temp_request, $emailBean);
        }
        $emailBean->save();
    } else {
            if ($emailBean->status !== 'draft') {
                $emailBean->status = 'send_error';
                $emailBean->save();
            } else {
                $emailBean->status = 'send_error';
            }
    }

    // In this case we also need store send to client time
    if ($lead->time_sent_to_client_c == '')
    {
        $lead->time_sent_to_client_c = $dateAUS;
    }
}
$time_send_client = ($lead->time_sent_to_client_c != "")? date("d/m/Y H:i:s", strtotime($lead->time_sent_to_client_c)):"";
$return_array = array(
    "time_complete" => date("d/m/Y H:i:s", strtotime($lead->time_completed_job_c)),
    "time_sent_client" => $time_send_client,
);
echo json_encode($return_array);
$lead->save();

?>