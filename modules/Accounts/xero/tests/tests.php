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


function downloadPDFFile($templateID="3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5", $recordID = "")
{
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';

    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    $url = 'http://suitecrm.pure-electric.com.au/index.php';
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

    $source = "http://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=PO_purchase_order&uid=".$recordID;
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


if (isset($_REQUEST['contact'])) {

    if (isset($_REQUEST['method']) && $_REQUEST['method'] == "put" && $_REQUEST['contact']== 1 ) {
 
        $record = urldecode($_GET['record']) ;
        $bean_account = new Account();
        $bean_account->retrieve($record);
    
        $contacts = $bean_account->get_linked_beans('contacts','Contact');
        if(count($contacts)> 0){
            for($i=0;$i < count($contacts);$i++){
                if($contacts[$i]->id == $bean->primary_contact_c){
                    $bean_contact = $contacts[$i];
                    break;
                }elseif($i == count($contacts) -1){
                    $bean_contact = $contacts[count($contacts) -1];
                }
            }
        }

        if($bean_contact->id != ''){
            $first_name = $bean_contact->first_name;
            $last_name = $bean_contact->last_name;
        }else{
            $array_name = explode(' ',$bean_account->name,2);
            $first_name = $array_name[0];
            $last_name = $array_name[1];
        }

        $xml_create_contact = "
        <Contacts>
            <Contact>
                <Name>". $bean_account->name ."</Name>
                <FirstName>". $first_name ."</FirstName>
                <LastName>". $last_name ."</LastName>
                <EmailAddress>". $bean_account->email1 ."</EmailAddress>
                <Addresses>
                  <Address>
                    <AddressType>POBOX</AddressType>
                    <AddressLine1>". $bean_account->billing_address_street ."</AddressLine1>
                    <City>". $bean_account->billing_address_city ."</City>
                    <Region>". $bean_account->billing_address_state ."</Region>
                    <PostalCode>". $bean_account->billing_address_postalcode ."</PostalCode>
                    <AttentionTo></AttentionTo>
                  </Address>
                  <Address>
                    <AddressType>STREET</AddressType>
                  </Address>
                </Addresses>
                <Phones>
                  <Phone>
                    <PhoneType>DEFAULT</PhoneType>
                    <PhoneNumber>". $bean_account->phone_office ."</PhoneNumber>
                    <PhoneAreaCode></PhoneAreaCode>
                    <PhoneCountryCode></PhoneCountryCode>
                  </Phone>
                  <Phone>
                    <PhoneType>FAX</PhoneType>
                    <PhoneNumber>". $bean_account->phone_fax ."</PhoneNumber>
                    <PhoneAreaCode></PhoneAreaCode>
                    <PhoneCountryCode></PhoneCountryCode>
                  </Phone>
                  <Phone>
                    <PhoneType>MOBILE</PhoneType>
                    <PhoneNumber>". $bean_account->mobile_phone_c ."</PhoneNumber>
                    <PhoneAreaCode></PhoneAreaCode>
                    <PhoneCountryCode></PhoneCountryCode>
                  </Phone>
                  <Phone>
                    <PhoneType>DDI</PhoneType>
                  </Phone>
                </Phones>
                <UpdatedDateUTC>2009-05-14T01:44:26.747</UpdatedDateUTC>
                <IsSupplier>false</IsSupplier>
                <IsCustomer>true</IsCustomer>
                <DefaultCurrency>NZD</DefaultCurrency>
            </Contact>
        </Contacts>";

        $response = $XeroOAuth->request("POST", $XeroOAuth->url('Contacts', 'core'), array(), $xml_create_contact);
        
        if ($response['code'] == 200) {
            
        }else {
            $response = $XeroOAuth->request("PUT", $XeroOAuth->url('Contacts', 'core'), array(), $xml_create_contact);
        }

        if($response['code'] == 200){
            $contact = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
            $contact_id = $contact->Contacts->Contact->ContactID;
            echo "success";
        }
    }

}
