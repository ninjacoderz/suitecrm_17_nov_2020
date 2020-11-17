<?php

    $comment_data = $_REQUEST['comment_text'];
    $type_data = $_REQUEST['type'];
    $commnet_index = $_REQUEST['index'];

    //get
    $path_commentListFile = dirname(__FILE__) .'/commmentList.json';
    $get_data = json_decode(file_get_contents($path_commentListFile),true);

    //set
    if( !in_array($comment_data,$get_data) && $type_data == 'push'){
        if(isset($commnet_index)){
            $get_data[$commnet_index] = $comment_data;
        }else{
            array_push($get_data,$comment_data);
        }
       
    }else if($type_data == 'delete'){
        $key = array_search($comment_data, $get_data);
        if (false !== $key) {
            unset($get_data[$key]);
        }
        array_multisort($get_data, SORT_ASC);
    }
    $data = json_encode($get_data);
    file_put_contents($path_commentListFile,$data);
    echo json_encode($get_data);
    die;
