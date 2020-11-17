<?php 
function get_list_destination_whLog($params) {
    $args = func_get_args();
        $pe_warehouse_id = $args[0]['pe_warehouse_id'];
        $return_array['select'] = 'SELECT *';
        $return_array['from'] = " FROM pe_warehouse_log";
        $return_array['where'] = "WHERE pe_warehouse_log.deleted = '0' AND pe_warehouse_log.destination_warehouse_id = '$pe_warehouse_id'";
        $return_array['join'] = "";
        $return_array['join_tables'] = '';
        return $return_array;

}