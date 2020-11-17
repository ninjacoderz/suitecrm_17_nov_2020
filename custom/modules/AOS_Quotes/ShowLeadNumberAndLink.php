<?php
$account_id = $_REQUEST['account_id'];
if($account_id != ''){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id,number FROM leads WHERE account_id ='$account_id'";
    $ret = $db->query($sql);
    while($row = $ret ->fetch_assoc()){
        $result= array(
        'number'=>$row['number'],
        'id'    =>$row['id'],
        );
    };
    echo json_encode($result);

}



