<?php
   //thien code
    $sg_order_number = trim($_GET['sg_order_number']);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    // $headers = array();
    // $headers[] = "Pragma: no-cache";
    // $headers[] = "Accept-Encoding: gzip, deflate, br";
    // $headers[] = "Accept-Language: en-US,en;q=0.9";
    // $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
    // $headers[] = "Accept: application/json, text/plain, */*";
    // $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
    // $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
    // $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    // $headers[] = "Connection: keep-alive";
    // $headers[] = "Cache-Control: no-cache";
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // $result = curl_exec($ch);
    // curl_close ($ch);
    //tu code serch solargain oder number
    $number = '';
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id FROM aos_invoices WHERE name LIKE '%$sg_order_number%' AND deleted = 0";
    $ret = $db->query($sql);
    if($ret->num_rows > 0){
        while($row = $ret ->fetch_assoc()){
            $number = $row['id'];
        }
    }
    $sg_order_json = json_decode($result);
    if($number !== ''){
        $array = array(
            'alert'=> 'Exist Invoice ' .$invoice_num,
            'id' => $number,
        );
        echo json_encode($array);
    }else{
        $array = array(
            'alert'=> 'Non-existence Invoice',
            'id' => '',
        );
        echo json_encode($array);
    }
    die();