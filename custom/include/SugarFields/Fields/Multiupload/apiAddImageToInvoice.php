<?Php
    $attachments;
    function autosendmail($invoiceID,$attachments){
        
        //config mail
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = "info@pure-electric.com.au";
        $mail->FromName = "PureElectric";
        $mail->IsHTML(true);
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->Subject = "Installer has sent photos for us !";
        $mail->Body = "Our Installer has updated the geotagged photos to our system, please check in this links ( <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoiceID."'>Invoice #".$invoiceNumber." Link</a>)";


        foreach( $attachments as $attachment )
        {
            // Set the different fields for the attachments
            $file_name  = $attachment['filename'];
            $location  = $attachment['location'];
            $mime_type  = $attachment['file_mime_type'];

            // Add attachment to email
            $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
        }

        //$mail->AddAddress("thienpb89@gmail.com");
        $mail->AddAddress("info@pure-electric.com.au");
        
        $mail->prepForOutbound();    
        $mail->setMailerForSystem();   
        $sent = $mail->send();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $invoiceNumber = $_REQUEST['invoiceNumber'];
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT id FROM aos_invoices WHERE number = $invoiceNumber AND deleted = 0";
        $ret = $db->query($sql);  
        $row = $db->fetchByAssoc($ret);
        $invoiceID = $row['id'];
        $invoice =  new AOS_Invoices();
        $invoice->retrieve($invoiceID);
        if(!empty($invoice->id)){
            $upload_path = $invoice->installation_pictures_c;
            echo $upload_path;
            $current_file_path =  dirname(__FILE__) . '/server/php/files/'.$upload_path;
            if(!file_exists ( $current_file_path )) {
                set_time_limit ( 0 );
                mkdir($current_file_path);
            }
            $images = $_FILES["images"];

            for($i = 0; $i < count($images['name']) ; $i++){
                $target_file = $current_file_path.'/'.basename($images["name"][$i]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                if (move_uploaded_file($images["tmp_name"][$i], $target_file)) {
                    
                    if (exif_imagetype($target_file) == 2) {
                        $new_name = str_replace($imageFileType,'jpeg',$target_file);
                        rename( $target_file,$new_name);
                    }else if(exif_imagetype($target_file) == 3){
                        $new_name = str_replace($imageFileType,'png',$target_file);
                        rename( $target_file,$new_name);
                    }else if(exif_imagetype($target_file) == 1){
                        $new_name = str_replace($imageFileType,'gif',$target_file);
                        rename( $target_file,$new_name);
                    } else {
                        return;
                    }                   
                    $imageType = strtolower(pathinfo($new_name,PATHINFO_EXTENSION));
                    if(is_file($new_name)){

                        $image['filename'] = str_replace($current_file_path.'/','',$new_name);
                        $image['location'] =  $new_name;
                        $image['file_mime_type'] = $imageType;
                        $attachments[$i] = $image;
                        //$type = strtolower(substr(strrchr($filename, '.'), 1));
                        $typeok = TRUE;
                        if($imageType == 'gif' || $imageType == 'jpg' || $imageType == 'jpeg' || $imageType == 'png') {
                            if(!file_exists ($current_file_path."/thumbnail/")) {
                                mkdir($current_file_path."/thumbnail/");
                            }
                            $thumb =  str_replace($imageFileType,$imageType,$current_file_path."/thumbnail/".basename($images["name"][$i]));
                            switch ($imageType) {
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
                }
            }
            autosendmail($invoiceID,$attachments);
        }else{
            die;
        }
    }
    die;
?>