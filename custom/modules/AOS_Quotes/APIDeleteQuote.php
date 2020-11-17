<?php

$quote = new AOS_Quotes();
$quote->retrieve($_REQUEST['quote_id']);
if($quote->id == '') {
    echo json_encode(array('msg'=>'error'));
    die();
};
$quote->mark_deleted($quote->id);

?>