<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 8/4/17
 * Time: 5:59 PM
 */
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
header('Content-Type: application/json; charset=utf-8');

$username = "matthew.wright";
$password =  "MW@pure733";

$leadID = urldecode($_GET['leadID']);
$url = 'https://crm.solargain.com.au/APIv2/quotes/create/'.$leadID;

//set the url, number of POST vars, POST data

$data = array(

);

$data_string = json_encode($data);

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
        "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$leadID,
        "Cache-Control: max-age=0"
    )
);

$quote = curl_exec($curl);
// We need update Install detail
$decode_result = json_decode($quote,true);
$install_info = $decode_result["Install"];

$install_info["Address"]["Street1"]  = urldecode($_GET['primary_address_street']);
$install_info["Address"]["State"]  = urldecode($_GET['primary_address_state']);
$install_info["Address"]["Locality"]  = urldecode($_GET['primary_address_city']);
$install_info["Address"]["PostCode"]  = urldecode($_GET['primary_address_postalcode']);

$install_encode =  json_encode( $install_info); // We place install encode here

//print_r($quote);
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
        "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$leadID,
    )
);
$result = curl_exec($curl);
//$result = json_encode(curl_exec($curl));

print_r($result);

$url = 'https://crm.solargain.com.au/APIv2/quotes/'.$result;
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
        "Referer: https://crm.solargain.com.au/quote/edit/".$result,
        "Cache-Control: max-age=0"
    )
);
$quote_id = $result;
$quote = curl_exec($curl);

//thienpb code here
$suite_field = urldecode($_GET['suite_field']);
$st = urldecode($_GET['primary_address_state']);;
$sgPrices = array("VIC"=> array("option1"=>7490,"option2"=>8590,"option3"=>10690,'option4'=>9790,"option5"=>11390,'option6'=>14590),
                  "SA" => array("option1"=>6890,"option2"=>7790,"option3"=>9490,'option4'=>8990,"option5"=>10590,'option6'=>13290),
                  "NSW"=> array("option1"=>7290,"option2"=>8290,"option3"=>9990,'option4'=>9690,"option5"=>10990,'option6'=>14490),
                  "ACT"=> array("option1"=>7890,"option2"=>8790,"option3"=>10590,'option4'=>9990,"option5"=>11590,'option6'=>14490),
                  "QLD"=> array("option1"=>6390,"option2"=>7290,"option3"=>8990,'option4'=>8690,"option5"=>9990,'option6'=>12990));

//end

/// Resubmit Quotes with options
// Thien fix - update json options date 1/10/2018
$quote_decode = json_decode($quote);
$solargain_options =
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
      'PVSTCQuantity' => 92,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 958.9950775290736828537774272263050079345703125,
        'Average' => 30.93532508158302363199254614301025867462158203125,
      ),
      'Feb' => 
      array (
        'Total' => 767.3779714667607549927197396755218505859375,
        'Average' => 27.406356123812884106882847845554351806640625,
      ),
      'Mar' => 
      array (
        'Total' => 680.039182209899081499315798282623291015625,
        'Average' => 21.93674781322255284976563416421413421630859375,
      ),
      'Apr' => 
      array (
        'Total' => 455.3865182112584761853213422000408172607421875,
        'Average' => 15.1795506070419481403632744331844151020050048828125,
      ),
      'May' => 
      array (
        'Total' => 324.27431720890666611012420617043972015380859375,
        'Average' => 10.460461845448602247188318870030343532562255859375,
      ),
      'Jun' => 
      array (
        'Total' => 254.57774860200066768811666406691074371337890625,
        'Average' => 8.48592495340002272996571264229714870452880859375,
      ),
      'Jul' => 
      array (
        'Total' => 292.115966609127326591988094151020050048828125,
        'Average' => 9.4230956970686232665457282564602792263031005859375,
      ),
      'Aug' => 
      array (
        'Total' => 416.0400712760732631068094633519649505615234375,
        'Average' => 13.4206474605184933324153462308458983898162841796875,
      ),
      'Sep' => 
      array (
        'Total' => 535.05753290828170065651647746562957763671875,
        'Average' => 17.83525109694272003935111570172011852264404296875,
      ),
      'Oct' => 
      array (
        'Total' => 734.427875995964086541789583861827850341796875,
        'Average' => 23.6912218063214226049240096472203731536865234375,
      ),
      'Nov' => 
      array (
        'Total' => 846.1887824193537426253897137939929962158203125,
        'Average' => 28.2062927473117923682366381399333477020263671875,
      ),
      'Dec' => 
      array (
        'Total' => 949.527044546612387421191670000553131103515625,
        'Average' => 30.6299046627939475229140953160822391510009765625,
      ),
      'Total' => 
      array (
        'Total' => 7214.0080889833116088993847370147705078125,
        'Average' => 237.61077989546600974790635518729686737060546875,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => NULL,
        'MinimumPanels' => 0,
        'MaximumPanels' => 20,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 20,
        'Size' => 6500,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 157,
          'Name' => 'Fronius Primo 5.0-1-I 5kW',
          'Code' => 'P-FRO-PRIMO-5.0-INT',
          'EuroEff' => 96.5,
          'MaxACOut' => 5000,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'PRIMO-5.0-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => true,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 6666,
          'InWattDCMax' => 6666,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 208,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 12,
              'ImpMod' => 12,
              'InWattMax' => 5000,
              'InWattMod' => 6133,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 209,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 12,
              'ImpMod' => 20,
              'InWattMax' => 5000,
              'InWattMod' => 6133,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1407.75,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.1984+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 130,
          'Name' => 'Q CELLS Q.PEAK DUO G5 325W',
          'Code' => 'P-Q.PEAK-DUO-G5-325',
          'Active' => true,
          'Popular' => false,
          'Model' => 'Q.Peak Duo',
          'Features' => 'PERC monocrystalline
Manufactured in Korea
Tier 1 manufacturer
Q.ANTUM technology',
          'Warranty' => '12yr Manufacturing (inc. Labour) + 25yr Performance',
          'Height' => 1.685000000000000053290705182007513940334320068359375,
          'Width' => 1,
          'PositiveEarthRequired' => false,
          'Watt' => 325,
          'Voc' => 40.39999999999999857891452847979962825775146484375,
          'Vmp' => 33.64999999999999857891452847979962825775146484375,
          'Imp' => 9.660000000000000142108547152020037174224853515625,
          'TempCoV' => 0.2800000000000000266453525910037569701671600341796875,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.36999999999999999555910790149937383830547332763671875,
          'Tolerance' => 3,
          'Cost' => 191.75,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.1695084+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 18,
            'MinimumStrings' => 0,
            'MaximumStrings' => 1,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 432.28000000000002955857780762016773223876953125,
                'PanelCount' => 10,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 18,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 432.28000000000002955857780762016773223876953125,
                'PanelCount' => 10,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => false,
    'Size' => 6500,
    'TotalPanels' => 20,
    'Travel' => $_GET['travel_km_1'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_1'],
    'AdditionalCableRun' => ($_GET['number_double_storey_panel_1'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_1'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_1'] != "")?$_GET['price_option_1']:$sgPrices[$st]['option1'],
      'PPrice' => ($_GET['price_option_1'] != "")?$_GET['price_option_1']:$sgPrices[$st]['option1'],
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
    ),
    'BusinessPPrice' => '74003643.20',
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => NULL,
      ),
    ),
  ),
  1 => 
  array (
    'Dirty' => true,
    'Number' => 1,
    'InternalNumber' => 1,
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
      'PVSTCQuantity' => 110,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 1153.17915851268116966821253299713134765625,
        'Average' => 37.19932769395745708607137203216552734375,
      ),
      'Feb' => 
      array (
        'Total' => 922.762070559615722231683321297168731689453125,
        'Average' => 32.9557882342719921098250779323279857635498046875,
      ),
      'Mar' => 
      array (
        'Total' => 817.73830780970683917985297739505767822265625,
        'Average' => 26.378655090635703572843340225517749786376953125,
      ),
      'Apr' => 
      array (
        'Total' => 547.59638935994234998361207544803619384765625,
        'Average' => 18.253212978664745236301314434967935085296630859375,
      ),
      'May' => 
      array (
        'Total' => 389.9356659991868809811421670019626617431640625,
        'Average' => 12.5785698709415125762234310968779027462005615234375,
      ),
      'Jun' => 
      array (
        'Total' => 306.126445054675286883139051496982574462890625,
        'Average' => 10.2042148351558434882235815166495740413665771484375,
      ),
      'Jul' => 
      array (
        'Total' => 351.26566596189780966597027145326137542724609375,
        'Average' => 11.331150514899928083423219504766166210174560546875,
      ),
      'Aug' => 
      array (
        'Total' => 500.2827965893839063937775790691375732421875,
        'Average' => 16.13815472868980549492334830574691295623779296875,
      ),
      'Sep' => 
      array (
        'Total' => 643.39975252515023385058157145977020263671875,
        'Average' => 21.44665841750501300566611462272703647613525390625,
      ),
      'Oct' => 
      array (
        'Total' => 883.14000757069106839480809867382049560546875,
        'Average' => 28.48838734099003744404399185441434383392333984375,
      ),
      'Nov' => 
      array (
        'Total' => 1017.531049864682017869199626147747039794921875,
        'Average' => 33.91770166215606963078244007192552089691162109375,
      ),
      'Dec' => 
      array (
        'Total' => 1141.793971494185598203330300748348236083984375,
        'Average' => 36.8320635965866358674247749149799346923828125,
      ),
      'Total' => 
      array (
        'Total' => 8674.751281301798371714539825916290283203125,
        'Average' => 285.723884964454782675602473318576812744140625,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => 0,
        'MinimumPanels' => 0,
        'MaximumPanels' => 24,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 24,
        'Size' => 7800,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 173,
          'Name' => 'Fronius Primo 6.0-1 6kW',
          'Code' => 'P-FRO-PRIMO-6.0-1',
          'EuroEff' => 96.7000000000000028421709430404007434844970703125,
          'MaxACOut' => 6000,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'Primo 6.0-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => false,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 8000,
          'InWattDCMax' => 8000,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 227,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8000,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 228,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8000,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1637.430000000000063664629124104976654052734375,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.5104+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 130,
          'Name' => 'Q CELLS Q.PEAK DUO G5 325W',
          'Code' => 'P-Q.PEAK-DUO-G5-325',
          'Active' => true,
          'Popular' => false,
          'Model' => 'Q.Peak Duo',
          'Features' => 'PERC monocrystalline
Manufactured in Korea
Tier 1 manufacturer
Q.ANTUM technology',
          'Warranty' => '12yr Manufacturing (inc. Labour) + 25yr Performance',
          'Height' => 1.685000000000000053290705182007513940334320068359375,
          'Width' => 1,
          'PositiveEarthRequired' => false,
          'Watt' => 325,
          'Voc' => 40.39999999999999857891452847979962825775146484375,
          'Vmp' => 33.64999999999999857891452847979962825775146484375,
          'Imp' => 9.660000000000000142108547152020037174224853515625,
          'TempCoV' => 0.2800000000000000266453525910037569701671600341796875,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.36999999999999999555910790149937383830547332763671875,
          'Tolerance' => 3,
          'Cost' => 191.75,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.1695084+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 24,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 518.7359999999999899955582804977893829345703125,
                'PanelCount' => 12,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 24,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 518.7359999999999899955582804977893829345703125,
                'PanelCount' => 12,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => false,
    'Size' => 7800,
    'TotalPanels' => 24,
    'Travel'=> $_GET['travel_km_2'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_2'],
    'AdditionalCableRun' => ($_GET['number_double_storey_panel_2'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_2'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_2'] != "")?$_GET['price_option_2']:$sgPrices[$st]['option2'],
      'PPrice' => ($_GET['price_option_2'] != "")?$_GET['price_option_2']:$sgPrices[$st]['option2'],
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
    ),
    'BusinessPPrice' => '74004356.00',
    'ID' => 0,
    'Selected' => false,
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => NULL,
      ),
    ),
  ),
  2 => 
  array (
    'Dirty' => true,
    'Number' => 2,
    'InternalNumber' => 2,
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
      'PVSTCQuantity' => 147,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 1552.30103675696000209427438676357269287109375,
        'Average' => 50.07422699215999983834990416653454303741455078125,
      ),
      'Feb' => 
      array (
        'Total' => 1242.1352816132593943621031939983367919921875,
        'Average' => 44.36197434333070077627780847251415252685546875,
      ),
      'Mar' => 
      array (
        'Total' => 1100.7621960893520736135542392730712890625,
        'Average' => 35.50845793836619890271322219632565975189208984375,
      ),
      'Apr' => 
      array (
        'Total' => 737.1226202389426589434151537716388702392578125,
        'Average' => 24.570754007964755061266259872354567050933837890625,
      ),
      'May' => 
      array (
        'Total' => 524.8946220808735461105243302881717681884765625,
        'Average' => 16.932084583253985243800343596376478672027587890625,
      ),
      'Jun' => 
      array (
        'Total' => 412.078552173963316818117164075374603271484375,
        'Average' => 13.7359517391321102053325375891290605068206787109375,
      ),
      'Jul' => 
      array (
        'Total' => 472.8407146666116886990494094789028167724609375,
        'Average' => 15.252926279568118417273581144399940967559814453125,
      ),
      'Aug' => 
      array (
        'Total' => 673.43352339022703745285980403423309326171875,
        'Average' => 21.723662044846033580824951059184968471527099609375,
      ),
      'Sep' => 
      array (
        'Total' => 866.08407333870445654611103236675262451171875,
        'Average' => 28.869469111290147367299141478724777698516845703125,
      ),
      'Oct' => 
      array (
        'Total' => 1188.799796834394555844482965767383575439453125,
        'Average' => 38.34838054304498911051268805749714374542236328125,
      ),
      'Nov' => 
      array (
        'Total' => 1369.704344704365894358488731086254119873046875,
        'Average' => 45.6568114901455288645593100227415561676025390625,
      ),
      'Dec' => 
      array (
        'Total' => 1536.97537163197057452634908258914947509765625,
        'Average' => 49.57985069780550446694178390316665172576904296875,
      ),
      'Total' => 
      array (
        'Total' => 11677.132133519626222550868988037109375,
        'Average' => 384.61454977090807005879469215869903564453125,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => 0,
        'MinimumPanels' => 0,
        'MaximumPanels' => 33,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 32,
        'Size' => 7800,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 145,
          'Name' => 'Fronius Primo 8.2-1 8.2kW',
          'Code' => 'P-FRO-PRIMO-8.2-1',
          'EuroEff' => 97.2000000000000028421709430404007434844970703125,
          'MaxACOut' => 8200,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'Primo 8.2-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => false,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 10933,
          'InWattDCMax' => 10933,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 190,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8300,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 191,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8300,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1887.200000000000045474735088646411895751953125,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.526+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 130,
          'Name' => 'Q CELLS Q.PEAK DUO G5 325W',
          'Code' => 'P-Q.PEAK-DUO-G5-325',
          'Active' => true,
          'Popular' => false,
          'Model' => 'Q.Peak Duo',
          'Features' => 'PERC monocrystalline
Manufactured in Korea
Tier 1 manufacturer
Q.ANTUM technology',
          'Warranty' => '12yr Manufacturing (inc. Labour) + 25yr Performance',
          'Height' => 1.685000000000000053290705182007513940334320068359375,
          'Width' => 1,
          'PositiveEarthRequired' => false,
          'Watt' => 325,
          'Voc' => 40.39999999999999857891452847979962825775146484375,
          'Vmp' => 33.64999999999999857891452847979962825775146484375,
          'Imp' => 9.660000000000000142108547152020037174224853515625,
          'TempCoV' => 0.2800000000000000266453525910037569701671600341796875,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.36999999999999999555910790149937383830547332763671875,
          'Tolerance' => 3,
          'Cost' => 191.75,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.1695084+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 25,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 518.7359999999999899955582804977893829345703125,
                'PanelCount' => 12,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 25,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 518.7359999999999899955582804977893829345703125,
                'PanelCount' => 10,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 3,
                'MaximumPanels' => 13,
                'VOC' => 0,
                'PanelCount' => 10,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => true,
    'Size' => 7800,
    'TotalPanels' => 24,
    'Travel'=> $_GET['travel_km_3'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_3'],
    'AdditionalCableRun' => ($_GET['number_double_storey_panel_3'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_3'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_3'] != "") ? $_GET['price_option_3'] : $sgPrices[$st]['option3'],
      'PPrice' => ($_GET['price_option_3'] != "") ? $_GET['price_option_3'] : $sgPrices[$st]['option3'],
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
    ),
    'BusinessPPrice' => '74004356.00',
    'ID' => 0,
    'Selected' => false,
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => 0,
      ),
    ),
  ),
  3 => 
  array (
    'Dirty' => true,
    'Number' => 3,
    'InternalNumber' => 3,
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
      'PVSTCQuantity' => 92,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 969.3359727414989492899621836841106414794921875,
        'Average' => 31.26890234649996358484713709913194179534912109375,
      ),
      'Feb' => 
      array (
        'Total' => 775.689043641956004648818634450435638427734375,
        'Average' => 27.703180130069856801355854258872568607330322265625,
      ),
      'Mar' => 
      array (
        'Total' => 687.107963399155096340109594166278839111328125,
        'Average' => 22.16477301287596901602228172123432159423828125,
      ),
      'Apr' => 
      array (
        'Total' => 459.80962931413984051687293685972690582275390625,
        'Average' => 15.3269876438046619426813776954077184200286865234375,
      ),
      'May' => 
      array (
        'Total' => 327.1934818342482458319864235818386077880859375,
        'Average' => 10.55462844626607221698577632196247577667236328125,
      ),
      'Jun' => 
      array (
        'Total' => 256.7123492241806843594531528651714324951171875,
        'Average' => 8.5570783074726879391391776152886450290679931640625,
      ),
      'Jul' => 
      array (
        'Total' => 294.5240157946901717878063209354877471923828125,
        'Average' => 9.5007747030545228739129015593789517879486083984375,
      ),
      'Aug' => 
      array (
        'Total' => 419.57396436353934632279560901224613189697265625,
        'Average' => 13.5346440117270763181522852391935884952545166015625,
      ),
      'Sep' => 
      array (
        'Total' => 539.850368697806288764695636928081512451171875,
        'Average' => 17.9950122899268762921565212309360504150390625,
      ),
      'Oct' => 
      array (
        'Total' => 741.3941740886201614557649008929729461669921875,
        'Average' => 23.91594109963291003850827109999954700469970703125,
      ),
      'Nov' => 
      array (
        'Total' => 854.6017653243825407116673886775970458984375,
        'Average' => 28.48672551081275372553136548958718776702880859375,
      ),
      'Dec' => 
      array (
        'Total' => 959.408166373278390892664901912212371826171875,
        'Average' => 30.94865052817027617493295110762119293212890625,
      ),
      'Total' => 
      array (
        'Total' => 7285.2008947974964030436240136623382568359375,
        'Average' => 239.957298030313637582366936840116977691650390625,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => 0,
        'MinimumPanels' => 0,
        'MaximumPanels' => 20,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 20,
        'Size' => 6540,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 157,
          'Name' => 'Fronius Primo 5.0-1-I 5kW',
          'Code' => 'P-FRO-PRIMO-5.0-INT',
          'EuroEff' => 96.5,
          'MaxACOut' => 5000,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'PRIMO-5.0-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => true,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 6666,
          'InWattDCMax' => 6666,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 208,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 12,
              'ImpMod' => 12,
              'InWattMax' => 5000,
              'InWattMod' => 6133,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 209,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 12,
              'ImpMod' => 20,
              'InWattMax' => 5000,
              'InWattMod' => 6133,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1407.75,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.1984+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 69,
          'Name' => 'SUNPOWER SPR327',
          'Code' => 'P-SPR327',
          'Active' => true,
          'Popular' => false,
          'Model' => 'SPR-327NE-WHT-D',
          'Features' => 'High Efficiency Monocrystalline
Maxeon, solid copper Cell
25 year parts and labour warranty
25 year Performance warranty: 98% by Year 1, then 0.25% per year so 92% by year 25 
Tier 1 manufacturer',
          'Warranty' => '25yr Manufacturing + 25yr Performance',
          'Height' => 1.5589999999999999413802242997917346656322479248046875,
          'Width' => 1.0460000000000000408562073062057606875896453857421875,
          'PositiveEarthRequired' => false,
          'Watt' => 327,
          'Voc' => 64.900000000000005684341886080801486968994140625,
          'Vmp' => 54.7000000000000028421709430404007434844970703125,
          'Imp' => 5.980000000000000426325641456060111522674560546875,
          'TempCoV' => 0.270000000000000017763568394002504646778106689453125,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.34999999999999997779553950749686919152736663818359375,
          'Tolerance' => 3,
          'Cost' => 278.41660000000001673470251262187957763671875,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.3567048+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 18,
            'MinimumStrings' => 0,
            'MaximumStrings' => 2,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 346.40375000000000227373675443232059478759765625,
                'PanelCount' => 5,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 346.40375000000000227373675443232059478759765625,
                'PanelCount' => 5,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 18,
            'MinimumStrings' => 0,
            'MaximumStrings' => 3,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 346.40375000000000227373675443232059478759765625,
                'PanelCount' => 5,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 346.40375000000000227373675443232059478759765625,
                'PanelCount' => 5,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              2 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => false,
    'Size' => 6540,
    'TotalPanels' => 20,
    'Travel'=> $_GET['travel_km_4'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_4'],
    'AdditionalCableRun' => ($_GET['number_double_storey_panel_4'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_4'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_4'] != "") ? $_GET['price_option_4'] : $sgPrices[$st]['option4'],
      'PPrice' => ($_GET['price_option_4'] != "") ? $_GET['price_option_4'] : $sgPrices[$st]['option4'],
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
    ),
    'BusinessPPrice' => '74003643.20',
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => 0,
      ),
    ),
    'ID' => 0,
    'Selected' => false,
  ),
  4 => 
  array (
    'Dirty' => true,
    'Number' => 4,
    'InternalNumber' => 4,
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
      'PVSTCQuantity' => 111,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 1165.61395105620249523781239986419677734375,
        'Average' => 37.60045003407105213000249932520091533660888671875,
      ),
      'Feb' => 
      array (
        'Total' => 932.7560271939127005680347792804241180419921875,
        'Average' => 33.31271525692545765195973217487335205078125,
      ),
      'Mar' => 
      array (
        'Total' => 826.238425625263744223047979176044464111328125,
        'Average' => 26.652852439524640004719913122244179248809814453125,
      ),
      'Apr' => 
      array (
        'Total' => 552.9151231669720800709910690784454345703125,
        'Average' => 18.4305041055657312654147972352802753448486328125,
      ),
      'May' => 
      array (
        'Total' => 393.445923648146845152950845658779144287109375,
        'Average' => 12.691803988649898116136682801879942417144775390625,
      ),
      'Jun' => 
      array (
        'Total' => 308.69327465257953235777677036821842193603515625,
        'Average' => 10.289775821752652262830451945774257183074951171875,
      ),
      'Jul' => 
      array (
        'Total' => 354.1613139151901350487605668604373931884765625,
        'Average' => 11.424558513393233027954920544289052486419677734375,
      ),
      'Aug' => 
      array (
        'Total' => 504.532257251244800499989651143550872802734375,
        'Average' => 16.2752341048788622401843895204365253448486328125,
      ),
      'Sep' => 
      array (
        'Total' => 649.1630754786887109730741940438747406005859375,
        'Average' => 21.638769182622951348093920387327671051025390625,
      ),
      'Oct' => 
      array (
        'Total' => 891.516890790088154972181655466556549072265625,
        'Average' => 28.758609380325420801227664924226701259613037109375,
      ),
      'Nov' => 
      array (
        'Total' => 1027.64755283151680487208068370819091796875,
        'Average' => 34.254918427717228723849984817206859588623046875,
      ),
      'Dec' => 
      array (
        'Total' => 1153.675892496945607490488328039646148681640625,
        'Average' => 37.2153513708692145200984668917953968048095703125,
      ),
      'Total' => 
      array (
        'Total' => 8760.35970810675280517898499965667724609375,
        'Average' => 288.545542626296310118050314486026763916015625,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => 0,
        'MinimumPanels' => 0,
        'MaximumPanels' => 24,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 24,
        'Size' => 7848,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 173,
          'Name' => 'Fronius Primo 6.0-1 6kW',
          'Code' => 'P-FRO-PRIMO-6.0-1',
          'EuroEff' => 96.7000000000000028421709430404007434844970703125,
          'MaxACOut' => 6000,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'Primo 6.0-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => false,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 8000,
          'InWattDCMax' => 8000,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 227,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8000,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 228,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8000,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1637.430000000000063664629124104976654052734375,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.5104+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 69,
          'Name' => 'SUNPOWER SPR327',
          'Code' => 'P-SPR327',
          'Active' => true,
          'Popular' => false,
          'Model' => 'SPR-327NE-WHT-D',
          'Features' => 'High Efficiency Monocrystalline
Maxeon, solid copper Cell
25 year parts and labour warranty
25 year Performance warranty: 98% by Year 1, then 0.25% per year so 92% by year 25 
Tier 1 manufacturer',
          'Warranty' => '25yr Manufacturing + 25yr Performance',
          'Height' => 1.5589999999999999413802242997917346656322479248046875,
          'Width' => 1.0460000000000000408562073062057606875896453857421875,
          'PositiveEarthRequired' => false,
          'Watt' => 327,
          'Voc' => 64.900000000000005684341886080801486968994140625,
          'Vmp' => 54.7000000000000028421709430404007434844970703125,
          'Imp' => 5.980000000000000426325641456060111522674560546875,
          'TempCoV' => 0.270000000000000017763568394002504646778106689453125,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.34999999999999997779553950749686919152736663818359375,
          'Tolerance' => 3,
          'Cost' => 278.41660000000001673470251262187957763671875,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.3567048+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 24,
            'MinimumStrings' => 0,
            'MaximumStrings' => 3,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 415.6844999999999572537490166723728179931640625,
                'PanelCount' => 6,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 415.6844999999999572537490166723728179931640625,
                'PanelCount' => 6,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              2 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 24,
            'MinimumStrings' => 0,
            'MaximumStrings' => 3,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 415.6844999999999572537490166723728179931640625,
                'PanelCount' => 6,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 415.6844999999999572537490166723728179931640625,
                'PanelCount' => 6,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              2 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => false,
    'Size' => 7848,
    'TotalPanels' => 24,
    'Travel'=> $_GET['travel_km_5'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_5'],
    'AdditionalCableRun' =>($_GET['number_double_storey_panel_5'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_5'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_5'] != "")?$_GET['price_option_5']:$sgPrices[$st]['option5'],
      'PPrice' => ($_GET['price_option_5'] != "")?$_GET['price_option_5']:$sgPrices[$st]['option5'],
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
    ),
    'BusinessPPrice' => '74003643.20',
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => 0,
      ),
    ),
    'ID' => 0,
    'Selected' => false,
  ),
  5 => 
  array (
    'Dirty' => true,
    'Number' => 5,
    'InternalNumber' => 5,
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
      'PVSTCQuantity' => 148,
      'SolarHotWaterSystemSTCQuantity' => 0,
      'STCPrice' => 39.60000000000000142108547152020037174224853515625,
      'Deeming' => 12,
      'Rating' => 1.185000000000000053290705182007513940334320068359375,
      'Multiplier' => 1,
      'Jan' => 
      array (
        'Total' => 1562.187880629616074656951241195201873779296875,
        'Average' => 50.393157439665031915865256451070308685302734375,
      ),
      'Feb' => 
      array (
        'Total' => 1250.105285670435250722221098840236663818359375,
        'Average' => 44.6466173453726895559157128445804119110107421875,
      ),
      'Mar' => 
      array (
        'Total' => 1107.347465987944133303244598209857940673828125,
        'Average' => 35.7208859996110987822248716838657855987548828125,
      ),
      'Apr' => 
      array (
        'Total' => 741.032057522643071933998726308345794677734375,
        'Average' => 24.701068584088101687257221783511340618133544921875,
      ),
      'May' => 
      array (
        'Total' => 527.30704968769214247004128992557525634765625,
        'Average' => 17.009904828635232121314402320422232151031494140625,
      ),
      'Jun' => 
      array (
        'Total' => 413.71921814864850830417708493769168853759765625,
        'Average' => 13.7906406049549499215345349512062966823577880859375,
      ),
      'Jul' => 
      array (
        'Total' => 474.6567350921267234298284165561199188232421875,
        'Average' => 15.311507583616990046948558301664888858795166015625,
      ),
      'Aug' => 
      array (
        'Total' => 676.1880097183176303587970323860645294189453125,
        'Average' => 21.81251644252637333920574747025966644287109375,
      ),
      'Sep' => 
      array (
        'Total' => 870.0262107759882610480417497456073760986328125,
        'Average' => 29.00087369253294156123956781812012195587158203125,
      ),
      'Oct' => 
      array (
        'Total' => 1194.835460665929758761194534599781036376953125,
        'Average' => 38.54307937632031411112620844505727291107177734375,
      ),
      'Nov' => 
      array (
        'Total' => 1377.281518582881062684464268386363983154296875,
        'Average' => 45.90938395276270256317729945294559001922607421875,
      ),
      'Dec' => 
      array (
        'Total' => 1546.18816615929836189025081694126129150390625,
        'Average' => 49.8770376180418821832063258625566959381103515625,
      ),
      'Total' => 
      array (
        'Total' => 11740.875058641520809032954275608062744140625,
        'Average' => 386.71667346812824916924000717699527740478515625,
      ),
    ),
    'RequiredAccessories' => 
    array (
    ),
    'Configurations' => 
    array (
      0 => 
      array (
        'ID' => 0,
        'MinimumPanels' => 0,
        'MaximumPanels' => 33,
        'MinimumTrackers' => 0,
        'MaximumTrackers' => 2,
        'NumberOfPanels' => 32,
        'Size' => 10464,
        'Upgrade' => false,
        'NewInverter' => false,
        'Inverter' => 
        array (
          'ID' => 145,
          'Name' => 'Fronius Primo 8.2-1 8.2kW',
          'Code' => 'P-FRO-PRIMO-8.2-1',
          'EuroEff' => 97.2000000000000028421709430404007434844970703125,
          'MaxACOut' => 8200,
          'Active' => true,
          'STCEnabled' => true,
          'MicroInverter' => false,
          'DCIsolator' => false,
          'Phases' => 1,
          'Model' => 'Primo 8.2-1',
          'Series' => 
          array (
            'ID' => 12,
            'Title' => 'Primo',
          ),
          'Popular' => false,
          'VocMod' => 600,
          'VocMax' => 600,
          'InWattDCMod' => 10933,
          'InWattDCMax' => 10933,
          'PanelEarth' => false,
          'Features' => 'Austrian Designed & Manufactured
Transformerless Design
Revolutionary Snap-In design
WLAN enabled / Smart Grid Ready
10 years parts warranty upon product registration',
          'Warranty' => 5,
          'Trackers' => 
          array (
            0 => 
            array (
              'ID' => 190,
              'Number' => 1,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8300,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
            1 => 
            array (
              'ID' => 191,
              'Number' => 2,
              'MinimumPanels' => 0,
              'MaximumPanels' => 0,
              'MinimumStrings' => 0,
              'MaximumStrings' => 0,
              'Strings' => NULL,
              'ImpMax' => 18,
              'ImpMod' => 20,
              'InWattMax' => 8000,
              'InWattMod' => 8300,
              'VmppLower' => 80,
              'VmppUpper' => 600,
            ),
          ),
          'Cost' => 1887.200000000000045474735088646411895751953125,
          'DatetimeSynchronised' => '2019-02-14T03:30:21.526+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Panel' => 
        array (
          'ID' => 69,
          'Name' => 'SUNPOWER SPR327',
          'Code' => 'P-SPR327',
          'Active' => true,
          'Popular' => false,
          'Model' => 'SPR-327NE-WHT-D',
          'Features' => 'High Efficiency Monocrystalline
Maxeon, solid copper Cell
25 year parts and labour warranty
25 year Performance warranty: 98% by Year 1, then 0.25% per year so 92% by year 25 
Tier 1 manufacturer',
          'Warranty' => '25yr Manufacturing + 25yr Performance',
          'Height' => 1.5589999999999999413802242997917346656322479248046875,
          'Width' => 1.0460000000000000408562073062057606875896453857421875,
          'PositiveEarthRequired' => false,
          'Watt' => 327,
          'Voc' => 64.900000000000005684341886080801486968994140625,
          'Vmp' => 54.7000000000000028421709430404007434844970703125,
          'Imp' => 5.980000000000000426325641456060111522674560546875,
          'TempCoV' => 0.270000000000000017763568394002504646778106689453125,
          'TempCoI' => 0.040000000000000000832667268468867405317723751068115234375,
          'TempCoP' => 0.34999999999999997779553950749686919152736663818359375,
          'Tolerance' => 3,
          'Cost' => 278.41660000000001673470251262187957763671875,
          'DatetimeSynchronised' => '2019-02-14T10:10:41.3567048+08:00',
          'Accessories' => 
          array (
          ),
        ),
        'Trackers' => 
        array (
          0 => 
          array (
            'ID' => 0,
            'Number' => 1,
            'MinimumPanels' => 0,
            'MaximumPanels' => 25,
            'MinimumStrings' => 0,
            'MaximumStrings' => 3,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 554.2459999999999809006112627685070037841796875,
                'PanelCount' => 8,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 554.2459999999999809006112627685070037841796875,
                'PanelCount' => 8,
                'Orientation' => 
                array (
                  'Name' => 'E 90',
                  'Value' => 90,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              2 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
          1 => 
          array (
            'ID' => 0,
            'Number' => 2,
            'MinimumPanels' => 0,
            'MaximumPanels' => 25,
            'MinimumStrings' => 0,
            'MaximumStrings' => 3,
            'Strings' => 
            array (
              0 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 554.2459999999999809006112627685070037841796875,
                'PanelCount' => 8,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              1 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 554.2459999999999809006112627685070037841796875,
                'PanelCount' => 8,
                'Orientation' => 
                array (
                  'Name' => 'W 270',
                  'Value' => 270,
                ),
                'Pitch' => 
                array (
                  'Name' => '20',
                  'Value' => 20,
                ),
                'Shading' => 0,
              ),
              2 => 
              array (
                'ID' => 0,
                'MinimumPanels' => 2,
                'MaximumPanels' => 8,
                'VOC' => 0,
                'PanelCount' => NULL,
                'Orientation' => NULL,
                'Pitch' => NULL,
                'Shading' => NULL,
              ),
            ),
            'ImpMax' => NULL,
            'ImpMod' => NULL,
            'InWattMax' => NULL,
            'InWattMod' => NULL,
            'VmppLower' => NULL,
            'VmppUpper' => NULL,
          ),
        ),
        'Number' => NULL,
      ),
    ),
    'ReCalculate' => false,
    'Size' => 10464,
    'TotalPanels' => 32,
    'Travel'=> $_GET['travel_km_6'],
    'ExcessHeightPanels' => $_GET['number_double_storey_panel_6'],
    'AdditionalCableRun' => ($_GET['number_double_storey_panel_6'] == 0)?0:1,
    'Splits' => $_GET['groups_of_panels_6'],
    'Finance' => 
    array (
      'Type' => NULL,
      'Price' => ($_GET['price_option_6'] != "")?$_GET['price_option_6']:$sgPrices[$st]['option6'],
      'PPrice' => ($_GET['price_option_6'] != "")?$_GET['price_option_6']:$sgPrices[$st]['option6'],
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
    ),
    'BusinessPPrice' => '74004395.60',
    'Accessories' => 
    array (
      0 => 
      array (
        'ID' => 0,
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
          'Manufacturer' => 
          array (
            'ID' => 2,
            'Name' => 'Fronius',
            'ServiceContact' => 'Simon',
            'ServiceHours' => '0900-1700',
            'ServicePhone' => '03 8340 2910',
            'ValidForPanels' => true,
            'ValidForInverters' => true,
            'ValidForAccessories' => true,
            'ValidForHotWaterSystems' => true,
          ),
          'Model' => 'Fronius 1P Smart Meter',
          'DisplayOnQuote' => true,
          'Warranty' => '2 year',
          'Features' => 'Real-time view of consumption data',
          'ExoCode' => 'P-FRO-SMART-METER-1P',
          'PurchaseOrderExoCode' => 'P-S-METER-INSTALL',
          'Active' => true,
          'Kit' => false,
          'Cost' => 130.479999999999989768184605054557323455810546875,
          'DatetimeSynchronised' => '2019-02-14T03:30:25.114+08:00',
        ),
        'Include' => false,
        'DisplayOnQuote' => true,
        'UnitPriceEnabled' => true,
        'IncludedEnabled' => true,
        'QuantityEnabled' => true,
        'Quantity' => '1',
        'Included' => true,
        'UnitPrice' => 0,
      ),
    ),
    'ID' => 0,
    'Selected' => false,
  ),
);
$quote_decode ->Options = array();
$sgOptions = urldecode($_GET['sgoption']);
if(isset($sgOptions)) {
    $sgOptions = explode(",", $sgOptions);
    if (count($sgOptions)>0){
        $i = 0;
        foreach($sgOptions as $option){
            $l_option = $solargain_options[$option];
            $l_option['Number'] = $i;
            $l_option['InternalNumber'] = $i;
            $quote_decode ->Options[] = $l_option;
            $i++;
            //'Number' => 2,
            //'InternalNumber' => 2,
        }
    }
}
$quote_decode->SpecialNotes = "";

//Proposed Install Date
$quote_decode -> ProposedInstallDate = array (
  "Date" => '31/12/2019', //date('d/m/Y', time() + 6*7*24*60*60)
  "Time" => "9:15 AM"
);


// Thienpb fix next action date +12 months
$today = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
$next_action_date = mktime(0, 0, 0, date('n', $today)+12, date('d', $today), date('Y', $today));

$quote_decode->NextActionDate = array(
  "Date" => date('d/m/Y', $next_action_date),
  "Time" => "9:15 AM"
);
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
        "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
    )
);
$result = curl_exec($curl);

$record = urldecode($_GET['record']);
$bean = BeanFactory::getBean("Leads", $record);
$bean -> solargain_quote_number_c = $quote_id;
$bean->save();

//dung code - upload file for Citipower Powercor push sogargain
if($bean->meter_number_c !=='' && ($bean->distributor_c == '4' || $bean->distributor_c == '7' || $bean->distributor_c == '6')) {
  $folder_pdf = dirname(__FILE__)."/server/php/files/".$bean->installation_pictures_c;

  //new logic get file citipower 
  $leads_file_attachmens = scandir( $folder_pdf . '/');
    foreach ($leads_file_attachmens as $key => $value) {
        if (strpos($value, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {
          $filename_pdf = $value;
        }          
    }
  
  if(is_file($folder_pdf.'/'.$filename_pdf)){
    $content_file =  file_get_contents($folder_pdf.'/'.$filename_pdf);
    $ch = curl_init();
    $data_file_upload = array(
        'Data'     => base64_encode($content_file),
        'Filename' => $filename_pdf,
        'Title'    => $filename_pdf,
        'Url'      => "",
    );
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/" .$quote_id."/upload");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data_file_upload) );
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
    $headers[] = "Content-Type: application/json";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/" .$quote_id;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
    $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    $decode_result_files_image_upload = json_decode($result,true);

    //dung code - need add field Category = "RETAILER APPROVAL" 
    if(strpos($filename_pdf, '_CITIPOWER_POWERCOR_APPROVAL') !== false) {

        foreach ($decode_result_files_image_upload as $value){
            if($value['Filename'] == $filename_pdf){
                $id_file_image_meterbox = $value['ID'];
            }
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/files/" .$id_file_image_meterbox ."/category/6");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
        $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }
  }
  
}

die();