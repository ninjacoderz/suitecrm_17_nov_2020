<?php
$pre_install_photos_c = trim($_REQUEST['pre_install_photos_c']);

if(isset($_REQUEST['lead_id'])){
        $lead_id = trim($_REQUEST['lead_id']);
        $quote_id = '';
}else if($_REQUEST['quote_id']){
        $quote_id = trim($_REQUEST['quote_id']);
        $lead_id = '';
}else if($_REQUEST['invoice_id']) {
        $pre_install_photos_c = trim($_REQUEST['installation_pictures_c']);
        $lead_id = '';
        $quote_id = '';
        $invoice_id = trim($_REQUEST['invoice_id']);
}else{
        die;
}

//app 1
$servername = "database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com";
$username = "root";
$password = "binhmatt2018";
$database_name = "message";
$message_folder = '/message/';
get_all_file_from_message_app($servername,$username,$password,$database_name,$lead_id,$quote_id,$invoice_id,$pre_install_photos_c,$message_folder);
//app 2
$servername = "database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com";
$username = "root";
$password = "binhmatt2018";
$database_name = "message2";
$message_folder = '/message2/';
get_all_file_from_message_app($servername,$username,$password,$database_name,$lead_id,$quote_id,$invoice_id,$pre_install_photos_c,$message_folder);

//function get all file from message app 
function get_all_file_from_message_app($servername,$username,$password,$database_name,$lead_id,$quote_id,$invoice_id,$pre_install_photos_c,$message_folder) {
        $db = DBManagerFactory::getInstance();

        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        // $database_name = "message";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $database_name);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        // variables post
        // $lead_id = trim($_REQUEST['lead_id']);
        // $pre_install_photos_c = trim($_REQUEST['pre_install_photos_c']);
        
        // Get files from app message 1
                // get array url folder
        if($lead_id != ''){
                $query = "SELECT messages.message_content as link_file FROM messages 
                INNER JOIN conversations ON conversations.id  = messages.conversation_id 
                INNER JOIN accounts ON conversations.to_user  = accounts.id 
                WHERE 
                        messages.message_type = 'image'
                AND accounts.crm_ref = '".$lead_id."' ";
        }else if($quote_id){
                $query_phone = "SELECT contacts.phone_mobile,accounts_cstm.mobile_phone_c FROM aos_quotes
                INNER JOIN accounts_cstm ON aos_quotes.billing_account_id = accounts_cstm.id_c
                INNER JOIN contacts ON aos_quotes.billing_contact_id = contacts.id
                WHERE aos_quotes.id = '".$quote_id."' AND aos_quotes.deleted = 0";
                $result =  $db->query($query_phone);
                $where_phone = '';
                if($result->num_rows > 0){
                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        if($row['phone_mobile'] != '' && $row['mobile_phone_c'] == ''){
                                $phone = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['phone_mobile'])));
                                $where_phone = " AND accounts.phone = '".$phone."'";
                        }else if($row['mobile_phone_c'] != '' && $row['phone_mobile'] == ''){
                                $phone = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['mobile_phone_c'])));
                                $where_phone = " AND accounts.phone = '".$phone."'";
                        }else if($row['phone_mobile'] != '' && $row['mobile_phone_c'] != ''){
                                $phone1 = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['phone_mobile'])));
                                $phone2 = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['mobile_phone_c'])));
                                $where_phone = " AND (accounts.phone = '".$phone1."' OR accounts.phone = '".$phone2."')";
                        }else{
                                die;
                        }
                        
                }

                $query = "SELECT messages.message_content as link_file FROM messages 
                INNER JOIN conversations ON conversations.id  = messages.conversation_id 
                INNER JOIN accounts ON conversations.to_user  = accounts.id 
                WHERE messages.message_type = 'image'".$where_phone;
               
        }else if ($invoice_id) {
                $query_phone = "SELECT contacts.phone_mobile,accounts_cstm.mobile_phone_c FROM aos_invoices
                INNER JOIN accounts_cstm ON aos_invoices.billing_account_id = accounts_cstm.id_c
                INNER JOIN contacts ON aos_invoices.billing_contact_id = contacts.id
                WHERE aos_invoices.id = '".$invoice_id."' AND aos_invoices.deleted = 0";

                $result =  $db->query($query_phone);
                $where_phone = '';
                if($result->num_rows > 0){
                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        if($row['phone_mobile'] != '' && $row['mobile_phone_c'] == ''){
                                $phone = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['phone_mobile'])));
                                $where_phone = " AND accounts.phone = '".$phone."'";
                        }else if($row['mobile_phone_c'] != '' && $row['phone_mobile'] == ''){
                                $phone = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['mobile_phone_c'])));
                                $where_phone = " AND accounts.phone = '".$phone."'";
                        }else if($row['phone_mobile'] != '' && $row['mobile_phone_c'] != ''){
                                $phone1 = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['phone_mobile'])));
                                $phone2 = preg_replace("/^0/", "+61", preg_replace('/\D/', '', str_replace(" ","",$row['mobile_phone_c'])));
                                $where_phone = " AND (accounts.phone = '".$phone1."' OR accounts.phone = '".$phone2."')";
                        }else{
                                die;
                        }
                }

                $query = "SELECT messages.message_content as link_file FROM messages 
                INNER JOIN conversations ON conversations.id  = messages.conversation_id 
                INNER JOIN accounts ON conversations.to_user  = accounts.id 
                WHERE messages.message_type = 'image'".$where_phone;
        }else{
                die;
        }
                $result =  $conn->query($query);
        
                $array_link_result = array();
                if($result->num_rows > 0){
                        $i = 0;
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        $array_link_result[$i]=$row['link_file'];
                        $i++;
                        }
                        $key = 0 ;
                }      
                //check folder and  get file img
                $condition_file_from_crm = 'https://suitecrm.pure-electric.com.au/public_files/';
                $path_save_file = dirname(__FILE__) .'/server/php/files/' .$pre_install_photos_c .'/';
                foreach ($array_link_result as $key => $value) {
                        $out_source = '';
                        $in_source = '';
                        $file_name = '';
                        if (strpos($value, $condition_file_from_crm) !== false) {
                                $array_link = explode('/public_files/',$value);
                                // $file_name = 'msg_'.$key.'_' .$array_link[1];
                                $file_name = $array_link[1];

                                $out_source = $path_save_file.'msg_'.$key.'_'.$file_name;
                                $in_source = realpath(dirname(__FILE__) . '/../../../../../').'/public_files/'.$file_name;     
                                //copy file root
                                if(!file_exists (dirname(__FILE__) .'/server/php/files/' .$pre_install_photos_c .'/')) {
                                mkdir(dirname(__FILE__) .'/server/php/files/' .$pre_install_photos_c .'/');
                                }
                                // copy($in_source,$out_source);
                                if (symlink($in_source,$out_source)) {
                                        // create image for file pdf and create thumbnail
                                        create_image_from_pdf($in_source,'msg_'.$key.'_'.$file_name,$path_save_file);
                                        create_thumbnail($in_source,'msg_'.$key.'_'.$file_name,$path_save_file);
                                }
                                
                        }else {
                                $array_link = explode('/',$value);
                                $file_name = 'msg_' .$key.'_'.$array_link[1];
                                $out_source = $path_save_file.$file_name;
                                $in_source = realpath(dirname(__FILE__) . '/../../../../../../').$message_folder.'jQuery-File-Upload-9.21.0/server/php/files/'.$value;
                                // copy file root
                                if(!file_exists (dirname(__FILE__) .'/server/php/files/' .$pre_install_photos_c .'/')) {
                                        mkdir(dirname(__FILE__) .'/server/php/files/' .$pre_install_photos_c .'/');
                                }
                                copy($in_source,$out_source);
                                // create image for file pdf and create thumbnail
                                create_image_from_pdf($out_source,$file_name,$path_save_file);
                                create_thumbnail($out_source,$file_name,$path_save_file);       
                        };
                }
}

//function create image from file pdf
function create_image_from_pdf($source,$file_name,$path_save_file){
        $arr_name_file = explode(".", $file_name);
        $type = $arr_name_file[1];
        $new_name_file_pdf =$arr_name_file[0] ;
        $typeok = TRUE;
        $path_to_write = '';
        if($type == 'pdf'){
                $l_Image = new Imagick();
                $l_Image->setResolution(150, 150);
                $l_Image->readImage($source);
                $l_Image = $l_Image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

                $l_Image->setCompression(Imagick::COMPRESSION_JPEG);
                $l_Image->setImageBackgroundColor('white');
                $l_Image->setCompressionQuality (100);
                $l_Image->stripImage();
                $l_Image->setImageFormat("jpg");
                $path_to_write = $path_save_file .$new_name_file_pdf.'.jpg';
                $l_Image->writeImage($path_to_write);
                $l_Image->clear();
                $l_Image->destroy();
                //create thumbnail
                create_thumbnail($path_to_write,$new_name_file_pdf.'.jpg',$path_save_file);
        }
        return $path_to_write;
}

//function create thumbnail from source
function create_thumbnail($source,$file_name,$path_save_file){
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        $typeok = TRUE;
        if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                if(!file_exists ($path_save_file."/thumbnail/")) {
                mkdir($path_save_file."/thumbnail/");
                }
                $thumb =  $path_save_file."/thumbnail/".$file_name;
                switch ($type) {
                case 'jpg': // Both regular and progressive jpegs
                case 'jpeg':
                        $src_func = 'imagecreatefromjpeg';
                        $write_func = 'imagejpeg';
                        $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                        break;
                case 'gif':
                        $src_func = 'imagecreatefromgif';
                        $write_func = 'imagegif';
                        $image_quality = null;
                        break;
                case 'png':
                        $src_func = 'imagecreatefrompng';
                        $write_func = 'imagepng';
                        $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                        break;
                default: $typeok = FALSE; break;
                }
                if ($typeok){
                list($w, $h) = getimagesize($source);

                $src = $src_func($source);
                $new_img = imagecreatetruecolor(80,80);
                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                $write_func($new_img,$thumb, $image_quality);
                
                imagedestroy($new_img);
                imagedestroy($src);
                }
        }         
}

