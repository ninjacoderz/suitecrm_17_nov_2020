<?php
    $fields = ["orderNumber" => $_REQUEST['order_number']];

    $url = "hhttps://pure-electric.com.au/pe_commerce/getOrder";
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

    foreach($products as $product){
        array_push($products_title,$product['title']);
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

    while($row = $db->fetchByAssoc($ret)){
        foreach ($products as $key => $value) {
            if($value['title'] == $row['name']){
                $check_qty = (int) $products[$key]['quantity'] ;
            }
        }
        if ($check_qty != 0) {
            $weight = floatval($row['weight_c']);
            $length = floatval($row['length_c']);
            $width  = floatval($row['width_c']);
            $height = floatval($row['height_c']);
        }
        $picking_code .= $row['picking_code_c'].', ';
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
                0 => $primary_address_street,
            ),
            'suburb'    => $primary_address_city,
            'state'     => $primary_address_state,
            'postcode'  => $primary_address_postalcode,
            'email'     => $email1,
            'phone'     => $phone_mobile,
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

    $curl = curl_init();
    $source = "http://suitecrm.devel.pure-electric.com.au/index.php?entryPoint=APICreateLabelAuspost";
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
    
?>