<?php
include dirname(__FILE__) . '/vardef_list_quote.php';

$html_group_check_list = render_html_list_quote_inputs($vardefs_array);
$result = array (
    'data' => json_encode($vardefs_array),
    'template_html' => $html_group_check_list,
);
echo json_encode($result);

function render_html_list_quote_inputs($vardefs_array){
    $html_group_check_list = '';
    foreach ($vardefs_array as $k => $v) {
        $html_group_check_list .= '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item">'
            .'<div class="col-md-6 col-xs-12 col-sm-12 label">'
            .  $v[0]
            . '</div>'
            .'<div class="col-md-6 col-xs-12 col-sm-5 edit-view-field " type="varchar">'
                . '<input type="text" style="width:100%" id="'.$v[1]."_input".'" name="'.$v[1]."_input".'" value="" title="" tabindex="">'
            . '</div>'
            .'</div>' ;
    }
    return $html_group_check_list;
}