<?php
   // Enable cross domain call
   header('Access-Control-Allow-Origin: *');
   
   $action =  $_POST['action'];
   if(!isset($action) && $action == '') return;
   $content = urldecode($_POST['content']);
   $title = urldecode($_POST['title']);
   $id = str_replace('_','',$_POST['id']);
   $path_file_json_template = dirname(__FILE__) .'/json_template_quote_notes.json';
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
// load add template of Electrical and Plumbing Notes and quick comment
$path_file_json_template_quickcomment = dirname(__FILE__) .'/../Emails/json_template_quick_comment.json';
$json_data_quickcomment = json_decode(file_get_contents($path_file_json_template_quickcomment),true);
$path_file_json_template_elec = dirname(__FILE__) .'/../AOS_Invoices/json_electrical_template.json';
$json_data_elec = json_decode(file_get_contents($path_file_json_template_elec),true);
$path_file_json_template_plum = dirname(__FILE__) .'/../AOS_Invoices/json_plumbing_template.json';
$json_data_plum = json_decode(file_get_contents($path_file_json_template_plum),true);

foreach ($json_data_quickcomment as $key => $value) {
   $value['title'] .= '(Quick Comments)';
   $json_data[$key] = $value;
}
foreach ($json_data_elec as $key => $value) {
   $value['title'] .= '(Electrical Notes)';
   $json_data[$key] = $value;
}
foreach ($json_data_plum as $key => $value) {
   $value['title'] .= '(Plumbing Notes)';
   $json_data[$key] = $value;
}

//sort title asc
$json_data_order_title = [];
$array_sort_title = [];
foreach ($json_data as $key => $value) {
   $array_sort_title[$key] = $value['title'];
}
asort($array_sort_title);

foreach ($array_sort_title as $key => $value) {
   $json_data_order_title['_' .$key] = $json_data[$key];
}

$json_encode_data = json_encode($json_data_order_title);
echo $json_encode_data;