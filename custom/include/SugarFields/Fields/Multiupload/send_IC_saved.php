<?php

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: 'GET,POST,OPTIONS,DELETE,PUT'");

require_once('include/SugarPHPMailer.php');
$db = DBManagerFactory::getInstance();

$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->IsHTML(true);

$sql = "SELECT * FROM aos_invoices WHERE number = ".$_REQUEST['invoice_number']." AND deleted = 0";
$result = $db->query($sql);
$row = $db->fetchByAssoc($result);

$mail->Subject = strtoupper($_REQUEST['role']).' has already set up installation date for invoice #'.$_REQUEST['invoice_number'].' '.$row['name'].', Please check!';
$url= "";
if($_REQUEST['role'] != 'client'){
    $url = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$_REQUEST['installation_id'].'/'. $_REQUEST['role'].'/'.$_REQUEST['installer_id'];
}else{
    $url = 'https://calendar.pure-electric.com.au/#/installation-booking/'.$_REQUEST['installation_id'].'/'.$_REQUEST['role'];
}
$mail->Body = strtoupper($_REQUEST['role']).' has already set up installation date, Please check the link below: <br><br> 
            Installation Calendar Link :<a href="'.$url.'">'.$url.'</a> <br><br>Invoice Link : <a href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'">https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'</a>';

$mail->prepForOutbound();
$mail->AddAddress('info@pure-electric.com.au');
$mail->AddAddress('thienpb89@gmail.com');
$sent = $mail->Send();

echo $sent;
die;