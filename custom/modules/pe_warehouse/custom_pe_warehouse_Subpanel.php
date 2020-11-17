<?php 
// function get_list_pe_stock_items($params) {
//     $args = func_get_args();
//         $pe_warehouse_id = $args[0]['pe_warehouse_id'];
//         $return_array['select'] = ' SELECT pe_warehouse.name as pe_warehouse_name ';
//         $return_array['from'] = " FROM pe_stock_items";
//         $return_array['where'] = "WHERE pe_warehouse.deleted = '0' AND pe_warehouse.id = '$pe_warehouse_id'";
//         $return_array['join'] = " JOIN pe_warehouse_log ON pe_warehouse_log.id = pe_stock_items.parent_id 
//         JOIN pe_warehouse_log_pe_warehouse_c as table_re_whlog_wh ON pe_warehouse_log.id = table_re_whlog_wh.pe_warehouse_log_pe_warehousepe_warehouse_log_idb 
//         JOIN pe_warehouse  ON pe_warehouse.id = table_re_whlog_wh.pe_warehouse_log_pe_warehousepe_warehouse_ida AND pe_warehouse.id = '$pe_warehouse_id'";
//         $return_array['join_tables'] = '';
//         return $return_array;

// }