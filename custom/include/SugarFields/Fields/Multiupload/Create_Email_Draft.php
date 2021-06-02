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
    case 'CreateEmailGEOFollowUp_System_Owner':
        create_CreateEmailGEOFollowUp($record_id,$type,$module);
        break;
    case 'CreateEmailGEOFollowUp_Installer':
        create_CreateEmailGEOFollowUp($record_id,$type,$module);
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
    $email->parent_type = 'Accounts';
    $email->parent_id = $account_bean->id;
    $email->parent_name = $account_bean->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description;
    $email->sms_message = strip_tags(trim(html_entity_decode($description_html,ENT_QUOTES)));
    $email->number_client =  $account_bean->mobile_phone_c;
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

function create_CreateEmailGEOFollowUp($record_id,$type) {
    $macro_nv = array();
    $focusName = "AOS_Invoices";
    $Invoice = BeanFactory::getBean($focusName, $record_id);

    if(!$Invoice->id) return '';

    //get email teamplate
    switch ($type) {
        case 'CreateEmailGEOFollowUp_System_Owner':
            $emailTemplateID = 'acd0d03e-e494-d298-79ce-5a057236fb84';
            break;
        case 'CreateEmailGEOFollowUp_Installer':
            $emailTemplateID = '6b4a9555-3fad-266b-095f-5f69a004a7a9';
            break;
        default:
            break;
    }

    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        $emailTemplateID
    );

    //get email from contact
    $account = new Account();
    $account = $account->retrieve($Invoice->billing_account_id);

    
    $installer = new Account();
    $installer_id = $Invoice->account_id1_c;
    if($installer_id == ''){
        $installer_id = $Invoice->account_id_c;
    }
    $installer = $installer->retrieve($installer_id);
    
    $sea = new SugarEmailAddress; 
    $primaryAdd = $sea->getPrimaryAddress($account);

    //prepare variables
    $db  = DBManagerFactory::getInstance();
    $sql = "SELECT aos_products_quotes.name FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$Invoice->id."' AND deleted = 0";
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


 
    $query_groupname = "SELECT aos_line_item_groups.name FROM aos_line_item_groups WHERE parent_id = '".$invoice_id."' AND deleted = 0 LIMIT 1";
    $ret_groupname = $db->query($query_groupname);
    if($ret_groupname->num_rows >0){
        $row_groupname = $db->fetchByAssoc($ret_groupname);
        $productType = strtolower($row_groupname['name']);
        
        if(strpos($productType,'sanden') !== false){
            $product_type = 'Sanden';
        }else if(strpos($productType,'daikin') !==false){
            $product_type = 'Daikin';
        }else{
            $product_type = '';
        }
    }
    if($Invoice->installation_date_c != '') {
        $dateInfos = explode(" ",$Invoice->installation_date_c);
        $dateInfos = explode("/",$dateInfos[0]);
        $inv_install_date_str = "$dateInfos[1]/$dateInfos[2]";
    }
    $request = array(
        "aos_invoices_name" => $Invoice->name,
        "client_name"=> current(explode(' ',$account->name)),
        'installer_first_name' => current(explode(' ',$installer->name)),
        "lead_first_name"=> current(explode(' ',$account->name)),
        "geo_name" => $geo_name,
        "intallation_address" => $Invoice->install_address_c .' '.$Invoice->install_address_city_c. ' '.$invoice->install_address_state_c .' '.$Invoice->install_address_postalcode_c, 
        "productType" => $product_type,
        "install_date" => ($inv_install_date_str)?$inv_install_date_str: ''
    );

    $name = $emailTemplate->subject;
    $description_html = $emailTemplate->body_html;
    $description = $emailTemplate->body;

    //parse value
    
    $description =  str_replace("STCs/VEECs",$request['geo_name'] , $description);
    $description_html = str_replace("STCs/VEECs",$request['geo_name'], $description_html);

    $description = str_replace("\$installer_first_name", $request['installer_first_name'] , $description);
    $description_html = str_replace("\$installer_first_name", $request['installer_first_name'] , $description_html);

    $description = str_replace("\$intallation_address", $request['intallation_address'] , $description);
    $description_html = str_replace("\$intallation_address", $request['intallation_address'], $description_html);

    $description = str_replace("\$client_name", $request['client_name']  , $description);
    $description_html = str_replace("\$client_name", $request['client_name'] , $description_html);

    $description = str_replace("\$lead_first_name", $request['lead_first_name']  , $description);
    $description_html = str_replace("\$lead_first_name", $request['lead_first_name'] , $description_html);
    
    $description = str_replace("\$productType", $request['productType']  , $description);
    $description_html = str_replace("\$productType", $request['productType'] , $description_html);

    
    $name = str_replace("STCs/VEECs",$request['geo_name'], $name);
    $name = str_replace("\$productType",$request['productType'] , $name);
    $name = str_replace("\$aos_invoices_name",$request['aos_invoices_name'] , $name);
    $name = str_replace("\$client_name",$request['lead_first_name'], $name);
    $name = str_replace("\$install_date",$request['install_date'], $name);

    print( $name );print( $description );
    
    switch ($type) {
        case 'CreateEmailGEOFollowUp_System_Owner':
            //account is owner
            $parent_email = $account;
            break;
        case 'CreateEmailGEOFollowUp_Installer':
             //installer is owner
            $parent_email = $installer;
            break;
        default:
            break;
    }
    //create email 
    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "draft";
    $email->status = "draft";
    $email->parent_type = 'Accounts';
    $email->parent_id = $parent_email->id;
    $email->parent_name = $parent_email->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description;
    $email->sms_message = strip_tags(trim(html_entity_decode($description_html,ENT_QUOTES)));
    $email->number_client =  $account_bean->mobile_phone_c;
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
    $email->to_addrs_names = $parent_email->name . " <" . $parent_email->email1 . ">";
    $email->save();
    header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $module . '&return_action=DetailView&return_id=' . $record_id . '&record=' . $email_id.'&email_template_id='.$emailTemplateID);
    //return $email->id;
}