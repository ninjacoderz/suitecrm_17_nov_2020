<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');

$curl = curl_init();
$tmpfname = dirname(__FILE__).'/cookiesolargain.txt';

$username = "matthew.wright";
$password =  "MW@pure733";

$quote_id = $_GET['quoteSG_ID'];
$specialNotes = urldecode($_GET['specialNotes']);
$quoteDate = urldecode($_GET['quoteDate']);

// thienpb code check status is converted to order
    $lead_id = $_GET['record'];
    $lead =  new Lead();
    $lead = $lead->retrieve($lead_id);

    if($lead->id){
        if($lead->solargain_quote_number_c == $quote_id){
            $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
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
            curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
                    "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
                    "Cache-Control: max-age=0"
                )
            );

            $quote = curl_exec($curl);
            curl_close($curl);
            $quote_decode = json_decode($quote);
            if(!isset($quote_decode)) die();
            if($quote_decode->Status->Description == "Converted To Order") die();
        }else{
            die();
        }
    }else{
        die();
    }
//end thienpb code

if(isset($specialNotes )&&$specialNotes != ""){
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
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
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$result,
            "Cache-Control: max-age=0"
        )
    );
    
    $quote = curl_exec($curl);
    $quote_decode = json_decode($quote);
    if(!isset($quote_decode)) die();
    $quote_decode -> SpecialNotes = $specialNotes;
    $quote_encode =  json_encode( $quote_decode);
    
    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote_encode);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Content-Length: " .strlen($quote_encode),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
        )
    );
    $result = curl_exec($curl);
    die();
}

//dung code - upload field quote date (in quote details) and date (in next action date)
if(isset($quoteDate )&&$quoteDate != ""){
    $date = DateTime::createFromFormat('d/m/Y H:i', $quoteDate);
    $date_nextActionDate= DateTime::createFromFormat('d/m/Y H:i', $quoteDate);
    $date_nextActionDate->add(new DateInterval('P7D'));// add 7 days

    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
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
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$result,
            "Cache-Control: max-age=0"
        )
    );
    
    $quote = curl_exec($curl);
    $quote_decode = json_decode($quote);
    if(!isset($quote_decode)) die();
    // push data 
    $quote_decode->Date->Date = $date->format('d/m/Y');
    $quote_decode->Date->Time = $date->format('g:i A');
    $quote_decode->NextActionDate->Date = $date_nextActionDate->format('d/m/Y');
    $quote_decode->NextActionDate->Time = $date_nextActionDate->format('g:i A');
    $quote_encode =  json_encode( $quote_decode);
    
    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote_encode);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Content-Length: " .strlen($quote_encode),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
        )
    );
    $result = curl_exec($curl);
    die();
}

//$quote_id = "78974";
$url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);


curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
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
        "Connection: keep-alive",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$result,
        "Cache-Control: max-age=0"
    )
);

$quote = curl_exec($curl);
curl_close($curl);
$quote_decode = json_decode($quote);
$decode_result = json_decode($quote,true);

//Thienpb code - Update option sg (solargain PV Pricing, and travel)
$data_option_string = $quote_decode;
$st = urldecode($_GET['state']);
$sgPrices = array("VIC"=> array("option1"=>7490,"option2"=>8590,"option3"=>10690,'option4'=>9790,"option5"=>11390,'option6'=>14590),
                  "SA" => array("option1"=>6890,"option2"=>7790,"option3"=>9490,'option4'=>8990,"option5"=>10590,'option6'=>13290),
                  "NSW"=> array("option1"=>7290,"option2"=>8290,"option3"=>9990,'option4'=>9690,"option5"=>10990,'option6'=>14490),
                  "ACT"=> array("option1"=>7890,"option2"=>8790,"option3"=>10590,'option4'=>9990,"option5"=>11590,'option6'=>14490),
                  "QLD"=> array("option1"=>6390,"option2"=>7290,"option3"=>8990,'option4'=>8690,"option5"=>9990,'option6'=>12990));

for($i=0;$i<count($data_option_string->Options);$i++){
    $data_option_string->Options[$i]->Travel = $_GET["travel_km_".($i+1)];
    //$data_option_string->Options[$i]->Finance->PPrice =  ($_GET["price_option_".($i+1)] != "")?$_GET["price_option_".($i+1)]:$sgPrices[$st]['option'.($i+1)];
    if($_GET['number_double_storey_panel_'.($i+1)] == 0){
        $data_option_string->Options[$i]->AdditionalCableRun = 0;
    }else{
        $data_option_string->Options[$i]->AdditionalCableRun = 1;
    }
    $data_option_string->Options[$i]->ExcessHeightPanels = $_GET['number_double_storey_panel_'.($i+1)];
    $data_option_string->Options[$i]->Splits =  $_GET['groups_of_panels_'.($i+1)];
}

//dung code - update field NextActionDate = today + 30days
$data_option_string->NextActionDate = array(
    "Date" => date('d/m/Y', time() + 30*24*60*60),
    "Time"=> "9:00 AM"
);

$data_option_string = json_encode($data_option_string);

$curl = curl_init();
$url = "https://crm.solargain.com.au/APIv2/quotes/";
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data_option_string);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
        "Content-Length: " .strlen($data_option_string),
        "Origin: https://crm.solargain.com.au",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
    )
);
$result = curl_exec($curl);
curl_close($curl);
//end

$install = $quote_decode->Install->ID;
$data = array(
    "ID"=>$install,
    "AccountHolderDateOfBirth" => array(
        "Date" => "01/01/1977"
    ),
    "Address" => array(
        "Street1" =>	urldecode($_GET['primary_address_street']),
        "Street2"	=> "",
        "Locality" =>	urldecode($_GET['primary_address_city']),
        "State"	=> urldecode($_GET['state']),
        "PostCode" =>	urldecode($_GET['postalcode'])
    ),
    "RoofType" =>	urldecode($_GET['roof_type']), //roof_type,
    "Notes" => array(array(
        "ID" => 0,
    )),
    "BuildHeight" => array(
        "ID" =>	urldecode($_GET['build_height']),
    ),
    "MainsTypeID"	=> urldecode($_GET['main_type']),
    "MeterNumber"	=> urldecode($_GET['meter_number']),
    "MeterPhase"=> urldecode($_GET['meter_phase']),
    "AccountNumber" =>	urldecode($_GET['account_number']),
    "BillingName"	=> urldecode($_GET['billing_name']),
    "EnergyRetailer" => array(
        "ID" => urldecode($_GET['energy_retailer']),
    ),
    "NetworkOperator" => array(
        "ID" => urldecode($_GET['distributor']),
    ),
);
if(urldecode($_GET['nmi_number']) !== ""){
    $data["NMINumber"]	= urldecode($_GET['nmi_number']);
}
if(urldecode($_GET['connection_type']) == 'Semi_Rural_Remote_Meter'){
    $data['ConnectionType'] = 'Semi Rural/Remote Meter';
}else{
    $data['ConnectionType'] =urldecode($_GET['connection_type']);
}

$data_string = json_encode($data);

$url = 'https://crm.solargain.com.au/APIv2/installs';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Origin: https://crm.solargain.com.au";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en";
$headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
$headers[] = "Content-Type: application/json";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Cache-Control: no-cache";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
$headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
$headers[] = "Connection: keep-alive";
$headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


$installer = curl_exec($curl);
curl_close($curl);

$customer_id = $quote_decode->Customer->ID;
$custommer_type = urldecode($_GET['customer_type']);
$data = array(
    "ID"=>$customer_id,
    "CustomerTypeID" => $custommer_type,
    "LastName" => htmlspecialchars_decode(urldecode($_GET['last_name']),ENT_QUOTES),
    "FirstName" => htmlspecialchars_decode(urldecode($_GET['first_name']),ENT_QUOTES),
    "Phone"	=> urldecode($_GET['phone_work']),
    "Mobile" => urldecode($_GET['phone_mobile']),
    "Email" =>	urldecode($_GET['email']),
    "Address" => array(
        "Street1" =>	urldecode($_GET['primary_address_street']),
        "Street2"	=> "",
        "Locality" =>	urldecode($_GET['primary_address_city']),
        "State"	=> urldecode($_GET['state']),
        "PostCode" =>	urldecode($_GET['postalcode'])
    ),
    "OptIn" => true,
    "Notes" => array(array(
        "ID" => 0,
    )),
);

$data_string = json_encode($data);

$url = "https://crm.solargain.com.au/APIv2/customers/";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Pragma: no-cache";
$headers[] = "Origin: https://crm.solargain.com.au";
$headers[] = "Accept-Encoding: gzip, deflate, br";
$headers[] = "Accept-Language: en";
$headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
$headers[] = "Content-Type: application/json";
$headers[] = "Accept: application/json, text/plain, */*";
$headers[] = "Cache-Control: no-cache";
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
$headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
$headers[] = "Connection: keep-alive";
$headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($curl);
curl_close($curl);

$record = urldecode($_GET['record']);
$bean = BeanFactory::getBean("Leads", $record);

$bean->first_name = htmlspecialchars_decode(urldecode($_GET['first_name']),ENT_QUOTES);
$bean->last_name =htmlspecialchars_decode(urldecode($_GET['last_name']),ENT_QUOTES);
$bean->primary_address_street = urldecode($_GET['primary_address_street']);
$bean->primary_address_city = urldecode($_GET['primary_address_city']);
$bean->primary_address_state = urldecode($_GET['state']);
$bean->primary_address_postalcode = urldecode($_GET['postalcode']);
$bean->customer_type_c = $custommer_type;

$bean->roof_type_c = urldecode($_GET['roof_type']);
$bean->gutter_height_c = urldecode($_GET['gutter_height']);
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


//dung code - upload file for Citipower Powercor push sogargain
if($bean->meter_number_c !== '' && ($bean->distributor_c == '4' || $bean->distributor_c == '7' || $bean->distributor_c == '6')){
    $folder_pdf = dirname(__FILE__)."/server/php/files/".$bean->installation_pictures_c;

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
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_exist);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Origin: https://crm.solargain.com.au";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Cache-Control: no-cache";
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);

                $ch = curl_init();
    
                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        
                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: en-US,en;q=0.9";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);
            }
        }

        //upload new file Citipower Powercor
        $content_file =  file_get_contents($folder_pdf.'/'.$filename_pdf);
        $ch = curl_init();
        $data_file_upload = array(
            'Data'     => base64_encode($content_file),
            'Filename' => $filename_pdf,
            'Title'    => $filename_pdf,
            'Url'      => "",
        );
        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/" .$quote_id."/upload");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data_file_upload) );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://crm.solargain.com.au";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/" .$quote_id;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
        $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $decode_result_files_image_upload = json_decode($result,true);

        //dung code - need add field Category = "RETAILER APPROVAL" 
        if(strpos($filename_pdf, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {

            foreach ($decode_result_files_image_upload as $value){
                if($value['Filename'] == $filename_pdf){
                    $id_file_image_meterbox = $value['ID'];
                }
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_meterbox ."/category/6");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
            $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Cache-Control: no-cache";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);
        }
                    
    }
}

//dung code - upload all file image into solargain
if($bean->installation_pictures_c !== '') {
    $folder_ = dirname(__FILE__)."/server/php/files/".$bean->installation_pictures_c ."/";
    $files = scandir($folder_);

    foreach($files as $file) {
        $file_type = strtolower(substr($file,-4));
        $file_type = str_replace(".","",$file_type);
        if($file_type == 'png' || $file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'gif'){
            
            $content_file =  file_get_contents($folder_.'/'.$file);
            //DUNG CODE -DELETE file image exist
                foreach ($decode_result['Files'] as $value) {
                    if($value['Filename'] == $file) {
                        $id_file_image_exist = $value['ID'];
                        $ch = curl_init();
    
                        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_exist);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            
                        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
                        $headers = array();
                        $headers[] = "Pragma: no-cache";
                        $headers[] = "Origin: https://crm.solargain.com.au";
                        $headers[] = "Accept-Encoding: gzip, deflate, br";
                        $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                        $headers[] = "Accept: application/json, text/plain, */*";
                        $headers[] = "Cache-Control: no-cache";
                        $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                        $headers[] = "Connection: keep-alive";
                        $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close ($ch);
        
                        $ch = curl_init();
            
                        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                
                        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
                        $headers = array();
                        $headers[] = "Pragma: no-cache";
                        $headers[] = "Accept-Encoding: gzip, deflate, br";
                        $headers[] = "Accept-Language: en-US,en;q=0.9";
                        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                        $headers[] = "Accept: application/json, text/plain, */*";
                        $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                        $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                        $headers[] = "Connection: keep-alive";
                        $headers[] = "Cache-Control: no-cache";
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close ($ch);
                    }
                }

            //DUNG CODE - Push all Image to solargain
            $ch = curl_init();
            $data_file_upload = array(
                'Data'     => base64_encode($content_file),
                'Filename' => $file,
                'Title'    => $file,
                'Url'      => "",
            );
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/" .$quote_id."/upload");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data_file_upload) );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Origin: https://crm.solargain.com.au";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en-US,en;q=0.9";
            $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
            $headers[] = "Content-Type: application/json";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Cache-Control: no-cache";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/" .$quote_id;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);
    
            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en-US,en;q=0.9";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
            $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Cache-Control: no-cache";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch); 

            $decode_result_files_image_upload = json_decode($result,true);

            //dung code - upload file meter box need add field Category = ""mextabog" 
            if(strpos($file, 'Meter_Box') !== false) {

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_meterbox = $value['ID'];
                    }
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_meterbox ."/category/3");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);
            }

            if(strpos($file, 'Acceptance') !== false) {

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_meterbox = $value['ID'];
                    }
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_meterbox ."/category/1");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);
            }

            if(strpos($file, 'Switchboard') !== false) {

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_meterbox = $value['ID'];
                    }
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_meterbox ."/category/2");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);
            }

            //thienpb code -- add bill to tag electricity bill
            if(strpos($file, 'Bill') !== false) {

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_bill = $value['ID'];
                    }
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_bill ."/category/12");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);
            }
        }
        
    }
        
}

// dung code - download file pdf new

$url = 'https://crm.solargain.com.au/APIv2/quotes/' .$quote_id .'/pdf?random=0.28232019025497257';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = "Connection: keep-alive";
$headers[] = "Pragma: no-cache";
$headers[] = "Cache-Control: no-cache";
$headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
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

$lead = new Lead();
$lead = $lead->retrieve($record);
if($lead->id !=''){
    $generate_ID = $lead->installation_pictures_c;
    $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;

    if(!file_exists ( $folder )) {
        mkdir($folder);
    }
    date_default_timezone_set('Australia/Melbourne');
    $dateAUS = date('d_M_Y', time());
    //save pdf file
    $file = $folder.'/Quote_#'.$quote_id ."_" .$dateAUS .".pdf";
    if($file!=''){
        file_put_contents($file, base64_decode($decode_result['Data']));
    }
}
die();
