<?php
$number_quote =(int)str_replace(',','',$_GET['number']);
if($number_quote !=''){
    $db = DBManagerFactory::getInstance();
    $query = "SELECT id , number FROM aos_quotes 
    WHERE deleted = 0 AND number = '$number_quote' LIMIT 1";
    $result = $db->query($query);
    while (($row=$db->fetchByAssoc($result)) != null) {
        $data_return = $row;
    }

    if($data_return['id'] == ''){
        $data_return = 'NotData';
    }
}else{
    $data_return = 'NotData';
}

echo json_encode($data_return);die();