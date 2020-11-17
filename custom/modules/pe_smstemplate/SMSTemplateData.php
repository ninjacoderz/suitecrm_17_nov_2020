<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$module = $_REQUEST['module_set'];
$error = false;
$msgs = array();
$data = array();
global $current_user;
$smsTemplateId = isset($_REQUEST['smsTemplateId']) && $_REQUEST['smsTemplateId'] ? $_REQUEST['smsTemplateId'] : null;

if ($bean = BeanFactory::getBean('pe_smstemplate', $smsTemplateId)) {
    $fields = array('id', 'name', 'body', 'body_html', 'subject');
    foreach ($bean as $key => $value) {
        if (in_array($key, $fields)) {
            $data[$key] = $bean->$key;
        }
    }
    switch ($module) {
        case 'Leads':
            $Bean_Parent = BeanFactory::getBean( $module ,$_REQUEST['record_id']);
            $value_first_name = $Bean_Parent->first_name;
            break;
        case 'Accounts':
            $Bean_Parent = new Account();
            $Bean_Parent->retrieve($_REQUEST['record_id']);
            $fullname = explode(' ',$Bean_Parent->name);
            $value_first_name = $fullname[0];
            break;
        case 'Contacts':
            $Bean_Parent = new Contact();
            $Bean_Parent->retrieve($_REQUEST['record_id']);
            $value_first_name = $Bean_Parent->first_name;
            break;
        case 'AOS_Quotes':
            $Bean_Parent = new AOS_Quotes();
            $Bean_Parent->retrieve($_REQUEST['record_id']);
            $fullname = explode(' ',$Bean_Parent->billing_account);
            $value_first_name = $fullname[0];
            $quote_number = $Bean_Parent->number; //VUT
            break;
        case 'AOS_Invoices':
            $Bean_Parent = new AOS_Invoices();
            $Bean_Parent->retrieve($_REQUEST['record_id']);
            $fullname = explode(' ',$Bean_Parent->billing_account);
            $value_first_name = $fullname[0];
            $quote_number = $Bean_Parent->quote_number; //VUT
            break;                      
        default:
            $value_first_name = '$first_name';
            break;
    }
    $data['body_from_html'] = trim(strip_tags(html_entity_decode(str_replace("\$first_name",$value_first_name,$bean->body_c).$current_user->sms_signature_c,ENT_QUOTES)));
    //VUT-S- $quote_number in sms template
    if (isset($quote_number)) {
        $data['body_from_html'] = trim(strip_tags(html_entity_decode(str_replace("\$quote_number",$quote_number,$bean->body_c).$current_user->sms_signature_c,ENT_QUOTES)));
    } else {
        $data['body_from_html'] = trim(strip_tags(html_entity_decode(str_replace("\$quote_number","",$bean->body_c).$current_user->sms_signature_c,ENT_QUOTES)));
    }
    //VUT-E- $quote_number in sms template
} else {
    $error = 'Email Template not found.';
}

$results = array(
    'error' => $error,
    'msgs' => $msgs,
    'data' => $data,
);

$results = json_encode($results);
if (!$results) {
    if (json_last_error()) {
        $results = array(
            'error' => 'json_encode error: '.json_last_error_msg()
        );
        $results = json_encode($results);
    }
}
echo $results;
