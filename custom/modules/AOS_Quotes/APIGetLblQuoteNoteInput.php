<?php
// $id =  'ad69ebde-5cf4-bccf-1541-602c478d7785';
$id =  $_REQUEST['quote_id'];
if(!isset($id) && $id == '') return;
$data_return = [];
$quote = new AOS_Quotes();
$quote->retrieve($id);

// Quote exits
if($quote->id != ""){
    // Parse assigned_user_id to Sale consultant
    $assigned_user_id = $quote->assigned_user_id;
    switch ($assigned_user_id){
        case '8d159972-b7ea-8cf9-c9d2-56958d05485e': $prepared_by = 'Matthew Wright';
        break;
        case '61e04d4b-86ef-00f2-c669-579eb1bb58fa': $prepared_by = 'Paul Szuster';
        break;
        case 'b33d5d2f-89fc-ce57-1df9-5e38d4d8e98d': $prepared_by = 'John Hooper';
        break;
        default: $prepared_by = 'PE Admin';
    }
    // Lead data
    $lead = new Lead();
    $lead->retrieve($quote->leads_aos_quotes_1leads_ida);
    // Return data
    $data_return['quote_note_inputs_c'] = ['quote_note_inputs_c',$quote->quote_note_inputs_c,'Quote Note Inputs'];
    $data_return['billing_address_street'] = ['billing_address_street',$quote->billing_address_street,'Street']; 
    $data_return['billing_address_city'] = ['billing_address_city',$quote->billing_address_city,'City'];
    $data_return['billing_address_state'] = ['billing_address_state',$quote->billing_address_state,'State'];
    $data_return['billing_address_postalcode'] = ['billing_address_postalcode',$quote->billing_address_postalcode,'Postal Code'];
    $data_return['own_solar_pv_pricing_c'] = ['own_solar_pv_pricing_c',$quote->own_solar_pv_pricing_c,'Own Solar PV Pricing'];
    // Any note
    $data_return['suite_pricing_infor']['special_notes_c'] = $quote->special_notes_c;
    // Pure Electric Sale Consultant
    $data_return['suite_pricing_infor']['prepared_by'] = $prepared_by;
    // Hear about
    $data_return['suite_pricing_infor']['hear_about'] = $quote->lead_source_c;
    // Info pack

    // Email
    $data_return['suite_pricing_infor']['your_email'] = $lead->email1;
    // First Name
    $data_return['suite_pricing_infor']['first_name'] = $lead->first_name;
    // Last Name
    $data_return['suite_pricing_infor']['last_name'] = $lead->last_name;
    // Phone number
    $data_return['suite_pricing_infor']['phone_mobile'] = $lead->phone_mobile;
    // Your street
    $data_return['suite_pricing_infor']['your_street'] = $lead->primary_address_street;
    // Post code
    $data_return['suite_pricing_infor']['post_code'] = $lead->primary_address_postalcode;
    // Suburb
    $data_return['suite_pricing_infor']['suburb'] = $lead->primary_address_city;
    // State
    $data_return['suite_pricing_infor']['state'] = $lead->primary_address_state;
}

// Return Response
echo json_encode($data_return);