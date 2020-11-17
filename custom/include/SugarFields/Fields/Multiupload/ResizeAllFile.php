<?php
$id_folder_file = $_REQUEST['id_folder_file'];
$path_folder = dirname(__FILE__) .'/server/php/files/' .$_REQUEST['id_folder_file'] .'/';
$files = scandir($path_folder);
foreach($files as  $value){
    if($value != '.' && $value != '..' && $value != 'thumbnail'){
        $path_parts = pathinfo($path_folder.$value);
        $type = strtolower($path_parts['extension']);
        if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png' ){
            list($width, $height) = getimagesize($path_folder.$value);
            $newWidth = round($width * 0.8);
            $targetFile = $path_folder.$value;
            $originalFile = $path_folder.$value;
            if($newWidth > 800){
                resize($newWidth , $targetFile, $originalFile);
            }
        }
    }
}
echo 'success';
function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
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

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile");
}

