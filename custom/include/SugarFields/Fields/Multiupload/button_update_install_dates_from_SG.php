<?php
$db = DBManagerFactory::getInstance();

date_default_timezone_set('STC');
$time_condition = date('m/d/Y h:i:s a', time());
$quoteJSON_MT = GetJson_CRMSolargainQuotes('matthew.wright','MW@pure733');
$quoteJSON_PS = GetJson_CRMSolargainQuotes('paul.szuster@solargain.com.au','Baited@42');
$data_json = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);

$aray_order_number = [];
foreach ($data_json as $key => $value) {
    $aray_order_number[] = $value->ID;
}

$string_order_no = implode("','",$aray_order_number) ;
$sql = "
SELECT aos_quotes_cstm.id_c as id ,aos_quotes_cstm.solargain_quote_number_c as SGquoteNumber , aos_quotes_cstm.solargain_tesla_quote_number_c as SGquoteTeslaNumber  
FROM `aos_quotes_cstm` 
LEFT JOIN `aos_quotes` ON aos_quotes.id = aos_quotes_cstm.id_c
WHERE aos_quotes.deleted = 0 
AND aos_quotes_cstm.proposed_install_date_c >= '$time_condition'
AND ( (aos_quotes_cstm.solargain_quote_number_c IN ('$string_order_no'))
    OR (aos_quotes_cstm.solargain_tesla_quote_number_c  IN ('$string_order_no')) 
    )
";

$ret = $db->query($sql);

while($row = $db->fetchByAssoc($ret)){
    //echo $row['id'] .'<br>';
    $quote_id = $row['SGquoteNumber'];
    if($quote_id == '') {
        $quote_id = $row['SGquoteTeslaNumber'];
    }

    $username = "matthew.wright";
    $password =  "MW@pure733";
    $quote_decode = get_data_sam($quote_id,$username,$password);
    if(!isset($quote_decode)){
        $username = 'paul.szuster@solargain.com.au';
        $password =  'Baited@42';
        $quote_decode = get_data_sam($quote_id,$username,$password);
    };
    date_default_timezone_set('Australia/Melbourne');
    $installdate = $quote_decode->ProposedInstallDate->Date .' ' .$quote_decode->ProposedInstallDate->Time;
    $date = str_replace('/', '-', $installdate);
    $timestamp_date = strtotime($date);
    
    date_default_timezone_set('UTC');
    $dateAUS = date('Y-m-d H:i:s',$timestamp_date );
    $Quote = new AOS_Quotes();
    $Quote->retrieve($row['id']);  
    if($Quote->id != '') {
        echo $Quote->id .'<br>';
        $Quote->proposed_install_date_c = $dateAUS;
        $Quote->save();
    }
}
 function get_data_sam($quote_id,$username,$password){
    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
            "Cache-Control: max-age=0"
        )
    );

    $quote = curl_exec($curl);
    curl_close($curl);
    $quote_decode = json_decode($quote);
    return $quote_decode;
 }

 function GetJson_CRMSolargainQuotes($username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $url = 'https://crm.solargain.com.au/apiv2/quotes/search';

    $param = array (
        'Page' => 1,
        'PageSize' => 25,
        'Sort' => 'ID',
        'Descending' => true,
        'Filters' => 
            array (
            0 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Operation' => 'EQ',
                'Value' => 'IN_PROGRESS',
                'Values' => NULL,
            ),
            1 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'WAITING_ACCEPTANCE',
                'Operation' => 'EQ',
            ),
            2 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'PENDING_APPROVAL',
                'Operation' => 'EQ',
            ),
            3 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'REQUIRES_SITE_INSPECTION',
                'Operation' => 'EQ',
            ),
            4 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'DESIGN_REJECTED',
                'Operation' => 'EQ',
            ),
            5 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'UNDER_CONSTRUCTION',
                'Operation' => 'EQ',
            ),
            6 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SITE_INSPECTION_BOOKED',
                'Operation' => 'EQ',
            ),
            7 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SITE_INSPECTION_COMPLETED',
                'Operation' => 'EQ',
            ),
            8 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SITE_VISIT',
                'Operation' => 'EQ',
            ),
            9 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'OPTION_ACCEPTED',
                'Operation' => 'EQ',
            ),
            10 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SOLARVIC_UPLOADED',
                'Operation' => 'EQ',
            ),
            11 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SOLARVIC_STARTED',
                'Operation' => 'EQ',
            ),
            12 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => 'SOLARVIC_APPROVED',
                'Operation' => 'EQ',
            ),
            ),
      );

    $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Content-Length: ".strlen($paramJSONDecode),
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/order/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );
    return $resultJSON;
}
die();