<?php
date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
$record = urldecode($_GET['record']) ;
$assigned_id = urldecode($_GET['assigned_id']);
$bean_type = urldecode($_GET['bean_type']);
switch ($bean_type) {
    case 'opportunity':
        $bean = BeanFactory::getBean("Opportunities", $record);
        $accounts = $bean->get_linked_beans('accounts','Account');
        break;
    case 'AOS_Quotes':
        $bean = BeanFactory::getBean("AOS_Quotes", $record);
        $accounts = $bean->get_linked_beans('accounts','Account');
        break;    
    case 'AOS_Invoices':
        $bean = BeanFactory::getBean("AOS_Invoices", $record);
        $accounts = $bean->get_linked_beans('accounts','Account');
        break;
    case 'Leads':
        $bean = BeanFactory::getBean("Leads", $record);
        $accounts = $bean->get_linked_beans('accounts','Account');
        break;
    case 'Contacts':
        $bean = BeanFactory::getBean("Contacts", $record);
        $accounts = $bean->get_linked_beans('accounts','Account');
        break;
    case 'Accounts':
        $bean = BeanFactory::getBean("Accounts", $record);
        $accounts[] = $bean;
        break;       
    default:
        # code...
        break;
}

//update all realate opportunity, invoice, quote
foreach ($accounts as $account) {
    $oppotunities = $account->get_linked_beans('opportunities','Opportunity');
    if (!empty($oppotunities) && is_array($oppotunities)) {
        foreach ($oppotunities as $oppotunity) {
            $oppotunity->assigned_user_id = $assigned_id;
            $oppotunity->save();
        }
    }

    $invoices = $account->get_linked_beans('aos_invoices','AOS_Invoices');
    if (!empty($invoices) && is_array($invoices)) {
        foreach ($invoices as $invoice) {
            $invoice->assigned_user_id = $assigned_id;
            $invoice->save();
        }
    }

    $quotes = $account->get_linked_beans('aos_quotes','AOS_Quotes');
    if (!empty($quotes) && is_array($quotes)) {
        foreach ($quotes as $quote) {
            $quote->assigned_user_id = $assigned_id;
            $quote->save();
        }
    }

    
    $leads = $account->get_linked_beans('leads','Leads');
    if (!empty($leads) && is_array($leads)) {
        foreach ($leads as $lead) {
            $lead->assigned_user_id = $assigned_id;
            $lead->save();
        }
    }

    $contacts = $account->get_linked_beans('contacts','Contact');
    if (!empty($contacts) && is_array($contacts)) {
        foreach ($contacts as $contact) {
            $contact->assigned_user_id = $assigned_id;
            $contact->save();
        }
    }
    $calls = $account->get_linked_beans('calls','Calls');
    if (!empty($calls) && is_array($calls)) {
        foreach ($calls as $call) {
            $call->assigned_user_id = $assigned_id;
            $call->save();
        }
    }
    $account->assigned_user_id = $assigned_id;
    $account->save();   
}
die();
