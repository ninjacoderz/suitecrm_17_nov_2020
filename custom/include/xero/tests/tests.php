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
    //http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid=9bff5445-2426-5ecc-94bf-59013c3b70c3
    //$url = "http://loc.suitecrm.com/index.php";//"https://suitecrm.pure-electric.com.au/index.php";

    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    $url = 'https://suitecrm.pure-electric.com.au/index.php';
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
    $file = fopen($destination, "w+");
    fputs($file, $body);
    fclose($file);
    curl_close($curl);

    //return $response;

}

function sendInvoicePdf($recordID)
{
    $templateID="91964331-fd45-e2d8-3f1b-57bbe4371f9c";

    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    //http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid=9bff5445-2426-5ecc-94bf-59013c3b70c3
    //$url = "http://loc.suitecrm.com/index.php";//"https://suitecrm.pure-electric.com.au/index.php";

    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    $url = 'https://suitecrm.pure-electric.com.au/index.php';
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

    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=emailpdf&module=AOS_Invoices&uid=".$recordID;
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
    curl_close($curl);

    print_r($curl_response);
}

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

    /*if (isset($_REQUEST['accounts'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Accounts', 'core'), array('Where' => $_REQUEST['where']));
        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->Accounts[0]). " accounts in this Xero organisation, the first one is: </br>";
            pr($accounts->Accounts[0]->Account);
        } else {
            outputError($XeroOAuth);
        }
    }

    if (isset($_REQUEST['payments'])) {
        if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Payments', 'core'), array('Where' => 'Status=="AUTHORISED"'));
            if ($XeroOAuth->response['code'] == 200) {
                $payments = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($payments->Payments[0]). " payments in this Xero organisation, the first one is: </br>";
                pr($payments->Payments[0]->Payment);
            } else {
                outputError($XeroOAuth);
            }

        } elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "post" && $_REQUEST['payments']== 1 ) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Payments', 'core'), array('Where' => 'Status=="AUTHORISED"'));
            if ($XeroOAuth->response['code'] == 200) {
                $payment = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                if(count($payment->Payments[0]) > 0){
                    echo "Deleting the first available payment with ID: " . $payment->Payments[0]->Payment->PaymentID . "</br>";
                }
            }
            $xml = "<Payment>
                      <Status>DELETED</Status>
                    </Payment>";
            $response = $XeroOAuth->request('POST', $XeroOAuth->url('Payments/'.$payment->Payments[0]->Payment->PaymentID, 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $payments = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo count($payments->Payments[0]). " payment deleted in this Xero organisation: </br>";
                pr($payments->Payments[0]->Payment);
            } else {
                outputError($XeroOAuth);
            }

        }
    }

    if (isset($_REQUEST['accountsfilter'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Accounts', 'core'), array('Where' => 'Type=="BANK"'));
        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->Accounts[0]). " accounts in this Xero organisation, the first one is: </br>";
            pr($accounts->Accounts[0]->Account);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['payrollemployees'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Employees', 'payroll'), array());
        if ($XeroOAuth->response['code'] == 200) {
            $employees = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($employees->Employees[0]). " employees in this Xero organisation, the first one is: </br>";
            pr($employees->Employees[0]->Employee);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['payrollsuperfunds'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('SuperFunds', 'payroll'), array());
        if ($XeroOAuth->response['code'] == 200) {
            $superfunds = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($superfunds->SuperFunds[0]). " superfunds in this Xero organisation, the first one is: </br>";
            pr($superfunds->SuperFunds[0]->SuperFund);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['payruns'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('PayRuns', 'payroll'), array('Where' => $_REQUEST['where']));
        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->PayRuns[0]). " PayRuns in this Xero organisation, the first one is: </br>";
            pr($accounts->PayRuns[0]->PayRun);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['timesheets'])) {
        $xml = "<Timesheet>
                    <EmployeeID>5e493b2e-c3ed-4172-95b2-593438101f76</EmployeeID>
                    <StartDate>2015-04-13T00:00:00</StartDate>
                    <EndDate>2015-04-20T00:00:00</EndDate>
                    <Status>Draft</Status>
                </Timesheet>";
        $response = $XeroOAuth->request('POST', $XeroOAuth->url('Timesheets', 'payroll'), array(), $xml, 'xml');
        if ($XeroOAuth->response['code'] == 200) {
            $timesheets = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($timesheets->Timesheets[0]). " Timesheet created in this Xero organisation, the first one is: </br>";
            pr($timesheets->Timesheets[0]->Timesheet);
        } else {
            outputError($XeroOAuth);
        }
    }
    if (isset($_REQUEST['superfundproducts'])) {
        $response = $XeroOAuth->request('GET', $XeroOAuth->url('SuperFundProducts', 'payroll'), array('ABN' => $_REQUEST['where']));
        if ($XeroOAuth->response['code'] == 200) {
            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            echo "There are " . count($accounts->SuperFundProducts[0]). " SuperFundProducts in this Xero organisation, the first one is: </br>";
            pr($accounts->SuperFundProducts[0]->SuperFundProduct[0]);
        } else {
            outputError($XeroOAuth);
        }
    } */

    if (isset($_REQUEST['invoice'])) {
        /*if (!isset($_REQUEST['method'])) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('order' => 'Total DESC'));
            if ($XeroOAuth->response['code'] == 200) {
                $invoices = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($invoices->Invoices[0]). " invoices in this Xero organisation, the first one is: </br>";
                pr($invoices->Invoices[0]->Invoice);
                if ($_REQUEST['invoice']=="pdf") {
                    $response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoice/'.$invoices->Invoices[0]->Invoice->InvoiceID, 'core'), array(), "", 'pdf');
                    if ($XeroOAuth->response['code'] == 200) {
                        $myFile = $invoices->Invoices[0]->Invoice->InvoiceID.".pdf";
                        $fh = fopen($myFile, 'w') or die("can't open file");
                        fwrite($fh, $XeroOAuth->response['response']);
                        fclose($fh);
                        echo "PDF copy downloaded, check your the directory of this script.</br>";
                    } else {
                        outputError($XeroOAuth);
                    }
                }
            } else {
                outputError($XeroOAuth);
            }
        } else*/ if (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['invoice']== 1 ) {

            $xero_invoice = urldecode($_GET['xero_invoice']) ;
            $rebate_xero_invoice = urldecode($_GET['rebate_xero_invoice']);
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

                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                                <Type>ACCREC</Type>
                                    <Contact>
                                        <Name>Green Energy Trading Pty Ltd</Name>
                                    </Contact>
                                <Date>".$date."</Date>
                                <DueDate>".$dua_date."</DueDate>
                                <LineAmountTypes>Exclusive</LineAmountTypes>
                                <Reference>".$bean->name."</Reference>";
                        if(isset($rebate_xero_invoice) && $rebate_xero_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$rebate_xero_invoice."</InvoiceID>";
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
                        if( isset($rebate_xero_invoice) && $rebate_xero_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);

                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $veec_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            $bean->xero_veec_rebate_invoice_c = $veec_invoice_id;
                            $bean->save();
                            $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                            if ($XeroOAuth->response['code'] == 200) {
                                echo "Attachment successfully created against this invoice.";
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

                        $rebate_xml = "
                        <Invoices>
                            <Invoice>
                            <Type>ACCREC</Type>
                                <Contact>
                                    <Name>Green Energy Trading Pty Ltd</Name>
                                </Contact>
                            <Date>" . $date . "</Date>
                            <DueDate>" . $dua_date . "</DueDate>
                            <LineAmountTypes>Exclusive</LineAmountTypes>
                            <Reference>" . htmlspecialchars($bean->name) . "</Reference>";
                        if(isset($rebate_xero_invoice) && $rebate_xero_invoice!="") $rebate_xml .=
                            "<InvoiceID>".$rebate_xero_invoice."</InvoiceID>";
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
                        if( isset($rebate_xero_invoice) && $rebate_xero_invoice!="") {
                            $method = 'POST';
                        }
                        $response = $XeroOAuth->request($method, $XeroOAuth->url('Invoices', 'core'), array(), $rebate_xml);
                        // File attachment
                        if ($XeroOAuth->response['code'] == 200) {
                            $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $stc_invoice_id = $invoice->Invoices[0]->Invoice->InvoiceID;
                            $bean->xero_stc_rebate_invoice_c = $stc_invoice_id;
                            $bean->save();

                            if (count($invoice->Invoices[0])>0) {
                                downloadPDFFile($templateID, $record);
                                $attachmentFile =  file_get_contents(dirname(__FILE__)."/files/invoice-". $record.".pdf");

                                $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoice->Invoices[0]->Invoice->InvoiceID.'/Attachments/invoice-'. $record.'.pdf', 'core'), array(), $attachmentFile, 'file');
                                if ($XeroOAuth->response['code'] == 200) {
                                    echo "Attachment successfully created against this invoice.";
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
                        echo "Attachment successfully created against this invoice.";
                    } else {
                        outputError($XeroOAuth);
                    }
                }
            } else {
                outputError($XeroOAuth);
            }
        } /*elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "4dp" && $_REQUEST['invoice']== 1 ) {
            $xml = "<Invoices>
                      <Invoice>
                        <Type>ACCREC</Type>
                        <Contact>
                          <Name>Steve Buscemi</Name>
                        </Contact>
                        <Date>2014-05-13T00:00:00</Date>
                        <DueDate>2014-05-20T00:00:00</DueDate>
                        <LineAmountTypes>Exclusive</LineAmountTypes>
                        <LineItems>
                          <LineItem>
                            <Description>Monthly rental for property at 56b Wilkins Avenue</Description>
                            <Quantity>4.3400</Quantity>
                            <UnitAmount>395.6789</UnitAmount>
                            <AccountCode>200</AccountCode>
                          </LineItem>
                        </LineItems>
                      </Invoice>
                    </Invoices>";
            $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoices', 'core'), array('unitdp' => '4'), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($invoice->Invoices[0]). " invoice created in this Xero organisation.";
                if (count($invoice->Invoices[0])>0) {
                    echo "The first one is: </br>";
                    pr($invoice->Invoices[0]->Invoice);
                }
            } else {
                outputError($XeroOAuth);
            }
        } elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "post" ) {
            $xml = "<Invoices>
                      <Invoice>
                        <Type>ACCREC</Type>
                        <Contact>
                          <Name>Martin Hudson</Name>
                        </Contact>
                        <Date>2013-05-13T00:00:00</Date>
                        <DueDate>2013-05-20T00:00:00</DueDate>
                        <LineAmountTypes>Exclusive</LineAmountTypes>
                        <LineItems>
                          <LineItem>
                            <Description>Monthly rental for property at 56a Wilkins Avenue</Description>
                            <Quantity>4.3400</Quantity>
                            <UnitAmount>395.00</UnitAmount>
                            <AccountCode>200</AccountCode>
                          </LineItem>
                       </LineItems>
                     </Invoice>
                   </Invoices>";
            $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
            if ($XeroOAuth->response['code'] == 200) {
                $invoice = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "" . count($invoice->Invoices[0]). " invoice created in this Xero organisation.";
                if (count($invoice->Invoices[0])>0) {
                    echo "The first one is: </br>";
                    pr($invoice->Invoices[0]->Invoice);
                }
            } else {
                outputError($XeroOAuth);
            }
        }elseif (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['invoice']=="attachment" ) {
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Invoices', 'core'), array('Where' => 'Status=="DRAFT"'));
            if ($XeroOAuth->response['code'] == 200) {
                $invoices = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                echo "There are " . count($invoices->Invoices[0]). " draft invoices in this Xero organisation, the first one is: </br>";
                pr($invoices->Invoices[0]->Invoice);
                if ($_REQUEST['invoice']=="attachment") {
                    $attachmentFile = file_get_contents('http://i.imgur.com/mkDFLf2.png');

                    $response = $XeroOAuth->request('PUT', $XeroOAuth->url('Invoice/'.$invoices->Invoices[0]->Invoice->InvoiceID.'/Attachments/image.png', 'core'), array(), $attachmentFile, 'file');
                    if ($XeroOAuth->response['code'] == 200) {
                        echo "Attachment successfully created against this invoice.";
                    } else {
                        outputError($XeroOAuth);
                    }
                }
            } else {
                outputError($XeroOAuth);
            }

        }*/


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

    if (isset($_REQUEST['sendinvoice']) && isset($_REQUEST['record']))
    {
        $recordID = $_REQUEST['record'];
        sendInvoicePdf($recordID);
    }

    if (isset($_REQUEST['bankstatements'])) {
        if (!isset($_REQUEST['method'])) {
            $txtFile = fopen("bankstatements_lasttime.txt", "r");
            $lastTime = fgets($txtFile);
            fclose($txtFile);
            $response = $XeroOAuth->request('GET', $XeroOAuth->url('Reports/BankStatement', 'core'), array('bankAccountID' => '7fddbe9f-62a6-439c-998b-2e4edb3acbd8', 'fromDate' => $lastTime), "", "xml");
            if ($XeroOAuth->response['code'] == 200) {
                $bankstatements = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                $rows = $bankstatements->Reports->Report->Rows->Row[1]->Rows;
                print_r(json_encode($rows));
                //pr($rows);
                // $rows = $rows->Row;
                // foreach ($rows as $row)
                // {
                //     if ($row->Cells->Cell[0]->Value != "")
                //     {
                //         $lastTime = $row->Cells->Cell[0]->Value;
                //     }                    
                // }
                // $txtFile = fopen("bankstatements_lasttime.txt", "w");
                // fwrite($txtFile, $lastTime);
                // fclose($txtFile);
            } else {
                outputError($XeroOAuth);
            }
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
