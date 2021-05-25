<?php

$data_return = array();
$db = DBManagerFactory::getInstance();

// Get Solar panels
$data_return['panel_data'] = getDataByCategoryName($db, "Solar", "solar_panels");
// Get Inverter
$data_return['inverter_data'] = getDataByCategoryName($db, "Microgrid", "inverters");
// Get Off-Grid Inverter
$data_return['og_inverter_data'] = getDataByCategoryName($db, "Microgrid", "og_inverters");
// Get Batteries
$data_return['battery_data'] = getDataByCategoryName($db, "Microgrid", "battery_storage");
// Get Accessories
$data_return['accessory_data'] = getDataByCategoryName($db, "Microgrid", "accessories");
// Get RE Generator
$data_return['re_generator_data'] = getDataByCategoryName($db, "Microgrid", "re_generator");

// Return
echo json_encode($data_return);

function getDataByCategoryName($db, $category_name, $solar_category){
  $data = array();
  $sql = "SELECT p.id, p.name AS product_name
      , pc.short_name_c AS short_name
      , c.name AS category_name
      , p.cost
      , p.price
      , p.description
      , p.part_number
      , p.currency_id
      , pc.product_status_c
      , pc.capacity_c
      , pc.solar_category_c
    FROM aos_products p	
    LEFT JOIN aos_product_categories c 
    ON p.aos_product_category_id = c.id
    LEFT JOIN aos_products_cstm pc
    ON p.id = pc.id_c
    WHERE c.name = '". $category_name ."'
    AND pc.solar_category_c = '". $solar_category ."'
    AND pc.product_status_c = 'available'
    ORDER BY pc.short_name_c ASC";
    
  $result = $db->query($sql);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  foreach ($rows as $record) {
    if($record['short_name'] != null || $record['short_name'] != ''){
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
        'solar_category' => $record['solar_category_c'],
      ));
    }
  }
  return $data;
}