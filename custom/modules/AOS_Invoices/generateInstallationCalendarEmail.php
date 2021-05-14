<?php
    require_once('include/SugarPHPMailer.php');
    global $sugar_config;
    if($_REQUEST["invoiceID"] != ''){

        $URL_arr = [];
        $invoice = new AOS_Invoices();
        $invoice->retrieve($_REQUEST["invoiceID"]);

        if(empty($invoice->id))return;
        $user_assign = new User();
        $user_assign->retrieve($invoice->assigned_user_id);
        $email_assigned = ", {$user_assign->email1}";

        $temp_request = array(
            "module" => "Emails",
            "action" => "send",
            "record" => "",
            "type" => "out",
            "send" => 1,
            "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
            "emails_email_templates_name" => "Installation Calendar Email ",
            "emails_email_templates_idb" => "3d130783-62df-4eaa-c1c5-5dee208d3e02",
            "parent_type" => "AOS_Invoices",
            "parent_name" =>$invoice->name,
            "parent_id" => $invoice->id,
            "from_addr" => 'operations@pure-electric.com.au',
            "to_addrs_names" => "",
            "cc_addrs_names" => "Pure Info <info@pure-electric.com.au>".$email_assigned,
            "bcc_addrs_names" => "",
            "is_only_plain_text" => false,
        );

        if($invoice->billing_account_id != ''){
            $account = new Account();
            $account = $account->retrieve($invoice->billing_account_id);
            if(!empty($account->id)){
                $sea = new SugarEmailAddress; 
                $primary = $sea->getPrimaryAddress($account);
                $temp_request['to_addrs_names'] =  $account->name . "  <".$primary.">";

                $client_installation_calendar_url = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$_REQUEST['installation_id'].'/client';
                $id = createEmailByRole('client',$temp_request,explode(" ", $account->name,2)[0],$client_installation_calendar_url,$invoice);
                $URL_arr['client_url'] = $sugar_config['site_url'].'/index.php?action=ComposeView&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_id='.$invoice->id.'&return_action=DetailView&record=' . $id .'&changedSubject=false&email_template_id=3d130783-62df-4eaa-c1c5-5dee208d3e02&sms_template_id=ab4b8f77-4bb5-a00d-9c55-5f9b4ad921b6&role=client&installation_id='.$_REQUEST['installation_id'];

            }else{
                $URL_arr['client_url'] = '';
            }
        }else{
            $URL_arr['client_url'] = '';
        }
        if($invoice->account_id_c){
            $account = new Account();
            $account = $account->retrieve($invoice->account_id_c);
            if(!empty($account->id)){
                $sea = new SugarEmailAddress; 
                $primary = $sea->getPrimaryAddress($account);
                $temp_request['to_addrs_names'] =  $account->name . "  <".$primary.">";

                $electric_installation_calendar_url = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$_REQUEST['installation_id'].'/electrician/'.$account->id;
                $id = createEmailByRole('electrician',$temp_request,explode(" ", $account->name,2)[0],$electric_installation_calendar_url,$invoice);
                $URL_arr['electrician_url'] = $sugar_config['site_url'].'/index.php?action=ComposeView&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_id='.$invoice->id.'&return_action=DetailView&record=' . $id .'&changedSubject=false&email_template_id=dc0416cd-6867-5508-3d20-5df843ba69dc&role=electrician&installation_id='.$_REQUEST['installation_id'].'&sms_template_id=ca646f5f-399a-d408-7536-601102429ed6';
            }else{
                $URL_arr['electrician_url'] = '';
            }
        }else{
            $URL_arr['electrician_url'] = '';
        }

        if($invoice->account_id1_c){
            $account = new Account();
            $account = $account->retrieve($invoice->account_id1_c);
            if(!empty($account->id)){
                $sea = new SugarEmailAddress; 
                $primary = $sea->getPrimaryAddress($account);
                $temp_request['to_addrs_names'] =  $account->name . "  <".$primary.">";

                $plumber_installation_calendar_url = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$_REQUEST['installation_id'].'/plumber/'.$account->id;
                $id = createEmailByRole('plumber',$temp_request,explode(" ", $account->name,2)[0],$plumber_installation_calendar_url,$invoice);
                $URL_arr['plumber_url'] = $sugar_config['site_url'].'/index.php?action=ComposeView&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_id='.$invoice->id.'&return_action=DetailView&record=' . $id .'&changedSubject=false&email_template_id=3722ae7c-d8b7-e03f-559c-5df843678e41&role=plumber&installation_id='.$_REQUEST['installation_id'].'&sms_template_id=ca646f5f-399a-d408-7536-601102429ed6';
            }else{
                $URL_arr['plumber_url'] = '';
            }
        }else{
            $URL_arr['plumber_url'] = '';
        }
        
        echo json_encode($URL_arr);
    }

    function createEmailByRole($role,$temp_request,$name,$installation_calendar_url,$invoice){
        $body = '';
        $body_html = '';
        $email = new Email();
        $email = $email->populateBeanFromRequest($email, $temp_request);
        $emailTemplate = [];
        $smsTemplate = [];
        $body = '';
        global $current_user;
        $email->id = create_guid();
        $email->new_with_id = true;
        $email->type = "draft";
        $email->status = "draft";
        if (strpos($name, "&#039;")!==false) {
            $name = explode("&#039;",$name,2)[0];
        }   

        $quote_type = '';
        switch ($invoice->quote_type_c) {
            case 'quote_type_sanden':
                $quote_type = 'Sanden';
                break;
            case 'quote_type_solar':
                $quote_type = 'Solar';
                break;
            case 'quote_type_daikin':
                $quote_type = 'Daikin';
                break;
            case 'quote_type_off_grid_system':
                $quote_type = 'Off-grid System';
                break;
            case 'quote_type_nexura':
                $quote_type = 'Daikin';
                break;
            case 'quote_type_methven':
                $quote_type = 'Methven';
                break;
            case 'quote_type_battery':
                $quote_type = 'Battery';
                break;
            case 'quote_type_tesla':
                $quote_type = 'Tesla';
                break;
        }

        if($role == "client"){
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',"3d130783-62df-4eaa-c1c5-5dee208d3e02"
                //'EmailTemplates',"3d130783-62df-4eaa-c1c5-5dee208d3e02"
            );
            $body = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$contact_first_name',$name,$emailTemplate->body));
            $body = str_replace('$aos_invoices_quote_type_c', $quote_type, $body);

            $body_html = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$contact_first_name',$name,$emailTemplate->body_html));
            $body_html = str_replace('$aos_invoices_quote_type_c', $quote_type, $body_html);
            // generate sms template
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                'ab4b8f77-4bb5-a00d-9c55-5f9b4ad921b6' 
            );
            $AccountClient = new Account();
            $AccountClient = $AccountClient->retrieve($invoice->billing_account_id);
            $body_sms =  $smsTemplate->body_c;
            $body_sms = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace("\$first_name", explode(" ", $AccountClient->name,2)[0], $body_sms));
            $body_sms = str_replace('$aos_invoices_quote_type_c', $quote_type,$body_sms);
            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $AccountClient->mobile_phone_c));
            $phone_number = preg_replace("/^61/", "+61", $phone_number);
            $email->emails_pe_smstemplate_idb = $smsTemplate->id;
            $email->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $email->number_client =  $phone_number; 
            $email->sms_message =trim(strip_tags(html_entity_decode($body_sms/**.' '.$current_user->sms_signature_c */,ENT_QUOTES)));
            //end generate sms template

        }else{
            if($role == "plumber"){
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',"3722ae7c-d8b7-e03f-559c-5df843678e41"
                    //'EmailTemplates',"3d130783-62df-4eaa-c1c5-5dee208d3e02"
                );

                $body = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$name',$name,$emailTemplate->body));
                $body = str_replace('$aos_invoices_plumbing_notes_c',$invoice->plumbing_notes_c , $body);
                $body = str_replace('$distance_to_suite_c',$invoice->distance_to_suite_c , $body);

                $body_html = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$name',$name,$emailTemplate->body_html));
                $body_html = str_replace('$aos_invoices_plumbing_notes_c', $invoice->plumbing_notes_c , $body_html);
                $body_html = str_replace('$distance_to_suite_c', $invoice->distance_to_suite_c , $body_html);
                $contact_installer = new Contact();
                $contact_installer->retrieve($invoice->contact_id4_c);
            }else if($role == "electrician"){
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',"dc0416cd-6867-5508-3d20-5df843ba69dc"
                    //'EmailTemplates',"3d130783-62df-4eaa-c1c5-5dee208d3e02"
                );

                $body = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$name',$name,$emailTemplate->body));
                $body = str_replace('$aos_invoices_electrical_notes_c',$invoice->electrical_notes_c , $body);
                $body = str_replace('$distance_to_suite_c',$invoice->distance_to_suitecrm_c , $body);

                $body_html = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace('$name',$name,$emailTemplate->body_html));
                $body_html = str_replace('$aos_invoices_electrical_notes_c', $invoice->electrical_notes_c , $body_html);
                $body_html = str_replace('$distance_to_suite_c', $invoice->distance_to_suitecrm_c , $body_html);
                $contact_installer = new Contact();
                $contact_installer->retrieve($invoice->contact_id_c);
            }

            //VUT - S - Add file "Proposed Install Location" to email Plumber/Electrician
            $invoice_file_attachments = scandir($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c .'/');
            $name_file_include = 'Proposed_Install_Location';
            if (count($invoice_file_attachments)>0 ) foreach ($invoice_file_attachments as $att){
                $source =  $_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c .'/'. $att;
                if(!is_file($source)) continue;
                if (strpos(strtolower($att),strtolower($name_file_include)) !==  false
                || strpos(strtolower($att),strtolower('_Existing_Hws')) !== false /**https://trello.com/c/3Fe84CCL/3026-invoice-email-po-and-send-out-the-calendar-link-to-the-installers-please-ensure-automatically-show-the-old-hws-photos-and-switch?menu=filter&filter=member:paulszuster1,mode:and*/
                || strpos(strtolower($att),strtolower('Switchboard')) !== false) {
                    $noteTemplate = new Note();
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $email->id;
                    $noteTemplate->parent_type = 'Emails';
                    $noteTemplate->date_entered = '';
                    // $noteTemplate->file_mime_type = 'application/pdf';
                    $noteTemplate->filename = $att;
                    $noteTemplate->name = $att;

                    $noteTemplate->save();

                    $destination = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$noteTemplate->id;
                    if (!symlink($source, $destination)) {
                        $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                    }
                   $email->attachNote($noteTemplate);
                }
            }
            //VUT - E - Add file "Proposed Install Location" to email Plumber/Electrician

            $contact_customer = new Contact();
            $contact_customer->retrieve($invoice->contact_id3_c);
            $customer_address = $invoice->install_address_c . " ".  $invoice->install_address_city_c . " ".  $invoice->install_address_state_c. " ".  $invoice->install_address_postalcode_c;

            $customer_phone = '';
            if ($contact_customer->phone_mobile != '') {
                $customer_phone .= 'M: '.$contact_customer->phone_mobile;
            } 
            if ($contact_customer->phone_work != '') {
                $customer_phone .= ' W: '.$contact_customer->phone_work;
            }
            $body = str_replace("\$aos_invoices_billing_contact",$contact_customer->name , $body);
            $body = str_replace("\$aos_invoices_install_address_c",$customer_address , $body);
            $body = str_replace("\$aos_invoices_contact_id3_c",$customer_phone , $body);

            $body_html = str_replace("\$aos_invoices_billing_contact", $contact_customer->name , $body_html);
            $body_html = str_replace("\$aos_invoices_install_address_c", $customer_address , $body_html);
            $body_html = str_replace("\$aos_invoices_contact_id3_c", $customer_phone , $body_html);

            //Add SMS Template for Plumber/Electrician https://trello.com/c/TzmOCJ2e/3032-bug-system-gets-the-name-of-the-electrician-for-the-plumbers-name-on-sms-calendar
            // $smsTemplate = BeanFactory::getBean( 
            //     'pe_smstemplate',
            //     'ca646f5f-399a-d408-7536-601102429ed6' 
            // );
            $smsTemplate = new pe_smstemplate();
            $smsTemplate->retrieve('ca646f5f-399a-d408-7536-601102429ed6');

            $body_sms = '';
            $body_sms =  $smsTemplate->body_c;
            $body_sms = str_replace("\$first_name", $contact_installer->first_name, $body_sms);
            $body_sms = str_replace("\$aos_invoices_billing_contact",  $contact_customer->name, $body_sms);
            $smsTemplate->body_c = $body_sms;
            $email->emails_pe_smstemplate_idb  =  $smsTemplate->id;
            $email->emails_pe_smstemplate_name =  $smsTemplate->name; 

            $email->number_receive_sms = "matthew_paul_client";
            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_installer->phone_mobile));
            $phone_number = preg_replace("/^61/", "+61", $phone_number);
            $email->number_client =  $phone_number; 
            $email->sms_message =trim(strip_tags(html_entity_decode($body_sms/**.' '.$current_user->sms_signature_c*/,ENT_QUOTES)));   

        }
        $email->emails_email_templates_name = $emailTemplate->name;
        $email->emails_email_templates_idb = $emailTemplate->id;

        $email->name = str_replace('$aos_invoices_name',$invoice->name,$emailTemplate->subject);

        // $customer = $invoice->contact_id3_c;
        // $contact = new Contact();
        // $contact = $contact->retrieve($customer);
        // $account = new Account();
        // $account = $account->retrieve( $contact->account_id);

        // $address_customer =$invoice->shipping_address_street .' '.$invoice->shipping_address_city .' ' .$invoice->shipping_address_state .' '. $invoice->shipping_address_postalcode;
        // if($address_customer) {
        //     $address_customer = $contact->alt_address_street .' '.$contact->alt_address_city .' ' .$contact->alt_address_state .' '. $contact->alt_address_postalcode;
        // }
        // if($address_customer) {
        //     $address_customer = $contact->primary_address_street .' '.$contact->primary_address_city .' ' .$contact->primary_address_state .' '. $contact->primary_address_postalcode;
        // }

        // if($address_customer) {
        //     $address_customer = $account->billing_address_street .' '.$account->billing_address_city .' ' .$account->billing_address_state .' '. $account->billing_address_postalcode;
        // }

        // if($contact->phone_home){
        //     $phone .= " H: ".$contact->phone_home;
        // }
        // if($contact->phone_mobile){
        //     $phone .= " M: ".$contact->phone_mobile;
        // }
        // if($contact->phone_work){
        //     $phone .= " W: ".$contact->phone_work;
        // }

        // if($phone){
        //     if($account->home_phone_c){
        //         $phone .= " H: ".$account->home_phone_c;
        //     }
        //     if($account->mobile_phone_c){
        //         $phone .= " M: ".$account->mobile_phone_c;
        //     }
        //     if($account->phone_office){
        //         $phone .= " W: ".$account->phone_office;
        //     }
        // }

        // $body = str_replace('$$ XX (Contact)',$invoice->site_contact_c,$body);
        // $body = str_replace('$$ XX (Install Address)',$address_customer,$body);
        // $body = str_replace('M: XX W: XX (Contact Mobile Number / Contact Work Number)',$phone,$body);
        // if($role == "plumber"){
        //     $body      = str_replace('XX km',$invoice->distance_to_suite_c,$body);
        //     $body_html = str_replace('XX km',$invoice->distance_to_suitecrm_c,$body_html);
        //     $body      = str_replace('$aos_invoices_plumbing_notes_c',$bean_invoice->plumbing_notes_c,$body);
        //     $body_html = str_replace('$aos_invoices_plumbing_notes_c',$bean_invoice->plumbing_notes_c,$body_html);
        // }else{
        //     $body      = str_replace('XX km',$invoice->distance_to_suitecrm_c,$body);
        //     $body_html = str_replace('XX km',$invoice->distance_to_suitecrm_c,$body_html);
        //     $body      = str_replace('$aos_invoices_electrical_notes_c',$invoice->electrical_notes_c,$body);
        //     $body_html = str_replace('$aos_invoices_electrical_notes_c',$invoice->electrical_notes_c,$body_html);
        // }
        // $body = str_replace('$aos_invoices_electrical_notes_c',$invoice->electrical_notes_c,$body);
        // $body = str_replace('$aos_invoices_plumbing_notes_c',$bean_invoice->plumbing_notes_c,$body);

        // $body_html = str_replace('$$ XX (Contact)',$invoice->site_contact_c,$body_html);
        // $body_html = str_replace('$$ XX (Install Address)',$address_customer,$body_html);
        // $body_html = str_replace('M: XX W: XX (Contact Mobile Number / Contact Work Number)',$phone,$body_html);

        $email->description = $body;
        $email->description_html = $body_html;
        // $email->id = create_guid();
        // $email->new_with_id = true;
        // $email->type = "draft";
        // $email->status = "draft";
        $email->save(false);

        return $email->id;
    }
?>