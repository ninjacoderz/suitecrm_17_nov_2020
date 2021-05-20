<?php
function get_account_address_subpanel()
{   
    // $bean = BeanFactory::getBean(
    //     $_REQUEST['module'],
    //     $_REQUEST['record']
    // );
    $query = "
            SELECT pe_address.*
            FROM pe_address
            JOIN accounts ON accounts.id = pe_address.billing_account_id AND pe_address.deleted = 0 AND accounts.id = '{$_REQUEST['record']}'
        ";
    return $query;
}