<?php
//thienpb code 
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');
$uxsurname = $_REQUEST['uxsurname'];

//get data
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://consumer.etoolbox.pic.vic.gov.au/_layouts/cc/pic_validatepp.aspx");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Upgrade-Insecure-Requests: 1";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en,vi;q=0.9";
$headers[] = "Cookie: __utmc=125409560; __utmz=125409560.1540540787.1.1.utmcsr=(direct)^|utmccn=(direct)^|utmcmd=(none); SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; __utma=125409560.165631979.1540540787.1540544999.1540548462.3; __utmt=1; __utmb=125409560.3.10.1540548462";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close ($ch);
$html = str_get_html($result);

$data_string = array(   "__SPSCEditMenu" =>  $html->find('input[id="__SPSCEditMenu"]',0)->value,
                        "MSOWebPartPage_PostbackSource" =>  $html->find('input[id="MSOWebPartPage_PostbackSource"]',0)->value,
                        "MSOTlPn_SelectedWpId" =>  $html->find('input[id="MSOTlPn_SelectedWpId"]',0)->value,
                        "MSOTlPn_View" =>  $html->find('input[id="MSOTlPn_View"]',0)->value,
                        "MSOTlPn_ShowSettings" =>  $html->find('input[id="MSOTlPn_ShowSettings"]',0)->value,
                        "MSOGallery_SelectedLibrary" =>  $html->find('input[id="MSOGallery_SelectedLibrary"]',0)->value,
                        "MSOGallery_FilterString" =>  $html->find('input[id="MSOGallery_FilterString"]',0)->value,
                        "MSOTlPn_Button" =>  $html->find('input[id="MSOTlPn_Button"]',0)->value,
                        "MSOAuthoringConsole_FormContext" =>  $html->find('input[id="MSOAuthoringConsole_FormContext"]',0)->value,
                        "MSOAC_EditDuringWorkflow" =>  $html->find('input[id="MSOAC_EditDuringWorkflow"]',0)->value,
                        "MSOSPWebPartManager_DisplayModeName" =>  $html->find('input[id="MSOSPWebPartManager_DisplayModeName"]',0)->value,
                        "__EVENTTARGET" =>  $html->find('input[id="__EVENTTARGET"]',0)->value,
                        "__EVENTARGUMENT" =>  $html->find('input[id="__EVENTARGUMENT"]',0)->value,
                        "MSOWebPartPage_Shared" =>  $html->find('input[id="MSOWebPartPage_Shared"]',0)->value,
                        "MSOLayout_LayoutChanges" =>  $html->find('input[id="MSOLayout_LayoutChanges"]',0)->value,
                        "MSOLayout_InDesignMode" =>  $html->find('input[id="MSOLayout_InDesignMode"]',0)->value,
                        "MSOSPWebPartManager_OldDisplayModeName" =>  $html->find('input[id="MSOSPWebPartManager_OldDisplayModeName"]',0)->value,
                        "MSOSPWebPartManager_StartWebPartEditingName" =>  $html->find('input[id="MSOSPWebPartManager_StartWebPartEditingName"]',0)->value,
                        "__VIEWSTATE" =>  $html->find('input[id="__VIEWSTATE"]',0)->value,
                        "__VIEWSTATEGENERATOR" =>  $html->find('input[id="__VIEWSTATEGENERATOR"]',0)->value,
                        "__EVENTVALIDATION" =>  $html->find('input[id="__EVENTVALIDATION"]',0)->value,
                        "ctl00\$PlaceHolderMain\$uxPPNo" =>  '',
                        "ctl00\$PlaceHolderMain\$uxSurname" => $uxsurname,
                        "ctl00\$PlaceHolderMain\$uxSearch" =>  'Search');

//validate
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://consumer.etoolbox.pic.vic.gov.au/_layouts/cc/pic_validatepp.aspx");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Origin: https://consumer.etoolbox.pic.vic.gov.au";
$headers[] = "Upgrade-Insecure-Requests: 1";
$headers[] = "Content-Type: application/x-www-form-urlencoded";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
$headers[] = "Referer: https://consumer.etoolbox.pic.vic.gov.au/_layouts/cc/pic_validatepp.aspx";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en,vi;q=0.9";
$headers[] = "Content-Length: ".strlen(http_build_query($data_string));
$headers[] = "Cookie: __utmc=125409560; __utmz=125409560.1540540787.1.1.utmcsr=(direct)^|utmccn=(direct)^|utmcmd=(none); SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; __utma=125409560.165631979.1540540787.1540544999.1540548462.3; __utmt=1; __utmb=125409560.1.10.1540548462";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close ($ch);

$html = str_get_html($result);

$return = '';
$table_return = $html->find("table[id='ctl00_PlaceHolderMain_tblRegSummary']",0)->innertext;
$table_return =  str_replace("Holds a","\nHolds a",$table_return);
$table_return =  str_replace("<li>","<li>\n\t- ",$table_return);
$table_return =  str_replace("&amp;","&",$table_return);
$return = trim(strip_tags($table_return));
echo $return;