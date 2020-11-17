<?php
$module_name = 'pe_service_case';
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
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL5' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/pe_service_case/pe_service_case.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/Leads/autosize.min.js',
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
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'number',
            'label' => 'LBL_SERVICE_CASE_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
          1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'leads_pe_service_case_1_name',
            'label' => 'LBL_LEADS_PE_SERVICE_CASE_1_FROM_LEADS_TITLE',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'label' => 'LBL_BILLING_ACCOUNT',
            'displayParams' => 
            array (
              'key' => 'billing',
              'copy' => 'billing',
              'billingKey' => 'billing',
            ),
          ),
          1 => 
          array (
            'name' => 'billing_contact',
            'label' => 'LBL_SHIPPING_CONTACT',
            'displayParams' => 
            array (
              'shippingKey' => 'shipping',
            ),
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'phone_number_c',
            'label' => 'LBL_PHONE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'email_service_case_c',
            'label' => 'LBL_EMAIL_SERVICE_CASE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_BILLING_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_SHIPPING_ADDRESS_STREET',
          ),
        ),
      ),
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'aos_invoices_pe_service_case_1_name',
            'label' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_AOS_INVOICES_TITLE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'invoice_billing_address_street',
            'comment' => 'The street address used for for billing address',
            'label' => 'LBL_INVOICE_BILLING_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'invoice_site_address_street',
            'comment' => 'The street address used for for billing address',
            'label' => 'LBL_INVOICE_SITE_ADDRESS_STREET',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'invoice_billing_address_city',
            'comment' => 'The city used for the invoice billing address',
            'label' => 'LBL_INVOICE_BILLING_ADDRESS_CITY',
          ),
          1 => 
          array (
            'name' => 'invoice_site_address_city',
            'comment' => 'The city used for the invoice site address',
            'label' => 'LBL_INVOICE_SITE_ADDRESS_CITY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'invoice_billing_address_state',
            'comment' => 'The state used for the invoice billing address',
            'label' => 'LBL_INVOICE_BILLING_ADDRESS_STATE',
          ),
          1 => 
          array (
            'name' => 'invoice_site_address_state',
            'comment' => 'The state used for the invoice site address',
            'label' => 'LBL_INVOICE_SITE_ADDRESS_STATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'invoice_billing_address_postalcode',
            'comment' => 'The zip code used for the invoice billing address',
            'label' => 'LBL_INVOICE_BILLING_ADDRESS_POSTALCODE',
          ),
          1 => 
          array (
            'name' => 'invoice_site_address_postalcode',
            'comment' => 'The zip code used for the invoice site address',
            'label' => 'LBL_INVOICE_SITE_ADDRESS_POSTALCODE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'invoice_billing_address_country',
            'comment' => 'The country used for the invoice billing address',
            'label' => 'LBL_INVOICE_BILLING_ADDRESS_COUNTRY',
          ),
          1 => 
          array (
            'name' => 'invoice_site_address_country',
            'comment' => 'The country used for the invoice site address',
            'label' => 'LBL_INVOICE_SITE_ADDRESS_COUNTRY',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'quote_type_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_TYPE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sanden_equipment_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SANDEN_EQUIPMENT_TYPE',
          ),
          1 => 
          array (
            'name' => 'is_error_code_sanden_c',
            'studio' => 'visible',
            'label' => 'LBL_IS_ERROR_CODE_SANDEN',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'error_content_c',
            'studio' => 'visible',
            'label' => 'LBL_ERROR_CONTENT',
          ),
          1 => 
          array (
            'name' => 'possible_solution_sanden_c',
            'studio' => 'visible',
            'label' => 'LBL_POSSIBLE_SOLUTION_SANDEN',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'manufacturer_diagnostic_c',
            'studio' => 'visible',
            'label' => 'LBL_MANUFACTURER_DIAGNOSTIC',
          ),
          1 => 
          array (
            'name' => 'manufacturer_judgement_c',
            'studio' => 'visible',
            'label' => 'LBL_MANUFACTURER_JUDGEMENT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'fault_type_c',
            'studio' => 'visible',
            'label' => 'LBL_FAULT_TYPE',
          ),
          1 => 
          array (
            'name' => 'fault_type_other_c',
            'studio' => 'visible',
            'label' => 'LBL_ FAULT_TYPE_OTHER',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'message_c',
            'studio' => 'visible',
            'label' => 'LBL_MESSAGE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'brief_description_c',
            'studio' => 'visible',
            'label' => 'LBL_BRIEF_DESCRIPTION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'detailed_desciption_c',
            'studio' => 'visible',
            'label' => 'LBL_DETAILED_DESCIPTION',
          ),
        ),
        8 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_photos_c',
            'label' => 'LBL_INSTALLATION_PHOTOS',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'id_message_servicecase_c',
            'label' => 'LBL_ID_MESSAGE_SERVICECASE',
          ),
          1 => 
          array (
            'name' => 'id_error_code_sanden_c',
            'label' => 'LBL_ID_ERROR_CODE_SANDEN',
          ),
        ),
      ),
    ),
  ),
);
;
?>
