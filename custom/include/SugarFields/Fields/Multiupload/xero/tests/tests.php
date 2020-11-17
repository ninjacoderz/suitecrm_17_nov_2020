<?php
function readHeader($ch, $header)
{
    // read headers
    /*global $filename;

    if ( strpos($header, "Content-disposition: attachment; filename=") !== false ) {
        $filename = str_replace("Content-disposition: attachment; filename=","",$header);
        $filename = trim(str_replace('"','',$filename));
    }*/
    return strlen($header);
}

function downloadPDFFile($templateID="91964331-fd45-e2d8-3f1b-57bbe4371f9c", $recordID = "")
{
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';
    $url = 'https://suitecrm.pure-electric.com.au/index.php';
    // $url = 'http://loc.suitecrm.com/index.php';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);

    $result = explode("\r\n\r\n", $result, 2);
    $response = json_decode($result[1]);
    $session_id = $response->id;
    //$result = explode("\r\n\r\n", $result, 2);
    //$response = json_decode($result[1]);
    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=AOS_Invoices&uid=".$recordID;
    // $source = "http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=AOS_Invoices&uid=".$recordID;
    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, "readHeader");
    $curl_response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($curl_response, 0, $header_size);
    $body = substr($curl_response, $header_size);
    $destination = dirname(__FILE__)."/files/invoice-". $recordID.".pdf";
    // $destination = "C:\Users\ADMIN\Downloads\invoices-". $recordID.".pdf";
    $file = fopen($destination, "w+");
    fputs($file, $body);
    fclose($file);
    curl_close($curl);

    //return $response;

}
global $mod_strings, $sugar_config;
if (isset($_REQUEST)){
    if (!isset($_REQUEST['where'])) $_REQUEST['where'] = "";
}

if ( isset($_REQUEST['wipe'])) {
    session_destroy();
    header("Location: {$here}");

// already got some credentials stored?
} elseif(isset($_REQUEST['refresh'])) {
    $response = $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['oauth_session_handle']);
    if ($XeroOAuth->response['code'] == 200) {
        $session = persistSession($response);
        $oauthSession = retrieveSession();
    } else {
        outputError($XeroOAuth);
        if ($XeroOAuth->response['helper'] == "TokenExpired") $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['session_handle']);
    }

} elseif ( isset($oauthSession['oauth_token']) && isset($_REQUEST) ) {

    $XeroOAuth->config['access_token']  = $oauthSession['oauth_token'];
    $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];
    $XeroOAuth->config['session_handle'] = $oauthSession['oauth_session_handle'];


    if (isset($_REQUEST['invoice'])) {
         if (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['invoice']== 1 ) {

            $xero_invoice = urldecode($_GET['xero_invoice']) ;
            $xero_stc_rebate_invoice = urldecode($_GET['xero_stc_rebate_invoice']);
            $xero_veec_rebate_invoice = urldecode($_GET['xero_veec_rebate_invoice']);
            $xero_shw_rebate_invoice = urldecode($_GET['xero_shw_rebate_invoice']);
            $quantity = urldecode($_GET['quantity']) ;
            $rebate_price = urldecode($_GET['rebate_price']) ;
            //$rebate_type =

            $invoice_type = urldecode($_GET['invoice_type']) ;
            $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
            if($invoice_type == "Default"){
                $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
            }
            if($invoice_type == "Sanden"){
                $templateID = "14066998-993a-e4e0-4d8e-58b79279b475";
            }
            if($invoice_type == "Daikin"){
                $templateID = "83a77470-c8b1-a174-3132-58df1804c777";
            }
            if($invoice_type == "Solar"){
                $templateID = "13e05dc5-5d61-9898-6a07-5918de5ff9e4";
            }
            $record = urldecode($_GET['record']) ;
            $bean = new AOS_Invoices();
            $bean->retrieve($record);

            // Logic with invoice title
            if(strpos($bean->name, "Solargain") === false && strpos($bean->name, "Sanden Pure Electric Warranty") === false){

            } else {
                $title_explode = explode("_", $bean->name);
                if(count($title_explode) > 0 && ( $title_explode[0] == "Solargain"|| $title_explode[0] == "Sanden Pure Electric Warranty")){
                    $account_name = $title_explode[1];
                }
            }
            //$bean = BeanFactory::getBean("AOS_Invoices", $record);
            //die(date_create_from_format ( "d/m/Y", $bean->invoice_date ));date

            $bean_account = new Account();
            $bean_account->retrieve($bean->billing_account_id);
            $date = "";
            $dua_date = "";
            //

            if($bean->invoice_date){
                $dateInfos = explode("/",$bean->invoice_date);
                $date = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
            }

            if($bean->due_date){
                $dateInfos = explode("/",$bean->due_date);
                $dua_date = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
            }
            // Tracking options
            $xml = "    
                        <Options>
                          <Option>
                            <Name>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Name>
                          </Option>
                        </Options>
                        ";

            $response = $XeroOAuth->request('POST', $XeroOAuth->url('TrackingCategories/0c42df0c-f53c-415b-9cc6-a27b9cb50f06/Options', 'core'), array(), $xml);
            if($response['code'] != 200){
                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('TrackingCategories/0c42df0c-f53c-415b-9cc6-a27b9cb50f06/Options', 'core'), array(), $xml);
                if($response['code'] != 200){
                    die(json_encode(array("error"=>"cannot create tracking option, please recheck!")));
                }
            }

            $xml = "<Invoices>
                      <Invoice>
                        <Type>ACCREC</Type>";
            if(isset($xero_invoice) && $xero_invoice!="") $xml .=
                "<InvoiceID>".htmlspecialchars($xero_invoice)."</InvoiceID>";
            $phone_numbers = explode(" ", $bean_account->phone_office);
            $country_code = "";
            $area_code = "";
            $number =  $bean_account->phone_office;
            if(count($phone_numbers) >= 3){
                $country_code = $phone_numbers[0];
                $area_code = $phone_numbers[1];
                $number = $phone_numbers[2];
            }
            $xml .= "
                        <Contact>
                          <Name>".htmlspecialchars($bean->billing_account)."</Name>
                          <Addresses>
                             <Address>
                                <AddressType>POBOX</AddressType>
                             </Address>
                            
                            <Address>
                              <AddressType>STREET</AddressType>
                              <AddressLine1>".htmlspecialchars($bean_account->billing_address_street)."</AddressLine1>
                              <City>".htmlspecialchars($bean_account->billing_address_city)."</City>
                              <PostalCode>".htmlspecialchars($bean_account->billing_address_postalcode)."</PostalCode>
                            </Address>
                          </Addresses>
                          <Phones>
                            <Phone>
                              <PhoneType>DEFAULT</PhoneType>
                              <PhoneNumber>".htmlspecialchars($number)."</PhoneNumber>
                              <PhoneAreaCode>".htmlspecialchars($area_code)."</PhoneAreaCode>
                              <PhoneCountryCode>".htmlspecialchars($country_code)."</PhoneCountryCode>
                            </Phone>
                          </Phones>
                          <IsCustomer>true</IsCustomer>
                        </Contact>
                        
                        <Date>".htmlspecialchars($date)."</Date>
                        <DueDate>".htmlspecialchars($dua_date)."</DueDate>
                        ";

            //thiencode -- update ExpectedPaymentDate
            $payment_expected_date = '';
            $installation_date = '';
            if($_GET['xero_payment_date'] == 'yes'){
                if($bean->status == 'Unpaid' || $bean->status == 'Deposit_Paid'){
                    if($bean->installation_date_c){
                        $dateInfos = explode("/",explode(" ",$bean->installation_date_c)[0]);
                        $installation_date = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
                        $xml .= "<ExpectedPaymentDate>".htmlspecialchars($installation_date)."</ExpectedPaymentDate>";
                        $payment_expected_date = htmlspecialchars($installation_date);
                    }else{
                        $xml .= "<ExpectedPaymentDate>".htmlspecialchars($dua_date)."</ExpectedPaymentDate>";
                        $payment_expected_date = htmlspecialchars($dua_date);
                    }
                }
            }
            //end

            $xml .=    "
                        <LineAmountTypes>Exclusive</LineAmountTypes>
                        <Reference>".htmlspecialchars($bean->name)."</Reference>
                        <InvoiceNumber>".htmlspecialchars($bean->number)."</InvoiceNumber>
                        <LineItems>";
            //Setting Group Line Items

            $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND deleted = 0";
            $result = $bean->db->query($sql);

            while ($row = $bean->db->fetchByAssoc($result)) {
                if(isset($row) && $row != null ){

                    $unit_price = "<UnitAmount>".$row["product_unit_price"]."</UnitAmount>";
                    // VEEC Invoice
                    if($row["product_id"] == "cbfafe6b-5e84-d976-8e32-574fc106b13f"){
                        $unit_price = "<UnitAmount>".(-$row["product_unit_price"])."</UnitAmount>";
                        if(isset($rebate_price) && $rebate_price!=""){
                            $unit_price = "<UnitAmount>".(-$rebate_price)."</UnitAmount>";
                        }
                        $date_for_rebate = strtotime(str_replace("/","-",$bean->due_date)) + 15*24*60*60;
                        $date_for_rebate = date("Y-m-d", $date_for_rebate)."T00:00:00";
                        $date_for_veec = strtotime(str_replace("/","-",$bean->due_date));
                        $date_for_veec = date("Y-m-d", $date_for_veec)."T00:00:00";
                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                                <Type>ACCREC</Type>
                                    <Contact>
                                        <Name>Green Energy Trading Pty Ltd</Name>
                                    </Contact>
                                <Date>".$date_for_veec."</Date>
                                <DueDate>".$date_for_rebate."</DueDate>
                                <LineAmountTypes>Exclusive</LineAmountTypes>
                                <Reference>".$bean->name."</Reference>";
                        if(isset($xero_veec_rebate_invoice) && $xero_veec_rebate_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$xero_veec_rebate_invoice."</InvoiceID>";
                        $rebate_xml .= "
                                <InvoiceNumber>".$bean->number." - VEECs Rebate</InvoiceNumber>
                                <LineItems>";

                        $rebate_xml .= "
                           <LineItem>
                                <ItemCode>".$product_mapping[$row["product_id"]]."</ItemCode>
                                <Quantity>".$row["product_qty"]."</Quantity>
                                ". $unit_price ."
                                <Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                </Tracking>
                           </LineItem>";

                        $rebate_xml .= "
                        </LineItems>
                          </Invoice>
                        </Invoices>";
                        $method = 'PUT';
                        if( isset($xero_veec_rebate_invoice) && $xero_veec_rebate_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);

                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $veec_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            //thienpb code - put note for Payment expected for vecc rebate invoice
                            if($_GET['xero_payment_date'] == 'yes'){
                                $history_vecc_invoice = "<HistoryRecords>
                                                <HistoryRecord>
                                                    <Details>Payment expected on ".date('d M Y', strtotime($payment_expected_date)).". </Details>
                                                </HistoryRecord>
                                            </HistoryRecords>";
                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices/'.$veec_invoice_id.'/History', 'core'), array(), $history_vecc_invoice);
                                if ($XeroOAuth->response['code'] == 200) {
                                } else {
                                    outputError($XeroOAuth);
                                }
                            }else{
                                $bean->xero_veec_rebate_invoice_c = $veec_invoice_id;
                                $bean->save();
                                $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                if ($XeroOAuth->response['code'] == 200) {
                                    //echo "Attachment successfully created against this invoice.";
                                    echo "<xero_veec_rebate_invoice_c>" .$veec_invoice_id  ."</xero_veec_rebate_invoice_c>";
                                } else {
                                    outputError($XeroOAuth);
                                }
                            }
                        }
                        $unit_price = "<UnitAmount>".$row["product_unit_price"]."</UnitAmount>";
                    }
                    //STC Rebate
                    if($row["product_id"] == "4efbea92-c52f-d147-3308-569776823b19"  && (strpos($bean->name, 'WH') !== 0)){
                        $unit_price = "
                                        <UnitAmount>" . (-$row["product_unit_price"] ). "</UnitAmount>
                                        ";

                        if(isset($rebate_price) && $rebate_price!=""){
                            $unit_price = "<UnitAmount>".(-$rebate_price)."</UnitAmount>";
                        }
                        $date_for_rebate = strtotime(str_replace("/","-",$bean->due_date)) + 30*24*60*60;
                        $date_for_rebate = date("Y-m-d", $date_for_rebate)."T00:00:00";
                        $date_for_stc = strtotime(str_replace("/","-",$bean->due_date));
                        $date_for_stc = date("Y-m-d", $date_for_stc)."T00:00:00";
                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                            <Type>ACCREC</Type>
                                <Contact>
                                    <Name>Green Energy Trading Pty Ltd</Name>
                                </Contact>
                            <Date>" . $date_for_stc . "</Date>
                            <DueDate>" . $date_for_rebate . "</DueDate>
                            <LineAmountTypes>Exclusive</LineAmountTypes>
                            <Reference>" . htmlspecialchars($bean->name) . "</Reference>";
                        if(isset($xero_stc_rebate_invoice) && $xero_stc_rebate_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$xero_stc_rebate_invoice."</InvoiceID>";
                        $rebate_xml .= "
                            <InvoiceNumber>" . $bean->number . " - STCs Rebate</InvoiceNumber>
                            <LineItems>";

                        $rebate_xml .= "
                           <LineItem>
                                <ItemCode>" . htmlspecialchars($product_mapping[$row["product_id"]]) . "</ItemCode>
                                <Quantity>" . $row["product_qty"] . "</Quantity>"
                            .$unit_price.
                            "<Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>" .htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                </Tracking>
                           </LineItem>";

                        $rebate_xml .= "
                        </LineItems>
                          </Invoice>
                        </Invoices>";
                        $method = 'PUT';
                        if( isset($xero_stc_rebate_invoice) && $xero_stc_rebate_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);
                        // File attachment
                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $stc_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            //thienpb code - put note for Payment expected stc rebate invoice
                            if($_GET['xero_payment_date'] == 'yes'){
                                $history_stc_invoice = "<HistoryRecords>
                                                <HistoryRecord>
                                                    <Details>Payment expected on ".date('d M Y', strtotime($payment_expected_date)).". </Details>
                                                </HistoryRecord>
                                            </HistoryRecords>";
                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices/'.$stc_invoice_id.'/History', 'core'), array(), $history_stc_invoice);
                                if ($XeroOAuth->response['code'] == 200) {
                                } else {
                                    outputError($XeroOAuth);
                                }
                            }else{
                                $bean->xero_stc_rebate_invoice_c = $stc_invoice_id;
                                $bean->save();

                                if (count($invoice->Invoices[0])>0) {
                                    downloadPDFFile($templateID, $record);
                                    $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                                    $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                    if ($XeroOAuth->response['code'] == 200) {
                                        //echo "Attachment successfully created against this invoice.";
                                        echo "<xero_stc_rebate_invoice_c>" .$stc_invoice_id  ."</xero_stc_rebate_invoice_c>";
                                    } else {
                                        outputError($XeroOAuth);
                                    }
                                }
                            }
                        }
                        $unit_price = "
                                        <UnitAmount>" . $row["product_unit_price"] . "</UnitAmount>
                                        ";
                    }
                    //SV_SHWR Rebate
                    if($row["product_id"] == "431a9064-7cbb-6a44-e7ba-5d5b794137c7"){
                            $unit_price = "
                                            <UnitAmount>" . (-$row["product_unit_price"] ). "</UnitAmount>
                                            ";

                            if(isset($rebate_price) && $rebate_price!=""){
                                $unit_price = "<UnitAmount>".(-$rebate_price)."</UnitAmount>";
                            }
                            $date_for_rebate = strtotime(str_replace("/","-",$bean->due_date)) + 30*24*60*60;
                            $date_for_rebate = date("Y-m-d", $date_for_rebate)."T00:00:00";
                            $date_for_SV_SHWR = strtotime(str_replace("/","-",$bean->due_date));
                            $date_for_SV_SHWR = date("Y-m-d", $date_for_SV_SHWR)."T00:00:00";
                            $rebate_xml = "
                            <Invoices>
                                <Invoice>
                                <Type>ACCREC</Type>
                                    <Contact>
                                        <Name>Green Energy Trading Pty Ltd</Name>
                                    </Contact>
                                <Date>" . $date_for_SV_SHWR . "</Date>
                                <DueDate>" . $date_for_rebate . "</DueDate>
                                <LineAmountTypes>Exclusive</LineAmountTypes>
                                <Reference>" . htmlspecialchars($bean->name) . "</Reference>";
                            if(isset($xero_SHW_rebate_invoice) && $xero_SHW_rebate_invoice!="") $rebate_xml .=
                                "<InvoiceID>".$xero_SHW_rebate_invoice."</InvoiceID>";
                            $rebate_xml .= "
                                <InvoiceNumber>" . $bean->number . " - SHW Rebate</InvoiceNumber>
                                <LineItems>";

                            $rebate_xml .= "
                                <LineItem>
                                    <ItemCode>" . htmlspecialchars($product_mapping[$row["product_id"]]) . "</ItemCode>
                                    <Quantity>" . $row["product_qty"] . "</Quantity>"
                                .$unit_price.
                                "<Tracking>
                                        <TrackingCategory>
                                            <Name>Customer</Name>
                                            <Option>" .htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                        </TrackingCategory>
                                    </Tracking>
                                </LineItem>";

                            $rebate_xml .= "
                            </LineItems>
                                </Invoice>
                            </Invoices>";
                            $method = 'PUT';
                            if( isset($xero_SHW_rebate_invoice) && $xero_SHW_rebate_invoice!="") {
                                $method = 'POST';
                            }
                            $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);
                            // File attachment
                            if ($XeroOAuth->response['code'] == 200) {
                                $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                                $shw_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                                
                                if($_GET['xero_payment_date'] == 'yes'){
                                    $history_shw_invoice = "<HistoryRecords>
                                                    <HistoryRecord>
                                                        <Details>Payment expected on ".date('d M Y', strtotime($payment_expected_date)).". </Details>
                                                    </HistoryRecord>
                                                </HistoryRecords>";
                                    $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices/'.$shw_invoice_id.'/History', 'core'), array(), $history_shw_invoice);
                                    if ($XeroOAuth->response['code'] == 200) {
                                    } else {
                                        outputError($XeroOAuth);
                                    }
                                }else{
                                    $bean->xero_shw_rebate_invoice_c = $shw_invoice_id;
                                    $bean->save();

                                    if (count($invoice->Invoices[0])>0) {
                                        downloadPDFFile($templateID, $record);
                                        $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                                        $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                        if ($XeroOAuth->response['code'] == 200) {
                                            //echo "Attachment successfully created against this invoice.";
                                            echo "<xero_shw_rebate_invoice_c>" .$shw_invoice_id  ."</xero_shw_rebate_invoice_c>";
                                        } else {
                                            outputError($XeroOAuth);
                                        }
                                    }
                                }
                            }
                            $unit_price = "
                                        <UnitAmount>" . $row["product_unit_price"] . "</UnitAmount>
                                        ";
                }
                    if(isset($product_mapping[$row["product_id"]])){
                        $xml .= "
                           <LineItem>
                                <ItemCode>".$product_mapping[$row["product_id"]]."</ItemCode>
                                <Quantity>".$row["product_qty"]."</Quantity>
                                ".
                            $unit_price
                            ."
                                <Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                  </Tracking>";
                        if($row["product_id"] == "78b8b420-5003-8249-3dd3-5918dd4d0d06" ){
                            $xml.= "<Description>".htmlspecialchars($row["item_description"])."</Description>";
                        }else{
                            $xml.= "<Description>".htmlspecialchars($row["name"])."</Description>";
                        }
                        $xml .= "
                        </LineItem>";
                    }
                }
            }
            $xml .= "
                    </LineItems>
                      </Invoice>
                    </Invoices>";
            $method = 'PUT';
            if( isset($xero_invoice) && $xero_invoice!="") {
                $method = 'POST';
            }
           
            $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {

                $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                $invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;

                //thienpb code - put note for Payment expected
                if($_GET['xero_payment_date'] == 'yes'){
                    $history_invoice = "<HistoryRecords>
                                    <HistoryRecord>
                                        <Details>Payment expected on ".date('d M Y', strtotime($payment_expected_date)).". </Details>
                                    </HistoryRecord>
                                </HistoryRecords>";
                    $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/History', 'core'), array(), $history_invoice);
                    if ($XeroOAuth->response['code'] == 200) {
                    } else {
                        outputError($XeroOAuth);
                    }
                }else{
                    $bean->xero_invoice_c = $invoice_id;
                    $bean->save();

                    echo "" . count($invoice->Invoices[0]). " invoice created in this Xero organisation.";
                    if (count($invoice->Invoices[0])>0) {
                        echo "The first one is: </br>";
                        pr($invoice->Invoices[0]->Invoice);
                        downloadPDFFile($templateID, $record);
                        $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                        $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                        if ($XeroOAuth->response['code'] == 200) {
                            //echo "Attachment successfully created against this invoice.";
                            echo "<xero_invoice_c>" .$invoice_id  ."</xero_invoice_c>";
                        } else {
                            outputError($XeroOAuth);
                        }
                    }
                }
            } else {
                outputError($XeroOAuth);
            }         
        }elseif($_REQUEST['xero_invoice'] != '' && isset($_REQUEST['method']) && $_REQUEST['method'] == 'post'){
            //tu-code update xero invoices
            $xero_invoice = urldecode($_GET['xero_invoice']) ;
            $xero_stc_rebate_invoice = urldecode($_GET['xero_stc_rebate_invoice']);
            $xero_veec_rebate_invoice = urldecode($_GET['xero_veec_rebate_invoice']);
            $quantity = urldecode($_GET['quantity']) ;
            $rebate_price = urldecode($_GET['rebate_price']) ;
            //$rebate_type =

            $invoice_type = urldecode($_GET['invoice_type']) ;
            $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
            if($invoice_type == "Default"){
                $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
            }
            if($invoice_type == "Sanden"){
                $templateID = "14066998-993a-e4e0-4d8e-58b79279b475";
            }
            if($invoice_type == "Daikin"){
                $templateID = "83a77470-c8b1-a174-3132-58df1804c777";
            }
            if($invoice_type == "Solar"){
                $templateID = "13e05dc5-5d61-9898-6a07-5918de5ff9e4";
            }
            $record = urldecode($_GET['record']) ;
            $bean = new AOS_Invoices();
            $bean->retrieve($record);

            // Logic with invoice title
            if(strpos($bean->name, "Solargain") === false && strpos($bean->name, "Sanden Pure Electric Warranty") === false){

            } else {
                $title_explode = explode("_", $bean->name);
                if(count($title_explode) > 0 && ( $title_explode[0] == "Solargain"|| $title_explode[0] == "Sanden Pure Electric Warranty")){
                    $account_name = $title_explode[1];
                }
            }
            //$bean = BeanFactory::getBean("AOS_Invoices", $record);
            //die(date_create_from_format ( "d/m/Y", $bean->invoice_date ));date

            $bean_account = new Account();
            $bean_account->retrieve($bean->billing_account_id);
            $date = "";
            $dua_date = "";
            //

            if($bean->invoice_date){
                $dateInfos = explode("/",$bean->invoice_date);
                $date = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
            }

            if($bean->due_date){
                $dateInfos = explode("/",$bean->due_date);
                $dua_date = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
            }
            // Tracking options
            $xml = "    
                        <Options>
                          <Option>
                            <Name>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Name>
                          </Option>
                        </Options>
                        ";

            $response = $XeroOAuth->request('POST', $XeroOAuth->url('TrackingCategories/0c42df0c-f53c-415b-9cc6-a27b9cb50f06/Options', 'core'), array(), $xml);
            if($response['code'] != 200){
                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('TrackingCategories/0c42df0c-f53c-415b-9cc6-a27b9cb50f06/Options', 'core'), array(), $xml);
                if($response['code'] != 200){
                    die(json_encode(array("error"=>"cannot create tracking option, please recheck!")));
                }
            }

            $xml = "<Invoices>
                      <Invoice>
                        <Type>ACCREC</Type>";
            if(isset($xero_invoice) && $xero_invoice!="") $xml .=
                "<InvoiceID>".htmlspecialchars($xero_invoice)."</InvoiceID>";
            $phone_numbers = explode(" ", $bean_account->phone_office);
            $country_code = "";
            $area_code = "";
            $number =  $bean_account->phone_office;
            if(count($phone_numbers) >= 3){
                $country_code = $phone_numbers[0];
                $area_code = $phone_numbers[1];
                $number = $phone_numbers[2];
            }
            $xml .= "
                        <Contact>
                          <Name>".htmlspecialchars($bean->billing_account)."</Name>
                          <Addresses>
                             <Address>
                                <AddressType>POBOX</AddressType>
                             </Address>
                            
                            <Address>
                              <AddressType>STREET</AddressType>
                              <AddressLine1>".htmlspecialchars($bean_account->billing_address_street)."</AddressLine1>
                              <City>".htmlspecialchars($bean_account->billing_address_city)."</City>
                              <PostalCode>".htmlspecialchars($bean_account->billing_address_postalcode)."</PostalCode>
                            </Address>
                          </Addresses>
                          <Phones>
                            <Phone>
                              <PhoneType>DEFAULT</PhoneType>
                              <PhoneNumber>".htmlspecialchars($number)."</PhoneNumber>
                              <PhoneAreaCode>".htmlspecialchars($area_code)."</PhoneAreaCode>
                              <PhoneCountryCode>".htmlspecialchars($country_code)."</PhoneCountryCode>
                            </Phone>
                          </Phones>
                          <IsCustomer>true</IsCustomer>
                        </Contact>
                        
                        <Date>".htmlspecialchars($date)."</Date>
                        <DueDate>".htmlspecialchars($dua_date)."</DueDate>
                        ";

            //thiencode -- update ExpectedPaymentDate
            $payment_expected_date = '';
            $installation_date = '';
            //end

            $xml .=    "
                        <LineAmountTypes>Exclusive</LineAmountTypes>
                        <Reference>".htmlspecialchars($bean->name)."</Reference>
                        <InvoiceNumber>".htmlspecialchars($bean->number)."</InvoiceNumber>
                        <LineItems>";
            //Setting Group Line Items

            $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND deleted = 0";
            $result = $bean->db->query($sql);

            while ($row = $bean->db->fetchByAssoc($result)) {
                if(isset($row) && $row != null ){

                    $unit_price = "<UnitAmount>".$row["product_unit_price"]."</UnitAmount>";
                    // VEEC Invoice
                    if($row["product_id"] == "cbfafe6b-5e84-d976-8e32-574fc106b13f"){
                        $unit_price = "<UnitAmount>".(-$row["product_unit_price"])."</UnitAmount>";
                        if(isset($rebate_price) && $rebate_price!=""){
                            $unit_price = "<UnitAmount>".(-$rebate_price)."</UnitAmount>";
                        }

                        $date_for_rebate = strtotime($dua_date) + 15*24*60*60;
                        $date_for_rebate = date("Y-m-d", $date_for_rebate)."T00:00:00";
                        $date_for_veec = strtotime($dua_date);
                        $date_for_veec = date("Y-m-d", $date_for_veec)."T00:00:00";
                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                                <Type>ACCREC</Type>
                                    <Contact>
                                        <Name>Green Energy Trading Pty Ltd</Name>
                                    </Contact>
                                <Date>".$date_for_veec."</Date>
                                <DueDate>".$date_for_rebate."</DueDate>
                                <LineAmountTypes>Exclusive</LineAmountTypes>
                                <Reference>".$bean->name."</Reference>";
                        if(isset($xero_veec_rebate_invoice) && $xero_veec_rebate_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$xero_veec_rebate_invoice."</InvoiceID>";
                        $rebate_xml .= "
                                <InvoiceNumber>".$bean->number." - VEECs Rebate</InvoiceNumber>
                                <LineItems>";

                        $rebate_xml .= "
                           <LineItem>
                                <ItemCode>".$product_mapping[$row["product_id"]]."</ItemCode>
                                <Quantity>".$row["product_qty"]."</Quantity>
                                ". $unit_price ."
                                <Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                </Tracking>
                           </LineItem>";

                        $rebate_xml .= "
                        </LineItems>
                          </Invoice>
                        </Invoices>";
                        $method = 'PUT';
                        if( isset($xero_veec_rebate_invoice) && $xero_veec_rebate_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);

                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $veec_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            //thienpb code - put note for Payment expected for vecc rebate invoice
                                $history_vecc_invoice = "<HistoryRecords>
                                                <HistoryRecord>
                                                    <Details>Payment expected on ".date('d M Y', strtotime($payment_expected_date)).". </Details>
                                                </HistoryRecord>
                                            </HistoryRecords>";
                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices/'.$veec_invoice_id.'/History', 'core'), array(), $history_vecc_invoice);
                                if ($XeroOAuth->response['code'] == 200) {
                                } else {
                                    outputError($XeroOAuth);
                                }
                            
                                $bean->xero_veec_rebate_invoice_c = $veec_invoice_id;
                                $bean->save();
                                downloadPDFFile($templateID, $record);
                                $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                if ($XeroOAuth->response['code'] == 200) {
                                    //echo "Attachment successfully created against this invoice.";
                                    echo "<xero_veec_rebate_invoice_c>" .$veec_invoice_id  ."</xero_veec_rebate_invoice_c>";
                                } else {
                                    outputError($XeroOAuth);
                                }
                        }
                        $unit_price = "<UnitAmount>".$row["product_unit_price"]."</UnitAmount>";
                    }
                    //STC Rebate
                    if($row["product_id"] == "4efbea92-c52f-d147-3308-569776823b19"){
                        $unit_price = "
                                        <UnitAmount>" . (-$row["product_unit_price"] ). "</UnitAmount>
                                        ";

                        if(isset($rebate_price) && $rebate_price!=""){
                            $unit_price = "<UnitAmount>".(-$rebate_price)."</UnitAmount>";
                        }
                        $dateInfos = explode("/",$bean->due_date);
                        $dua_date = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]";
                        $date_for_rebate = strtotime($dua_date) + 30*24*60*60;
                        $date_for_rebate = date("Y-m-d", $date_for_rebate)."T00:00:00";
                        $date_for_stc = strtotime($dua_date);
                        $date_for_stc = date("Y-m-d", $date_for_stc)."T00:00:00";
                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                            <Type>ACCREC</Type>
                                <Contact>
                                    <Name>Green Energy Trading Pty Ltd</Name>
                                </Contact>
                            <Date>" . $date_for_stc . "</Date>
                            <DueDate>" . $date_for_rebate . "</DueDate>
                            <LineAmountTypes>Exclusive</LineAmountTypes>
                            <Reference>" . htmlspecialchars($bean->name) . "</Reference>";
                        if(isset($xero_stc_rebate_invoice) && $xero_stc_rebate_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$xero_stc_rebate_invoice."</InvoiceID>";
                        $rebate_xml .= "
                            <InvoiceNumber>" . $bean->number . " - STCs Rebate</InvoiceNumber>
                            <LineItems>";

                        $rebate_xml .= "
                           <LineItem>
                                <ItemCode>" . htmlspecialchars($product_mapping[$row["product_id"]]) . "</ItemCode>
                                <Quantity>" . $row["product_qty"] . "</Quantity>"
                            .$unit_price.
                            "<Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>" .htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                </Tracking>
                           </LineItem>";

                        $rebate_xml .= "
                        </LineItems>
                          </Invoice>
                        </Invoices>";
                        $method = 'PUT';
                        if( isset($xero_stc_rebate_invoice) && $xero_stc_rebate_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);
                        // File attachment
                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $stc_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            //thienpb code - put note for Payment expected stc rebate invoice
                                $bean->xero_stc_rebate_invoice_c = $stc_invoice_id;
                                $bean->save();

                                if (count($invoice->Invoices[0])>0) {
                                    downloadPDFFile($templateID, $record);
                                    $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");
                                    $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                    if ($XeroOAuth->response['code'] == 200) {
                                        //echo "Attachment successfully created against this invoice.";
                                        echo "<xero_stc_rebate_invoice_c>" .$stc_invoice_id  ."</xero_stc_rebate_invoice_c>";
                                    } else {
                                        outputError($XeroOAuth);
                                    }
                                }
                        }
                        $unit_price = "
                                        <UnitAmount>" . $row["product_unit_price"] . "</UnitAmount>
                                        ";
                    }
                    if(isset($product_mapping[$row["product_id"]])){
                        $xml .= "
                           <LineItem>
                                <ItemCode>".$product_mapping[$row["product_id"]]."</ItemCode>
                                <Quantity>".$row["product_qty"]."</Quantity>
                                ".
                            $unit_price
                            ."
                                <Tracking>
                                    <TrackingCategory>
                                        <Name>Customer</Name>
                                        <Option>".htmlspecialchars(($account_name?$account_name:$bean->billing_account))."</Option>
                                    </TrackingCategory>
                                  </Tracking>";
                        if($row["product_id"] == "78b8b420-5003-8249-3dd3-5918dd4d0d06" ){

                            $xml.= "<Description>".htmlspecialchars($row["item_description"])."</Description>";
                        }else{
                            $xml.= "<Description>".htmlspecialchars($row["name"])."</Description>";
                        }
                        $xml .= "
                        </LineItem>";
                    }
                }
            }
            $xml .= "
                    </LineItems>
                      </Invoice>
                    </Invoices>";
            $method = 'PUT';
            if( isset($xero_invoice) && $xero_invoice!="") {
                $method = 'POST';
            }
            $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {

                $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                $invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;

                //thienpb code - put note for Payment expected
                    $bean->xero_invoice_c = $invoice_id;
                    $bean->save();

                    echo "" . count($invoice->Invoices[0]). " invoice created in this Xero organisation.";
                    if (count($invoice->Invoices[0])>0) {
                        echo "The first one is: </br>";
                        pr($invoice->Invoices[0]->Invoice);
                        downloadPDFFile($templateID, $record);
                        $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");
                        // $attachmentFile =  file_get_contents("C:\Users\ADMIN\Downloads\invoices-d2ae9534-b9fe-d154-daa8-5c6f68778269.pdf");                      
                        $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                        if ($XeroOAuth->response['code'] == 200) {
                            //echo "Attachment successfully created against this invoice.";
                            echo "<xero_invoice_c>" .$invoice_id  ."</xero_invoice_c>";
                        } else {
                            outputError($XeroOAuth);
                        }
                    }
            } else {
                outputError($XeroOAuth);
            }
      }



    }
    if (isset($_REQUEST['invoicesfilter'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('Where' => 'Contact.Name.Contains("Martin")'));

        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->Invoices[0]). " matching invoices in this Xero organisation, the first one is: </br>";
            pr($accounts->Invoices[0]->Invoice);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['invoicesmodified'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('If-Modified-Since' => gmdate("M d Y H:i:s",(time() - (1 * 24 * 60 * 60)))));
        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->Invoices[0]). " matching invoices in this Xero organisation, the first one is: </br>";
            pr($accounts->Invoices[0]->Invoice);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['banktransactions'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('BankTransactions', 'core'), array(), "", "xml");
            if ($XeroOAuth->response['code'] == 200) {
                $banktransactions = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($banktransactions->BankTransactions[0]). " bank transactions in this Xero organisation.";
                if (count($banktransactions->BankTransactions[0])>0) {
                    echo "The first one is: </br>";
                    pr($banktransactions->BankTransactions[0]->BankTransaction);
                }
            } else {
                outputError($XeroOAuth);
            }
        } elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" ) {
            $xml = "<BankTransactions>
                     <BankTransaction>
                     <Type>SPEND</Type>
                     <Contact>
                       <Name>Westpac</Name>
                     </Contact>
                     <Date>2013-04-16T00:00:00</Date>
                     <LineItems>
                       <LineItem>
                         <Description>Yearly Bank &amp; Account Fee</Description>
                         <Quantity>1.0000</Quantity>
                         <UnitAmount>20.00</UnitAmount>
                         <AccountCode>400</AccountCode>
                      </LineItem>
                    </LineItems>
                    <BankAccount>
                      <Code>090</Code>
                    </BankAccount>
                  </BankTransaction>
                </BankTransactions>";
            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('BankTransactions', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $banktransactions = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($banktransactions->BankTransactions[0]). " successful bank transaction(s) created in this Xero organisation.";
                if (count($banktransactions->BankTransactions[0])>0) {
                    echo "The first one is: </br>";
                    pr($banktransactions->BankTransactions[0]->BankTransaction);
                }
            } else {
                outputError($XeroOAuth);
            }
        }
    }

    if( isset($_REQUEST['contacts'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Contacts', 'core'), array());
            if ($XeroOAuth->response['code'] == 200) {
                $contacts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($contacts->Contacts[0]). " contacts in this Xero organisation, the first one is: </br>";
                pr($contacts->Contacts[0]->Contact);

            } else {
                outputError($XeroOAuth);
            }
        } elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "post" ){
            $xml = "<Contacts>
                     <Contact>
                       <Name>Matthew and son</Name>
                       <EmailAddress>emailaddress@yourdomain.com</EmailAddress>
                       <SkypeUserName>matthewson_test99</SkypeUserName>
                       <FirstName>Matthew</FirstName>
                       <LastName>Masters</LastName>
                     </Contact>
                   </Contacts>
                   ";
            $response = $XeroOAuth->request('POST', $XeroOAuth->url('Contacts', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $contact = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($contact->Contacts[0]). " contact created/updated in this Xero organisation.";
                if (count($contact->Contacts[0])>0) {
                    echo "The first one is: </br>";
                    pr($contact->Contacts[0]->Contact);
                }
            } else {
                outputError($XeroOAuth);
            }
        }elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "put" ){
            $xml = "<Contacts>
            <Contact>
              <Name>Orlena Greenville</Name>
            </Contact>
          </Contacts>";
            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Contacts', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $contacts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($contacts->Contacts[0]). " successful contact(s) created in this Xero organisation.";
                if(count($contacts->Contacts[0])>0){
                    echo "The first one is: </br>";
                    pr($contacts->Contacts[0]->Contact);
                }
            } else {
                outputError($XeroOAuth);
            }
        }
    }

    if( isset($_REQUEST['items'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Items', 'core'), array());
            if ($XeroOAuth->response['code'] == 200) {
                $items = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($items->Items[0]). " items in this Xero organisation, the first one is: </br>";
                pr($items->Items[0]->Item);

            } else {
                outputError($XeroOAuth);
            }
        } elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "put" ){
            $xml = "<Items>
                     <Item>
                       <Code>ITEM-CODE-01</Code>
                      </Item>
                   </Items>
                   ";
            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Items', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $item = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($item->Items). " item created in this Xero organisation. ";
                if (count($item->Items[0])>0) {
                    echo "The item is: </br>";
                    pr($item->Items[0]->Item);
                }
            } else {
                outputError($XeroOAuth);
            }
        }
    }

    if (isset($_REQUEST['organisation'])&&$_REQUEST['request']=="") {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Organisation', 'core'), array('page' => 0));
        if ($XeroOAuth->response['code'] == 200) {
            $organisation = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "Organisation name: " . $organisation->Organisations[0]->Organisation->Name;
        } else {
            outputError($XeroOAuth);
        }
    }elseif (isset($_REQUEST['organisation'])&&$_REQUEST['request']=="json") {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Organisation', 'core'), array(), $xml, 'json');
        if ($XeroOAuth->response['code'] == 200) {
            $organisation = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            $json = json_decode(json_encode($organisation),true);
            echo "Organisation name: " . $json['Organisations'][0]['Name'];
        } else {
            outputError($XeroOAuth);
        }
    }

    if (isset($_REQUEST['trialbalance'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Reports/TrialBalance', 'core'), array('page' => 0));
        if ($XeroOAuth->response['code'] == 200) {
            $report = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "Organisation name: " . $report->Organisations[0]->Organisation->Name;
        } else {
            outputError($XeroOAuth);
        }
    }

    if (isset($_REQUEST['trackingcategories'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('TrackingCategories', 'core'), array('page' => 0));
            if ($XeroOAuth->response['code'] == 200) {
                $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($tracking->TrackingCategories[0]). " tracking categories in this Xero organisation, the first with ". count($tracking->TrackingCategories[0]->TrackingCategory->Options) ." options. </br>";
                echo "The first one has tracking category name: " . $tracking->TrackingCategories[0]->TrackingCategory->Name;
                echo "</br>The first option in that category is: " . $tracking->TrackingCategories[0]->TrackingCategory->Options->Option[0]->Name;
            } else {
                outputError($XeroOAuth);
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "getarchived") {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('TrackingCategories', 'core'), array('includeArchived' => 'true'));
            if ($XeroOAuth->response['code'] == 200) {
                $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($tracking->TrackingCategories[0]). " tracking categories in this Xero organisation, the first with ". count($tracking->TrackingCategories[0]->TrackingCategory->Options) ." options. </br>";
                echo "The first one has tracking category name: " . $tracking->TrackingCategories[0]->TrackingCategory->Name;
                echo "</br>The first option in that category is: " . $tracking->TrackingCategories[0]->TrackingCategory->Options->Option[0]->Name;
            } else {
                outputError($XeroOAuth);
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['trackingcategories']== 1 ) {
            $xml = "<TrackingCategories>
                      <TrackingCategory>
                        <Name>Salespersons</Name>
                        <Status>ACTIVE</Status>
                      </TrackingCategory>
                    </TrackingCategories>";
            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('TrackingCategories', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($tracking->TrackingCategories[0]). " tracking created in this Xero organisation.";
                if (count($tracking->TrackingCategories[0])>0) {
                    echo "The first one is: </br>";
                    pr($tracking->TrackingCategories[0]->TrackingCategory);
                }
            } else {
                outputError($XeroOAuth);
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "archive" && $_REQUEST['trackingcategories']== 1 ) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('TrackingCategories', 'core'), array());
            if ($XeroOAuth->response['code'] == 200) {
                $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($tracking->TrackingCategories[0]). " tracking categories in this Xero organisation. </br>";
                if(count($tracking->TrackingCategories[0]) > 0){
                    echo "The first one has tracking category name: " . $tracking->TrackingCategories[0]->TrackingCategory->Name;
                    echo ", and will be archived.</br>";
                }
            }
            $xml = "<TrackingCategories>
                      <TrackingCategory>
                        <Name>".$tracking->TrackingCategories[0]->TrackingCategory->Name."</Name>
                        <TrackingCategoryID>".$tracking->TrackingCategories[0]->TrackingCategory->TrackingCategoryID."</TrackingCategoryID>
                        <Status>ARCHIVED</Status>
                      </TrackingCategory>
                    </TrackingCategories>";
            if(count($tracking->TrackingCategories[0]) > 0){
                $response = $XeroOAuth->request('POST', $XeroOAuth->url('TrackingCategories', 'core'), array(), $xml);
                if ($XeroOAuth->response['code'] == 200) {
                    $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                    echo "" . count($tracking->TrackingCategories[0]). " tracking archived in this Xero organisation.";
                    if (count($tracking->TrackingCategories[0])>0) {
                        echo "The first one is: </br>";
                        pr($tracking);
                    }
                } else {
                    outputError($XeroOAuth);
                }
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "restore" && $_REQUEST['trackingcategories']== 1 ) {
            $xml = "<TrackingCategories>
                      <TrackingCategory>
                        <Name>Region</Name>

                        <Status>ACTIVE</Status>
                      </TrackingCategory>
                    </TrackingCategories>";
            $response = $XeroOAuth->request('POST', $XeroOAuth->url('TrackingCategories', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $tracking = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($tracking->TrackingCategories[0]). " tracking restored in this Xero organisation.";
                if (count($tracking->TrackingCategories[0])>0) {
                    echo "The first one is: </br>";
                    pr($tracking->TrackingCategories[0]);
                }
            } else {
                outputError($XeroOAuth);
            }
        }
    }

    if (isset($_REQUEST['folders'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Folders', 'file'), array());
            if ($XeroOAuth->response['code'] == 200) {
                $folders = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($folders). " folders in this Xero organisation, the first one is: </br>";
                pr($folders->Folder[0]);
            } else {
                outputError($XeroOAuth);
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "files" && $_REQUEST['folders']== 1 ) {

            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Folders', 'file'), array());
            if ($XeroOAuth->response['code'] == 200) {
                $folder = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($folder). " folders in this Xero organisation. </br>";
                if(count($folder->Folder[0]) > 0){
                    echo "The first one has the name: " . $folder->Folder[0]->Name;
                    echo ", and will be checked for files.</br>";
                }
            }

            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Folders/'.$folder->Folder[0]->Id.'/Files', 'file'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $folders = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . $folders->FileCount. " files in this folder.";
                if (count($folders->Files[0])>0) {
                    echo "The first one is: </br>";
                    pr($folders->Files[0]->Items[0]->File[0]);
                }
            } else {
                outputError($XeroOAuth);
            }
        }
    }

    if (isset($_REQUEST['multipleoperations'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Organisation', 'core'), array('page' => 0));
        if ($XeroOAuth->response['code'] == 200) {
        } else {
            outputError($XeroOAuth);
        }

        $xml = "<ContactGroups>
                <ContactGroup>
                  <Name>Test group</Name>
                  <Status>ACTIVE</Status>
                </ContactGroup>
              </ContactGroups>";
        $response = $XeroOAuth->request('POST', $XeroOAuth->url('ContactGroups', 'core'), array(), $xml);
        if ($XeroOAuth->response['code'] == 200) {
        } else {
            outputError($XeroOAuth);
        }
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('ContactGroups', 'core'), array('page' => 0));
        if ($XeroOAuth->response['code'] == 200) {
        } else {
            outputError($XeroOAuth);
        }

    }

}
