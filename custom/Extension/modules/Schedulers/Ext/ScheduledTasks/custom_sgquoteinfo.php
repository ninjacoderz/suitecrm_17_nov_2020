
<?php 
array_push($job_strings, 'custom_sgquoteinfo');

function wrapperCRMSolargain($status,$username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $url = 'https://crm.solargain.com.au/APIv2/quotes/search';

    $param = array (
        'Page' => 1,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'PageSize' => 50,
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
            'Value' => $status,
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
                "Referer: https://crm.solargain.com.au/quote/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );

    return $resultJSON;
}

function wrapperCRMSolargainOrders($status,$username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $url = 'https://crm.solargain.com.au/apiv2/orders/search';

    $param = array (
        'Page' => 1,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'PageSize' => 50,
        'Filters' => 
        array (
          0 => 
          array (
            'Field' => 
            array (
              'Category' => 'Order',
              'Name' => 'Status',
              'Code' => 'STATUS',
              'Type' => 5,
            ),
            'Value' => $status,
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

function CRMSolargainPV_SERVICES_CASES($username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $url = 'https://crm.solargain.com.au/APIv2/servicecases/1/search';
    if($username == "matthew.wright"){
        $user_id = 475;
    }else{
        $user_id = 730;
    }
    $param = array (
        'Page' => 1,
        'PageSize' => 25,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'Filters' => 
        array (
          0 => 
          array (
            'Field' => 
            array (
              'Code' => 'ASSIGNEDUSER',
              'Name' => 'Assigned User',
              'Category' => 'Service Case',
              'Type' => 1,
            ),
            'Operation' => 'EQ',
            'Value' => $user_id,
            'Values' => NULL,
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

//thienpb code -- Upcoming Solargain Installs
function CRMSolargainOrderUpcomming($username,$password){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $param_order = array(
                'Page' => 1,
                'PageSize' => 25,
                'Sort' => 'LASTUPDATED',
                'Descending' => false,
                'Sort'=> "INSTDATE",
                'Filters' =>    
                array (
                    0 => array(
                        'Field' =>  array(
                            'Category' => 'Order',
                            'Name' => 'Install Date',
                            'Code' => 'INSTDATE',
                            'Type' => 0,
                        ),
                        'Operation' => 'GT',
                        'Value' => date('d/m/Y'),
                        'Values' => NULL,
                    ),
                    1 => array(
                        'Field' => array(
                        'Category' => 'Order',
                        'Name' => 'Install Date',
                        'Code' => 'INSTDATE',
                        'Type' => 0,
                        ),
                        'Value' => '[+14 Days]',
                        'Operation' => 'LT',
                    ),
                ),
            );
        
    $json_param_order = json_encode($param_order,JSON_UNESCAPED_SLASHES);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_param_order);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Content-Type: application/json";
    $headers[] = "Content-Length: ".strlen($json_param_order);
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: ". $_SERVER['HTTP_USER_AGENT'];
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/order/";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result_json_order = json_decode(curl_exec($ch));
    curl_close ( $curl );

    return ($result_json_order->Results);
}

//Quote is tesla
function CRMSolargainQuoteTesla($username,$password){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $param_order = array(
                'Page' => 1,
                'PageSize' => 25,
                'Sort' => 'LASTUPDATED',
                'Descending' => false,
                'Sort'=> "INSTDATE",
                'Filters' =>    
                array (
                    0 => array(
                        'Field' =>  array(
                            'Category' => 'Components',
                            'Name' => 'Manufacturer',
                            'Code' => 'MANUFACTURER',
                            'Type' => 17,
                        ),
                        'Operation' => 'SELECTED',
                        'Value' => 46,
                    ),
                ),
            );
        
    $json_param_order = json_encode($param_order,JSON_UNESCAPED_SLASHES);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_param_order);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Content-Type: application/json";
    $headers[] = "Content-Length: ".strlen($json_param_order);
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: ". $_SERVER['HTTP_USER_AGENT'];
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/order/";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result_json_order = json_decode(curl_exec($ch));
    curl_close ( $curl );

    return ($result_json_order->Results);
}

//thienpb code -- new logic use for Matthew Wright SG account and Paul Szuster SG account
function generate_email_content(){
    //create array id quote tesla
    $quoteJSON_MT = CRMSolargainQuoteTesla('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainQuoteTesla('paul.szuster@solargain.com.au','S0larga1n$');
    $quoteJSON = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
    $aray_quote_tesla_number = [];
    foreach ($data_json as $key => $value) {
        $aray_quote_tesla_number[] = $value->ID;
    }

    $status_arr = array("PENDING_APPROVAL","DESIGN_REJECTED","SITE_INSPECTION_COMPLETED","SITE_INSPECTION_BOOKED","REQUIRES_SITE_INSPECTION","OPTION_ACCEPTED","SOLARVIC_APPROVED","SOLARVIC_STARTED","SOLARVIC_UPLOADED");
    $html_content = "";
    $quoteJSON = "";
    for($i = 0; $i < count($status_arr); $i++){
        $quoteJSON_MT = wrapperCRMSolargain($status_arr[$i],'matthew.wright','MW@pure733');
        $quoteJSON_PS = wrapperCRMSolargain($status_arr[$i],'paul.szuster@solargain.com.au','S0larga1n$');
        $quoteJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);
    
        $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";
    
        if(count($quoteJSON)>0){
            $html_content .= '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;" style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                    <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Product Type</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($quoteJSON as $res){
                $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->QuotedByUser->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->Status_Description;
                }
                $date_LastUpDated = $res->LastUpdated;
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $quoted_by_user = $res->QuotedByUser->Name;
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days '  ;
                }
                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                }
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }

                $prduct_type = '';
                if(in_array($res->ID,$aray_quote_tesla_number)){
                    $prduct_type = 'Tesla';
                }else{
                    $prduct_type = 'Solar';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">QUOTE#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$prduct_type."</td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No Quote</h4>";
        }
    }

    // Get Orders Infor 
    $status_arr = array("NETWORK_APPROVAL_SUBMITTED","NETWORK_APPROVAL_APPROVED","SALES_ORDER_SENT","RECEIVED","APPROVALS_RECEIVED","INSTALLATION_PENDING","INSTALLATION_DATE_CONFIRMED","SYSTEM_INSTALLED","QUOTE_ACCEPTED");
    for($i = 0; $i < count($status_arr); $i++){
        $quoteJSON_MT = wrapperCRMSolargainOrders($status_arr[$i],'matthew.wright','MW@pure733');
        $quoteJSON_PS = wrapperCRMSolargainOrders($status_arr[$i],'paul.szuster@solargain.com.au','S0larga1n$');
        $orderJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);
    
        $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";
    
        if(count($orderJSON)>0){
            $html_content .= 
            '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Install Date :</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Install Time :</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($orderJSON as $res){
                $link = "https://crm.solargain.com.au/order/edit/".$res->ID;
                //https://crm.solargain.com.au/order/edit/29194
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->AssignedUser->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->PVStatus;
                }
               
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $date_LastUpDated = $res->LastUpdated;
                $quoted_by_user = $res->AssignedUser->Name;
                if( $quoted_by_user == ''){
                    $quoted_by_user = $res->ExternalSalesPerson->Name;
                }
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days ';
                }
                if(isset($res->InstallDate) && $res->InstallDate != ""){
                    $time_install_date = new DateTime($res->InstallDate);
                } else {
                    $time_install_date = false;
                }

                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                };
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">ORDER#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".($time_install_date?$time_install_date->format('l ,d-M-Y'):"Didn't Set") ."</td>
                    <td style='border: 1px solid black;' >".($time_install_date?$time_install_date->format('h:i A'):"Didn't Set" ) ."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No ORDER</h4>";
        }
    }

    //get PV_SERVICES_CASES
    $quoteJSON_MT = CRMSolargainPV_SERVICES_CASES('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainPV_SERVICES_CASES('paul.szuster@solargain.com.au','S0larga1n$');
    $quoteJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);

    $html_content .= "<div><h3>PV SERVICES CASES</h3></div>";

    if(count($quoteJSON)>0){
        $html_content .= 
        '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
            <tr>
            <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
            </tr>';
    foreach($quoteJSON as $res){
        $link = "https://crm.solargain.com.au/servicecase/edit/".$res->ID;
        if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->AssignedUser->Name) == 'Paul Szuster'){
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
            </tr>";
        }
        $name = $res->Customer->Name;
        $status = $res->Status->Description;
        $date_LastUpDated = $res->LastUpdated;
        $state_customer = $res->Customer->Address->State;
        $quoted_by_user = $res->AssignedUser->Name;
        if($date_LastUpDated == ''){
            $date_LastUpDated = 'NO';
        }else{
            $d1=new DateTime($date_LastUpDated); 
            $d2=new DateTime(); 
            $date_diff= $d2->diff($d1)->format('%a');
            $date_LastUpDated = $date_diff .' days ' ;
        }
        //link lead 
        $quote_number_SAM = $res->ID;
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
        $ret = $db->query($sql);
        $link_quote_suite = '';
        $seek_install_date = '';
        while ($row = $db->fetchByAssoc($ret)) {
            $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
            $seek_install_date = $row['seek_install_date_c'];
        };
        if($seek_install_date != ''){
            $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
        }else{
            $seek_install_date = '--';
        }
        $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">PV Service Cases #".$res->ID."</a></td>
                <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                <td style='border: 1px solid black;' >".$seek_install_date."</td>
                <td style='border: 1px solid black;' >".$state_customer."</td>
                <td style='border: 1px solid black;' >".$name."</td>
                <td style='border: 1px solid black;' >".$status."</td>
                <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
            </tr>";
        
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No Quote</h4>";
    }
    
    //thienpb code -- Upcoming Solargain Installs 
    $quoteJSON_MT = CRMSolargainOrderUpcomming('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainOrderUpcomming('paul.szuster@solargain.com.au','S0larga1n$');
    $order_result_search = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
    $html_content .= "<div><h3>UPCOMING SOLARGAIN INSTALLS</h3></div>";

    if(count($order_result_search) > 0){
        $html_content .= 
        '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
            <tr>
            <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Install Date</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
            </tr>';
        foreach($order_result_search as $res){
            $link = "https://crm.solargain.com.au/order/edit/".$res->ID;
            if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->Quote->QuotedByUser->Name) == 'Paul Szuster'){
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                </tr>";
            }
            $name = $res->Customer->Name;
            if($name == ''){
                $name = $res->Customer_Name;
            }
            $status = $res->PVStatus;

            $quoted_by_user = $res->Quote->QuotedByUser->Name;
            if($quoted_by_user == ''){
                $quoted_by_user = $res->ExternalSalesPerson->Name;
            }
            $install_date = date('d/m/Y',strtotime($res->InstallDate));
       
            $state_customer = $res->Customer->Address->State;
            if($state_customer == ''){
                $state_customer = $res->Customer_Address_State;
            }
             //link lead 
            $order_number_SAM = $res->ID;
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT id_c , installation_date_c FROM `aos_invoices_cstm` WHERE solargain_invoices_number_c='$order_number_SAM'";
            $ret = $db->query($sql);
            $link_invoice_suite = '';
            $seek_install_date = '';
            while ($row = $db->fetchByAssoc($ret)) {
                $link_invoice_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$row['id_c'];
                $seek_install_date = $row['installation_date_c'];
            };
            if($seek_install_date != ''){
                $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
            }else{
                $seek_install_date = '--';
            }
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">ORDER#".$res->ID."</a></td>
                <td style='border: 1px solid black;' ><a target='_blank' href=". $link_invoice_suite.">".(($link_invoice_suite != '')?'Invoice SuiteCRM' : '') ."</a></td>
                <td style='border: 1px solid black;' >".$state_customer."</td>
                <td style='border: 1px solid black;' >".$name."</td>
                <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                <td style='border: 1px solid black;' >".$install_date."</td>
                <td style='border: 1px solid black;' >".$status."</td>
            </tr>";
        }
        $html_content .= '</table>';
    }else{
        $html_content .= "<h4>No Upcoming Solargain Installs</h4>";
    }

    // Get Quote Tesla
        $quoteJSON_MT = CRMSolargainQuoteTesla('matthew.wright','MW@pure733');
        $quoteJSON_PS = CRMSolargainQuoteTesla('paul.szuster@solargain.com.au','S0larga1n$');
        $orderJSON = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
        $html_content .= "<div><h3>Tesla Quotes</h3></div>";
    
        if(count($orderJSON)>0){
            $html_content .= 
            '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($orderJSON as $res){
          
                $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
                
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->ExternalSalesPerson->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->PVStatus;
                }
               
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $date_LastUpDated = $res->LastUpdated;
                $quoted_by_user = $res->ExternalSalesPerson->Name;
                if( $quoted_by_user == ''){
                    $quoted_by_user = $res->CreatedBy->Name;
                }
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days ';
                }
                if(isset($res->InstallDate) && $res->InstallDate != ""){
                    $time_install_date = new DateTime($res->InstallDate);
                } else {
                    $time_install_date = false;
                }

                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c = '$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                };
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">Quote#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No ORDER</h4>";
        }
    

    return $html_content;
}

function custom_sgquoteinfo(){

    $html_content = generate_email_content();

    require_once('include/SugarPHPMailer.php');
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "PureElectric";
    $mail->IsHTML(true);

    $mail->Subject = 'SOLARGAIN STATUS DAILY EMAIL';

    $mail->Body = $html_content;

    $mail->prepForOutbound();
    //$mail->AddAddress('binhdigipro@gmail.com');
    $mail->AddAddress('info@pure-electric.com.au');
    //$mail->AddCC('info@pure-electric.com.au');
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');

    $sent = $mail->Send();
}