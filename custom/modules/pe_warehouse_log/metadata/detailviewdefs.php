<?php
$module_name = 'pe_warehouse_log';
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/pe_warehouse_log/CustomWarehouseLogDetailView.js',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_DELIVERY_DOCKET' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_CARRIER_DETAILS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_STOCK_ITEMS' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_ORDER_DETAIL' => 
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
          0 => 'date_entered',
          1 => 'date_modified',
        ),
        2 => 
        array (
          0 => 'description',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'shipping_product_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SHIPPING_PRODUCT_TYPE',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'po_purchase_order_pe_warehouse_log_1_name',
            'label' => 'LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE',
          ),
          1 => '',
        ),
      ),
      'LBL_DETAILVIEW_DELIVERY_DOCKET' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'destination_warehouse',
            'studio' => 'visible',
            'label' => 'LBL_DESTINATION_WAREHOUSE',
          ),
          1 => 
          array (
            'name' => 'pe_warehouse_log_pe_warehouse_name',
            'label' => 'LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE',
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
            'name' => 'billing_account',
            'studio' => 'visible',
            'label' => 'LBL_BILLING_ACCOUNT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'shipping_account',
            'studio' => 'visible',
            'label' => 'LBL_BILLING_CONTACT',
          ),
          1 => 
          array (
            'name' => 'sold_to_invoice',
            'studio' => 'visible',
            'label' => 'LBL_SOLD_TO_INVOICE',
          ),
        ),
      ),
      'LBL_DETAILVIEW_CARRIER_DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'carrier',
            'comment' => 'The city used for billing address',
            'label' => 'LBL_CARRIER',
          ),
          1 => 
          array (
            'name' => 'connote',
            'comment' => 'The city used for billing address',
            'label' => 'LBL_CONNOTE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status_c',
            'label' => 'LBL_STATUS',
          ),
        ),
      ),
      'LBL_DETAILVIEW_STOCK_ITEMS' => 
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
            'label' => 'LBL_DETAILVIEW_STOCK_ITEMS',
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
      'LBL_DETAILVIEW_ORDER_DETAIL' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'purchaseorder',
            'studio' => 'visible',
            'label' => 'LBL_PURCHASEORDER',
          ),
          1 => 
          array (
            'name' => 'warehouse_order_number',
            'comment' => 'The city used for billing address',
            'label' => 'LBL_CONNOTE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'estimate_ship_date',
            'label' => 'LBL_START_DATE',
          ),
          1 => 
          array (
            'name' => 'delivery_docket_rep',
            'comment' => 'The city used for billing address',
            'label' => 'LBL_DELIVERY_DOCKET_REP',
          ),
        ),
      ),
    ),
  ),
);
;
?>
