<?php

function display_account_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $result_data = $focus->billing_account;
                break;
            case 'Leads':
                $result_data = $focus->account_name;
                break;
            default:
                # code...
                break;
        }

        $html .= $result_data;
    }
    return $html;
}

function display_contact_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $result_data = $focus->billing_contact;
                break;
            
            default:
                # code...
                break;
        }

        $html .= $result_data;
    }
    return $html;
}


function mobile_phone_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        $account = new Account();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes': 
                $account->retrieve($focus->billing_account_id);
                break;
            case 'Leads': 
                $account->retrieve($focus->account_id);
                break;            
            default:
                # code...
                break;
        }
        $result_data = $account->mobile_phone_c;
        $html .= $result_data;
    }
    return $html;
}


function email_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        $account = new Account();
        $EmailAddress = '';
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $account->retrieve($focus->billing_account_id);
                $EmailAddress = $account->email1;  
                break;
            case 'Leads':
                $EmailAddress = $focus->email1;  
                break;            
            default:
                # code...
                break;
        }
        $result_data .='<a class="email-link"  onclick="$(document).openComposeViewModal(this);" data-module="AOS_Quotes" data-record-id="'. $focus->id .'" data-module-name="'. $focus->name .'" data-email-address="'.$EmailAddress.'">'.$EmailAddress.'</a>';
        $result_data .='<br><a style="color:blue;" class="email-link-gsearch"  target="_blank" href="https://mail.google.com/#search/'.$EmailAddress.'">GSearch </a>';
        $result_data .= '<a class="copy-email-link" data-email-address="'.$EmailAddress.'" 
        title="Copy '.$EmailAddress.'" onclick="$(document).copy_email_address(this);"
        style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip">&nbsp;<span class="glyphicon glyphicon-copy"></span>
        <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied '.$EmailAddress.'</span></a>';
        $html .= $result_data;
    }
    return $html;
}

function address_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $result_data = $focus->install_address_c . ' '
                .$focus->install_address_city_c . ' '
                .$focus->install_address_state_c . ' '
                .$focus->install_address_postalcode_c;
                break;
            case 'Leads':
                $result_data =  $focus->site_detail_addr__c . ' '
                .$focus->site_detail_addr__city_c . ' '
                .$focus->site_detail_addr__state_c . ' '
                .$focus->site_detail_addr__postalcode_c;
                break;            
            default:
                # code...
                break;
        }

        $html .= $result_data;
    }
    return $html;
}

function image_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        $folderID = '';
        $url_image_site_details = '';
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $folderID = $focus->pre_install_photos_c;
                $url_image_site_details =  $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' . $folderID .'/Image_Site_Detail.jpg' ;
                break;
            case 'Leads':
                $folderID = $focus->installation_pictures_c;
                $url_image_site_details =  $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' . $folderID .'/Image_Site_Detail.jpg' ;
                break;            
            default:
                # code...
                break;
        }
        //check exist files image site map
        if ($url_image_site_details != '' && $folderID != '' && file_exists($url_image_site_details) ) {   
            $result_data = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folderID.'/Image_Site_Detail.jpg?'.time().'">
            <canvas hidden="" id="clipboard"></canvas>';
        }else{
            $result_data = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">Map Template Image</div>
            <canvas hidden="" id="clipboard"></canvas>';
        }

        $html .= $result_data;
    }
    return $html;
}


