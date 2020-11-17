<?php

    require_once 'modules/InboundEmail/AOPInboundEmail.php';

    global $charset,$htmlmsg,$plainmsg,$attachments,$current_user;
    $has_file = false;

    require_once('modules/Configurator/Configurator.php');    
    require_once(dirname(__FILE__).'/simple_html_dom.php');

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    
    //get all list block file from  json file     
    $path_ListBlockFile = '';
    $path_ListBlockFile = dirname(__FILE__) .'/server/php/files/ListBlockFile.json';       
    $file_arr = json_decode(file_get_contents($path_ListBlockFile), true);
    //imap get all attachment
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

    //imap search email by email address and call function get all attachment
    function getEmailQuery($conn,$email_address){
        $emails_from = imap_search($conn,'FROM "'.$email_address.'"');
        $emails_to = imap_search($conn,'TO "'.$email_address.'"');

        if($emails_from !== false && $emails_to !== false){
            $email = array_merge($emails_from,$emails_to);
        }elseif($emails_from === false && $emails_to !== false){
            $email = $emails_to;
        }elseif($emails_to === false &&  $emails_from !== false){
            $email = $emails_from;
        }else{
            $email = array();
        }

        foreach($email as $email_number) {
            
            $overview = imap_fetch_overview($conn,$email_number,0);
            foreach($overview as $result){
                $s = imap_fetchstructure($conn, $email_number);
                if (!$s->parts)  // simple
                    getpart($conn,$mid,$s,0);  // pass 0 as part-number
                else {  // multipart: cycle through each part
                    foreach ($s->parts as $partno0=>$p)
                        getpart($conn,$email_number,$p,$partno0+1);
                }
            }
            
        }
    }

    // Query to get notes file
    $account_id = $_REQUEST['billing_account_id'];
    $opportunity_id = $_REQUEST['opportunity_id'];
    $contact_id = $_REQUEST['billing_contact_id'];
    $pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
    $lead_id = "";
    $db = DBManagerFactory::getInstance();

    //dung code - Button Get All Files In Leads
        //logic for alert button Solar Design Detail Lead 
        if(!isset($_REQUEST['pre_install_photos_c']) && ($_REQUEST['action'] == 'detailLeadSolarDesignComplete')) {
            $bean = new Lead();
            $bean->retrieve($_REQUEST['lead_id']);
            $_REQUEST['pre_install_photos_c'] = $bean->installation_pictures_c;
            $pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
        }
    if($_REQUEST['module'] == 'Leads' && $_REQUEST['lead_id'] !== '' && $_REQUEST['pre_install_photos_c'] !== '' ) {
       
        $lead_id = $_REQUEST['lead_id'];
        $sql = "SELECT nt.id as note_id, nt.filename as file_name FROM notes nt 
                LEFT JOIN emails_beans eb ON eb.email_id = nt.parent_id 
                WHERE 1=1 AND nt.deleted = 0 AND nt.parent_type = 'Emails' AND 
                (
                    (eb.bean_id = '$lead_id' AND eb.bean_module = 'Leads')
                ) GROUP BY nt.filename
                ";
                
        $ret = $db->query($sql);
        $note_id_array = array();
        while ($row = $db->fetchByAssoc($ret)) {
            if($row["note_id"] != ""){
                $a_note_id_array["note_id"] = $row["note_id"];
            }
            if($row["file_name"] != ""){
                $a_note_id_array["file_name"] = $row["file_name"];
            }
            $note_id_array[] = $a_note_id_array;
        }
        $current_file_path =  dirname(__FILE__) . '/server/php/files/'.$pre_install_photos_c;

    } else {
        // dung code - copy logic task of Thien
        if($opportunity_id != ''){
            //Return if account_id & opportunity_id & contact_id empty
            if(empty($account_id) && empty($opportunity_id) && empty($contact_id)) return;
            // Get the lead id
            $sql = "SELECT * FROM leads 
            WHERE 1=1 AND (opportunity_id = '$opportunity_id' OR account_id = '$account_id' OR contact_id = '$contact_id' )
            ";
    
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["id"] != ""){
                    $lead_id = $row["id"];
                }
            }
        }
        
        //check billing_account = Solargain PV account
        if($account_id == '61db330d-0aee-6661-8ac3-585c79c765a2'){
            $account_id = '';
        }
        
        $sql = "SELECT nt.id as note_id, nt.filename as file_name FROM notes nt 
                LEFT JOIN emails_beans eb ON eb.email_id = nt.parent_id 
                WHERE 1=1 AND nt.deleted = 0 AND nt.parent_type = 'Emails' AND 
                (
                    (eb.bean_id = '$account_id' AND eb.bean_module = 'Accounts') OR
                    (eb.bean_id = '$contact_id' AND eb.bean_module = 'Contacts') OR
                    (eb.bean_id = '$lead_id' AND eb.bean_module = 'Leads')
                ) GROUP BY nt.filename
                ";
                
        $ret = $db->query($sql);
        $note_id_array = array();
        while ($row = $db->fetchByAssoc($ret)) {
            if($row["note_id"] != ""){
                $a_note_id_array["note_id"] = $row["note_id"];
            }
            if($row["file_name"] != ""){
                if( strpos( $row["file_name"], 'VBA') ){
                    $new_name = str_replace("VBA","PCOC", $row["file_name"]);
                    $a_note_id_array["file_name"] = $new_name;
                }else {
                    $a_note_id_array["file_name"] = $row["file_name"];
                }
            }
            $note_id_array[] = $a_note_id_array;
        }
        $current_file_path =  dirname(__FILE__) . '/server/php/files/'.$pre_install_photos_c;
    }

    if(!file_exists ( $current_file_path )) {
        set_time_limit ( 0 );
        mkdir($current_file_path);
    }
    
    foreach ($note_id_array as $note) {
        $source = realpath(dirname(__FILE__) . '/../../../../../').'/upload/'.$note['note_id'];
        $destination = $current_file_path."/".$note['file_name'];
        // dung code --  update check filename
        $file_arr = array_map('strtolower', $file_arr);      
        if(in_array(strtolower($note['file_name']),$file_arr)){
            $fp = fopen($destination, "w+");
            fwrite($fp, '');
            fclose($fp);
        }else{
            copy( $source, $destination);
        }
        //end
        
        //thien fix show thumb
        if(is_file($source)){
                $type = strtolower(substr(strrchr($note['file_name'], '.'), 1));
                $typeok = TRUE;
                if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                    if(!file_exists ($current_file_path."/thumbnail/")) {
                        mkdir($current_file_path."/thumbnail/");
                    }
                    $thumb =  $current_file_path."/thumbnail/".$note['file_name'];
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
                        list($w, $h) = getimagesize($destination);

                        $src = $src_func($destination);
                        $new_img = imagecreatetruecolor(80,80);
                        imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                        $write_func($new_img,$thumb, $image_quality);
                        
                        imagedestroy($new_img);
                        imagedestroy($src);
                    }
                }
            }
    }

    //get all file from email 
    //get inbound id with email_user is pure.electric.com.au@gmail.com
    $aopInboundEmail = new AOPInboundEmail();
    $sqlQueryResult = $aopInboundEmail->db->query(
        'SELECT id FROM inbound_email WHERE email_user = \'pure.electric.com.au@gmail.com\' LIMIT 1'
    );
    
    $inboundEmailRow = $aopInboundEmail->db->fetchByAssoc($sqlQueryResult);

    $aopInboundEmailX = new AOPInboundEmail();
    $aopInboundEmailX->retrieve($inboundEmailRow['id']);
    
    if($aopInboundEmailX->id == ''){
        return;
    }

    //check inbox of email user logined
    $server_url = $aopInboundEmailX->server_url;
    $email_user = $aopInboundEmailX->email_user;
    $email_password = $aopInboundEmailX->email_password;
    $port = $aopInboundEmailX->port;

    // $server_url = 'imap.gmail.com';
    // $email_user = 'thienpb89@gmail.com';
    // $email_password = 'thienpham07';
    // $port = '993';

    $imap_con = "{".$server_url.":".$port."/imap/ssl/novalidate-cert}[Gmail]/All Mail";
    $conn = imap_open($imap_con,$email_user,$email_password); 

    //get email
    //$account_id ="90fb2302-3447-6746-05e0-5b9f283d7a1a";
    //$contact_id = "90f10897-8c9c-a494-e2d4-5b9f28c0b4ae";

    if($account_id == '61db330d-0aee-6661-8ac3-585c79c765a2'){
        $account_id = '';
    }
    if($lead_id !=''){
        $lead = new Lead();
        $lead = $lead->retrieve($lead_id);
        $lead_email1 = $lead->email1;
        $lead_email2 = $lead->email2;
        getEmailQuery($conn,$lead_email1);
        getEmailQuery($conn,$lead_email2);
    }else{
        if($account_id != ''){
            $account = new Account();
            $account = $account->retrieve($account_id);
            $account_email = $account->email1;
            getEmailQuery($conn,$account_email);
        }
        if($contact_id != ''){
            $contact = new Contact();
            $contact = $contact->retrieve($contact_id);
            $contact_email = $contact->email1;
            getEmailQuery($conn,$contact_email);
        }
    }
    
    
    if(count($attachments) > 0) {
        foreach($attachments as $filename=>$data) {
            $filename = $filename;
            $folder = $current_file_path;

            // Thienpb update check filename
            $file_arr = array_map('strtolower', $file_arr);
            $check_= false;
            $fp = fopen($folder.'/'. $filename, "w+");
            if(in_array(strtolower($filename),$file_arr)){
                fwrite($fp, '');
                $check_ = true;
            }else{
                $fp = fopen($folder.'/'. $filename, "w+");
                fwrite($fp, $data);
                $check_ = false;
            }
            fclose($fp);

            //end

            if(is_file($folder.'/'. $filename) && $check_ == false){
                $type = strtolower(substr(strrchr($filename, '.'), 1));
                $typeok = TRUE;
                if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                    if(!file_exists ($folder."/thumbnail/")) {
                        mkdir($folder."/thumbnail/");
                    }
                    $thumb =  $folder."/thumbnail/".$filename;
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
                        list($w, $h) = getimagesize($folder.'/'. $filename);

                        $src = $src_func($folder.'/'. $filename);
                        $new_img = imagecreatetruecolor(80,80);
                        imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                        $write_func($new_img,$thumb, $image_quality);
                        
                        imagedestroy($new_img);
                        imagedestroy($src);
                    }
                }
            }
        }
    }
    imap_expunge($conn); 
    imap_close($conn);


    
