<?php
$account_id = $_GET['account_id'];
$contact_id = $_GET['contact_id'];
if($account_id != ''){
    $account = new Account();
    $account->retrieve($account_id);
    if($account->id != ''){
        $contact = new Contact();
        $contact->account_id = $account->id;
        $contact->account_name = $account->name;
        $explode_name = explode(' ',$account->name);
        $contact->first_name = $explode_name[0];
        $contact->last_name = str_replace($explode_name[0],'',$account->name);
        $contact->phone_work = $account->phone_office;
        $contact->phone_mobile = $account->mobile_phone_c;
        $contact->phone_fax = $account->phone_fax;
        $contact->primary_address_street = $account->billing_address_street;
        $contact->primary_address_city = $account->billing_address_city;
        $contact->primary_address_state = $account->billing_address_state;
        $contact->primary_address_postalcode = $account->billing_address_postalcode;
        $contact->primary_address_country = $account->billing_address_country;
        $contact->assigned_user_name = $account->assigned_user_name;
        $contact->assigned_user_id = $account->assigned_user_id;
        $contact->email1 = $account->email1;
        $contact->save();
        echo  $contact->id;
    }
}
if($contact_id != ''){
    $contact = new Contact();
    $contact->retrieve($contact_id);
    if( isset($_GET['plumber_license_number'])){
        $contact->plumber_license_number_c =  $_GET['plumber_license_number'];
        $contact->check_contact_type_c = 'Plumber' ;
    }
    if( isset($_GET['electrician_license_number'])){
        $contact->electrician_license_number_c =  $_GET['electrician_license_number'];
        $contact->check_contact_type_c = 'Electrician' ;
    }
    $contact->save();
    echo  $contact->id;
}
