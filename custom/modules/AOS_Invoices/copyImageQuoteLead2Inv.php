<?php 
$folder = $_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/server/php/files/';

$invoice_id = $_GET['record_id']; 

$invoice = new AOS_Invoices();
$invoice->retrieve($invoice_id); 
if ($invoice->id != '') {
    $order_by = 'number DESC';
    $where = "aos_quotes.number = '{$invoice->quote_number}'";
    $folder_inv = $folder.$invoice->installation_pictures_c; //$destination
    $files_inv = dirToArray($folder_inv);
    $quote = $invoice->get_linked_beans('aos_quotes_aos_invoices','AOS_Quotes', $order_by, 0, -1, 0, $where)[0];
    if ($quote->id != '') {
        $folder_quote =  $folder.$quote->pre_install_photos_c; //$source $quote->number
        $files_quote = dirToArray($folder_quote);
        $quote_inv = array_diff($files_quote,$files_inv);
        $files_quote2Invoice = checkHaveFile($quote_inv, $files_inv, 'Q'.$quote->number);
        if (count($files_quote2Invoice['forCopy']) > 0) {
            createFileSymLink($files_quote2Invoice['forCopy'], $folder_quote, $folder_inv);
        } 
        $lead = $quote->get_linked_beans('aos_quotes_leads_2','Leads', $order_by, 0, -1, 0, '')[0];
        if ($lead->id != '') {
            $folder_lead =  $folder.$lead->installation_pictures_c; //$source $lead->number
            $files_lead = dirToArray($folder_lead);
            $lead_inv = array_diff($files_lead,$files_inv);
            $file_lead2Invoice = checkHaveFile($lead_inv, $files_quote2Invoice['forCheck'], 'L'.$lead->number);
            if (count($file_lead2Invoice['forCopy']) > 0) {
                createFileSymLink($file_lead2Invoice['forCopy'], $folder_lead, $folder_inv);
            }
            echo 'Copied Quote and Lead';
        } else {
            $lead_id = $quote->leads_aos_quotes_1leads_ida;
            if(!empty($lead_id)){
                $lead = new Lead();
                $lead->retrieve($lead_id);
                if($lead->id){
                    $folder_lead =  $folder.$lead->installation_pictures_c; //$source $lead->number
                    $files_lead = dirToArray($folder_lead);
                    $lead_inv = array_diff($files_lead,$files_inv);
                    $file_lead2Invoice = checkHaveFile($lead_inv, $files_quote2Invoice['forCheck'], 'L'.$lead->number);
                    if (count($file_lead2Invoice['forCopy']) > 0) {
                        createFileSymLink($file_lead2Invoice['forCopy'], $folder_lead, $folder_inv);
                    } 
                }
            }else{
                echo 'Copied Quote - No Lead';
            }
        }
    } else {
        echo 'Have Invoice - No Quote';
    }
} else {
    echo 'Not Invoice\'s id';
}
die();


//FUNCTION DECLEARE

//function create thumbnail from source
function create_thumbnail($source,$file_name,$path_save_file){
    //$type = strtolower(substr(strrchr($file_name, '.'), 1));
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

function dirToArray($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}

/**
 * VUT
 * @param Array $folder_input
 * @param Array $folder_check
 * @param String $number For replace
 */
function checkHaveFile($folder_input, $folder_check, $number) {
    $forCopy = [];
    $forCheck = [];
    foreach ($folder_input as $key => $file_name) {
        $new_name = '';
        $new_name = str_replace($number.'_', '', $file_name);
        $new_name = str_replace($number, '', $new_name);
        if (!in_array($new_name, $folder_check)) {
            array_push($forCopy, $file_name);
            array_push($forCheck, $new_name);
        }
    }
    $res = array(
        'forCopy' => $forCopy,
        'forCheck' => $forCheck,
    );
    return $res;
}

/**
 * VUT
 * @param Array $array_files copy file
 * @param Path $source 
 * @param Path $destination
 */
function createFileSymLink($array_files, $source, $destination) {
    foreach ($array_files as $key => $file_name) {
        if (exif_imagetype($source.'/'.$file_name)) {
            create_thumbnail($source.'/'.$file_name,$file_name,$destination);
        }
        symlink($source.'/'.$file_name, $destination.'/'.$file_name);
    }
}