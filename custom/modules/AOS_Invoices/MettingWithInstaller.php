<?php

$record_id = $_GET['record_id'];

$invoice = new AOS_Invoices;
$invoice->retrieve($record_id);

if(!$invoice->id) return;

$meetings = new Meeting;
$meetings->name = $invoice->name;
$invoice->installation_date_c = $_GET["installation_date"];
$meetings->date_start = $_GET["installation_date"]?$_GET["installation_date"]:$invoice->installation_date_c;
$meetings->parent_type = "Accounts";
$meetings->parent_id = $invoice->billing_account_id;
$meetings->assigned_user_id = $invoice->assigned_user_id;
$meetings->aos_invoices_id_c = $record_id;
if(empty($meetings->duration_hours)){
    $meetings->duration_hours  = 3;
    $meetings->duration_minutes  = 0;
}
$meetings->save();
$invoice->meeting_c = $meetings->id;
$invoice->save();
global $current_user;


$reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
$meetings->saving_reminders_data = true;
$reminderData = json_encode(
    $meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
);
Reminder::saveRemindersDataJson('Meetings', $meetings->id, $reminderData);
$meetings->saving_reminders_data = false;


$relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meetings->id);
$data_values = array('accept_status'=>true);
$meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

//thien fix
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

// $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
// $data_values = array('accept_status'=>true);
// $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

// $relate_values = array('user_id'=>'d028d21f-504c-c8ff-3ba3-57ab05ae7a4d','meeting_id'=>$meetings->id);
// $data_values = array('accept_status'=>true);
// $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

$relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meetings->id);
$data_values = array('accept_status'=>true);
$meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

//$meetings->users->add("61e04d4b-86ef-00f2-c669-579eb1bb58fa");
//$meetings->save();

if($meetings->update_vcal)
{
    vCal::cache_sugar_vcal($user);
}
echo $meetings->id;
die();
