<?php 

$parent_id = $_REQUEST['parent_id'];
// $parent_module = $_REQUEST['parent_module'];
$product = new AOS_Products();
$product->retrieve($parent_id);
if ($product->id) {
    $price = new pe_product_prices();
    $price->name = $product->name;
    $price->part_number = $product->part_number;
    $price->description = $product->description;
    $price->save();
    echo trim($price->id);
} else {
    echo 'error';
}
