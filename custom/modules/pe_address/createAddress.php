<?php

$user = $_REQUEST['user'];
$acc_id = $_REQUEST['acc_id'];
$contact_id = $_REQUEST['contact_id'];

$street = $_REQUEST['street'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$postcode = $_REQUEST['postcode'];
$country = $_REQUEST['country'];

$distributor = $_REQUEST['distributor'];
$retailer = $_REQUEST['retailer'];
$nmi = $_REQUEST['nmi'];
$address_nmi = $_REQUEST['address_nmi'];
$meter_number = $_REQUEST['meter_number'];

$address = new pe_address();

//relate Account/Contact
$address->billing_account_id = $acc_id;
$address->billing_contact_id = $contact_id;
$address->assigned_user_id = $user;

//address
$address->billing_address_street = $street;
$address->billing_address_city = $city;
$address->billing_address_state = $state;
$address->billing_address_postalcode = $postcode;
$address->billing_address_country = $country;
$address->name = "{$street}, {$city}, {$state}, {$postcode}";
$geo = get_lat_long($address->name);
$address->map_data = json_encode($geo);
//other
$address->electricity_distributor = $distributor;
$address->electricity_retailer = $retailer;
$address->billing_meter_number = $meter_number;
$address->nmi = $nmi;
$address->address_nmi = $address_nmi;

$address->save();

echo $address->id;

//*****FUNTIONC DECLARE */
// function to get  the address 
function get_lat_long($address) {
    $array = array();
    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo');
 
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
 
    if ($geo['status'] = 'OK') {
        $array['location'] = $geo['results'][0]['geometry']['location'];
        $array['place_id'] =  $geo['results'][0]['place_id'];
    }
 
    return $array;
 }