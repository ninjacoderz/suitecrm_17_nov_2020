<?php
$url_img = $_REQUEST['url_img'];

//Get path image and thumbnail
$parse_url_img = parse_url($url_img);

$array_url_img = explode('/',$parse_url_img['path']);
$array_url_img  = array_slice($array_url_img,6);
$array_url_img_thub = $array_url_img;


//get url image and thumbnail && type image 
$url_img = implode('/',$array_url_img);

$count_ele = count($array_url_img_thub);
$name_img = $array_url_img_thub[$count_ele-1];

array_splice($array_url_img_thub,$count_ele-1,0,'thumbnail'); 
$url_img_thumb = implode('/',$array_url_img_thub);

$type_img = strtolower(pathinfo($url_img_thumb, PATHINFO_EXTENSION));

// convert image rotated
if($type_img == 'png') {
    $original = imagecreatefrompng(dirname(__FILE__) .'/' .$url_img);
    $original_thumb = imagecreatefrompng(dirname(__FILE__) .'/' .$url_img_thumb);
}elseif($type_img == 'jpg' || $type_img == 'jpeg'){
    $original = imagecreatefromjpeg (dirname(__FILE__) .'/' .$url_img);
    $original_thumb = imagecreatefromjpeg (dirname(__FILE__) .'/' .$url_img_thumb);
} elseif($type_img == 'gif') {
    $original = imagecreatefromgif(dirname(__FILE__) .'/' .$url_img);
    $original_thumb = imagecreatefromgif(dirname(__FILE__) .'/' .$url_img_thumb);
}else {
    die();
}


// Rotate the image by 90 degrees
$rotated = imagerotate($original,-90, 0);
$rotated_thumb = imagerotate($original_thumb,-90, 0);

// Save the rotated image
if($type_img == 'png') {
    imagepng($rotated,dirname(__FILE__) .'/' .$url_img);
    imagepng($rotated_thumb,dirname(__FILE__) .'/' .$url_img_thumb);
}elseif($type_img == 'jpg' ||$type_img == 'jpeg'){
    imagejpeg ($rotated,dirname(__FILE__) .'/' .$url_img);
    imagejpeg ($rotated_thumb,dirname(__FILE__) .'/' .$url_img_thumb);
} elseif($type_img == 'gif') {
    imagegif($rotated,dirname(__FILE__) .'/' .$url_img);
    imagegif($rotated_thumb,dirname(__FILE__) .'/' .$url_img_thumb);
}else {
    die();
}

die();