<?php
$record_id = isset($_GET['account_id']) ? $_GET['account_id']: "";
$bean = BeanFactory::getBean("Accounts", $record_id);
$contacts = $bean->get_linked_beans('contacts','Contact');
$result = [];
foreach ($contacts as $key => $value) {
    if($value->id != ''){
        $result[] =  array (
           'id' => $value->id,
           'name' => $value->name,
        );
    }
}

 echo json_encode($result);