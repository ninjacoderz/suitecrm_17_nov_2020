<?php
$module_name = 'pe_internal_note';
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
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/pe_internal_note/Custom_EditView_Pe_internal_note.js',
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
            'name' => 'type_inter_note_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPE_INTER_NOTE',
          ),
          1 => 
          array (
            'name' => 'email_c',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'calls_pe_internal_note_1_name',
          ),
          1 => 
          array (
            'name' => 'calls_pe_internal_note_1_name',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'po_purchase_order_pe_internal_note_1_name',
          ),
          1 => 
          array (
            'name' => 'po_purchase_order_pe_internal_note_1_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
