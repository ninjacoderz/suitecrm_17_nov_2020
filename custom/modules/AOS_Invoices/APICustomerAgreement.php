<?php
use setasign\Fpdi\Fpdi;
require_once('text/fpdf.php');
require_once('text/src/autoload.php');
require_once('include/SugarPHPMailer.php');

$method         = $_POST['method'];
$invoiceID       = $_POST['invoiceID'];
$Invoice = new AOS_Invoices();
$Invoice->retrieve($invoiceID);

if($Invoice->id == ""){
    echo json_encode(array('msg'=>'error'));die();
}

if($Invoice->installation_pictures_c == ''){
    $Invoice->installation_pictures_c = create_guid();
    $Invoice->save();
}

switch ($method) {
    case 'getCustomerInfo':
        $data_return = render_json_invoice($Invoice);
        echo json_encode($data_return);die();
        break;
    case 'generatePDF':
        
        $dataRequest = $_REQUEST['dataRequest'];
        $data_return = render_json_invoice($Invoice);
        createFileSignature($dataRequest,$Invoice->installation_pictures_c);
        $dataPDF = generatePDF($dataRequest,$Invoice->installation_pictures_c);
        $data_return['dataPDF'] = $dataPDF;
        echo json_encode($data_return);die();
        break;
    case 'IAgree':
        $dataRequest = $_REQUEST['dataRequest'];
        $data_return = render_json_invoice($Invoice);
        CustomerAgree($Invoice->installation_pictures_c);
        Send_Email_Notification($Invoice,$dataRequest);
        echo json_encode($data_return);die();
        break;
    case 'generatePDF_solor':
    
        $dataRequest = $_REQUEST['dataRequest'];
        $data_return = render_json_invoice($Invoice);
        createFileSignature_solor($dataRequest,$Invoice->installation_pictures_c);
        $dataPDF = generatePDF_solor($dataRequest,$Invoice->installation_pictures_c);
        $data_return['dataPDF'] = $dataPDF;
        echo json_encode($data_return);die();
        break;
    case 'IAgree_solor':
        $dataRequest = $_REQUEST['dataRequest'];
        $data_return = render_json_invoice($Invoice);
        CustomerAgreeSolor($Invoice->installation_pictures_c);
        Send_Email_Notification_solor($Invoice,$dataRequest);
        echo json_encode($data_return);die();
        break;
        
    default:
        echo json_encode(array('msg'=>'error'));die();
        break;
}



function render_json_invoice($Invoice){
    $Contact = new Contact();
    $Contact->retrieve($Invoice->billing_contact_id);

    if($Invoice->installation_date_c != ''){
        $dateInfos = explode(" ",$Invoice->installation_date_c);
        $dateInfos = explode("/",$dateInfos[0]);
        $inv_install_date_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
        $installation_date_c = date("d/m/Y", strtotime($inv_install_date_str));
    }else{
        $installation_date_c = date("d/m/Y", time());
    }

    $result = array(
        'id' =>$Invoice->id,
        'number' =>$Invoice->number,
        'name' =>$Invoice->name,
        'install_address_c' =>$Invoice->install_address_c,
        'install_address_city_c' =>$Invoice->install_address_city_c,
        'install_address_state_c' =>$Invoice->install_address_state_c,
        'install_address_postalcode_c' =>$Invoice->install_address_postalcode_c,
        'first_name'=> $Contact->first_name,
        'last_name' => $Contact->last_name,
        'phone_mobile' => $Contact->phone_mobile,
        'email1' => $Contact->email1,
        'department' => $Contact->department,
        'installation_date_c' => $installation_date_c,
        'check_contact_type_c' => $Contact->check_contact_type_c,
        'abn_c' => $Invoice->abn_c,
        'date' => date("d/m/Y", time())
    );   
    return $result;
}


function generatePDF($dataRequest,$foldeId){
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    //$pdf->setSourceFile('/custom/modules/AOS_Invoices/text/ttt.pdf');
    $pdf->setSourceFile(__DIR__.'/text/goc3.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetTextColor(0, 0, 0);
    
    
    $pdf->Write($pdf->SetXY(128, 214), $dataRequest['your_install_date']);
    $pdf->Write($pdf->SetXY(128, 227), html_entity_decode($dataRequest['your_company_name'],ENT_QUOTES));
    $pdf->Write($pdf->SetXY(35, 229), html_entity_decode($dataRequest['first_name'] .' '.$dataRequest['last_name'],ENT_QUOTES) );
    $pdf->Write($pdf->SetXY(35, 240), html_entity_decode($dataRequest['your_position'],ENT_QUOTES));
    $pdf->Write($pdf->SetXY(128, 240), html_entity_decode($dataRequest['phone_number'],ENT_QUOTES));
    $pdf->Write($pdf->SetXY(35, 255),html_entity_decode($dataRequest['your_street'].','.$dataRequest['suburb_customer'].','.$dataRequest['state_customer'].','.$dataRequest['postcode_customer'],ENT_QUOTES));
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf->Image($ds_dir.'/signature_draft.png' ,35,210,50,10);
    $fp = fopen($ds_dir.'/CustomerAgreement_Draft.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/CustomerAgreement_Draft.pdf', 'F');
   
    return base64_encode($pdf->Output($ds_dir.'/CustomerAgreement_Draft.pdf', "S"));
    
}

function generatePDF_solor($dataRequest,$foldeId){
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    //$pdf->setSourceFile('/custom/modules/AOS_Invoices/text/ttt.pdf');
    $pdf->setSourceFile(__DIR__.'/text/solar-origin.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Write($pdf->SetXY(35, 65),  html_entity_decode($dataRequest['account_name'],ENT_QUOTES));
    $pdf->Write($pdf->SetXY(118, 275), $dataRequest['your_date']);
    $pdf->Write($pdf->SetXY(118, 235), html_entity_decode($dataRequest['customer_contact_name'],ENT_QUOTES));
    $pdf->Write($pdf->SetXY(118, 262), $dataRequest['your_abn']);
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf->Image($ds_dir.'/signature_solor_draft.png' ,120,213,50,10);
    $fp = fopen($ds_dir.'/CustomerAgreement_Solar_Draft.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/CustomerAgreement_Solar_Draft.pdf', 'F');
   
    return base64_encode($pdf->Output($ds_dir.'/CustomerAgreement_Solar_Draft.pdf', "S"));
    
}


function createFileSignature($dataRequest,$foldeId){
    $img = $dataRequest['signatureData'];
    if (strpos($img, 'data:image/png;base64') === 0) {
               $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
        mkdir($ds_dir);
        $time = time();
        $file = $ds_dir ."/signature_draft.png";
        $a = file_put_contents($files,$data);
        if (file_put_contents($file,$data)) {
          $result['img'] = 'http://' . $_SERVER['SERVER_NAME'] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$foldeId ."/signature_draft.png";
          $result['img_name'] = 'signature_draft.png';
          create_thumbnail($file,'signature_draft.png',$ds_dir);
        } 
     }
}


function createFileSignature_solor($dataRequest,$foldeId){
    $img = $dataRequest['signatureData'];
    if (strpos($img, 'data:image/png;base64') === 0) {
               $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
        mkdir($ds_dir);
        $time = time();
        $file = $ds_dir ."/signature_solor_draft.png";
        $a = file_put_contents($files,$data);
        if (file_put_contents($file,$data)) {
          $result['img'] = 'http://' . $_SERVER['SERVER_NAME'] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$foldeId ."/signature_draft.png";
          $result['img_name'] = 'signature_solor_draft.png';
          create_thumbnail($file,'signature_solor_draft.png',$ds_dir);
        } 
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

function CustomerAgree($foldeId){
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    rename($ds_dir ."/signature_draft.png",$ds_dir ."/signature.png");
    rename($ds_dir ."/thumbnail/signature_draft.png",$ds_dir ."/thumbnail/signature.png");

    rename($ds_dir ."/CustomerAgreement_Draft.pdf",$ds_dir ."/CustomerAgreement.pdf");
   // create_image_from_pdf($ds_dir ."/CustomerAgreement.pdf", 'CustomerAgreement.pdf', $ds_dir.'/');
}

function CustomerAgreeSolor($foldeId){
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    rename($ds_dir ."/signature_solor_draft.png",$ds_dir ."/signature_solor.png");
    rename($ds_dir ."/thumbnail/signature_solor_draft.png",$ds_dir ."/thumbnail/signature_solor.png");

    rename($ds_dir ."/CustomerAgreement_Solar_Draft.pdf",$ds_dir ."/CustomerAgreement_Solar.pdf");
    create_image_from_pdf($ds_dir ."/CustomerAgreement_Solar.pdf", 'CustomerAgreement_Solar.pdf', $ds_dir.'/');

}

function create_image_from_pdf($source,$file_name,$path_save_file){
    $arr_name_file = explode(".", $file_name);
    $type = $arr_name_file[1];
    $new_name_file_pdf =$arr_name_file[0] ;
    $typeok = TRUE;
    $path_to_write = '';
    if($type == 'pdf'){
            $l_Image = new Imagick();
            $l_Image->setResolution(150, 150);
            $l_Image->readImage($source[0]);
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

function Send_Email_Notification($Invoice,$dataRequest){
    global $sugar_config;
    if($Invoice->id == '') { return false;}
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    
    $files = array(
        'signature_link' => $ds_dir ."/signature.png",
        'CustomerAgreement_link' => $ds_dir ."/CustomerAgreement.pdf",
    );

    $customer_name = $dataRequest['first_name'] .' '.$dataRequest['last_name'] ;
    $your_company_name = $dataRequest['your_company_name'];
    $your_position = $dataRequest['your_position'];
    $phone_number = $dataRequest['phone_number'];
    $your_address = $dataRequest['your_street'].','.$dataRequest['suburb_customer'].','.$dataRequest['state_customer'].','.$dataRequest['postcode_customer'];
    $your_email = $dataRequest['email_customer'];
    $your_install_date = $dataRequest['your_install_date'];

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  $customer_name.' Inv#'.$Invoice->number.' submitted the Customer Agreement Form';

    $style_td = 'padding-top: 5px; font-weight: bold;  text-align: left;border: 1px solid black;';

    $style_button  = 'color:#fff;font-family:Helvetica;font-size: 15px;margin:3px;line-height:100%;text-align:center;text-decoration:none;background-color:#428bca;border:1px solid #428bca;display:inline-block;font-weight:bold;padding-top: 10px;padding-right: 16px;padding-bottom: 10px;padding-left: 16px;border-radius:5px;';
    
    $InformationInvoice = 
    '<h2 style="text-align:center;">Customer Agreement Form</h2>'
    .'<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
        <tbody style="padding-top: 15px; padding-bottom:15px; width: 100%">
            <tr>
                <td style="'. $style_td .'">Invoice Name:</td>
                <td style="'. $style_td .'">'.$Invoice->name.'</td>
                <td style="'. $style_td .'">Invoice Number:</td>
                <td style="'. $style_td .'">'.$Invoice->number.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Customer Name:</td>
                <td style="'. $style_td .'">'. $customer_name.'</td>
                <td style="'. $style_td .'">Customer Email:</td>
                <td style="'. $style_td .'">'.$your_email.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Customer Phone Number:</td>
                <td style="'. $style_td .'">'.$phone_number.'</td>
                <td style="'. $style_td .'">Customer Position:</td>
                <td style="'. $style_td .'">'.$your_position.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Customer Company Name:</td>
                <td style="'. $style_td .'">'.$your_company_name.'</td>
                <td style="'. $style_td .'">Install Date:</td>
                <td style="'. $style_td .'">'.$your_install_date.'</td>
            </tr>
        </tbody>
    </table>';
    $mail->Body = '<div><p>Hi Accounts Team,</p><p>' 
        . $customer_name . ' submitted the Customer Agreement Form</p>' 
        . '</div>'
    .$InformationInvoice
    .'<br><div><a style="'.$style_button.'" target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$Invoice->id.'">Link CRM Invoice →</a></div>';
 
    foreach ($files as $key => $value) {
        $note = addToNotes_Invoice($value,$Invoice);
        if($note){
            $file_name = $note->filename;
      
            $location = $sugar_config['upload_dir'].$note->id;
            $mime_type = $note->file_mime_type;
            // Add attachment to email
            $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
        }
    }

    $mail->AddAddress('info@pure-electric.com.au');
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    //$mail->AddAddress('accounts@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function addToNotes_Invoice($link_file,$Invoice){
    global $sugar_config;
    if(is_file($link_file)) {
        $file_info = pathinfo ($link_file);
        $note = new Note();
        $note->id = create_guid();
        $note->new_with_id = true; 
        $note->parent_id = $Invoice->id;
        $note->parent_type = 'AOS_Invoices';
        $note->date_entered = '';
        $note->file_mime_type = mime_content_type($link_file);
        $note->filename =  $file_info['filename'];
        $note->name = $file_info['filename'];
        $note->save();
        $destination = $sugar_config['upload_dir'].$note->id;;
        if (!copy($link_file, $destination)) {
            $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
        }
        return $note;
    }else{
        return false;
    }
}

function Send_Email_Notification_solor($Invoice,$dataRequest){
    global $sugar_config;
    if($Invoice->id == '') { return false;}
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    
    $files = array(
        'signature_link' => $ds_dir ."/signature_solor.png",
        'CustomerAgreement_link' => $ds_dir ."/CustomerAgreement_Solar.pdf",
    );


    $customer_name = html_entity_decode($dataRequest['account_name'],ENT_QUOTES) ;
    $your_date = $dataRequest['your_date'];
    $customer_contact_name = html_entity_decode($dataRequest['customer_contact_name'],ENT_QUOTES);
    $your_abn = $dataRequest['your_abn'];


    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  $customer_name.' Inv#'.$Invoice->number.' submitted the Customer Agreement Solor Form';

    $style_td = 'padding-top: 5px; font-weight: bold;  text-align: left;border: 1px solid black;';

    $style_button  = 'color:#fff;font-family:Helvetica;font-size: 15px;margin:3px;line-height:100%;text-align:center;text-decoration:none;background-color:#428bca;border:1px solid #428bca;display:inline-block;font-weight:bold;padding-top: 10px;padding-right: 16px;padding-bottom: 10px;padding-left: 16px;border-radius:5px;';
    
    $InformationInvoice = 
    '<h2 style="text-align:center;">Customer Agreement Solor Form</h2>'
    .'<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
        <tbody style="padding-top: 15px; padding-bottom:15px; width: 100%">
            <tr>
                <td style="'. $style_td .'">Invoice Name:</td>
                <td style="'. $style_td .'">'.$Invoice->name.'</td>
                <td style="'. $style_td .'">Invoice Number:</td>
                <td style="'. $style_td .'">'.$Invoice->number.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Account Name:</td>
                <td style="'. $style_td .'">'. $customer_name.'</td>
                <td style="'. $style_td .'">Customer Contact Name:</td>
                <td style="'. $style_td .'">'.$customer_contact_name.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Date:</td>
                <td style="'. $style_td .'">'.$your_date.'</td>
                <td style="'. $style_td .'">ABN:</td>
                <td style="'. $style_td .'">'.$your_abn.'</td>
            </tr>
        </tbody>
    </table>';
    $mail->Body = '<div><p>Hi Accounts Team,</p><p>' 
        . $customer_name . ' submitted the Customer Agreement Solor Form</p>' 
        . '</div>'
    .$InformationInvoice
    .'<br><div><a style="'.$style_button.'" target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$Invoice->id.'">Link CRM Invoice →</a></div>';
 
    foreach ($files as $key => $value) {
        $note = addToNotes_Invoice($value,$Invoice);
        if($note){
            $file_name = $note->filename;
      
            $location = $sugar_config['upload_dir'].$note->id;
            $mime_type = $note->file_mime_type;
            // Add attachment to email
            $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
        }
    }

    $mail->AddAddress('info@pure-electric.com.au');
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    //$mail->AddAddress('accounts@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}