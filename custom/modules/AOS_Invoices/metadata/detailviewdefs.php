<?php
$module_name = 'AOS_Invoices';
$_object_name = 'aos_invoices';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          4 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'pdf\');" value="{$MOD.LBL_PRINT_AS_PDF}">',
          ),
          5 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'emailpdf\');" value="{$MOD.LBL_EMAIL_PDF}">',
          ),
          6 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'email\');" value="{$MOD.LBL_EMAIL_INVOICE}">',
          ),
        ),
      ),
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CUSTOM_PANEL_ALL_VIEW' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_INVOICE_TO' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'collapsed',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/AOS_Invoices/CustomInvoicesDetailView.js',
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
    ),
    'panels' => 
    array (
      'LBL_CUSTOM_PANEL_ALL_VIEW' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'number',
            'label' => 'LBL_INVOICE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'invoice_date',
            'label' => 'LBL_INVOICE_DATE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'installation_date_c',
            'label' => 'LBL_INSTALLATION_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'quote_type_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_TYPE',
          ),
          1 => 
          array (
            'name' => 'due_date',
            'label' => 'LBL_DUE_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'quote_date',
            'label' => 'LBL_QUOTE_DATE',
          ),
        ),
        4 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'next_action_date_c',
            'label' => 'LBL_NEXT_ACTION_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'custom_detail_in_detail_view',
            'label' => 'custom_detail_in_detail_view',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'order_number_c',
            'label' => 'LBL_ORDER_NUMBER_C',
          ),
          1 => 
          array (
            'name' => 'stc_aggregator_serial_c',
            'label' => 'LBL_STC_AGGREGATOR_SERIAL',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'total_revenue_c',
            'label' => 'LBL_TOTAL_REVENUE_C',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'quote_number',
            'label' => 'LBL_QUOTE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'total_cost_c',
            'label' => 'LBL_TOTAL_COST_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'registered_for_gst_c',
            'studio' => 'visible',
            'label' => 'LBL_REGISTERED_FOR_GST',
          ),
          1 => 
          array (
            'name' => 'profit_c',
            'label' => 'LBL_PROFIT',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'gross_profit_percent_c',
            'label' => 'LBL_GROSS_PROFIT_PERCENT_C',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'invoice_note_c',
            'studio' => 'visible',
            'label' => 'LBL_INVOICE_NOTE',
          ),
          1 => 
          array (
            'name' => 'customer_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOMER_NOTES',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'sanden_total_costs_c',
            'label' => 'LBL_SANDEN_TOTAL_COSTS_C',
          ),
          1 => 
          array (
            'name' => 'sanden_total_revenue_c',
            'label' => 'LBL_SANDEN_TOTAL_REVENUE_C',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'sanden_gross_profit_c',
            'label' => 'LBL_SANDEN_GROSS_PROFIT_C',
          ),
          1 => 
          array (
            'name' => 'sanden_gprofit_percent_c',
            'label' => 'LBL_SANDEN_GPROFIT_PERCENT_C',
          ),
        ),
      ),
      'LBL_INVOICE_TO' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'label' => 'LBL_BILLING_ACCOUNT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'billing_contact',
            'label' => 'LBL_BILLING_CONTACT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'label' => 'LBL_BILLING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
            ),
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'label' => 'LBL_SHIPPING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
            ),
          ),
        ),
      ),
      'lbl_line_items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'line_items',
            'label' => 'LBL_LINE_ITEMS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'total_amt',
            'label' => 'LBL_TOTAL_AMT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'subtotal_amount',
            'label' => 'LBL_SUBTOTAL_AMOUNT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'shipping_amount',
            'label' => 'LBL_SHIPPING_AMOUNT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'shipping_tax_amt',
            'label' => 'LBL_SHIPPING_TAX_AMT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'tax_amount',
            'label' => 'LBL_TAX_AMOUNT',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'total_amount',
            'label' => 'LBL_GRAND_TOTAL',
          ),
        ),
      ),
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'data_checklist_invoice_c',
            'studio' => 'visible',
            'label' => 'LBL_DATA_CHECKLIST_INVOICE',
          ),
          1 => '',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
      ),
    ),
  ),
);
;
?>
