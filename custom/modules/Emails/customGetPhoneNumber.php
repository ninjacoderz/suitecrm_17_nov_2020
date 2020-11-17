<?php
$term = $_REQUEST['term'];
$db = DBManagerFactory::getInstance();
$result = [];
$i = 0;
$sql = "SELECT DISTINCT id , phone_mobile ,last_name ,first_name
FROM leads
WHERE ( phone_mobile like '%$term%' 
OR last_name like '%$term%' 
OR first_name like '%$term%')
AND deleted = 0 ";
$ret = $db->query($sql);
while($row = $ret->fetch_assoc()){
    $array_data = array(
        'name' => $row['last_name'] .' ' .$row['first_name'] ."|". $row['phone_mobile'],
        'id' => $row['id'],
        'phone' => $row['phone_mobile']
    );
    $result[] = $array_data;
}

echo json_encode($result);
