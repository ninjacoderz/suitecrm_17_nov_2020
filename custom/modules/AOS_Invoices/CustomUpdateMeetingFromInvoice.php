<?php
$meeting_id = trim($_REQUEST['meeting_id']);
$install_date = trim($_REQUEST['installation_date_c']);

$meetings = new Meeting();
$meetings->retrieve($meeting_id);
if(!$meetings->id) return;
if($install_date != '') {
    $meetings->date_start = $install_date;
}
$meetings->save();
echo 'success';
