<?php
$parent_id = $_REQUEST['parent_id'];
$parent_module = $_REQUEST['parent_module'];
$call_type = (isset($_REQUEST['call_type'])) ? $_REQUEST['call_type'] : '';


if($parent_id != ''){
    switch ($parent_module) {
        case 'AOS_Quotes':
            $bean = new AOS_Quotes();
            $bean->retrieve($parent_id);
            echo trim(create_call_back($bean->billing_account_id,$bean->name,'AOS_Quotes',$parent_id));
            break;
        
        case 'AOS_Invoices':
            $bean =  new AOS_Invoices();
            $bean->retrieve($parent_id);
            if($call_type == 'Immediate_Post_Install') {
                echo trim(create_call_Immediate_Post_Install($bean));
            }else{
                echo trim(create_call_back($bean->billing_account_id,$bean->name,'AOS_Invoices',$parent_id));
            }
            break;

        default:
            # code...
            break;
    }
}else{
    echo 'error';
}

function create_call_back($account_id,$name,$parent_type,$parent_id,$Call_Type=''){
    global $current_user;
    $account = new Account();
    $account = $account->retrieve($account_id);
    if($account->id != ''){

   
        $contacts = $account->get_linked_beans('contacts','Contact');
        if(count($contacts)> 0){
            for($i=0;$i < count($contacts);$i++){
                if($contacts[$i]->id == $account->primary_contact_c){
                    $contact = $contacts[$i];
                    break;
                }elseif($i == count($contacts) -1){
                    $contact = $contacts[count($contacts) -1];
                }
            }
        }
        

        $call = new Call();
        $call->parent_type = $parent_type;
        $call->parent_id = $parent_id;
        $call->name = $name;
        $call->assigned_user_id = $current_user->id;
        $call->assigned_user_name = $current_user->name;
        $call->direction='Outbound';
        date_default_timezone_set('UTC');
        $dateAUS = date('Y-m-d H:i:s', (time()+24*60*60));
        $call->date_start = $dateAUS;
        $call->date_end = $dateAUS;
        $call->duration_hours='0';
        $call->duration_minutes='30';
        $call->account_id =$account->id;
        $call->status='Planned';
        // add new logic
        if($parent_type == 'AOS_Quotes'){
            $Quote = new AOS_Quotes();
            $Quote->retrieve($parent_id);
            if($Quote->stage == "Delivered") {
                $call->calls_type = 'sales';
            }     
            $call->aos_quotes_id_c = $Quote->id;
        }

        $call->save();

        // Reason comment: Because it's writing code in hook calls
        // if($contact->id != ''){
        //     $call->set_relationship('calls_contacts', array('call_id'=>$call->id ,'contact_id'=> $contact->id), false);
        // }
        // switch ($parent_type) {
        //     case 'AOS_Quotes':
        //         $call->set_relationship('calls_aos_quotes_1_c', array('calls_aos_quotes_1calls_ida'=>$call->id ,'calls_aos_quotes_1aos_quotes_idb'=> $parent_id), false);
        //         break;        
        //     case 'AOS_Invoices':
        //         $call->set_relationship('calls_aos_invoices_1_c', array('opportunity_id'=>$opp->id ,'account_id'=> $account->id), false);
        //         break;
        //     default:
        //         # code...
        //         break;
        // }    

        // $call->set_relationship('calls_users', array('call_id'=>$call->id ,'user_id'=> $current_user->id), false);
        // $call->set_relationship('calls_accounts_1_c', array('calls_accounts_1calls_ida'=>$call->id ,'calls_accounts_1accounts_idb'=> $account->id), false);
        // $call->save();
        // if($contact->id != ''){
        //     $call_contacts = $call->get_linked_beans('contacts','Contact');
        //     if(count($call_contacts) == 0){
        //         $call->set_relationship('calls_contacts', array('call_id'=>$call->id ,'contact_id'=> $contact->id), false);
        //     }
        // }
        $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
        $call->saving_reminders_data = true;
        $reminderData = json_encode(
            $call->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
        );
        Reminder::saveRemindersDataJson('Calls', $call->id, $reminderData);
        $call->saving_reminders_data = false;
        return $call->id;
    }else{
        return '';
    }

}

function create_call_Immediate_Post_Install($InvoiceCRM){
    if($InvoiceCRM->id == '') return '';
    global $current_user;
    $account = new Account();
    $account = $account->retrieve($InvoiceCRM->billing_account_id);
    if($account->id != ''){
        $contacts = $account->get_linked_beans('contacts','Contact');
        if(count($contacts)> 0){
            for($i=0;$i < count($contacts);$i++){
                if($contacts[$i]->id == $account->primary_contact_c){
                    $contact = $contacts[$i];
                    break;
                }elseif($i == count($contacts) -1){
                    $contact = $contacts[count($contacts) -1];
                }
            }
        }
        

        $call = new Call();
        $call->parent_type = 'AOS_Invoices';
        $call->parent_id =$InvoiceCRM->id;
        $call->name = $InvoiceCRM->name;
        $call->assigned_user_id = $current_user->id;
        $call->assigned_user_name = $current_user->name;
        $call->direction='Outbound';
        date_default_timezone_set('UTC');
        //add +7 days
        if($InvoiceCRM->installation_date_c != '') {
            $dateInfos = explode(" ",$InvoiceCRM->installation_date_c);
            $time_hours = $dateInfos[1];
            $dateInfos = explode("/",$dateInfos[0]);
            $inv_install_date_str = "$dateInfos[2]-$dateInfos[1]-$dateInfos[0]T00:00:00";
            $dateAUS = date("Y-m-d H:i:s", (strtotime($inv_install_date_str)+24*60*60*8));
        }else{
            $dateAUS = date('Y-m-d H:i:s', (time()+24*60*60*8));
        }
       
        $call->date_start = $dateAUS;
        $call->date_end = $dateAUS;
        $call->duration_hours='0';
        $call->duration_minutes='30';
        $call->account_id =$account->id;
        $call->status='Planned';
        $call->calls_type='immediate_post_install';
        $call->save();

        $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
        $call->saving_reminders_data = true;
        $reminderData = json_encode(
            $call->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
        );
        Reminder::saveRemindersDataJson('Calls', $call->id, $reminderData);
        $call->saving_reminders_data = false;
        return $call->id;
    }else{
        return '';
    }

}
