<?php
    $map = $_REQUEST['mapAPI'];
    $address_components = $map['results'][0]['address_components'];
    $dataMap = [];
    foreach($address_components as $key => $value){
        switch ($value['types'][0]) {
            case 'street_number':
                $dataMap['street_number'] = $value['short_name'];
                break;
            case 'route':
                $dataMap['route'] = $value['short_name'];
                break;
            case 'locality':
                $dataMap['locality'] = $value['short_name'];
                break;
            case 'administrative_area_level_1':
                $dataMap['state'] = $value['short_name'];
                break;
            case 'postal_code':
                $dataMap['zip'] = $value['short_name'];
                break;
        }
    }
    $dataMap['address'] = $dataMap['street_number'].' '.$dataMap['route'];
    $default  = array(
        "usage_data_source" => "Default",
        "kwh_annual" => "",
        "bill_annual" => "",
        "interval_60min" => "",
        "curve_weekday" => null,
        "curve_weekend" => null,
        "scale_weekend" => null,
        "controlled_load_daily_kwh_0" => null,
        "controlled_load_daily_kwh_1" => null,
        "controlled_load_daily_kwh_2" => null,
        "country_iso2" => "AU",
        "is_residential" => true,
        "usage" => "{\"usage_data_source\":\"default\",\"values\":null,\"curve_weekday\":null,\"curve_weekend\":null,\"scale_weekend\":null,\"controlled_load_daily_kwh_0\":null,\"controlled_load_daily_kwh_1\":null,\"controlled_load_daily_kwh_2\":null}",
        "street_number" => $dataMap['street_number'],
        "route" => $dataMap['route'],
        "locality" => $dataMap['locality'],
        "state" => $dataMap['state'],
        "zip" => $dataMap['zip'],
        "address" => $dataMap['address'],
        "lat" => $map['results'][0]['geometry']['location']['lat'],
        "lon" => $map['results'][0]['geometry']['location']['lng'],
        "contacts_new" => array(
            array(
                "first_name" => $_REQUEST['first_name'],
                "family_name" => $_REQUEST['family_name'],
                "email" => $_REQUEST['email'],
                "phone" => $_REQUEST['phone']
            )
        )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://loc.api.solardesign.com/api/orgs/1388/projects/?skip_response=true');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($default));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"87\", \" Not;A Brand\";v=\"99\", \"Chromium\";v=\"87\"';
    $headers[] = 'Accept: application/json';
    // $headers[] = 'Authorization: Bearer e59069b120869d64a45abd7bb78be7034699f4f8';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Origin: https://solardesign.pure-electric.com.au';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://solardesign.pure-electric.com.au/';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

?>