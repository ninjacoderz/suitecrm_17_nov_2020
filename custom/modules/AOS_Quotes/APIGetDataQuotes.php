<?php
$id =  $_REQUEST['record_id'];
if(!isset($id) && $id == '') return;
$data_return = [];
$quote = new AOS_Quotes();
$quote->retrieve($id);
$array_map_distributor_c = array (
    0 => '-Blank-',
    1 => 'Western Power',
    2 => 'Energex',
    3 => 'Ergon',
    4 => 'Citipower',
    5 => 'Jemena',
    6 => 'Powercor',
    7 => 'SP Ausnet',
    8 => 'United Energy Distribution',
    9 => 'Essential Energy',
    10 => 'Ausgrid',
    11 => 'EVO Energy',
    12 => 'Endeavour Energy',
    13 => 'South Australia Power Network',
    14 => 'AusNet Electricity Services Pty Ltd'

);

$array_map_gutter_height_c = array (
    1 => '0-3m',
    2 => '3-5m',
    3 => '5m - 10m',
    4 => '10m - 15m',
    5 => '15m+',
    6 => 'Other',
);

if($quote->id != ""){
    $data_return['id'] = ['id',$quote->id,'id'];
    $data_return['name'] = ['name',$quote->name,'Name'];
    $data_return['status'] = ['status',$quote->stage,'Status'];
    $data_return['account_id'] = ['account_id',$quote->billing_account_id,'Account Id'];
    $data_return['account_name'] = ['account_name',$quote->billing_account,'Account'];
    $data_return['contact_id'] =['contact_id',$quote->billing_contact_id,'Contact Id'];
    $data_return['contact_name'] = ['contact_name',$quote->billing_contact,'Contact'];
    $data_return['billing_address_street'] = ['billing_address_street',$quote->billing_address_street,'Street']; 
    $data_return['billing_address_city'] = ['billing_address_city',$quote->billing_address_city,'City'];
    $data_return['billing_address_state'] = ['billing_address_state',$quote->billing_address_state,'State'];
    $data_return['billing_address_postalcode'] = ['billing_address_postalcode',$quote->billing_address_postalcode,'Postal Code'];
    $data_return['address'] = ['address',$quote->billing_address_street.' '.$quote->billing_address_city.' '.$quote->billing_address_state.' '.$quote->billing_address_postalcode,'Address'];
    $data_return['solargain_quote_number_c'] = ['solargain_quote_number_c',$quote->solargain_quote_number_c,'Solargain Quote Number'];
    $data_return['solargain_lead_number_c'] = ['solargain_lead_number_c',$quote->solargain_lead_number_c,'Solargain Lead Number'];
    $data_return['solargain_tesla_quote_number_c'] = ['solargain_tesla_quote_number_c',$quote->solargain_tesla_quote_number_c,'Solargain Quote Tesla Number'];
    $account = new Account();
    $account->retrieve($quote->billing_account_id);
    if($account->id != ""){
        $data_return['billing_account_email'] = ['billing_account_email',$account->email1,'Email']; 
        $data_return['mobile_phone_c'] = ['mobile_phone_c',$account->mobile_phone_c,'Mobile'];
        $data_return['phone_office'] = ['phone_office',$account->phone_office,'Phone'];
    }else{
        $data_return['billing_account_email'] = ['billing_account_email','','Email']; 
        $data_return['mobile_phone_c'] = ['mobile_phone_c','','Mobile'];
        $data_return['phone_office'] = ['phone_office','','Phone'];
    }

    //check exist files image site map
    $url_image_site_details =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $quote->pre_install_photos_c .'/Image_Site_Detail.jpg' ;
    if (file_exists($url_image_site_details) &&  $quote->pre_install_photos_c != '') {   
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$quote->pre_install_photos_c,true];       
    }else{
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$quote->pre_install_photos_c,false];
    }
    
    //data site details
    $data_return['roof_type_c'] = ['roof_type_c',$quote->roof_type_c,'Roof Type'];
    $data_return['gutter_height_c'] = ['gutter_height_c',$array_map_gutter_height_c[$quote->gutter_height_c],'Gutter Height'];
    $data_return['nmi_c'] = ['nmi_c',$quote->nmi_c,'NMI (billing account)'];
    $data_return['distributor_c'] = ['distributor_c',$array_map_distributor_c[$quote->distributor_c],'Distributor'];
    $address_site_details = $quote->install_address_c . ' '
            .$quote->install_address_city_c . ' '
            .$quote->install_address_state_c . ' '
            .$quote->install_address_postalcode_c;
    $data_return['address_site_details'] = ['address_site_details', $address_site_details,'Site Address'];
}

echo json_encode($data_return);
