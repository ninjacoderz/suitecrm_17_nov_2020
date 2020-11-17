<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

function updateStatusSolargainLead($solarLeadID, $status, $quoteNumber = "",$onHoldDate){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    
    $username = "matthew.wright";
    $password =  "MW@pure733";

    // Convert quote 
    if($quoteNumber != ""){
        // if update status quote
        if($status =="reopen"){
            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteNumber."/reopen";
        } else 
            $url = "https://crm.solargain.com.au/APIv2/quotes/".$quoteNumber."/lost/". $status;
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
                "Referer: https://crm.solargain.com.au/quote/edit/".$quoteNumber,
                "Cache-Control: max-age=0"
            )
        );
        
        $result = curl_exec($curl);
        curl_close ( $curl );
    }

    // Update next date
    // Thien fix
    if($status == 'ON_HOLD') {
        $url = "https://crm.solargain.com.au/APIv2/quotes/". $quoteNumber;

        //set the url, number of POST vars, POST data
    
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);

        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Upgrade-Insecure-Requests: 1",
                "Cache-Control: no-cache",
                "Pragma: no-cache"
            )
        );
        
        $quoteJSON = curl_exec($curl);
        curl_close ( $curl );
    
        $quoteSolarGain = json_decode($quoteJSON);

        $quoteSolarGain->NextActionDate = array(
            "Date" => date('d/m/Y', strtotime($onHoldDate)),
            "Time"=> "12:00 PM"
        );

        $quoteSolarGain->Status->Description = "Contact Later";

        $quoteSolarGain->Notes[] = array(
            "ID" => 0,
            "Type"=> array(
                "ID"=>10,
                "Name"=>"Status Updated",
                "RequiresComment"=> false,
            ),
            "Text"=> 'Quote Status: Contact Later'
        );

        $quoteSolarGainJSONDecode = json_encode($quoteSolarGain, JSON_UNESCAPED_SLASHES);
        // Save back quote 
        $url = "https://crm.solargain.com.au/APIv2/quotes/";
        //set the url, number of POST vars, POST data
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $quoteSolarGainJSONDecode);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($quoteSolarGainJSONDecode),
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$quoteNumber,
            )
        );
        
        curl_exec($curl);
        curl_close ( $curl );
    }else{
        if($status =="reopen"){
            $url = "https://crm.solargain.com.au/APIv2/leads/".$solarLeadID."/reopen";
        }  else 
            $url = "https://crm.solargain.com.au/APIv2/leads/".$solarLeadID."/lost/". $status;
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
                "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
                "Cache-Control: max-age=0"
            )
        );
        
        $result = curl_exec($curl);
        curl_close ( $curl );

        $url = "https://crm.solargain.com.au/APIv2/leads/". $solarLeadID;
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
                "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
                "Cache-Control: max-age=0"
            )
        );
        
        $leadJSON = curl_exec($curl);
        curl_close ( $curl );

        $leadSolarGain = json_decode($leadJSON);

        $leadSolarGain->NextActionDate = array(
            "Date" => date('d/m/Y', time() + 3*24*60*60),
            "Time"=>"9:00 AM"
        );

        $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);

        // Save back lead 
        $url = "https://crm.solargain.com.au/APIv2/leads/";
        //set the url, number of POST vars, POST data
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($leadSolarGainJSONDecode),
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/lead/edit/".$solarLeadID,
            )
        );
        
        curl_exec($curl);
        curl_close ( $curl );
    }

}

class OpportunitiesCreateRelationship
{
    function after_save_method($bean, $event, $arguments)
    {
        //echo "s";
        $account_id = $bean-> account_id;

        $sql = "SELECT opportunity_id FROM accounts_opportunities WHERE account_id='".$account_id."' AND deleted = 0";

        $result = $GLOBALS['db']->query($sql);

        while($row = $GLOBALS['db']->fetchByAssoc($result) ) {
            //Use $row['id'] to grab the id fields value
            $opportunity_id = $row['opportunity_id'];
            if($opportunity_id != $bean->id){
                $oOpportunity = BeanFactory::getBean('Opportunities', $opportunity_id);
                $oOpportunity->load_relationship('opportunities_opportunities_1');
                $oOpportunity->opportunities_opportunities_1->add($bean->id);
            }
        }
        /*$query = new SugarQuery();
        $query->from(BeanFactory::getBean('Opportunities'));
        $query->where()->equals('account_id', $account_id);
        $results = $query->execute();
        print_r($results);*/
    }
}

class PushToSolargain
{
    function before_save_method_pushToSolargain($bean, $event, $arguments)
    {

        $old_fields = $bean->fetched_row;
        if($old_fields['id'] == "") return;

        $lost_status_mapping = array(
            "Lost_Competitor" => "LOST_TO_COMPETITOR",
            "Lost_Uncontactable" => "UNCONTACTABLE",
            "Lost_Unsuitable" => "UNSUITABLE_ROOF",
            "Lost_Enquiry_Only" => "ENQUIRY_ONLY",
            "Lost_No_Longer_Interested" => "NO_LONGER_INTERESTED",
            "Lost_Outside_Service_Area" => "OUTSIDE_SERVICE_AREA",
            "Lost_Duplicate" => "DUPLICATE",
            "Lost_Council" => "COUNCIL",
            "New" => "reopen" ,
            "Assigned" => "reopen" ,
            "In Process" => "reopen" ,
            "Converted" => "reopen" ,
            "Recycled" => "reopen" ,
            "On_Hold" => "ON_HOLD"
        );

        if($old_fields['sales_stage'] != $bean->sales_stage){
            // Push status to solargain
            $db = DBManagerFactory::getInstance();
            $ret = $db->query(
                "
                SELECT leads.id FROM `opportunities` 
                LEFT JOIN accounts_opportunities ON accounts_opportunities.opportunity_id = opportunities.id
                LEFT JOIN leads ON leads.account_id = accounts_opportunities.account_id
                WHERE opportunities.id LIKE '".$bean->id."'"

            );
            while ( $row = $db->fetchByAssoc($ret) ) {
                $lead = new Lead();
                $lead = $lead->retrieve($row['id']);
            }

            if(isset($lost_status_mapping[$bean->sales_stage]) && $lead->id){
                if($bean->sales_stage == 'On_Hold' && $old_fields['date_closed'] != $bean->date_closed){
                    $today = date('d/m/Y', time());
                    if(strtotime($today) < strtotime($bean->date_closed)){
                        updateStatusSolargainLead($lead->solargain_lead_number_c, $lost_status_mapping[$bean->sales_stage], $lead->solargain_quote_number_c, $bean->date_closed);
                    }
                }else{
                    updateStatusSolargainLead($lead->solargain_lead_number_c, $lost_status_mapping[$bean->sales_stage], $lead->solargain_quote_number_c, '');
                }
            }
        }
    }
}
?>