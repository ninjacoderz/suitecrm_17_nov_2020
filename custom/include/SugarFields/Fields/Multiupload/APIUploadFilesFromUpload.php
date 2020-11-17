<?php

date_default_timezone_set('Australia/Sydney');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

$AWS_ACCESS_KEY_ID = 'AKIAJG53TQTXLTGRNAVA';
$AWS_SECRET_ACCESS_KEY = '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';
$BUCKET_NAME = 'upload-bk';

global $sugar_config;
$folder = $sugar_config['upload_dir'];
$time = $_REQUEST['time'];
$file_array = scandir($folder);
$file_array = array_diff($file_array, array('.', '..'));

foreach($file_array as $file){
    $source_file =  $folder.$file;
    $modified = filemtime($source_file);
    if(strtotime($time) <= $modified && date('d-m-Y', strtotime($time)) != '01-01-1970'){
        //echo 'AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp '.$source_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$file;
        $output = shell_exec('AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp /var/www/suitecrm/upload/'.$file.' s3://'.$BUCKET_NAME.'/'.$file);
        echo $output;
    }
}