<?php

function customReplaceEmailVariables(Email $email, $request)
{

    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        isset($request['emails_email_templates_idb']) ?
            $request['emails_email_templates_idb'] :
            null
    );
    $email->name = "Quote #".$request['quote_number']." ".$emailTemplate->subject;
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
    $email->description_html = str_replace("Notes:", "",  $email->description_html);

    $email->description = strip_tags($email->description_html);

    return $email;
}

$record = $_GET["record_id"];
$button = $_GET['button'];
$quote = new AOS_Quotes();
$quote->retrieve($record);

if(!isset($quote->id) || $quote->id == "") {
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

$contact = new Contact();
// $contact_id = $_GET['contact_id']; /**Contact's infomation -- Sanden/Daikin */
$contact_id = $quote->billing_contact_id; /**Contact's infomation -- Customer */

$contact = $contact->retrieve($contact_id);

if($contact->phone_mobile != ""){
    $phone_info .= "M: ". $contact->phone_mobile;
    $phone_info .= " ";
}
if($contact->phone_home != ""){
    $phone_info .= "H: ". $contact->phone_home;
    $phone_info .= " ";
}
if($contact->phone_work != ""){
    $phone_info .= "W: ". $contact->phone_work;
    $phone_info .= " ";
}

/**Account's infomation --Sanden/Daikin*/
$account = new Account();
$account_id = $_GET['account_id'];
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
    "inbound_email_id" => ($quote->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
    "emails_email_templates_name" => "Sanden / Daikin Install Date",
    "emails_email_templates_idb" => "6b53f113-ba12-4978-2eb8-5a4ecacae48b",
    "parent_type" => "AOS_Quotes",
    "quote_number" => $quote->number,
    "parent_name" => $quote->name,
    "parent_id" => $quote->id,
    "from_addr" => $from_address,
    "to_addrs_names" => $account->name . "  <".$primary.">",//"binhdigipro@gmail.com",//$lead->email1,
    "cc_addrs_names" => "info@pure-electric.com.au",
    "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
    "is_only_plain_text" => false,
    "lead_name" => $quote->billing_account,
    "lead_primary_address_city" => $quote->billing_address_city,
    "aos_invoices_contact_id4_c" =>"",
    "aos_invoices_billing_contact" => $quote->billing_contact,
    "aos_invoices_install_address_c" =>  $quote->billing_address_street . " " 
                                        .  $quote->billing_address_city . " "
                                        .  $quote->billing_address_state. " "
                                        .  $quote->billing_address_postalcode,
    "aos_invoices_contact_id3_c" => $phone_info,
    // "aos_invoices_plumbing""
    "distance_to_suite_c" => "",
);
/**Check button */
if ($button == 'sanden_installer') {
    $temp_request["aos_invoices_contact_id4_c"] = $quote->plumber_new_c;
    $temp_request['distance_to_suite_c'] = str_replace(" km","",$quote->distance_to_travel_c);
} else if ($button == 'sanden_electrician') {
    $temp_request["aos_invoices_contact_id4_c"] = $quote->plumber_electrician_c;
    $temp_request['distance_to_suite_c'] = str_replace(" km","",$quote->distance_to_electrician_c);
} else {
    $temp_request["aos_invoices_contact_id4_c"] = $quote->daikin_installer_c;
    $temp_request['distance_to_suite_c'] = str_replace(" km","",$quote->distance_to_daikin_installer_c);
}
$emailBean = new Email();
$emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
$inboundEmailAccount = new InboundEmail();
$inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
$emailBean->save();

// parse and replace bean variables
$emailBean = customReplaceEmailVariables($emailBean, $temp_request);

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
if ($emailBean->save(false)) {
    //echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $emailBean->module_dir . '&return_action=DetailView&return_id=' . $emailBean->id . '&return_action=DetailView&record=' . $emailBean->id;
    echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Quotes&return_action=DetailView&return_id=' . $quote->id . '&return_action=DetailView&record=' . $emailBean->id.'&email_template_id='.$temp_request['emails_email_templates_idb'];

} else {
    if ($emailBean->status !== 'draft') {
        $emailBean->status = 'send_error';
        $emailBean->save();
    } else {
        $emailBean->status = 'send_error';
    }
    echo false;
}