<?php
    $sg_order_number = trim($_REQUEST['order_num']);
    $generateUUID = trim($_REQUEST['generateUUID']);
    $InvoiceNumber = trim($_REQUEST['numberInv']);
    if($sg_order_number != ''){
        $username = "matthew.wright";
        $password = "MW@pure733";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number/formbayfiles");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Sec-Fetch-Site: none';
        $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-User: ?1';
        $headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decode_result = json_decode($result,true);
        if( $decode_result['Message'] == 'An error has occurred.' || $decode_result == "" ){
            $username = 'paul.szuster@solargain.com.au';
            $password = 'Baited@42';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number/formbayfiles");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Cache-Control: max-age=0';
            $headers[] = 'Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==';
            $headers[] = 'Upgrade-Insecure-Requests: 1';
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36';
            $headers[] = 'Sec-Fetch-Dest: document';
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
            $headers[] = 'Sec-Fetch-Site: none';
            $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
            $headers[] = 'Sec-Fetch-Mode: navigate';
            $headers[] = 'Sec-Fetch-User: ?1';
            $headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close ($ch);
        }
        $photo_case = [];
        $img = json_decode($result);
        if( $img !=""){
            foreach($img as $index => $design_file_name){
                if(strpos($design_file_name->Title,'roofpanels') == true )
                {
                    $file_name = $design_file_name->Filename;
                    $url  = $design_file_name->Url;
                    $file_type = strtolower(substr(strrchr($file_name, '.'), 1));
                    $new_file_name = '/'.$InvoiceNumber.'_New_Install_Photo_'.$sg_order_number.'_'. $index  .'.'.$file_type;
                    get_all_file_solar_crm($url,$file_name,$new_file_name,$generateUUID, $username,$password);
                    $photo_case[] = array("title" => $file_name,"url" => $design_file_name->Url );
                }
            } 
        }
        if(count($photo_case) > 0){
            echo 'success';
        }
    }
    
   //function get all file from message app 
    function get_all_file_solar_crm($url,$file_name,$new_file_name,$generateUUID, $username,$password) {
                $img_path = 'https://crm.solargain.com.au'.$url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $img_path);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                $headers = array();
                $headers[] = 'Connection: keep-alive';
                $headers[] = 'Cache-Control: max-age=0';
                $headers[] = 'Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==';
                $headers[] = 'Upgrade-Insecure-Requests: 1';
                $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36';
                $headers[] = 'Sec-Fetch-Dest: document';
                $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
                $headers[] = 'Sec-Fetch-Site: none';
                $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
                $headers[] = 'Sec-Fetch-Mode: navigate';
                $headers[] = 'Sec-Fetch-User: ?1';
                $headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);
               
                //check folder and  get file img
                $path_save_file = $_SERVER['DOCUMENT_ROOT']."/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$generateUUID;
                $path_save_file_new_file = $path_save_file .'/'.$new_file_name;
    
                if(!file_exists ($path_save_file)) {
                    mkdir($path_save_file);
                }
       
                file_put_contents($path_save_file_new_file ,$result);
                create_thumbnail($path_save_file_new_file,$new_file_name,$path_save_file);
    }

    //function create thumbnail from source
    function create_thumbnail($source,$file_name,$path_save_file){
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
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