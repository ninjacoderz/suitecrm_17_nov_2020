<?php
// ini_set('display_errors',1);
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
        
        case 'Solar_Hot_Water_Rebate':
            // generate PDF Solar_Hot_Water_Rebate
            echo Generate_Solar_Hot_Water_Rebate($Invoice);
            break;
        case 'Solar_Hot_Water_Proof' : 
            // generate PDF Solar_Hot_Water_Proof
            echo Generate_Solar_Hot_Water_Proof_Install_Rebate($Invoice);
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
    $source_link = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$Invoice->contact_id4_c.'_signature_c';
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

function Generate_Solar_Hot_Water_Rebate($Invoice){
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/SolarHotWaterProvide.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(10);
    $pdf->SetTextColor(0, 0, 0);
    
    //Installation date:
    if($Invoice->installation_date_c != ''){
        $dateInfos_explode = explode(" ",$Invoice->installation_date_c);
        $dateInfos = $dateInfos_explode[0];
    }else{
        $dateInfos = '';
    }
  
   // $pdf->Write($pdf->SetXY(13, 167.3), html_entity_decode($dateInfos,ENT_QUOTES));
    
    //Solar hot water provider details:
    $pdf->Write($pdf->SetXY(42  ,89), html_entity_decode('Beyond the Grid Pty Ltd',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(22,97), html_entity_decode('67603174661',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(16,112), html_entity_decode('Pure Electric Solutions',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(36,120), html_entity_decode('38 Ewing Street',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(28,135), html_entity_decode('BRUNSWICH',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(28,143), html_entity_decode('VIC',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(80,143), html_entity_decode('3056',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(36,151), html_entity_decode('Paul Szuster',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(43,158), html_entity_decode('1300 86 78 73',ENT_QUOTES));
    $pdf->Write($pdf->SetXY(39,166), html_entity_decode('info@pure-electric.com.au',ENT_QUOTES));

    //Customer‘s details
    $contact_bean = new Contact;
    $contact_bean->retrieve($Invoice->billing_contact_id);

    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(10);
    $pdf->SetTextColor(0, 0, 0);

    //Documentation of installation
    //Does the installation address have an existing PV system with capacity greater than 2.5kW?
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,128.3,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,122,128.3,4,4);
    // Is the installation address connected to reticulated natural gas?
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,140.5,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,122,140.5,4,4);

      
    //Applicant details (primary home owner)
    $pdf->Write($pdf->SetXY(29,220.5), html_entity_decode($contact_bean->first_name,ENT_QUOTES)); // first name
    $pdf->Write($pdf->SetXY(29,228.5), html_entity_decode($contact_bean->last_name,ENT_QUOTES)); // last name
    // $pdf->Write($pdf->SetXY(41,235.8), html_entity_decode($contact_bean->email1 ,ENT_QUOTES)); //Address (unit/floor)
    // install address
    $pdf->Write($pdf->SetXY(35.5,244.3), html_entity_decode($Invoice->install_address_c,ENT_QUOTES)); // install address 1
    $pdf->Write($pdf->SetXY(30,251.3), html_entity_decode($Invoice->install_address_city_c,ENT_QUOTES)); // suburd
    $pdf->Write($pdf->SetXY(25,260.1), html_entity_decode($Invoice->install_address_state_c,ENT_QUOTES)); // state
    $pdf->Write($pdf->SetXY(80,260.1), html_entity_decode($Invoice->install_address_postalcode_c,ENT_QUOTES)); // postcode
    
    //Quote number 
    $pdf->Write($pdf->SetXY(130,84), html_entity_decode($Invoice->quote_number,ENT_QUOTES)); 

    $Invoice_Total_Amount = $Invoice->total_amount;
    $Invoice_Total_Including_GST = 0;

    $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = '" . $Invoice->object_name . "' AND parent_id = '".$Invoice->id."' AND deleted = 0";
    $result = $Invoice->db->query($sql);
    while ($row = $Invoice->db->fetchByAssoc($result)) {
        if (strpos($row['part_number'],'STC') !== false || strpos($row['part_number'],'VEEC') !== false  ) { 
            $product_total_price  = abs($row['product_total_price']);
            $Invoice_Total_Including_GST += $product_total_price;
        }
    }
    $Invoice_Total_Including_GST += $Invoice_Total_Amount;
    $Invoice_Total_Including_GST = number_format($Invoice_Total_Including_GST,2);
    $Invoice_Total_Amount = number_format($Invoice_Total_Amount,2);

    //Total cost of system including GST 
    $pdf->Write($pdf->SetXY(160,101), html_entity_decode($Invoice_Total_Including_GST,ENT_QUOTES)); 
    // Net amount payable by customer 
    $pdf->Write($pdf->SetXY(160,109), html_entity_decode($Invoice_Total_Amount,ENT_QUOTES)); 

    //Confirm the main power source for the existing hot water system to be replaced:
        // Electric storage
    if($Invoice->old_tank_fuel_c == 'electric_storage'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,168.3,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,107.8,168.3,4,4);
    }
        //Electric instantaneous
    if($Invoice->old_tank_fuel_c == 'instant_electric'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,151.2,168.3,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,151.2,168.3,4,4);
    }

        //Gas storage
    if($Invoice->old_tank_fuel_c == 'gas_storage'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,173.3,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,107.8,173.3,4,4);
    }
        //Gas instantaneous
    if($Invoice->old_tank_fuel_c == 'gas_instant'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,151.2,173.3,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,151.2,173.3,4,4);
    }
        //LPG
    if($Invoice->old_tank_fuel_c == 'lpg'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,178.5,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,107.8,178.5,4,4);
    }
        //Solid fuel
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,151.2,178.3,4,4);

        //Electric boosted solar
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,107.8,184.3,4,4);
    
        //Gas boosted solar
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,151.2,184.3,4,4);

        //Heat pump
    if($Invoice->old_tank_fuel_c == 'heatpump'){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,107.8,190.3,4,4);
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,107.8,190.3,4,4);
    }

    //Be conducted by an appropriately licensed plumber
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,109.8,241.3,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,124.8,241.3,4,4);
    

    // add Secord Page 
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/SolarHotWaterProvide.pdf');
    // import page 2
    $tplIdx = $pdf->importPage(2);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,17,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,17,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,42,17,4,4);

    // Be conducted by an installer that has no prosecutions
    // registered with Worksafe Victoria or an equivalent
    // authority in Australia in the past 3 years.
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,48,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,48,4,4);
    
    // Be conducted by an installer that ensures safe work methods
    // and fall prevention measures are in place as per the
    // Occupational Health and Safety Regulations 2017 (S.R. No.
    // 22/17) and otherwise ensures all other applicable occupational
    // health and safety laws and requirements are complied with.
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,74,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,74,4,4);

    // Have products that are on both the Clean Energy Regulator
    // List of Registered Solar Hot Water Heaters, and on the Victorian
    // Essential Services Commission Registered Products List
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,94,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,94,4,4);

    // Have installation completed by the installer and have a
    // date certified on the Plumbing Certificate of Compliance
    // and the CES prescribed or non-prescribed.
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,113,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,113,4,4);

    // Provide a minimum of 5 years’ warranty on all major
    // components
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,128,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,128,4,4);

    //Ensure that the volumetric storage capacity of the installed
    // heater is appropriate for the premises at which the heater
    // is to be installed and the purposes for which the heater,
    // and the hot water produced by the heater, are to be used.
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,152,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,152,4,4);

    // Ensure that the installed system replaces a previous
    // system that is at least three years old, unless faulty or out
    // of warranty as assessed by a licensed plumber.
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,172,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,172,4,4);

    // Do you agree as the Solar Hot Water Provider to provide all
    // the required installation documentation to the applicant
    // post installation? 
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,16,190,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,30,190,4,4);

    // Has a replacement solar hot water system already been
    // installed due to an emergency breakdown or fault with the
    // original hot water system?
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,109.8,35.3,4,4);
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,124.8,35.3,4,4);
    //Print name:
    $pdf->Write($pdf->SetXY(136,121), html_entity_decode('Paul Szuster',ENT_QUOTES));

    $pre_file = str_replace(' ' ,'_',trim($contact_bean->first_name .' '. $contact_bean->last_name));
    $fp = fopen($ds_dir.'/'.$pre_file.'_Solar_Hot_Water_Rebate.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/'.$pre_file.'_Solar_Hot_Water_Rebate.pdf', 'F');
    return 'Finish';
}

/**
 * VUT - Create pdf file
 */
function Generate_Solar_Hot_Water_Proof_Install_Rebate($Invoice){
    $foldeId = $Invoice->installation_pictures_c;
    $ds_dir =  $_SERVER['DOCUMENT_ROOT'] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' .$foldeId;
    $pdf = new Fpdi();
    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/solarHotWater_Proof.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(10);
    $pdf->SetTextColor(0, 0, 0);
    
    //Installation date:
    if($Invoice->installation_date_c != ''){
        $dateInfos_explode = explode(" ",$Invoice->installation_date_c);
        $dateInfos = $dateInfos_explode[0];
    }else{
        $dateInfos = '';
    }
  
    $pdf->Write($pdf->SetXY(13, 167.3), html_entity_decode($dateInfos,ENT_QUOTES));
    
    //Solar Hot Water retailer name:
    $pdf->Write($pdf->SetXY(13,189.3), html_entity_decode('Beyond the Grid Pty Ltd',ENT_QUOTES));

    //Customer‘s details
    $contact_bean = new Contact;
    $contact_bean->retrieve($Invoice->billing_contact_id);

    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(10);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Write($pdf->SetXY(13,211.5), html_entity_decode('1',ENT_QUOTES)); 
    $pdf->Write($pdf->SetXY(29,219.5), html_entity_decode($contact_bean->first_name,ENT_QUOTES)); // first name
    $pdf->Write($pdf->SetXY(29,227), html_entity_decode($contact_bean->last_name,ENT_QUOTES)); // last name
    $pdf->Write($pdf->SetXY(35,235), html_entity_decode($contact_bean->email1 ,ENT_QUOTES)); //Email address
    // install address
    $pdf->Write($pdf->SetXY(36.5,248.5), html_entity_decode($Invoice->install_address_c,ENT_QUOTES)); // install address 1
    // $pdf->Write($pdf->SetXY(35.5,256), html_entity_decode($Invoice->install_address_c,ENT_QUOTES)); // install address 2
    $pdf->Write($pdf->SetXY(30,263.5), html_entity_decode($Invoice->install_address_city_c,ENT_QUOTES)); // suburd
    $pdf->Write($pdf->SetXY(25,271.5), html_entity_decode($Invoice->install_address_state_c,ENT_QUOTES)); // state
    $pdf->Write($pdf->SetXY(81,271.5), html_entity_decode($Invoice->install_address_postalcode_c,ENT_QUOTES)); // postcode

    $check_VEEC_STCs = false;
    $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = '" . $Invoice->object_name . "' AND parent_id = '".$Invoice->id."' AND deleted = 0";
    $result = $Invoice->db->query($sql);
    while ($row = $Invoice->db->fetchByAssoc($result)) {
        if (strpos($row['part_number'],'VEEC') !== false  ) { 
            $check_VEEC_STCs = true;
        }
    }

    //Documentation of installation
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,175,106,5,5);
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,175,115,5,5); //120
    if($check_VEEC_STCs){
        $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,175,127,5,5); //131
    }else{
        $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,175,127,5,5); //131
    }

    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,175,139,5,5); //141
    $pdf->Image(__DIR__.'/text/icon_checkbox_true.jpg' ,175,150,5,5); //161

    //Emergency Installations
    $pdf->Image(__DIR__.'/text/icon_checkbox.jpg' ,124,255,5,5);

    //Solar retailer payment details 
    $pdf->Write($pdf->SetXY(130,212), html_entity_decode('Beyond the Grid Pty Ltd',ENT_QUOTES)); //Account name 
    $pdf->Write($pdf->SetXY(128,220), html_entity_decode('814282',ENT_QUOTES)); //BSB number 
    $pdf->Write($pdf->SetXY(141,227.8), html_entity_decode('50514152',ENT_QUOTES)); //Bank account number 

    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile(__DIR__.'/text/solarHotWater_Proof.pdf');
    // import page 2
    $tplIdx = $pdf->importPage(2);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
 
    //Solar retailer declaration
    $today_Date =  date('d F Y', time());
    $today_name =  date('dMY', time());
    $pdf->Write($pdf->SetXY(30, 99.2), html_entity_decode('Paul Szuster',ENT_QUOTES)); //Print name
    $pdf->Write($pdf->SetXY(23, 107), html_entity_decode($today_Date,ENT_QUOTES)); //Date
    $pre_file = str_replace(' ' ,'_',trim($contact_bean->first_name .' '. $contact_bean->last_name.' signed '.$today_name));
    $pre_name = 'SolarHotWater-Proof-of-Install-and-Rebate-Claim-Form';
    $fp = fopen($ds_dir.'/SolarHotWater-Proof-of-Install-and-Rebate-Claim-Form_'.$pre_file.'.pdf', 'wb');
    fclose($fp);
    $pdf->Output($ds_dir.'/SolarHotWater-Proof-of-Install-and-Rebate-Claim-Form_'.$pre_file.'.pdf', 'F');
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
