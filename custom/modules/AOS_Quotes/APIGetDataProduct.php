<?php

$listPartNumber = $_POST;
$data_return = array();
$db = DBManagerFactory::getInstance();

foreach ($listPartNumber as $key => $value) {
    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$value."') AND deleted = 0 GROUP BY part_number";
    $ret = $db->query($sql);
    $row = $db->fetchByAssoc($ret);
    $data_return[$key] = array(
        'name' => $row['name'],
        'part_number' => $row['part_number'],
        'description' => trim(str_replace("\n", "<br />", $row['description'])),
        'cost' => round($row['cost'], 2),
    );
}
    
echo json_encode($data_return);