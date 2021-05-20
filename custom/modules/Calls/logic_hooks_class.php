<?php

    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    if ( !function_exists('updateCallSolargain') ) {
        function updateCallSolargain($solarLeadID, $call_name, $quoteNumber = ""){
            date_default_timezone_set('Australia/Sydney');
            set_time_limit ( 0 );
            ini_set('memory_limit', '-1');
            
            $username = "matthew.wright";
            $password =  "MW@pure733";

            $url = "https://crm.solargain.com.au/APIv2/leads/". $solarLeadID;

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
            
            $leadJSON = curl_exec($curl);
            curl_close ( $curl );
        
            $leadSolarGain = json_decode($leadJSON);

            $leadSolarGain->Notes[] = array(
                "ID" => 0,
                "Type"=> array(
                    "ID"=>3,
                    "Name"=>"Phone Out",
                    "RequiresComment"=> true,
                ),
                "Text"=> $call_name
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
    class PushCallToSolargain {
        function after_save_method_push_cal_sg($bean, $event, $arguments){
            $db = DBManagerFactory::getInstance();
            $old_fields = $bean->fetched_row;
            if($old_fields == false){
                switch($bean->parent_type){
                    case "Leads" :
                        $query = "SELECT leads.id FROM leads WHERE leads.id = '".$bean->parent_id."'";
                        break;
                    case "Opportunities" :
                        $query =  "SELECT leads.id FROM leads INNER JOIN opportunities_contacts ON leads.contact_id = opportunities_contacts.contact_id WHERE opportunities_contacts.opportunity_id = '".$bean->parent_id."'";
                        break;
                    case "Accounts" :
                        $query =  "SELECT leads.id FROM leads WHERE leads.account_id = '".$bean->parent_id."'";
                        break;
                    case "Contacts" :
                        $query =  "SELECT leads.id FROM leads WHERE leads.contact_id = '".$bean->parent_id."'";
                        break;
                    default :
                        break;
                }
                $lead = $db->fetchByAssoc($db->query($query));
                if(isset($lead['id'])){
                    $lead_bean = BeanFactory::getBean('Leads',$lead['id']);
                    if($lead_bean !== false){
                        updateCallSolargain($lead_bean->solargain_lead_number_c,$bean->name,$lead_bean->solargain_quote_number_c);
                    }
                }
               // updateCallSolargain('105072',$bean->name,'68878');
            }
        }
    }
    
    //thien code change call subject name
    class ChangeSubjectCall {
        function after_save_method_change_subject($bean, $event, $arguments){
            if($bean->name == "Call for Convert Lead"){
                $lead = new Lead();
                $lead = $lead->retrieve($bean->parent_id);

                if($lead->id == ''){
                    return;
                }

                $customer_name = $lead->account_name;
                $address       = $lead->primary_address_street . " " . 
                                    $lead->primary_address_city   . " " . 
                                    $lead->primary_address_state  . " " . 
                                    $lead->primary_address_postalcode ;
                $subject = "Call ".$customer_name." ".trim($address);

                $call = new Call();
                $call = $call->retrieve($bean->id);

                if($call->id == ''){
                    return;
                }

                $call->name = $subject;
                $call->save();
            }
        }
    }

    class DuplicateCall {
        function after_save_method_duplicate_call($bean, $event, $arguments){
            /*$db = DBManagerFactory::getInstance();
            $old_fields = $bean->fetched_row;
            if($old_fields == false){
                switch($bean->parent_type){
                    case "Leads" :
                        $query = "SELECT leads.id FROM leads WHERE leads.id = '".$bean->parent_id."'";
                        break;
                    case "Opportunities" :
                        $query =  "SELECT leads.id FROM leads INNER JOIN opportunities_contacts ON leads.contact_id = opportunities_contacts.contact_id WHERE opportunities_contacts.opportunity_id = '".$bean->parent_id."'";
                        break;
                    case "Accounts" :
                        $query =  "SELECT leads.id FROM leads WHERE leads.account_id = '".$bean->parent_id."'";
                        break;
                    case "Contacts" :
                        $query =  "SELECT leads.id FROM leads WHERE leads.contact_id = '".$bean->parent_id."'";
                        break;
                    default :
                        break;
                }
                $lead = $db->fetchByAssoc($db->query($query));
                if(isset($lead['id'])){
                    $lead_bean = BeanFactory::getBean('Leads',$lead['id']);
                    if($lead_bean !== false){
                        updateCallSolargain($lead_bean->solargain_lead_number_c,$bean->name,$lead_bean->solargain_quote_number_c);
                    }
                }
               // updateCallSolargain('105072',$bean->name,'68878');
            }*/
            if(isset($bean->next_call_c) && ($bean->next_call_c != "")){
                $exclude = array(
                    'id',
                    'date_entered',
                    'date_modified'
                );
                $newbean = new $bean->object_name;
                foreach($bean->field_defs as $def){
                    if(!(isset($def['source']) && $def['source'] == 'non-db') 
                        && !empty($def['name'])
                        && !in_array($def['name'], $exclude)){
                        $field = $def['name'];
                        $newbean->{$field} = $bean->{$field};
                        // Special value
                        $newbean->date_start =  $bean->next_call_c;
                        $newbean->next_call_c = "";
                        $newbean->status = 'Planned';
                    }
                }
                $newbean->save();
                //thienpb code
                $bean->status = 'Held';
            }
        }
    }

    class UpdateRelated {
        function before_save_method_update_related($bean, $event, $arguments) {
            $old_fields = $bean->fetched_row;
            $parent_type = $bean->parent_type;
            $account_id = '';
            $db = DBManagerFactory::getInstance();

            switch ($parent_type) {
                case 'Accounts':
                    $account_id = $bean->parent_id;
                    break;
                
                default:
                    $account_id = '';
                    break;
            }

            if($account_id != '') {
                
                if($bean->parent_id != $old_fields['parent_id']) {
                    $array_remove_quote_id = [];
                    $old_quotes = $bean->get_linked_beans('calls_aos_quotes_1','AOS_Quotes');
                    $sql_delete = "UPDATE calls_aos_quotes_1_c
                    SET deleted = 1
                    WHERE calls_aos_quotes_1calls_ida = '$bean->id' AND deleted = 0";
                    $result_delete = $db->query($sql_delete);
                }

                $array_quote_id = [];
                $array_old_quote_id = [];
                $sql = "SELECT DISTINCT id
                FROM aos_quotes
                WHERE billing_account_id = '$account_id' AND deleted = 0";
                $ret = $db->query($sql);
                while($row = $ret->fetch_assoc()){
                    if ($row['id'] != '') { 
                        $array_quote_id[] = $row['id'];
                    }
                }
                $old_quotes = $bean->get_linked_beans('calls_aos_quotes_1','AOS_Quotes');
                foreach ($old_quotes as $old_quote) {
                    $array_old_quote_id[] = $old_quote->id;
                }
                $array_all_quote_id = array_unique(array_merge($array_old_quote_id,$array_quote_id),SORT_REGULAR);
                if(count($array_all_quote_id)> 0){
                    foreach ($array_all_quote_id as $quote_id) {
                        if(!in_array( $quote_id,$array_old_quote_id)){
                            $bean->set_relationship('calls_aos_quotes_1_c', array('calls_aos_quotes_1calls_ida'=>$bean->id ,'calls_aos_quotes_1aos_quotes_idb'=> $quote_id), false);   
                        } 
                    }
                } 
                
            }
            
            //dung custom code -- update related Call with Contacts When Contacts missing
            if($bean->contact_id == ''&& $parent_type == 'Accounts' && $account_id != ''){
                $account = new Account();
                $account = $account->retrieve($bean->parent_id);
                $contacts = $account->get_linked_beans('contacts','Contact');
                if(count($contacts)> 0){
                    for($i=0;$i < count($contacts);$i++){
                        if($contacts[$i]->id == $account->primary_contact_c){
                            $contact = $contacts[$i];
                            break;
                        }elseif($i == count($contacts) -1){
                            $contact = $contacts[count($contacts) -1];
                        }
                    }
                }else{
                    // get contact when we missing related contact and account
                    $db = DBManagerFactory::getInstance();
                    $query = "SELECT accounts.id AS account_id,accounts_contacts.contact_id  FROM accounts 
                                LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = accounts.id 
                                LEFT JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id 
                                LEFT JOIN accounts_contacts ON accounts_contacts.account_id = accounts.id
                                WHERE accounts.name = '".$account->name."' AND email_addresses.email_address = '".$account->email1."' AND accounts.deleted = 0 
                                ORDER BY accounts.date_entered LIMIT 0,1";
                    $ret = $db->query($query);
                    while ($row = $db->fetchByAssoc($ret)) {
                        $contactID = $row['contact_id'];
                    }
                    $contact = new Contact();
                    $contact = $contact->retrieve($contactID);
                }
                if($contact->id != ''){
                    $bean->set_relationship('calls_contacts', array('call_id'=>$bean->id ,'contact_id'=> $contact->id), false);   
                }
               
            }
        }
    }
    /**
     * Create Internal note (create new/change status,assign_user)
     */
    class CreateInternalNotesCall {
        function after_save_createdInternalNotesCall($bean, $event, $arguments) {
            global $app_list_strings;
            global $current_user;
            $call_status = $app_list_strings['call_status_dom'];
            $call_direction = $app_list_strings['call_direction_dom'];
            $old_fields = $bean->fetched_row;
            //format Date modified
            $format = 'Y-m-d H:i:s';
            $date = DateTime::createFromFormat($format, $bean->date_modified);
            // $test = DateTime::createFromFormat($format, "2020-09-28 17:51:57");
            $date_note = $date->format("d/m/Y h:ia");
            //case 1- new Call
            if ($old_fields == false) { 
                //Check Sql
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT DISTINCT pe_internal_note.id as note_id
                FROM pe_internal_note
                LEFT JOIN calls_pe_internal_note_1_c ON calls_pe_internal_note_1_c.calls_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id
                LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                LEFT JOIN calls ON calls.id = calls_pe_internal_note_1_c.calls_pe_internal_note_1calls_ida
                WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated'
                AND pe_internal_note.deleted = 0
                AND calls.id ='$bean->id'
                ";    
                $ret = $db->query($sql);
                if ($ret->num_rows == 0) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'NEW '.$call_direction[$bean->direction].' '.$call_status[$bean->status].' '.$date_note;
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('calls_pe_internal_note_1');
                    $bean_intenal_notes->calls_pe_internal_note_1->add($bean->id);
                }
            } else { //case 2 - update status Call
                if ($old_fields['status'] != $bean->status || $old_fields['direction'] != $bean->direction) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = $call_direction[$bean->direction].' '.$call_status[$bean->status].' '.$date_note;
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    $bean_intenal_notes->load_relationship('calls_pe_internal_note_1');
                    $bean_intenal_notes->calls_pe_internal_note_1->add($bean->id);
                }
                if ($old_fields['assigned_user_id'] != $bean->assigned_user_id) {
                    $bean_intenal_notes = new  pe_internal_note();
                    if ($old_fields['assigned_user_id']) {
                        $old_user = new User();
                        $old_user->retrieve($old_fields['assigned_user_id']);
                        if ($old_user->id) {
                            $old_fields['assigned_user_name'] = $old_user->name;
                        }
                    }
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = "Change Assigned User from {$old_fields['assigned_user_name']} to {$bean->assigned_user_name}";
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    $bean_intenal_notes->load_relationship('calls_pe_internal_note_1');
                    $bean_intenal_notes->calls_pe_internal_note_1->add($bean->id);
                }
            }
        }
    }
?>