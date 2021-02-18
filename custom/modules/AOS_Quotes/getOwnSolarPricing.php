<?php
// $test = '{"own_totalkW_1":"6.5","own_totalkW_2":"10.4","own_totalkW_3":"6.5","own_totalkW_4":"10.8","own_totalkW_5":"6.65","own_totalkW_6":"10.5","own_panelType_1":"Sunpower P3 325 BLACK","own_panelType_2":"Sunpower Maxeon 3 400","own_panelType_3":"Sunpower P3 325 BLACK","own_panelType_4":"Sunpower Maxeon 3 400","own_panelType_5":"Q CELLS Q.MAXX-G2 350W","own_panelType_6":"Q CELLS Q.MAXX-G2 350W","own_inverterType_1":"Sungrow 5","own_inverterType_2":"Sungrow 8","own_inverterType_3":"Primo 5","own_inverterType_4":"Primo 8.2","own_inverterType_5":"S Edge 5","own_inverterType_6":"S Edge 8","own_totalPanels_1":"20","own_totalPanels_2":"26","own_totalPanels_3":"20","own_totalPanels_4":"27","own_totalPanels_5":"19","own_totalPanels_6":"30"}';
// $input = json_decode($test, true);
$panels_type = $_POST["panel_type"];
$inverters_type = $_POST["inverter_type"];
//change name panel
if (array_search('Sunpower P3 370 BLACK',$panels_type) != false) {
    $panels_type[array_search('Sunpower P3 370 BLACK',$panels_type)] = 'Sunpower P3 370W BLACK';
}

$panel_sql = implode('|', $panels_type);
$products_panel = getProductSolar($panel_sql, 'panel'); 
$inverter_sql = implode('|', $inverters_type);
$products_inverter = getProductSolar($inverter_sql, 'panel'); 

$products = [
    'panels' => $products_panel,
    'inverters' => $products_inverter
];
echo json_encode($products);

die();
// /** getOwnSolarPricing */
// if (isset($_REQUEST["json_data"])) {
//     $input = $_REQUEST["json_data"];
//     $products = getProductSolar($input);

//     $input['own_solar_pv_std_install'] = floatval($products['Solar PV Standard Install']);
//     for ($i=1;$i<7;$i++) {
//         //panel
//         if (array_key_exists($input['own_panelType_'.$i],$products)) {
//             $input['own_panelCost_'.$i] = floatval($products[$input['own_panelType_'.$i]]);
//         }
//         //inverter
//         if ($input['own_inverterType_'.$i] != '') {
//             foreach ($products as $name=>$cost) {
//                 if (preg_match("/".$input['own_inverterType_'.$i]."/",$name)) {
//                     $input['own_inverterCost_'.$i] = floatval($cost);
//                     break;
//                 }
//             }
//         }
//         //calculate base Price
//         $panels_totalCost = $input['own_panelCost_'.$i]*floatval($input['own_totalPanels_'.$i]);
//         $inverter_cost = $input['own_inverterCost_'.$i];
//         $install_cost = $input['own_solar_pv_std_install']*floatval($input['own_totalkW_'.$i]);
//         $basePrice = $panels_totalCost + $inverter_cost + $install_cost;
//         $input['own_basePrice_'.$i] = number_format($basePrice,2,'.','');
//     }

//     echo json_encode($input); 
// } else {
//     echo '';
// }

//DECLARE FUNTIONS
function getProductSolar($string, $type='') {
    $products = [];
    $id_product_category_solar = '64f85f4e-1b8c-e00a-7e4f-5918dcde98e9';
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT name, cost 
            FROM aos_products 
            WHERE aos_product_category_id = '".$id_product_category_solar."'
                AND name REGEXP '(".$string.")' AND deleted = 0";
    $ret = $db->query($sql);
    while($row = $ret->fetch_assoc()){
        $products[$row['name']] = number_format(floatval($row['cost']),2,'.','');
    }
    
    //check product Sunpower P3 325W BLACK
    if ($type == 'panel' && array_key_exists('Sunpower P3 370W BLACK',$products)) {
        $products['Sunpower P3 370 BLACK'] = $products['Sunpower P3 370W BLACK'];
        unset($products['Sunpower P3 370W BLACK']);
    }
    return $products;
}


// /**
//  * VUT - get product solar (panel, inverter, std install)
//  * @param {'array'} $array
//  * @return {'array'} $products
//  */
// function getProductSolar($data) {
//     $regex_sql = [];
//     // $inverter_sql = [];
//     $products = [];
//     $arr_panel = array(   
//         //'Jinko 330W Mono PERC HC',
//         //'Longi Hi-MO X 350W',
//         'Jinko 370W Cheetah Plus JKM370M-66H' => 'Jinko 370W Cheetah Plus JKM370M-66H',
//         'Q CELLS Q.MAXX-G2 350W' => 'Q CELLS Q.MAXX-G2 350W',
//         // 'Q CELLS Q.PEAK DUO G6+ 350W',
//         // 'Sunpower X22 360W',
//         'Sunpower Maxeon 3 400' => 'Sunpower Maxeon 3 400',
//         'Sunpower P3 325 BLACK' => 'Sunpower P3 325W BLACK',
//     );
    
//     for ($i=1;$i<7;$i++) {
//         if (!in_array($arr_panel[$data['own_panelType_'.$i]],$regex_sql) ) {
//             array_push($regex_sql,$arr_panel[$data['own_panelType_'.$i]]);
//         }
//         if (!in_array($data['own_inverterType_'.$i],$regex_sql) ) {
//             array_push($regex_sql,$data['own_inverterType_'.$i]);
//         }
//     }
//     //Std solar install
//     array_push($regex_sql, 'Solar PV Standard Install');

//     $in_sql = implode("|",$regex_sql);
//     $id_product_category_solar = '64f85f4e-1b8c-e00a-7e4f-5918dcde98e9';
//     $db = DBManagerFactory::getInstance();
//     $sql = "SELECT name, cost 
//             FROM aos_products 
//             WHERE aos_product_category_id = '".$id_product_category_solar."'
//                 AND name REGEXP '(".$in_sql.")' AND deleted = 0";
//     $ret = $db->query($sql);
//     while($row = $ret->fetch_assoc()){
//         $products[$row['name']] = $row['cost'];
//     }
    
//     //check product Sunpower P3 325W BLACK
//     if (array_key_exists('Sunpower P3 325W BLACK',$products)) {
//         $products['Sunpower P3 325 BLACK'] = $products['Sunpower P3 325W BLACK'];
//         unset($products['Sunpower P3 325W BLACK']);
//     }

//     return $products;
// }

//END DECLARE FUNCTIONS



