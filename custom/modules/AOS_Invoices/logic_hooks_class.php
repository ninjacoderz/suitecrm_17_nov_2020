<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 3/19/17
 * Time: 6:12 PM
 */


    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class RenameUploadFiles
    {
        function after_save_method($bean, $event, $arguments)
        {
            //logic

            $files = json_decode(html_entity_decode($bean->file_rename_c));

            //$current_file_path = dirname(__FILE__);
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->installation_pictures_c ;
            $invoice_number = $bean->number;
            $hp_serial = $bean->sanden_hp_serial_c;
            $tank_serial = $bean->sanden_tank_serial_c;
            $vpa_pic_cert = $bean->vba_pic_cert_c;
            $cert_number = $bean->ces_cert_c;
            //[Old Tank serial]
            $old_tank_serial = $bean->old_tank_serial_c;
            $plumber_invoice = $bean->plumber_invoice_number_c;
            if(count($files)) foreach($files as $file){
                if($file->rename_option != 0){
                    $extension=end(explode(".", $file->file_name));
                    $new_name = "";
                    //print($file->rename_option);
                    switch ($file->rename_option){
                        case "1":
                            $new_name = $invoice_number.'_Old';
                            break;
                        case "2":
                            $new_name = $invoice_number.'_Photo';
                            break;
                        case "3":
                            $new_name = $invoice_number.'PCOC'.(($vpa_pic_cert)?("_".$vpa_pic_cert):"");//change VBA
                            break;
                        case "4":
                            $new_name = $invoice_number.'CES'.(($cert_number)?("_".$cert_number):"");
                            break;
                        case "5":
                            $new_name = $invoice_number.'HP'.(($hp_serial)?("_".$hp_serial):"");
                            break;
                        case "6":
                            $new_name = $invoice_number.'Payment';
                            break;
                        case "7":
                            $new_name = $invoice_number.'Invoice';
                            break;
                        case "8":
                            $new_name = $invoice_number.'_New';
                            break;
                        case "9":
                            $new_name = $invoice_number.'Tank_'.(($tank_serial)?("_".$tank_serial):"");
                            break;
                        case "10":
                            $new_name = $invoice_number.'Diagram';
                            break;
                        case "11":
                            $new_name = $invoice_number.'OldSerial'.(($old_tank_serial)?("_".$old_tank_serial):"");
                            break;
                        case "12":
                            $new_name = $invoice_number.'ElectInvoice';
                            break;
                        case "13":
                            $new_name = $invoice_number.'Switchboard';
                            break;
                        case "14":
                            $new_name = $invoice_number.'PlumbInvoice'.(($plumber_invoice)?("_".$plumber_invoice):"");
                            break;
                        case "19":
                            $new_name = $invoice_number.'DeliveryDocket';
                            break;
                        case "20":
                            $new_name = $invoice_number.'SystemOwnerTaxInvoice';
                            break;
                        case "34":
                            $new_name = $invoice_number.'_New_Install_Photo';
                            break;
                        case "36":
                            $new_name = $invoice_number.'Proposed_Install_Location';
                            break;
                        case "37":
                            $new_name = $invoice_number.'Remittance_Advice';
                            break;
                        case "39":
                            $new_name = $invoice_number.'New_Install_Water_Pressure_Property';
                            break;
                        case "40":
                            $new_name = $invoice_number.'New_Install_Water_Pressure_NRIPRV';
                            break;
                        case "41":
                            $new_name = $invoice_number.'_Existing_HWS';
                            break;
                    }
                    // If new name look like old name

                    if ($new_name.'.'.$extension == $file->file_name) return;
                    $i = 1;
                    $will_rename = $new_name;
                    while( !empty(glob($current_file_path.'/'.$will_rename."*")) || !empty(glob($current_file_path.'/'.$will_rename.("_".str_replace(' ', '_', $file->suffix))."*")) )
                    {
                        $will_rename = $new_name.$i;
                        if ($will_rename.".".$extension == $file->file_name) return;
                        $i++;
                    }

                    if(isset($file->suffix) && $file->suffix != "") {
                        $will_rename .= ("_".str_replace(' ', '_', $file->suffix));
                    }

                    $will_rename .= ('.'.$extension);
                    // If the file posted have same name that we generated ( this file is rename one time  )
                    if ($will_rename == $file->file_name) return;

                    rename($current_file_path."/".$file->file_name, $current_file_path."/". $will_rename);
                    rename($current_file_path."/thumbnail/".$file->file_name, $current_file_path."/thumbnail/". $will_rename);
                }
                // Create another jpg if extenstion is pdf
                /*if(strtolower($extension) == 'pdf'){
                    $imagick = new Imagick();
                    $imagickthumb = new Imagick();
// Reads image from PDF
                    //$file = scandir($myFramesPath);
                    $file_handle_for_viewing_image = fopen($current_file_path."/". $will_rename, 'a+');
                    $imagick->readImageFile($file_handle_for_viewing_image);
                    fclose($file_handle_for_viewing_image);

                    $file_handle_for_saving_good = fopen($current_file_path."/". str_replace ( $extension , 'png' , $will_rename ), 'a+');
                    $imagick_clone = clone $imagick;
                    $imagick_clone->setImageFormat("png");
                    $imagick_clone->setFormat("png");
// Writes an image or image sequence Example- converted-0.jpg, converted-1.jpg
                    $imagick_clone->writeImageFile($file_handle_for_saving_good);
                    fclose($file_handle_for_saving_good);


                    $file_handle_for_viewing_image_thumb = fopen($current_file_path."/". $will_rename, 'a+');
                    $imagickthumb->readImageFile($file_handle_for_viewing_image_thumb);
                    fclose($file_handle_for_viewing_image_thumb);

                    $file_handle_for_saving_good_thumb = fopen($current_file_path."/thumbnail/". str_replace ( $extension , 'png' , $will_rename ), 'a+');
                    $imagickthumb_clone = clone $imagickthumb;
                    $imagickthumb_clone->setImageFormat("png");
                    $imagickthumb_clone->setFormat("png");
// Writes an image or image sequence Example- converted-0.jpg, converted-1.jpg
                    $imagickthumb_clone->writeImageFile($file_handle_for_saving_good_thumb);
                    fclose($file_handle_for_saving_good_thumb);
                }*/
            }
        }
    }
    class InvoiceAddAttachments
    {
        function after_save_method($bean, $event, $arguments)
        {

            //$current_file_path = dirname(__FILE__);
            if($bean->installation_pictures_c == ""){
                $uuid = md5(uniqid(mt_rand(), true));
                $guid =  $prefix.substr($uuid,0,8)."-".
                substr($uuid,8,4)."-".
                substr($uuid,12,4)."-".
                substr($uuid,16,4)."-".
                substr($uuid,20,12);
                $bean->installation_pictures_c  = $guid;
                $bean->save();
            }
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->installation_pictures_c;
            if( file_exists ( $current_file_path ) ) return;
            // Query to get notes file
            $account_id = $bean->billing_account_id;

            if(!is_object($bean->opportunities_aos_invoices_1opportunities_ida) && $bean->opportunities_aos_invoices_1opportunities_ida != ''){
                $opportunity_id = $bean->opportunities_aos_invoices_1opportunities_ida;
            }else{
                $opportunity_id = '';
            }
            $contact_id = $bean->billing_contact_id;
            $lead_id = "";
            $sql_where = "";
            $db = DBManagerFactory::getInstance();

            // Get the lead id

            if($opportunity_id != ''){
                $sql = "SELECT * FROM leads 
                WHERE 1=1 AND (opportunity_id = '$opportunity_id' OR account_id = '$account_id' OR contact_id = '$contact_id' )
                ";

                $ret = $db->query($sql);
                while ($row = $db->fetchByAssoc($ret)) {
                    if($row["id"] != ""){
                        $lead_id = $row["id"];
                    }
                }

                $sql_where = "OR (eb.bean_id = '$$lead_id' AND eb.bean_module = 'Leads')";
            }

            //check billing_account = Solargain PV account
            if($account_id == '61db330d-0aee-6661-8ac3-585c79c765a2'){
                $account_id = '';
            }
            //check account = Solargain Accounts
            if($contact_id == '296a953e-c7d0-40b6-09d3-5ab9cb674a5c'){
                $contact_id = '';
            }
            
            $sql = "SELECT nt.id as note_id, nt.filename as file_name FROM notes nt 
                    LEFT JOIN emails_beans eb ON eb.email_id = nt.parent_id 
                    WHERE 1=1 AND nt.deleted = 0 AND nt.parent_type = 'Emails' AND 
                    (
                        (eb.bean_id = '$account_id' AND eb.bean_module = 'Accounts') OR
                        (eb.bean_id = '$contact_id' AND eb.bean_module = 'Contacts') 
                    ". $sql_where."
                        )
                    ";
                    

            $ret = $db->query($sql);
            $note_id_array = array();
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["note_id"] != ""){
                    $a_note_id_array["note_id"] = $row["note_id"];
                }
                if($row["file_name"] != ""){
                    $a_note_id_array["file_name"] = $row["file_name"];
                }
                $note_id_array[] = $a_note_id_array;
            }
            
            if(!file_exists ( $current_file_path )) {
                set_time_limit ( 0 );
                mkdir($current_file_path);
                foreach ($note_id_array as $note) {
                    $source = realpath(dirname(__FILE__) . '/../../../').'/upload/'.$note['note_id'];
                    //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                    $destination = $current_file_path."/".$note['file_name'];
                    copy( $source, $destination);
                    //thien fix show thumb
                    if(is_file($source)){
                            $type = strtolower(substr(strrchr($note['file_name'], '.'), 1));
                            $typeok = TRUE;
                            if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                                if(!file_exists ($current_file_path."/thumbnail/")) {
                                    mkdir($current_file_path."/thumbnail/");
                                }
                                $thumb =  $current_file_path."/thumbnail/".$note['file_name'];
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
                                    list($w, $h) = getimagesize($destination);
    
                                    $src = $src_func($destination);
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
            
        }
    }

    //dung code - hook update status invoice
    class UploadStatusInvoice
    {
        function after_save_method($bean, $event, $arguments)
        {
            if($bean->next_payment_amount_c == '0' || $bean->next_payment_amount_c == '0.00'){
                if(strpos(strtolower($bean->name), 'sanden') !== false && $bean->status == 'Deposit_Paid') {
                    $bean->status = 'STC_Unpaid';
                    $bean->save();
                } elseif(strpos(strtolower($bean->name), 'daikin') !== false && $bean->status == 'Deposit_Paid') {
                    $bean->status = 'VEEC_Unpaid';
                    $bean->save();
                }
            }

            //change logic Paid for Invoice "Daikin", "Solor"
            // $total_balance_owing_c = floatval($bean->total_balance_owing_c);

            // if(($total_balance_owing_c < 0 || $total_balance_owing_c == 0) &&  $bean->status != 'Paid' &&  $bean->status != 'Cancelled' &&  $bean->status != 'Test'){
            //     $inv_type = $bean->quote_type_c;
            //     $array_product_type_change_status = ['quote_type_solar','quote_type_daikin','quote_type_nexura'];
            //     if(in_array($inv_type,$array_product_type_change_status)){
            //         $bean->status = 'Paid';
            //          $bean->save();
            //     }
            // }
        }
    }

    class UpdateStockItems
    {
        function before_save_method($bean, $event, $arguments)
        {
            $old_fields = $bean->fetched_row;

            //if(($old_fields['sanden_tank_serial_c'] != $bean->sanden_tank_serial_c) || ($old_fields['sanden_hp_serial_c'] != $bean->sanden_hp_serial_c )){
            if($bean->sanden_tank_serial_c != "" ){
                $sanden_tank_serials = explode(",", $bean->sanden_tank_serial_c);
                
                $db = DBManagerFactory::getInstance();

                if(count($sanden_tank_serials) > 0) foreach($sanden_tank_serials as $sanden_series) {
                    // update serialize
                    $sanden_series = trim($sanden_series);
                    if($sanden_series == "") continue;

                    $sql = "SELECT * FROM pe_stock_items 
                    WHERE 1=1 AND serial_number = '".$sanden_series."'
                    ";

                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row["id"] != ""){
                            $query  = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '$bean->id' WHERE pe_stock_items.id = '".$row["id"]."'";
                            $db->query($query);
                        }
                    }
                }
                
            }else{
                
                $db = DBManagerFactory::getInstance();
                
                $sanden_tank_serials = explode(",", $old_fields['sanden_tank_serial_c']);
                
                $db = DBManagerFactory::getInstance();

                if(count($sanden_tank_serials)) foreach($sanden_tank_serials as $serial){
                    if($serial == "") continue;
                    $sql = "SELECT * FROM pe_stock_items INNER JOIN pe_stock_items_cstm ON pe_stock_items.id = pe_stock_items_cstm.id_c WHERE pe_stock_items_cstm.aos_invoices_id_c = '$bean->id' AND pe_stock_items.serial_number = '".$serial."'";
                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row["id"] != ""){
                            $query  = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '' WHERE pe_stock_items.id = '".$row["id"]."'";
                            $db->query($query);
                        }
                    }
                }
            }

            if($bean->sanden_hp_serial_c != "" ){
                // update serialize
                $db = DBManagerFactory::getInstance();
                $sanden_hp_serials = explode(",", $old_fields['sanden_hp_serial_c']);

                if(count($sanden_hp_serials)) foreach ($sanden_hp_serials  as $serial){
                    if($serial == "") return;
                    $sql = "SELECT * FROM pe_stock_items 
                    WHERE 1=1 AND serial_number = '".$serial."'
                    ";

                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row["id"] != ""){
                            $query  = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '$bean->id' WHERE pe_stock_items.id = '".$row["id"]."'";
                            $db->query($query);
                        }
                    }
                }
                
            }else{
                $db = DBManagerFactory::getInstance();

                $sanden_hp_serials = explode(",", $old_fields['sanden_hp_serial_c']);

                if(count($sanden_hp_serials)>0) foreach($sanden_hp_serials as $serial){
                    if($serial == "") continue;
                    $sql = "SELECT * FROM pe_stock_items INNER JOIN pe_stock_items_cstm On pe_stock_items.id = pe_stock_items_cstm.id_c WHERE pe_stock_items_cstm.aos_invoices_id_c = '$bean->id' AND pe_stock_items.serial_number = '".$serial."'";
                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row["id"] != ""){
                            $query  = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '' WHERE pe_stock_items.id = '". $row["id"] ."'";
                            $db->query($query);
                        }
                    }
                }
                
            }
            
        }
    }

    //  Auto create new Internal Notes When we change status Invoice
    class CreateInternalNotes_invoice {
        function before_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            if($old_fields == false){
                //check internal notes new 
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT pe_internal_note.id as id  FROM pe_internal_note 
                LEFT JOIN aos_invoices_pe_internal_note_1_c ON aos_invoices_pe_internal_note_1_c.aos_invoices_pe_internal_note_1pe_internal_note_idb  = pe_internal_note.id 
                LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated' 
                AND pe_internal_note.description = 'Invoice Status : New Invoice' 
                AND aos_invoices_pe_internal_note_1_c.aos_invoices_pe_internal_note_1aos_invoices_ida  ='$bean->id' ";
    
                $ret = $db->query($sql);
                if($ret->num_rows == 0){
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Invoice Status : New Invoice';
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('aos_invoices_pe_internal_note_1');
                    $bean_intenal_notes->aos_invoices_pe_internal_note_1->add($bean->id);
                }
            }else{
                //case 2 : updated
                if($old_fields['status'] != $bean->status && $bean->status != ''){
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Invoices Status : ';
                    $decription_internal_notes .= str_replace('_',' ',$bean->status);

                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->save();
                 
                    $bean_intenal_notes->load_relationship('aos_invoices_pe_internal_note_1');
                    $bean_intenal_notes->aos_invoices_pe_internal_note_1->add($bean->id);
                }
            }
        }
    }
    //Thienpb code  -- update next action date = '' when status = Paid >> comment https://trello.com/c/xe2bwURy/2354-invoice-cant-fill-the-next-action-date
    // class UpdateNextActionDate {
    //     function after_save_method($bean, $event, $arguments){
    //        if($bean->status == 'Paid'){
    //             $db = DBManagerFactory::getInstance();
    //             $query  = "UPDATE aos_invoices_cstm SET next_action_date_c = NULL WHERE id_c = '".$bean->id."'";
    //             $ret = $db->query($query);
    //         }
    //     }
    // }
            /**
     * Auto AutoSendCustomerWarrantyMail
     */
    // class AutoSendCustomerWarrantyMail {
    //     function after_save_AutoSendCustomerWarrantyMail($bean, $event, $arguments) {
    //         date_default_timezone_set('Australia/Melbourne');
    //         $old_fields = $bean->fetched_row;
    //         if($old_fields['installation_date_c'] != $bean->installation_date_c && $bean->installation_date_c != ''){
    //             $db = DBManagerFactory::getInstance();
    //             $sql = "UPDATE `emails` SET `deleted` = 1 WHERE `status` = 'email_schedule' AND `parent_id` = '$bean->id' AND `name` = 'Warranty registration photos and serials' AND deleted = 0";
    //             $db->query($sql);
    //             $emailTemplateID = 'a60e5ca5-6919-87ac-916c-6034cbff7477';//test 'c51e810f-f6b5-bf50-5ab6-6034cbce9ce3';

    //             $emailtemplate = new EmailTemplate();
    //             $emailtemplate = $emailtemplate->retrieve($emailTemplateID);

    //             $contact =  new Contact();
    //             $contact->retrieve($bean->billing_contact_id);

    //             $name = $emailTemplate->subject;
    //             $description_html = $emailTemplate->body_html;
    //             $description = $emailTemplate->body;
                
    //             $template_data = $emailtemplate->parse_email_template(
    //                 array(
    //                     "subject" => $emailtemplate->subject,
    //                     "body_html" => htmlspecialchars($emailtemplate->body_html),
    //                     "body" => $emailtemplate->body_html
    //                     ),
    //                     'AOS_Invoices',
    //                     $lead,
    //                     $macro_nv
    //                 );
                
    //             $name = $template_data['subject'];
    //             $description = $template_data['body'];
    //             $description_html = $template_data['body_html'];
    //             //parse value

    //             $link_upload_files = 'https://pure-electric.com.au/upload_file_sanden/client-warranty?invoice_id=' . $invoice->id;
    //             $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
    //             $description = str_replace("\$contact_first_name",$contact->first_name , $description);
    //             $description = str_replace("\$aos_invoices_link_upload",$string_link_upload_files , $description);

    //             $description_html = str_replace("\$contact_first_name",$contact->first_name , $description_html);
    //             $description_html = str_replace("\$aos_invoices_link_upload",$string_link_upload_files, $description_html);

    //             $mail_From = "info@pure-electric.com.au";
    //             $mail_FromName = "Pure Electric";
    //             $emailSignatureId = '3ad8f82a-d3e7-5897-7c98-5ba1c4ac785e'; 
    //             //signature
    //             $user = new User();
    //             $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
    //             $defaultEmailSignature = $user->getSignature($emailSignatureId);
            
    //             if (empty($defaultEmailSignature)) {
    //                 $defaultEmailSignature = array(
    //                     'html' => '<br>',
    //                     'plain' => '\r\n',
    //                 );
    //                 $defaultEmailSignature['no_default_available'] = true;
    //             } else {
    //                 $defaultEmailSignature['no_default_available'] = false;
    //             }
    //             $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
    //             $description .= "<br><br><br>";
    //             $description .=  $defaultEmailSignature['signature_html'];
    //             $description_html .= "<br><br><br>";
    //             $description_html .=  $defaultEmailSignature['signature_html'];
    //             $schedule_time = strtotime(str_replace('/', '-',$bean->installation_date_c)) + 60*60*24; //+ 24 minutes
    //             //create email 
    //             $email = new Email();
    //             $email->id = create_guid();
    //             $email->new_with_id = true;
    //             $email->name = $name;
    //             $email->type = "out";
    //             $email->status = "email_schedule";
    //             $email->parent_type = 'AOS_Invoices';
    //             $email->parent_id = $bean->id;
    //             $email->parent_name = $bean->name;
    //             $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    //             $email->description_html = $description_html;
    //             $email->description = $description_html;
    //             $email->schedule_timestamp_c = $schedule_time;
    //             $email->from_addr = $mail_From;
    //             $email->from_name = $mail_FromName;
    //             $email->to_addrs_emails = $contact->email1 . ";";
    //             $email->to_addrs = $contact->first_name." ".$contact->last_name . " <" . $contact->email1 . ">";
    //             $email->to_addrs_names = $contact->first_name." ".$contact->last_name . " <" . $contact->email1 . ">";
    //             $email->to_addrs_arr = array(
    //                 array(
    //                     'email' => $contact->email1,
    //                     'display' =>  $contact->first_name." ".$contact->last_name,
    //                 )
    //             );
    //             $email->cc_addrs_emails = "Pure Info <info@pure-electric.com.au>;";
    //             $email->cc_addrs = 'Pure Info <info@pure-electric.com.au>';
    //             $email->cc_addrs_names = "Pure Info <info@pure-electric.com.au>";
    //             $email->cc_addrs_arr = array(
    //                 array(
    //                     'email' => 'info@pure-electric.com.au',
    //                     'display' => 'Pure Info'
    //                 )
    //             );
    //             $email_id = $email->id;
            
    //             // $note = new Note();
    //             // $where = "notes.parent_id = '$emailTemplateID'";
    //             // $attachments = $note->get_full_list("", $where, true);
    //             // $all_attachments = array();
    //             // $all_attachments = array_merge($all_attachments, $attachments);
    //             // foreach($all_attachments as $attachment) {
    //             //     $noteTemplate = clone $attachment;
    //             //     $noteTemplate->id = create_guid();
    //             //     $noteTemplate->new_with_id = true; 
    //             //     $noteTemplate->parent_id = $email->id;
    //             //     $noteTemplate->parent_type = 'Emails';
    //             //     $noteFile = new UploadFile();
    //             //     $noteFile->duplicate_file($attachment->id, $noteTemplate->id, $noteTemplate->filename);
    //             //     $noteTemplate->save();
    //             //     $email->attachNote($noteTemplate);
    //             // }
    //             $email->save();
    //         }
    //     }
    // }
?>