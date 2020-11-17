<?php
$folderName = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
$record_id = $_REQUEST["installation_id"];
$dir = $folderName.$record_id;
$array_photo = [];
$get_all_photo = dirToArray($dir);
foreach ($get_all_photo as $photo_exist) {
        if( strpos($photo_exist,"VBA")){
            $new_name = str_replace("VBA","PCOC",$photo_exist);
            rename($dir.'/'.$photo_exist,$dir.'/'.$new_name);
            rename($dir."/thumbnail/".$photo_exist, $dir."/thumbnail/". $new_name);
        }
    
}
$new_all_photo = dirToArray($dir);
foreach ($new_all_photo as $photo_new) {
    $array_photo[] = array(
        'name'=> $photo_new,
        'deleteUrl'=> "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/index.php?file=".$photo_new,
        'thumbnailUrl'=> "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$record_id."/thumnail/".$photo_new,
        'url'=> "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$record_id."/".$photo_new,
    );
}
function dirToArray($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".","..","thumbnail"))) 
       {  
             $result[] = $value; 
       } 
    }
    return $result; 
}
echo json_encode($array_photo);

?>