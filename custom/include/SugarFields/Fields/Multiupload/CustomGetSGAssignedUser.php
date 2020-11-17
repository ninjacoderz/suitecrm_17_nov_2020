<?php
$quote_id =$_GET['quote_solorgain'];
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
curl_close($curl);

$quote_decode = json_decode($quote);
if(!isset($quote_decode->ID)){
    if($username == 'paul.szuster@solargain.com.au'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'Baited@42';
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
        $user = $quote_decode->QuotedByUser->Name;
        curl_close($curl);
}
$user = $quote_decode->QuotedByUser->Name;
echo $user;
?>