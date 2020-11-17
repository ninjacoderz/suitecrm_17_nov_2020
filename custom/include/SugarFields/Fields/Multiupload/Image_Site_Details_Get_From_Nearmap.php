<?php
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    require_once(dirname(__FILE__).'/simple_html_dom.php');

//get parameter from ajax
    $quoteSG_id = $_GET['quoteSG_ID'];
    $siteDetail_id = $_GET['siteDetail_ID'];
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
        if($type == 'GET') return $result;
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
        $GLOBALS['password'] = 'Baited@42';
        //get data from SG quote
        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_id;
        $quoteSG = curlSG('GET','',$url,$quoteSG_id);
        $quote_decode = json_decode($quoteSG);
        $decode_result = json_decode($quoteSG,true);
    }
//END

//Get
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/installs/'.$siteDetail_id.'/importimage');
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
          "Authorization: Basic ".base64_encode($GLOBALS['username'] . ":" . $GLOBALS['password']),
          "Referer: https://crm.solargain.com.au/quote/edit/".$quoteSG_id,
          "Cache-Control: max-age=0"
      )
  );
  $result = curl_exec($ch);
  curl_close($ch);
  $image_source = json_decode($result);
  ?>
  <div id="map" style="display: block;height: 300px;width: 300px;overflow: hidden;border: 1px solid #cccccc;box-sizing: border-box;position: relative;cursor: move;margin-bottom:5px;">
    <img id="drag-image" style="left: 0;pointer-events: none;position: relative;top: 0;-moz-user-select: none;"  src="data:image/jpeg;base64,<?=$image_source->Data?>" />
  </div>
