<?php
ini_set('display_errors',1);
$id = $_REQUEST['InvoiceID'];
if(!isset($id) && $id == '') return;
$db = DBManagerFactory::getInstance();

$sql = "SELECT id, name FROM pe_warehouse_log WHERE sold_to_invoice_id = '". $id ."'";
$ret = $db->query($sql);
$html = '<div>';
if($ret->num_rows >0){
    while($row = $db->fetchByAssoc($ret)){
        if(!empty($row)){
            $html .= "<br><a target='_blank' href='/index.php?module=pe_warehouse_log&action=EditView&record=". $row['id'] ."'>". $row['name'] ."</a>" ;
        }
    }
}
$html .= '</div>';
echo $html;