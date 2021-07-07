<?php

$data_return = array();
$db = DBManagerFactory::getInstance();

//name
$data_return['battery_main'] = getDataByCategoryName($db, "cc2e434a-e627-8d01-0a2a-5b05f6d2930e", "59514f8a-9874-037b-9c69-60de962209a9");
//install
$data_return['battery_install'] = getDataByCategoryName($db, "" , "59514f8a-9874-037b-9c69-60de962209a9");
// Return
echo json_encode($data_return);

function getDataByCategoryName($db, $brand_name, $condition = ''){
  $data = array();
  if( $condition != "" ){
    if( $brand_name != "" ){
      $condition = " ap.accounts_aos_products_1accounts_ida = '". $brand_name ."' AND p.aos_product_category_id = '". $condition."'"  ;//ap.accounts_aos_products_1accounts_ida = 'cc2e434a-e627-8d01-0a2a-5b05f6d2930e'
    }else {
      $condition = "p.aos_product_category_id = '". $condition."'"  ;//ap.accounts_aos_products_1accounts_ida = 'cc2e434a-e627-8d01-0a2a-5b05f6d2930e'
    }
  }
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
      , pc.tempcoeff_c
      , pc.noct_c
      , pc.cop_cooling_c
    FROM aos_products p	
    LEFT JOIN aos_product_categories c 
    ON p.aos_product_category_id = c.id
    LEFT JOIN aos_products_cstm pc
    ON p.id = pc.id_c
    LEFT JOIN accounts_aos_products_1_c ap
    ON p.id = ap.accounts_aos_products_1aos_products_idb
    WHERE  {$condition}
    AND (pc.product_status_c = 'available' OR pc.product_status_c IS NULL)
    ORDER BY pc.short_name_c ASC";
    //c.name = '{$category_name}'
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
    'tempcoeff' => $record['tempcoeff_c'],
    'noct' => $record['noct_c'],
    'cop_cooling_c' => $record['cop_cooling_c'],
    'item_code_xero' => $record['item_code_xero'],
    ));
  }
  return $data;
}