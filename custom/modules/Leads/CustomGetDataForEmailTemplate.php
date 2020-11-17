<?php

$relate_to = $_GET['relate_to'];
$parent_id = $_GET['parent_id'];
$email_templates_id = $_GET['emails_email_templates_idb'];
$error_getdata = "It don't has data !!!";
function check_return_data($result_array) {
    if(isset($result_array)){
        echo json_encode($result_array); 
    }else {
        echo $error_getdata;
    }
};
if( $email_templates_id == '5ad80115-b756-ea3e-ca83-5abb005602bf' || $email_templates_id == '58230a56-82cd-03ae-1d60-59eec0f8582d' || $email_templates_id =="7c189f2f-19a9-c2c1-23fa-59f922602067" || $email_templates_id == "8d9e9b2c-e05f-deda-c83a-59f97f10d06a"){
    switch ($relate_to) {
        case 'Leads':
            $focus = BeanFactory::getBean($relate_to, $parent_id);
            $result_array = array();
            $result_array['lead_first_name'] = $focus->first_name;
            $result_array['lead_primary_address_city'] = $focus->primary_address_city;
            check_return_data($result_array);
            break;
        case 'Accounts':
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT * FROM leads WHERE account_id ='" .$parent_id ."'";
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                $result[] = $row;
            } 
            $result_array = array();
            $result_array['lead_first_name'] = $result[0]['first_name'];
            $result_array['lead_primary_address_city'] = $result[0]['primary_address_city'];
            check_return_data($result_array);
            break;
        default:
            echo $error_getdata;
            break;
    }  
}
else {
    echo "Template is wrong !!!";
}
