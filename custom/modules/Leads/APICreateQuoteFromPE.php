<?php
$record_lead_id = trim($_REQUEST['record_id']);
$products =  trim($_REQUEST['products']);
$array_products =array_map('strtolower',array_map('trim', explode(',',$products)));

// bean is bean lead
$bean = new Lead();
$bean->retrieve($record_lead_id);
if($bean->id == '') return;

date_default_timezone_set('Australia/Melbourne');
$current_hour = date('H', time());
$next_day = date('m/d/Y', strtotime("+1 days") );
$next_day .= ' 08:00:00';
$next_hours = date('m/d/Y',time()) . ' 08:00:00';

if(!($current_hour > 7 && $current_hour < 19)) { 
    if( $current_hour >= 0 && $current_hour < 8){
        $schedule_time = strtotime($next_hours);
    }else{
        $schedule_time = strtotime($next_day);
    }
}else{
    $schedule_time = time();
}
// if( (int) $current_hour >= 9 && (int) $current_hour <= 13){
//     $bean->assigned_user_name = 'Michael Golden';
//     $bean->assigned_user_id = "71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb"; //MGolden
//     $bean->save();
// }
send_sms_notication_for_assigned_user($bean,$array_products);
// internal notes
$internal_notes = new pe_internal_note();
$internal_notes->name = $bean->created_by_name;
$internal_notes->assigned_user_id = $bean->modified_user_id;
$internal_notes->type_inter_note_c = 'email_out';
$internal_notes->description = "GET A FREE QUOTE! ";
$internal_notes->description .= "Product#: ";
foreach ($array_products as $key_product => $value_product) {
    if( $value_product != "" ){
        $internal_notes->description .= $value_product.", ";
    }
}
$internal_notes->description .= " - First Name: ".$bean->first_name;
$internal_notes->description .= " - Last Name: ".$bean->last_name;
$internal_notes->description .= " - Email: ".$bean->email1;
$internal_notes->description .= " - Phone Number: ".$bean->phone_mobile;
$internal_notes->description .= " - Street: ".$bean->primary_address_street;
$internal_notes->description .= " - Suburb: ".$bean->primary_address_city;
$internal_notes->description .= " - Post Cost: ".$bean->primary_address_postalcode;
$internal_notes->description .= " - State: ".$bean->primary_address_state;
$internal_notes->save();
// $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
// $internal_notes->aos_quotes_pe_internal_note_1->add($bean_module->id);
send_email_notication_for_customer($lead, '517a2f77-ed2a-7c53-0f44-5f34f4750970');
foreach ($array_products as $key_product => $value_product) {
    $schedule_time = $schedule_time + 60*6; //+ 6 minutes
    switch ($value_product) {
        case 'sanden eco heat pump hot water':
            // $type_button = 'convert_sanden_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            if( $bean->primary_address_state == "WA" ){
                send_email_schedule_info_pack($bean,'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5',$schedule_time);                  
            }else {
                send_email_schedule_info_pack($bean,'dbf622ae-bb45-cb79-eb97-5cd287c48ac3',$schedule_time);                  
            }
            break;
        case 'daikin us7':
            // $type_button = 'convert_daikin_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'8d9e9b2c-e05f-deda-c83a-59f97f10d06a',$schedule_time);    
            break;   
        case 'off grid':
            // $type_button = 'convert_off_grid_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'aadabe47-f800-266e-78dc-5e49e7eb2629',$schedule_time);  
            break;     
        case 'methven kiri satinjet ultra low flow showerhead':
            // $type_button ='convert_mathven_button'; 
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'ec302586-cd96-e843-bd9b-5b25c5b0b321',$schedule_time); 
            break;
        case 'rooftop solar':
            // $type_button ='convert_solar_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'3c143527-67a2-6190-1565-5d5b3809767e',$schedule_time); 
            break;            
        case 'daikin nexura':
            // $type_button = 'convert_daikin_nexura_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'5ad80115-b756-ea3e-ca83-5abb005602bf',$schedule_time); 
            break;  
        case 'daikin alira':
            // $type_button = 'convert_daikin_nexura_button';
            // convert_lead_to_quote_api_from_pe($bean,$type_button);
            send_email_schedule_info_pack($bean,'56ff8695-7163-315a-e1e8-60b9ae967c1a',$schedule_time); 
            break;                      
        default:
            # code...
            break;
    }
}

function convert_lead_to_quote_api_from_pe($bean,$type_button){
            
        $id_lead = $bean->id;
        //step 1 - get field json_open_new_tag
        $json_open_new_tag = $bean->open_new_tag_c;
        date_default_timezone_set('UTC');
        $dateAction = new DateTime('+7 day');
        $dateQuote = new DateTime();
        if($json_open_new_tag == '') {
            $json_open_new_tag = array(
                'create_opportunity_number_c' => '0',
                'create_solar_number_c' => '0',
                'create_methven_number_c' => '0',
                'create_grid_button_number_c' => '0',
                'create_sanden_number_c' => '0',
                'create_daikin_number_c' => '0',
                'create_sanden_quote_num_c' => '0',
                'create_daikin_quote_num_c' => '0',
                'create_methven_quote_num_c' => '0',
                'create_solar_quote_num_c' => '0',
            );
        }else {
            $json_open_new_tag = json_decode ($json_open_new_tag);
        }

        //step 2 - create contact
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

        //step 3 - create account
        if($bean->account_id == '') {
            $account = new Account();
            $account->name = $bean->first_name ." " . $bean->last_name;
            $account->phone_office = $bean->phone_office;
            $account->phone_fax = $bean->phone_fax;
            $account->mobile_phone_c = $bean->phone_mobile;
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

    //create Sanden Quote        

    if($type_button == 'convert_sanden_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ")  .' Sanden';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Sanden';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_sanden';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);

        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
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
        $product_qty_300 = 0;
        $product_qty_250 = 0;
        $part_numners = ['Sanden_Plb_Install_Std','Sanden_Elec_Install_Std','STC Rebate Certificate','QIK15−HPUMP'];
        //defaul product "GAUS-315EQTAQ" with quantity = 1
        //if($_REQUEST['sanden_315'] !== ''){ -- thien fix
            array_push($part_numners, 'GAUS-300FQS'); 
            $product_qty_300 = 1;
        //}
            
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
                //logic total amount  --thienpb fix
                
                if($_REQUEST['sanden_315'] !== '' && $row['id'] =='2e3e02ab-596c-aa4d-ec75-59dae3a11c63'){
                    $product_line->product_qty = $product_qty_315; 
                    $product_line->product_total_price = $row['price']*$product_qty_315;
                    $product_line->vat = '10.0';
                    $product_line->vat_amt = round($row['price'] * 0.1 *$product_qty_315,2);                                                
                    $product_line->save();
                }
                else if($_REQUEST['sanden_300'] !== '' && $row['id'] =='719810a1-6e5e-78a3-54da-5d6f8fb5aed6'){
                    $product_line->product_qty = $product_qty_300; 
                    $product_line->product_total_price =$row['price']*$product_qty_300;
                    $product_line->vat = '10.0';
                    $product_line->vat_amt = round($row['price'] * 0.1*$product_qty_300,2);                            
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
                    if($row['id'] == '4efbea92-c52f-d147-3308-569776823b19'){
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
        $bean->create_solar_quote_fqs_c =  1;
        $json_open_new_tag['create_solar_quote_fqs_c'] ='1';     
        
    }

    //create DaikinUS7 Quote
    if($type_button == 'convert_daikin_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Daikin';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Daikin';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_daikin';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);
        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
        // save group product
        $product_quote_group = new AOS_Line_Item_Groups();
        $product_quote_group->name = 'Daikin VIC';
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
        $part_numners = ['BRP072A42','DAIKIN_MEL_METRO_DELIVERY','JOLLYAIR_STANDARD_INSTALL','VEEC Rebate Certificate'];
        //defaul partnumber : "FTXZ25N" with quantity = 1
        if($_REQUEST['daikin_25_pro'] !== ''){
            array_push($part_numners, 'FTXZ25N');   
            $product_qty_25 = 1;
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
                if(($row['id'] =='3518d3a1-7c11-77c5-b9db-5694fed992e6'
                || $row['id'] =='571aa1b6-9abe-80ec-5cdd-56b4536a29d0' 
                || $row['id'] =='ef81036f-9889-234d-02e5-57b2c0c71e79')&& !$is_use_number_1) {
                    $product_line->number = 1;
                    $is_use_number_1 = true;
                }else {
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
        $bean->create_daikin_quote_num_c =  $quote->id;
        $bean->create_daikin_quote_c =  1;
        $json_open_new_tag['create_daikin_quote_num_c'] ='1';                
        
    }

    //create Daikin Nexura Quote
    if($type_button == 'convert_daikin_nexura_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Daikin Nexura';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Daikin Nexura';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_nexura';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);

        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
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
            $product_qty_25 = 1;
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
                || $row['id'] =='687c3167-094a-bb17-40d9-59535f5cf7f4')&& !$is_use_number_1) {
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
        $bean->daikin_nexura_quote_num_c =  $quote->id;
        $bean->create_daikin_nexura_quote_c = 1;
        $json_open_new_tag['daikin_nexura_quote_num_c'] ='1';                 
        
    }

    //create Methven Quote
    if($type_button == 'convert_mathven_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Methven';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Methven';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_methven';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);
        
        //$quote->opportunity_id = $bean->create_methven_number_c; 
        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
        $bean->create_methven_quote_num_c =  $quote->id;
        $bean->create_methven_quote_c = 1;
        $json_open_new_tag['create_methven_quote_num_c'] ='1';
    }

    //create solar Quote 
    if($type_button == 'convert_solar_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Solar';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Solar';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_solar';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);
        
        //$quote->opportunity_id = $bean->create_solar_number_c; 
        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
        $bean->create_solar_quote_num_c =  $quote->id;
        $bean->create_solar_quote_c = 1;
        //VUT-S-Auto push SG from Lead's solar
        $quote->solargain_lead_number_c = create_solar_lead_c($quote,$bean);
        if ($quote->solargain_lead_number_c != "") {
            $quote->solargain_quote_number_c = create_solar_quote_c($quote->solargain_lead_number_c,$quote);
            if ($quote->solargain_quote_number_c != "") {
                $quote->save();
                update_solar_quote_c($quote->solargain_quote_number_c,$quote);
            }
            // $quote->solargain_lead_number_c  = '229214';
            // $quote->solargain_quote_number_c = create_solar_quote_c('229214',$quote);
        }
        $quote->save();
        //VUT-E-Auto push SG from Lead's solar
        $json_open_new_tag['create_solar_quote_num_c'] ='1';
    }

    //Create Convert Off Grid Quote
    if($type_button == 'convert_off_grid_button'){
        $quote = new AOS_Quotes();
        if(empty($bean->account_name)){
            $quote->name = trim($bean->first_name," ") .' '.trim($bean->last_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ").' Off Grid';
        }else{
            $quote->name = trim($bean->account_name," ") .' '.trim($bean->primary_address_city," ").' '.trim($bean->primary_address_state," ") .' Off Grid';
        }
        $quote->name = str_replace("&rsquo;","'",$quote->name);
        $quote->quote_type_c = 'quote_type_off_grid_system';
        $quote = convert_info_basic_quote_api($quote,$bean ,$contact ,$account);
        
        //$quote->opportunity_id = $bean->create_grid_button_number_c; 
        $quote->save();
        convert_lead_to_quote_api($bean,$quote);
        create_relationship_aos_quotes_leads_2_api($quote->id,$bean->id);
        $bean->create_off_grid_button_num_c =  $quote->id;
        $json_open_new_tag['create_off_grid_button_num_c'] ='1';
    }
    // update status + id new account + id  new contact  + opportunity_id + opportunity_name
    // $db = DBManagerFactory::getInstance();
    // $sql = 'UPDATE leads SET 
    // status="Converted" 
    // ,converted="1" 
    // ,contact_id="' .$contact->id 
    // .'" ,account_id="'.$account->id
    // .'" , opportunity_id="' .$bean->opportunity_id
    // .'",opportunity_name="' .$bean->opportunity_name
    // .'"  WHERE id="' .$id_lead .'"' ;
    // $ret = $db->query($sql);
    // $row = $db->fetchByAssoc($ret);

    // // update string json open_new_tag_c 
    // $json_open_new_tag = json_encode($json_open_new_tag);

    // $sql = "UPDATE leads_cstm SET 
    // open_new_tag_c = '" .$json_open_new_tag ."' WHERE" .' id_c="' .$id_lead .'"' ;
    // $ret = $db->query($sql);
    // $row = $db->fetchByAssoc($ret);
    $bean->save();
}

function convert_lead_to_quote_api($lead,$quote){
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

function create_relationship_aos_quotes_leads_2_api( $quote_id,$lead_id){
    $AOS_Quotes = BeanFactory::getBean('AOS_Quotes', $quote_id );
    $AOS_Quotes->load_relationship('aos_quotes_leads_2');
    $AOS_Quotes->aos_quotes_leads_2->add($lead_id);
    $AOS_Quotes->load_relationship('leads_aos_quotes_1');
    $AOS_Quotes->leads_aos_quotes_1->add($lead_id);
}

 function convert_info_basic_quote_api($quote , $lead ,$contact ,$account){
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

//VUT-Auto push SG from Lead's solar
function create_solar_lead_c($quote, $lead) {
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
        $password = 'WalkingElephant#256';
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

function create_solar_quote_c($SGleadID,$quoteSuite) {
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
        $password = 'WalkingElephant#256';
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
                $password = 'WalkingElephant#256';
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

function update_solar_quote_c($SGquote_ID, $quoteSuite) {
        global $current_user;

        $username = $password = "";

        if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
            $username = "matthew.wright";
            $password =  "MW@pure733";
        }else{
            $username = 'paul.szuster@solargain.com.au';
            $password = 'WalkingElephant#256';
        }
        //THIENPB UPDATE
        $option_models = array(
            'Jinko Tiger N-type Mono 370' => '196',
            // 'Jinko 370W Cheetah Plus JKM370M-66H' => '171',
            'Q CELLS Q.MAXX-G3 385W'=> '202',
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
            //'Sungrow Smart Meter (3P)' => '414'
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
            $basePrice = (int)getBasePrice_c($panelType,$inverterType,$totalPanel,$jsonPrice);
    
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
            $sgPrices = calc_price_c($basePrice,$panelType,$inverterType,$totalPanel,$postcode,$state);
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

                 
                $data_result = calc_panel_c((int)$totalPanel,$MinimumPanels,$MaximumPanels,array(count($quote_decode->Options[$i]->Configurations[0]->Trackers[0]->Strings),count($quote_decode->Options[$i]->Configurations[0]->Trackers[1]->Strings)),$MaximumGroup);
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

function calc_panel_c($totalPanel,$min,$max,$line,$groupmax,$index=0){
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
        $type = calc_panel_c($totalPanel,$min,$max,$line,$groupmax,$index);
    }else if ( $type == "false" &&  $index+1 == $line[0]){
        $totalPanel--;
        $type = calc_panel_c($totalPanel,$min,$max,$line,$groupmax);
    }
    
    return $type;
}
    
function calc_price_c($basePrice,$panelType,$inverterType,$totalPanel,$postcode = '3056',$state){
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
        $extraPrice1    =  extra_c('Fro. Smart Meter (1P)');
        $extraPrice2    =  extra_c('Fronius Service Partner Plus 10YR Warranty');
    }else if(strpos($inverterType,'S Edge ') !== false){
        $extraPrice1    =  extra_c('SE Wifi');
        $extraPrice2    =  extra_c('SE Smart Meter');
    }else if(strpos($inverterType,'Sungrow ') !== false){
        $extraPrice1    =  extra_c('Sungrow Smart Meter (1P)');
    }

    $total_kw = ((int)$panel_kw * (int)$totalPanel)/1000;
    $stcNumber = getSTCs_c($total_kw,$postcode);
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

function extra_c($extra){
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

function getSTCs_c($total_kw,$postcode){
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

function getBasePrice_c($panel_type,$inverter_type,$total_panel,$dataJSON){
    
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

function send_email_schedule_info_pack($lead, $emailTemplateID,$schedule_time =''){
    date_default_timezone_set('Australia/Melbourne');
    if($schedule_time == ''){
        //default
        $schedule_time = time() + 60*6; //+ 6 minutes
    }

    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve($emailTemplateID);

    $link_upload_files = '';
    $string_link_upload_files = '';
    switch ($emailTemplateID) {
        case 'dbf622ae-bb45-cb79-eb97-5cd287c48ac3': //FQS
            $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break;
        case 'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5': // FQV
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
            // $link_upload_files = 'https://pure-electric.com.au/pesolarform/confirm-to-lead?lead-id=' . $lead->id;
            // .:nhantv:. Update link to solar quote form
            $link_upload_files = 'https://pure-electric.com.au/pesolarform?lead-id=' . $lead->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">Link Upload Here</a>';
            break; 
                   
        default:
            # code...
            break;
    }
    $macro_nv = array();
    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => htmlspecialchars($emailtemplate->body_html),
            "body" => $emailtemplate->body_html
            ),
            'Leads',
            $lead,
            $macro_nv
        );
    
    $name = $template_data['subject'];
    $description = $template_data['body'];
    $description_html = $template_data['body_html'];
    //parse value
    $name = str_replace("\$lead_first_name",$lead->first_name , $name);
    $name = str_replace('$lead_primary_address_city', $lead->primary_address_city, $name);
    $name = str_replace('$lead_primary_address_state', $lead->primary_address_state, $name);
    $description = str_replace("\$lead_first_name",$lead->first_name , $description);
    $description_html = str_replace("\$lead_first_name",$lead->first_name , $description_html);
    $description = str_replace("\$lead_primary_address_city",$lead->primary_address_city , $description);
    $description_html = str_replace("\$lead_primary_address_city",$lead->primary_address_city , $description_html);
    $description = str_replace("\$lead_primary_address_state",$lead->primary_address_state , $description);
    $description_html = str_replace("\$lead_primary_address_state",$lead->primary_address_state , $description_html);
    $description_html = str_replace("\$link_upload_files",$string_link_upload_files, $description_html);
    $description = str_replace("\$link_upload_files",$string_link_upload_files, $description);
    //get signature

    
    // if($lead->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
    //     $mail_From = "matthew.wright@pure-electric.com.au";
    //     $mail_FromName = "PureElectric";
    //     $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";   
    // }else{
    //     $mail_From = "paul.szuster@pure-electric.com.au";
    //     $mail_FromName = "PureElectric";
    //     $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe"; 
    // }

    $mail_From = "info@pure-electric.com.au";
    $mail_FromName = "Pure Electric";
    $emailSignatureId = '3ad8f82a-d3e7-5897-7c98-5ba1c4ac785e'; 
    $user = new User();
    $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
    $defaultEmailSignature = $user->getSignature($emailSignatureId);

    if (empty($defaultEmailSignature)) {
        $defaultEmailSignature = array(
            'html' => '<br>',
            'plain' => '\r\n',
        );
        $defaultEmailSignature['no_default_available'] = true;
    } else {
        $defaultEmailSignature['no_default_available'] = false;
    }
    $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
    $description .= "<br><br><br>";
    $description .=  $defaultEmailSignature['signature_html'];
    $description_html .= "<br><br><br>";
    $description_html .=  $defaultEmailSignature['signature_html'];

    //create email 
    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "out";
    $email->status = "email_schedule";
    $email->parent_type = 'Leads';
    $email->parent_id = $lead->id;
    $email->parent_name = $lead->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description_html;
    $email->schedule_timestamp_c = $schedule_time;
    $email->from_addr = $mail_From;
    $email->from_name = $mail_FromName;
    $email->to_addrs_emails = $lead->email1 . ";";
    $email->to_addrs = $lead->name . " <" . $lead->email1 . ">";
    $email->to_addrs_names = $lead->name . " <" . $lead->email1 . ">";
    $email->to_addrs_arr = array(
        array(
            'email' => $lead->email1,
            'display' => $lead->name
        )
    );

    $email->cc_addrs_emails = "Pure Info <info@pure-electric.com.au>;";
    $email->cc_addrs = 'Pure Info <info@pure-electric.com.au>';
    $email->cc_addrs_names = "Pure Info <info@pure-electric.com.au>";
    $email->cc_addrs_arr = array(
        array(
            'email' => 'info@pure-electric.com.au',
            'display' => 'Pure Info'
        )
    );

    $email_id = $email->id;

    $note = new Note();
    $where = "notes.parent_id = '$emailTemplateID'";
    $attachments = $note->get_full_list("", $where, true);
    $all_attachments = array();
    $all_attachments = array_merge($all_attachments, $attachments);
    foreach($all_attachments as $attachment) {
        $noteTemplate = clone $attachment;
        $noteTemplate->id = create_guid();
        $noteTemplate->new_with_id = true; 
        $noteTemplate->parent_id = $email->id;
        $noteTemplate->parent_type = 'Emails';
        $noteFile = new UploadFile();
        $noteFile->duplicate_file($attachment->id, $noteTemplate->id, $noteTemplate->filename);
        $noteTemplate->save();
        $email->attachNote($noteTemplate);
    }
    $email->save();
    return $email_id ;
}
function send_email_notication_for_customer($lead, $emailTemplateID){
    date_default_timezone_set('Australia/Melbourne');
    
    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve($emailTemplateID);
    $macro_nv = array();
    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => htmlspecialchars($emailtemplate->body_html),
            "body" => $emailtemplate->body_html
            ),
            'Leads',
            $lead,
            $macro_nv
        );
    
    $name = $template_data['subject'];
    $description = $template_data['body'];
    $description_html = $template_data['body_html'];
    //parse value
    $name = str_replace("\$lead_first_name",$lead->first_name , $name);
    $name = str_replace("\$lead_last_name",$lead->last_name , $name);
    $name = str_replace('$lead_primary_address_city', $lead->primary_address_city, $name);
    $name = str_replace('$lead_primary_address_state', $lead->primary_address_state, $name);
    $description = str_replace("\$lead_first_name",$lead->first_name , $description);
    $description_html = str_replace("\$lead_first_name",$lead->first_name , $description_html);

    
    //get signature

    $mail_From = "info@pure-electric.com.au";
    $mail_FromName = "Pure Electric";
    $emailSignatureId = '3ad8f82a-d3e7-5897-7c98-5ba1c4ac785e'; 
    $user = new User();
    $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
    $defaultEmailSignature = $user->getSignature($emailSignatureId);

    if (empty($defaultEmailSignature)) {
        $defaultEmailSignature = array(
            'html' => '<br>',
            'plain' => '\r\n',
        );
        $defaultEmailSignature['no_default_available'] = true;
    } else {
        $defaultEmailSignature['no_default_available'] = false;
    }
    $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
    $description .= "<br><br><br>";
    $description .=  $defaultEmailSignature['signature_html'];
    $description_html .= "<br><br><br>";
    $description_html .=  $defaultEmailSignature['signature_html'];

    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "out";
    $email->parent_type = 'Leads';
    $email->parent_id = $lead->id;
    $email->parent_name = $lead->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description_html;
    $email->from_addr = $mail_From;
    $email->from_name = $mail_FromName;
    $email->to_addrs_emails = $lead->email1 . ";";
    $email->to_addrs = $lead->name . " <" . $lead->email1 . ">";
    $email->to_addrs_names = $lead->name . " <" . $lead->email1 . ">";
    $email->to_addrs_arr = array(
        array(
            'email' => $lead->email1,
            'display' => $lead->name
        )
    );

    $email->cc_addrs_emails = "Pure Info <info@pure-electric.com.au>;";
    $email->cc_addrs = 'Pure Info <info@pure-electric.com.au>';
    $email->cc_addrs_names = "Pure Info <info@pure-electric.com.au>";
    $email->cc_addrs_arr = array(
        array(
            'email' => 'info@pure-electric.com.au',
            'display' => 'Pure Info'
        )
    );
    $email_id = $email->id;

    $email->save();
    return $email_id ;
}
function send_sms_notication_for_assigned_user($lead,$array_products){
    global $sugar_config;
    $smsTemplate = BeanFactory::getBean(
        'pe_smstemplate',
        '2a1008d6-f9e4-9b6a-d737-60adca4e3166' 
    );
    $user_assign = new User();
    $user_assign->retrieve($lead->assigned_user_id);
    $phone_assigned = $user_assign->phone_mobile;

    foreach ($array_products as $key_product => $value_product) {
        if( $value_product != "" ){
            $productType .= $value_product.", ";
        }
    }
    // $link_leads = 'https://suitecrm.pure-electric.com.au/index.php?module=Leads&action=EditView&record='.$lead->id;
    $description = $smsTemplate->description;
    $body = $smsTemplate->body_c;

    $body = str_replace("\$assigned_first_name", $user_assign->first_name, $body);
    $body = str_replace("\$customer_first_name", $lead->first_name, $body);
    $body = str_replace("\$customer_last_name", $lead->last_name, $body);
    $body = str_replace("\$lead_number", $lead->number, $body);
    $body = str_replace("\$address_subub", $lead->primary_address_city, $body);
    $body = str_replace("\$address_state", $lead->primary_address_state, $body);
    $body = str_replace("\$productType", $productType, $body);

    $phone_assigned = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_assigned));
    $phone_assigned = preg_replace("/^61/", "+61", $phone_assigned);
  
    exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_assigned.' "'.$description.' '.$body.'"');
}