<?php
$module_name = 'pe_smstemplate';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
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
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'include/javascript/mozaik/vendor/tinymce/tinymce/tinymce.min.js',
      ),
      1 => 
      array (
        'file' => 'custom/modules/pe_smstemplate/pe_smstemplate.js',
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
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Email template description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'body_c',
            'label' => 'LBL_BODY',
          ),
        ),
      ),
    ),
  ),
);
;
?>
