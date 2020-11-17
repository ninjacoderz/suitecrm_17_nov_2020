<?php
    header('Access-Control-Allow-Origin: *');
    ini_set('memory_limit', '-1');
    $data_return = array();
    $listPartNumber = $_POST;
    // $record = $_REQUEST['record_id'];
    foreach ($listPartNumber as $key => $value) {
            
        $product = new AOS_Products();
        $product->retrieve($value);
        $text = $product->description;
        $data_return[$key] = array(
            'description' => trim(str_replace("\n", "<br />", $text)),
        );
    }
    
    echo json_encode($data_return);
?>