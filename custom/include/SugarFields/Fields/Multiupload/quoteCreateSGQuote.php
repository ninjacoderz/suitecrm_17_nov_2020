<?php
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    require_once(dirname(__FILE__).'/simple_html_dom.php');

    $tmpfname = dirname(__FILE__).'/cookiesolargain.txt';

    global $current_user;
    $username = $password = "";
    $GLOBALS['data_return'] = [];

    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'S0larga1n$';
    }

    function calc_panel($totalPanel,$min,$max,$line,$groupmax,$index=0){
        $type= '';
        for($i = $max; $i >= $min ; $i--){
            $arrLine1 = [];
            $arrLine2 = [];
            for($j = 0; $j < $line[0] ;$j++){
                if($j<=$index)
                    $arrLine1[$j] = $i;
            }
            $count = $line[1];
            $res = $totalPanel - array_sum($arrLine1);
    
            if($res % $count != 0){
                $count--;
                $type = "false";
                continue;
            }else{
                
                if($res/$count > $max || $res/$count < $min){
                    $type = "false";
                    continue;
                }else{
                    if($res > $groupmax ){
                        $type = "false";
                        continue;
                    }
                    for($k = 0; $k < $line[1] ; $k++){
                        $arrLine2[$k] = $res/$count;
                    }
                    $type = array('type'=>'OK','SuggestTotalPanel'=>$totalPanel,'panelConfig'=>array($arrLine1,$arrLine2));
                break;
                }
            }
        }
    
        if(  $type == "false" &&  $index+1 < $line[0]){
            $index++;
            $type = calc_panel($totalPanel,$min,$max,$line,$groupmax,$index);
        }else if ( $type == "false" &&  $index+1 == $line[0]){
            $totalPanel--;
            $type = calc_panel($totalPanel,$min,$max,$line,$groupmax);
        }
        
        return $type;
    }

    function return_message($quote,$specialMess=''){

        if($specialMess != ''){
            $GLOBALS['data_return']['SG_error'] = $specialMess;
            echo json_encode($GLOBALS['data_return']);
            die();
        }
        $message_return = '';
    
        $patten_err  = '/"ExceptionMessage":"(.*?)","ExceptionType"/';
        preg_match($patten_err , $quote, $matches);
    
        if(isset($matches) && $matches[1] != ''){
            if(strpos($matches[1],"User does not have view rights to view quote") !== false){
    
            }else{
                $message_return = $matches[1];
                $GLOBALS['data_return']['SG_error'] = str_replace("\\","",$message_return);
                echo json_encode($GLOBALS['data_return']);
                die();
            }
        }else{
            $patten_err  = '/"Message":"(.*?)","MessageDetail"/';
            preg_match($patten_err , $quote, $matches);
            if(isset($matches) && $matches[1] != ''){
                $message_return = $matches[1];
                $GLOBALS['data_return']['SG_error'] = str_replace("\\","",$message_return);
                echo json_encode($GLOBALS['data_return']);
                die();
            }
        }
        
        echo json_encode($GLOBALS['data_return']);
    }

    if($_GET['process'] == 'lead'){
        //get parameter from ajax
            $quote_id = $_GET['record'];
            $st = urldecode($_GET['state']);
        //END

        //set the url, number of POST vars, POST data
            $custommer_type = urldecode($_GET['customer_type']);
        //END

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
                $phone_work = str_replace(' ','',$lead->phone_work);
                $phone_mobile = str_replace(' ','',$lead->phone_mobile);
                $email_lead = $lead->email1;
            }
        //END

        //
            $data = array(
                "CustomerTypeID" => $custommer_type, //last_name
                "LastName" => $last_name,
                "FirstName" => $first_name,
                "TradingName" => "Trading Name",
                "ABN" =>	"ABN",
                "Phone"	=> $phone_work ? $phone_work : '',
                "Mobile" => $phone_mobile ? $phone_mobile : '',
                "Email" =>	$email_lead ? $email_lead : '',
                "Address" => array(
                    "Street1"	=> urldecode($_GET['billing_address_street']),//
                    "Street2"	=> "",
                    "Locality" =>	urldecode($_GET['billing_address_city']),
                    "State" => 	urldecode($_GET['state']),
                    "PostCode"	=> urldecode($_GET['postalcode']) //postalcode
                ),
                "Category" => array(
                    "Value" => 1,
                ),
                "OptIn" => true,
                "Notes" => array(array(
                    "ID" => 0,
                )),
            );

            $data_string = json_encode($data);
            
            $url = 'https://crm.solargain.com.au/APIv2/customers/';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl,CURLOPT_ENCODING , "gzip");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
                    "Content-Length: " .strlen($data_string),
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/Lead/Create",
                )
            );

            $custommer = json_decode(curl_exec($curl));

        // Pushing the sites

        $url = 'https://crm.solargain.com.au/APIv2/installs';

        //set the url, number of POST vars, POST data
        $data = array(
            "AccountHolderDateOfBirth" => array(
                "Date" => "01/01/1977"
            ),

            "Address" => array(
                "Street1" =>	urldecode($_GET['billing_address_street']),
                "Street2"	=> "",
                "Locality" =>	urldecode($_GET['billing_address_city']),
                "State"	=> urldecode($_GET['state']),
                "PostCode" =>	urldecode($_GET['postalcode'])
            ),
            "RoofType" =>array("ID" =>urldecode($_GET['roof_type'])), //roof_type,
            "Notes" => array(array(
                "ID" => 0,
            )),
            "BuildHeight" => array(
                "ID" =>	urldecode($_GET['build_height']),
            ),
            "MainsTypeID"	=> urldecode($_GET['main_type']),
            "ConnectionType" =>	urldecode($_GET['connection_type']),
            "MeterNumber"	=> urldecode($_GET['meter_number']),
            "MeterPhase" => 1,
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
        $data_string = json_encode($data);


        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
                "Content-Length: " .strlen($data_string),
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/Lead/Create",
            )
        );

        $installer = json_decode(curl_exec($curl));

        $primary_address_state = urldecode($_GET['state']);
        $primary_address_name = "PERTH";
        $primary_address_id = 1;
        if($primary_address_state == "WA State"){
            $primary_address_name = "PERTH";
            $primary_address_id= 1;
        }
        if ($primary_address_state == "VIC"){
            $primary_address_name = "VIC";
            $primary_address_id= 3;
        }
        if ($primary_address_state == "QLD"){
            $primary_address_name = "QLD";
            $primary_address_id= 4;
        }
        if ($primary_address_state == "NSW"){
            $primary_address_name = "SYDNEY";
            $primary_address_id= 9;
        }

        if ($primary_address_state == "SA"){
            $primary_address_name = "SOUTH AUSTRALIA";
            $primary_address_id= 16;
        }
        if ($primary_address_state == "ACT"){
            $primary_address_name = "ACT";
            $primary_address_id= 2;
        }
        if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
            $assigneduser = array(
                "ID" => 475,
                "Name" => "Matthew Wright",
                "Enabled"=>false,
                "Administrator"=>false,
                "IsDealership"=>false
            );
        }else{
            $assigneduser = array(
                "ID" => 730,
                "Name" => "Paul Szuster",
                "Enabled"=>false,
                "Administrator"=>false,
                "IsDealership"=>false
            );
        }
        $data = array(
            "ID" => 0,
            "Status" => "New",
            "IsLost" => false,
            "IsConverted" => false,
            "Created" => "0001-01-01T00:00:00",
            "RoofType" =>array("ID" =>urldecode($_GET['roof_type'])),
            "AssignedUser" => $assigneduser,
            "AssignedUnit" => array(
                "ID" => $primary_address_id,
                "Name"=>$primary_address_name,
                "RailLength" => 0,
                "IsDealership" => false,
                "OrdersEMail"=> "sg.orders@solargain.com.au",
                "HotWaterOrdersEMail" => "sg.shw.orders@solargain.com.au",
                "RequiresDesignApproval" => false
            ),
            "NextActionDate" => array (
                "Date" => date('d/m/Y', time() + 24*60*60),
                "Time"=>"9:00 AM"
            ),
            "NextActionDateDays"=> 0,
            "LastActionDateDays"=>0,
            "EMails"=>0,
            "Calls"=>0,
            "Editable"=>true,
            "Notes"=>array(
                array(
                    "ID"=>0,
                    "Text"=>urldecode($_GET['notes']),
                    "Type"=> array(
                        "ID"=>1,
                        "Name"=>"General",
                        "RequiresComment"=>true
                    )
                )
            ),
            "Errors"=> array(),
            "Customer" => $custommer,
            "Install" =>$installer,
            "Source"=> array(
                "ID" => 0,
                "Description" => "Beyond the Grid",
                "Category" => array(
                    "ID" =>5,
                    "Description" =>"3rd Party Partners",
                    "Order" => 5,
                    "Units" => array()
                ),
                "Active" =>true,
                "Default" =>false,
                "Order" =>8,
                "StatusReport" =>false,
            ),
            "SystemType" => "PV",
            "SystemSize"=>urldecode($_GET['system_size']),
            "UnitsPerDay"=>urldecode($_GET['unit_per_day']),
            "DollarsPerMonth"=>urldecode($_GET['dolar_month']),
            "NumberOfPeople"=>urldecode($_GET['number_of_people']),
        );

        $data_string = json_encode($data);

        $url = 'https://crm.solargain.com.au/APIv2/leads/';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.8,vi;q=0.6",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string),
                "Origin: https://crm.solargain.com.au",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/Lead/Create",
            )
        );
        $result = curl_exec($curl);
        //$result = json_encode(curl_exec($curl));

        echo $result;

        $record = urldecode($_GET['record']);
        $bean = BeanFactory::getBean("AOS_Quotes", $record);
        $bean->solargain_lead_number_c = $result;
        $bean->save();

        die();
    }else if($_GET['process'] == 'quote'){

        //Check set account sg

            $SGleadID = $_GET['SGleadID'];
            if($SGleadID != ''){
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/leads/'.$SGleadID);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Host: crm.solargain.com.au",
                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                        "Content-Type: application/json",
                        "Accept: application/json, text/plain, */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: 	gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Cache-Control: max-age=0"
                    )
                );

                $result = curl_exec($ch);
                curl_close ($ch);
                
                $decode_result = json_decode($result);
                if(!isset($decode_result->ID)){
                    die();
                }
                if($decode_result->AssignedUser->EMail == 'matthew.wright@solargain.com.au'){
                    $username = "matthew.wright";
                    $password =  "MW@pure733";
                }else{
                    $username = 'paul.szuster@solargain.com.au';
                    $password = 'S0larga1n$';
                }
            }else{
                die;
            }
        //END
        $url = 'https://crm.solargain.com.au/APIv2/quotes/create/'.$SGleadID;

        //set the url, number of POST vars, POST data

        $data = array(

        );

        $data_string = json_encode($data);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
                "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$SGleadID,
                "Cache-Control: max-age=0"
            )
        );

        $quote = curl_exec($curl);
        // We need update Install detail
        $decode_result = json_decode($quote,true);
        $install_info = $decode_result["Install"];

        $install_info["Address"]["Street1"]  = urldecode($_GET['billing_address_street']);
        $install_info["Address"]["State"]  = urldecode($_GET['billing_address_state']);
        $install_info["Address"]["Locality"]  = urldecode($_GET['billing_address_city']);
        $install_info["Address"]["PostCode"]  = urldecode($_GET['billing_address_postalcode']);

        $install_encode =  json_encode( $install_info); // We place install encode here

        //print_r($quote);
        $curl = curl_init();
        $url = "https://crm.solargain.com.au/APIv2/quotes/";

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $quote);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
                "Content-Length: " .strlen($quote),
                "Origin: https://crm.solargain.com.au",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$SGleadID,
            )
        );
        $result = curl_exec($curl);
        //$result = json_encode(curl_exec($curl));

        //print_r($result);

        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$result;
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
        $SGquote_ID = $result;
        $quote = curl_exec($curl);
        //dung code  --- get number site detail quote and number quote solar
        $quote_decode = json_decode($quote);
        $data_result = array(
            'SiteDetailNumber' => $quote_decode->Install->ID,
            'QuoteNumber' => $quote_decode->ID,
        );

        $GLOBALS['data_return']['quote_info'] = $data_result;
        if ( $SGquote_ID !='') {
            $record = urldecode($_GET['record']);
            $bean = BeanFactory::getBean("AOS_Quotes", $record);
            $bean -> solargain_quote_number_c = $SGquote_ID;
            $bean ->sg_site_details_no_c = $data_result['SiteDetailNumber'];
            $bean->save();
        }

        //thienpb code here
        $suite_field = urldecode($_GET['suite_field']);
        $st = urldecode($_GET['billing_address_state']);;
        $sgPrices = array("VIC"=> array("option1"=>7490,"option2"=>8590,"option3"=>10690,'option4'=>9790,"option5"=>11390,'option6'=>14590),
                        "SA" => array("option1"=>6890,"option2"=>7790,"option3"=>9490,'option4'=>8990,"option5"=>10590,'option6'=>13290),
                        "NSW"=> array("option1"=>7290,"option2"=>8290,"option3"=>9990,'option4'=>9690,"option5"=>10990,'option6'=>14490),
                        "ACT"=> array("option1"=>7890,"option2"=>8790,"option3"=>10590,'option4'=>9990,"option5"=>11590,'option6'=>14490),
                        "QLD"=> array("option1"=>6390,"option2"=>7290,"option3"=>8990,'option4'=>8690,"option5"=>9990,'option6'=>12990));

        //end

        /// Resubmit Quotes with options
        // Thien fix - update json options date 1/10/2018
        $quote_decode = json_decode($quote);
        
        // $sgOptions = urldecode($_GET['sgoption']);
        // if(isset($sgOptions)) {
        //     $sgOptions = explode(",", $sgOptions);
        //     if (count($sgOptions)>0){
        //         $i = 0;
        //         foreach($sgOptions as $option){
        //             $l_option = $solargain_options[$option];
        //             $l_option['Number'] = $i;
        //             $l_option['InternalNumber'] = $i;
        //             $quote_decode ->Options[] = $l_option;
        //             $i++;
        //             //'Number' => 2,
        //             //'InternalNumber' => 2,
        //         }
        //     }
        // }
        
        //Thienpb code - new Update custom option pricing
        $sgOptions = urldecode($_GET['sgoption']);
        if(isset($sgOptions)) {
            for($i=0;$i<$sgOptions;$i++){
                $solargain_options =
                    array (
                    'Dirty' => true,
                    'Number' => $i,
                    'InternalNumber' => 0,
                    'ReValidate' => false,
                    'AddAccessories' => false,
                    'Validation' => 
                    array (
                        'Valid' => true,
                        'Errors' => 
                        array (
                        ),
                        'Warnings' => 
                        array (
                        ),
                    ),
                    'Yield' => 
                    array (
                        'Location' => 
                        array (
                        'ID' => 2,
                        'Code' => 'MEL',
                        'Name' => 'Melbourne',
                        'AverageTemperatures' => 
                        array (
                            'Annual' => 15.8375000000000003552713678800500929355621337890625,
                            'January' => 21.199999999999999289457264239899814128875732421875,
                            'February' => 21.39999999999999857891452847979962825775146484375,
                            'March' => 19.550000000000000710542735760100185871124267578125,
                            'April' => 16.60000000000000142108547152020037174224853515625,
                            'May' => 13.449999999999999289457264239899814128875732421875,
                            'June' => 10.6500000000000003552713678800500929355621337890625,
                            'July' => 10,
                            'August' => 11.1500000000000003552713678800500929355621337890625,
                            'September' => 13.25,
                            'October' => 15.5999999999999996447286321199499070644378662109375,
                            'November' => 17.60000000000000142108547152020037174224853515625,
                            'December' => 19.60000000000000142108547152020037174224853515625,
                            'Maximum' => 80,
                            'Minimum' => 0,
                        ),
                        'AverageExposures' => 
                        array (
                            'Annual' => 4.3240999999999996106225808034650981426239013671875,
                            'January' => 6.86110000000000042064129956997931003570556640625,
                            'February' => 6.08330000000000037374547900981269776821136474609375,
                            'March' => 4.83330000000000037374547900981269776821136474609375,
                            'April' => 3.3056000000000000937916411203332245349884033203125,
                            'May' => 2.25,
                            'June' => 1.8056000000000000937916411203332245349884033203125,
                            'July' => 2,
                            'August' => 2.861099999999999976552089719916693866252899169921875,
                            'September' => 3.833299999999999929656269159750081598758697509765625,
                            'October' => 5.13889999999999957935870043002068996429443359375,
                            'November' => 6.16669999999999962625452099018730223178863525390625,
                            'December' => 6.75,
                        ),
                        'Efficiencies' => 
                        array (
                            0 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            1 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 10,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            2 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 20,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            3 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 30,
                            'Efficiency' => 1,
                            ),
                            4 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 40,
                            'Efficiency' => 1,
                            ),
                            5 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 50,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            6 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 60,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            7 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 70,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            8 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 80,
                            'Efficiency' => 0.770000000000000017763568394002504646778106689453125,
                            ),
                            9 => 
                            array (
                            'Orientation' => 0,
                            'Pitch' => 90,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            10 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            11 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            12 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 20,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            13 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 30,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            14 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 40,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            15 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 50,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            16 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 60,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            17 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 70,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            18 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 80,
                            'Efficiency' => 0.770000000000000017763568394002504646778106689453125,
                            ),
                            19 => 
                            array (
                            'Orientation' => 10,
                            'Pitch' => 90,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            20 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            21 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            22 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 20,
                            'Efficiency' => 0.95999999999999996447286321199499070644378662109375,
                            ),
                            23 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 30,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            24 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 40,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            25 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 50,
                            'Efficiency' => 0.95999999999999996447286321199499070644378662109375,
                            ),
                            26 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 60,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            27 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 70,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            28 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 80,
                            'Efficiency' => 0.7600000000000000088817841970012523233890533447265625,
                            ),
                            29 => 
                            array (
                            'Orientation' => 20,
                            'Pitch' => 90,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            30 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            31 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            32 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 20,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            33 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 30,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            34 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 40,
                            'Efficiency' => 0.95999999999999996447286321199499070644378662109375,
                            ),
                            35 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 50,
                            'Efficiency' => 0.939999999999999946709294817992486059665679931640625,
                            ),
                            36 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 60,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            37 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 70,
                            'Efficiency' => 0.82999999999999996003197111349436454474925994873046875,
                            ),
                            38 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 80,
                            'Efficiency' => 0.75,
                            ),
                            39 => 
                            array (
                            'Orientation' => 30,
                            'Pitch' => 90,
                            'Efficiency' => 0.66000000000000003108624468950438313186168670654296875,
                            ),
                            40 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            41 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 10,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            42 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 20,
                            'Efficiency' => 0.939999999999999946709294817992486059665679931640625,
                            ),
                            43 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 30,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            44 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 40,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            45 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 50,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            46 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 60,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            47 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 70,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            48 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 80,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            49 => 
                            array (
                            'Orientation' => 40,
                            'Pitch' => 90,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            50 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            51 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 10,
                            'Efficiency' => 0.90000000000000002220446049250313080847263336181640625,
                            ),
                            52 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 20,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            53 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 30,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            54 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 40,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            55 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 50,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            56 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 60,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            57 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 70,
                            'Efficiency' => 0.79000000000000003552713678800500929355621337890625,
                            ),
                            58 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 80,
                            'Efficiency' => 0.70999999999999996447286321199499070644378662109375,
                            ),
                            59 => 
                            array (
                            'Orientation' => 50,
                            'Pitch' => 90,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            60 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            61 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 10,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            62 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 20,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            63 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 30,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            64 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 40,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            65 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 50,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            66 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 60,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            67 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 70,
                            'Efficiency' => 0.7600000000000000088817841970012523233890533447265625,
                            ),
                            68 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 80,
                            'Efficiency' => 0.689999999999999946709294817992486059665679931640625,
                            ),
                            69 => 
                            array (
                            'Orientation' => 60,
                            'Pitch' => 90,
                            'Efficiency' => 0.60999999999999998667732370449812151491641998291015625,
                            ),
                            70 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            71 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 10,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            72 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 20,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            73 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 30,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            74 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 40,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            75 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 50,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            76 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 60,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            77 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 70,
                            'Efficiency' => 0.729999999999999982236431605997495353221893310546875,
                            ),
                            78 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 80,
                            'Efficiency' => 0.66000000000000003108624468950438313186168670654296875,
                            ),
                            79 => 
                            array (
                            'Orientation' => 70,
                            'Pitch' => 90,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            80 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            81 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 10,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            82 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 20,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            83 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 30,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            84 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 40,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            85 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 50,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            86 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 60,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            87 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 70,
                            'Efficiency' => 0.68000000000000004884981308350688777863979339599609375,
                            ),
                            88 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 80,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            89 => 
                            array (
                            'Orientation' => 80,
                            'Pitch' => 90,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            90 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            91 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 10,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            92 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 20,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            93 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 30,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            94 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 40,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            95 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 50,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            96 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 60,
                            'Efficiency' => 0.6999999999999999555910790149937383830547332763671875,
                            ),
                            97 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 70,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            98 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 80,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            99 => 
                            array (
                            'Orientation' => 90,
                            'Pitch' => 90,
                            'Efficiency' => 0.5300000000000000266453525910037569701671600341796875,
                            ),
                            100 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            101 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 10,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            102 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 20,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            103 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 30,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            104 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 40,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            105 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 50,
                            'Efficiency' => 0.6999999999999999555910790149937383830547332763671875,
                            ),
                            106 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 60,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            107 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 70,
                            'Efficiency' => 0.59999999999999997779553950749686919152736663818359375,
                            ),
                            108 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 80,
                            'Efficiency' => 0.5500000000000000444089209850062616169452667236328125,
                            ),
                            109 => 
                            array (
                            'Orientation' => 100,
                            'Pitch' => 90,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            110 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            111 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 10,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            112 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 20,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            113 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 30,
                            'Efficiency' => 0.75,
                            ),
                            114 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 40,
                            'Efficiency' => 0.70999999999999996447286321199499070644378662109375,
                            ),
                            115 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 50,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            116 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 60,
                            'Efficiency' => 0.60999999999999998667732370449812151491641998291015625,
                            ),
                            117 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 70,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            118 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 80,
                            'Efficiency' => 0.5,
                            ),
                            119 => 
                            array (
                            'Orientation' => 110,
                            'Pitch' => 90,
                            'Efficiency' => 0.450000000000000011102230246251565404236316680908203125,
                            ),
                            120 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            121 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 10,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            122 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 20,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            123 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 30,
                            'Efficiency' => 0.7199999999999999733546474089962430298328399658203125,
                            ),
                            124 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 40,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            125 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 50,
                            'Efficiency' => 0.60999999999999998667732370449812151491641998291015625,
                            ),
                            126 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 60,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            127 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 70,
                            'Efficiency' => 0.5100000000000000088817841970012523233890533447265625,
                            ),
                            128 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 80,
                            'Efficiency' => 0.460000000000000019984014443252817727625370025634765625,
                            ),
                            129 => 
                            array (
                            'Orientation' => 120,
                            'Pitch' => 90,
                            'Efficiency' => 0.419999999999999984456877655247808434069156646728515625,
                            ),
                            130 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            131 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 10,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            132 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 20,
                            'Efficiency' => 0.7600000000000000088817841970012523233890533447265625,
                            ),
                            133 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 30,
                            'Efficiency' => 0.689999999999999946709294817992486059665679931640625,
                            ),
                            134 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 40,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            135 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 50,
                            'Efficiency' => 0.56999999999999995115018691649311222136020660400390625,
                            ),
                            136 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 60,
                            'Efficiency' => 0.5100000000000000088817841970012523233890533447265625,
                            ),
                            137 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 70,
                            'Efficiency' => 0.460000000000000019984014443252817727625370025634765625,
                            ),
                            138 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 80,
                            'Efficiency' => 0.419999999999999984456877655247808434069156646728515625,
                            ),
                            139 => 
                            array (
                            'Orientation' => 130,
                            'Pitch' => 90,
                            'Efficiency' => 0.36999999999999999555910790149937383830547332763671875,
                            ),
                            140 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            141 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 10,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            142 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 20,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            143 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 30,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            144 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 40,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            145 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 50,
                            'Efficiency' => 0.5300000000000000266453525910037569701671600341796875,
                            ),
                            146 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 60,
                            'Efficiency' => 0.4699999999999999733546474089962430298328399658203125,
                            ),
                            147 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 70,
                            'Efficiency' => 0.419999999999999984456877655247808434069156646728515625,
                            ),
                            148 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 80,
                            'Efficiency' => 0.38000000000000000444089209850062616169452667236328125,
                            ),
                            149 => 
                            array (
                            'Orientation' => 140,
                            'Pitch' => 90,
                            'Efficiency' => 0.340000000000000024424906541753443889319896697998046875,
                            ),
                            150 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            151 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            152 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 20,
                            'Efficiency' => 0.729999999999999982236431605997495353221893310546875,
                            ),
                            153 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 30,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            154 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 40,
                            'Efficiency' => 0.56999999999999995115018691649311222136020660400390625,
                            ),
                            155 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 50,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            156 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 60,
                            'Efficiency' => 0.429999999999999993338661852249060757458209991455078125,
                            ),
                            157 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 70,
                            'Efficiency' => 0.38000000000000000444089209850062616169452667236328125,
                            ),
                            158 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 80,
                            'Efficiency' => 0.34999999999999997779553950749686919152736663818359375,
                            ),
                            159 => 
                            array (
                            'Orientation' => 150,
                            'Pitch' => 90,
                            'Efficiency' => 0.309999999999999997779553950749686919152736663818359375,
                            ),
                            160 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            161 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            162 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 20,
                            'Efficiency' => 0.7199999999999999733546474089962430298328399658203125,
                            ),
                            163 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 30,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            164 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 40,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            165 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 50,
                            'Efficiency' => 0.4699999999999999733546474089962430298328399658203125,
                            ),
                            166 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 60,
                            'Efficiency' => 0.40000000000000002220446049250313080847263336181640625,
                            ),
                            167 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 70,
                            'Efficiency' => 0.34999999999999997779553950749686919152736663818359375,
                            ),
                            168 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 80,
                            'Efficiency' => 0.320000000000000006661338147750939242541790008544921875,
                            ),
                            169 => 
                            array (
                            'Orientation' => 160,
                            'Pitch' => 90,
                            'Efficiency' => 0.289999999999999980015985556747182272374629974365234375,
                            ),
                            170 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            171 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            172 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 20,
                            'Efficiency' => 0.70999999999999996447286321199499070644378662109375,
                            ),
                            173 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 30,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            174 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 40,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            175 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 50,
                            'Efficiency' => 0.460000000000000019984014443252817727625370025634765625,
                            ),
                            176 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 60,
                            'Efficiency' => 0.39000000000000001332267629550187848508358001708984375,
                            ),
                            177 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 70,
                            'Efficiency' => 0.330000000000000015543122344752191565930843353271484375,
                            ),
                            178 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 80,
                            'Efficiency' => 0.289999999999999980015985556747182272374629974365234375,
                            ),
                            179 => 
                            array (
                            'Orientation' => 170,
                            'Pitch' => 90,
                            'Efficiency' => 0.270000000000000017763568394002504646778106689453125,
                            ),
                            180 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            181 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            182 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 20,
                            'Efficiency' => 0.70999999999999996447286321199499070644378662109375,
                            ),
                            183 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 30,
                            'Efficiency' => 0.61999999999999999555910790149937383830547332763671875,
                            ),
                            184 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 40,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            185 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 50,
                            'Efficiency' => 0.460000000000000019984014443252817727625370025634765625,
                            ),
                            186 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 60,
                            'Efficiency' => 0.39000000000000001332267629550187848508358001708984375,
                            ),
                            187 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 70,
                            'Efficiency' => 0.330000000000000015543122344752191565930843353271484375,
                            ),
                            188 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 80,
                            'Efficiency' => 0.289999999999999980015985556747182272374629974365234375,
                            ),
                            189 => 
                            array (
                            'Orientation' => 180,
                            'Pitch' => 90,
                            'Efficiency' => 0.270000000000000017763568394002504646778106689453125,
                            ),
                            190 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 0,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            191 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            192 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 20,
                            'Efficiency' => 0.7199999999999999733546474089962430298328399658203125,
                            ),
                            193 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 30,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            194 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 40,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            195 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 50,
                            'Efficiency' => 0.4699999999999999733546474089962430298328399658203125,
                            ),
                            196 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 60,
                            'Efficiency' => 0.40000000000000002220446049250313080847263336181640625,
                            ),
                            197 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 70,
                            'Efficiency' => 0.330000000000000015543122344752191565930843353271484375,
                            ),
                            198 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 80,
                            'Efficiency' => 0.299999999999999988897769753748434595763683319091796875,
                            ),
                            199 => 
                            array (
                            'Orientation' => 190,
                            'Pitch' => 90,
                            'Efficiency' => 0.2800000000000000266453525910037569701671600341796875,
                            ),
                            200 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            201 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 10,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            202 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 20,
                            'Efficiency' => 0.729999999999999982236431605997495353221893310546875,
                            ),
                            203 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 30,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            204 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 40,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            205 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 50,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            206 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 60,
                            'Efficiency' => 0.419999999999999984456877655247808434069156646728515625,
                            ),
                            207 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 70,
                            'Efficiency' => 0.35999999999999998667732370449812151491641998291015625,
                            ),
                            208 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 80,
                            'Efficiency' => 0.330000000000000015543122344752191565930843353271484375,
                            ),
                            209 => 
                            array (
                            'Orientation' => 200,
                            'Pitch' => 90,
                            'Efficiency' => 0.299999999999999988897769753748434595763683319091796875,
                            ),
                            210 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            211 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 10,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            212 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 20,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            213 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 30,
                            'Efficiency' => 0.66000000000000003108624468950438313186168670654296875,
                            ),
                            214 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 40,
                            'Efficiency' => 0.57999999999999996003197111349436454474925994873046875,
                            ),
                            215 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 50,
                            'Efficiency' => 0.5100000000000000088817841970012523233890533447265625,
                            ),
                            216 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 60,
                            'Efficiency' => 0.450000000000000011102230246251565404236316680908203125,
                            ),
                            217 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 70,
                            'Efficiency' => 0.40000000000000002220446049250313080847263336181640625,
                            ),
                            218 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 80,
                            'Efficiency' => 0.35999999999999998667732370449812151491641998291015625,
                            ),
                            219 => 
                            array (
                            'Orientation' => 210,
                            'Pitch' => 90,
                            'Efficiency' => 0.330000000000000015543122344752191565930843353271484375,
                            ),
                            220 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            221 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 10,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            222 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 20,
                            'Efficiency' => 0.75,
                            ),
                            223 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 30,
                            'Efficiency' => 0.68000000000000004884981308350688777863979339599609375,
                            ),
                            224 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 40,
                            'Efficiency' => 0.61999999999999999555910790149937383830547332763671875,
                            ),
                            225 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 50,
                            'Efficiency' => 0.5500000000000000444089209850062616169452667236328125,
                            ),
                            226 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 60,
                            'Efficiency' => 0.5,
                            ),
                            227 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 70,
                            'Efficiency' => 0.440000000000000002220446049250313080847263336181640625,
                            ),
                            228 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 80,
                            'Efficiency' => 0.40000000000000002220446049250313080847263336181640625,
                            ),
                            229 => 
                            array (
                            'Orientation' => 220,
                            'Pitch' => 90,
                            'Efficiency' => 0.35999999999999998667732370449812151491641998291015625,
                            ),
                            230 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            231 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 10,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            232 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 20,
                            'Efficiency' => 0.770000000000000017763568394002504646778106689453125,
                            ),
                            233 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 30,
                            'Efficiency' => 0.70999999999999996447286321199499070644378662109375,
                            ),
                            234 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 40,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            235 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 50,
                            'Efficiency' => 0.59999999999999997779553950749686919152736663818359375,
                            ),
                            236 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 60,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            237 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 70,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            238 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 80,
                            'Efficiency' => 0.440000000000000002220446049250313080847263336181640625,
                            ),
                            239 => 
                            array (
                            'Orientation' => 230,
                            'Pitch' => 90,
                            'Efficiency' => 0.40000000000000002220446049250313080847263336181640625,
                            ),
                            240 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            241 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 10,
                            'Efficiency' => 0.82999999999999996003197111349436454474925994873046875,
                            ),
                            242 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 20,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            243 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 30,
                            'Efficiency' => 0.75,
                            ),
                            244 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 40,
                            'Efficiency' => 0.6999999999999999555910790149937383830547332763671875,
                            ),
                            245 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 50,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            246 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 60,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            247 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 70,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            248 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 80,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            249 => 
                            array (
                            'Orientation' => 240,
                            'Pitch' => 90,
                            'Efficiency' => 0.440000000000000002220446049250313080847263336181640625,
                            ),
                            250 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            251 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 10,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            252 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 20,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            253 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 30,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            254 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 40,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            255 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 50,
                            'Efficiency' => 0.689999999999999946709294817992486059665679931640625,
                            ),
                            256 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 60,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            257 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 70,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            258 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 80,
                            'Efficiency' => 0.54000000000000003552713678800500929355621337890625,
                            ),
                            259 => 
                            array (
                            'Orientation' => 250,
                            'Pitch' => 90,
                            'Efficiency' => 0.4899999999999999911182158029987476766109466552734375,
                            ),
                            260 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            261 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 10,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            262 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 20,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            263 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 30,
                            'Efficiency' => 0.810000000000000053290705182007513940334320068359375,
                            ),
                            264 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 40,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            265 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 50,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            266 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 60,
                            'Efficiency' => 0.689999999999999946709294817992486059665679931640625,
                            ),
                            267 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 70,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            268 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 80,
                            'Efficiency' => 0.57999999999999996003197111349436454474925994873046875,
                            ),
                            269 => 
                            array (
                            'Orientation' => 260,
                            'Pitch' => 90,
                            'Efficiency' => 0.5300000000000000266453525910037569701671600341796875,
                            ),
                            270 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            271 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 10,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            272 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 20,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            273 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 30,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            274 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 40,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            275 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 50,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            276 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 60,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            277 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 70,
                            'Efficiency' => 0.68000000000000004884981308350688777863979339599609375,
                            ),
                            278 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 80,
                            'Efficiency' => 0.63000000000000000444089209850062616169452667236328125,
                            ),
                            279 => 
                            array (
                            'Orientation' => 270,
                            'Pitch' => 90,
                            'Efficiency' => 0.560000000000000053290705182007513940334320068359375,
                            ),
                            280 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            281 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 10,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            282 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 20,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            283 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 30,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            284 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 40,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            285 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 50,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            286 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 60,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            287 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 70,
                            'Efficiency' => 0.729999999999999982236431605997495353221893310546875,
                            ),
                            288 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 80,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            289 => 
                            array (
                            'Orientation' => 280,
                            'Pitch' => 90,
                            'Efficiency' => 0.58999999999999996891375531049561686813831329345703125,
                            ),
                            290 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            291 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 10,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            292 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 20,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            293 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 30,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            294 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 40,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            295 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 50,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            296 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 60,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            297 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 70,
                            'Efficiency' => 0.7600000000000000088817841970012523233890533447265625,
                            ),
                            298 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 80,
                            'Efficiency' => 0.6999999999999999555910790149937383830547332763671875,
                            ),
                            299 => 
                            array (
                            'Orientation' => 290,
                            'Pitch' => 90,
                            'Efficiency' => 0.61999999999999999555910790149937383830547332763671875,
                            ),
                            300 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            301 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 10,
                            'Efficiency' => 0.90000000000000002220446049250313080847263336181640625,
                            ),
                            302 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 20,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            303 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 30,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            304 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 40,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            305 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 50,
                            'Efficiency' => 0.89000000000000001332267629550187848508358001708984375,
                            ),
                            306 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 60,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            307 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 70,
                            'Efficiency' => 0.8000000000000000444089209850062616169452667236328125,
                            ),
                            308 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 80,
                            'Efficiency' => 0.729999999999999982236431605997495353221893310546875,
                            ),
                            309 => 
                            array (
                            'Orientation' => 300,
                            'Pitch' => 90,
                            'Efficiency' => 0.64000000000000001332267629550187848508358001708984375,
                            ),
                            310 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            311 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 10,
                            'Efficiency' => 0.91000000000000003108624468950438313186168670654296875,
                            ),
                            312 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 20,
                            'Efficiency' => 0.939999999999999946709294817992486059665679931640625,
                            ),
                            313 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 30,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            314 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 40,
                            'Efficiency' => 0.65000000000000002220446049250313080847263336181640625,
                            ),
                            315 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 50,
                            'Efficiency' => 0.61999999999999999555910790149937383830547332763671875,
                            ),
                            316 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 60,
                            'Efficiency' => 0.88000000000000000444089209850062616169452667236328125,
                            ),
                            317 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 70,
                            'Efficiency' => 0.81999999999999995115018691649311222136020660400390625,
                            ),
                            318 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 80,
                            'Efficiency' => 0.7399999999999999911182158029987476766109466552734375,
                            ),
                            319 => 
                            array (
                            'Orientation' => 310,
                            'Pitch' => 90,
                            'Efficiency' => 0.66000000000000003108624468950438313186168670654296875,
                            ),
                            320 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            321 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            322 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 20,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            323 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 30,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            324 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 40,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            325 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 50,
                            'Efficiency' => 0.9499999999999999555910790149937383830547332763671875,
                            ),
                            326 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 60,
                            'Efficiency' => 0.90000000000000002220446049250313080847263336181640625,
                            ),
                            327 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 70,
                            'Efficiency' => 0.83999999999999996891375531049561686813831329345703125,
                            ),
                            328 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 80,
                            'Efficiency' => 0.7600000000000000088817841970012523233890533447265625,
                            ),
                            329 => 
                            array (
                            'Orientation' => 320,
                            'Pitch' => 90,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                            330 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            331 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            332 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 20,
                            'Efficiency' => 0.95999999999999996447286321199499070644378662109375,
                            ),
                            333 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 30,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            334 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 40,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            335 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 50,
                            'Efficiency' => 0.95999999999999996447286321199499070644378662109375,
                            ),
                            336 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 60,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            337 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 70,
                            'Efficiency' => 0.84999999999999997779553950749686919152736663818359375,
                            ),
                            338 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 80,
                            'Efficiency' => 0.770000000000000017763568394002504646778106689453125,
                            ),
                            339 => 
                            array (
                            'Orientation' => 330,
                            'Pitch' => 90,
                            'Efficiency' => 0.68000000000000004884981308350688777863979339599609375,
                            ),
                            340 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            341 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 10,
                            'Efficiency' => 0.92000000000000003996802888650563545525074005126953125,
                            ),
                            342 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 20,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            343 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 30,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            344 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 40,
                            'Efficiency' => 0.9899999999999999911182158029987476766109466552734375,
                            ),
                            345 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 50,
                            'Efficiency' => 0.9699999999999999733546474089962430298328399658203125,
                            ),
                            346 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 60,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            347 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 70,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            348 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 80,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            349 => 
                            array (
                            'Orientation' => 340,
                            'Pitch' => 90,
                            'Efficiency' => 0.68000000000000004884981308350688777863979339599609375,
                            ),
                            350 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 0,
                            'Efficiency' => 0.85999999999999998667732370449812151491641998291015625,
                            ),
                            351 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 10,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            352 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 20,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            353 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 30,
                            'Efficiency' => 1,
                            ),
                            354 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 40,
                            'Efficiency' => 1,
                            ),
                            355 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 50,
                            'Efficiency' => 0.979999999999999982236431605997495353221893310546875,
                            ),
                            356 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 60,
                            'Efficiency' => 0.93000000000000004884981308350688777863979339599609375,
                            ),
                            357 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 70,
                            'Efficiency' => 0.86999999999999999555910790149937383830547332763671875,
                            ),
                            358 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 80,
                            'Efficiency' => 0.7800000000000000266453525910037569701671600341796875,
                            ),
                            359 => 
                            array (
                            'Orientation' => 350,
                            'Pitch' => 90,
                            'Efficiency' => 0.67000000000000003996802888650563545525074005126953125,
                            ),
                        ),
                        'PostCodes' => 
                        array (
                            0 => 
                            array (
                            'From' => 3000,
                            'To' => 3999,
                            ),
                            1 => 
                            array (
                            'From' => 8000,
                            'To' => 8999,
                            ),
                            2 => 
                            array (
                            'From' => 7000,
                            'To' => 7799,
                            ),
                            3 => 
                            array (
                            'From' => 7800,
                            'To' => 7999,
                            ),
                        ),
                        ),
                        'PVSTCQuantity' => 92,
                        'SolarHotWaterSystemSTCQuantity' => 0,
                        'STCPrice' => 39.60000000000000142108547152020037174224853515625,
                        'Deeming' => 12,
                        'Rating' => 1.185000000000000053290705182007513940334320068359375,
                        'Multiplier' => 1,
                        'Jan' => 
                        array (
                        'Total' => 958.9950775290736828537774272263050079345703125,
                        'Average' => 30.93532508158302363199254614301025867462158203125,
                        ),
                        'Feb' => 
                        array (
                        'Total' => 767.3779714667607549927197396755218505859375,
                        'Average' => 27.406356123812884106882847845554351806640625,
                        ),
                        'Mar' => 
                        array (
                        'Total' => 680.039182209899081499315798282623291015625,
                        'Average' => 21.93674781322255284976563416421413421630859375,
                        ),
                        'Apr' => 
                        array (
                        'Total' => 455.3865182112584761853213422000408172607421875,
                        'Average' => 15.1795506070419481403632744331844151020050048828125,
                        ),
                        'May' => 
                        array (
                        'Total' => 324.27431720890666611012420617043972015380859375,
                        'Average' => 10.460461845448602247188318870030343532562255859375,
                        ),
                        'Jun' => 
                        array (
                        'Total' => 254.57774860200066768811666406691074371337890625,
                        'Average' => 8.48592495340002272996571264229714870452880859375,
                        ),
                        'Jul' => 
                        array (
                        'Total' => 292.115966609127326591988094151020050048828125,
                        'Average' => 9.4230956970686232665457282564602792263031005859375,
                        ),
                        'Aug' => 
                        array (
                        'Total' => 416.0400712760732631068094633519649505615234375,
                        'Average' => 13.4206474605184933324153462308458983898162841796875,
                        ),
                        'Sep' => 
                        array (
                        'Total' => 535.05753290828170065651647746562957763671875,
                        'Average' => 17.83525109694272003935111570172011852264404296875,
                        ),
                        'Oct' => 
                        array (
                        'Total' => 734.427875995964086541789583861827850341796875,
                        'Average' => 23.6912218063214226049240096472203731536865234375,
                        ),
                        'Nov' => 
                        array (
                        'Total' => 846.1887824193537426253897137939929962158203125,
                        'Average' => 28.2062927473117923682366381399333477020263671875,
                        ),
                        'Dec' => 
                        array (
                        'Total' => 949.527044546612387421191670000553131103515625,
                        'Average' => 30.6299046627939475229140953160822391510009765625,
                        ),
                        'Total' => 
                        array (
                        'Total' => 7214.0080889833116088993847370147705078125,
                        'Average' => 237.61077989546600974790635518729686737060546875,
                        ),
                    ),
                    'RequiredAccessories' => 
                    array (
                    ),
                    'ReCalculate' => false,
                    'TotalPanels' => 20,
                    'Accessories' => 
                    array (
                    ),
                    'Finance' => 
                    array (
                        'Type' => NULL,
                        'Price' => 0,
                        'PPrice' => 0,
                        'APrice' => 0,
                        'CampaignDiscount' => 0,
                        'CostOfFinance' => 0,
                        'PCostOfFinance' => 0,
                        'HCostOfFinance' => 0,
                        'FreedomPackage' => false,
                        'PSecondStoreyInstallation' => false,
                        'HSecondStoreyInstallation' => false,
                        'BaseDepositRate' => 0,
                        'InterestRate' => 0,
                        'Months' => 0,
                        'TotalFinancedAmount' => 0,
                        'AdditionalDeposit' => 0,
                        'MinimumDeposit' => 0,
                        'FortnightlyRepayment' => 0,
                        'TotalPriceLessTotalDeposit' => 0,
                        'TotalDeposit' => 0,
                        'ClassicDeposit' => 0,
                        'ClassicRepayment' => 0,
                    ),
                    'BusinessPPrice' => '74903643.20',
                    );
            
                $solargain_options['Finance']['Price'] = ($_GET["price_option_".$i] != "")?$_GET["price_option_".$i]:0;
                $solargain_options['Finance']['PPrice'] = ($_GET["price_option_".$i] != "")?$_GET["price_option_".$i]:0;

                if(isset($_REQUEST['vicRebate'])){
                    if($_REQUEST['vicRebate'] == 'yes' && $_REQUEST['loanRebate'] == 'yes'){
                        $solargain_options['Finance']['Type'] = array (
                            'ID' => 43,
                            'Code' => 'SOLARVIF18',
                            'Name' => 'Solar VIC $1,888 Rebate & Interest free loan',
                            'EXOSystemCode' => 'P-PV SYSTEM',
                            'InterestRates' => array (),
                            'Deposits' => array (),
                            'Terms' => array (),
                            'FileCategories' => array (),
                        );
                    }
                    if($_REQUEST['vicRebate'] == 'yes' && $_REQUEST['loanRebate'] == 'no'){
                        $solargain_options['Finance']['Type'] = array (
                            'ID' => 41,
                            'Code' => 'SOLARVRB18',
                            'Name' => 'Solar VIC $1,888 Rebate',
                            'EXOSystemCode' => 'P-PV SYSTEM',
                            'InterestRates' => array (),
                            'Deposits' => array (),
                            'Terms' => array (),
                            'FileCategories' => array (),
                        );
                    }
                }
                
                if(strpos($_GET['option_inverter_type_name_'.$i],'Primo ') !== false || strpos($_GET['option_inverter_type_name_'.$i],'Symo ') !== false){
                    //$accessories_item = count($solargain_options['Accessories']);
                    
                    $solargain_options['Accessories'][0] =  array (
                                                                'ID' => 0,
                                                                'Accessory' => 
                                                                array (
                                                                    'ID' => 1,
                                                                    'Code' => 'Fronius 1P Smart Meter',
                                                                    'Category' => 
                                                                    array (
                                                                    'ID' => 2,
                                                                    'Code' => 'SMART_METER',
                                                                    'Name' => 'Smart Meter',
                                                                    'Order' => 3,
                                                                    ),
                                                                    'Manufacturer' => 
                                                                    array (
                                                                    'ID' => 2,
                                                                    'Name' => 'Fronius',
                                                                    'ServiceContact' => 'Simon',
                                                                    'ServiceHours' => '0900-1700',
                                                                    'ServicePhone' => '03 8340 2910',
                                                                    'ValidForPanels' => true,
                                                                    'ValidForInverters' => true,
                                                                    'ValidForAccessories' => true,
                                                                    'ValidForHotWaterSystems' => true,
                                                                    ),
                                                                    'Model' => 'Fronius 1P Smart Meter',
                                                                    'DisplayOnQuote' => true,
                                                                    'Warranty' => '2 year',
                                                                    'Features' => 'Real-time view of consumption data',
                                                                    'ExoCode' => 'P-FRO-SMART-METER-1P',
                                                                    'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
                                                                    'Active' => true,
                                                                    'Kit' => false,
                                                                    'Cost' => 130.479999999999989768184605054557323455810546875,
                                                                    'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
                                                                ),
                                                                'Include' => false,
                                                                'DisplayOnQuote' => true,
                                                                'UnitPriceEnabled' => true,
                                                                'IncludedEnabled' => true,
                                                                'QuantityEnabled' => true,
                                                                'Quantity' => '1',
                                                                'Included' => true,
                                                                'UnitPrice' => 0,
                                                            );
                    $solargain_options['Accessories'][1] = array (
                                                                    'ID' => NULL,
                                                                    'Quantity' => 1,
                                                                    'UnitPrice' => 0,
                                                                    'Included' => true,
                                                                    'DisplayOnQuote' => true,
                                                                    'Accessory' => 
                                                                    array (
                                                                    'ID' => 387,
                                                                    'Code' => 'Fronius Service Partner Plus 10YR Warranty',
                                                                    'Category' => 
                                                                    array (
                                                                        'ID' => 3,
                                                                        'Code' => 'OTHER',
                                                                        'Name' => 'Other',
                                                                        'Order' => 9,
                                                                    ),
                                                                    'Model' => 'Fronius Service Partner Plus 10YR Warranty',
                                                                    'DisplayOnQuote' => true,
                                                                    'ExoCode' => 'P-FRO-FSP-PLUS',
                                                                    'Active' => true,
                                                                    'Kit' => false,
                                                                    ),
                                                                );
                }else if(strpos($_GET['option_inverter_type_name_'.$i],'S Edge ') !== false){
                    $solargain_options['Accessories'][0] = array ( 'ID' => NULL,
                                                            'Quantity' => 1,
                                                            'UnitPrice' => 0,
                                                            'Included' => true,
                                                            'DisplayOnQuote' => true,  
                                                            'Accessory' => array (
                                                                'ID' => 433,
                                                                'Code' => 'SolarEdge 10kW HD-Wave 1Ph Inverter',
                                                                'Category' => 
                                                                array (
                                                                  'ID' => 3,
                                                                  'Code' => 'OTHER',
                                                                  'Name' => 'Other',
                                                                  'Order' => 9,
                                                                ),
                                                                'Model' => 'SolarEdge 10kW HD-Wave 1Ph Inverter',
                                                                'BrochureURL' => 'https://www.solargain.com.au/sites/default/files/SolarEdge%20HD-wave-inverter-datasheet-aus.pdf',
                                                                'DisplayOnQuote' => true,
                                                                'LabourOnly' => false,
                                                                'ExoCode' => 'P-SE-SE10000H-AU000BWU4',
                                                                'Active' => true,
                                                                'Kit' => false,
                                                              )
                                                              
                                                           );  
                    $solargain_options['Accessories'][1] = array ( 'ID' => NULL,
                                                            'Quantity' => 1,
                                                            'UnitPrice' => 0,
                                                            'Included' => true,
                                                            'DisplayOnQuote' => true,
                                                            'Accessory' => array (
                                                                'ID' => 17,
                                                                'Code' => 'SolarEdge Wifi Interface for SE Inverter',
                                                                'Category' => 
                                                                array (
                                                                  'ID' => 3,
                                                                  'Code' => 'OTHER',
                                                                  'Name' => 'Other',
                                                                  'Order' => 9,
                                                                ),
                                                                'Model' => 'SolarEdge Wifi Interface for SE Inverter',
                                                                'DisplayOnQuote' => true,
                                                                'LabourOnly' => false,
                                                                'ExoCode' => 'P-SE-SE1000-WIFI01',
                                                                'Active' => true,
                                                                'Kit' => false,
                                                              )
                                                             );
                    $solargain_options['Accessories'][2] = array ( 'ID' => NULL,
                                                             'Quantity' => 1,
                                                             'UnitPrice' => 0,
                                                             'Included' => true,
                                                             'DisplayOnQuote' => true,  
                                                             'Accessory' => array (
                                                                'ID' => 22,
                                                                'Code' => 'SolarEdge Consumption Meter',
                                                                'Category' => 
                                                                array (
                                                                  'ID' => 2,
                                                                  'Code' => 'SMART_METER',
                                                                  'Name' => 'Smart Meter',
                                                                  'Order' => 3,
                                                                ),
                                                                'Model' => 'SE-WNC-3Y400-MB-K1',
                                                                'DisplayOnQuote' => true,
                                                                'LabourOnly' => false,
                                                                'ExoCode' => 'P-SE-METER',
                                                                'PurchaseOrderExoCode' => 'P-SE-METER-INSTALL',
                                                                'Active' => true,
                                                                'Kit' => false,
                                                              )
                                                            ); 

                }

                if($_GET['option_tilting_'.$i] > 0){
                
                    $accessories_item = count($solargain_options['Accessories']);
                    $solargain_options['Accessories'][$accessories_item] =  array (
                                                                                'ID' => NULL,
                                                                                'Accessory' => 
                                                                                array (
                                                                                'ID' => 10,
                                                                                'Code' => 'Tilt Frame 10-15 Degrees',
                                                                                'Category' => 
                                                                                array (
                                                                                    'ID' => 6,
                                                                                    'Code' => 'TILT_FRAME',
                                                                                    'Name' => 'Tilt Frame',
                                                                                    'Order' => 4,
                                                                                ),
                                                                                'Manufacturer' => 
                                                                                array (
                                                                                    'ID' => 45,
                                                                                    'Name' => 'Clenergy',
                                                                                    'ValidForPanels' => false,
                                                                                    'ValidForInverters' => false,
                                                                                    'ValidForAccessories' => true,
                                                                                    'ValidForHotWaterSystems' => false,
                                                                                ),
                                                                                'Model' => 'Tilt Frame 10-15 Degrees',
                                                                                'DisplayOnQuote' => true,
                                                                                'Warranty' => '10 year',
                                                                                'Features' => 'Tilt Frame to add 10-15 Degree tilt',
                                                                                'ExoCode' => 'P-ER-TL-10/15',
                                                                                'Active' => true,
                                                                                'Kit' => false,
                                                                                ),
                                                                                'Include' => false,
                                                                                'DisplayOnQuote' => true,
                                                                                'UnitPriceEnabled' => true,
                                                                                'IncludedEnabled' => true,
                                                                                'QuantityEnabled' => true,
                                                                                'Quantity' => $_GET['option_tilting_'.$i],
                                                                                'UnitPrice' => 0,
                                                                                'Included' => true,
                                                                            );
                }

                //THIENPB code add accessories Battery
                if($_GET['option_battery_'.$i] > 0){

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/accessories/businessunit/3');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                    
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Host: crm.solargain.com.au",
                            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                            "Content-Type: application/json",
                            "Accept: application/json, text/plain, */*",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding: 	gzip, deflate, br",
                            "Connection: keep-alive",
                            "Authorization: Basic ".base64_encode($username . ":" . $password),
                            "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                            "Cache-Control: max-age=0"
                        )
                    );
                    
                    $result = curl_exec($ch);
                    curl_close ($ch);
                    
                    $option_battery = json_decode($result);
                    $dataid = array_column($option_battery, 'ID');
                    $datakey = array_search($_GET['option_battery_'.$i], $dataid);
                    $data_battery = $option_battery[$datakey];

                    if($_GET['option_battery_'.$i] == 40){
                        $battery_price = '10000';
                    }
                    $accessories_item = count($solargain_options['Accessories']);
                    $solargain_options['Accessories'][$accessories_item]= 
                    array (
                        'ID' => NULL,
                        'Include' => false,
                        'DisplayOnQuote' => true,
                        'UnitPriceEnabled' => true,
                        'IncludedEnabled' => true,
                        'QuantityEnabled' => true,
                        'Quantity' => '1',
                        'UnitPrice' => $battery_price,
                        'Accessory' => $data_battery,
                    );
                }
                //END


                $arr = array (
                    'Configurations' => 
                    array (
                    0 => array (
                        'ID' => NULL,
                        'MinimumPanels' => 0,
                        'MaximumPanels' => (int)$_GET['option_total_panel_'.$i],
                        'MinimumTrackers' => 0,
                        'MaximumTrackers' => 2,
                        'Upgrade' => false,
                        'NewInverter' => false,
                        'Inverter' => 
                        array (
                        ),
                        'Panel' => 
                        array (
                        ),
                        'Trackers' => 
                        array (
                        ),
                        'Number' => NULL,
                        'NumberOfPanels' => (int)$_GET['option_total_panel_'.$i],
                      )
                    )
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/panels/businessunit/3');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Host: crm.solargain.com.au",
                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                        "Content-Type: application/json",
                        "Accept: application/json, text/plain, */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: 	gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                        "Cache-Control: max-age=0"
                    )
                );
                
                $result = curl_exec($ch);
                curl_close ($ch);
                
                $option_panels = json_decode($result);
                $dataid = array_column($option_panels, 'ID');
                $datakey = array_search($_GET['option_model_'.$i], $dataid);
                $data_panel = $option_panels[$datakey];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/inverters/businessunit/3');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Host: crm.solargain.com.au",
                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                        "Content-Type: application/json",
                        "Accept: application/json, text/plain, */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: 	gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                        "Cache-Control: max-age=0"
                    )
                );
                
                $result = curl_exec($ch);
                curl_close ($ch);
                
                $option_inverters= json_decode($result);
                $dataid = array_column($option_inverters, 'ID');
                $datakey = array_search($_GET['option_inverter_'.$i], $dataid);
                $data_inverter = $option_inverters[$datakey];

                $arr['Configurations'][0]['Inverter'] = $data_inverter;
                $arr['Configurations'][0]['Panel']  = $data_panel;
                $data_option_string = json_encode($arr);




                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/quotes/calculate?postcode=3056');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data_option_string);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    "Cache-Control: max-age=0"
                )
                );

                $result = curl_exec($ch);
                curl_close ($ch);

                $data_option_string = json_decode($result);

                // $quote_decode->Options[$i]->Configurations[0] = $data_option_string->Configurations[0];

                $MaximumGroup =  $data_option_string->Configurations[0]->Trackers[1]->MaximumPanels;
                $MaximumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MaximumPanels;
                $MinimumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MinimumPanels;
                
                if($MaximumPanels == 1){
                    $data_option_string->Configurations[0]->NumberOfPanels = 1;
                    $data_option_string->Configurations[0]->Number = (int)$_GET['option_total_panel_'.$i];
                    $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->PanelCount = 1;
                    $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->Orientation = array ('Name' => 'N 0','Value' => 0);
                    $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->Pitch = array ('Name' => '20','Value' => 20);
                    $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->Shading = 0;
                    $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->Arrays = 1;

                }else{
                    if($MaximumPanels > $MaximumGroup ){
                        $MaximumPanels = $MaximumGroup;
                    }

                    /** Thienpb update logic check max panel by VOC */
                    $tempCov = $data_panel->TempCoV;
                    $covPer = $data_panel->Voc;
                    $COV = ($covPer * ((25 * $tempCov)+100)/100);
                    $max = (int)(600/$COV);
                    if($max < $MaximumPanels){
                        $MaximumPanels = $max;
                    }
                     /** End */

                    $data_result = calc_panel((int)$_GET['option_total_panel_'.$i],$MinimumPanels,$MaximumPanels,array(count($data_option_string->Configurations[0]->Trackers[0]->Strings),count($data_option_string->Configurations[0]->Trackers[1]->Strings)),$MaximumGroup);
                    $sub_panels = $data_result['panelConfig'];
                    if(($data_option_string->Configurations[0]->Trackers[0]->MaximumPanels > $data_option_string->Configurations[0]->Trackers[1]->MaximumPanels) && (count($sub_panels[0]) != count($data_option_string->Configurations[0]->Trackers[0]->Strings))){
                        $sub_panels = array_reverse($sub_panels);
                    }
                    if($data_result['SuggestTotalPanel'] != (int)$_GET['option_total_panel_'.$i]){
                        $specialMess .= "Can't push Option ".($i+1)." with ".(int)$_GET['option_total_panel_'.$i]." panels.(Suggestion : ".$data_result['SuggestTotalPanel']." panels.)\n";
                        if(($i+1) == count($quote_decode->Options)){
                            $specialMess .= "\nYou can use suggestions or remove options was wrong.";
                        }
                    }
                    for ($j= 0; $j < count($data_option_string->Configurations[0]->Trackers) ; $j++) {
                        $data_option_string->Configurations[0]->Trackers[$j]->MaximumPanels = (int)$_GET['option_total_panel_'.$i];
                        for($k = 0; $k < count($data_option_string->Configurations[0]->Trackers[$j]->Strings) ; $k++){
                            $data_option_string->Configurations[0]->Trackers[$j]->Strings[$k]->PanelCount =  ($sub_panels[$j][$k] != 0)?$sub_panels[$j][$k]:NULL;
                            $data_option_string->Configurations[0]->Trackers[$j]->Strings[$k]->Orientation = ($sub_panels[$j][$k] != 0)?array ('Name' => 'N 0','Value' => 0):NULL;
                            $data_option_string->Configurations[0]->Trackers[$j]->Strings[$k]->Pitch = ($sub_panels[$j][$k] != 0)?array ('Name' => '20','Value' => 20):NULL;
                            $data_option_string->Configurations[0]->Trackers[$j]->Strings[$k]->Shading = ($sub_panels[$j][$k] !=0)?0:NULL;
                            $data_option_string->Configurations[0]->Trackers[$j]->Strings[$k]->Arrays = ($sub_panels[$j][$k] !=0)?1:NULL;

                        }
                    }
                }

                $solargain_options['Configurations'][0] = $data_option_string->Configurations[0];
                $quote_decode->Options[$i] =  $solargain_options;
                 //tuan code
                $travel = $_REQUEST['travel_km_'.$i];
                $splits =$_REQUEST['splits_'.$i];
                $tilted =$_REQUEST['option_tilting_'.$i];
                $double_storey_panels = $_REQUEST['number_double_storey_panel_'.$i];
                $Additional =  $_REQUEST['additional_'.$i];

                $quote_decode ->Options[$i]->Splits =  $splits;
                $quote_decode ->Options[$i]->Travel =  $travel;
                $quote_decode ->Options[$i]->TiltedPanels =  $tilted;
                $quote_decode ->Options[$i]->AdditionalCableRun =  $Additional;
                $quote_decode ->Options[$i]->ExcessHeightPanels =  $double_storey_panels;
            }
        }
        $special_note = $quote_decode->SpecialNotes;
        $special_note[count($special_note)] = (object)array("Text" => $_GET['specialNotes']);
        $quote_decode->SpecialNotes = $special_note;
        // $quote_decode -> SpecialNotes ="Option #1 Upgrade from 6.5kW QCells DUO Half Cut 325W panels (x20) to 6.54kW Sunpower E 327W panels (x20) for $2300"
        //                                ."\r\nOption #2 Upgrade from 7.8kW QCells DUO Half Cut 325W panels (x24) to 7.85kW Sunpower E 327W panels (x24) for $2800"
        //                                ."\r\nOption #3 Upgrade from 10.73kW QCells DUO Half Cut 325W panels (x33) to 10.79kW Sunpower E 327W panels (x33) for $3900"
        //                                ."\r\nAssumes ample room in switchboard"
        //                                ."\r\nQuality IMO DC Isolaters, Clenergy Railings, CEC Accredited installation"
        //                                ."\r\nSolar PV system may not be turned on until after the independent inspection which can take up to 2 weeks after install"
        //                                ."\r\n*For single phase sites, Grid Operator will most likely apply 5kW grid export limit pending grid application and therefore may affect production"
        //                                ."\r\nExcludes any metering cost which is separate and subject to your retailer";
        //Proposed Install Date
        $quote_decode -> ProposedInstallDate = array (
        "Date" => '31/12/2021', //date('d/m/Y', time() + 6*7*24*60*60)
        "Time" => "9:15 AM"
        );


        // Thienpb fix next action date +12 months
        $today = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
        $next_action_date = mktime(0, 0, 0, date('n', $today)+12, date('d', $today), date('Y', $today));

        $quote_decode->NextActionDate = array(
        "Date" => date('d/m/Y', $next_action_date),
        "Time" => "9:15 AM"
        );
        //end

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
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            )
        );
        $result = curl_exec($curl);

        //thienpb code return if update false
        return_message($result,$specialMess);

        // End we update install here
        
        $curl = curl_init();
        $url = "https://crm.solargain.com.au/APIv2/installs/";

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $install_encode);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
                "Content-Length: " .strlen($install_encode),
                "Origin: https://crm.solargain.com.au",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            )
        );
        $result = curl_exec($curl);

        $record = urldecode($_GET['record']);
        $bean = BeanFactory::getBean("AOS_Quotes", $record);
        $bean -> solargain_quote_number_c = $SGquote_ID;
        $bean ->sg_site_details_no_c = $data_result['SiteDetailNumber'];
        $bean->save();

        //dung code - upload file for Citipower Powercor push sogargain
        if($bean->meter_number_c !=='' && ($bean->distributor_c == '4' || $bean->distributor_c == '7' || $bean->distributor_c == '6')) {
        $folder_pdf = dirname(__FILE__)."/server/php/files/".$bean->pre_install_photos_c;

        //new logic get file citipower 
        $leads_file_attachmens = scandir( $folder_pdf . '/');
            foreach ($leads_file_attachmens as $key => $value) {
                if (strpos($value, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {
                $filename_pdf = $value;
                }          
            }
        
        if(is_file($folder_pdf.'/'.$filename_pdf)){
            $content_file =  file_get_contents($folder_pdf.'/'.$filename_pdf);
            $ch = curl_init();
            $data_file_upload = array(
                'Data'     => base64_encode($content_file),
                'Filename' => $filename_pdf,
                'Title'    => $filename_pdf,
                'Url'      => "",
            );
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/" .$SGquote_ID."/upload");
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
            $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/" .$SGquote_ID;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$SGquote_ID."/files");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en-US,en;q=0.9";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID;
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

                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$SGquote_ID."/files/" .$id_file_image_meterbox ."/category/6");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID;
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

        die();
    }else{
        die();
    }