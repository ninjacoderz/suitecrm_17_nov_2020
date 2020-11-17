<?php
$InvoiceID = $_REQUEST['InvoiceID'];
$Invoice = new AOS_Invoices();
$Invoice->retrieve($InvoiceID);
$result = [];
if($Invoice->id == ""){
    echo 'Resource Not Found';die();
}

$subtotal_invoice = get_subtotal_amount($Invoice);

$Plumber_PO = new PO_purchase_order();
$Plumber_PO->retrieve(trim($Invoice->plumber_po_c));
$subtotal_plumber_po = get_subtotal_amount($Plumber_PO);

$Electric_PO = new PO_purchase_order();
$Electric_PO->retrieve(trim($Invoice->electrical_po_c));
$subtotal_electrical_po = get_subtotal_amount($Electric_PO);

$Daikin_PO = new PO_purchase_order();
$Daikin_PO->retrieve(trim($Invoice->daikin_po_c));
$subtotal_daikin_po = get_subtotal_amount($Daikin_PO);


$subtotal_stc = 0;
if($Invoice->stc_aggregator_serial_c != ''){
    $subtotal_stc = get_subtotal_STC($Invoice->stc_aggregator_serial_c);
}

$quote_type_c = $Invoice->quote_type_c;

switch ($quote_type_c) {
    case 'quote_type_daikin':
        $subtotal_total_cost = $subtotal_plumber_po + $subtotal_electrical_po + $subtotal_daikin_po;
        $subtotal_total_revenue = $subtotal_invoice;
        $profit = $subtotal_total_revenue - $subtotal_total_cost;
        break;
    case 'quote_type_nexura':
        $subtotal_total_cost = $subtotal_plumber_po + $subtotal_electrical_po + $subtotal_daikin_po;
        $subtotal_total_revenue = $subtotal_invoice;
        $profit = $subtotal_total_revenue - $subtotal_total_cost;
        break;
    case 'quote_type_sanden':
        $group_po = $Invoice->get_linked_beans('aos_invoices_po_purchase_order_1','PO_purchase_order');
        $array_po_plum_and_elec = [$Invoice->electrical_po_c,$Invoice->plumber_po_c];
        $Sanden_PO = null;
        if(count($group_po)> 0){
            for($i=0;$i < count($group_po);$i++){
                if(!in_array($group_po[$i],$array_po_plum_and_elec)){
                    $Sanden_PO = $group_po[$i];
                    break;
                }
            }
        }
        if( $Sanden_PO == null){
            $subtotal_sanden_po = 0;
        }else{
            $subtotal_sanden_po = get_subtotal_amount($Sanden_PO);
        }
       
        $subtotal_total_cost = $subtotal_plumber_po + $subtotal_electrical_po + $subtotal_sanden_po;
        $subtotal_total_revenue = $subtotal_invoice + $subtotal_stc;
        $profit = $subtotal_total_revenue - $subtotal_total_cost;
        break;
    default:
        $subtotal_total_cost = $subtotal_plumber_po + $subtotal_electrical_po + $subtotal_daikin_po;
        $subtotal_total_revenue = $subtotal_invoice;
        $profit = $subtotal_total_revenue - $subtotal_total_cost;
        break;
}

if($subtotal_total_cost != 0 ){
    $gp = number_format($profit/$subtotal_total_cost * 100, 1) ;
}else {
    $gp= 0;
}

$result = array(
    'subtotal_total_revenue' =>  number_format($subtotal_total_revenue,2),
    'profit' =>  number_format($profit,2),
    'subtotal_total_cost' =>  number_format($subtotal_total_cost,2),
    'gp' =>  $gp .'%',
);
echo json_encode($result);

function get_subtotal_amount($bean){
    $subtotal_amount = $bean->subtotal_amount;
    if($subtotal_amount == '') {
        $subtotal_amount = 0;
    } else{
        $subtotal_amount = substr($subtotal_amount,0,-4);
    } 
    return  $subtotal_amount;
}

function get_subtotal_STC($assignment){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $subtotal_stc = 0;
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

    
    // Get JSON of assignment by assignment ID
    $curl = curl_init();
    $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
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
            "Referer: https://geocreation.com.au/assignments/$assignment/edit",
            "Origin: https://geocreation.com.au",
        )
    );

    $result = curl_exec($curl);
    curl_close ($curl);

    if($result != false){
        $result = json_decode($result);
        $assignment_byID = $result->assignment->result;
        $address = $assignment_byID->commonSection->activityAddress->displayAddress;
        $activityDate = $assignment_byID->commonSection->activityDate;
        $subtotal_stc = $result->assignment->result->totalValue;
    }

    return $subtotal_stc;
}

