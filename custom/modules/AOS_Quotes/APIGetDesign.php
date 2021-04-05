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
    echo html_entity_decode($quote->design_tool_json_c);