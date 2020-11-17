<?php
// created: 2020-05-26 05:26:22
$subpanel_layout['list_fields'] = array (
  'number' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_SERVICE_CASE_NUMBER',
    'width' => '5%%',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '40%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'pe_service_case',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'pe_service_case',
    'width' => '5%',
    'default' => true,
  ),
);