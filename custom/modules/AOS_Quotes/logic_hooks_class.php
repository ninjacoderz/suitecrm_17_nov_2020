<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 3/19/17
 * Time: 6:12 PM
 */


    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class QuoteRenameUploadFiles
    {
        function after_save_method($bean, $event, $arguments)
        {
            //logic

            $files = json_decode(html_entity_decode($bean->file_rename_c));

            //$current_file_path = dirname(__FILE__);
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->pre_install_photos_c;
            $invoice_number = $bean->number;

            // Thienpb fix for add city to name
            $address = $bean->billing_address_street;
            $city = $bean->billing_address_city;
            $address_city = $address.'_'.strtolower($city);

            if(count($files)) foreach($files as $file){
                $extension=end(explode(".", $file->file_name));
                if($file->rename_option != 0){
                    $new_name = "";
                    //print($file->rename_option);
                    switch ($file->rename_option){
                        case "15":
                            $new_name = "Q".$invoice_number.'Old';
                            break;
                        case "16":
                            $new_name = "Q".$invoice_number.'New';
                            break;
                        case "17":
                            $new_name = "Q".$invoice_number.'Diagram';
                            break;
                        case "18":
                            $new_name = "Q".$invoice_number.'Switchboard';
                            break;
                        case "33":
                            $new_name = "Q".$invoice_number.'ShippingConfirmation';
                            break;
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
                            $new_name = $path_parts['filename'] ."_" .$bean->distributor_c;
                            break;
                        case "32"://thienpb code
                            $new_name = "Q".$invoice_number."_Roof_Pitch";
                            break;
                        case "35":
                            $new_name = "Q".$invoice_number."_Meter_UpClose";
                            break;
                        case "36":
                            $new_name = "Q".$invoice_number."_Proposed_Install_Location";
                            break;
                        case "37":
                            $new_name = "Q".$invoice_number.'Remittance_Advice';
                            break;
                        case "38":
                            $new_name = "Q".$invoice_number.'_Existing_HWS';
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
            }

            //get all list block file from  json file
            $path_ListBlockFile = '';
            $path_ListBlockFile = dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/ListBlockFile.json';       
            $file_arr = json_decode(file_get_contents($path_ListBlockFile), true);

            $file_arr = array_map('strtolower', $file_arr);
            $file_attachmens = scandir( $current_file_path . '/');
            foreach ($file_attachmens as $key => $value) {
                if(in_array(strtolower(($value)),$file_arr)){
                    $fp = fopen($current_file_path."/".$value, "w+");
                    fwrite($fp, '');
                    fclose($fp);
                }          
            }
            if($bean->quote_type_c == 'quote_type_solar'){
                $this->autoUploadToSAM($bean);
            }

        }
        
        function curlSG($type = 'GET',$data_string,$url,$id){

            $glb_username = $GLOBALS['username'];
            $glb_password = $GLOBALS['password'];
    
            $content_length = '';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        
            if($data_string != '' && $type == 'POST'){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_POST, 1);
                $content_length = "Content-Length: " .strlen($data_string);
            }
        
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl,CURLOPT_ENCODING , "gzip");
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    $content_length,
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($glb_username . ":" . $glb_password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$id,
                    "Cache-Control: max-age=0"
                )
            );
            $result = curl_exec($curl);
            curl_close($curl);
            if($type == 'GET') return $result;
        }
        function autoUploadToSAM($bean){
            //thienpb fix Upload all file image into solargain
            if($bean->pre_install_photos_c !== '') {
                $folder_ = dirname(__FILE__) . "/../../include/SugarFields/Fields/Multiupload/server/php/files/".$bean->pre_install_photos_c ."/";
                $files = scandir($folder_);
                $quoteSG_id = $bean->solargain_quote_number_c;

                $GLOBALS['username'] = "matthew.wright";
                $GLOBALS['password'] =  "MW@pure733";
                $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
                $quoteSG = $this->curlSG('GET','',$url,$quoteSG_id);
                $quote_decode = json_decode($quoteSG);
                $decode_result = json_decode($quoteSG,true);

                if(!isset($quote_decode->ID)){
                    $GLOBALS['username'] = 'paul.szuster@solargain.com.au';
                    $GLOBALS['password'] = 'S0larga1n$';
                    //get data from SG quote
                        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
                        $quoteSG = $this->curlSG('GET','',$url,$quoteSG_id);
                        $quote_decode = json_decode($quoteSG);
                        $decode_result = json_decode($quoteSG,true);
                    //END
                }
                $files = array_diff($files, array('.', '..','thumbnail'));
                foreach($files as $file) {
                    $file_type = strtolower(substr($file,-4));
                    $file_type = str_replace(".","",$file_type);
                    if($file_type == 'png' || $file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'gif'|| $file_type == "pdf"){
                        //check exist file on SAM
                        if(array_search($file, array_column($decode_result['Files'],'Filename')) !== false) continue;

                        $category = '';
                        //upload file meter box need add field Category = ""mextabog" 
                        if(strpos($file, 'Meter_Box') !== false || strpos($file, 'Meter_UpClose') !== false) {
                            $category = 3;
                        }else if(strpos($file, 'Acceptance') !== false) {
                            $category = 1;
                        }else if(strpos($file, 'Switchboard') !== false) {
                            $category = 2;
                        }else if(strpos($file, 'Bill') !== false) {
                            $category = 12;
                        }
                        if($category !== ''){
                            $content_file =  file_get_contents($folder_.'/'.$file);
                            //Push all Image to solargain
                            $data_file_upload = array(
                                'Data'     => base64_encode($content_file),
                                'Filename' => $file,
                                'Title'    => $file,
                                'Url'      => "",
                            );
                            $data_tring_upload = json_encode($data_file_upload);

                            //PUSH
                            $url = "https://crm.solargain.com.au/APIv2/quotes/" .$quoteSG_id."/upload";
                            $this->curlSG('POST',$data_tring_upload,$url,$quoteSG_id);
                            
                            //GET
                            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                            $result = $this->curlSG('GET','',$url,$quoteSG_id);
                            $decode_result_files_image_upload = json_decode($result,true);
                            $id_file_image_bill = '';
                            foreach ($decode_result_files_image_upload as $value){
                                if($value['Filename'] == $file){
                                    $id_file_image_bill = $value['ID'];
                                }
                            }
                            //GET
                            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_bill ."/category/".$category;
                            $this->curlSG('GET','',$url,$quoteSG_id);
                        }
                    }
                    
                }
                    
            }
        }
            
        
    }

    class QuoteAddAttachments
    {
        function after_save_method($bean, $event, $arguments)
        {

            //$current_file_path = dirname(__FILE__);
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->pre_install_photos_c;
            if( file_exists ( $current_file_path ) ) return;
            // Query to get notes file
            $account_id = $bean->billing_account_id;
            $contact_id = $bean->billing_contact_id;
            $lead_id = "";
            $db = DBManagerFactory::getInstance();

            // Get the lead id

            $sql = "SELECT * FROM leads 
            WHERE deleted = 0 AND (";
            
            if($account_id != '' && $contact_id != ''){
                $sql .= "account_id = '$account_id' OR contact_id = '$contact_id'";
            }elseif($account_id != '' && $contact_id == ''){
                $sql .= "account_id = '$account_id'";
            }elseif($account_id == '' && $contact_id != ''){
                $sql .= "contact_id = '$contact_id'";
            }else{
                return;
            }

            $sql .= ')';

            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["id"] != ""){
                    $lead_id = $row["id"];
                }
            }

            //check billing_account = Solargain PV account
            if($account_id == '61db330d-0aee-6661-8ac3-585c79c765a2'){
                $account_id = '';
            }
            
            $sql = "SELECT nt.id as note_id, nt.filename as file_name FROM notes nt 
                    LEFT JOIN emails_beans eb ON eb.email_id = nt.parent_id 
                    WHERE 1=1 AND nt.deleted = 0 AND nt.parent_type = 'Emails' AND 
                    (
                        (eb.bean_id = '$account_id' AND eb.bean_module = 'Accounts') OR
                        (eb.bean_id = '$contact_id' AND eb.bean_module = 'Contacts') OR
                        (eb.bean_id = '$lead_id' AND eb.bean_module = 'Leads')
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

class QuoteAfterRelationshipAdd
{
    function after_relationship_add_method($bean, $event, $arguments)
    {
        if(!isset($bean->opportunity_id)){
            return;
        }
        $opportunity_id = $bean->opportunity_id;
        $invoice_id = $arguments['related_id'];
        $oOpportunity = BeanFactory::getBean('Opportunities', $opportunity_id);
        $oOpportunity->load_relationship('opportunities_aos_invoices_1');
        $oOpportunity->opportunities_aos_invoices_1->add($invoice_id);
    }
}

class QuoteAfterRelationshipDelete
{
    function after_relationship_delete_method($bean, $event, $arguments)
    {
        if(!isset($bean->opportunity_id)){
            return;
        }
        $opportunity_id = $bean->opportunity_id;
        $invoice_id = $arguments['related_id'];
        $oOpportunity = BeanFactory::getBean('Opportunities', $opportunity_id);
        $oOpportunity->load_relationship('opportunities_aos_invoices_1');
        $oOpportunity->opportunities_aos_invoices_1->delete($invoice_id);
    }
}

class UpdateToSolargain {
    function before_save_method_UpdateToSolargain ($bean, $event, $arguments){
        $old_fields = $bean->fetched_row;
        if($old_fields['id'] == "") return;

        $lost_status_mapping = array(
            "Lost_Competitor" => "LOST_TO_COMPETITOR",
            "Lost_No_Longer_Interested" => "NO_LONGER_INTERESTED",
            "Lost_No_Finance" => "NO_APPROVAL",
            "Lost_Unsuitable_Site_Contract" => "UNSUITABLE",
        );

        if($old_fields['stage'] != $bean->stage){
            if(isset($lost_status_mapping[$bean->stage])){
                updateStatusSolargainLeadFromQuote($lost_status_mapping[$bean->stage], $bean->solargain_quote_number_c);
                updateStatusSolargainLeadFromQuote($lost_status_mapping[$bean->stage], $bean->solargain_tesla_quote_number_c);
            }
        }
    }

}

function updateStatusSolargainLeadFromQuote($status, $quoteNumber = ""){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    
    $username = "matthew.wright";
    $password =  "MW@pure733";

    // Convert quote 
    if($quoteNumber != ""){
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

}

class UpdateAcountContactCustomer {
    function update_account_contact_customer_func($bean,$event,$arguments) {
        $old_fields = $bean->fetched_row;
        if($old_fields['id'] == "") return;
        if($old_fields['account_firstname_c'] != $bean->account_firstname_c || $old_fields['account_lastname_c'] != $bean->account_lastname_c){
            $id_account = $bean->billing_account_id;
            $id_contact = $bean->billing_contact_id;
            $bean_account = new Account();
            $bean_account->retrieve($id_account);
            if($bean_account->id != '') {
                $bean_account->name = $bean->account_firstname_c .' ' . $bean->account_lastname_c;
                $bean_account->save();
            }
            $bean_contact = new Contact();
            $bean_contact->retrieve($id_contact);
            if( $bean_contact->id != '' && $bean_contact->account_id == $id_account ){
                $bean_contact->first_name = $bean->account_firstname_c;
                $bean_contact->last_name = $bean->account_lastname_c;
                $bean_contact->save();
            }
        }
    }
}


class CreateInternalNotes {
    function before_save_method_CreateInternalNotes ($bean, $event, $arguments){
        $old_fields = $bean->fetched_row;
        // case 1: create new 
        if($old_fields == false || $bean->pre_install_photos_c == "") {
            
            //thienpb update set folderUploadID
            // fix bugs: duplicate folderUploadID in here 
            if($_REQUEST['CountTimefolderUploadID'] != true) {
                $uuid = md5(uniqid(mt_rand(), true));
                $guid =  substr($uuid,0,8)."-".
                        substr($uuid,8,4)."-".
                        substr($uuid,12,4)."-".
                        substr($uuid,16,4)."-".
                        substr($uuid,20,12);
                
                $folderUploadID =  $guid;
                $bean->pre_install_photos_c = $folderUploadID;
                //end
                //copy file from Lead when quote create the first time
                $quote = $bean;
                $lead = new Lead();
                $lead->retrieve($bean->leads_aos_quotes_1leads_ida);
                if($lead->id != ''){
                    $this->convert_file_and_photo_to_quote($lead,$quote);
                }
                $_REQUEST['CountTimefolderUploadID'] = true;
            }
        }
        if($old_fields == false){
            //check internal notes new 
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT pe_internal_note.id as id  FROM pe_internal_note 
            LEFT JOIN aos_quotes_pe_internal_note_1_c ON aos_quotes_pe_internal_note_1_c.aos_quotes_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id 
            LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
            WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated' 
            AND pe_internal_note.description = 'Quote Status : New' 
            AND aos_quotes_pe_internal_note_1_c.aos_quotes_pe_internal_note_1aos_quotes_ida ='$bean->id' ";

            $ret = $db->query($sql);
            if($ret->num_rows == 0){
                $bean_intenal_notes = new  pe_internal_note();
                $bean_intenal_notes->type_inter_note_c = 'status_updated';
                $decription_internal_notes = 'Quote Status : New';
                $bean_intenal_notes->description =  $decription_internal_notes;
                $bean_intenal_notes->save();
                
                $bean_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                $bean_intenal_notes->aos_quotes_pe_internal_note_1->add($bean->id);
            }
        }else{
            //case 2 : updated
            if($old_fields['stage'] != $bean->stage){
                $bean_intenal_notes = new  pe_internal_note();
                $bean_intenal_notes->type_inter_note_c = 'status_updated';
                $decription_internal_notes = 'Quote Status : ';
                switch ($bean->stage) {
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
                        $decription_internal_notes .= $bean->stage;
                        break;
                }
                $bean_intenal_notes->description =  $decription_internal_notes;
                $bean_intenal_notes->save();
             
                $bean_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                $bean_intenal_notes->aos_quotes_pe_internal_note_1->add($bean->id);
            }

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
                $bean_intenal_notes->save();
                $bean_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                $bean_intenal_notes->aos_quotes_pe_internal_note_1->add($bean->id);
            }

        }

    }

    function convert_file_and_photo_to_quote($lead,$quote){
        $get_all_photo = $this->dirToArray_convert_to_quote($_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$lead->installation_pictures_c.'/') ;
        $array_convert_file_name = array(
            'proposed_install_location' => '_Proposed_Install_Location',
            'switchboard' => '_Switchboard',
            'shipping_confirmation' => '_ShippingConfirmation',
            'street_view' => '_Street_View',
            'remittance_advice' => 'Remittance_Advice',
            'Existing_HWS' => '_Existing_HWS',
            'Meter_UpClose' => '_Meter_UpClose',
            'Roof_Pitch' => '_Roof_Pitch',
            'Acceptance' => '_Acceptance',
            'House_Plans' => '_House_Plans',
            'Meter_Box' => '_Meter_Box',
            'Install_Photo' => '_New_Install_Photo'
        );
    
        foreach($get_all_photo as $photo){
            $file_name = $photo;
            foreach ($array_convert_file_name as $key => $label_new_file) {
                $condition_change_file = false;
                $array_explode_name =  explode('_',$key);
                // check file in quote include name in array convert file
                foreach ($array_explode_name as $value_name) {
                    if(strpos(strtolower($file_name), strtolower($value_name)) !== false ){
                        $condition_change_file = true;
                    }else{
                        $condition_change_file = false;
                    }
                }  
                if($condition_change_file){    
                    $extension=end(explode(".", $file_name));
                    $new_file_name = 'Q'.$quote->number.$label_new_file;
                    $inv_file_path = 
                    $i = 1;
                    $will_rename = $new_file_name;
                    $current_file_path_quote = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$quote->pre_install_photos_c;
                    while( !empty(glob($current_file_path_quote.'/'.$will_rename."*"))){
                      $will_rename = $new_file_name.$i;
                      $i++;
                    }
                   
                    $will_rename .= ('.'.$extension);
                    $new_file_name = $will_rename; 
                    break;
                }else{
                    $new_file_name = $file_name;
                }
            }
    
            $folderName_old  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$lead->installation_pictures_c.'/'.$file_name;
            $folderName_new  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$quote->pre_install_photos_c.'/';
          
            //check exists folder
            if(!file_exists ($folderName_new)) {
                mkdir($folderName_new);
            }
            copy($folderName_old, $folderName_new.$new_file_name);
            $this->resize_image_file_and_photo_to_quote($new_file_name,$folderName_new);
        }   
    }
    
    function resize_image_file_and_photo_to_quote($file, $current_file_path) {
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
    
    function dirToArray_convert_to_quote($dir) { 
        $result = array();
        $cdir = scandir($dir); 
        foreach ($cdir as $key => $value) 
        { 
           if (!in_array($value,array(".",".."))) 
           { 
              if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
              { 
                 $result[$value] = $this->dirToArray_convert_to_quote($dir . DIRECTORY_SEPARATOR . $value); 
              } 
              else 
              { 
                 $result[] = $value; 
              } 
           } 
        }
        return $result; 
    }
}
class UpdateSuburb {
    function before_save_method_updateSuburb ($bean, $event, $arguments){
        $old_fields = $bean->fetched_row;
        if( $bean->install_address_city_c != '') {
            if($bean->install_address_city_c != $old_fields['install_address_city_c']) {
                if (strpos($old_fields['name'], strtoupper($old_fields['install_address_city_c'])) !== false) {
                    $bean->name = str_ireplace($old_fields['install_address_city_c'],strtoupper($bean->install_address_city_c),$bean->name);  
                } else {
                    $bean->name = str_ireplace($bean->billing_address_city,strtoupper($bean->install_address_city_c),$bean->name); 
                }
            }
        } else {
            if($old_fields['install_address_city_c'] != '') {
                $bean->name = str_ireplace($old_fields['install_address_city_c'],strtoupper($bean->billing_address_city),$bean->name);
            }
        }
    }
}

class UpdateLeadSourceInLeadModule {
    function before_save_method_update_lead_source ($bean, $event, $arguments){
        if( $bean->leads_aos_quotes_1leads_ida != '') {
            $db = DBManagerFactory::getInstance();
            $sql = 'UPDATE leads_cstm SET lead_source_co_c = "'.$bean->lead_source_co_c.'" WHERE id_c = "'.$bean->leads_aos_quotes_1leads_ida.'";';
            $db->query($sql);
        }
    }
}

class AutoFillPricingOption {
    function before_save_method_autoFillPricingOption ($bean, $event, $arguments){
        if($bean->quote_type_c == 'quote_type_solar' && $bean->solargain_quote_number_c == ''){
            $bean->pe_pricing_options_id_c = '406fbeb4-0614-3bcd-7e15-5fbdea690303';
            //$bean->pe_pricing_options_id_c = '17c42ed8-fc5b-c93c-e23a-5e412bf10680';
        }
    }
}

//VUT - Check duplicate Solar Quote and auto create Sam Quote
class DuplicateSolarQuote {
    function before_save_method_duplicateSolarQuote ($bean, $event, $arguments) {
        if (isset($_REQUEST["duplicateSave"]) && $_REQUEST["duplicateSave"]) {
            if ($bean->quote_type_c == 'quote_type_solar') {
                if ($bean->solargain_lead_number_c !='' && $bean->solargain_quote_number_c == '') {
                    require_once('custom/modules/AOS_Quotes/PushToSG.php');
                    $bean->solargain_quote_number_c = create_solar_quote($bean->solargain_lead_number_c,$bean);
                    if ($bean->solargain_quote_number_c != "") {
                        update_solar_quote($bean->solargain_quote_number_c,$bean);
                    }
                }
            }
        }
    }
}

//Thienpb - set default quote field
class SetDefaultField {
    function before_save_method_setDefaultField($bean, $event, $arguments){
        $old_fields = $bean->fetched_row;
        if(!$old_fields){
            if($bean->the_quote_prepared_c != 'solar_quote_form'){
                $bean->meter_type_c = 'SmartMeter';
                $bean->main_switch_c = 'Yes';
                $bean->inverter_to_mainswitch_c = '5';
            }
        }
    }
}
?>