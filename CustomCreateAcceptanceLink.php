<?php

$lead_id = $_REQUEST["lead_id"];

//header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $module_type . '&return_action=DetailView&return_id=' . $module->id . '&record=' . $email_id);

$lead = new Lead();
$lead = $lead->retrieve($lead_id);


global $current_user, $mod_strings, $sugar_config;
$email = new Email();
// set the id for relationships
$email->id = create_guid();
$email->new_with_id = true;

$email->from_name = $current_user->full_name ." <". $current_user->email1.">";
$email->from_addr = $current_user->full_name ." <". $current_user->email1.">";

// subject
$email->name = "PureElectric ".$mod_strings['LBL_EMAIL_NAME'] . ' ' . $module->name;
//BinhNT
// body
$email->description_html = $printable;
// type is draft
$email->type = "draft";
$email->status = "draft";

if (!empty($module->billing_contact_id)) {
    $contact_id = $module->billing_contact_id;
} else {
    if (!empty($module->contact_id)) {
        $contact_id = $module->contact_id;
    }
}

// TODO: FIX UID / Inbound Email Account
$inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
$email->mailbox_id = $inboundEmailID;


if ($lead->id) {
    $email->parent_type = 'Leads';
    $email->parent_id = $lead->id;

    if (!empty($lead->email1)) {
        $email->to_addrs_emails = $lead->email1 . ";";
        $email->to_addrs = $lead->name . " <" . $lead->email1 . ">";
        $email->to_addrs_names = $lead->name . " <" . $lead->email1 . ">";
        $email->parent_name = $lead->name;
    }
}


// team id
$email->team_id = $current_user->default_team;
// assigned_user_id
$email->assigned_user_id = $current_user->id;

//parsing description
// Save the email object
global $timedate;
$email->date_start = $timedate->to_display_date_time(gmdate($GLOBALS['timedate']->get_db_date_time_format()));

/**
 * @var EmailTemplate $emailTemplate
 */ 
// 825462b3-13de-70bf-913a-5aa077d13344
$emailTemplate = BeanFactory::getBean(
    'EmailTemplates',"825462b3-13de-70bf-913a-5aa077d13344"
);
$email->name = $emailTemplate->subject;
$email->description_html = $emailTemplate->body_html;
$email->description = $emailTemplate->body;

$email->description_html = str_replace("FIRSTNAME", $lead->name, $email->description_html);
$email->description = str_replace("FIRSTNAME", $lead->name, $email->description);

$email->description_html = str_replace("QUOTATION #\$lead_solargain_quote_number_c", "QUOTATION #$lead->solargain_quote_number_c", $email->description_html);
$email->description = str_replace("QUOTATION #\$lead_solargain_quote_number_c", "QUOTATION #$lead->solargain_quote_number_c", $email->description);

// ATTACHMENT

/*$note = new Note();
$note->modified_user_id = $current_user->id;
$note->created_by = $current_user->id;
$note->name = $file;
$note->parent_type = 'Emails';
$note->parent_id = $email->id;
$note->file_mime_type = mime_content_type ( $current_file_path . '/' . $file );
$note->filename = $file; 
$noteId = $note->save();

if($noteID !== false && !empty($noteId)) {
    copy($current_file_path . '/' . $file, $sugar_config['upload_dir'] . $note->id);
    $email->attachNote($note);
} else {
    $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
}
*/

date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

$username = "matthew.wright";
$password = "MW@pure733";

$url = "https://crm.solargain.com.au/APIv2/leads/". $solargainLead;
//set the url, number of POST vars, POST data

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($leadSolarGainJSONDecode),
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
    )
);

$lead = json_decode(curl_exec($curl));
$destination = dirname(__FILE__)."/files/invoice-". $recordID.".pdf";
$file = fopen($destination, "w+");
fputs($file, $body);
fclose($file);
curl_close($curl);


$email->save(false);
$email_id = $email->id;
header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Quotes&return_action=DetailView&return_id=' . $module->id . '&record=' . $email_id);
die();