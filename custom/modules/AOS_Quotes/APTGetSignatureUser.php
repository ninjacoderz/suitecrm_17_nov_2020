<?php
// Enable cross domain call
header('Access-Control-Allow-Origin: *');

$json_data = [];

//get all SMS signture from other users
$db = DBManagerFactory::getInstance();
$query =  "SELECT users.id as id, users.first_name  as first_name ,users.last_name   as last_name  , users_cstm.sms_signature_c as sms_signature_c
FROM users
INNER JOIN users_cstm ON users_cstm.id_c = users.id
WHERE ( users_cstm.sms_signature_c IS NOT NULL AND  users_cstm.sms_signature_c != '' ) AND users.deleted = 0 AND users.status = 'Active'";
$result = $db->query($query);
while (($row=$db->fetchByAssoc($result)) != null) {
    $json_data[$row['id']]  = array (
      'first_name' => $row['first_name'],
      'last_name' => $row['last_name']  ,
      'sms_signature' => $row['sms_signature_c'] 
   ); 
}

echo json_encode($json_data);
