
<?php
    $quote_id = $_REQUEST['quoteSG_ID'];
    $state = $_REQUEST['state'];
    $username = "matthew.wright";
    $password =  "MW@pure733";
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$result,
            "Cache-Control: max-age=0"
        )
    );
    $quote = curl_exec($curl);
    $quote_decode = json_decode($quote);
    if(!isset($quote_decode->ID)){
        if($username == 'paul.szuster@solargain.com.au'){
            $username = "matthew.wright";
            $password =  "MW@pure733";
        }else{
            $username = 'paul.szuster@solargain.com.au';
            $password = 'WalkingElephant#256';
        }
            $url = "https://crm.solargain.com.au/APIv2/quotes/". $quote_id;
            $curl = curl_init();
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_ENCODING , "gzip");
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:     gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
                    "Cache-Control: max-age=0"
                )
            );
    
            $quote = curl_exec($curl);
            $quote_decode = json_decode($quote);
    }
    if(!isset($quote_decode)) die();
    $date = $quote_decode->Date;
    $install = $quote_decode->Install;
   
    //Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/units/');
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
            "Cache-Control: max-age=0"
        )
    );
            
    $result = curl_exec($ch);
    curl_close ($ch);
        
    $units = json_decode($result);
    $dataid = array_column($units, 'Name');
    $datakey = array_search($state, $dataid);
    $unit = $units[$datakey];
        
        $html1 = '<tr class="sg_STCs_get"><td>PV STCs:</td>';
        $per_stc = '<tr class="sg_per_STCs"><td>Per STC:</td>';
        $stc_price = '<tr class="sg_STCs_price"><td>STC $:</td>';

        for($i = 0;$i < count($quote_decode->Options); $i++){

        $options = $quote_decode->Options[$i];

        $quote = array("Option"=>$options,"Install"=>$install,"Date"=> $date,"Unit"=>$unit);
        $data_option_string = json_encode($quote);
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/quotes/yield');
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
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
                    "Cache-Control: max-age=0"
                ));

                $result = curl_exec($curl);
                if (curl_errno($curl)) {
                    echo 'Error:' . curl_error($curl);
                }
                curl_close ($curl);
                $get_stc_sg = json_decode($result);
    
            $html1 .= "<td>". $get_stc_sg->PVSTCQuantity.'</td>';
            $per_stc .= "<td>".($get_stc_sg->STCPrice) / 1.1.'</td>';
            $stc_price .= "<td id='stc_price_".$i."'>". $get_stc_sg->PVSTCQuantity * ($get_stc_sg->STCPrice / 1.1).'</td>';
            // . ((($get_stc_sg->PVSTCQuantity * $get_stc_sg->STCPrice * 10) / 100 ) + $get_stc_sg->PVSTCQuantity * $get_stc_sg->STCPrice) .'
    }

    $html1 .= "</tr>";
    $per_stc .= "</tr>";
    $stc_price .= "</tr>";
    $return =  array("html1"=>$html1,"per_stc"=>$per_stc,"stc_price"=>$stc_price);
    echo json_encode($return);
    ?>