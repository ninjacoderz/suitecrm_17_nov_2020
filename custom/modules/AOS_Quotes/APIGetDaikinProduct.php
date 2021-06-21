<?php

$data_return = array();
$db = DBManagerFactory::getInstance();

//main
$data_return['dk_main'] = getDataByCategoryName($db, "Daikin", "main");
//extra
$data_return['dk_extra'] = getDataByCategoryName($db, "Daikin", "extra");
//wifi
$data_return['dk_wifi'] = getDataByCategoryName($db, "Daikin", "wifi");
//install
$data_return['dk_install'] = getDataByCategoryName($db, "Daikin", "install");
//air install
$data_return['dk_air_install'] = getDataByCategoryName($db, "Install Air Conditioner");


// Return
echo json_encode($data_return);

function getDataByCategoryName($db, $category_name, $condition = ''){
  $data = array();
  $condition = ($condition != "") ? "AND p.daikin_category = '". $condition ."'" : "";
  $sql = "SELECT p.id, p.name AS product_name
      , pc.short_name_c AS short_name
      , c.name AS category_name
      , p.cost
      , p.price
      , p.description
      , p.part_number
      , p.currency_id
      , p.item_code_xero
      , pc.product_status_c
      , pc.capacity_c
      , p.daikin_category
      , pc.heating_cooling_category_c
      , pc.rated_capacity_heating_c
      , pc.range_lower_heating_c
      , pc.range_upper_heating_c
      , pc.cop_heating_c
      , pc.rated_capacity_cooling_c
      , pc.range_lower_cooling_c
      , pc.range_upper_cooling_c
      , pc.cop_cooling_c
    FROM aos_products p	
    LEFT JOIN aos_product_categories c 
    ON p.aos_product_category_id = c.id
    LEFT JOIN aos_products_cstm pc
    ON p.id = pc.id_c
    WHERE c.name = '{$category_name}' {$condition}
    AND (pc.product_status_c = 'available' OR pc.product_status_c IS NULL)
    ORDER BY pc.short_name_c ASC";
    
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
    'daikin_category' => $record['daikin_category'],
    'heat_cool_category' => $record['heating_cooling_category_c'],
    'heat_capacity' => $record['rated_capacity_heating_c'],
    'heat_lower' => $record['range_lower_heating_c'],
    'heat_upper' => $record['range_upper_heating_c'],
    'heat_cop' => $record['cop_heating_c'],
    'cool_capacity' => $record['rated_capacity_cooling_c'],
    'cool_lower' => $record['range_lower_cooling_c'],
    'cool_upper' => $record['range_upper_cooling_c'],
    'cool_cop' => $record['cop_cooling_c'],
    'item_code_xero' => $record['item_code_xero'],
    ));
  }
  return $data;
}