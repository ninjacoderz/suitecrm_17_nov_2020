<?php 
/*$data = $_POST['json'];

//$data = json_decode($json, true);
$record_id = $data['record_id'];
$line_item_orders = $data['line_item_orders'];
$db = DBManagerFactory::getInstance();
$i = 1;
foreach($line_item_orders as $item){ 
    $update_line_item = "UPDATE aos_products_quotes SET number = $i WHERE parent_id = '$record_id' AND product_id = '$item'";  
    $result_test = $db->query($update_line_item);              
    $i ++;
}

die();