<?php
// created: 2020-11-09 09:13:09
$listViewDefs['Emails'] = array (
  'FROM_ADDR_NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_LIST_FROM_ADDR',
    'default' => true,
  ),
  'SUBJECT' => 
  array (
    'width' => '32%',
    'label' => 'LBL_LIST_SUBJECT',
    'default' => true,
    'link' => false,
    'customCode' => '',
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '32%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'DATE_SENT_RECEIVED' => 
  array (
    'width' => '32%',
    'label' => 'LBL_LIST_DATE_SENT_RECEIVED',
    'default' => true,
  ),
  'CATEGORY_ID' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_CATEGORY',
    'default' => true,
  ),
  'HAS_ATTACHMENT' => 
  array (
    'width' => '32%',
    'label' => 'LBL_HAS_ATTACHMENT_INDICATOR',
    'default' => false,
    'sortable' => false,
    'hide_header_label' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'TO_ADDRS_NAMES' => 
  array (
    'width' => '32%',
    'label' => 'LBL_LIST_TO_ADDR',
    'default' => false,
  ),
  'INDICATOR' => 
  array (
    'width' => '32%',
    'label' => 'LBL_INDICATOR',
    'default' => false,
    'sortable' => false,
    'hide_header_label' => true,
  ),
);