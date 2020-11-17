<?php
$module_name = 'pe_message_servicecase';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 'description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'message',
            'comment' => 'Message content',
            'label' => 'LBL_MESSAGE',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'quote_type_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_TYPE',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'sanden_equipment_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SANDEN_EQUIPMENT_TYPE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'error_code',
            'comment' => 'Error code',
            'label' => 'LBL_ERROR_CODE',
          ),
          1 => 
          array (
            'name' => 'error_content',
            'comment' => 'Error Content',
            'label' => 'LBL_ERROR_CONTENT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'manufacturer_diagnostic',
            'comment' => 'Manufacturer Diagnostic Recommended Next Steps',
            'label' => 'LBL_MANUFACTURER_DIAGNOSTIC',
          ),
          1 => 
          array (
            'name' => 'manufacturer_judgement',
            'comment' => 'Manufacturer Judgement and Repair Methods Recommended Next Steps',
            'label' => 'LBL_MANUFACTURER_JUDGEMENT',
          ),
        ),
      ),
    ),
  ),
);
;
?>
