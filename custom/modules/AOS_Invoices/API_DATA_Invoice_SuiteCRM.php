<?php

// Main DATA 
$data = $_POST['data'];
$InvoiceID = $_POST['invoice_id'];
$path = $_POST['path'];
$node_id = $data['node_id'];
$array_model_product = array(
    'Sanden FQS 315' => 'GAUS-315FQS',               
    'Sanden FQS 300' => 'GAUS-300FQS',
    'Sanden FQS 250' => 'GAUS-250FQS',
    'Sanden FQV 300' => 'GAUS-315FQV',
    'Sanden EQTAQ 315' => 'GAUS-315EQTAQ',
    'Sanden EQTAQ 250' => 'GAUS-250EQTAQ',
);

$array_system = array(
    'New Building' => 'newBuilding',               
    'Replaced electric water heater' => 'electric_storage',
    'Replaced solar water heater' => 'solar',
    'Replaced heat pump' => 'heatpump',
    'First SWH at existing building' => 'firstSwhAtExistingBuilding',
    'Replaced gas water heater' => 'gas_storage',
);

$Invoice = new AOS_Invoices();
$Invoice->retrieve($InvoiceID);
if($Invoice->id != '') {

    $installation_date = explode('-',$data['field_survey_installation_date']['value']);
    $Invoice->installation_date_c = $installation_date[1] . '/' . $installation_date[2]. '/' .$installation_date[0] . ' 00:00';
    $Invoice->sanden_hp_serial_c = str_replace('-','',$data['field_survey_hp_serial_number']['value']) ;
    $Invoice->sanden_tank_serial_c = $data['field_survey_tank_serial_number']['value'] ;
    $Invoice->install_address_c = $data['field_install_address_street']['value'];
    $Invoice->install_address_city_c = $data['field_install_address_suburb']['value'];
    $Invoice->install_address_state_c = $data['field_install_address_state']['value'];
    $Invoice->install_address_postalcode_c = $data['field_install_address_postcode']['value'];
    $Invoice->sanden_model_c = $array_model_product[$data['field_survey_wh_product']['value']];
    $Invoice->vba_pic_cert_c = $data['field_survey_plumbing_cert_num']['value'];
    $Invoice->ces_cert_c =  $data['field_survey_elec_ins_cert_num']['value'];
    $Invoice->old_tank_fuel_c =  $array_system[$data['field_survey_system']['value']];
    
    if($data['field_survey_is_sys_company']['value'] == 'yes') {
        $Invoice->registered_for_gst_c = true;
    }else{
        $Invoice->registered_for_gst_c = false;
    }

    //set up account and contact plumber
    $email_plumber = $data['field_survey_plumbing_ins_email']['value'];
    $data_address = get_address($data['field_survey_plumbing_ins_add']['value']);
    $array_plumber_contacts = get_contacts_id_by_email($email_plumber);
    $plumber_contact = create_contacts_plum( $data,$array_plumber_contacts[0],$Invoice,$data_address,$email_plumber );
    $array_plumber_accounts = get_accounts_id_by_email($email_plumber);
    $plumber_account = create_accounts_plum( $data,$array_plumber_contacts[0],$Invoice,$data_address,$email_plumber );
    $plumber_account->load_relationship('contacts');
    $plumber_account->contacts->add($plumber_contact);
    $plumber_account->save();
    $Invoice->account_id1_c = $plumber_account->id;
    $Invoice->contact_id4_c = $plumber_contact->id;

    //set up account and contact Electric
    $email_electrican = $data['field_survey_elec_ins_email']['value'];
    $data_address = get_address($data['field_survey_elec_ins_add']['value']);
    $array_electrican_contacts = get_contacts_id_by_email($email_electrican);
    $electrican_contact = create_contacts_elec( $data,$array_electrican_contacts[0],$Invoice,$data_address,$email_electrican);
    $array_electrican_accounts = get_accounts_id_by_email($email_electrican);
    $electrican_account = create_accounts_elec( $data,$array_electrican_contacts[0],$Invoice,$data_address,$email_electrican);
    $electrican_account->load_relationship('contacts');
    $electrican_account->contacts->add($electrican_contact);
    $electrican_account->save();
    $Invoice->account_id_c = $electrican_account->id;
    $Invoice->contact_id_c = $electrican_contact->id;

    switch ($data['field_survey_property_type']['value']) {
        case 'Commercial':
            $Invoice->property_type_c = 'commercial';
            break;
        case 'Residential':
            $Invoice->property_type_c = 'residential';
            break;  
        default:
            $Invoice->property_type_c = 'school';
            break;
    }
    
    if(strpos($data['field_survey_system_owner_addres']['value'],'The Postal Address is the same as the installation address') !== false) {
        $custom_postal_address = preg_match('/Manually input the Postal Address:(.*)/s',$data['field_survey_system_owner_addres']['value'],$out_put);
        $Invoice->billing_address_street =  $out_put[1];
    }else{
        //billing address =  install address
        $Invoice->billing_address_street =  $Invoice->install_address_c;
        $Invoice->billing_address_city =  $Invoice->install_address_city_c;
        $Invoice->billing_address_state =  $Invoice->install_address_state_c;
        $Invoice->billing_address_postalcode =  $Invoice->install_address_postalcode_c;
    }

    switch ($data['field_survey_number_of_storeys']['value']) {
        case 'multi storeys':
            $Invoice->number_of_storeys_c = 'true';
            break;
        default:
            $Invoice->number_of_storeys_c = 'false';
            break;
    }


    if($data['field_survey_number_installation']['value'] == "This is the only system installed at this address") {
        $Invoice->number_of_installations_c = 'false';
    }else  {
        $Invoice->number_of_installations_c = 'true';
    }

    //check folder and  get file img
    $pre_install_photos_c = $Invoice->installation_pictures_c;
    $path_save_file = $_SERVER['DOCUMENT_ROOT']."/custom/include/SugarFields/Fields/Multiupload/server/php/files/" .$pre_install_photos_c.'/';
    $array_link_result = $path;
    $count_file_install_photos = 1;
    foreach ($array_link_result as $key => $value) {
            $out_source = '';
            $in_source = '';
            $file_name = '';

            $array_link = explode('/',$value);
           
            //logic name file 
            $type_file = strtolower(substr(strrchr($array_link[8], '.'), 1));
            switch ($array_link[7]) {
                case 'customer-agreement':
                    $file_name = $array_link[7].'_'.$array_link[8];
                    break;
                case 'install-tax-invoice':
                    $file_name = $array_link[7].'_'.$array_link[8];
                    break;
                case 'install-photos':
                    $file_name = $Invoice->number.'New'.$count_file_install_photos.'.'.$type_file;
                    $count_file_install_photos ++;
                    break;
                case 'plumbing-compliance-certificate':
                    $file_name = $Invoice->number.'PCOC_'. str_replace(' ','_', $Invoice->vba_pic_cert_c).'.'.$type_file;
                    break;
                case 'electrical-installation-compliance-certificate':
                    $file_name = $Invoice->number.'CES_'. str_replace(' ','_',$Invoice->ces_cert_c).'.'.$type_file;
                    break;                      
                case 'geo-tag-photo':
                    $file_name =  $Invoice->number.'Geotaggedphoto'.'.'.$type_file;
                    break;                
                default:
                    $file_name = $array_link[7].'_'.$array_link[8];
                    break;
            }
            $out_source = $path_save_file.$file_name;
            $in_source = $value;
            // copy file root
            if(!file_exists ($_SERVER['DOCUMENT_ROOT']."/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$pre_install_photos_c .'/')) {
                mkdir($_SERVER['DOCUMENT_ROOT']."/custom/include/SugarFields/Fields/Multiupload/server/php/files/".$pre_install_photos_c .'/');
            }
            copy($in_source,$out_source);
            // create image for file pdf and create thumbnail
            create_image_from_pdf($out_source,$file_name,$path_save_file);
            create_thumbnail($out_source,$file_name,$path_save_file);       
            
    }
    $Invoice->save();
    Send_Email_Notification($node_id,$InvoiceID);
}

//function create thumbnail from source
function create_thumbnail($source,$file_name,$path_save_file){
    $type = strtolower(substr(strrchr($file_name, '.'), 1));
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
            if(!file_exists ($path_save_file."/thumbnail/")) {
            mkdir($path_save_file."/thumbnail/");
            }
            $thumb =  $path_save_file."/thumbnail/".$file_name;
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
            list($w, $h) = getimagesize($source);

            $src = $src_func($source);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
            }
    }         
}

//function create image from file pdf
function create_image_from_pdf($source,$file_name,$path_save_file){
    $arr_name_file = explode(".", $file_name);
    $type = $arr_name_file[1];
    $new_name_file_pdf =$arr_name_file[0] ;
    $typeok = TRUE;
    $path_to_write = '';
    if($type == 'pdf'){
            $l_Image = new Imagick();
            $l_Image->setResolution(150, 150);
            $l_Image->readImage($source);
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

function Send_Email_Notification($node_id,$InvoiceID){
    $Invoice = new AOS_Invoices();
    $Invoice->retrieve($InvoiceID);
    if($Invoice->id == '') { return false;}
    $customer_name = $Invoice->billing_account;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  $customer_name.' Inv#'.$Invoice->number.' submitted the Sanden Subsidy Form';
    date_default_timezone_set('Australia/Melbourne');
    $dateAUS = date('m/d/Y h:i', time());

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $dateVIE = date('m/d/Y h:i', time());

    $dateInfos = explode(" ",$Invoice->installation_date_c);
    $dateInfos = explode("/",$dateInfos[0]);
    $inv_install_date_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
    $timestamp_inv_installdate = date("d/m/Y", strtotime($inv_install_date_str));
    $style_td = 'padding-top: 5px; font-weight: bold;  text-align: left;border: 1px solid black;';

    $style_button  = 'color:#fff;font-family:Helvetica;font-size: 15px;margin:3px;line-height:100%;text-align:center;text-decoration:none;background-color:#428bca;border:1px solid #428bca;display:inline-block;font-weight:bold;padding-top: 10px;padding-right: 16px;padding-bottom: 10px;padding-left: 16px;border-radius:5px;';
    
    $InformationInvoice = 
    '<h2>Sanden Subsidy Form</h2>'
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
                <td style="'. $style_td .'">Installation Date:</td>
                <td style="'. $style_td .'">'.$timestamp_inv_installdate.'</td>
                <td style="'. $style_td .'">Install Address:</td>
                <td style="'. $style_td .'">'.$Invoice->install_address_c.' ' .$Invoice->install_address_city_c .' ' .$Invoice->install_address_state_c .' '.$Invoice->install_address_postalcode_c.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Sanden Model:</td>
                <td style="'. $style_td .'">'.$Invoice->sanden_model_c.'</td>
                <td style="'. $style_td .'">Installation Date:</td>
                <td style="'. $style_td .'">'.$Invoice->number.'</td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Plumbing Installer:</td>
                <td style="'. $style_td .'">'.$Invoice->plumber_c.'</td>
                <td style="'. $style_td .'">Electrical Installer:</td>
                <td style="'. $style_td .'">'.$Invoice->electrician_c.'</td>
            </tr>
        </tbody>
    </table>';
    $mail->Body = '<div><p>Hi Accounts Team,</p><p>' 
        . $customer_name . ' submitted the Sanden Subsidy Form</p>' 
        . ' <p>Please process the paperwork </p></div>'
    .$InformationInvoice
    .'<br><div><a style="'.$style_button.'" target="_blank" href="https://pure-electric.com.au/node/'.$node_id.'">Link PE Survey Form →</a>
     <a style="'.$style_button.'" target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$InvoiceID.'">Link CRM Invoice →</a></div>';
 
    // $mail->AddAddress('admin@pure-electric.com.au');
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress('accounts@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function get_accounts_id_by_email($address_email) {
    global $db;
    $array_id = [];
    $query = 
    "SELECT accounts.id as id 
    FROM accounts 
    INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = accounts.id
    INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE accounts.deleted = 0  AND email_addresses.email_address = '$address_email'";
    $result = $db->query($query);
    if($result->num_rows > 0) {
        while ($row = $db->fetchByAssoc($result)) {
            $array_id[] =$row['id'];
        }
    }
    return $array_id;
}

function get_contacts_id_by_email($address_email) {
    global $db;
    $array_id = [];
    $query = 
    "SELECT contacts.id as id 
    FROM contacts 
    INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = contacts.id
    INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE contacts.deleted = 0  AND email_addresses.email_address = '$address_email'";
    $result = $db->query($query);
    if($result->num_rows > 0) {
        while ($row = $db->fetchByAssoc($result)) {
            $array_id[] =$row['id'];
        }
    }
    return $array_id;
}

function create_contacts_plum($data, $contactID ='',$beanInvoice,$data_address,$address_email){
    $contact = new Contact();
    $contact->retrieve($contactID);
    if($contact->id == ''){
        $FullName  = explode(",",$data['field_survey_plumbing_ins_name']['value']);
        $contact->first_name = $FullName[0];
        $contact->last_name = $FullName[1];
        $contact->phone_mobile = $data['field_survey_plumbing_ins_phone']['value'];
        $contact->primary_address_street = $data_address['street'] ;
        $contact->primary_address_city = $data_address['city'] ;
        $contact->primary_address_state =  $data_address['state'] ;
        $contact->primary_address_postalcode = $data_address['postcode'];
        $contact->email1 = $data['field_survey_plumbing_ins_email']['value'];
        $contact->assigned_user_name = $beanInvoice->assigned_user_name;
        $contact->assigned_user_id = $beanInvoice->assigned_user_id;
        $contact->save();
    }
    return  $contact;
}

function create_contacts_elec($data, $contactID ='',$beanInvoice,$data_address,$address_email){
    $contact = new Contact();
    $contact->retrieve($contactID);
    if($contact->id == ''){
        $FullName  = explode(",",$data['field_survey_elec_ins_name']['value']);
        $contact->first_name = $FullName[0];
        $contact->last_name = $FullName[1];
        $contact->phone_mobile = $data['field_survey_elec_ins_phone']['value'];
        $contact->primary_address_street = $data_address['street'] ;
        $contact->primary_address_city = $data_address['city'] ;
        $contact->primary_address_state =  $data_address['state'] ;
        $contact->primary_address_postalcode = $data_address['postcode'];
        $contact->email1 = $data['field_survey_elec_ins_email']['value'];
        $contact->assigned_user_name = $beanInvoice->assigned_user_name;
        $contact->assigned_user_id = $beanInvoice->assigned_user_id;
        $contact->save();
    }
    return  $contact;
}

function create_accounts_plum($data, $accountID ='',$beanInvoice,$data_address,$address_email){
    $account = new Account();
    $account->retrieve($accountID);
    if($account->id == ''){
        $account->name = $data['field_survey_plumbing_ins_name']['value'];
        $account->mobile_phone_c = $data['field_survey_plumbing_ins_phone']['value'];
        $account->billing_address_street = $data_address['street'] ;
        $account->billing_address_city = $data_address['city'] ;
        $account->billing_address_state = $data_address['state'] ;
        $account->billing_address_postalcode = $data_address['postcode'] ;
        $account->assigned_user_name = $beanInvoice->assigned_user_name;
        $account->assigned_user_id = $beanInvoice->assigned_user_id;
        $account->email1 = $data['field_survey_plumbing_ins_email']['value'];
        $account->save();  
    }
    return  $account;
}

function create_accounts_elec($data, $accountID ='',$beanInvoice,$data_address,$address_email){
    $account = new Account();
    $account->retrieve($accountID);
    if($account->id == ''){
        $account->name = $data['field_survey_elec_ins_name']['value'];
        $account->mobile_phone_c = $data['field_survey_elec_ins_phone']['value'];
        $account->billing_address_street = $data_address['street'] ;
        $account->billing_address_city = $data_address['city'] ;
        $account->billing_address_state = $data_address['state'] ;
        $account->billing_address_postalcode = $data_address['postcode'] ;
        $account->assigned_user_name = $beanInvoice->assigned_user_name;
        $account->assigned_user_id = $beanInvoice->assigned_user_id;
        $account->email1 =  $data['field_survey_elec_ins_email']['value'];
        $account->save();  
    }
    return  $account;
}

function get_address($string_address){
    $return_data=[];
    $return_data['street'] ='';
    $return_data['city'] = '';
    $return_data['state'] = '';
    $return_data['postcode'] ='';
    if($string_address !== '' && $string_address !== null){
        $curl = curl_init();
        $address = str_replace ( " " , "+" , $string_address );
        $url = "https://www.energyaustralia.com.au/qt2/app/quoteservice/qas/find?address=".$address."&postcode=";
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);   
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: www.energyaustralia.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Upgrade-Insecure-Requests: 1",
                "Cache-Control: max-age=0",
            )
        );

        $result = curl_exec($curl);
        $Full_address = explode(',',$result[1]->name);
        $address0 = $Full_address[0];
        $address1 = $Full_address[1];
        $return_data['street'] = $address0;
        $address2 = explode(' ',$address1);
        $return_data['city'] = $address2[0];
        $return_data['state'] = $address2[1];
        $return_data['postcode'] = $address2[2];
        if($result[1]->name == ''){
            $return_data['street'] = $string_address;
        }
    }
    return $return_data;
}