<?php
require_once('include/SugarPHPMailer.php');
$path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
$dirName        = $_POST['pre_install_photos_c'];
$folderName     = $path . $dirName . '/';
$thumbnail      = $path . $dirName . '/thumbnail' . '/';
$shortcuts ="";
$result = array(
    'AOS_Quotes' => [],
    // 'AOS_Invoices' => [] ,
    'Leads' => "",
    'Accounts' => "",
    'Contacts' => "",
    'PO_purchase_order' => [],
);
$number_module = "";
$email_assigned = "";
$quote_id = $_POST['quote_id'];
$invoice_id = $_POST['invoice_id'];
$lead_id = $_POST['lead_id'];
if( $lead_id != ""){
    $lead = new Lead();
    $lead->retrieve($lead_id);
    $number_module = $lead->number;
    $user = new User();
    $user->retrieve($lead->assigned_user_id);
    $email_assigned = $user->email1;
}else if($quote_id != ""){
    $parent_id = $quote_id;
    $parent_type = "AOS_Quotes";
    $quote = new AOS_Quotes();
    $quote->retrieve($quote_id);
    $number_module = $quote->number;
    $result = render_json_quote($result,$quote_id);
    $user = new User();
    $user->retrieve($quote->assigned_user_id);
    $email_assigned = $user->email1;
    foreach($result as $key => $value){
        if( !empty($value) ){
            $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value."'>".$key."</a> | ";
        }
    }
}else if($invoice_id != ""){
    $parent_id = $invoice_id;
    $parent_type = "AOS_Invoices";
    $invoice = new AOS_Invoices();
    $invoice->retrieve($_POST['invoice_id']);
    $number_module = $invoice->number;
    $result = render_json_invoice($result,$invoice_id);
    $user = new User();
    $user->retrieve($invoice->assigned_user_id);
    $email_assigned = $user->email1;
    foreach($result as $key => $value){
        if( !empty($value) ){
            if( $key == "AOS_Quotes"){
                $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value['id']."'>Quote #".$value['number']."</a> | ";
            }else if( $key == "PO_purchase_order"){
                if( !empty($value['plumber']) ){
                    $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value['plumber']['id']."'>PO#".$value['plumber']['number']."</a> | ";
                }
                if( !empty($value['electrical']) ){
                    $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value['electrical']['id']."'>PO#".$value['electrical']['number']."</a> | ";
                }
                if( !empty($value['daikin']) ){
                    $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value['daikin']['id']."'>PO#".$value['daikin']['number']."</a> | ";
                }
            }else {
                $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value."'>".$key."</a> | ";
            }
        }
    }
}
    $list_photos = "<br><h4>List Photos:</h4>";
    $file_to_attach = array();
    if (!file_exists($folderName)) {
        mkdir($path . $dirName, 0777, true);
        $folderName = $path . $dirName.'/';
    }

    if(count($_POST['files']['data-pe-files-hws']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hws']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hws']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_');
                $file_type = $number_module.'_Existing_HWS_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-hws']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing HWS '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hws']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-hws-centre']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hws-centre']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hws-centre']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_Centre_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-centre']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_Centre');
                $file_type = $number_module.'_Existing_HWS_Centre_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-centre']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-hws-centre']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing HWS (centre) '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hws-centre']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-hws-right']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hws-right']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hws-right']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_right_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-right']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_right');
                $file_type = $number_module.'_Existing_HWS_right_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-right']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-hws-right']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing HWS (right)'.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hws-right']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-hws-left']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hws-left']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hws-left']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_left_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-left']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_left');
                $file_type = $number_module.'_Existing_HWS_left_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-left']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-hws-left']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing HWS (left)'.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hws-left']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-hws-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hws-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hws-serial']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_serial_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-serial']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_serial');
                $file_type = $number_module.'_Existing_HWS_serial_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hws-serial']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-hws-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing HWS (serial)'.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hws-serial']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-switchboard']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-switchboard']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Switchboard_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_');
                $file_type = $number_module.'_Switchboard_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-switchboard']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Switchboard '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-switchboard']['tmp_name'][$i], 'fileName' => $file_type);
            };
        };
    };
    if(count($_POST['files']['data-pe-files-newsanden']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-newsanden']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-newsanden']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'new_sanden_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-newsanden']['name'][$i]), PATHINFO_EXTENSION );
                $count = checkCountExistPhoto($file_type,$folderName,'new_sanden_');
                $file_type = $number_module.'new_sanden_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-newsanden']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-newsanden']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">New Sanden '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-newsanden']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-existing-HWS-serial-number']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-existing-HWS-serial-number']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-existing-HWS-serial-number']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Existing_HWS_serial_number'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-existing-HWS-serial-number']['name'][$i]), PATHINFO_EXTENSION );
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_serial_number');
                $file_type = $number_module.'_Existing_HWS_serial_number'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-existing-HWS-serial-number']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-existing-HWS-serial-number']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">New Sanden '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-existing-HWS-serial-number']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-access']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-access']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-access']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Photo_access_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-access']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Photo_access_');
                $file_type = $number_module.'_Photo_access_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-access']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-access']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Access '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-access']['tmp_name'][$i], 'fileName' => $file_type);
            };
        };
    }
    if(count($_POST['files']['data-pe-files-upclose']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-upclose']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-upclose']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Photo_upclose_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Photo_upclose_');
                $file_type = $number_module.'_Photo_upclose_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-upclose']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Upclose '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-upclose']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-meterbox']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-meterbox']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-meterbox']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Photo_meterbox_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Photo_meterbox_');
                $file_type = $number_module.'_Photo_meterbox_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-meterbox']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Meterbox '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-meterbox']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-electricity-bill']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = $number_module.'_Electricity_bill_'.$i.'.'.pathinfo(basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION);
                $count = checkCountExistPhoto($file_type,$folderName,'_Electricity_bill_');
                $file_type = $number_module.'_Electricity_bill_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-electricity-bill']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Electricity bill '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-electricity-bill']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    // plumber upload
    $checkgeo = "";
    if(count($_POST['files']['data-pe-files-water-pressure-property']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-water-pressure-property']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-water-pressure-property']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_New_Install_Water_Pressure_Property'.$i.'.'.pathinfo($_POST['files']['data-pe-files-water-pressure-property']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'New_Install_Water_Pressure_Property');
                $file_type = $number_module.'_New_Install_Water_Pressure_Property'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-water-pressure-property']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Measure Water Pressure Into Property '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-water-pressure-property']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-existing-hws']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-existing-hws']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-existing-hws']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-existing-hws']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Existing_Hws'.$i.'.'.pathinfo($_POST['files']['data-pe-files-existing-hws']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_Hws');
                $file_type = $number_module.'_Existing_Hws'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-existing-hws']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-existing-hws']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Old Existing HWS '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-existing-hws']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-existing-hws-brand-model']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-existing-hws-brand-model']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-existing-hws-brand-model']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-existing-hws-brand-model']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Existing_HWS_Brand_Model'.$i.'.'.pathinfo($_POST['files']['data-pe-files-existing-hws-brand-model']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Existing_HWS_Brand_Model');
                $file_type = $number_module.'_Existing_HWS_Brand_Model'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-existing-hws-brand-model']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-existing-hws-brand-model']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Old Existing HWS Brand/Model/Serial Number '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-existing-hws-brand-model']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-decommission-hws']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-decommission-hws']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-decommission-hws']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-decommission-hws']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Decommission_HWS'.$i.'.'.pathinfo($_POST['files']['data-pe-files-decommission-hws']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Decommission_HWS');
                $file_type = $number_module.'_Decommission_HWS'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-decommission-hws']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-decommission-hws']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Drill Hole OR Remove Element of Elec Storage HWS '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-decommission-hws']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-tank-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-tank-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-tank-serial']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-tank-serial']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Tank_Serial_Number'.$i.'.'.pathinfo($_POST['files']['data-pe-files-tank-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Tank_Serial_Number');
                $file_type = $number_module.'_Tank_Serial_Number'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-tank-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-tank-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Sanden Tank Serial Number '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-tank-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-hp-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hp-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hp-serial']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-hp-serial']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_HP_Serial_Number'.$i.'.'.pathinfo($_POST['files']['data-pe-files-hp-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_HP_Serial_Number');
                $file_type = $number_module.'_HP_Serial_Number'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-hp-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-hp-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Sanden HP Serial Number '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hp-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-water-pressure-nriprv']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-water-pressure-nriprv']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-water-pressure-nriprv']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-water-pressure-nriprv']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Measure_Water_Pressure_NRIPRV'.$i.'.'.pathinfo($_POST['files']['data-pe-files-water-pressure-nriprv']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'Measure_Water_Pressure_NRIPRV');
                $file_type = $number_module.'_Measure_Water_Pressure_NRIPRV'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-water-pressure-nriprv']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-water-pressure-nriprv']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Measure Water Pressure NRIPRV '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-water-pressure-nriprv']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-install-photo']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-install-photo']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-install-photo']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-install-photo']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_New_Install_Photo'.$i.'.'.pathinfo($_POST['files']['data-pe-files-install-photo']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_New_Install_Photo');
                $file_type = $number_module.'_New_Install_Photo'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-install-photo']['name'][$i]), PATHINFO_EXTENSION );
                copy($_POST['files']['data-pe-files-install-photo']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">New Install Photos '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-install-photo']['tmp_name'][$i], 'fileName' => $file_type);
            }    
        };
    }
    if(count($_POST['files']['data-pe-files-plumbing-pcoc']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-plumbing-pcoc']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-plumbing-pcoc']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-plumbing-pcoc']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'PCOC'.$i.'.'.pathinfo($_POST['files']['data-pe-files-plumbing-pcoc']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'PCOC');
                $file_type = $number_module.'PCOC'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-plumbing-pcoc']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-plumbing-pcoc']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Attach Plumbing Certificate of Compliance (PCOC) '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-plumbing-pcoc']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    if(count($_POST['files']['data-pe-files-upload-invoice']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-upload-invoice']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-upload-invoice']['name'][$i] != ""){
                if( strpos($_POST['files']['data-pe-files-upload-invoice']['name'][$i], '.pdf') == true  ){
                    $new_name = '_invoice_PDF';
                }else {
                    $new_name = '_Invoice(Plumber)';
                }
                $file_type = basename($number_module.$new_name.$i.'.'.pathinfo($_POST['files']['data-pe-files-upload-invoice']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,$new_name);
                $file_type = $number_module.$new_name.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-upload-invoice']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-upload-invoice']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Invoice(Plumber) '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-upload-invoice']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    // Electrician
    if(count($_POST['files']['data-pe-files-switchboard-sanden']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard-sanden']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-switchboard-sanden']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-switchboard-sanden']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Switchboard_Sanden_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-switchboard-sanden']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_Sanden_');
                $file_type = $number_module.'_Switchboard_Sanden_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard-sanden']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-switchboard-sanden']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Switchboard Sanden '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-switchboard-sanden']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-electrical-ces']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-electrical-ces']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-electrical-ces']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-electrical-ces']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'CES'.$i.'.'.pathinfo($_POST['files']['data-pe-files-electrical-ces']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'CES');
                $file_type = $number_module.'CES'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electrical-ces']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-electrical-ces']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Attach Electrical Certificate of Safety (CES) '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-electrical-ces']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    /**Tank Serial */
    if(count($_POST['files']['data-pe-files-electrical-tank-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-electrical-tank-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-electrical-tank-serial']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-electrical-tank-serial']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_Tank_Serial_CES'.$i.'.'.pathinfo($_POST['files']['data-pe-files-electrical-tank-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'CES');
                $file_type = $number_module.'_Tank_Serial_CES'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electrical-tank-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-electrical-tank-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Tank Serial (CES) '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-electrical-tank-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    /**HP Serial */
    if(count($_POST['files']['data-pe-files-electrical-hp-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-electrical-hp-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-electrical-hp-serial']['name'][$i] != ""){
                $info = exif_read_data($_POST['files']['data-pe-files-electrical-hp-serial']['tmp_name'][$i]);
                if( isset($info['GPS_IFD_Pointer']) && $info['GPS_IFD_Pointer'] > 0 ){
                    $checkgeo = "( GEOTAGGED )";
                }else {
                    $checkgeo = "( without GEOTAGGED )";
                }
                $file_type = basename($number_module.'_HP_Serial_CES'.$i.'.'.pathinfo($_POST['files']['data-pe-files-electrical-hp-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'CES');
                $file_type = $number_module.'_HP_Serial_CES'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electrical-hp-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-electrical-hp-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">HP Serial (CES) '.$i.' '.$checkgeo.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-electrical-hp-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }
        };
    }
    
    // Daikin suppler
    if(count($_POST['files']['data-pe-files-switchboard-daikin']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard-daikin']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-switchboard-daikin']['name'][$i] != ""){
                $file_type = basename($number_module.'_Switchboard_DAIKIN_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-switchboard-daikin']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_DAIKIN_');
                $file_type = $number_module.'_Switchboard_DAIKIN_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard-daikin']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-switchboard-daikin']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Switchboard DAIKIN '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-switchboard-daikin']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-indoor-unit']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-indoor-unit']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-indoor-unit']['name'][$i] != ""){
                $file_type = basename($number_module.'_Indoor_Unit_Proposed_Location_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-indoor-unit']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Indoor_Unit_Proposed_Location');
                $file_type = $number_module.'_Indoor_Unit_Proposed_Location_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-indoor-unit']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-indoor-unit']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Indoor Unit Proposed Location DAIKIN '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-indoor-unit']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-outdoor-unit']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-outdoor-unit']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-outdoor-unit']['name'][$i] != ""){
                $file_type = basename($number_module.'_Outdoor_Unit_Proposed_Location_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-outdoor-unit']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Outdoor_Unit_Proposed_Location');
                $file_type = $number_module.'_Outdoor_Unit_Proposed_Location_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-outdoor-unit']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-outdoor-unit']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Outdoor Unit Proposed Location DAIKIN  '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-outdoor-unit']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['ddata-pe-files-outdoor-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-outdoor-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-outdoor-serial']['name'][$i] != ""){
                $file_type = basename($number_module.'_Outdoor_Serial_DAIKIN_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-outdoor-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Outdoor_Serial_DAIKIN_');
                $file_type = $number_module.'_Outdoor_Serial_DAIKIN_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-outdoor-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-outdoor-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Outdoor Serial DAIKIN '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-outdoor-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-indoor-serial']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-indoor-serial']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-indoor-serial']['name'][$i] != ""){
                $file_type = basename($number_module.'_Indoor_Serial_DAIKIN_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-indoor-serial']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Indoor_Serial_DAIKIN_');
                $file_type = $number_module.'_Indoor_Serial_DAIKIN_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-indoor-serial']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-indoor-serial']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Indoor Serial DAIKIN '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-indoor-serial']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    // for client
    if(count($_POST['files']['data-pe-files-indoor-unit-client']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-indoor-unit-client']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-indoor-unit-client']['name'][$i] != ""){
                $file_type = basename($number_module.'_Indoor_Unit_Proposed_Location_Client_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-indoor-unit-client']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Indoor_Unit_Proposed_Location_Client');
                $file_type = $number_module.'_Indoor_Unit_Proposed_Location_Client_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-indoor-unit-client']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-indoor-unit-client']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">(Client)Indoor Unit Proposed Location DAIKIN '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-indoor-unit-client']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-outdoor-unit-client']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-outdoor-unit-client']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-outdoor-unit-client']['name'][$i] != ""){
                $file_type = basename($number_module.'_Outdoor_Unit_Proposed_Location_Client_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-outdoor-unit-client']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Outdoor_Unit_Proposed_Location_Client');
                $file_type = $number_module.'_Outdoor_Unit_Proposed_Location_Client_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-outdoor-unit-client']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-outdoor-unit-client']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">(Client)Outdoor Unit Proposed Location DAIKIN  '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-outdoor-unit-client']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-switchboard-daikin-client']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard-daikin-client']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-switchboard-daikin-client']['name'][$i] != ""){
                $file_type = basename($number_module.'_Switchboard_Client_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-switchboard-daikin-client']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_Client');
                $file_type = $number_module.'_Switchboard_Client_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard-daikin-client']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-switchboard-daikin-client']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">(Client)Switchboard '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-switchboard-daikin-client']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    if(count($_POST['files']['data-pe-files-floorplan']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-floorplan']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-floorplan']['name'][$i] != ""){
                $file_type = basename($number_module.'_Floorplan_'.$i.'.'.pathinfo($_POST['files']['data-pe-files-floorplan']['name'][$i], PATHINFO_EXTENSION));
                $count = checkCountExistPhoto($file_type,$folderName,'_Floorplan');
                $file_type = $number_module.'_Floorplan_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-floorplan']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-floorplan']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">(Client)Floorplan '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-floorplan']['tmp_name'][$i], 'fileName' => $file_type);
            }        
        };
    }
    // REMITTANCE ADVICE
    if(count($_POST['files']['data-client-files-remittance-advice']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-client-files-remittance-advice']['tmp_name']); $i++) {
            if($_POST['files']['data-client-files-remittance-advice']['name'][$i] != ""){
                $file_type = $number_module.'_Remittance_Advice.'.pathinfo( basename($_POST['files']['data-client-files-remittance-advice']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-client-files-remittance-advice']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Remittance Advice '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-client-files-remittance-advice']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    // Delivery Photos
    if(count($_POST['files']['data-pe-files-delivery-has-arrived']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-delivery-has-arrived']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-delivery-has-arrived']['name'][$i] != ""){
                $file_type =  $number_module.'Delivery_Has_Arrived'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-delivery-has-arrived']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-delivery-has-arrived']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Delivery Has Arrived '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-delivery-has-arrived']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    // CLient warranty
    if(count($_POST['files']['data-pe-files-tank-serial-warranty']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-tank-serial-warranty']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-tank-serial-warranty']['name'][$i] != ""){
                $file_type =  $number_module.'_Tank_Serial_Client_Warranty'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-tank-serial-warranty']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-tank-serial-warranty']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Sanden Tank Serial Number (Client warranty) '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-tank-serial-warranty']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };
    if(count($_POST['files']['data-pe-files-hp-serial-warranty']['tmp_name']) > 0) {
        for($i = 0; $i < count($_POST['files']['data-pe-files-hp-serial-warranty']['tmp_name']); $i++) {
            if($_POST['files']['data-pe-files-hp-serial-warranty']['name'][$i] != ""){
                $file_type =  $number_module.'_HP_Serial_Client_Warranty'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-hp-serial-warranty']['name'][$i]), PATHINFO_EXTENSION);
                copy($_POST['files']['data-pe-files-hp-serial-warranty']['tmp_name'][$i], $folderName.$file_type);
                $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Sanden HP Serial Number (Client warranty) '.$i.'</a>';
                addToNotes($file_type,$folderName,$parent_id,$parent_type);
                $file_to_attach[] = array('folderName' => $_POST['files']['data-pe-files-hp-serial-warranty']['tmp_name'][$i], 'fileName' => $file_type);
            };
        }
    };

// clone file from Quote To Leads
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $url = 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=APICloneFile&method=clone_file_Quote_to_Lead&leadID='
    .$quote->leads_aos_quotes_1leads_ida .'&quoteID='.$quote->id ;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $resultCURL = curl_exec($curl); 
    curl_close($curl);

if($_POST['to_module'] == "aos_invoice"){
    $worker_type = $_POST['worker_type'];
    if($invoice->id == '') {
        echo json_encode(array('msg'=>'error'));
        die();
    };
    if($worker_type == "Plumber" ){
        $installer_id = $invoice->contact_id4_c;
        $installer=  new Contact();
        $installer->retrieve($installer_id);
    }else if($worker_type == "Electrician" ){
        $installer_id = $invoice->contact_id_c;
        $installer=  new Contact();
        $installer->retrieve($installer_id);
    }else if($worker_type == "Daikin installer" ){
        $installer_id = $invoice->account_id2_c;
        $installer=  new Account();
        $installer->retrieve($installer_id);
    }
    $send_install_link ='';
    if ($worker_type == "Plumber") {
        $send_install_link = "<p>Send Installer link: <a href='https://suitecrm.pure-electric.com.au/index.php?entryPoint=APISendPhotoInstallToInstaller&invoice_id=".$invoice->id."&billing_account_id=".$invoice->billing_account_id."&installer_id=".$installer_id."&installer_name=Plumber&generateUUID=".$invoice->installation_pictures_c."' target='_blank'>Mail to ".$installer->name."</a></p>";
    } else if ($worker_type == "Electrician") {
        $send_install_link = "<p>Send Installer link: <a href='https://suitecrm.pure-electric.com.au/index.php?entryPoint=APISendPhotoInstallToInstaller&invoice_id=".$invoice->id."&billing_account_id=".$invoice->billing_account_id."&installer_id=".$installer_id."&installer_name=Electrician&generateUUID=".$invoice->installation_pictures_c."' target='_blank'>Mail to ".$installer->name."</a></p>";
    }
    $client = new Account();
    $client->retrieve($invoice->billing_account_id);

    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = 'info@pure-electric.com.au';  
    $mail->FromName = 'Pure Electric';  

    if($worker_type == "Delivery Client" ){
        $mail->Subject = "Client ".$client->name." -  Uploaded (delivery has arrived) photo to Invoice#".$invoice->number."";
        $mail->Body = $shortcuts;
        $mail->Body .= "<p>Link Invoice: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoice->id."' target='_blank'>".$invoice->name."</a></p>";
        $mail->Body .= "<p>Email Client: ".$client->email1." <a href='https://mail.google.com/#search/".$client->email1."'>GSearch</a></p>";
    }else {
        if ( $worker_type == "Customer"){
            $mail->Subject = $worker_type." ".$client->name." Upload Install Photos - Sanden international (Australia) Pty Customer Warranty registration - Invoice#".$invoice->number." ".$invoice->name;
        }else {
            $mail->Subject = $worker_type." ".$installer->name." uploaded photo to Invoice#".$invoice->number." ".$invoice->name;
        }
        $mail->Body = $shortcuts;
        $mail->Body .= "<p>Link Invoice: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoice->id."' target='_blank'>".$invoice->name."</a></p>";
        if ( $worker_type != "Customer" ){
            $mail->Body .= "<p>Email ".$worker_type.": ".$installer->email1." <a href='https://mail.google.com/#search/".$installer->email1."'>GSearch</a></p>";
            /// Tri Todo
            if ( $worker_type == "Plumber" || $worker_type == "Electrician" ){
                $mail->Body .= "<p>Phone ".$worker_type.": <a href='tel:".$installer->phone_mobile."'>".$installer->phone_mobile." </a></p>";
            } else if ($worker_type == "Daikin installer"){
                $mail->Body .= "<p>Phone ".$worker_type.": <a href='tel:".$installer->mobile_phone_c."'>".$installer->mobile_phone_c." </a></p>";
            }
            
        }
        $mail->Body .= "<p>Email Client: ".$client->email1." <a href='https://mail.google.com/#search/".$client->email1."'>GSearch</a></p>";
        $mail->Body .= $send_install_link;    
    }
    $mail->Body .= $list_photos;
    // $mail->Body = "<a href='http://new.suitecrm-pure.com/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote->id."' target='_blank'>Link Quote: ".$quote->name."</a>";

}else if($_POST['to_module'] == "purchase_order"){

    $purchase = new PO_purchase_order();
    $purchase->retrieve($_POST['purchase_id']);

    $installer=  new Account();
    $installer->retrieve($purchase->billing_account_id);

    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = 'info@pure-electric.com.au';  
    $mail->FromName = 'Pure Electric';  
    $mail->Subject = $purchase->billing_account." - Uploaded (delivery has arrived) photo to Purhchase Order";
    $mail->Body = $shortcuts;
    $mail->Body .= "<p>Link Purchase Order: <a href=https://suitecrm.pure-electric.com.au/index.php?module=PO_purchase_order&action=EditView&record=".$purchase->id."' target='_blank'>".$purchase->name."</a></p>";
    $mail->Body .= "<p>Email Supplier: ".$installer->email1." <a href='https://mail.google.com/#search/".$installer->email1."'>GSearch</a></p>";
    $mail->Body .= $list_photos;
    
}else{
    if($quote->id != '') {
        // echo json_encode(array('msg'=>'error'));
        // die();
        if( isset($_POST['type_api']) && $_POST['type_api'] == "sanden_type"){
            $quote->old_tank_serial_c = $_POST['old_tank_serial'];
            $quote->old_tank_model_c = $_POST['old_tank_model'];
            $quote->old_tank_make_c = $_POST['old_tank_make'];
            $quote->old_tank_date_c = $_POST['old_tank_date'];
            $quote->save();
        }
        $account = new Contact();
        $account->retrieve($quote->billing_contact_id);

        $mail = new SugarPHPMailer();  
        $mail->setMailerForSystem();  
        $mail->From = 'info@pure-electric.com.au';   
        $mail->FromName = 'Pure Electric';  
        $mail->Subject = $quote->account_firstname_c." ".$quote->account_lastname_c." uploaded file to Quote#".$quote->number." ".$quote->name;
        $mail->Body = $shortcuts;
        $mail->Body .= "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote->id."' target='_blank'>".$quote->name."</a></p>";
        $mail->Body .= "<p>Email: ".$account->email1." <a href='https://mail.google.com/#search/".$account->email1."'>GSearch</a></p>";
        $mail->Body .= "<p>Phone number: <a href='#'>".$account->phone_mobile."</a></p>";
        if($quote->quote_type_c == 'quote_type_daikin') {
            $mail->Body .= "<p><a href='http://daikintool.pure-electric.com.au/index.php?quote_id=".$quote->id."'>Daikin Design Tool</a></p>";
        } else if($quote->quote_type_c == 'quote_type_sanden') {
            $mail->Body .= "<p><a href='http://sandentool.pure-electric.com.au/index.php?quote_id=".$quote->id."'>Sanden Design Tool</a></p>";
        } else if($quote->quote_type_c == 'quote_type_solar') {
            $mail->Body .= "<p><a href='https://solardesigndev.pure-electric.com.au/".$quote->id."'>Solar Design Tool</a></p>";
        }
        
        $mail->Body .= "<p><a href='https://suitecrm.pure-electric.com.au/index.php?entryPoint=converToInvoice&record=".$quote->id."' target='_blank'>Convert Invoice</a></p>";
        $mail->Body .= "<p>PE Sales Consultant: <strong>".$user->name."</strong></p>";
        $mail->Body .= $list_photos;
        email_notification_for_client($quote->account_firstname_c,$quote->account_lastname_c,$account->email1,$list_photos);

    }else if( $lead_id != ""){
        $lead = new Lead();
        $lead->retrieve($lead_id);
        $list_optional;
        if( $_POST['type_product'] == "Solar" ){
            $lead->name_on_billing_account_c = $_POST['name_as_it_appears_on_bill']? $_POST['name_as_it_appears_on_bill']: $lead->name_on_billing_account_c;
            $lead->meter_number_c = $_POST['meter_number']? $_POST['meter_number']:$lead->meter_number_c;
            $lead->account_number_c = $_POST['account_number']? $_POST['account_number']:$lead->account_number_c;
            $lead->nmi_c = $_POST['NMI_number']? $_POST['NMI_number']:$lead->nmi_c;
            $lead->roof_type_c = $_POST['main_roof_type']? $_POST['main_roof_type']:$lead->roof_type_c;
            $lead->secondary_roof_type_c = $_POST['secondary_roof_type']? $_POST['secondary_roof_type']:$lead->secondary_roof_type_c;
            $lead->primary_wall_type_c = $_POST['primary_wall_type']? $_POST['primary_wall_type']:$lead->primary_wall_type_c;
            $lead->secondary_wall_type_c = $_POST['secondary_wall_type']? $_POST['secondary_wall_type']:$lead->secondary_wall_type_c;
            $lead->switchboard_location_c = $_POST['switchboard_location']? $_POST['switchboard_location']:$lead->switchboard_location_c;
            $lead->meter_location_c = $_POST['meter_location']? $_POST['meter_location']:$lead->meter_location_c;
            $lead->save();
            $list_optional .= '<div style="line-height: 5px;">';
            $list_optional .= '<p>Name as it appears on bill: '.$_POST['name_as_it_appears_on_bill'] .'</p>';
            $list_optional .= '<p>Account Number: '. strtoupper(str_replace("_"," ",$_POST['account_number'])).'</p>';
            $list_optional .= '<p>NMI Number: '.strtoupper(str_replace("_"," ",$_POST['NMI_number'])).'</p>';
            $list_optional .= '<p>Meter Number: '.strtoupper(str_replace("_"," ",$_POST['meter_number'])).'</p>';
            $list_optional .= '<p>Main Roof Type: '.strtoupper(str_replace("_"," ",$_POST['main_roof_type'])).'</p>';
            $list_optional .= '<p>Secondary Roof Type: '.strtoupper(str_replace("_"," ",$_POST['secondary_roof_type'])).'</p>';
            $list_optional .= '<p>Primary Wall Type: '.strtoupper(str_replace("_"," ",$_POST['primary_wall_type'])).'</p>';
            $list_optional .= '<p>Secondary Wall Type: '.strtoupper(str_replace("_"," ",$_POST['secondary_wall_type'])).'</p>';
            $list_optional .= '<p>Switchboard location: '.strtoupper(str_replace("_"," ",$_POST['switchboard_location'])).'</p>';
            $list_optional .= '<p>Meter location: '.strtoupper(str_replace("_"," ",$_POST['meter_location'])).'</p>';
            $list_optional .= '</div>';
            if( $lead->create_solar_quote_num_c != ""){
                $quote_slgain = new AOS_Quotes();
                $quote_slgain->retrieve($lead->create_solar_quote_num_c);
                $quote_slgain->name_on_billing_account_c =($_POST['name_as_it_appears_on_bill'])? ($_POST['name_as_it_appears_on_bill']) : $quote_slgain->name_on_billing_account_c;
                $quote_slgain->meter_number_c = ($_POST['meter_number'])? ($_POST['meter_number']) : $quote_slgain->meter_number_c;
                $quote_slgain->account_number_c = ($_POST['account_number'])? ($_POST['account_number']) : $quote_slgain->account_number_c;
                $quote_slgain->nmi_c = ($_POST['NMI_number'])? ($_POST['NMI_number']) : $quote_slgain->nmi_c;
                $quote_slgain->roof_type_c = ($_POST['main_roof_type'])? ($_POST['main_roof_type']) : $quote_slgain->roof_type_c;
                $quote_slgain->secondary_roof_type_c = $_POST['secondary_roof_type']? $_POST['secondary_roof_type']:$quote_slgain->secondary_roof_type_c;
                $quote_slgain->primary_wall_type_c = $_POST['primary_wall_type']? $_POST['primary_wall_type']:$quote_slgain->primary_wall_type_c;
                $quote_slgain->secondary_wall_type_c = $_POST['secondary_wall_type']? $_POST['secondary_wall_type']:$quote_slgain->secondary_wall_type_c;
                $quote_slgain->switchboard_location_c = $_POST['switchboard_location']? $_POST['switchboard_location']:$lequote_slgainad->switchboard_location_c;
                $quote_slgain->meter_location_c = $_POST['meter_location']? $_POST['meter_location']:$quote_slgain->meter_location_c;
                $quote_slgain->save();
            }else {
                $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
                $curl = curl_init();
                $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=CustomButtonConvertLead&record_id=".$lead_id."&type_convert=convert_solar_button&product_type=quote_type_solar&from_upload_solar=solar";
    
                curl_setopt($curl, CURLOPT_URL, $source);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
                curl_setopt($curl, CURLOPT_HEADER, true);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                                                                         
                curl_setopt($curl, CURLOPT_HEADERFUNCTION, "readHeader");
                $result = curl_exec($curl);
                curl_close($curl);
            }
        }
            
        //thienpb update
        $lead = new Lead();//refresh field
        $lead->retrieve($lead_id);
        $quote_slgain = new AOS_Quotes();
        $quote_slgain->retrieve($lead->create_solar_quote_num_c);

        $fields = ['create_daikin_quote_num_c' ,'daikin_nexura_quote_num_c' ,'create_solar_quote_fqv_num_c' , 'create_methven_quote_num_c' , 'create_solar_quote_fqs_num_c' , 'create_off_grid_button_num_c', 'create_solar_quote_num_c'];
        foreach($fields as $fieldName){
            if($lead->$fieldName != ''){
                $quoteNew =  new AOS_Quotes();
                $quoteNew->retrieve($lead->$fieldName);
                if(!empty($quoteNew->id)){
                    convert_file_and_photo_to_quote($lead,$quoteNew);

                    //copy to invoice
                    $quoteNew->load_relationships('AOS_Invoices');                    
                    $invoices = $quoteNew->get_linked_beans('aos_quotes_aos_invoices','AOS_Invoices');
                    if(count($invoices) > 0){
                        foreach($invoices as $invoiceNew){
                            convert_file_and_photo_to_quote($quoteNew,$invoiceNew);
                        }
                    }
                    
                }
            }
        }     
        $lead->status = "Info_Uploaded";
        $lead->uploaded_photos_c = "yes";
        $lead->save();   
        $mail = new SugarPHPMailer();  
        $mail->setMailerForSystem();  
        $mail->From = 'info@pure-electric.com.au';  
        $mail->FromName = 'Pure Electric';  
        $mail->Subject = $_POST['type_product'] .' - '.$lead->first_name." ".$lead->last_name." uploaded file to lead#".$lead->number." ".$lead->account_name;
        $mail->Body = $shortcuts;
        $mail->Body .= "<p>Link Lead: <a href='https://suitecrm.pure-electric.com.au/index.php?module=Leads&action=EditView&record=".$lead->id."' target='_blank'>".$lead->account_name."</a></p>";
        $mail->Body .= "<p>Email: ".$lead->email1." <a href='https://mail.google.com/#search/".$lead->email1."'>GSearch</a></p>";
        $mail->Body .= "<p>Phone number: <a href='#'>".$lead->phone_mobile."</a></p></p>";
        //info to design
        $address_array = [$lead->primary_address_street, $lead->primary_address_city.' '.$lead->primary_address_state, $lead->primary_address_postalcode, 'Australia'];
        $address = join(', ',$address_array);
        $link_sg_design = 'https://solardesign.pure-electric.com.au/#/projects/create?addressSearch='.$address.'&first_name='.$lead->first_name.'&family_name='.$lead->last_name.'&email='.$lead->email1.'&phone='.$lead->phone_mobile;
        if( $_POST['type_product'] == "Solar" ){
            $mail->Body .= "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote_slgain->id."' target='_blank'>".$quote_slgain->name."</a></p>";
            $mail->Body .= "<p>Link Solargain Lead: <a href='https://crm.solargain.com.au/lead/edit/".$quote_slgain->solargain_lead_number_c."' target='_blank'>Solargain Lead Number ".$quote_slgain->solargain_lead_number_c."</a></p>";
            $mail->Body .= "<p>Link Solargain Quote: <a href='https://crm.solargain.com.au/quote/edit/".$quote_slgain->solargain_quote_number_c."' target='_blank'>Solargain Quote Number ".$quote_slgain->solargain_quote_number_c."</a></p>";
            $mail->Body .= "<p>Link PE Design: <a href='".$link_sg_design."' target='_blank'>PE Design Tool</a></p>";
            $mail->Body .= "<br><h4>Optional details (to speed up the quoting process)</h4>";
            $mail->Body .= $list_optional;
            $mail->AddCC('quochuybkdn@gmail.com');
        }   
        $mail->Body .= $list_photos;
        email_notification_for_client($lead->first_name,$lead->last_name,$lead->email1,$list_photos);
    }

}
    $mail->IsHTML(true);
    foreach($file_to_attach as $file_attach) {
        $mail->AddAttachment($file_attach['folderName'], $file_attach['fileName'], 'base64', 'application/octet-stream');
    }
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->AddCC($email_assigned);
    // $mail->AddCC('matthew.wright@pure-electric.com.au');
    // $mail->AddCC('john.hooper@pure-electric.com.au');
    // $mail->AddCC('quochuybkdn@gmail.com');
    // $mail->AddAddress('ngoanhtuan2510@gmail.com');
    $mail->prepForOutbound();
    $mail->setMailerForSystem();  
    $mail->Send();
    die;
function email_notification_for_client($firtname,$lastname,$email_address,$list_photos){
    require_once('include/SugarPHPMailer.php');

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'info@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $_POST['type_product'] .' - '.$firtname." ".$lastname." uploaded file to Pure Electric";
    $email->Body = "<p>Hi ".$firtname." ".$lastname.",</p>";
    $email->Body .= "<p>Thank you for uploading photos for your Pure Electric ".$_POST['type_product']." Quote. We will review the photos to discuss/confirm your quote and be in touch shortly.<p>";
    $email->Body .= $list_photos;
    $email->IsHTML(true);
    $email->AddAddress($email_address);
    $email->prepForOutbound();
    $email->setMailerForSystem();  
    $email->Send();
    return ;
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

    // $url_img = $folderName.$file;
    // $percent = 0.85;
    //Get path image


    // Get new sizes
    // list($width, $height) = getimagesize($url_img);
    // $newwidth = round($width * $percent);

    // if($newwidth >= 800){
    //     resize_upload($newwidth,$url_img,$url_img);
    //     $size_image_new =filesize ($url_img);
    //     echo FileSizeConvert_Upload($size_image_new);
    // } else {
    //     echo 'ERROR';
    // }
        // rotateImage($url_img);
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

function resize_image($file, $current_file_path) {
    if(!file_exists ($current_file_path."thumbnail/")) {
        mkdir($current_file_path."thumbnail/");
    }
    try{
        $imagick = new Imagick($current_file_path.$file);
        switch ($imagick->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPLEFT:
                break;
            case Imagick::ORIENTATION_TOPRIGHT:
                $imagick->flopImage();
                break;
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $imagick->rotateImage("#000", 180);
                break;
            case Imagick::ORIENTATION_BOTTOMLEFT:
                $imagick->flopImage();
                $imagick->rotateImage("#000", 180);
                break;
            case Imagick::ORIENTATION_LEFTTOP:
                $imagick->flopImage();
                $imagick->rotateImage("#000", -90);
                break;
            case Imagick::ORIENTATION_RIGHTTOP:
                $imagick->rotateImage("#000", 90);
                break;
            case Imagick::ORIENTATION_RIGHTBOTTOM:
                $imagick->flopImage();
                $imagick->rotateImage("#000", 90);
                break;
            case Imagick::ORIENTATION_LEFTBOTTOM:
                $imagick->rotateImage("#000", -90);
                break;
            default: // Invalid orientation
                break;
        }
        $imagick->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
        $imagick->thumbnailImage(80, 80, true, true);
        $imagick->writeImage( $current_file_path.'thumbnail/'.$file);
        $imagick->destroy();
    }catch(Exception $e){
            
    }
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

function resize_upload($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];
    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // if (file_exists($targetFile)) {
    //         unlink($targetFile);
    // }
    // $image_save_func($tmp, "$targetFile");
}
// convert byte to MB,KB,B,TB,GB
function FileSizeConvert_Upload($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}
function rotateImage($url_img) {

    //Get path image and thumbnail
    $parse_url_img = parse_url($url_img);

    $array_url_img = explode('/',$parse_url_img['path']);
    $array_url_img  = array_slice($array_url_img,6);
    $array_url_img_thub = $array_url_img;


    //get url image and thumbnail && type image 
    $url_img = implode('/',$array_url_img);

    $count_ele = count($array_url_img_thub);
    $name_img = $array_url_img_thub[$count_ele-1];

    array_splice($array_url_img_thub,$count_ele-1,0,'thumbnail'); 
    $url_img_thumb = implode('/',$array_url_img_thub);

    $type_img = strtolower(pathinfo($url_img_thumb, PATHINFO_EXTENSION));
    // convert image rotated
    if($type_img == 'png') {
        $original = imagecreatefrompng($url_img);
        $original_thumb = imagecreatefrompng($url_img_thumb);
    }elseif($type_img == 'jpg' || $type_img == 'jpeg'){
        $original = imagecreatefromjpeg ($url_img);
        $original_thumb = imagecreatefromjpeg ($url_img_thumb);
    } elseif($type_img == 'gif') {
        $original = imagecreatefromgif($url_img);
        $original_thumb = imagecreatefromgif($url_img_thumb);
    }else {
        die();
    }
    // Rotate the image by 90 degrees
    $rotated = imagerotate($original,-90, 0);
    $rotated_thumb = imagerotate($original_thumb,-90, 0);

    // Save the rotated image
    if($type_img == 'png') {
        imagepng($rotated, $url_img);
        imagepng($rotated_thumb, $url_img_thumb);
    }elseif($type_img == 'jpg' ||$type_img == 'jpeg'){
        imagejpeg ($rotated, $url_img);
        imagejpeg ($rotated_thumb, $url_img_thumb);
    } elseif($type_img == 'gif') {
        imagegif($rotated, $url_img);
        imagegif($rotated_thumb, $url_img_thumb);
    }else {
        die();
    }
}
function render_json_quote($result ,$record_id){
    
        $bean_quotes =  new AOS_Quotes();
        $bean_quotes->retrieve($record_id);
        //account + contact +oppurtunity
        if(empty($result['Leads'])) {
            $result['Leads']= $bean_quotes->leads_aos_quotes_1leads_ida;
        }
        if(empty($result['Accounts'])) {
            $result['Accounts'] = $bean_quotes->billing_account_id;
        }
        if(empty($result['Contacts'])) {
            $result['Contacts'] = $bean_quotes->billing_contact_id;
        }
    return $result;
}
function render_json_invoice($result ,$record_id){
    
    $bean =  new AOS_Invoices();
    $bean->retrieve($record_id);
    $db = DBManagerFactory::getInstance();

    $sql = "SELECT aos_quotes77d9_quotes_ida FROM aos_quotes_aos_invoices_c WHERE aos_quotes6b83nvoices_idb = '$record_id' AND deleted = 0";
    $ret = $db->query($sql);
    while($row = $ret->fetch_assoc()){
        if(empty($result['AOS_Quotes'])) {
            $result['AOS_Quotes']['id']= $row['aos_quotes77d9_quotes_ida'];
            $bean_quotes =  new AOS_Quotes();
            $bean_quotes->retrieve($row['aos_quotes77d9_quotes_ida']);
            //account + contact +oppurtunity
            $result['AOS_Quotes']['number'] = $bean_quotes->number;
        }
    }
    
    
    if(empty($result['Accounts'])) {
        $result['Accounts'] = $bean->billing_account_id;
    }
    if(empty($result['Contacts'])) {
        $result['Contacts'] = $bean->billing_contact_id;
    }
    if(empty($result['PO_purchase_order'])) {
        if($bean->quote_type_c == "quote_type_sanden"){
            if( $bean->plumber_po_c != ""){
                $purchaseOrder = new PO_purchase_order();
                $purchaseOrder->retrieve($bean->plumber_po_c);
                $result['PO_purchase_order']['plumber']['id'] = $bean->plumber_po_c;
                $result['PO_purchase_order']['plumber']['number'] = $purchaseOrder->number;
            }
            if( $bean->electrical_po_c != ""){
                $purchaseOrder = new PO_purchase_order();
                $purchaseOrder->retrieve($bean->electrical_po_c);
                $result['PO_purchase_order']['electrical']['id'] = $bean->electrical_po_c;
                $result['PO_purchase_order']['electrical']['number'] = $purchaseOrder->number;           
            }
        }else {
            if( $bean->daikin_po_c != ""){
                $purchaseOrder = new PO_purchase_order();
                $purchaseOrder->retrieve($bean->daikin_po_c);
                $result['PO_purchase_order']['daikin']['id'] = $bean->daikin_po_c;
                $result['PO_purchase_order']['daikin']['number'] = $purchaseOrder->number;  
            }
        }   
    }
return $result;
}
function convert_file_and_photo_to_quote($from,$to){
    
    $location = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    
    if($from->module_name == "Leads" || $from->module_name == "AOS_Invoices"){
        $fromFolderName = $from->installation_pictures_c ;
    }else{
        $fromFolderName = $from->pre_install_photos_c ;
    }

    if($to->module_name == "Leads"  || $to->module_name == "AOS_Invoices"){
        $toFolderName   = $to->installation_pictures_c;
    }else{
        $toFolderName   = $to->pre_install_photos_c;
    }
    
    $fromDir = $location. $fromFolderName;
    $toDir   = $location. $toFolderName;
    
    $allPhotos = dirToArray($fromDir) ;
    $array_convert_file_name = array(
        'switchboard' => '_Switchboard_',
        'upclose' => '_Photo_upclose_',
        'meterbox' => '_Photo_meterbox_',
        'floorplan' => '_Floorplan_',
        'electricity_bill' => '_Electricity_bill_',
    );

    foreach($allPhotos as $photo){
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
                $new_file_name = 'Q'.$to->number.$label_new_file;
                $inv_file_path = 
                $i = 1;
                $will_rename = $new_file_name;
                while( !empty(glob($toDir.'/'.$will_rename."*"))){
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

        $folderName_old  = $fromDir .'/'.$file_name;
        $folderName_new  = $toDir.'/';
      

        //check exists folder
        if(!file_exists ($folderName_new)) {
            mkdir($folderName_new);
            if($to->module_name == "Leads" || $to->module_name == "AOS_Invoices"){
                $to->installation_pictures_c = $folderName_new;
                $to->save();
            }else{
                $to->pre_install_photos_c = $folderName_new;
                $to->save();
            }
        }
        copy($folderName_old, $folderName_new.$new_file_name);
        resize_image($new_file_name,$folderName_new);
    }   
}