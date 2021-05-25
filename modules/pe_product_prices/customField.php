<?php

function link_website($focus, $field, $value, $view)
{
    $html = '';
    if ($view == 'DetailView') {
        $html = "<a id='{$field}' target='_blank' href='{$value}'>{$value}</a>";
    } 
    
    if ($view == 'EditView') {
        $html = "<input type='text' name='{$field}' id='{$field}' size='30' maxlength='255' value='{$value}' >";
    }
    return $html;
}

function link_product($focus, $field, $value, $view) {
    // $html = '';
    if ($view == 'DetailView') {
        $return_html = '';
        if ($focus->product_id != '') {
            $return_html = "<a target='_blank' href='/index.php?module=AOS_Products&action=EditView&record={$focus->product_id}'>{$value}</a>";
        } else {
            $return_html = "<span class='sugar_field' id='{$field}'>{$value}</span>";
        }
        $html = $return_html;
    }

    if ($view == 'EditView') {
        $return_html = '';
        $return_html = "<input type='text' name='{$field}' id='{$field}' size='30' maxlength='255' value='{$value}' class='sqsEnabled' autocomplete='off'>";
        $html = $return_html;
    }
    return $html;
}

?>