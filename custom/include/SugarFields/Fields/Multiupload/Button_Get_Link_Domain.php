<?php 
$address = $_REQUEST['address'];
$record_id = $_REQUEST['record_id'];
$bean = new Lead();
$bean->retrieve($record_id);

$address = str_replace("_"," ",$address);
$address = $address = rawurlencode(utf8_encode($address));

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://location-typeahead-api.domain.com.au/v1/locations/properties/_suggest/components?source=PropertyId&pageSize=1&terms='.$address);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Referer: https://www.domain.com.au/property-profile';
$headers[] = 'Origin: https://www.domain.com.au';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36';
$headers[] = 'Api-Consumer: API-Consumer';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);


$json_result = json_decode($result);

if(isset($json_result ) && $json_result !== NULL ){
    $UrlSlug = $json_result[0]->urlSlug;
    $url_domain = "https://www.domain.com.au/property-profile/".$UrlSlug ;
    $bean->link_domain_address_c = $url_domain;
}else{
    $url_domain = 'Not Find Address On Domain';
    $bean->link_domain_address_c = '';
}
$bean->save();
echo $url_domain;