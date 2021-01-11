<?php

$type = $_REQUEST['type'];
$module = $_REQUEST['module'];
$record_id = $_REQUEST['record'];

switch ($type) {
    case 'CreateEmailPaperWork_Elec':
        $email_id = create_EmailInstallerPaperworkFollowUp($record_id,$type);
        header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $module . '&return_action=DetailView&return_id=' . $record_id . '&record=' . $email_id.'&email_template_id=2f71d3bd-77a2-1a7b-5e06-5e49d2e51988');
        break;
    case 'CreateEmailPaperWork_Plum':
        $email_id = create_EmailInstallerPaperworkFollowUp($record_id,$type);
        header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $module . '&return_action=DetailView&return_id=' . $record_id . '&record=' . $email_id.'&email_template_id=2f71d3bd-77a2-1a7b-5e06-5e49d2e51988');
        break;
    default:
        echo 'Forbidden';die();
        break;
}

function create_EmailInstallerPaperworkFollowUp($record_id,$type){
    $macro_nv = array();
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);

    if(!$focus->id) return '';

    $emailTemplateID = '2f71d3bd-77a2-1a7b-5e06-5e49d2e51988';
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        $emailTemplateID
    );
    //get purchase order
    $PO_bean = new PO_purchase_order();
    switch ($type) {
        case 'CreateEmailPaperWork_Elec':
            $PO_bean->retrieve($focus->electrical_po_c);
            break;
        case 'CreateEmailPaperWork_Plum':
            $PO_bean->retrieve($focus->plumber_po_c);
            break;
        default:
            break;
    }

    //get email from contact
    $account_bean = new Account();
    $account_bean->retrieve($PO_bean->billing_account_id);

    $name = $emailTemplate->subject;
    $description_html = $emailTemplate->body_html;
    $description = $emailTemplate->body;


    //parse value
    $description = str_replace("\$account_name",$account_bean->name , $description);
    $description_html = str_replace("\$account_name",$account_bean->name , $description_html);
    $description = str_replace("\$po_purchase_order_number",$PO_bean->number , $description);
    $description_html = str_replace("\$po_purchase_order_number",$PO_bean->number , $description_html);
    $description = str_replace("\$po_purchase_order_name",$PO_bean->name , $description);
    $description_html = str_replace("\$po_purchase_order_name",$PO_bean->name , $description_html);
    $name = str_replace("\$account_name",$account_bean->name , $name);
    $name = str_replace("\$po_purchase_order_number",$PO_bean->number , $name);
    $name = str_replace("\$po_purchase_order_name",$PO_bean->name , $name);

    //create email 
    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "draft";
    $email->status = "draft";
    $email->parent_type = 'Contacts';
    $email->parent_id = $contact_bean->id;
    $email->parent_name = $contact_bean->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description;
    $email->save(false);
    $email_id = $email->id;

    $attachmentBeans = $emailTemplate->getAttachments();

    if($attachmentBeans) {
        foreach($attachmentBeans as $attachmentBean) {

            $noteTemplate = clone $attachmentBean;
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; 
            $noteTemplate->parent_id = $email->id;
            $noteTemplate->parent_type = 'Emails';
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

            $noteTemplate->save();
            $email->attachNote($noteTemplate);
        }
    }

    $email->from_addr = "accounts@pure-electric.com.au";
    $email->from_name = "Pure Electric Accounts";
    $email->to_addrs_names = $account_bean->name . " <" . $account_bean->email1 . ">";
    $email->save();
    return $email->id;
}