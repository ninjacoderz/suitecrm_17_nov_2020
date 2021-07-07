<?php
// Enable cross domain call
header('Access-Control-Allow-Origin: *');

if (!isset($_POST)) {
  return;
}

$listPartNumber = array_values($_POST);

// build IN statement
$inStatement = "";
for($i = 0; $i < count($listPartNumber); ++$i) {
  $inStatement .= "'".$listPartNumber[$i]."'";
  if ($i + 1 < count($listPartNumber)) {
    $inStatement .= ", ";
  }
}

$data_return = array();
$db = DBManagerFactory::getInstance();

$sql = "SELECT * FROM aos_products 
  LEFT JOIN aos_products_cstm 
  ON aos_products.id = aos_products_cstm.id_c 
  WHERE aos_products.part_number IN (". $inStatement .") AND aos_products.deleted = 0";
$ret = $db->query($sql);
while ($row = $db->fetchByAssoc($ret)) {
  array_push($data_return, array(
    'name' => $row['name'],
    'partNumber' => $row['part_number'],
    'description' => trim($row['description']),
    'cost' => round($row['cost'], 2),
    'productImage' => $row['product_image'],
    'productStatus' => $row['product_status_c']
  ));
}

echo json_encode($data_return);