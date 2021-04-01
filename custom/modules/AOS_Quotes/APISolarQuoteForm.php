<?php
    require_once('include/SugarPHPMailer.php');

    /// Data Request
    $file_to_attach = array();
    $solarOptions = json_encode(($_POST['products']), true);
    
    $type_form = $_POST['type_form'];
    $email_customer = $_POST['email_customer'];
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    // .:nhantv:. Add sms_send from form
    $sms_send = $_POST['sms_send'];


    $primary_address_city = $_POST['suburb_customer'];
    $primary_address_state = $_POST['state_customer'];
    $primary_address_postalcode = $_POST['postcode_customer'];
    $your_street = $_POST['your_street'];

    $phone_number = $_POST['phonenumber'];
    // $strorey = $_POST['storeys'];
    $solar_aspiration = $_POST['solar_aspiration'];
    $distributor = $_POST['distributor'];
    $option_distributor = $_POST['option_distributor'];
    $first_solar = $_POST['first_solar'];
    $roof_type = $_POST['roof_type'];
    $roof_pitch = $_POST['roof_pitch'];
    $storeys = $_POST['many_storeys'];
    $phanes = $_POST['many_phanes'];
    $meter_type = $_POST['meter_type'];
    $main_switch = $_POST['main_switch'];
    $distancetoswitch = $_POST['distancetoswitch'];
    $external_or_internal = $_POST['external_or_internal'];
    $prepared_by = $_POST['prepared_by'];
    $hear_about = $_POST['hear_about'];
    $preferred = $_POST['preferred'];
    $vic_rebate = $_POST['solar_vic_rebate'];
    $vic_loan = $_POST['solar_vic_loan'];
    $decription_internal_notes = $_POST['notes'];
    
    //VUT - S - Add data quote_note_inputs_c
    $data_solar_input = array(
        "solar_aspiration" => $solar_aspiration,
        'electricity_distributor' => $distributor,
        'first_solar_pv_system' => $first_solar,
        'roof_type' => strtoupper($roof_type),
        'roof_pitch' => $roof_pitch,
        'storeys' => $storeys,
        'phases' => $phanes,
        'meter_type' => $meter_type,
        'main_switch' => $main_switch,
        'distance_from_inverter_to_main_switchboard' => $distancetoswitch,
        'external_or_internal_switchboard' => $external_or_internal,
    );
    //VUT - E - Add data quote_note_inputs_c
    /// Assign User
    // $assigned_user = $_POST['assigned_user'];
    // $lead_source = "PE_website_quote_form";
    if($prepared_by == 'Matthew Wright') {
        $assigned_user = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
        $email_assigigned = 'matthew.wright@pure-electric.com.au';
    } else if($prepared_by == 'Paul Szuster') {
        $assigned_user = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
        $email_assigigned = 'paul.szuster@pure-electric.com.au';
    } else if($prepared_by == 'Michael Golden') {
        $assigned_user = '71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb';
        $email_assigigned = 'michael.golden@pure-electric.com.au';
    } else if($prepared_by == 'PE Admin') {
        $assigned_user = '1';
    } else {
        $assigned_user = '1';
    }
    
    /// check Lead existing
    $db = DBManagerFactory::getInstance();
    $sql ="SELECT * FROM leads WHERE deleted = 0 AND first_name = '".$first_name."' AND last_name = '".$last_name."' LIMIT 1";
    $ret = $db->query($sql);
    $row = $db->fetchByAssoc($ret);

    $quoteId = '';

    $existed_lead = false;
    if($ret->num_rows > 0){
        $lead = new Lead();
        $lead ->retrieve($row['id']);
        if($lead->id){
            if($email_customer == $lead->email1)
                $existed_lead = true;
            else
                $existed_lead = false; 
        }else{
            $existed_lead = false;
        }
    } else {
        $existed_lead = false; 
    }

    // Add Product to List Build

    /// If Lead is not existing 
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
        // $new_lead->description = $decription_internal_notes;
        $new_lead->lead_source = $lead_source;
        $new_lead->lead_source_co_c = 'PureElectric';
        // $new_lead->status = 'Converted'; //VUT- create status NEW for new Lead
        $new_lead->assigned_user_id = $assigned_user;

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

        ///// Create Quote Solar

        $quote = new AOS_Quotes();
        $quote->name = $first_name.' '.$last_name.' '.$primary_address_city.' '.$primary_address_state." Solar";
        $quote->account_firstname_c = $first_name;
        $quote->account_lastname_c = $last_name;
        $quote->quote_type_c = 'quote_type_solar';
        $quote->install_address_postalcode_c =  $primary_address_postalcode;
        $quote->install_address_state_c = $primary_address_state;
        $quote->install_address_city_c = $primary_address_city;
        $quote->billing_address_postalcode = $primary_address_postalcode;
        $quote->billing_address_state = $primary_address_state;
        $quote->billing_address_city = $primary_address_city;
        $quote->leads_aos_quotes_1leads_ida = $new_lead->id;
        $quote->billing_contact_id = $contact->id;
        $quote->billing_account_id = $account->id;
        $quote->billing_address_street = $your_street;
        $quote->install_address_c = $your_street;
        $quote->special_notes_c = $decription_internal_notes;
        $quote->lead_source_c = $hear_about;
        $quote->lead_source_co_c = 'PureElectric';
        $quote->assigned_user_id = $assigned_user;
        $quote->the_quote_prepared_c = "solar_quote_form";
        // if(  $first_solar == "yes_first_solar" ){
        //     $quote->first_solar_c = "bool_true";
        // }else {
        //     $quote->first_solar_c = "bool_false";
        // }
        $quote->quote_note_inputs_c = json_encode($data_solar_input); 
        // if( $strorey == "Double Storey"){
        //     $quote->Double_Storey = 1;
        // }else {
        //     $quote->Double_Storey = '';
        // }
        if( $phanes == "Three Phases"){
            $quote->meter_phase_c = '3';
        }else if( $phanes == "Two Phases") {
            $quote->meter_phase_c = '2';
        }else if( $phanes == "Single Phase") {
            $quote->meter_phase_c = '1';
        }
        $quote->solar_pv_pricing_input_c = $solarOptions;
        if( $vic_rebate == "yes_rebate"){
            $quote->vic_rebate_c = 1;
        }else {
            $quote->vic_rebate_c = 0;
        }
        if( $vic_loan == "yes_loan"){
            $quote->vic_loan_c = 1;
        }else {
            $quote->vic_loan_c = 0;
        }
        $quote->save();
        //VUT-Change Lead status New -> Converted
        $save_lead = new Lead();
        $save_lead->retrieve($new_lead->id);
        $save_lead->status = 'Converted';
        $save_lead->save();        
        
        $quote_id = $quote->id;
        $leads_intenal_notes = new  pe_internal_note();
        $leads_intenal_notes->type_inter_note_c = 'status_updated';
        
        $leads_intenal_notes->description =  $decription_internal_notes;
        $leads_intenal_notes->save();
        
        $leads_intenal_notes->load_relationship('leads_pe_internal_note_1');
        $leads_intenal_notes->leads_pe_internal_note_1->add($new_lead->id);

        // Quote Internal Note

        $leads_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
        $leads_intenal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
        $file_to_attach = upload_file_form_solar($quote_id);
        echo  $quote_id;
    } else {
        $lead =  new Lead();
        $lead->retrieve($row['id']);

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
        $lead->the_quote_prepared_c = "solar_quote_form";

        // $lead->description = $decription_internal_notes;
        $lead->status = 'Converted';
        $lead->save();

        //get bean quote
        $quote = new AOS_Quotes();
        date_default_timezone_set('UTC');
        $quote->quote_date_c = date('Y-m-d H:i:s', time());
        $quote->name = $first_name.' '.$last_name.' '.$primary_address_city.' '.$primary_address_state." Solar";
        $quote->account_firstname_c = $first_name;
        $quote->account_lastname_c = $last_name;
        $quote->quote_type_c = 'quote_type_solar';
        $quote->install_address_postalcode_c =  $primary_address_postalcode;
        $quote->install_address_state_c = $primary_address_state;
        $quote->install_address_city_c = $primary_address_city;
        $quote->billing_address_postalcode = $primary_address_postalcode;
        $quote->billing_address_state = $primary_address_state;
        $quote->billing_address_city = $primary_address_city;
        $quote->leads_aos_quotes_1leads_ida = $new_lead->id;
        $quote->billing_contact_id = $lead->contact_id;
        $quote->billing_account_id = $lead->account_id;
        $quote->billing_address_street = $your_street;
        $quote->install_address_c = $your_street;
        $quote->special_notes_c = $decription_internal_notes;
        $quote->lead_source_c = $hear_about;
        $quote->lead_source_co_c = 'PureElectric';
        $quote->assigned_user_id = $assigned_user;
        $quote->the_quote_prepared_c = "solar_quote_form";
        // if(  $first_solar == "yes_first_solar" ){
        //     $quote->first_solar_c = "bool_true";
        // }else {
        //     $quote->first_solar_c = "bool_false";
        // }
        $quote->quote_note_inputs_c = json_encode($data_solar_input);
        // if( $strorey == "Double Storey"){
        //     $quote->Double_Storey = 1;
        // }else {
        //     $quote->Double_Storey = '';
        // }
        if( $phanes == "Three Phases"){
            $quote->meter_phase_c = '3';
        }else if( $phanes == "Two Phases") {
            $quote->meter_phase_c = '2';
        }else if( $phanes == "Single Phase") {
            $quote->meter_phase_c = '1';
        }
        $quote->distributor_c = $option_distributor;

        $quote->main_switch_c = $main_switch;
        $quote->meter_type_c = str_replace(" ", "" ,$meter_type);
        $quote->inverter_to_mainswitch_c = $distancetoswitch."m";
        $quote->external_or_internal_c = $external_or_internal;
        
        $quote->solar_pv_pricing_input_c = $solarOptions;

        $quote->leads_aos_quotes_1leads_ida = $lead->id;
        // $quote->description = $decription_internal_notes;
        $quote->billing_contact_id = $row['contact_id'];
        $quote->billing_account_id = $row['account_id'];
        if( $vic_rebate == "yes_rebate"){
            $quote->vic_rebate_c = 1;
        }else {
            $quote->vic_rebate_c = 0;
        }
        if( $vic_loan == "yes_loan"){
            $quote->vic_loan_c = 1;
        }else {
            $quote->vic_loan_c = 0;
        }
        $quote->save();
        $quote_id = $quote->id;

        $leads_intenal_notes = new  pe_internal_note();
        $leads_intenal_notes->type_inter_note_c = 'status_updated';
        
        $leads_intenal_notes->description =  $decription_internal_notes;
        $leads_intenal_notes->save();
        
        $leads_intenal_notes->load_relationship('leads_pe_internal_note_1');
        $leads_intenal_notes->leads_pe_internal_note_1->add($row['id']);

        // Quote Internal Note

        $leads_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
        $leads_intenal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
        $file_to_attach = upload_file_form_solar($quote_id);
        echo  $quote_id;
    }

    //create call by work-flow
    $workflow = new AOW_WorkFlow();
    $workflow->retrieve('5ac74a36-1248-6b28-85be-5c7f1832a865');
    $workflow->run_actions($quote, true);
    
    if($solarOptions !=""){

        global $current_user;

        $macro_nv = array();
        $quote = new AOS_Quotes();
        $focus = $quote->retrieve($quote_id);

        $lead =  new Lead();
        $lead->retrieve($focus->leads_aos_quotes_1leads_ida);

        $contact =  new Contact();
        $contact->retrieve($focus->billing_contact_id);

        if(!$focus->id) return;
        /**
         * @var EmailTemplate $emailTemplate
         */

        $emailTemplate = BeanFactory::getBean(
            'EmailTemplates',
            //'ba4a72df-d9e3-7a20-d7b2-5d5bb366c7a4'
            '9d9f03ae-fe75-68d0-72ad-5d5b95cda15b'
        );
        $name = $emailTemplate->subject;
        $description_html = $emailTemplate->body_html;
        $description = $emailTemplate->body;
        $templateData = $emailTemplate->parse_email_template(
            array(
                'subject' => $name,
                'body_html' => $description_html,
                'body' => $description,
            ),
            $focusName,
            $focus,
            $macro_nv
        );

        $account_id    = "a4d3c2c4-484e-8dfd-3d52-59f93249c95b";
        $current_user = new User();
        $current_user->retrieve($account_id);
        $defaultEmailSignature = $current_user->getSignature('1df22928-d247-afc1-15b8-5b222bb12089');

        if($attachmentBeans) {
            $this->bean->status = "draft";
            $this->bean->save();
            foreach($attachmentBeans as $attachmentBean) {
                $noteTemplate = clone $attachmentBean;
                $noteTemplate->id = create_guid();
                $noteTemplate->new_with_id = true;
                $noteTemplate->parent_id = $this->bean->id;
                $noteTemplate->parent_type = 'Emails';

                $noteFile = new UploadFile();
                $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                $noteTemplate->save();
                $this->bean->attachNote($noteTemplate);
            }
        }

        $meter_phase_c  = array('','Single Phase','Two Phase (Rural Only)','Three Phase');
        $distributor_c = array("0"=>"",
                "4" => "Citipower",
                "5" => "Jemena",
                "6"=>"Powercor",
                "7"=>"SP Ausnet",
                "8"=>"United Energy Distribution",
                "1"=>"Western Power",
                "13"=>"South Australia Power Network",
                "2"=>"Energex",
                "3" => "Ergon",
                "9" => "Essential Energy",
                "10"=>"Ausgrid",
                "12"=>"Endeavour Energy",
                "11"=>"ActewAGL",
                "14"=>"AusNet Electricity Services Pty Ltd",
        );
        // $gutter_height_c = array( "",
        //                         '0-3m',
        //                         '3-5m',
        //                         '5m - 10m',
        //                         '10m - 15m',
        //                         '15m+',
        //                         'Other');
        if(  $storeys == "Double Storey"){
            $gutter_height_c = "3-5m";
        }else {
            $gutter_height_c = "0-3m";
        }
        $roof_type_c    = array('Tin'=>'Tin',
                                'Tile'=>'Tile',
                                'klip_loc'=>'Klip Loc',
                                'Concrete'=>'Concrete',
                                'Trim_Deck'=>'Trim Deck',
                                'Insulated'=>'Insulated',
                                'Asbestos'=>'Asbestos',
                                'Ground_Mount'=>'Ground Mount',
                                'Terracotta'=>'Terracotta',
                                'Other'=>'Other');

        $to = $lead->first_name.' '.$lead->last_name." <$lead->email1>";
        $subject = $templateData['subject'];
        $body_html = $templateData['body_html'];
        $body = $templateData['body_html'];

        //replace data for subject - VUT - 2020/03/04
        $subject = str_replace("\$aos_quotes_billing_account",  $focus->billing_account, $subject);
        $subject = str_replace("\$aos_quotes_site_detail_addr__city_c",  $focus->install_address_city_c , $subject);
        $subject = str_replace("\$aos_quotes_site_detail_addr__state_c",  $focus->install_address_state_c.' ' , $subject);
        
        //replace data for body
        $body_html = str_replace("\$contact_first_name",  $contact->first_name , $body_html);
        $body_html = str_replace("\$table_solar_quote_inputs",  '' , $body_html);
        $body_html = str_replace("\$aos_solar_vic_loan_c",  '' , $body_html);

        $body_html = str_replace("\$aos_quotes_installation address_c",  $your_street.' '.$primary_address_city.' '.$primary_address_state.' '.$primary_address_postalcode , $body_html);
        $body_html = str_replace("\$aos_quotes_distributor_c",  $distributor , $body_html);
        $body_html = str_replace("\$aos_quotes_first_solar_c", $first_solar , $body_html);
        $body_html = str_replace("\$aos_quotes_roof_type_c",  $roof_type , $body_html);
        $body_html = str_replace("\$aos_quotes_roof_pitch_c",  $roof_pitch , $body_html);
        $body_html = str_replace("\$aos_quotes_stroreys_c",  $storeys , $body_html);
        $body_html = str_replace("\$aos_quotes_meter_phase_c",  $meter_phase_c[$focus->meter_phase_c] , $body_html);
        $body_html = str_replace("\$aos_quotes_main_switch_c",  $main_switch , $body_html);
        $body_html = str_replace("\$aos_quotes_meter_type_c",  $meter_type , $body_html);
        $body_html = str_replace("\$aos_quotes_external_internal_c",  $external_or_internal ." Switchboard" , $body_html);
        $body_html = str_replace("\$aos_quotes_gutter_height_c",   $gutter_height_c , $body_html);
        $body_html = str_replace("\$aos_quotes_preferred_c",   ($preferred == "I'LL CREATE MY OWN") ? "No": $preferred , $body_html);
        if( $decription_internal_notes !=""){
            $body_html = str_replace("\$aos_quotes_special_notes_c", $decription_internal_notes , $body_html);
        }else {
            $body_html = str_replace("\$aos_quotes_special_notes_c", "" , $body_html);
        }
        
        if(  $primary_address_state == "VIC"){
            $body_html = str_replace("\$aos_quote_solar_vic_rebate_c",   (($vic_rebate == "yes_rebate") ? "Yes": 'No')  , $body_html);
            $body_html = str_replace("\$aos_quote_solar_loan_c",   (($vic_rebate == "yes_loan") ? "Yes": 'No')  , $body_html);
            // $html_vic = '<table style="margin-bottom:20px;text-align:left;border-collapse:collapse;width:735px;">
            //             <tbody>
            //             <tr>
            //                 <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Rebate to your solar pricing?</td>
            //                 <td style="padding: 5px; border: .5px solid #8a8a8a;">'. (($vic_rebate == "yes_rebate") ? "Yes": 'No') .'</td>
            //             </tr>
            //             <tr>
            //                 <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Loan to your solar pricing?</td>
            //                 <td style="padding: 5px; border: .5px solid #8a8a8a;width: 45%;" >'. (($vic_loan == "yes_loan") ? "Yes": 'No') .'</td>
            //             </tr>
            //             </tbody></table>';
            // $body_html = str_replace("\$aos_solar_vic_loan_c", $html_vic , $body_html);
            // $body_html = str_replace("\$aos_quotes_loan_c",    ($vic_loan == "yes_loan") ? "Yes": 'No' , $body_html);
        }else {
            $body_html = str_replace("\$aos_quote_solar_vic_rebate_c",  "No" , $body_html);
            $body_html = str_replace("\$aos_quote_solar_loan_c",   "No"  , $body_html);
        }
        

        $pricing_options = $focus->solar_pv_pricing_input_c;

        if($pricing_options != ''){
            $pricings = json_decode(html_entity_decode($pricing_options));

            $solar_pricing_options = '';
            for ($i=1; $i < 7 ; $i++) { 
                if($pricings->{'base_price_'.$i} != "" || $pricings->{'base_price_'.$i} != 0){
                    switch ( $pricings->{'inverter_type_'.$i} ){
                        case "Primo 3":
                            $inverter = "Fronius Primo 3.0-1 3kW";
                            break;
                        case "Primo 4":
                            $inverter = "Fronius Primo 4.0-1 4kW";
                            break;
                        case "Primo 5":
                            $inverter = "Fronius Primo 5.0-1-I 5kW";
                            break;
                        case "Primo 6":
                            $inverter = "Fronius Primo 6.0-1 6kW"; 
                            break;
                        case "Primo 8.2":
                            $inverter = "Fronius Primo 8.2-1 8.2kW"; 
                            break;
                        case "Symo 5":
                            $inverter = "Fronius Symo 5 Dual Tracker"; 
                            break;
                        case "Symo 6":
                            $inverter = "Fronius Symo 6 Dual Tracker"; 
                            break;
                        case "Symo 8.2":
                            $inverter = "Fronius Symo 8.2 Dual Tracker"; 
                            break;
                        case "Symo 10":
                            $inverter = "Fronius Symo 10 Dual Tracker"; 
                            break;
                        case "Symo 15":
                            $inverter = "Fronius Symo 15.0kW Dual Tracker 10yr warranty"; 
                            break;
                        case "SYMO 20":
                            $inverter = "Fronius Symo 20.0kW Dual Tracker 10yr warranty"; 
                            break;
                        case "IQ7X":
                            $inverter = "Enphase IQ7X 315W Micro Inverter" ;
                            break; 
                        case "IQ7+":
                            $inverter = "Enphase IQ7+ 290W Micro Inverter";
                            break;
                        case "S Edge 3G": 
                            $inverter = "SolarEdge 3G";
                            break; 
                        case "S Edge 5G": 
                            $inverter = "SolarEdge 5G";
                            break; 
                        case "S Edge 6G":
                            $inverter = "SolarEdge 6G" ;
                            break; 
                        case "S Edge 8G":
                            $inverter = "SolarEdge 8G"; 
                            break;
                        case "S Edge 8 3P":
                            $inverter = "SolarEdge 8 3P"; 
                            break;
                        case "S Edge 10G":
                            $inverter = "SolarEdge 10G";
                            break;
                        // case "Growatt 5":
                        //  $inverter = "Growatt 5000TL-X Dual MPPT 5kW"; 
                        // break;
                        // case "Growatt 6":
                        //  $inverter = "Growatt 6000TL-X Dual MPPT 6kW"; 
                        // break;
                        case "Sungrow 3":
                            $inverter = "Sungrow SG3K-D 3kW Dual MPPT WiFi"; 
                            break;
                        case "Sungrow 5":
                            $inverter = "Sungrow SG5K-D 5kW Dual MPPT WiFi"; 
                            break;
                        case "Sungrow 8":
                            $inverter = "Sungrow SG8K-D PREMIUM 8kW Dual MPPT WiFi";
                            break;
                        case "Sungrow 10 3P":
                            $inverter = "Sungrow SG-10KTL-MT 10kW Three Phase";
                            break;
                        case "Sungrow 15 3P":
                            $inverter = "Sungrow SG-15KTL-M 15kW Three Phase";
                            break;
                    }
                    if($i == 4 ){
                        $solar_pricing_options .= '<div style="clear:left"></div>';
                    }
                    $pm = 100;
                    $price_kw = round($pricings->{'customer_price_'.$i}/($pricings->{'total_kW_'.$i}*1000), 2);
                    if( $vic_rebate == 'yes_rebate'){
                        $str_vicreabte = 1850;
                        if( $vic_loan == 'yes_loan'){
                            $reabte_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte;
                            $loan_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte - $str_vicreabte;
                            // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' - $1888 (Solar VIC Rebate)'.' - $1888 (Solar VIC Loan) = $'. $loan_price.' ($'.$price_kw.'/W)</p>';
                            $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width:290px;border: 1px solid #f6ebd9;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                            <div class="op_header" style="width: 100%;position: inherit;">
                                                                <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;"><p style="margin: unset;">' . $i . '</p></div>
                                                                <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;    background-image: linear-gradient(90deg,#ea9e23,white,#efb352);position: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                    <p style="color: red;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 2px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                </div>
                                                            </div>
                                                            <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                                <div style="font-size: 13px;">' . $inverter . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_3_'.$i})? $pricings->{'extra_3_'.$i} : '<br>') . '</div>
                                                            </div>
                                                            <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                        <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'sgp_system_price_'.$i} . '</span></div>
                                                                        <div><span >Less STCs (GST N/A)</span><span  style="float: right;color:red">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                        <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                        <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . $pricings->{'customer_price_'.$i} . '</span></div>
                                                                        <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                        <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                        <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                        <div><span style="margin-top:8px;">Out of Pocket Price <small>(inc. GST)</small></span><span  style="float: right;">$'.$reabte_price.'</span></div>
                                                                        <div><span >Interest Free Loan <small>(inc. GST)</small></span><span  style="float:right;color:red;">$-'.$str_vicreabte.'</span></div>
                                                                        <div><small>Payable to Solar VIC</small></div>
                                                                        <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                        <div><span >Up-front Price <small>(inc. GST)</small></span></div>
                                                                        <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                            <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$</span>
                                                                            <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$loan_price.'</span>
                                                                        </div>
                                                            </div>
                                                            <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;">
                                                            </div>
                                                    </div>';// <span class="after" style="color: #3b3b3b;font-weight: 600;">AUD</span>

                        }else {
                            $reabte_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte;
                            // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' - $1888 (Solar VIC Rebate) = $'. $reabte_price.' ($'.$price_kw.'/W)</p>';
                            $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width:290px;border: 1px solid #f6ebd9;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                            <div class="op_header" style="width: 100%;position: inherit;">
                                                                <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;"><p style="margin: unset;">' . $i . '</p></div>
                                                                <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;    background-image: linear-gradient(90deg,#ea9e23,white,#efb352);position: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                    <p style="color: red;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 2px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                </div>
                                                            </div>
                                                            <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                                <div style="font-size: 13px;">' . $inverter . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                                <div style="font-size: 13px;">' . (($pricings->{'extra_3_'.$i})? $pricings->{'extra_3_'.$i} : '<br>') . '</div>
                                                            </div>
                                                            <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                        <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'total_price_'.$i} . '</span></div>
                                                                        <div><span >Less STCs (GST N/A)</span><span  style="float: right;color:red">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                        <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                        <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . $pricings->{'customer_price_'.$i} . '</span></div>
                                                                        <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                        <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                        <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                        <div><span >Out of Pocket Price <small>(inc. GST)</small></span></div>
                                                                        <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                            <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$</span>
                                                                            <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$reabte_price.'</span>
                                                                        </div>
                                                            </div>
                                                            <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;">
                                                            </div>
                                                    </div>';
                        }
                    }else{
                        // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' ($'.$price_kw.'/W)</p>';
                        $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width:290px;border: 1px solid #f6ebd9;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                        <div class="op_header" style="width: 100%;position: inherit;">
                                                            <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;"><p style="margin: unset;">' . $i . '</p></div>
                                                            <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;    background-image: linear-gradient(90deg,#ea9e23,white,#efb352);position: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                <p style="color: red;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 2px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                            </div>
                                                        </div>
                                                        <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                            <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                            <div style="font-size: 13px;">' . $inverter . '</div>
                                                            <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                            <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                            <div style="font-size: 13px;">' . (($pricings->{'extra_3_'.$i})? $pricings->{'extra_3_'.$i} : '<br>') . '</div>
                                                        </div>
                                                        <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                    <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'total_price_'.$i} . '</span></div>
                                                                    <div><span >Less STCs (GST N/A)</span><span  style="float: right;color:red">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                    <div style="border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                    <div><span >Discounted Purchase Price</span></div>
                                                                    <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                        <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$</span>
                                                                        <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$pricings->{'customer_price_'.$i}.'</span>
                                                                    </div>
                                                        </div>
                                                        <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;">
                                                        </div>
                                                    </div>';
                    }
                }
            }
            $solar_pricing_options .= '<div style="clear:left"></div>';
            $body_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $body_html);
            $body_html = str_replace("\$aos_quotes_id", $quote_id , $body_html);

        }
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
        $body_html .= "<br><br><br>";
        $body_html .=  $defaultEmailSignature['signature_html'];

        $attachmentBeans = $emailTemplate->getAttachments();

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();  
        $mail->setMailerForSystem();  
        $mail->From = 'info@pure-electric.com.au';  
        $mail->FromName = 'Pure Electric';  
        $mail->Subject = $subject;
        $mail->Body = $body_html;
        $mail->IsHTML(true);
        $mail->AddAddress($email_customer);
        $array = array();
        // foreach($attachmentBeans as $attachment) {
    
        //     $noteTemplate = clone $attachment;
        //     $noteTemplate->id = create_guid();

        //     $noteFile = new UploadFile();
        //     $noteFile->duplicate_file($attachment->id, $noteTemplate->id, $noteTemplate->filename);

        //     $noteTemplate->save();

        //     $file_name = $attachment->filename;
        //     $filename = $attachment->id . $attachment->filename;
        //     $file_location = "upload/".$attachment->id;
        //     $mime_type = $attachment->file_mime_type;
        //     $filename = substr($filename, 36, strlen($filename)); 

        //     $mail->AddAttachment($file_location, $file_name, 'base64', $mime_type);
        // }
        // print_r($file_to_attach);
        foreach($file_to_attach as $file_attach) {
            $mail->AddAttachment($file_attach['folderName'], $file_attach['fileName'], 'base64', $file_attach['file_mime_type']);
        }
        $mail->AddCC($email_assigigned);
        $mail->AddCC('info@pure-electric.com.au');
        // $mail->AddCC('ngoanhtuan2510@gmail.com');
        $mail->prepForOutbound();
        $mail->setMailerForSystem();  
        $sent = $mail->Send();

        // .:nhantv:. Add logic to check that "Quote via SMS?" is selected
        if($sms_send === "Yes"){
            $phone = preg_replace("/^0/", "+61", preg_replace('/\D/', '',  $phone_number));

            $message_dir = '/var/www/message';
            $admin_name = "Paul";

            $body_sms = "Hi ".$contact->first_name.", Thank you for your solar pricing request, we have sent your solar PV pricing options to your email inbox. Kind regards, Pure Electric";
            $body_sms .= "To firm the quote, upload photos via this link : https://pure-electric.com.au/pesolarform/confirm?quote-id=".$quote_id;
            $body_sms .= "To approve option of the quote via this link : https://pure-electric.com.au/confirm_option_acceptance?quote-id=".$quote_id;

            exec("cd " . $message_dir . "; php send-message.php sms " . $phone . ' "' . $body_sms . '"');
        }

    }
    function upload_file_form_solar($quote_id){    
        global $sugar_config;

        $quote = new AOS_Quotes();
        $quote->retrieve($quote_id);
        $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
        $dirName        = $quote->pre_install_photos_c;
        $folderName     = $path . $dirName . '/';
        $thumbnail      = $path . $dirName . '/thumbnail' . '/';
        if (!file_exists($folderName)) {
            mkdir($path . $dirName, 0777, true);
            $folderName = $path . $dirName.'/';
        }
        //thienpb - code - add watermark
        $pricing_options = $quote->solar_pv_pricing_input_c;
        $pricings = json_decode(html_entity_decode($pricing_options));

        $data_option = [];
        $data_option['blank'] = false;
        $data_option['customer_name'] = $quote->account_firstname_c.' '.$quote->account_lastname_c;
        $data_option['address_1'] = $quote->install_address_c;
        $data_option['address_2'] = $quote->install_address_city_c.' '.$quote->install_address_state_c.' '.$quote->install_address_postalcode_c;
        if(count($_POST['files']['data-pe-files-switchboard']['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-pe-files-switchboard']['tmp_name']); $i++) {
                if($_POST['files']['data-pe-files-switchboard']['name'][$i] != ""){
                    $file_type = 'Q'.$quote->number.'_Switchboard_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Switchboard_');
                    $file_type = 'Q'.$quote->number.'_Switchboard_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-switchboard']['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-pe-files-switchboard']['tmp_name'][$i], $folderName.$file_type);
                    // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Switchboard '.$i.' '.$checkgeo.'</a>';
                    $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                        
                    $file_name =  $note->filename;
                    $file_location = $sugar_config['upload_dir'].$note->id;
                    $mime_type = $note->file_mime_type;
                    $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                };
            };
        };
        if(count($_POST['files']['data-pe-files-upclose']['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-pe-files-upclose']['tmp_name']); $i++) {
                if($_POST['files']['data-pe-files-upclose']['name'][$i] != ""){
                    $file_type = 'Q'.$quote->number.'_Photo_upclose_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Photo_upclose_');
                    $file_type = 'Q'.$quote->number.'_Photo_upclose_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-upclose']['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-pe-files-upclose']['tmp_name'][$i], $folderName.$file_type);
                    // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Upclose '.$i.' '.$checkgeo.'</a>';
                    $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                        
                    $file_name =  $note->filename;
                    $file_location = $sugar_config['upload_dir'].$note->id;
                    $mime_type = $note->file_mime_type;
                    $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
            };
        }
        if(count($_POST['files']['data-pe-files-meterbox']['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-pe-files-meterbox']['tmp_name']); $i++) {
                if($_POST['files']['data-pe-files-meterbox']['name'][$i] != ""){
                    $file_type = 'Q'.$quote->number.'_Photo_meterbox_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Photo_meterbox_');
                    $file_type = 'Q'.$quote->number.'_Photo_meterbox_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-meterbox']['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-pe-files-meterbox']['tmp_name'][$i], $folderName.$file_type);
                    // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Photo Meterbox '.$i.' '.$checkgeo.'</a>';
                    $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                        
                    $file_name =  $note->filename;
                    $file_location = $sugar_config['upload_dir'].$note->id;
                    $mime_type = $note->file_mime_type;
                    $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
            };
        }
        if(count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-pe-files-electricity-bill']['tmp_name']); $i++) {
                if($_POST['files']['data-pe-files-electricity-bill']['name'][$i] != ""){
                    $file_type ='Q'.$quote->number.'_Electricity_bill_'.$i.'.'.pathinfo(basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Electricity_bill_');
                    $file_type = 'Q'.$quote->number.'_Electricity_bill_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-electricity-bill']['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-pe-files-electricity-bill']['tmp_name'][$i], $folderName.$file_type);
                    // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Electricity bill '.$i.' '.$checkgeo.'</a>';
                    $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                        
                    $file_name =  $note->filename;
                    $file_location = $sugar_config['upload_dir'].$note->id;
                    $mime_type = $note->file_mime_type;
                    $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);                }
            };
        }
        for( $j = 1; $j <= 6; $j++){
            $data_option['Option_number'] = $j;
            $data_option['Inverter'] = (!empty($pricings->{'inverter_type_'.($j)}) ? $pricings->{'inverter_type_'.($j)} : '');
            $data_option['Panel'] = (!empty($pricings->{'panel_type_'.($j)}) ? $pricings->{'panel_type_'.($j)} : '');
            $data_option['NumberOfPanels'] = ( ((int)$pricings->{'total_panels_'.($j)} > 0) ? $pricings->{'total_panels_'.($j)} : '0').' Panels';

            if(count($_POST['files']['data-design-upload-'.$j]['tmp_name']) > 0) {
                for($i = 0; $i < count($_POST['files']['data-design-upload-'.$j]['tmp_name']); $i++) {
                    if($_POST['files']['data-design-upload-'.$j]['name'][$i] != ""){
                        $file_type = 'Q'.$quote->number.'_Solar_Design'.$j.'_'.$i.'.'.pathinfo( basename($_POST['files']['data-design-upload-'.$j]['name'][$i]), PATHINFO_EXTENSION);
                        $count = checkCountExistPhoto($file_type,$folderName,'_Solar_Design'.$j);
                        $file_type =  'Q'.$quote->number.'_Solar_Design'.$j.'_'.$count.'.'.pathinfo( basename($_POST['files']['data-design-upload-'.$j]['name'][$i]), PATHINFO_EXTENSION );
                        copy($_POST['files']['data-design-upload-'.$j]['tmp_name'][$i], $folderName.$file_type);
                        // $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Solar Design'.$j.'_'.$i.'</a>';
                        create_img_option($folderName,$file_type,$data_option);
                        $note = addToNotes($file_type,$folderName,$parent_id,$parent_type);
                        
                        $file_name =  $note->filename;
                        $file_location = $sugar_config['upload_dir'].$note->id;
                        $mime_type = $note->file_mime_type;
                        $file_to_attach[] = array('folderName' => $file_location, 'fileName' => $file_name , 'file_mime_type'=> $mime_type);
                    };
                }
            };
        }

        // print_r($file_to_attach);
        return $file_to_attach;
    }
    function checkCountExistPhoto($file_type,$folderName,$new_name){
        $data_exist= [];
        $get_all_photo = dirToArray($folderName);
        foreach ($get_all_photo as $photo_exist) {
            if( strpos($photo_exist, $new_name) == true){
                $data_exist[] = $photo_exist;
            }
        }
        $count =  count($data_exist);
        return $count;   
    }
    function dirToArray($dir) { 
       
        $result = array();
        $cdir = scandir($dir); 
        foreach ($cdir as $key => $value) 
        { 
           if (!in_array($value,array(".",".."))) 
           { 
              if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
              { 
                 $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
              } 
              else 
              { 
                 $result[] = $value; 
              } 
           } 
        }
        return $result; 
    }
    function addToNotes($file,$folderName,$parent_id,$parent_type){
        // $listFile = dirToArray($folderName);
        resize_image($file, $folderName);
        
        $noteTemplate = new Note();
        $noteTemplate->id = create_guid();
        $noteTemplate->new_with_id = true; // duplicating the note with files
        $noteTemplate->parent_id = $parent_id;
        $noteTemplate->parent_type = $parent_type;
        $noteTemplate->date_entered = '';
        $noteTemplate->file_mime_type = mime_content_type($folderName.$file);
        $noteTemplate->filename = $file;
        $noteTemplate->name = $file;
        $noteTemplate->save();

        $source =  $folderName.$file ;
        $destination = realpath(dirname(__FILE__) . '/../../../').'/upload/'.$noteTemplate->id;
        if (!symlink($source, $destination)) {
            $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
        }
        
        return $noteTemplate;
    }
    function resize_image($file, $current_file_path) {
        $type = strtolower(substr(strrchr($file, '.'), 1));
        $typeok = TRUE;
        if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
            if(!file_exists ($current_file_path."/thumbnail/")) {
                mkdir($current_file_path."/thumbnail/");
            }
            $thumb =  $current_file_path."/thumbnail/".$file;
            switch ($type) {
                case 'jpg': // Both regular and progressive jpegs
                case 'jpeg':
                    $src_func = 'imagecreatefromjpeg';
                    $write_func = 'imagejpeg';
                    $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                    break;
                case 'gif':
                    $src_func = 'imagecreatefromgif';
                    $write_func = 'imagegif';
                    $image_quality = null;
                    break;
                case 'png':
                    $src_func = 'imagecreatefrompng';
                    $write_func = 'imagepng';
                    $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                    break;
                default: $typeok = FALSE; break;
            }
            if ($typeok){
                list($w, $h) = getimagesize($current_file_path.'/'. $file);
    
                $src = $src_func($current_file_path.'/'. $file);
                $new_img = imagecreatetruecolor(80,80);
                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                $write_func($new_img,$thumb, $image_quality);
                
                imagedestroy($new_img);
                imagedestroy($src);
            }
        } 
    }
    function create_img_option($path,$fullname,$data_option){
        
        //create image png
        $source = $path.$fullname;

        $file_ = explode(".",$fullname);
        
        $type = end($file_);
        unset($file_[count($file_)-1]);
        $filename = implode('.', $file_);

        $new_name='';
        $type ='';
        if (exif_imagetype($source) == 2) {
            $type = 'jpeg';
            $new_name = $path.$filename.'.jpg';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 3){
            $type = 'png';
            $new_name = $path.$filename.'.png';
            rename( $source,$new_name);
        }else if(exif_imagetype($source) == 1){
            $type = 'gif';
            $new_name = $path.$filename.'.gif';
            rename( $source,$new_name);
        } else {
            return;
        }

        //append image info
        $source = $new_name;

        // get size of image source
        list($w_source, $h_source) = getimagesize($source);

        $font = $path.'../arial.ttf';
        // add text for image info
        if($w_source >= 500){
            list($w_info, $h_info) = getimagesize($path.'../dessign-image_full.jpeg');
            $img_info = imagecreatefromjpeg($path.'../dessign-image_full.jpeg');
            $black = imagecolorallocate($img_info, 0, 0, 0);
            $orange = imagecolorallocate($img_info,243, 143, 42);

            if($data_option['blank'] == true){
                imagettftext($img_info,24,0,315,65,$black,$font,'Blank');
            }
            imagettftext($img_info,30,0,145,90,$orange,$font,$data_option['Option_number']);
            imagettftext($img_info,22,0,560,40,$black,$font,$data_option['customer_name']);
            imagettftext($img_info,16,0,560,70,$black,$font,$data_option['address_1']);
            imagettftext($img_info,16,0,560,95,$black,$font,$data_option['address_2']);
            imagettftext($img_info,22,0,215,40,$black,$font,$data_option['NumberOfPanels']);
            imagettftext($img_info,16,0,215,70,$black,$font,$data_option['Panel']);
            imagettftext($img_info,16,0,215,95,$black,$font,$data_option['Inverter']);
        }else{
            $black = imagecolorallocate($img_info, 0, 0, 0);
            $orange = imagecolorallocate($img_info,243, 143, 42);
            list($w_info, $h_info) = getimagesize($path.'../dessign-image_500.png');
            $img_info = imagecreatefrompng($path.'../dessign-image_500.png');
            if($data_option['blank'] == true){
                imagettftext($img_info,14,0,170,35,$orange,$font,'Blank');
            }
            imagettftext($img_info,20,0,72,50,$orange,$font,$data_option['Option_number']);
            imagettftext($img_info,14,0,305,22,$black,$font,$data_option['customer_name']);
            imagettftext($img_info,9,0,305,40,$black,$font,$data_option['address_1']);
            imagettftext($img_info,9,0,305,55,$black,$font,$data_option['address_2']);
            imagettftext($img_info,14,0,110,22,$black,$font,$data_option['NumberOfPanels']);
            imagettftext($img_info,9,0,110,40,$black,$font,$data_option['Panel']);
            imagettftext($img_info,9,0,110,55,$black,$font,$data_option['Inverter']);
        }

        $scale = ($h_info/$w_info);
        $new_w_info = $w_source;
        $new_h_info = intval($w_source*$scale);

        $img_info_resize = imagecreatetruecolor($new_w_info, $new_h_info);
        imagecopyresampled($img_info_resize,$img_info,0,0,0,0,$new_w_info,$new_h_info,$w_info,$h_info);

        // create outputImage
        $outputImage = imagecreatetruecolor($w_source, ($h_source + $new_h_info));
        $white = imagecolorallocate($outputImage, 255, 255, 255);
        imagefill($outputImage, 0, 0, $white);

        $src_function = 'imagecreatefrom'.$type;
        $write_function = 'image'.$type;
        $img_source = $src_function($source);
        
        // merge img_info and img_source to outputImage
        imagecopyresized($outputImage,$img_source,0,0,0,0, $w_source,$h_source,$w_source,$h_source);
        imagecopyresized($outputImage,$img_info_resize,0,$h_source,0,0, $new_w_info,$new_h_info,$new_w_info,$new_h_info);
        header('Content-Type: image/'+$type);
        $write_function($outputImage,$source);

        imagedestroy($img_info);
        imagedestroy($img_source);
        imagedestroy($outputImage);

        //create thumbnail
        if($type == 'gif' || $type == 'jpeg' || $type == 'png') {
            //create thumbnail
            if(!file_exists ($path."thumbnail/")) {
                mkdir($path."thumbnail/");
            }
            $typeok = TRUE;
            $thumb =  $path."thumbnail/".$filename.'.'.$type;
            switch ($type) {
                case 'jpeg':
                    $src_func = 'imagecreatefromjpeg';
                    $write_func = 'imagejpeg';
                    $thumb =  $path."thumbnail/".$filename.'.jpg';
                    $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                    break;
                case 'png':
                    $src_func = 'imagecreatefrompng';
                    $write_func = 'imagepng';
                    $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                    break;
                case 'gif':
                    $src_func = 'imagecreatefromgif';
                    $write_func = 'imagegif';
                    $image_quality = null;
                    break;
                default: $typeok = FALSE; break;
            }
            if($typeok){
                list($w, $h) = getimagesize($new_name);

                $src = $src_func($new_name);
                $new_img = imagecreatetruecolor(80,80);
                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                $write_func($new_img,$thumb, $image_quality);
                
                imagedestroy($new_img);
                imagedestroy($src);
            }
            
        } 
    }
