<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 7/4/17
 * Time: 6:04 PM
 */
require_once(dirname(__FILE__).'/simple_html_dom.php');

date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

// Get NMI from momentumenergy
$momentumenergy = $_GET['momentumenergy'];
$nmi_number = '';
if (isset($momentumenergy) && $momentumenergy == 1)
{
    $address = urlencode($_GET['address']);
    // 1. get encrypted param from address
    // https://www.momentumenergy.com.au/switch/api/AddressSearch/ForPartialAddress?address=79 BOUNDARY ST, ROSEVILLE, NSW 2069

    $url = "https://www.momentumenergy.com.au/api/AddressSearch/ForPartialAddress?address=" .$address;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);

    // 2. get nmi from the param
    // https://www.momentumenergy.com.au/switch/quote?address=AUS|cbddb54c-06a3-4212-b759-c1781e3ceb98|0xOAUSHArhBwAAAAAIAwEAAAAAXbUSQAAAAAAAADc5AAD..2QAAAAA.....wAAAAAAAAAAAAAAAAA3OSBCT1VOREFSWSBTVCwgUk9TRVZJTExFLCBOU1cgMjA2OQA-

    $result = json_decode($result);

    if (count($result) == 0)
    {
        //call function get NMI from globirdenergy if have not address
        get_nmi_globirdenergy();
    }

    $address = $result[0]->Moniker;
    $postCode = $result[0]->Moniker;
    $dataPost = array (
        'Postcode' => '',
        'FilterType' => 'address',
        'address' => $address,
        'nmi' => '',
        'mirn' => '',
        'customerSegment' => 'Residential',
        'electricityUsageLevel' => 'Low',
        'gasUsageLevel' => 'Low',
        'dailyKwh' => '',
        'dailyMj' => '',
        'electricityDays' => '31',
        'gasDays' => '31',
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.momentumenergy.com.au/energy-plans/GetProductsUsingFilters');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataPost));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Authority: www.momentumenergy.com.au';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Origin: https://www.momentumenergy.com.au';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/json;charset=UTF-8';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://www.momentumenergy.com.au/energy-plans?postcode=';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
 
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $returnValue = json_decode($result);
    $nmi_number = $returnValue->result->Model->SiteFilters->NmiFilter;
    if($nmi_number != '') {
        echo $nmi_number;
    }else{
        get_nmi_globirdenergy();
    }
    // //$pattern = '<input class="nmi" data-form-command="FilterToNmi" id="nmi-search" name="SiteFilters.NmiFilter" placeholder="Enter your NMI" type="text" value="(.*)">';
    // $pattern =   '/<input class="nmi" data-form-command="FilterToNmi" id="nmi-search" name="SiteFilters.NmiFilter" onkeypress="showLoadingGifOnKeyPress\(event\);showLoadingGasGifOnKeyPress\(event\);" placeholder="Enter your NMI" type="text" value="(.*?)"/';

    // $matches = null;
    // $returnValue = preg_match($pattern, $result, $matches);

    // if ($returnValue == false || $matches == null || count($matches) < 2)
    // {
    //     //call function get NMI from globirdenergy if pattent not matches
    //     get_nmi_globirdenergy();
    // }
    
    // if($matches[1]==''){
    //     $html = str_get_html($result);
    //     $nmi_number_from_gas = $html->find("input[id='ChooseExactMeter_SelectedNmi']",0)->value;
    //     if($nmi_number_from_gas != '' && $nmi_number_from_gas !== null){
    //         echo  $nmi_number_from_gas; die();
    //     }else{ 
    //         $session_script = $html->find('div.form-group--radio-group--vertical')[0]->innertext;
    //         if($session_script != null && $session_script != '') {
    //             echo $session_script; die();
    //         }else {
    //             get_nmi_globirdenergy();
    //         }
            
    //     }
    // }else{
    //     echo $matches[1];
    //     return;
    // }
    // return;
}

//function get NMI from globirdenergy
function get_nmi_globirdenergy(){

    $address = $_GET['address'];
  
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://hosted.mastersoftgroup.com/harmony/rest/au/address?callback=jsonCallback__address&sourceOfTruth=AUPAF&transactionID=4f75dfec13207d3020e8357ad81946cc&Authorization=Basic%20Z2xvYmlyZGVuZXJneXVzZXI6WG1nVlQ3eDRabnBvVXhNZk5xTldqV1RWV3JQZGhnWjM=&fullAddress='.  urlencode ( $address));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Referer: https://quote.globirdenergy.com.au/yourproperty';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
     // echo $result;
     $pattern =   '/jsonCallback__address\((.*?)\);/';
 
     $matches = null;
     $returnValue = preg_match($pattern, $result, $matches);
     //echo $matches[1];
    $a = json_decode($matches[1]);
    // GET NMI
   
    $array_post = array(
        "token" => "",
        "address" => array(
            "stateOrTerritory" => $a->payload[0]->state,
            "buildingOrPropertyName" => '',
            "flatOrUnitNumber" =>  $a->payload[0]->flatUnitNumber,
            "flatOrUnitType" => ($a->payload[0]->flatUnitType == 'U') ? 'Unit' : '',
            "floorOrLevelNumber" => "",
            "houseNumber" => $a->payload[0]->streetNumber,
            "streetName" => $a->payload[0]->streetName,
            "streetType" => $a->payload[0]->streetType,
            "streetSuffix" => $a->payload[0]->streetSuffix,
            "postcode" => $a->payload[0]->postcode,
            "suburbOrPlaceOrLocality" => $a->payload[0]->locality,
            "lotNumber" => ""
        )
    );
    $addressJSONEncode = json_encode($array_post);
    $ch = curl_init();
 
    curl_setopt($ch, CURLOPT_URL, "https://signup.globirdenergy.com.au/api/Quote/NmiDiscovery/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$addressJSONEncode);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
 
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://signup.globirdenergy.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-AU";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
    $headers[] = "Content-Type: application/json;charset=UTF-8";
    $headers[] = "Accept: application/json;charset=UTF-8";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "Content-Length: ".strlen($addressJSONEncode);
    $headers[] = "Referer: https://signup.globirdenergy.com.au/yourproperty";
    $headers[] = "Cookie: _ga=GA1.3.65123537.1535016815; ai_user=rcm+a^|2018-08-23T09:34:35.668Z; ARRAffinity=86408107e5467ace2e57d2391337ec37c8d5b317aa51c6fc29a59799ad657b54; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gid=GA1.3.98739520.1535331583; _gat=1; ai_session=UfF0g^|1535338524967.1^|1535338613535.7";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Request-Id: ^|iFOPV.FgDZO";
    $headers[] = "Request-Context: appId=cid-v1:d4d90ee1-3847-42dd-a744-d8c9dded0a89";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
    $result = curl_exec($ch);
 
    $return_json = json_decode($result);
    curl_close ($ch);
    if(count($return_json->addressForSelect) == 1 ){
        $nmi_number = $return_json->addressForSelect[0]->identifier;
        echo $nmi_number;
        die();
    }elseif(count($return_json->addressForSelect) > 1){
        $html ='';
        foreach($return_json->addressForSelect as $key => $value){
            $full_address = '';
            $full_address .= (($value->flatOrUnitType != null) ? $value->flatOrUnitType .' ' : '');
            $full_address .= (($value->flatOrUnitNumber != null) ? $value->flatOrUnitNumber.' ' : '');
            $full_address .= (($value->houseNumber != null) ? $value->houseNumber.' ' : '');
            $full_address .= (($value->streetName != null) ? $value->streetName.' ' : '');
            $full_address .= (($value->streetType != null) ? $value->streetType.' ' : '');
            $full_address .= (($value->suburbOrPlaceOrLocality != null) ? $value->suburbOrPlaceOrLocality.' ' : '');
            $full_address .= (($value->stateOrTerritory != null) ? $value->stateOrTerritory.' ' : '');
            $full_address .= (($value->postcode != null) ? $value->postcode.' ' : '');
            
            $html .= 
            '<div class="radio">
                <input id="'.$value->identifier.'" name="ChooseExactMeter.SelectedNmi" type="radio" value="'.$value->identifier .'" />
                        <label for="'.$value->identifier.'"> '.$full_address.'</label> <span>: '.$value->identifier.'</span></div>
                        <input id="ChooseExactMeter_Nmis_'.$key.'__MeterNumber" name="ChooseExactMeter.Nmis['.$key.'].MeterNumber" type="hidden" value="'.$value->identifier.'" />
                        <input id="ChooseExactMeter_Nmis_'.$key.'__Address" name="ChooseExactMeter.Nmis['.$key.'].Address" type="hidden" value="'.$full_address.'" />';
      
        }
        echo $html;
        die();
    }
}
// header('Content-Type: application/json; charset=utf-8');
// header('Content-Encoding: gzip');
// $nmi = $_GET['nmi'];
// if( isset($nmi)  && $nmi != '' ){
//     $curl = curl_init();

//     $array_post = array(
//         "token" => "",
//         "nmi" => $nmi,
//     );
    
//     $addressJSONEncode = json_encode($array_post);
//     $url = "https://signup.globirdenergy.com.au/api/Quote/Nmi/";

//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_URL, $url);

//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//     curl_setopt($curl, CURLOPT_POST, 1);

//     curl_setopt($curl, CURLOPT_POSTFIELDS, $addressJSONEncode);

//     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//     //
//     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
//     curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
//     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//             "Host: signup.globirdenergy.com.au",
//             "Origin:https://signup.globirdenergy.com.au",
//             "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
//             "Content-Type: application/json",
//             "Accept: application/json, text/plain, */*",
//             "Accept-Language: en-US,en;q=0.5",
//             "Accept-Encoding: 	gzip, deflate, br",
//             "Connection: keep-alive",
//             "Content-Length: " .strlen($addressJSONEncode),
//             "Referer: https://signup.globirdenergy.com.au/yourproperty",
//         )
//     );

//     $result = curl_exec($curl);
//     echo $result ;
//     curl_close ( $curl );

//     die();
// }

// $url = "https://signup.globirdenergy.com.au/api/Quote/ElectricityQuoteByNmi/";

// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $url);

// curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($curl, CURLOPT_POST, 1);

// curl_setopt($curl, CURLOPT_POSTFIELDS, $addressJSONEncode);

// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// //
// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
// curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
// curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//         "Host: signup.globirdenergy.com.au",
//         "Origin:https://signup.globirdenergy.com.au",
//         "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
//         "Content-Type: application/json",
//         "Accept: application/json, text/plain, */*",
//         "Accept-Language: en-US,en;q=0.5",
//         "Accept-Encoding: 	gzip, deflate, br",
//         "Connection: keep-alive",
//         "Content-Length: " .strlen($addressJSONEncode),
//         "Referer: https://signup.globirdenergy.com.au/yourproperty",
//     )
// );

// $result = curl_exec($curl);
// echo $result ;
// curl_close ( $curl );
//die();

// Using Originery
//$address = str_replace("/","&", $address);
// $add  = http_build_query($address);
// $url = "https://plans.api.odcdn.com.au/api/v1/plans?".$add;
// curl_setopt($curl, CURLOPT_URL, $url);

// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0); 
// curl_setopt($curl, CURLOPT_TIMEOUT, 400);
// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
// curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);

// curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//         "Host: plans.api.odcdn.com.au",
//         "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
//         "Accept: application/json, text/plain, */*",
//         "Accept-Language: en-US,en;q=0.5",
//         "Accept-Encoding: 	gzip, deflate, br",
//         "Referer: https://www.originenergy.com.au/for-home/electricity-and-gas/plans/energy-plans.html",
//         "Origin: https://www.originenergy.com.au",

//         "Connection: keep-alive",
//     )
// );

// $result = curl_exec($curl);
// print( $result);

// die();