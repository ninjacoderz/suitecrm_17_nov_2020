<?php
// .:nhantv:. Get Design data from Solar design tool
header('Access-Control-Allow-Origin: *');

$data_return = array();
$db = DBManagerFactory::getInstance();

// Get Solar panels
$data_return['panel_data'] = getDataByCategoryName($db, "Solar", "solar_panels");

// Return
echo json_encode($data_return);

function getDataByCategoryName($db, $category_name, $solar_category){
  $data = array();
  $solar_condition = ($solar_category != "") ? "AND pc.solar_category_c = '". $solar_category ."'" : "";
  $sql = "SELECT 
      p.id
      , p.name AS manufacturer
      , p.part_number AS model
      , pc.panel_type_c AS type
      , pc.length_c AS length
      , pc.width_c AS width
      , pc.capacity_c AS nominalPower
      , pc.noct_c AS NOCT
      , pc.tempCoeff_c AS tempCoeff
    FROM aos_products p	
    LEFT JOIN aos_product_categories c 
    ON p.aos_product_category_id = c.id
    LEFT JOIN aos_products_cstm pc
    ON p.id = pc.id_c
    WHERE c.name = '". $category_name ."'". $solar_condition .
    "AND pc.product_status_c = 'available'
    ORDER BY p.name ASC";
    
  $result = $db->query($sql);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $index = 0;
  foreach ($rows as $record) {
    if($record['manufacturer'] != null || $record['manufacturer'] != ''){
      $index += 1;
      array_push($data, array(
        'id' => $index,
        'manufacturer' => $record['manufacturer'],
        'model' => $record['model'],
        'type' => $record['type'],
        'length' => $record['length'],
        'width' => $record['width'],
        'nominalPower' => $record['nominalPower'],
        'NOCT' => $record['NOCT'],
        'tempCoeff' => $record['tempCoeff'],
      ));
    }
  }
  return $data;
}