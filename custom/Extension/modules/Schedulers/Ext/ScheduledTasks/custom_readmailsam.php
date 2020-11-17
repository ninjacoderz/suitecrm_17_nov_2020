<?php
array_push($job_strings, 'custom_readmailsam');

function custom_readmailsam(){
    $hostname = '{webmail.solargain.com.au:993/imap/ssl/novalidate-cert}INBOX'; 
    $username = 'paul.szuster@solargain.com.au';
    $password = 'Baited@42';

    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Exchange: ' . imap_last_error());
    
    /* grab emails */
    $date = new DateTime("-1 days");
    $date_before_1_days = $date->format('d F Y');
    
    //$emails = imap_search($inbox,'SUBJECT "Assigned (L#" SINCE "'.$date_before_3_days.'" UNSEEN');
    $emails = imap_search($inbox,'SINCE "'.$date_before_1_days.'" UNSEEN');
    
    /* if emails are returned, cycle through each... */
    if($emails) {
        
        /* begin output var */
        $output = '';
        
        /* put the newest emails on top */
        rsort($emails);
        
        /* for every email... */
        foreach($emails as $email_number) {

            $overview  = imap_fetch_overview($inbox,$email_number,0);
            $subject   =  htmlentities($overview[0]->subject);
            
            if(stripos($subject,"Assigned (L#") !== false){
                $file_content = strip_tags(imap_body($inbox, $email_number));
                $result_array_info_email = get_information_from_email($file_content);
                if(strpos($result_array_info_email['lead_status'],'Assigned') !== false || strpos($result_array_info_email['lead_status'],'New') !== false){
                    $result_array_address = get_address($result_array_info_email['lead_address']);
                    $result_array_name = get_name($result_array_info_email['lead_fullname']);

                    //check exist lead
                    if(!check_exist_lead($result_array_name['first_name'],$result_array_name['last_name'],$result_array_info_email['lead_email'])){
                        if($result_array_name['first_name'] != '' || $result_array_name['last_name'] != '') {
                            $lead = new Lead();
                            $lead->primary_address_street     = $result_array_address['street'] ? $result_array_address['street'] : '';
                            $lead->primary_address_postalcode = $result_array_address['postcode'] ? $result_array_address['postcode'] : "";
                            $lead->primary_address_city       = $result_array_address['city'] ? $result_array_address['city'] : "";
                            $lead->primary_address_state      = $result_array_address['state'] ? $result_array_address['state'] : "";
                            $lead->first_name  = $result_array_name['first_name'] ? $result_array_name['first_name'] : "";
                            $lead->last_name = $result_array_name['last_name'] ? $result_array_name['last_name'] : "";
                            $lead->email1 = $result_array_info_email['lead_email'] ? trim($result_array_info_email['lead_email']) : "";
                            $lead->phone_work  = $result_array_info_email['lead_phone'] ? $result_array_info_email['lead_phone'] : "";
                            $lead->phone_mobile  = $result_array_info_email['lead_mobile_phone'] ? $result_array_info_email['lead_mobile_phone'] : "";
                            
                            $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";//defaul paul
                            $lead->lead_source                = "Solargain";
                            $lead->description = $result_array_info_email['lead_note'];
                            $lead->status = "Assigned";
                            $lead->lead_source_co_c = 'Solargain';
                            $lead->solargain_lead_number_c = $result_array_info_email["lead_number"];
                            $lead->save();   
                            if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                                        
                                // Send email 
                                if ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") {
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
                                    "inbound_email_id" => ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
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
                                
                                $emailBean->saved_attachments = custom_handleMultipleFileAttachments($temp_request, $emailBean);
                                //$GLOBALS['log']->debug('--------------------------------------------> LEAD Number Run HERE <--------------------------------------------\n' .$solargain_lead_number );
                                // parse and replace bean variables
                                $emailBean                    = custom_replaceEmailVariables($emailBean, $temp_request);
                                
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
                                $emailBean->save();
                                
                                $lead->email_send_id_c     = $emailBean->id;
                                $lead->email_send_status_c = 'pending';
                                $lead->save();
                                
                                if ($temp_request["parent_type"] == "Leads") {
                                    $leadID = $temp_request["parent_id"];
                                }      
                            } 
                            $record_id = $lead->id;
                            custom_sendDesignRequestToAdmin($record_id);
                            echo "<br><a href='/index.php?module=Leads&action=EditView&record=$lead->id' target='_blank' >$lead->id</a>";
                        }
                    }else{
                        send_email_alert_lead_exist($result_array_info_email['lead_email'],$result_array_info_email['to']);
                    }
                }
            }else{
                $message   = getBody($email_number,$inbox);
                $structure = imap_fetchstructure($inbox,$email_number);
                $header    = imap_headerinfo($inbox, $email_number);
                $attachments = array();
                $file_attachments = getAttachments($inbox,$email_number,$structure);

                require_once('include/SugarPHPMailer.php');
                $overview  = imap_fetch_overview($inbox,$email_number,0);
                $subject = $overview[0]->subject;
                $from = $overview[0]->from;
                $fromEmail = $header->from[0]->personal." (".$header->from[0]->mailbox . "@" . $header->from[0]->host.")";

                preg_match('/<body[^>]*>.*?/',$message, $out);
                $first_div = "";
                if(isset($out[0])){
                    $first_div  = $out[0];
                }
                $add_to_body = '';
                foreach($header->to as $email_to){
                  $add_to_body .= $email_to->personal." (".$email_to->mailbox."@".$email_to->host."), ";
                }
                $add_cc_body = '';
                foreach($header->cc as $email_cc){
                  $add_cc_body .= $email_cc->personal." (".$email_cc->mailbox."@".$email_cc->host."), ";
                }
                $add_cc_body =  trim($add_cc_body,', ');
                $message  = preg_replace("/<body[^>]*>.*?/", $first_div.'<br/><strong>Sendder Address :</strong> '.$fromEmail.'<br/><br/><strong>To Address :</strong> '.$add_to_body.'<br/><br/><strong>Cc Address:</strong> '.$add_cc_body.'<br/><br/>', $message, 1);
  
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer();
                $mail->setMailerForSystem();
                $mail->From = "paul.szuster@solargain.com.au";
                $mail->FromName = 'Paul Szuster Solargain';
                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;;
                $mail->prepForOutbound();
                
                $mail->AddAddress('info@pure-electric.com.au');
                if(!empty($file_attachments)){
                    foreach($file_attachments as $file){
                        $mail->AddAttachment($file);
                    }
                }
                $mail->Send();
                foreach($file_attachments as $file){
                    unlink($file);
                }
            }
        }
    } 
} 

//Thienpb code
    function get_mime_type($structure)
    {
        $primaryMimetype = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];
        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false)
    {
        if (!$structure) {
            $structure = imap_fetchstructure($imap,$uid);
        }
        if ($structure) {
            if ($mimetype == get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber);
                switch ($structure->encoding) {
                    case 3:
                        return imap_base64($text);
                    case 4:
                        return imap_qprint($text);
                    default:
                        return $text;
                }
            }
            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    function getBody($uid, $imap)
    {
        $body = get_part($imap, $uid, "TEXT/HTML");
        if ($body == "") {
            $body = get_part($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }
    function getAttachments($inbox,$email_number,$structure){
        if(isset($structure->parts) && count($structure->parts)) {
          for($i = 0; $i < count($structure->parts); $i++) {
            $attachments[$i] = array(
              'is_attachment' => false,
              'filename' => '',
              'name' => '',
              'attachment' => ''
            );
            
            if($structure->parts[$i]->ifdparameters) {
              foreach($structure->parts[$i]->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['filename'] = $object->value;
                }
              }
            }
      
            if($structure->parts[$i]->ifparameters) {
              foreach($structure->parts[$i]->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['name'] = $object->value;
                }
              }
            }
            
            if($attachments[$i]['is_attachment']) {
              $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
              if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
              } elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
              }
            }
          } 
        } 
        
        $file_attachments = array();
        if(count($attachments) != 0){
          $path = "custom/include/SugarFields/Fields/Multiupload/server/php/files/attachments/";
          if(!file_exists ( $path )) {
            set_time_limit ( 0 );
            mkdir($path);
          }
          foreach($attachments as $at){
            if($at[is_attachment]==1){
              $fname = $at['filename'];
              $file_attachments[] = $path."$fname";
              $fp = fopen( $path."$fname","w");
              fwrite($fp, $at['attachment']);
              fclose($fp);
            }
          }
        }
      
        return $file_attachments;
      }
//end

function get_information_from_email ($file_content) {

    preg_match("/Subject:(.*?)\r\n/s" , $file_content, $match_body);
    $subject = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/To:(.*?)\r\n/s" , $file_content, $match_body);
    $to = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Lead #(\d+)/s" , $file_content, $match_body);
    $lead_number = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Status:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_status = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/System Interested In:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_System_Interested_In =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Next Action Date:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_next_action_date =  (isset($match_body[1])) ? $match_body[1] : ''; ;
    
    //get info custom details
    preg_match_all("/Customer Details(.*?)Site Details/s" , $file_content, $match_body);
    $content_custom_details = (isset($match_body[1])) ? $match_body[1][0] : ''; 
    
    preg_match("/\r\n(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_fullname =  (isset($match_body[1])) ? $match_body[1] : ''; 

    preg_match("/p:(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/m:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_mobile_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/e:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_email = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get info Site Details
    preg_match("/Site Details(.*?)Notes/s" , $file_content, $match_body);
    $lead_address=  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get note 
    preg_match("/Notes(.*?)This e-mail is private and confidential./s" , $file_content, $match_body);
    $lead_note=  (isset($match_body[1])) ? $match_body[1] : ''; 

    $result = array (
        'subject' => $subject,
        'to' => $to,
        'lead_fullname' => $lead_fullname,
        'lead_number' =>$lead_number ,
        'lead_status' => $lead_status,
        'lead_System_Interested_In' => $lead_System_Interested_In,
        'lead_next_action_date' => $lead_next_action_date,
        'lead_phone' =>$lead_phone,
        'lead_mobile_phone' => $lead_mobile_phone,
        'lead_email' => $lead_email,
        'lead_address' => $lead_address,
        'lead_note' => $lead_note
    );

    return $result;
}

function get_address($string_address){
    if($string_address != ''){
    
        //case 1: get address from string_address.com.au
        $address = rawurlencode(utf8_encode($string_address));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/autocomplete?q='.$address.'&key=MFQ93YPXGETJ7DKWLN48&format=json&max=7&wv=3.18.8&session=&highlight=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
        $headers[] = 'Accept: application/json, text/javascript';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Referer: https://addressfinder.com.au/';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Origin: https://addressfinder.com.au';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Te: Trailers';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);

        $address_arr =  json_decode($result);

        if($address_arr){
            $address_id = $address_arr->completions[0]->id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/info?format=json&key=MFQ93YPXGETJ7DKWLN48&wv=3.18.8&session=&paf=1&gps=1&id='.$address_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
            $headers[] = 'Accept: application/json, text/javascript';
            $headers[] = 'Accept-Language: en-US,en;q=0.5';
            $headers[] = 'Referer: https://addressfinder.com.au/';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Origin: https://addressfinder.com.au';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Pragma: no-cache';
            $headers[] = 'Cache-Control: no-cache';
            $headers[] = 'Te: Trailers';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close ($ch);

            $address_parse = json_decode($result);

            $address_elements = array(
                'full_address'  => (isset($address_parse->full_address)) ? $address_parse->full_address : '' ,
                'street'        => (isset($address_parse->address_line_1)) ? $address_parse->address_line_1.' '.$address_parse->address_line_2 : '',
                'city'          => (isset($address_parse->locality_name)) ? $address_parse->locality_name : '',
                'state'         => (isset($address_parse->state_territory)) ? $address_parse->state_territory : '',
                'postcode'      => (isset($address_parse->postcode)) ? $address_parse->postcode : '',
                'lat'           => (isset($address_parse->latitude)) ? $address_parse->latitude : '',
                'long'          => (isset($address_parse->longitude)) ? $address_parse->longitude : '',
            );
        }
        //case 2: get address by regex
        if(empty($address_elements['street']) && empty($address_elements['city']) && empty($address_elements['state']) && empty($address_elements['post_code'])){
            $address_pattern = '/(\n([a-zA-Z-\s])+( )|)+(VIC|SA|NSW|ACT|TAS|WA|QLD|NT)+( )+(\d{4})/s';
            $out_address     = array();
            preg_match_all($address_pattern, $string_address, $out_address, PREG_PATTERN_ORDER);
            $out_address[0][0] = trim($out_address[0][0]);
            if (isset($out_address[0][0]) && $out_address[0][0] != "") {
                $address_element = explode(" ", $out_address[0][0]);
                if (count($address_element) >= 3) {
                    $state            = $address_element[count($address_element) - 2];
                    $post_code        = $address_element[count($address_element) - 1];
                    $city           = str_replace($state . " " . $post_code, "", $out_address[0][0]);
                    $city           = str_replace("-", "", $city);
                    $exploded_address = explode("\n", $out_address[0][0]);
                    if (count($exploded_address) == 2)
                        $client_primary_address = $exploded_address[0];
                }
                
                if (count($address_element) == 2) {
                    $state     = $address_element[0];
                    $post_code = $address_element[1];
                    $city    = "";
                    $address1 = explode(',',$out_address);
                }
                $address_elements = array(
                    'full_address'  => (isset($out_address1)) ? $out_address : '' ,
                    'street'        => (isset($address_street)) ? $address_street : '',
                    'city'          => (isset($city)) ? $city : '',
                    'state'         => (isset($state)) ? $state : '',
                    'postcode'      => (isset($post_code)) ? $post_code : '',
                    'lat'           => '',
                    'long'          => '',
                );
            }
            
        }   
    }
    
    return $address_elements ;
}

function get_name($string_name){
    if($string_name != '') {
        $name_array = explode(",", trim($string_name));
        $last_name = trim($name_array[0]);
        $first_name = trim($name_array[1]);
        $full_name =  $last_name .' ' .$first_name; 
    }
    $result = array(
        'fullname' => (isset($full_name)) ? $full_name : '',
        'last_name' => (isset($last_name)) ? $last_name : '',
        'first_name' => (isset($first_name)) ? $first_name : '',
    );
    return $result;
}

function check_exist_lead($first_name,$last_name,$lead_email){
    $email = trim($lead_email);
    $db = DBManagerFactory::getInstance();
    $query = "SELECT leads.id as id ,email_addresses.email_address as email  FROM leads 
    JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
    JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE email_addresses.email_address = '$email'" ;
    $ret = $db->query($query);
    $is_exsit = false;
    if($ret->num_rows > 0){
        $is_exsit = true;
    }else{
        $is_exsit = false;
    }
    return $is_exsit;
}

function send_email_alert_lead_exist($customer_lead_email,$fromto){
    //check email is exists
    $lead_email = trim($customer_lead_email);
    if($lead_email != '') {
        if(strpos($fromto,'matthew') !== false){
            $address = "matthew.wright@pure-electric.com.au";
        }else if(strpos($fromto,'paul') !== false){
            $address = "paul.szuster@pure-electric.com.au";
        }else{
            $address = "matthew.wright@pure-electric.com.au";
        }
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
            //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
            $mail->AddAddress($address);
            $sent = $mail->Send();
            die();
        }  
    }

}


function custom_getLatLong($address)
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

function custom_sendDesignRequestToAdmin($record_id)
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
    $lat_long = custom_getLatLong($address);

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
        "to_addrs_names" => 'nguyenphudung93.dn@gmail.com',//"admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        // "cc_addrs_names" => "info@pure-electric.com.au",
        //  "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
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
    $emailBean->saved_attachments = custom_handleMultipleFileAttachments($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = custom_replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();

    custom_updateDesignForSolargainLead($record_id);
    $lead->email_send_design_request_id_c = $emailBean->id;
    $lead->email_send_design_status_c = 'pending';
    $lead->save();

    return $emailBean;
}

function custom_handleMultipleFileAttachments($request, $email)
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
            $noteTemplate->new_with_id  = true; 
            $noteTemplate->parent_id    = $email->id;
            $noteTemplate->parent_type  = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();
            
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
    }
}

function custom_replaceEmailVariables(Email $email, $request)
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

function custom_updateDesignForSolargainLead($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    
    $solargainLead = $lead->solargain_lead_number_c;
    
    $username = "paul.szuster@solargain.com.au";
    $password = "Baited@42";
    
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