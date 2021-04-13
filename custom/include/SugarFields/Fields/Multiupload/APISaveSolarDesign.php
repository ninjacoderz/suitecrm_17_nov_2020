<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
    header('Access-Control-Allow-Headers: Content-type');

    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    date_default_timezone_set('Australia/Melbourne');

    // .:nhantv:. Check Content-type
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if ($contentType === "application/json") {
        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        // If json_decode success, the JSON is valid.
        if(is_array($decoded)) {
            $quote_id = $decoded['quote_id'];
            $design_json = json_encode($decoded['design_json']);
            $type = $decoded['type'];
            $status = $decoded['status'];
            $dataURLs = $decoded['dataURL'];
            $dataBgURL = base64_decode($decoded['dataBgURL']);
        }
    } else {
        $quote_id = $_REQUEST['quote_id'];
        $design_json = html_entity_decode($_REQUEST['design_json']);
        $type = $_REQUEST['type'];
        $status = $_REQUEST['status'];
    }

    $quote  = new AOS_Quotes();
    $quote->retrieve($quote_id);
    $quoteType = '';
    $designType ='';
        if(empty($quote->id)) echo json_encode(array());
    $dataReturn = [];

    if($type == 'save'){
        if ($quote->quote_type_c == 'quote_type_solar'){
            $quoteType = 'Solar';
            $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
            $files = scandir($path, SCANDIR_SORT_DESCENDING);

            // .:nhantv:. Delete old Design image
            delete_file($files, $path, '_Design_Proposed_Install_Location');
            delete_file($files, $path.'/thumbnail', '_Design_Proposed_Install_Location');

            // .:nhantv:. Save rendered image
            if(!empty($dataURLs) || count($dataURLs) > 0){
                foreach($dataURLs as &$dataURL){
                    createImage($quote
                        , base64_decode($dataURL['base64'])
                        , ''
                        , $designType
                        , $quoteType
                        , $status
                        , false
                        , $dataURL['name']);
                }
            }
            // .:nhantv:. Save background
            if (isset($dataBgURL) && $dataBgURL != ''){
                createImage($quote, $dataBgURL, '', $designType, $quoteType, $status, true, '');
                // get lastet image background URL
                $bgURLPath = get_latest_image_name($quote, '_Design_Background');
                $decoded['design_json']['mapDesign']['imagePath'] = $bgURLPath;
                $design_json = json_encode($decoded['design_json']);
            }
            // .:nhantv:. Empty dataURL
            if ($dataURL == '' || (isset($dataBgURL) && $dataBgURL == '')) {
                $dataReturn['message'] = 'SuiteCRM: Unable to create Image from EMPTY DataURL';
            }
        }

        // .:nhantv:. Save Quote design
        $quote->design_tool_json_c = $design_json;
        $quote->save();
    }

    $dataReturn['quote_id'] = $quote->id;
    $dataReturn['pre_install_photos_c'] = $quote->pre_install_photos_c;
    $dataReturn['quote_number'] = $quote->number;

    echo json_encode($dataReturn);

    /** Delete all file that match $strpos */
    function delete_file($files, $path, $strpos) {
        foreach($files as $file){
            if(is_file($path.'/'.$file) && strpos($file, $strpos) !== false){
                unlink($path.'/'.$file);
            }
        }
    }

    /** Generate file name follow pattern: Q<quoteNumber>_<designType>_<?inputName>_<strpos><timeString>.<?png> */
    function file_name_builder($path, $quote, $quoteType, $designType, $strpos, $inputName){
        $strBuilder = '';
        $time_string = strftime("%d%b%Y_%H%M",time());
        
        // Case source: append $path
        if($path !== ''){
            $strBuilder .= $path.'/';
        }
        // Append Quote number, quote type, design type 
        $strBuilder .= 'Q'.$quote->number.'_'.$quoteType.$designType;
        // Case input name
        if($inputName !== ''){
            $strBuilder .= '_'.str_replace(' ', '_', $inputName);
        }
        // Append strpos, time string
        $strBuilder .= $strpos.$time_string;
        // Case source: append PNG
        if($path !== ''){
            $strBuilder .= '.png';
        }

        return $strBuilder;
    }

    /** Get latest image name that match $strpos */
    function get_latest_image_name($quote, $strpos){
        $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
        $files = scandir($path,SCANDIR_SORT_DESCENDING);
        foreach($files as $file){
            if(is_file($path.'/'.$file) && strpos($file, $strpos) !== false){
                return pathinfo($path.'/'.$file)["basename"];
            }
        }
    }

    /** Create image */
    function createImage($quote, $dataURL, $key, $designType, $quoteType, $status, $isBackGround, $inputName){
        if($dataURL != ''){
            $img = preg_replace('/data:image\/(.*?);base64,/', '', $dataURL);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
            $files = scandir($path,SCANDIR_SORT_DESCENDING);
            $filename = '';
            $strpos = $isBackGround ? '_Design_Background' : '_Design_Proposed_Install_Location';

            // .:nhantv:. Delete old file. Exclude: solar design tab
            if($inputName === ''){
                delete_file($files, $path, $strpos);
            }

            if($filename == ''){
                $filename = file_name_builder('', $quote, $quoteType, $designType, $strpos, $inputName);
                $source = file_name_builder($path, $quote, $quoteType, $designType, $strpos, $inputName);
            }
            
            $success = file_put_contents($source, $data);

            create_thumbnail($path, $filename, $key, $quoteType, $strpos, $inputName);
        }
    }

    function create_thumbnail($inputPath, $fullname, $key, $quoteType, $strpos, $inputName){
        $path = $inputPath.'/';
        $filename = $fullname;
        $source = $path.$filename.'.png';

        $new_name='';
        $type ='';
        if (exif_imagetype($source) == 2) {
            $type = 'jpeg';
            $new_name = $path.$filename.'.jpeg';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 3){
            $type = 'png';
            $new_name = $path.$filename.'.png';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 1){
            $type = 'gif';
            $new_name = $path.$filename.'.gif';
            rename( $source,$new_name);
        } else {
            return;
        }

        //create thumbnail
        if($type == 'gif' || $type == 'jpeg' || $type == 'png') {
            //create thumbnail
            if(!file_exists ($path."/thumbnail/")) {
                mkdir($path."/thumbnail/");
            }
            
            // delete old file
            if($inputName === ''){
                $files = scandir($path."/thumbnail",SCANDIR_SORT_DESCENDING);
                delete_file($files, $path."/thumbnail", $strpos);
            }

            $typeok = TRUE;
            $thumb =  $path."/thumbnail/".$filename.'.'.$type;
            switch ($type) {
                case 'jpeg':
                    $src_func = 'imagecreatefromjpeg';
                    $write_func = 'imagejpeg';
                    $thumb =  $path."/thumbnail/".$filename.'.jpeg';
                    $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                    break;
                case 'png':
                    $src_func = 'imagecreatefrompng';
                    $write_func = 'imagepng';
                    $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                    break;
                case 'gif':
                    $src_func = 'imagecreatefromgif';
                    $write_func = 'imagegif';
                    $image_quality = null;
                    break;
                default: $typeok = FALSE; break;
            }
            if($typeok){
                list($w, $h) = getimagesize($new_name);

                $src = $src_func($new_name);
                $new_img = imagecreatetruecolor(80,80);
                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                $write_func($new_img, $thumb, $image_quality);
                
                imagedestroy($new_img);
                imagedestroy($src);
            }
            
        } 
    }
?>