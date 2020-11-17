<?php
global $timedate;
$timezone = $timedate->getInstance()->userTimezone();
date_default_timezone_set($timezone);

$record_id =  $_REQUEST['record_id'];
$module = $_REQUEST['module'];

if($module == 'AOS_Quotes'){
    $bean =  new AOS_Quotes();
    $bean->retrieve($record_id); 
    $folder_install = $bean->pre_install_photos_c;
}else{
    $bean =  new Lead();
    $bean->retrieve($record_id); 
    $folder_install = $bean->installation_pictures_c;
}

$date_last;
if($folder_install) {
    $forder  = dirname(__FILE__)."/server/php/files/". $folder_install."/";
    $allFiles = scandir($forder);
    if($allFiles !== false){
        $files = array_diff($allFiles, array('.', '..'));
        if(count($files) > 0){
            $str_files = implode(",",$files);
            if(stripos($str_files,"design_") !== false || stripos($str_files,"quote_") !== false){
                $file_timestamp = filemtime($forder);
                if(!isset($date_last) || ($file_timestamp > $date_last)) {
                    $date_last = $file_timestamp;
                }
            }
        }   
    }     
}
if(empty($date_last)) {
    echo 'Not Data';
}else {
    echo(date("d/m/Y h:i:s",$date_last));
}
