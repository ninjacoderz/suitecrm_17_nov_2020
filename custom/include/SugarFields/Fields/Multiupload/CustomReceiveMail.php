<?php 

function updateSolargainLeadReceiveEmail($leadID, $email, $in_or_out){
    $lead = new Lead();
    $lead->retrieve($leadID);
    if(!$lead->solargain_lead_number_c) {
        return;
    }
    $solargainLead = $lead->solargain_lead_number_c;
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 
    
    $username = "matthew.wright";
    $password =  "MW@pure733";
    
    // Get full json response for Leads

    $url = "https://crm.solargain.com.au/APIv2/leads/". $solargainLead;
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
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
            "Cache-Control: max-age=0"
        )
    );
    
    $leadJSON = curl_exec($curl);
    curl_close ( $curl );

    $leadSolarGain = json_decode($leadJSON);

    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "";
    if(isset($email->from_name) && $email->from_name != ""){
        $note = "From: ".$email->from_name  ." ";//."<".$email->from_addr.">"; // at ".date('d-n-Y H:i')." ".date_default_timezone_get()."\n";
        $array_check_explode = ['Regards','regards','Thanks in advance'];
        $string_explode = $email->description_html;
        foreach($array_check_explode as $value){
            $description_array = explode($value, $string_explode);
            $string_explode = reset($description_array);
        }
        $description_html = $string_explode;
        //$description_array = explode("Regards", $email->description_html);
        //$description_html = reset($description_array);
        $description_html = html_entity_decode($description_html);
        $description_html = preg_replace('/CRM Links[\s\S]+?End CRM Links/', '', $description_html);
        $note .= substr(preg_replace('/\s/', ' ', trim(strip_tags($description_html))), 0, 500);
    }
    /*else {
        $note = $current_user->full_name. " : ".$request["emails_email_templates_name"];
    }*/
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>$in_or_out?4:5,
            "Name"=>$in_or_out?"E-Mail In":"E-Mail Out",
            "RequiresComment"=> true
        ),
        
        "Text"=> trim($note),
    );
    $leadSolarGain->NextActionDate = array(
        "Date" => date('d/m/Y', time() + 3*24*60*60),
        "Time"=>"9:00 AM"
    );
    /*
    "NextActionDate" => array (
        "Date" => date('d/m/Y', time() + 24*60*60),
        "Time"=>"9:00 AM"
    ),*/

    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl = curl_init();
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
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($leadSolarGainJSONDecode),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
        )
    );
    
    $lead = json_decode(curl_exec($curl));
    curl_close ( $curl );
}

function updateSolargainQuoteReceiveEmail($leadID, $email, $in_or_out){
    $lead = new Lead();
    $lead->retrieve($leadID);
    if(!$lead->solargain_quote_number_c) {
        return;
    }
    $solargainQuote = $lead->solargain_quote_number_c;
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    
    $username = "matthew.wright";
    $password = "MW@pure733";
    
    // Get full json response for Leads

    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$solargainQuote;
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
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$solargainQuote,
            "Cache-Control: max-age=0"
        )
    );

    $quote = curl_exec($curl);
    
    
    /// Resubmit Quotes with options
    $quote_decode = json_decode($quote);
    $note = "";
    if(isset($email->from_name) && $email->from_name != ""){
        $note = "From: ".$email->from_name ." ";//."<".$email->from_addr."> at ".date('d-n-Y H:i')." ".date_default_timezone_get()."\n";
        $array_check_explode = ['Regards','regards','Thanks in advance'];
        $string_explode = $email->description_html;
        foreach($array_check_explode as $value){
            $description_array = explode($value, $string_explode);
            $string_explode = reset($description_array);           
        }
        $description_html = $string_explode;
        //$description_array = explode("Regards", $email->description_html);
        //$description_html = reset($description_array);
        $description_html = html_entity_decode($description_html);
        $description_html = preg_replace('/CRM Links[\s\S]+?End CRM Links/', '', $description_html);
        $note .= substr(preg_replace('/\s/', ' ', trim(strip_tags($description_html))), 0, 500);

        //$note .= substr( preg_replace('/\s/', ' ', trim(strip_tags($email->description_html))), 0, 500);
    }
    //$quote_decode ->SpecialNotes = $quote_decode ->SpecialNotes . $note;
    $quote_decode->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>$in_or_out?4:5,
            "Name"=>$in_or_out?"E-Mail In":"E-Mail Out",
            "RequiresComment"=> true
        ),
        
        "Text"=> trim($note),
    );
    $quote_decode->NextActionDate = array(
        "Date" => date('d/m/Y', time() + 3*24*60*60),
        "Time"=>"9:00 AM"
    );

    $quote_encode =  json_encode( $quote_decode);
    
    
    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";
    
    curl_setopt($curl, CURLOPT_URL, $url);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote_encode);
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($quote_encode),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$solargainQuote,
        )
    );
    $result = curl_exec($curl);
}

function getmsg($mbox,$mid) {
    // input $mbox = IMAP stream, $mid = message id
    // output all the following:
    global $charset,$htmlmsg,$plainmsg,$attachments;
    $htmlmsg = $plainmsg = $charset = '';
    $attachments = array();

    // HEADER
    $h = imap_header($mbox,$mid);
    // add code here to get date, from, to, cc, subject...

    // BODY
    $s = imap_fetchstructure($mbox,$mid);
    if (!$s->parts)  // simple
        getpart($mbox,$mid,$s,0);  // pass 0 as part-number
    else {  // multipart: cycle through each part
        foreach ($s->parts as $partno0=>$p)
            getpart($mbox,$mid,$p,$partno0+1);
    }
}

function getpart($mbox,$mid,$p,$partno) {
    // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
    global $htmlmsg,$plainmsg,$charset,$attachments;

    // DECODE DATA
    $data = ($partno)?
        imap_fetchbody($mbox,$mid,$partno):  // multipart
        imap_body($mbox,$mid);  // simple
    // Any part may be encoded, even plain text messages, so check everything.
    if ($p->encoding==4)
        $data = quoted_printable_decode($data);
    elseif ($p->encoding==3)
        $data = base64_decode($data);

    // PARAMETERS
    // get all parameters, like charset, filenames of attachments, etc.
    $params = array();
    if ($p->parameters)
        foreach ($p->parameters as $x)
            $params[strtolower($x->attribute)] = $x->value;
    if ($p->dparameters)
        foreach ($p->dparameters as $x)
            $params[strtolower($x->attribute)] = $x->value;

    // ATTACHMENT
    // Any part with a filename is an attachment,
    // so an attached text file (type 0) is not mistaken as the message.
    if ($params['filename'] || $params['name']) {
        // filename may be given as 'Filename' or 'Name' or both
        $filename = ($params['filename'])? $params['filename'] : $params['name'];
        // filename may be encoded, so see imap_mime_header_decode()
        $attachments[$filename] = $data;  // this is a problem if two files have same name
    }

    // TEXT
    if ($p->type==0 && $data) {
        // Messages may be split in different parts because of inline attachments,
        // so append parts together with blank row.
      if (strtolower($p->subtype)=='plain')
           $plainmsg = $plainmsg . trim($data) . "\n\n";
       else
           $htmlmsg = $htmlmsg . $data . "<br><br>";
      $charset = $params['charset'];  // assume all parts are same charset
    }

    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
    elseif ($p->type==2 && $data) {
        $plainmsg = $plainmsg . $data . "\n\n";
    }

    // SUBPART RECURSION
    if ($p->parts) {
        foreach ($p->parts as $partno0=>$p2)
            getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
    }
}
function writeTestFile($text){
    global $sugar_config;
    $destination = "/var/www/suitecrm/upload/"."test.txt";
    //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
    $fp = fopen($destination, "a+");
    fwrite($fp, $text);
    fclose($fp);
}
function customReceiveMail()
{
    require_once 'modules/InboundEmail/AOPInboundEmail.php';

    global $dictionary;
    global $app_strings;
    global $sugar_config;

    require_once('modules/Configurator/Configurator.php');
    $aopInboundEmail = new AOPInboundEmail();
    $sqlQueryResult = $aopInboundEmail->db->query(
        'SELECT id, name FROM inbound_email WHERE email_user = \'pure.electric.com.au@gmail.com\' LIMIT 1'
    );

    while ($inboundEmailRow = $aopInboundEmail->db->fetchByAssoc($sqlQueryResult)) {

        $aopInboundEmailX = new AOPInboundEmail();
        $aopInboundEmailX->retrieve($inboundEmailRow['id']);
        $mailboxes = $aopInboundEmailX->mailboxarray;
        foreach ($mailboxes as $mbox) {

            $aopInboundEmailX->mailbox = $mbox;
            $newMsgs = array();
            $msgNoToUIDL = array();
            $connectToMailServer = false;
           
            if ($aopInboundEmailX->connectMailserver() == 'true') {
                $connectToMailServer = true;
            } 

            if ($connectToMailServer) {
                $newMsgs = $aopInboundEmailX->getNewMessageIds();
                $newMsgs = array_slice($newMsgs, 0 , 500);
                if (is_array($newMsgs)) {
                    $current = 1;
                    $total = count($newMsgs);
                    require_once("include/SugarFolders/SugarFolders.php");
                    $sugarFolder = new SugarFolder();
                    $groupFolderId = $aopInboundEmailX->groupfolder_id;
                    $isGroupFolderExists = false;
                    $users = array();
                    if ($groupFolderId != null && $groupFolderId != "") {
                        $sugarFolder->retrieve($groupFolderId);
                        $isGroupFolderExists = true;
                    } // if
                   
                    foreach ($newMsgs as $k => $msgNo) {
                        $uid = $msgNo;
                        $uid = imap_uid($aopInboundEmailX->conn, $msgNo);

                        global $charset,$htmlmsg,$plainmsg,$attachments;
                        
                        getmsg($aopInboundEmailX->conn, $msgNo, $custom_email);

                        //$aopInboundEmailX->getMessagesInEmailCache($msgNo, $uid);
                        $email = new Email();
                        $header = imap_headerinfo($aopInboundEmailX->conn, $msgNo);
                        $email->name = $aopInboundEmailX->handleMimeHeaderDecode($header->subject);

                        //$email->parent_name = "HERSBACH RICHMOND VICTORIA OTHER";
                        // $bean->mailbox_id = $_REQUEST['inbound_email_id'];
                        $email->from_addr = $aopInboundEmailX->convertImapToSugarEmailAddress($header->from);
                        $email->from_name = $header->from[0]->personal;
                        $email->to_addrs = $aopInboundEmailX->convertImapToSugarEmailAddress($header->to);
                        // Logic for email name here
                        if ($email->from_addr == "") {
                            return;
                        }
                        
                        $email->reply_to_email = $aopInboundEmailX->convertImapToSugarEmailAddress($header->reply_to);
                        $email->description = $plainmsg;
                        $email->description_html = $htmlmsg;

                        $db = DBManagerFactory::getInstance();

                        if ( $email->from_addr == "binh.nguyen@pure-electric.com.au" || 
                        $email->from_addr == "paul.szuster@pure-electric.com.au" || 
                        $email->from_addr == "paul@pure-electric.com.au" || 
                        $email->from_addr == "matthew.wright@pure-electric.com.au" || 
                        $email->from_addr == "matthew@pure-electric.com.au" ||
                        $email->from_addr == "lee.andrewartha@pure-electric.com.au" ||
                        $email->from_addr == "ross@pure-electric.com.au" ||
                        $email->from_addr == "james@pure-electric.com.au"||
                        $email->from_addr == "ross.munro@pure-electric.com.au"||
                        $email->from_addr == "accounts@pure-electric.com.au" ||
                        $email->from_addr == "operations@pure-electric.com.au"
                        ) {
                            $lead_email = $email->to_addrs;
                        } else {
                            $lead_email = $email->from_addr;
                        }

                        $sql = "SELECT * FROM email_addresses ea 
                                LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                WHERE 1=1 AND LOWER(ea.email_address) = LOWER('$lead_email') AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module != 'Users'
                                ";
                                
                             writeTestFile("log1:".$sql);
                        $ret = $db->query($sql);

                        while ($row = $db->fetchByAssoc($ret)) {
                            if($row["bean_id"] != ""){
                                
                                $email->parent_id = $row["bean_id"];
                                $email->parent_type = $row["bean_module"];
                                
                                $email->status = 'received';
                                if ( $email->from_addr == "binh.nguyen@pure-electric.com.au" || 
                                $email->from_addr == "paul.szuster@pure-electric.com.au" || 
                                $email->from_addr == "paul@pure-electric.com.au" || 
                                $email->from_addr == "matthew.wright@pure-electric.com.au" || 
                                $email->from_addr == "matthew@pure-electric.com.au" ||
                                $email->from_addr == "lee.andrewartha@pure-electric.com.au" ||
                                $email->from_addr == "ross@pure-electric.com.au" ||
                                $email->from_addr == "james@pure-electric.com.au"||
                                $email->from_addr == "ross.munro@pure-electric.com.au"||
                                $email->from_addr == "accounts@pure-electric.com.au" ||
                                $email->from_addr == "operations@pure-electric.com.au"
                                ) {
                                    $email->status = 'sent';
                                }

                                // If bean_module == lead Update to solargain here
                                if($row["bean_module"] == "Leads"){
                                    updateSolargainLeadReceiveEmail($row["bean_id"], $email, (($email->status == 'sent')?0:1));
                                    updateSolargainQuoteReceiveEmail($row["bean_id"], $email, (($email->status == 'sent')?0:1));
                                }
                                $email->save();
                                // Create a new attachments
                                print_r($attachments);
                                if(count($attachments) > 0) {
                                    foreach($attachments as $filename=>$data) {

                                        $db = DBManagerFactory::getInstance();

                                        $sql = "SELECT COUNT(*) as total FROM notes nt 
                                        WHERE 1=1 AND nt.parent_id = '".$email->id."' 
                                        AND nt.parent_type = 'Emails' 
                                        AND nt.filename = '".$filename."'
                                        AND nt.deleted = 0
                                        ";
                                        $result = $db->query($sql);
                                        $row = $db->fetchByAssoc($result);
                                        $total_no_of_file = (int)$row['total'];
                                        if($total_no_of_file > 0 ) continue;

                                        // We solve the case that we reply 
                                        
                                        // Create Note
                                        $noteTemplate = new Note();
                                        //$noteTemplate->retrieve($noteId->id);
                                        $noteTemplate->id = create_guid();
                                        $noteTemplate->new_with_id = true; // duplicating the note with files
                                        $noteTemplate->parent_id = $email->id;
                                        $noteTemplate->parent_type = $email->module_dir;
                                        $noteTemplate->date_entered = '';
                                        $noteTemplate->filename = $filename;
                                        
                                        $noteTemplate->save();
                                        // if there are files exist create symlink
                                        $sql = "
                                            SELECT  n.id, e.name, n.filename, e.parent_id FROM `notes` n 
                                            LEFT JOIN emails e ON n.parent_id = e.id
                                            
                                            
                                            WHERE e.name IS NOT NULL 
                                            AND n.filename = '".$filename."'
                                            AND (e.name = '".$email->name."' OR CONCAT('Re: ',e.name)  = '".$email->name."') 
                                            AND n.deleted = 0
                                            ORDER BY n.date_entered ASC
                                            "
                                            //LEFT JOIN emails_email_addr_rel ear ON ear.email_id = e.id
                                            //LEFT JOIN email_addresses ea ON ea.id = ear.email_address_id
                                            //AND (ea.email_address ='".$email->from_addr."' OR ea.email_address ='".$email->to_addrs."')
                                        ;
                                        $ret = $db->query($sql);
                                        writeTestFile("numberRow1:".$ret->num_rows.$sql);
                                        if($ret->num_rows > 1){
                                            $symlink_success = false;
                                            while($row = $db->fetchByAssoc($ret)){
                                                if( is_link ( "/var/www/suitecrm/upload/".$row['id'] )){
                                                    echo "$symlink_success = true; dont do anything".$row['id']."<br/>";;
                                                    continue;
                                                } else if( file_exists("/var/www/suitecrm/upload/".$row['id'] ) && ($row['id'] != $noteTemplate->id)) {
                                                    echo "file_exists: ".file_exists( "/var/www/suitecrm/upload/".$row['id'] );
                                                    $destination = "/var/www/suitecrm/upload/".$noteTemplate->id;
                                                    $fp = fopen($destination, "w+");
                                                    fwrite($fp, $data);
                                                    fclose($fp);

                                                    if(filesize($destination) == filesize("/var/www/suitecrm/upload/".$row['id'])){
                                                        unlink($destination);
                                                        symlink("/var/www/suitecrm/upload/".$row['id'] ,$destination);
                                                        echo "symlink "."/var/www/suitecrm/upload/".$row['id']. " === ". $destination ."<br/>";;
                                                        $symlink_success = true;
                                                        break;
                                                    }

                                                    
                                                }
                                            }
                                            if(!$symlink_success){
                                                // solve copy physic 
                                                $destination = "/var/www/suitecrm/upload/".$noteTemplate->id;
                                                //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                                                $fp = fopen($destination, "w+");
                                                fwrite($fp, $data);
                                                fclose($fp);
                                                echo "create file here .". $destination ."<br/>";
                                            }
                                        }
                                        else {
                                            // solve copy physic 
                                            $destination = "/var/www/suitecrm/upload/".$noteTemplate->id;
                                            //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                                            $fp = fopen($destination, "w+");
                                            fwrite($fp, $data);
                                            fclose($fp);
                                        }
                                        echo "result for here ".$symlink_success;
                                        $noteArray[] = $noteTemplate;
                                    }
                                    //$email->saved_attachments = array_merge($email->saved_attachments, $noteArray);
                                }
                                
                            }
                        } 
                        auto_create_internal_note_when_receive_email($lead_email,$email);
            
                        $sql = "SELECT * FROM accounts_opportunities ao 
                            INNER JOIN accounts acc ON acc.id = ao.account_id 
                            INNER JOIN email_addr_bean_rel eabr ON acc.id = eabr.bean_id 
                            INNER JOIN email_addresses ea ON ea.id = eabr.email_address_id 
                            WHERE 1=1 AND  LOWER(ea.email_address) =  LOWER('$lead_email') AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module != 'Users'
                        ";
                        writeTestFile("log2:".$sql);
                        $ret = $db->query($sql);
                        while ($row = $db->fetchByAssoc($ret)) {
                            if($row["opportunity_id"] != ""){

                                $email->parent_id = $row["opportunity_id"];
                                $email->parent_type = "Opportunities";
                                $email->status = 'received';
                                if ( $email->from_addr == "binh.nguyen@pure-electric.com.au" || 
                                $email->from_addr == "paul.szuster@pure-electric.com.au" || 
                                $email->from_addr == "paul@pure-electric.com.au" || 
                                $email->from_addr == "matthew.wright@pure-electric.com.au" || 
                                $email->from_addr == "matthew@pure-electric.com.au"||
                                $email->from_addr == "lee.andrewartha@pure-electric.com.au" ||
                                $email->from_addr == "ross@pure-electric.com.au" ||
                                $email->from_addr == "james@pure-electric.com.au"||
                                $email->from_addr == "ross.munro@pure-electric.com.au"||
                                $email->from_addr == "accounts@pure-electric.com.au" ||
                                $email->from_addr == "operations@pure-electric.com.au"
                                ) {
                                    $email->status = 'sent';
                                    // Update to solargain here
                                }
                                $email->save();
                                if(false && count($attachments) > 0) { // không suwr lys file ở đây nữa
                                    foreach($attachments as $filename=>$data) {

                                        $db = DBManagerFactory::getInstance();

                                        $sql = "SELECT COUNT(*) as total FROM notes nt 
                                        WHERE 1=1 AND nt.parent_id = '".$email->id."' 
                                        AND nt.parent_type = 'Emails' 
                                        AND nt.filename = '".$filename."'
                                        AND nt.deleted = 0
                                        ";
                                        $result = $db->query($sql);
                                        $row = $db->fetchByAssoc($result);
                                        $total_no_of_file = $row['total'];
                                        if($total_no_of_file > 0 ) continue;

                                        // Create Note
                                        $noteTemplate = new Note();
                                        //$noteTemplate->retrieve($noteId->id);
                                        $noteTemplate->id = create_guid();
                                        $noteTemplate->new_with_id = true; // duplicating the note with files
                                        $noteTemplate->parent_id = $email->id;
                                        $noteTemplate->parent_type = $email->module_dir;
                                        $noteTemplate->date_entered = '';
                                        $noteTemplate->filename = $filename;
                                        
                                        $noteTemplate->save();
                                        /*$destination = $sugar_config['upload_dir'].$noteTemplate->id;
                                        //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                                        $fp = fopen($destination, "w+");
                                        fwrite($fp, $data);
                                        fclose($fp);
                                        */

                                        $sql = "
                                        SELECT  n.id, e.name, n.filename, e.parent_id FROM `notes` n 
                                        LEFT JOIN emails e ON n.parent_id = e.id
                                        
                                        
                                        WHERE e.name IS NOT NULL 
                                        AND n.filename = '".$filename."'
                                        AND (e.name = '".$email->name."' OR CONCAT('Re: ',e.name)  = '".$email->name."') 
                                        AND n.deleted = 0
                                        ORDER BY n.date_entered ASC
                                        "
                                        //LEFT JOIN emails_email_addr_rel ear ON ear.email_id = e.id
                                        //LEFT JOIN email_addresses ea ON ea.id = ear.email_address_id
                                        //AND (ea.email_address ='".$email->from_addr."' OR ea.email_address ='".$email->to_addrs."')
                                        ;
                                        $ret = $db->query($sql);
                                        writeTestFile("numberRow2:".$ret->num_rows);
                                        if($ret->num_rows > 1){
                                            $symlink_success = false;
                                            while($row = $db->fetchByAssoc($ret)){
                                                if( is_link ( $sugar_config['upload_dir'].$row['id'] )){
                                                    $symlink_success = true;
                                                    continue;
                                                } else if(file_exists($sugar_config['upload_dir'].$row['id'] )) {

                                                    $destination = '/var/www/suitecrm/upload/'.$noteTemplate->id;
                                                    $fp = fopen($destination, "w+");
                                                    fwrite($fp, $data);
                                                    fclose($fp);

                                                    if(filesize($destination) == filesize($sugar_config['upload_dir'].$row['id'])){
                                                        unlink($destination);
                                                        symlink("/var/www/suitecrm/upload/".$row['id'] ,$destination);
                                                        echo "symlink ".$sugar_config['upload_dir'].$row['id']. " === ". $destination;
                                                        $symlink_success = true;
                                                        break;
                                                    }
                                                    
                                                }
                                            }
                                            if(!$symlink_success){
                                                // solve copy physic 
                                                $destination = $sugar_config['upload_dir'].$noteTemplate->id;
                                                //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                                                $fp = fopen($destination, "w+");
                                                fwrite($fp, $data);
                                                fclose($fp);
                                            }
                                        }
                                        else {
                                            // solve copy physic 
                                            $destination = "/var/www/suitecrm/upload/".$noteTemplate->id;
                                            //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                                            $fp = fopen($destination, "w+");
                                            fwrite($fp, $data);
                                            fclose($fp);
                                        }

                                        $noteArray[] = $noteTemplate;
                                    }
                                    //$email->saved_attachments = array_merge($email->saved_attachments, $noteArray);
                                }
                            }
                        }

                        $current++;
                    } 

                } 
            } else {
                $GLOBALS['log']->fatal("SCHEDULERS: could not get an IMAP connection resource for ID [ {$inboundEmailRow['id']} ]. Skipping mailbox [ {$inboundEmailRow['name']} ].");
                // cn: bug 9171 - continue while
            } // else
        } // foreach
        imap_expunge($aopInboundEmailX->conn);
        imap_close($aopInboundEmailX->conn, CL_EXPUNGE);
    } // while
    return true;
}

function auto_create_internal_note_when_receive_email($address_email_customer,$beanEmail){
    if($beanEmail->id != '' && $address_email_customer != '' && $beanEmail->status == 'received') {
        $db = DBManagerFactory::getInstance();

        //get id contact , id account by email address 
        $sql = "SELECT contacts.id as contacts_id , accounts.id as accounts_id FROM email_addr_bean_rel 
        LEFT JOIN contacts ON email_addr_bean_rel.bean_id = contacts.id
        LEFT JOIN accounts ON email_addr_bean_rel.bean_id = accounts.id
        LEFT JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id
        WHERE email_addresses.email_address  LIKE '%$address_email_customer%' AND (email_addr_bean_rel.bean_module ='Contacts' OR email_addr_bean_rel.bean_module ='Accounts'  )";
        $contacts_id = ''; $accounts_id = '';
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            if($row["contacts_id"] != ""){
                $contacts_id = $row["contacts_id"];
            }
            if($row["accounts_id"] != ""){
                $accounts_id = $row["accounts_id"];
            }
        }
        
        $sql = "SELECT aos_invoices.id as aos_invoices_id ,  aos_quotes.id as aos_quotes_id, leads.id as leads_id FROM accounts_contacts
        LEFT JOIN aos_invoices ON aos_invoices.billing_account_id = accounts_contacts.account_id
        LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = accounts_contacts.account_id
        LEFT JOIN leads ON leads.account_id = accounts_contacts.account_id
        WHERE  ( accounts_contacts.contact_id  = '$contacts_id' OR accounts_contacts.account_id = '$accounts_id')";
        //get id invoice and id quote by id account or contact
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            if($row["aos_invoices_id"] != ""){
                $invoice_id[] = $row["aos_invoices_id"];
            }
            if($row["aos_quotes_id"] != ""){
                $quote_id[] = $row["aos_quotes_id"];
            }
            if($row["leads_id"] != ""){
                $lead_id[] = $row["leads_id"];
            }
        }
    
        $bean_intenal_notes = new  pe_internal_note();
        $bean_intenal_notes->type_inter_note_c = 'email_in';
        $bean_intenal_notes->description =  $beanEmail->name;
        $bean_intenal_notes->email_id_c =  $beanEmail->id;
        $bean_intenal_notes->save();
        
    
        if(count($invoice_id) > 0 || count($quote_id) > 0 || count($lead_id) > 0 ) {  
            //delete value same like
            $quote_id = array_unique($quote_id, SORT_REGULAR);
            $invoice_id = array_unique($invoice_id, SORT_REGULAR);
            $lead_id = array_unique($lead_id, SORT_REGULAR);
    
            foreach ($invoice_id as $key => $value) {
                if($value != ''){
                    $bean_intenal_notes->load_relationship('aos_invoices_pe_internal_note_1');
                    $bean_intenal_notes->aos_invoices_pe_internal_note_1->add($value);
                }
            }

            foreach ($lead_id as $key => $value) {
                if($value != ''){
                    $bean_intenal_notes->load_relationship('leads_pe_internal_note_1');
                    $bean_intenal_notes->leads_pe_internal_note_1->add($value);
                }
            }
    
            foreach ($quote_id as $key => $value) {
                if($value != ''){
                    $bean_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                    $bean_intenal_notes->aos_quotes_pe_internal_note_1->add($value);
                }
            }

        }     
    }
}

customReceiveMail();
die();