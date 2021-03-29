<?php
$type = $_POST["type"];
$geoAssignments = $_POST["geoAssignments"];
// $type = 'gp_cal';
// $geoAssignments = [
//   "WH-210000971",
//   "WH-180034025",
//   "WH-180034118"
// ];
$returnValues = [];

if ($type == 'gp_cal' && (count($geoAssignments)>0)) {
  date_default_timezone_set('Africa/Lagos');
  set_time_limit(0);
  ini_set('memory_limit', '-1');
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Origin: https://geocreation.com.au';
  $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
  $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
  $headers[] = 'Content-Type: application/x-amz-json-1.1';
  $headers[] = 'Accept: */*';
  $headers[] = 'Sec-Fetch-Site: cross-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Referer: https://geocreation.com.au/';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  $result_data = json_decode($result);
  $accesstoken =  $result_data->AuthenticationResult->AccessToken;
  $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Origin: https://geocreation.com.au';
  $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
  $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
  $headers[] = 'Content-Type: application/x-amz-json-1.1';
  $headers[] = 'Accept: */*';
  $headers[] = 'Sec-Fetch-Site: cross-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Referer: https://geocreation.com.au/';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);


  $param = array (
      'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
      'AuthFlow' => 'REFRESH_TOKEN_AUTH',
      'AuthParameters' => 
      array (
      'REFRESH_TOKEN' => $RefreshToken,
      'DEVICE_KEY' => NULL,
      ),
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Origin: https://geocreation.com.au';
  $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
  $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
  $headers[] = 'Content-Type: application/x-amz-json-1.1';
  $headers[] = 'Accept: */*';
  $headers[] = 'Sec-Fetch-Site: cross-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Referer: https://geocreation.com.au/';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  $IdToken =  $result_data->AuthenticationResult->IdToken;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://api.geocreation.com.au/api/users/58e18e9b79c887010004f715');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = 'Connection: keep-alive';
  $headers[] = 'Pragma: no-cache';
  $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Authorization: token '.$IdToken;
  $headers[] = 'Origin: https://geocreation.com.au';
  $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
  $headers[] = 'Accept: */*';
  $headers[] = 'Sec-Fetch-Site: same-site';
  $headers[] = 'Sec-Fetch-Mode: cors';
  $headers[] = 'Referer: https://geocreation.com.au/';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.9';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);
  $result_json  = json_decode($result);
  $clientRef = $result_json->user->result->clients[0]->reference;
  //loop
  foreach ($geoAssignments as $key => $assignment) {
    $curl = curl_init();
    $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: hhttps://geocreation.com.au/assignments/$assignment/edit",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($curl);
    curl_close ($curl);
    $result_json  = json_decode($result);
    $returnValues[$key]['status'] = $result_json->assignment->result->status;
    $returnValues[$key]['totalValue'] = $result_json->assignment->result->certificateBundles[0]->value;
  }
} 

echo json_encode($returnValues);
