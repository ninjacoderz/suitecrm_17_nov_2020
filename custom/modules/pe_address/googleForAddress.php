<?php

$data = $_POST['data'];
$record_id = $_POST['id'];
$nameModule = $_POST['module'];
// $action = $_POST['action'];

$bean = BeanFactory::getBean($nameModule,$record_id);
if ($bean->id) {
    //get data image
    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
        $data = substr($data, strpos($data, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif
    
        if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
            throw new \Exception('Invalid image type!');
        }
        $data = str_replace( ' ', '+', $data );
        $data = base64_decode($data);
    
        if ($data === false) {
            throw new \Exception('base64_decode failed');
        }
    } else {
        throw new \Exception('did not match data URI with image data');
    }
    //check beans
    if ($bean->installation_pictures_c == "") {
        $bean->save();
    }
    //copy image to folder
    copyToFolder($bean->installation_pictures_c,$data);
}
die;

function copyToFolder($id_folder, $data) {
    $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    $folderName     = $path . $id_folder . '/';
    $thumbnail      = $path . $id_folder . '/thumbnail' . '/';
    if (!file_exists($folderName)) {
        mkdir($path . $id_folder, 0777, true);
        $folderName = $path . $id_folder.'/';
    }
    $file = $folderName ."/Image_Site_Detail.jpg";
    if (file_put_contents($file, $data)) {
        create_thumbnail($file,'Image_Site_Detail.jpg',$folderName);
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

