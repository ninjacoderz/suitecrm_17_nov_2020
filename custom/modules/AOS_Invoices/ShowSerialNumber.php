<?php
$sanden_model = $_REQUEST['id'];
if($sanden_model != ''){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT serial_number FROM pe_stock_items si LEFT JOIN pe_stock_items_cstm stic ON stic.id_c = si.id
    WHERE 1=1
    AND (stic.aos_invoices_id_c IS NULL OR stic.aos_invoices_id_c = '')
    AND serial_number LIKE '%$sanden_model%'LIMIT 5";
    $ret = $db->query($sql);
    $result = array();
    while($row = $db->fetchByAssoc($ret)){
        $result[]= array(
        'serial_number'=>$row['serial_number'],
        );
    };
    echo json_encode($result);
}
?>