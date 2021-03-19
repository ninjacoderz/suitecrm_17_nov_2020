<?php
require_once('include/SugarPHPMailer.php');
    if( isset($_REQUEST['type_api'])  && $_REQUEST['type_api'] == "Sanden-Review-Approve") {
        $invoice = new AOS_Invoices();
        $invoice->retrieve($_REQUEST['invoice_id']);
        $account_customer = new Account();
        $account_customer->retrieve($invoice->billing_account_id);
        if( $_REQUEST['installer'] == "plumber" ){
            $worker =  new Contact();
            $worker->retrieve($invoice->contact_id4_c);
            $purchase = new PO_purchase_order();
            $purchase->retrieve($invoice->plumber_po_c);
        }else {
            $worker =  new Contact();
            $worker->retrieve($invoice->contact_id_c);
            $purchase = new PO_purchase_order();
            $purchase->retrieve($invoice->electrical_po_c);
        }
        $mail = new SugarPHPMailer();  
        $mail->setMailerForSystem();  
        $mail->From = 'info@pure-electric.com.au';  
        $mail->FromName = 'Pure Electric';  
        // $mail->Subject = "All photo installs ".$installer->name." have sent to Pure Electric";
        $mail->Subject = ucwords($_REQUEST['installer'])." - Review and approve PO - PO#".$purchase->number ." - ".$invoice->install_address_c." ".$invoice->install_address_city_c." ".$invoice->install_address_state_c." ".$invoice->install_address_postalcode_c;
        $mail->Body = "<p>Link PO: <a href='https://suitecrm.pure-electric.com.au/index.php?module=PO_purchase_order&action=EditView&record=".$purchase->id."' target='_blank'>".$purchase->name."</a></p>";
        $mail->Body .= "<p>Link Invoice: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoice->id."' target='_blank'>".$invoice->name."</a></p>";
        $mail->Body .= "<p>Address install: ".$invoice->install_address_c." ".$invoice->install_address_city_c." ".$invoice->install_address_state_c." ".$invoice->install_address_postalcode_c."</p>";
        $mail->Body .= "<p>Client Name: ".$account_customer->name."</p>";
        $mail->Body .= "<p>Client Phone number: ".$account_customer->mobile_phone_c."</p>";
        $mail->Body .= "<p>Installer Name: ".$worker->first_name." ".$worker->last_name."</p>";
        $mail->Body .= "<p>Installer Phone: ".$worker->phone_mobile."</p>";
        $mail->Body .= "<p>Installer Email: <a href='https://mail.google.com/#search/".$worker->email1."'>".$worker->email1." GSearch</p>";
        $mail->Body .= "<p>APPROVE?: ".$_REQUEST['confirmreview']."</p>";
        $mail->Body .= "<p>Notes:</p>";
        $mail->Body .= "<p>".$_REQUEST['add_note_review']."</p>";
        $mail->IsHTML(true);
        $mail->AddAddress('info@pure-electric.com.au');
        // $mail->AddAddress('ngoanhtuan2510@gmail.com');
        // $mail->AddCC('info@pure-electric.com.au');
        $mail->prepForOutbound();
        $mail->setMailerForSystem();  
        // $mail->Send();

        if(!$mail->Send()) {
            echo "Mailer Error";
            } else {
            echo "Message sent!";
        }
        die;
    }else {

    $installer_id = $_REQUEST['installer_id'];
    $generateUUID = $_REQUEST['generateUUID'];
    $installer_name = $_REQUEST['installer_name'];
    $invoice_id = $_REQUEST['invoice_id'];
    $billing_account_id = $_REQUEST['billing_account_id'];

    $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    $dirName        = $generateUUID;
    $folderName     = $path . $dirName . '/';
    $list_photos = "<br><h4>List Photos:</h4>";
    $get_all_photo = dirToArray($folderName);
    $photoIdArray = [];
    foreach($get_all_photo as $k => $each_photo ) {
        if( strpos( $each_photo,'New_Install_Photo') || 
            strpos( $each_photo,'Old_Existing_Hws') ||
            strpos( $each_photo,'Measure_Water_Pressure_NRIPRV0') ||
            strpos( $each_photo,'New_Install_Water_Pressure_Property') ||
            strpos( $each_photo,'Tank_Serial_Number') ||
            strpos( $each_photo,'HP_Serial_Number') ||
            strpos( $each_photo,'Decommission_HWS') ){
    
            $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$each_photo.'">'.$each_photo.'</a>';
        }
    }
    $invoice = new AOS_Invoices();
    $invoice->retrieve($invoice_id);

    $account_customer = new Account();
    $account_customer->retrieve($billing_account_id);

    $installer=  new Account();
    $installer->retrieve($installer_id);

    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = 'info@pure-electric.com.au';  
    $mail->FromName = 'Pure Electric';  
    // $mail->Subject = "All photo installs ".$installer->name." have sent to Pure Electric";
    $mail->Subject = "All photo installs - ".$installer_name." ".$installer->name." had uploaded to Pure Electric - Invoice#".$invoice->number ." - ".$invoice->install_address_c." ".$invoice->install_address_city_c." ".$invoice->install_address_state_c." ".$invoice->install_address_postalcode_c;
    $mail->Body = "<p>Address install: ".$invoice->install_address_c." ".$invoice->install_address_city_c." ".$invoice->install_address_state_c." ".$invoice->install_address_postalcode_c."</p>";
    $mail->Body .= "<p>Client Name: ".$account_customer->name."</p>";
    $mail->Body .= "<p>Client Phone number: ".$account_customer->mobile_phone_c."</p>";
    $mail->Body .= "<p>".$installer_name." installer: ".$installer->name."</p>";
    $mail->Body .= "<p>".$installer_name." phone: ".$installer->mobile_phone_c."</p>";

    $mail->Body .= $list_photos;
    // $mail->Body = "<a href='http://new.suitecrm-pure.com/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote->id."' target='_blank'>Link Quote: ".$quote->name."</a>";
    $mail->IsHTML(true);
    $mail->AddAddress($installer->email1);
    $mail->AddCC('info@pure-electric.com.au');
    $mail->AddCC('paul.szuster@pure-electric.com.au');
    // $mail->AddCC('ngoanhtuan2510@gmail.com');
    // $mail->AddCC('info@pure-electric.com.au');
    $mail->prepForOutbound();
    $mail->setMailerForSystem();  
    // $mail->Send();

        if(!$mail->Send()) {
            echo "Mailer Error";
        } else {
            echo "Message sent!";
        }
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
    
?>