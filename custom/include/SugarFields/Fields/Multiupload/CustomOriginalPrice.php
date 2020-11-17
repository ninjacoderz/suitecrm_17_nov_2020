<?php
$data = [];
$id_partNum = $_GET['id_partNumber'];
$id_partNum = json_decode(htmlspecialchars_decode($id_partNum));
foreach ($id_partNum as $key => $value){
    $product = new AOS_Products;
    $product->retrieve($value);
    $data[$key]["cost_price"] =  $product->cost;
    $data[$key]["price"] =  $product->price;
    $data[$key]["part_number"] =  $product->part_number;
    $data[$key]["product_name"] =  $product->name;
    $data[$key]["description"] = $product->description;
    $data[$key]["currency_id"] =  $product->currency_id;
}

echo json_encode($data);
?>