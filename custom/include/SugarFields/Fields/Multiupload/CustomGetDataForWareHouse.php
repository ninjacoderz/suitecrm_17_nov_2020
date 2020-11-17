<?php

    $db  = DBManagerFactory::getInstance();

    $deliver_to = $_REQUEST['deliver_to'];
    $sold_to = $_REQUEST['sold_to'];

    $deliver_arr = array();
    $sold_arr = array();
    if($deliver_to != ''){
        $account_query = "SELECT id FROM accounts WHERE name LIKE '%".$deliver_to."%'";
        $ret_acc = $db->query($account_query);
        if($ret_acc->num_rows >0){
            $row_id = $db->fetchByAssoc($ret_acc);
            $deliver_to = $row_id["id"];
            $deliver_arr['deliver_to'] = $deliver_to;
            $account = new Account();
            $account = $account->retrieve($deliver_to);
                if($account->id != ''){
                    $deliver_arr['billing_address_street'] = $account->billing_address_street;
                    $deliver_arr['billing_address_city'] = $account->billing_address_city;
                    $deliver_arr['billing_address_state'] = $account->billing_address_state;
                    $deliver_arr['billing_address_postalcode'] = $account->billing_address_postalcode;
            }
        }
    }

    if($sold_to != ''){
        $account_query =  "SELECT id FROM accounts WHERE name LIKE '%".$sold_to."%'";
        $ret_acc = $db->query($account_query);
        if($ret_acc->num_rows >0){
            $row_id = $db->fetchByAssoc($ret_acc);
            $sold_to = $row_id["id"];
            $sold_arr['sold_to'] = $sold_to;
            $account = new Account();
            $account = $account->retrieve($sold_to);
            if($account->id != ''){
                $sold_arr['shipping_address_street'] = $account->shipping_address_street;
                $sold_arr['shipping_address_city'] = $account->shipping_address_city;
                $sold_arr['shipping_address_state'] = $account->shipping_address_state;
                $sold_arr['shipping_address_postalcode'] = $account->shipping_address_postalcode;
            }
        }
    }
    
    echo json_encode(array($deliver_arr,$sold_arr));
    die();