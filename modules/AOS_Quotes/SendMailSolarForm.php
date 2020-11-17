<?php
    $products = json_decode(htmlspecialchars_decode($_REQUEST['products']), true);
    $options = $_REQUEST['options'];

// print_r($products);type_form
    if($options !=""){
        require_once('include/SugarPHPMailer.php');
        global $sugar_config;
        $temp_request = array(
            "module" => "Emails",
            "action" => "send",
            "record" => "",
            "type" => "out",
            "send" => 1,
            "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
            "emails_email_templates_name" => "Installation Calendar Email ",
            "emails_email_templates_idb" => "3d130783-62df-4eaa-c1c5-5dee208d3e02",
            "parent_type" => "AOS_Quotes",
            "parent_name" => $options->firstname,
            // "parent_id" => $invoice->id,
            "from_addr" => 'operations@pure-electric.com.au',
            "to_addrs_names" => "",
            "cc_addrs_names" => 'ngoanhtuan2510@gmail.com',//"info@pure-electric.com.au",
            "bcc_addrs_names" => 'ngoanhtuan2510@gmail.com',
            "to_addrs_arr" => 'ngoanhtuan2510@gmail.com',
            "is_only_plain_text" => false,
        );
        // $emailObj = new Email();
        // $defaults = $emailObj->getSystemDefaultEmail();
        $email = new Email();
        // $mail->setMailerForSystem();
        // $mail->From = $defaults['email'];
        // $mail->FromName = $defaults['name'];
        // $mail->IsHTML(true);
        $email = $email->populateBeanFromRequest($email, $temp_request);

        $emailTemplate = BeanFactory::getBean(
            'EmailTemplates', $options['templateID']
            //'EmailTemplates',"3d130783-62df-4eaa-c1c5-5dee208d3e02"
        );
        $subject = str_replace('$lead_name',$options['firstname'].' '.$options['lastname'],$emailTemplate->subject);//$emailTemplate->name;
        $subject = str_replace('$lead_primary_address_city',$options['primary_address_city'].' '.$options['primary_address_state'],$subject);

        $body = str_replace('$lead_first_name',$options['firstname'],$emailTemplate->body);
        $body = str_replace('$aos_quotes_meter_phase_c',$options['many_phases'],$body);
        $body = str_replace('$aos_quotes_distributor_c',$options['distributor'],$body);
        $body = str_replace('$aos_quotes_gutter_height_c',$options['firstname'],$body);
        $body = str_replace('$aos_quotes_roof_type_c',$options['roof_type'],$body);
        $body = str_replace('$solar_pricing_options',$options['products'].' '.$options['custumer_price'],$body);

        $body_html = str_replace('$lead_first_name',$options['firstname'],$emailTemplate->body_html);
        $body_html = str_replace('$aos_quotes_meter_phase_c',$options['many_phases'],$body_html);
        $body_html = str_replace('$aos_quotes_distributor_c',$options['distributor'],$body_html);
        $body_html = str_replace('$aos_quotes_gutter_height_c',$options['firstname'],$body_html);
        $body_html = str_replace('$aos_quotes_roof_type_c',$options['roof_type'],$body_html);
        $body_html = str_replace('$solar_pricing_options',$options['products'].' '.$options['custumer_price'],$body_html);

        
        $email->emails_email_templates_name = $emailTemplate->name;
        $email->emails_email_templates_idb = $emailTemplate->id;

        $email->name = $subject;
        $email->description = $body;
        $email->description_html = $body_html;
        $email->sms_message = strip_tags($body);

        $email->id = create_guid();
        $email->new_with_id = true;
        $email->type = "draft";
        $email->status = "draft";
        // $mail->AddAddress('admin@pure-electric.com.au');
        // $mail->AddAddress($current_user->email1);
        // $mail->AddCC('info@pure-electric.com.au');
        // $email->AddAddress('ngoanhtuan2510@gmail.com');
        $email->save(false);
        // return $mail->id;
        }
    
?>