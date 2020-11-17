<?php
$id =  $_REQUEST['record_id'];
// $module = $_REQUEST['modules'];
if(!isset($id) && $id == '') return;
$data_return = [];

$lead = new Lead();
$lead->retrieve($id);

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

if($lead->id != ""){
    $data_return['id'] = ['id',$lead->id,'id'];
    $data_return['name'] = ['name',$lead->name,'Name'];
    $data_return['number'] = ['number',$lead->number,'Lead Number'];
    $data_return['first_name'] = ['first_name',$lead->first_name,'First Name'];
    $data_return['last_name'] = ['last_name',$lead->last_name,'Last Name'];
    $data_return['status'] = ['status',$lead->status,'Status'];
    $data_return['account_id'] = ['account_id',$lead->account_id,'Account Id'];
    $data_return['account_name'] = ['account_name',$lead->account_name,'Account'];
    $data_return['billing_address_street'] = ['billing_address_street',$lead->primary_address_street,'Street']; 
    $data_return['billing_address_city'] = ['billing_address_city',$lead->primary_address_city,'City'];
    $data_return['billing_address_state'] = ['billing_address_state',$lead->primary_address_state,'State'];
    $data_return['billing_address_postalcode'] = ['billing_address_postalcode',$lead->primary_address_postalcode,'Postal Code'];
    $data_return['address'] = ['address',$lead->primary_address_street.' '.$lead->primary_address_city.' '.$lead->primary_address_state.' '.$lead->primary_address_postalcode,'Address'];
    $data_return['solargain_quote_number_c'] = ['solargain_quote_number_c',$lead->solargain_quote_number_c,'Solargain Quote Number'];
    $data_return['solargain_lead_number_c'] = ['solargain_lead_number_c',$lead->solargain_lead_number_c,'Solargain Lead Number'];
    $data_return['solargain_tesla_quote_number_c'] = ['solargain_tesla_quote_number_c',$lead->solargain_tesla_quote_number_c,'Solargain Quote Tesla Number'];
    $data_return['billing_account_email'] = ['billing_account_email',$lead->email1,'Email']; 
    $data_return['mobile_phone_c'] = ['mobile_phone_c',$lead->phone_mobile,'Mobile'];
    $data_return['phone_office'] = ['phone_office',$lead->phone_work,'Phone'];
    $data_return['lead_source'] = ['lead_source',$lead->lead_source,'Lead Source'];
    
    // $account = new Account();
    // $account->retrieve($lead->account_id);
    // if($account->id != ""){
    //     $data_return['billing_account_email'] = ['billing_account_email',$account->email1,'Email']; 
    //     $data_return['mobile_phone_c'] = ['mobile_phone_c',$account->mobile_phone_c,'Mobile'];
    //     $data_return['phone_office'] = ['phone_office',$account->phone_office,'Phone'];
    // }else{
    //     $data_return['billing_account_email'] = ['billing_account_email','','Email']; 
    //     $data_return['mobile_phone_c'] = ['mobile_phone_c','','Mobile'];
    //     $data_return['phone_office'] = ['phone_office','','Phone'];
    // }

    //check exist files image site map
    $url_image_site_details =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $lead->installation_pictures_c .'/Image_Site_Detail.jpg' ;
    if (file_exists($url_image_site_details)) {   
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$lead->installation_pictures_c,true];       
    }else{
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$lead->installation_pictures_c,false];
    }
    
    //data site details
    $data_return['roof_type_c'] = ['roof_type_c',$lead->roof_type_c,'Roof Type'];
    $data_return['gutter_height_c'] = ['gutter_height_c',$array_map_gutter_height_c[$lead->gutter_height_c],'Gutter Height'];
    $data_return['nmi_c'] = ['nmi_c',$lead->nmi_c,'NMI (billing account)'];
    $data_return['distributor_c'] = ['distributor_c',$array_map_distributor_c[$lead->distributor_c],'Distributor'];
    $address_site_details = $lead->site_detail_addr__c . ' '
            .$lead->site_detail_addr__city_c . ' '
            .$lead->site_detail_addr__state_c . ' '
            .$lead->site_detail_addr__postalcode_c;
    $data_return['address_site_details'] = ['address_site_details', $address_site_details,'Site Address'];
}

echo json_encode($data_return);
