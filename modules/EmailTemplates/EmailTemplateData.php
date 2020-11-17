<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

function handleAttachmentForRemove()
{
    if (!empty($_REQUEST['attachmentsRemove'])) {
        foreach ($_REQUEST['attachmentsRemove'] as $attachmentIdForRemove) {
            if ($bean = BeanFactory::getBean('Notes', $attachmentIdForRemove)) {
                $bean->mark_deleted($bean->id);
            }
        }
    }
}

$error = false;
$msgs = array();
$data = array();

$emailTemplateId = isset($_REQUEST['emailTemplateId']) && $_REQUEST['emailTemplateId'] ? $_REQUEST['emailTemplateId'] : null;
if (isset($_REQUEST['campaignId'])) {
    $_SESSION['campaignWizard'][$_REQUEST['campaignId']]['defaultSelectedTemplateId'] = $emailTemplateId;
}

if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $emailTemplateId) || !$emailTemplateId) {
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : null;

    $fields = array('body_html', 'subject', 'name');

    // TODO: validate for email template before save it!

    include_once 'modules/EmailTemplates/EmailTemplateFormBase.php';

    switch ($func) {

        case 'update':
            $bean = BeanFactory::getBean('EmailTemplates', $emailTemplateId);
            foreach ($bean as $key => $value) {
                if (in_array($key, $fields)) {
                    $bean->$key = $_POST[$key];
                }
            }
            $formBase = new EmailTemplateFormBase();
            $bean = $formBase->handleAttachmentsProcessImages($bean, false, true, 'download', true);
            if ($bean->save()) {
                $msgs[] = 'LBL_TEMPLATE_SAVED';
            }
            //$formBase = new EmailTemplateFormBase();
            //$bean = $formBase->handleAttachmentsProcessImages($bean, false, true);
            $data['id'] = $bean->id;
            $data['name'] = $bean->name;
            handleAttachmentForRemove();

            // update marketing->template_id if we have a selected marketing..
            if (!empty($_REQUEST['campaignId']) && !empty($_SESSION['campaignWizard'][$_REQUEST['campaignId']]['defaultSelectedMarketingId'])) {
                $marketingId = $_SESSION['campaignWizard'][$_REQUEST['campaignId']]['defaultSelectedMarketingId'];

                $campaign = BeanFactory::getBean('Campaigns', $_REQUEST['campaignId']);
                $campaign->load_relationship('emailmarketing');
                $marketings = $campaign->emailmarketing->get();
                // just a double check for campaign->marketing relation correct is for e.g the user deleted the marketing record or something may could happened..
                if (in_array($marketingId, $marketings)) {
                    $marketing = BeanFactory::getBean('EmailMarketing', $marketingId);
                    $marketing->template_id = $emailTemplateId;
                    $marketing->save();
                } else {
                    // TODO something is not OK, the selected campaign isn't related to this marketing!!
                    $GLOBALS['log']->debug('Selected marketing not found!');
                }
            }
            break;

        case 'createCopy':
            $bean = BeanFactory::getBean('EmailTemplates', $emailTemplateId);
            $newBean = BeanFactory::newBean('EmailTemplates');
            $fieldsForCopy = array('type', 'description');
            foreach ($bean as $key => $value) {
                if (in_array($key, $fields)) {
                    $newBean->$key = $_POST[$key];
                } else {
                    if (in_array($key, $fieldsForCopy)) {
                        $newBean->$key = $bean->$key;
                    }
                }
            }
            $newBean->assigned_user_id = $GLOBALS['current_user']->id;
            if ($newBean->save()) {
                $msgs[] = 'LBL_TEMPLATE_SAVED';
            }
            //$formBase = new EmailTemplateFormBase();
            //$newBean = $formBase->handleAttachmentsProcessImages($newBean, false, true);
            $data['id'] = $newBean->id;
            $data['name'] = $newBean->name;
            break;

        case 'uploadAttachments':
            $formBase = new EmailTemplateFormBase();
            $focus = BeanFactory::getBean('EmailTemplates', $_REQUEST['attach_to_template_id']);
            //$data = $formBase->handleAttachments($focus, false, null);
            $data = $formBase->handleAttachmentsProcessImages($focus, false, true, 'download', true);
            $redirectUrl = 'index.php?module=Campaigns&action=WizardMarketing&campaign_id=' . $_REQUEST['campaign_id'] . "&jump=2&template_id=" . $_REQUEST['attach_to_template_id']; // . '&marketing_id=' . $_REQUEST['attach_to_marketing_id'] . '&record=' . $_REQUEST['attach_to_marketing_id'];
            header('Location: ' . $redirectUrl);
            die();
            break;

        default: case 'get':
            if ($bean = BeanFactory::getBean('EmailTemplates', $emailTemplateId)) {
                $fields = array('id', 'name', 'body', 'body_html', 'subject');
                //VUT-S-Email Template-Change variable at Accounts   >> acb45043-691d-9bfc-432e-59f9cc15a870 PureElectric / New Sanden Plumber
                if ($emailTemplateId == 'acb45043-691d-9bfc-432e-59f9cc15a870' && $_REQUEST['parent_type'] == 'Accounts') {
                    $account = new Account();
                    $account = $account->retrieve($_REQUEST['parent_id']);
                    if ($account->id != '') {
                        $bean->subject = str_ireplace("\$account_name",$account->name,$bean->subject);
                        $bean->body_html = str_ireplace("\$account_billing_address_state", $account->billing_address_state, $bean->body_html);
                    }
                }else if ($emailTemplateId == '872b8b71-0374-c4ee-50aa-5f0e99e1728a' ) { //'b02309a5-289c-7ebe-6c0d-5f101d2ac861'
                    $account = new Account();
                    $account = $account->retrieve($_REQUEST['parent_id']);
                    $lead = new Lead();
                    $lead = $lead->retrieve($_REQUEST['parent_id']);
                    $contact = new Contact();
                    $contact = $contact->retrieve($_REQUEST['parent_id']);
                    if($account != ""){
                        if ($account->id != '') {
                            $bean->subject = str_ireplace("\$aos_invoices_name",$account->name,$bean->subject);
                            $bean->body_html = str_ireplace("\$aos_customer_name",$account->name, $bean->body_html);
                            $bean->body_html = str_ireplace("\$aos_invoices_install_address_c", $account->billing_address_street." ".$account->billing_address_city." ".$account->billing_address_state." ".$account->billing_address_postalcode, $bean->body_html);
                        }
                    }else if($lead != ""){
                        $bean->subject = str_ireplace("\$aos_invoices_name",$lead->account_name,$bean->subject);
                        $bean->body_html = str_ireplace("\$aos_customer_name",$lead->account_name, $bean->body_html);
                        $bean->body_html = str_ireplace("\$aos_invoices_install_address_c", $lead->primary_address_street." ".$lead->primary_address_city." ".$lead->primary_address_state." ".$lead->primary_address_postalcode, $bean->body_html);
                    }else if ($contact !=""){
                        $bean->subject = str_ireplace("\$aos_invoices_name",$contact->account_name,$bean->subject);
                        $bean->body_html = str_ireplace("\$aos_customer_name",$contact->account_name, $bean->body_html);
                        $bean->body_html = str_ireplace("\$aos_invoices_install_address_c", $contact->primary_address_street." ".$contact->primary_address_city." ".$contact->primary_address_state." ".$contact->primary_address_postalcode, $bean->body_html);
                    }   
                }
                else if ($emailTemplateId == '189997bb-975f-03d7-cd0d-5f7537e26b43' ) { 
                    $quote = new AOS_Quotes();
                    $quote = $quote->retrieve($_REQUEST['parent_id']);
                    $lead = new Lead();
                    $lead = $lead->retrieve($quote->leads_aos_quotes_1leads_ida);
    
                    $bean->subject = str_ireplace("\$aos_quotes_number",$quote->number,$bean->subject);
                    $bean->subject = str_ireplace("\$aos_quotes_name",$quote->name, $bean->subject);
                    $bean->body_html = str_ireplace("\$lead_first_name",$lead->first_name, $bean->body_html);  
                }
                //VUT-E-Email Template-Change variable at Accounts   >> acb45043-691d-9bfc-432e-59f9cc15a870 PureElectric / New Sanden Plumber
                foreach ($bean as $key => $value) {
                    if (in_array($key, $fields)) {
                        $data[$key] = $bean->$key;
                    }
                }

                $data['body_from_html'] = from_html($bean->body_html);
                $attachmentBeans = $bean->getAttachments();
                if ($attachmentBeans) {
                    $attachments = array();
                    foreach ($attachmentBeans as $attachmentBean) {
                        $attachments[] = array(
                            'id' => $attachmentBean->id,
                            'name' => $attachmentBean->name,
                            'file_mime_type' => $attachmentBean->file_mime_type,
                            'filename' => $attachmentBean->filename,
                            'parent_type' => $attachmentBean->parent_type,
                            'parent_id' => $attachmentBean->parent_id,
                            'description' => $attachmentBean->description,
                        );
                    }
                    $data['attachments'] = $attachments;
                }
            } else {
                $error = 'Email Template not found.';
            }
            break;
    }
} else {
    $error = 'Illegal GUID format.';
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
