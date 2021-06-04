<?php

function get_address() {
    global $app;
    $controller = $app->controller;
    $bean = $controller->bean;

    // $return_array['select']='SELECT DISTINCT pe_address.id';
    $return_array['from']='FROM pe_address';
    $return_array['where']="aos_quotes.id = '{$bean->id}'";
    $return_array['join'] = "   LEFT JOIN accounts ON accounts.id = pe_address.billing_account_id
                                LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = accounts.id
                                ";
    $return_array['join_tables'][0] = '';
    return $return_array;
}
