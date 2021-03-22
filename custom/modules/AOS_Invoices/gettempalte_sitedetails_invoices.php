<?php
   $action =  $_REQUEST['action'];
   if(!isset($action) && $action == '') return;
   $id = $_REQUEST['id'];

   switch ($action) {
      case 'render':
        $template = new Sugar_Smarty();
        $a =  $template->fetch('custom/modules/AOS_Invoices/template_sitedetails_new.tpl');
        echo $a;
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

?>