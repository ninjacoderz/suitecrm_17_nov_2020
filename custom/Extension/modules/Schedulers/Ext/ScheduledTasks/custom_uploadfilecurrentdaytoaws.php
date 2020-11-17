<?php
    array_push($job_strings, 'custom_uploadfilecurrentdaytoaws');

    function custom_uploadfilecurrentdaytoaws(){

        date_default_timezone_set('Australia/Sydney');

        $AWS_ACCESS_KEY_ID = 'AKIAJG53TQTXLTGRNAVA';
        $AWS_SECRET_ACCESS_KEY = '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';
    
        $folder = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/server/php/files/';
        $file_array = scandir($folder);
        $file_array = array_diff($file_array, array('.', '..'));
        foreach($file_array as $file){
            $source_file =  $folder.$file;
            if(is_dir($source_file) && $file != 'attachments'){
                $modified = filemtime($source_file);
                if(strtotime('-25 hours') <= $modified){
                    $file_child_array = scandir($source_file);
                    $file_child_array = array_diff($file_child_array, array('.', '..'));
                    foreach($file_child_array as $file_child){
                        $source_child_file = $source_file.'/'.$file_child;
                        if(strtotime('-25 hours') <= filemtime($source_child_file)){
                            file_put_contents('logs_folder_files.txt', $source_child_file.PHP_EOL , FILE_APPEND | LOCK_EX);

                            //echo 'AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp '.$source_child_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$file.'/'.$file_child;
                            shell_exec('AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp /var/www/suitecrm/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$file.'/'.$file_child.' s3://files-bk/'.$file.'/'.$file_child);

                        }
                    }
                }
            }
        }
    }
?>