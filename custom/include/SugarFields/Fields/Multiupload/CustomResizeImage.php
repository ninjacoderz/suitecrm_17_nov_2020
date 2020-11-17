<?php
$url_img = $_REQUEST['url_img'];
$percent = 0.85;
//Get path image
$parse_url_img = parse_url($url_img);
$array_url_img = explode('/',$parse_url_img['path']);
$array_url_img  = array_slice($array_url_img,6);

//get url image && type image 
$url_img = implode('/',$array_url_img);


// Get new sizes
list($width, $height) = getimagesize(dirname(__FILE__) .'/' .$url_img);
$newwidth = round($width * $percent);

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
// convert byte to MB,KB,B,TB,GB
function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}


if($newwidth >= 800){
    resize($newwidth,dirname(__FILE__) .'/' .$url_img,dirname(__FILE__) .'/' .$url_img);
    $size_image_new =filesize (dirname(__FILE__) .'/' .$url_img);
    echo FileSizeConvert($size_image_new);
} else {
    echo 'ERROR';
}