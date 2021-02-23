<?php

function create_solar_quote($SGleadID,$quoteSuite) {
    // if ($SGleadID == "") {return "00000";}
    // else return "654321";
    // die();
    global $current_user;
    $username = $password = "";
    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'S0larga1n$';
    }

        //Check set account sg

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
    $SGquote_ID = $result;

    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
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
    $quote_decode = json_decode($quote);
    curl_close ($curl);
    //Proposed Install Date
    $quote_decode -> ProposedInstallDate = array (
        "Date" => '31/12/2021', //date('d/m/Y', time() + 6*7*24*60*60)
        "Time" => "9:15 AM"
    );
    
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
    curl_close ($curl);

    return $SGquote_ID;
    //THIENPB UPDATE
}

function update_solar_quote($SGquote_ID, $quoteSuite) {
  global $current_user;
  global $sugar_config;
  $main_url = $sugar_config['site_url'];
  
  $username = $password = "";

  if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
      $username = "matthew.wright";
      $password =  "MW@pure733";
  }else{
      $username = 'paul.szuster@solargain.com.au';
      $password = 'S0larga1n$';
  }
  //THIENPB UPDATE
  $option_models = array(
      'Jinko 330W Mono PERC HC' => '149',
    //   'Jinko 370W Cheetah Plus JKM370M-66H' => '171',
      // 'Q CELLS Q.MAXX 330W' => '156',
      'Q CELLS Q.MAXX-G2 350W'=>'185',
      // 'Q CELLS Q.PEAK DUO G6+ 350W' => '173',
      // 'Sunpower Maxeon 2 350' => '144',
      // 'Sunpower Maxeon 3 395' => '167',
      // 'Sunpower X22 360W'=> '110',
      'Sunpower Maxeon 3 400W'=> '145',
    //   'Sunpower P3 325 BLACK' => '174',            
    'Sunpower P3 370 BLACK' => '193',                    
  );

  $option_inverters = array(
      'Primo 3'=>'274',
      'Primo 4'=>'275',
      'Primo 5'=>'269',
      'Primo 6'=>'277',
      'Primo 8.2'=>'278',
      'Symo 5'=>'273',
      'Symo 6'=>'282',
      'Symo 8.2'=>'284',
      'Symo 10'=>'285',
      'Symo 15'=>'287',
      'SYMO 20'=>'289',
      'S Edge 3G'=>'292',
      'S Edge 5G'=>'292',
      'S Edge 6G'=>'292',
      'S Edge 8G'=>'292',
      'S Edge 8 3P'=>'292',
      'S Edge 10G'=>'292',
      'IQ7 plus'=>'201',
      //'IQ7'=>'200',
      'IQ7X'=>'229',
      'SolarEdge with P500'=>'168',
      'SolarEdge with P401'=>'292',
      'SolarEdge with P370'=>'203',
      //'Growatt 3'=>'233',
      // 'Growatt 5'=>'213',
      // 'Growatt 6'=>'230',
      // 'Growatt 8.2'=>'247',
      'Sungrow 3'=>'223',
      'Sungrow 5'=>'259',
      'Sungrow 8'=>'257',
      'Sungrow 10 3P'=>'226',
      'Sungrow 15 3P'=>'241'
  );
  $option_extras = array(   'Fro. Smart Meter (1P)' => '1',
      'Fro. Smart Meter (3P)' => '2',
      'Fronius Service Partner Plus 10YR Warranty' => '387',
      'Switchboard UPG' => '',
      'ENPHS Envoy-S Met.' => '13',
      'SE Smart Meter' => '22',
      'SE Wifi' => '17',
      'Sungrow Smart Meter (1P)' => '413',
      // 'Sungrow Smart Meter (3P)' => '414'
      'Sungrow Three Phase Smart Meter DTSU666' => '524'
  );
  $option_battery = array( 'LG Chem RESU 10H SolarEdge & Fronius' => '40',);

  $inverterType = '';
  $totalPanel = '';
  $panelType =  '';
  $postcode = $quoteSuite->install_address_postalcode_c;
  $state = $quoteSuite->install_address_state_c;
  
  $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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

  $quote_decode = json_decode($quote);
  unset($quote_decode->Options[0]);
  //END
  
  //SETUP  DEFAULT OPTIONS
  $pe_pricing_options = new pe_pricing_options();
  $pe_pricing_options->retrieve("406fbeb4-0614-3bcd-7e15-5fbdea690303");
  $defaultOptions = json_decode(htmlspecialchars_decode($pe_pricing_options->pricing_option_input_c),true);

  $curl = curl_init();
  $url = $main_url.'/index.php?entryPoint=popularSolarBasePrice&state='.strtoupper($state);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
  $jsonPrice = json_decode(curl_exec($curl),true);

  for($i = 0; $i < 6 ; $i++){
      $inverterType = $defaultOptions['inverter_type_'.($i+1)];
      $totalPanel = $defaultOptions['total_panels_'.($i+1)];
      $panelType =  $defaultOptions['panel_type_'.($i+1)];
      $basePrice = (int)getBasePrice($panelType,$inverterType,$totalPanel,$jsonPrice);

      if($basePrice > 0){
          $new_option = array (                
              'Finance' => 
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
                  'Number' => $i,
                  'Key' => '00000000-0000-0000-0000-000000000000',
                  'Selected' => false,
                  'DisplayOrder' => $i,
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
      } 
  }
  
  $data_option_string = json_encode($quote_decode);
  
  $curl = curl_init();
  $url = "https://crm.solargain.com.au/APIv2/quotes/";
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_option_string);
  curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
  ////return_message($quote);

  $quote_decode = json_decode($quote);

  for($i = 0; $i < 6 ; $i++){
      $inverterType = $defaultOptions['inverter_type_'.($i+1)];
      $totalPanel = $defaultOptions['total_panels_'.($i+1)];
      $panelType =  $defaultOptions['panel_type_'.($i+1)];
      $sgPrices = calc_price($basePrice,$panelType,$inverterType,$totalPanel,$postcode,$state);
      $quote_decode->Options[$i]->Finance->PPrice =  (int)$sgPrices;

      $arr = array (
          'Configurations' => 
          array (
          0 => array (
              'ID' => NULL,
              'MinimumPanels' => 0,
              'MaximumPanels' => (int)$totalPanel,
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
              'NumberOfPanels' => (int)$totalPanel,
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
      ////return_message($result);
      
      $optionPanels = json_decode($result);
      $dataid = array_column($optionPanels, 'ID');
      $datakey = array_search($option_models[$panelType], $dataid);
      $dataPanel = $optionPanels[$datakey];

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
      //return_message($result);
      
      $inverters= json_decode($result);
      $dataid = array_column($inverters, 'ID');

      if($panelType == 'Sunpower Maxeon 3 400W' && strpos($inverterType,'S Edge') !== false ){
          $datakey = array_search($option_inverters['SolarEdge with P500'], $dataid);
      }else{
          $datakey = array_search($option_inverters[$inverterType], $dataid);
      }
      $dataInverter = $inverters[$datakey];

      $arr['Configurations'][0]['Inverter'] = $dataInverter;
      $arr['Configurations'][0]['Panel']  = $dataPanel;
      $data_option_string = json_encode($arr);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/quotes/calculate?postcode='.$postcode);
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
      //return_message($result);

      $data_option_string = json_decode($result);

      unset($quote_decode->Options[$i]->Configurations[0]);
      $quote_decode->Options[$i]->Configurations[0] = $data_option_string->Configurations[0];
      
      $MaximumGroup =  $data_option_string->Configurations[0]->Trackers[0]->MaximumPanels;
      $MaximumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MaximumPanels;
      $MinimumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MinimumPanels;

      
      if($MaximumPanels == 1 || $MinimumPanels == 1){
          $quote_decode->Options[$i]->Configurations[0]->NumberOfPanels = 1;
          $quote_decode->Options[$i]->Configurations[0]->Number = (int)$totalPanel;
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
        $tempCov = $dataPanel->TempCoV;
        $covPer = $dataPanel->Voc;
        $COV = ($covPer * ((25 * $tempCov)+100)/100);
        $max = (int)(600/$COV);
        if($max < $MaximumPanels){
            $MaximumPanels = $max;
        }
         /** End */

          $data_result = calc_panel((int)$totalPanel,$MinimumPanels,$MaximumPanels,array(count($quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings),count($quote_decode->Options[$i]->Configurations[0]->Trackers[1]->Strings)),$MaximumGroup);
          $sub_panels = $data_result['panelConfig'];
          if(($data_option_string->Configurations[0]->Trackers[0]->MaximumPanels > $data_option_string->Configurations[0]->Trackers[1]->MaximumPanels) && (count($sub_panels[0]) != count($data_option_string->Configurations[0]->Trackers[0]->Strings))){
              $sub_panels = array_reverse($sub_panels);
          }
          if($data_result['SuggestTotalPanel'] != (int)$totalPanel){
              $specialMess .= "Can't Push Option ".($i+1)." with ".(int)$totalPanel." panels.(Suggestion : ".$data_result['SuggestTotalPanel']." panels.)\n";
              if(($i+1) == count($quote_decode->Options)){
                  $specialMess .= "\nYou can use suggestions or remove options was wrong.";
              }
          }
          for ($j= 0; $j < count($quote_decode->Options[$i]->Configurations[0]->Trackers) ; $j++) {
              
              $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->MaximumPanels =(int)$totalPanel;
              for($k = 0; $k < count($quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings) ; $k++){
                  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->PanelCount =  ($sub_panels[$j][$k] != 0)?$sub_panels[$j][$k]:NULL;
                  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Orientation = ($sub_panels[$j][$k] != 0)?array ('Name' => 'N 0','Value' => 0):NULL;
                  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Pitch = ($sub_panels[$j][$k] != 0)?array ('Name' => '0','Value' => 0):NULL;
                  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Shading = ($sub_panels[$j][$k] !=0)?0:NULL;
                  $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Arrays = ($sub_panels[$j][$k] !=0)?1:NULL;;
              }
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

      $option_accessories = json_decode($result);
      $dataid = array_column($option_accessories, 'ID');
      $data_option_extra = [];

      if(strpos($inverterType,'Primo ') !== false ){
          $extraPrice1    =  $option_extras['Fro. Smart Meter (1P)'];
          $extraPrice2    =  $option_extras['Fronius Service Partner Plus 10YR Warranty'];
      }else if( strpos($inverterType,'Symo ') !== false){
          $extraPrice1    =  $option_extras['Fro. Smart Meter (3P)'];
          $extraPrice2    =  $option_extras['Fronius Service Partner Plus 10YR Warranty'];
      }else if(strpos($inverterType,'S Edge ') !== false){
          $extraPrice1    =  $option_extras['SE Wifi'];
          $extraPrice2    =  $option_extras['SE Smart Meter'];
      }else if(strpos($inverterType,'Sungrow ') !== false){
          if(strpos($inverterType,'3P') !== false){
              // $extraPrice1    =  $option_extras['Sungrow Smart Meter (3P)'];
              $extraPrice1    =  $option_extras['Sungrow Three Phase Smart Meter DTSU666'];
          }else {
              $extraPrice1    =  $option_extras['Sungrow Smart Meter (1P)'];
          }
      }
      if((int)$extraPrice1 > 0){
          $datakey = array_search($extraPrice1, $dataid);
          $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey];
      }
      if((int)$extraPrice2 > 0){
          $datakey_2 = array_search($extraPrice2, $dataid);
          $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_2];
      }
      
      if((int)$extraPrice1 > 0 && ((int)$extraPrice1 == 22 || (int)$extraPrice1 == 17)){
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
  }

  $data_option_string = json_encode($quote_decode);
      
  $curl = curl_init();
  $url = "https://crm.solargain.com.au/APIv2/quotes/";
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_option_string);
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
          "Content-Length: " .strlen($data_option_string),
          "Origin: https://crm.solargain.com.au",
          "Authorization: Basic ".base64_encode($username . ":" . $password),
          "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
      )
  );
  $result = curl_exec($curl);
  curl_close($curl);
  //END
  // return $SGquote_ID;    
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
  
function calc_price($basePrice,$panelType,$inverterType,$totalPanel,$postcode = '3056',$state){
  $pm = 100;

  $result = preg_match_all('/\d+/',$panelType, $matches);

  if($result){
      if(count($matches[0]) > 1){
          $panel_kw = $matches[0][1];
      }else{
          $panel_kw = $matches[0][0];
      }
  }
  $extraPrice1 = $extraPrice2 = 0;
  if(strpos($inverterType,'Primo ') !== false || strpos($inverterType,'Symo ') !== false){
      $extraPrice1    =  extra_('Fro. Smart Meter (1P)');
      $extraPrice2    =  extra_('Fronius Service Partner Plus 10YR Warranty');
  }else if(strpos($inverterType,'S Edge ') !== false){
      $extraPrice1    =  extra_('SE Wifi');
      $extraPrice2    =  extra_('SE Smart Meter');
  }else if(strpos($inverterType,'Sungrow ') !== false){
      $extraPrice1    =  extra_('Sungrow Smart Meter (1P)');
  }

  $total_kw = ((int)$panel_kw * (int)$totalPanel)/1000;
  $stcNumber = getSTCs($total_kw,$postcode);
  $STCsPrice = $stcNumber * 35;

  $extras = $pm + $extraPrice1 + $extraPrice2;

  $netPrice = 0 ;
  $netPrice = $basePrice + $extras;

  $grossPrice = 0 ;
  $grossPrice = $netPrice + $STCsPrice;

  $incPer = 0;
  switch ($state) {
      case 'VIC':case 'NSW':
          $incPer = 0.055;
          break;
      case 'WA':
          $incPer = 0.05;
          break;
      case 'QLD':case 'ACT':
          $incPer = 0.053;
          break;
      case 'SA':
          $incPer = 0.054;
          break;
  }

  $peIncrease = (float)(($netPrice + $STCsPrice) * $incPer);

  $customerPrice = $netPrice + $peIncrease;

  $customerPrice = substr_replace((int)$customerPrice,"90",-2);

  return $customerPrice;
}

function extra_($extra){
  if($extra == 'Fro. Smart Meter (1P)'){
      $data_return = 300;
  }
  else if($extra == 'Fro. Smart Meter (3P)'){
      $data_return = 500;
  }
  else if($extra == 'Switchboard UPG'){
      $data_return = 900;
  }
  else if($extra == 'ENPHS Envoy-S Met.'){
      $data_return = 300;
  }
  else if($extra == 'SE Smart Meter'){
      $data_return = 0;
  }
  else if($extra == 'SE Wifi'){
      $data_return = 0;
  }
  else if($extra == 'Fronius Service Partner Plus 10YR Warranty'){
      $data_return = 100;
  }
  else if($extra == 'Sungrow Smart Meter (1P)'){
      $data_return = 300;
  }
  else if($extra == 'Sungrow Three Phase Smart Meter DTSU666'){//'Sungrow Smart Meter (3P)'){
      $data_return = 400;
  }
  return (int)$data_return;
}

function getSTCs($total_kw,$postcode){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu/stc');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"sguType":"SolarDeemed","expectedInstallDate":"2020-12-31T00:00:00.000Z","ratedPowerOutputInKw":'.$total_kw.',"deemingPeriod":"ELEVEN_YEARS","postcode":"'.$postcode.'","sguDisclaimer":true,"useDefaultResourceAvailability":"true","sguTypeOptions":[{"sguDeemingPeriodsStrategies":[{"years":[2016,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fifteen years","name":"FIFTEEN_YEARS"}]},{"years":[2017],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fourteen years","name":"FOURTEEN_YEARS"}]},{"years":[2018],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Thirteen years","name":"THIRTEEN_YEARS"}]},{"years":[2019],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Twelve years","name":"TWELVE_YEARS"}]},{"years":[2020],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}]},{"years":[2021],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Ten years","name":"TEN_YEARS"}]},{"years":[2022],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Nine years","name":"NINE_YEARS"}]},{"years":[2023],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eight years","name":"EIGHT_YEARS"}]},{"years":[2024],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Seven years","name":"SEVEN_YEARS"}]},{"years":[2025],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Six years","name":"SIX_YEARS"}]},{"years":[2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - solar (deemed)","name":"SolarDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - wind (deemed)","name":"WindDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - hydro (deemed)","name":"HydroDeemed"}],"deemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}],"helpWithSolarCreditsVisible":true}');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
      "Content-Type: application/json; charset=UTF-8",
      "Accept: application/json, text/javascript, */*; q=0.01",
      "Accept-Language:  en-US,en;q=0.9",
      "Accept-Encoding:   gzip, deflate, br",
      "Connection: keep-alive",
      "Origin: https://www.rec-registry.gov.au",
      "Referer: https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu-stc-calculator"
  ));
  $result = curl_exec($ch);
  curl_close($ch);

  $data_return =  json_decode($result);
  if($data_return->status == 'Completed'){
      return (int)$data_return->result->numberOfStcs;
  }else{
      return 0;
  }
}

function getBasePrice($panel_type,$inverter_type,$total_panel,$dataJSON){
  
  if($dataJSON == '')die;

  $list_panel = $dataJSON[$panel_type];
  $list_suggest = '';
  $temp = [];
  $check = '';

  foreach($list_panel as $itemkey => $itemVal){
      if(strpos($itemkey,$total_panel.' panels') !== false){
          for($i = 0 ; $i < count($itemVal) ; $i++){
              if($itemVal[$i]['inverter'] == $inverter_type){
                  $list_suggest = $itemVal[$i]['price'];
                  break 2;
              }
          }
      }
  }

  return  (int)$list_suggest;
}

