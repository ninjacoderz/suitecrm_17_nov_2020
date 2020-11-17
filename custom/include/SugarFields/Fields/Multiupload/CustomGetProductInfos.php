<?php 
if(isset($_POST["part_numbers"]) && count($_POST["part_numbers"])>0){
    $part_numners = $_POST["part_numbers"];
    $part_numners_implode = implode("','", $part_numners);
    $db = DBManagerFactory::getInstance();

    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
    $ret = $db->query($sql);

    $products = array();
    while ($row = $db->fetchByAssoc($ret))
    {
        $product = array();
        $product['product_currency'] = $row['currency_id'];
        $product['product_item_description'] = $row['description'];
        $product['product_name'] = $row['name'];
        $product['product_part_number'] = $row['part_number'];
        $product['product_product_cost_price'] = $row['cost'];
        $product['product_product_id'] = $row['id'];
        $product['product_product_list_price'] = $row['price'];
        $products[$product['product_part_number']] = $product;
    }
    $ordered_products = array();
    foreach($part_numners as $part_number){
        if(isset($products[$part_number])) $ordered_products[$part_number] = $products[$part_number];
    }
    $return_product = array();
    foreach($ordered_products as $product){
        $return_product[] = $product;
    }
    print(json_encode($return_product));
}

//VUT- Get price/cost Product >> custom\include\SugarFields\Fields\Multipayment\js\multipayment.js
$product_id = $_REQUEST['product_id'];
$type = $_REQUEST['type'];

if (isset($product_id) && $type=='gp_profit') {
  $product = new AOS_Products();
  $product->retrieve(trim($product_id));
    if ($product->id != '') {
      $res = array(
        'price' => $product->price,
        'cost' => $product->cost,
      );
      echo json_encode($res);
  }
}

die();
?>