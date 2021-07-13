<?php

$user = $_REQUEST['user'];
$acc_id = $_REQUEST['acc_id'];
$contact_id = $_REQUEST['contact_id'];
$quote_id = $_REQUEST['quote_id'];
$type = $_REQUEST['type'];

if (isset($type) && $type == 'get_address') {
    $quote = new AOS_Quotes();
    $quote->retrieve($quote_id);
    if ($quote->id) {
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT pe_address.id as add_id  FROM pe_address 
                WHERE pe_address.related_quote_id  = '{$quote->id}' 
                AND pe_address.deleted = 0 LIMIT 1";
        $ret = $db->query($sql);
        if ($ret->num_rows != 0) {
            $row = $db->fetchByAssoc($ret);
            echo $row['add_id'];
        } else {
            echo 'notyet';
        }
    } else {
        echo 'error';
    }
}

if (isset($quote_id) && $quote_id != '' && !isset($type)) {
    $quote = new AOS_Quotes();
    $quote->retrieve($quote_id);
    if ($quote->id) {
        $street = $_REQUEST['street'];
        $city = $_REQUEST['city'];
        $state = $_REQUEST['state'];
        $postcode = $_REQUEST['postcode'];
        $country = $_REQUEST['country'];
        //update install address quote
        $quote->install_address_c = $street;
        $quote->install_address_city_c = $city;
        $quote->install_address_state_c = $state;
        $quote->install_address_postalcode_c = $postcode;
        $quote->save();
        //update quote

        $distributor = $_REQUEST['distributor'];
        $retailer = $_REQUEST['retailer'];
        $nmi = $_REQUEST['nmi'];
        $address_nmi = $_REQUEST['address_nmi'];
        $meter_number = $_REQUEST['meter_number'];

        $db = DBManagerFactory::getInstance();
        $sql = "SELECT pe_address.id as add_id  FROM pe_address 
        WHERE pe_address.related_quote_id  = '{$quote->id}' 
        AND pe_address.deleted = 0 LIMIT 1";

        $ret = $db->query($sql);
        if($ret->num_rows == 0){
            $address = new pe_address();
            //relate Account/Contact
            $address->billing_account_id = $acc_id;
            $address->billing_contact_id = $contact_id;
            $address->related_quote_id = $quote_id; 
            $address->assigned_user_id = $user;
            //address
            $address->billing_address_street = $street;
            $address->billing_address_city = $city;
            $address->billing_address_state = $state;
            $address->billing_address_postalcode = $postcode;
            $address->billing_address_country = $country;
            $full_address = "{$street}, {$city}, {$state}, {$postcode}";
            $address->name = "{$street} {$city} {$state} {$postcode}";
            $geo = get_lat_long($full_address);
            $address->map_data = json_encode($geo);
            //other
            $address->electricity_distributor = $distributor;
            $address->electricity_retailer = $retailer;
            $address->billing_meter_number = $meter_number;
            $address->nmi = $nmi;
            $address->address_nmi = $address_nmi;
            $address->save();
            echo $address->id;
        } else {
            $row = $db->fetchByAssoc($ret);
            $address = new pe_address();
            $address->retrieve($row['add_id']);
            if ($address->id) {
                //address
                $address->billing_address_street = $street;
                $address->billing_address_city = $city;
                $address->billing_address_state = $state;
                $address->billing_address_postalcode = $postcode;
                $address->billing_address_country = $country;
                $full_address = "{$street}, {$city}, {$state}, {$postcode}";
                $address->name = "{$street} {$city} {$state} {$postcode}";
                $geo = get_lat_long($full_address);
                $address->map_data = json_encode($geo);
                deleteFileMapSatellite($address->installation_pictures_c);
                $address->save();
                echo $address->id;
            } else {
                echo 'error';
            }
        }
    } else {
        echo 'error';
    }
}

//*****FUNTIONC DECLARE */
// function to get the address 
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

function deleteFileMapSatellite($id_folder) {
    $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    $folderName     = $path . $id_folder . '/';
    $thumbnail      = $path . $id_folder . '/thumbnail/Image_Site_Detail.jpg';
    $file = $folderName ."/Image_Site_Detail.jpg";

    if (file_exists($file)) {
        unlink($file);
        unlink($thumbnail);
    }
}
