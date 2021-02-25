<?php
    require_once(dirname(__FILE__).'/simple_html_dom.php');

    $$data_string = array();

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    $nmi_number =  urlencode($_GET['nmi_number']);
    $lead_id = urldecode($_GET['lead_id']);
    $meter_phase_c = urlencode($_GET['meter_phase_c']);
    $lead = new Lead();
    $lead = $lead->retrieve($lead_id);
    
    function getMeterAndSaveFile($nmi_number,$lead,$meter_phase_c){
        //solarpreapprovalrequestpage : Step1 (curl get data string)
        $url = "https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en";
        $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);

        //get form id 1
        $pattern = '/searchNMI=function\(\)\{A4J.AJAX.Submit\(\'j_id0:solarPreApprovalForm\',null,\{\'similarityGroupingId\':\'(.*?)\',\'oncomplete\'/'; //(.*?)':'/";
        $returnValue = preg_match($pattern, $result, $matches);
        if ( $returnValue == false || $matches == null || count($matches) < 2)
        {
            echo '';die();
        }
        $jform_id1 = $matches[1];
        //get form id 2
        $pattern = '/setParameters=function\(.*\)\{A4J.AJAX.Submit\(\'j_id0:solarPreApprovalForm\',null,\{\'similarityGroupingId\':\'(.*?)\',\'parameters\'/';
        $returnValue = preg_match($pattern, $result, $matches);
        if ( $returnValue == false || $matches == null || count($matches) < 2){
            echo ''; die();
        }
        $jform_id2 = $matches[1];
        //get form id 3
        $pattern = '/submitSPA=function\(\)\{A4J.AJAX.Submit\(\'j_id0:solarPreApprovalForm\',null,\{\'similarityGroupingId\':\'(.*?)\',\'oncomplete\'/';
        $returnValue = preg_match($pattern, $result, $matches);
        if ( $returnValue == false || $matches == null || count($matches) < 2){
            echo ''; die();
        }
        $jform_id3 = $matches[1];

        $html = str_get_html($result);
        $ViewState = $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value;
        $ViewStateVersion = $html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value;
        $ViewStateMAC = $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value;

        $data_string = array("AJAXREQUEST"=>"_viewRoot",
                                "j_id0:solarPreApprovalForm"=>"j_id0:solarPreApprovalForm",
                                "SiteCapacity:"=>'',
                                "j_id0:solarPreApprovalForm:reqType"=> "New Solar",
                                "j_id0:solarPreApprovalForm:existingProperty" => "true",
                                "j_id0:solarPreApprovalForm:nationMeterID"=> $nmi_number,
                                "j_id0:solarPreApprovalForm:meterNumber"=>'',
                                "j_id0:solarPreApprovalForm:addressOne"=>'',
                                "j_id0:solarPreApprovalForm:addressTwo"=>'',
                                "j_id0:solarPreApprovalForm:suburb"=>'',
                                "j_id0:solarPreApprovalForm:postcode"=>'',
                                "j_id0:solarPreApprovalForm:j_id93"=>'',
                                "j_id0:solarPreApprovalForm:existingInverter"=>'',
                                "j_id0:solarPreApprovalForm:proposedRating"=>'',
                                "j_id0:solarPreApprovalForm:phaseA"=>'',
                                "j_id0:solarPreApprovalForm:phaseB"=>'',
                                "j_id0:solarPreApprovalForm:phaseC"=>'',
                                "j_id0:solarPreApprovalForm:customerName"=>'',
                                "j_id0:solarPreApprovalForm:accreditationName"=>'',
                                "j_id0:solarPreApprovalForm:companyName"=>'',
                                "j_id0:solarPreApprovalForm:solarEmail"=>'',
                                "j_id0:solarPreApprovalForm:solarPhone"=>'',
                                "j_id0:solarPreApprovalForm:solarAddress"=>'',
                                "j_id0:solarPreApprovalForm:applicantName"=>'',
                                "j_id0:solarPreApprovalForm:applicantEmail"=>'',
                                "j_id0:solarPreApprovalForm:emailRequired"=>'on',
                                "j_id0:solarPreApprovalForm:application_date"=>date("d/m/Y"),
                                "j_id0:solarPreApprovalForm:companyId"=>'',
                                "com.salesforce.visualforce.ViewState"=>$ViewState,
                                "com.salesforce.visualforce.ViewStateVersion"=>$ViewStateVersion,
                                "com.salesforce.visualforce.ViewStateMAC"=> $ViewStateMAC,
                                $jform_id1=>$jform_id1
        );

        //solarpreapprovalrequestpage : Step 2 (curl search by NMI number)
        $ch = curl_init();
        $url = "https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://econnect.portal.powercor.com.au";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en";
        $headers[] = "Content-Length: ".strlen(http_build_query($data_string));
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
        $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
        $headers[] = "Accept: */*";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Referer: https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
        $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
        $headers[] = "Connection: keep-alive";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        // print_r($result);die();
        curl_close ($ch);

        $html = str_get_html($result);
        
        //check is confirm after search nmi.
        $pattern = '/<button class="btn btn-blue btn-block confirm_btn" onclick="confirm\(this\);" type="button">(.*?)<\/button>/';
        $returnValue = preg_match($pattern, $result, $matches);
        $confirm = '';
        if ($returnValue == false || $matches == null || count($matches) < 2)
        {
            echo '';
            die();
        }
        if($matches[1] ==''){
            $confirm = $html->find('button[class="confirm_btn"]',0)->innertext;
        }else{
            $confirm = $matches[1];
        }

        //return meter number
        if($confirm == 'Confirm'){
            $address_value = $html->find('div[id="sin_search_res"]',0)->children(0)->innertext;
            $meter_value = $html->find('label[class="meter_value"]',0)->innertext;
            $company_value = $html->find('label[class="company_value"]',0)->innertext;
            //set post data for submit confirm
            $post_confirm = array (
                'action' => 'SolarPreApprovalRequestController',
                'method' => 'getTransformerDetailsAndSolarLimit',
                'data' => 
                array (
                    0 => $nmi_number,
                ),
                'type' => 'rpc',
                'tid' => 2,
                'ctx' => 
                array (
                    'csrf' => 'VmpFPSxNakF4T0Mwd09DMHdPVlF3T1Rvek1Eb3lOUzQzTVRkYSxhQVJqUlFmWnJPQ3ZGcXdfQmx3dVJoLFltRmlaRGxp',
                    'vid' => '06628000000RyX9',
                    'ns' => '',
                    'ver' => 34,
                ),
            );
            $post_confirm = json_encode($post_confirm);

            //curl submit confirm
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://econnect.portal.powercor.com.au/customer/apexremote");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_confirm);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "X-User-Agent: Visualforce-Remoting";
            $headers[] = "Origin: https://econnect.portal.powercor.com.au";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en";
            $headers[] = "Content-Length: ".strlen($post_confirm);
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
            $headers[] = "Content-Type: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Cache-Control: no-cache";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gat=1";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Referer: https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close($ch);

            

            if($meter_value !=''){

                //solarpreapprovalrequestpage :Step 3 (set data string after confirm)
                $data_string["j_id0:solarPreApprovalForm:meterNumber"] = $meter_value;
                $data_string["j_id0:solarPreApprovalForm:addressOne"] = $address_value;
                $data_string["j_id0:solarPreApprovalForm:companyId"] =  $company_value;
                unset($data_string[$jform_id1]);

                //clone $data_string
                $result = json_decode($result);
                $data_after_confirm = $data_string;
                $data_after_confirm["tranInsCapacity"] = $result[0]->result->tranInstallCapacity;
                $data_after_confirm["tranParentLimit"] = '';
                $data_after_confirm["tranName"] = $result[0]->result->tranName;
                $data_after_confirm["tranParentName"] = '';
                $data_after_confirm[$jform_id2] = $jform_id2;
                $data_after_confirm["tranLimit"] = $result[0]->result->tranSolarLimit;
                $data_after_confirm["tranParentInsCapacity"] = '';
                
                //solarpreapprovalrequestpage : Step 4 (curl after submit confirm)
                $ch = curl_init();
                $url = "https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
                curl_setopt($ch, CURLOPT_URL,$url) ;
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data_after_confirm));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Origin: https://econnect.portal.powercor.com.au";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: en";
                $headers[] = "Content-Length: ".strlen(http_build_query($data_after_confirm));
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
                $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
                $headers[] = "Accept: */*";
                $headers[] = "Cache-Control: no-cache";
                $headers[] = "Referer: https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
                $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                $headers[] = "Connection: keep-alive";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                curl_close ($ch);
                

                $html = str_get_html($result);
                $ViewState = $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value;
                $ViewStateVersion = $html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value;
                $ViewStateMAC = $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value;

                //set data string for submit form
                $customer_name = $lead->first_name." ".$lead->last_name;
                $data_string["com.salesforce.visualforce.ViewState"] = $ViewState;
                $data_string["com.salesforce.visualforce.ViewStateVersion"] = $ViewStateVersion;
                $data_string["com.salesforce.visualforce.ViewStateMAC"] =  $ViewStateMAC;
                if($meter_phase_c == 1){
                    $data_string["j_id0:solarPreApprovalForm:phaeses"] = 'Single Phase';
                    $data_string["j_id0:solarPreApprovalForm:proposedRating"] = '5';
                    $data_string["j_id0:solarPreApprovalForm:phaseA"] = '5' ;
                }elseif($meter_phase_c == 3){
                    $data_string["j_id0:solarPreApprovalForm:phaeses"] = 'Three Phase';
                    $data_string["j_id0:solarPreApprovalForm:proposedRating"] = '30';
                    $data_string["j_id0:solarPreApprovalForm:phaseA"] = '10' ;
                    $data_string["j_id0:solarPreApprovalForm:phaseB"] = '10' ;
                    $data_string["j_id0:solarPreApprovalForm:phaseC"] = '10' ;
                }
                $data_string["j_id0:solarPreApprovalForm:customerName"] = $customer_name;
                $data_string["j_id0:solarPreApprovalForm:applicantName"] = 'Matthew Wright';
                $data_string["j_id0:solarPreApprovalForm:applicantEmail"] = 'info@pure-electric.com.au';
                $data_string["j_id0:solarPreApprovalForm:inchk"] =  'on';
                $data_string[ $jform_id3] =  $jform_id3;

                //solarpreapprovalrequestpage : Step 5 (final and get redirect link)          
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER,true);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                
                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Origin: https://econnect.portal.powercor.com.au";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: en";
                $headers[] = "Content-Length: ".strlen(http_build_query($data_string));
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
                $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
                $headers[] = "Accept: */*";
                $headers[] = "Cache-Control: no-cache";
                $headers[] = "Referer: https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
                $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                $headers[] = "Connection: keep-alive";
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);

                preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $result, $matches);
                $redirectURL = "https://econnect.portal.powercor.com.au".$matches[1];
                curl_close($ch);

                //get reference number and passcode after submit form
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$redirectURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                $headers[] = "Connection: keep-alive";
                $headers[] = "Pragma: no-cache";
                $headers[] = "Cache-Control: no-cache";
                $headers[] = "Upgrade-Insecure-Requests: 1";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
                $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
                $headers[] = "Referer: https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: en";
                $headers[] = "Cookie: cookies.js=1; pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                curl_close ($ch);
                
                $html = str_get_html($result);
                $reference_number = $html->find('a[href="requestvalidatepasscodepage?RequestType=SPA&amp;returnURL=SPAConfirmationPage"]',0)->innertext;
                $passcode = $html->find('a[href="requestvalidatepasscodepage?RequestType=SPA&amp;returnURL=SPAConfirmationPage"]',1)->innertext;
    
                if($reference_number !='' &&  $passcode !=''){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://econnect.portal.powercor.com.au/customer/requestvalidatepasscodepage?RequestType=SPA&returnURL=SPAConfirmationPage");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                    
                    $headers = array();
                    $headers[] = "Connection: keep-alive";
                    $headers[] = "Pragma: no-cache";
                    $headers[] = "Cache-Control: no-cache";
                    $headers[] = "Upgrade-Insecure-Requests: 1";
                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
                    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
                    $headers[] = "Accept-Encoding: gzip, deflate, br";
                    $headers[] = "Accept-Language: en-US,en;q=0.9";
                    $headers[] = 'Cookie: cookies.js=1; _ga=GA1.3.1709575075.1579573027; _gid=GA1.3.642147485.1579573027; pctrk=ac5ef2dc-ffdb-4974-8baf-e42b0097552e; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gat=1';

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    //print_r($result);
                    curl_close ($ch);
                    
                    //get form id form login
                    $pattern = '/jsfcljs\(document.forms\[\'j_id0:spa_passcodelogin_form\'\],\'(.*?),/';
                    $returnValue = preg_match($pattern, $result, $matches);
                    if ( $returnValue == false || $matches == null || count($matches) < 2){
                        echo ''; die();
                    }
                    $jform_id_login = $matches[1];

                    $html = str_get_html($result);
                    $ViewStateVersion = urlencode($html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value);
                    //$query_post = "j_id0%3Aspa_passcodelogin_form=j_id0%3Aspa_passcodelogin_form&requestId=fake+input&j_id0%3Aspa_passcodelogin_form%3ArequestId=".$reference_number."&passCode=fake+input&j_id0%3Aspa_passcodelogin_form%3ApassCode=".$passcode."&".$jform_id_login."=".$jform_id_login."&com.salesforce.visualforce.ViewState=i%3AAAAAWXsidCI6IjAwRDI4MDAwMDAwSFBTWSIsInYiOiIwMkcwSTAwMDAwMERQbVQiLCJhIjoidmZlbmNyeXB0aW9ua2V5IiwidSI6IjAwNTI4MDAwMDAwaGN3cCJ9e%2FljvAr6emHoR3R7KgVKLX637c7z1YJHx3%2FycgAAAWUS2DbJ50ETxBVWkXI5TvOUXq4yjOY8p84v6E3%2BwVHZ1bnkYUcAjRUMAdm3SVNi54mgPGSfv6bs54fElGF8Q8xW%2BTpSd3TxUeEe6bckNxMAhxO3RIjsSqh2Fs%2BfRwryhUPTWJY7VczMsAIr5HjLNcTtKRNGHWH0M9ORDtZXeEbHsSp2oU9p%2FDasaO31YcZ4cRFffMrzSv7M%2Bik6sV%2BVKyud%2FhiHk0LXiPR%2FFSRmZEEwBizqlnow7FgI55zpPn68Pv1fLr%2FS05I1MlDJUgQ7VEW3tuu6WP7Yt%2F8XJBj01wnjtbwp0f6i9MTjgCfE1uvsWNFAaj8Rvohrzx4%2FxBBRgRfxRO5MWbmuNvp%2Fvp52NzYx%2BCuShVQbt1qQ3uap4ES8w9htqF%2F0B91WlW%2BeA9Sut8xOBnistiLrYVWc1g6DWjetDy1z7EUkUvW%2BFcajWqMC66A3nXfzMVKePWFWlFK9ar602Jl8PIyofbbRaXxPjfl2WOFXeQ3LYD9XcgpMijRuH39ZADW2HlvaLIvaLVUZdOJSdPo1FN%2FMm2LIEWUjB20CoSC9LRhwNIQTFERFQedwoZUhPUAoFwE8uFR5qHRxJ6AnXiNb7Ui3sxu6JNlNuvia95S8jutc8bY6A4r2uFbCA7XIAUJMbib7OocMRVaAUShBIT1SN2W6ggcmwtEl3Qecqvtvq%2F7KQf0QGcRxrs1IwZoPTVAPtNMeNh%2Fukwz1PUml2njvQvP1YvU6CV%2FhlSNoHmX70tKzV93Udha2%2B016OObF%2FlckPWOCAc1pveLFJNF3bfvXEAzVEySuMwUyOgd9PK%2BAr7chLJPxiat0U4ergriSSJscMK5eYnBBp3JAMNX2UAooNsVvreVd3xe0MrOmxW03Wcek7x8Fi7KqIQb5qNhKX7rK5vitB0%2FAzHEovXEs%2FVaJjnY%2FG%2F9Qd81pDKCJG5PYlvsl4Q0mBJQ2k%2Fk48kBoBErLq7HJUDIlmkVGVnZyvAvFO3LlPbXfDCf0Qjx8EODSzJGFrQSkxTGFez4mZxjstVMPP7INNKGcHVxHiC96oysqjpSf9P8p6S%2FPYs6k9I3B4BHIOVukpQDtRbe1GN6WUJ2FSHQDOfJK6hpb%2BYfkY%2BtNPCSYaZzhM0J9nOtm0XB9MLfCtIiTTZQmVgoDFfIzXaEXcHQWN%2B2868gQtepPZYQQHYQABP1zjkSOHSA6r%2FV5KGTwNcl958hRddAfQ%2FRppe4XrXnsmUXlXcIW088B35b3XPTjKr%2FL5tqErMHrk0vr9ysNUfFuUfttA7Y8rV8KhSD%2F%2F95MI21SBE54c9OLK8hwBFP9SCpnA2EY%2FOsxRFVvlSKwUFZSiMVLMDlk0Nx1JkbbyP6Mb4%2F7dsIa6VIRMfjLAfxVWGqLRxWMa8pd2Mpe%2BNGVF8mg6C7GqX5b6oEzFaWLMwoIaIQ5YgNs%2FP4h6Ov%2Bk1HMZPM4UyFoVtMU6vJ4tK1QS79w7bsTVFiBoJVY6bZjhnTVsQ2FTYMr4H2ZHo%2FDBNPd6YMh4dqy6GItqbV6mXRx67%2Ba%2B4q4Ula6fv3OEIr%2BDFRFj5YUrrRYDYDd6%2FKlFkgMY63Ql9V2CELBxc9e5%2BohzP3exmFpDXdSeLNXh4O1UQfeua%2FLgN52AjD9NLJR8Gkc06miHzJsZPkagWJZG6aEJnIYXWLt20fxjRjq8%2FcdHCKjqmUiG731UC3bc4CEKwX%2BgdtTHfp2q7fqSrGT6mUgjucVGSwcCxfMkXlXLyEJA7Hz0u37Q7n%2Fh2IMpiGzWbl6ADMOEa2W1HAUNrAMWztJtQvqVmo3b%2BHA%2FhSm%2F5yNtyVAN97jPSvXbIi74HlGxJag%2BPwZ5c9cukOS%2F8%2FsD0zIwZIdFjJr1f7tYM%2B1c5yOGLsiucO9xZRU6%2F1e%2FLG0OJWlXtcCLimyMuremuXujg4dOs7U6f0rdm7PAR5o1fzCB09bM3xRO7HqFg%2FV696dsYme%2F73f93Q%2F5KDK%2Bk%2BXBwDnDohfB0FoS6bKTF9NyKSmJcVNl%2Fpf4Sck69b7xmz784H28k3EIG51geeGsVpEWsLt5Ws6jaMUto%2Fj%2FT68ksqhQ1UywmxtqYvIbsxYctV%2B6HTISuVgrN6i3tAwzLiVROxZ5ZlM2riYuTjPgv5%2BrkUGf69KTzji3GrfTlYPxLgyFYSjM4%2BQdQOaPQ4lG9uDBfzq2EzxpFBXFjmlmEOtJKOY51GgPJ%2F80MwO8nLwi4KeRpPOpQpaast46r%2BjSFpgqdxKPMS6FM0%2FODncrfoqoL7NgAY2jRebBC9TMniV8Py5IpRA69Cp2nJ6ox9UPbupFie3DhBtZFZHDt2pqAyJJ63AU4nVPTv3XdAZcU7TrTqiDQ1XNhc9tIuKmnlrSJ4f6x8onSa13%2F3sJseS1XIswegbZdpWvCbl3ODKh1zf4Y2pEQSydtDprQuQwJCV30zA8pROEimGB6hQWTk20ef4YRRc15TasYQFlqRFlzaQNdd0uM3ybptR0hxJrdUXlpewXLtblGlNADyCBMtG35Ztz7QSBORS2UBLrmJwEdzT0BSLc9rdZCsu8L42aHHB2UuyN9pWbwA5hxRlqlW1gmvN4dejolrvMBrWD1iWZvEt7gsk4Wf8O9BI0yna93h9z%2Fe04nXD%2BncnpCqkr2ZsG%2Bx%2F60EzgZUQxjXkvcMr5mXv3xIvFhyIxXUgbnnW6Ry%2BTKUpT%2Fo28SVwyJERJGzH4HRYPDbxUzInn4BzpXyJ%2BOORGP7mIF9eK%2B0cBvDzk0e2%2Bm8ViKehF4DVyvl2M%2B3bGiW9zvkA5UY3F9LEOF97%2ByLcxyQQ3dML1EPLu%2Fv0JhCpaLPjASKNHaTIHZcuZw2glQj7EenDLMQCBlX%2FaKZ9pI%2BF0KWUdccx8rjeEs%2FKfTkXnlgofFxfZQk2PGVdlcFnUX4K0yDXldej9TOuZ4AAr6%2B0n24JvvKOaYJi5N3T9SxJsq49PgbrsN3KPgV5wwgf7n5a%2F6hRc0qbS0lR1eebefmGE0s5DavGungJfJds92vBJ5Q9xzwBpL%2BxPWZPXipCU%2Fh4NxeAR%2BfCdw%2BHf5uhxiWst6x54D5hXYJO4A%2BBbk1lnd7s6fTnfVu3poru6vSD7%2BesbYyM14RD1kkw8pU3bTbiZV57IgalWMRVpV9rE0qq5efuZ3shZIPhCa6EwE%2FSC6DrKp1jSgfdPnQiJzoDKhcEzr6OcOo%2BOAA1viceXNMhLBiriagO9VkcZrXwijlZUIGOwtoLJf%2BqRGvQIw2geQOSg%2Bu55O2%2B1dAdRiYRtT5U1XQcG9AsBngdFL4MxRPzoR3bnwWyi1E5ntdfIxJj6Owq%2F8ZRGt78Fq8Hb6SYi8GO26pJTcof0NMSIjVFRiUIGZdCFoSLWDfTmtoWLLd0ffQoCazclnqte00Edbw3QrIEJDYth36FR1DAVifWWGo8OhS%2B4APBRtAUEkceN09o5biUfprj7xDXiXifkYIhqiE1XtggJbu3yrNqoUyr9frhujsSUWKUKRpd6TMxjOQMLR3nlpNviiOXMOVFbcIndxx0mu85y3NaR9l5SOKSLCxbNUcPeF5nIa9TKVT4Z%2BX2VZpWtbhNPT5hKtLcCH7GzfsMfL3OfdIj7kmadzEEO%2BIwmk44bdCTjyHpbFa%2BSx1hdr7JhZaVWDCtmzzKAs0asxxeO1zwsgMQq0M8a7OoFz7jDAiSeNT82Swttpiz%2F452IUgP0PL3xm2cS5Qmt1iul27NEz1utFfbY7fK2gYjS1f04I%2FWqWxXTRRxWdLEcWxBW5JIzyezOHlmCefTGjOk6I2Av2JBMJl046%2BB%2F9uvpSmLDcIMRcro6ndyrcO%2FHjIdCg2Gu1MiQmW1lVmXeS0RY1HBBfdwhT%2FumoGAokRzqeLxX7MexqZRu6hvxuDH4TW6C8OegBtDuANC3taNKtkgGiNYSo9efQHkhCLexmR5uSeITwSvNR5FeZ%2F%2FOhUDlKEnif%2BjXLftZXnhSoAbnQ1298q9zW%2BFjGrHllCVubxlnYxfAZIsYBQIdkoLGzlks6Qq4spTij5WPEhrBe7EXrKmCrOYYVFpk1zBhX%2FGDs3WRhuH%2FxLjGGZmI2%2Bi0oXsGxsuy%2FqXX7oSYRZGZwPpzNU%2FEU5v9XmJYeoRF8vb8iFFLtjOy93m8qsykBC2EhU6HPuRK1hUY0MtGNvkRYZNthJXIw%2FH8iSFL2p9wVbYaqnlciFPvlyEQ88V4WlXIVanXdFpVNqCw5tAIva38LHHj6Bjmq%2B9dhPpl%2Fv%2FovZ08ewDyXIdRtmQ5hFhD3nmUvLNwZgPHQFj4sy7k5hH0Ogb65%2BOq0NbxKHNgxepngaRjtTy9Eo3slRC48lI%2BATieZEtFfy8h90UOlQnBkDLg%2BPWCBeByR4QwgI21VegdrgpiUBczEQfnjgx8cIRoGg5su3L5oDXnvcUYwydSurw0pkZaxKNgcpSMSQvRZ4%2Bbl0rwoAQXgsAmM8sWlpckGIojItHRSyU5RVO8fS4ZoQGASIkH7RocQXL%2Fr92OgFwoiixZ0AC9lSMncarujOsFWwxmioRNDe0HsdgvzCHj3PO1xBux6CAcUaw1iKVRtTrBFPxBJBgXS3yrFQOPpW4UWXG1g5DVViU6Kmi8gT%2BFqMUVax4z%2FMCbWoWh0DfaJww6gaS7PImQrx7Rs19d9q0VlOuWwlO16oxS3q3akgPK7Zt8hP9EQ%2B9BGr4Z78CTtLTBXR2o5MhfQtnJSDxg8DNVThm8G2NlH9yNGw1zd1eD3Rz7ET1Jzk5vnepkv92qZJV%2B9HpiZKD%2B1TY%2FyUNUKvYK3LXLiew0dZ1uL38kKNJpejSItwL%2BYx5Hvb3j6dgUW2uFTfFOEVJS4NMAsxGp0F%2BFX%2B4Ttb6WfOLIEC6CzpxXBq0eco44tlzZO4QKBF81qp6iSYyl3hyOJafkM4gvz48xKwu1D1ecWMHeNCL3LP6XWm1HJc0RaL3BLF%2F9FfOmsw3QikVihZ9iAgCXuQ2HCvez695OYSgNs6pLZahDw3goMwefh3yXLMVozsh2hEUzNRYgyzdUnYexIBTK0p%2BcHLKlQ%3D%3D&com.salesforce.visualforce.ViewStateVersion=".$ViewStateVersion."&com.salesforce.visualforce.ViewStateMAC=AGV5SnViMjVqWlNJNklsSkVTRlppYldWT2IxTmlVMVZJWXpneWNXUXpUWFZ0UzB4VmVFdFpUbE55ZFMxa1MzcG9TV0ZDY0RCY2RUQXdNMlFpTENKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUlzSW10cFpDSTZJbnRjSW5SY0lqcGNJakF3UkRJNE1EQXdNREF3U0ZCVFdWd2lMRndpZGx3aU9sd2lNREpITUVrd01EQXdNREJFVUcxVVhDSXNYQ0poWENJNlhDSjJabk5wWjI1cGJtZHJaWGxjSWl4Y0luVmNJanBjSWpBd05USTRNREF3TURBd2FHTjNjRndpZlNJc0ltTnlhWFFpT2xzaWFXRjBJbDBzSW1saGRDSTZNVFV6TXpZeE9UUTRORE0yTXl3aVpYaHdJam93ZlE9PS4uazlLSEtna0lNU2g0VmxRUjk4eXFmYl9tRThuQ2Exa3lZTlpyY2xkYTJHWT0%3D";
                    $query_post = array(
                        "j_id0:spa_passcodelogin_form" => $html->find("input[name='j_id0:spa_passcodelogin_form']",0)->value,
                        "requestId" => $html->find("input[name='requestId']",0)->value,
                        "j_id0:spa_passcodelogin_form:requestId" =>  $reference_number,
                        "passCode" =>  $html->find("input[name='passCode']",0)->value,
                        "j_id0:spa_passcodelogin_form:passCode" => $passcode,
                        $jform_id_login => $jform_id_login,
                        //"com.salesforce.visualforce.ViewState" => 'i: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',//$html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value,
                        "com.salesforce.visualforce.ViewState" => $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value,
                        "com.salesforce.visualforce.ViewStateVersion" => $ViewStateVersion,
                        //"com.salesforce.visualforce.ViewStateMAC" => 'AGV5SnViMjVqWlNJNklrbEdZbGsxUTFCRlVtaGZPVzFLY3pkd2RFNXFUR0pNTFVsR0xWcEJXV3hZVDNwS1ZITjRPV1ZmZERSY2RUQXdNMlFpTENKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUlzSW10cFpDSTZJbnRjSW5SY0lqcGNJakF3UkRJNE1EQXdNREF3U0ZCVFdWd2lMRndpZGx3aU9sd2lNREpITUVrd01EQXdNREJFVUcxVVhDSXNYQ0poWENJNlhDSjJabk5wWjI1cGJtZHJaWGxjSWl4Y0luVmNJanBjSWpBd05USTRNREF3TURBd2FHTjNjRndpZlNJc0ltTnlhWFFpT2xzaWFXRjBJbDBzSW1saGRDSTZNVFUwTWpBM09EZzJPRFkxTXl3aVpYaHdJam93ZlE9PS4uei0tcm80cVRicERJdzJVd0U1dFZRN3B5bFlUWWpWaDM4bnZFdWtwQWRDND0='//$html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value);
                        "com.salesforce.visualforce.ViewStateMAC" => $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value,
                    );
                    // old code

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://econnect.portal.powercor.com.au/customer/requestvalidatepasscodepage');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query_post));
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTREDIR, 3);

                    $headers = array();
                    $headers[] = 'Connection: keep-alive';
                    $headers[] = 'Pragma: no-cache';
                    $headers[] = 'Cache-Control: no-cache';
                    $headers[] = 'Origin: https://econnect.portal.powercor.com.au';
                    $headers[] = 'Upgrade-Insecure-Requests: 1';
                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    $headers[] = "Content-Length: ".strlen(http_build_query($query_post));
                    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
                    $headers[] = 'Sec-Fetch-User: ?1';
                    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
                    $headers[] = 'Sec-Fetch-Site: same-origin';
                    $headers[] = 'Sec-Fetch-Mode: navigate';
                    $headers[] = 'Referer: https://econnect.portal.powercor.com.au/customer/requestvalidatepasscodepage?RequestType=SPA&returnURL=SPAConfirmationPage';
                    $headers[] = 'Accept-Encoding: gzip, deflate, br';
                    $headers[] = 'Accept-Language: en-US,en;q=0.9';
                    $headers[] = 'Cookie: cookies.js=1; _ga=GA1.3.1709575075.1579573027; _gid=GA1.3.642147485.1579573027; pctrk=ac5ef2dc-ffdb-4974-8baf-e42b0097552e; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gat=1';

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    curl_close ($ch);

                    //get redirect link after login
                    $pattern = "/window.location.replace\('(.*?)'\);/";
                    $returnValue = preg_match($pattern,$result,$matches);

                    if($matches[1]!=''){
                        $link = "https://econnect.portal.powercor.com.au".$matches[1];
                        $refer = explode("?",$matches[1]);

                    }

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                    
                    $headers = array();
                    $headers[] = 'Connection: keep-alive';
                    $headers[] = 'Pragma: no-cache';
                    $headers[] = 'Cache-Control: no-cache';
                    $headers[] = 'Upgrade-Insecure-Requests: 1';
                    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';
                    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
                    $headers[] = 'Sec-Fetch-Site: same-origin';
                    $headers[] = 'Sec-Fetch-Mode: navigate';
                    $headers[] = 'Referer: https://econnect.portal.powercor.com.au/customer/requestvalidatepasscodepage';
                    $headers[] = 'Accept-Encoding: gzip, deflate, br';
                    $headers[] = 'Accept-Language: en-US,en;q=0.9';
                    $headers[] = 'Cookie: cookies.js=1; _ga=GA1.3.1709575075.1579573027; _gid=GA1.3.642147485.1579573027; pctrk=ac5ef2dc-ffdb-4974-8baf-e42b0097552e; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gat=1';

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    curl_close($ch);

                    //get form id button download
                    $pattern = '/<div id="(.*?):buttonPanel2Id">/';
                    $returnValue = preg_match($pattern, $result, $matches);
                    if ( $returnValue == false || $matches == null || count($matches) < 2){
                        echo ''; die();
                    }
                    $jform_id_download = $matches[1];


                    //set data query for submit form get file pdf
                    $html = str_get_html($result);
                    //$data1= $html->find('input[name="'.$jform_id_download.'"]',0)->value;
                    $data2 = $jform_id_download.":generatePDF";
                    $ViewState = $html->find("input[id='com.salesforce.visualforce.ViewState']",0)->value;
                    $ViewStateVersion = $html->find("input[id='com.salesforce.visualforce.ViewStateVersion']",0)->value;
                    $ViewStateMAC = $html->find("input[id='com.salesforce.visualforce.ViewStateMAC']",0)->value;
                    $data = array($jform_id_download=> $jform_id_download,
                                    $data2=>$data2,
                                    "com.salesforce.visualforce.ViewState"=>$ViewState,
                                    "com.salesforce.visualforce.ViewStateVersion"=>$ViewStateVersion,
                                    "com.salesforce.visualforce.ViewStateMAC"=>$ViewStateMAC);

                    // curl for submit form get file pdf              
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestdetailpage");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                    $headers = array();
                    $headers[] = "Connection: keep-alive";
                    $headers[] = "Pragma: no-cache";
                    $headers[] = "Cache-Control: no-cache";
                    $headers[] = "Origin: https://econnect.portal.powercor.com.au";
                    $headers[] = "Upgrade-Insecure-Requests: 1";
                    $headers[] = "Content-Type: application/x-www-form-urlencoded";
                    $headers[] = "Content-Length:".strlen(http_build_query($data));
                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36";
                    $headers[] = "Accept-Encoding: gzip, deflate, br";
                    $headers[] = "Accept-Language: en";
                    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
                    $headers[] = "Cookie: pctrk=d9127ffb-a447-407b-8221-b80ab0c70be3; _ga=GA1.3.1521755390.1533521031; _gid=GA1.3.1717996090.1533521031; cookies.js=1; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; _gat=1";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    curl_close ($ch);

                    //get and save pdf file
                    $pattern = "/window.location.replace\('(.*?)'\);/";
                    $returnValue = preg_match($pattern,$result,$matches);
                    $pdf_link = '';
                    if($matches[1]!=''){
                        $pdf_link = "https://econnect.portal.powercor.com.au".$matches[1];

                    }

                    if($lead->id !=''){
                        $generate_ID = $lead->installation_pictures_c;
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
                            file_put_contents($file, file_get_contents($pdf_link));
                            echo $meter_value;
                        }else{
                            echo '';
                        }
                    }
                }
            }else{
                echo '';
            }
        }else{
            echo '';
        }
    }
    if(isset($nmi_number) && $nmi_number !=''){
        if($meter_phase_c == 1){
            getMeterAndSaveFile($nmi_number,$lead,1);
        }elseif($meter_phase_c == 3){
            getMeterAndSaveFile($nmi_number,$lead,1);
            getMeterAndSaveFile($nmi_number,$lead,3);
        }
    }else{
        echo '';
        die();
    }
    die();
?>