<?php
   $action =  $_POST['action'];
   if(!isset($action) && $action == '') return;
   $content = urldecode($_POST['content']);
   $title = urldecode($_POST['title']);
   $id = $_POST['id'];
   $path_file_json_template = dirname(__FILE__) .'/json_plumbing_template.json';
   $json_data = json_decode(file_get_contents($path_file_json_template),true);
   if(!isset($json_data)) {
      $json_data = [];
   }
   switch ($action) {
      case 'read':
         break;
      case 'update':
         if($id == '' || $title == '') break;
         $json_data[$id]  = array (
            'title' => $title,
            'content' => $content
         );
         break;    
      case 'create':
         if($id != '' || $title == '') break;      
         $id = time();
         $json_data[$id] = array (
            'title' => $title,
            'content' => $content
         );
        
         break;
      case 'delete':
         if($id == '') break;
         unset($json_data[$id]);
         break;

      default:
         # code...
         break;
   }

$json_encode_data = json_encode($json_data);
file_put_contents($path_file_json_template,$json_encode_data);
echo $json_encode_data;