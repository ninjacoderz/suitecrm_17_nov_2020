<?php
   $action =  $_POST['action'];
   if(!isset($action) && $action == '') return;
   $record_id = trim($_POST['record_id']);
   $id_po = trim($_POST['id_po']);
   $line_number = urldecode($_POST['line_number']);

   $path_file_json_template = dirname(__FILE__) .'/json_supply_PO.json';
  
   $json_data = json_decode(file_get_contents($path_file_json_template),true);
   if(!isset($json_data)) {
      $json_data = [];
   }
   switch ($action) {
      case 'read':
         break;   
      case 'create':
         if($id_po != ''){
            $json_data[$record_id][] = array (
               'id_supply_po' => $id_po,
               'line_number' => $line_number,
            );
         }      
         break;
      default:
         # code...
         break;
   }

$json_encode_data = json_encode($json_data);
file_put_contents($path_file_json_template,$json_encode_data);

echo $json_encode_data;