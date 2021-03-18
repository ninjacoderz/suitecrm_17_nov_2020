<?php  

$files = $_POST['files'];
$module = $_POST['module'];
$module_id = $_POST['module_id'];
$installation_pictures_c = $_POST['installation_pictures_c'];

$url = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$installation_pictures_c.'/';
$array_files = [];
foreach ($files as $key=>$value) {
    $fullname = '';
    $fullname = $_SERVER['DOCUMENT_ROOT'].$url.$value;
    if (mime_content_type($fullname) == "image/jpeg" || mime_content_type($fullname) == "image/png" ) {
        $array_files[$url.$value] = filectime($fullname);
    }
}
arsort($array_files);
echo key($array_files);
die();
