<?php
    array_push($job_strings, 'custom_sgquoteinfo_site_inspections');
    function wrapperCRMSolargain_site_Inspections($status){

        date_default_timezone_set('Australia/Sydney');
        set_time_limit ( 0 );
        ini_set('memory_limit', '-1'); 

        $username = "matthew.wright";
        $password =  "MW@pure733";

        $url = 'https://crm.solargain.com.au/APIv2/quotes/search';

        $param = array (
            'Page' => 1,
            'Sort' => 'ID',
            'Descending' => true,
            'PageSize' => 50,
            'Filters' => 
            array (
            0 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Quoted By User',
                'Code' => 'QUOTEDBYUSER',
                'Type' => 1,
                ),
                'Value' => '475',
                'Operation' => 'EQ',
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

    function custom_sgquoteinfo_site_inspections(){
        $status_arr = array("SITE_INSPECTION_BOOKED","REQUIRES_SITE_INSPECTION");
        $html_content = "";
        for($i = 0; $i < count($status_arr); $i++){
            $quoteJSON = wrapperCRMSolargain_site_Inspections($status_arr[$i]);

            $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";

            if(count($quoteJSON->Results)>0){
            $html_content .= 
            '<table>
                <tr>
                <td width="30%"><strong>Link</strong></td>
                <td width="30%"><strong>Customer</strong></td>
                <td width="30%"><strong>Status</strong></td>
                <td width="30%"><strong>Duration</strong></td>
                </tr>';
            foreach($quoteJSON->Results as $res){
            $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
            $name = $res->Customer->Name;
            $status = $res->Status->Description;
            $date_LastUpDated = $res->LastUpdated;
            if($date_LastUpDated == ''){
                $date_LastUpDated = 'NO';
            }else{
                $d1=new DateTime($date_LastUpDated); 
                $d2=new DateTime(); 
                $date_diff= $d2->diff($d1);
                $date_LastUpDated = $date_diff->d .' days ' .$date_diff->h .' hours ' .$date_diff->i .' minutes';
            }
            $html_content .= 
            "<tr>
                <td><a href=".$link.">QUOTE#".$res->ID."</a></td>
                <td>".$name."</td>
                <td>".$status."</td>
                <td>".$date_LastUpDated."</td>
            </tr>";
            
            }
            $html_content .= 
            "</table>";
            }else{
                $html_content .= "<h4>No Quote</h4>";
            }
        }

        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);

        $mail->Subject = 'SOLARGAIN STATUS DAILY SITE INSPECTION';

        $mail->Body = $html_content;

        $mail->prepForOutbound();
        //$mail->AddAddress('binhdigipro@gmail.com');
        $mail->AddAddress('info@pure-electric.com.au');
        $mail->AddCC('tiarna.hodge@solargain.com.au');
        //$mail->AddCC('info@pure-electric.com.au');
        //$mail->AddAddress('thienpb89@gmail.com');

        $sent = $mail->Send();
    }