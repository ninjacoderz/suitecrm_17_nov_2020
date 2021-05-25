<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 3/19/17
 * Time: 6:12 PM
 */


    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    if ( !function_exists('updateStatusSolargainLeadOppo') ) {
        function updateStatusSolargainLeadOppo($solarLeadID, $status, $quoteNumber = ""){
            date_default_timezone_set('Australia/Sydney');
            set_time_limit ( 0 );
            ini_set('memory_limit', '-1');
            
            $username = "matthew.wright";
            $password =  "MW@pure733";

            // Convert quote 
            if($quoteNumber != ""){
                // if update status quote
                if($status =="reopen"){
                    $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteNumber."/reopen";
                } else  
                    $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteNumber."/lost/". $status;
                //set the url, number of POST vars, POST data
            
                $curl = curl_init();
                
                curl_setopt($curl, CURLOPT_URL, $url);
                
                
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                //
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        "Host: crm.solargain.com.au",
                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                        "Content-Type: application/json",
                        "Accept: application/json, text/plain, */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: 	gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Referer: https://crm.solargain.com.au/quote/edit/".$quoteNumber,
                        "Cache-Control: max-age=0"
                    )
                );
                
                $result = curl_exec($curl);
                curl_close ( $curl );
            }

            if($status =="reopen"){
                $url = "https://crm.solargain.com.au/APIv2/leads/".$solarLeadID."/reopen";
            } else 
                $url = "https://crm.solargain.com.au/APIv2/leads/".$solarLeadID."/lost/". $status;
            //set the url, number of POST vars, POST data
        
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, $url);
            
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $result = curl_exec($curl);
            curl_close ( $curl );

            // Update next date

            $url = "https://crm.solargain.com.au/APIv2/leads/". $solarLeadID;
            //set the url, number of POST vars, POST data
        
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, $url);
            
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $leadJSON = curl_exec($curl);
            curl_close ( $curl );
        
            $leadSolarGain = json_decode($leadJSON);

            $leadSolarGain->NextActionDate = array(
                "Date" => date('d/m/Y', time() + 3*24*60*60),
                "Time"=>"9:00 AM"
            );

            $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);

            // Save back lead 
            $url = "https://crm.solargain.com.au/APIv2/leads/";
            //set the url, number of POST vars, POST data
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POST, 1);
            
            curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);
            
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " .strlen($leadSolarGainJSONDecode),
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
                )
            );
            
            curl_exec($curl);
            curl_close ( $curl );

        }
    }
    class LeadRenameUploadFiles
    {   
        function before_save_method_pushToSolargain($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;

            $lost_status_mapping = array(
               "Lost_Competitor" => "LOST_TO_COMPETITOR",
               "Lost_Uncontactable" => "UNCONTACTABLE",
               "Lost_Unsuitable_Roof" => "UNSUITABLE_ROOF",
               "Lost_Enquiry_Only" => "ENQUIRY_ONLY",
               "Lost_No_Longer_Interested" => "NO_LONGER_INTERESTED",
               "Lost_Outside_Service_Area" => "OUTSIDE_SERVICE_AREA",
               "Lost_Duplicate" => "DUPLICATE",
               "Lost_Council" => "COUNCIL",
               "New" => "reopen" ,
               "Assigned" => "reopen" ,
               "In Process" => "reopen" ,
               "Converted" => "reopen" ,
               "Recycled" => "reopen" ,
            );

            if($old_fields['status'] != $bean->status){
                if($lost_status_mapping[$old_fields['status']]!='reopen' &&  $lost_status_mapping[$bean->status]=='reopen')
                {
                    // Push status to solargain
                    updateStatusSolargainLeadOppo($bean->solargain_lead_number_c, $lost_status_mapping[$bean->status], $bean->solargain_quote_number_c);
                }else{
                    updateStatusSolargainLeadOppo($bean->solargain_lead_number_c, $lost_status_mapping[$bean->status], "");
                }
            }
        }

        function before_save_method_autoCalculateDistance($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            if($old_fields["solargain_offices_c"] == "" || $old_fields["distance_to_sg_c"] == ""){
                // calculate the distance
                $solargain_address = array(
                    "2"=>"Unit 7, 88 Dynon Road, West Melbourne VIC 3003",
                    "14"=>"963/1002 Grand Junction Road, Holden Hill SA 5088",
                    "0"=>"10 Milly Court, Malaga WA 6090",
                    "1"=>"Unit 2, 7 Beale Way, Rockingham WA 6168",
                    "3"=>"Unit 1, 5-7 Imboon Street, Deception Bay QLD 4508",
                    "4"=>"21C Richmond Road, Homebush NSW 2140",
                    "5"=>"244 Fitzgerald Street, Northam WA 6401",
                    "6"=>"117 Lockyer Avenue, Albany WA 6330",
                    "7"=>"Unit 2, 18 Bourke Street, Bunbury WA 6230",
                    "8"=>"25 Wright Street, Busselton WA 6280",
                    "9"=>"Lot 10 Reg Clarke Road, Geraldton WA 6530",
                    "10"=>"23-49 Parfitt Road, Wangaratta VIC 3676",
                    "11"=>"Shed 16B, 22 Walsh Road, Warrnambool VIC 3280",
                    "12"=>"Unit 7, 8-10 Boat Harbour Drive, Pialba QLD 4655",
                    "13"=>"14 Ipswich St, Fyshwick ACT 2609"
                );
                $distances = array();
                if($old_fields["primary_address_street"] && $old_fields["primary_address_city"] && $old_fields["primary_address_state"] && $old_fields["primary_address_postalcode"]){
                    $from_address = $old_fields["primary_address_street"] .", ". $old_fields["primary_address_city"] .", ". $old_fields["primary_address_state"].", ".$old_fields["primary_address_postalcode"];
                    
                    foreach($solargain_address as $key=>$solargain){
                        $to_address = $solargain;
                        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$from_address."&destination=".$to_address."&key=AIzaSyDcPlmWLNUZ4tbEeisTzu_8cuuxXZrH6H4";
                        $url =  str_replace(" ", "+", $url);
                        $geocodeTo = file_get_contents($url);
                        $data = json_decode($geocodeTo, true);
                        $distance = $data['routes'][0]['legs'][0]['distance']['text'];
                        $distances[$key] = $distance;
                    }
                    // get the shortest distance
                    if(count($distances) == 0) return;
                    $shortest = str_replace(array("km", " ", ","), "", $distances[0]);
                    $shortest_key = "";
                    foreach($distances as $key=> $dis){
                        $a_dis = str_replace(array("km", " ", ","), "", $dis);
                        if($shortest >= $a_dis) {
                            $shortest = $a_dis;
                            $shortest_key = $key;
                        }
                    }
                    if($shortest_key != ""){
                        $shortest_val = $distances[$shortest_key];
                        $bean->solargain_offices_c = $shortest_key;
                        $bean->distance_to_sg_c = $distances[$shortest_key];
                    }

                    //echo $geocodeTo;
                }
            }

            
        }

        function after_save_method($bean, $event, $arguments)
        {
            //logic

            $files = json_decode(html_entity_decode($bean->file_rename_c));

            //$current_file_path = dirname(__FILE__);
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->installation_pictures_c ;
            $address = $bean->primary_address_street;

            // Thienpb fix for add city to name
            $city = $bean->primary_address_city;
            $address_city = $address.'_'.strtolower($city);

            
            $file_attachmens = array();
            $block_files_for_email = array();
            if(count($files)) foreach($files as $file){
                if($file->rename_option != 0){
                    $extension=end(explode(".", $file->file_name));
                    $new_name = "";
                    //print($file->rename_option);
                    switch ($file->rename_option){
                        case "20":
                            $new_name = "Blank_".str_replace(' ', '_', $address_city);
                            break;
                        case "21":
                            $new_name = "Design_".str_replace(' ', '_', $address_city);
                            break;
                        case "22":
                            $new_name = "L".$bean->number."Switchboard";
                            break;
                        case "23":
                            $new_name = "Map";
                            break;
                        case "24":
                            $new_name = "Street_View";
                            break;
                        case "25":
                            $new_name = "L".$bean->number."Bill";
                            break;
                        case "26"://dung code - add option Meter Box
                            $new_name = "L".$bean->number."_Meter_Box";
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
                            $new_name = $path_parts['filename'] ."_" .$bean->distributor_c;
                            break;
                        case "32"://thienpb code
                            $new_name = "L".$bean->number."Roof_Pitch";
                            break;
                        case "35":
                            $new_name = "L".$bean->number."_Meter_UpClose";
                            break;
                        case "36":
                            $new_name = "L".$bean->number."_Proposed_Install_Location";
                            break;
                        case "37":
                            $new_name = "L".$bean->number.'_Remittance_Advice';
                            break;
                        case "38":
                            $new_name = "L".$bean->number.'_Existing_HWS';
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
            $bean->file_attachment_c = json_encode($file_attachmens);
            $bean->block_files_for_email_c = json_encode($block_files_for_email);
            //$bean->save();

            //Thienpb update code
            $fields = $bean->field_name_map;
            foreach($fields as $field_name => $field_defs){
               if(!empty($bean->$field_name)){
                $bean->$field_name = trim($bean->$field_name);
               }
            }
        }
    }
    class LeadChangeEmailStatus
    {
        function before_save_method_changeEmailStatus($bean, $event, $arguments){
            // $old_fields = $bean->fetched_row;
            // // if($bean->primary_address_street != '' && strlen($bean->primary_address_street) > 3 
            // //                                        && $bean->primary_address_city != '' 
            // //                                        && $bean->primary_address_state != '' 
            // //                                        && $bean->primary_address_postalcode != '')
            // // {
            // //     if($bean->email_send_status_c != 'sent'){
            // //         $bean->email_send_status_c = "dontSend";
            // //     }
            // //     if($bean->email_send_design_status_c != 'sent'){
            // //         $bean->email_send_design_status_c = "dontSend";
            // //     }
            // // }
        }
    }

    class LeadSentEmailToCustomerFromPESite {
        function after_save_method_set_entry( $bean, $event, $arguments){
            if(isset($_POST['method']) && isset($_POST['rest_data']) && $_POST['method'] == 'set_entry') {
                //config mail
                $db = DBManagerFactory::getInstance();
                $sql = "Update leads_cstm SET lead_source_co_c = 'PureElectric' WHERE id_c = '$bean->id'";
                $ret = $db->query($sql);
                // auto save PE entry  = PE Website Get a free quote 
                $sql = "Update leads_cstm SET entered_communicated_into_pe_c = 'pe_website_get_a_free_quote' WHERE id_c = '$bean->id'";
                $ret = $db->query($sql);

                $array_product = json_decode(html_entity_decode($_POST['rest_data']));
                // setup Lead Source
                $lead_source = $array_product->name_value_list[10]->value;
                if(is_null($lead_source) || $lead_source == '' || $lead_source == 'Other'){
                    $sql_update_lead_source = "Update leads SET lead_source = 'PE_website_quote_form' WHERE id = '$bean->id'";
                    $ret = $db->query($sql_update_lead_source);
                }
                foreach ($array_product->name_value_list as $key => $value) {
                    if($value->name == 'description'){
                        $str_products = preg_match('/Products:(.+?).Hot Water Type/s',$value->value,$match);
                        if($str_products == 0){
                            $str_products = preg_match('/Products:(.+?).Please select/s',$value->value,$match);
                        }

                        $product_info = str_replace('Products: ', ' ',str_replace(',',' &#13;&#10;',explode('.',$value->value)[0]));
                        $sql = "Update leads_cstm SET requested_products_c = '$product_info' WHERE id_c = '$bean->id'";
                        $ret = $db->query($sql);

                        $array_products =array_map('strtolower',array_map('trim', explode(',',$match[1])));
                        if(count($array_products) == 2 && in_array("methven kiri satinjet ultra low flow showerhead",$array_products)){
                            $lead = $bean;     
                               //config mail
                                $emailObj = new Email();
                                $defaults = $emailObj->getSystemDefaultEmail();
                                $mail = new SugarPHPMailer();
                                $mail->setMailerForSystem();
                                $mail->From = $defaults['email'];
                                $mail->FromName = $defaults['name'];
                                $mail->IsHTML(true);
                        
                                //get email template and replace Email Variables
                                $emailtemplate = new EmailTemplate();
                                $emailtemplate = $emailtemplate->retrieve("ec302586-cd96-e843-bd9b-5b25c5b0b321");
                            
                                $emailtemplate->parsed_entities = null;
                                $macro_nv = array();
                                $focus = BeanFactory::getBean('Leads', $lead->id);
                            
                                $template_data = $emailtemplate->parse_email_template(
                                    array(
                                        "subject" => $emailtemplate->subject,
                                        "body_html" => $emailtemplate->body_html,
                                        "body" => $emailtemplate->body
                                        ),
                                        'Leads',
                                        $focus,
                                        $temp
                                    );
                                $email_body = str_replace('$lead_first_name',$lead->first_name,$template_data["body_html"]);
                                $email_subject =$template_data["subject"];
                                
                                //get and add attachment from template
                        
                                $note = new Note();
                                $where = "notes.parent_id = 'ec302586-cd96-e843-bd9b-5b25c5b0b321'";
                                $attachments = $note->get_full_list("", $where, true);
                                $all_attachments = array();
                                $all_attachments = array_merge($all_attachments, $attachments);
                                foreach($all_attachments as $attachment) {
                                    $file_name = $attachment->filename;
                                    global $sugar_config;
                                    $location = $sugar_config['upload_dir'].$attachment->id;
                                    $mime_type = $attachment->file_mime_type;
                                    // Add attachment to email
                                    $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
                                }
                                
                                $mail->Subject = $email_subject;

                                //SignatureId User 
                                if($lead->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
                                    $mail->From = "matthew.wright@pure-electric.com.au";
                                    $mail->FromName = "PureElectric";
                                    $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";   
                                }else{
                                    $mail->From = "paul.szuster@pure-electric.com.au";
                                    $mail->FromName = "PureElectric";
                                    $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe"; 
                                }
                                $user = new User();
                                $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
                                $signature = $user->getSignature($emailSignatureId);

                                $email_body .= $signature["signature_html"];  
                                $mail->Body = $email_body;

                                $mail->AddAddress("admin@pure-electric.com.au");
                                $mail->AddAddress($lead->email1);
                                $mail->prepForOutbound();    
                                $mail->setMailerForSystem();   
                                $sent = $mail->send();                
                        }

                        // auto select products type 
                        $array_products_type = array();
                        foreach ($array_products as $key_product => $value_product) {
                            switch ($value_product) {
                                case 'sanden eco heat pump hot water':
                                    $array_products_type[] = 'quote_type_sanden';                
                                    break;
                                case 'daikin us7':
                                    $array_products_type[] = 'quote_type_daikin'; 
                                    break;   
                                case 'off grid':
                                    $array_products_type[] = 'quote_type_off_grid_system'; 
                                    break;     
                                case 'methven kiri satinjet ultra low flow showerhead':
                                    $array_products_type[] = 'quote_type_methven'; 
                                    break;
                                case 'rooftop solar':
                                    $array_products_type[] = 'quote_type_solar'; 
                                    break;            
                                case 'daikin nexura':
                                    $array_products_type[] = 'quote_type_nexura'; 
                                    break;                     
                                default:
                                    # code...
                                    break;
                            }
                        }
                        $string_products_type = '^' .implode('^,^',$array_products_type) .'^';
                        $sql = "Update leads_cstm SET product_type_c = '$string_products_type' WHERE id_c = '$bean->id'";
                        $ret = $db->query($sql);
                    }
                }
                
            }
        }
    }
    class CreateInternalNotesLead {
        function before_save_method_CreateInternalNotes ($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            global $current_user;
            // case 1: create new 
            if($old_fields == false) {       
                //thienpb update set folderUploadID
                $uuid = md5(uniqid(mt_rand(), true));
                $guid =  substr($uuid,0,8)."-".
                        substr($uuid,8,4)."-".
                        substr($uuid,12,4)."-".
                        substr($uuid,16,4)."-".
                        substr($uuid,20,12);
                
                $folderUploadID =  $guid;
                $bean->installation_pictures_c = $folderUploadID;
                //end
                //check internal notes new 
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT pe_internal_note.id as id  FROM pe_internal_note 
                LEFT JOIN leads_pe_internal_note_1_c ON leads_pe_internal_note_1_c.leads_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id 
                LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated' 
                AND pe_internal_note.description = 'Lead Status : New' 
                AND leads_pe_internal_note_1_c.leads_pe_internal_note_1leads_ida ='$bean->id' ";
    
                $ret = $db->query($sql);
                if($ret->num_rows == 0){
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Lead Status : '.$bean->status;
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('leads_pe_internal_note_1');
                    $bean_intenal_notes->leads_pe_internal_note_1->add($bean->id);
                }
            }else{
                //case 2 : updated
                if($old_fields['status'] != $bean->status){
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Lead Status : ';
                    switch ($bean->status) {
                        case 'Draft':
                            $decription_internal_notes .= 'New';
                            break;
                        case 'Lost_Competitor':
                            $decription_internal_notes .= 'Lost - Competitor';
                            break;
                        case 'Lost_No_Longer_Interested':
                            $decription_internal_notes .= 'Lost - No Longer Interested';
                            break;
                        case 'Lost_Unsuitable_Site_Contract':
                            $decription_internal_notes .= 'Lost - Unsuitable Site/Contract';
                            break;
                        case 'Lost_No_Finance':
                            $decription_internal_notes .= 'Lost - No Finance/Landlord/Other Approval';
                            break;
                        case 'Request_Designs':
                            $decription_internal_notes .= 'Request Designs';
                            break;
                        case 'JobAccepted_InProgress':
                            $decription_internal_notes .= 'Job Accepted In Progress';
                            break;
                        case 'Designs_Complete':
                            $decription_internal_notes .= 'Designs Complete';
                            break;
                        case 'Site_Inspection_Requested':
                            $decription_internal_notes .= 'Site Inspection Requested';
                            break;
                        case 'Site_Inspection_Booked':
                            $decription_internal_notes .= 'Site Inspection Booked';
                            break;
                        case 'Site_Inspection_Completed':
                            $decription_internal_notes .= 'Site Inspection Completed';
                            break;                     
                        default:
                            $decription_internal_notes .= $bean->status;
                            break;
                    }
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                 
                    $bean_intenal_notes->load_relationship('leads_pe_internal_note_1');
                    $bean_intenal_notes->leads_pe_internal_note_1->add($bean->id);
                }
            }
    
        }
    }

    class UpdateDataToAccountAndContact{
        function before_save_method_UpdateDataToAccountAndContact($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            // case 1: create new 
            if($old_fields !== false) { 
                  // update contact
                  $contact = new Contact();
                  $contact->retrieve($bean->contact_id);
                  if($contact->id != '') {
                      $contact->salutation = $bean->salutation;
                      $contact->first_name = $bean->first_name;
                      $contact->last_name = $bean->last_name;
                      $contact->phone_work = $bean->phone_work;
                      $contact->phone_mobile = $bean->phone_mobile;
                      $contact->department = $bean->department;
                      $contact->phone_fax = $bean->phone_fax;
                      $contact->email1 = $bean->email1;
                      $contact->primary_address_street = $bean->primary_address_street;
                      $contact->primary_address_city = $bean->primary_address_city;
                      $contact->primary_address_state = $bean->primary_address_state;
                      $contact->primary_address_postalcode = $bean->primary_address_postalcode;
                      $contact->primary_address_country = $bean->primary_address_country;
                      $contact->assigned_user_name = $bean->assigned_user_name;
                      $contact->assigned_user_id = $bean->assigned_user_id;
                      $contact->save();
                  }
  
                  // update account
                  $account = new Account();
                  $account->retrieve($bean->account_id);
                  if( $account->id != '') {
                      //thienpb change logic
                      if(empty($bean->account_name)){
                          $account->name = $bean->first_name ." " . $bean->last_name;
                      }else{
                        $account->name = $bean->account_name;
                      }                      
                      $account->phone_office = $bean->phone_office;
                      $account->phone_fax = $bean->phone_fax;
                      $account->mobile_phone_c = $bean->phone_mobile;
                      $account->website = $bean->website;
                      $account->email1 = $bean->email1;
                      $account->billing_address_street = $bean->primary_address_street;
                      $account->billing_address_city = $bean->primary_address_city;
                      $account->billing_address_state = $bean->primary_address_state;
                      $account->billing_address_postalcode = $bean->primary_address_postalcode;
                      $account->billing_address_country = $bean->primary_address_country;
                      $account->assigned_user_name = $bean->assigned_user_name;
                      $account->assigned_user_id = $bean->assigned_user_id;
                      $account->primary_contact_c = $contact->id;
                      $account->save();
                      if($contact->id != ''){
                        $contact->account_id = $account->id;
                        $contact->account_name = $account->name;
                        $contact->save();
                      }
                  }
            }else{
                $db = DBManagerFactory::getInstance();
                $query = "SELECT accounts.id AS account_id,accounts_contacts.contact_id  FROM accounts 
                            LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = accounts.id 
                            LEFT JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id 
                            LEFT JOIN accounts_contacts ON accounts_contacts.account_id = accounts.id
                            WHERE accounts.name = '".$bean->account_name."' AND email_addresses.email_address = '".$bean->email1."' AND accounts.deleted = 0 
                            ORDER BY accounts.date_entered LIMIT 0,1";
                $ret = $db->query($query);
                if($ret->num_rows > 0){
                    $row = $db->fetchByAssoc($ret);
                    $bean->account_id = $row['account_id'];
                    $bean->contact_id = $row['contact_id'];
                    //$bean->account_name = $bean->account_name;

                }else{
                    //create contact
                    $contact = new Contact();
                    $contact->salutation = $bean->salutation;
                    $contact->first_name = $bean->first_name;
                    $contact->last_name = $bean->last_name;
                    $contact->phone_work = $bean->phone_work;
                    $contact->phone_mobile = $bean->phone_mobile;
                    $contact->department = $bean->department;
                    $contact->phone_fax = $bean->phone_fax;
                    $contact->email1 = $bean->email1;
                    $contact->primary_address_street = $bean->primary_address_street;
                    $contact->primary_address_city = $bean->primary_address_city;
                    $contact->primary_address_state = $bean->primary_address_state;
                    $contact->primary_address_postalcode = $bean->primary_address_postalcode;
                    $contact->primary_address_country = $bean->primary_address_country;
                    $contact->assigned_user_name = $bean->assigned_user_name;
                    $contact->assigned_user_id = $bean->assigned_user_id;
                    $contact->save();
                    $bean->contact_id = $contact->id;

                    //create account
                    $account = new Account();
                    //thienpb change logic
                    if(empty($bean->account_name)){
                        $account->name = $bean->first_name ." " . $bean->last_name;
                    }else{
                        $account->name = $bean->account_name;
                    }
                    $account->phone_office = $bean->phone_office;
                    $account->phone_fax = $bean->phone_fax;
                    $account->mobile_phone_c = $bean->phone_mobile;
                    $account->website = $bean->website;
                    $account->email1 = $bean->email1;
                    $account->billing_address_street = $bean->primary_address_street;
                    $account->billing_address_city = $bean->primary_address_city;
                    $account->billing_address_state = $bean->primary_address_state;
                    $account->billing_address_postalcode = $bean->primary_address_postalcode;
                    $account->billing_address_country = $bean->primary_address_country;
                    $account->assigned_user_name = $bean->assigned_user_name;
                    $account->assigned_user_id = $bean->assigned_user_id;
                    $account->primary_contact_c = $contact->id;
                    $account->save();
                    if($contact->id != ''){
                        $contact->account_id = $account->id;
                        $contact->account_name = $account->name;
                        $contact->save();
                    }
                    $bean->account_id = $account->id;
                }
            }
        }
    }

    // VUT - Change status = "Spam" If the phone number is starts with a "8" for new Lead
    class ChangeStatusToSpam {
        function before_save_method_ChangeStatusToSpam ($bean, $event, $arguments) {
            $old_fields = $bean->fetched_row;
            global $current_user;
            /**new Leads*/
            if ($old_fields == false) {
                if ($bean->phone_mobile[0] == "8" && $bean->status != "Spam") {
                    $bean->status = "Spam";
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $bean_intenal_notes->description =  "Lead Status : {$bean->status}";
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                 
                    $bean_intenal_notes->load_relationship('leads_pe_internal_note_1');
                    $bean_intenal_notes->leads_pe_internal_note_1->add($bean->id);
                }
            }
        }
    }

?>