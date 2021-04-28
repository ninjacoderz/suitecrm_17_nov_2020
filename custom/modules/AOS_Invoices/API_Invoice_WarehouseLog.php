<?php
//$invoiceID =  "2e3b54f3-0f56-01b4-2fa3-5dbf6cac5285";
// $warehouseID = "6012b50a-3c12-e82f-109c-5dd35051f386";
$invoiceID = $_REQUEST['invoiceID'];

if ($invoiceID != "") {
    $db = DBManagerFactory::getInstance();
    $query = "SELECT id 
                FROM pe_warehouse_log
                WHERE sold_to_invoice_id='".$invoiceID."' 
                AND deleted=0";

    $res = $db->query($query);

    $warehouseID = $db->fetchByAssoc($res);

    echo $warehouseID['id'];
}

$module = $_POST["module"];
$type = $_GET['type'];

if ($module == "AOS_Invoices" && $type == "invoice_checklist") {
    $module_id = $_POST["module_id"];
    $data_checklist = $_POST["data_checklist"];
    $bean = new AOS_Invoices();
    $bean->retrieve($module_id);
    if (!$bean) {
        sugar_die("Invalid Record");
    } else {
        $bean->data_checklist_invoice_c = $data_checklist;
        $bean->save();
    }
    die();
}

//aupost
$check = isset($_REQUEST['check']) ? $_REQUEST['check'] : '';
$invoice_id = isset($_REQUEST['invoice_id']) ? trim($_REQUEST['invoice_id']): '';

if ($check != '' && $invoice_id != '') {
    $invoice = new AOS_Invoices();
    $invoice->retrieve($invoice_id);
    if ($invoice->id) {
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * 
                    FROM pe_warehouse_log
                    WHERE sold_to_invoice_id='".$invoice_id."' 
                    AND deleted=0 AND aupost_shipping_id != '' 
                    ORDER BY number DESC
                    LIMIT 1";
        $result = $db->query($sql);
        $WHL = $db->fetchByAssoc($result);

        if ($WHL) {
            $invoice->aupost_shipping_id = trim($WHL['aupost_shipping_id']);
            $invoice->save();
            echo trim($WHL['aupost_shipping_id']);
            die;
        } 
    }
    echo 'not id';
}


// function getAupostInfo ($shipments_id) {
//         $tmpfname = dirname(__FILE__).'/cookie.auspost.txt';
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/cssoapi/v2/session');
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"accounts@pure-electric.com.au","password":"aPureandTrue2018*"}');
//         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//         curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//         curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//         curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
//         $headers = array();
//         $headers[] = 'Connection: keep-alive';
//         $headers[] = 'Pragma: no-cache';
//         $headers[] = 'Cache-Control: no-cache';
//         $headers[] = 'Accept: application/json, text/plain, */*';
//         $headers[] = 'Origin: https://auspost.com.au';
//         $headers[] = 'Ap_app_id: MYPOST';
//         $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
//         $headers[] = 'Content-Type: application/json';
//         $headers[] = 'Sec-Fetch-Site: same-site';
//         $headers[] = 'Sec-Fetch-Mode: cors';
//         $headers[] = 'Referer: https://auspost.com.au/mypost-business/auth/';
//         $headers[] = 'Accept-Encoding: gzip, deflate, br';
//         $headers[] = 'Accept-Language: en-US,en;q=0.9';
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         $result = curl_exec($ch);
//         curl_close($ch);
        
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/accessone/v1/session');
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//         curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//         curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//         curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
//         $headers = array();
//         $headers[] = 'Connection: keep-alive';
//         $headers[] = 'Pragma: no-cache';
//         $headers[] = 'Cache-Control: no-cache';
//         $headers[] = 'Accept: application/json, text/plain, */*';
//         $headers[] = 'Origin: https://auspost.com.au';
//         $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
//         $headers[] = 'Sec-Fetch-Site: same-site';
//         $headers[] = 'Sec-Fetch-Mode: cors';
//         $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
//         $headers[] = 'Accept-Encoding: gzip, deflate, br';
//         $headers[] = 'Accept-Language: en-US,en;q=0.9';
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $result = curl_exec($ch);
//         curl_close($ch);

//         // $ch = curl_init();
//         // curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments?shipment_ids='.$shipments_id);
//         // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
//         // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//         // curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//         // curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//         // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//         // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//         // curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
//         // $headers = array();
//         // $headers[] = 'Connection: keep-alive';
//         // $headers[] = 'Pragma: no-cache';
//         // $headers[] = 'Cache-Control: no-cache';
//         // // $headers[] = 'Access-Control-Request-Method: POST';
//         // $headers[] = 'Origin: https://auspost.com.au';
//         // $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
//         // $headers[] = 'Access-Control-Request-Headers: account-number,auspost-partner-id,content-type';
//         // $headers[] = 'Accept: */*';
//         // $headers[] = 'Sec-Fetch-Site: same-site';
//         // $headers[] = 'Sec-Fetch-Mode: cors';
//         // // $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/add/retail';
//         // $headers[] = 'Accept-Encoding: gzip, deflate, br';
//         // $headers[] = 'Accept-Language: en-US,en;q=0.9';
//         // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         // $result = curl_exec($ch);
//         // curl_close($ch);

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/shipments?shipment_ids='.$shipments_id);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//         curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
//         curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//         curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
//         $headers = array();
//         $headers[] = 'Connection: keep-alive';
//         $headers[] = 'Pragma: no-cache';
//         $headers[] = 'Cache-Control: no-cache';
//         $headers[] = 'Accept: application/json, text/plain, */*';
//         $headers[] = 'Origin: https://auspost.com.au';
//         $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
//         $headers[] = 'Sec-Fetch-Site: same-site';
//         $headers[] = 'Sec-Fetch-Mode: cors';
//         $headers[] = 'Referer: https://auspost.com.au';
//         $headers[] = 'Accept-Encoding: gzip, deflate, br';
//         $headers[] = 'Accept-Language: en-US,en;q=0.9';
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         $result = curl_exec($ch);
//         curl_close($ch);
//         echo $result;

// }


