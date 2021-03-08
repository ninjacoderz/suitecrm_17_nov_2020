<?php
    require_once(dirname(__FILE__).'/simple_html_dom.php');
    $tmpfname = dirname(__FILE__).'/cookieseconnect.txt';

    $nmi_number =  urlencode($_GET['nmi_number']);
    $dnsp_number = urlencode($_GET['dnsp']);
    //$nmi_number = '62037511033';
    $quote_id = urldecode($_GET['record']);
    $lead_id = urldecode($_GET['lead_id']);
    $meter_phase_c = urlencode($_GET['meter_phase_c']);
    //$meter_phase_c = 1;
    $type = urlencode($_GET['type']);
    
    $bean = [];
    if(empty($lead_id)){
        $quote = new AOS_Quotes();
        $quote = $quote->retrieve($quote_id);
        if($quote->id){
            $bean = $quote;
        }else{
            echo '';
            die;
        }
    }else{
        $lead = new Lead();
        $lead = $lead->retrieve($lead_id);
        if($lead->id){
            $bean = $lead;
        }else{
            echo '';
            die;
        }
    }

    $site = "https://econnect.portal.powercor.com.au/customer";

    /** LOGIN  https://econnect.portal.powercor.com.au */
        login($site);
    /** END LOGIN */
    
   
    if(empty($dnsp_number)){
         /** SUBMIT NEW REQUEST */
            $html = submitNewRequest($site);
        /** END SUBMIT NEW REQUEST */

        /** GET METER NUMBER */
            getMeterNumber($site,$html,$nmi_number,$bean,$meter_phase_c);
         /** END GET METER NUMBER*/
    }else{
        downloadPDF($site, $dnsp_number, $meter_value, $bean);
    }
    
    
   

function CURL($url, $referer, $method = 'GET',$post_type = 'ARRAY',$params = [],$header = false){
    $tmpfname = dirname(__FILE__).'/cookieseconnect.txt';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    if($method == "POST"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, (($post_type == 'ARRAY') ? http_build_query($params) : $params));
    }else{
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }
    if($header){
        curl_setopt($ch, CURLOPT_HEADER,true);
    }
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = (($post_type == 'ARRAY') ? 'Content-Type: application/x-www-form-urlencoded' : 'Content-Type: application/json;charset=UTF-8');
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Referer: '.$referer;
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    if($method == "POST"){
        $headers[] = "Content-Length: ".(($post_type == 'ARRAY') ? strlen(http_build_query($params)) : strlen($params));
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close ($ch);
    return $result;
}

function login($site){
    $tmpfname = dirname(__FILE__).'/cookieseconnect.txt';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site.'/loginpage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Referer: '.$site.'/loginpage';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);

    $pattern = '/<script>[\S\s]function redirectOnLoad\(\) {[\S\s]if \(this\.SfdcApp && this\.SfdcApp\.projectOneNavigator\) { SfdcApp\.projectOneNavigator\.handleRedirect\(\'(.*?)\'\); }/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue != false){
        $url_redirect = $matches[1];
        curl_setopt($ch, CURLOPT_URL,  $url_redirect);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Referer: '.$site.'/HomePage';
        $headers[] = 'Accept-Language: en,vi;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    $pattern = '/<form id=".*" name="(.*?)" method="post" action="\/customer\/loginpage"/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id_login = $matches[1];

    $pattern = '/<input type="text" name="(.*?)" class="userNameInput/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id1 = $matches[1];

    $pattern = '/<input type="password" name="(.*?)" value="" class="passwordInput/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id2 = $matches[1];

    $pattern = '/jsfcljs\(document.getElementById\(\''.$jform_id_login.'\'\),\'(.*?),(.*?)\',\'\'\)/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id3 = $matches[1];

    $html = str_get_html($result);
    $ViewStateVersion = urlencode($html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value);

    $query_post = array(
        $jform_id_login => $jform_id_login,
        $jform_id1      => 'operations@pure-electric.com.au',
        $jform_id2      => 'pPureandTrue2016*',
        $jform_id3      => $jform_id3,
        'com.salesforce.visualforce.ViewState'       => $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value,
        'com.salesforce.visualforce.ViewStateVersion' => $ViewStateVersion,
        'com.salesforce.visualforce.ViewStateMAC'     => $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value,
    );  

    curl_setopt($ch, CURLOPT_URL,$site.'/loginpage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query_post));
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = "Content-Length: ".strlen(http_build_query($query_post));
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Referer: '.$site.'/loginpage';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);

    $pattern = '/<script>[\S\s]function redirectOnLoad\(\) {[\S\s]if \(this\.SfdcApp && this\.SfdcApp\.projectOneNavigator\) { SfdcApp\.projectOneNavigator\.handleRedirect\(\'(.*?)\'\); }/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $url_redirect = $matches[1];

    curl_setopt($ch, CURLOPT_URL,  $url_redirect);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Referer: '.$site.'/HomePage';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $site.'/HomePage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Referer: '. $url_redirect;
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function submitNewRequest($site){
    $result = CURL($site.'/SolarPreApprovalListViewPage',$site.'/Homepage', 'GET');
    $html = str_get_html($result);

    $pattern = '/<form id=".*" name="(.*?)" method="post" action="\/customer\/SolarPreApprovalListViewPage"/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id_search = $matches[1];

    $pattern = '/<label class="control-label" for="requestID">Request ID<\/label><input .* name="(.*?)" value="" .* \/>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $spaReqId = $matches[1];

    $pattern = '/<label class="control-label" for="addressStreetName">Work Site Address<\/label><input .* name="(.*?)" .* \/>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $searchadd = $matches[1];

    $pattern = '/<label class="control-label" for="meterSerialNumber">Meter Number<\/label>.*?<input .*? name="(.*?)" .*?\/>/s';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $meternum = $matches[1];

    $pattern = '/<label class="control-label" for="nationalMeteringID">NMI<\/label>*?<input .*? name="(.*?)" .*?\/>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $nmiNum = $matches[1];

    $pattern = '/<label class="control-label" for="dateRange">Expiry Date<\/label>*?<input .*? name="(.*?)" .*?\/>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jformId4 = $matches[1];

    $pattern = '/jsfcljs\(document.getElementById\(\''.$jform_id_search.'\'\),\'(.*?),(.*?)\',\'\'\)/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $subNewBtn = $matches[1];

    $pattern  = '/<input .*? name="com.salesforce.visualforce.ViewStateCSRF" value="(.*?)" \/>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2){
        $ViewStateCSRF = $html->find("input[id='com.salesforce.visualforce.ViewStateCSRF']",0)->value;
    }else{
        $ViewStateCSRF = $matches[1];    
    }
    
    $query_post = array(
        $jform_id_search => $jform_id_search,
        'search'         => '', 
        $spaReqId        => '',
        $searchadd       => '',
        $meternum        => '',
        $nmiNum          => '',
        $jformId4        => $jformId4,
        $jform_id_search.':solarPreList_length' => 10,
        $subNewBtn       => $subNewBtn,
        'com.salesforce.visualforce.ViewState'        => $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value,
        'com.salesforce.visualforce.ViewStateVersion' => urlencode($html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value),
        'com.salesforce.visualforce.ViewStateMAC'     => $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value,
        'com.salesforce.visualforce.ViewStateCSRF'    => $ViewStateCSRF
    );

    $result1 = CURL($site.'/SolarPreApprovalListViewPage',$site.'/SolarPreApprovalListViewPage', 'POST',"ARRAY", $query_post);
    $result2 = CURL($site.'/solarpreapprovalrequestpage',$site.'/SolarPreApprovalListViewPage', 'GET');
    
    return $result2;
}

function getMeterNumber($site,$html,$nmi_number,$bean,$meter_phase_c){
    $body = str_get_html($html);

    $pattern = '/<form id=".*" name="(.*?)" method="post" action="\/customer\/solarpreapprovalrequestpage"/';
    $returnValue = preg_match($pattern, $html, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $solarPreApprovalForm = $matches[1];

    $pattern = '/searchNMI=function\(\)\{A4J.AJAX.Submit\(\''.$solarPreApprovalForm.'\',null,\{\'similarityGroupingId\':\'(.*?)\',\'oncomplete\'/';
    $returnValue = preg_match($pattern, $html, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id1 =  $matches[1];

    $pattern  = '/<input .*? name="com.salesforce.visualforce.ViewStateCSRF" value="(.*?)" \/>/';
    $returnValue = preg_match($pattern, $html, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2){
        $ViewStateCSRF = $body->find("input[id='com.salesforce.visualforce.ViewStateCSRF']",0)->value;
    }else{
        $ViewStateCSRF = $matches[1];    
    }
    //get form id 2
    $pattern = '/setParameters=function\(.*?\){A4J\.AJAX\.Submit\(\'j_id0:solarPreApprovalForm\',null,{\'similarityGroupingId\':\'(.*?)\',\'parameters\'/';
    $returnValue = preg_match($pattern, $html, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $jform_id2 = $matches[1];

    //get form id 3
    $pattern = '/submitSPA=function\(\)\{A4J.AJAX.Submit\(\'j_id0:solarPreApprovalForm\',null,\{\'similarityGroupingId\':\'(.*?)\',\'oncomplete\'/';
    $returnValue = preg_match($pattern, $html, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2)die;
    $jform_id3 = $matches[1];

    $query_post = array(
        'AJAXREQUEST' =>  '_viewRoot',
        $solarPreApprovalForm => $solarPreApprovalForm,
        'SiteCapacity' => '', 
        $solarPreApprovalForm.':requestTypes'            => 'Generation',
        $solarPreApprovalForm.':generationStatus'        => true,
        $solarPreApprovalForm.':batteryStatus'           => false,
        $solarPreApprovalForm.':evStatus'                => false,
        $solarPreApprovalForm.':nationMeterID'           => $nmi_number,
        $solarPreApprovalForm.':meterNumber'             => '',
        $solarPreApprovalForm.':addressOne'              => '',
        $solarPreApprovalForm.':addressTwo'              => '',
        $solarPreApprovalForm.':suburb'                  => '',
        $solarPreApprovalForm.':postcode'                => '',
        $solarPreApprovalForm.':generationTypesSF'       => '',
        $solarPreApprovalForm.':proposedRating'          => '',
        $solarPreApprovalForm.':planningToExportValue'   => '',
        $solarPreApprovalForm.':existingExportCapacity'  => '',
        $solarPreApprovalForm.':proposedExportAmount'    => 0,
        $solarPreApprovalForm.':SPARequestType'          => '',
        $solarPreApprovalForm.':maxExportAmount'         => '',
        $solarPreApprovalForm.':exportUpgradeReason'     => '',
        $solarPreApprovalForm.':isVPPValue'              => false,
        $solarPreApprovalForm.':virtualPowerPlantRetailerRequired' => '',
        $solarPreApprovalForm.':genDeedReq'              => false,
        $solarPreApprovalForm.':batterySubType'          => '',
        $solarPreApprovalForm.':batteryBrand'            => '',
        $solarPreApprovalForm.':batteryModel'            => '',
        $solarPreApprovalForm.':batteryPeekPower'        => '',
        $solarPreApprovalForm.':batteryRatedOutput'      => '',
        $solarPreApprovalForm.':batteryTotalStorage'     => '',
        $solarPreApprovalForm.':j_id168:carMake'         => '',
        $solarPreApprovalForm.':j_id168:carModel'        => '',
        $solarPreApprovalForm.':j_id168:carStorageCapacity' => '',
        $solarPreApprovalForm.':j_id168:carRatedOutput'  => '',
        $solarPreApprovalForm.':j_id168:evChargingPhase' => '',
        $solarPreApprovalForm.':j_id168:chargerOutput'   => '',
        $solarPreApprovalForm.':j_id168:evIntendedChargingTime'  => '',
        $solarPreApprovalForm.':j_id168:evPrimaryChargeLocation' => '',
        $solarPreApprovalForm.':customerName'            => '',
        $solarPreApprovalForm.':customerEmail'           => '',
        $solarPreApprovalForm.':accreditationName'       => 'A3420842',
        $solarPreApprovalForm.':companyName'             => 'Pure Electric Solutions',
        $solarPreApprovalForm.':solarEmail'              => 'info@pure-electric.com.au',
        $solarPreApprovalForm.':solarPhone'              => '1300867873',
        $solarPreApprovalForm.':solarAddress'            => '38 Ewing Street, BRUNSWICK VIC 3056',
        $solarPreApprovalForm.':applicantName'           => 'Paul Szuster',
        $solarPreApprovalForm.':applicantEmail'          => 'operations@pure-electric.com.au',
        $solarPreApprovalForm.':application_date'        => '',
        $solarPreApprovalForm.':companyId'               => '',
        'com.salesforce.visualforce.ViewState'          => $body->find("input[id='com.salesforce.visualforce.ViewState']",0)->value,
        'com.salesforce.visualforce.ViewStateVersion'   => urlencode($body->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value),
        'com.salesforce.visualforce.ViewStateMAC'       => $body->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value,
        'com.salesforce.visualforce.ViewStateCSRF'      => $ViewStateCSRF,
        $jform_id1 => $jform_id1
    );

    //solarpreapprovalrequestpage : Step1 (curl get data string)
    $result = CURL($site.'/solarpreapprovalrequestpage',$site.'/solarpreapprovalrequestpage', 'POST',"ARRAY", $query_post);
    // echo htmlspecialchars($result);die;
    //solarpreapprovalrequestpage : Step 2 (curl search by NMI number)
    $confirm = '';
    $body2 = str_get_html($result);
    $pattern = '/<button class="btn btn-blue btn-block confirm_btn" onclick="confirm\(this\);" type="button">(.*?)<\/button>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if($returnValue == false || empty($matches[1])){
        $confirm = $body2->find('button[class="confirm_btn"]',0)->innertext;
    }else{
        $confirm = $matches[1];
    }

    if($confirm == 'Confirm'){
       
        $address_value  = $body2->find('div[id="sin_search_res"]',0)->children(0)->innertext;
        $meter_value    = $body2->find('label[class="meter_value"]',0)->innertext;
        $company_value  = $body2->find('label[class="company_value"]',0)->innertext;
        if($type == "GET_METER"){
            $bean->meter_number_c = $meter_value;
            $bean->save();
            echo $meter_value; die;
        }
        //set post data for submit confirm
        $pattern = '/Visualforce\.remoting\.Manager\.add\(new \$VFRM\.RemotingProviderImpl\({"vf":{"vid":"06628000000RyX9",.*{"name":"(.*?)".*?"csrf":"(.*?)"/';
        $returnValue = preg_match($pattern, $html, $matches);
        if($returnValue == false || empty($matches[1])) die;

        $post_confirm = array (
            'action' => 'SolarPreApprovalRequestController',
            'method' => $matches[1],
            'data' => array (0 => $nmi_number,"get_transformer"),
            'type' => 'rpc',
            'tid' => 4,
            'ctx' => 
            array (
                'csrf' => $matches[2],
                'vid' => '06628000000RyX9',
                'ns' => '',
                'ver' => 34,
            ),
        );
        $post_confirm = json_encode($post_confirm);
        $result = CURL($site.'/apexremote',$site.'/solarpreapprovalrequestpage', 'POST',"JSON", $post_confirm);

        if($meter_value != ''){
            //solarpreapprovalrequestpage :Step 3 (set data string after confirm)
            $query_post[$solarPreApprovalForm.":meterNumber"] = $meter_value;
            $query_post[$solarPreApprovalForm.":addressOne"]  = $address_value;
            $query_post[$solarPreApprovalForm.":companyId"]   =  $company_value;
            unset($query_post[$jform_id1]);

            //clone $data_string
            $apexremote = json_decode($result);
            $data_after_confirm = $query_post;
            // $data_after_confirm["tranInsCapacity"] = $result[0]->result->tranInstallCapacity;
            // $data_after_confirm["tranParentLimit"] = '';
            // $data_after_confirm["tranName"] = $result[0]->result->tranName;
            // $data_after_confirm["tranParentName"] = '';
            // $data_after_confirm[$jform_id2] = $jform_id2;
            // $data_after_confirm["tranLimit"] = $result[0]->result->tranSolarLimit;
            // $data_after_confirm["tranParentInsCapacity"] = '';

            $data_after_confirm["supply_point_id"]               = $apexremote[0]->result->supply_point_id;
            $data_after_confirm["transformer_phase"]             = $apexremote[0]->result->transformer_phase;
            $data_after_confirm["feeder"]                        = $apexremote[0]->result->feeder;
            $data_after_confirm["transformer_name_plate_rating"] = $apexremote[0]->result->transformer_name_plate_rating;
            $data_after_confirm["substation_urban_code"]         = $apexremote[0]->result->substation_urban_code;
            $data_after_confirm["sitePhase"]                     = 'Single Phase';
            $data_after_confirm["substation_fire_area"]          = $apexremote[0]->result->substation_fire_area;
            $data_after_confirm["transformer_rating"]            = $apexremote[0]->result->transformer_rating;
            $data_after_confirm[$jform_id2]                      = $jform_id2;
            $data_after_confirm["installed_capacity"]            = $apexremote[0]->result->installed_capacity;
            $data_after_confirm["parent_installed_capacity"]     = '';
            $data_after_confirm["parent_transformer_name_plate_rating"] = '';
            $data_after_confirm["existing_export_capacity"]      = '';
            $data_after_confirm["no_customers_on_transformer"]   = $apexremote[0]->result->no_customers_on_transformer;
            $data_after_confirm["parent_transformer_name"]       = '';
            $data_after_confirm["substation_id"]        = $apexremote[0]->result->substation_id;
            $data_after_confirm["transformer_id"]       = $apexremote[0]->result->transformer_id;
            $data_after_confirm["transformer_name"]     = $apexremote[0]->result->transformer_name;

            //solarpreapprovalrequestpage : Step 4 (curl after submit confirm)
            $result = CURL($site.'/solarpreapprovalrequestpage',$site.'/solarpreapprovalrequestpage', 'POST',"ARRAY", $data_after_confirm);
            // echo htmlspecialchars($result);die;
            $html = str_get_html($result);

            $ViewState = $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value;
            $ViewStateVersion = $html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value;
            $ViewStateMAC = $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value;

            $pattern  = '/<input .*? name="com.salesforce.visualforce.ViewStateCSRF" value="(.*?)" \/>/';
            $returnValue = preg_match($pattern, $result, $matches);
            if ( $returnValue == false || $matches == null || count($matches) < 2){
                $ViewStateCSRF = $html->find("input[id='com.salesforce.visualforce.ViewStateCSRF']",0)->value;
            }else{
                $ViewStateCSRF = $matches[1];    
            }
            $customer_name = '';
            if($bean->module_name == 'AOS_Quotes'){
                $customer_name = $bean->account_firstname_c . ' ' .$bean->account_lastname_c;
            }else{
                $customer_name = $bean->first_name . ' ' .$bean->last_name;
            }
            $query_post["com.salesforce.visualforce.ViewState"] = $ViewState;
            $query_post["com.salesforce.visualforce.ViewStateVersion"] = $ViewStateVersion;
            $query_post["com.salesforce.visualforce.ViewStateMAC"] =  $ViewStateMAC;
            if($meter_phase_c == 1){
                $query_post[$solarPreApprovalForm.":phaeses"] = 'Single Phase';
                $query_post[$solarPreApprovalForm.":proposedRating"] = '5';
                $query_post[$solarPreApprovalForm.":phaseA"] = '5' ;
            }elseif($meter_phase_c == 3){
                $query_post[$solarPreApprovalForm.":phaeses"] = 'Three Phase';
                $query_post[$solarPreApprovalForm.":proposedRating"] = '30';
                $query_post[$solarPreApprovalForm.":phaseA"] = '10' ;
                $query_post[$solarPreApprovalForm.":phaseB"] = '10' ;
                $query_post[$solarPreApprovalForm.":phaseC"] = '10' ;
            }
            $query_post[$solarPreApprovalForm.":customerName"] = $customer_name;
            $query_post[$solarPreApprovalForm.":applicantName"] = 'Matthew Wright';
            $query_post[$solarPreApprovalForm.":applicantEmail"] = 'info@pure-electric.com.au';
            $query_post[$solarPreApprovalForm.":inchk"] =  'on';
            $query_post[$solarPreApprovalForm.':planningToExportValue']  = true;
            $query_post[$solarPreApprovalForm.':SPARequestType'] = 'New';
            $query_post[ $jform_id3] =  $jform_id3;

            $result = CURL($site.'/solarpreapprovalrequestpage',$site.'/solarpreapprovalrequestpage', 'POST',"ARRAY", $query_post,true);
            
            preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $result, $matches);
            $redirectURL = $matches[1];

            $result = CURL('https://econnect.portal.powercor.com.au'.$redirectURL,$site.'/solarpreapprovalrequestpage', 'GET');

            $html = str_get_html($result);
            $DNSP = $html->find('a[class="link-confirmation"]',0)->innertext;
            $ViewState = $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value;
            $ViewStateVersion = $html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value;
            $ViewStateMAC = $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value;

            $pattern  = '/<input .*? name="com.salesforce.visualforce.ViewStateCSRF" value="(.*?)" \/>/';
            $returnValue = preg_match($pattern, $result, $matches);
            if ( $returnValue == false || $matches == null || count($matches) < 2){
                $ViewStateCSRF = $html->find("input[id='com.salesforce.visualforce.ViewStateCSRF']",0)->value;
            }else{
                $ViewStateCSRF = $matches[1];    
            }

            if($type = "GET_DNSP"){
                if(!empty($DNSP)){
                    $bean->dnsp_approval_number_c = $DNSP;
                    $bean->save();
                    echo $DNSP;
                    $query_post_ok = array(
                        'j_id0:j_id57' => 'j_id0:j_id57',
                        'j_id0:j_id57:j_id61' => 'OK',
                        'com.salesforce.visualforce.ViewState'        => $ViewState,
                        'com.salesforce.visualforce.ViewStateVersion' => $ViewStateVersion,
                        'com.salesforce.visualforce.ViewStateMAC'     => $ViewStateMAC,
                        'com.salesforce.visualforce.ViewStateCSRF'    => $ViewStateCSRF,
                    );
                    $result = CURL($site.'/solarpreapprovalconfirmationpage','https://econnect.portal.powercor.com.au'.$redirectURL,'POST','ARRAY', $query_post_ok);
                    downloadPDF($site, $DNSP, $meter_value, $bean);
                }
            }
        }
    }
}

function downloadPDF($site, $dnsp_number, $meter_value, $bean){
    $tmpfname = dirname(__FILE__).'/cookieseconnect.txt';
    $result = CURL($site.'/SolarPreApprovalListViewPage',$site.'/SolarPreApprovalListViewPage','GET');
    $pattern = '/<tr><td id=".*?" colspan="1"><a href="(.*?)".*?>'.$dnsp_number.'<\/a>/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $url_DNSP = $matches[1];

    $pattern = '/recordId=(.*?)&/';
    $returnValue = preg_match($pattern, $url_DNSP, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2) die;
    $recordId = $matches[1];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://econnect.portal.powercor.com.au'.$url_DNSP);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    $headers[] = 'Referer: https://econnect.portal.powercor.com.au/';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Authority: www.google.com.vn';
    $headers[] = 'Content-Length: 0';
    $headers[] = 'Content-Type: text/plain';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $pattern = '/<div id="(.*?):buttonPanel2Id">/';
    $returnValue = preg_match($pattern, $result, $matches);
    if ( $returnValue == false || $matches == null || count($matches) < 2)die;

    $pdf_link = '';
    if(!empty($recordId)){
        $pdf_link = $site.'/solarpreapprovaldetailpdfpage?recordId='.$recordId;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,  $pdf_link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36';
    $headers[] = 'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8';
    $headers[] = 'Accept-Language: en,vi;q=0.9';
    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
    $headers[] = 'Authority: www.google.com.vn';
    $headers[] = 'Content-Type: application/pdf';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $resultPDF = curl_exec($ch);
    curl_close($ch);

    if(!empty($bean->id)){
        $generate_ID = $bean->pre_install_photos_c;
        $folder = dirname(__FILE__)."/server/php/files/".$generate_ID;

        if(!file_exists ( $folder )) {
            mkdir($folder);
        }
        
        //save pdf file
        if($meter_phase_c == 1){
            $file = $folder.'/'.$meter_value."_SinglePhase_CITIPOWER_POWERCOR_APPROVAL.pdf";
        }elseif($meter_phase_c == 3){
            $file = $folder.'/'.$meter_value."_ThreePhase_CITIPOWER_POWERCOR_APPROVAL.pdf";
        }
        
        if($pdf_link!=''){
            file_put_contents($file, $resultPDF);
        }else{
            echo '';
        }
    }
}

?>