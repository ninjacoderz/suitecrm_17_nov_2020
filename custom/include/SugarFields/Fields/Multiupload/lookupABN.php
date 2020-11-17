<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');
$SearchText = $_REQUEST['text_search'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://abr.business.gov.au/Search/ResultsActive?SearchText=".urlencode($SearchText));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Upgrade-Insecure-Requests: 1";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36";
$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
$headers[] = "Referer: https://abr.business.gov.au/Search/ResultsActive?SearchText=".urlencode($SearchText);
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "Cookie: _ga=GA1.3.1562672961.1541055058; _gid=GA1.3.1643016219.1541055058; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close ($ch);
$html = str_get_html($result);
$ABN_list='';
for($i=0;$i<5;$i++){
    $data = explode(',',$html->find("input[name='Results.NameItems[".$i."].Compressed']",0)->value);
    $ABN_list .= $data[1].' - '.html_entity_decode($data[5]).',';
}
echo $ABN_list;

