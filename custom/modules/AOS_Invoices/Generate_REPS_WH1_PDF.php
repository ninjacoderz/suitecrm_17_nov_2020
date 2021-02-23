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
    $pdf->Write($pdf->SetXY(37,103.7), html_entity_decode($contact_bean->primary_address_street,ENT_QUOTES)); // install address
    $pdf->Write($pdf->SetXY(37,108.5), html_entity_decode($contact_bean->primary_address_city,ENT_QUOTES)); // suburd
    $pdf->Write($pdf->SetXY(118,108.5), html_entity_decode($contact_bean->primary_address_state,ENT_QUOTES)); // state
    $pdf->Write($pdf->SetXY(170,108.5), html_entity_decode($contact_bean->primary_address_postalcode,ENT_QUOTES)); // postcode
    $pdf->Write($pdf->SetXY(37,113), html_entity_decode($contact_bean->phone_mobile,ENT_QUOTES)); // phone
    $pdf->Write($pdf->SetXY(118,112.8), html_entity_decode($contact_bean->email1,ENT_QUOTES)); // email

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
    $pdf->Write($pdf->SetXY(36.5,203.5), html_entity_decode($Invoice->plumber_c,ENT_QUOTES)); // Installer Name
    $pdf->Write($pdf->SetXY(36.5,208), html_entity_decode($Invoice->plumber_license_number_c,ENT_QUOTES)); // Installers Lic. No
    $pdf->Write($pdf->SetXY(125,208), html_entity_decode($Invoice->vba_pic_cert_c,ENT_QUOTES)); // COC No.

    $fp = fopen($ds_dir.'/REPS_WH1_Hot_Water_Replacement.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/REPS_WH1_Hot_Water_Replacement.pdf', 'F');
   
    return 'Finish';
    
}


// $foldeId = '5f2c2b1a-898e-102d-9c19-a95fffcb502a';
// $foldeId = $_REQUEST['foldeId'];
$InvoiceID = $_REQUEST['InvoiceID'];
$Invoice = BeanFactory::getBean('AOS_Invoices', $InvoiceID);
if($Invoice->id != '') {
    echo generatePDF($Invoice);
}else{
    echo 'Error';
}
