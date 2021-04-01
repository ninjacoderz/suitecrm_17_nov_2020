<?php

include "config.php";

$record_id = $_GET['record'];
$dispatch_date = $_GET['dispatch_date'];
$delivery_date = $_GET['delivery_date'];
// $install_date = $_GET['install_date'];
$assigned_user = $_GET['assigned_user_name'];
$name = $_GET["name"];

$dbconfig = $sugar_config["dbconfig"];
$servername = $dbconfig["db_host_name"];
$username = $dbconfig["db_user_name"];
$dbname = $dbconfig["db_name"];
$password = $dbconfig["password"];

$is_update = false;
$deleted = 0;
$current_meetings;
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT id, name, date_entered, deleted FROM meetings");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach($stmt->fetchAll() as $k=>$v) {
    if($name === $v['name']){
        $is_update = true;
        $current_meetings = $v['id'];
        $deleted = $v['deleted'];
        break;
    } 
  }
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

$meetings = new Meeting;

if($is_update && $deleted !== 1){    
    $meetings->retrieve($record_id);
    $meetings->id = $current_meetings;
    $meetings->name = $name;
    $meetings->date_modified = DateTime::createFromFormat('d/m/Y H:i:s', date('d/m/Y H:i:s'), new DateTimeZone("Australia/Melbourne"))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    $meetings->assigned_user_id = $assigned_user;
    $meetings->parent_type = "PO_purchase_order";
    $meetings->parent_type_options = "PO_purchase_order";
    $meetings->parent_id = $record_id;
    $meetings->parent_name = $record_id;

    if($dispatch_date){
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $dispatch_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    } elseif ($delivery_date) {
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $delivery_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    $meetings->duration_hours = "1";
    $meetings->duration_minutes  = "0";
    
    $meetings->save();
    
} else {
    $meetings->name = $name;
    $meetings->assigned_user_id = $assigned_user;

    if($dispatch_date){
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $dispatch_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    } elseif ($delivery_date) {
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $delivery_date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
        $meetings->date_start = $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }
    
    $meetings->duration_hours = "1";
    $meetings->duration_minutes  = "0";
    $meetings->parent_type = "PO_purchase_order";
    $meetings->parent_type_options = "PO_purchase_order";
    $meetings->parent_id = $record_id;
    $meetings->parent_name = $record_id;

    $meetings->save();
    global $current_user;
    
    $reminder_json = '[{"idx":0,"id":"","popup":true,"email":true,"timer_popup":"86400","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
    $meetings->saving_reminders_data = true;

    $reminderData = json_encode($meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true)));
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
    }

    if($meetings->update_vcal){
        vCal::cache_sugar_vcal($user);
    }
    // return $meetings->id;    
}

die();
