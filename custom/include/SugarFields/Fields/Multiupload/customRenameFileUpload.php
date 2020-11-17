<?php

//thienpb custom rename files
if($_POST){
    //get resquest data
    $files = $_POST['files'];
    $files = json_decode(html_entity_decode($files));

    $record = $_POST['record'];
    $installation_pictures_c = $_POST['installation_pictures_c'];
    $primary_address_street = $_POST['primary_address_street'];
    $primary_address_city = $_POST['primary_address_city'];
    $distributor_c = $_POST['distributor_c'];

    $current_file_path =  dirname(__FILE__)."/server/php/files/" . $installation_pictures_c ;
    $address = $primary_address_street;

    //custom file name with address
    $city = $primary_address_city;
    $address_city = $address.'_'.strtolower($city);
    
    $file_attachmens = array();
    $block_files_for_email = array();

    // read all file need rename
    if(count($files)) foreach($files as $file){
        if($file->rename_option != 0){
            $extension=end(explode(".", $file->file_name));
            $new_name = "";
            switch ($file->rename_option){
                case "20":
                    $new_name = "Blank_".str_replace(' ', '_', $address_city);
                    break;
                case "21":
                    $new_name = "Design_".str_replace(' ', '_', $address_city);
                    break;
                case "22":
                    $new_name = "Switchboard";
                    break;
                case "23":
                    $new_name = "Map";
                    break;
                case "24":
                    $new_name = "Street_View";
                    break;
                case "25":
                    $new_name = "Bill";
                    break;
                case "26"://dung code - add option Meter Box
                    $new_name = "Meter_Box";
                    break;
                case "27"://dung code - add option Meter Box
                    $new_name = "Acceptance";
                    break;
                case "28"://dung code - add option Meter Box
                    $new_name = "Pricing";
                    break;
                case "29"://dung code - add option Meter Box
                    $new_name = "House_Plans";
                    break;
                case "30"://dung code - add option Grid approval
                    $path_parts = pathinfo($file->file_name);
                    $new_name = $path_parts['filename'] ."_" .$distributor_c;
                    break;
                case "32"://thienpb code
                    $new_name = "Roof_Pitch";
                    break;
            }
            // If new name look like old name

            if ($new_name.'.'.$extension == $file->file_name) return;
            $i = 1;
            $will_rename = $new_name;
            while( !empty(glob($current_file_path.'/'.$will_rename."*")) || !empty(glob($current_file_path.'/'.$will_rename.("_".str_replace(' ', '_', $file->suffix))."*")) )
            {
                $will_rename = $new_name.'_'.$i;
                if ($will_rename.".".$extension == $file->file_name) return;
                $i++;
            }

            if(isset($file->suffix) && $file->suffix != "") {
                $will_rename .= ("_".str_replace(' ', '_', $file->suffix));
            }

            $will_rename .= ('.'.$extension);
            // If the file posted have same name that we generated ( this file is rename one time  )
            if ($will_rename == $file->file_name) return;
            
            if(strpos($will_rename,'/') !== false){
                $will_rename =  str_replace('/','-',$will_rename);
            }
            
            rename($current_file_path."/".$file->file_name, $current_file_path."/".$will_rename);
            rename($current_file_path."/thumbnail/".$file->file_name, $current_file_path."/thumbnail/".$will_rename);
        }
        if($file->is_attachment){
            $file_attachmens[] = $will_rename?$will_rename:$file->file_name;
        }

        //thienpb code
        if($file->is_block){
            $block_files_for_email[] = $will_rename?$will_rename:$file->file_name;
        }
    }

    $lead = new Lead();
    $lead = $lead->retrieve($record);
    $lead->file_attachment_c = json_encode($file_attachmens);
    $lead->block_files_for_email_c = json_encode($block_files_for_email);
    $lead->save();
}else{
    die();
}
