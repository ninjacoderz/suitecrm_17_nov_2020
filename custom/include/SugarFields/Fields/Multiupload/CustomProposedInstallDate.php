
<?php
$quote_id = $_REQUEST['quoteSG_ID'];
$username = "matthew.wright";
$password =  "MW@pure733";

//1. login and get json quotes
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
    
        //get data from SG quote
            $url = "https://crm.solargain.com.au/APIv2/quotes/". $quote_id;
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
    $proposed_date = $_REQUEST['proposed_date'];

    if($proposed_date == ''){
        unset($quote_decode ->ProposedInstallDate);
    }else{
       $proposed_date = date('d/m/Y h:i A', strtotime(str_replace('/','-',$proposed_date)));
       $proposed_date = explode(" ",$proposed_date); //21/2/2019 09:44
       if($proposed_date >1){
               $quote_decode ->ProposedInstallDate = array (
                   "Date" =>  $proposed_date[0], //date('d/m/Y', time() + 6*7*24*60*60)
                   "Time" => $proposed_date[1]." ".$proposed_date[2],
               );
       }
    }
    // Thienpb fix next action date +12 months
    // $today = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
    // $next_action_date = mktime(0, 0, 0, date('n', $today)+12, date('d', $today), date('Y', $today));

    // $quote_decode->NextActionDate = array(
    // "Date" => date('d/m/Y', $next_action_date),
    // "Time" => "9:15 AM"
    // );
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
        )
    );
    $result = curl_exec($curl);
    ?>