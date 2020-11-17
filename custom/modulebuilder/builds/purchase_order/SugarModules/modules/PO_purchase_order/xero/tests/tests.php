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

    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=PO_purchase_order&uid=".$recordID;
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


if (isset($_REQUEST['invoice'])) {
    if (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['invoice']== 1 ) {

        $templateID = "33a427fd-34c7-3c33-b89d-5a3e1302c243";
       
        $record = urldecode($_GET['record']) ;
        $bean = new PO_purchase_order();
        
        $bean->retrieve($record);
        if($bean->number == "") return;
        $bean_account = new Account();
        $bean_account->retrieve($bean->billing_account_id);
        $date = date('Y-m-d').'T00:00:00';///$date = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
        //$date_for_rebate = strtotime("+15 day", strtotime(str_replace("/","-",$bean->due_date)));
        $dua_date = strtotime("+15 day", time());
        $date_for_rebate = date("Y-m-d", $dua_date)."T00:00:00";
        // Tracking options
        $xml = "    
                    <Options>
                        <Option>
                        <Name>".htmlspecialchars($bean->billing_account)."</Name>
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
        
        $xero_invoice  = $bean->purchase_invoice_xero;
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
                    <DueDate>".htmlspecialchars($date_for_rebate)."</DueDate>
                    <LineAmountTypes>Exclusive</LineAmountTypes>
                    <Reference>".htmlspecialchars($bean->name)."</Reference>
                    <InvoiceNumber>PO - ".htmlspecialchars($bean->number)."</InvoiceNumber>
                    <LineItems>";

        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND parent_id = '".$bean->id."' AND deleted = 0";
        $result = $bean->db->query($sql);

        while ($row = $bean->db->fetchByAssoc($result)) {
            if(isset($row) && $row != null ){

                $unit_price = "<UnitAmount>".$row["product_unit_price"]."</UnitAmount>";
                
                if(isset($product_mapping[$row["product_id"]])){ // <ItemCode>".$product_mapping[$row["product_id"]]."</ItemCode>
                    $xml .= "
                        <LineItem>
                            <ItemCode>DUS735</ItemCode>
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
            
            // Check if exisit xero invoice 
            $bean->purchase_invoice_xero = $invoice_id;
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

            // Check if exisit xero invoice 
            $bean->purchase_invoice_xero = "";
            $bean->save();
            outputError($XeroOAuth);
        }
    }

}
