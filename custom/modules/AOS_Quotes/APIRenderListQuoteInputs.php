<?php
if($_REQUEST['type'] == 'quote_type_solar'){
    include dirname(__FILE__) . '/vardef_list_quote_solar.php';
}else{
    include dirname(__FILE__) . '/vardef_list_quote.php';
}

$html = renderQuoteFieldHTML($vardefs_array);
$result = array (
    'data' => json_encode($vardefs_array),
    'template_html' => $html,
);
echo json_encode($result);
die();

function renderQuoteFieldHTML($vardefs_array){
    $display_label = '';
    $field_content = '';
    $fieldHTML = '<div class="col-md-12 col-xs-12 col-sm-12 edit-view-row">';
    foreach ($vardefs_array as $k => $v) {
        $display_label  = $v['display_label'];
        switch ($v['type']) {
            case 'select':
                $field_content = '<select class="custom_fields" name="'.$k.'" id="'.$k.'" style="width:200px;" title="">'.renderOption($v['list_array']).'</select>';
                break;
            case 'input':
                $field_content = '<input class="custom_fields" type="text" value="" name="'.$k.'" id="'.$k.'" style="width:200px;" />';
                break;
            case 'number':
                $field_content = '<input class="custom_fields" type="number" value="" name="'.$k.'" id="'.$k.'" step="'.$v['step'].'" style="width:70px;" />';
                break;
            case 'multiselect':
                $field_content = '<select class="custom_fields" name="'.$k.'" id="'.$k.'" style="width:260px;" title="" size="6" multiple>'.renderOption($v['list_array']).'</select>';
                break;
        }
        $tempHTML = '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item">
                        <div class="col-md-6 col-xs-12 col-sm-12 label">$display_label</div>
                        <div class="col-md-6 col-xs-12 col-sm-5 edit-view-field " type="varchar">
                            $field_content
                        </div>
                    </div>';
        // if($_REQUEST['type'] == 'quote_type_solar'){
        //     $tempHTML = '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item">
        //                 <div class="col-md-6 col-xs-12 col-sm-12 label">$display_label</div>
        //                 <div class="col-md-6 col-xs-12 col-sm-5 edit-view-field " type="varchar">
        //                     $field_content
        //                 </div>
        //             </div>';
        // }else{
        //     $tempHTML = '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item" style="'.($k == "quote_main_tank_water" ? "display: block": "display:none").'">
        //                 <div class="col-md-6 col-xs-12 col-sm-12 label">$display_label</div>
        //                 <div class="col-md-6 col-xs-12 col-sm-5 edit-view-field " data-next-step="'.$v['next_step'].'" data-next-step-backup="'.$v['next_step_backup'].'" type="varchar">
        //                     $field_content
        //                 </div>
        //             </div>';
        // }
        
        $fieldHTML .= str_replace(['$display_label','$field_content'],[$display_label,$field_content],$tempHTML);
    }
    $fieldHTML .= '</div>';

    return $fieldHTML;
}

function renderOption($option_array) {
    $option_group = '';
    foreach($option_array as $key => $option) {
        $option_group .= '<option label="'.$option.'" value="'.$option.'" data-value-item="'.$key.'">'.$option.'</option>';
    };
    return $option_group;
}
