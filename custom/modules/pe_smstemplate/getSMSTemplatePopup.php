<?php
//VUT-- themes\SuiteP\js\sms_js.js
$db = DBManagerFactory::getInstance();
global $current_user;
$array_result = array();

$sql =  "   SELECT main.id, main.name, sub.body_c
            FROM pe_smstemplate as main
            INNER JOIN pe_smstemplate_cstm as sub
            WHERE main.id = sub.id_c AND main.deleted != 1
            ORDER BY main.name ASC;
        ";
$ret = $db->query($sql);
while ($row = $ret->fetch_assoc()) {
    $array_result[$row['id']]['name'] = $row['name'];
    $array_result[$row['id']]['body_c'] = strip_tags(trim(html_entity_decode($row['body_c'],ENT_QUOTES)));
    $array_result[$row['id']]['sms_content'] = $array_result[$row['id']]['body_c'];
    $array_result[$row['id']]['sms_signture'] = $current_user->sms_signature_c;
}    

$result_json = json_encode($array_result);
echo $result_json;
