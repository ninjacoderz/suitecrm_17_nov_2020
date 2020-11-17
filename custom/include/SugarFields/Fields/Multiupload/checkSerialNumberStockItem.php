<?php
$serial_number = strtolower($_GET['serial_number']);

$db = DBManagerFactory::getInstance();

$sql_stock = "SELECT * FROM pe_stock_items WHERE parent_type = 'pe_warehouse_log' AND deleted = 0 AND LOWER(serial_number) ='$serial_number'";
$result = $db->query($sql_stock);
if($result->num_rows > 0){
    echo 'exits';
}else{
    echo 'not exits';
}
