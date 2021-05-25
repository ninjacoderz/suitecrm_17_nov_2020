<?php

function get_product_prices()
{   
    $query = "
            SELECT pe_product_prices.*
            FROM pe_product_prices
            LEFT JOIN aos_products ON aos_products.part_number = pe_product_prices.part_number
            WHERE pe_product_prices.deleted = 0 AND aos_products.id ='{$_REQUEST['record']}'
        ";
    return $query;
}