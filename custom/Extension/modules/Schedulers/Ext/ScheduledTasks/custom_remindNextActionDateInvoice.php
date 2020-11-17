<?php 
array_push($job_strings, 'custom_remindNextActionDateInvoice');
require_once('include/SugarPHPMailer.php');

function custom_remindNextActionDateInvoice() {
  $db = DBManagerFactory::getInstance();
  $sql = "SELECT aos_invoices.id, aos_invoices.name, aos_invoices.number, aos_invoices.billing_account_id as acc_id, aos_invoices.billing_contact_id as contact_id, aos_invoices_cstm.next_action_date_c as next_action_date, accounts.name as acc_name, CONCAT(contacts.first_name, ' ', contacts.last_name) as contact_name
          FROM aos_invoices
          LEFT JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
          LEFT JOIN accounts ON accounts.id = aos_invoices.billing_account_id
          LEFT JOIN contacts ON contacts.id = aos_invoices.billing_contact_id
          WHERE DATEDIFF(aos_invoices_cstm.next_action_date_c, NOW()) = 0
          ORDER BY aos_invoices_cstm.next_action_date_c ASC
        ";
  
  $ret = $db->query($sql);
  while ($row = $db->fetchByAssoc($ret))
  {
    $main_url = 'https://suitecrm.pure-electric.com.au/';
    $from_address = 'operations@pure-electric.com.au';
    $to_address = 'operations@pure-electric.com.au';

    $body_mail ='';
    $body_mail .= '<div><table><tbody>';
    if ($row['contact_id'] != '') {
      $body_mail .= '<tr><td style="font-size: 20px;"><span>Contact : </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Contacts&action=DetailView&record='.$row['contact_id'].'" target="_blank">'.$row['contact_name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Contacts&action=EditView&record='.$row['contact_id'].'" target="_blank">[Edit]</a></td></tr>';
    }
    if ($row['acc_id'] != '') {
      $body_mail .='<tr><td style="font-size: 20px;"><span>Account: </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Accounts&action=DetailView&record='.$row['acc_id'].'" target="_blank">'.$row['acc_name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Accounts&action=EditView&record='.$row['acc_id'].'" target="_blank">[Edit]</a></td></tr>';
    }
    $body_mail .='<tr><td style="font-size: 20px;"><span>Invoice #'.$row['number'].': </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=AOS_Invoices&action=DetailView&record='.$row['id'].'" target="_blank">'.$row['name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'" target="_blank">[Edit]</a></td></tr></tbody></table></div>';
    
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $from_address;
    $mail->FromName = "Pure Info";
    $mail->Subject = 'Prepare for this job Invoice#'.$row['number'];
    $mail->Body = $body_mail;
    $mail->IsHTML(true);
    $mail->AddAddress($to_address);
    $mail->prepForOutbound();
    $mail->Send();
  }
}
