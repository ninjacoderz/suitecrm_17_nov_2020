<?php

//get resquest data
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$address_email = trim($_POST['address_email']);
$record_id = trim($_POST['record_id']);
$result_html = '';
if($first_name !== '' && $last_name !== '' && $address_email !== '') {
    global $db;
    $query = 
    "SELECT leads.id as id ,leads.number as number 
    FROM leads 
    INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = leads.id
    INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE leads.deleted = 0  AND leads.first_name = '$first_name' AND leads.last_name = '$last_name' AND email_addresses.email_address = '$address_email'";
    if($record_id !== ''){
        $query .=  " AND leads.id <> '$record_id'";
    }
    $result = $db->query($query);
    if($result->num_rows > 0) {
        while ($row = $db->fetchByAssoc($result)) {
            $result_html .= "<a target='_blank' href='/index.php?module=Leads&action=EditView&record=" .$row['id']  ."'>Number Lead Exist " .$row['number'] ."</a> <br>";
        }
    }else {
        $result_html ='Not Exist';
    }
}else {
    $result_html ='Not Exist';
}
echo $result_html;
die();

