<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point' );


$accountID = $_GET['account_id'];
$account = new Account();
$account->retrieve($accountID);

/**update Account information basic*/
if ($account->id != "") {
    $account->check_account_type_c      = urldecode($_GET['account_type']);
    $account->name                      = urldecode($_GET['name']);
    $account->phone_fax                 = urldecode($_GET['phone_fax']);
    $account->daikin_account_number_c   = urldecode($_GET['daikin_account_number_c']);
    $account->phone_office              = urldecode($_GET['phone_office']);
    $account->mobile_phone_c            = urldecode($_GET['mobile_phone_c']);
    $account->home_phone_c              = urldecode($_GET['home_phone_c']);
    $account->save();
}

/**fullname to firstname and lastname */
$names = explode(" ",$account->name);
$firstname = $names[0];
$lastname = trim(str_replace($firstname,"",$account->name));

/**Relate to Contact and Lead */
$db = DBManagerFactory::getInstance();
$sql =  "   SELECT DISTINCT accounts.id as account_id, contacts.id as contact_id, leads.id as lead_id
            FROM leads 
            LEFT JOIN accounts ON accounts.id = leads.account_id 
            LEFT JOIN contacts ON contacts.id = leads.contact_id 
            WHERE accounts.id = '$accountID' AND leads.account_id != ''
        ";
$ret = $db->query($sql);
while($row = $ret->fetch_assoc()){
    if ($row['lead_id'] != "") {
        $lead = new Lead();
        $lead->retrieve($row['lead_id']);
        if ($lead->id != "") {
            $lead->first_name = $firstname;
            $lead->last_name = $lastname;
            $lead->phone_mobile = $account->mobile_phone_c;
            $lead->phone_work = $account->phone_office;
            $lead->save();
        }
    }
    if ($row['contact_id'] != "") {
        $contact = new Contact();
        $contact->retrieve($row['contact_id']);
        if ($contact->id != "") {
            $contact->first_name = $firstname;
            $contact->last_name = $lastname;
            $contact->check_contact_type_c = $account->check_account_type_c;
            $contact->phone_mobile = $account->mobile_phone_c;
            $contact->phone_work = $account->phone_office;
            $contact->phone_home = $account->home_phone_c;
            $contact->phone_fax  = $account->phone_fax;
            $contact->save();
        }
    }
}

die();