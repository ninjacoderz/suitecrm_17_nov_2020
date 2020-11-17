<?php

if($_REQUEST['last_request'] == 'yes') {
    global $sugar_config;
    $files = glob($sugar_config['upload_dir'] .'/BulkActionInvoice/*'); 
    foreach($files as $file){ 
        if(is_file($file))
        unlink($file); 
    }   
}

if($_REQUEST['send_get_list'] == 'yes'){
    global  $mod_strings;

    $uid = $_REQUEST['uid'];

    $mod_strings['LBL_PDF_NAME'] = "Invoice";
    $_REQUEST['task'] = 'pdf';
    $_REQUEST['uid'] = $uid;
    $_REQUEST['module'] = "AOS_Invoices";
    $_REQUEST['templateID'] = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
    require_once('modules/AOS_PDF_Templates/generatePdf.php');  
}

if($_REQUEST['last_file'] == 'yes'){
    global $sugar_config;

    $zip = new ZipArchive();
    $filename = $sugar_config['upload_dir'] .'/BulkActionInvoice/InvoicePDF.zip';

    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
        die();
    }
    $files = glob($sugar_config['upload_dir'] .'/BulkActionInvoice/*');
    foreach($files as $file){
        if(is_file($file)){
            $new_name = basename($file);
            $zip->addFile($file,$new_name);
        }
    }
    $zip->close();
    echo $filename;
}