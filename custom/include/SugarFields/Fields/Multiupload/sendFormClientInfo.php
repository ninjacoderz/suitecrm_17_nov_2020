<?php
    require_once('include/SugarPHPMailer.php');  
    $db = DBManagerFactory::getInstance();
    $billingStreet  = $_POST['billing_street'];
    $billingCity    = $_POST['billing_city'];
    $billingState   = $_POST['billing_state'];
    $billingPortal  = $_POST['billing_postal_code'];
    $billingCountry = $_POST['billing_country'];

    $description    = '';

    $bodytext       = '';
    $path           = dirname(__FILE__) . '/server/php/files/';
    $dirName        = $_POST['installation_pictures_c'];
    $folderName     = $path . $dirName . '/';
    $thumbnail      = $path . $dirName . '/thumbnail' . '/';
    $url            = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$_POST['quote_id'];

    $description   .= 'Inverter capacity (e.g. 5 kW, 6 kW etc):'. $_POST['inverter_capacity'].' &#13;&#10;';
    $description   .= 'Inverter type (e.g. SMA, Fronius etc):'.$_POST['inverter_type'].' &#13;&#10;';
    $description   .= 'Solar panel wattage (e.g. 270 W, 300W etc):'.$_POST['solar_wattage'].' &#13;&#10;';
    $description   .= 'Number of solar panels on your roof:'.$_POST['number_solar'].' &#13;&#10;';
    $description   .= 'Type of solar panels (e.g. Jinko, Q-Cell, Sunpower etc):'.$_POST['type_solar'];

    $quote = new AOS_Quotes();
    $quote_id = $_POST['quote_id'];
    $quote->retrieve($quote_id);

    $quote->billing_address_street = $billingStreet;
    $quote->billing_address_city = $billingCity;
    $quote->billing_address_state = $billingState;
    $quote->billing_address_postalcode = $billingPortal;
    $quote->billing_address_country = $billingCountry;

    $quote->install_address_c = $billingStreet;
    $quote->install_address_city_c = $billingCity;
    $quote->install_address_state_c = $billingState;
    $quote->install_address_postalcode_c = $billingPortal;
    $quote->install_address_country_c = $billingCountry;

    $quote->description = $description;

    if (!file_exists($folderName)) {
        mkdir($path . $dirName, 0777, true);
        move_uploaded_file($_FILES['switchboardPhoto']['tmp_name'], $folderName . basename('switchboard.'.explode(".", $_FILES['switchboardPhoto']['name'])[1]));
        move_uploaded_file($_FILES['meterPhoto']['tmp_name'], $folderName . basename('meter.'.explode(".", $_FILES['meterPhoto']['name'])[1]));
        move_uploaded_file($_FILES['billPhoto']['tmp_name'], $folderName . basename('bill.'.explode(".", $_FILES['billPhoto']['name'])[1]));
        //read all files
        $listFile = dirToArray($folderName);
        foreach($listFile as $file) {
            resize_image($file, $folderName);
            
            $noteTemplate = new Note();
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            $noteTemplate->parent_id = $quote_id;
            $noteTemplate->parent_type = 'AOS_Quotes';
            $noteTemplate->date_entered = '';
            $noteTemplate->file_mime_type = mime_content_type($folderName.$file);
            $noteTemplate->filename = $file;
            $noteTemplate->name = $file;
            $noteTemplate->save();
        }
        //create note
    } else {
        delete_directory($thumbnail);
        move_uploaded_file($_FILES['switchboardPhoto']['tmp_name'], $folderName . basename('switchboard.'.explode(".", $_FILES['switchboardPhoto']['name'])[1]));
        move_uploaded_file($_FILES['meterPhoto']['tmp_name'], $folderName . basename('meter.'.explode(".", $_FILES['meterPhoto']['name'])[1]));
        move_uploaded_file($_FILES['billPhoto']['tmp_name'], $folderName . basename('bill.'.explode(".", $_FILES['billPhoto']['name'])[1]));
        //read all files
        $listFile = dirToArray($folderName);
        foreach($listFile as $file) {
            resize_image($file, $folderName);
            
            $noteTemplate = new Note();
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            $noteTemplate->parent_id = $quote_id;
            $noteTemplate->parent_type = 'AOS_Quotes';
            $noteTemplate->date_entered = '';
            $noteTemplate->file_mime_type = mime_content_type($folderName.$file);
            $noteTemplate->filename = $file;
            $noteTemplate->name = $file;
            $noteTemplate->save();
        }
    }
    $deletequery = "DELETE FROM pending_quote_token WHERE token ='".$_POST['token']."'";
    $db->query($deletequery); 
    $quote->save();
    $customer_name = $quote->account_firstname_c . ' ' . $quote->account_lastname_c;
    if (($billingStreet != '') && ($billingCity != '') && ($billingState != '') && ($billingPortal != '') && ($billingCountry != '')) {
        
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = "accounts@pure-electric.com.au";
        $mail->FromName = "PureElectric Accounts";
        $mail->IsHTML(true);
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->Subject =  'Automatic update! '.$customer_name.' has successfully submitted bill, switchboard and meter photos via the submission form';
        $bodytext .= $customer_name.' has successfully submitted bill, switchboard and meter photos via the submission form. Please check the files uploaded to their quote: '.$url.' &#13;&#10;';
        $mail->Body = $bodytext;
        $mail->AddAddress('admin@pure-electric.com.au');
        //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
        $mail->AddCC('info@pure-electric.com.au');
        $mail->prepForOutbound();    
        $mail->setMailerForSystem();   
        $sent = $mail->send();
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

    function delete_directory($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                    unlink($dirname."/".$file);
                else
                    delete_directory($dirname.'/'.$file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
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
?>