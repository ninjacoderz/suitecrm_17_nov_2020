<?php

$quote = new AOS_Quotes();
$quote->retrieve($_POST['quote_id']);
if($quote->id == '') {
    echo json_encode(array('msg'=>'error'));
    die();
};

$quote->drupal_node_c = $_POST['node_id'];
$quote->save();

?>