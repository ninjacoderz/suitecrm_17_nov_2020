<?php

array_push($job_strings, 'custom_readmail');

function replaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();
    
    $focusName = $request['parent_type'];
    $focus     = BeanFactory::getBean($focusName, $request['parent_id']);
    
    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate           = BeanFactory::getBean('EmailTemplates', isset($request['emails_email_templates_idb']) ? $request['emails_email_templates_idb'] : null);
    $email->name             = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description      = $emailTemplate->body;
    $templateData            = $emailTemplate->parse_email_template(array(
        'subject' => $email->name,
        'body_html' => $email->description_html,
        'body' => $email->description
    ), $focusName, $focus, $macro_nv);
    
    $email->name             = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description      = $templateData['body'];
    
    return $email;
}

function updateSolargainLead($leadID, $request, $email, $sg_user = "matthew")
{
   
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    $solargainLead = $lead->solargain_lead_number_c;
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    if($sg_user == "matthew"){
        $username = "matthew.wright";
        $password = "MW@pure733";
    }else{
        $username = "paul.szuster";
        $password = "Baited@42";
    }
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
    global $current_user;
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "";
    if (isset($email->from_name) && $email->from_name != "") {
        $note = $current_user->full_name . " : " . $email->from_name . " : " . $request["emails_email_templates_name"];
    }
    /*else {
    $note = $current_user->full_name. " : ".$request["emails_email_templates_name"];
    }*/
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 5,
            "Name" => "E-Mail Out",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
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
}

function handleMultipleFileAttachments($request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if ( /*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
        
        require_once('modules/Notes/Note.php');
        $note        = new Note();
        $where       = "notes.parent_id = '" . $request["emails_email_templates_idb"] . "' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template
        
        $attachments = array();
        
        $attachments = array_merge($attachments, $attach_list);
        
        foreach ($attachments as $noteId) {
            
            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id           = create_guid();
            $noteTemplate->new_with_id  = true; // duplicating the note with files
            //$noteTemplate->parent_id = $this->id;
            //$noteTemplate->parent_type = $this->module_dir;
            $noteTemplate->parent_id    = $email->id;
            $noteTemplate->parent_type  = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();
            
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
        //$email->attachments = array_merge($email->attachments, $noteArray);
    }
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini    = strpos($string, $start);
    if ($ini == 0)
        return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function custom_readmail() 
{
    global $sugar_config;
    
    $folder = $sugar_config['mail_dir'];
    
    $file_array = scandir($folder);
    
    if (count($file_array) == 2)
        return true;
    
    foreach ($file_array as $file) {
        // temporary move file to specials
        //copy($folder . "/" . $file, $folder . "/specials/" . $file);

        if (is_file($folder . "/" . $file)) {
            // parse all content of file to get address
            $l_file_content = file_get_contents($folder . "/" . $file);
            // Parse for create new lead from forwarded newlead@pure-electric.com.au
            preg_match('#To: (.+?)\@pure-electric.com.au#i', $l_file_content, $newlead);
            if($newlead[1] == "newlead"){
                
                $address_street = '';
                $city =  '';
                $state =  '';
                $post_code = '';

                preg_match('#Name (.+?)\n#i', $l_file_content, $lead_name);
                if(count($lead_name) == 2){
                    $lead = explode(" ", trim($lead_name[1]));
                    $first_name = $lead[0];
                    $last_name = str_replace($first_name, "", trim($lead_name[1]));
                }

                preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $matches_email);
                if (count($matches_email) == 2) {
                    $from_email = trim($matches_email[1]);
                }

                preg_match('#Email (.+?)\n#i', $l_file_content, $email);
                if(count($email)==2){
                    $lead_email = $email[1];
                    if($lead_email != ''){
                        //check email is exists
                        $db = DBManagerFactory::getInstance();
                        $query_lead = "SELECT leads.id
                                  FROM leads
                                  JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
                                  JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
                                  WHERE email_addresses.email_address = '".$lead_email."' AND leads.deleted = 0";
                        $result = $db->query($query_lead);
                        if($result->num_rows > 0){
                            
                            require_once('include/SugarPHPMailer.php');
                            $emailObj = new Email();
                            $defaults = $emailObj->getSystemDefaultEmail();
                            $mail = new SugarPHPMailer();
                            $mail->setMailerForSystem();
                            $mail->From = $defaults['email'];
                            $mail->FromName = $defaults['name'];
                            $mail->IsHTML(true);

                            $mail->Subject = 'Automatically add Lead';

                            $mail->Body = 'Can not automatically add lead because email '.$lead_email.' is exists! ';

                            $mail->prepForOutbound();
                            $mail->AddAddress($from_email);

                            $sent = $mail->Send();
                            die();
                        }
                    }
                }

                preg_match('#Phone (.+?)\n#i', $l_file_content, $phone);
                $phone = $phone[1];

                preg_match('#Address (.+?)\n#i', $l_file_content, $address);
                $address = strtolower($address[1]);

                if($address != ''){
                    $curl = curl_init();
                    $address = str_replace ( " " , "+" , $address );
                    $url = "https://www.energyaustralia.com.au/qt2/app/quoteservice/qas/find?address=".$address."&postcode=";
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            "Host: www.energyaustralia.com.au",
                            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding: 	gzip, deflate, br",
                            "Connection: keep-alive",
                            "Upgrade-Insecure-Requests: 1",
                            "Cache-Control: max-age=0",
                        )
                    );
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $out_address = json_decode($result);
                    if(count($out_address) >1){
                        $out_address = $out_address[1]->name;
                        $address1 = explode(',',$out_address);
                        $address_street = $address1[0];
                        $address_element = explode('  ',trim($address1[1]));
                        $city =  $address_element[0];
                        $state =  $address_element[1];
                        $post_code = $address_element[2];
                    }
                }

                $lead = new Lead();
                $lead->primary_address_street     = $address_street ? $address_street : "";
                $lead->primary_address_postalcode = $post_code ? $post_code : "";
                $lead->primary_address_city       = $city ? $city : "";
                $lead->primary_address_state      = $state ? $state : "";
                $lead->first_name = $first_name;
                $lead->last_name = $last_name;
                $lead->email1 = $lead_email;
                $lead->phone_mobile = $phone;

                if($from_email == "matthew@pure-electric.com.au" || $from_email == "matthew.wright@pure-electric.com.au"){
                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                }else if($from_email == "paul.szuster@pure-electric.com.au" || $from_email == "paul@pure-electric.com.au"){
                    $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                }else{
                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                }
                $lead->save;

                // save file attachment and thumb
                $count_ =  substr_count($l_file_content,'Content-Disposition: attachment;');

                $guid_new = create_guid();
                $current_file_path = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/server/php/files/'.$guid_new;
                
                if(!file_exists ( $current_file_path )) {
                    set_time_limit ( 0 );
                    mkdir($current_file_path);
                }

                preg_match_all('/filename="(.*?)"(.*?)--0000000000/s',$l_file_content,$match_pdf);
                for ($i=0; $i < $count_ ; $i++) {
                    $file_name =  $match_pdf[1][$i];
                    $file_content_ = $match_pdf[2][$i];
                    $file_content_ = explode("\n\n",$file_content_);
                    $source = $current_file_path.'/'.$file_name;
                    $fp = fopen($source, "w+");
                    fwrite($fp, base64_decode($file_content_[1]));
                    fclose($fp);

                    if(is_file($source)){
                        $type = strtolower(substr(strrchr($file_name, '.'), 1));
                        $typeok = TRUE;
                        if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                            if(!file_exists ($current_file_path."/thumbnail/")) {
                                mkdir($current_file_path."/thumbnail/");
                            }
                            $thumb =  $current_file_path."/thumbnail/".$file_name;
                            switch ($type) {
                                case 'jpg': // Both regular and progressive jpegs
                                case 'jpeg':
                                    $src_func = 'imagecreatefromjpeg';
                                    $write_func = 'imagejpeg';
                                    $image_quality = isset($options['jpeg_quality']) ?
                                        $options['jpeg_quality'] : 75;
                                    break;
                                case 'gif':
                                    $src_func = 'imagecreatefromgif';
                                    $write_func = 'imagegif';
                                    $image_quality = null;
                                    break;
                                case 'png':
                                    $src_func = 'imagecreatefrompng';
                                    $write_func = 'imagepng';
                                    $image_quality = isset($options['png_quality']) ?
                                        $options['png_quality'] : 9;
                                    break;
                                default: $typeok = FALSE; break;
                            }
                            if ($typeok){
                                list($w, $h) = getimagesize($source);

                                $src = $src_func($source);
                                $new_img = imagecreatetruecolor(80,80);
                                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                                $write_func($new_img,$thumb, $image_quality);

                                imagedestroy($new_img);
                                imagedestroy($src);
                            }
                        }
                    }
                }
                
                $lead->installation_pictures_c = $guid_new;
                $lead->save();
                unlink($folder . "/" . $file);
                return true; // break function 
            }

            // End parese
            preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $email_matches);
            if (isset($email_matches[1]) && $email_matches[1] != "") {
                preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $email_matches[1], $mail_matchs);
                if (isset($mail_matchs[0]) && $mail_matchs[0] != "") {
                    $email = $mail_matchs[0];
                    $db    = DBManagerFactory::getInstance();
                    $sql   = "SELECT * FROM email_addresses ea 
                                        LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                        WHERE 1=1 AND ea.email_address = '$email' AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module = 'Leads'
                                        ";
                    $ret   = $db->query($sql);
                    
                    while ($row = $db->fetchByAssoc($ret)) {
                        // We get address here 
                        preg_match('/my address is(:|)(.*?)(\n|\.)/i', $l_file_content, $address_matchs);
                        if (count($address_matchs) && $address_matchs[2] != "") {
                            // use regrxt pattern to get address 
                            // Just update address lead
                            $lead = new Lead();
                            $lead->retrieve($row["bean_id"]);
                            $lead->primary_address_street = $address_matchs[2];
                            $lead->save();
                            sendDesignRequestToAdmin($lead->id);
                            fclose($handle);
                            unlink($folder . "/" . $file);
                            return;
                        }
                    }
                }
            }

            // Get full plain text of email 
            $web_enquiry            = get_string_between($l_file_content, "web.enquiries", "01/01/0001");
            $full_plain_text        = get_string_between($l_file_content, "Content-Type: text/plain;", "Content-Type: text/html;");
            $address_section        = get_string_between($l_file_content, "Site Details", "Roof Type:");
            $address_section        = strip_tags($address_section);
            $address_section        = preg_replace('/\<http(.+?)\>/s', '', $address_section);
            $address_section        = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $address_section);
            $client_primary_address = "";

            // Pattern to get 
            $address_pattern = '/(\n([a-zA-Z-\s])+( )|)+(VIC|SA|NSW|ACT|TAS|WA|QLD|NT)+( )+(\d{4})/s';
            $out_address     = array();
            preg_match_all($address_pattern, $address_section, $out_address, PREG_PATTERN_ORDER);
            $out_address[0][0] = trim($out_address[0][0]);
            if (isset($out_address[0][0]) && $out_address[0][0] != "") {
                $address_element = explode(" ", $out_address[0][0]);
                if (count($address_element) >= 3) {
                    $state            = $address_element[count($address_element) - 2];
                    $post_code        = $address_element[count($address_element) - 1];
                    $suburb           = str_replace($state . " " . $post_code, "", $out_address[0][0]);
                    $suburb           = str_replace("-", "", $suburb);
                    $exploded_address = explode("\n", $out_address[0][0]);
                    if (count($exploded_address) == 2)
                        $client_primary_address = $exploded_address[0];
                }
                
                if (count($address_element) == 2) {
                    $state     = $address_element[0];
                    $post_code = $address_element[1];
                    $suburb    = "";
                }
            }

            // Special solve address 
            // dont understand why it s coded
            if (false){ //$client_primary_address == "") {
                /*$handle = fopen($folder."/".$file, "r");
                if ($handle) {
                $temp_client_primary_address = "";
                while (($line = fgets($handle)) !== false) {
                // get the next line
                if(isset($out_address[0][0]) &&  $out_address[0][0] != "" && (strpos($line, $out_address[0][0] ) !== false) ){
                if(strlen($temp_client_primary_address) > 5)
                if($client_primary_address == "") $client_primary_address = $temp_client_primary_address;
                }
                $temp_client_primary_address = $line;
                }
                }
                fclose($handle);
                */
                $client_primary_address = trim(preg_replace('/[^a-zA-Z0-9]/', " ", str_replace(array(
                    $suburb,
                    $post_code,
                    $state
                ), "", $address_section)));
            }
            
            if (strlen($client_primary_address) < 2)
                $client_primary_address = "";
            
            $handle = fopen($folder . "/" . $file, "r");
            if ($handle) {
                $live_chat_text        = "";
                $solargain_lead_number = "";
                $note                  = "";
                $end_live_chat         = false;
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, "This e-mail is private and confidential") !== false) {
                        fclose($handle);
                        copy($folder . "/" . $file, $folder . "/backup/" . $file);
                        unlink($folder . "/" . $file);
                        break;
                    }
                    ;
                    if (strpos(strtolower($line), "lead/edit") !== false) {
                        preg_match('/lead\/edit\/(.+?)>]/', strtolower($line), $match_firstchars);
                        if (isset($match_firstchars[1]) && $match_firstchars[1] != "") {
                            // Neu solargain lead number van la solargain cu ta continue toi dong tiep theo cho den khi gap Lead Edit 
                            if ($solargain_lead_number == $match_firstchars[1])
                                continue;
                            $solargain_lead_number = $match_firstchars[1];
                        } else
                            continue;
                        
                        while (($line1 = fgets($handle)) !== false) {
                            
                            if (strpos($line1, "This e-mail is private and confidential") !== false) {
                                fclose($handle);
                                copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                unlink($folder . "/" . $file);
                                break;
                            }
                            ;
                            
                            $line1 = strip_tags($line1);
                            // Co live chat
                            if (strpos($line1, "livechat") !== false) {
                                $api_comment_line = fgets($handle);
                                
                                if ($api_comment_line !== false && (strpos($api_comment_line, "API Comment: Livechat Submission") !== false)) {
                                    $blank_line = fgets($handle);
                                    while (($l_line = fgets($handle)) !== false) {
                                        
                                        if (strpos($l_line, "This e-mail is private and confidential") !== false) {
                                            fclose($handle);
                                            copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                            unlink($folder . "/" . $file);
                                            break;
                                        }
                                        ;
                                        
                                        if (strpos($l_line, "Customer Details") == false) {
                                            if (strpos($l_line, "01/01/0001") !== false) {
                                                $end_live_chat = true;
                                            } else {
                                                if (!$end_live_chat)
                                                    $live_chat_text .= trim(strip_tags($l_line), "=\n");
                                            }
                                        } else if (strpos($l_line, "Customer Details") !== false) {
                                            // Read the next line
                                            if (($next_line = fgets($handle)) !== false) {
                                                $next_line = strip_tags($next_line);
                                                if (strpos($next_line, ",") !== false) {
                                                    $names = explode(",", $next_line);
                                                } else {
                                                    $names = explode(" ", $next_line);
                                                }
                                                $first_name = "";
                                                $last_name  = "";
                                                $first_name = trim($names[1] ? $names[1] : "");
                                                $first_name = ucfirst(strtolower($first_name));
                                                $last_name  = trim($names[0] ? $names[0] : "");
                                                $last_name  = ucfirst(strtolower($last_name));
                                                
                                                if ($first_name == "") {
                                                    $last_name_explode = explode(" ", $last_name);
                                                    if (count($last_name_explode) > 1) {
                                                        $real_last_name = end($last_name_explode);
                                                        $first_name     = str_replace($real_last_name, "", $last_name);
                                                        $last_name      = $real_last_name;
                                                    }
                                                }
                                                
                                                $phone_mobile = "";
                                                $phone_work   = "";
                                                $email        = "";
                                                
                                                // Not exist do our next work
                                                // read all line by while until we get the new custommer lead
                                                
                                                while (($next_line = fgets($handle)) !== false) {
                                                    //if touch to end of New lead sections
                                                    // we do the if statement for each pattern here
                                                    if (strpos($next_line, "This e-mail is private and confidential") !== false) {
                                                        fclose($handle);
                                                        copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                                        unlink($folder . "/" . $file);
                                                        break;
                                                    }
                                                    
                                                    if (strpos($next_line, "m:") === 0) {
                                                        $phone_mobile = strip_tags(trim(str_replace(array(
                                                            "m:",
                                                            "**",
                                                            "*m:*"
                                                        ), "", $next_line)));
                                                        $phone_mobile = ($phone_mobile != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_mobile))) ) ? $phone_mobile : "";
                                                    }

                                                    if (strpos($next_line, "p:") !== false) {
                                                        $phone_work = strip_tags(trim(str_replace(array(
                                                            "*p:*",
                                                            "p:"
                                                        ), "", $next_line)));
                                                        $phone_work = ($phone_work != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_work))) ) ? $phone_work : "";
                                                    }
                                                    
                                                    if ( (strpos($next_line, "e:") === 0 || strpos($next_line, "*e:") === 0) && strpos($next_line, "@") !== false) {
                                                        //$email = strip_tags(trim(str_replace(array("*e:*", "e:"), "", $next_line)));
                                                        //$email = trim(str_replace(array("*e:*", "e:"),"",strip_tags( $next_line)));
                                                        //$email = ($email != "N/A\n") ? trim($email) : "";
                                                        preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $next_line, $mail_match_firstchars);
                                                        if (isset($mail_match_firstchars[0]) && $mail_match_firstchars[0] != "") {
                                                            $email = $mail_match_firstchars[0];
                                                        }
                                                        
                                                        // If we have already name in database Break
                                                        $db = DBManagerFactory::getInstance();
                                                        if ($first_name == "" && $last_name == "") {
                                                            fclose($handle);
                                                            unlink($folder . "/" . $file);
                                                            return;
                                                        }
                                                        
                                                        $db  = DBManagerFactory::getInstance();
                                                        $sql = "SELECT * FROM email_addresses ea 
                                                                            LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                                                            WHERE 1=1 AND ea.email_address = '$email' AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module = 'Leads'
                                                                            ";
                                                        $ret = $db->query($sql);
                                                        
                                                        while ($row = $db->fetchByAssoc($ret)) {
                                                            if (isset($row["bean_id"]) && $row["bean_id"] != "") {
                                                                fclose($handle);
                                                                unlink($folder . "/" . $file);
                                                                return;
                                                            }
                                                        }
                                                        
                                                        $sql = "SELECT * FROM leads WHERE 1=1 ";
                                                        //if ($first_name != "")
                                                        $sql .= " AND first_name = '" . $first_name . "'";
                                                        //if ($last_name != "")
                                                        $sql .= " AND last_name = '" . $last_name . "'";
                                                        $sql .= " AND deleted != 1 ";
                                                        $ret      = $db->query($sql);
                                                        $is_exsit = false;
                                                        while ($row = $db->fetchByAssoc($ret)) {
                                                            if (isset($row) && $row != null) {
                                                                $lead = new Lead();
                                                                $lead->retrieve($row["id"]);
                                                                
                                                                if ($lead->email1 == $email)
                                                                    $is_exsit = true;
                                                                if ($email == "") {
                                                                    $is_exsit = true;
                                                                }
                                                            }
                                                        }

                                                        if ($is_exsit) {
                                                            fclose($handle);
                                                            unlink($folder . "/" . $file);
                                                            return;
                                                        }
                                                        
                                                        $next_second_line = fgets($handle);
                                                        $note .= $next_second_line;
                                                        
                                                        //$GLOBALS['log']->debug('--------------------------------------------> at mext line.php <--------------------------------------------' .$next_second_line );
                                                        $next_third_line = fgets($handle);
                                                        $note .= $next_third_line;
                                                        
                                                        if ($next_third_line == $out_address[0][0] && strlen($next_second_line) > 5) {
                                                            $street_address = strip_tags($next_second_line);
                                                        }

                                                        // If it is full adreses
                                                        if (!isset($post_code) || $post_code == "") {
                                                            if (strlen($next_second_line) > 5) {
                                                                $next_fourth_line = fgets($handle);
                                                                if (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                                    $next_fourth_line = trim(strip_tags($next_fourth_line));
                                                                    //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                                    //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                                    $state            = $match_firstchars[0][0];
                                                                    
                                                                    $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line);
                                                                    $suburb        = $match_subburb[0];
                                                                    $post_code     = $match_subburb[1];
                                                                }
                                                            } elseif (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                                $next_third_line = trim(strip_tags($next_third_line));
                                                                //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                                //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                                $state           = $match_firstchars[0][0];
                                                                
                                                                $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line);
                                                                $suburb        = $match_subburb[0];
                                                                $post_code     = $match_subburb[1];
                                                            }
                                                        }
                                                    }
                                                    $note .= strip_tags($next_line);
                                                    if (strpos(strtolower($next_line), "assigned to") !== false) {
                                                        $note .= strip_tags($next_line);
                                                    }
                                                    
                                                }
                                                //if (strpos($line1, "Status:") !== false) break;
                                            }
                                            
                                            // store lead here
                                            if ($first_name != "" || $last_name != "") {
                                                $lead                             = new Lead();
                                                $lead->first_name                 = $first_name;
                                                $lead->last_name                  = $last_name;
                                                $lead->email1                     = $email;
                                                $lead->phone_mobile               = $phone_mobile;
                                                $lead->phone_work                 = $phone_work;
                                                $lead->solargain_lead_number_c    = $solargain_lead_number;
                                                $lead->primary_address_postalcode = $post_code ? $post_code : "";
                                                $lead->primary_address_city       = $suburb ? $suburb : "";
                                                $lead->primary_address_state      = $state ? $state : "";
                                                $lead->live_chat_c                = $live_chat_text ? str_replace("\n\n", "", $live_chat_text) : $web_enquiry;
                                                $lead->lead_source                = "Solargain";
                                                $lead->primary_address_street     = $client_primary_address ? $client_primary_address : ($street_address ? $street_address : "");
                                                if(strtolower(trim($lead->primary_address_street)) == 'n a' || strtolower(trim($lead->primary_address_street)) == 'n/a' || strtolower(trim($lead->primary_address_street)) == 'na'){
                                                    $lead->primary_address_street = "";
                                                }
                                                $full_plain_text_explode          = explode("This e-mail is private and confidential", $full_plain_text);
                                                $note_des = explode("Notes", $full_plain_text_explode[0]);
                                                $lead->description                = $note_des[1];
                                                $lead->address_provided_c         = $client_primary_address ? "1" : "0";
                                                $lead->status                     = "Assigned";
                                                
                                                
                                                preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $sent_froms);
                                                if(isset($sent_froms[1]) && $sent_froms[1] == "Matthew.Wright@solargain.com.au"){
                                                    $random_number = 60;
                                                } elseif(isset($sent_froms[1]) && (strtolower($sent_froms[1]) == "paul.szuster@solargain.com.au")) {
                                                    $random_number = 40;
                                                } else {
                                                    $random_number = rand ( 1 , 100 );
                                                }
                                                
                                                if ($random_number <= 50) {
                                                    $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa"; // Paul
                                                } else{
                                                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e"; // Matth
                                                }
                                                
                                                $lead->save();
                                                /* Need enought address
                                                $primary_address_street = $lead->primary_address_street;
                                                $primary_address_city = $lead->primary_address_city;
                                                $primary_address_state = $lead->primary_address_state;
                                                $primary_address_postalcode = $lead->primary_address_postalcode;
                                                */
                                                if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                                    
                                                    // Send email
                                                    if ($random_number <= 50) {
                                                        $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                                    } else{
                                                        $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                                    }
                                                    
                                                    $temp_request = array(
                                                        "module" => "Emails",
                                                        "action" => "send",
                                                        "record" => "",
                                                        "type" => "out",
                                                        "send" => 1,
                                                        "inbound_email_id" => ($random_number > 50) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                                        "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                                        "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                                        "parent_type" => "Leads",
                                                        "parent_name" => $lead->first_name ." ". $lead->last_name,
                                                        "parent_id" => $lead->id,
                                                        "from_addr" => $from_address,
                                                        "to_addrs_names" => $lead->email1, //"binhdigipro@gmail.com",//$lead->email1,
                                                        "cc_addrs_names" => "info@pure-electric.com.au",
                                                        "bcc_addrs_names" =>  "binh.nguyen@pure-electric.com.au",
                                                        "is_only_plain_text" => false
                                                    );

                                                    $emailBean    = new Email();
                                                    $emailBean    = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                                    
                                                    $emailBean->save();
                                                    
                                                    $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);
                                                    //$GLOBALS['log']->debug('--------------------------------------------> LEAD Number Run HERE <--------------------------------------------\n' .$solargain_lead_number );
                                                    // parse and replace bean variables
                                                    $emailBean                    = replaceEmailVariables($emailBean, $temp_request);
                                                    
                                                    // Signature
                                                    $matthew_id                   = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                                    $paul_id                      = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                                    $user                         = new User();
                                                    $user->retrieve($matthew_id);

                                                    if ($random_number <= 50) {
                                                        $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                                    } else{
                                                        $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; // Matthew signature
                                                    }
                                                    
                                                    $signature = $user->getSignature($emailSignatureId);
                                                    $emailBean->description .= $signature["signature"];
                                                    $emailBean->description_html .= $signature["signature_html"];
                                                    $emailBean->description .= $live_chat_text;
                                                    $emailBean->description_html .= $live_chat_text;
                                                    
                                                    //thien fix save email khi có live chat
                                                    $emailBean->save();
                                                    
                                                    $lead->email_send_id_c     = $emailBean->id;
                                                    $lead->email_send_status_c = 'pending';
                                                    $lead->save();
                                                    
                                                    if ($temp_request["parent_type"] == "Leads") {
                                                        $leadID = $temp_request["parent_id"];
                                                        //updateSolargainLead($leadID, $temp_request, $emailBean);
                                                    }
                                                    
                                                    //$body_html = $emailBean->description_html;
                                                    
                                                    // thien comment
                                                    // if ($emailBean->send()) {
                                                    //     $emailBean->status = 'sent';
                                                    //     // Do extended things here
                                                    //     // Save note to solargain
                                                    
                                                    //     if($temp_request["parent_type"] == "Leads"){
                                                    //         $leadID = $temp_request["parent_id"];
                                                    //         updateSolargainLead($leadID, $temp_request, $emailBean);
                                                    //     }
                                                    //     $emailBean->save();
                                                    
                                                    //     $body_html = $emailBean->description_html;
                                                    
                                                    // } else {
                                                    //     // Don't save status if the email is a draft.
                                                    //         // We need to ensure that drafts will still show
                                                    //         // in the list view
                                                    //         if ($emailBean->status !== 'draft') {
                                                    //             $emailBean->status = 'send_error';
                                                    //             $emailBean->save();
                                                    //         } else {
                                                    //             $emailBean->status = 'send_error';
                                                    //         }
                                                    // }

                                                    // Send SMS to client too 
                                                    $phone_number = $lead->phone_mobile ? $lead->phone_mobile : $lead->phone_work;
                                                    if($phone_number){
                                                        $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
                                                        if(strlen($phone_number) >= 10){
                                                            $phone_number = preg_replace("/^61/", "+61", $phone_number);
                                                            if(strpos($phone_number, "+61") !== false ){
                                                                $user = new User();
                                                                $user = $user->retrieve($lead->assigned_user_id);
                                                                $message_body = 'Hi '.$lead->first_name.', my name is '.$user->first_name.' from Solargain. I received your request for a Solargain solar/battery quote for your place, I have that you are in '.$lead->primary_address_city.' '.$lead->primary_address_state.'. If you could please reply back with your street address I would be more than happy to assist.  Look forward to your response. Regards, '.$user->first_name;
                                                                //exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$message_body.'"');
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    //thien fix
                                                    // Do nothing
                                                }
                                                $record_id = $lead->id;
                                                sendDesignRequestToAdmin($record_id);
                                            }
                                            
                                        }
                                    }
                                }
                                //$GLOBALS['log']->debug('--------------------------------------------> Live chat <--------------------------------------------\n' .$live_chat_text );
                            }
                            // Khong co live chat
                            if (strpos($line1, "Customer Details") !== false) {
                                // Read the next line
                                if (($next_line = fgets($handle)) !== false) {
                                    $next_line  = fgets($handle);
                                    $next_line  = strip_tags($next_line);
                                    $names      = explode(",", $next_line);
                                    $first_name = "";
                                    $last_name  = "";
                                    $first_name = trim($names[1] ? $names[1] : "");
                                    $first_name = ucfirst(strtolower($first_name));
                                    $last_name  = trim($names[0] ? $names[0] : "");
                                    $last_name  = ucfirst(strtolower($last_name));
                                    
                                    $phone_mobile = "";
                                    $phone_work   = "";
                                    $email        = "";
                                    
                                    // Not exist do our next work
                                    // read all line by while until we get the new custommer lead
                                    
                                    while (($next_line = fgets($handle)) !== false) {
                                        //if touch to end of New lead sections
                                        // we do the if statement for each pattern here
                                        
                                        if (strpos($next_line, "This e-mail is private and confidential") !== false) {
                                            fclose($handle);
                                            copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                            unlink($folder . "/" . $file);
                                            break;
                                        }
                                    
                                        if (strpos($next_line, "m:") === 0) {
                                            $phone_mobile = strip_tags(trim(str_replace(array(
                                                "m:",
                                                "**",
                                                "*m:*"
                                            ), "", $next_line)));
                                            $phone_mobile = ($phone_mobile != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_mobile))) ) ? $phone_mobile : "";
                                        }
                                        
                                        if (strpos($next_line, "p:") !== false) {
                                            $phone_work = strip_tags(trim(str_replace(array(
                                                "*p:*",
                                                "p:"
                                            ), "", $next_line)));
                                            $phone_work = ($phone_work != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_work))) ) ? $phone_work : "";
                                        }
                                        
                                        if ( (strpos($next_line, "e:") === 0 || strpos($next_line, "*e:") === 0) && strpos($next_line, "@") !== false) {
                                            //$email = strip_tags(trim(str_replace(array("*e:*", "e:"), "", $next_line)));
                                            //$email = trim(str_replace(array("*e:*", "e:"),"",strip_tags( $next_line)));
                                            //$email = ($email != "N/A\n") ? trim($email) : "";
                                            preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $next_line, $mail_match_firstchars);
                                            if (isset($mail_match_firstchars[0]) && $mail_match_firstchars[0] != "") {
                                                $email = $mail_match_firstchars[0];
                                            }
                                            
                                            // If we have already name in database Break
                                            $db = DBManagerFactory::getInstance();
                                            if ($first_name == "" && $last_name == "") {
                                                fclose($handle);
                                                unlink($folder . "/" . $file);
                                                return;
                                            }
                                            $sql = "SELECT * FROM leads WHERE 1=1 ";
                                            //if ($first_name != "")
                                            $sql .= " AND first_name = '" . $first_name . "'";
                                            //if ($last_name != "")
                                            $sql .= " AND last_name = '" . $last_name . "'";
                                            $sql .= " AND deleted != 1 ";
                                            $ret      = $db->query($sql);
                                            $is_exsit = false;
                                            while ($row = $db->fetchByAssoc($ret)) {
                                                if (isset($row) && $row != null) {
                                                    $lead = new Lead();
                                                    $lead->retrieve($row["id"]);
                                                    
                                                    if ($lead->email1 == $email)
                                                        $is_exsit = true;
                                                }
                                            }
                                            if ($is_exsit) {
                                                fclose($handle);
                                                unlink($folder . "/" . $file);
                                                return;
                                            }
                                            
                                            $next_second_line = fgets($handle);
                                            $note .= $next_second_line;
                                            //$GLOBALS['log']->debug('--------------------------------------------> at mext line.php <--------------------------------------------' .$next_second_line );
                                            $next_third_line = fgets($handle);
                                            $note .= $next_third_line;
                                            
                                            if ($next_third_line == $out_address[0][0] && strlen($next_second_line) > 5) {
                                                $street_address = strip_tags($next_second_line);
                                            }
                                            
                                            // If it is full adreses
                                            if (!isset($post_code) || $post_code == "") {
                                                if (strlen($next_second_line) > 5) {
                                                    $next_fourth_line = fgets($handle);
                                                    if (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                        $next_fourth_line = trim(strip_tags($next_fourth_line));
                                                        //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                        //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                        $state            = $match_firstchars[0][0];
                                                        
                                                        $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line);
                                                        $suburb        = $match_subburb[0];
                                                        $post_code     = $match_subburb[1];
                                                    }
                                                } elseif (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                    $next_third_line = trim(strip_tags($next_third_line));
                                                    //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                    //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                    $state           = $match_firstchars[0][0];
                                                    
                                                    $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line);
                                                    $suburb        = $match_subburb[0];
                                                    $post_code     = $match_subburb[1];
                                                }
                                            }
                                        }
                                        $note .= strip_tags($next_line);
                                        
                                        if (strpos(strtolower($next_line), "Assigned To") !== false) {
                                            $note .= strip_tags($next_line);
                                        }
                                        
                                    }
                                    
                                    //if (strpos($line1, "Status:") !== false) break;
                                    
                                }
                                // store lead here

                                if ($first_name != "" || $last_name != "") {
                                    $lead                             = new Lead();
                                    $lead->first_name                 = $first_name;
                                    $lead->last_name                  = $last_name;
                                    $lead->email1                     = $email;
                                    $lead->phone_mobile               = $phone_mobile;
                                    $lead->phone_work                 = $phone_work;
                                    $lead->solargain_lead_number_c    = $solargain_lead_number;
                                    $lead->primary_address_postalcode = $post_code ? $post_code : "";
                                    $lead->primary_address_city       = $suburb ? $suburb : "";
                                    $lead->primary_address_state      = $state ? $state : "";
                                    $lead->primary_address_street     = $client_primary_address ? $client_primary_address : ($street_address ? $street_address : "");
                                    if(strtolower(trim($lead->primary_address_street)) == 'n a' || strtolower(trim($lead->primary_address_street)) == 'n/a' || strtolower(trim($lead->primary_address_street)) == 'na'){
                                        $lead->primary_address_street = "";
                                    }
                                    $lead->live_chat_c                = $live_chat_text ? str_replace("\n\n", "", $live_chat_text) : $web_enquiry;
                                    $lead->lead_source                = "Solargain";
                                    $full_plain_text_explode          = explode("This e-mail is private and confidential", $full_plain_text);
                                    $note_des = explode("Notes", $full_plain_text_explode[0]);
                                    $lead->description                = $note_des[1];
                                    
                                    $lead->address_provided_c = $client_primary_address ? "1" : "0";
                                    $lead->status             = "Assigned";

                                    preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $sent_froms);
                                    if(isset($sent_froms[1]) && $sent_froms[1] == "Matthew.Wright@solargain.com.au"){
                                        $random_number = 60;
                                    } elseif(isset($sent_froms[1]) && (strtolower($sent_froms[1]) == "paul.szuster@solargain.com.au")) {
                                        $random_number = 40;
                                    } else {
                                        $random_number = rand ( 1 , 100 );
                                    }
                                    
                                    if ($random_number <= 50) {
                                        $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa"; // Paul
                                    } else{
                                        $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e"; // Matth
                                    }
                                    
                                    $lead->save();
                                    
                                    if ($random_number <= 50) {
                                        $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                    } else{
                                        $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                    }
                                    
                                    $matthew_inbound_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
                                    $paul_inbound_id    = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
                                    
                                    if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                        $temp_request        = array(
                                            "module" => "Emails",
                                            "action" => "send",
                                            "record" => "",
                                            "type" => "out",
                                            "send" => 1,
                                            "inbound_email_id" => ($random_number > 50) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                            "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                            "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                            "parent_type" => "Leads",
                                            "parent_name" => $lead->first_name .' '. $lead->last_name,
                                            "parent_id" => $lead->id,
                                            "from_addr" => $from_address,
                                            "to_addrs_names" => $lead->email1, //$lead->email1, //"binhdigipro@gmail.com",
                                            "cc_addrs_names" => "info@pure-electric.com.au",
                                            "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
                                            "is_only_plain_text" => false
                                        );

                                        $emailBean           = new Email();
                                        $emailBean           = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                        $inboundEmailAccount = new InboundEmail();
                                        $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);

                                        $emailBean->save();
                                        $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);
                                        
                                        // parse and replace bean variables
                                        $emailBean = replaceEmailVariables($emailBean, $temp_request);
                                        
                                        // Signature
                                        $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                        $paul_id    = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                        $user       = new User();
                                        $user->retrieve($matthew_id);
                                        if ($random_number <= 50) { // Matthew 
                                            $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                        } else{
                                            $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
                                        }
                                        
                                        $signature = $user->getSignature($emailSignatureId);
                                        $emailBean->description .= $signature["signature"];
                                        $emailBean->description_html .= $signature["signature_html"];
                                        $emailBean->description .= $live_chat_text;
                                        $emailBean->description_html .= $live_chat_text;
                                        
                                        //thien fix save email khi không có live chat
                                        $emailBean->save();
                                        
                                        if ($temp_request["parent_type"] == "Leads") {
                                            $leadID = $temp_request["parent_id"];
                                            //updateSolargainLead($leadID, $temp_request, $emailBean);
                                        }
                                        
                                        $lead->email_send_id_c     = $emailBean->id;
                                        $lead->email_send_status_c = 'pending';
                                        
                                        $lead->save();
                                        
                                        //thien commnent
                                        // if ($emailBean->send()) {
                                        //     $emailBean->status = 'sent';
                                        //     // Do extended things here
                                        //     // Save note to solargain
                                        //     if($temp_request["parent_type"] == "Leads"){
                                        //         $leadID = $temp_request["parent_id"];
                                        //         updateSolargainLead($leadID, $temp_request, $emailBean);
                                        //     }
                                        //     $emailBean->save();
                                        // } else {
                                        //         if ($emailBean->status !== 'draft') {
                                        //             $emailBean->status = 'send_error';
                                        //             $emailBean->save();
                                        //         } else {
                                        //             $emailBean->status = 'send_error';
                                        //         }
                                        // }

                                        // Send SMS To client
                                        $phone_number = $lead->phone_mobile ? $lead->phone_mobile : $lead->phone_work;
                                        if($phone_number){
                                            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
                                            if(strlen($phone_number) >= 10){
                                                $phone_number = preg_replace("/^61/", "+61", $phone_number);

                                                if(strpos($phone_number, "+61") !== false ){
                                                    $user = new User();
                                                    $user = $user->retrieve($lead->assigned_user_id);
                                                    $message_body = 'Hi '.$lead->first_name.', my name is '.$user->first_name.' from Solargain. I received your request for a Solargain solar/battery quote for your place, I have that you are in '.$lead->primary_address_city.' '.$lead->primary_address_state.'. If you could please reply back with your street address I would be more than happy to assist.  Look forward to your response. Regards, '.$user->first_name;
                                                    // we will not send sms
                                                    // exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$message_body.'"');
                                                }
                                            }
                                        }
                                    } else {
                                        //thien fix
                                        
                                    }
                                    $record_id = $lead->id;
                                    sendDesignRequestToAdmin($record_id);
                                }
                                
                            }
                            
                        }
                    }
                }
                
                fclose($handle);
                if ($lead->id != "") {
                    copy($folder . "/" . $file, $folder . "/backup/" . $file);
                }
                unlink($folder . "/" . $file);
                return;
            } else {
                // error opening the file.
            }
        }
    }
}

function updateDesignForSolargainLead($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    
    $solargainLead = $lead->solargain_lead_number_c;
    
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
    
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note                   = "Preparing designs and quote for client";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 1,
            "Name" => "General",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
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
}

function getLatLong($address)
{
    
    $array = array();
    $geo   = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
    
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude  = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $array     = array(
            'lat' => $latitude,
            'lng' => $longitude
        );
    }
    
    return $array;
}

function sendDesignRequestToAdmin($record_id)
{

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = getLatLong($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "Leads",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $lead->id,
        "from_addr" => $from_address,
        "to_addrs_names" => "admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    $emailBean->save();
    $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();

    updateDesignForSolargainLead($record_id);
    $lead->email_send_design_request_id_c = $emailBean->id;
    $lead->email_send_design_status_c = 'pending';
    $lead->save();

    return $emailBean;
}
