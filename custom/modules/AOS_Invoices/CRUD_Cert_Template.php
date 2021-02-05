<?php
   $action =  $_POST['action'];
   if(!isset($action) && $action == '') return;
   $content = urldecode($_POST['content']);
   $title = urldecode($_POST['title']);
   $id = $_POST['id'];
   $module = isset($_POST["module"]) ? $_POST["module"] : '';
   $module_id = isset($_POST["module_id"]) ? $_POST["module_id"] : '';
   if ($module_id !='' && $module!='') {
      $module_array = [
         $module => $module_id,
      ];
   }
   if ( $_REQUEST['type_template'] == "pcoc_type"){
      $path_file_json_template = dirname(__FILE__) .'/json_pcoc_cert_template.json';
   }else {
      $path_file_json_template = dirname(__FILE__) .'/json_ces_cert_template.json';
   }
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
//parse variable /**$action == 'read' && */ 
if ($_REQUEST['type_template'] == 'ces_type' && $module!='') {
   require_once('modules/AOS_PDF_Templates/templateParserQuoteForm.php');
   // require_once('modules/AOS_PDF_Templates/templateParser.php');

   if ($module == 'AOS_Quotes'|| $module == 'AOS_Invoices') {
       foreach ($json_data as $key => $value) {
         $text ='';
         $text = templateParserQuoteForm::parse_template_quote_form($value['content'], $module_array);
         $text = preg_replace('/&nbsp;/', '', $text);
         $json_data[$key]['content'] = $text;
       }
   } 
   // else {
   //     foreach ($json_data as $key => $value) {
   //         $text = templateParser::parse_template($value['content'], $module_array);
   //         $json_data[$key]['content'] = $text;
   //      }
   // }
   $json_encode_data = json_encode($json_data);
}
//parse variable
echo $json_encode_data;