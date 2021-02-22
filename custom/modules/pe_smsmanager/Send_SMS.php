<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
require_once('modules/pe_smsmanager/pe_smsmanager.php');
require_once('include/UploadFile.php');
require_once('include/UploadMultipleFiles.php');
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : '';
if($action == 'send_files'){
    $phone_number_customer = urldecode($_REQUEST['phone_number_customer']); 
    $from_phone_number = urldecode($_REQUEST['from_phone_number']);
    $module =urldecode($_REQUEST['module']);
    $record_id = urldecode($_REQUEST['record_id']);
    $status = urldecode($_REQUEST['status']);
    $timestamp = urldecode($_REQUEST['timestamp']);
    $action = isset($_REQUEST['action'])? $_REQUEST['action'] : '';
    global $mod_strings;
    $_FILES["sms_files"]["name"] = array_unique($_FILES["sms_files"]["name"]);
    $_FILES["sms_files"]["type"] = array_unique($_FILES["sms_files"]["type"]);
    $_FILES["sms_files"]["tmp_name"] = array_unique($_FILES["sms_files"]["tmp_name"]);
    $_FILES["sms_files"]["error"] = array_unique($_FILES["sms_files"]["error"]);
    $_FILES["sms_files"]["size"] = array_unique($_FILES["sms_files"]["size"]);
    $count_files = count($_FILES["sms_files"]["name"]);
    if($count_files == 0) return;
    for ($i = 0; $i < $count_files; $i++) {
        $sms = new pe_smsmanager();
        $sms->name = 'Attachment';
        $uniqid = uniqid();
        $sms->message_uniqid_c = $uniqid;
        if($status == 'sent') {
            $sms->status_c = 'sent';
        }else{
            date_default_timezone_set('UTC');
            $dateAUS =  date( "Y-m-d H:i:s",$timestamp);
            $sms->time_schedule_c = $dateAUS ;
            $sms->status_c = 'schedule' ;
        }
        $sms->save();
        if($module == 'AOS_Invoices'){
            $bean_module = new AOS_Invoices();
            $bean_module = $bean_module->retrieve($record_id);
            $sms->load_relationship('pe_smsmanager_aos_invoices');
            $sms->pe_smsmanager_aos_invoices->add($bean_module);
        }elseif($module == 'Accounts'){
            $bean_module = new Account();
            $bean_module = $bean_module->retrieve($record_id);
            $sms->load_relationship('pe_smsmanager_accounts');
            $sms->pe_smsmanager_accounts->add($bean_module);
        }elseif($module == 'Leads'){
            $bean_module = new Lead();
            $bean_module = $bean_module->retrieve($record_id);
            $button_send = $_POST['button_send'];
            
            if($button_send == "Request_address_sms"){
                $bean_module->status = 'Address_Requested';
                $bean_module->save();
            }else{
                if($bean_module->status == "Assigned"){
                    $bean_module->status = 'In Process';
                    $bean_module->save();
                }
            }
            $sms->load_relationship('pe_smsmanager_leads');
            $sms->pe_smsmanager_leads->add($bean_module);
        }elseif($module == 'Contacts'){
            $bean_module = new Contact();
            $bean_module = $bean_module->retrieve($record_id);
            $sms->load_relationship('pe_smsmanager_contacts');
            $sms->pe_smsmanager_contacts->add($bean_module);
        }elseif($module == 'AOS_Quotes'){
            $bean_module = new AOS_Quotes();
            $bean_module = $bean_module->retrieve($record_id);
            $sms->load_relationship('pe_smsmanager_aos_quotes');
            $sms->pe_smsmanager_aos_quotes->add($bean_module);
            $internal_notes = new pe_internal_note();
            $internal_notes->type_inter_note_c = 'sms_out';
            $internal_notes->description = $sms->name;
            $internal_notes->save();
            $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
            $internal_notes->aos_quotes_pe_internal_note_1->add($bean_module->id);
           
        }else{}
        
        $upload_file = new UploadMultipleFiles('sms_files', $i);
        if (empty($upload_file)) {
            continue;
        }
        if (isset($_FILES['sms_files']['name'][$i]) && $upload_file->confirm_upload()) {
            $note = new Note();
            $note->parent_id = $sms->id ;
            $note->parent_type = 'pe_smsmanager';
            $note->filename = $upload_file->get_stored_file_name();
            $note->file = $upload_file;
            $note->name = $mod_strings['LBL_EMAIL_ATTACHMENT'] . ': ' . $note->file->original_file_name;
            $note->save();
            $note_id =  $note->id;
            $note->file->final_move($note_id);
            
            $file_path = "/var/www/suitecrm/upload/". $note_id;
            //$file_path = "C:/xampp/htdocs/crm_3_march/upload/".$note_id;
            $imagick = new Imagick();
            $imagick->readImage($file_path);
            $noOfPagesInPDF = $imagick->getNumberImages();
            $files = array();
            if ($noOfPagesInPDF) {
                for ($k = 0; $k < $noOfPagesInPDF; $k++) {
                    $l_Image = new Imagick();
                    $l_Image->setResolution(150, 150);
                    $l_Image->readImage($file_path."[".$k."]");
                    $l_Image = $l_Image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                    $l_Image->setCompression(Imagick::COMPRESSION_JPEG);
                    $l_Image->setImageBackgroundColor('white');
                    $l_Image->setCompressionQuality (100);
                    $l_Image->stripImage();
                    $l_Image->setImageFormat("jpg");
                    $path_to_write = "/var/www/suitecrm/public_files/".$note_id.$k.'.jpg';
                    //$path_to_write = "C:/xampp/htdocs/crm_3_march/upload/".$note_id.$k.'.jpg';
                    $l_Image->writeImage($path_to_write);
                    $l_Image->clear();
                    $l_Image->destroy();
                    $image_url = "https://".$_SERVER['HTTP_HOST'].'/public_files/'.$note_id.$k.".jpg";
                    //$image_url = "C:/xampp/htdocs/crm_3_march/public_files/".$note_id.$k.".jpg";
    
                    
                    if(  $from_phone_number == "+61421616733"){
                        $message_dir = '/var/www/message2';
                    }
                    elseif(  $from_phone_number == "+61490942067"){
                        $message_dir = '/var/www/message';
                    }
                    $sms->description = "cd " . $message_dir . "; php send-message.php mms " . $phone_number_customer . ' "' . $image_url . '"';
                    $sms->save();
                    if($status == 'sent'){
                        exec("cd " . $message_dir . "; php send-message.php mms " . $phone_number_customer . ' "' . $image_url . '"');
                    }else{
                        exec("cd " . $message_dir . "; php send-message-scheduled.php mms " .$phone_number_customer.' "'.$image_url.'" ' .$timestamp .' ' .$uniqid);
                    }
                }
            }
        }
    }

    die('success');
}
if($_POST['content_messager'] == '') return;
$phone_number_customer = $_POST['phone_number_customer'];
$from_phone_number = $_POST['from_phone_number'];
$content_messager = $_POST['content_messager'];
$content_messager = str_replace("$", "\\$", html_entity_decode($content_messager, ENT_QUOTES));
$module =$_POST['module'];
$record_id = $_POST['record_id'];
$status = $_POST['status'];
$timestamp = $_POST['timestamp'];
$short_content_messager = explode(" ",$content_messager);
$short_content_messager = array_slice($short_content_messager,0,10);
$short_content_messager = $phone_number_customer .' - '  .implode(" ",$short_content_messager) ." ...";
$uniqid = uniqid();

$sms = new pe_smsmanager();
$sms->description = $content_messager;
$sms->name = $short_content_messager;
$sms->message_uniqid_c = $uniqid;

if($status == 'sent') {
    $sms->status_c = 'sent';
}else{
    date_default_timezone_set('UTC');
    $dateAUS =  date( "Y-m-d H:i:s",$timestamp);
    $sms->time_schedule_c = $dateAUS ;
    $sms->status_c = 'schedule' ;
}
$sms->save();
if($module == 'AOS_Invoices'){
    $bean_module = new AOS_Invoices();
    $bean_module = $bean_module->retrieve($record_id);
    $sms->load_relationship('pe_smsmanager_aos_invoices');
    $sms->pe_smsmanager_aos_invoices->add($bean_module);
}elseif($module == 'Accounts'){
    $bean_module = new Account();
    $bean_module = $bean_module->retrieve($record_id);
    $sms->load_relationship('pe_smsmanager_accounts');
    $sms->pe_smsmanager_accounts->add($bean_module);
}elseif($module == 'Leads'){
    $bean_module = new Lead();
    $bean_module = $bean_module->retrieve($record_id);
    //dung code - convert status Lead
    $button_send = $_POST['button_send'];
    
    if($button_send == "Request_address_sms"){
        $bean_module->status = 'Address_Requested';
        $bean_module->save();
    }else{
        if($bean_module->status == "Assigned"){
            $bean_module->status = 'In Process';
            $bean_module->save();
        }
    }
    $sms->load_relationship('pe_smsmanager_leads');
    $sms->pe_smsmanager_leads->add($bean_module);
}elseif($module == 'Contacts'){
    $bean_module = new Contact();
    $bean_module = $bean_module->retrieve($record_id);
    $sms->load_relationship('pe_smsmanager_contacts');
    $sms->pe_smsmanager_contacts->add($bean_module);
}elseif($module == 'AOS_Quotes'){
    $bean_module = new AOS_Quotes();
    $bean_module = $bean_module->retrieve($record_id);
    $sms->load_relationship('pe_smsmanager_aos_quotes');
    $sms->pe_smsmanager_aos_quotes->add($bean_module);
    //VUT-S-Quote DetailView-SMS out
    $internal_notes = new pe_internal_note();
    $internal_notes->type_inter_note_c = 'sms_out';
    // $internal_notes->pe_smsmanager_id_c = $sms->id;
    $internal_notes->description = $sms->name;
    $internal_notes->save();
    $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
    $internal_notes->aos_quotes_pe_internal_note_1->add($bean_module->id);
    //VUT-E-Quote DetailView-SMS out
}else{}

$sms->save();

if($module == 'Leads'){
    $lead =  new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->solargain_lead_number_c != ''){
        $solargainLead = $lead->solargain_lead_number_c;
    
        date_default_timezone_set('Africa/Lagos');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $username = "matthew.wright";
        $password = "MW@pure733";
        
        // Get full json response for Leads
        
        $url = "https://crm.solargain.com.au/APIv2/leads/" . $solargainLead;
        //set the url, number of POST vars, POST data
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: Basic " . base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead,
            "Cache-Control: max-age=0"
        ));
        
        $leadJSON = curl_exec($curl);
        curl_close($curl);
        
        $leadSolarGain = json_decode($leadJSON);
        $ass_name = get_assigned_user_name($lead->assigned_user_id);
        // dung code - push content notes SG for button Request Address SMS
        $type_notes = '';
        $type_id =5; //value 5 = email out
        if($_POST['button_send'] == 'Request_address_sms'){
            $note = "From: ".$ass_name." - ". "SMS sent to customer requesting street address to be able to quote";
            $type_notes = "SMS Out";
            $type_id = 15;
            if($lead->status == "Assigned"){
                $lead->status = 'In Process';
            }
            $lead->save();
        }else {
            $note = "From: ".$ass_name." - ".$content_messager;
            $type_notes = "E-Mail Out" ;
            $type_id = 5;
        }
    
        if($leadSolarGain->Status != "Converted To Quote"){
            $leadSolarGain->Notes[] = array(
                "ID" => 0,
                "Type" => array(
                    "ID" => $type_id,
                    "Name" => $type_notes,
                    "RequiresComment" => true
                ),
                "Text" => $note
            );
            
            $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
            $url                     = "https://crm.solargain.com.au/APIv2/leads/";
            //set the url, number of POST vars, POST data
            $curl                    = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POST, 1);
            
            curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);
            
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:   gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " . strlen($leadSolarGainJSONDecode),
                "Authorization: Basic " . base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead
            ));
            
            $lead = json_decode(curl_exec($curl));
            curl_close($curl);
        }else{
            if($lead->solargain_quote_number_c != '')
            {
                $solargainQuote = $lead->solargain_quote_number_c;
                $url = "https://crm.solargain.com.au/APIv2/quotes/" . $solargainQuote;
                //set the url, number of POST vars, POST data
                
                $curl = curl_init();
                
                curl_setopt($curl, CURLOPT_URL, $url);
                
                
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                //
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                
                curl_setopt($curl, CURLOPT_ENCODING, "gzip");
                
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic " . base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainQuote,
                    "Cache-Control: max-age=0"
                ));
                
                $quoteJSON = curl_exec($curl);
                curl_close($curl);

                $quoteSolarGain = json_decode($quoteJSON);
                $quoteSolarGain->Notes[] = array(
                    "ID" => 0,
                    "Type" => array(
                        "ID" => $type_id,
                        "Name" => $type_notes,
                        "RequiresComment" => true
                    ),
                    "Text" => $note
                );

                $quoteSolarGainJSONDecode = json_encode($quoteSolarGain, JSON_UNESCAPED_SLASHES);
                $url                     = "https://crm.solargain.com.au/APIv2/quotes/";
                //set the url, number of POST vars, POST data
                $curl                    = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);                
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_POST, 1);
                
                curl_setopt($curl, CURLOPT_POSTFIELDS, $quoteSolarGainJSONDecode);
                
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                //
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " . strlen($quoteSolarGainJSONDecode),
                    "Authorization: Basic " . base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/" . $solargainQuote
                ));
                
                $quote = json_decode(curl_exec($curl));
                curl_close($curl);

            }
            
        }
        
    }
}

global $sugar_config;

$message_dir = "";
if( $_POST['from_phone_number'] == "+61421616733"){
    $message_dir = '/var/www/message2';
}
elseif( $_POST['from_phone_number'] == "+61490942067"){
    $message_dir = '/var/www/message';
}
if($status == 'sent'){
    exec("cd ".$message_dir."; php send-message.php sms ".$phone_number_customer." ".escapeshellarg($content_messager));
    $phone_number = "+61421616733";
    $content_messager = "Sent to: ".$phone_number_customer.". ".$content_messager;
    exec("cd ".$message_dir."; php send-message.php sms ".$phone_number." ".escapeshellarg($content_messager));
}else{
    exec("cd ".$message_dir."; php send-message-scheduled.php sms ".$phone_number_customer." ".escapeshellarg($content_messager)." ".$timestamp .' ' .$uniqid);
    $phone_number = "+61421616733";
    $content_messager = "Sent to: ".$phone_number_customer.". ".$content_messager;
    exec("cd ".$message_dir."; php send-message-scheduled.php sms ".$phone_number." ".escapeshellarg($content_messager)." ".$timestamp .' ' .$uniqid);
}

