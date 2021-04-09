<?php
ini_set('display_errors',1);
use setasign\Fpdi\Fpdi;
require_once('text/fpdf.php');
require_once('text/src/autoload.php');
//setup get user Paul Format Date 
$paul_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
$user = new User();
$user->retrieve($paul_id);
global $current_user;
$current_user = $user;
// $foldeId = '5f2c2b1a-898e-102d-9c19-a95fffcb502a';
// $foldeId = $_REQUEST['foldeId'];
$InvoiceID = $_REQUEST['InvoiceID'];
$Invoice = BeanFactory::getBean('AOS_Invoices', $InvoiceID);
$action = ($_REQUEST['action'])? $_REQUEST['action'] : 'default';
if($Invoice->id != '') {
    switch ($action) {
        case 'REPS_Infor_State':
            // generate PDF REPS_Information_Statement
            echo Generate_REPS_Information_Statement($Invoice);
            break;
        
        default:
            // generate PDF REPS_WH1_PDF
            echo generatePDF($Invoice);
            break;
    } 
    
}else{
    echo 'Error';
}

function generatePDF($Invoice){
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/REPS_WH1_PDF.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(8);
    $pdf->SetTextColor(0, 0, 0);
    
    //Transaction Id/Internal Reference Number
    //$pdf->Write($pdf->SetXY(62, 29.5), html_entity_decode($Invoice->number,ENT_QUOTES));
    //Activity Completed Date
    if($Invoice->installation_date_c != ''){
        $dateInfos_explode = explode(" ",$Invoice->installation_date_c);
        $dateInfos = $dateInfos_explode[0];
    }else{
        $dateInfos = '';
    }
  
    $pdf->Write($pdf->SetXY(137, 29.5), html_entity_decode($dateInfos,ENT_QUOTES));
   
    //WH1 - REPLACE OR UPGRADE WATER HEATER 
    // $pdf->Image(__DIR__.'/text/icon.jpg' ,13,45,3,2.8);
    // $pdf->Image(__DIR__.'/text/icon.jpg' ,13,49.5,3,2.8);
    // $pdf->Image(__DIR__.'/text/icon.jpg' ,13,54,3,2.8);
    // $pdf->Image(__DIR__.'/text/icon.jpg' ,13,58.5,3,2.8);
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,63,3,2.8);

    //New Water Heater Make
    $pdf->Write($pdf->SetXY(130,52), html_entity_decode('Sanden',ENT_QUOTES));
    //New Water Heater Model
    $pdf->Write($pdf->SetXY(130,59), html_entity_decode($Invoice->sanden_model_c,ENT_QUOTES));
    //Spec Sheet Attached
    $pdf->Image(__DIR__.'/text/icon.jpg' ,194,59,3,2.8);
    //Has a new gas connection been made to this property?
    $pdf->Image(__DIR__.'/text/icon.jpg' ,77.4,68,3,2.8);

    // Property is Class 1 or Class 2 Dwelling 
    $pdf->Image(__DIR__.'/text/icon.jpg' ,52.5,72.5,3,2.8);

    //Are existing water eﬃcient models 9l or less
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,80,3,2.8);
    $pdf->Write($pdf->SetXY(84,81.5), html_entity_decode($Invoice->the_flow_rate_tested_c,ENT_QUOTES)); // The ﬂow rate tested 

    //Have been replaced with a minimum three star showerhead
    // $pdf->Image(__DIR__.'/text/icon.jpg' ,13,84.5,3,2.8);
    $pdf->Write($pdf->SetXY(75,86), html_entity_decode($Invoice->replacement_showerhead_c,ENT_QUOTES)); // star showerhead 
    $pdf->Write($pdf->SetXY(105,86), html_entity_decode($Invoice->existing_sh_flow_rate_c,ENT_QUOTES)); // model WELS rated 
    //customer detail
    $contact_bean = new Contact;
    $contact_bean->retrieve($Invoice->billing_contact_id);

    $pdf->Write($pdf->SetXY(37,99.2), html_entity_decode($contact_bean->first_name,ENT_QUOTES)); // first name
    $pdf->Write($pdf->SetXY(118,99.2), html_entity_decode($contact_bean->last_name,ENT_QUOTES)); // last name
    // install address
    $pdf->Write($pdf->SetXY(37,103.7), html_entity_decode($Invoice->install_address_c,ENT_QUOTES)); // install address
    $pdf->Write($pdf->SetXY(37,108.5), html_entity_decode($Invoice->install_address_city_c,ENT_QUOTES)); // suburd
    $pdf->Write($pdf->SetXY(118,108.5), html_entity_decode($Invoice->install_address_state_c,ENT_QUOTES)); // state
    $pdf->Write($pdf->SetXY(170,108.5), html_entity_decode($Invoice->install_address_postalcode_c,ENT_QUOTES)); // postcode
    $pdf->Write($pdf->SetXY(37,113), html_entity_decode($contact_bean->phone_mobile,ENT_QUOTES)); // phone
    $pdf->Write($pdf->SetXY(118,112.8), html_entity_decode($contact_bean->email1,ENT_QUOTES)); // email

    // PROPERTY TYPE    
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,120,3,2.8);
    //Have you resided in the premises for more than 3 years?
    if($Invoice->resided_in_the_premises_c == '1'){
        $pdf->Image(__DIR__.'/text/icon.jpg' ,168.5,120,3,2.8);
    }else{
        $pdf->Image(__DIR__.'/text/icon.jpg' ,178.8,120,3,2.8);
    }

    //Customer is NOT Priority Group Status
    $pdf->Image(__DIR__.'/text/icon.jpg' ,154.2,148.2,3,2.8);

    // If No, is an existing gas connection present?
    if($Invoice->existing_gas_connection_c == 'true'){
        $pdf->Image(__DIR__.'/text/icon.jpg' ,158.3,72.5,3,2.8);
    }else{
        $pdf->Image(__DIR__.'/text/icon.jpg' ,168.3,72.5,3,2.8);
    }
    
    //CUSTOMER / INSTALLER DECLARATION
    $pdf->Write($pdf->SetXY(36.5,203.5), html_entity_decode($Invoice->plumber_contact_c,ENT_QUOTES)); // Installer Name
    $pdf->Write($pdf->SetXY(36.5,208), html_entity_decode($Invoice->plumber_license_number_c,ENT_QUOTES)); // Installers Lic. No
    $pdf->Write($pdf->SetXY(125,208), html_entity_decode($Invoice->vba_pic_cert_c,ENT_QUOTES)); // COC No.
    
    //For YESS Pty Ltd terms and conditions please visit: www.yess.net.au/terms-conditions.html
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,246,3,2.8);
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,250.7,3,2.8);
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,255.7,3,2.8);

    //customer signature     
    $source_link = $_SERVER['DOCUMENT_ROOT'].'/upload/e5bc4811-5017-1600-adb4-5db10782d473_signature_c';
    if (file_exists($source_link)) {   
        $mime_content_type = mime_content_type($source_link);
        $ext = return_ext($mime_content_type);
        if($ext != '') {
            $signature_draft_link = __DIR__.'/text/signature_draft.'.$ext;
            if(!file_exists ($signature_draft_link)) {
               $fp = fopen($signature_draft_link, 'wb');
               fclose($fp);
            }    
            if(file_put_contents($signature_draft_link,file_get_contents($source_link))){
                $pdf->Image($signature_draft_link,127,194.7,30,7.8);
            }
        }
    }
   
    //Customer Name
    $pre_file = str_replace(' ' ,'_',trim($contact_bean->first_name .' '. $contact_bean->last_name));
    $pdf->Write($pdf->SetXY(36.5,261.3), html_entity_decode(trim($contact_bean->first_name .' '. $contact_bean->last_name),ENT_QUOTES));

    $fp = fopen($ds_dir.'/'.$pre_file.'_SA_REPS_Activity_Record_WH1_Hot_Replacement.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/'.$pre_file.'_SA_REPS_Activity_Record_WH1_Hot_Replacement.pdf', 'F');
   
    return 'Finish';
    
}


function Generate_REPS_Information_Statement($Invoice){
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/SA_REP_Information_Statement.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(8);
    $pdf->SetTextColor(0, 0, 0);
    
    //WH1 - REPLACE OR UPGRADE WATER HEATER
    //$pdf->Write($pdf->SetXY(62, 29.5), html_entity_decode($Invoice->number,ENT_QUOTES));
    //Activity Completed Date
    if($Invoice->installation_date_c != ''){
        $dateInfos_explode = explode(" ",$Invoice->installation_date_c);
        $dateInfos = $dateInfos_explode[0];
    }else{
        $dateInfos = '';
    }
  
    $pdf->Write($pdf->SetXY(109, 26), html_entity_decode($dateInfos,ENT_QUOTES));
   
    //REPS ACTIVITY 
    $pdf->Image(__DIR__.'/text/icon.jpg' ,13,41.5,3,2.8); //WH1 - REPLACE OR UPGRADE WATER HEATER

    //CUSTOMER DETAILS
    $contact_bean = new Contact;
    $contact_bean->retrieve($Invoice->billing_contact_id);

    $pdf->Write($pdf->SetXY(37,59.5), html_entity_decode($contact_bean->first_name,ENT_QUOTES)); // first name
    $pdf->Write($pdf->SetXY(118,59.5), html_entity_decode($contact_bean->last_name,ENT_QUOTES)); // last name
    // install address
    $pdf->Write($pdf->SetXY(37,64), html_entity_decode($Invoice->install_address_c,ENT_QUOTES)); // install address
    $pdf->Write($pdf->SetXY(37,69), html_entity_decode($Invoice->install_address_city_c,ENT_QUOTES)); // suburd
    $pdf->Write($pdf->SetXY(118,69), html_entity_decode($Invoice->install_address_state_c,ENT_QUOTES)); // state
    $pdf->Write($pdf->SetXY(170,69), html_entity_decode($Invoice->install_address_postalcode_c,ENT_QUOTES)); // postcode
    $pdf->Write($pdf->SetXY(37,73), html_entity_decode($contact_bean->phone_mobile,ENT_QUOTES)); // phone
    $pdf->Write($pdf->SetXY(118,73), html_entity_decode($contact_bean->email1,ENT_QUOTES)); // email

    //Customer is NOT Priority Group Status
    $pdf->Image(__DIR__.'/text/icon.jpg' ,153,99.2,3,2.8);



    //CUSTOMER / INSTALLER DECLARATION
        //I confirm that all shower heads connected to the installed water heater have been tested or replaced and are 9 litres per minute or less. 
        $pdf->Image(__DIR__.'/text/icon.jpg' ,13.5,160,3,2.8);

        $pdf->Write($pdf->SetXY(36.5,169.5), html_entity_decode($Invoice->plumber_contact_c,ENT_QUOTES)); // Installer Name
        $pdf->Write($pdf->SetXY(125,169.5), html_entity_decode($Invoice->plumber_license_number_c,ENT_QUOTES)); // Installers Lic. No
        $pdf->Write($pdf->SetXY(125,173.7), html_entity_decode($Invoice->vba_pic_cert_c,ENT_QUOTES)); // COC No.

        //I confirm that a minimum of $33 has been paid for this service as evidenced on my Tax Invoice
        $pdf->Image(__DIR__.'/text/icon.jpg' ,13.5,216,3,2.8);
        $pdf->Image(__DIR__.'/text/icon.jpg' ,13.5,223,3,2.8);
        $pdf->Image(__DIR__.'/text/icon.jpg' ,13.5,230,3,2.8);
        // $pdf->Image(__DIR__.'/text/icon.jpg' ,13.5,236,3,2.8);

        $pdf->Write($pdf->SetXY(34.5,244), html_entity_decode($contact_bean->first_name .' ' .$contact_bean->last_name ,ENT_QUOTES)); // Customer Name

    $pre_file = str_replace(' ' ,'_',trim($contact_bean->first_name .' '. $contact_bean->last_name));

    $fp = fopen($ds_dir.'/'.$pre_file.'_SA_REPS_Information_Statement.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/'.$pre_file.'_SA_REPS_Information_Statement.pdf', 'F');
    return 'Finish';
}

function return_ext($mime) {

    $mime_types = array(
        // images
        'image/png' =>'png' ,
        'image/jpeg' => 'jpe',
        'image/jpeg' =>'jpeg',
        'image/jpeg' => 'jpg',
    );

    if (array_key_exists($mime, $mime_types)) {
        return $mime_types[$mime];
    }
    else {
        return 'application/octet-stream';
    }
}
