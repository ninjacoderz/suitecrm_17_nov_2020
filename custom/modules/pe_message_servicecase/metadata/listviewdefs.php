<?php
$module_name = 'pe_message_servicecase';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'QUOTE_TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_QUOTE_TYPE',
    'width' => '10%',
  ),
  'SANDEN_EQUIPMENT_TYPE_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SANDEN_EQUIPMENT_TYPE',
    'width' => '10%',
  ),
  'ERROR_CODE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ERROR_CODE',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
