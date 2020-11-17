<?php
    $mes_arr = $_POST;
    $db = DBManagerFactory::getInstance();

    // print_r(json_decode(html_entity_decode($message),true));die;
    if($mes_arr['id'] != ''){
        if(strpos($mes_arr["from"],"+61") === false){
            $phone = preg_replace('/\D/', '', $mes_arr["from"]);
            $phone = preg_replace('/^0/', '+61', $phone);
        }else{
            $phone = "+".preg_replace('/\D/', '', $mes_arr["from"]);
        }

        if($phone != '+61444503982' && $phone != '' && $phone != "+61490942067"){
            $data_phone = str_replace('+61', '', $phone);
            $sql = "SELECT * FROM contacts WHERE REPLACE(phone_mobile,' ','') LIKE '%".$data_phone."%' OR REPLACE(phone_work,' ','') LIKE '%".$data_phone."%' OR REPLACE(phone_home,' ','') LIKE '%".$data_phone."%' LIMIT 0,1";
            $ret = $db->query($sql);
            $row = $db->fetchByAssoc($ret);

            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = 'info@pure-electric.com.au';  
            $mail->FromName = 'Pure Electric';  
            $mail->IsHTML(true);
            $mail->ClearAllRecipients();
            $mail->ClearReplyTos();

            $mail->Subject =  'Notification ! We have new message from '.$phone ;
            $bodytext = '';
            $bodytext .= '<p>Hi team, '.$phone.' has sent you a new sms. Please check it.</p>';
            if($mes_arr['message_type'] !== 1){
                $bodytext .= '<p>Message Content : '.$mes_arr['message_body'].'</p>';
            }
            $bodytext .= "<p>Message Link : <a href='http://message.pure-electric.com.au/#".trim($phone,"+")."'>".$phone."</a></p>" ;
            $bodytext .= "<p>Contact Name : <b>".$row["first_name"]." ".$row["last_name"]."</b></p>";
            $mail->Body = $bodytext;
            $mail->AddBCC('thienpb89@gmail.com');
            $mail->AddAddress('info@pure-electric.com.au');
            $mail->prepForOutbound();    
            $mail->setMailerForSystem();
            $sent = $mail->send();
            die;
        }
    }
?>