<?php
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
require_once(dirname(__FILE__).'/simple_html_dom.php');

global $timedate;
$timezone = $timedate->getInstance()->userTimezone();
date_default_timezone_set($timezone);

$tmpfname = dirname(__FILE__).'/cookiesolargain.txt';
$username = "matthew.wright";
$password =  "MW@pure733";

$quote_solorgain = urldecode($_GET['quote_solorgain']);
$type = urldecode($_GET['type']);

$module = urldecode($_GET['module']);
$beanID = urldecode($_GET['record_id']);

if($module == "AOS_Quotes"){
    $bean =  new AOS_Quotes();
    $bean =  $bean->retrieve($beanID);
    $generate_ID = $bean->pre_install_photos_c;
    if($type == 'quote'){
        $quote_solorgain = $bean->solargain_quote_number_c;
    }else{
        $quote_solorgain = $bean->solargain_tesla_quote_number_c;
    }
}else if($module == "AOS_Invoices"){
    $bean =  new AOS_Invoices();
    $bean =  $bean->retrieve($beanID);
    $generate_ID = $bean->installation_pictures_c;
    $quote_solorgain = $bean->solargain_invoices_number_c;
}else{
    $bean =  new Lead();
    $bean =  $bean->retrieve($beanID);
    $generate_ID = $bean->installation_pictures_c;
}

$url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_solorgain;
//set the url, number of POST vars, POST data
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_solorgain,
        "Cache-Control: max-age=0"
    )
);
$result = curl_exec($curl);
curl_close ($curl);

$decode_result = json_decode($result,true);
//Thienpb code for change account if download false
if(!isset($decode_result['ID'])){
    $username = 'paul.szuster@solargain.com.au';
    $password = 'WalkingElephant#256';
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_solorgain;
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: 	gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_solorgain,
            "Cache-Control: max-age=0"
        )
    );
    $result = curl_exec($curl);
    curl_close ($curl);

}
//END

//VUT - S - check field to build pdf
$data = json_decode($result,true);
$install =  $data['Install'];
$state_arr = [
                "ACT"   => false,
                "NSW"   => false,
                "NT"    => false,
                "SA"    => false,
                "TAS"   => false,
                "QLD"   => false,
                "VIC"   => false,
                "WA"    => false   
            ];

if (empty($install['EnergyRetailer'])) {
    $install['EnergyRetailer'] = array_merge(array (
        "ID"    => 36,
        "Name"  => "Powershop"),
        $state_arr
    );
}

if (empty($install['EnergyPlan'])) {
    $install['EnergyPlan'] = array_merge(array(
        "ID"    => 51,
        "Name"  => "Powershop",
        "IsDefault"         => false,
        "DailySupplyCharge" => 0,
        "FlatRateTariff"    => 0,
        "FeedInTariff"      => 0,
        "IsActive"          => false,
        "EnergyRetailerID"  => 0), 
        $state_arr
    );
}

if (empty($install['NetworkOperator'])) {
    $install['NetworkOperator'] = array_merge(array(
        "ID"    => 4,
        "Name"  => "Citipower"), 
        $state_arr
    );
}
if (empty($install['UsageUnitID'])) {
    $install['UsageUnitID'] = 2;
}
if (empty($install['SummerDailyUsage'])) {
    $install['SummerDailyUsage'] = 20;
}
if (empty($install['WinterDailyUsage'])) {
    $install['WinterDailyUsage'] = 20;
}
if (empty($install['DayConsumption'])) {
    $install['DayConsumption'] = 20;
}

$data_change = json_encode($install);

$url = 'https://crm.solargain.com.au/APIv2/installs';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_change);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($data_change),
        "Origin: https://crm.solargain.com.au",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_solorgain,
    )
);
$result = curl_exec($curl);
curl_close($curl);

$url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_solorgain;
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_solorgain,
        "Cache-Control: max-age=0"
    )
);

$result = curl_exec($curl);
curl_close($curl);

//END

$curl = curl_init();
$url = "https://crm.solargain.com.au/APIv2/quotes/";
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $result);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($result),
        "Origin: https://crm.solargain.com.au",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_solorgain,
    )
);
$result = curl_exec($curl);
curl_close ($ch);

$url = 'https://crm.solargain.com.au/APIv2/quotes/' .$quote_solorgain .'/pdf?random=';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
$headers[] = "Upgrade-Insecure-Requests: 1";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en-US,en;q=0.9";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close ($ch);

$decode_result = json_decode($result,true);

if($bean->id !=''){
    $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;

    if(!file_exists ( $folder )) {
        mkdir($folder);
    }
    //delete file before get
    $files = glob($folder.'/*'); //get all file names
    foreach($files as $quote_file_name){
        if($type == 'quote'){
            if(strpos($quote_file_name,'Quote_#') !== false){
                unlink($quote_file_name); //delete file
            }
        }
    }

    $dateAUS = date('d_M_Y', time());
    //save pdf file
    if($type == 'quote'){
        $file = $folder.'/Quote_#'.$quote_solorgain ."_" .$dateAUS .".pdf";
    }else{
        $file = $folder.'/Tesla_Quote_#'.$quote_solorgain ."_" .$dateAUS .".pdf";
    }
    if($file!=''){
        if(!file_exists ($file)) {
            $check_file = 'no';
        }
        file_put_contents($file,base64_decode($decode_result['Data']));
        $result = array(
            'url_file'=>$file,
            'pdf_file' => 'Quote_#'.$quote_solorgain .'_' .$dateAUS .'.pdf',
            'name_file' => $quote_solorgain ."_" .$dateAUS,
            'generate_ID' => $generate_ID,
            'check_file' => $check_file,
        );
        // if(isset($_REQUEST['preview'])){
        //     echo json_encode(array("file_name"=>$decode_result['Filename'],"pdf_content"=>$decode_result['Data']));
        //     die;
        // }else{
            echo json_encode($result);
        // }
    }else{
        echo 'error';
    }
}


/// new logic
use setasign\Fpdi\Fpdi;

if (!isset($_REQUEST['preview'])) {
    require_once('custom/modules/AOS_Invoices/text/fpdf.php');
    require_once('custom/modules/AOS_Invoices/text/src/autoload.php');
    ob_end_clean();
    ob_start();
    $folderID = $_REQUEST['folder_id'];
    //thienpb
    $result =  getIstoreExisted($folderID);
    if(count($result['list_index']) == 0)die;
    $pdf = new Fpdi();
    $pageCount = $pdf->setSourceFile($result['url']);
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        if(in_array($pageNo,$result['list_index']))continue;
        $pdf->AddPage();
        $templateId = $pdf->importPage($pageNo);
        $size = $pdf->getTemplateSize($templateId);
        $pdf->useTemplate($templateId);
    }
    unlink($result['url']);
    $pdf->Output($result['url'], 'F');

    ob_end_flush(); 
}
function getIstoreExisted($folderID){
    try {
        $htmlPath = dirname(__FILE__)."/server/php/html/".$folderID;
        if(!file_exists ( $htmlPath )) {
            mkdir($htmlPath);
        }

        $filesPath = dirname(__FILE__)."/server/php/files/".$folderID;
        $files = scandir($filesPath);
        $files = array_diff($files, array('.', '..'));
        $filePDF = '';
        foreach($files as $file){
            if(strpos($file,".pdf") !== false){
                if(strpos($file,"Quote_#") !== false){
                    $filePDF = $filesPath.'/'.$file;
                    break;
                }
            }
        }
        exec("which pdftohtml", $output, $returnStatus);
        exec("/bin/pdftohtml -p -c ".escapeshellarg($filePDF)." ".$htmlPath.'/render.pdf'." 2>&1", $output);

        $fileArray = scandir($htmlPath);
        $fileArray = array_diff($fileArray, array('.', '..'));
        $index = 0;

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($filePDF);
        $list_index = [];
        foreach($fileArray as $file){
            if(strpos(mime_content_type($htmlPath.'/'.$file),"image/") !== false){

                $fileNames = explode(".",$file,-1);
                $index = (int)substr($fileNames[count($fileNames)-1],-2,strlen($fileNames[count($fileNames)-1]));
                if($index < ((int)($pageCount/2)-2)) continue;

                list($width, $height) = getimagesize($htmlPath.'/'.$file);
                $newwidth = round($width * 0.7);
                resize($newwidth,$htmlPath.'/'.$file);

                $check = uploadToApi($htmlPath.'/'.$file);
                if(!empty($check)){ 
                    array_push($list_index,$index);
                }

                if(count($list_index) > 1){
                    break;
                }
            }
        }
        rrmdir($htmlPath);
        return array("list_index"=>$list_index,"url"=>$filePDF);
    }catch (Exception $ex) {
        // print_r($ex);
    }
}
function rrmdir($src) {
    if (file_exists($src)) {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
function uploadToApi($target_file){
    require_once('PDFtoText/vendor/autoload.php');
    $fileData = fopen($target_file, 'r+');
    $client = new \GuzzleHttp\Client();
    try {
        $r = $client->request('POST', 'https://api.ocr.space/parse/image',[
            'headers' => ['apiKey' => '1364259c2288957'],
            'multipart' => [
                [
                    'name' => 'file',
                    'filetype' => 'png',
                    'contents' => $fileData
                ]
            ]
        ], ['file' => $fileData]);
        $response =  json_decode($r->getBody(),true);
        if($response['ErrorMessage'] == "") {
            foreach($response['ParsedResults'] as $pareValue) {                
                if(strpos(strtolower($pareValue['ParsedText']),"plenti") !== false){
                    return 'plenti';
                }else if(strpos(strtolower($pareValue['ParsedText']),"istore") !== false){
                    return 'istore';
                }
            }
        }
    } catch(Exception $err) {
        // header('HTTP/1.0 403 Forbidden');
        // echo $err->getMessage();
    }
}

function resize($newWidth, $targetFile) {

    $info = getimagesize($targetFile);
    $mime = $info['mime'];
    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($targetFile);
    list($width, $height) = getimagesize($targetFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile");
}