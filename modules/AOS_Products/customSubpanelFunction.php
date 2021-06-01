<?php

function get_product_prices() {
    global $app;
    $controller = $app->controller;
    $bean = $controller->bean;

    $return_array['select']='SELECT DISTINCT pe_product_prices.id as prices_id';
    $return_array['from']='FROM pe_product_prices';
    $return_array['where']="pe_product_prices.deleted = 0 AND aos_products.id ='{$bean->id}'";
    $return_array['join'] = "LEFT JOIN aos_products ON aos_products.part_number = pe_product_prices.part_number";
    $return_array['join_tables'][0] = '';
    return $return_array;
}
