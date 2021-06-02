<?php

    set_time_limit(0);
    ini_set('memory_limit', '-1');

    try {
        $client = new Google_Client(); 
        $client->setApplicationName('Google Sheets API');
        $client->addScope(Google_Service_Drive::DRIVE); 
        $client->setAuthConfig(dirname(__FILE__) .'/client_credentials.json');

        $sheets = new Google_Service_Sheets($client);
        $spreadsheetId = '1yVOVTtCVgmHUuxmn6WHOY20tv7suZXKrY7sg1MVDwj0';
        $range = 'From 1/3 to !A2:K';
        $optParams = [];
        $optParams['valueRenderOption'] = 'FORMULA';
        $response = $sheets->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        for($i = 0; $i < count($values) ;$i++){
            $orderID = trim($values[$i][0],"#");

            if(empty($values[$i][6])){
                $customerInfo = getSAMOrderInfo($orderID);
                if(count($customerInfo) >0){
                    $values[$i][1] = $customerInfo['salePersion'];
                    $values[$i][2] = $customerInfo['customer'];
                    $values[$i][3] = $customerInfo['site'];
                    $values[$i][4] = $customerInfo['installDate'];
                    $values[$i][5] = $customerInfo['pvStatus'];
                    $values[$i][6] = $customerInfo['orderPrice'];
                }
            }
            $invoiceInfo = getInvoiceInfo($orderID);
            if(count($invoiceInfo)){
                $values[$i][7] = (!empty($values[$i][7])) ? $values[$i][7]  : '';
                $values[$i][8] = (!empty($values[$i][8])) ? $values[$i][8]  : '' ;
                $values[$i][9] = "=HYPERLINK(\"https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=DetailView&record=".$invoiceInfo['id']."\",\"".$invoiceInfo['number']."\")";
                $values[$i][10] = (!empty($invoiceInfo['status'])) ? $invoiceInfo['status']  : '';
            }

        }

        $requestBody = new Google_Service_Sheets_ValueRange();
        $requestBody->setRange("From 1/3 to !A2:K");
        $requestBody->setValues($values);
        $requestBody->setMajorDimension('ROWS');
        $params = [
        'valueInputOption' => 'USER_ENTERED',
        'responseValueRenderOption' => 'FORMULA',
        ];
        $response = $sheets->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
        
        echo json_encode(array("errors"=>"","status"=>true));
    } catch (Exception $e) {
        echo json_encode(array("errors"=>$e->getMessage(),"status"=>false));
    }

    function getSAMOrderInfo($orderID){
        if($orderID == '') return '';
        // account matthew
        $username = "matthew.wright";
        $password = "MW@pure733";
        $assigned_user_name = 'Matthew Wright';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$orderID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Referer: https://crm.solargain.com.au/order/edit/".$orderID;
        $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
        $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);
        $json_result = json_decode($result);
        //change account paul
        if(!isset($json_result->ID)) {
            $username = 'paul.szuster@solargain.com.au';
            $password = 'WalkingElephant#256';
            $assigned_user_name = 'Paul Szuster';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$orderID");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en-US,en;q=0.9";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Referer: https://crm.solargain.com.au/order/edit/".$orderID;
            $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
            $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Cache-Control: no-cache";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $result = curl_exec($ch);
            curl_close ($ch);
            $json_result = json_decode($result);
        }
        $customerInfo = array();
        if(!empty($json_result->ID)){
            $salePersion  =  $assigned_user_name;
            $customer     =  $json_result->Customer->Name;
            $customer    .= ($json_result->Customer->CustomerTypeID == 0) ? "\n\r(Residential)" : "\n\r(Business)";
            $customer    .= "\n\rP : ".$json_result->Customer->Phone;
            $customer    .= "\n\rM : ".$json_result->Customer->Mobile2;
            $customer    .= "\n\rE : ".$json_result->Customer->Email;
            $customer    .= "\n\r".$json_result->Customer->Address->Value;
            $site         = 'Site #'.$json_result->Install->ID;
            $site        .= "\n\r".$json_result->Install->Address->Value;
            $installDate  = (!empty($json_result->InstallDate)) ?  date('d/m/Y',strtotime($json_result->InstallDate)) : $json_result->ProposedInstallDate->Date;
            $orderPrice   =   '$'.$json_result->Quote->SelectedOption->Finance->Price;
            $pvStatus     = $json_result->PVStatus;

            $customerInfo = array("salePersion" => $salePersion,
                                "customer"    => $customer,
                                "site"        => $site,
                                "installDate" => $installDate,
                                "orderPrice"  => $orderPrice,
                                "pvStatus"    => $pvStatus);
        }

        return $customerInfo;
    }

    function getInvoiceInfo($orderID){
        $db  = DBManagerFactory::getInstance();
        $sqlInvoice = "SELECT aos_invoices.id,aos_invoices.status,aos_invoices.number  FROM aos_invoices LEFT JOIN aos_invoices_cstm as csm ON aos_invoices.id = csm.id_c  WHERE csm.solargain_invoices_number_c = '".$orderID."' AND aos_invoices.deleted = 0 LIMIT 0,1";
        $res =  $db->query($sqlInvoice);
        $row = $db->fetchByAssoc($res);
        return $row;
    }
?>