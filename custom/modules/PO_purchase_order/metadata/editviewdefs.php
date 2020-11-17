<?php
$module_name = 'PO_purchase_order';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
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
        'LBL_ADDRESS_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/PO_purchase_order/PurchaseOrder.js',
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
            'label' => 'LBL_QUOTE_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
          1 => 
          array (
            'name' => 'seek_install_time_c',
            'label' => 'LBL_SEEK_INSTALL_TIME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'po_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PO_TYPE',
          ),
          1 => 
          array (
            'name' => 'acceptance_date',
            'label' => 'LBL_ACCEPTANCE_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'install_date',
            'label' => 'LBL_INSTALL_DATE',
          ),
          1 => 
          array (
            'name' => 'aos_quotes_po_purchase_order_1_name',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'dispatch_date_c',
            'label' => 'LBL_DISPATCH_DATE',
          ),
          1 => 
          array (
            'name' => 'freight_company_c',
            'studio' => 'visible',
            'label' => 'LBL_FREIGHT_COMPANY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'delivery_date_c',
            'label' => 'LBL_DELIVERY_DATE',
          ),
          1 => 
          array (
            'name' => 'local_freight_company_c',
            'studio' => 'visible',
            'label' => 'LBL_LOCAL_FREIGHT_COMPANY',
          ),
        ),
        6 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'status_c',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'aos_invoices_po_purchase_order_1_name',
          ),
          1 => 
          array (
            'name' => 'bill_status_c',
            'studio' => 'visible',
            'label' => 'LBL_BILL_STATUS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'supplier_order_number_c',
            'label' => 'LBL_SUPPLIER_ORDER_NUMBER',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'supplier_order_c',
            'label' => 'LBL_SUPPLIER_ORDER',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_pdf_c',
            'label' => 'LBL_INSTALLATION_PDF_C',
          ),
          1 => '',
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'label' => 'LBL_BILLING_ACCOUNT',
            'displayParams' => 
            array (
              'key' => 
              array (
                0 => 'billing',
              ),
              'copy' => 
              array (
                0 => 'billing',
              ),
              'billingKey' => 'billing',
            ),
          ),
          1 => 
          array (
            'name' => 'receiver_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_RECEIVER_CONTACT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'shipping_account',
            'label' => 'LBL_BILLING_CONTACT',
            'displayParams' => 
            array (
              'key' => 
              array (
                0 => 'shipping',
              ),
              'copy' => 
              array (
                0 => 'shipping',
              ),
              'shippingKey' => 'shipping',
              'initial_filter' => '&account_name="+this.form.{$fields.billing_account.name}.value+"',
            ),
          ),
          1 => '',
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
              'copy' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_SHIPPING_ADDRESS_STREET',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'distance_to_travel',
            'label' => 'LBL_DISTANCE_TO_TRAVEL',
          ),
        ),
      ),
      'lbl_line_items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'line_items',
            'label' => 'LBL_LINE_ITEMS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'total_amt',
            'label' => 'LBL_TOTAL_AMT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'subtotal_amount',
            'label' => 'LBL_SUBTOTAL_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'shipping_amount',
            'label' => 'LBL_SHIPPING_AMOUNT',
            'displayParams' => 
            array (
              'field' => 
              array (
                'onblur' => 'calculateTotal(\'lineItems\');',
              ),
            ),
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'shipping_tax_amt',
            'label' => 'LBL_SHIPPING_TAX_AMT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'tax_amount',
            'label' => 'LBL_TAX_AMOUNT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'total_amount',
            'label' => 'LBL_GRAND_TOTAL',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'xero_po_id_c',
            'label' => 'LBL_XERO_PO_ID',
          ),
        ),
      ),
    ),
  ),
);
;
?>
