<?php

$record_id = $_GET['record'];
$dispatch_date = $_GET['dispatch_date'];
$delivery_date = $_GET['delivery_date'];
// $install_date = $_GET['install_date'];
$assigned_user = $_GET['assigned_user_name'];
$name = $_GET["name"];
$invoices = $_GET["invoices"];

$meetings = new Meeting;
$meetings->name = $name;
global $current_user;

$reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';

$meetings->saving_reminders_data = true;
$reminderData = json_encode(
    $meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
);
Reminder::saveRemindersDataJson('Meetings', $meetings->id, $reminderData);

if (empty($meetings->duration_hours)) {
 $meetings->duration_hours  = 1;
 $meetings->duration_minutes  = 0;
}

if($dispatch_date){
$date = DateTime::createFromFormat('d/m/Y H:i:s', $dispatch_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
$meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
} elseif ($delivery_date) {
 $date = DateTime::createFromFormat('d/m/Y H:i:s', $delivery_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
 $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
}

$meetings->assigned_user_id = $assigned_user;
$meetings->aos_invoices_id_c = $invoices;

$meetings->save();

$meetings->saving_reminders_data = false;
$relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meetings->id);
$data_values = array('accept_status'=>true);
$meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
    $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
}else if($current_user->id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
    $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
}else{
    $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

    $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
    $data_values = array('accept_status'=>true);
    $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
}

$relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meetings->id);
$data_values = array('accept_status'=>true);
$meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

 if($meetings->update_vcal)
    {
        vCal::cache_sugar_vcal($user);
    }
    return $meetings->id;
die();
