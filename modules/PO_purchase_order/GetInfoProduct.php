<?php
//Setting Line Items
    $product_id = $_REQUEST['product_id'];
    $db = DBManagerFactory::getInstance();
	$sql = "SELECT * FROM aos_products WHERE  `id` = '".$product_id."' AND deleted = 0";
    $result = $db->query($sql);
    $item = array();
    while ($row = $result->fetch_assoc()) {
        $item['product_currency'] = $row['currency_id'];
        $item['product_item_description'] = (empty($row['description']) ? "" : $row['description']);
        $item['product_name'] = (empty($row['name']) ? "" : $row['name']);
        $item['product_part_number'] = $row['part_number'];
        $item['product_product_cost_price'] = $row['cost'];
        $item['product_product_id'] = $product_id;
        $item['product_product_list_price'] = $row['price'];
    }
    echo json_encode($item);
?>