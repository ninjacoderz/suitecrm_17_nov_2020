<?php
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    require_once(dirname(__FILE__).'/simple_html_dom.php');

//get parameter from ajax
    $quoteSG_id = $_GET['quoteSG_ID'];
    $quote_id = $_GET['record'];
    $st = urldecode($_GET['state']);
    $quoteDate = urldecode($_GET['quoteDate']);
//END
    
//function curl for all method GET and POST Data
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
//END

//retrieve quote
    $quote =  new AOS_Quotes();
    $quote = $quote->retrieve($quote_id);

    if($quote->id){
        if($quote->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
            $GLOBALS['username'] = "matthew.wright";
            $GLOBALS['password'] =  "MW@pure733";
        }else{
            $GLOBALS['username'] = 'paul.szuster@solargain.com.au';
            $GLOBALS['password'] = 'WalkingElephant#256';
        }
    }
//END

//get data from SG quote
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
    $quoteSG = curlSG('GET','',$url,$quoteSG_id);
    $quote_decode = json_decode($quoteSG);
    $decode_result = json_decode($quoteSG,true);
//END

//Thienpb code for change account if download false
    if(!isset($quote_decode->ID)){
        if( $GLOBALS['username'] == 'paul.szuster@solargain.com.au'){
            $GLOBALS['username'] = "matthew.wright";
            $GLOBALS['password'] =  "MW@pure733";
        }else{
            $GLOBALS['username'] = 'paul.szuster@solargain.com.au';
            $GLOBALS['password'] = 'WalkingElephant#256';
        }
        
        //get data from SG quote
            $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
            $quoteSG = curlSG('GET','',$url,$quoteSG_id);
            $quote_decode = json_decode($quoteSG);
            $decode_result = json_decode($quoteSG,true);
        //END
    }
//END
//NEW logic
if($_REQUEST['type'] == 'updateMeterPhase'){
    $quoteInstall = $quote_decode->Install;
    $quoteInstall->MeterPhase = urldecode($_REQUEST['meter_phase_c']);

    $data_string = json_encode($quoteInstall);

    //Push
    $url = 'https://crm.solargain.com.au/APIv2/installs';
    curlSG('POST',$data_string,$url,$quoteSG_id);
    die;
}
//END
//check status is converted to order
    if($quote->id){
        if($quote->solargain_quote_number_c == $quoteSG_id){
            if(!isset($quote_decode)) die();
            if($quote_decode->Status->Description == "Converted To Order") die();
        }else{
            die();
        }
    }else{
        die();
    }
//END

//Update quote date to SG
    if(isset($quoteDate ) && $quoteDate != ""){
        $date = DateTime::createFromFormat('d/m/Y H:i', $quoteDate);
        $date_nextActionDate= DateTime::createFromFormat('d/m/Y H:i', $quoteDate);
        $date_nextActionDate->add(new DateInterval('P7D'));// add 7 days

        // push data 
        $quote_decode->Date->Date = $date->format('d/m/Y');
        $quote_decode->Date->Time = $date->format('g:i A');
        $quote_decode->NextActionDate->Date = $date_nextActionDate->format('d/m/Y');
        $quote_decode->NextActionDate->Time = $date_nextActionDate->format('g:i A');
        $data_string =  json_encode( $quote_decode);
        
        $url = "https://crm.solargain.com.au/APIv2/quotes/";
        $quoteSG = curlSG('POST',$data_string,$url,$quoteSG_id);
        
        die();
    }
//end

//Update option sg (Pricing info , and travel)
    $data_option_string = $quote_decode;
    for($i=0;$i<count($data_option_string->Options);$i++){
        $data_option_string->Options[$i]->Travel = $_GET["travel_km_".($i+1)];
        if($_GET['number_double_storey_panel_'.($i+1)] == 0){
            $data_option_string->Options[$i]->AdditionalCableRun = 0;
        }else{
            $data_option_string->Options[$i]->AdditionalCableRun = 1;
        }
        $data_option_string->Options[$i]->ExcessHeightPanels = $_GET['number_double_storey_panel_'.($i+1)];
        $data_option_string->Options[$i]->Splits =  $_GET['groups_of_panels_'.($i+1)];
    }
    //Update field NextActionDate = today + 30days
    $data_option_string->NextActionDate = array(
        "Date" => date('d/m/Y', time() + 30*24*60*60),
        "Time"=> "9:00 AM"
    );
    $data_option_string = json_encode($data_option_string);
    //Push
    $url = "https://crm.solargain.com.au/APIv2/quotes/";
    curlSG('POST',$data_option_string,$url,$quoteSG_id);
//END

//Update install field to sg
    $install = $quote_decode->Install->ID;
    $data = array(
        "ID"=>$install,
        "AccountHolderDateOfBirth" => array(
            "Date" => "01/01/1977"
        ),
        "Address" => array(
            "Street1" =>	(urldecode($_GET['billing_address_street']))?urldecode($_GET['billing_address_street']):$quote_decode->Install->Address->Street1,
            "Street2"	=> "",
            "Locality" =>	(urldecode($_GET['billing_address_city']))?urldecode($_GET['billing_address_city']):$quote_decode->Install->Address->Locality,
            "State"	=> (urldecode($_GET['state']))?urldecode($_GET['state']):$quote_decode->Install->Address->State,
            "PostCode" =>	(urldecode($_GET['postalcode']))?urldecode($_GET['postalcode']):$quote_decode->Install->Address->PostCode,
        ),
        "RoofType" =>array("ID" =>(urldecode($_GET['roof_type']) && urldecode($_GET['roof_type']) != '0') ? urldecode($_GET['roof_type']): ((urldecode($_GET['roof_type']) == '0') ? 1 : $quote_decode->Install->RoofType->ID)), //roof_type,
        "Notes" => array(array(
            "ID" => 0,
        )),
        "BuildHeight" => array(
            "ID" =>	(urldecode($_GET['build_height']))?urldecode($_GET['build_height']):$quote_decode->Install->BuildHeight->ID,
        ),
        "MainsTypeID"	=> (urldecode($_GET['main_type']))?urldecode($_GET['main_type']):$quote_decode->Install->MainsTypeID,
        "MeterNumber"	=> (urldecode($_GET['meter_number']))?urldecode($_GET['meter_number']):$quote_decode->Install->MeterNumber,
        "MeterPhase"=> (urldecode($_GET['meter_phase']))?urldecode($_GET['meter_phase']):$quote_decode->Install->MeterPhase,
        "AccountNumber" =>	(urldecode($_GET['account_number']))?urldecode($_GET['account_number']):$quote_decode->Install->AccountNumber,
        "BillingName"	=> (urldecode($_GET['billing_name']))?urldecode($_GET['billing_name']):$quote_decode->Install->BillingName,
        "EnergyRetailer" => array(
            "ID" => (urldecode($_GET['energy_retailer']))?urldecode($_GET['energy_retailer']):$quote_decode->Install->EnergyRetailer->ID,
        ),
        "NetworkOperator" => array(
            "ID" => (urldecode($_GET['distributor']))?urldecode($_GET['distributor']):$quote_decode->Install->NetworkOperator->ID,
        ),
    );
    $data["NMINumber"]	= (urldecode($_GET['nmi_number']))?urldecode($_GET['nmi_number']):$quote_decode->Install->NMINumber;
    
    if(urldecode($_GET['connection_type']) == 'Semi_Rural_Remote_Meter'){
        $data['ConnectionType'] = 'Semi Rural/Remote Meter';
    }else{
        $data['ConnectionType'] = (urldecode($_GET['connection_type']))?urldecode($_GET['connection_type']):$quote_decode->Install->ConnectionType;
    }
    $roof_type_arr =array(  2 => "TIN/COLORBOND",
                            3 => "CONCRETE TILE",
                            4 => "KLIPLOC"      ,
                            0 => "SLATE ROOF"   ,
                            8 => "ASBESTOS ROOF",
                            10 => "TERRACOTTA"  ,
                            1 => "UNSURE");
    if(urldecode($_GET['roof_type']) == '0' || urldecode($_GET['roof_type']) == '1'){
        $data['RoofDetails'] = $roof_type_arr[urldecode($_GET['roof_type'])];
    }
    $data_string = json_encode($data);

    //Push
    $url = 'https://crm.solargain.com.au/APIv2/installs';
    curlSG('POST',$data_string,$url,$quoteSG_id);
//END

//Update customer info to sg
    //Get missing fields of Quote from Leads
    $quote = new AOS_Quotes();
    $quote = $quote->retrieve($quote_id);
    $query =   "SELECT leads.id FROM aos_quotes 
                INNER JOIN leads ON leads.account_id = aos_quotes.billing_account_id 
                WHERE aos_quotes.billing_account_id = '$quote->billing_account_id' 
                AND aos_quotes.id = '$quote->id' 
                AND aos_quotes.deleted = 0 LIMIT 1";
    $db = DBManagerFactory::getInstance();
    $result_lead = $db->query($query);

    if($result_lead->num_rows > 0){
        $lead_row = $db->fetchByAssoc($result_lead);

        $lead = new Lead();
        $lead =  $lead->retrieve($lead_row['id']);

        $first_name = $lead->first_name;
        $last_name = $lead->last_name;
        $phone_work = $lead->phone_work;
        $phone_mobile = $lead->phone_mobile;
        $email_lead = $lead->email1;
    }

    $customer_id = $quote_decode->Customer->ID;
    $custommer_type = (urldecode($_GET['customer_type']))?urldecode($_GET['customer_type']):$quote_decode->Install->CustomerTypeID;
    $data = array(
        "ID"=>$customer_id,
        "CustomerTypeID" => $custommer_type,
        "LastName" => $last_name,
        "FirstName" => $first_name,
        "Phone"	=> $phone_work ? $phone_work : '',
        "Mobile" => $phone_mobile ? $phone_mobile : '',
        "Email" =>	$email_lead ? $email_lead : '',
        "Address" => array(
            "Street1" =>	(urldecode($_GET['billing_address_street']))?urldecode($_GET['billing_address_street']):$quote_decode->Customer->Address->Street1,
            "Street2"	=> "",
            "Locality" =>	(urldecode($_GET['billing_address_city']))?urldecode($_GET['billing_address_city']):$quote_decode->Customer->Address->Locality,
            "State"	=> (urldecode($_GET['state']))?urldecode($_GET['state']):$quote_decode->Customer->Address->State,
            "PostCode" =>	(urldecode($_GET['postalcode']))?urldecode($_GET['postalcode']):$quote_decode->Customer->Address->PostCode,
        ),
        "OptIn" => true,
        "Notes" => array(array(
            "ID" => 0,
        )),
    );
    $data_string = json_encode($data);
    //Push
    $url = 'https://crm.solargain.com.au/APIv2/customers';
    $quoteSG = curlSG('POST',$data_string,$url,$quoteSG_id);
//END

//Save bean
    $bean = BeanFactory::getBean("AOS_Quotes", $quote_id);

    $bean->billing_address_street = urldecode($_GET['billing_address_street']);
    $bean->billing_address_city = urldecode($_GET['billing_address_city']);
    $bean->billing_address_state = urldecode($_GET['state']);
    $bean->billing_address_postalcode = urldecode($_GET['postalcode']);
    $bean->customer_type_c = $custommer_type;

    $bean->roof_type_c = urldecode($_GET['roof_type']);
    $bean->gutter_height_c = urldecode($_GET['build_height']);
    $bean->connection_type_c = urldecode($_GET['connection_type']);
    $bean->main_type_c = urldecode($_GET['main_type']);
    $bean->meter_number_c = urldecode($_GET['meter_number']);
    $bean->meter_phase_c = urldecode($_GET['meter_phase']);
    $bean->nmi_c = urldecode($_GET['nmi_number']);
    $bean->address_nmi_c = urldecode($_GET['address_nmi']);
    $bean->account_number_c = urldecode($_GET['account_number']);
    $bean->name_on_billing_account_c = urldecode($_GET['billing_name']);
    $bean->energy_retailer_c = urldecode($_GET['energy_retailer']);
    $bean->distributor_c = urldecode($_GET['distributor']);
    $bean->save();
//END

//Files to sg
    //Upload file for Citipower Powercor push sogargain
    if($bean->meter_number_c !== '' && ($bean->distributor_c == '4' || $bean->distributor_c == '7' || $bean->distributor_c == '6')){
        $folder_pdf = dirname(__FILE__)."/server/php/files/".$bean->pre_install_photos_c;

        //new logic get file citipower 
        $leads_file_attachmens = scandir( $folder_pdf . '/');
        foreach ($leads_file_attachmens as $key => $value) {
            if (strpos($value, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {
            $filename_pdf = $value;
            }          
        }
    
        if(is_file($folder_pdf.'/'.$filename_pdf)){

            //delete file if it exist on Solargain
            foreach ($decode_result['Files'] as $value) {
                if($value['Filename'] == $filename_pdf) {
                    $id_file_image_exist = $value['ID'];

                    //DELETE file before get
                    $url = "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_exist;
                    curlSG('DELETE','',$url,$quoteSG_id);

                    //GET
                    $url = "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files";
                    curlSG('GET','',$url,$quoteSG_id);
                }
            }

            //upload new file Citipower Powercor
            $content_file =  file_get_contents($folder_pdf.'/'.$filename_pdf);
            $data_file_upload = array(
                'Data'     => base64_encode($content_file),
                'Filename' => $filename_pdf,
                'Title'    => $filename_pdf,
                'Url'      => "",
            );
            $data_string_file = json_encode($data_file_upload);

            //PUSH
            $url = "https://crm.solargain.com.au/APIv2/quotes/" .$quote_id."/upload";
            curlSG('POST',$data_string_file,$url,$quoteSG_id);
            
            //GET
            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files";
            $result = curlSG('GET','',$url,$quoteSG_id);
            $decode_result_files_image_upload = json_decode($result,true);

            //need add field Category = "RETAILER APPROVAL" 
            if(strpos($filename_pdf, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {
                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $filename_pdf){
                        $id_file_image_meterbox = $value['ID'];
                    }
                }
                
                //PUSH
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_meterbox ."/category/6";
                curlSG('GET','',$url,$quoteSG_id);
            }
        }
    }

    //thienpb fix Upload all file image into solargain
    if($bean->pre_install_photos_c !== '') {
        $folder_ = dirname(__FILE__)."/server/php/files/".$bean->pre_install_photos_c ."/";
        $files = scandir($folder_);
        $files = array_diff($files, array('.', '..','thumbnail'));
        foreach($files as $file) {
            $file_type = strtolower(substr($file,-4));
            $file_type = str_replace(".","",$file_type);
            if($file_type == 'png' || $file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'gif'|| $file_type == "pdf"){
                
                $content_file =  file_get_contents($folder_.'/'.$file);
                //DELETE file image exist
                    foreach ($decode_result['Files'] as $value) {
                        if($value['Filename'] == $file) {
                            $id_file_image_exist = $value['ID'];

                            //DELETE
                            $url  = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_exist;
                            curlSG('DELETE','',$url,$quoteSG_id);
                            
                            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                            curlSG('GET','',$url,$quoteSG_id);
                        }
                    }

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
                        curlSG('POST',$data_tring_upload,$url,$quoteSG_id);
                        
                        //GET
                        $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                        $result = curlSG('GET','',$url,$quoteSG_id);
                        $decode_result_files_image_upload = json_decode($result,true);
                        $id_file_image_bill = '';
                        foreach ($decode_result_files_image_upload as $value){
                            if($value['Filename'] == $file){
                                $id_file_image_bill = $value['ID'];
                            }
                        }
                        //GET
                        $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_bill ."/category/".$category;
                        curlSG('GET','',$url,$quoteSG_id);
                    }
                // END
            }
            
        }
            
    }

    //Download file pdf new
    $url = 'https://crm.solargain.com.au/APIv2/quotes/' .$quoteSG_id .'/pdf?random=0.28232019025497257';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = "Connection: keep-alive";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "Authorization: Basic ".base64_encode( $GLOBALS['username'] . ":" .   $GLOBALS['password']);
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    $decode_result = json_decode($result,true);
    
    $quote = new AOS_Quotes();
    $quote = $quote->retrieve($quote_id);
    if($quote->id !=''){
        $generate_ID = $quote->pre_install_photos_c;
        $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;

        if(!file_exists ( $folder )) {
            mkdir($folder);
        }

        date_default_timezone_set('Australia/Melbourne');
        $dateAUS = date('d_M_Y', time());
        //save pdf file
        $file = $folder.'/Quote_#'.$quoteSG_id ."_" .$dateAUS .".pdf";
        if($file!=''){
            file_put_contents($file, base64_decode($decode_result['Data']));
        }
    }
    die();
//END
?>