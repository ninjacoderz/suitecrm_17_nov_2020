<?php
$id =  $_REQUEST['quote_id'];
if(!isset($id) && $id == '') return;
$data_return = [];
$quote = new AOS_Quotes();
$quote->retrieve($id);

// Quote exits
if($quote->id != ""){
    $data_return['quote_note_inputs_c'] = ['quote_note_inputs_c',$quote->quote_note_inputs_c,'Quote Note Inputs'];
    $data_return['billing_address_street'] = ['billing_address_street',$quote->billing_address_street,'Street']; 
    $data_return['billing_address_city'] = ['billing_address_city',$quote->billing_address_city,'City'];
    $data_return['billing_address_state'] = ['billing_address_state',$quote->billing_address_state,'State'];
    $data_return['billing_address_postalcode'] = ['billing_address_postalcode',$quote->billing_address_postalcode,'Postal Code'];
}

// Return Response
echo json_encode($data_return);