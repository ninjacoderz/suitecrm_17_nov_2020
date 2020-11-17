<?php 
$address = $_REQUEST['address'];
$record_id = $_REQUEST['record_id'];
$bean = new Lead();
$bean->retrieve($record_id);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://suggest.realestate.com.au//consumer-suggest/suggestions?max=7&type=address&src=homepage&query=" .$address);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Origin: https://www.realestate.com.au";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36";
$headers[] = "Accept: */*";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Authority: suggest.realestate.com.au";
$headers[] = "Referer: https://www.realestate.com.au/property/10-royal-palms-aspendale-gardens-vic-3195";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

$json_result = json_decode($result);
$count_result = $json_result->count;
$id_source = $json_result->_embedded->suggestions[0]->id;
if($count_result !== '0'){
    $url_realestate = "https://www.realestate.com.au/property/lookup?id=" .$id_source ."&source=property-search-hp";
    $bean->link_realestate_address_c = $url_realestate;
}else{
    $url_realestate = 'Not Find Address On Realestate';
    $bean->link_realestate_address_c = '';
}
$bean->save();
echo $url_realestate;