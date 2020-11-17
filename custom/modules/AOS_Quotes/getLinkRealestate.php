<?php
$street = trim(strtolower($_POST['street']));
$city = trim(strtolower($_POST['city']));
$state = trim(strtolower($_POST['state']));
$postcode = trim(strtolower($_POST['postcode']));

// if (strpos($street,'street') !== false ) { && strpos($street, "unit")
//    $street = str_replace('street', 'st', $street);
// }
if (strpos($street, "/") !== false) {
    if (strpos($street, 'unit') !== false) {
        $street = str_replace('/', '-', $street);
    } else {
        $street = 'unit-'.str_replace('/', '-', $street);
    }
}

$address = $street.' '.$city.' '.$state.' '.$postcode;

$ch = curl_init();
$url = 'https://suggest.realestate.com.au/consumer-suggest/suggestions?max=1&query='.str_replace(' ','-',$address).'&type=address&src=homepage';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0';
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: en-GB,en;q=0.5';
$headers[] = 'Origin: https://www.realestate.com.au';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://www.realestate.com.au/property/';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close ($ch);

$json_result = json_decode($result);
$count_result = $json_result->count;
$urlAddress = $json_result->_embedded->suggestions[0]->source->url;

if($count_result !== '0'){
    $url_realestate = $urlAddress;
}else{
    $url_realestate = 'Not Find Address On Realestate';
}

echo $url_realestate;