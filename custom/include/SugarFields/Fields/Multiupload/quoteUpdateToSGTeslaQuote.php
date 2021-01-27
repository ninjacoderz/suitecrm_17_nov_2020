<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
require_once(dirname(__FILE__).'/simple_html_dom.php');

//get parameter from ajax
    $quoteSG_id = $_GET['tesla_quote_id'];
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
        $GLOBALS['password'] = 'S0larga1n$';
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
            $GLOBALS['password'] = 'S0larga1n$';
        }
        
        //get data from SG quote
            $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
            $quoteSG = curlSG('GET','',$url,$quoteSG_id);
            $quote_decode = json_decode($quoteSG);
            $decode_result = json_decode($quoteSG,true);
        //END
    }
//END

//check status is converted to order
    if($quote->id){
        if($quote->solargain_tesla_quote_number_c == $quoteSG_id){
            if(!isset($quote_decode)) die();
            if($quote_decode->Status->Description == "Converted To Order") die();
        }else{
            die();
        }
    }else{
        die();
    }
//END


//Thienpb code - Update option sg (solargain info)

    $quote_decode->NextActionDate = array(
        "Date" => date('d/m/Y', time() + 30*24*60*60),
        "Time"=> "9:00 AM"
    );

//Thienpb code for dynamic accessories
    $options = $quote_decode->Options;

    $accessories = array();
    $accessory = array();

    $price_tesla = 13990;
    $meter_phase = ($_GET['meter_phase']) ? $_GET['meter_phase'] : '1';
    if($meter_phase == '3'){
    $price_tesla += 500;//bonus 500
    }

    $sg_inverter_model = $_GET['solargain_inverter_model'];
    if($meter_phase == '1'){
        $accessory = 
        array (
            'ID' => 375,
            'Code' => 'Tesla Powerwall 2 AC 1P SITE /1P PV Kit',
            'Category' => 
            array (
            'ID' => 1,
            'Code' => 'BATTERY',
            'Name' => 'Battery',
            'Order' => 2,
            ),
            'Manufacturer' => 
            array (
            'ID' => 46,
            'Name' => 'Tesla',
            'ValidForPanels' => false,
            'ValidForInverters' => false,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => false,
            ),
            'Model' => 'Tesla Powerwall 2 AC 1P SITE /1P PV Kit',
            'DisplayOnQuote' => true,
            'Warranty' => '10 Years',
            'ExoCode' => 'P-PW2-AC',
            'PurchaseOrderExoCode' => 'P-BATTERY-INSTL-TES',
            'Active' => true,
            'Battery' => 
            array (
            'StorageCapacity' => '13.2 kWh',
            'UsableCapacity' => '13.2 kWh',
            'ChargeDischargeRate' => '5 kW',
            'Type' => 'Li',
            'MountingType' => 'Wall/Floor',
            ),
            'Kit' => true,
            'Accessories' => 
            array (
            ),
        );

        $accessories = 
        array (
            0 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 30,
                'Code' => 'Tesla Powerwall 2 AC Backup Gateway',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Powerwall 2 Backup Gateway',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-PW2-AC-GWY-BACKUP',
                'Kit' => false,
                ),
            ),
        );

        $accessory['Accessories'] = $accessories;
    }else if($meter_phase == '3' && $sg_inverter_model == 'Fronius_Primo'){
        $accessory = 
        array (
            'ID' => 377,
            'Code' => 'Tesla Powerwall 2 AC 3P SITE /1P PV Kit',
            'Category' => 
            array (
            'ID' => 1,
            'Code' => 'BATTERY',
            'Name' => 'Battery',
            'Order' => 2,
            ),
            'Manufacturer' => 
            array (
            'ID' => 46,
            'Name' => 'Tesla',
            'ValidForPanels' => false,
            'ValidForInverters' => false,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => false,
            ),
            'Model' => 'Tesla Powerwall 2 AC 3P SITE /1P PV Kit',
            'DisplayOnQuote' => true,
            'Warranty' => '10 Years',
            'ExoCode' => 'P-PW2-AC',
            'PurchaseOrderExoCode' => 'P-BATTERY-INSTL-TES',
            'Active' => true,
            'Battery' => 
            array (
            'StorageCapacity' => '13.2 kWh',
            'UsableCapacity' => '13.2 kWh',
            'ChargeDischargeRate' => '5 kW',
            'Type' => 'Li',
            'MountingType' => 'Wall/Floor',
            ),
            'Kit' => true,
            'Accessories' => 
            array (
            ),
        );

        $accessories = 
        array (
            0 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 376,
                'Code' => 'Tesla Neurio set of 2 CT 200A',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Neurio set of 2 CT 200A',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-TESLA-2CT-200A',
                'Kit' => false,
                ),
            ),
            1 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 30,
                'Code' => 'Tesla Powerwall 2 AC Backup Gateway',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Powerwall 2 Backup Gateway',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-PW2-AC-GWY-BACKUP',
                'Kit' => false,
                ),
            ),
        );

        $accessory['Accessories'] = $accessories;
    }else if($meter_phase == '3' && $sg_inverter_model == 'Fronius_Symo'){
        $accessory = 
        array (
            'ID' => 380,
            'Code' => 'Tesla Powerwall 2 AC 3P SITE /3P PV Kit',
            'Category' => 
            array (
            'ID' => 1,
            'Code' => 'BATTERY',
            'Name' => 'Battery',
            'Order' => 2,
            ),
            'Manufacturer' => 
            array (
            'ID' => 46,
            'Name' => 'Tesla',
            'ValidForPanels' => false,
            'ValidForInverters' => false,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => false,
            ),
            'Model' => 'Tesla Powerwall 2 AC 3P SITE /3P PV Kit',
            'DisplayOnQuote' => true,
            'Warranty' => '10 Years',
            'ExoCode' => 'P-PW2-AC',
            'Active' => true,
            'Battery' => 
            array (
            'StorageCapacity' => '13.2 kWh',
            'UsableCapacity' => '13.2 kWh',
            'ChargeDischargeRate' => '5 kW',
            'Type' => 'Li',
            'MountingType' => 'Wall/Floor',
            ),
            'Kit' => true,
            'Accessories' => 
            array (
            ),
        );
                    
        $accessories = 
        array (
            0 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 379,
                'Code' => 'Tesla Neurio Meter RS485 Cable',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Neurio Meter RS485 Cable',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-TESLA-MET-RS485',
                'Kit' => false,
                ),
            ),
            1 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 30,
                'Code' => 'Tesla Powerwall 2 AC Backup Gateway',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Powerwall 2 Backup Gateway',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-PW2-AC-GWY-BACKUP',
                'Kit' => false,
                ),
            ),
            2 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 378,
                'Code' => 'Tesla Neurio Meter inc 2 CT 200A',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Neurio Meter inc 2 CT 200A',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-TESLA-MET-2CT-200A',
                'Kit' => false,
                ),
            ),
            3 => 
            array (
                'ID' => 0,
                'Quantity' => 1,
                'UnitPrice' => 0,
                'Included' => true,
                'DisplayOnQuote' => false,
                'Accessory' => 
                array (
                'ID' => 376,
                'Code' => 'Tesla Neurio set of 2 CT 200A',
                'Manufacturer' => 
                array (
                    'ID' => 46,
                    'Name' => 'Tesla',
                    'ValidForPanels' => false,
                    'ValidForInverters' => false,
                    'ValidForAccessories' => true,
                    'ValidForHotWaterSystems' => false,
                ),
                'Model' => 'Tesla Neurio set of 2 CT 200A',
                'DisplayOnQuote' => false,
                'ExoCode' => 'P-TESLA-2CT-200A',
                'Kit' => false,
                ),
            ),
        );
        
        $accessory['Accessories'] = $accessories;
    }

    foreach ($options[0]->Accessories as $key => $value) {
        if($options[0]->Accessories[$key]->Accessory->ID == 375 || $options[0]->Accessories[$key]->Accessory->ID == 380 || $options[0]->Accessories[$key]->Accessory->ID == 377) {
            unset($options[0]->Accessories[$key]->Accessory);
            $options[0]->Accessories[$key]->Accessory = $accessory;
            $options[0]->Accessories[$key]->UnitPrice = $price_tesla;
        }
    }

    $quote_decode->Options = $options;

    //check VIC
    if ($st == 'VIC') {
        for($i=0;$i<count($quote_decode->Options);$i++){
            $quote_decode->Options[$i]->Finance->Rebate = array(
                "ID" => 9,
                "Code" => "SOLARVB41",
                "Name" => "Solar VIC Battery $4174 Rebate",
                "EXOSystemCode" => "P-PV SYSTEM",
                "Active" => true,
                "FileCategories" => array()
            );        
            $quote_decode->Options[$i]->Finance->RebateAmount = 4174.0;
        }
    }

    $data_option_string = json_encode($quote_decode);

    $url = 'https://crm.solargain.com.au/APIv2/quotes/';
    $quoteSG = curlSG('POST',$data_option_string,$url,$quoteSG_id);

// END

//thienpb fix Upload all file image into solargain

if($quote->pre_install_photos_c !== '') {
    $folder_ = dirname(__FILE__)."/server/php/files/".$quote->pre_install_photos_c ."/";
    $files = scandir($folder_);

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

            //Push all Image to solargain
            $ch = curl_init();
            $data_file_upload = array(
                'Data'     => base64_encode($content_file),
                'Filename' => $file,
                'Title'    => $file,
                'Url'      => "",
            );
            $data_tring_upload = json_encode($data_file_upload);

            //upload file meter box need add field Category = ""mextabog" 
            if(strpos($file, 'Meter_Box') !== false) {

                //PUSH
                $url = "https://crm.solargain.com.au/APIv2/quotes/" .$quoteSG_id."/upload";
                curlSG('POST',$data_tring_upload,$url,$quoteSG_id);
                
                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                $result = curlSG('GET','',$url,$quoteSG_id);
                $decode_result_files_image_upload = json_decode($result,true);

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_meterbox = $value['ID'];
                    }
                }

                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_meterbox ."/category/3";
                curlSG('GET','',$url,$quoteSG_id);
                
            }

            if(strpos($file, 'Switchboard') !== false) {

                //PUSH
                $url = "https://crm.solargain.com.au/APIv2/quotes/" .$quoteSG_id."/upload";
                curlSG('POST',$data_tring_upload,$url,$quoteSG_id);
                
                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                $result = curlSG('GET','',$url,$quoteSG_id);
                $decode_result_files_image_upload = json_decode($result,true);

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_Switchboard = $value['ID'];
                    }
                }

                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_Switchboard ."/category/2";
                curlSG('GET','',$url,$quoteSG_id);
                
            }

            //thienpb code -- add bill to tag electricity bill
            if(strpos($file, 'Bill') !== false) {

                //PUSH
                $url = "https://crm.solargain.com.au/APIv2/quotes/" .$quoteSG_id."/upload";
                curlSG('POST',$data_tring_upload,$url,$quoteSG_id);
                
                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files";
                $result = curlSG('GET','',$url,$quoteSG_id);
                $decode_result_files_image_upload = json_decode($result,true);

                foreach ($decode_result_files_image_upload as $value){
                    if($value['Filename'] == $file){
                        $id_file_image_bill = $value['ID'];
                    }
                }

                //GET
                $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteSG_id."/files/" .$id_file_image_bill ."/category/12";
                curlSG('GET','',$url,$quoteSG_id);
                
            }
        }
        
    }
        
}
//end

// dung code - download file pdf new
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
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);

    $decode_result = json_decode($result,true);

    if($quote->id !=''){
        $generate_ID = $quote->pre_install_photos_c;
        $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;

        if(!file_exists ( $folder )) {
            mkdir($folder);
        }

        date_default_timezone_set('Australia/Melbourne');
        $dateAUS = date('d_M_Y', time());
        //save pdf file
        $file = $folder.'/Tesla_Quote_#'.$quoteSG_id ."_" .$dateAUS .".pdf";
        if($file!=''){
            file_put_contents($file, base64_decode($decode_result['Data']));
        }
    }
die();
