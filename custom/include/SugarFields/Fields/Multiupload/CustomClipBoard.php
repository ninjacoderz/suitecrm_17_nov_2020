<?php
   $img = $_POST['img'];
   $id = $_POST['id'];
   $result = array();

   if (strpos($img, 'data:image/png;base64') === 0) {
       
      $img = str_replace('data:image/png;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      $ds_dir = dirname(__FILE__) .'/server/php/files/' .$id;
      mkdir($ds_dir);
      $time = time();
      $file = $ds_dir ."/clipboard_" . $time  .".png";
      //$file = 'uploads/img.png';
   
      if (file_put_contents($file, $data)) {
        $result['img'] = 'http://' . $_SERVER['SERVER_NAME'] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$id ."/clipboard_" . $time  .".png";
        $result['img_name'] = 'clipboard_' .$time .".png";
        
        //create thumbnail

         $destination = $file;
         if(!file_exists ($ds_dir."/thumbnail/")) {
            mkdir($ds_dir ."/thumbnail/");
         }
        $thumb =  $ds_dir."/thumbnail/clipboard_" . $time  .".png";
        $src_func = 'imagecreatefrompng';
        $write_func = 'imagepng';
        $image_quality = isset($options['png_quality']) ?
        $options['png_quality'] : 9;
        list($w, $h) = getimagesize($destination);

        $src = $src_func($destination);
        $new_img = imagecreatetruecolor(80,80);
        imagealphablending($new_img, false);
        imagesavealpha($new_img,true);
        $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
        imagefilledrectangle($src, 0, 0, 80, 80, $transparent);

        imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
        $write_func($new_img,$thumb, $image_quality);
        
        imagedestroy($new_img);
        imagedestroy($src);
        $result['thub'] ='http://' . $_SERVER['SERVER_NAME'] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$id ."/thumbnail/clipboard_" . $time  .".png";
        $result['thub_name'] = 'clipboarb_' .$time .'.png';
      } else {
        $result['thub'] = '';
        $result['thub_name'] = '';
        $result['img'] = '';
        $result['img_name'] = '';

      }   
      
   }

echo json_encode($result);