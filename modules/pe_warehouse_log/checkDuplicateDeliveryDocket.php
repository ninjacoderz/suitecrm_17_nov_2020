<?php
    $db = DBManagerFactory::getInstance();
    if($_REQUEST['record'] != ''){
        $sql = "SELECT * FROM pe_warehouse_log WHERE name = '".$_REQUEST['name']."' AND id != '".$_REQUEST['record']."' AND deleted = 0";
    }else{
        $sql = "SELECT * FROM pe_warehouse_log WHERE name = '".$_REQUEST['name']."' AND deleted = 0";
    }
    $result = $db->query($sql);
    if($result->num_rows > 0){
        echo 'existed';
    }else{
        echo 'not exist';
    }
?>