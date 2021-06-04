<?php
$layout_defs["AOS_Quotes"]["subpanel_setup"]['subpanel_address'] = 
  array ('order' => 110,
    'module' => 'pe_address',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'function:get_address',
    'generate_select' => true,
    'title_key' => 'ADDRESS',
    'sort_order' => 'desc',
    'sort_by' => 'number',
    'top_buttons' => array(),
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/AOS_Quotes/customSubpanelFunction.php',
        'return_as_array' => 'true'
    ),
);