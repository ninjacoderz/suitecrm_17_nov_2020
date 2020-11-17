<?php
//Thienpb code
// ini_set("display_errors",1);
    $macro_nv = array();
    $focusName = "Leads";
    $quote = new AOS_Quotes();
    $quote->retrieve($_REQUEST['quote_id']);
    $email_bean->return_module = 'AOS_Quotes';
    $email_bean->return_id = $quote->id;
    $lead =  new Lead();
    $focus = $lead->retrieve($quote->leads_aos_quotes_1leads_ida);

    $contact =  new Contact();
    $contact->retrieve($quote->billing_contact_id);

    if(!$focus->id) return;
    /**
     * @var EmailTemplate $emailTemplate
     */
    $email_bean = new Email();

    /**
     * @var EmailTemplate $emailTemplate
     */

    $emailTemplate_id = '64084c36-9ba4-68fd-20c8-5ecc3b51c593';
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        $emailTemplate_id
    );

    $name = $emailTemplate->subject;
    $description_html = $emailTemplate->body_html;
    $description = $emailTemplate->body;

    $templateData = $emailTemplate->parse_email_template(
        array(
            'subject' => $name,
            'body_html' => $description_html,
            'body' => $description,
        ),
        $focusName,
        $focus,
        $macro_nv
    );

    $email_bean->emails_email_templates_idb = $emailTemplate_id;
    $attachmentBeans = $emailTemplate->getAttachments();

    // Thienpb code set email template by sg inverter model
    // if($_REQUEST['email_type'] != "quote_type_tesla"){
    //     if($_REQUEST['inverter_model'] == 'Fronius_Primo'){
    //         $emailTemplate1 = BeanFactory::getBean(
    //             'EmailTemplates',
    //             '3742953d-1318-43cb-00e3-5bbaab707bcd'
    //         );
    //         $attachmentBeans = array_merge($attachmentBeans,$emailTemplate1->getAttachments()) ;
    //     }elseif($_REQUEST['inverter_model'] == 'Fronius_Symo'){
    //         $emailTemplate2 = BeanFactory::getBean(
    //             'EmailTemplates',
    //             '180953f6-3dda-b10e-8f39-5bbbfe2bec38'
    //         );
    //         $attachmentBeans = array_merge($attachmentBeans,$emailTemplate2->getAttachments()) ;

    //     }elseif($_REQUEST['inverter_model'] == 'SolarEdge'){
    //         $emailTemplate3 = BeanFactory::getBean(
    //             'EmailTemplates',
    //             '12fb3725-0581-cf2c-18ed-5bbbfe6b0089'
    //         );
    //         $attachmentBeans = array_merge($attachmentBeans,$emailTemplate3->getAttachments()) ;
    //     }
    // }
    //end

    if($attachmentBeans) {
        $email_bean->status = "draft";
        $email_bean->save();
        foreach($attachmentBeans as $attachmentBean) {
            $noteTemplate = clone $attachmentBean;
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            $noteTemplate->parent_id = $email_bean->id;
            $noteTemplate->parent_type = 'Emails';

            $noteFile = new UploadFile();
            $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

            $noteTemplate->save();
            $email_bean->attachNote($noteTemplate);
        }
    }
    if($quote->id != ""){
        $focus = $quote;
    }
    
    $email_bean->name = $templateData['subject'];
    $email_bean->description_html = $templateData['body_html'];
    $email_bean->description = $templateData['body_html'];
    $email_bean->description_html = str_replace("\$aos_quotes_id",$focus->id, $email_bean->description_html);
    $email_bean->return_module = 'AOS_Quotes';
    $email_bean->return_id = $focus->id;

    $file_attachmens = scandir(dirname(__FILE__)."/server/php/files/". $focus->pre_install_photos_c ."/");
    
    $noteArray = array();
    //thienpb code - block file for email
    if($focus->block_files_for_email_c != ""){
        $file_attachmens = array_diff($file_attachmens,json_decode(htmlspecialchars_decode($focus->block_files_for_email_c)));
    }
    
    $quote_file_exist = false;
    $num_quote_SG = $focus->solargain_quote_number_c;

    if (count($file_attachmens)>0){
        foreach ($file_attachmens as $att){
            // Create Note
            //if(strpos($att, "Bill") !== false) continue;
            //check button send tesla and quote nomal
            if(strpos($att, 'Quote_') !== false || strpos($att, 'Design_') !== false || strpos($att, 'Image_Site_Detail') !== false){
                $source =  dirname(__FILE__)."/server/php/files/". $focus->pre_install_photos_c ."/" . $att ;
                
                if(!is_file($source)) continue;
                
                $noteTemplate = new Note();
                $noteTemplate->id = create_guid();
                $noteTemplate->new_with_id = true; // duplicating the note with files
                $noteTemplate->parent_id = $email_bean->id;
                $noteTemplate->parent_type = 'Emails';
                $noteTemplate->date_entered = '';
                $noteTemplate->file_mime_type = mime_content_type($att);
                $noteTemplate->filename = $att;
                $noteTemplate->name = $att;

                $noteTemplate->save();

                $destination =  realpath(dirname(__FILE__) . '/../../../../../').'/upload/'.$noteTemplate->id;
                //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                //copy( $source, $destination);
                if (!symlink($source, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                }
                $email_bean->attachNote($noteTemplate);
            }
        }
        
        $first_name = $lead->first_name;
        $email_bean->send_sms = 1;

        $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $lead->phone_mobile));
        $phone_number = preg_replace("/^61/", "+61", $phone_number);
        $email_bean->number_client = $phone_number;//$_REQUEST['sms_received'];
        $email_bean->number_receive_sms = "matthew_paul_client";
        //$email_bean->sms_message = "Hi $first_name your pure-electric quote has been sent to your inbox, if you can't find it please check your spam folder";
        $assigned_name = $focus->assigned_user_name;
        $email_bean->sms_message = "Hi $first_name, Your Solar PV quote has been prepared and sent to your email inbox. If you can't find it, check your spam folder and if no success still, please don't hesitate to contact us. Regards, $assigned_name";
        
        //start - code render sms_template  
        global $current_user;
        $smsTemplate = BeanFactory::getBean(
            'pe_smstemplate',
            '5999d6d4-d1b7-161d-c1eb-5ecc6b2df036' 
        );
        $body =  $smsTemplate->body_c;
        $body = str_replace("\$first_name", $lead->first_name, $body);
        $smsTemplate->body_c = $body;
        $email_bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
        $email_bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
        $email_bean->sms_message =trim(strip_tags(html_entity_decode(parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
        //end - code render sms_template
    }
    $email_bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
    $email_bean->parent_id = $quote->id;
    $email_bean->parent_type = 'AOS_Quotes';
    $email_bean->save(false);
    $email_id = $email_bean->id;
    header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Quotes&return_action=DetailView&return_id=' . $quote->id . '&record=' . $email_id .'&sms_template_id=5999d6d4-d1b7-161d-c1eb-5ecc6b2df036&email_template_id=64084c36-9ba4-68fd-20c8-5ecc3b51c593');
    die();

function check_exist_file($source, $string) {
    $file_array = scandir($source);
    $file_array = array_diff($file_array, array('.', '..'));
    $result = array();
    foreach($file_array as $file){
        if (strpos(strtolower($file), strtolower($string)) !== false && strpos($file, $string) == 0) {
            $result[] = $file;
        }
    }
    return $result;
}

function parse_sms_template($smsTemplate, $focus)
    {
        global $beanList, $app_list_strings;
        $body =  $smsTemplate->body_c;
        $address_customer =  $focus->primary_address_street . ' ' .$focus->primary_address_city . ' ' .$focus->primary_address_state . ' ' .$focus->primary_address_postalcode;
        $body = str_replace("\$first_name", $focus->first_name, $body);
        $body = str_replace("\$last_name", $focus->last_name,$body);
        $body = str_replace("\$address",$address_customer, $body);
        //VUT-S- $quote_number in sms template
            if ($focus->module_dir == 'AOS_Quotes') {
                $body = str_replace("\$quote_number",$focus->number, $body);
            } else if ($focus->module_dir == 'AOS_Invoices') {
                $body = str_replace("\$quote_number",$focus->quote_number, $body);
                $product_type =  $app_list_strings['quote_type_list'][$focus->quote_type_c];
                $body = str_replace("\$product_type",$product_type, $body);
            } else {
                $body = str_replace("\$quote_number","", $body);
            }
        //VUT-E- $quote_number in sms template
        if($focus->assigned_user_id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa') {
            //paul
            $body = str_replace("\$assigned_user_first_name", 'Paul', $body);
            $body = str_replace("\$assigned_user_email", 'paul.szuster@pure-electric.com.au', $body);
            $body = str_replace("\$assigned_user_phone_number", '0423 494 949', $body);
        }else{
            //matt
            $body = str_replace("\$assigned_user_first_name", 'Matthew', $body);
            $body = str_replace("\$assigned_user_email", 'matthew.wright@pure-electric.com.au', $body);
            $body = str_replace("\$assigned_user_phone_number", '0421 616 733', $body);
        }
        return $body;
    }
?>