<?php
//code remove folder files with modifined time < 3 months
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $FOLDER_NAME  = dirname(__FILE__) .'/server/php/files/';

    $file_array = scandir($FOLDER_NAME);
    $file_array = array_diff($file_array, array('.', '..'));
    foreach($file_array as $file){
        $source = $FOLDER_NAME.$file;
        if(is_dir($source) && $file != 'attachments'){
            $modified = filemtime( $source);
            if($modified <= strtotime('-3 months')){
              rrmdir( $source);
            }
        }
    }
    function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != ".." && $object != "thumbnail") {
              if (filetype($dir."/".$object) == "dir") 
                 rrmdir($dir."/".$object); 
              else unlink   ($dir."/".$object);
            }
          }
          reset($objects);
          rmdir($dir);
        }
    }
    die;
?>