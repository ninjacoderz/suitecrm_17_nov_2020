<?php
include dirname(__FILE__) .'/vardef_list_po_inputs.php';
$po_type  = $_REQUEST['type'];
switch ($po_type) {
    case 'sanden_supply':
        $rebate_html = renderPlumbingInputsHTML($vardefs_sanden_supply_array);
        $result = array (
            'data_rebate' => json_encode($vardefs_sanden_supply_array),
            'template_html_rebate' => $rebate_html,
        );
        break;
    case 'SolarBOS':
        $rebate_html = renderPlumbingInputsHTML($vardefs_SolarPV_Bos_supply_array);
        $result = array (
            'data_rebate' => json_encode($vardefs_SolarPV_Bos_supply_array),
            'template_html_rebate' => $rebate_html,
        );
        break;
    case 'daikin_supply':
        $rebate_html = renderPlumbingInputsHTML($vardefs_daikin_supply_array);
        $result = array (
            'data_rebate' => json_encode($vardefs_daikin_supply_array),
            'template_html_rebate' => $rebate_html,
        );
        break;
    case 'sanden_plumber':
        $rebate_html = renderPlumbingInputsHTML($vardefs_plumbung_array);
        $result = array (
            'data_rebate' => json_encode($vardefs_plumbung_array),
            'template_html_rebate' => $rebate_html,
        );
        break;
    case 'installer':
        $rebate_html = renderPlumbingInputsHTML($vardefs_daikin_installer_array);
        $result = array (
            'data_rebate' => json_encode($vardefs_daikin_installer_array),
            'template_html_rebate' => $rebate_html,
        );
        break;
}
echo json_encode($result);

die;

function renderPlumbingInputsHTML($vardefs_array) {
    $display_label = '';
    $field_content = '';
    $fieldHTML = '<div class="col-md-12 col-xs-12 col-sm-12 edit-view-row">';
    foreach ($vardefs_array as $k => $v) {
        $display_label  = $v['display_label'];
        switch ($v['type']) {
            case 'select':
                $field_content = '<select class="custom_rebate_fields" data-name="'.$v['display_label'].'" data-id="'.$v['data_id'].'" name="'.$v['name'].'" id="'.$k.'" style="width:200px;" title="">/**'.renderOption($v['list_array']).'*/</select>';
                break;
            case 'input':
                $field_content = '<input class="custom_rebate_fields" data-name="'.$v['display_label'].'" data-id="'.$v['data_id'].'" type="text" value="" name="'.$v['name'].'" id="'.$k.'" style="width:200px;" />';
                break;
            case 'number':
                $field_content = '<input class="custom_rebate_fields" data-name="'.$v['display_label'].'" data-id="'.$v['data_id'].'" type="number" value="'.$v['default_val'].'" name="'.$v['name'].'" id="'.$k.'" min="'.$v['min'].'" max="'.$v['max'].'" style="width:70px;" />';
                break;
            case 'checkbox':
                $field_content = '<input class="custom_rebate_fields" data-name="'.$v['display_label'].'" data-id="'.$v['data_id'].'" type="checkbox" value="'.$k.'" name="'.$v['name'].'" id="'.$k.'"/>';
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
function renderOption($option_array) {
    $option_group = '<option value="0"></option>';
    
    foreach(json_decode($option_array) as $option) {
        $option_group .= '<option label="'.$option.'" value="'.$option.'">'.$option.'</option>';
    };
    return $option_group;
}
