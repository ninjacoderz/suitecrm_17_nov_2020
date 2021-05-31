<?php
    // .:nhantv:. Get Product Info by Short name
    $short_name = $_REQUEST['short_name'];
    $db = DBManagerFactory::getInstance();
	$sql = "SELECT p.id, p.name, pc.short_name_c, p.cost, p.price, p.description, p.part_number, p.currency_id".
        " FROM `aos_products` p LEFT JOIN `aos_products_cstm` pc ON p.id = pc.id_c".
        " WHERE LCASE(TRIM(pc.short_name_c)) = LCASE(TRIM('".$short_name."'))".
        " OR LCASE(TRIM(p.name)) = LCASE(TRIM('".$short_name."'))".
        " AND p.deleted = 0 AND pc.product_status_c = 'available'";
    $result = $db->query($sql);
    $item = array();
    while ($row = $result->fetch_assoc()) {
        $item['id'] = $row['id'];
        $item['name'] = (empty($row['name']) ? "" : $row['name']);
        $item['short_name'] = (empty($row['short_name_c']) ? "" : $row['short_name_c']);
        $item['cost'] = $row['cost'];
        $item['price'] = $row['price'];
        $item['description'] = $row['description'];
        $item['part_number'] = $row['part_number'];
        $item['currency'] = $row['currency_id'];
    }
    echo json_encode($item);
?>