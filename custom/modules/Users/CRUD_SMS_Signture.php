<?php

   $action =  $_POST['action'];
   if(!isset($action) && $action == '') return;
   $content = urldecode($_POST['content']);
   $title = urldecode($_POST['title']);
   $id = str_replace('_','',$_POST['id']);
   $custom_action = $_POST['custom_action'];
   $path_file_json_sms_signture = dirname(__FILE__) .'/json_sms_signture.json';
   $json_data = json_decode(file_get_contents($path_file_json_sms_signture),true);
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
file_put_contents($path_file_json_sms_signture,$json_encode_data);

//get all SMS signture from other users
$db = DBManagerFactory::getInstance();
$query =  "SELECT users.id as id, users.first_name  as first_name ,users.last_name   as last_name  , users_cstm.sms_signature_c as sms_signature_c
FROM users
INNER JOIN users_cstm ON users_cstm.id_c = users.id
WHERE ( users_cstm.sms_signature_c IS NOT NULL AND  users_cstm.sms_signature_c != '' ) AND users.deleted = 0";
$result = $db->query($query);
while (($row=$db->fetchByAssoc($result)) != null) {
    $aray_order_done[] = $row['order_number'] ;
    $array_invoice_created[] = $row['id'];

    $json_data[$row['id']]  = array (
      'title' =>$row['first_name'] . ' ' .$row['last_name']  ,
      'content' =>$row['sms_signature_c'] 
   ); 
}
//custom action
// if($custom_action == 'get_sms_signture'){
//       global $current_user;
//       $json_data[$current_user->id]  = array (
//          'title' => $current_user->name,
//          'content' => $current_user->sms_signature_c
//       );   
// }
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