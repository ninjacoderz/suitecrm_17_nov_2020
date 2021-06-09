<?php

    $db = DBManagerFactory::getInstance();
    $moduleID = $_REQUEST["moduleID"];
    $products = [];
    if(!empty($moduleID)){

        $focus = new AOS_Quotes();
        $focus = $focus->retrieve($moduleID);
        if($focus){
            $sql = "SELECT pg.id, pg.group_id,pg.part_number FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $focus->object_name . "' AND pg.parent_id = '" . $focus->id . "' AND pg.deleted = 0 AND lig.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                $products[] = $row;
            }
        }
    }
    echo  json_encode($products);
?>