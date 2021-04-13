<?php
  // .:nhantv:. Get Design data from Solar design tool
  header('Access-Control-Allow-Origin: *');

  $id = $_REQUEST['id'];
  // Case id invalid
  if(!isset($id) && $id == '') return;

  // Get quote
  $quote = new AOS_Quotes();
  $quote->retrieve($id);
  // Case quote not exist
  if(!$quote->id) return;

  $resData = [];
  // Get map design data JSON
  $jsonData = json_decode(html_entity_decode($quote->design_tool_json_c), true);
  $imageName = $jsonData['mapDesign']['imagePath'];
  if(!is_null($imageName) || !empty($imageName)){
    // Get and convert background image to base 64
    $latestImageBg = get_latest_bg_base64($imageName, $quote);
    $jsonData['mapDesign']['imagePath'] = $latestImageBg;
  }
  
  // Return
  if(is_null($jsonData) || empty($jsonData)){
      $resData['code'] = -1;
      $resData['content'] = [];
  } else {
      $resData['code'] = 0;
      $resData['content'] = utf8_encode(json_encode($jsonData));
  }
  echo json_encode($resData);

  function get_latest_bg_base64($imageName, $quote){
    $path = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c."/".$imageName;
    $base64 = "";
    if(is_file($path)){
      // Case file exist
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return $base64;
  }

?>