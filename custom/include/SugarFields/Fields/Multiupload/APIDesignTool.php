<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $quote_id = $_REQUEST['quote_id'];
    $design_json = html_entity_decode($_REQUEST['design_json']);
    $type = $_REQUEST['type'];
    $dataURL = $_REQUEST['dataURL'];
    $quote  = new AOS_Quotes();
    $quote->retrieve($quote_id);

    if(empty($quote->id)) echo json_encode(array());
    $dataReturn = [];

    if($type == 'save'){
        $quote->design_tool_json_c = $design_json;
        $quote->save();
        $img = str_replace('data:image/png;base64,', '', $dataURL);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c;
        $filename = 'Q'.$quote->number.'_Sanden_Design_Proposed_Install_Location';
        $source = $path.'/Q'.$quote->number.'_Sanden_Design_Proposed_Install_Location.jpeg';
        $success = file_put_contents($source, $data);
        if($success !== false){
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
                rename( $source,$filename);
            } else {
                return;
            }
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
        
    }

    $dataReturn['design_json'] = html_entity_decode($quote->design_tool_json_c);
    $dataReturn['quote_id'] = $quote->id;
    $dataReturn['pre_install_photos_c'] = $quote->pre_install_photos_c;
    $dataReturn['quote_number'] = $quote->number;

    echo json_encode($dataReturn);
?>