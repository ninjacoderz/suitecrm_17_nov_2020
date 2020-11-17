<?php 
//Dung code
$term = $_GET['term'];
$db = DBManagerFactory::getInstance();
global $current_user;
$sql = 'SELECT tmp.name,tmp.id,custmp.body_c FROM  pe_smstemplate as tmp INNER JOIN pe_smstemplate_cstm as custmp ON tmp.id = custmp.id_c WHERE tmp.name LIKE "%'.$term .'%" AND deleted != 1';
$ret = $db->query($sql);
$i = 0;
while ($row = $db->fetchByAssoc($ret)) {
    $array_result[] = $row;
    $array_result[$i]['body_c'] = strip_tags(trim(html_entity_decode($array_result[$i]['body_c'],ENT_QUOTES)));
    $array_result[$i]['sms_content'] = $array_result[$i]['body_c'];
    $array_result[$i]['sms_signture'] = $current_user->sms_signature_c;
    $i++;
}

$result_json = json_encode($array_result);
echo $result_json;
//End Dung code