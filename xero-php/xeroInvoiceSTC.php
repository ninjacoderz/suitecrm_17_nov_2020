<?php
    /**
        * User: thienpb
        * Date Updated: 5/12/2020
    **/
    use XeroPHP\Remote\URL;
    use XeroPHP\Remote\Request;
    require_once('xeroConfig.php');

    //Param request
    $xeroType   =  $_REQUEST['xeroType'];
    $method =  $_REQUEST['method'];
    $record =  $_REQUEST['record'];

    
    try {
        $return = xeroInvoiceSTC($xero,$method,$record,$product_mapping,$xeroType);
        echo json_encode($return);
        die;
    } catch (\Throwable $th) {
        echo json_encode(array('status'=>'Fail','xeroID'=>''));
    }

    function xeroInvoiceSTC($xero,$method,$record,$product_mapping,$xeroType){
        //bean Po
        $invoiceBean = new AOS_Invoices();
        $invoiceBean->retrieve($record);
        if($invoiceBean->number == "" || $invoiceBean->id == ""){
            return array('status'=>'Invoice is invalid.','xeroID'=>'');
        }

        //bean account
        $beanAccount = new Account();
        $beanAccount->retrieve($invoiceBean->billing_account_id);
        if($beanAccount->number == "" || $beanAccount->id == ""){
            return array('status'=>'Account invalid.','xeroID'=>'');
        }

        $xeroInvoices = $xero->load('Accounting\\Invoice')->where('InvoiceNumber.Contains("ADJUST")')->where('Status!="DELETED"')->execute();
        $number = count($xeroInvoices)+1;
        $xeroInvoiceNumber = 'ADJUST-'.$number.' '.$beanAccount->name;

        $xeroInvoice = $xero->load('Accounting\\Invoice')->where('InvoiceNumber.Contains("'.$xeroInvoiceNumber.'")')->where('Status!="DELETED"')->execute();
        if(count($xeroInvoice) > 0){
            return array('status'=>'Xero Invoice existed.','xeroID'=>'');
        }

        $contact = $xero->loadByGUID('Accounting\\Contact','ea2e90af-9cd8-4880-9a8d-436d88f777fc');
        $assignment = loadGeoAssignment($invoiceBean->stc_aggregator_serial_c,$xeroType);
        $dueDate            = new DateTime(date('Y-m-d',strtotime($assignment['dueDate'])));

        $xeroInvoice = new \XeroPHP\Models\Accounting\Invoice($xero);
        $xeroInvoice->setInvoiceNumber($xeroInvoiceNumber);
        $xeroInvoice->setReference($invoiceBean->name)
                    ->setDate($dueDate)
                    ->setDueDate($dueDate)
                    ->setType(\XeroPHP\Models\Accounting\Invoice::INVOICE_TYPE_ACCREC)
                    ->setLineAmountType('Exclusive')
                    ->setContact($contact);
        
        //save tracking
        $optionTracking = $beanAccount->name;
        try {
            $class = 'XeroPHP\Models\Accounting\TrackingCategory';
            $uri = sprintf('%s/%s', $class::getResourceURI(), '0c42df0c-f53c-415b-9cc6-a27b9cb50f06/Options');
            $url = new URL($xero, $uri, NULL);
            $request = new Request($xero, $url, Request::METHOD_PUT);
            $data =  "<Options>
                            <Option>
                                <Name>".$optionTracking."</Name>
                            </Option>
                        </Options>";
            $request->setBody($data);
            $request->send(); 
        } catch (\Throwable $th) {
        }
        $trackingCategoryItem = new \XeroPHP\Models\Accounting\TrackingCategory($xero);

        // lineItem
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoiceBean->id."' AND deleted = 0";
        $result = $db->query($sql);

        $basePrice = 0;
        while ($row = $db->fetchByAssoc($result)) {
            if(isset($row) && $row != null ){
                if($row['part_number'] == 'STC Rebate Certificate' && $xeroType == 'STC'){
                    $basePrice = $row['product_total_price'];
                }else if($row['part_number'] == 'VEEC Rebate Certificate' && $xeroType == 'VEEC'){
                    $basePrice = $row['product_total_price'];
                }
            }
        }
        $price = $assignment['Price'] + $basePrice;
        $lineitem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
        $lineitem   ->setQuantity(1)
                    ->setUnitAmount($price)
                    ->setItemCode($product_mapping['914a2728-0dcc-5451-dd35-58a663b48e67'])
                    ->addTracking($trackingCategoryItem ->setName('Customer')
                                                        ->setOption($optionTracking));
        $xeroInvoice->addLineItem($lineitem);

        //save
        $xeroInvoice->save();

        //Attachment
        $xeroInvoice =  $xero->loadByGUID('Accounting\\Invoice',$xeroInvoice->getInvoiceID());
        $templateID = "14066998-993a-e4e0-4d8e-58b79279b475";
        downloadPDFFile($templateID,$record,'AOS_Invoices');
        $attachmentFile =  dirname(__FILE__)."/files/invoice-". $record.".pdf";
        $attachment = \XeroPHP\Models\Accounting\Attachment::createFromLocalFile($attachmentFile);
        $xeroInvoice->addAttachment($attachment);

        //save
        $xeroInvoice->save();

        return array('status'=>'Ok','xeroID'=>$xeroInvoice->getInvoiceID());
    }

    function loadGeoAssignment($assignment,$xeroType){
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

        //LOAD GEO ASSIGNMENT
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

            $jsonDecode = json_decode($result);
        //END LOAD

        for($i = 0; $i < count($jsonDecode->assignment->result->certificateBundles); $i++){
            if($jsonDecode->assignment->result->certificateBundles[$i]->certificateType == $xeroType){
                $dueDate = $jsonDecode->assignment->result->certificateBundles[$i]->paymentDueDate;
                $price   = $jsonDecode->assignment->result->certificateBundles[$i]->value;
                break;
            }
        }

        return array('Price'=>$price,'dueDate'=>$dueDate);
    }
?>