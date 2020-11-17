<?php
//tu_code
$warehouse_log_id = $_REQUEST['parent_id'];
if($warehouse_log_id !=''){
    $pe_warehouse_log = new pe_warehouse_log();
    $pe_warehouse_log ->retrieve($warehouse_log_id);
    if($pe_warehouse_log->id !== null){
        if($pe_warehouse_log->pe_warehouse_log_pe_warehousepe_warehouse_ida != null && $pe_warehouse_log->pe_warehouse_log_pe_warehousepe_warehouse_ida !=''){
            $result = array (
                'id' => $pe_warehouse_log->pe_warehouse_log_pe_warehousepe_warehouse_ida,
                'name' => $pe_warehouse_log->pe_warehouse_log_pe_warehouse_name,
            );
            echo json_encode($result);
            die();
        }else{
           echo "";
           die();
        }
    }else{
        echo "";
        die();
    }
}