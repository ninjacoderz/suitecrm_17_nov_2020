<?php
require_once(dirname(__FILE__).'/simple_html_dom.php');
$postcode_num = $_REQUEST['postcode_num'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.sanden-hot-water.com.au/check-your-water-quality?r=588011^&postcode='.$postcode_num);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: www.sanden-hot-water.com.au';
$headers[] = 'Cache-Control: max-age=0';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';
$headers[] = 'Sec-Fetch-Mode: navigate';
$headers[] = 'Sec-Fetch-User: ?1';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
$headers[] = 'Sec-Fetch-Site: none';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
$headers[] = 'Cookie: SSESSd80f296fc16cc75ed84fc64c61a3b8aa=e3wUIs2W9wQwH1pmQkBsRrt0uIhYb7VaC8a5yIvptYM; has_js=1; __utmc=182719780; __utmz=182719780.1568953797.3.2.utmcsr=loc.suitecrm.com^|utmccn=(referral)^|utmcmd=referral^|utmcct=/index.php; __utma=182719780.1764227098.1568945277.1568953797.1568963241.4; __utmt=1; __utmb=182719780.4.10.1568963241';
$headers[] = 'If-None-Match: ^^1568963429^^\"\"';
$headers[] = 'If-Modified-Since: Fri, 20 Sep 2019 07:10:29 +0000';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
$html_check = str_get_html($result);
$content_check = $html_check->find('div[id="water-quality-results"]')[0]->find('div[class="form-block"]')[0]->find('p')[0]->innertext;
$content = trim(preg_replace("/\t|\n/","",$content_check));
echo $content;
?>