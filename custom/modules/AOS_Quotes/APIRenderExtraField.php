<?php
include dirname(__FILE__) . '/vardef_list_quote_extra.php';
$html = renderExtraItemsHTML($vardefs_array);

$result = array (
    'data' => json_encode($vardefs_array),
    'template_html' => $html,
);
echo json_encode($result);
// die();

function renderExtraItemsHTML($vardefs_array){
    $display_label = '';
    $field_content = '';
    $fieldHTML = '<div class="col-md-12 col-xs-12 col-sm-12 edit-view-row">';
    $fieldHTML .= '<h3 style="font-size: 16px;font-weight: 600;margin-bottom: 20px;">Extra:</h3>';
    foreach ($vardefs_array as $k => $v) {
        $display_label  = $v['display_label'];
        switch ($v['type']) {
            case 'custom':
                $field_item = '';
                foreach ($v['dataItem'] as $key => $value) {
                    switch ($value['type']) {
                        case 'checkbox':
                            $field_item = '<span>'.$value['display_label'].'</span><input class="custom_fields" type="checkbox" value="" name="'.$key.'" id="'.$key.'" step="'.$value['step'].'" style="margin:0 5px;" />';
                            break;
                        case 'number':
                            $field_item = '<input class="custom_fields" type="number" value="" name="'.$key.'" id="'.$key.'" step="'.$value['step'].'" style="width :50px;" />';
                            break;
                    }
                    $field_content .= $field_item;
                }
                break;
        }
        $tempHTML = '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item item-extras" id="'.$v['name'].'" data-partnumber="'.$v['partnumber'].'">
                        <div class="col-md-6 col-xs-12 col-sm-12 label">$display_label</div>
                        <div class="col-md-6 col-xs-12 col-sm-5 edit-view-field" data-item-id="'.$v['name'].'" type="varchar">
                            $field_content
                        </div>
                    </div>';
        $fieldHTML .= str_replace(['$display_label','$field_content'],[$display_label,$field_content],$tempHTML);
        $field_content = '';
    }
    $fieldHTML .= '</div>';
    return $fieldHTML;
}
function renderDaikinOption($option_array) {
    $option_group = '';
    foreach($option_array as $option) {
        $option_group .= '<option label="'.$option.'" value="'.$option.'">'.$option.'</option>';
    };
    return $option_group;
}
