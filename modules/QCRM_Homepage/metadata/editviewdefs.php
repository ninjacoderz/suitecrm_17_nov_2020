<?php
$module_name = 'QCRM_Homepage';
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
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
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
            'name' => 'shared',
            'label' => 'LBL_SHARED',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'icons',
            'studio' => 'visible',
            'label' => 'LBL_ICONS',
            'customCode' => '<input name="icons"  id="icons" size="25" type="hidden" value="{$fields.icons.value}"><ul id="icons_ul">{$icons_list}</ul>',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'creates',
            'studio' => 'visible',
            'label' => 'LBL_CREATES',
            'customCode' => '<input name="creates"  id="creates" size="25" type="hidden" value="{$fields.creates.value}"><ul id="creates_ul">{$creates_list}</ul>',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'dashlets',
            'studio' => 'visible',
            'label' => 'LBL_DASHLETS',
            'customCode' => '<input name="dashlets"  id="dashlets" size="25" type="hidden" value="{$fields.dashlets.value}"><ul id="dashlets_ul">{$dashlets_list}</ul>',
          ),
        ),
      ),
    ),
  ),
);
?>
