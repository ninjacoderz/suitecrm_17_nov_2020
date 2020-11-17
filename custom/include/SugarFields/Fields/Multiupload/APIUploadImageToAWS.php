<?php

    // $output = shell_exec('AWS_ACCESS_KEY_ID=AKIAJG53TQTXLTGRNAVA AWS_SECRET_ACCESS_KEY=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 aws  s3  cp /var/www/suitecrm/upload_test/icon-twitter.png s3://my-pe-testing/icon-twitter.png');
    // var_dump($output);
    // die();
    //890ini_set("display_errors",1);
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    //?entryPoint=APIUploadImageFromAWS&stage=upload&type=upload&file=000000000000
    $GLOBALS['AWS_ACCESS_KEY_ID'] = 'AKIAJG53TQTXLTGRNAVA';
    $GLOBALS['AWS_SECRET_ACCESS_KEY'] = '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';

    $state      = $_REQUEST['stage'];
    $fileName   = $_REQUEST['file'];
    $folderName = $_REQUEST['folder'];
    $folderRoot  = $_REQUEST['folderRoot'];
    $is_today   = $_REQUEST['is_today'];

    if($folderRoot == 'upload'){
        $GLOBALS['BUCKET_NAME']  = 'upload-bk/';
        $GLOBALS['FOLDER_NAME']  = '/var/www/suitecrm/upload/';
    }else if($folderRoot == 'files'){
        $GLOBALS['BUCKET_NAME']  = 'files-bk/';
        $GLOBALS['FOLDER_NAME']  = '/var/www/suitecrm/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    }else{
        die;
    }

    if($fileName != ''){
        $GLOBALS['RECURSIVE']    = '';
    }else{
        $GLOBALS['RECURSIVE']    = '--recursive';
    }
    
    switch ($state) {
        case 'download':
            //echo 'AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' s3://'.$GLOBALS['BUCKET_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName.' '.$GLOBALS['FOLDER_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName;
            shell_exec('AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' s3://'.$GLOBALS['BUCKET_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName.' '.$GLOBALS['FOLDER_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName);
            break;
        case 'upload':
            upload($folderName,$fileName);
            break;
    }

    function upload($folderName,$fileName){
        if($is_today == 'yes'){
            $source = $GLOBALS['FOLDER_NAME'].$folderName;
            $file_array = scandir($source);
            $file_array = array_diff($file_array, array('.', '..'));
            foreach($file_array as $file){
                $source_file =  $source.$file;
                $modified = filemtime($source_file);
                if(strtotime('-1 day') <= $modified){
                    //echo 'AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' '.$source_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$source_file;
                    shell_exec('AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' '.$source_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$source_file);
                }
            }
        }else{
            if($fileName == '' && $folderName == ''){die;}
            //echo 'AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' '.$GLOBALS['FOLDER_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName.' s3://'.$GLOBALS['BUCKET_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName;
            shell_exec('AWS_ACCESS_KEY_ID='.$GLOBALS['AWS_ACCESS_KEY_ID'].' AWS_SECRET_ACCESS_KEY='.$GLOBALS['AWS_SECRET_ACCESS_KEY'].' aws s3 cp '.$GLOBALS['RECURSIVE'].' '.$GLOBALS['FOLDER_NAME'].(($folderName != '') ? $folderName.'/' : '').$fileName.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.(($folderName != '') ? $folderName.'/' : '').$fileName);
        }
    }
    die();
?>