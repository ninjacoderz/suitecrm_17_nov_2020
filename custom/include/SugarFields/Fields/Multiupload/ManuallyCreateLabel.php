<?php
ini_set("display_errors",1);
    $fields = ["orderNumber" => $_REQUEST['order_number']];
    $quote_number = isset($_REQUEST['quote_number']) ? $_REQUEST['quote_number'] : '';
    if ($quote_number == '') {
        $url = "https://pure-electric.com.au/pe_commerce/getOrder";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        $data_json = json_decode($result,true);
    
        curl_close ($ch);
    } else {
        $db = DBManagerFactory::getInstance();
        $query =  " SELECT aos_quotes.id as quote_id
                    FROM aos_quotes
                    WHERE aos_quotes.number = '$quote_number' AND aos_quotes.deleted = 0 LIMIT 1";
        $result = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            $quote = new AOS_Quotes();
            $quote->retrieve($row['quote_id']);
            $account_customer = new Account();
            $account_customer->retrieve($quote->billing_account_id);
            $array_products = [];
            $ship_method_id_quote = '';
            $couponCode_quote = '';
            $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
            $result = $db->query($sql);
            while ($row = $db->fetchByAssoc($result)) {
                if ($row['part_number'] == 'Australia_Post_Express') {
                    $ship_method_id_quote = '2';
                } elseif ($row['part_number'] == 'Australia_Post_Standard') {
                    $ship_method_id_quote = '1';
                } elseif ($row['part_number'] == 'Pure_Electric_Promo_Code') {
                    $couponCode_quote = '1';
                } else {
                    array_push($array_products,[
                        'title' => $row['name'],
                        'quantity' => $row['product_qty'],
                    ]);
                }
            }
            //save $data_json
            $data_json = [
                'account_name' => $quote->billing_account,
                'first_name' => $quote->account_firstname_c,
                'last_name' => $quote->account_lastname_c,
                'primary_address_street' => $quote->shipping_address_street != '' ? $quote->shipping_address_street: $quote->billing_address_postalcode,
                'primary_address_city' => $quote->shipping_address_city != '' ? $quote->shipping_address_city: $quote->billing_address_city,
                'primary_address_state' => $quote->shipping_address_state != '' ? $quote->shipping_address_state: $quote->billing_address_state,
                'primary_address_postalcode' => $quote->shipping_address_postalcode != '' ? $quote->shipping_address_postalcode: $quote->billing_address_postalcode,
                'primary_address_country' => $quote->shipping_address_country != '' ? $quote->shipping_address_country : 'Australia',
                'email1' => $account_customer->email1,
                'phone_mobile' => $account_customer->mobile_phone_c,
                'products' => $array_products,
                'couponCode' => $couponCode_quote,
                'ship_method_id' => $ship_method_id_quote,
                'orderID' => 'Q'.$quote_number,
            ];

        }
    }

    $account_name =  $data_json['account_name'];
    $first_name = $data_json['first_name'];
    $last_name = $data_json['last_name'];
    $primary_address_street = $data_json['primary_address_street'];
    $primary_address_city = $data_json['primary_address_city'];
    $primary_address_state = $data_json['primary_address_state'];
    $primary_address_postalcode =  $data_json['primary_address_postalcode'];
    $primary_address_country =  $data_json['primary_address_country'];
    $email1 =  $data_json['email1'];
    $phone_mobile = $data_json['phone_mobile'];
    $orderID =  $data_json['orderID'];
    $products = $data_json['products'];
    $couponCode = $data_json['couponCode'];
    $ship_method_id = $data_json['ship_method_id'];
    $products_title = [];
    $products_clone = [];
    foreach($products as $product){
        $title = explode('-',$product['title']);
        $product['title'] = trim($title[0]);
        if($product['title'] == 'ValveCosy' || $product['title'] == 'Valvecosy Insulator'){
            array_push($products_title,'Valvecosy Insulator');
        }else{
            array_push($products_title,$product['title']);
        }
        array_push($products_clone,$product);
    }
    

    if( $ship_method_id == "1"){
        array_push($products_title,"Methven Shipping and Handling Standard");
        $type_shipping = "B30";
    }elseif( $ship_method_id == "2"){
        array_push($products_title,"Methven Shipping and Handling Express");
        $type_shipping = "B20";
    }
    //add line items promo code
    if($couponCode != ''){
        array_push($products_title,"Pure Electric Promo Code");
    }

    $products_title = implode("','", $products_title);

    $db = DBManagerFactory::getInstance();
    $weight = 0;
    $length = 0;
    $width = 0;
    $height = 0;
    $picking_code = '';
    $sql = "SELECT * FROM aos_products 
            LEFT JOIN aos_products_cstm ON aos_products.id = aos_products_cstm.id_c
            WHERE `name` IN ('".$products_title."') 
            ORDER BY price ASC";
    $ret = $db->query($sql);
    echo $sql;
    var_dump($ret);
    while($row = $db->fetchByAssoc($ret)){
        echo $row['name'];
        foreach ($products_clone as $key => $value) {
            if(strtolower($value['title']) == strtolower($row['name']) || strtolower($row['name']) == strtolower('Valvecosy Insulator')){
                $check_qty = (int) $products_clone[$key]['quantity'] ;
            }
        }
        if ($check_qty != 0) {
            $weight = floatval($row['weight_c']);
            $length = floatval($row['length_c']);
            $width  = floatval($row['width_c']);
            $height = floatval($row['height_c']);
            
            $picking_code .= $check_qty .'x '.$row['picking_code_c'].', ';
        }else{
            $picking_code .= $row['picking_code_c'].', ';
        }
    }

    $shipments = array (
        'shipments' => 
        array (
        0 => 
        array (
            'from'  => 
            array (
            'name' => 'Matthew Wright',
            'business_name' => 'Pure Electric',
            'lines' => 
            array (
                0   => '38 EWING ST',
            ),
            'suburb'=> 'BRUNSWICK',
            'state' => 'VIC',
            'postcode' => '3056',
            'email' => 'info@pure-electric.com.au',
            'phone' => '0421616733',
            ),
            'to' => 
            array (
            'name' => $account_name,
            'business_name' => '',
            'type' => 'STANDARD_ADDRESS',
            'country' => 'AU',
            'lines' => 
            array (
                0 => strtoupper(trim($primary_address_street)),
            ),
            'suburb'    => trim($primary_address_city),
            'state'     => trim($primary_address_state),
            'postcode'  => trim($primary_address_postalcode),
            'email'     => trim($email1),
            'phone'     => trim($phone_mobile),
            ),
            'email_tracking_enabled' => true,
            'customer_reference_1' => '#'.$orderID.' '.trim($picking_code,', '),
            'items' => 
            array (
            0 => 
            array (
                'contains_dangerous_goods' => false,
                'item_description' => '#'.$orderID.' '.trim($picking_code,', '),
                'weight' => $weight,
                'length' => $length,
                'width'  => $width,
                'height' => $height,
                'product_id' => $type_shipping,
            ),
            ),
        ),
        ),
    );
    //custom overide parcel collect
    $regex_parcel_collect = regex_parcel_collect($primary_address_street);
    if(!empty($regex_parcel_collect)){
        if(isset($regex_parcel_collect['type_to']) && isset($regex_parcel_collect['apcn'])){
            $shipments['shipments'][0]['to']['type'] = $regex_parcel_collect['type_to'];
            $shipments['shipments'][0]['to']['apcn'] =  str_replace(' ','',$regex_parcel_collect['apcn']); 
            $shipments['shipments'][0]['to']['lines'][0] =  $regex_parcel_collect['lines_address'];    
        } 
    }

    $curl = curl_init();
    $source = "http://suitecrm.pure-electric.com.au/index.php?entryPoint=APICreateLabelAuspost";
    //$source = "http://loc.suitecrm.com/index.php?entryPoint=APICreateLabelAuspost";
    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("shipments"=>$shipments)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);

    $json_result = json_decode($result);
    $aupost_shipping_id = $json_result->shipments[0]->shipment_id;

    if(!empty($aupost_shipping_id)){
        echo $aupost_shipping_id;  
    }else{
        echo '';
    }
    
function regex_parcel_collect($str){
    $return_data = array();
    if(isset($str)){
        //get type_to
        $str_lowercase = strtolower($str);
        if(strpos($str_lowercase,'parcel') !== false){
            if(strpos($str_lowercase,'collect') !== false){
                $return_data['type_to'] = 'PARCEL_COLLECT';
            }
            if(strpos($str_lowercase,'locker') !== false){ 
                $return_data['type_to'] = 'PARCEL_LOCKER';
            }
        }
        //get apcn
        preg_match('/[\d]{10}|[\d]{5}\s[\d]{5}/s',$str,$match);
        if(isset($match[0])){
            $return_data['apcn'] = $match[0];
        //get lines_address
            $add_explode = explode($match[0],$str);
            $return_data['lines_address'] = trim(end($add_explode));
        }    
    }
    return $return_data;
}
?>