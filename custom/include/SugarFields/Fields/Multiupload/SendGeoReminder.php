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
    //thienpb fix here
    $emailTemplate->subject = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$productType",$request['productType'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$aos_invoices_name",$request['aos_invoices_name'],$emailTemplate->subject);
    $emailTemplate->body = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body);
    $emailTemplate->body_html = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body_html);

    $email->name = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description = $emailTemplate->body;

   
    
    $email->description_html = str_replace("\$lead_first_name", $request['lead_first_name'] , $email->description_html);
    $email->description_html = str_replace("\$productType", $request['productType'] , $email->description_html);

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
// if($current_user->id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){
//     $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
// }
// else {
//     $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
// }
$from_address = "PureElectric Accounts - PureElectric &lt;accounts@pure-electric.com.au&gt;";
$account = new Account();
$account_id = $_GET['billing_account_id'];
if($account_id == "") {
    $account_id = $invoice->billing_account_id;
}
$account = $account->retrieve($account_id);

//thienpb get product name and compare
$db = DBManagerFactory::getInstance();

$sql = "SELECT aos_products_quotes.name FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
$result = $db->query($sql);

$isSTC = false;
$isVEEC = false;
$geo_name = '';
while($row = $db->fetchByAssoc($result)){
    if(strpos($row['name'],'STC') !== false){
        $isSTC = true;
    }
    if(strpos($row['name'],'VEEC') !== false){
        $isVEEC = true;
    }
}

if($isSTC == true && $isVEEC == true){
    $geo_name = "STCs/VEECs";
}else if($isSTC){
    $geo_name = "STCs";
}else if($isVEEC){
    $geo_name = "VEECs";
}else{
    $geo_name ='';
}
//end
$sea = new SugarEmailAddress; 
// Grab the primary address for the given record represented by the $bean object
$primary = $sea->getPrimaryAddress($account);

//thien fix
$productType = strtolower($_GET['productType']);
$product_type = '';
if(strpos($productType,'sanden') !== false){
    $product_type = 'Sanden';
}else if(strpos($productType,'daikin') !==false){
    $product_type = 'Daikin';
}else{
    $product_type = '';
}

$temp_request = array(
    "module" => "Emails",
    "action" => "send",
    "record" => "",
    "type" => "out",
    "send" => 1,
    "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
    "emails_email_templates_name" => "CLIENT GEO STCs/VEECs Contractor Email Follow Up",
    "emails_email_templates_idb" => "acd0d03e-e494-d298-79ce-5a057236fb84",
    "parent_type" => "Accounts",
    "parent_name" => $account->name,
    "parent_id" => $account->id,
    "from_addr" => $from_address,
    "to_addrs_names" => $account->name . "  <".$primary.">",//"binhdigipro@gmail.com",//$lead->email1,
    "cc_addrs_names" => "info@pure-electric.com.au",
    "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
    "is_only_plain_text" => false,
    "aos_invoices_name"=> $invoice->name,
    "lead_first_name"=> current(explode(' ',$account->name)),
    "geo_name" => $geo_name,
    "productType" => $product_type,
    "sendGeo_invoice_id" => $record
);
$emailBean = new Email();
$emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
$inboundEmailAccount = new InboundEmail();
$inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
if($current_user->id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa")
    $emailBean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";// Account email;
//$emailBean->save();

// parse and replace bean variables
$emailBean = customReplaceEmailVariables($emailBean, $temp_request);


$smsTemplateID = '5fcde64f-63ac-dc94-21fb-5e5ef5cf4c70';
$smsTemplate = BeanFactory::getBean(
    'pe_smstemplate',
    $smsTemplateID
);

$contact = new Contact();
$contact_id = $invoice->billing_contact_id; 
$contact = $contact->retrieve($contact_id);
$sms_body =  $smsTemplate->body_c;
$sms_body = str_replace("\$first_name", $contact->first_name, $sms_body);
$phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
$phone_number = preg_replace("/^61/", "+61", $phone_number);
$emailBean->number_client = $phone_number;
$emailBean->sms_message = strip_tags(trim(html_entity_decode($sms_body.$current_user->sms_signature_c,ENT_QUOTES)));

if ($emailBean->save(false)) {
    echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_id=' . $invoice->id . '&return_action=DetailView&record=' . $emailBean->id.'&sendGeo_invoice_id='.$record.'&email_template_id=acd0d03e-e494-d298-79ce-5a057236fb84' .'&sms_template_id='.$smsTemplateID;
    die();
  
} else {
    if ($emailBean->status !== 'draft') {
        $emailBean->status = 'send_error';
        $emailBean->save();
    } else {
        $emailBean->status = 'send_error';
    }
    echo false;
}