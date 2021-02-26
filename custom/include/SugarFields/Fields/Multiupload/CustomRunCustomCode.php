<?php
    $bean = new AOS_Invoices();
    $bean->retrieve('611e5cc6-8c67-ea50-eac8-603330a8a178');

    $db = DBManagerFactory::getInstance();
    $sql = "UPDATE `emails` SET `deleted` = 1 WHERE `status` = 'email_schedule' AND `parent_id` = '$bean->id' AND `name` = 'Warranty registration photos and serials' AND deleted = 0";
    $db->query($sql);
    $emailTemplateID = 'a60e5ca5-6919-87ac-916c-6034cbff7477';//test 'c51e810f-f6b5-bf50-5ab6-6034cbce9ce3';

    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve($emailTemplateID);

    $contact =  new Contact();
    $contact->retrieve($bean->billing_contact_id);

    $name = $emailTemplate->subject;
    $description_html = $emailTemplate->body_html;
    $description = $emailTemplate->body;
    
    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => htmlspecialchars($emailtemplate->body_html),
            "body" => $emailtemplate->body_html
            ),
            'AOS_Invoices',
            $lead,
            $macro_nv
        );
    
    $name = $template_data['subject'];
    $description = $template_data['body'];
    $description_html = $template_data['body_html'];
    //parse value

    $link_upload_files = 'https://pure-electric.com.au/upload_file_sanden/client-warranty?invoice_id=' . $invoice->id;
    $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
    $description = str_replace("\$contact_first_name",$contact->first_name , $description);
    $description = str_replace("\$aos_invoices_link_upload",$string_link_upload_files , $description);

    $description_html = str_replace("\$contact_first_name",$contact->first_name , $description_html);
    $description_html = str_replace("\$aos_invoices_link_upload",$string_link_upload_files, $description_html);

    $mail_From = "info@pure-electric.com.au";
    $mail_FromName = "Pure Electric";
    $emailSignatureId = '3ad8f82a-d3e7-5897-7c98-5ba1c4ac785e'; 
    //signature
    $user = new User();
    $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
    $defaultEmailSignature = $user->getSignature($emailSignatureId);

    if (empty($defaultEmailSignature)) {
        $defaultEmailSignature = array(
            'html' => '<br>',
            'plain' => '\r\n',
        );
        $defaultEmailSignature['no_default_available'] = true;
    } else {
        $defaultEmailSignature['no_default_available'] = false;
    }
    $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
    $description .= "<br><br><br>";
    $description .=  $defaultEmailSignature['signature_html'];
    $description_html .= "<br><br><br>";
    $description_html .=  $defaultEmailSignature['signature_html'];
    $schedule_time = strtotime(date('d-m-Y H:i:s')) + 3; //+ 24 minutes
    //create email 
    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "out";
    $email->status = "email_schedule";
    $email->parent_type = 'AOS_Invoices';
    $email->parent_id = $bean->id;
    $email->parent_name = $bean->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description_html;
    $email->schedule_timestamp_c = $schedule_time;
    $email->from_addr = $mail_From;
    $email->from_name = $mail_FromName;
    $email->to_addrs_emails = "tuan test <ngoanhtuan2510@gmail.com>;";
    $email->to_addrs = "tuan test <ngoanhtuan2510@gmail.com>";
    $email->to_addrs_names =  "tuan test <ngoanhtuan2510@gmail.com>";
    $email->to_addrs_arr = array(
        array(
            'email' => "ngoanhtuan2510@gmail.com",
            'display' => "tuan test",
        )
    );
    $email->cc_addrs_emails = "Binhdj <binhdigipro@gmail.com>;";
    $email->cc_addrs = 'Binhdj <binhdigipro@gmail.com>';
    $email->cc_addrs_names = "Binhdj <binhdigipro@gmail.com>";
    $email->cc_addrs_arr = array(
        array(
            'email' => 'binhdigipro@gmail.com',
            'display' => 'Binhdj'
        )
    );
    $email_id = $email->id;

    // $note = new Note();
    // $where = "notes.parent_id = '$emailTemplateID'";
    // $attachments = $note->get_full_list("", $where, true);
    // $all_attachments = array();
    // $all_attachments = array_merge($all_attachments, $attachments);
    // foreach($all_attachments as $attachment) {
    //     $noteTemplate = clone $attachment;
    //     $noteTemplate->id = create_guid();
    //     $noteTemplate->new_with_id = true; 
    //     $noteTemplate->parent_id = $email->id;
    //     $noteTemplate->parent_type = 'Emails';
    //     $noteFile = new UploadFile();
    //     $noteFile->duplicate_file($attachment->id, $noteTemplate->id, $noteTemplate->filename);
    //     $noteTemplate->save();
    //     $email->attachNote($noteTemplate);
    // }
    $email->save();
die();
$db = DBManagerFactory::getInstance();
$sql = "SELECT DISTINCT calls_aos_quotes_1calls_ida as id FROM `calls_aos_quotes_1_c` WHERE `date_modified` >= '2020-06-25 04:50:53' AND `deleted` = 0";
$ret = $db->query($sql);
while($row = $ret->fetch_assoc()){
    if ($row['id'] != '') { 
        $sql_count = "SELECT * FROM `calls_aos_quotes_1_c` WHERE calls_aos_quotes_1calls_ida = '".$row['id']."' AND `deleted` = 0 ";
        $ret_count = $db->query($sql_count);
        if($ret_count->num_rows == 1) {
            while($row_count = $ret_count->fetch_assoc()){
                $sql_update = "UPDATE `calls_cstm` SET aos_quotes_id_c ='".$row_count['calls_aos_quotes_1aos_quotes_idb']."' WHERE id_c = '".$row['id']."' ";
                $ret_update= $db->query($sql_update);
               // var_dump($ret_update,$row_count['calls_aos_quotes_1aos_quotes_idb'],$sql_update);
                echo $row['id'] .'<br>'; 
            }
        }

    }
}
die();

die();
$db = DBManagerFactory::getInstance();

$sql= "SELECT * FROM aos_products WHERE `name` IN ('Methven Kiri Satinjet Graphite Low Flow Showerhead Handset with Hose','Methven Shipping and Handling') ORDER BY price ASC";
$result = $db->query($sql);
    if($result->num_rows > 1){
        while($row =  $db->fetchByAssoc($result)){
            var_dump($row);
            if( $row['part_number'] == "13-8265 (FLX252)_H" ){ //Handheld with only
                echo '1'. $row['part_number'];
            }else if( $row['part_number'] == "13-8258" ){
                echo '2'. $row['part_number'];
            }else if( $row['part_number'] == "13-8265 (FLX252)" ){ //Handheld only
                echo '3'. $row['part_number'];
            }
        }
    }
die;;
$weight = 0.66;
$length = 29.6;
$width = 19.0;
$height = 8.5;

//auto create shipments auspost
$tmpfname = dirname(__FILE__).'/cookie.auspost.txt';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/cssoapi/v2/session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"accounts@pure-electric.com.au","password":"aPureandTrue2018*"}');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
$headers = array();
$headers[] = 'Connection: keep-alive';
$headers[] = 'Pragma: no-cache';
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'Origin: https://auspost.com.au';
$headers[] = 'Ap_app_id: MYPOST';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Referer: https://auspost.com.au/mypost-business/auth/';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
echo '----------';
var_dump($result);
echo '----------</br>';
curl_close($ch);
$shipments = array (
    'shipments' => 
    array (
    0 => 
    array (
        'from' => 
        array (
        'name' => 'Matthew Wright',
        'business_name' => 'Pure Electric',
        'lines' => 
        array (
            0 => '38 EWING ST',
        ),
        'suburb' => 'BRUNSWICK',
        'state' => 'VIC',
        'postcode' => '3056',
        'email' => 'info@pure-electric.com.au',
        'phone' => '0421616733',
        ),
        'to' => 
        array (
        'name' => 'thien test',
        'business_name' => '',
        'type' => 'STANDARD_ADDRESS',
        'country' => 'AU',
        'lines' => 
        array (
            0 => '38 Ewing St',
        ),
        'suburb' => 'BRUNSWICK',
        'state' => 'VIC',
        'postcode' => '3056',
        'email' => 'thienpb89@gmail.com',
        'phone' => '0909999999',
        ),
        'email_tracking_enabled' => true,
        'items' => 
        array (
        0 => 
        array (
            'contains_dangerous_goods' => false,
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'product_id' => 'B30',
        ),
        ),
    ),
    ),
);
file_put_contents('text1.txt',json_encode($shipments));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/accessone/v1/session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
$headers = array();
$headers[] = 'Connection: keep-alive';
$headers[] = 'Pragma: no-cache';
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'Origin: https://auspost.com.au';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
$headers = array();
$headers[] = 'Connection: keep-alive';
$headers[] = 'Pragma: no-cache';
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Access-Control-Request-Method: POST';
$headers[] = 'Origin: https://auspost.com.au';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
$headers[] = 'Access-Control-Request-Headers: account-number,auspost-partner-id,content-type';
$headers[] = 'Accept: */*';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($shipments));
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);

$headers = array();
$headers[] = 'Connection: keep-alive';
$headers[] = 'Pragma: no-cache';
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'Account-Number: 62ff9f94f4534eb3b93080c9a3edcd9c';
$headers[] = 'Origin: https://auspost.com.au';
$headers[] = 'Content-Type: application/json;charset=UTF-8';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
$headers[] = 'Auspost-Partner-Id: SENDAPARCEL-UI';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
//$headers[] = 'Content-Length: ' .strlen(json_encode($shipments));
$headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Accept-Language: en-US,en;q=0.9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);
echo '----------';
var_dump($result);
echo '----------</br>';

die;
ini_set("display_errors",1);
set_time_limit(0);
ini_set('memory_limit', '-1');
$db = DBManagerFactory::getInstance();

// $sql = "SELECT count(*) from po_purchase_order_aos_products_quotes_1_c";
// $result = $db->query($sql);
// var_dump($db->fetchByAssoc($result));

// $sql = "TRUNCATE TABLE po_purchase_order_aos_products_quotes_1_c";
// $result = $db->query($sql);
// var_dump($result);

// $sql = "SELECT count(*) from po_purchase_order_aos_products_quotes_1_c";
// $result = $db->query($sql);
// var_dump($db->fetchByAssoc($result));
// die;
    $sql = "select id,parent_type,parent_id,product_id from aos_products_quotes where deleted = 0 and parent_type='PO_purchase_order'";
    $result = $db->query($sql);
    if($result->num_rows > 1){
        while($row =  $db->fetchByAssoc($result)){
            $sql = "INSERT INTO `po_purchase_order_aos_products_quotes_1_c` (`id`, `date_modified`, `deleted`, `po_purchase_order_aos_products_quotes_1po_purchase_order_ida`, `po_purchase_order_aos_products_quotes_1aos_products_quotes_idb`) VALUES (\"".generateGUID()."\", now() , '0',\"".$row['parent_id']."\" ,\"".$row['id']."\" )";
            $result1 = $db->query($sql);
        }
    }
    function generateGUID($prefix = '') {
        $uuid = md5(uniqid(mt_rand(), true));
        $guid =  $prefix.substr($uuid,0,8)."-".
                substr($uuid,8,4)."-".
                substr($uuid,12,4)."-".
                substr($uuid,16,4)."-".
                substr($uuid,20,12);
        return $guid;
    }
die;
?>