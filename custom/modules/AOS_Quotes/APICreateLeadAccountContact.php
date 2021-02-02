<?php
    $rq_data = $_POST;
    if(isset($rq_data['firstname']) && isset($rq_data['lastname'])){
       
        if($rq_data['type_form'] == 'daikin_form') {

            $first_name = $rq_data['firstname'];
            $last_name = $rq_data['lastname'];
            $primary_address_city = $rq_data['primary_address_city'];
            $primary_address_state = $rq_data['primary_address_state'];
            $primary_address_postalcode =  $rq_data['primary_address_postalcode'];
            $primary_address_country = 'Austrailia';
            $email_customer =  $rq_data['email_customer'];
            $phone_number = $rq_data['phonenumber'];
            $your_street = $rq_data['your_street'];
            $products = $rq_data['products'];
            $quote_id = $rq_data['uid'];
            $notes = $rq_data['notes'];

        } else {
            $first_name = $rq_data['firstname'];
            $last_name = $rq_data['lastname'];
            $primary_address_city = $rq_data['primary_address_city'];
            $primary_address_state = $rq_data['primary_address_state'];
            $primary_address_postalcode =  $rq_data['primary_address_postalcode'];
            $primary_address_country =  $rq_data['primary_address_country'];
            $email_customer =  $rq_data['email_customer'];
            $phone_number = $rq_data['phonenumber'];
            $your_street = $rq_data['your_street'];
            $products = $rq_data['products'];
            $quote_id = $rq_data['uid'];
    
            //Data Note
            $are_you_have_hws = $rq_data['are_you_have_hws'];
            $type_device = $rq_data['type_device'];
            $gas_type = $rq_data['gas_type'];
            $gas_instant_electrical = $rq_data['gas_instant_electrical'];
            $electric_type = $rq_data['electric_type'];
            $electric_storage_located = $rq_data['electric_storage_located'];
            $electric_storage_outside = $rq_data['electric_storage_outside'];
            $electric_storage_inside = $rq_data['electric_storage_inside'];
            $solar_type = $rq_data['solar_type'];
            $solar_rooftank = $rq_data['solar_rooftank'];
            $solar_grouptank = $rq_data['solar_grouptank'];
            $wood_type = $rq_data['wood_type'];
            $install_location = $rq_data['install_location'];
            $product_choice = $rq_data['product_choice'];
            $quickie_type = $rq_data['quickie_type'];
            $plumbing_installation = $rq_data['plumbing_installation'];
            $electrical_installation = $rq_data['electrical_installation'];
            $hot_water_rebate = $rq_data['hot_water_rebate'];
            $reticulated_gas = $rq_data['reticulated_gas'];
    
            $sanden_compressor = $rq_data['sanden_compressor'];
            $where_install_location = $rq_data['where_install_location'];
            $install_location_access = $rq_data['install_location_access'];
            $stairs = $rq_data['stairs'];
    
            $connections_presented = $rq_data['connections_presented'];
            $additional_untempered = $rq_data['additional_untempered'];
            $notes_field = $rq_data['notes_field'];
        }
        
        if($rq_data['prepared_by'] == 'Matthew Wright') {
            $assigned_user = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
        } else if($rq_data['prepared_by'] == 'Paul Szuster') {
            $assigned_user = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
        } else if($rq_data['prepared_by'] == 'John Hooper') {
            $assigned_user = 'b33d5d2f-89fc-ce57-1df9-5e38d4d8e98d';
        } else if($rq_data['prepared_by'] == 'PE Admin') {
            $assigned_user = '1';
        } else {
            $assigned_user = '1';
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


        if($rq_data['type_form'] == 'daikin_form') {

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
            $lead_source = "PE_Daikin_Quote_Form";
        } else {
            $lead_source = "PE_Sanden_Quote_Form";
            $decription_internal_notes = 'Node Description :  ';

            if($are_you_have_hws != '') {
                $decription_internal_notes .= '  |  Are you existing HWS or New Build: '.$are_you_have_hws;
            }
            if($type_device != '') {
                $decription_internal_notes .= '  |  </br>You want to replacing: '.$type_device;
            }
            if($gas_type != '') {
                $decription_internal_notes .= '  |  </br>Gas Type: '.$gas_type;
            }
            if($gas_instant_electrical != '') {
                $decription_internal_notes .= '  |  </br>Gas instant electrical connection: '.$gas_instant_electrical;
            }
            if($electric_type != '') {
                $decription_internal_notes .= '  |  </br>Electric Type: '.$electric_type;
            }
            if($electric_storage_located != '') {
                $decription_internal_notes .= '  |  </br>Where is your electric storage located: '.$electric_storage_located;
            }
            if($electric_storage_outside != '') {
                $decription_internal_notes .= '  |  </br>Where about outside: '.$electric_storage_outside;
            }
            if($electric_storage_inside != '') {
                $decription_internal_notes .= '  |  </br>Where about inside: '.$electric_storage_inside;
            }
            if($solar_type != '') {
                $decription_internal_notes .= '  |  </br>Solar Type: '.$solar_type;
            }
            if($solar_rooftank != '') {
                $decription_internal_notes .= '  |  </br>How is it boosted: '.$solar_rooftank;
            }
            if($solar_grouptank != '') {
                $decription_internal_notes .= '  |  </br>How is it boosted: '.$solar_grouptank;
            }
            if($wood_type != '') {
                $decription_internal_notes .= '  |  </br>Wood type: '.$wood_type;
            }
            if($install_location != '') {
                $decription_internal_notes .= '  |  </br>Install Location: '.$install_location ;
            }
            if($product_choice != '') {
                $decription_internal_notes .= '  |  </br>Product Choice: '.$product_choice;
            }
            if($quickie_type != '') {
                $decription_internal_notes .= '  |  </br>Quickie Type: '.$quickie_type;
            }
            if($plumbing_installation != '') {
                $decription_internal_notes .= '  |  </br>Plumbing Installation: '.$plumbing_installation;
            }
            if($electrical_installation != '') {
                $decription_internal_notes .= '  |  </br>Electrical Installation: '.$electrical_installation;
            }
            if($hot_water_rebate != '') {
                $decription_internal_notes .= '  |  </br>Solar Vic Solar Hot Water Rebate - Do you qualify: '.$hot_water_rebate;
            }
            if($reticulated_gas != '') {
                $decription_internal_notes .= '  |  </br>Are you connected to reticulated gas: '.$reticulated_gas;
            }
            if($where_install_location != '') {
                $decription_internal_notes .= '  |  </br>New Sanden HWS Install Location: '.$where_install_location;
            }
            if($sanden_compressor != '') {
                $decription_internal_notes .= '  |  </br>Sanden compressor unit position: '.$sanden_compressor;
            }
            if($install_location_access != '') {
                $decription_internal_notes .= '  |  </br>Install Location Access: '.$install_location_access;
            }
            if($stairs != '') {
                $decription_internal_notes .= '  |  </br>Stairs: '.$stairs;
            }
            //
            if($connections_presented != '') {
                $decription_internal_notes .= '  |  </br>Hot and Cold Connections presented, externally located, single storey, paved area: '.$connections_presented;
            }
            if($additional_untempered != '') {
                $decription_internal_notes .= '  |  </br>Additional untempered: '.$additional_untempered;
            }
            if($notes_field != '') {
                $decription_internal_notes .= '  |  </br>Notes Field: '.$notes_field;
            }
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
            $new_lead->lead_source = $rq_data['hear_about'];
            $new_lead->lead_source_co_c = 'PureElectric';
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
                $quote->name = str_replace('GUEST', $_REQUEST['firstname'].' '.$_REQUEST['lastname'].' '.$primary_address_city.' '.$primary_address_state, $quote->name);
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
            $quote->lead_source_c = $rq_data['hear_about'];
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
            $islead->lead_source = $rq_data['hear_about'];
    
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
                $quote->name = str_replace('GUEST', $_REQUEST['firstname'].' '.$_REQUEST['lastname'].' '.$primary_address_city.' '.$primary_address_state, $quote->name);
                $quote->lead_source_c = $rq_data['hear_about'];
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