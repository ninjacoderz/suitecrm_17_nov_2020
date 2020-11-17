<?php
$request_type = $_GET['request_type'];
if(isset($request_type) && $request_type != '') {
    switch ($request_type) {
        case 'create':
            $message = $_POST;
            $lead_id = $message['crm_ref'];
            $last_message = $message['last_message'];
            $message_content = $message['message_content'];
            $message_type = $message['message_type'];
            $message_status = $message['message_status'];
            $phone_number_customer = $message['phone'];
            $message_uniqid = $message['message_uniqid'];

            $schedule_timestamp = $message['schedule_timestamp'];
            if($message_type == 'text') {
                $short_content_messager = explode(" ",$message_content);
                $short_content_messager = array_slice($short_content_messager,0,10);
                $short_content_messager = $phone_number_customer .' - '  .implode(" ",$short_content_messager) ." ...";
            }else{
                $short_content_messager = $phone_number_customer .' - Attachment';
                $message_content = 'Attachment';
            }

            
            $sms = new pe_smsmanager();
            $sms->description = $message_content;
            $sms->name = $short_content_messager;
            $sms->message_uniqid_c = $message_uniqid;

            if($message_status == 'sent') {
                $sms->status_c = 'sent';
            }else{
                date_default_timezone_set('UTC');
                $dateAUS =  date( "Y-m-d H:i:s",$schedule_timestamp);
                $sms->time_schedule_c = $dateAUS ;
                $sms->status_c = 'schedule' ;
            }
            
            $sms->save();
            
            if($lead_id != '') {
                $bean_module = new Lead();
                $bean_module = $bean_module->retrieve($lead_id);
                if($bean_module->id != '') {
                    if($bean_module->status == "Assigned"){
                        $bean_module->status = 'In Process';
                        $bean_module->save();
                    }
                    $sms->load_relationship('pe_smsmanager_leads');
                    $sms->pe_smsmanager_leads->add($bean_module);
                    $sms->name = 'LEAD ' .$bean_module->number;
                }
            }
            
            $sms->save();
            break;
        
        case 'update':
            $message_uniqid = $_GET['message_uniqid'];
            if(isset($message_uniqid) && $message_uniqid != '') {
                $db = DBManagerFactory::getInstance();
                $result = $db->query("Update pe_smsmanager_cstm SET status_c = 'sent' WHERE message_uniqid_c = '$message_uniqid'");
            }
            break;
        default:
            
            break;
    }
}

