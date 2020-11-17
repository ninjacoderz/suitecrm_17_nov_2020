<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2018-07-19 08:35:42
$layout_defs["pe_warehouse"]["subpanel_setup"]['pe_warehouse_log_pe_warehouse'] = array (
  'order' => 100,
  'module' => 'pe_warehouse_log',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE',
  'get_subpanel_data' => 'pe_warehouse_log_pe_warehouse',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2018-12-05 18:20:56
$layout_defs["pe_warehouse"]["subpanel_setup"]['pe_warehouse_pe_stock_items_1'] = array (
  'order' => 100,
  'module' => 'pe_stock_items',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_1_FROM_PE_STOCK_ITEMS_TITLE',
  'get_subpanel_data' => 'pe_warehouse_pe_stock_items_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


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

// $layout_defs['pe_warehouse']['subpanel_setup']['subpanel_stock_item_custom'] =
//         array('order' => 101,
//             'module' => 'pe_stock_items',
//             'subpanel_name' => 'ForWareHouse',
//             'get_subpanel_data' => 'function:get_list_pe_stock_items',
//             'generate_select' => true,
//             'title_key' => 'LBL_PE_WAREHOUSE_PE_STOCK_ITEMS_TITLE',
//             'top_buttons' => array(),
//             'function_parameters' => array(
//                 'import_function_file' => 'custom/modules/pe_warehouse/custom_pe_warehouse_Subpanel.php',
//                 'pe_warehouse_id' => $this->_focus->id,
//                 'return_as_array' => 'true'
//             ),
// );
?>