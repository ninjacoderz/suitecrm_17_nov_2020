<?php
   $record_lead_id = trim('a32f96d4-2a80-dc71-10cc-5e142cca51bd');
    $products = 'Sanden,solar,methven,';
$lead = new Lead();
   $lead->retrieve($record_lead_id);
   $smsTemplate = BeanFactory::getBean(
       'pe_smstemplate',
       '357303ad-8b3b-5f42-b9c5-60ae1a915a22' 
   );
   $user_assign = new User();
   $user_assign->retrieve($lead->assigned_user_id);
//    $phone_assigned = $user_assign->phone_mobile;

   foreach ($array_products as $key_product => $value_product) {
       if( $value_product != "" ){
           $productType .= $value_product.", ";
       }
   }
   $link_leads = 'https://suitecrm.pure-electric.com.au/index.php?module=Leads&action=EditView&record='.$lead->id;
   $description = $smsTemplate->description;
   $body = $smsTemplate->body_c;

   $body = str_replace("\$assigned_first_name", $user_assign->first_name, $body);
   $body = str_replace("\$customer_first_name", $lead->first_name, $body);
   $body = str_replace("\$customer_last_name", $lead->first_name, $body);
   $body = str_replace("\$lead_number", $lead->number, $body);
   $body = str_replace("\$address_subub", $lead->primary_address_city, $body);
   $body = str_replace("\$address_state", $lead->primary_address_state, $body);
   $body = str_replace("\$productType", $productType, $body);

//    $phone_assigned = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_assigned));
   $phone_assigned = '+61421616733';//preg_replace("/^61/", "+61", $phone_assigned);
   $message_dir1 = '/var/www/message';
   exec("cd ".$message_dir1."; php send-message.php sms ".$phone_assigned.' "'.$description.'<br>'.$body.'"');
   $client_number2 = '+61490942067';
   exec("cd ".$client_number2."; php send-message.php sms ".$phone_assigned.' "'.$description.'<br>'.$body.'"');

   die();
    // header("Location: https://pvwatts.nrel.gov/handle_mylocation.php?myloc=2%20Eady%20St%20Dickson%20ACT%202602");
    // die;
    // $quote = new AOS_Quotes();
    // $quote->retrieve('92252f14-4f95-9578-c236-6063b6f5b3b5');
    // $saleperson = new User();
    // $saleperson->retrieve($quote->assigned_user_id);
    // echo $saleperson->email1;
// $date = strtotime(); $dateAUS = date('m/d/Y H:i:s a', time());

date_default_timezone_set('Australia/Melbourne');
$dateAUS = date('H', time());
echo $dateAUS; 
die;
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