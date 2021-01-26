<?php

global $current_user;
$recordID = $_REQUEST['recordID'];
$OriginInvoice =  new AOS_Invoices();
$OriginInvoice->retrieve($recordID);
$result_data = array (
    'msg' => '',
    'IdInvoiceOSTI' => ''
);

$stc_aggregator_serial_c = $OriginInvoice->stc_aggregator_serial_c;

if($stc_aggregator_serial_c != ''){
    $InvoiceOSTI =  new AOS_Invoices();
    $InvoiceOSTI->name = $stc_aggregator_serial_c;
    $InvoiceOSTI->status = 'Unpaid';
    $InvoiceOSTI->due_date = date("d/m/Y", time()+ 7*24*60*60);
    $InvoiceOSTI->installation_date_c = $OriginInvoice->installation_date_c ;
    $InvoiceOSTI->billing_account = 'Green Energy Trading Pty Ltd';
    $InvoiceOSTI->billing_account_id = 'a0291eb6-5326-460f-f5fe-5aaa0d7c830d' ;
    $InvoiceOSTI->billing_contact = $OriginInvoice->site_contact_c = 'Natalie Barnes' ;
    $InvoiceOSTI->billing_contact_id = $OriginInvoice->contact_id3_c = '8be3e6c9-aba5-23fa-b5a2-5b63977bcca2';
    $InvoiceOSTI->billing_address_street = $InvoiceOSTI->install_address_c =  $OriginInvoice->install_address_c ;
    $InvoiceOSTI->billing_address_city = $InvoiceOSTI->install_address_city_c =  $OriginInvoice->install_address_city_c ;
    $InvoiceOSTI->billing_address_state = $InvoiceOSTI->install_address_state_c =  $OriginInvoice->install_address_state_c ;
    $InvoiceOSTI->billing_address_postalcode = $InvoiceOSTI->install_address_postalcode_c =  $OriginInvoice->install_address_postalcode_c ;

    $InvoiceOSTI->save();
    $result_data['IdInvoiceOSTI'] = $InvoiceOSTI->id;
    $result_data['msg'] = 'New Invoice OSTI Created';
    $data_Items_GEO = GET_Items_From_GEO($stc_aggregator_serial_c);
        
        // create Items 
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$OriginInvoice->id."' AND deleted = 0";
        $result = $db->query($sql);
        $row = $db->fetchByAssoc($result);

        $row['id'] = "";
        $row['name'] = 'STCs';
        $row['currency_id'] = '-99';
        $row['number'] = '1';
        $row['assigned_user_id'] = $InvoiceOSTI->assigned_user_id;
        $row['parent_id'] = $InvoiceOSTI->id;
        $row['parent_type'] = 'AOS_Invoices';
        
        $group_invoice = new AOS_Line_Item_Groups();
        $group_invoice->populateFromRow($row);
        $group_invoice-> save();

            $part_numners = array(
                "STC Rebate Certificate"        
            );
            $number_items = 1;
            $total_price = 0;
            $part_numners_implode = implode("','", $part_numners);
            $db = DBManagerFactory::getInstance();

            $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
            $ret = $db->query($sql);

            $products = array();
            while ($row_product = $db->fetchByAssoc($ret))
            {

                $row = array();
                $row['id'] = '';
                $row['parent_id'] = $InvoiceOSTI->id;
                $row['parent_type'] = 'AOS_Invoices';
                $row['name'] = $row_product['name'];
                $row['assigned_user_id'] = $InvoiceOSTI->assigned_user_id;
                $row['currency_id'] = -99;
                $row['part_number'] = $row_product['part_number'];
                $row['item_description'] = $row_product['item_description'];
                $row['number'] = $number_items;
                $row['product_qty'] = format_number($data_Items_GEO['quantity']);
                $row['product_cost_price'] = format_number($data_Items_GEO['price']);
                $row['product_list_price'] = format_number($data_Items_GEO['price']);
                $row['discount'] = "Percentage";
                $row['product_unit_price'] = format_number($data_Items_GEO['price']);
                $row['product_amt'] = 'vat_amt';
                $row['vat_amt'] = format_number(0);
                $row['product_total_price'] = format_number($data_Items_GEO['price']*$data_Items_GEO['quantity']);
                $row['vat'] = "0.0";
                $row['group_id'] = $group_invoice->id;
                $row['product_id'] = $row_product['id'];
                $total_price += $data_Items_GEO['price'];
                $prod_invoice = new AOS_Products_Quotes();
                $prod_invoice->populateFromRow($row);
                $prod_invoice->save();
                $number_items ++;

            }

            $InvoiceOSTI->total_amt = format_number($total_price);
            $InvoiceOSTI->subtotal_amount = format_number($total_price);
            $InvoiceOSTI->tax_amount = format_number(0);
            $InvoiceOSTI->total_amount = format_number($total_price );
            $InvoiceOSTI->save();

    
}else{
    $result_data['msg'] = 'STC Aggregator Serial is empty';
}

echo json_encode($result_data);

function GET_Items_From_GEO($reference) {
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $curl = curl_init();
    $tmpfname = dirname(__FILE__).'/cookiegeo.txt';


    //LOGIC LOGIN (verify user and accesstoken)
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
        curl_close($ch);

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
    //END LOGIC LOGIN

    //Get Data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.greenenergytrading.com.au/api/assignments/'.$reference);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/".$reference."/edit",
            "Origin: https://geocreation.com.au",
        )
    );
    $result = curl_exec($ch);
    curl_close($ch);
    $result_object = json_decode($result);
    $return_data = array(
        'quantity' => $result_object->assignment->result->certificateBundles[0]->dealBundle->quantity,
        'price' => $result_object->assignment->result->certificateBundles[0]->dealBundle->paymentTerms->price,
    );
    return $return_data;
}

