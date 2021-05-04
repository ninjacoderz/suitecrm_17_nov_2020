<?php
  require_once('include/SugarPHPMailer.php');

  $lead_id = $_POST['lead_id'];
  $quote_id = $_POST['quote_id'];
  $data_input = [];
  $file_to_attach = array();

  $data_input['type_form'] = $_POST['type_form'];
  $data_input['email_customer'] = $_POST['email_customer'];
  $data_input['first_name'] = $_POST['firstname'];
  $data_input['last_name'] = $_POST['lastname'];
  $data_input['sms_send'] = $_POST['sms_send'];

  $data_input['primary_address_city'] = $_POST['suburb_customer'];
  $data_input['primary_address_state'] = $_POST['state_customer'];
  $data_input['primary_address_postalcode'] = $_POST['postcode_customer'];
  $data_input['your_street'] = $_POST['your_street'];

  $data_input['phone_number'] = $_POST['phonenumber'];
  $data_input['solar_aspiration'] = $_POST['solar_aspiration'];
  $data_input['distributor'] = $_POST['distributor'];
  $data_input['option_distributor'] = $_POST['option_distributor'];
  $data_input['first_solar'] = $_POST['first_solar'];
  $data_input['roof_type'] = $_POST['roof_type'];
  $data_input['roof_pitch'] = $_POST['roof_pitch'];
  $data_input['storeys'] = $_POST['many_storeys'];
  $data_input['phases'] = $_POST['many_phanes'];
  $data_input['meter_type'] = $_POST['meter_type'];
  $data_input['main_switch'] = $_POST['main_switch'];
  $data_input['distancetoswitch'] = $_POST['distancetoswitch'];
  $data_input['external_or_internal'] = $_POST['external_or_internal'];
  $data_input['prepared_by'] = $_POST['prepared_by'];
  $data_input['hear_about'] = $_POST['hear_about'];
  $data_input['preferred'] = $_POST['preferred'];
  $data_input['vic_rebate'] = $_POST['solar_vic_rebate'];
  $data_input['vic_loan'] = $_POST['solar_vic_loan'];
  $data_input['decription_internal_notes'] = $_POST['notes'];

  if($data_input['prepared_by'] == 'Matthew Wright') {
    $data_input['assigned_user'] = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
    $data_input['email_assigigned'] = 'matthew.wright@pure-electric.com.au';
  } else if($data_input['prepared_by'] == 'Paul Szuster') {
    $data_input['assigned_user'] = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
    $data_input['email_assigigned'] = 'paul.szuster@pure-electric.com.au';
  } else if($data_input['prepared_by'] == 'Michael Golden') {
    $data_input['assigned_user'] = '71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb';
    $data_input['email_assigigned'] = 'michael.golden@pure-electric.com.au';
  } else {
    $data_input['assigned_user'] = '1';
  }

  // init variable
  $lead = new Lead();
  $quote = new AOS_Quotes();

  // Case lead_id != null
  if (isset($lead_id)) {
    $lead ->retrieve($lead_id);
    update_quote_value_step_1($quote, $data_input, $lead);
    $quote->save();
    $file_to_attach = upload_file_form_solar($quote->id);
  }
  // Case lead_id == null

  // Create call by work-flow
  $workflow = new AOW_WorkFlow();
  $workflow->retrieve('5ac74a36-1248-6b28-85be-5c7f1832a865');
  $workflow->run_actions($quote, true);

  /** FUNCTION DECLARE */
  function update_quote_value_step_1($quote, $data_input, $lead){
    $data_solar_input = array(
      'solar_aspiration' => $data_input['solar_aspiration'],
      'electricity_distributor' => $data_input['distributor'],
      'first_solar_pv_system' => $data_input['first_solar'],
      'roof_type' => strtoupper($data_input['roof_type']),
      'roof_pitch' => $data_input['roof_pitch'],
      'storeys' => $data_input['storeys'],
      'phases' => $data_input['phases'],
      'meter_type' => $data_input['meter_type'],
      'main_switch' => $data_input['main_switch'],
      'distance_from_inverter_to_main_switchboard' => $data_input['distancetoswitch'],
      'external_or_internal_switchboard' => $data_input['external_or_internal'],
    );

    $quote->leads_aos_quotes_1leads_ida = $lead->id;

    $quote->name = $data_input['first_name'].' '.$data_input['last_name'].' '.$data_input['primary_address_city'].' '.$data_input['primary_address_state']." Solar";
    $quote->account_firstname_c = $data_input['first_name'];
    $quote->account_lastname_c = $data_input['last_name'];
    $quote->quote_type_c = 'quote_type_solar';
    $quote->lead_source_co_c = 'PureElectric';
    $quote->assigned_user_id = $data_input['assigned_user'];
    $quote->the_quote_prepared_c = "solar_quote_form";
    $quote->quote_note_inputs_c = json_encode($data_solar_input);
    $quote->distributor_c = $data_input['option_distributor'];
    $quote->main_switch_c = $data_input['main_switch'];
    $quote->meter_type_c = str_replace(" ", "" , $data_input['meter_type']);
    $quote->inverter_to_mainswitch_c = $data_input['distancetoswitch']."m";
    $quote->external_or_internal_c = $data_input['external_or_internal'];

    $quote->install_address_postalcode_c =  $data_input['primary_address_postalcode'];
    $quote->install_address_state_c = $data_input['primary_address_state'];
    $quote->install_address_city_c = $data_input['primary_address_city'];
    $quote->install_address_c = $data_input['your_street'];

    $quote->billing_address_street = $data_input['your_street'];
    $quote->billing_address_postalcode = $data_input['primary_address_postalcode'];
    $quote->billing_address_state = $data_input['primary_address_state'];
    $quote->billing_address_city = $data_input['primary_address_city'];
    $quote->billing_contact_id = $lead->contact_id;
    $quote->billing_account_id = $lead->account_id;

    if ( $data_input['phases'] == "Three Phases") {
        $quote->meter_phase_c = '3';
    } else if ( $data_input['phases'] == "Two Phases") {
        $quote->meter_phase_c = '2';
    } else if ( $data_input['phases'] == "Single Phase") {
        $quote->meter_phase_c = '1';
    }
  }
  function upload_file_form_solar($quote_id){    
    global $sugar_config;

    $quote = new AOS_Quotes();
    $quote->retrieve($quote_id);
    $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    $dirName        = $quote->pre_install_photos_c;
    $folderName     = $path . $dirName . '/';
    $thumbnail      = $path . $dirName . '/thumbnail' . '/';
    if (!file_exists($folderName)) {
        mkdir($path . $dirName, 0777, true);
        $folderName = $path . $dirName.'/';
    }
    //thienpb - code - add watermark
    $pricing_options = $quote->solar_pv_pricing_input_c;
    $pricings = json_decode(html_entity_decode($pricing_options));

    $data_option = [];
    $data_option['blank'] = false;
    $data_option['customer_name'] = $quote->account_firstname_c.' '.$quote->account_lastname_c;
    $data_option['address_1'] = $quote->install_address_c;
    $data_option['address_2'] = $quote->install_address_city_c.' '.$quote->install_address_state_c.' '.$quote->install_address_postalcode_c;
    if(count($_POST['files']['data-pe-files-switchboard']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-switchboard']['name'][$i] != ""){
                $file_type = 'Q'.$quote->number.'_Switchboard_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_');
                $file_type = 'Q'.$quote->number.'_Switchboard_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-switchboard']['tmp_name'][$i], $folderName.$file_type);
                // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Switchboard '.$i.' '.$checkgeo.'</a>';
                $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    
                $file_name =  $note->filename;
                $file_location = $sugar_config['upload_dir'].$note->id;
                $mime_type = $note->file_mime_type;
                $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                };
        };
    };
    if(count($_POST['files']['data-pe-files-upclose']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-upclose']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-upclose']['name'][$i] != ""){
                $file_type = 'Q'.$quote->number.'_Photo_upclose_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Photo_upclose_');
                $file_type = 'Q'.$quote->number.'_Photo_upclose_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-upclose']['tmp_name'][$i], $folderName.$file_type);
                // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Upclose '.$i.' '.$checkgeo.'</a>';
                $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    
                $file_name =  $note->filename;
                $file_location = $sugar_config['upload_dir'].$note->id;
                $mime_type = $note->file_mime_type;
                $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
        };
    }
    if(count($_POST['files']['data-pe-files-meterbox']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-meterbox']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-meterbox']['name'][$i] != ""){
                $file_type = 'Q'.$quote->number.'_Photo_meterbox_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Photo_meterbox_');
                $file_type = 'Q'.$quote->number.'_Photo_meterbox_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-meterbox']['tmp_name'][$i], $folderName.$file_type);
                // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Meterbox '.$i.' '.$checkgeo.'</a>';
                $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    
                $file_name =  $note->filename;
                $file_location = $sugar_config['upload_dir'].$note->id;
                $mime_type = $note->file_mime_type;
                $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
        };
    }
    if(count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-electricity-bill']['name'][$i] != ""){
                $file_type ='Q'.$quote->number.'_Electricity_bill_'.$i.'.'.pathinfo(basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Electricity_bill_');
                $file_type = 'Q'.$quote->number.'_Electricity_bill_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-electricity-bill']['tmp_name'][$i], $folderName.$file_type);
                // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Electricity bill '.$i.' '.$checkgeo.'</a>';
                $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    
                $file_name =  $note->filename;
                $file_location = $sugar_config['upload_dir'].$note->id;
                $mime_type = $note->file_mime_type;
                $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
        };
    }
    for( $j = 1; $j <= 6; $j++){
        $data_option['Option_number'] = $j;
        $data_option['Inverter'] = (!empty($pricings->{'inverter_type_'.($j)}) ? $pricings->{'inverter_type_'.($j)} : '');
        $data_option['Panel'] = (!empty($pricings->{'panel_type_'.($j)}) ? $pricings->{'panel_type_'.($j)} : '');
        $data_option['NumberOfPanels'] = ( ((int)$pricings->{'total_panels_'.($j)} > 0) ? $pricings->{'total_panels_'.($j)} : '0').' Panels';

        if(count($_POST['files']['data-design-upload-'.$j]['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-design-upload-'.$j]['tmp_name']); $i++) {
                if($_POST['files']['data-design-upload-'.$j]['name'][$i] != ""){
                    $file_type = 'Q'.$quote->number.'_Solar_Design'.$j.'_'.$i.'.'.pathinfo( basename($_POST['files']['data-design-upload-'.$j]['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Solar_Design'.$j);
                    $file_type =  'Q'.$quote->number.'_Solar_Design'.$j.'_'.$count.'.'.pathinfo( basename($_POST['files']['data-design-upload-'.$j]['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-design-upload-'.$j]['tmp_name'][$i], $folderName.$file_type);
                    // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Solar Design'.$j.'_'.$i.'</a>';
                    create_img_option($folderName,$file_type,$data_option);
                    $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    
                    $file_name =  $note->filename;
                    $file_location = $sugar_config['upload_dir'].$note->id;
                    $mime_type = $note->file_mime_type;
                    $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);
                };
            }
        };
    }

    // print_r($file_to_attach);
    return $file_to_attach;
}
function checkCountExistPhoto($file_type,$folderName,$new_name){
    $data_exist= [];
    $get_all_photo = dirToArray($folderName);
    foreach ($get_all_photo as $photo_exist) {
        if( strpos($photo_exist, $new_name) == true){
            $data_exist[] = $photo_exist;
        }
    }
    $count =  count($data_exist);
    return $count;   
}
function dirToArray($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}
function addToNotes($file,$folderName,$parent_id,$parent_type){
    // $listFile = dirToArray($folderName);
    resize_image($file, $folderName);
    
    $noteTemplate = new Note();
    $noteTemplate->id = create_guid();
    $noteTemplate->new_with_id = true; // duplicating the note with files
    $noteTemplate->parent_id = $parent_id;
    $noteTemplate->parent_type = $parent_type;
    $noteTemplate->date_entered = '';
    $noteTemplate->file_mime_type = mime_content_type($folderName.$file);
    $noteTemplate->filename = $file;
    $noteTemplate->name = $file;
    $noteTemplate->save();

    $source =  $folderName.$file ;
    $destination = realpath(dirname(__FILE__) . '/../../../').'/upload/'.$noteTemplate->id;
    if (!symlink($source, $destination)) {
        $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
    }
    
    return $noteTemplate;
}
function resize_image($file, $current_file_path) {
    $type = strtolower(substr(strrchr($file, '.'), 1));
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
        if(!file_exists ($current_file_path."/thumbnail/")) {
            mkdir($current_file_path."/thumbnail/");
        }
        $thumb =  $current_file_path."/thumbnail/".$file;
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
            list($w, $h) = getimagesize($current_file_path.'/'. $file);

            $src = $src_func($current_file_path.'/'. $file);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
    } 
}
function create_img_option($path,$fullname,$data_option){
    
    //create image png
    $source = $path.$fullname;

    $file_ = explode(".",$fullname);
    
    $type = end($file_);
    unset($file_[count($file_)-1]);
    $filename = implode('.', $file_);

    $new_name='';
    $type ='';
    if (exif_imagetype($source) == 2) {
        $type = 'jpeg';
        $new_name = $path.$filename.'.jpg';
        rename( $source,$new_name);
    }else if(exif_imagetype($source) == 3){
        $type = 'png';
        $new_name = $path.$filename.'.png';
        rename( $source,$new_name);
    }else if(exif_imagetype($source) == 1){
        $type = 'gif';
        $new_name = $path.$filename.'.gif';
        rename( $source,$new_name);
    } else {
        return;
    }

    //append image info
    $source = $new_name;

    // get size of image source
    list($w_source, $h_source) = getimagesize($source);

    $font = $path.'../arial.ttf';
    // add text for image info
    if($w_source >= 500){
        list($w_info, $h_info) = getimagesize($path.'../dessign-image_full.jpeg');
        $img_info = imagecreatefromjpeg($path.'../dessign-image_full.jpeg');
        $black = imagecolorallocate($img_info, 0, 0, 0);
        $orange = imagecolorallocate($img_info,243, 143, 42);

        if($data_option['blank'] == true){
            imagettftext($img_info,24,0,315,65,$black,$font,'Blank');
        }
        imagettftext($img_info,30,0,145,90,$orange,$font,$data_option['Option_number']);
        imagettftext($img_info,22,0,560,40,$black,$font,$data_option['customer_name']);
        imagettftext($img_info,16,0,560,70,$black,$font,$data_option['address_1']);
        imagettftext($img_info,16,0,560,95,$black,$font,$data_option['address_2']);
        imagettftext($img_info,22,0,215,40,$black,$font,$data_option['NumberOfPanels']);
        imagettftext($img_info,16,0,215,70,$black,$font,$data_option['Panel']);
        imagettftext($img_info,16,0,215,95,$black,$font,$data_option['Inverter']);
    }else{
        $black = imagecolorallocate($img_info, 0, 0, 0);
        $orange = imagecolorallocate($img_info,243, 143, 42);
        list($w_info, $h_info) = getimagesize($path.'../dessign-image_500.png');
        $img_info = imagecreatefrompng($path.'../dessign-image_500.png');
        if($data_option['blank'] == true){
            imagettftext($img_info,14,0,170,35,$orange,$font,'Blank');
        }
        imagettftext($img_info,20,0,72,50,$orange,$font,$data_option['Option_number']);
        imagettftext($img_info,14,0,305,22,$black,$font,$data_option['customer_name']);
        imagettftext($img_info,9,0,305,40,$black,$font,$data_option['address_1']);
        imagettftext($img_info,9,0,305,55,$black,$font,$data_option['address_2']);
        imagettftext($img_info,14,0,110,22,$black,$font,$data_option['NumberOfPanels']);
        imagettftext($img_info,9,0,110,40,$black,$font,$data_option['Panel']);
        imagettftext($img_info,9,0,110,55,$black,$font,$data_option['Inverter']);
    }

    $scale = ($h_info/$w_info);
    $new_w_info = $w_source;
    $new_h_info = intval($w_source*$scale);

    $img_info_resize = imagecreatetruecolor($new_w_info, $new_h_info);
    imagecopyresampled($img_info_resize,$img_info,0,0,0,0,$new_w_info,$new_h_info,$w_info,$h_info);

    // create outputImage
    $outputImage = imagecreatetruecolor($w_source, ($h_source + $new_h_info));
    $white = imagecolorallocate($outputImage, 255, 255, 255);
    imagefill($outputImage, 0, 0, $white);

    $src_function = 'imagecreatefrom'.$type;
    $write_function = 'image'.$type;
    $img_source = $src_function($source);
    
    // merge img_info and img_source to outputImage
    imagecopyresized($outputImage,$img_source,0,0,0,0, $w_source,$h_source,$w_source,$h_source);
    imagecopyresized($outputImage,$img_info_resize,0,$h_source,0,0, $new_w_info,$new_h_info,$new_w_info,$new_h_info);
    header('Content-Type: image/'+$type);
    $write_function($outputImage,$source);

    imagedestroy($img_info);
    imagedestroy($img_source);
    imagedestroy($outputImage);

    //create thumbnail
    if($type == 'gif' || $type == 'jpeg' || $type == 'png') {
        //create thumbnail
        if(!file_exists ($path."thumbnail/")) {
            mkdir($path."thumbnail/");
        }
        $typeok = TRUE;
        $thumb =  $path."thumbnail/".$filename.'.'.$type;
        switch ($type) {
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $thumb =  $path."thumbnail/".$filename.'.jpg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            default: $typeok = FALSE; break;
        }
        if($typeok){
            list($w, $h) = getimagesize($new_name);

            $src = $src_func($new_name);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
        
    } 
}