<?php
//Thienpb code

$lead_id = $_REQUEST["lead_id"];
//$lead_id = "885e74c5-8446-cfc4-f894-5bcd7c32066f";

$lead = new Lead();
$lead = $lead->retrieve($lead_id);

global $current_user, $mod_strings, $sugar_config;
$email = new Email();

// set the id for relationships
$email->id = create_guid();
$email->new_with_id = true;

$email->from_name = $current_user->full_name ." <". $current_user->email1.">";
$email->from_addr = $current_user->full_name ." <". $current_user->email1.">";

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
        $email->to_addrs_names = "Solargain CRM <info@pure-electric.com.au>";
    }
}

// Save the email object
global $timedate;
$email->date_start = $timedate->to_display_date_time(gmdate($GLOBALS['timedate']->get_db_date_time_format()));

/**
 * @var EmailTemplate $emailTemplate
 */ 
// 825462b3-13de-70bf-913a-5aa077d13344
$emailTemplate = BeanFactory::getBean(
    'EmailTemplates',"2361ca39-a862-e898-3cc7-5bb588b9adeb"
);
$email->name = str_replace("\$lead_solargain_quote_number_c",$lead->solargain_quote_number_c,$emailTemplate->subject);
$email->description_html = $emailTemplate->body_html;
$email->description = $emailTemplate->body;

// ATTACHMENT

$email->save(false);
$email_id = $email->id;
header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Quotes&return_action=DetailView&return_id=' . $module->id . '&record=' . $email_id);
die();