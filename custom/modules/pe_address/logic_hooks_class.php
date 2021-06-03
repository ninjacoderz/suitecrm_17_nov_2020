<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class CreateFolderUpload
    {
        function before_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            if($old_fields == false || $bean->installation_pictures_c == ""){
                $uuid = md5(uniqid(mt_rand(), true));
                $guid =  $prefix.substr($uuid,0,8)."-".
                substr($uuid,8,4)."-".
                substr($uuid,12,4)."-".
                substr($uuid,16,4)."-".
                substr($uuid,20,12);
                $bean->installation_pictures_c  = $guid;
                // $bean->save();
                if ($old_fields == false && $bean->map_data) {
                    $check = 1;
                    $map_data = json_decode(html_entity_decode($bean->map_data));
                    $data = $map_data->data_img;
                    $map_data->data_img = '';
                    $bean->map_data = json_encode($map_data);
                    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                        $data = substr($data, strpos($data, ',') + 1);
                        $type = strtolower($type[1]); // jpg, png, gif
                    
                        if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                            $check = 0;
                        }
                        $data = str_replace( ' ', '+', $data );
                        $data = base64_decode($data);
                    
                        if ($data === false) {
                            $check = 0;
                        }
                    } else {
                        $check = 0;
                    }

                    if ($check == 1) {
                        $this->copyToFolder($bean->installation_pictures_c,$data);
                    }
                
                }
            }
        }

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
                $this->create_thumbnail($file,'Image_Site_Detail.jpg',$folderName);
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
    }

    class CreateInternalNoteForAddress
    {
        function after_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            global $current_user;
            // $format = 'Y-m-d H:i:s';
            // $date = DateTime::createFromFormat($format, $bean->date_modified);
            // // $test = DateTime::createFromFormat($format, "2020-09-28 17:51:57");
            // $date_note = $date->format("d/m/Y h:ia");

            if ($old_fields == false) { 
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT pe_internal_note.id FROM pe_internal_note 
                        LEFT JOIN pe_address_pe_internal_note_1_c ON pe_address_pe_internal_note_1_c.pe_address_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id 
                        LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                        WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated'  
                        AND pe_internal_note.description ='Create New Address'
                        AND pe_address_pe_internal_note_1_c.pe_address_pe_internal_note_1pe_address_ida ='$bean->id'  
                        AND pe_internal_note.deleted = 0
                        ORDER BY `pe_internal_note`.`date_modified` DESC";    
                $ret = $db->query($sql);
                if ($ret->num_rows == 0) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Create New Address';
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('pe_address_pe_internal_note_1');
                    $bean_intenal_notes->pe_address_pe_internal_note_1->add($bean->id);
                }
            } else {
                if ($old_fields['assigned_user_id'] != $bean->assigned_user_id) {
                    $bean_intenal_notes = new  pe_internal_note();
                    if ($old_fields['assigned_user_id']) {
                        $old_user = new User();
                        $old_user->retrieve($old_fields['assigned_user_id']);
                        if ($old_user->id) {
                            $old_fields['assigned_user_name'] = $old_user->name;
                        }
                    }
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = "Change Assigned User from {$old_fields['assigned_user_name']} to {$bean->assigned_user_name}";
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    $bean_intenal_notes->load_relationship('pe_address_pe_internal_note_1');
                    $bean_intenal_notes->pe_address_pe_internal_note_1->add($bean->id);
                }
            }
        }
    }

