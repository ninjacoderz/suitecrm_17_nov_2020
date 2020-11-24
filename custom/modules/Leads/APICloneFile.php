<?php

$leadID = $_REQUEST['leadID'];
$quoteID = $_REQUEST['quoteID'];
$method =  $_REQUEST['method'];

switch ($method) {
    case 'clone_file_Lead_to_Quote':
        clone_file_Lead_to_Quote($leadID,$quoteID);
        break;
    case 'clone_file_Quote_to_Lead':
        clone_file_Quote_to_Lead($leadID,$quoteID);
        break;
    default:
        # code...
        break;
}



function clone_file_Lead_to_Quote($leadID,$quoteID){
    $lead = BeanFactory::getBean('Leads', $leadID);
    $quote = BeanFactory::getBean('AOS_Quotes', $quoteID);
    if($lead->id != '' && $quote->id != '' ){
        var_dump( $lead->installation_pictures_c);
        if($lead->installation_pictures_c == ''){
            $lead->installation_pictures_c = APICloneFile_gererate_UUID();
            $lead->save();
        }
        var_dump( $quote->pre_install_photos_c);
        if($quote->pre_install_photos_c == ''){
            $quote->pre_install_photos_c = APICloneFile_gererate_UUID();
            $quote->save();
        }
        var_dump( $quote->pre_install_photos_c);
        $folderID_from  = $lead->installation_pictures_c;
        $folderID_to  = $quote->pre_install_photos_c;
        $preFileName = 'Q'.$quote->number;
        APICloneFile_CloneFileBetweenFolders($folderID_from,$folderID_to,$preFileName);
    }
}

function clone_file_Quote_to_Lead($leadID,$quoteID){
    $lead = BeanFactory::getBean('Leads', $leadID);
    $quote = BeanFactory::getBean('AOS_Quotes', $quoteID);
    if($lead->id != '' && $quote->id != '' ){
        if($lead->installation_pictures_c == ''){
            $lead->installation_pictures_c = APICloneFile_gererate_UUID();
            $lead->save();
        }
        if($quote->pre_install_photos_c == ''){
            $quote->pre_install_photos_c = APICloneFile_gererate_UUID();
            $quote->save();
        }
        $folderID_to  = $lead->installation_pictures_c;
        $folderID_from  = $quote->pre_install_photos_c;
        $preFileName = 'L'.$quote->number;
        APICloneFile_CloneFileBetweenFolders($folderID_from,$folderID_to,$preFileName);
    }
}

function APICloneFile_CloneFileBetweenFolders($folderID_from,$folderID_to,$preFileName){
    //get all file from folder Origin
    $GetFilesFromOrigin = APICloneFile_dirToArray($_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folderID_from.'/') ;
    
    $array_convert_file_name = array(
        'proposed_install_location' => '_Proposed_Install_Location',
        'switchboard' => '_Switchboard',
        'shipping_confirmation' => '_ShippingConfirmation',
        'street_view' => '_Street_View',
        'remittance_advice' => 'Remittance_Advice',
        'Existing_HWS' => '_Old_Existing_HWS',
        'Meter_UpClose' => '_Meter_UpClose',
        'Roof_Pitch' => '_Roof_Pitch',
        'Acceptance' => '_Acceptance',
        'House_Plans' => '_House_Plans',
        'Meter_Box' => '_Meter_Box',
        'Install_Photo' => '_New_Install_Photo'
    );

    foreach($GetFilesFromOrigin as $file_name){

        foreach ($array_convert_file_name as $key => $label_new_file) {
            $condition_change_file = false;
            $array_explode_name =  explode('_',$key);
            // check file in include name in array convert file
            foreach ($array_explode_name as $value_name) {
                if(strpos(strtolower($file_name), strtolower($value_name)) !== false ){
                    $condition_change_file = true;
                }else{
                    $condition_change_file = false;
                }
            }  
            if($condition_change_file){    
                $extension=end(explode(".", $file_name));
                $new_file_name = $preFileName.$label_new_file;
                $inv_file_path = 
                $i = 1;
                $will_rename = $new_file_name;
                $current_file_path = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folderID_to;
                while( !empty(glob($current_file_path.'/'.$will_rename."*"))){
                  $will_rename = $new_file_name.$i;
                  $i++;
                }
               
                $will_rename .= ('.'.$extension);
                $new_file_name = $will_rename; 
                break;
            }else{
                $new_file_name = $file_name;
            }
        }

        $folderName_old  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folderID_from.'/'.$file_name;
        $folderName_new  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folderID_to.'/';
      
        //check exists folder
        if(!file_exists ($folderName_new)) {
            mkdir($folderName_new);
        }
        copy($folderName_old, $folderName_new.$new_file_name);
        APICloneFile_createThumbnail($new_file_name,$folderName_new);
    }   
}

function APICloneFile_createThumbnail($file, $current_file_path) {
    $type = strtolower(substr(strrchr($file, '.'), 1));
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
        if(!file_exists ($current_file_path."/thumbnail/")) {
            mkdir($current_file_path."/thumbnail/");
        }
        $thumb =  $current_file_path."/thumbnail/".$file;
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
            list($w, $h) = getimagesize($current_file_path.'/'. $file);

            $src = $src_func($current_file_path.'/'. $file);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
    } 
}

function APICloneFile_dirToArray($dir) { 
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = APICloneFile_dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}

function APICloneFile_gererate_UUID(){
    mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12);
    return $uuid;
}
