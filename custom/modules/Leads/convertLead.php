<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    class convertLead {
        function convertLead($bean, $event, $arguments){
            $condition_check = 
            //($bean->status !== 'Converted')&& ($bean->converted !== '1') 
            ($_REQUEST['create_opportunity_c'] == '1' && $bean->create_opportunity_number_c =='') 
            || ($_REQUEST['create_solar_c'] == '1' && $bean->create_solar_number_c == '')
            || ($_REQUEST['create_sanden_c'] == '1' && $bean->create_sanden_number_c == '')
            || ($_REQUEST['create_daikin_c'] == '1' && $bean->create_daikin_number_c =='')
            || ($_REQUEST['create_methven_c'] == '1' && $bean->create_methven_number_c =='')
            || ($_REQUEST['create_sanden_quote_c'] == '1' &&  $bean->create_sanden_quote_num_c == '')
            || ($_REQUEST['create_daikin_quote_c'] == '1' && $bean->create_daikin_quote_num_c == '')
            || ($_REQUEST['create_methven_quote_c'] == '1' && $bean->create_methven_quote_num_c == '')
            || ($_REQUEST['create_solar_quote_c'] == '1' && $bean->create_solar_quote_num_c == '')
            || ($_REQUEST['create_tesla_quote_c'] == '1' && $bean->create_tesla_quote_num_c == '')
            || ($_REQUEST['create_solar_quote_fqs_c'] == '1' && $bean->create_solar_quote_fqs_num_c == '')
            || ($_REQUEST['create_daikin_nexura_quote_c'] == '1' && $bean->daikin_nexura_quote_num_c == '')
            || ($_REQUEST['create_off_grid_quote_c'] == '1' && $bean->create_off_grid_button_num_c == '')
            || ($_REQUEST['create_solar_quote_fqv_c'] == '1' && $bean->create_solar_quote_fqv_num_c == '')
            || ($_REQUEST['create_service_case_c'] == '1' && $bean->service_case_number_c == '');

            
            
            $part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19','431a9064-7cbb-6a44-e7ba-5d5b794137c7','cbfafe6b-5e84-d976-8e32-574fc106b13f'];
            $json_open_new_tag = $bean->open_new_tag_c;
            if($json_open_new_tag == '') {
                $json_open_new_tag = array(
                    'create_opportunity_number_c' => '0',
                    'create_solar_number_c' => '0',
                    'create_methven_number_c' => '0',
                    'create_sanden_number_c' => '0',
                    'create_daikin_number_c' => '0',
                    'create_sanden_quote_num_c' => '0',
                    'create_daikin_quote_num_c' => '0',
                    'create_methven_quote_num_c' => '0',
                    'create_solar_quote_num_c' => '0',
                    'create_tesla_quote_num_c' => '0',
                    'create_solar_quote_fqs_num_c' => '0',
                    'create_solar_quote_fqv_num_c' => '0',
                    'daikin_nexura_quote_num_c' => '0',
                    'create_off_grid_button_num_c' => '0',
                    'service_case_number_c' => '0',
                                    );
            }else {
                $json_open_new_tag = json_decode ($json_open_new_tag);
            }
            
            if($condition_check ) {
                $id_lead = $bean->id;
                $db = DBManagerFactory::getInstance();               

                // create contact
                if($bean->contact_id == '') {
                    $contact = new Contact();
                    $contact->salutation = $bean->salutation;
                    $contact->first_name = $bean->first_name;
                    $contact->last_name = $bean->last_name;
                    $contact->phone_work = $bean->phone_work;
                    $contact->phone_mobile = $bean->phone_mobile;
                    $contact->department = $bean->department;
                    $contact->phone_fax = $bean->phone_fax;
                    $contact->primary_address_street = $bean->primary_address_street;
                    $contact->primary_address_city = $bean->primary_address_city;
                    $contact->primary_address_state = $bean->primary_address_state;
                    $contact->primary_address_postalcode = $bean->primary_address_postalcode;
                    $contact->primary_address_country = $bean->primary_address_country;
                    $contact->assigned_user_name = $bean->assigned_user_name;
                    $contact->assigned_user_id = $bean->assigned_user_id;
                    $contact->save();
                    $bean->contact_id = $contact->id;
                }else{
                    $contact = new Contact();
                    $contact->retrieve($bean->contact_id);
                }

                // create account 
                if($bean->account_id == '') {
                    $account = new Account();
                    $account->name = $bean->first_name ." " . $bean->last_name;
                    $account->phone_office = $bean->phone_office;
                    $account->mobile_phone_c = $bean->phone_mobile;
                    $account->phone_fax = $bean->phone_fax;
                    $account->website = $bean->website;
                    $account->billing_address_street = $bean->primary_address_street;
                    $account->billing_address_city = $bean->primary_address_city;
                    $account->billing_address_state = $bean->primary_address_state;
                    $account->billing_address_postalcode = $bean->primary_address_postalcode;
                    $account->billing_address_country = $bean->primary_address_country;
                    $account->assigned_user_name = $bean->assigned_user_name;
                    $account->assigned_user_id = $bean->assigned_user_id;
                    $account->save();
                    $bean->account_id = $account->id;
                }else{
                    $account = new Account();
                    $account->retrieve($bean->account_id);
                }
                // convert email to account  + contact
                $contact->email1 = $bean->email1;
                $account->email1 = $bean->email1;
                $contact->account_id = $bean->account_id;
                $contact->account_name = $account->name;
                $account->primary_contact_c = $contact->id;
                $contact->lead_source_co_c = $bean->lead_source_co_c;
                $account->lead_source_co_c = $bean->lead_source_co_c;
                $contact->save();
                $account->save();
                date_default_timezone_set('UTC');
                $dateAction = new DateTime('+7 day');
                $dateQuote = new DateTime();


                //create Sanden Quote        

                if($_REQUEST['create_sanden_quote_c'] == '1' &&  $bean->create_sanden_quote_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Sanden';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_sanden';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);

                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Sanden';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();

                    //product sanden
                    $product_qty_315 = 0;
                    $product_qty_250 = 0;
                    $product_qty_160 = 0;
                    $part_numners = ['Sanden_Plb_Install_Std','Sanden_Elec_Install_Std','STC Rebate Certificate','QIK15−HPUMP'];
                    if($_REQUEST['sanden_315'] !== ''){
                        array_push($part_numners, 'GAUS-315EQTAQ');   
                        $product_qty_315 = (int)$_REQUEST['sanden_315'];
                    }
                    if($_REQUEST['sanden_250'] !== ''){
                        array_push($part_numners, 'GAUS-250EQTAQ');   
                        $product_qty_250 = (int)$_REQUEST['sanden_250'];
                    }
                    if($_REQUEST['sanden_160'] !== ''){
                        array_push($part_numners, 'GAUS-160EQTAQ');   
                        $product_qty_160 = (int)$_REQUEST['sanden_160'];
                    }
                    
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();
                
                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $total_amount = 0;
                    $index = 1;
                    $is_use_number_1 = false;


                    while ($row = $db->fetchByAssoc($ret))
                    {   
                        if($row['id'] !== '68c375f4-5faa-167d-7bf1-580da8cffc76' && $row['id'] !== '742aed09-2694-fdad-d879-580da9bbf3a3') {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';
                            //display number index 
                            if(($row['id'] =='2e3e02ab-596c-aa4d-ec75-59dae3a11c63'
                            || $row['id'] =='a7be5deb-9a6d-d459-7856-58fd98483620' 
                            || $row['id'] =='7997a97c-6ab4-7151-a44e-58fd992e4b3c')&& !$is_use_number_1) {
                                $product_line->number = 1;
                                $is_use_number_1 = true;
                            }else {
                                $index ++;
                                $product_line->number = $index;
                            }
                            //logic total amount 
                            if($_REQUEST['sanden_315'] !== '' && $row['id'] =='2e3e02ab-596c-aa4d-ec75-59dae3a11c63'){
                                $product_line->product_qty = $product_qty_315; 
                                $product_line->product_total_price = $row['price']*$product_qty_315;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_315,2);                                                
                                $product_line->save();
                            }
                            else if($_REQUEST['sanden_250'] !== '' && $row['id'] =='a7be5deb-9a6d-d459-7856-58fd98483620'){
                                $product_line->product_qty = $product_qty_250; 
                                $product_line->product_total_price =$row['price']*$product_qty_250;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_250,2);                            
                                $product_line->save();
                            }
                            else if($_REQUEST['sanden_160'] !== '' && $row['id'] =='7997a97c-6ab4-7151-a44e-58fd992e4b3c'){
                                $product_line->product_qty = $product_qty_160; 
                                $product_line->product_total_price = $row['price']*$product_qty_160;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_160,2);                                               
                                $product_line->save();
                            }else {
                                $product_line->product_qty = 1;
                                $product_line->product_total_price = $row['price'];
                                if(in_array($row['id'],$part_numbers_tax_0)){  
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;   
                                }else {
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1,2);   
                                }
                                
                                $product_line->save();
                            }
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
                    
                    $discount_amount =0;
                    $total_amount = $total_amt + $tax_amount;
                    $subtotal_amount= $total_amt;

                    $quote->total_amt = round($total_amt , 2);
                    $quote->subtotal_amount = round($subtotal_amount , 2);
                    $quote->discount_amount = round($discount_amount , 2);
                    $quote->tax_amount = round($tax_amount , 2);
                    $quote->total_amount = round($total_amount , 2);
                   // $quote->opportunity_id = $bean->create_sanden_number_c; 
                    $quote->save();

                    $product_quote_group->tax_amount = round($tax_amount , 2);
                    $product_quote_group->total_amount = round($total_amount , 2);
                    $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                    $product_quote_group->save();

                    $bean->create_sanden_quote_num_c =  $quote->id;
                    $json_open_new_tag['create_sanden_quote_num_c'] ='1';     

                    if($bean->check_email_sanden_quote_c == '' && $_REQUEST['sent_email_sanden_c'] == '1') {
                        auto_send_email_convert_lead($bean->id, '7c189f2f-19a9-c2c1-23fa-59f922602067');
                    }  
                    
                    
                }

                //create Sanden Quote FQS     
                if($_REQUEST['create_solar_quote_fqs_c'] == '1' &&  $bean->create_solar_quote_fqs_num_c == ''){
                    $quote = new AOS_Quotes();
                    if($_REQUEST['sanden_fqs_300'] !== ''){
                        $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Sanden 300FQS';
                    }
                    if($_REQUEST['sanden_fqs_250'] !== ''){
                        $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Sanden 250FQS';
                    }
                    if($_REQUEST['sanden_fqs_315'] !== ''){
                        $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ")  .' Sanden 315FQS';
                    }
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_sanden';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);

                    // Tri Truong Add Auto Get Distance 
                    $from_address = $bean->primary_address_street.', '.$bean->primary_address_city.', '.$bean->primary_address_state.', '.$bean->primary_address_postalcode;
                    $data_plumbing = json_decode(cusomFilterPlumberConvertLead($type = 'plumber', $from_address), true);
                    $data_electrician = json_decode(cusomFilterPlumberConvertLead($type = 'electrician', $from_address), true);
                    // Solve suggest
                    $data_distance_plumbing = bubble_SortConvertLead($data_plumbing);
                    $data_distance_electrician = bubble_SortConvertLead($data_electrician);
                    
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
                    // End

                    $quote->save();

                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Sanden';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();

                    //product sanden
                    $product_qty_315 = 0;
                    $product_qty_250 = 0;
                    $product_qty_160 = 0;
                    $part_numners = ['STC Rebate Certificate','QIK15−HPUMP'];
                    if($_REQUEST['check_box_veec'] == '1') {
                        array_push($part_numners,'VEEC Rebate Certificate');
                    }
                    if($_REQUEST['check_box_supply_only_fqs'] != '1') {
                        array_push($part_numners,'Sanden_Plb_Install_Std');
                        array_push($part_numners,'Sanden_Elec_Install_Std');
                    }
                    if($_REQUEST['sanden_fqs_300'] !== ''){
                        // comment because we haven't this product
                        array_push($part_numners, 'GAUS-300FQS');   
                        $product_qty_300 = (int)$_REQUEST['sanden_fqs_300'];
                    }
                    if($_REQUEST['sanden_fqs_250'] !== ''){
                        array_push($part_numners, 'GAUS-250FQS');   
                        $product_qty_250 = (int)$_REQUEST['sanden_fqs_250'];
                    }
                    if($_REQUEST['sanden_fqs_315'] !== ''){
                        array_push($part_numners, 'GAUS-315FQS');   
                        $product_qty_315 = (int)$_REQUEST['sanden_fqs_315'];
                    }
                    
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();
                
                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $total_amount = 0;
                    $index = 1;
                    $is_use_number_1 = false;


                    while ($row = $db->fetchByAssoc($ret))
                    {   
                        if($row['id'] !== '68c375f4-5faa-167d-7bf1-580da8cffc76' && $row['id'] !== '742aed09-2694-fdad-d879-580da9bbf3a3') {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';
                            //display number index 
                            if(($row['id'] =='def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'
                            || $row['id'] =='67605168-6b72-5504-282c-5cc8e1492ec9' 
                            || $row['id'] =='335cc359-a2e9-a2a0-3b94-5cb015b32f1b' )&& !$is_use_number_1) {
                                $product_line->number = 1;
                                $is_use_number_1 = true;
                            }else {
                                $index ++;
                                $product_line->number = $index;
                            }
                            //logic total amount 
                            if($_REQUEST['sanden_fqs_315'] !== '' && $row['id'] =='def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'){
                                $product_line->product_qty = $product_qty_315; 
                                $product_line->product_total_price = $row['price']*$product_qty_315;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_315,2);                                                
                                $product_line->save();
                            }
                            else if($_REQUEST['sanden_fqs_250'] !== '' && $row['id'] =='67605168-6b72-5504-282c-5cc8e1492ec9'){
                                $product_line->product_qty = $product_qty_250; 
                                $product_line->product_total_price =$row['price']*$product_qty_250;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_250,2);                            
                                $product_line->save();
                            }
                            //7997a97c-6ab4-7151-a44e-58fd992e4b3c of sanden_fqs_160
                            else if($_REQUEST['sanden_fqs_300'] !== '' && $row['id'] =='335cc359-a2e9-a2a0-3b94-5cb015b32f1b'){
                                // comment because we haven't this product
                                $product_line->product_qty = $product_qty_300; 
                                $product_line->product_total_price = $row['price']*$product_qty_300;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_300,2);                                               
                                $product_line->save();
                            }else {
                                $product_line->product_qty = 1;
                                $product_line->product_total_price = $row['price'];
                                if(in_array($row['id'],$part_numbers_tax_0)){  
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;   
                                }else {
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1,2);   
                                }
                                
                                $product_line->save();
                            }
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
                    
                    $discount_amount =0;
                    $total_amount = $total_amt + $tax_amount;
                    $subtotal_amount= $total_amt;

                    $quote->total_amt = round($total_amt , 2);
                    $quote->subtotal_amount = round($subtotal_amount , 2);
                    $quote->discount_amount = round($discount_amount , 2);
                    $quote->tax_amount = round($tax_amount , 2);
                    $quote->total_amount = round($total_amount , 2);
                    //$quote->opportunity_id = $bean->create_sanden_number_c; 
                    $quote->save();

                    $product_quote_group->tax_amount = round($tax_amount , 2);
                    $product_quote_group->total_amount = round($total_amount , 2);
                    $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                    $product_quote_group->save();

                    $bean->create_solar_quote_fqs_num_c =  $quote->id;
                    $json_open_new_tag['create_solar_quote_fqs_num_c'] ='1';     

                    if($_REQUEST['sent_email_sanden_fqs_c'] == '1') {
                        auto_send_email_convert_lead($bean->id, 'dbf622ae-bb45-cb79-eb97-5cd287c48ac3');
                    }  
                    
                    
                }

                //create Sanden Quote FQV     
                if($_REQUEST['create_solar_quote_fqv_c'] == '1' &&  $bean->create_solar_quote_fqv_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Sanden FQV';
                    $quote->quote_type_c = 'quote_type_sanden';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);
                    $quote->save();

                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Sanden';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();

                    //product sanden
                    $product_qty_315 = 0;
                    $product_qty_250 = 0;
                    $product_qty_160 = 0;
                    $part_numners = ['STC Rebate Certificate','QIK15−HPUMP'];
                    if($_REQUEST['check_box_supply_only_fqv'] != '1') {
                        array_push($part_numners,'Sanden_Plb_Install_Std');
                        array_push($part_numners,'Sanden_Elec_Install_Std');
                    }
                    if($_REQUEST['sanden_fqv_160'] !== ''){
                        // comment because we haven't this product
                        // array_push($part_numners, 'GAUS-160FQS');   
                        // $product_qty_160 = (int)$_REQUEST['sanden_fqv_160'];
                    }
                    if($_REQUEST['sanden_fqv_250'] !== ''){
                        // comment because we haven't this product
                        // array_push($part_numners, 'GAUS-250FQV');   
                        // $product_qty_250 = (int)$_REQUEST['sanden_fqv_250'];
                    }
                    if($_REQUEST['sanden_fqv_315'] !== ''){
                        array_push($part_numners, 'GAUS-315FQV');   
                        $product_qty_315 = (int)$_REQUEST['sanden_fqv_315'];
                    }
                    
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();
                
                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $total_amount = 0;
                    $index = 1;
                    $is_use_number_1 = false;


                    while ($row = $db->fetchByAssoc($ret))
                    {   
                        if($row['id'] !== '68c375f4-5faa-167d-7bf1-580da8cffc76' && $row['id'] !== '742aed09-2694-fdad-d879-580da9bbf3a3') {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';
                            //display number index 
                            if(($row['id'] =='def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'
                            )&& !$is_use_number_1) {
                                $product_line->number = 1;
                                $is_use_number_1 = true;
                            }else {
                                $index ++;
                                $product_line->number = $index;
                            }
                            //logic total amount 
                            if($_REQUEST['sanden_fqv_315'] !== '' && $row['id'] =='def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'){
                                $product_line->product_qty = $product_qty_315; 
                                $product_line->product_total_price = $row['price']*$product_qty_315;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_315,2);                                                
                                $product_line->save();
                            }
                            else if($_REQUEST['sanden_fqv_250'] !== '' && $row['id'] =='67605168-6b72-5504-282c-5cc8e1492ec9'){
                                // comment because we haven't this product
                                // $product_line->product_qty = $product_qty_250; 
                                // $product_line->product_total_price =$row['price']*$product_qty_250;
                                // $product_line->vat = '10.0';
                                // $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_250,2);                            
                                // $product_line->save();
                            }
                            else if($_REQUEST['sanden_fqv_160'] !== '' && $row['id'] =='7997a97c-6ab4-7151-a44e-58fd992e4b3c'){
                                // comment because we haven't this product
                                // $product_line->product_qty = $product_qty_160; 
                                // $product_line->product_total_price = $row['price']*$product_qty_160;
                                // $product_line->vat = '10.0';
                                // $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_160,2);                                               
                                // $product_line->save();
                            }else {
                                $product_line->product_qty = 1;
                                $product_line->product_total_price = $row['price'];
                                if(in_array($row['id'],$part_numbers_tax_0)){  
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;   
                                }else {
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1,2);   
                                }
                                
                                $product_line->save();
                            }
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
                    
                    $discount_amount =0;
                    $total_amount = $total_amt + $tax_amount;
                    $subtotal_amount= $total_amt;

                    $quote->total_amt = round($total_amt , 2);
                    $quote->subtotal_amount = round($subtotal_amount , 2);
                    $quote->discount_amount = round($discount_amount , 2);
                    $quote->tax_amount = round($tax_amount , 2);
                    $quote->total_amount = round($total_amount , 2);
                    //$quote->opportunity_id = $bean->create_sanden_number_c; 
                    $quote->save();

                    $product_quote_group->tax_amount = round($tax_amount , 2);
                    $product_quote_group->total_amount = round($total_amount , 2);
                    $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                    $product_quote_group->save();

                    $bean->create_solar_quote_fqv_num_c =  $quote->id;
                    $json_open_new_tag['create_solar_quote_fqv_num_c'] ='1';     

                    if($_REQUEST['sent_email_sanden_fqv_c'] == '1') {
                        auto_send_email_convert_lead($bean->id, 'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5');
                    }  
                    
                    
                }

                //create Daikin Quote
                if($_REQUEST['create_daikin_quote_c'] == '1' && $bean->create_daikin_quote_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Daikin';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_daikin';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);

                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Daikin';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();
                    //product daikin
                    $product_qty_25 = 0;
                    $product_qty_35 = 0;
                    $product_qty_50 = 0;
                    $part_numners = ['DAIKIN_MEL_METRO_DELIVERY','STANDARD_AC_INSTALL','DSI'];
                    if($_REQUEST['daikin_25_pro'] !== ''){
                        array_push($part_numners, 'FTXZ25N');   
                        $product_qty_25 = (int)$_REQUEST['daikin_25_pro'];
                    }
                    if($_REQUEST['daikin_35_pro'] !== ''){
                        array_push($part_numners, 'FTXZ35N');   
                        $product_qty_35 = (int)$_REQUEST['daikin_35_pro'];
                    }
                    if($_REQUEST['daikin_50_pro'] !== ''){
                        array_push($part_numners, 'FTXZ50N');   
                        $product_qty_50 = (int)$_REQUEST['daikin_50_pro'];
                    }
                        
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();
                
                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $index = 2;
                    $is_use_number_1 = false;
                    $is_use_number_2 = false;
                    while ($row = $db->fetchByAssoc($ret))
                    {
                        $check_install_address_postalcode_VEEC = (int)$quote->install_address_postalcode_c;
                        if($check_install_address_postalcode_VEEC < 3000 && $row['id'] == 'cbfafe6b-5e84-d976-8e32-574fc106b13f') {
                            
                        }else {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';
                            //display number index 
                            if(($row['id'] =='3518d3a1-7c11-77c5-b9db-5694fed992e6'
                            || $row['id'] =='571aa1b6-9abe-80ec-5cdd-56b4536a29d0' 
                            || $row['id'] =='ef81036f-9889-234d-02e5-57b2c0c71e79')&& !$is_use_number_2) {
                                $product_line->number = 2;
                                $is_use_number_2 = true;
                            }elseif($row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'&& !$is_use_number_1 ){
                                $product_line->number = 1;
                                $is_use_number_1 = true;
                            }
                            else {
                                $index ++;
                                $product_line->number = $index;
                            }
                            //logic total amount 
                            if($_REQUEST['daikin_25_pro'] !== '' && $row['id'] =='3518d3a1-7c11-77c5-b9db-5694fed992e6'){
                                $product_line->product_qty = $product_qty_25; 
                                $product_line->product_total_price = $row['price']*$product_qty_25;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_25,2);                                                
                                $product_line->save();
                            }
                            else if($_REQUEST['daikin_35_pro'] !== '' && $row['id'] =='571aa1b6-9abe-80ec-5cdd-56b4536a29d0'){
                                $product_line->product_qty = $product_qty_35; 
                                $product_line->product_total_price =$row['price']*$product_qty_35;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_35,2);                            
                                $product_line->save();
                            }
                            else if($_REQUEST['daikin_50_pro'] !== '' && $row['id'] =='ef81036f-9889-234d-02e5-57b2c0c71e79'){
                                $product_line->product_qty = $product_qty_50; 
                                $product_line->product_total_price = $row['price']*$product_qty_50;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_50,2);                                               
                                $product_line->save();
                            }
                            //custom code for products "Daikin Supply and Installation" 
                                //case 1 : US7 3.5 fully installed = $3590 Fully Installed
                            else if($_REQUEST['daikin_35_pro'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 1010.00;
                                $product_line->product_total_price = 1010.00;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 1010.00;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }
                                 //case 2 : Grand Total US7 2.5 fully installed = $3190
                            elseif($_REQUEST['daikin_25_pro'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 993.16;
                                $product_line->product_total_price = 993.16;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 993.16;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }
                                //case 3 : US7 5 fully installed = $4590 fully installed GRAND TOTAL
                            else if($_REQUEST['daikin_50_pro'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 763.64;
                                $product_line->product_total_price = 763.64;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 763.64;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }
                            
                            else {
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = $row['price'];
                                $product_line->product_total_price = $row['price'];
                                if($row['id'] == 'cbfafe6b-5e84-d976-8e32-574fc106b13f'){
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;   
                                }else {
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1,2);   
                                }
                                
                                $product_line->save();
                            }
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
                    
                    $discount_amount =0;
                    $total_amount = $total_amt + $tax_amount;
                    $subtotal_amount= $total_amt;

                    $quote->total_amt = round($total_amt , 2);
                    $quote->subtotal_amount = round($subtotal_amount , 2);
                    $quote->discount_amount = round($discount_amount , 2);
                    $quote->tax_amount = round($tax_amount , 2);
                    $quote->total_amount = round($total_amount , 2);
                    //$quote->opportunity_id = $bean->create_daikin_number_c; 
                    $quote->save();

                    $product_quote_group->tax_amount = round($tax_amount , 2);
                    $product_quote_group->total_amount = round($total_amount , 2);
                    $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                    $product_quote_group->save();
                    //value update Leads after convert Leads
                    $bean->create_daikin_quote_num_c =  $quote->id;
                    $json_open_new_tag['create_daikin_quote_num_c'] ='1';

                    if($bean->check_email_daikin_quote_c == '' && $_REQUEST['sent_email_daikin_quote_c'] == '1') {
                        auto_send_email_convert_lead($bean->id, '8d9e9b2c-e05f-deda-c83a-59f97f10d06a');
                    }                 
                    
                }
        
                //create Daikin Nexura Quote
                if($_REQUEST['create_daikin_nexura_quote_c'] == '1' && $bean->daikin_nexura_quote_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ")  .' Daikin Nexura';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_nexura';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);

                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Daikin Nexura';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();
                    //product daikin Nexura
                    $product_qty_25 = 0;
                    $product_qty_35 = 0;
                    $product_qty_48 = 0;
                    $part_numners = ['STANDARD_AC_INSTALL','DAIKIN_MEL_METRO_DELIVERY'];
                    if($_REQUEST['daikin_nexura_25'] !== ''){
                        array_push($part_numners, 'FVXG25K2V1B');   
                        $product_qty_25 = (int)$_REQUEST['daikin_nexura_25'];
                    }
                    if($_REQUEST['daikin_nexura_35'] !== ''){
                        array_push($part_numners, 'FVXG35K2V1B');   
                        $product_qty_35 = (int)$_REQUEST['daikin_nexura_35'];
                    }
                    if($_REQUEST['daikin_nexura_48'] !== ''){
                        array_push($part_numners, 'FVXG50K2V1B');   
                        $product_qty_50 = (int)$_REQUEST['daikin_nexura_48'];
                    }
                        
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();
                
                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $index = 1;
                    $is_use_number_1 = false;

                    while ($row = $db->fetchByAssoc($ret))
                    {
                        $check_install_address_postalcode_VEEC = (int)$quote->install_address_postalcode_c;
                        if($check_install_address_postalcode_VEEC < 3000 && $row['id'] == 'cbfafe6b-5e84-d976-8e32-574fc106b13f') {
                            
                        }else {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';
                            //display number index
                            if(($row['id'] =='f1d13f20-8ac4-7998-f60e-595360b739a8'
                            || $row['id'] =='ddd3e2ba-6fac-9b75-cda5-59535d50cac6' 
                            || $row['id'] =='687c3167-094a-bb17-40d9-59535f5cf7f4')&& !$is_use_number_2) {
                                $product_line->number = 2;
                                $is_use_number_2 = true;
                            }elseif($row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'&& !$is_use_number_1 ){//a0aba065-a6cd-6a18-1066-5de87c18a3e2 Daikin Supply and Installation
                                $product_line->number = 1;
                                $is_use_number_1 = true;
                            }else {
                                $index ++;
                                $product_line->number = $index;
                            }
                            //logic total amount 
                            if($_REQUEST['daikin_nexura_25'] !== '' && $row['id'] =='ddd3e2ba-6fac-9b75-cda5-59535d50cac6'){
                                $product_line->product_qty = $product_qty_25; 
                                $product_line->product_total_price = $row['price']*$product_qty_25;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_25,2);                                                
                                $product_line->save();
                            }
                            else if($_REQUEST['daikin_nexura_35'] !== '' && $row['id'] =='687c3167-094a-bb17-40d9-59535f5cf7f4'){
                                $product_line->product_qty = $product_qty_35; 
                                $product_line->product_total_price =$row['price']*$product_qty_35;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_35,2);                            
                                $product_line->save();
                            }
                            else if($_REQUEST['daikin_nexura_48'] !== '' && $row['id'] =='f1d13f20-8ac4-7998-f60e-595360b739a8'){
                                $product_line->product_qty = $product_qty_50; 
                                $product_line->product_total_price = $row['price']*$product_qty_50;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_50,2);                                               
                                $product_line->save();
                            }else if($_REQUEST['daikin_nexura_48'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 763.64;
                                $product_line->product_total_price = 763.64;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 763.64;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }else if($_REQUEST['daikin_nexura_35'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 1010.00;
                                $product_line->product_total_price = 1010.00;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 1010.00;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }else if($_REQUEST['daikin_nexura_25'] !== '' && $row['id'] =='79656166-ca6d-2715-2788-5d7ec2db2ce2'){
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = 993.16;
                                $product_line->product_total_price = 993.16;
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_list_price = 993.16;
                                $product_line->vat = '10.0';
                                $product_line->vat_amt = round( $product_line->product_total_price * 0.1,2);                                               
                                $product_line->save();
                            }else {
                                $product_line->product_qty = 1;
                                $product_line->product_unit_price = $row['price'];
                                $product_line->product_total_price = $row['price'];
                                if($row['id'] == 'cbfafe6b-5e84-d976-8e32-574fc106b13f'){
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;   
                                }else {
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1,2);   
                                }
                                
                                $product_line->save();
                            }
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
                    
                    $discount_amount =0;
                    $total_amount = $total_amt + $tax_amount;
                    $subtotal_amount= $total_amt;

                    $quote->total_amt = round($total_amt , 2);
                    $quote->subtotal_amount = round($subtotal_amount , 2);
                    $quote->discount_amount = round($discount_amount , 2);
                    $quote->tax_amount = round($tax_amount , 2);
                    $quote->total_amount = round($total_amount , 2);
                    //$quote->opportunity_id = $bean->create_daikin_number_c; 
                    $quote->save();

                    $product_quote_group->tax_amount = round($tax_amount , 2);
                    $product_quote_group->total_amount = round($total_amount , 2);
                    $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                    $product_quote_group->save();
                    //value update Leads after convert Leads
                    $bean->daikin_nexura_quote_num_c =  $quote->id;
                    $json_open_new_tag['daikin_nexura_quote_num_c'] ='1';              
                    
                }

                //create Methven Quote
                if($_REQUEST['create_methven_quote_c'] == '1' && $bean->create_methven_quote_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = $bean->first_name.' ' .$bean->last_name .' ' .$bean->primary_address_city.' ' .$bean->primary_address_state .' Methven' ;
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_methven';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);
                    
                    //$quote->opportunity_id = $bean->create_methven_number_c; 
                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    // save group product tuan code
                    $product_quote_group = new AOS_Line_Item_Groups();
                    $product_quote_group->name = 'Methven';
                    $product_quote_group->created_by = $bean->assigned_user_id;
                    $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                    $product_quote_group->parent_type = 'AOS_Quotes';
                    $product_quote_group->parent_id = $quote->id;
                    $product_quote_group->number = '1';
                    $product_quote_group->currency_id = '-99';
                    $product_quote_group->save();
                    //product methven
                    $part_numners = ['Methven_Kiri_Rose_Only','Methven_Shipping_Handling'];
                
                    $part_numners_implode = implode("','", $part_numners);
                    $db = DBManagerFactory::getInstance();

                    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
                    $ret = $db->query($sql);
                    $total_amt = 0;
                    $subtotal_amount= 0;
                    $discount_amount =0;
                    $tax_amount =0;
                    $total_amount = 0;
                    $index = 1;
                    $is_use_number_1 = false;

                    while ($row = $db->fetchByAssoc($ret))
                    {
                        $check_install_address_postalcode_VEEC = (int)$quote->install_address_postalcode_c;
                        if($check_install_address_postalcode_VEEC < 3000 && $row['id'] == 'cbfafe6b-5e84-d976-8e32-574fc106b13f') {
                            
                        }else {
                            $product_line = new AOS_Products_Quotes();
                            $product_line->currency_id = $row['currency_id'];
                            $product_line->item_description = $row['description'];
                            $product_line->name = $row['name'];
                            $product_line->part_number = $row['part_number'];
                            $product_line->product_cost_price = $row['cost'];
                            $product_line->product_id = $row['id'];
                            $product_line->product_list_price =$row['price'];
                            $product_line->group_id = $product_quote_group->id;
                            $product_line->parent_id = $quote->id;;
                            $product_line->parent_type = 'AOS_Quotes';
                            $product_line->discount = 'Percentage';

                            $product_line->product_total_price =$row['price']*1;
                            $product_line->vat = '10.0';
                            $product_line->vat_amt = round($row['price'] * 0.1*1,2);                            
                            $product_line->save();
                        
                            $total_amt += $product_line->product_total_price;
                            $tax_amount += $product_line->vat_amt;
                        }
                    }
    
                            $discount_amount =0;
                            $total_amount = $total_amt + $tax_amount;
                            $subtotal_amount= $total_amt;

                            $quote->total_amt = round($total_amt , 2);
                            $quote->subtotal_amount = round($subtotal_amount , 2);
                            $quote->discount_amount = round($discount_amount , 2);
                            $quote->tax_amount = round($tax_amount , 2);
                            $quote->total_amount = round($total_amount , 2);
                            //$quote->opportunity_id = $bean->create_daikin_number_c; 
                            $quote->save();

                            $product_quote_group->tax_amount = round($tax_amount , 2);
                            $product_quote_group->total_amount = round($total_amount , 2);
                            $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                            $product_quote_group->save();

                            $bean->create_methven_quote_num_c =  $quote->id;
                            $json_open_new_tag['create_methven_quote_num_c'] ='1';
                }
            
                //create solar Quote
                if($_REQUEST['create_solar_quote_c'] == '1' && $bean->create_solar_quote_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ")  .' Solar';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_solar';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);
                    
                   // $quote->opportunity_id = $bean->create_solar_number_c; 
                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    $quote = request_send_email_request_design($quote);
                    $bean->create_solar_quote_num_c =  $quote->id;
                    //VUT-S-Auto push SG from Lead's solar
                        // $quote->solargain_lead_number_c  = '229214';
                    $quote->solargain_lead_number_c = create_solar_lead($quote,$bean);
                    if ($quote->solargain_lead_number_c != "") {
                        $quote->solargain_quote_number_c = create_solar_quote($quote->solargain_lead_number_c,$quote);
                        if ($quote->solargain_quote_number_c != "") {
                            $quote->save();
                            update_solar_quote($quote->solargain_quote_number_c,$quote);
                        }
                        // $quote->solargain_lead_number_c  = '229214';
                        // $quote->solargain_quote_number_c = create_solar_quote('229214',$quote);
                    }
                    $quote->save();
                    //VUT-E-Auto push SG from Lead's solar
                    $json_open_new_tag['create_solar_quote_num_c'] ='1';
                }

                // create tesla Quote 
                if($_REQUEST['create_tesla_quote_c'] == '1' && $bean->create_tesla_quote_num_c == '' ){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Tesla';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_tesla';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);
                    // $quote->opportunity_id = $bean->create_tesla_number_c; 
                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    $bean->create_tesla_quote_num_c =  $quote->id;
                    //VUT-S-Auto push SG Tesla from Lead's Tesla
                    $quote->solargain_lead_number_c = create_tesla_lead($quote,$bean);
                    if ($quote->solargain_lead_number_c != "") {
                        $quote->solargain_tesla_quote_number_c = create_tesla_quote($quote->solargain_lead_number_c, $quote);
                    }
                    $quote->save();
                    //VUT-E-Auto push SG Tesla from Lead's Tesla
                    $json_open_new_tag['create_tesla_quote_num_c'] ='1';
                }

                // create off grid Quote
                if($_REQUEST['create_off_grid_quote_c'] == '1' && $bean->create_off_grid_button_num_c == ''){
                    $quote = new AOS_Quotes();
                    $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .'  Off Grid';
                    $quote->name = str_replace("&rsquo;","'",$quote->name);
                    $quote->quote_type_c = 'quote_type_off_grid_system';
                    $quote = convert_info_basic_quote($quote,$bean ,$contact ,$account);
                    // $quote->opportunity_id = $bean->create_tesla_number_c; 
                    $quote->save();
                    convert_lead_to_quote($bean,$quote);
                    create_relationship_aos_quotes_leads_2($quote->id,$bean->id);
                    //VUT-S-Create group item
                        /**Save group product*/
                        $product_quote_group = new AOS_Line_Item_Groups();
                        $product_quote_group->name = 'Selectronic Microgrid Solution 2x BYD';
                        $product_quote_group->created_by = $bean->assigned_user_id;
                        $product_quote_group->assigned_user_id = $bean->assigned_user_id;
                        $product_quote_group->parent_type = 'AOS_Quotes';
                        $product_quote_group->number = '1';
                        $product_quote_group->currency_id = '-99';
                        $product_quote_group->save();

                        /**Product Off Grid */
                        $part_numners = [   'Off_Grid_System_BYD2'  ,
                                            'SPMC482-AU-48V-5kW'    ,
                                            'BYDBBPRO13.8'          ,
                                            'SPR-P19-320-BLK'       ,
                                            'PRIMO 8.2-1 SCERT'     ,
                                            'SEL_SELECT_LIVE'       ,
                                            'STC Rebate Certificate'];
                        $part_numners_implode = implode("','",$part_numners);

                        /**Product quantity */
                        $product_byd2_qty  = 1; //Off_Grid_System_BYD2
                        $product_byd_box_qty = 2; //BYD Battery-Box Pro 13.8kWh
                        $product_blk_qty = 34; //SunPower P19 320W Black - SPR-P19-320-BLK
                        $product_stc_qty = 140; //STC Rebate Certificate
                        $product_default_qty = 1; //Default
                        /**Get database */
                        $db = DBManagerFactory::getInstance();
                        $sql = "    SELECT *
                                    FROM aos_products 
                                    WHERE part_number
                                    IN ('".$part_numners_implode."')
                                    ORDER BY price DESC";
                        $ret = $db->query($sql);
                        $total_amt = 0;
                        $subtotal_amount = 0;
                        $discount_amount =0;
                        $tax_amount =0;
                        $total_amount = 0;
                        $index = 1;
                        $is_use_number_1 = false;
                        
                        while ($row = $db->fetchByAssoc($ret)) {
                            if ($row['id'] =='4dc503c3-1446-e25e-aad8-5d38017ccd83') { continue;
                            } else {
                                $product_line = new AOS_Products_Quotes();
                                $product_line->currency_id = $row['currency_id'];
                                $product_line->item_description = $row['description'];
                                $product_line->name = $row['name'];
                                $product_line->part_number = $row['part_number'];
                                $product_line->product_cost_price = $row['cost'];
                                $product_line->product_id = $row['id'];
                                $product_line->product_list_price =$row['price'];
                                $product_line->group_id = $product_quote_group->id;
                                $product_line->parent_id = $quote->id;;
                                $product_line->parent_type = 'AOS_Quotes';
                                $product_line->discount = 'Percentage';
                                /**Display number index */
                                if ($row['id'] =='7efc82ec-7a06-11a1-394e-5d356a1c1f49' && !$is_use_number_1) {//Selectronics Microgrid 2x BYD B-Box Pro, 10.8kW Sunpower
                                    $product_line->number = 1;
                                    $is_use_number_1 = true;
                                } else {
                                    $index++;
                                    $product_line->number = $index;
                                }
                                
                                /**Logic Total amount */
                                    /** Off_Grid_System_BYD2 */
                                if ($row['id'] == '7efc82ec-7a06-11a1-394e-5d356a1c1f49' || $row['id'] == '4dc503c3-1446-e25e-aad8-5d38017ccd83') {
                                    $product_line->product_qty = $product_byd2_qty;
                                    $product_line->product_total_price = $row['price']*$product_byd2_qty;
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1 *$product_byd2_qty,2);                                                
                                    $product_line->save();
                                } 
                                    /**BYD Battery-Box Pro 13.8kWh */
                                else if ($row['id'] == '3f4eb3ac-b756-48c8-4b9b-5cf7a86fae17') {
                                    $product_line->product_qty = $product_byd_box_qty;
                                    $product_line->product_total_price = $row['price']*$product_byd_box_qty;
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1 *$product_byd_box_qty,2);                                                
                                    $product_line->save();
                                }
                                    /**SunPower P19 320W Black - SPR-P19-320-BLK */
                                else if ($row['id'] == '61407d3a-4010-3fdb-2940-5d382ad81df9') {
                                    $product_line->product_qty = $product_blk_qty;
                                    $product_line->product_total_price = $row['price']*$product_blk_qty;
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1 *$product_blk_qty,2);                                                
                                    $product_line->save();
                                }
                                    /**STC Rebate Certificate */
                                else if ($row['id'] == '4efbea92-c52f-d147-3308-569776823b19') {
                                    $product_line->product_qty = $product_stc_qty;
                                    $product_line->product_total_price = $row['price']*$product_stc_qty;
                                    $product_line->vat = '0.0';
                                    $product_line->vat_amt = 0;                                                
                                    $product_line->save();
                                }
                                    /**Default */
                                else {
                                    $product_line->product_qty = $product_default_qty;
                                    $product_line->product_total_price = $row['price']*$product_default_qty;
                                    $product_line->vat = '10.0';
                                    $product_line->vat_amt = round($row['price'] * 0.1 *$product_default_qty,2);                                                
                                    $product_line->save();
                                }

                                $total_amt += $product_line->product_total_price;
                                $tax_amount += $product_line->vat_amt;
                            }    
                        }

                        $discount_amount = 0;
                        $total_amount = $total_amt + $tax_amount;
                        $subtotal_amount= $total_amt;
                
                        $quote->total_amt = round($total_amt , 2);
                        $quote->subtotal_amount = round($subtotal_amount , 2);
                        $quote->discount_amount = round($discount_amount , 2);
                        $quote->tax_amount = round($tax_amount , 2);
                        $quote->total_amount = round($total_amount , 2);
                        //$quote->opportunity_id = $bean->create_daikin_number_c; 
                        $quote->save();
                
                        $product_quote_group->tax_amount = round($tax_amount , 2);
                        $product_quote_group->total_amount = round($total_amount , 2);
                        $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
                        $product_quote_group->save();
                    //VUT-E-Create group item
                    $bean->create_off_grid_button_num_c =  $quote->id;
                    $json_open_new_tag['create_off_grid_button_num_c'] ='1';
                }

                //VUT-Create service Case
                if ($_REQUEST['create_service_case_c'] == '1' && $bean->service_case_number_c == '') {
                    $serviceCase = new pe_service_case();
                    $serviceCase->name = 'From Lead #'.$bean->number.": ".$bean->first_name." ".$bean->last_name;
                    /**Add account infomation */
                    $serviceCase->billing_address_street = $account->billing_address_street;
                    $serviceCase->billing_address_city = $account->billing_address_city;
                    $serviceCase->billing_address_state = $account->billing_address_state;
                    $serviceCase->billing_address_postalcode = $account->billing_address_postalcode;
                    $serviceCase->billing_address_country = $account->billing_address_country;
                    /**Add contact infomation */
                    $serviceCase->shipping_address_street = $contact->primary_address_street;
                    $serviceCase->shipping_address_city = $contact->primary_address_city;
                    $serviceCase->shipping_address_state = $contact->primary_address_state;
                    $serviceCase->shipping_address_postalcode = $contact->primary_address_postalcode;
                    $serviceCase->shipping_address_country = $contact->primary_address_country;
                    $serviceCase->save();
                    /** */
                    $serviceCase->assigned_user_id = $bean->assigned_user_id;
                    $serviceCase->created_by_name = $bean->created_by_name;
                    $serviceCase->billing_account_id = $account->id;
                    $serviceCase->billing_contact_id = $contact->id;
                    $serviceCase->quote_type_c = trim($bean->product_type_c,'^');
                    create_relationship_lead_servicecase($bean->id,$serviceCase->id);
                    $serviceCase->save();
                    $bean->service_case_number_c = $serviceCase->id;
                    $bean->save();
                    $json_open_new_tag['service_case_number_c'] ='1';
                }

                // update status + id new account + id  new contact  + opportunity_id + opportunity_name
                $sql = 'UPDATE leads SET 
                status="Converted" 
                ,converted="1" 
                ,contact_id="' .$contact->id 
                .'" ,account_id="'.$account->id
                .'" , opportunity_id="' .$bean->opportunity_id
                .'",opportunity_name="' .$bean->opportunity_name
                .'"  WHERE id="' .$id_lead .'"' ;
                $ret = $db->query($sql);
                $row = $db->fetchByAssoc($ret);

                // update string json open_new_tag_c 
                $json_open_new_tag = json_encode($json_open_new_tag);

                $sql = "UPDATE leads_cstm SET 
                open_new_tag_c = '" .$json_open_new_tag ."' WHERE" .' id_c="' .$id_lead .'"' ;
                $ret = $db->query($sql);
                $row = $db->fetchByAssoc($ret);

                // update record id opporturnity and quote
                $sql = 'UPDATE leads_cstm SET 
                create_opportunity_number_c="' .$bean->create_opportunity_number_c
                .'" ,create_solar_number_c="'   .$bean->create_solar_number_c
                .'" ,create_methven_number_c="'   .$bean->create_methven_number_c
                .'"  ,create_sanden_number_c="' .$bean->create_sanden_number_c
                .'" , create_daikin_number_c="' .$bean->create_daikin_number_c
                .'" , create_sanden_quote_num_c ="' .$bean->create_sanden_quote_num_c
                .'" , create_daikin_quote_num_c="' .$bean->create_daikin_quote_num_c
                .'" , create_methven_quote_num_c="' .$bean->create_methven_quote_num_c
                .'" , create_solar_quote_num_c="' .$bean->create_solar_quote_num_c
                .'" , create_tesla_quote_num_c="' .$bean->create_tesla_quote_num_c
                .'" , create_solar_quote_fqs_num_c="' .$bean->create_solar_quote_fqs_num_c
                .'" , daikin_nexura_quote_num_c="' .$bean->daikin_nexura_quote_num_c
                .'" , create_solar_quote_fqv_num_c="' .$bean->create_solar_quote_fqv_num_c
                .'" , create_off_grid_button_num_c="' .$bean->create_off_grid_button_num_c
                .'" , service_case_number_c="' .$bean->service_case_number_c
                .'" WHERE id_c="' .$id_lead .'"' ;
                $ret = $db->query($sql);
                $row = $db->fetchByAssoc($ret);
            }  
        } 
    }

    
//dung code - function send email quote Daikin and Sanden
function auto_send_email_convert_lead($record_id ,$templete_id){
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    
    $mail->setMailerForSystem();
    $mail->IsHTML(true);

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);
    //get Signature  and address from sent
    $user_id = '';
    $emailSignatureId  ='';
        //Case: Matthew
    if($lead->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $user_id = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
        $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; 
        $mail->From = "matthew.wright@pure-electric.com.au";
        $mail->FromName = "Matthew Wright - PureElectric";
        $user = new User();
        $user->retrieve($user_id);
        $signature = $user->getSignature($emailSignatureId);
    } 
        //Case : Paul 
    elseif($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
        $user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
        $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
        $mail->From = "paul.szuster@pure-electric.com.au";
        $mail->FromName = "Paul Szuster - PureElectric";
        $user = new User();
        $user->retrieve($user_id);
        $signature = $user->getSignature($emailSignatureId);
    }
        //Case :defaul
    else{

        $mail->From = "accounts@pure-electric.com.au";
        $mail->FromName = "PureElectric";
    }
    

    $link_upload_files = '';
    $string_link_upload_files = '';
    switch ($templete_id) {
        case 'dbf622ae-bb45-cb79-eb97-5cd287c48ac3':
            $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break;
        case 'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5':
            $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break;
        case '8d9e9b2c-e05f-deda-c83a-59f97f10d06a':
            $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break;   
            
        case '5ad80115-b756-ea3e-ca83-5abb005602bf':
            $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break;
            
        case '3c143527-67a2-6190-1565-5d5b3809767e':
            $link_upload_files = 'https://pure-electric.com.au/pesolarform/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break; 
                   
        default:
            # code...
            break;
    }

    //get email template and replace Email Variables
    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve($templete_id);
    $emailtemplate->parsed_entities = null;
    $macro_nv = array();
    $focusName = 'Leads';
    $focus = BeanFactory::getBean($focusName, $lead->id);
    
    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => $emailtemplate->body_html,
            "body" => $emailtemplate->body
            ),
            'Leads',
            $focus,
            $temp
        );
    $email_body = str_replace('$lead_first_name',$lead->first_name,$template_data["body_html"]);
    $email_subject = str_replace('$lead_first_name',$lead->first_name,$template_data["subject"]);
    $email_subject = str_replace('$lead_primary_address_city',$lead->primary_address_city, $email_subject);
    $email_body = str_replace("\$link_upload_files",$string_link_upload_files, $email_body);
    //get and add attachment from template
    
    $note = new Note();
    $where = "notes.parent_id =  '" . $templete_id ."'";
    $attachments = $note->get_full_list("", $where, true);
    $all_attachments = array();
    $all_attachments = array_merge($all_attachments, $attachments);
    foreach($all_attachments as $attachment) {
        $file_name = $attachment->filename;
        global $sugar_config;
        $location = $sugar_config['upload_dir'].$attachment->id;
        $mime_type = $attachment->file_mime_type;
        // Add attachment to email
        $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
    }
    
    $mail->Subject = $email_subject;
    $mail->Body = $email_body . $signature["signature_html"];
    $mail->prepForOutbound();
    //$mail->AddAddress('binhdigipro@gmail.com');
    $mail->AddAddress('admin@pure-electric.com.au');
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
    $mail->AddCC('info@pure-electric.com.au');
    $check_exist_email_customer = '';
    if($_REQUEST['Leads0emailAddress0'] !== '' && isset($_REQUEST['Leads0emailAddress0'])){
        $mail->AddAddress($_REQUEST['Leads0emailAddress0']);
        $check_exist_email_customer = $_REQUEST['Leads0emailAddress0'];
    }else {
        $mail->AddAddress($lead->email1);
        $check_exist_email_customer = $lead->email1;
    }

    if($check_exist_email_customer !== '') {
        $sent = $mail->Send();
    }
    
}
// dung code - function auto copy multiple field same name from Lead to Quote
function convert_lead_to_quote($lead,$quote){
    //field same name
    $array_field_need_copy = array(
        'customer_type_c','roof_type_c','gutter_height_c',
        'build_account_c','connection_type_c','main_type_c','meter_number_c','meter_phase_c','address_nmi_c','nmi_c','account_number_c',
        'phone_num_registered_account_c','name_on_billing_account_c','energy_retailer_c','distributor_c','jemena_account_c',
        'live_chat_c','solargain_lead_number_c','solargain_quote_number_c','solargain_tesla_quote_number_c','solargain_options_c',
        'solargain_inverter_model_c','solargain_offices_c','distance_to_sg_c','price_notes_c',
        'time_request_design_c','time_accepted_job_c','time_sent_to_client_c','time_completed_job_c','address_provided_c', 'description','lead_source_co_c'
    );

    foreach ($array_field_need_copy as $value) {
        if(in_array($value,$quote->column_fields)){
            $quote->$value = $lead->$value;
        }               
    }
    $quote->save();
}

//dung code - function update relationship quote
function create_relationship_aos_quotes_leads_2( $quote_id,$lead_id){
    $AOS_Quotes = BeanFactory::getBean('AOS_Quotes', $quote_id );
    $AOS_Quotes->load_relationship('aos_quotes_leads_2');
    $AOS_Quotes->aos_quotes_leads_2->add($lead_id);
    $AOS_Quotes->load_relationship('leads_aos_quotes_1');
    $AOS_Quotes->leads_aos_quotes_1->add($lead_id);
}

//function convert lead to quote all information basic for quote
function convert_info_basic_quote($quote , $lead ,$contact ,$account){
    date_default_timezone_set('UTC');
    $dateAction = new DateTime('+7 day');
    $dateQuote = new DateTime();
    $quote->quote_date_c = $dateQuote->format('Y-m-d H:i:s');
    $quote->next_action_date_c = $dateAction->format('Y-m-d');
    $quote->leads_aos_quotes_1leads_ida = $lead->id;
    $quote->account_name = $lead->EditView_account_name;
    $quote->assigned_user_name = $lead->assigned_user_name;
    $quote->assigned_user_id = $lead->assigned_user_id;

    $quote->billing_account_id = $account->id;
    $quote->billing_contact_id = $contact->id;
    $quote->billing_account = $account->name;
    $quote->billing_contact = $contact->name;

    $quote->billing_address_street = trim($lead->primary_address_street," ");
    $quote->billing_address_city = trim($lead->primary_address_city," ");
    $quote->billing_address_state = trim($lead->primary_address_state," ");
    $quote->billing_address_postalcode = trim($lead->primary_address_postalcode," ");
    $quote->billing_address_country = trim($lead->primary_address_country," ");

    $quote->shipping_address_street = trim($lead->primary_address_street," ");
    $quote->shipping_address_city = trim($lead->primary_address_city," ");
    $quote->shipping_address_state = trim($lead->primary_address_state," ");
    $quote->shipping_address_postalcode = trim($lead->primary_address_postalcode," ");
    $quote->shipping_address_country = trim($lead->primary_address_country," ");

    $quote->install_address_c = trim($lead->primary_address_street," ");
    $quote->install_address_city_c = trim($lead->primary_address_city," ");
    $quote->install_address_state_c = trim($lead->primary_address_state," ");
    $quote->install_address_postalcode_c = trim($lead->primary_address_postalcode," ");
    $quote->install_address_country_c = trim($lead->primary_address_country," ");
    

    $quote->account_firstname_c = $lead->first_name;
    $quote->account_lastname_c = $lead->last_name;
    
    // $quote->site_detail_addr__c = $lead->primary_address_street;
    // $quote->site_detail_addr__city_c = $lead->primary_address_city;
    // $quote->site_detail_addr__country_c = $lead->primary_address_country;
    // $quote->site_detail_addr__postalcode_c = $lead->primary_address_postalcode;
    // $quote->site_detail_addr__state_c = $lead->primary_address_state;
    return $quote;
}

//function send email "Request Design" 
function request_send_email_request_design($quote){
    if($quote->id == '' || $quote->number == '') return $quote;
    if($quote->lead_source_co_c != 'Solargain') return $quote;

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    //BinhNT: Possible to use new Email()?
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->Subject = 'Request Solar Designs';

    $record_id = urldecode($_GET['record_id']);

    
    $description = $quote->description;
    $address = $_GET["billing_address_street"] . ", " . 
                $_GET["billing_address_city"] . ", " . 
                $_GET["billing_address_state"] . ", " . 
                $_GET["billing_address_postalcode"] ;

    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $lat_long = array('lat'=> $latitude ,'lng'=>$longitude);
    }

    $body = '
    <div dir="ltr">
    Hi Team,
    <div><br></div>
    <div>Another Solar design to do.&nbsp;&nbsp;</div>
    <div><br></div>
    <div>'.$quote->billing_account.'</div>
    <div>'.$_GET["billing_address_street"].'</div>
    <div>'.$_GET["billing_address_city"].'<br></div>
    <div>'.$_GET["billing_address_state"].'</div>
    <div>'.$_GET["billing_address_postalcode"].'</div>
    <div><br></div>


    <div><a target="_blank" href="https://www.google.com/maps/place/' . $address . '">Google Maps</a></div>

    <div><a target="_blank" href="http://maps.google.com/maps?q=&layer=c&cbll=' .$lat_long['lat']. ',' .$lat_long['lng']. '&cbp=11,0,0,0,0">Google Streetview</a></div>

    <div><a target="_blank" href="http://maps.nearmap.com?addr='. $address . '&z=22&t=roadmap">Near Map</a></div>
    <div><br>
    <b>Instruction: </b>
    '
    . '' .'
    </div>
    <div><br></div>
    <div>Please upload the pictures to the CRM when you\'re finished.</div>
    <div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=AOS_Quotes&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link</a></div>
    <div><br></div>
    <div>Please click the link below to accept job.</div>
    <div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customQuotesAcceptJob&accepted=link&record_id=' . $record_id . '">Accept Job</a></div>
    <div><br></div>';
    //TriTruong : get information from report Solar awaiting designer
    $report_solar_awaiting_designer = new AOR_Report();
    $report_solar_awaiting_designer->retrieve("b15ba74a-2aa2-41c2-b8da-5c7721de0bc2"); //report name : Solar awaiting designer
    $bottom_report_solar_awaiting_designer = $report_solar_awaiting_designer->build_group_report();
    $bottom_report_solar_awaiting_designer = '<br>————————————<br><h2><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOR_Reports&action=DetailView&record=b15ba74a-2aa2-41c2-b8da-5c7721de0bc2">Solar awaiting designer </a></h2>' . $bottom_report_solar_awaiting_designer;

    //TriTruong : get information from report Solar job in progress

    $report_solar_job_in_progress = new AOR_Report();
    $report_solar_job_in_progress->retrieve("3e36a332-807d-aa7e-8444-5c7f0ddd6e92"); //report name : Solar job in progress
    $bottom_report_solar_job_in_progress = $report_solar_job_in_progress->build_group_report();
    $bottom_report_solar_job_in_progress = '<br>————————————<br><h2><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOR_Reports&action=DetailView&record=3e36a332-807d-aa7e-8444-5c7f0ddd6e92">Solar job in progress </a></h2>' . $bottom_report_solar_job_in_progress;

    $mail->Body = $body.$bottom_report_solar_awaiting_designer.$bottom_report_solar_job_in_progress;

    $mail->prepForOutbound();
    $mail->AddAddress('admin@pure-electric.com.au');
    $mail->AddCC('info@pure-electric.com.au');
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
    $sent = $mail->Send();
    if ($sent) {
        $mail->status = 'sent';
        $quote->email_send_design_status_c = 'sent';
        $quote->email_send_design_request_id_c = $emailObj->id;
        //dung code- update time sent email Request Design
        date_default_timezone_set('UTC');
        $dateAUS = date('Y-m-d H:i:s', time());
        $quote->time_request_design_c = $dateAUS;
        $quote->stage = 'Request_Designs';
        $quote->save();
    } 
    return $quote;
}

// Tri Truong Add Auto Get Distance for Convert Lead

function cusomFilterPlumberConvertLead($type, $f_address) {
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

function bubble_SortConvertLead($distance_array )  
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

//VUT-Create relationship Lead 1-n Service case
function create_relationship_lead_servicecase($lead_id,$sc_id) {
    $LEADS = BeanFactory::getBean('Leads', $lead_id);
    $LEADS->load_relationship('leads_pe_service_case_1');
    $LEADS->leads_pe_service_case_1->add($sc_id);
}

//VUT-S-Auto push SG from Lead's solar
global $main_url;
// $main_url = "http://locsuitecrm.com/";
$main_url = "https://suitecrm.pure-electric.com.au/";

function login_suitecrm($url, $tmpfsuitename) {
    // $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    // $url = $main_url;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
}

function create_solar_lead($quote, $lead) {
    $tmpfname = dirname(__FILE__).'/cookiesolargain.txt';
    // return "123456";
    // die();
    global $current_user;
    $username = $password = "";

    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'S0larga1n$';
    }

    $first_name = $lead->first_name;
    $last_name = $lead->last_name;
    $phone_work = str_replace(' ','',$lead->phone_work);
    $phone_mobile = str_replace(' ','',$lead->phone_mobile);
    $email_lead = $lead->email1;

    $data = array(
        "CustomerTypeID" => $quote->customer_type_c, 
        "LastName" => $last_name,
        "FirstName" => $first_name,
        "TradingName" => "Trading Name",
        "ABN" =>	"ABN",
        "Phone"	=> $phone_work ? $phone_work : '',
        "Mobile" => $phone_mobile ? $phone_mobile : '',
        "Email" =>	$email_lead ? $email_lead : '',
        "Address" => array(
            "Street1"	=> $quote->install_address_c,
            "Street2"	=> "",
            "Locality" =>	$quote->install_address_city_c,
            "State" => 	$quote->install_address_state_c,
            "PostCode"	=> $quote->install_address_postalcode_c 
        ),
        "Category" => array(
            "Value" => 1,
        ),
        "OptIn" => true,
        "Notes" => array(array(
            "ID" => 0,
        )),
    );

    $data_string = json_encode($data);

    $url = 'https://crm.solargain.com.au/APIv2/customers/';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Content-Length: " .strlen($data_string),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/Lead/Create",
        )
    );

    $custommer = json_decode(curl_exec($curl));

    // Pushing the sites

    $url = 'https://crm.solargain.com.au/APIv2/installs';

    //set the url, number of POST vars, POST data
    $data = array(
        "AccountHolderDateOfBirth" => array(
            "Date" => "01/01/1977"
        ),

        "Address" => array(
            "Street1"	=> $quote->install_address_c,
            "Street2"	=> "",
            "Locality" =>	$quote->install_address_city_c,
            "State" => 	$quote->install_address_state_c,
            "PostCode"	=> $quote->install_address_postalcode_c 
        ),
        "RoofType" =>array("ID" =>$quote->roof_type_c), //roof_type,
        "Notes" => array(array(
            "ID" => 0,
        )),
        "BuildHeight" => array(
            "ID" =>	$quote->gutter_height_c,
        ),
        "MainsTypeID"	=> $quote->main_type_c,
        "ConnectionType" =>	$quote->connection_type_c,
        "MeterNumber"	=> $quote->meter_number_c,
        "MeterPhase" => 1,
        "AccountNumber" =>	$quote->account_number_c,
        "BillingName"	=> $quote->name_on_billing_account_c,
        "EnergyRetailer" => array(
            "ID" => $quote->energy_retailer_c,
        ),
        "NetworkOperator" => array(
            "ID" => $quote->distributor_c,
        ),
    );
    if($quote->nmi_c !== ""){
        $data["NMINumber"]	= $quote->nmi_c;
    }
    $data_string = json_encode($data);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Content-Length: " .strlen($data_string),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/Lead/Create",
        )
    );

    $installer = json_decode(curl_exec($curl));

    $primary_address_state = $quote->install_address_state_c;
    $primary_address_name = "PERTH";
    $primary_address_id = 1;
    if($primary_address_state == "WA State"){
        $primary_address_name = "PERTH";
        $primary_address_id= 1;
    }
    if ($primary_address_state == "VIC"){
        $primary_address_name = "VIC";
        $primary_address_id= 3;
    }
    if ($primary_address_state == "QLD"){
        $primary_address_name = "QLD";
        $primary_address_id= 4;
    }
    if ($primary_address_state == "NSW"){
        $primary_address_name = "SYDNEY";
        $primary_address_id= 9;
    }

    if ($primary_address_state == "SA"){
        $primary_address_name = "SOUTH AUSTRALIA";
        $primary_address_id= 16;
    }
    if ($primary_address_state == "ACT"){
        $primary_address_name = "ACT";
        $primary_address_id= 2;
    }

    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $assigneduser = array(
            "ID" => 475,
            "Name" => "Matthew Wright",
            "Enabled"=>false,
            "Administrator"=>false,
            "IsDealership"=>false
        );
    }else{
        $assigneduser = array(
            "ID" => 730,
            "Name" => "Paul Szuster",
            "Enabled"=>false,
            "Administrator"=>false,
            "IsDealership"=>false
        );
    }

    $data = array(
        "ID" => 0,
        "Status" => "New",
        "IsLost" => false,
        "IsConverted" => false,
        "Created" => "0001-01-01T00:00:00",
        "RoofType" =>array("ID" =>$quote->roof_type_c),
        "AssignedUser" => $assigneduser,
        "AssignedUnit" => array(
            "ID" => $primary_address_id,
            "Name"=>$primary_address_name,
            "RailLength" => 0,
            "IsDealership" => false,
            "OrdersEMail"=> "sg.orders@solargain.com.au",
            "HotWaterOrdersEMail" => "sg.shw.orders@solargain.com.au",
            "RequiresDesignApproval" => false
        ),
        "NextActionDate" => array (
            "Date" => date('d/m/Y', time() + 24*60*60),
            "Time"=>"9:00 AM"
        ),
        "NextActionDateDays"=> 0,
        "LastActionDateDays"=>0,
        "EMails"=>0,
        "Calls"=>0,
        "Editable"=>true,
        "Notes"=>array(
            array(
                "ID"=>0,
                "Text"=>$quote->description,
                "Type"=> array(
                    "ID"=>1,
                    "Name"=>"General",
                    "RequiresComment"=>true
                )
            )
        ),
        "Errors"=> array(),
        "Customer" => $custommer,
        "Install" =>$installer,
        "Source"=> array(
            "ID" => 0,
            "Description" => "Beyond the Grid",
            "Category" => array(
                "ID" =>5,
                "Description" =>"3rd Party Partners",
                "Order" => 5,
                "Units" => array()
            ),
            "Active" =>true,
            "Default" =>false,
            "Order" =>8,
            "StatusReport" =>false,
        ),
        "SystemType" => "PV",
        "SystemSize"=>$quote->system_size_c,
        "UnitsPerDay"=>$quote->units_per_day_c,
        "DollarsPerMonth"=>$quote->dolar_month_c,
        "NumberOfPeople"=>$quote->number_of_people_c,
    );

    $data_string = json_encode($data);

    $url = 'https://crm.solargain.com.au/APIv2/leads/';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: crm.solargain.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json",
            "Accept: application/json, text/plain, */*",
            "Accept-Language: en-US,en;q=0.8,vi;q=0.6",
            "Accept-Encoding: 	gzip, deflate, br",
            "Connection: keep-alive",
            "Content-Length: " .strlen($data_string),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/Lead/Create",
        )
    );
    $result = curl_exec($curl);
    return $result;

}//END

function create_solar_quote($SGleadID,$quoteSuite) {
    // if ($SGleadID == "") {return "00000";}
    // else return "654321";
    // die();
    global $current_user;
    $username = $password = "";

    if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
        $username = "matthew.wright";
        $password =  "MW@pure733";
    }else{
        $username = 'paul.szuster@solargain.com.au';
        $password = 'S0larga1n$';
    }

        //Check set account sg

        if($SGleadID != ''){
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/leads/'.$SGleadID);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Cache-Control: max-age=0"
                )
            );

            $result = curl_exec($ch);
            curl_close ($ch);
            
            $decode_result = json_decode($result);
            if(!isset($decode_result->ID)){
                die();
            }
            if($decode_result->AssignedUser->EMail == 'matthew.wright@solargain.com.au'){
                $username = "matthew.wright";
                $password =  "MW@pure733";
            }else{
                $username = 'paul.szuster@solargain.com.au';
                $password = 'S0larga1n$';
            }
        }else{
            die;
        }
    //END
    $url = 'https://crm.solargain.com.au/APIv2/quotes/create/'.$SGleadID;

    //set the url, number of POST vars, POST data


    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);


    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$SGleadID,
            "Cache-Control: max-age=0"
        )
    );

    $quote = curl_exec($curl);

    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
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
            "Content-Length: " .strlen($quote),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/CreateFromLead?LeadID=".$SGleadID,
        )
    );
    $result = curl_exec($curl);
    $SGquote_ID = $result;

    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            "Cache-Control: max-age=0"
        )
    );
    $quote = curl_exec($curl);
    $quote_decode = json_decode($quote);
    curl_close ($curl);
    //Proposed Install Date
    $quote_decode -> ProposedInstallDate = array (
        "Date" => '31/12/2021', //date('d/m/Y', time() + 6*7*24*60*60)
        "Time" => "9:15 AM"
    );
    
    $quote_encode =  json_encode( $quote_decode);

    $curl = curl_init();
    $url = "https://crm.solargain.com.au/APIv2/quotes/";
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $quote_encode);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
            "Content-Length: " .strlen($quote_encode),
            "Origin: https://crm.solargain.com.au",
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
        )
    );
    $result = curl_exec($curl);
    curl_close ($curl);

    return $SGquote_ID;
    //THIENPB UPDATE
}

function update_solar_quote($SGquote_ID, $quoteSuite) {
        global $current_user;

        $username = $password = "";

        if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
            $username = "matthew.wright";
            $password =  "MW@pure733";
        }else{
            $username = 'paul.szuster@solargain.com.au';
            $password = 'S0larga1n$';
        }
        //THIENPB UPDATE
        $option_models = array(
            'Jinko 330W Mono PERC HC' => '149',
            // 'Jinko 370W Cheetah Plus JKM370M-66H' => '171',
            // 'Q CELLS Q.MAXX 330W' => '156',
            'Q CELLS Q.MAXX-G2 350W'=>'185',
            // 'Q CELLS Q.PEAK DUO G6+ 350W' => '173',
            // 'Sunpower Maxeon 2 350' => '144',
            // 'Sunpower Maxeon 3 395' => '167',
            // 'Sunpower X22 360W'=> '110',
            'Sunpower Maxeon 3 400W'=> '145',
            // 'Sunpower P3 325 BLACK' => '174', 
            'Sunpower P3 370 BLACK' => '193',                               
        );
    
        $option_inverters = array(
            'Primo 3'=>'274',
            'Primo 4'=>'275',
            'Primo 5'=>'269',
            'Primo 6'=>'277',
            'Primo 8.2'=>'278',
            'Symo 5'=>'273',
            'Symo 6'=>'282',
            'Symo 8.2'=>'284',
            'Symo 10'=>'285',
            'Symo 15'=>'287',
            'SYMO 20'=>'289',
            'S Edge 3G'=>'292',
            'S Edge 5G'=>'292',
            'S Edge 6G'=>'292',
            'S Edge 8G'=>'292',
            'S Edge 8 3P'=>'292',
            'S Edge 10G'=>'292',
            'IQ7 plus'=>'201',
            //'IQ7'=>'200',
            'IQ7X'=>'229',
            'SolarEdge with P500'=>'168',
            'SolarEdge with P401'=>'292',
            'SolarEdge with P370'=>'203',
            //'Growatt 3'=>'233',
            // 'Growatt 5'=>'213',
            // 'Growatt 6'=>'230',
            // 'Growatt 8.2'=>'247',
            'Sungrow 3'=>'223',
            'Sungrow 5'=>'259',
            'Sungrow 8'=>'257',
            'Sungrow 10 3P'=>'226',
            'Sungrow 15 3P'=>'241'
        );
        $option_extras = array(   'Fro. Smart Meter (1P)' => '1',
            'Fro. Smart Meter (3P)' => '2',
            'Fronius Service Partner Plus 10YR Warranty' => '387',
            'Switchboard UPG' => '',
            'ENPHS Envoy-S Met.' => '13',
            'SE Smart Meter' => '22',
            'SE Wifi' => '17',
            'Sungrow Smart Meter (1P)' => '413',
            // 'Sungrow Smart Meter (3P)' => '414'
            'Sungrow Three Phase Smart Meter DTSU666' => '524'
        );
        $option_battery = array( 'LG Chem RESU 10H SolarEdge & Fronius' => '40',);
    
        $inverterType = '';
        $totalPanel = '';
        $panelType =  '';
        $postcode = $quoteSuite->install_address_postalcode_c;
        $state = $quoteSuite->install_address_state_c;
        
        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                "Cache-Control: max-age=0"
            )
        );
            
        $quote = curl_exec($curl);
        curl_close($curl);
    
        $quote_decode = json_decode($quote);
        unset($quote_decode->Options[0]);
        //END
        
        //SETUP  DEFAULT OPTIONS
        $pe_pricing_options = new pe_pricing_options();
        $pe_pricing_options->retrieve("406fbeb4-0614-3bcd-7e15-5fbdea690303");
        $defaultOptions = json_decode(htmlspecialchars_decode($pe_pricing_options->pricing_option_input_c),true);
    
        $curl = curl_init();
        $url = 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=popularSolarBasePrice&state='.strtoupper($state);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        $jsonPrice = json_decode(curl_exec($curl),true);
    
        for($i = 0; $i < 6 ; $i++){
            $inverterType = $defaultOptions['inverter_type_'.($i+1)];
            $totalPanel = $defaultOptions['total_panels_'.($i+1)];
            $panelType =  $defaultOptions['panel_type_'.($i+1)];
            $basePrice = (int)getBasePrice($panelType,$inverterType,$totalPanel,$jsonPrice);
    
            if($basePrice > 0){
                $new_option = array (                
                    'Finance' => 
                        array (
                        'Price' => 0,
                        'STCValue' => 0,
                        'APrice' => 0,
                        'CampaignDiscount' => 0,
                        'CostOfFinance' => 0,
                        'PCostOfFinance' => 0,
                        'HCostOfFinance' => 0,
                        'FreedomPackage' => false,
                        'PSecondStoreyInstallation' => false,
                        'HSecondStoreyInstallation' => false,
                        'BaseDepositRate' => 0,
                        'InterestRate' => 0,
                        'Months' => 0,
                        'TotalFinancedAmount' => 0,
                        'AdditionalDeposit' => 0,
                        'MinimumDeposit' => 0,
                        'FortnightlyRepayment' => 0,
                        'TotalPriceLessTotalDeposit' => 0,
                        'TotalDeposit' => 0,
                        'CertegyApprovalNumber' => '',
                        'ClassicDeposit' => 0,
                        'ClassicRepayment' => 0,
                        'ClassicLoanNumber' => '',
                        'ClassicApprovalNumber' => '',
                        'ClassicMonths' => 
                        array (
                            'Value' => 0,
                        ),
                        ),
                        'Splits' => 0,
                        'Travel' => 0,
                        'TiltedPanels' => 0,
                        'AdditionalCableRun' => 0,
                        'ExcessHeightPanels' => 0,
                        'AdditionalInstallationCosts' => 0,
                        'AdditionalProjectCosts' => 0,
                        'RequiresElevatedWorkPlatform' => false,
                        'Accepted' => false,
                        'ID' => 0,
                        'Number' => $i,
                        'Key' => '00000000-0000-0000-0000-000000000000',
                        'Selected' => false,
                        'DisplayOrder' => $i,
                        'Size' => 0,
                        'kWp' => 0,
                        'kVA' => 0,
                        'ExportLimit' => false,
                        'Validation' => 
                        array (
                        'Valid' => true,
                        'Errors' => 
                        array (
                        ),
                        'Warnings' => 
                        array (
                        ),
                        ),
                );
    
                $quote_decode->Options[count($quote_decode->Options)] =  (object)$new_option;
            } 
        }
        
        $data_option_string = json_encode($quote_decode);
        
        $curl = curl_init();
        $url = "https://crm.solargain.com.au/APIv2/quotes/";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_option_string);
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_option_string),
                "Origin: https://crm.solargain.com.au",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            )
        );
        $result = curl_exec($curl);
        curl_close($curl);
    
        $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$SGquote_ID;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                "Cache-Control: max-age=0"
            )
        );
        
        $quote = curl_exec($curl);
        curl_close($curl);
        //thienpb code return if update false
        ////return_message($quote);
    
        $quote_decode = json_decode($quote);
    
        for($i = 0; $i < 6 ; $i++){
            $inverterType = $defaultOptions['inverter_type_'.($i+1)];
            $totalPanel = $defaultOptions['total_panels_'.($i+1)];
            $panelType =  $defaultOptions['panel_type_'.($i+1)];
            $sgPrices = calc_price($basePrice,$panelType,$inverterType,$totalPanel,$postcode,$state);
            $quote_decode->Options[$i]->Finance->PPrice =  (int)$sgPrices;
    
            $arr = array (
                'Configurations' => 
                array (
                0 => array (
                    'ID' => NULL,
                    'MinimumPanels' => 0,
                    'MaximumPanels' => (int)$totalPanel,
                    'MinimumTrackers' => 0,
                    'MaximumTrackers' => 2,
                    'Upgrade' => false,
                    'NewInverter' => false,
                    'Inverter' => 
                    array (
                    ),
                    'Panel' => 
                    array (
                    ),
                    'Trackers' => 
                    array (
                    ),
                    'Number' => NULL,
                    'NumberOfPanels' => (int)$totalPanel,
                    )
                )
            );
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/panels/businessunit/3');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $result = curl_exec($ch);
            curl_close ($ch);
    
            //thienpb code return if update false
            ////return_message($result);
            
            $optionPanels = json_decode($result);
            $dataid = array_column($optionPanels, 'ID');
            $datakey = array_search($option_models[$panelType], $dataid);
            $dataPanel = $optionPanels[$datakey];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/inverters/businessunit/3');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $result = curl_exec($ch);
            curl_close ($ch);
    
            //thienpb code return if update false
            //return_message($result);
            
            $inverters= json_decode($result);
            $dataid = array_column($inverters, 'ID');
    
            if($panelType == 'Sunpower Maxeon 3 400W' && strpos($inverterType,'S Edge') !== false ){
                $datakey = array_search($option_inverters['SolarEdge with P500'], $dataid);
            }else{
                $datakey = array_search($option_inverters[$inverterType], $dataid);
            }
            $dataInverter = $inverters[$datakey];
    
            $arr['Configurations'][0]['Inverter'] = $dataInverter;
            $arr['Configurations'][0]['Panel']  = $dataPanel;
            $data_option_string = json_encode($arr);
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/quotes/calculate?postcode='.$postcode);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_option_string);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                "Cache-Control: max-age=0"
            )
            );
    
            $result = curl_exec($ch);
            curl_close ($ch);
    
            //thienpb code return if update false
            //return_message($result);
    
            $data_option_string = json_decode($result);
    
            unset($quote_decode->Options[$i]->Configurations[0]);
            $quote_decode->Options[$i]->Configurations[0] = $data_option_string->Configurations[0];
            
            $MaximumGroup =  $data_option_string->Configurations[0]->Trackers[0]->MaximumPanels;
            $MaximumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MaximumPanels;
            $MinimumPanels = $data_option_string->Configurations[0]->Trackers[0]->Strings[0]->MinimumPanels;
    
            
            if($MaximumPanels == 1 || $MinimumPanels == 1){
                $quote_decode->Options[$i]->Configurations[0]->NumberOfPanels = 1;
                $quote_decode->Options[$i]->Configurations[0]->Number = (int)$totalPanel;
                $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->PanelCount = 1;
                $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Orientation = array ('Name' => 'N 0','Value' => 0);
                $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Pitch = array ('Name' => '0','Value' => 0);
                $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Shading = 0;
                $quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings[0]->Arrays = 1;
            }else{
                if($MaximumPanels > $MaximumGroup ){
                    $MaximumPanels = $MaximumGroup;
                }

                /** Thienpb update logic check max panel by VOC */
                $tempCov = $dataPanel->TempCoV;
                $covPer = $dataPanel->Voc;
                $COV = ($covPer * ((25 * $tempCov)+100)/100);
                $max = (int)(600/$COV);
                if($max < $MaximumPanels){
                    $MaximumPanels = $max;
                }
                 /** End */

                $data_result = calc_panel((int)$totalPanel,$MinimumPanels,$MaximumPanels,array(count($quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings),count($quote_decode->Options[$i]->Configurations[0]->Trackers[1]->Strings)),$MaximumGroup);
                $sub_panels = $data_result['panelConfig'];
                if(($data_option_string->Configurations[0]->Trackers[0]->MaximumPanels > $data_option_string->Configurations[0]->Trackers[1]->MaximumPanels) && (count($sub_panels[0]) != count($data_option_string->Configurations[0]->Trackers[0]->Strings))){
                    $sub_panels = array_reverse($sub_panels);
                }
                if($data_result['SuggestTotalPanel'] != (int)$totalPanel){
                    $specialMess .= "Can't Push Option ".($i+1)." with ".(int)$totalPanel." panels.(Suggestion : ".$data_result['SuggestTotalPanel']." panels.)\n";
                    if(($i+1) == count($quote_decode->Options)){
                        $specialMess .= "\nYou can use suggestions or remove options was wrong.";
                    }
                }
                for ($j= 0; $j < count($quote_decode->Options[$i]->Configurations[0]->Trackers) ; $j++) {
                    
                    $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->MaximumPanels =(int)$totalPanel;
                    for($k = 0; $k < count($quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings) ; $k++){
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->PanelCount =  ($sub_panels[$j][$k] != 0)?$sub_panels[$j][$k]:NULL;
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Orientation = ($sub_panels[$j][$k] != 0)?array ('Name' => 'N 0','Value' => 0):NULL;
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Pitch = ($sub_panels[$j][$k] != 0)?array ('Name' => '0','Value' => 0):NULL;
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Shading = ($sub_panels[$j][$k] !=0)?0:NULL;
                        $quote_decode->Options[$i]->Configurations[0]->Trackers[$j]->Strings[$k]->Arrays = ($sub_panels[$j][$k] !=0)?1:NULL;;
                    }
                }
            }
    
            $check_tilting = false;
            $check_inveter_type = false;
            $check_battery_type = false;
    
            unset($quote_decode->Options[$i]->Accessories);
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/accessories/businessunit/3');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                    "Cache-Control: max-age=0"
                )
            );
            
            $result = curl_exec($ch);
            curl_close ($ch);
    
            $option_accessories = json_decode($result);
            $dataid = array_column($option_accessories, 'ID');
            $data_option_extra = [];
    
            if(strpos($inverterType,'Primo ') !== false ){
                $extraPrice1    =  $option_extras['Fro. Smart Meter (1P)'];
                $extraPrice2    =  $option_extras['Fronius Service Partner Plus 10YR Warranty'];
            }else if( strpos($inverterType,'Symo ') !== false){
                $extraPrice1    =  $option_extras['Fro. Smart Meter (3P)'];
                $extraPrice2    =  $option_extras['Fronius Service Partner Plus 10YR Warranty'];
            }else if(strpos($inverterType,'S Edge ') !== false){
                $extraPrice1    =  $option_extras['SE Wifi'];
                $extraPrice2    =  $option_extras['SE Smart Meter'];
            }else if(strpos($inverterType,'Sungrow ') !== false){
                if(strpos($inverterType,'3P') !== false){
                    // $extraPrice1    =  $option_extras['Sungrow Smart Meter (3P)'];
                    $extraPrice1    =  $option_extras['Sungrow Three Phase Smart Meter DTSU666'];
                }else {
                    $extraPrice1    =  $option_extras['Sungrow Smart Meter (1P)'];
                }
            }
            if((int)$extraPrice1 > 0){
                $datakey = array_search($extraPrice1, $dataid);
                $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey];
            }
            if((int)$extraPrice2 > 0){
                $datakey_2 = array_search($extraPrice2, $dataid);
                $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_2];
            }
            
            if((int)$extraPrice1 > 0 && ((int)$extraPrice1 == 22 || (int)$extraPrice1 == 17)){
                if($_GET['option_inverter_type_name_'.$i] == 'S Edge 3G'){
                    $datakey_3 = array_search(568,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 5G'){
                    $datakey_3 = array_search(569,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 6G'){
                    $datakey_3 = array_search(570,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 8G'){
                    $datakey_3 = array_search(571,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 8 3P'){
                    $datakey_3 = array_search(500,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }else if($_GET['option_inverter_type_name_'.$i] == 'S Edge 10G'){
                    $datakey_3 = array_search(572,$dataid);
                    $data_option_extra[count($data_option_extra)] = $option_accessories[$datakey_3];
                }
                //array_reverse($data_option_extra,true);
            }
    
            for ($extra=0; $extra < count($data_option_extra); $extra++) { 
                $quote_decode->Options[$i]->Accessories[$extra] = array (
                        'ID' => NULL,
                        'Include' => false,
                        'DisplayOnQuote' => true,
                        'UnitPriceEnabled' => true,
                        'IncludedEnabled' => true,
                        'QuantityEnabled' => true,
                        'Quantity' => '1',
                        'Included' => true,
                        'UnitPrice' => 0,
                        'Accessory' => $data_option_extra[$extra],
                );
            }
        }
    
        $data_option_string = json_encode($quote_decode);
            
        $curl = curl_init();
        $url = "https://crm.solargain.com.au/APIv2/quotes/";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_option_string);
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
                "Content-Length: " .strlen($data_option_string),
                "Origin: https://crm.solargain.com.au",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
            )
        );
        $result = curl_exec($curl);
        curl_close($curl);
        //END
        // return $SGquote_ID;    
}

function calc_panel($totalPanel,$min,$max,$line,$groupmax,$index=0){
    $type= '';
    for($i = $max; $i >= $min ; $i--){
        $arrLine1 = [];
        $arrLine2 = [];
        for($j = 0; $j < $line[0] ;$j++){
            if($j<=$index)
                $arrLine1[$j] = $i;
        }
        $count = $line[1];
        if(array_sum($arrLine1) > $groupmax){
            $type = "false";
            continue;
        }
        $res = $totalPanel - array_sum($arrLine1);

        if($res % $count != 0){
            $count--;
            $type = "false";
            continue;
        }else{
            
            if($res/$count > $max || $res/$count < $min){
                $type = "false";
                continue;
            }else{
                if($res > $groupmax ){
                    $type = "false";
                    continue;
                }
                for($k = 0; $k < $line[1] ; $k++){
                    $arrLine2[$k] = $res/$count;
                }
                $type = array('type'=>'OK','SuggestTotalPanel'=>$totalPanel,'panelConfig'=>array($arrLine1,$arrLine2));
            break;
            }
        }
    }

    if(  $type == "false" &&  $index+1 < $line[0]){
        $index++;
        $type = calc_panel($totalPanel,$min,$max,$line,$groupmax,$index);
    }else if ( $type == "false" &&  $index+1 == $line[0]){
        $totalPanel--;
        $type = calc_panel($totalPanel,$min,$max,$line,$groupmax);
    }
    
    return $type;
}
    
function calc_price($basePrice,$panelType,$inverterType,$totalPanel,$postcode = '3056',$state){
    $pm = 100;

    $result = preg_match_all('/\d+/',$panelType, $matches);

    if($result){
        if(count($matches[0]) > 1){
            $panel_kw = $matches[0][1];
        }else{
            $panel_kw = $matches[0][0];
        }
    }
    $extraPrice1 = $extraPrice2 = 0;
    if(strpos($inverterType,'Primo ') !== false || strpos($inverterType,'Symo ') !== false){
        $extraPrice1    =  extra_('Fro. Smart Meter (1P)');
        $extraPrice2    =  extra_('Fronius Service Partner Plus 10YR Warranty');
    }else if(strpos($inverterType,'S Edge ') !== false){
        $extraPrice1    =  extra_('SE Wifi');
        $extraPrice2    =  extra_('SE Smart Meter');
    }else if(strpos($inverterType,'Sungrow ') !== false){
        $extraPrice1    =  extra_('Sungrow Smart Meter (1P)');
    }

    $total_kw = ((int)$panel_kw * (int)$totalPanel)/1000;
    $stcNumber = getSTCs($total_kw,$postcode);
    $STCsPrice = $stcNumber * 35;

    $extras = $pm + $extraPrice1 + $extraPrice2;

    $netPrice = 0 ;
    $netPrice = $basePrice + $extras;

    $grossPrice = 0 ;
    $grossPrice = $netPrice + $STCsPrice;

    $incPer = 0;
    switch ($state) {
        case 'VIC':case 'NSW':
            $incPer = 0.055;
            break;
        case 'WA':
            $incPer = 0.05;
            break;
        case 'QLD':case 'ACT':
            $incPer = 0.053;
            break;
        case 'SA':
            $incPer = 0.054;
            break;
    }

    $peIncrease = (float)(($netPrice + $STCsPrice) * $incPer);

    $customerPrice = $netPrice + $peIncrease;

    $customerPrice = substr_replace((int)$customerPrice,"90",-2);

    return $customerPrice;
}

function extra_($extra){
    if($extra == 'Fro. Smart Meter (1P)'){
        $data_return = 300;
    }
    else if($extra == 'Fro. Smart Meter (3P)'){
        $data_return = 500;
    }
    else if($extra == 'Switchboard UPG'){
        $data_return = 900;
    }
    else if($extra == 'ENPHS Envoy-S Met.'){
        $data_return = 300;
    }
    else if($extra == 'SE Smart Meter'){
        $data_return = 0;
    }
    else if($extra == 'SE Wifi'){
        $data_return = 0;
    }
    else if($extra == 'Fronius Service Partner Plus 10YR Warranty'){
        $data_return = 100;
    }
    else if($extra == 'Sungrow Smart Meter (1P)'){
        $data_return = 300;
    }
    else if($extra == 'Sungrow Three Phase Smart Meter DTSU666'){//'Sungrow Smart Meter (3P)'){
        $data_return = 400;
    }
    return (int)$data_return;
}

function getSTCs($total_kw,$postcode){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu/stc');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"sguType":"SolarDeemed","expectedInstallDate":"2020-12-31T00:00:00.000Z","ratedPowerOutputInKw":'.$total_kw.',"deemingPeriod":"ELEVEN_YEARS","postcode":"'.$postcode.'","sguDisclaimer":true,"useDefaultResourceAvailability":"true","sguTypeOptions":[{"sguDeemingPeriodsStrategies":[{"years":[2016,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fifteen years","name":"FIFTEEN_YEARS"}]},{"years":[2017],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Fourteen years","name":"FOURTEEN_YEARS"}]},{"years":[2018],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Thirteen years","name":"THIRTEEN_YEARS"}]},{"years":[2019],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Twelve years","name":"TWELVE_YEARS"}]},{"years":[2020],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}]},{"years":[2021],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Ten years","name":"TEN_YEARS"}]},{"years":[2022],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Nine years","name":"NINE_YEARS"}]},{"years":[2023],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eight years","name":"EIGHT_YEARS"}]},{"years":[2024],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Seven years","name":"SEVEN_YEARS"}]},{"years":[2025],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Six years","name":"SIX_YEARS"}]},{"years":[2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - solar (deemed)","name":"SolarDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - wind (deemed)","name":"WindDeemed"},{"sguDeemingPeriodsStrategies":[{"years":[2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"}]},{"years":[2027],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Four years","name":"FOUR_YEARS"}]},{"years":[2028],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Three years","name":"THREE_YEARS"}]},{"years":[2029],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Two years","name":"TWO_YEARS"}]},{"years":[2030],"sguDeemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"}]}],"displayName":"S.G.U. - hydro (deemed)","name":"HydroDeemed"}],"deemingPeriods":[{"displayName":"One year","name":"ONE_YEAR"},{"displayName":"Five years","name":"FIVE_YEARS"},{"displayName":"Eleven years","name":"ELEVEN_YEARS"}],"helpWithSolarCreditsVisible":true}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json; charset=UTF-8",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "Accept-Language:  en-US,en;q=0.9",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Origin: https://www.rec-registry.gov.au",
        "Referer: https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu-stc-calculator"
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    $data_return =  json_decode($result);
    if($data_return->status == 'Completed'){
        return (int)$data_return->result->numberOfStcs;
    }else{
        return 0;
    }
}

function getBasePrice($panel_type,$inverter_type,$total_panel,$dataJSON){
    
    if($dataJSON == '')die;

    $list_panel = $dataJSON[$panel_type];
    $list_suggest = '';
    $temp = [];
    $check = '';

    foreach($list_panel as $itemkey => $itemVal){
        if(strpos($itemkey,$total_panel.' panels') !== false){
            for($i = 0 ; $i < count($itemVal) ; $i++){
                if($itemVal[$i]['inverter'] == $inverter_type){
                    $list_suggest = $itemVal[$i]['price'];
                    break 2;
                }
            }
        }
    }

    return  (int)$list_suggest;
}

/**Use entrypoint */
/**Create Lead is same for Solar/Tesla */ 
function  create_tesla_lead($quote, $lead) {
    global $main_url;
    // Step 1: Log in suitecrm
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    login_suitecrm($main_url, $tmpfsuitename);

    // Step 2: Create SG LEAD
    $fields = array (
        "process" => "lead",
        "record" => $quote->id,
        "notes" => rawurlencode(html_entity_decode($quote->description, ENT_QUOTES)),
        "system_size" => $quote->system_size_c,
        "unit_per_day" => $quote->units_per_day_c,
        "dolar_month" => $quote->dolar_month_c,
        "number_of_people" => $quote->number_of_people_c,

        "customer_type" => $quote->customer_type_c,
        "billing_address_street" => $quote->install_address_c,
        "billing_address_city" => $quote->install_address_city_c,
        "state" => $quote->install_address_state_c,
        "postalcode" => $quote->install_address_postalcode_c,
        "build_height" => $quote->gutter_height_c,

        "main_type" => $quote->main_type_c,
        "meter_number" => $quote->meter_number_c,
        "nmi_number" => $quote->nmi_c,

        "account_number" => $quote->account_number_c,
        "billing_name" => $quote->name_on_billing_account_c,
        "distributor" => $quote->distributor_c,
        "energy_retailer" => $quote->energy_retailer_c,
    );
    /**Check field "connection_type_c"*/
    $connection_type = $quote->connection_type_c;
    if ($connection_type == "Semi_Rural_Remote_Meter") {
        $connection_type = "Semi Rural/Remote Meter";
    }
    $fields['connection_type'] = $connection_type;
                        
    $data = http_build_query($fields);
    $url =$main_url."index.php?entryPoint=quoteCreateSGQuote";
    // $url =$main_url."index.php?entryPoint=VUTmyTimeEntryPoint1";

    $url .= "&$data"; 
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    
    $result = curl_exec($curl);
    curl_close ($curl);
    // Solargain lead number
    return $result;
}

function create_tesla_quote($SGleadID, $quote) {
    global $main_url;
    // Step 1: Log in suitecrm
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    login_suitecrm($main_url, $tmpfsuitename);

    // Step 2: Create SG QUOTE
    $fields = array (
        "leadID" => $SGleadID,
        "record" => $quote->id,
        "billing_address_street" => $quote->install_address_c,
        "billing_address_city" => $quote->install_address_city_c,
        "billing_address_state" => $quote->install_address_state_c,
        "billing_address_postalcode" => $quote->install_address_postalcode_c,
        "meter_phase_c" => $quote->meter_phase_c,
        "solargain_inverter_model" => $quote->solargain_inverter_model_c,
    );

    $data = http_build_query($fields);
    
    $url =$main_url."index.php?entryPoint=quoteCreateSGTeslaQuote";
    // $url =$main_url."index.php?entryPoint=VUTmyTimeEntryPoint1";
    $url .="&$data";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    
    $data_result = curl_exec($curl);
    curl_close ($curl);
    $result = json_decode($data_result);
    return $result->QuoteNumber;
}
//VUT-E-Auto push SG from Lead's solar
