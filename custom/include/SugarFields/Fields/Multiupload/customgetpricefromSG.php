<?php

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $username = "matthew.wright";
    $password =  "MW@pure733";

    $quote_id = $_GET['sg_quote_id'];


    if($quote_id != ""){
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
        $quote_decode = json_decode($quote);

        //Thienpb code for change account if download false
        if(!isset($quote_decode->ID)){
            
            $username = 'paul.szuster@solargain.com.au';
            $password = 'Baited@42';
            
            //get data from SG quote
            $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
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
                    "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
                    "Cache-Control: max-age=0"
                )
            );
            $quote = curl_exec($curl);
            $quote_decode = json_decode($quote);
            //END
        }
        //END

        if(!isset($quote_decode)) die();
        $options = $quote_decode->Options;
        $html = '<tr class="sg_price_get"><td>SG Price:</td>';
        $html_inverter = '<tr class="sg_price_get"><td>Inverter Model:</td>';
        $model = '';
        $error = '';
        for($i = 0;$i < 6;$i++){
            if(isset($options[$i])){
                $html .= "<td>". $options[$i]->Finance->PPrice.'</td>';
                $html_inverter .= "<td>". $options[$i]->Configurations[0]->Inverter->Name."</td>";
                if(strpos(strtolower($options[$i]->Configurations[0]->Inverter->Name),strtolower('Fronius Primo')) !== false){
                    if($model != '' && $model != 'Fronius_Primo'){
                        $error ='error';
                    }else{
                        $error ='';
                        $model = 'Fronius_Primo';
                    }
                   
                }elseif(strpos(strtolower($options[$i]->Configurations[0]->Inverter->Name),strtolower('SolarEdge')) !== false){
                    if($model != '' && $model != 'SolarEdge'){
                        $error ='error';
                    }else{
                        $error ='';
                        $model = 'SolarEdge';
                    }
                }elseif(strpos(strtolower($options[$i]->Configurations[0]->Inverter->Name),strtolower('Fronius Symo')) !== false){
                    if($model != '' && $model != 'Fronius_Symo'){
                        $error ='error';
                    }else{
                        $error ='';
                        $model = 'Fronius_Symo';
                    }
                }
            }else{
                $html .= "<td></td>";
                $html_inverter .= "<td></td>";
            }
        }
        $html .= "</tr>";
        $html_inverter .= "</tr>";
        $return =  array("html"=>$html,"html_inverter"=>$html_inverter,"model"=>$model,"error"=>$error);
        echo json_encode($return);

    }
?>