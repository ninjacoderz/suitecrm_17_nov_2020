<?php
//Setting Line Items
    $product_id = $_REQUEST['product_id'];
    $db = DBManagerFactory::getInstance();
	$sql = "SELECT * FROM aos_products WHERE  `id` = '".$product_id."' AND deleted = 0";
    $result = $db->query($sql);
    $line_items = array();
    while ($row = $result->fetch_assoc()) {
        $item = array();
        $item['product_currency'] = $row['currency_id'];
        $item['product_item_description'] = $row['description'];
        $item['product_name'] = $row['name'];
        $item['product_part_number'] = $row['part_number'];
        $item['product_product_cost_price'] = $row['cost'];
        $item['product_product_id'] = $row['aos_product_category_id'];
        $item['product_product_list_price'] = $row['price'];
        // $item['product_qty'] = $row['product_qty'];
        $line_items[$invoiceGroupIds[$row['group_id']]][] = $item;
    }
    $return_array['line_items'] = $line_items;
    print(json_encode($return_array));
?>