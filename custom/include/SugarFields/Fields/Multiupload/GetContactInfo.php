<?php

$module_name = isset($_GET['module_name']) ? $_GET['module_name']: "";
$record_id = isset($_GET['record_id']) ? $_GET['record_id']: "";
$action = isset($_GET['action']) ? $_GET['action']: "";

$bean = BeanFactory::getBean($module_name, $record_id);

if($action=="GetInfoForSendEmail"){
    $return['email'] = $bean->email1;
    $return['account_name'] = isset($bean->account_name) ? $bean->account_name : "";
    $return['phone_number'] = isset($bean->phone_mobile) ? $bean->phone_mobile : "";
    echo json_encode($return);
    die();
}
if($action == 'getInfoFromCall'){
    $accountID = '';
    switch ($module_name) {
        case 'Accounts':
            $accountID = $bean->id;
            break;
        case 'Contacts':
            $accountID = $bean->account_id;
            break;
        case 'Leads':
            $accountID = $bean->account_id;
            break;
        case 'AOS_Quotes':
            $accountID = $bean->billing_account_id;
            break;  
        default:
            # code...
            break;
    }
    $html = '<div class="col-xs-12 col-sm-12 edit-view-field" id="information_account_contact">';
    $html .= render_information_call($accountID);
    $html .= '</div>';
    echo $html;
    die();
}
if($module_name == "Employees"){
    if($bean->phone_mobile != ""){
        $mess_phone_number = preg_replace("/^0/", "#61", preg_replace('/\D/', '', $bean->phone_mobile));
        $mess_phone_number = preg_replace("/^61/", "#61", $mess_phone_number);
        $mess_phone_number = str_replace(" ", "", $mess_phone_number);
        echo "M: <span class='employee_phone_number'>". $bean->phone_mobile .'</span> <img class="sms_icon_invoice" data-source="employee" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14"> <a href=http://message.pure-electric.com.au/"'.$mess_phone_number.'"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="16" width="16"></a>';
        echo " ";
    }
    if($bean->phone_home != ""){
        echo "H: ". $bean->phone_home;
        echo " ";
    }
    if($bean->phone_work != ""){
        echo "W: ". $bean->phone_work;
        echo " ";
    }
}

if($module_name == "Contacts"||$module_name == "Leads"){
    if($bean->phone_mobile != ""){
        $mess_phone_number = preg_replace("/^0/", "#61", preg_replace('/\D/', '', $bean->phone_mobile));
        $mess_phone_number = preg_replace("/^61/", "#61", $mess_phone_number);
        $mess_phone_number = str_replace(" ", "", $mess_phone_number);
        echo "M: <span class='contact_phone_number'>". $bean->phone_mobile.'</span> <img class="sms_icon_invoice"  data-source="contact"  src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14"> <a href=http://message.pure-electric.com.au/"'.$mess_phone_number.'"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="16" width="16"></a>';
        echo " ";
    }
    if($bean->phone_home != ""){
        echo "H: ". $bean->phone_home;
        echo " ";
    }
    if($bean->phone_work != ""){
        echo "W: ". $bean->phone_work;
        echo " ";
    }
}
//Dung code
if($module_name == "Accounts") {
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT contacts.*  FROM contacts LEFT JOIN accounts_contacts ON  contacts.id = accounts_contacts.contact_id  Where accounts_contacts.account_id ='" .$bean->id ."'";

    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret)) {
    	$lookup_result[] = $row;
    }
    if($action == 'delivery_contact_phone_number'){
        if($lookup_result[0]['phone_mobile'] != ""){
            echo  $lookup_result[0]['phone_mobile'];

        }
        else if($lookup_result[0]['phone_home'] != ""){
            echo  $lookup_result[0]['phone_home'];
        } else{
            echo $lookup_result[0]['phone_work'];
        }
    } else {
        $mess_phone_number = preg_replace("/^0/", "#61", preg_replace('/\D/', '', $lookup_result[0]['phone_mobile']));
        $mess_phone_number = preg_replace("/^61/", "#61", $mess_phone_number);
        $mess_phone_number = str_replace(" ", "", $mess_phone_number);
        if($lookup_result[0]['phone_mobile'] != ""){
            echo "M: <span class='account_phone_number'>". $lookup_result[0]['phone_mobile'].'</span> <img class="sms_icon_invoice" data-source="account"  src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14"> <a href=http://message.pure-electric.com.au/"'.$mess_phone_number.'"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="16" width="16"></a>';
            echo " ";
        }
        if($lookup_result[0]['phone_home']!= ""){
            echo "H: ". $lookup_result[0]['phone_home'];
            echo " ";
        }
        if($lookup_result[0]['phone_work'] != ""){
            echo "W: ". $lookup_result[0]['phone_work'];
            echo " ";
        }
    }
}

 function render_information_call($accountID) {
    $hmtl = '';
    $bean = new Account();
    $bean->retrieve($accountID);
    $contacts = $bean->get_linked_beans('contacts','Contact');
   
    if(count($contacts)> 0){
        for($i=0;$i < count($contacts);$i++){
            if($contacts[$i]->id == $bean->primary_contact_c){
                $contact = $contacts[$i];
                break;
            }elseif($i == count($contacts) -1){
                $contact = $contacts[count($contacts) -1];
            }
        }
    }else{

        $db = DBManagerFactory::getInstance();
        //get by email
        $query = "SELECT accounts.id AS account_id,accounts_contacts.contact_id  FROM accounts 
                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = accounts.id 
                    LEFT JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id 
                    LEFT JOIN accounts_contacts ON accounts_contacts.account_id = accounts.id
                    WHERE accounts.name = '".$bean->name."' AND email_addresses.email_address = '".$bean->email1."' AND accounts.deleted = 0 
                    ORDER BY accounts.date_entered LIMIT 0,1";
        $ret = $db->query($query);
        while ($row = $db->fetchByAssoc($ret)) {
            $contactID = $row['contact_id'];
        }
        if($contactID == '' && $bean->id !='' ) {
            //get by from Lead
            $query = "SELECT leads.contact_id as contact_id 
            FROM leads 
            LEFT JOIN accounts ON accounts.id = leads.account_id 
            WHERE leads.account_id = '$bean->id' AND leads.account_id != '' ";

            $ret = $db->query($query);
            while ($row = $db->fetchByAssoc($ret)) {
                $contactID = $row['contact_id'];
            }
        }

        $contact = new Contact();
        $contact->retrieve($contactID);
    }

    if($contact->id != '') {
        $hmtl .= '<p>Primary Contact: <a target="_blank" href="/index.php?module=Contacts&action=EditView&record='. $contact->id .'">'.$contact->first_name. ' ' . $contact->last_name.'</a> </p>';
    }
    
    if($bean->mobile_phone_c != ""){
        $phone_number = $bean->mobile_phone_c;
    }elseif($bean->home_phone_c!= ""){
        $phone_number = $bean->home_phone_c;
    }else{
        $phone_number = $bean->phone_office;
    }
    
    if( $phone_number == '') {
        if($contact->phone_mobile != ""){
            $phone_number = $contact->phone_mobile;
        }elseif($contact->phone_home!= ""){
            $phone_number = $contact->phone_home;
        }else{
            $phone_number = $contact->phone_work;
        }
    } 

    if( $phone_number != '') { 
        $mess_phone_number = preg_replace("/^0/", "#61", preg_replace('/\D/', '', $phone_number));
        $mess_phone_number = preg_replace("/^61/", "#61", $mess_phone_number);
        $mess_phone_number = str_replace(" ", "", $mess_phone_number);
        $phone_number =   preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $phone_number); //format mobile phone number
        $hmtl .= '<p>M:<input type="text" disabled class="sugar_field phone" style="font-size: x-large;" id="mobile_phone_c" value="'.$phone_number.'"> <img class="sms_icon" data-source="account"  src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14"> <a target="_blank" href="http://message.pure-electric.com.au/'.$mess_phone_number.'" title="Message Portal" ><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a></p>';
    }

    if($bean->email1 != '') {
        $emailAddress =  $bean->email1;
    }else{
        $emailAddress =$contact ->email1;
    }
    if( $emailAddress != '') {       
        $html_copy_email = <<<HTML
        <a class="copy-email-link" data-email-address="{$emailAddress}"
                title="Copy {$emailAddress}" onclick="$(document).copy_email_address(this);"
            style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip"
            >  &nbsp;<span class="glyphicon glyphicon-copy"></span>
            <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied {$emailAddress}</span>
            </a>
        HTML; 
        $hmtl .= '<p>Email: <a style="" onclick="$(document).openComposeViewModal(this);" data-module="Accounts" data-record-id="'.$bean->id.'" data-module-name="'.$bean->name.'" data-email-address="'.$emailAddress.'">'.$emailAddress.'</a>';
        $hmtl .= '-<a style="color:blue;" class="email-link-gsearch" target="_blank" href="https://mail.google.com/#search/'.$emailAddress.'">GSearch</a>'.$html_copy_email .'</p>';
    } 
    
    $Address =  $bean->billing_address_street .' '.$bean->billing_address_city. ' '. $bean->billing_address_state. ' '.$bean->billing_address_postalcode;
    if( $Address != '') {
        $hmtl .= '<p>Address:'.$Address .'</p>';
    } 

    return $hmtl;
}
?>