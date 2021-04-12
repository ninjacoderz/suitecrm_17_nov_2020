<?php
    // .:nhantv:. Get Design data from Solar design tool
    header('Access-Control-Allow-Origin: *');

    $id = $_REQUEST['id'];
    // Case id invalid
    if(!isset($id) && $id == '') return;

    // Get quote
    $quote = new AOS_Quotes();
    $quote->retrieve($id);
    // Case quote not exist
    if(!$quote->id) return;

    // Return
    $resData = [];
    $data = $quote->design_tool_json_c;
    if(is_null($data) || empty($data)){
        $resData['code'] = -1;
        $resData['content'] = [];
    } else {
        $resData['code'] = 0;
        $resData['content'] = utf8_encode(html_entity_decode($data));
    }
    echo json_encode($resData);