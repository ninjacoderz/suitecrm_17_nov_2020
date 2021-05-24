<?php
$module_name = 'pe_product_prices';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/pe_product_prices/customProductPrices.js',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'number',
            'label' => 'LBL_PRODUCT_PRICES_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'part_number',
            'label' => 'LBL_PART_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cost',
            'label' => 'LBL_COST',
          ),
          1 => 
          array (
            'name' => 'date_release',
            'comment' => 'Date Release',
            'label' => 'LBL_DATE_RELEASE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'account',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNT',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'pricing_source',
            'studio' => 'visible',
            'label' => 'LBL_PRICING_SOURCE',
          ),
          1 => 
          array (
            'name' => 'website',
            'comment' => 'URL of website for the Product',
            'label' => 'LBL_WEBSITE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
