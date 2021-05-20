<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    date_default_timezone_set('Australia/Melbourne');

    $quote_id = $_REQUEST['quote_id'];
    $design_json = html_entity_decode($_REQUEST['design_json']);
    $type = $_REQUEST['type'];
    $status = $_REQUEST['status'];
    $quote  = new AOS_Quotes();
    $quote->retrieve($quote_id);
    $quoteType = '';
    $designType ='';
        if(empty($quote->id)) echo json_encode(array());
    $dataReturn = [];

    if($type == 'save'){
        $quote->design_tool_json_c = $design_json;
        $quote->save();
        $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
        $files = scandir($path,SCANDIR_SORT_DESCENDING);
        $filename = '';
        // if($status == 'override' || $quoteType == 'Daikin'){
        foreach($files as $file){
            if(is_file($path.'/'.$file) && strpos($file,"_Design_Proposed_Install_Location") !== false){
                unlink($path.'/'.$file);
                unlink($path.'/thumbnail/'.$file);
            }
        }

        if($quote->quote_type_c == 'quote_type_daikin' || $quote->quote_type_c == 'quote_type_nexura'){
            $dataURL = $_REQUEST['dataURL'];
            $quoteType = 'Daikin';
            for($i = 0 ; $i < count($dataURL); $i++){
                foreach($dataURL[$i] as $key =>$val){
                    if($key == "tabname") continue;
                    $designType = '_'.str_replace(" ","_",$dataURL[$i]['tabname']).'_'.(($key!='floorplan')?$key:'');
                    createImage($quote,base64_decode($val),$key,$designType,$quoteType,$status,$dataURL[$i]['tabname']);
                }
            }
    
        }else if($quote->quote_type_c == 'quote_type_sanden'){
            $dataURL = base64_decode($_REQUEST['dataURL']);
            $quoteType = 'Sanden';
            createImage($quote,$dataURL,'sanden',$designType,$quoteType,$status,'');
        }
    }

    $dataReturn['design_json'] = html_entity_decode($quote->design_tool_json_c);
    $dataReturn['quote_id'] = $quote->id;
    $dataReturn['pre_install_photos_c'] = $quote->pre_install_photos_c;
    $dataReturn['quote_number'] = $quote->number;

    echo json_encode($dataReturn);

    function createImage($quote,$dataURL,$key,$designType,$quoteType,$status,$tabname){
        if($dataURL != ''){
            $img = preg_replace('/data:image\/(.*?);base64,/', '', $dataURL);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
            $files = scandir($path,SCANDIR_SORT_DESCENDING);
            $filename = '';
            // if($status == 'override' || $quoteType == 'Daikin'){
            // foreach($files as $file){
            //     if(is_file($path.'/'.$file) && strpos($file,"_Design_Proposed_Install_Location") !== false){
            //         // $filename = explode(".",$file)[0];
            //         // $source = $path.$file;
            //         unlink($path.'/'.$file);
            //         unlink($path.'/thumbnail/'.$file);
            //         // break;
            //     }
            // }
            // }else{
            //     $time_string = strftime("%d%b%Y_%H%M",time());
            //     $filename = 'Q'.$quote->number.'_'.$quoteType.$designType.'_Design_Proposed_Install_Location'.$time_string;
            //     $source = $path.'/Q'.$quote->number.'_'.$quoteType.$designType.'_Design_Proposed_Install_Location'.$time_string.'.png';
            // }

            if($filename == ''){
                $time_string = strftime("%d%b%Y_%H%M",time());
                $filename = 'Q'.$quote->number.'_'.$quoteType.$designType.'_Design_Proposed_Install_Location'.$time_string;
                $source = $path.'/Q'.$quote->number.'_'.$quoteType.$designType.'_Design_Proposed_Install_Location'.$time_string.'.png';
            }
            
            $success = file_put_contents($source, $data);
            $data_option['tabname'] = $tabname;
            $data_option['quote_number'] = $quote->number;
            $data_option['customer_name'] = $quote->account_firstname_c.' '.$quote->account_lastname_c;
            $data_option['address_line1'] = $quote->install_address_c;
            $data_option['address_line2'] = $quote->install_address_city_c.' '.$quote->install_address_state_c.' '.$quote->install_address_postalcode_c;
            $data_option['address_line3'] = "Australia";
            $data_option['product0'] = '';
            $data_option['product1'] = '';
            $data_option['product2'] = '';
            if($key == 'sanden'){
                $quote_input = json_decode(html_entity_decode($quote->quote_note_inputs_c));
                $data_option['product0'] = $quote_input->quote_number_sanden."x ".$quote_input->quote_tank_size;
            }else{
                preg_match('/\[\{(.*?)\}\]/',$quote->description, $matches);
                if(count($matches) > 1){
                    foreach(json_decode(html_entity_decode($matches[0])) as $k=>$v){
                        $data_option['product'.$k] = $v->quantity."x "."Daikin ".$v->typeOfProduct;
                    }
                }
            }
            
            create_img_option($path,$filename,$data_option,$key);
        }
    }

    function create_img_option($path,$fullname,$data_option,$key){
        $path= $path.'/';
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

        //append image info
        $source = $new_name;

        // get size of image source
        list($w_source, $h_source) = getimagesize($source);

        $font = $path.'../arial.ttf';
        // add text for image info
        $img_template = '';
        if($w_source > 800){
            if($key == "floorplan"){
                $img_template = $path.'../daikin_bot_image_floorplan.png';
            }else if($key == "indoor"){
                $img_template = $path.'../daikin_bot_image_indoor.png';
            }else if($key == "outdoor"){
                $img_template = $path.'../daikin_bot_image_outdoor.png';
            }else if($key == "sanden"){
                $img_template = $path.'../sanden_bot_image.png';
            }

            list($w_info, $h_info) = getimagesize($img_template);
            $img_info = imagecreatefrompng($img_template);
            $black = imagecolorallocate($img_info, 0, 0, 0);
            if($key != "floorplan"){
                imagettftext($img_info,27,0,240,110,$black,$font, $data_option["tabname"]);
            }
            imagettftext($img_info,24,0,480,46,$black,$font,"Quote #".$data_option['quote_number']);
            imagettftext($img_info,20,0,480,92,$black,$font, $data_option["product0"]);
            imagettftext($img_info,20,0,480,128,$black,$font,$data_option["product1"]);
            imagettftext($img_info,20,0,480,164,$black,$font,$data_option["product2"]);

            imagettftext($img_info,24,0,1045,46,$black,$font,$data_option["customer_name"]);

            imagettftext($img_info,20,0,1045,92,$black,$font,$data_option["address_line1"]);
            imagettftext($img_info,20,0,1045,128,$black,$font,$data_option["address_line2"]);
            imagettftext($img_info,20,0,1045,164,$black,$font,"Australia");
        }else{
            if($key == "floorplan"){
                $img_template = $path.'../daikin_bot_image_floorplan_small.png';
            }else if($key == "indoor"){
                $img_template = $path.'../daikin_bot_image_indoor_small.png';
            }else if($key == "outdoor"){
                $img_template = $path.'../daikin_bot_image_outdoor_small.png';
            }else if($key == "sanden"){
                $img_template = $path.'../sanden_bot_image_small.png';
            }

            list($w_info, $h_info) = getimagesize($img_template);
            $img_info = imagecreatefrompng($img_template);
            $black  = imagecolorallocate($img_info, 0, 0, 0);
            if($key != "floorplan"){
                imagettftext($img_info,13,0,85,50,$black,$font, $data_option["tabname"]);
            }
            imagettftext($img_info,14,0,185,25,$black,$font,"Quote #".$data_option['quote_number']);
            imagettftext($img_info,12,0,185,50,$black,$font,$data_option["product0"]);
            imagettftext($img_info,12,0,185,70,$black,$font,$data_option["product1"]);
            imagettftext($img_info,12,0,185,90,$black,$font,$data_option["product2"]);

            imagettftext($img_info,14,0,390,25,$black,$font,$data_option["customer_name"]);

            imagettftext($img_info,12,0,390,50,$black,$font,$data_option["address_line1"]);
            imagettftext($img_info,12,0,390,70,$black,$font,$data_option["address_line2"]);
            imagettftext($img_info,12,0,390,90,$black,$font,"Australia");
        }

        $scale = ($h_info/$w_info);
        $new_w_info = $w_source;
        $new_h_info = intval($w_source*$scale);

        $img_info_resize = imagecreatetruecolor($new_w_info, $new_h_info);
        imagecopyresampled($img_info_resize,$img_info,0,0,0,0,$new_w_info,$new_h_info,$w_info,$h_info);

        // create outputImage
        $outputImage = imagecreatetruecolor($w_source, ($h_source + $new_h_info));
        $white = imagecolorallocate($outputImage, 255, 255, 255);
        imagefill($outputImage, 0, 0, $white);

        $src_function = 'imagecreatefrom'.$type;
        $write_function = 'image'.$type;
        $img_source = $src_function($source);
        
        // merge img_info and img_source to outputImage
        imagecopyresized($outputImage,$img_source,0,0,0,0, $w_source,$h_source,$w_source,$h_source);
        imagecopyresized($outputImage,$img_info_resize,0,$h_source,0,0, $new_w_info,$new_h_info,$new_w_info,$new_h_info);
        header('Content-Type: image/'+$type);
        $write_function($outputImage,$source);

        imagedestroy($img_info);
        imagedestroy($img_source);
        imagedestroy($outputImage);

        //create thumbnail
        if($type == 'gif' || $type == 'jpeg' || $type == 'png') {
            //create thumbnail
            if(!file_exists ($path."/thumbnail/")) {
                mkdir($path."/thumbnail/");
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
                $write_func($new_img,$thumb, $image_quality);
                
                imagedestroy($new_img);
                imagedestroy($src);
            }
            
        } 
    }
?>