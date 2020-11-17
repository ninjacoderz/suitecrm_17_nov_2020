<?php
array_push($job_strings, 'custom_autouploadfolderuploadtoaws');

function custom_autouploadfolderuploadtoaws(){
    date_default_timezone_set('Australia/Sydney');

    $AWS_ACCESS_KEY_ID = 'AKIAJG53TQTXLTGRNAVA';
    $AWS_SECRET_ACCESS_KEY = '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';

    global $sugar_config;

    $folder = $sugar_config['upload_dir'];

    $file_array = scandir($folder);
    $file_array = array_diff($file_array, array('.', '..'));
    foreach($file_array as $file){
        $source_file =  $folder.$file;
        $modified = filemtime($source_file);
        if(strtotime('-25 hours') <= $modified){
            file_put_contents('logs_folder_upload.txt','/var/www/suitecrm/upload/'.$file.PHP_EOL , FILE_APPEND | LOCK_EX);

            //echo 'AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp '.$source_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$file;
            shell_exec('AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp /var/www/suitecrm/upload/'.$file.' s3://upload-bk/'.$file);
        }
    }
}

?>
