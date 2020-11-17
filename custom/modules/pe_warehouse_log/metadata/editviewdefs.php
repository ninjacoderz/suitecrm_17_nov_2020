<?php
$module_name = 'pe_warehouse_log';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/pe_warehouse_log/CustomWarehouse_log.js',
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
        'LBL_DELIVER_TO' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_CARRIER_DETAILS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PURCHASEORDER_DETAILS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
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
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'shipping_product_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SHIPPING_PRODUCT_TYPE',
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
            'label' => 'LBL_INSTALLATION_PDF',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'file_rename_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_RENAME',
          ),
          1 => '',
        ),
      ),
      'LBL_DELIVER_TO' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pe_warehouse_log_pe_warehouse_name',
            'label' => 'Source Warehouse:',
          ),
        ),
        1 => 
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
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'sold_to_invoice',
            'studio' => 'visible',
            'label' => 'LBL_SOLD_TO_INVOICE',
          ),
        ),
        3 => 
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
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'destination_warehouse',
            'studio' => 'visible',
            'label' => 'LBL_DESTINATION_WAREHOUSE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'destination_warehouse_owner_c',
            'studio' => 'visible',
            'label' => 'LBL_DESTINATION_WAREHOUSE_OWNER',
          ),
        ),
        6 => 
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
        7 => 
        array (
          0 => 
          array (
            'name' => 'destination_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'destination',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_DESTINATION_ADDRESS_STREET',
          ),
        ),
        8 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_CARRIER_DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'carrier',
            'studio' => 'visible',
            'label' => 'LBL_CARRIER',
          ),
          1 => 
          array (
            'name' => 'connote',
            'studio' => 'visible',
            'label' => 'LBL_CONNOTE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'whlog_status',
            'studio' => 'visible',
            'label' => 'LBL_WHLOG_STATUS',
          ),
          1 => 
          array (
            'name' => 'status_c',
            'label' => 'LBL_STATUS',
          ),
        ),
        2 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'aupost_shipping_id',
            'comment' => 'Aupost Shipping ID',
            'label' => 'LBL_AUPOST_SHIPPING_ID',
          ),
        ),
      ),
      'LBL_PURCHASEORDER_DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pe_purchase_order_no_c',
            'label' => 'LBL_PE_PURCHASE_ORDER_NO_C',
          ),
          1 => 
          array (
            'name' => 'warehouse_order_number',
            'studio' => 'visible',
            'label' => 'LBL_WAREHOUSE_ORDER_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'po_purchase_order_pe_warehouse_log_1_name',
            'label' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
          ),
          1 => 
          array (
            'name' => 'delivery_docket_rep',
            'label' => 'LBL_DELIVER_DOCKET_REP',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'dispatch_ship_date_c',
            'label' => 'LBL_DISPATCH_SHIP_DATE_C',
          ),
          1 => 
          array (
            'name' => 'arrival_date_c',
            'label' => 'LBL_ARRIVAL_DATE_C',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'meeting_dispatch_date_c',
            'label' => 'LBL_MEETING_DISPATCH_DATE',
          ),
          1 => 
          array (
            'name' => 'meeting_arrival_date_c',
            'label' => 'LBL_MEETING_ARRIVAL_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'pe_warehouse_log_pe_warehouse_log_1_name',
            'label' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE',
          ),
          1 => '',
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
            'displayParams' => 
            array (
              'field' => 
              array (
                'onblur' => 'calculateTotal(\'lineItems\');',
              ),
            ),
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
    ),
  ),
);
;
?>
