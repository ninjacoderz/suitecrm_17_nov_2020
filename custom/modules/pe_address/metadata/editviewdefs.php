<?php
$module_name = 'pe_address';
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
          'file' => 'custom/modules/pe_address/customAddress.js',
        ),
        1 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/html2canvas.js',
        ),
        2 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/canvas2image.js',
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
        'LBL_EDITVIEW_PANEL1' => 
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
          0 => 'name',
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'studio' => 'visible',
            'label' => 'LBL_BILLING_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'billing_contact',
            'studio' => 'visible',
            'label' => 'LBL_BILLING_CONTACT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'comment' => 'The street address used for billing address',
            'label' => 'LBL_BILLING_ADDRESS_STREET',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_city',
            'comment' => 'The city used for billing address',
            'label' => 'LBL_BILLING_ADDRESS_CITY',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_state',
            'comment' => 'The state used for billing address',
            'label' => 'LBL_BILLING_ADDRESS_STATE',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_postalcode',
            'comment' => 'The postal code used for billing address',
            'label' => 'LBL_BILLING_ADDRESS_POSTALCODE',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_country',
            'comment' => 'The country used for the billing address',
            'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'electricity_distributor',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICTY_DISTRIBUTOR',
          ),
          1 => 
          array (
            'name' => 'electricity_retailer',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICTY_RETAILER',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'nmi',
            'label' => 'LBL_NMI',
          ),
          1 => 
          array (
            'name' => 'address_nmi',
            'label' => 'LBL_ADDRESS_NMI',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'billing_meter_number',
            'label' => 'LBL_BILLING_METER_NUMBER',
          ),
          1 => 
          array (
            'name' => 'grid_export_limit',
            'label' => 'LBL_APPROVED_GRID_EXPORT_CAPACITY',
          ),
        ),
        11 => 
        array (
          0 => '',
          1 => '',
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'satellite_view',
            'studio' => true,
            'label' => 'LBL_SATELLITE_VIEW',
          ),
          1 => 
          array (
            'name' => 'street_view',
            'studio' => true,
            'label' => 'LBL_STREET_VIEW',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_pictures_c',
            'label' => 'LBL_INSTALLATION_PICTURES',
          ),
        ),
        1 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
