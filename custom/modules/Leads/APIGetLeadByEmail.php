<?php

    $email_info =  $_REQUEST['email_info'];
    // $email_info =  'tritruong.dev@gmail.com';
    $data_return = [];
    // $module = $_REQUEST['modules'];

    $db = DBManagerFactory::getInstance();
    $sql = "SELECT leads.id FROM leads INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = leads.id INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id WHERE leads.deleted = 0 AND email_addresses.email_address = '$email_info' LIMIT 1";
    $ret = $db->query($sql);
    $row = $db->fetchByAssoc($ret);

    $existed_lead = false;
    if($ret->num_rows > 0){
        $lead =  new Lead();
        $lead->retrieve($row['id']);
        $data_return['id'] = $lead->id;
        $data_return['name'] = $lead->name;
        $data_return['first_name'] = $lead->first_name;
        $data_return['last_name'] = $lead->last_name;
        $data_return['status'] = $lead->status;
        $data_return['account_id'] = $lead->account_id;
        $data_return['account_name'] = $lead->account_name;
        $data_return['billing_address_street'] = $lead->primary_address_street; 
        $data_return['billing_address_city'] = $lead->primary_address_city;
        $data_return['billing_address_state'] = $lead->primary_address_state;
        $data_return['billing_address_postalcode'] = $lead->primary_address_postalcode;
        $data_return['address'] = $lead->primary_address_street;
        $data_return['billing_account_email'] = $lead->email1; 
        $data_return['mobile_phone_c'] = $lead->phone_mobile;
        $data_return['phone_office'] = $lead->phone_work;
    }
    echo json_encode($data_return);
?>