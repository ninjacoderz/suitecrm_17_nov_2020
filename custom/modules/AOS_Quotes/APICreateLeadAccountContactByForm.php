<?php

    $rq_data = $_POST;
    
    if($rq_data['type_of_form'] == 'sanden_pure') {

        $first_name = $rq_data['first_name'];
        $last_name = $rq_data['last_name'];
        $primary_address_city = $rq_data['suburb_customer'];
        $primary_address_state = $rq_data['state_customer'];
        $primary_address_postalcode =  $rq_data['postcode_customer'];
        $primary_address_country =  "Australia";
        $email_customer =  $rq_data['your_email'];
        $phone_number = preg_replace('/[^A-Za-z0-9-]/', '', $rq_data['phone_number']);
        $your_street = $rq_data['your_street'];
        $products = $rq_data['choice_product_sanden'];
        $quote_id = $rq_data['quote_id'];

        $lead_source = "PE_Sanden_Quote_Form";
        $decription_internal_notes = 'Node Description :  ';

        if($rq_data['on_water'] != '') {
            if($rq_data['on_water'] != '') {
                $decription_internal_notes .= '  |  On Mains Water or Tank Water : '.$rq_data['on_water'];
            }
            if($rq_data['number_of_sanden'] != '') {
                $decription_internal_notes .= '  |  Number Of Sanden: '.$rq_data['number_of_sanden'];
            }
            $decription_internal_notes .= '  |  Selected Product: '.$rq_data['choice_product_sanden'];
            if($rq_data['connection_kit'] != '') {
                $decription_internal_notes .= '  |  Plumbing Quick Connection Kit: '.$rq_data['connection_kit'];
            }
            if($rq_data['plumbing_installation'] != '') {
                $decription_internal_notes .= '  |  Plumbing Installation: '.$rq_data['plumbing_installation'];
            }
            if($rq_data['electric_installation'] != '') {
                $decription_internal_notes .= '  |  Electric Installation: '.$rq_data['electric_installation'];
            }
            if($rq_data['provide_stcs'] != '') {
                $decription_internal_notes .= '  |  Would you like us to provide STCs as an upfront discount on your quote: '.$rq_data['provide_stcs'];
            }
        }
        if($rq_data['choice_type_install'] != '') {
            $decription_internal_notes .= '  |  Are you existing HWS or New Build: '.$rq_data['choice_type_install'];
        }
        if($rq_data['choice_type_product'] != '') {
            $decription_internal_notes .= '  |  You want to replacing: '.$rq_data['choice_type_product'];
            if($rq_data['choice_type_product'] == 'Electric') {
                if($rq_data['product_choice_type_electric'] != '') {
                    $decription_internal_notes .= '  |  Electric type: '.$rq_data['product_choice_type_electric'];
                }
                if($rq_data['electric_storage_located'] != '') {
                    $decription_internal_notes .= '  |  Where is your electric storage located?: '.$rq_data['electric_storage_located'];
                }
                if($rq_data['where_about_outside'] != '') {
                    $decription_internal_notes .= '  |  About outside: '.$rq_data['where_about_outside'];
                }
                if($rq_data['compressor_unit'] != '') {
                    $decription_internal_notes .= '  |  Sanden compressor unit position?: '.$rq_data['compressor_unit'];
                }
                if($rq_data['where_about_inside'] != '') {
                    $decription_internal_notes .= '  |  About inside: '.$rq_data['where_about_inside'];
                }
            } elseif($rq_data['choice_type_product'] == 'Gas') {
                if($rq_data['product_choice_type_gas'] != '') {
                    $decription_internal_notes .= '  |  Gas type: '.$rq_data['product_choice_type_gas'];
                }
                if($rq_data['gas_connection'] != '') {
                    $decription_internal_notes .= '  |  Gas instant electrical connection?: '.$rq_data['gas_connection'];
                }
            } elseif($rq_data['choice_type_product'] == 'Solar') {
                if($rq_data['product_choice_type_solar'] != '') {
                    $decription_internal_notes .= '  |  Solar type: '.$rq_data['product_choice_type_solar'];
                }
                if($rq_data['solar_boosted'] != '') {
                    $decription_internal_notes .= '  |  How is it boosted: '.$rq_data['solar_boosted'];
                }
                if($rq_data['ground_tank_boosted'] != '') {
                    $decription_internal_notes .= '  |  How is it boosted?: '.$rq_data['ground_tank_boosted'];
                }
            } elseif($rq_data['choice_type_product'] == 'Wood') {
                if($rq_data['product_choice_type_wood'] != '') {
                    $decription_internal_notes .= '  |  Wood type: '.$rq_data['product_choice_type_wood'];
                }
            } elseif($rq_data['choice_type_product'] == 'lpg') {
                if($rq_data['product_choice_type_lpg'] != '') {
                    $decription_internal_notes .= '  |  LPG type: '.$rq_data['product_choice_type_lpg'];
                }
            }
        }
        if($rq_data['new_place_choice'] != '') {
            $decription_internal_notes .= '  |  New Sanden HWS Install Location: '.$rq_data['new_place_choice'];
            if($rq_data['install_location_access'] != '') {
                $decription_internal_notes .= '  |  Install location access: '.$rq_data['install_location_access'];
            }
            if($rq_data['stair_access'] != '') {
                $decription_internal_notes .= '  |  Stairs: '.$rq_data['stair_access'];
            }
        }
        if($rq_data['alectrical_already'] != '') {
            $decription_internal_notes .= '  |  Electrical Already RCD Protected: '.$rq_data['alectrical_already'];
        }
        
        if($rq_data['hot_cold_connections'] != '') {
            $decription_internal_notes .= '  |  Hot and Cold Connections presented, externally located, single storey, paved area?: '.$rq_data['hot_cold_connections'];
            $decription_internal_notes .= '  | Distance Tank: '.$rq_data['distance_tank'].'(m)';
        }
        if($rq_data['additional_untempered'] != '') {
            $decription_internal_notes .= "  | Confirm you don't have an untampered hot water line (> 50 deg) - majority of houses answer YES to this question: ".$rq_data['additional_untempered'];
        }
        if($rq_data['connected_to_reticulated_gas'] != '') {
            $decription_internal_notes .= '  |  Are you connected to reticulated gas? (not LPG): '.$rq_data['connected_to_reticulated_gas'];
        }
        if($rq_data['located_within'] != '') {
            $decription_internal_notes .= '  |  New Sanden tank to be located within 2m of hot and cold connections: '.$rq_data['located_within'];
        }
        
        if($rq_data['hot_water_rebate'] != '') {
            $decription_internal_notes .= '  |  Solar Vic Solar Hot Water Rebate - Do you qualify?: '.$rq_data['hot_water_rebate'];
        }
        if($rq_data['is_your_replacement_urgent'] != '') {
            $decription_internal_notes .= '  |  Is Your Replacement Urgent: '.$rq_data['is_your_replacement_urgent'];
        }
        if($rq_data['hear_about'] != '') {
            $decription_internal_notes .= '  |  Where did you hear about us: '.$rq_data['hear_about'];
        }
        // if($rq_data['notes_field'] != '') {
        //     $decription_internal_notes .= '  | Any Note: '.$rq_data['notes_field'];
        // }
    }

    if($rq_data['prepared_by'] == 'Matthew Wright') {
        $assigned_user = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
    } else if($rq_data['prepared_by'] == 'Paul Szuster') {
        $assigned_user = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
    } else if($rq_data['prepared_by'] == 'Michael Golden') {
        $assigned_user = '71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb';
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
        $new_lead->product_type_c = "^quote_type_sanden^";
        // $dataProductType = explode(",",$lead->product_type_c);
        // if (!in_array("^quote_type_sanden^", $dataProductType)) {
        //     array_push($dataProductType, "^quote_type_sanden^");
        //     $dataProductType = implode(",", $dataProductType);
        // }
        // $new_lead->status = 'Converted'; //VUT status New for new Lead
        $new_lead->assigned_user_id = $assigned_user;

        if (strpos($products, 'FQV') === 0 ) {
            $new_lead->create_solar_quote_fqv_c = "1";
            $new_lead->create_solar_quote_fqv_num_c = $quote_id;
        } else {
            $new_lead->create_solar_quote_fqs_c = "1";
            $new_lead->create_solar_quote_fqs_num_c = $quote_id;
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
        $account->system_owner_type_c = $rq_data['system_owner_entity_type'];
        $account->abn_c = $rq_data['abn_number'];
        $account->entity_name_c = $rq_data['entity_name'];
        $account->entity_type_c = $rq_data['entity_type'];
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
            $quote->quote_upload_url_c = "https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=".$quote->id;
        } else {
            $from_address = $your_street.', '.$primary_address_city.', '.$primary_address_state.', '.$primary_address_postalcode;
            $data_plumbing = json_decode(cusomFilterPlumberByForm($type = 'plumber', $from_address), true);
            $data_electrician = json_decode(cusomFilterPlumberByForm($type = 'electrician', $from_address), true);
            // Solve suggest
            $data_distance_plumbing = bubble_SortByForm($data_plumbing);
            $data_distance_electrician = bubble_SortByForm($data_electrician);
            
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
        }
        
        $quote->leads_aos_quotes_1leads_ida = $new_lead->id;
        $quote->billing_contact_id = $contact->id;
        $quote->billing_account_id = $account->id;
        $quote->account_firstname_c = $first_name;
        $quote->account_lastname_c = $last_name;
        $quote->billing_address_street = $your_street;
        $quote->install_address_c = $your_street;
        $quote->description = $decription_internal_notes;
        $quote->quote_note_c = $rq_data['notes_field'];
        $quote->lead_source_c = $rq_data['hear_about'];
        $quote->lead_source_co_c = 'PureElectric';
        $quote->assigned_user_id = $assigned_user;
        $quote->the_quote_prepared_c = "sanden_quote_form";
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
        $lead =  new Lead();
        $lead->retrieve($row['id']);

        $dataProductType = explode(",",$lead->product_type_c);
        if(empty($dataProductType)) {
            $islead->product_type_c = "^quote_type_sanden^";
        } else {
            if (!in_array("^quote_type_sanden^", $dataProductType)) {
                array_push($dataProductType, "^quote_type_sanden^");
                $dataProductType = implode(",", $dataProductType);
            }
        }

        if(!$lead->account_id) {
            // create account
            $account = new Account();
            $account->name = $lead->first_name ." " . $lead->last_name;
            $account->mobile_phone_c =  $lead->phone_mobile;
            $account->billing_address_city = $lead->primary_address_city;
            $account->billing_address_street = $lead->primary_address_street;
            $account->billing_address_state = $lead->primary_address_state;
            $account->billing_address_postalcode = $lead->billing_address_postalcode;
            $account->email1 = $lead->email1;
            $account->system_owner_type_c = $rq_data['system_owner_entity_type'];
            $account->abn_c = $rq_data['abn_number'];
            $account->entity_name_c = $rq_data['entity_name'];
            $account->entity_type_c = $rq_data['entity_type'];
            $account->assigned_user_id = $assigned_user;

            $account->save();

        } else {
            $account = new Account();
            $account->retrieve($lead->account_id);
        }

        if(!$lead->contact_id) {
            $contact = new Contact();
            $contact->first_name = $lead->first_name;
            $contact->last_name = $lead->last_name;
            $contact->phone_mobile = $lead->phone_mobile;
            $contact->primary_address_city = $lead->primary_address_city;
            $contact->billing_address_street = $lead->primary_address_street;
            $contact->primary_address_state = $lead->primary_address_state;
            $contact->primary_address_postalcode = $lead->billing_address_postalcode;
            $contact->email1 = $lead->email1;
            $contact->assigned_user_id = $assigned_user;
            $contact->account_id = $account->id;

            $contact->save();
            
        } else {
            $contact = new Contact();
            $contact->retrieve($lead->contact_id);
        }


        $lead->account_id = $account->id;
        $lead->account_name = $account->name;
        $lead->contact_id = $contact->id;

        $lead->description = $decription_internal_notes;
        $lead->status = 'Converted';
        $lead->product_type_c = $dataProductType;
        $lead->lead_source = $rq_data['hear_about'];

        if (strpos($products, 'FQV') === 0 ) {
            $lead->create_solar_quote_fqv_c = "1";
            $lead->create_solar_quote_fqv_num_c = $quote_id;
        } else {
            $lead->create_solar_quote_fqs_c = "1";
            $lead->create_solar_quote_fqs_num_c = $quote_id;
        }

        $lead->save();

        //get bean quote
        $quote = new AOS_Quotes();
        $quote->retrieve($quote_id);
        if($quote->id == '') {
            echo json_encode(array('msg'=>'error'));
            die();
        };

        if($rq_data['type_form'] == 'daikin_form') {
            $quote->quote_upload_url_c = "https://pure-electric.com.au/pedaikinform-new/confirm?quote-id=".$quote->id;
        } else {
            $from_address = $lead->primary_address_street.', '.$lead->primary_address_city.', '.$lead->primary_address_state.', '.$lead->primary_address_postalcode;
            $data_plumbing = json_decode(cusomFilterPlumberByForm($type = 'plumber', $from_address), true);
            $data_electrician = json_decode(cusomFilterPlumberByForm($type = 'electrician', $from_address), true);
            // Solve suggest
            $data_distance_plumbing = bubble_SortByForm($data_plumbing);
            $data_distance_electrician = bubble_SortByForm($data_electrician);
            
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
        }

        $quote->leads_aos_quotes_1leads_ida = $lead->id;
        $quote->description = $decription_internal_notes;
        $quote->quote_note_c = $rq_data['notes_field'];
        $quote->account_firstname_c = $contact->first_name;
        $quote->account_lastname_c = $contact->last_name;
        $quote->billing_address_street = $your_street;
        $quote->billing_contact_id = $lead->contact_id;
        $quote->billing_account_id = $lead->account_id;
        $quote->billing_address_country = 'Australia';
        /// Install Address
        $quote->install_address_c = $your_street;
        $quote->install_address_city_c = $primary_address_city;
        $quote->install_address_state_c = $primary_address_state;
        $quote->install_address_postalcode_c = $primary_address_postalcode;
        $quote->install_address_country_c = 'Australia';
        $quote->assigned_user_id = $assigned_user;
        $quote->the_quote_prepared_c = "sanden_quote_form";
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
        echo $lead->id;
    }

    function cusomFilterPlumberByForm($type, $f_address) {
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

    function bubble_SortByForm($distance_array )  
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