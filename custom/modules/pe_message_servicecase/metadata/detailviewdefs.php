<?php
$module_name = 'pe_message_servicecase';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
          0 => 'date_entered',
          1 => 'date_modified',
        ),
        2 => 
        array (
          0 => 'description',
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
        4 => 
        array (
          0 => 
          array (
            'name' => 'sanden_equipment_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SANDEN_EQUIPMENT_TYPE',
          ),
          1 => 
          array (
            'name' => 'error_code',
            'comment' => 'Error code',
            'label' => 'LBL_ERROR_CODE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
