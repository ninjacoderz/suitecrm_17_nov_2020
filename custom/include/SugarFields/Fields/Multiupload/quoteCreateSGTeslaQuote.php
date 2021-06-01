<?php
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    header('Content-Type: application/json; charset=utf-8');
    
    $username = "matthew.wright";
    $password =  "MW@pure733";
    
    $lead_SGID = urldecode($_GET['leadID']);        
    if($lead_SGID != ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/leads/'.$lead_SGID);
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
            $password = 'WalkingElephant#256';
        }
    }else{
        die;
    }
    //END

    // step 1: create quote talest
    $url = 'https://crm.solargain.com.au/APIv2/quotes/create/'.$lead_SGID;
    
    $data = array(
    
    );
    
    $data_string = json_encode($data);
    
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$lead_SGID,
            "Cache-Control: max-age=0"
        )
    );
    
    $quote = curl_exec($curl);
    
    // step 2: update field address to quote
    $decode_result = json_decode($quote,true);
    $install_info = $decode_result["Install"];
    
    $install_info["Address"]["Street1"]  = urldecode($_GET['billing_address_street']);
    $install_info["Address"]["State"]  = urldecode($_GET['billing_address_state']);
    $install_info["Address"]["Locality"]  = urldecode($_GET['billing_address_city']);
    $install_info["Address"]["PostCode"]  = urldecode($_GET['billing_address_postalcode']);
    
    $install_encode =  json_encode( $install_info); // We place install encode here
    
    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$lead_SGID,
        )
    );
    $result = curl_exec($curl);
    
    //print_r($result);
    
    //step 3 - update option quote talest
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$result;
    
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$result,
            "Cache-Control: max-age=0"
        )
    );
    $quoteSG_id = $result;
    $quote = curl_exec($curl);
    //dung code  --- get number site detail quote and number quote solar
    $quote_decode = json_decode($quote);
    $data_result = array(
        'SiteDetailNumber' => $quote_decode->Install->ID,
        'QuoteNumber' => $quote_decode->ID,
    );
    print_r(json_encode($data_result));
    
    $quote_decode = json_decode($quote);
    
    $lead_SGID = urldecode($_GET['leadID']);
    $price_tesla = 14990;
    $meter_phase = ($_GET['meter_phase_c']) ? $_GET['meter_phase_c'] : '';
    if($meter_phase == '3'){
      $price_tesla += 500;//bonus 500
    }

    $accessories = array();
    $accessory = array();

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

    $quote_decode ->Options = 
    array (
      0 => 
      array (
        'Dirty' => true,
        'Number' => 0,
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
          'PVSTCQuantity' => 0,
          'SolarHotWaterSystemSTCQuantity' => 0,
          'STCPrice' => 39.60000000000000142108547152020037174224853515625,
          'Deeming' => 0,
          'Rating' => 0,
          'Multiplier' => 0,
          'Jan' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Feb' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Mar' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Apr' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'May' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Jun' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Jul' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Aug' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Sep' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Oct' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Nov' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Dec' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
          'Total' => 
          array (
            'Total' => 0,
            'Average' => 0,
          ),
        ),
        'RequiredAccessories' => 
        array (
        ),
        'Accessories' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Quantity' => 1,
            'UnitPrice' => $price_tesla,
            'Included' => false,
            'DisplayOnQuote' => true,
            'Accessory' => $accessory,
          ),
          1 => 
          array (
            'ID' => 0,
            'Quantity' => 1,
            'UnitPrice' => 0,
            'Included' => true,
            'DisplayOnQuote' => true,
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
              'Model' => 'Fronius 1P Smart Meter',
              'DisplayOnQuote' => true,
              'Warranty' => '2 year',
              'Features' => 'Real-time view of consumption data',
              'ExoCode' => 'P-FRO-SMART-METER-1P',
              'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
              'Active' => true,
              'Kit' => false,
            ),
          ),
        )
      ),
    );
    
    $quote_decode -> SpecialNotes =
    "";
    //Proposed Install Date
    $quote_decode -> ProposedInstallDate = array (
      "Date" => '31/12/2019', //date('d/m/Y', time() + 6*7*24*60*60)
      "Time" => "9:15 AM"
    );
    
    //check VIC
    if (urldecode($_GET['billing_address_state']) == 'VIC') {
      for($i=0;$i<count($quote_decode->Options);$i++){
        $quote_decode->Options[$i]['Finance'] = array (
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
        );

        $quote_decode->Options[$i]['Finance']['Rebate'] = array(
          "ID" => 9,
          "Code" => "SOLARVB41",
          "Name" => "Solar VIC Battery $4174 Rebate",
          "EXOSystemCode" => "P-PV SYSTEM",
          "Active" => true,
          "FileCategories" => array()
        );        
        $quote_decode->Options[$i]['Finance']['RebateAmount'] = 4174.0;
      }
    }
    
    // update next action date +12 months
    $today = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
    $next_action_date = mktime(0, 0, 0, date('n', $today)+12, date('d', $today), date('Y', $today));
    
    $quote_decode->NextActionDate = array(
      "Date" => date('d/m/Y', $next_action_date),
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$quoteSG_id,
        )
    );
    $result = curl_exec($curl);
    
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$quoteSG_id,
        )
    );
    $result = curl_exec($curl);
    
    $record = urldecode($_GET['record']);
    if($record == '')die();
    $bean = BeanFactory::getBean("AOS_Quotes", $record);
    if (!empty($bean->id)) {
        $bean->solargain_tesla_quote_number_c = $quoteSG_id;
        $bean ->sg_site_details_no_c = $data_result['SiteDetailNumber'];
        $bean->save();
    }
    die();
?>