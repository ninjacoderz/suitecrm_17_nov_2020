<?php

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: 'GET,POST,OPTIONS,DELETE,PUT'");

require_once('include/SugarPHPMailer.php');
$db = DBManagerFactory::getInstance();

//VUT - Get data calendar
$calendarInfos = getInfoCalendar($_REQUEST['installation_id']);

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
    //VUT - S - Send email to customer
    $customer = new Contact();
    $customer->retrieve($row['billing_contact_id']); //$customer ->email1
    $date_available = getDate_available(json_decode($calendarInfos->message->client_available_date));
    $mail_clone = clone $mail;
    $mail_clone->Subject = 'You have already set up installation date for invoice #'.$_REQUEST['invoice_number'].' '.$row['name'].', Please check!';
    $mail_clone->Body = 'You have already set up installation date, Please check the link below: <br><br> 
    Installation Calendar Link : <a target="_blank" href="'.$url.'">'.$url.'</a><br><br> Dates selected: '.implode(', ',$date_available);
    $mail_clone->prepForOutbound();
    $mail_clone->AddAddress($customer->email1);
    // $mail_clone->AddAddress('info@pure-electric.com.au');
    $sent = $mail_clone->Send();
    //VUT - E - Send email to customer
}
$mail->Body = strtoupper($_REQUEST['role']).' has already set up installation date, Please check the link below: <br><br> 
            Installation Calendar Link :<a target="_blank" href="'.$url.'">'.$url.'</a> <br><br>Invoice Link : <a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'">https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'</a>';
$mail->prepForOutbound();
$mail->AddAddress('info@pure-electric.com.au');
$mail->AddAddress('thienpb89@gmail.com');
$sent = $mail->Send();

echo $sent;
die;

//DECLARE FUNCTION
/**
 * VUT - Get Calendar's Infomation follow id
 * @param NUMBER $calendar_id
 * @return OBJECT $result
 */
function getInfoCalendar($calendar_id) {
        $url = 'https://calendar.pure-electric.com.au/api/API.php/getInstallationByInstaller?id='.$calendar_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Host: calendar.pure-electric.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Cache-Control: no-cache"
            )
        );
        $result = curl_exec($ch);
        curl_close ($ch);
        return json_decode($result);
}

/**
 * VUT - Get Date in datetime
 * @param ARRAY $array_date
 * @return ARRAY $result
 */
function getDate_available($array_date) {
    $result = [];
    foreach ($array_date as $index => $date_str) {
        preg_match('/([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/', $date_str, $output_array);
        array_push($result,$output_array[3].'/'.$output_array[2].'/'.$output_array[1]);
    }
    return $result;
}