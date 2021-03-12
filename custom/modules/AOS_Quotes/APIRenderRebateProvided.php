<?php
include dirname(__FILE__) .'/vardef_checklist_rebates.php';

$rebate_html = renderRebateProvidedHTML($vardefs_array);
$result = array (
    'data_rebate' => json_encode($vardefs_array),
    'template_html_rebate' => $rebate_html,
);
echo json_encode($result);

die;

function renderRebateProvidedHTML($vardefs_array) {
    $display_label = '';
    $field_content = '';
    $fieldHTML = '<div class="col-md-12 col-xs-12 col-sm-12 edit-view-row">';
    foreach ($vardefs_array as $k => $v) {
        $display_label  = $v['display_label'];
        switch ($v['type']) {
            // case 'select':
            //     $field_content = '<select class="custom_rebate_fields" name="'.$k.'" id="'.$k.'" style="width:200px;" title="">/**'.renderOption($v['list_array']).'*/</select>';
            //     break;
            case 'input':
                $field_content = '<input class="custom_rebate_fields" type="text" value="" name="'.$k.'" id="'.$k.'" style="width:200px;" />';
                break;
            case 'number':
                $field_content = '<input class="custom_rebate_fields" type="number" value="" name="'.$k.'" id="'.$k.'" step="'.$v['step'].'" style="width:70px;" />';
                break;
            case 'checkbox':
                $field_content = '<input class="custom_rebate_fields" type="checkbox" value="false" name="'.$k.'" id="'.$k.'"/>';
                break;
        }
        $tempHTML = '<div class="col-md-6 col-xs-12 col-sm-12 edit-view-row-item">
                        <div class="col-md-6 col-xs-12 col-sm-12 label">$display_label</div>
                        <div class="col-md-6 col-xs-12 col-sm-5 edit-view-field " type="varchar">
                            $field_content
                        </div>
                    </div>';
        $fieldHTML .= str_replace(['$display_label','$field_content'],[$display_label,$field_content],$tempHTML);
    }
    $fieldHTML .= '</div>';
    return $fieldHTML;
}