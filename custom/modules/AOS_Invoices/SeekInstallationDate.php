<?php 

function customReplaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    //$macro_nv = array();

    //$focusName = $request['parent_type'];
    //$focus = BeanFactory::getBean($focusName, $request['parent_id']);

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

    $email->name = str_replace("\$lead_name", $request['lead_name'] , $email->name);// $templateData['subject'];
    //lead_primary_address_city
    $email->name = str_replace("\$lead_primary_address_city", $request['lead_primary_address_city'] , $email->name);// $templateData['subject'];
    
    $email->description_html = str_replace("\$aos_invoices_contact_id4_c", $request['aos_invoices_contact_id4_c'] , $email->description_html);
    $email->description_html = str_replace("\$aos_invoices_billing_contact", $request['aos_invoices_billing_contact'] , $email->description_html);
    $email->description_html = str_replace("\$aos_invoices_install_address_c", $request['aos_invoices_install_address_c'] ,  $email->description_html);
    $email->description_html = str_replace("\$aos_invoices_contact_id3_c", $request['aos_invoices_contact_id3_c'] ,  $email->description_html);
    $email->description_html = str_replace("\$aos_invoices_plumbing_notes_c", $request['aos_invoices_plumbing_notes_c'] ,  $email->description_html);
    $email->description_html = str_replace("Distance from base:", "Distance from base: ".$request['distance_to_suite_c'] ,  $email->description_html);
    $email->description = strip_tags($email->description_html);

    return $email;
}

$record = $_GET["record_id"];
$invoice = new AOS_Invoices();
$invoice->retrieve($record);

if(!isset($invoice->id) || $invoice->id == "") {
    die();
}
global $current_user;
if($current_user->id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){
    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
}
else {
    $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
}

$phone_info ="";
$plumbner_contact = BeanFactory::getBean("Contacts", $invoice->contact_id3_c);

if($plumbner_contact->phone_mobile != ""){
    $phone_info .= "M: ". $plumbner_contact->phone_mobile;
    $phone_info .= " ";
}
if($plumbner_contact->phone_home != ""){
    $phone_info .= "H: ". $plumbner_contact->phone_home;
    $phone_info .= " ";
}
if($plumbner_contact->phone_work != ""){
    $phone_info .= "W: ". $plumbner_contact->phone_work;
    $phone_info .= " ";
}

$account = new Account();
$account_id = $_GET['account_id'];
if($account_id == "") {
    $account_id = $invoice->account_id1_c;
}
$account = $account->retrieve($account_id);

$sea = new SugarEmailAddress; 
// Grab the primary address for the given record represented by the $bean object
$primary = $sea->getPrimaryAddress($account);
$temp_request = array(
    "module" => "Emails",
    "action" => "send",
    "record" => "",
    "type" => "out",
    "send" => 1,
    "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
    "emails_email_templates_name" => "Sanden / Daikin Install Date",
    "emails_email_templates_idb" => "6b53f113-ba12-4978-2eb8-5a4ecacae48b",
    "parent_type" => "AOS_Invoices",
    "parent_name" => $invoice->name,
    "parent_id" => $invoice->id,
    "from_addr" => $from_address,
    "to_addrs_names" => $account->name . "  <".$primary.">",//"binhdigipro@gmail.com",//$lead->email1,
    "cc_addrs_names" => "info@pure-electric.com.au",
    "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
    "is_only_plain_text" => false,
    "lead_name" => $invoice->billing_account,
    "lead_primary_address_city" => $invoice->billing_address_city,
    "aos_invoices_contact_id4_c" => $invoice->plumber_contact_c,
    "aos_invoices_billing_contact" => $invoice->site_contact_c,
    "aos_invoices_install_address_c" =>  $invoice->install_address_c . " " 
                                        .  $invoice->install_address_city_c . " "
                                        .  $invoice->install_address_state_c. " "
                                        .  $invoice->install_address_postalcode_c,
    "aos_invoices_contact_id3_c" => $phone_info,
    "aos_invoices_plumbing_notes_c" => $invoice->plumbing_notes_c,
    "distance_to_suite_c" => str_replace(" km","",$invoice->distance_to_suite_c),
);
$emailBean = new Email();
$emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
$inboundEmailAccount = new InboundEmail();
$inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
$emailBean->save();

// parse and replace bean variables
$emailBean = customReplaceEmailVariables($emailBean, $temp_request);
$url_calendar = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$invoice->installation_calendar_id_c.'/plumber/'.$account_id;
$html_link_calendar = 'Here is the installation calendar URL :<a href="'.$url_calendar.'">'.$url_calendar.'</a>';
$emailBean->description_html .=   $html_link_calendar;
$emailBean->description .=   $html_link_calendar;
//VUT - S - Copy SMS message = Email message
    /**Get Contact Plumber */
    $plumber  = new Contact();
    $plumber->retrieve($invoice->contact_id4_c);
$phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $plumber->phone_mobile));
$phone_number = preg_replace("/^61/", "+61", $phone_number);
$emailBean->number_client =  $phone_number; 
$emailBean->sms_message = trim(strip_tags(html_entity_decode($emailBean->description), ENT_QUOTES)); 
//VUT - E - Copy SMS message = Email message

// Signature
$matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
$paul_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
$user = new User();
$user->retrieve($matthew_id);



if($current_user->id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew 
    $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
} else {
    $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
}


$signature = $user->getSignature($emailSignatureId);
//comment because : double signature when click seek install date from invoice
// $emailBean->description .= $signature["signature"];
// $emailBean->description_html .= $signature["signature_html"];

if ($emailBean->save(false)) {
    // echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $emailBean->module_dir . '&return_action=DetailView&return_id=' . $emailBean->id . '&return_action=DetailView&record=' . $emailBean->id;
    echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_id=' . $invoice->id . '&return_action=DetailView&record=' . $emailBean->id."&email_template_id={$temp_request['emails_email_templates_idb']}";
    die();
    //$emailBean->status = 'sent';
    // Do extended things here
    // Save note to solargain
    /*if($temp_request["parent_type"] == "Leads"){
        $leadID = $temp_request["parent_id"];
        //updateSolargainLead($leadID, $temp_request, $emailBean);
    }*/
    //$emailBean->save();
    //echo true;
} else {
    if ($emailBean->status !== 'draft') {
        $emailBean->status = 'send_error';
        $emailBean->save();
    } else {
        $emailBean->status = 'send_error';
    }
    echo false;
}
