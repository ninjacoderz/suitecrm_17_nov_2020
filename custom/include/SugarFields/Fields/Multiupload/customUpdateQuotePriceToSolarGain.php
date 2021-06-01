<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');

$curl = curl_init();
$tmpfname = dirname(__FILE__).'/cookiesolargain.txt';

$username = "matthew.wright";
$password =  "MW@pure733";

function return_message($quote,$specialMess=''){

    if($specialMess != ''){
        echo $specialMess;
        die();
    }
    $message_return = '';

    $patten_err  = '/"ExceptionMessage":"(.*?)","ExceptionType"/';
    preg_match($patten_err , $quote, $matches);

    if(isset($matches) && $matches[1] != ''){
        if(strpos($matches[1],"User does not have view rights to view quote") !== false){

        }else{
            $message_return = $matches[1];
            echo str_replace("\\","",$message_return);
            die();
        }
    }else{
        $patten_err  = '/"Message":"(.*?)","MessageDetail"/';
        preg_match($patten_err , $quote, $matches);
        if(isset($matches) && $matches[1] != ''){
            $message_return = $matches[1];
            echo str_replace("\\","",$message_return);
            die();
        }
    }
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
        if(array_sum($arrLine1) > $groupmax){
            $type = "false";
            continue;
        }
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

$SGquote_ID = $_GET['quoteSG_ID'];

if($_GET['process'] == 'quote'){
    $quote_id = $_GET['record'];
    $quote =  new AOS_Quotes();
    $bean_module_data = $quote->retrieve($quote_id);
}else{
    $lead_id = $_GET['record'];
    $lead =  new Lead();
    $bean_module_data = $quote->retrieve($lead_id);
}
if($bean_module_data->id){

    if($bean_module_data->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'WalkingElephant#256';
    }
    if($bean_module_data->solargain_quote_number_c == $SGquote_ID){
        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
        //set the url, number of POST vars, POST data

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
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
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                "Cache-Control: max-age=0"
            )
        );
        
        $quote = curl_exec($curl);
        curl_close($curl);

        //thienpb code return if update false
        return_message($quote);

        $quote_decode = json_decode($quote);

        //Thienpb code for change account if download false
        if(!isset($quote_decode->ID)){
            if($username == 'paul.szuster@solargain.com.au'){
                $username = "matthew.wright";
                $password =  "MW@pure733";
            }else{
                $username = 'paul.szuster@solargain.com.au';
                $password = 'WalkingElephant#256';
            }
            $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
            //set the url, number of POST vars, POST data

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
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
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $quote = curl_exec($curl);
            curl_close($curl);

            //thienpb code return if update false
            return_message($quote);

            $quote_decode = json_decode($quote);
        }
        //END

        if(!isset($quote_decode)){
            $message_return = 'Solargain quote number isn\'t exist';
            echo $message_return;
            die();
        }
        if($quote_decode->Status->Description == "Converted To Order"){
            $message_return = 'Solargain quote status is Converted To Order';
            echo $message_return;
            die(); // thienpb code check status is converted to order
        } 
        //set option price
        $st = urldecode($_GET['state']);
        $sgPrices = array("VIC"=> array("option1"=>7490,"option2"=>8590,"option3"=>10690,'option4'=>9790,"option5"=>11390,'option6'=>14590),
                        "SA" => array("option1"=>6890,"option2"=>7790,"option3"=>9490,'option4'=>8990,"option5"=>10590,'option6'=>13290),
                        "NSW"=> array("option1"=>7290,"option2"=>8290,"option3"=>9990,'option4'=>9690,"option5"=>10990,'option6'=>14490),
                        "ACT"=> array("option1"=>7890,"option2"=>8790,"option3"=>10590,'option4'=>9990,"option5"=>11590,'option6'=>14490),
                        "QLD"=> array("option1"=>6390,"option2"=>7290,"option3"=>8990,'option4'=>8690,"option5"=>9990,'option6'=>12990));
        // tuan code
        $data_old = array();
        for($i=0;$i<count($quote_decode->Options);$i++){
            for($j=0;$j<count($quote_decode->Options[$i]->Configurations[0]->Trackers);$j++){
                $Orientation =  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Orientation;
                $pitch_old =  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Pitch;
                $data_old["option".$i]["Tracker".$j]["Orientation"] = $Orientation;
                $data_old["option".$i]["Tracker".$j]["Pitch"] = $pitch_old;
            }
                
        }
    
        // THIENPB CODE NEW LOGIC PUSH OPTION
        $increase_option = 0;
        $reduce_option = 0;
        if(count($quote_decode->Options) < $_REQUEST['number_of_option']){
            $increase_option = $_REQUEST['number_of_option'] - count($quote_decode->Options);
            for ($i=0; $i < $increase_option ; $i++) { 
                $new_option = array (                'Finance' => 
                    array (
                    'Price' => 0,
                    'STCValue' => 0,
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
                    'CertegyApprovalNumber' => '',
                    'ClassicDeposit' => 0,
                    'ClassicRepayment' => 0,
                    'ClassicLoanNumber' => '',
                    'ClassicApprovalNumber' => '',
                    'ClassicMonths' => 
                    array (
                        'Value' => 0,
                    ),
                    ),
                    'Splits' => 0,
                    'Travel' => 0,
                    'TiltedPanels' => 0,
                    'AdditionalCableRun' => 0,
                    'ExcessHeightPanels' => 0,
                    'AdditionalInstallationCosts' => 0,
                    'AdditionalProjectCosts' => 0,
                    'RequiresElevatedWorkPlatform' => false,
                    'Accepted' => false,
                    'ID' => 0,
                    'Number' => count($quote_decode->Options),
                    'Key' => '00000000-0000-0000-0000-000000000000',
                    'Selected' => false,
                    'DisplayOrder' => count($quote_decode->Options),
                    'Size' => 0,
                    'kWp' => 0,
                    'kVA' => 0,
                    'ExportLimit' => false,
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
                );
                
                $quote_decode->Options[count($quote_decode->Options)] =  (object)$new_option;
            
                $data_option_string = json_encode($quote_decode);
            
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
                        "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    )
                );
                $result = curl_exec($curl);
                curl_close($curl);
           
                $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
                //set the url, number of POST vars, POST data

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
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
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                        "Cache-Control: max-age=0"
                    )
                );
                
                $quote = curl_exec($curl);
                curl_close($curl);

                //thienpb code return if update false
                return_message($quote);
                $quote_decode = json_decode($quote);
            }
        }else if(count($quote_decode->Options) > $_REQUEST['number_of_option']){
            $reduce_option = count($quote_decode->Options) - $_REQUEST['number_of_option'];
            for ($i=0; $i < $reduce_option ; $i++) { 
                unset($quote_decode->Options[(count($quote_decode->Options)-1)]);
            }
            
        }
        //END
        $specialMess = '';
        for($i=0;$i<count($quote_decode->Options);$i++){
            if(count($quote_decode->Options) == $_REQUEST['number_of_option']){
                if($_REQUEST['sl_option_'.$i] == 'no') continue;
            }
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
            $quote_decode ->Options[$i]->Finance->PPrice =  ($_GET["price_option_".($i)] != "")?$_GET["price_option_".($i)]:$sgPrices[$st]['option'.($i)];
            
            if(isset($_REQUEST['vicRebate'])){
                if($_REQUEST['vicRebate'] == 'yes' && $_REQUEST['loanRebate'] == 'yes'){
                    $quote_decode ->Options[$i]->Finance->Rebate = array(
                        "ID" => 8,
                        "Code" => "SOLARVIF50",
                        "Name" => "Solar VIC $1,850 Rebate & Interest free loan",
                        "EXOSystemCode" => "P-PV SYSTEM",
                        "Active" => true,
                        "FileCategories" => array()
                    );
                    $quote_decode ->Options[$i]->Finance->RebateAmount = 1850.0;
                }
                if($_REQUEST['vicRebate'] == 'yes' && $_REQUEST['loanRebate'] == 'no'){
                    $quote_decode ->Options[$i]->Finance->Rebate = array(
                        "ID" => 7,
                        "Code" => "SOLARVRB50",
                        "Name" => "Solar VIC $1,850 Rebate",
                        "EXOSystemCode" => "P-PV SYSTEM",
                        "Active" => true,
                        "FileCategories" => array()
                    );
                    $quote_decode ->Options[$i]->Finance->RebateAmount = 1850.0;
                }
            }

            if($_GET['option_inverter_'.$i] != $quote_decode->Options[$i]->Configurations[0]->Inverter->ID 
            || $_GET['option_model_'.$i] != $quote_decode->Options[$i]->Configurations[0]->Panel->ID 
            || (int)$_GET['option_total_panel_'.$i] != $quote_decode->Options[$i]->Configurations[0]->NumberOfPanels){
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

                //thienpb code return if update false
                return_message($result);
                
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

                //thienpb code return if update false
                return_message($result);
                
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

                //thienpb code return if update false
                return_message($result);

                $data_option_string = json_decode($result);

                unset($quote_decode->Options[$i]->Configurations[0]);
                $quote_decode->Options[$i]->Configurations[0] = $data_option_string->Configurations[0];
                
                $MaximumGroup =  $data_option_string->Configurations[0]->Trackers[0]->MaximumPanels;
                $MaximumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MaximumPanels;
                $MinimumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MinimumPanels;

                
                if($MaximumPanels == 1 || $MinimumPanels == 1){
                    $quote_decode->Options[$i]->Configurations[0]->NumberOfPanels = 1;
                    $quote_decode->Options[$i]->Configurations[0]->Number = (int)$_GET['option_total_panel_'.$i];
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->PanelCount = 1;
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Orientation = array ('Name' => 'N 0','Value' => 0);
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Pitch = array ('Name' => '0','Value' => 0);
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Shading = 0;
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Arrays = 1;
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

                    $data_result = calc_panel((int)$_GET['option_total_panel_'.$i],$MinimumPanels,$MaximumPanels,array(count($quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings),count($quote_decode->Options[$i]->Configurations[0]->Trackers[1]->Strings)),$MaximumGroup);
                    $sub_panels = $data_result['panelConfig'];
                    if(($data_option_string->Configurations[0]->Trackers[0]->MaximumPanels > $data_option_string->Configurations[0]->Trackers[1]->MaximumPanels) && (count($sub_panels[0]) != count($data_option_string->Configurations[0]->Trackers[0]->Strings))){
                        $sub_panels = array_reverse($sub_panels);
                    }
                    if($data_result['SuggestTotalPanel'] != (int)$_GET['option_total_panel_'.$i]){
                        $specialMess .= "Can't Push Option ".($i+1)." with ".(int)$_GET['option_total_panel_'.$i]." panels.(Suggestion : ".$data_result['SuggestTotalPanel']." panels.)\n";
                        if(($i+1) == count($quote_decode->Options)){
                            $specialMess .= "\nYou can use suggestions or remove options was wrong.";
                        }
                    }
                    for ($j= 0; $j < count($quote_decode->Options[$i]->Configurations[0]->Trackers) ; $j++) {
                       
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->MaximumPanels =(int)$_GET['option_total_panel_'.$i];
                        for($k = 0; $k < count($quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings) ; $k++){
                            $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->PanelCount =  ($sub_panels[$j][$k] != 0)?$sub_panels[$j][$k]:NULL;
                            $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Orientation = ($sub_panels[$j][$k] != 0)?array ('Name' => 'N 0','Value' => 0):NULL;
                            $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Pitch = ($sub_panels[$j][$k] != 0)?array ('Name' => '0','Value' => 0):NULL;
                            $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Shading = ($sub_panels[$j][$k] !=0)?0:NULL;
                            $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Arrays = ($sub_panels[$j][$k] !=0)?1:NULL;;
                        }
                    }
                }
              
            }else{
                for($j=0;$j<count($quote_decode->Options[$i]->Configurations[0]->Trackers);$j++){
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Orientation = $data_old["option".$i]["Tracker".$j]["Orientation"];
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Pitch = $data_old["option".$i]["Tracker".$j]["Pitch"];
                }
            }


            $check_tilting = false;
            $check_inveter_type = false;
            $check_battery_type = false;

            unset($quote_decode->Options[$i]->Accessories);

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

            //thienpb code return if update false
            return_message($result);
            
            $option_accessories = json_decode($result);
            $dataid = array_column($option_accessories, 'ID');
            $data_option_extra = [];
            
            if((int)$_GET['option_extra_1_'.$i] > 0){
                $datakey = array_search($_GET['option_extra_1_'.$i], $dataid);
                $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey];
            }
            if((int)$_GET['option_extra_2_'.$i] > 0){
                $datakey_2 = array_search($_GET['option_extra_2_'.$i], $dataid);
                $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_2];
            }
            if((int)$_GET['option_extra_3_'.$i] > 0){
                $datakey_3 = array_search($_GET['option_extra_3_'.$i], $dataid);
                $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
            }
            if((int)$_GET['option_extra_1_'.$i] > 0 && ((int)$_GET['option_extra_1_'.$i] == 22 || (int)$_GET['option_extra_1_'.$i] == 17)){
                if($_GET['option_inverter_type_name_'.$i] == 'S Edge 3G'){
                    $datakey_3 = array_search(568,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
               }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 5G'){
                    $datakey_3 = array_search(569,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
               }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 6G'){
                    $datakey_3 = array_search(570,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
               }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 8G'){
                    $datakey_3 = array_search(571,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
               }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 8 3P'){
                    $datakey_3 = array_search(500,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 10G'){
                    $datakey_3 = array_search(572,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
               }
                //array_reverse($data_option_extra,true);
            }
            
            //$accessories_item = count($quote_decode->Options[$i]->Accessories);
            for ($extra=0; $extra < count($data_option_extra); $extra++) { 
                $quote_decode->Options[$i]->Accessories[$extra] = array (
                        'ID' => NULL,
                        'Include' => false,
                        'DisplayOnQuote' => true,
                        'UnitPriceEnabled' => true,
                        'IncludedEnabled' => true,
                        'QuantityEnabled' => true,
                        'Quantity' => '1',
                        'Included' => true,
                        'UnitPrice' => 0,
                        'Accessory' => $data_option_extra[$extra],
                );
            }
            
            for($count_accessories = 0;$count_accessories < count($quote_decode->Options[$i]->Accessories); $count_accessories++){
                if($quote_decode->Options[$i]->Accessories[$count_accessories]->Accessory->Code == 'Tilt Frame 10-15 Degrees'){
                    if($_GET['option_tilting_'.$i] > 0 && $_GET['option_tilting_'.$i] != $quote_decode->Options[$i]->Accessories[$count_accessories]->Quantity){
                        $quote_decode->Options[$i]->Accessories[$count_accessories]->Quantity = $_GET['option_tilting_'.$i];
                    }else if($_GET['option_tilting_'.$i] == 0){
                        unset($quote_decode->Options[$i]->Accessories[$count_accessories]);
                    }
                    $check_tilting = true;
                }

                if($quote_decode->Options[$i]->Accessories[$count_accessories]->Accessory->Code == 'LG Chem RESU 10H SolarEdge & Fronius'){
                    if($_GET['option_battery_'.$i] == 0){
                        unset($quote_decode->Options[$i]->Accessories[$count_accessories]);
                    }
                    $check_battery_type = true;
                }

            }

            if($check_tilting == false && $_GET['option_tilting_'.$i] > 0){
                $accessories_item = count($quote_decode->Options[$i]->Accessories);
                $datakey = array_search(10,$dataid);
                $data_tilting = $option_accessories[$datakey];
                $quote_decode->Options[$i]->Accessories[$accessories_item] =  array (
                                                                            'ID' => NULL,
                                                                            'Accessory' =>  $data_tilting,
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

            // if($check_inveter_type == false && (strpos($_GET['option_inverter_type_name_'.$i],'Primo ') !== false || strpos($_GET['option_inverter_type_name_'.$i],'Symo ') !== false)){
            //     $accessories_item = count($quote_decode->Options[$i]->Accessories);
            //     $quote_decode->Options[$i]->Accessories[$accessories_item] = array (
            //                                                             'ID' => NULL,
            //                                                             'Quantity' => 1,
            //                                                             'UnitPrice' => 0,
            //                                                             'Included' => true,
            //                                                             'DisplayOnQuote' => true,
            //                                                             'Accessory' => 
            //                                                             array (
            //                                                             'ID' => 387,
            //                                                             'Code' => 'Fronius Service Partner Plus 10YR Warranty',
            //                                                             'Category' => 
            //                                                             array (
            //                                                                 'ID' => 3,
            //                                                                 'Code' => 'OTHER',
            //                                                                 'Name' => 'Other',
            //                                                                 'Order' => 9,
            //                                                             ),
            //                                                             'Model' => 'Fronius Service Partner Plus 10YR Warranty',
            //                                                             'DisplayOnQuote' => true,
            //                                                             'ExoCode' => 'P-FRO-FSP-PLUS',
            //                                                             'Active' => true,
            //                                                             'Kit' => false,
            //                                                             ),
            //                                                         );
            // }

            //THIENPB code add accessories Battery
            if($check_battery_type == false && $_GET['option_battery_'.$i] != 0){
                $datakey = array_search($_GET['option_battery_'.$i],$dataid);
                $data_battery = $option_accessories[$datakey];

                if($_GET['option_battery_'.$i] == 40){
                    $battery_price = '10000';
                }

                $accessories_item = count($quote_decode->Options[$i]->Accessories);
                $quote_decode->Options[$i]->Accessories[$accessories_item] = 
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

            if(!isset($quote_decode->Options[$i]->Accessories)){
                $quote_decode->Options[$i]->Accessories[0]=array();
            }
            
        }

        //tuan code
        // for($i=0;$i<(count($quote_decode->Options) - $increase_option);$i++){
        //     for($j=0;$j<count($quote_decode->Options[$i]->Configurations[0]->Trackers);$j++){
        //         $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Orientation = $data_old["option".$i]["Tracker".$j]["Orientation"];
        //         $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[0]->Pitch = $data_old["option".$i]["Tracker".$j]["Pitch"];
        //     }
        // }
        $data_option_string = json_encode($quote_decode);
        
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
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            )
        );
        $result = curl_exec($curl);
        curl_close($curl);

        //thienpb code return if update false
        return_message($result,$specialMess);
    }
}
die();

