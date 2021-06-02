<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class UpdateRelatedProductPrices
    {
        function after_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            global $current_user;
            if (strcmp($old_fields['name'], $bean->name) != 0 || strcmp($old_fields['part_number'], $bean->part_number) != 0) { 
                $db = DBManagerFactory::getInstance();
                $query  = " UPDATE pe_product_prices 
                            SET name = '{$bean->name}', part_number = '{$bean->part_number}'  
                            WHERE pe_product_prices.product_id = '{$bean->id}'";
                $db->query($query);
            } 
        }
    }
