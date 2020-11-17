<?php
    header('Access-Control-Allow-Origin: *');
    ini_set('memory_limit', '-1');
    $solar_options = json_decode(htmlspecialchars_decode($_REQUEST['products']));
    $customer_price = 0;
    $extras         = 0;
    $STCs_number    = 0;
    $STCs_price     = 0;
    
    //print_r($solar_options);die;
    calc_extras($solar_options);

    function calc_extras($solar_options){
        $return_solar_options = [];
        $double_storey = array(
            "WA"  =>array('base' => 285.7, 'per_panel' => 14.29,'per_panel1' => 0,'per_panel2' => 0),
            "ACT" =>array('base' => 171.43,'per_panel' => 0,    'per_panel1' => 0,'per_panel2' => 0),
            "SA"  =>array('base' => 285.7, 'per_panel' => 14.29,'per_panel1' => 0,'per_panel2' => 0),
            "QLD" =>array('base' => 257.1, 'per_panel' => 0,    'per_panel1' => 0,'per_panel2' => 0),
            "VIC" =>array('base' => 500,   'per_panel' => 0,    'per_panel1' => 0,'per_panel2' => 0),
            "NSW" =>array('base' => 300,   'per_panel' => 0,    'per_panel1' => 0,'per_panel2' => 0),
        );

        if(count($solar_options) > 0){
            for($i = 0 ; $i < count($solar_options); $i++){
                $pm = 100;
                $curr_option =  $solar_options[$i];
                $state = $curr_option->state;
                $total_kw = (float)$curr_option->kWpanel;
                $postcode = $curr_option->postcode;
                $base_price = $curr_option->basePrice + $pm;//11290
                $total_panel = (int)$curr_option->panels;
                $choice_roof_type = $curr_option->choice_roof_type;
                if($choice_roof_type == 'Terracotta'){
                    $choice_roof_type_price = 8.25 * $total_panel;
                }else{
                    $choice_roof_type_price = 0;
                }
                $roof_pitch       = $curr_option->roof_pitch;
                if($roof_pitch == '25 - 30 Degrees'){
                    $roof_pitch_price = 220;
                }else{
                    $roof_pitch_price = 0;
                }
                $many_storeys     = $curr_option->many_storeys;
                if($many_storeys == 'Double Storey'){
                    $many_storeys_price = $double_storey[$state]['base'] +  ($double_storey[$state]['per_panel'] * $total_panel);
                }else{
                    $many_storeys_price = 0;
                }
                $extra_1          = $curr_option->extra_1;
                $extra_1_price    =  extra_($extra_1);
                $extra_2          = $curr_option->extra_2;
                $extra_2_price    =  extra_($extra_2);

                $extras =  $choice_roof_type_price + $roof_pitch_price +  $many_storeys_price + $extra_1_price + $extra_2_price;

                $STCs_number = getSTCs($total_kw,$postcode);
                $STCs_price = $STCs_number * 35;

                $net_price = 0 ;
                $net_price = $base_price + $extras;

                $gross_price = 0 ;
                $gross_price = $net_price + $STCs_price;

                $inc_per = 0;
                switch ($state) {
                    case 'VIC':case 'NSW':
                        $inc_per = 0.055;
                    break;
                    case 'WA':
                        $inc_per = 0.05;
                        break;
                    case 'QLD':case 'ACT':
                        $inc_per = 0.053;
                        break;
                    case 'SA':
                        $inc_per = 0.054;
                    break;
                    // default :
                    //     $inc_per = 0.0267;
                    //     break;
                }
                // $pe_increase = (float)(($net_price + $STCs_price) * $inc_per);

                $customer_price = $net_price;// + $pe_increase;

                $customer_price = substr_replace((int)$customer_price,"90",-2);

                $return_solar_options = $solar_options;
                $return_solar_options[$i]->customer_price =  $customer_price;
                $return_solar_options[$i]->STCs_number    =  $STCs_number;
                $return_solar_options[$i]->STCs_price     =  $STCs_price;
                $return_solar_options[$i]->gross_price    =  $customer_price +  $STCs_price;
            }
        }

        echo json_encode($return_solar_options);
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
        else if($extra == 'Sungrow Three Phase Smart Meter DTSU666'){
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


?>