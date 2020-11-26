<?php
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    require_once(dirname(__FILE__).'/simple_html_dom.php');

//get parameter from ajax
    $quoteSG_id = $_GET['quoteSG_ID'];
    $option_choose = $_GET['option_choose'];
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
        return $result;
    }
//END

//retrieve quote
    $GLOBALS['username'] = "matthew.wright";
    $GLOBALS['password'] =  "MW@pure733";
//END

//get data from SG quote
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
    $quoteSG = curlSG('GET','',$url,$quoteSG_id);
    $quote_decode = json_decode($quoteSG);
    $decode_result = json_decode($quoteSG,true);
//END

//Thienpb code for change account if download false
    if(!isset($quote_decode->ID)){
        $GLOBALS['username'] = 'paul.szuster@solargain.com.au';
        $GLOBALS['password'] = 'S0larga1n$';
        //get data from SG quote
        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
        $quoteSG = curlSG('GET','',$url,$quoteSG_id);
        $quote_decode = json_decode($quoteSG);
        $decode_result = json_decode($quoteSG,true);
    }
//END

$data_get = array();

$data_get['Option'] = $quote_decode->Options[$option_choose];
$data_get['Install'] = $quote_decode->Install;
$data_get['Date'] = $quote_decode->Date;
$data_get['Unit'] = $quote_decode->QuotedByUnit;

$url = 'https://crm.solargain.com.au/APIv2/quotes/yield';
$quoteSG = curlSG('POST',json_encode($data_get),$url,$quoteSG_id);
$quote_decode = json_decode($quoteSG);

echo (int)$quote_decode->Total->Total;

