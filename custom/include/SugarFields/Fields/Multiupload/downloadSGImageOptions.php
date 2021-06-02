<?php

    //Thienpb code - download file design option from sg and append image info
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    require_once(dirname(__FILE__).'/simple_html_dom.php');

    global $timedate;
    $timezone = $timedate->getInstance()->userTimezone();
    date_default_timezone_set($timezone);


    $username = "matthew.wright";
    $password =  "MW@pure733";
    $quote_solorgain = urldecode($_GET['quote_solorgain']);
    
    $pre_install_photos_c = urldecode($_GET['pre_install_photos_c']);
    $path = dirname(__FILE__)."/server/php/files/".$pre_install_photos_c;
    
    //CURL for get json from quotesg
    $url = 'https://crm.solargain.com.au/APIv2/quotes/' .$quote_solorgain;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Connection: keep-alive";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close ($ch);

    $decode_result = json_decode($result,true);
//Thienpb code for change account if download false
    if(!isset($decode_result['ID'])){
        $username = 'paul.szuster@solargain.com.au';
        $password = 'WalkingElephant#256';
        //CURL for get json from quotesg
        $url = 'https://crm.solargain.com.au/APIv2/quotes/' .$quote_solorgain;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);
    }
//END
    $decode_result = json_decode($result,true);

    $customer_name = $decode_result['Customer']['Name'];
    $customer_name_file = str_replace(" ","_",$decode_result['Customer']['Address']['Street1'].' '.$decode_result['Customer']['Address']['Locality']);
    $customer_name_file = str_replace("/", "_", $customer_name_file);
    $Options = $decode_result['Options'];
    $install_ID = $decode_result['Install']['ID'];
    $data_option = array();
    
    //Download blank image(image map)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/installs/$install_ID/map?random=");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Connection: keep-alive";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result_blank = curl_exec($ch);
    curl_close ($ch);

    //delete file before get
    $files = glob($path.'/*'); //get all file names
    foreach($files as $design_file_name){
        if(strpos($design_file_name,'Design_'.$customer_name_file)){
            unlink($design_file_name); //delete file
        }
    }

    $filename = 'Design_'.$customer_name_file.'_blank';
    $data_option['blank'] = true;
    $data_option['customer_name'] = $customer_name;
    $data_option['address_1'] = $decode_result['Customer']['Address']['Street1'];
    $data_option['address_2'] = $decode_result['Customer']['Address']['Locality'].' '.$decode_result['Customer']['Address']['State'].' '.$decode_result['Customer']['Address']['PostCode'];
    //create image blank
    create_img_option($path,$filename,$result_blank,$data_option);
    
    //Download image of Options
    for($i = 0 ; $i < count($Options) ; $i++){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/quotes/$quote_solorgain/options/$i/design?random=");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result_option = curl_exec($ch);
        curl_close ($ch);
        $option_panel = 0;
        for($j= 0; $j < count($Options[$i]['Configurations']) ; $j++){
            if(strpos($Options[$i]['Configurations'][0]['Inverter']['Name'],'Enphase') !== false || strpos($Options[$i]['Configurations'][0]['Inverter']['Name'],'SolarEdge') !== false)
            {
                $option_panel = $option_panel + $Options[$i]['Configurations'][$j]['Number'];
            }else{
                $option_panel = $option_panel + $Options[$i]['Configurations'][$j]['NumberOfPanels'];
            }
        }

        $filename = 'Design_'.$customer_name_file.'_'.$option_panel.'panels';
        //$filename = 'Design_'.$customer_name_file.'_'.'21panels';
        $data_option['blank'] = false;
        $data_option['Option_number'] = $i+1;
        $data_option['Inverter'] = $Options[$i]['Configurations'][0]['Inverter']['Name'];
        $data_option['Panel'] = $Options[$i]['Configurations'][0]['Panel']['Name'];
        $data_option['NumberOfPanels'] = $option_panel.' Panels';

        //create image option
        create_img_option($path,$filename,$result_option,$data_option);
    } 

    function create_img_option($path,$filename,$result,$data_option){
        //create folder 
        if(!file_exists ( $path )) {
            set_time_limit ( 0 );
            mkdir($path);
        }
        
        // rename with address and number of panel
        $count=1;
        $names= explode('_',$filename);
        while(true){
            if(empty(glob($path.'/'.implode('_',$names).'*'))){
                break;
            }else{
                $names[count($names)-1] = str_replace(($count-1).'_','',$names[count($names)-1]);
                $names[count($names)-1] = $count.'_'.$names[count($names)-1];
                $count++;
            }
        }
        
        $filename = implode('_',$names);
        
        //create image png
        $source = $path.'/'.$filename.'.png';
        if(file_exists($source)){
            @unlink($source);
        }
        $fp = fopen($source,'x');

        if($result){
            fwrite($fp, $result);
        }
        fclose($fp);

        $new_name='';
        $type ='';
        if (exif_imagetype($source) == 2) {
            $type = 'jpeg';
            $new_name = $path.'/'.$filename.'.jpg';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 3){
            $type = 'png';
            $new_name = $path.'/'.$filename.'.png';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 1){
            $type = 'gif';
            $new_name = $path.'/'.$filename.'.gif';
            rename( $source,$new_name);
        } else {
            return;
        }

        //append image info
        $source = $new_name;

        // get size of image source
        list($w_source, $h_source) = getimagesize($source);

        $font = $path.'/../arial.ttf';
        // add text for image info
        if($w_source >= 500){
            list($w_info, $h_info) = getimagesize($path.'/../dessign-image_full.jpeg');
            $img_info = imagecreatefromjpeg($path.'/../dessign-image_full.jpeg');
            $black = imagecolorallocate($img_info, 0, 0, 0);
            $orange = imagecolorallocate($img_info,243, 143, 42);

            if($data_option['blank'] == true){
                imagettftext($img_info,24,0,315,65,$black,$font,'Blank');
            }
            imagettftext($img_info,30,0,145,90,$orange,$font,$data_option['Option_number']);
            imagettftext($img_info,22,0,560,40,$black,$font,$data_option['customer_name']);
            imagettftext($img_info,16,0,560,70,$black,$font,$data_option['address_1']);
            imagettftext($img_info,16,0,560,95,$black,$font,$data_option['address_2']);
            imagettftext($img_info,22,0,215,40,$black,$font,$data_option['NumberOfPanels']);
            imagettftext($img_info,16,0,215,70,$black,$font,$data_option['Panel']);
            imagettftext($img_info,16,0,215,95,$black,$font,$data_option['Inverter']);
        }else{
            $black = imagecolorallocate($img_info, 0, 0, 0);
            $orange = imagecolorallocate($img_info,243, 143, 42);
            list($w_info, $h_info) = getimagesize($path.'/../dessign-image_500.png');
            $img_info = imagecreatefrompng($path.'/../dessign-image_500.png');
            if($data_option['blank'] == true){
                imagettftext($img_info,14,0,170,35,$orange,$font,'Blank');
            }
            imagettftext($img_info,20,0,72,50,$orange,$font,$data_option['Option_number']);
            imagettftext($img_info,14,0,305,22,$black,$font,$data_option['customer_name']);
            imagettftext($img_info,9,0,305,40,$black,$font,$data_option['address_1']);
            imagettftext($img_info,9,0,305,55,$black,$font,$data_option['address_2']);
            imagettftext($img_info,14,0,110,22,$black,$font,$data_option['NumberOfPanels']);
            imagettftext($img_info,9,0,110,40,$black,$font,$data_option['Panel']);
            imagettftext($img_info,9,0,110,55,$black,$font,$data_option['Inverter']);
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
                    $thumb =  $path."/thumbnail/".$filename.'.jpg';
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