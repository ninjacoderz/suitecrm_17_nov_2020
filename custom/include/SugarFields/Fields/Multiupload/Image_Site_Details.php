<?php
   $id = $_REQUEST['id'];
   if(isset($_FILES['file']) && isset($id)){
    $ds_dir = dirname(__FILE__) .'/server/php/files/' .$id;
    if(!file_exists ($ds_dir)){
      mkdir($ds_dir);
    }
   
    $errors= array();
    $file_size =$_FILES['file']['size'];
    $file_tmp =$_FILES['file']['tmp_name'];
    $file_type=$_FILES['file']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));
    $file_name = 'Image_Site_Detail.jpg';
    $extensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext,$extensions)=== false){
       $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if($file_size > 2097152){
       $errors[]='File size must be excately 2 MB';
    }
    
    if(empty($errors)==true){
       move_uploaded_file($file_tmp,$ds_dir.'/'.$file_name);
       create_thumbnail($ds_dir.'/'.$file_name,$file_name,$ds_dir);
       echo "Success";
    }else{
       print_r($errors);
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
