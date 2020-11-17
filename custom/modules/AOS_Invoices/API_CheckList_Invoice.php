<?php
include dirname(__FILE__) . '/vardef_check_list.php';

$html_group_check_list = render_html_checklist($vardefs_array);
$result = array (
    'data' => json_encode($vardefs_array),
    'template_html' => $html_group_check_list,
);
echo json_encode($result);

function render_html_checklist($vardefs_array){
    $html_group_check_list = '';
    foreach ($vardefs_array as $k => $v) {
        $html_group_check_list .= '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
            .'<div class="col-xs-12 col-sm-4 label">'
            .  $v[0]
            . '</div>'
            . '<div class="col-xs-12 col-sm-1 edit-view-field " type="bool" field="'.$v[2].'">'
                .  '<input type="hidden" name="'.$v[2].'" value="0">' 
                .'<input type="checkbox" id="'.$v[2].'" name="'.$v[2].'" value="false" title="" tabindex="0" label="'.$v[0].'">'
            .'</div>'
            .'<div class="col-xs-12 col-sm-2 edit-view-field col-button" type="varchar">' 
            . '</div>'
            .'<div class="col-xs-12 col-sm-5 edit-view-field " type="varchar">'
                . '<textarea style="width:100%" id="'.$v[2]."_textarea".'" name="'.$v[2]."_textarea".'" rows="1" cols="50" title="" tabindex=""></textarea>'
            . '</div>'
            .'</div>' ;
    }
    return $html_group_check_list;
}