<?php
$popupMeta = array (
    'moduleMain' => 'pe_smstemplate',
    'varName' => 'pe_smstemplate',
    'orderBy' => 'pe_smstemplate.name',
    'whereClauses' => array (
  'name' => 'pe_smstemplate.name',
  'assigned_user_id' => 'pe_smstemplate.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'assigned_user_id',
),
'create' =>
  array('formBase' => 'SMSTemplateFormBase.php',
          'formBaseClass' => 'SMSTemplateFormBase',
          'getFormBodyParams' => array('','','SMSTemplateSave'),
          'createButton' => 'LNK_NEW_SMSTEMPLATE'
          ),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
    'name' => 'created_by_name',
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
    'name' => 'date_modified',
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
    'name' => 'date_entered',
  ),
),
);
