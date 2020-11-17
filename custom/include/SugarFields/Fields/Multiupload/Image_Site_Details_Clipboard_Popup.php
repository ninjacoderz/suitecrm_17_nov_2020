<?php
   $img = $_POST['img'];
   $id = $_POST['id'];
   $record_id = $_POST['record_id'];
   $nameModule = $_POST['nameModule'];
   $action = $_POST['action'];
   
   $result = array();

   if (strpos($img, 'data:image/png;base64') === 0) {
       
      $img = str_replace('data:image/png;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      $ds_dir = dirname(__FILE__) .'/server/php/files/' .$id;
      mkdir($ds_dir);
      $time = time();
      $file = $ds_dir ."/Image_Site_Detail.jpg";
      //$file = 'uploads/img.png';
      if($action == ' DetailView') {
        save_id_folder($record_id,$nameModule);
      }
      
      if (file_put_contents($file, $data)) {
        $result['img'] = 'http://' . $_SERVER['SERVER_NAME'] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$id ."/Image_Site_Detail.jpg";
        $result['img_name'] = 'Image_Site_Detail.jpg';
        create_thumbnail($file,'Image_Site_Detail.jpg',$ds_dir);
      } 
   }

 //function create thumbnail from source
 function create_thumbnail($source,$file_name,$path_save_file){
    $type = strtolower(end(explode('.',$file_name)));
    $typeok = TRUE;
    if(!file_exists ($path_save_file."/thumbnail/")) {
        mkdir($path_save_file."/thumbnail/");
        }
    $thumb =  $path_save_file."/thumbnail/".$file_name;
 
    $info = getimagesize($source);
    $mime = $info['mime'];
    switch ($mime) {
            case 'image/jpeg':
                $src_func  = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                $options['jpeg_quality'] : 75;
                break;
            case 'image/png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                $options['png_quality'] : 9;
                break;
            case 'image/gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            default: 
            $typeok =FALSE;
                    throw new Exception('Unknown image type.');
    }
 
    if ($typeok){
        list($w, $h) = getimagesize($source);
 
        $src = $src_func($source);
        $new_img = imagecreatetruecolor(80,80);
        $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
        imagefilledrectangle($src, 0, 0, 80, 80, $transparent);
        imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
        $write_func($new_img,$thumb, $image_quality);
        
        imagedestroy($new_img);
        imagedestroy($src);
    }      
 }

 function save_id_folder($record_id,$nameModule){
    $bean = new $nameModule();
    $bean->retrieve($record_id);
    if($bean->id != '') {
        switch ($nameModule) {
            case 'AOS_Quote':
                if($bean->pre_install_photos_c == ''){
                    $bean->pre_install_photos_c = $id;
                }
                
                break;
            
            default:
                # code...
                break;
        }

        $bean->save();
    }

 }
 