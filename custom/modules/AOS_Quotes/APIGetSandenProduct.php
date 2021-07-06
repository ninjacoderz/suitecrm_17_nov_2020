<?php

$data_return = array();
$db = DBManagerFactory::getInstance();

//Complete
$data_return['sanden_complete'] = getDataByCategoryName($db, "Sanden", "complete");
//HeatPump
$data_return['sanden_hpump'] = getDataByCategoryName($db, "Sanden", "heat_pump");
//Tank
$data_return['sanden_tank'] = getDataByCategoryName($db, "Sanden", "tank");
//accessory
$data_return['sanden_accessory'] = getDataByCategoryName($db, "Sanden", "accessory");
//extra
$data_return['sanden_extra'] = getDataByCategoryName($db, "Sanden", "extra");

//install (include delivery)
$data_return['sanden_install'] = getDataByCategoryName($db, "Sanden", "install");


// Return
echo json_encode($data_return);

function getDataByCategoryName($db, $category_name, $condition = ''){
  $data = array();
  $condition = ($condition != "") ? "AND p.sanden_category = '". $condition ."'" : "";
  $sql = "SELECT p.id, p.name AS product_name
      , pc.short_name_c AS short_name
      , c.name AS category_name
      , p.cost
      , p.price
      , p.description
      , p.part_number
      , p.currency_id
      , p.item_code_xero
      , p.sanden_category
      , pc.product_status_c
      , pc.capacity_c
    FROM aos_products p	
    LEFT JOIN aos_product_categories c 
    ON p.aos_product_category_id = c.id
    LEFT JOIN aos_products_cstm pc
    ON p.id = pc.id_c
    WHERE c.name = '{$category_name}' {$condition}
    AND (pc.product_status_c = 'available' OR pc.product_status_c IS NULL)
    ORDER BY p.name ASC";
    
  $result = $db->query($sql);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  foreach ($rows as $record) {
    array_push($data, array(
    'id' => $record['id'],
    'name' => $record['product_name'],
    'short_name' => $record['short_name'],
    'cost' => $record['cost'],
    'price' => $record['price'],
    'description' => $record['description'],
    'part_number' => $record['part_number'],
    'currency' => $record['currency_id'],
    'product_status' => $record['product_status'],
    'category_name' => $record['category_name'],
    'capacity' => $record['capacity_c'],
    'item_code_xero' => $record['item_code_xero'],
    ));
  }
  return $data;
}