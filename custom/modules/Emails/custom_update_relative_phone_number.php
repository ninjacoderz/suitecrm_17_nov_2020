<?php

$record_id = $_REQUEST['record_id'];
$module = $_REQUEST['module'];
$phone_number =  $_REQUEST['phone_number'];
if($record_id == '' || $phone_number == '' || $module == '') return ;
$db = DBManagerFactory::getInstance();
switch ($module) {
    case 'Leads':
        $sql = "SELECT DISTINCT id ,account_id , contact_id
            FROM leads
            WHERE id = '$record_id' AND deleted = 0 ";
        break;
    case 'Accounts':
        $sql = "SELECT DISTINCT id ,account_id , contact_id
            FROM leads
            WHERE account_id = '$record_id' AND deleted = 0 ";
        break;
    case 'Contacts':
        $sql = "SELECT DISTINCT id ,account_id , contact_id
            FROM leads
            WHERE contact_id = '$record_id' AND deleted = 0 ";
        break;
    case 'AOS_Invoices':
        $sql = "SELECT DISTINCT leads.id as id , leads.account_id as account_id , leads.contact_id as contact_id 
            FROM leads
            LEFT JOIN aos_invoices ON aos_invoices.billing_account_id = leads.account_id 
            WHERE aos_invoices.id  = '$record_id' AND aos_invoices.deleted = 0 ";
        break;
    case 'AOS_Quotes':
        $sql = "SELECT DISTINCT leads.id as id , leads.account_id as account_id , leads.contact_id as contact_id 
            FROM leads
            LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = leads.account_id 
            WHERE aos_quotes.id  = '$record_id' AND aos_quotes.deleted = 0 ";
        break;            
    default:
        $sql = '';
        break;
}

 

$ret = $db->query($sql);
while($row = $ret->fetch_assoc()){
    $contact = new Contact();
    $contact->retrieve($row['contact_id']);
    if($contact->id != ''){
        $contact->phone_mobile = $phone_number;
        $contact->save();
    }
    $account = new Account();
    $account->retrieve($row['account_id']);
    if($account->id != ''){
        $account->phone_mobile = $phone_number;
        $account->save();
    }
    $lead =  new Lead();
    $lead->retrieve($row['id']);
    if($lead->id != ''){
        $lead->phone_mobile = $phone_number;
        $lead->save();
    }
}