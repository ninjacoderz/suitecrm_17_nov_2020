<?php
require_once('include/SugarPHPMailer.php');
 $call =  new Call();
 $call->retrieve(trim($_REQUEST['call_id']));
 $name_client = "";
if( $call->id != ''){
    $call->feedback_from_client_c = $_REQUEST['feedback'];
    $name_client = $call->parent_name;
    $call->save();
    email_notification($call);
    echo $name_client;
}
function email_notification($call){
    $account =  new Account();
    $account->retrieve($call->parent_id);

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'info@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $account->name ."has feedbacked to Pure Electric";
    $email->Body = "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$call->aos_quotes_id_c."' target='_blank'>".$call->quote_c."</a></p>";
    $email->Body .= "<p>Link Call: <a href='https://suitecrm.pure-electric.com.au/index.php?module=Calls&action=EditView&record=".$call->id."' target='_blank'>".$call->name."</a></p>";
    $email->Body .= "<p>Email: ".$account->email1." <a href='https://mail.google.com/#search/".$account->email1."'>GSearch</a></p>";

    $email->Body .= $list_photos;
    $email->IsHTML(true);
    $email->AddAddress('info@pure-electric.com.au');
    // $email->AddAddress('ngoanhtuan2510@gmail');
    $email->AddCC('matthew.wright@pure-electric.com.au');  
    $email->AddCC('paul.szuster@pure-electric.com.au');  
    $email->setMailerForSystem();  
    $email->Send();
    return ;
}
?>