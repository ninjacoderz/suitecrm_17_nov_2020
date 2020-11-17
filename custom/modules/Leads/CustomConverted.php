<?php
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];
if($module == 'AOS_Quotes'){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT aos_quotes.id,aos_quotes.name FROM aos_quotes INNER JOIN leads ON aos_quotes.billing_account_id = leads.account_id WHERE leads.id = '$record' AND leads.account_id != '' AND aos_quotes.deleted = 0";
    $ret = $db->query($sql);
    $result = array();
     while($row = $ret ->fetch_assoc()){
     $result[]= array(
         'id'=>$row['id'],
         'name'=>$row['name']
     );
 };
 echo json_encode($result);
 }elseif($module == 'AOS_Invoices'){
     $db = DBManagerFactory::getInstance();
     $sql = "SELECT aos_invoices.id,aos_invoices.name FROM aos_invoices INNER JOIN leads ON aos_invoices.billing_account_id = leads.account_id WHERE leads.id = '$record' AND leads.account_id != '' AND aos_invoices.deleted = 0";
     $ret = $db->query($sql);
     $result = array();
     while($row = $ret ->fetch_assoc()){
     $result[]= array(
         'id'=>$row['id'],
         'name'=>$row['name']
     );
 };
echo json_encode($result);
}


