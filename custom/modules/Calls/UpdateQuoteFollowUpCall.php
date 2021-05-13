<?php

if( $call->id != ''){
    $call->feedback_from_client_c = $_REQUEST['feedback'];
    $call->save();
    email_notification($call);
    echo $call->parent_name;
}
function email_notification($call){
    $account =  new Account();
    $account->retrieve($call->parent_id);
    require_once('include/SugarPHPMailer.php');

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'info@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $account->name ."has feedbacked to Pure Electric";
    $mail->Body = "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$call->aos_quotes_id_c."' target='_blank'>".$call->quote_c."</a></p>";
    $mail->Body .= "<p>Link Call: <a href='https://suitecrm.pure-electric.com.au/index.php?module=Calls&action=EditView&record=".$call->id."' target='_blank'>".$call->name."</a></p>";
    $mail->Body .= "<p>Email: ".$account->email1." <a href='https://mail.google.com/#search/".$account->email1."'>GSearch</a></p>";

    $email->Body .= $list_photos;
    $email->IsHTML(true);
    // $mail->AddAddress('info@pure-electric.com.au');
    $mail->AddAddress('ngoanhtuan2510@gmail.com');
    // $mail->AddCC('matthew.wright@pure-electric.com.au');  
    // $mail->AddCC('paul.szuster@pure-electric.com.au');  
    $email->setMailerForSystem();  
    $email->Send();
    return ;
}
?>