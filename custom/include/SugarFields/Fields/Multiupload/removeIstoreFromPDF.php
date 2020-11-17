<?php
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

use setasign\Fpdi\Fpdi;
require_once('custom/modules/AOS_Invoices/text/fpdf.php');
require_once('custom/modules/AOS_Invoices/text/src/autoload.php');

ob_end_clean();
ob_start();
$folderID = $_REQUEST['folder_id'];
//thienpb
$result =  getIstoreExisted($folderID);
if($result['index'] == 0)die;
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($result['url']);
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    if($pageNo == $result['index'])continue;
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageNo);
    $size = $pdf->getTemplateSize($templateId);
    $pdf->useTemplate($templateId);
}
unlink($result['url']);
$pdf->Output($result['url'], 'F');

ob_end_flush(); 

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

        foreach($fileArray as $file){
            if(strpos(mime_content_type($htmlPath.'/'.$file),"image/") !== false){

                $fileNames = explode(".",$file,-1);
                $index = (int)substr($fileNames[count($fileNames)-1],-2,strlen($fileNames[count($fileNames)-1]));
                if($index < ((int)($pageCount/2)-2)) continue;

                $check = uploadToApi($htmlPath.'/'.$file);
                if($check == 'ok'){ 
                    break;
                }
            }
        }
        rrmdir($htmlPath);
        return array("index"=>$index,"url"=>$filePDF);
    }catch (Exception $ex) {
        print_r($ex);
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
                if(strpos(strtolower($pareValue['ParsedText']),"istore") !== false){
                    return 'ok';
                }
            }
        }
    } catch(Exception $err) {
        header('HTTP/1.0 403 Forbidden');
        echo $err->getMessage();
    }
}