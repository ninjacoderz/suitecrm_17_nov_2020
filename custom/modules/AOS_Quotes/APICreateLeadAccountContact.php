<?php
    $rq_data = $_POST;
    global $current_user;
    if(isset($rq_data['list_infomation']['first_name']) && isset($rq_data['list_infomation']['last_name'])){
       
        if($rq_data['type_form'] == 'daikin_form') {

            $first_name = $rq_data['list_infomation']['first_name'];
            $last_name = $rq_data['list_infomation']['last_name'];
            $primary_address_city = $rq_data['list_infomation']['suburb_customer'];
            $primary_address_state = $rq_data['list_infomation']['state_customer'];
            $primary_address_postalcode =  $rq_data['list_infomation']['postcode_customer'];
            $primary_address_country = 'Australia';
            $email_customer =  $rq_data['list_infomation']['email_customer'];
            $phone_number = $rq_data['list_infomation']['phone_number'];
            $your_street = $rq_data['list_infomation']['your_street'];
            $products = $rq_data['list_infomation']['products'];
            $quote_id = $rq_data['list_infomation']['quote_daikin_id'];
            $notes = $rq_data['list_infomation']['notes'];
            $wifi = $rq_data['list_infomation']['wifi'];

            if($rq_data['list_infomation']['prepared_by'] == 'Matthew Wright') {
                $assigned_user = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
            } else if($rq_data['list_infomation']['prepared_by'] == 'Paul Szuster') {
                $assigned_user = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
            } else if($rq_data['list_infomation']['prepared_by'] == 'Michael Golden') {
                $assigned_user = '71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb';
            } else if($rq_data['list_infomation']['prepared_by'] == 'PE Admin') {
                $assigned_user = '1';
            } else {
                $assigned_user = '1';
            }

            $decription_internal_notes = 'Node Description :  ';
            if($products != '') {
                $product = str_replace('"', '', $products);
                $decription_internal_notes .= '  |  List Products: '.$product;
            }
            if($wifi != '') {
                $decription_internal_notes .= '  |  </br>Wifi: '.$wifi;
            }
            if($notes != '') {
                $decription_internal_notes .= '  |  </br>Notes: '.$notes;
            }
        } 
        
        //check Lead existing
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT leads.id FROM leads INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = leads.id INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id WHERE leads.deleted = 0 AND email_addresses.email_address = '$email_customer' LIMIT 1";
        $ret = $db->query($sql);
        $row = $db->fetchByAssoc($ret);

        $existed_lead = false;
        if($ret->num_rows > 0){
            $lead =  new Lead();
            $lead->retrieve($row['id']);
            if($lead->id){
                if($email_customer == $lead->email1)
                    $existed_lead = true;
                else
                    $existed_lead = false; 
            }else{
                $existed_lead = false;
            }
        }else{
            $existed_lead = false; 
        }

        if(!$existed_lead){


            //create Lead
            $new_lead =  new Lead();
            $new_lead->first_name = $first_name;
            $new_lead->last_name = $last_name;
            $new_lead->primary_address_city = $primary_address_city;
            $new_lead->primary_address_state = $primary_address_state;
            $new_lead->primary_address_postalcode = $primary_address_postalcode;
            $new_lead->primary_address_street = $your_street;
            $new_lead->phone_mobile = $phone_number;
            $new_lead->email1 = $email_customer;
            $new_lead->description = $decription_internal_notes;
            $new_lead->lead_source = $rq_data['list_infomation']['hear_about'];
            $new_lead->lead_source_co_c = 'PureElectric';
            $new_lead->product_type_c = "^quote_type_daikin^";
            // $new_lead->status = 'Converted'; //VUT - Create status NEW with new Lead
            
            $new_lead->assigned_user_id = $assigned_user;

            $product_parse = json_decode(htmlspecialchars_decode($products), true);

            foreach($product_parse as $prod) {
                if($prod['productName'] == 'Daikin US7') {
                    $new_lead->create_daikin_quote_c = "1";
                    $new_lead->create_daikin_quote_num_c = $quote_id;
                } elseif($prod['productName'] == 'Daikin Nexura') {
                    $new_lead->create_daikin_nexura_quote_c = "1";
                    $new_lead->daikin_nexura_quote_num_c = $quote_id;
                }
            }

            // create account
            $account = new Account();
            $account->name = $first_name ." " . $last_name;
            $account->mobile_phone_c = $phone_number;
            $account->billing_address_city = $primary_address_city;
            $account->billing_address_street = $your_street;
            $account->billing_address_state = $primary_address_state;
            $account->billing_address_postalcode = $primary_address_postalcode;
            $account->billing_address_street = $your_street;
            $account->email1 = $email_customer;
            $account->assigned_user_id = $assigned_user;


            // create contact
            $contact = new Contact();
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            $contact->phone_mobile = $phone_number;
            $contact->primary_address_city = $primary_address_city;
            $contact->billing_address_street = $your_street;
            $contact->primary_address_state = $primary_address_state;
            $contact->primary_address_postalcode = $primary_address_postalcode;
            $contact->primary_address_street = $your_street;
            $contact->email1 = $email_customer;
            $contact->assigned_user_id = $assigned_user;
           

            // convert email to account  + contact
            $account->save();

            $contact->account_id = $account->id;
            $contact->save();

            $new_lead->account_id = $account->id;
            $new_lead->account_name = $account->name;
            $new_lead->contact_id = $contact->id;
            $new_lead->save();

            //get bean quote
            $quote = new AOS_Quotes();
            $quote->retrieve($quote_id);
            if($quote->id == '') {
                echo json_encode(array('msg'=>'error'));
                die();
            };
            if($rq_data['type_form'] == 'daikin_form') {
                $quote->install_address_postalcode_c =  $primary_address_postalcode;
                $quote->install_address_state_c = $primary_address_state;
                $quote->install_address_city_c = $primary_address_city;
                $quote->billing_address_postalcode = $primary_address_postalcode;
                $quote->billing_address_state = $primary_address_state;
                $quote->billing_address_city = $primary_address_city;
                $quote->name = str_replace('GUEST', $_REQUEST['list_infomation']['first_name'].' '.$_REQUEST['list_infomation']['last_name'].' '.$primary_address_city.' '.$primary_address_state, $quote->name);
                $quote->quote_upload_url_c = "https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=".$quote->id;
                $quote->the_quote_prepared_c = "daikin_quote_form";
            } else {
                $from_address = $your_street.', '.$primary_address_city.', '.$primary_address_state.', '.$primary_address_postalcode;
                $data_plumbing = json_decode(cusomFilterPlumberSandenForm($type = 'plumber', $from_address), true);
                $data_electrician = json_decode(cusomFilterPlumberSandenForm($type = 'electrician', $from_address), true);
                // Solve suggest
                $data_distance_plumbing = bubble_SortSandenForm($data_plumbing);
                $data_distance_electrician = bubble_SortSandenForm($data_electrician);
                
                $short_distance_plumbing = $data_distance_plumbing[0][3];
                $name_plumbing = $data_distance_plumbing[0][1];
                $id_plumbing = $data_distance_plumbing[0][2];
    
                $short_distance_electrician = $data_distance_electrician[0][3];
                $name_electrician = $data_distance_electrician[0][1];
                $id_electrician = $data_distance_electrician[0][2];
    
                $quote->distance_to_travel_c =  $short_distance_plumbing;
                $quote->account_id3_c =  $id_plumbing;
                $quote->plumber_new_c =  $name_plumbing;
    
                $quote->distance_to_electrician_c =  $short_distance_electrician;
                $quote->plumber_electrician_c =  $name_electrician;
                $quote->account_id2_c =  $id_electrician;
                $quote->quote_upload_url_c = "https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id=".$quote->id;
                $quote->the_quote_prepared_c = "sanden_quote_form";
            }
            
            $quote->leads_aos_quotes_1leads_ida = $new_lead->id;
            $quote->billing_contact_id = $contact->id;
            $quote->billing_account_id = $account->id;
            $quote->account_firstname_c = $first_name;
            $quote->account_lastname_c = $last_name;
            $quote->billing_address_street = $your_street;
            $quote->install_address_c = $your_street;
            $quote->description = $decription_internal_notes;
            $quote->quote_note_c = $notes;
            $quote->lead_source_c = $rq_data['list_infomation']['hear_about'];
            $quote->lead_source_co_c = 'PureElectric';
            $quote->assigned_user_id = $assigned_user;
            $quote->stage = 'New';

            $quote->save();
            //VUT-Change Lead status New -> Converted
            $save_lead = new Lead();
            $save_lead->retrieve($new_lead->id);
            $save_lead->status = 'Converted';
            $save_lead->save();
            $leads_intenal_notes = new  pe_internal_note();
            $leads_intenal_notes->type_inter_note_c = 'status_updated';
            
            $leads_intenal_notes->description =  $decription_internal_notes;
            $leads_intenal_notes->created_by = $current_user->id;
            $leads_intenal_notes->save();
            
            $leads_intenal_notes->load_relationship('leads_pe_internal_note_1');
            $leads_intenal_notes->leads_pe_internal_note_1->add($new_lead->id);

            // Quote Internal Note

            $leads_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
            $leads_intenal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
            echo  $new_lead->id;

        }else{
            $islead =  new Lead();
            $islead->retrieve($row['id']);

            if(!$islead->account_id) {
                // create account
                $account = new Account();
                $account->name = $islead->first_name ." " . $islead->last_name;
                $account->mobile_phone_c =  $islead->phone_mobile;
                $account->billing_address_city = $islead->primary_address_city;
                $account->billing_address_street = $islead->primary_address_street;
                $account->billing_address_state = $islead->primary_address_state;
                $account->billing_address_postalcode = $islead->billing_address_postalcode;
                $account->email1 = $islead->email1;
                $account->assigned_user_id = $assigned_user;
    
                $account->save();
    
            } else {
                $account = new Account();
                $account->retrieve($islead->account_id);
            }
    
            if(!$lead->contact_id) {
                $contact = new Contact();
                $contact->first_name = $islead->first_name;
                $contact->last_name = $islead->last_name;
                $contact->phone_mobile = $islead->phone_mobile;
                $contact->primary_address_city = $islead->primary_address_city;
                $contact->billing_address_street = $islead->primary_address_street;
                $contact->primary_address_state = $islead->primary_address_state;
                $contact->primary_address_postalcode = $islead->billing_address_postalcode;
                $contact->email1 = $islead->email1;
                $contact->assigned_user_id = $assigned_user;
                $contact->account_id = $account->id;
    
                $contact->save();
            } else {
                $contact = new Contact();
                $contact->retrieve($islead->contact_id);
            }
    
    
            $islead->account_id = $account->id;
            $islead->account_name = $account->name;
            $islead->contact_id = $contact->id;
            $islead->lead_source = $rq_data['list_infomation']['hear_about'];
            $dataProductType = explode(",",$islead->product_type_c);
            if(empty($dataProductType)) {
                $islead->product_type_c = "^quote_type_daikin^";
            } else {
                if (!in_array("^quote_type_daikin^", $dataProductType)) {
                    array_push($dataProductType, "^quote_type_daikin^");
                    $dataProductType = implode(",", $dataProductType);
                }
            }
            $islead->product_type_c = $dataProductType;
            
            $islead->status = 'Converted';
            // $islead->the_quote_prepared_c = "daikin_quote_form";
            $islead->description = $decription_internal_notes;

            $product_parse = json_decode(htmlspecialchars_decode($products), true);

            foreach($product_parse as $prod) {
                if($prod['productName'] == 'Daikin US7') {
                    $islead->create_daikin_quote_c = "1";
                    $islead->create_daikin_quote_num_c = $quote_id;
                } elseif($prod['productName'] == 'Daikin Nexura') {
                    $islead->create_daikin_nexura_quote_c = "1";
                    $islead->daikin_nexura_quote_num_c = $quote_id;
                }
            }

            $islead->save();
    
            //get bean quote
            $quote = new AOS_Quotes();
            $quote->retrieve($quote_id);
            if($quote->id == '') {
                echo json_encode(array('msg'=>'error'));
                die();
            };

            if($rq_data['type_form'] == 'daikin_form') {
                $quote->install_address_postalcode_c =  $primary_address_postalcode;
                $quote->install_address_state_c = $primary_address_state;
                $quote->install_address_city_c = $primary_address_city;
                $quote->billing_address_postalcode = $primary_address_postalcode;
                $quote->billing_address_state = $primary_address_state;
                $quote->billing_address_city = $primary_address_city;
                $quote->name = str_replace('GUEST', $_REQUEST['list_infomation']['first_name'].' '.$_REQUEST['list_infomation']['last_name'].' '.$primary_address_city.' '.$primary_address_state, $quote->name);
                $quote->lead_source_c = $rq_data['list_infomation']['hear_about'];
                $quote->lead_source_co_c = 'PureElectric';
                $quote->assigned_user_id = $assigned_user;
                $quote->quote_upload_url_c = "https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=".$quote->id;
                $quote->the_quote_prepared_c = "daikin_quote_form";
            } else {
                $from_address = $lead->primary_address_street.', '.$lead->primary_address_city.', '.$lead->primary_address_state.', '.$lead->primary_address_postalcode;
                $data_plumbing = json_decode(cusomFilterPlumberSandenForm($type = 'plumber', $from_address), true);
                $data_electrician = json_decode(cusomFilterPlumberSandenForm($type = 'electrician', $from_address), true);
                // Solve suggest
                $data_distance_plumbing = bubble_SortSandenForm($data_plumbing);
                $data_distance_electrician = bubble_SortSandenForm($data_electrician);
                
                $short_distance_plumbing = $data_distance_plumbing[0][3];
                $name_plumbing = $data_distance_plumbing[0][1];
                $id_plumbing = $data_distance_plumbing[0][2];
    
                $short_distance_electrician = $data_distance_electrician[0][3];
                $name_electrician = $data_distance_electrician[0][1];
                $id_electrician = $data_distance_electrician[0][2];
    
                $quote->distance_to_travel_c =  $short_distance_plumbing;
                $quote->account_id3_c =  $id_plumbing;
                $quote->plumber_new_c =  $name_plumbing;
    
                $quote->distance_to_electrician_c =  $short_distance_electrician;
                $quote->plumber_electrician_c =  $name_electrician;
                $quote->account_id2_c =  $id_electrician;
                $quote->quote_upload_url_c = "https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id=".$quote->id;
                $quote->the_quote_prepared_c = "sanden_quote_form";

            }

            $quote->leads_aos_quotes_1leads_ida = $islead->id;
            $quote->description = $decription_internal_notes;
            $quote->quote_note_c = $notes;
            $quote->billing_address_street = $your_street;
            $quote->install_address_c = $your_street;
            $quote->billing_contact_id = $islead->contact_id;
            $quote->billing_account_id = $islead->account_id;
            $quote->account_firstname_c = $contact->first_name;
            $quote->account_lastname_c = $contact->last_name;
            $quote->assigned_user_id = $assigned_user;
            $quote->stage = 'New';

            $quote->save();

            $leads_intenal_notes = new  pe_internal_note();
            $leads_intenal_notes->type_inter_note_c = 'status_updated';
            
            $leads_intenal_notes->description =  $decription_internal_notes;
            $leads_intenal_notes->created_by = $current_user->id;
            $leads_intenal_notes->save();
            
            $leads_intenal_notes->load_relationship('leads_pe_internal_note_1');
            $leads_intenal_notes->leads_pe_internal_note_1->add($row['id']);

            // Quote Internal Note

            $leads_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
            $leads_intenal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
            echo $islead->id;
        }
        
    }

    function cusomFilterPlumberSandenForm($type, $f_address) {
        $db = DBManagerFactory::getInstance();
        if( $type == "plumber"){
            $query  = "SELECT * FROM `accounts_cstm` WHERE `sanden_plumber_c` = '1'";
        }else if($type == "electrician"){
            $query  = "SELECT * FROM `accounts_cstm` WHERE `sanden_electrician_c` = '1'";
        }
        $result =  $db->query($query);
        $array_id_result = array();
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $array_id_result[$i]= $row['id_c'];
                $i++;
                } 
            }
        // $address = array();
        $infor_plumber = array();
        $from_address = $f_address;
        $key = 0 ;
        foreach ($array_id_result as $key => $value) {

            $sql  = "SELECT * FROM `accounts` WHERE id = '$value'";
            $result_acc =  $db->query($sql);
            $row_acc = $result_acc->fetch_array(MYSQLI_ASSOC);
            if($result_acc->num_rows > 0){
                $to_address = $row_acc['billing_address_street'] . "," . $row_acc['billing_address_city'] . " " . $row_acc['billing_address_state'] . " " . $row_acc['billing_address_postalcode'] ;
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$from_address."&destination=".$to_address."&key=AIzaSyDcPlmWLNUZ4tbEeisTzu_8cuuxXZrH6H4";
                $url =  str_replace(" ", "+", $url);
                $geocodeTo = file_get_contents($url);
                $geocodeTo = json_decode($geocodeTo);
                if( count($geocodeTo->routes[0]) > 0){
                    if( isset( $geocodeTo->routes[0]->legs) ){
    
                        $l_distance = floatval( str_replace(' km', '',str_replace(',','',str_replace(' km', '',$geocodeTo->routes[0]->legs[0]->distance->text) ) ) );
                        $addr_sh = $geocodeTo->routes[0]->legs[0]->end_address;
                        $infor_plumber[] = array($to_address,$row_acc['name'],$value,"distance" => $l_distance,$geocodeTo->routes[0]->legs[0]->distance->text);
                    } 
                }
            }
        }
        return json_encode($infor_plumber);
    }

    function bubble_SortSandenForm($distance_array )  
    {  
        do  
        {  
            $swapped = false;  
            for( $i = 0, $count = count( $distance_array ) - 1; $i < $count; $i++ )  
            {  
                if( $distance_array[$i]['distance'] > $distance_array[$i + 1]['distance'] )  
                {  
                    list( $distance_array[$i + 1], $distance_array[$i] ) =  
                            array( $distance_array[$i], $distance_array[$i + 1] );  
                    $swapped = true;  
                }  
            }  
        }  
        while( $swapped );  
        return $distance_array;
    }
    function ParseDataProductByDaikinQuoteForm($data) {

    }