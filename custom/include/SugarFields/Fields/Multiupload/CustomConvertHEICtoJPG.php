<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

$filePath = $_REQUEST['filePath'];
$fileInfo = explode("/",$filePath);

$fileName = $fileInfo[count($fileInfo) -1];
$folderID = $fileInfo[count($fileInfo) -2];

$destination_dir = dirname(__FILE__) ."/server/php/files/".$folderID;

$data = file_get_contents('php://input');
$source_dir = $destination_dir.'/'.$fileName;

file_put_contents($source_dir,$data);
rename($source_dir,str_replace([".heic",".HEIC"],".jpg",$source_dir));

create_thumbnail(str_replace([".heic",".HEIC"],".jpg",$source_dir),str_replace([".heic",".HEIC"],".jpg",$fileName),$destination_dir);
function create_thumbnail($source,$file_name,$path_save_file){
    if (exif_imagetype($source) == 2) {
        $type = 'jpeg';
    }else if(exif_imagetype($source) == 3){
        $type = 'png';
    }else if(exif_imagetype($source) == 1){
        $type = 'gif';
    } else {
        $type = 'jpeg';
    }
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
        if(!file_exists ($path_save_file."/thumbnail/")) {
        mkdir($path_save_file."/thumbnail/");
        }
        $thumb =  $path_save_file."/thumbnail/".$file_name;
        switch ($type) {
        case 'jpg': // Both regular and progressive jpegs
        case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                $options['jpeg_quality'] : 75;
                break;
        case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
        case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                $options['png_quality'] : 9;
                break;
        default: $typeok = FALSE; break;
        }
        if ($typeok){
            list($w, $h) = getimagesize($source);

            $src = $src_func($source);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
    }         
}
fclose($fp);
