<?php

if (isset($_POST['type_get']) || $_POST['type_get'] == 'quote_input' ) {
    $listPartNumber = ['Sanden_Complex_Install', 'SANDEN_ELEC_EXTRA', 'RCBO', 'SwitchUpgrade', 'HWS_R', 'Sanden_Tank_Slab', 'Sanden_HP_Pavers', 'Site_Delivery', 'Spec_Trade_Disc', 'san_wall_bracket', 'Travel'];
} else {
    $listPartNumber = $_POST;
}

$data_return = array();
$db = DBManagerFactory::getInstance();

foreach ($listPartNumber as $key => $value) {
    $sql = "SELECT * FROM aos_products WHERE part_number = '".$value."' AND deleted = 0";
    $ret = $db->query($sql);
    $row = $db->fetchByAssoc($ret);
    $data_return[$key] = array(
        'name' => $row['name'],
        'part_number' => $row['part_number'],
        'description' => trim(str_replace("\n", "<br />", $row['description'])),
        'cost' => round($row['cost'], 2),
        'product_image_c' => $row['product_image'],
        'product_status_c' => $row['product_status_c']
    );
}
    
echo json_encode($data_return);