<?php
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $tmpfsuitename = dirname(__FILE__).'/cookiesrealestate.txt';

    require_once(dirname(__FILE__).'/simple_html_dom.php');
    $address = $_REQUEST['address'];//'15-maleela-gr-rosanna-vic-3084'
    $address = strtolower(str_replace(" ","-",$address));
    $generateUUID = trim($_REQUEST['generateUUID']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://suggest.realestate.com.au/consumer-suggest/suggestions?max=7&type=address&src=homepage&query=".$address);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://www.realestate.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36";
    $headers[] = "Accept: */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "Authority: suggest.realestate.com.au";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close ($ch);

    $json_result = json_decode($result);
    $count_result = $json_result->count;
    $id_source = $json_result->_embedded->suggestions[0]->id;
    if($count_result !== '0'){
        $url_realestate = "https://www.realestate.com.au/property/lookup?id=" .$id_source;
    }else{
        echo 'fail';die;
    }


    // URL to fetch cookies 
    $ch = curl_init();
    $url = $url_realestate; 
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: www.realestate.com.au';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Accept-Language: en,en-US;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    
    preg_match_all('/<script src="(.*?)"/', $result,$output_array);
    for ($i=0; $i < count($output_array[1]) ; $i++) {
        $url = "https://www.realestate.com.au".$output_array[1][$i]; 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);    
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: www.realestate.com.au';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Sec-Fetch-Site: none';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Accept-Language: en,en-US;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
    
        if($i == 2){
           // preg_match('/url=(.*?)&token=(.*)/', $output_array[1][$i], $output);
            $param = array (
                't' => '5f8abb4c-4a29-8652-c91f-6dc78c8ca05f',
                'd' => 
                array (
                  'a2b966c6015331aef2c546e18fcbb837d1d1d5c6310b2599b3e258221ba628ab4' => '9296dbc88b94a17ec56628efa6ec1087',
                  'a7709ad4921d9d2fddd184fb203d972b67c091da33a60f5148d19257ef55fe80e' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',
                  'a4d06a5a037b2609a375e7da3ef18b4328a7f7f2efc3687dbd6f85f9e46c84d0b' => false,
                  'ae66350c25e6310a46b1211d07758c383d4ea36e2c9f590a66733bd78ee10554e' => false,
                  'a79ed4cf26aa77aa2d14f423ffbc3f6d78bf9d122a35ebe573c6b6780cbdbc26c' => false,
                  'ab31231a8571d50420af08ce68fcf3cafb567af56168f6174866f33bfaaa4971c' => true,
                  'af9f8b0e61e9010426a5fa622f86a96b7d732a7400413cda1d2a31ef8cf8c0e5d' => 
                  array (
                    0 => 1792,
                    1 => 1017,
                  ),
                  'a3b776a38b40e6369ccd2dbf50b34e9f57d6a8aa3f0b168c5f2b13acf7a6b167c' => 
                  array (
                    0 => 'Chrome PDF Plugin::Portable Document Format::application/x-google-chrome-pdf~pdf',
                    1 => 'Chrome PDF Viewer::::application/pdf~pdf',
                    2 => 'Native Client::::application/x-nacl~,application/x-pnacl~',
                  ),
                  'abcbbdd4450d158e48b9c6e42e408558b3881d61460f5e3dfde4f537b9aaaaa1e' => 
                  array (
                  ),
                  'a06fafc0eb414c403a30c19284134fbb3622dd2d04a99fd46254cc2b1ce5fc403' => 'TypeError: [] is not a function
                  at _0x1ef3c0 (https://www.realestate.com.au'.$output_array[1][$i].':1:15853)
                  at _0x3bbcae (https://www.realestate.com.au'.$output_array[1][$i].':1:20922)
                  at https://www.realestate.com.au'.$output_array[1][$i].':1:22922
                  at https://www.realestate.com.au'.$output_array[1][$i-1].':1:1802
                  at https://www.realestate.com.au'.$output_array[1][$i-1].':1:14149',
                  'acce6ae932bda4327dffaf46564500a1217c9b68a5b24b5bd3f5704d04321e9eb' => false,
                  'aaf5d594fbc8f5058fd20598c7e6ab3f2e656b0f4006e5b100fcf74ffdf54cb01' => false,
                  'a3f61b6cbfe8119712454f2d834e373c4d9796f8ba260aa64b58d5575d7353e43' => false,
                  'adaaea67c8907130d8e777454ae1935d46ee40c689f0642b805563f880b4a72a3' => false,
                  'a416dc10c4782df33b1a8df4cdfb084f96dfb510bc65bd1ce162e3ba2c994f7dc' => false,
                ),
            );
            $data = json_encode( $param);
            $url = "https://www.realestate.com.au";
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);    
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($ch, CURLOPT_COOKIE, "bb_lpj=cd155d48-a61f-6c1a-3a49-054486316382");
            $headers = array();
            $headers[] = 'Authority: www.realestate.com.au';
            $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';
            $headers[] = 'Content-Type: application/json; charset=UTF-8';
            $headers[] = 'Accept: */*';
            $headers[] = 'Origin: https://www.realestate.com.au';
            $headers[] = 'Sec-Fetch-Site: same-origin';
            $headers[] = 'Sec-Fetch-Mode: cors';
            $headers[] = 'Sec-Fetch-Dest: empty';
            $headers[] = 'Accept-Language: en,vi;q=0.9';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
    
            curl_setopt($ch, CURLOPT_URL, $url_realestate);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfsuitename);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            $headers = array();
            $headers[] = 'Authority: www.realestate.com.au';
            $headers[] = 'Cache-Control: max-age=0';
            $headers[] = 'Upgrade-Insecure-Requests: 1';
            $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
            $headers[] = 'Sec-Fetch-Site: same-origin';
            $headers[] = 'Sec-Fetch-Mode: navigate';
            $headers[] = 'Sec-Fetch-Dest: document';
            $headers[] = 'Referer: '.$url_realestate;
            $headers[] = 'Accept-Language: en,vi;q=0.9';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    $html = str_get_html($result);
    $json_data_customer = $html->find('script[id="P4EPconfig"]')[0]->innertext;
    $compare = '/REA.allImages = (.*?)REA.propertyType/s';
    preg_match($compare , $json_data_customer, $matches);
    $data_tr = str_replace(";"," ",$matches[1]);
    $data_tr = json_decode($data_tr);
    $photo_floor = [];
    foreach ($data_tr as $key => $value){
        if(strpos($value->uri, "floorplan") == true){
            $file_name = $value->caption;
            $url  = 'https://i2.au.reastatic.net/2298x1162-resize,r=33,g=40,b=46'.$value->uri;
            $file_type = strtolower(substr(strrchr($value->uri, '.'), 1));
            $new_file_name = '/FloorPlan_Photo_'. $key .'.'.$file_type;
            get_all_file_floorplan_realestate($url,$file_name,$new_file_name,$generateUUID);
            $photo_floor[] = array("title" => $file_name,"url" => $value->uri );
        }
    }
    if(count($photo_floor) > 0){
        echo 'success';
    }else{
        echo 'fail';
    }
function get_all_file_floorplan_realestate($url,$file_name,$new_file_name,$generateUUID) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: i2.au.reastatic.net';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';
    $headers[] = 'If-None-Match: ^^2b9ca-VKLgmK5DW5KYN32ORw3+mwobYhU^^\"\"';
    $headers[] = 'If-Modified-Since: Sat, 25 May 2013 08:01:21 GMT';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);
   
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
?>