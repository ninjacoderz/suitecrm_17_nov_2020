<?php

$module_name = "Leads"; //isset($_GET['module_name']) ? $_GET['module_name']: "";
$account_id = isset($_GET['account_id']) ? $_GET['account_id']: "";
$lead_id = isset($_GET['lead_id']) ? $_GET['lead_id']: "";

if($lead_id != ''){
    $bean = BeanFactory::getBean($module_name, $lead_id);
}else{
    if($account_id != ''){
        $sql = "SELECT id, first_name, last_name FROM leads WHERE account_id = '$account_id' AND deleted = 0";
        $db = DBManagerFactory::getInstance();
        $ret = $db->query($sql);
        while($row = $db->fetchByAssoc($ret)){
            $lead_id = $row['id'];
        }
        $bean = BeanFactory::getBean($module_name, $lead_id);
    }
}

if($bean->id){
    $return['id'] = $bean->id;
    $return['number'] = $bean->number;
    $return['email'] = $bean->email1;
    $return['first_name'] = isset($bean->first_name) ? html_entity_decode($bean->first_name, ENT_QUOTES) : "";
    $return['last_name'] = isset($bean->last_name) ? html_entity_decode($bean->last_name, ENT_QUOTES) : "";
    $return['phone_number'] = isset($bean->phone_mobile) ? html_entity_decode($bean->phone_mobile, ENT_QUOTES) : "";
    $return['lead_source'] = isset($bean->lead_source) ? html_entity_decode($bean->lead_source, ENT_QUOTES) : "";
    echo json_encode($return);
}else{
    $return['id'] = '';
    echo json_encode($return);
}
die();
