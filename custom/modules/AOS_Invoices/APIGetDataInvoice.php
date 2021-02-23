<?php
$id =  $_REQUEST['record_id'];
if(!isset($id) && $id == '') return;
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

$data_return = [];
$invoice = new AOS_Invoices();
$invoice->retrieve($id);
$data_site_detail = json_decode(str_replace("&quot;",'"',$invoice->data_json_site_details_c),true);

if($invoice->id != ""){
    $data_return['id'] = ['id',$invoice->id,'id'];
    $data_return['name'] = ['name',$invoice->name,'Name'];
    $data_return['status'] = ['status',$invoice->status,'Status'];
    $data_return['billing_account_id'] = ['billing_account_id',$invoice->billing_account_id,'Account Id'];
    $data_return['billing_account'] = ['billing_account',$invoice->billing_account,'Account'];
    $data_return['billing_address_street'] = ['billing_address_street',$invoice->billing_address_street,'Street']; 
    $data_return['billing_address_city'] = ['billing_address_city',$invoice->billing_address_city,'City'];
    $data_return['billing_address_state'] = ['billing_address_state',$invoice->billing_address_state,'State'];
    $data_return['billing_address_postalcode'] = ['billing_address_postalcode',$invoice->billing_address_postalcode,'Postal Code'];
    $data_return['invoice_type_c'] = ['invoice_type_c',$invoice->invoice_type_c,'Invoice type'];
    //SG Order number
    $data_return['solargain_invoices_number_c'] = ['solargain_invoices_number_c', $invoice->solargain_invoices_number_c ,'Solargain Order Number'];
    //VUT - GP Calculation
    $data_return['sanden_total_costs'] = ['sanden_total_costs',$invoice->sanden_total_costs_c,'Sanden Total Costs'];
    $data_return['sanden_gross_profit'] = ['sanden_gross_profit',$invoice->sanden_gross_profit_c,'Sanden Gross Profit'];
    $data_return['sanden_total_revenue'] = ['sanden_total_revenue',$invoice->sanden_total_revenue_c,'Sub Total Revenue'];
    $data_return['sanden_gprofit_percent'] = ['sanden_gprofit_percent',$invoice->sanden_gprofit_percent_c,'Sanden Gross Profit %'];
    //id xero invoice
    $data_return['xero_invoice_c'] = ['xero_invoice_c',$invoice->xero_invoice_c,'Xero Invoice'];
    $data_return['xero_stc_rebate_invoice_c'] = ['xero_stc_rebate_invoice_c',$invoice->xero_stc_rebate_invoice_c,'Xero STC Rebate Invoice'];
    $data_return['xero_shw_rebate_invoice_c'] = ['xero_shw_rebate_invoice_c',$invoice->xero_shw_rebate_invoice_c,'XERO SHW Rebate Invoice'];
    $data_return['xero_veec_rebate_invoice_c'] = ['xero_veec_rebate_invoice_c',$invoice->xero_veec_rebate_invoice_c,'Xero VEEC Rebate Invoice'];
    
    $data_return['address'] = ['address',$invoice->billing_address_street.' '.$invoice->billing_address_city.' '.$invoice->billing_address_state.' '.$invoice->billing_address_postalcode,'Address'];
    $account = new Account();
    $account->retrieve($invoice->billing_account_id);
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
    $url_image_site_details =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $invoice->installation_pictures_c .'/Image_Site_Detail.jpg' ;
    if (file_exists($url_image_site_details)) {   
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$invoice->installation_pictures_c,true];       
    }else{
        $data_return['installation_pictures_c'] = ['installation_pictures_c',$invoice->installation_pictures_c,false];
    }
    //file design
    $link_file_design = getFileDesign($invoice,'Design_');
    if ($link_file_design == '') {
        $data_return['file_design'] = ['file_design',$link_file_design,false];
    } else {
        $data_return['file_design'] = ['file_design',$link_file_design,true];
    }
    //data site details
    if(is_null($data_site_detail)) {
        $data_site_detail = array (
            'pe_site_details_no_c' => '',
            'sg_site_details_no_c' => '',
            'solargain_quote_number_c' => '',
            'detail_site_install_address_c' => '',
            'detail_site_install_address_city_c' => '',
            'detail_site_install_address_state_c' => '',
            'detail_site_install_address_postalcode_c' => '',
            'detail_site_install_address_country_c' => '',
            'customer_type_c' => '0',
            'gutter_height_c' => '1',
            'roof_type_c' => 'Tin',
            'export_meter_c' => false,
            'potential_issues_c' => 
            array (
              0 => 'Shading',
            ),
            'cable_size_c' => '',
            'connection_type_c' => 'Underground',
            'main_type_c' => '1',
            'meter_number_c' => '',
            'meter_phase_c' => '1',
            'nmi_c' => '',
            'account_number_c' => '',
            'address_nmi_c' => '',
            'name_on_billing_account_c' => '',
            'distributor_c' => '0',
            'energy_retailer_c' => '0',
            'account_holder_dob_c' => '',
          );
    }
    $data_return['roof_type_c'] = ['roof_type_c',$data_site_detail['roof_type_c'],'Roof Type'];
    $data_return['gutter_height_c'] = ['gutter_height_c',$array_map_gutter_height_c[$data_site_detail['gutter_height_c']],'Gutter Height'];
    $data_return['nmi_c'] = ['nmi_c',$data_site_detail['nmi_c'],'NMI (billing account)'];
    $data_return['distributor_c'] = ['distributor_c',$array_map_distributor_c[$data_site_detail['distributor_c']],'Distributor'];
    $address_site_details =  $invoice->install_address_c .' '.$invoice->install_address_city_c .' ' .$invoice->install_address_state_c .' ' .$invoice->install_address_postalcode_c ;
    $address_site_image =  $invoice->install_address_c .', '.$invoice->install_address_city_c .', '.$invoice->install_address_state_c .', ' .$invoice->install_address_postalcode_c ;
    if($address_site_details == ''){
        // sync data two group install address and sitedetail address , but sitedetail address will hide and not use future
        $address_site_details = $data_site_detail['detail_site_install_address_c'] . ' '
        .$data_site_detail['detail_site_install_address_city_c'] . ' '
        .$data_site_detail['detail_site_install_address_state_c'] . ' '
        .$data_site_detail['detail_site_install_address_postalcode_c'];
    }
    $data_return['address_site_image'] = ['address_site_image',$address_site_image,'Image Address'];
    $data_return['address_site_details'] = ['address_site_details', $address_site_details,'Site Address'];
    $data_return['solargain_quote_number_c'] = ['solargain_quote_number_c', $data_site_detail['solargain_quote_number_c'] ,'Solargain Quote Number'];
}

echo json_encode($data_return);


function getFileDesign($invoice, $str) {
    $array_files = [];
    $invoice_file_attachments = scandir($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/");
    foreach ($invoice_file_attachments as $att){
        $source = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/" . $att ;
        if(!is_file($_SERVER['DOCUMENT_ROOT'].$source)) continue;
        if (strpos($att, $str) !== false && mime_content_type($_SERVER['DOCUMENT_ROOT'].$source) == "image/jpeg") {
            $fullname = $_SERVER['DOCUMENT_ROOT'].$source;
            $array_files[$source] = filectime($fullname);
        }
    }
    arsort($array_files);
    if (count($array_files) > 0) {
        return key($array_files);
    } else {
        return '';
    }
}
