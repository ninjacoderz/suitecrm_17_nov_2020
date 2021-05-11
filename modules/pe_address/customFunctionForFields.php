<?php

function street_view($focus, $field, $value, $view)
{
    $html = '';
    // if ($view == 'EditView') {
    //     $result_data = '';
        // if ($focus->map_data != '' || $focus->map_data) {
        //     $map_data = json_decode(html_entity_decode($focus->map_data));
        //     $street_view_url = "https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location={$map_data->location->lat},{$map_data->location->lng}";
        //     $result_data = '<iframe id="street-view-google" src="'.$street_view_url.'" height="300" width="100%" title="Street View"></iframe>';
        // }
        // $html = $result_data;
    // }
    return $html;
}

function satellite_view($focus, $field, $value, $view)
{
    $html = '';
    if ($view == 'EditView' || $view == 'DetailView') {
        $result_data = '';
        $folderID = $focus->installation_pictures_c;
        $url_image_site_details =  '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' . $folderID .'/Image_Site_Detail.jpg' ;
        //check exist files image site map
        if ($folderID != '' && file_exists($_SERVER["DOCUMENT_ROOT"] . $url_image_site_details) ) {   
            $result_data = '<img id="image_satellite" src="'.$url_image_site_details.'"/>';
        }else{
            $result_data = '';
        }
        $html = $result_data;
    }
    return $html;
}
