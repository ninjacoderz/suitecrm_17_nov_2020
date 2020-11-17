<?php
$layout_defs['pe_warehouse']['subpanel_setup']['subpanel_destination_warehouse_log_custom'] =
    array('order' => 100,
        'module' => 'pe_warehouse_log',
        'subpanel_name' => 'ForWareHouse',
        'get_subpanel_data' => 'function:get_list_destination_whLog',
        'generate_select' => true,
        'title_key' => 'Destination Warehouse Log',
        'top_buttons' => array(),
        'function_parameters' => array(
            'import_function_file' => 'custom/modules/pe_warehouse/custom_pe_warehouse_destination_Subpanel.php',
            'pe_warehouse_id' => $this->_focus->id,
            'return_as_array' => 'true'
        ),
            
);