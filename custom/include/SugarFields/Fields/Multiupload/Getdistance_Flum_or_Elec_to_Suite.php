<?php
    $account_id2_c =$_GET['ac_id'];
    $accounts = new Account();
    $accounts->retrieve($account_id2_c);
    $from_address = $accounts->billing_address_street . ', ' .$accounts->billing_address_city .', ' 
    .$accounts->billing_address_state .', ' .$accounts->billing_address_postalcode;
    echo $from_address;
?>