<?php 
    //custom get name template from id template
    if($_GET['action'] == 'get_name_template') {
      
        $db = DBManagerFactory::getInstance();
        $emails_email_templates_idb = $_GET['emails_email_templates_idb'];
        //email
        $sql = 'SELECT name,id FROM  email_templates WHERE id = "'.$emails_email_templates_idb .'" AND deleted != 1';
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            $array_result['emails_email_templates_name'] =  $row['name'];
        }
        //sms
        $emails_pe_smstemplate_idb = $_GET['emails_pe_smstemplate_idb'];
        $sql = 'SELECT name,id FROM pe_smstemplate WHERE id = "'.$emails_pe_smstemplate_idb .'" AND deleted != 1';
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            $array_result['emails_pe_smstemplate_name'] =  $row['name'];
        }
        
        $result_json = json_encode($array_result);
        echo $result_json;
        die();
    }

//Dung code --- autocomplete get template
$term = $_GET['term'];
$db = DBManagerFactory::getInstance();

$sql = 'SELECT name,id FROM  email_templates WHERE name LIKE "%'.$term .'%" AND deleted != 1';
$ret = $db->query($sql);

while ($row = $db->fetchByAssoc($ret)) {
    $array_result[] =  $row;
}

$result_json = json_encode($array_result);
echo $result_json;
//End Dung code