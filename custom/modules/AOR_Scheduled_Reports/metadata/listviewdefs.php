<?php
// created: 2020-11-09 09:13:09
$listViewDefs['AOR_Scheduled_Reports'] = array (
  'NAME' => 
  array (
    'width' => '40%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
  ),
  'AOR_REPORT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_AOR_REPORT_NAME',
    'id' => 'AOR_REPORT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'LAST_RUN' => 
  array (
    'type' => 'readonly',
    'label' => 'LBL_LAST_RUN',
    'width' => '10%',
    'default' => false,
  ),
  'EMAIL_RECIPIENTS' => 
  array (
    'type' => 'longtext',
    'label' => 'LBL_EMAIL_RECIPIENTS',
    'width' => '10%',
    'default' => false,
  ),
  'SCHEDULE' => 
  array (
    'type' => 'CronSchedule',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'default' => false,
  ),
);