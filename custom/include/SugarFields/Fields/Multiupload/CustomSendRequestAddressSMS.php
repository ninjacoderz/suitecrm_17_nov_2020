<?php
$lead = new Lead();
$lead = $lead -> retrieve($_REQUEST['record']);
if ($lead->id == "") return false;
global $sugar_config;
$phone_number = $lead->phone_mobile ? $lead->phone_mobile : $lead->phone_work;
if($phone_number){
    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
    if(strlen($phone_number) >= 10){
        $phone_number = preg_replace("/^61/", "+61", $phone_number);
        if(strpos($phone_number, "+61") !== false ){
            $user = new User();
            $user = $user->retrieve($lead->assigned_user_id);
            $message_body = 'Hi '.$lead->first_name.', my name is '.$user->first_name.' from Solargain. I received your request for a Solargain solar/battery quote for your place, I have that you are in '.$lead->primary_address_city.' '.$lead->primary_address_state.'. If you could please reply back with your street address I would be more than happy to assist.  Look forward to your response. Regards, '.$user->first_name;
            return exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$message_body.'"');
        }
    }
}
