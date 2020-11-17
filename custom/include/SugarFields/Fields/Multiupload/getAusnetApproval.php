<?php
require_once 'modules/InboundEmail/AOPInboundEmail.php';

    global $dictionary;
    global $app_strings;
    global $sugar_config;
    global $charset,$htmlmsg,$plainmsg,$attachments,$current_user;
    $attachments = array();
    $has_file = false;

    $nmi_number =  urlencode($_GET['nmi_number']);
    $meter_number =  urlencode($_GET['meter_number']);
    $installation_pictures = urlencode($_GET['installation_pictures']);

    require_once('modules/Configurator/Configurator.php');

    require_once(dirname(__FILE__).'/simple_html_dom.php');

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

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

    // create file pdf from https://scp.ausnetservices.com.au/
    if($nmi_number !='' && $meter_number !=''){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://scp.ausnetservices.com.au/SolarCapacity/YourDetails");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "agreeToTerms=true");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Origin: https://scp.ausnetservices.com.au";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "Content-Length: 17";
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Referer: https://scp.ausnetservices.com.au/";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Cookie: ARRAffinity=2291623293653a30f4347c450ac1ea30274a1f05700cc0f45038c6383dfb3875; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; ASP.NET_SessionId=dwq2b2cjplfexbclhblsclnd; __RequestVerificationToken=Hos1YEAOtyhArroSsZ1VzMiXJuQ7YIvgFSM1H9B85ZUq_jkwpn73TT-RnX2HQC6YxcjZ0l_xaChIYg9-N4_Z2plh3SngyDcqe_KO6b4auRM1";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        $html = str_get_html($result);
        $token = $html->find("input[name='__RequestVerificationToken']",0)->value;

        curl_close ($ch);

        $ch = curl_init();

        $email = $current_user->email1;
        $data_string = array("__RequestVerificationToken" => $token,
                             "NMI"                        => $nmi_number,
                             "MeterId"                    => $meter_number,
                             "Email"                      => $email);
        curl_setopt($ch, CURLOPT_URL, "https://scp.ausnetservices.com.au/SolarCapacity/CurrentCapacity");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Origin: https://scp.ausnetservices.com.au";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "Content-Length: ".strlen(http_build_query($data_string));
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Referer: https://scp.ausnetservices.com.au/SolarCapacity/YourDetails";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Cookie: ARRAffinity=2291623293653a30f4347c450ac1ea30274a1f05700cc0f45038c6383dfb3875; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; ASP.NET_SessionId=dwq2b2cjplfexbclhblsclnd; __RequestVerificationToken=Hos1YEAOtyhArroSsZ1VzMiXJuQ7YIvgFSM1H9B85ZUq_jkwpn73TT-RnX2HQC6YxcjZ0l_xaChIYg9-N4_Z2plh3SngyDcqe_KO6b4auRM1";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
       
        $html = str_get_html($result);
        $new_pre_approval = $html->find("input[class='btn btn-primary pull-left']",0)->value;
 
        if($new_pre_approval == 'New Pre-Approval'){
            
            //wait check inbox
            sleep(90);

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

            $imap_con = "{".$server_url.":".$port."/imap/ssl/novalidate-cert}INBOX";

            $conn = imap_open($imap_con,$email_user,$email_password); 

            $emails = imap_search($conn,'UNSEEN');
            
            rsort($emails);
            /* for every email... */
            foreach($emails as $email_number) {
                $overview = imap_fetch_overview($conn,$email_number,0);
                // $message = imap_fetchbody($conn,$email_number, 1);
                /* get mail structure */
            
                foreach($overview as $result){
                    if($result->from == 'Ausnet Services <SVC_SolarCapEst_Mail@ausnetservices.com.au>' && $result->subject == 'Ausnet Services Solar PreApproval'){
                        $s = imap_fetchstructure($conn, $email_number);
                        if (!$s->parts)  // simple
                            getpart($mbox,$mid,$s,0);  // pass 0 as part-number
                        else {  // multipart: cycle through each part
                            foreach ($s->parts as $partno0=>$p)
                                getpart($conn,$email_number,$p,$partno0+1);
                        }

                        // check and write file attachments to folder
                        if(count($attachments) > 0) {
                            foreach($attachments as $filename=>$data) {
                                $filename = $filename;
                                $expl_filename = explode("_",$filename);
                                if(count($expl_filename) > 1){
                                    if($expl_filename[0] == $nmi_number){
                                        $filename = $meter_number.'_CITIPOWER_POWERCOR_APPROVAL.pdf';
                                        $generate_ID = $installation_pictures;
                                        $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;
                                        if(!is_dir($folder))
                                        {
                                            mkdir($folder);
                                        }
                                        $fp = fopen($folder.'/'. $filename, "w+");
                                        fwrite($fp, $data);
                                        fclose($fp);
                                        $has_file = true;
                                    }
                                }
                            }

                        }
                    }
                }
                break;
            }
            imap_expunge($conn); 
            imap_close($conn);
        }
        curl_close ($ch);
    }

    if($has_file){
        echo 'done';
    }
    die();