<?php

$record = $_REQUEST["record"];

$quote = new AOS_Quotes();
$quote->retrieve($record);
if(!$quote-id){
    return false;
    die();
}

$folder = $quote->pre_install_photos_c;
$folder = realpath(dirname(__FILE__) . '/../../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folder;
$file_array = scandir($folder);
$return_value = false;
if (count($file_array)) foreach ($file_array as $file){
    if(strpos(strtolower($file), "switchboard") !== false ){
        $return_value = true;
        echo $return_value;
        die();
    }

}

$has_sanden_or_daikin = false;
$sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
$db = DBManagerFactory::getInstance();
$result = $db->query($sql);

while ($row = $db->fetchByAssoc($result)) {
    if(strpos(strtolower($row['name']),"sanden") || strpos(strtolower($row['name']),"daikin") ){
        $has_sanden_or_daikin = true;
    }
}
if($has_sanden_or_daikin) {
    echo false;
} else {
    echo true;
}

die;