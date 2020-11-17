<?php
    //get all list block file from  json file     
    $path_ListBlockFile = '';
    
    $path_ListBlockFile = dirname(__FILE__) .'/server/php/files/ListBlockFile.json';
    $json_ListBlockFile = json_decode(file_get_contents($path_ListBlockFile), true);

    // add name file to ListBlockFile.json
    if($_GET['is_block']  == 'true' && !in_array($_GET['file_name'],$json_ListBlockFile)) {
            array_push($json_ListBlockFile,$_GET['file_name']);
            //write new list block file to file json
            $json_ListBlockFile_new = json_encode($json_ListBlockFile);
            file_put_contents($path_ListBlockFile,$json_ListBlockFile_new);
            echo 'Block File';
    } 
    // add name file to ListBlockFile.json
    if($_GET['is_block'] == 'false' && in_array($_GET['file_name'],$json_ListBlockFile)) {
        if (($key = array_search($_GET['file_name'], $json_ListBlockFile)) !== false) {
            unset($json_ListBlockFile[$key]);
        }
        //write new list block file to file json
        $json_ListBlockFile_new = json_encode($json_ListBlockFile);
        file_put_contents($path_ListBlockFile,$json_ListBlockFile_new);
        echo 'Unblock File';
    } 

die();
