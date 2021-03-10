<?php

function display_account_site_details($focus, $field, $value, $view)
{
    global $mod_strings, $app_list_strings;

    $html = '';

    if ($view == 'DetailView') {
        $result_data = '';
        // var_dump($focus);die();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $result_data = $focus->billing_account;
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
        // var_dump($focus);die();
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
        // var_dump($focus);die();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $account = new Account();
                $account->retrieve($focus->billing_account_id);
                $result_data = $account->mobile_phone_c;
                break;
            
            default:
                # code...
                break;
        }

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
        // var_dump($focus);die();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $account = new Account();
                $account->retrieve($focus->billing_account_id);

                $result_data .='<a class="email-link"  onclick="$(document).openComposeViewModal(this);" data-module="AOS_Quotes" data-record-id="'. $focus->id .'" data-module-name="'. $focus->name .'" data-email-address="'.$account->email1.'">'.$account->email1.'</a>';
                $result_data .='<br><a style="color:blue;" class="email-link-gsearch"  target="_blank" href="https://mail.google.com/#search/'.$account->email1.'">GSearch </a>';
                $result_data .= '<a class="copy-email-link" data-email-address="'.$account->email1.'" 
                title="Copy '.$account->email1.'" onclick="$(document).copy_email_address(this);"
                style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip">&nbsp;<span class="glyphicon glyphicon-copy"></span>
                <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied '.$account->email1.'</span></a>';
                break;
            
            default:
                # code...
                break;
        }

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
        // var_dump($focus);die();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                $result_data = $focus->install_address_c . ' '
                .$focus->install_address_city_c . ' '
                .$focus->install_address_state_c . ' '
                .$focus->install_address_postalcode_c;
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
        // var_dump($focus);die();
        switch ($focus->module_dir ) {
            case 'AOS_Quotes':
                //check exist files image site map
                $url_image_site_details =  $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' . $focus->pre_install_photos_c .'/Image_Site_Detail.jpg' ;
                if (file_exists($url_image_site_details) &&  $focus->pre_install_photos_c != '') {   
                    $result_data = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/905cd90a-ad69-39de-a630-5a6b1f7d1e74/Image_Site_Detail.jpg?1615339640442">
                    <canvas hidden="" id="clipboard"></canvas>';
                }else{
                    $result_data = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">Map Template Image</div>
                    <canvas hidden="" id="clipboard"></canvas>';
                }

                break;
            
            default:
                # code...
                break;
        }

        $html .= $result_data;
    }
    return $html;
}


