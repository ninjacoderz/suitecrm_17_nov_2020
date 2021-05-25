<?php
$layout_defs["AOS_Products"]["subpanel_setup"]['subpanel_product_prices'] = 
  array ('order' => 102,
    'module' => 'pe_product_prices',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_product_prices',
    'generate_select' => true,
    'title_key' => 'PRODUCT PRICE',
    'sort_order' => 'desc',
    'sort_by' => 'number',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'modules/AOS_Products/customSubpanelFunction.php',
        'product_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
);