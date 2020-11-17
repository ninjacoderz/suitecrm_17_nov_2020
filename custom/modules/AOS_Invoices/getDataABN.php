<?php
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');
$number_ABN = str_replace(' ', '', $_GET['number_ABN']);
$url = 'https://abr.business.gov.au/ABN/View?id='.$number_ABN;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Upgrade-Insecure-Requests: 1";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
$headers[] = "Referer: https://abr.business.gov.au/ABN/View?abn=215185561789";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "Cookie: _ga=GA1.3.306808548.1532588411; _gid=GA1.3.1877047624.1532588411";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close ($ch);

$html = str_get_html($result);


$json_result = array();
// get ABN details
foreach ($html->find('div.container-content div[itemtype=http://schema.org/LocalBusiness] table tbody tr') as $value) {
        if(trim($value->find('th')[0]->innertext) == 'Entity name:'){
            $json_result['Entiny_name'] = html_entity_decode(trim($value->find('td span')[0]->innertext));
        }elseif (trim($value->find('th')[0]->innertext) == 'ABN status:') {
            $json_result['ABN_status'] = html_entity_decode(trim($value->find('td')[0]->innertext));
        }elseif (trim($value->find('th')[0]->innertext) == 'Entity type:') {
             $json_result['Entity_type'] = html_entity_decode(trim($value->find('td a')[0]->innertext));
        }elseif (trim($value->find('th')[0]->innertext) == 'Main business location:') {
            $json_result['Main_business_location'] = html_entity_decode(trim($value->find('td div span')[0]->innertext));
        }else{
            $json_result['Goods_Services_Tax'] = html_entity_decode(trim($value->find('td')[0]->innertext));
        }      
}

$table = $html->find('div.container-content table')[0]->innertext;
// get Business name(s)
foreach ($html->find('div.container-content table') as $value) {  
    $result = trim($value->find('caption')[0]->innertext);
    preg_match('#</span>(.*)<span#', $result, $matches);
    if( trim($matches[1]) == 'Business name(s)'){
        foreach($value->find('tbody tr') as $item){
            $business_name = html_entity_decode(trim($item->find('td a')[0]->innertext));           
            $business_name = trim(strip_tags($business_name,'</img>'));
            if(isset($business_name)) {
                $json_result['Business_name'][$business_name][0] = html_entity_decode(trim($item->find('td[2]')[0]->innertext));
                $json_result['Business_name'][$business_name][1] = false;
            }
        }
    }

    if( trim($matches[1]) == 'Trading name(s)'){
        foreach($value->find('tbody tr') as $item){
            //if(isset($business_name)) {
                if(html_entity_decode(trim($item->find('td[2]')[0]->innertext)) == "") continue;
                $trading_name = html_entity_decode(trim($item->find('td')[0]->innertext));
                $json_result['trading_name'][$trading_name][0] = html_entity_decode(trim($item->find('td[2]')[0]->innertext));
                $json_result['trading_name'][$trading_name][1] = false;
            //}
        }
    }
}

// get ASIC registration - ACN or ARBN

foreach ($html->find('div.container-content table') as $value) {  
    $result = trim($value->find('caption')[0]->innertext);
    preg_match('#</span>(.*)#', $result, $matches);
    if( trim($matches[1]) == 'ASIC registration - ACN or ARBN'){
        foreach($value->find('tbody tr') as $item){          
            $ASIC_registration_ACN_or_ARBN = html_entity_decode(trim($item->find('td')[0]->innertext));
            preg_match('#(.*)<a#', $ASIC_registration_ACN_or_ARBN, $matches);
            $json_result['ASIC_registration_ACN_or_ARBN'] = trim($matches[1]);         
        }
    }
}

$json_result = json_encode($json_result);
echo $json_result;