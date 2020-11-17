<?php
require_once('include/SugarPHPMailer.php');
$record_id_report = trim($_REQUEST['report_id']);
global $current_user;
$report =new AOR_Report();
$report->retrieve($record_id_report);
if($report->id == '') return;
$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->IsHTML(true);
$mail->Subject = 'Report :'.$report->name;
$bottom_report = $report->build_group_report();
$bottom_report = '<br>————————————<br><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOR_Reports&action=DetailView&record='.$report->id.'">'.$report->name.'</a>' . $bottom_report;
$body = '';
$mail->Body = $body . $bottom_report;

$mail->prepForOutbound();
$mail->AddAddress('admin@pure-electric.com.au');
$mail->AddAddress($current_user->email1);
$mail->AddCC('info@pure-electric.com.au');
// $mail->AddAddress('nguyenphudung93.dn@gmail.com');
$sent = $mail->Send();
