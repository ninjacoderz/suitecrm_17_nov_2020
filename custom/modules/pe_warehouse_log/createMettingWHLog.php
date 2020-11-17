<?php
date_default_timezone_set('UTC');
set_time_limit(0);
ini_set('memory_limit', '-1');
$record_id = $_GET['record_id'];
$purchase_id = $_GET['purchase_id'];
$dispatch_date = $_GET['dispatch_date'];
$arrival_date = $_GET['arrival_date'];
$update_met = $_GET['update_met'];
$id_dispatch = $_GET['id_dispatch'];
$id_arrival = $_GET['id_arrival'];
$color_type = $_GET['set_color'];
if( $color_type == 'color_meeting'){
    $calen = new Meeting();
    $calen->retrieve($record_id);
    echo $calen->color_type_c;
    die;
}
$purchase = new PO_purchase_order();
$purchase->retrieve($purchase_id);

$WH_Log = new pe_warehouse_log();
$WH_Log->retrieve($record_id);

if($update_met == 'update_to_WH_log'){
    if($dispatch_date !=""){
        $WH_Log->dispatch_ship_date_c = $dispatch_date;
    }
    if($arrival_date !=""){
        $WH_Log->arrival_date_c = $arrival_date;
    }
    $WH_Log->save();
    die;
}else if( $update_met == 'update_move_to_WH_log'){
        $calen = new Meeting();
        $calen->retrieve($record_id);
        $id_WH = $calen->link_to_warehouse_log_c;
        $WH_update = new pe_warehouse_log();
        $WH_update->retrieve($id_WH);
        if($dispatch_date !=""){
            $WH_update->dispatch_ship_date_c = $dispatch_date;
        }
        if($arrival_date !=""){
            $WH_update->arrival_date_c = $arrival_date;
        }
        $WH_update->save();
        die;
    
}
switch ( $WH_Log->shipping_product_type_c ){
    case "quote_type_sanden":
        $product_type = "Sanden";
    break;
    case "quote_type_solar":
        $product_type = "Solar";
    break;
    case "quote_type_daikin":
        $product_type = "Daikin";
    break;
    case "quote_type_off_grid_system":
        $product_type = "Off-grid System";
    break;
    case "quote_type_nexura":
        $product_type = "Daikin Naxura";
    break;
    case "quote_type_methven":
        $product_type = "Methven";
    break;
    case "quote_type_tesla":
        $product_type = "Tesla";
    break;
}
if( $update_met == 'update_met'){
    $dispatch_update = new Meeting;
    $dispatch_update->retrieve($id_dispatch);
    
    if( $WH_Log->destination_address_city != "" ){
        $dispatch_update->name = "Dispatch ".$WH_Log->destination_address_city." ".$WH_Log->destination_address_state." ".$WH_Log->destination_address_postalcode." ".$product_type;
    }else {
        $dispatch_update->name = "Dispatch ".$WH_Log->shipping_address_city." ".$WH_Log->shipping_address_state." ".$WH_Log->shipping_address_postalcode." ".$product_type;
    }
    $dispatch_update->parent_type = "Accounts";
    $dispatch_update->parent_id = $WH_Log->account_id_c;
    $dispatch_update->date_start = $dispatch_date;
    $dispatch_update->date_end = $dispatch_date;
    // $dispatch_update->description = $purchase->name;
    $dispatch_update->save();

    $arrival_update = new Meeting;
    $arrival_update->retrieve($id_arrival);

    if( $WH_Log->destination_address_city != "" ){
        $arrival_update->name = "Arrival ".$WH_Log->destination_address_city." ".$WH_Log->destination_address_state." ".$WH_Log->destination_address_postalcode." ".$product_type;
    }else {
        $arrival_update->name = "Arrival ".$WH_Log->shipping_address_city." ".$WH_Log->shipping_address_state." ".$WH_Log->shipping_address_postalcode." ".$product_type;
    }
    $arrival_update->parent_type = "Accounts";
    $arrival_update->parent_id = $WH_Log->account_id_c;
    $arrival_update->date_start = $arrival_date;
    $arrival_update->date_end = $arrival_date;
    // $arrival_update->description = $purchase->name;
    $arrival_update->save();

    $WH_Log->dispatch_ship_date_c = $dispatch_date;
    $WH_Log->arrival_date_c = $arrival_date;
    $WH_Log->meeting_dispatch_date_c = $meeting_dispatch->id;
    $WH_Log->meeting_arrival_date_c = $meeting_arrival->id;
    $WH_Log->save();
}else if($update_met == 'create_met'){
    if( $WH_Log->id ){
        if( $dispatch_date  != ""){
            $meeting_dispatch = new Meeting;
            if( $WH_Log->destination_address_city != "" ){
                $meeting_dispatch->name = "Dispatch ".$WH_Log->destination_address_city." ".$WH_Log->destination_address_state." ".$WH_Log->destination_address_postalcode." ".$product_type;
            }else {
                $meeting_dispatch->name = "Dispatch ".$WH_Log->shipping_address_city." ".$WH_Log->shipping_address_state." ".$WH_Log->shipping_address_postalcode." ".$product_type;
            }
            $meeting_dispatch->date_start = $dispatch_date;
            $meeting_dispatch->date_end = $dispatch_date;
            $meeting_dispatch->parent_type = "Accounts";
            $meeting_dispatch->parent_id = $WH_Log->account_id_c;
            $meeting_dispatch->location ='Meeting Delivery';
            $meeting_dispatch->assigned_user_id = $WH_Log->created_by;
            $meeting_dispatch->modified_user_id = $WH_Log->modified_user_id;
            $meeting_dispatch->description = $purchase->name;
            $meeting_dispatch->duration_hours = '1';
            $meeting_dispatch->duration_minutes = '0';
            $meeting_dispatch->color_type_c = 'dispatch_meeting';
            $meeting_dispatch->link_to_warehouse_log_c = $record_id;
            // if(empty($meeting_wh_log->duration_hours)){
            //     $meeting_wh_log->duration_hours  = 3;
            //     $meeting_wh_log->duration_minutes  = 0;
            // }
            $meeting_dispatch->save();
            global $current_user;

            $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
            $meeting_dispatch->saving_reminders_data = true;
            $reminderData = json_encode(
                $meeting_dispatch->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
            );
            Reminder::saveRemindersDataJson('Meetings', $meeting_dispatch->id, $reminderData);
            $meeting_dispatch->saving_reminders_data = false;


            $relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meeting_dispatch->id);
            $data_values = array('accept_status'=>true);
            $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);

            if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
                $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meeting_dispatch->id);
                $data_values = array('accept_status'=>true);
                $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);
            }else if($current_user->id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
                $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meeting_dispatch->id);
                $data_values = array('accept_status'=>true);
                $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);
            }else {
                $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meeting_dispatch->id);
                $data_values = array('accept_status'=>true);
                $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);
                // $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meeting_dispatch->id);
                // $data_values = array('accept_status'=>true);
                // $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);
            }
            // $relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meeting_dispatch->id);
            // $data_values = array('accept_status'=>true);
            // $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);

            if($meeting_dispatch->update_vcal)
            {
                vCal::cache_sugar_vcal($user);
            }
        }
        if( $arrival_date  != ""){
            $meeting_arrival = new Meeting;
            if( $WH_Log->destination_address_city != "" ){
                $meeting_arrival->name = "Arrival ".$WH_Log->destination_address_city." ".$WH_Log->destination_address_state." ".$WH_Log->destination_address_postalcode." ".$product_type;
            }else {
                $meeting_arrival->name = "Arrival ".$WH_Log->shipping_address_city." ".$WH_Log->shipping_address_state." ".$WH_Log->shipping_address_postalcode." ".$product_type;
            }
            $meeting_arrival->date_start = $arrival_date;
            $meeting_arrival->date_end = $arrival_date;
            $meeting_arrival->parent_type = "Accounts";
            $meeting_arrival->parent_id = $WH_Log->account_id_c;
            $meeting_arrival->location ='Meeting Delivery';
            $meeting_arrival->assigned_user_id = $WH_Log->created_by;
            $meeting_arrival->modified_user_id = $WH_Log->modified_user_id;
            $meeting_arrival->description = $purchase->name;
            $meeting_arrival->duration_hours = '1';
            $meeting_arrival->duration_minutes = '0';
            $meeting_arrival->color_type_c = 'arrival_meeting';
            $meeting_arrival->link_to_warehouse_log_c = $record_id;
            // if(empty($meeting_wh_log->duration_hours)){
            //     $meeting_wh_log->duration_hours  = 3;
            //     $meeting_wh_log->duration_minutes  = 0;
            // }
            $meeting_arrival->save();
            global $current_user;

            $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
            $meeting_arrival->saving_reminders_data = true;
            $reminderData = json_encode(
                $meeting_arrival->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
            );
            Reminder::saveRemindersDataJson('Meetings', $meeting_arrival->id, $reminderData);
            $meeting_arrival->saving_reminders_data = false;


            $relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meeting_arrival->id);
            $data_values = array('accept_status'=>true);
            $meeting_arrival->set_relationship($meeting_arrival->rel_users_table, $relate_values, false, false,$data_values);

            //thien fix
            if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
                $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meeting_arrival->id);
                $data_values = array('accept_status'=>true);
                $meeting_arrival->set_relationship($meeting_arrival->rel_users_table, $relate_values, false, false,$data_values);
            }else if($current_user->id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
                $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meeting_arrival->id);
                $data_values = array('accept_status'=>true);
                $meeting_arrival->set_relationship($meeting_arrival->rel_users_table, $relate_values, false, false,$data_values);
            }else {
                $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meeting_arrival->id);
                $data_values = array('accept_status'=>true);
                $meeting_arrival->set_relationship($meeting_arrival->rel_users_table, $relate_values, false, false,$data_values);
                // $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meeting_dispatch->id);
                // $data_values = array('accept_status'=>true);
                // $meeting_dispatch->set_relationship($meeting_dispatch->rel_users_table, $relate_values, false, false,$data_values);
            }
            // $relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meeting_arrival->id);
            // $data_values = array('accept_status'=>true);
            // $meeting_arrival->set_relationship($meeting_arrival->rel_users_table, $relate_values, false, false,$data_values);

            if($meeting_arrival->update_vcal)
            {
                vCal::cache_sugar_vcal($user);
            }
        }
    }
        $WH_Log->dispatch_ship_date_c = $dispatch_date;
        $WH_Log->arrival_date_c = $arrival_date;
        $WH_Log->meeting_dispatch_date_c = $meeting_dispatch->id;
        $WH_Log->meeting_arrival_date_c = $meeting_arrival->id;
        $WH_Log->save();
        $meeting_date = array();
        $meeting_date[] = array($meeting_dispatch->id,$meeting_arrival->id);
        echo json_encode($meeting_date);
}
?>