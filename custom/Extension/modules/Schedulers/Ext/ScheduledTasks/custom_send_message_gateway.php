<?php
array_push($job_strings, 'custom_send_message_gateway');

function custom_send_message_gateway()
{
    $message_dir1 = '/var/www/message';

    if(check_exist_json_sms("/message")){
        autosendmail_notification("1");
    }

    $sms_body1 = "This is test message from message 2";
    $client_number1 = '+61421616733';
    exec("cd ".$message_dir1."; php send-message.php sms ".$client_number1.' "'.$sms_body1.'"');
    
    $message_dir2 = '/var/www/message2';

    if(check_exist_json_sms("/message2")){
        autosendmail_notification("1");
    }

    $sms_body2 = "This is test message from message 1";
    $client_number2 = '+61490942067';
    exec("cd ".$message_dir2."; php send-message.php sms ".$client_number2.' "'.$sms_body2.'"');
    die;
}
function check_exist_json_sms($folder){
    $check = false;
    $folder_message = '/var/www/suitecrm'.'/..'.$folder.'/'.'messages/';
    $allFiles = scandir($folder_message);
    
    if($allFiles !== false){
        $files = array_diff($allFiles, array('.', '..', 'sent_backup'));
        if(count($files) > 0){
            foreach ($files as $file){
                if(!is_dir($folder_message.$file) && strpos(strtolower($file),'json') !== false){
                    $file_timestamp = filemtime($folder_message.$file);
                }else{
                    continue;
                }
                if(!isset($date_last)) {
                    $date_last = $file_timestamp;
                }else if($file_timestamp < $date_last){
                    $date_last = $file_timestamp;
                }
            }
            if($date_last >= strtotime("-10 minutes")){
                $check = false;
            }else{
                $check = true;
            }
        }else{
            $check = false;
        }
    }else{
       $check = false;
    }
    return $check;
}
function autosendmail_notification($gateway){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "PureElectric";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Warning SMS GATEWAY ".$gateway." DOWN";
    $mail->Body = "Warning SMS GATEWAY ".$gateway." DOWN";

    $mail->AddAddress("info@pure-electric.com.au");

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}
