<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2017 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

$dictionary['pe_warehouse_log'] = array(
    'table' => 'pe_warehouse_log',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
    	'billing_account_id' =>
            array(
                'required' => false,
                'name' => 'billing_account_id',
                'vname' => '',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => 0,
                'reportable' => 0,
                'len' => 36,
            ),
        'billing_account' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'billing_account',
                'vname' => 'LBL_BILLING_ACCOUNT',
                'type' => 'relate',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '255',
                'id_name' => 'billing_account_id',
                'ext2' => 'Accounts',
                'module' => 'Accounts',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        'shipping_account_id' =>
            array(
                'required' => false,
                'name' => 'shipping_account_id',
                'vname' => '',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => 0,
                'reportable' => 0,
                'len' => 36,
            ),
        'shipping_account' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'shipping_account',
                'vname' => 'LBL_BILLING_CONTACT',
                'type' => 'relate',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '255',
                'id_name' => 'shipping_account_id',
                'ext2' => 'Accounts',
                'module' => 'Accounts',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),

        'billing_address_street' =>
            array(
                'name' => 'billing_address_street',
                'vname' => 'LBL_BILLING_ADDRESS_STREET',
                'type' => 'varchar',
                'len' => '150',
                'comment' => 'The street address used for billing address',
                'group' => 'billing_address',
                'merge_filter' => 'enabled',
            ),
        'billing_address_city' =>
            array(
                'name' => 'billing_address_city',
                'vname' => 'LBL_BILLING_ADDRESS_CITY',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'The city used for billing address',
                'group' => 'billing_address',
                'merge_filter' => 'enabled',
            ),
        'billing_address_state' =>
            array(
                'name' => 'billing_address_state',
                'vname' => 'LBL_BILLING_ADDRESS_STATE',
                'type' => 'varchar',
                'len' => '100',
                'group' => 'billing_address',
                'comment' => 'The state used for billing address',
                'merge_filter' => 'enabled',
            ),
        'billing_address_postalcode' =>
            array(
                'name' => 'billing_address_postalcode',
                'vname' => 'LBL_BILLING_ADDRESS_POSTALCODE',
                'type' => 'varchar',
                'len' => '20',
                'group' => 'billing_address',
                'comment' => 'The postal code used for billing address',
                'merge_filter' => 'enabled',

            ),
        'billing_address_country' =>
            array(
                'name' => 'billing_address_country',
                'vname' => 'LBL_BILLING_ADDRESS_COUNTRY',
                'type' => 'varchar',
                'group' => 'billing_address',
                'comment' => 'The country used for the billing address',
                'merge_filter' => 'enabled',
            ),

        'shipping_address_street' =>
            array(
                'name' => 'shipping_address_street',
                'vname' => 'LBL_SHIPPING_ADDRESS_STREET',
                'type' => 'varchar',
                'len' => 150,
                'group' => 'shipping_address',
                'comment' => 'The street address used for for shipping purposes',
                'merge_filter' => 'enabled',
            ),
        'shipping_address_city' =>
            array(
                'name' => 'shipping_address_city',
                'vname' => 'LBL_SHIPPING_ADDRESS_CITY',
                'type' => 'varchar',
                'len' => 100,
                'group' => 'shipping_address',
                'comment' => 'The city used for the shipping address',
                'merge_filter' => 'enabled',
            ),
        'shipping_address_state' =>
            array(
                'name' => 'shipping_address_state',
                'vname' => 'LBL_SHIPPING_ADDRESS_STATE',
                'type' => 'varchar',
                'len' => 100,
                'group' => 'shipping_address',
                'comment' => 'The state used for the shipping address',
                'merge_filter' => 'enabled',
            ),
        'shipping_address_postalcode' =>
            array(
                'name' => 'shipping_address_postalcode',
                'vname' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
                'type' => 'varchar',
                'len' => 20,
                'group' => 'shipping_address',
                'comment' => 'The zip code used for the shipping address',
                'merge_filter' => 'enabled',
            ),
        'shipping_address_country' =>
            array(
                'name' => 'shipping_address_country',
                'vname' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
                'type' => 'varchar',
                'group' => 'shipping_address',
                'comment' => 'The country used for the shipping address',
                'merge_filter' => 'enabled',
            ),
        //dung code - address Destination LBL_DESTINATION_ADDRESS_STREET
        'destination_address_street' =>
            array(
                'name' => 'destination_address_street',
                'vname' => 'LBL_DESTINATION_ADDRESS_STREET',
                'type' => 'varchar',
                'len' => 150,
                'group' => 'destination_address',
                'comment' => 'The street address used for for destination purposes',
                'merge_filter' => 'enabled',
            ),
        'destination_address_city' =>
            array(
                'name' => 'destination_address_city',
                'vname' => 'LBL_DESTINATION_ADDRESS_CITY',
                'type' => 'varchar',
                'len' => 100,
                'group' => 'destination_address',
                'comment' => 'The city used for the destination address',
                'merge_filter' => 'enabled',
            ),
        'destination_address_state' =>
            array(
                'name' => 'destination_address_state',
                'vname' => 'LBL_DESTINATION_ADDRESS_STATE',
                'type' => 'varchar',
                'len' => 100,
                'group' => 'destination_address',
                'comment' => 'The state used for the destination address',
                'merge_filter' => 'enabled',
            ),
        'destination_address_postalcode' =>
            array(
                'name' => 'destination_address_postalcode',
                'vname' => 'LBL_DESTINATION_ADDRESS_POSTALCODE',
                'type' => 'varchar',
                'len' => 20,
                'group' => 'destination_address',
                'comment' => 'The zip code used for the destination address',
                'merge_filter' => 'enabled',
            ),
        'destination_address_country' =>
            array(
                'name' => 'destination_address_country',
                'vname' => 'LBL_DESTINATION_ADDRESS_COUNTRY',
                'type' => 'varchar',
                'group' => 'destination_address',
                'comment' => 'The country used for the destination address',
                'merge_filter' => 'enabled',
            ),

        'currency_id' =>
            array(
                'required' => false,
                'name' => 'currency_id',
                'vname' => 'LBL_CURRENCY',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'len' => 36,
                'size' => '20',
                'studio' => 'visible',
                'function' =>
                    array(
                        'name' => 'getCurrencyDropDown',
                        'returns' => 'html',
                    ),
            ),
        // thienpb code -- add field number
        'number' =>
            array(
                'required' => true,
                'name' => 'number',
                'vname' => 'LBL_WHLog_NUMBER',
                'type' => 'int',
                'len' => 11,
                'isnull' => 'false',
                'unified_search' => true,
                'comments' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'reportable' => true,
                'disable_num_format' => true,
            ),
        'line_items' =>
            array(
                'required' => false,
                'name' => 'line_items',
                'vname' => 'LBL_LINE_ITEMS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'inline_edit' => false,
                'function' =>
                    array(
                        'name' => 'display_lines_stock_items',
                        'returns' => 'html',
                        'include' => 'modules/pe_stock_items/Stock_Items.php'
                    ),
            ),

        'total_amt' =>
            array(
                'required' => false,
                'name' => 'total_amt',
                'vname' => 'LBL_TOTAL_AMT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '26,6',
            ),
        'total_amt_usdollar' =>
            array(
                'name' => 'total_amt_usdollar',
                'vname' => 'LBL_TOTAL_AMT_USDOLLAR',
                'type' => 'currency',
                'group' => 'total_amt',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'subtotal_amount' =>
            array(
                'required' => false,
                'name' => 'subtotal_amount',
                'vname' => 'LBL_SUBTOTAL_AMOUNT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '26,6',
            ),
        'subtotal_amount_usdollar' =>
            array(
                'name' => 'subtotal_amount_usdollar',
                'vname' => 'LBL_SUBTOTAL_AMOUNT_USDOLLAR',
                'type' => 'currency',
                'group' => 'subtotal_amount',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'discount_amount' =>
            array(
                'required' => false,
                'name' => 'discount_amount',
                'vname' => 'LBL_DISCOUNT_AMOUNT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '26,6',
            ),
        'discount_amount_usdollar' =>
            array(
                'name' => 'discount_amount_usdollar',
                'vname' => 'LBL_DISCOUNT_AMOUNT_USDOLLAR',
                'type' => 'currency',
                'group' => 'discount_amount',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'tax_amount' =>
            array(
                'required' => false,
                'name' => 'tax_amount',
                'vname' => 'LBL_TAX_AMOUNT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '26,6',
            ),
        'tax_amount_usdollar' =>
            array(
                'name' => 'tax_amount_usdollar',
                'vname' => 'LBL_TAX_AMOUNT_USDOLLAR',
                'type' => 'currency',
                'group' => 'tax_amount',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'shipping_amount' =>
            array(
                'required' => false,
                'name' => 'shipping_amount',
                'vname' => 'LBL_SHIPPING_AMOUNT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'len' => '26,6',
                'size' => '10',
                'enable_range_search' => false,
            ),
        'shipping_amount_usdollar' =>
            array(
                'name' => 'shipping_amount_usdollar',
                'vname' => 'LBL_SHIPPING_AMOUNT_USDOLLAR',
                'type' => 'currency',
                'group' => 'shipping_amount',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'shipping_tax' =>
            array(
                'required' => false,
                'name' => 'shipping_tax',
                'vname' => 'LBL_SHIPPING_TAX',
                'type' => 'enum',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'len' => 100,
                'options' => 'vat_list',
                'studio' => 'visible',
            ),
        'shipping_tax_amt' =>
            array(
                'required' => false,
                'name' => 'shipping_tax_amt',
                'vname' => 'LBL_SHIPPING_TAX_AMT',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'len' => '26,6',
                'size' => '10',
                'enable_range_search' => false,
                'function' =>
                    array(
                        'name' => 'display_shipping_vat_stock_items',
                        'returns' => 'html',
                        'include' => 'modules/pe_stock_items/Stock_Items.php'
                    ),
            ),
        'shipping_tax_amt_usdollar' =>
            array(
                'name' => 'shipping_tax_amt_usdollar',
                'vname' => 'LBL_SHIPPING_TAX_AMT_USDOLLAR',
                'type' => 'currency',
                'group' => 'shipping_tax_amt',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'total_amount' =>
            array(
                'required' => false,
                'name' => 'total_amount',
                'vname' => 'LBL_GRAND_TOTAL',
                'type' => 'currency',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'len' => '26,6',
                'enable_range_search' => true,
                'options' => 'numeric_range_search_dom',
            ),
        'total_amount_usdollar' =>
            array(
                'name' => 'total_amount_usdollar',
                'vname' => 'LBL_GRAND_TOTAL_USDOLLAR',
                'type' => 'currency',
                'group' => 'total_amount',
                'disable_num_format' => true,
                'duplicate_merge' => '0',
                'audited' => true,
                'comment' => '',
                'studio' => array(
                    'editview' => false,
                    'detailview' => false,
                    'quickcreate' => false,
                ),
                'len' => '26,6',
            ),
        'currency_id' =>
            array(
                'required' => false,
                'name' => 'currency_id',
                'vname' => 'LBL_CURRENCY',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'len' => 36,
                'size' => '20',
                'studio' => 'visible',
                'function' =>
                    array(
                        'name' => 'getCurrencyDropDown',
                        'returns' => 'html',
                    ),
            ),

        'carrier' =>
            array(
                'name' => 'carrier', 
                /*'type' => 'enum',
      'options' => 'account_type_dom',*/
                'vname' => 'LBL_CARRIER',
                //'type' => 'varchar',
                'type' => 'enum',
                'options' => 'carrier',
                'len' => '100',
                'comment' => 'The city used for billing address',
                'merge_filter' => 'enabled',
            ),
        'connote' =>
            array(
                'name' => 'connote',
                'vname' => 'LBL_CONNOTE',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'The city used for billing address',
                'merge_filter' => 'enabled',
            ),
        'aupost_shipping_id' =>
            array(
                'name' => 'aupost_shipping_id',
                'vname' => 'LBL_AUPOST_SHIPPING_ID',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'Aupost Shipping ID',
                'merge_filter' => 'enabled',
            ),
        'purchaseorder_id' =>
            array(
                'required' => false,
                'name' => 'purchaseorder_id',
                'vname' => '',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => 0,
                'reportable' => 0,
                'len' => 36,
            ),
        'purchaseorder' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'purchaseorder',
                'vname' => 'LBL_PURCHASEORDER',
                'type' => 'relate',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '255',
                'id_name' => 'purchaseorder_id',
                'ext2' => 'PO_purchase_order',
                'module' => 'PO_purchase_order',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        
        'whlog_status' =>
            array(
                'required' => false,
                'name' => 'whlog_status',
                'vname' => 'LBL_WHLOG_STATUS',
                'type' => 'enum',
                'massupdate' => 0,
                'default' => 'Unallocated',
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => 100,
                'options' => 'whlog_status_dom',
                'studio' => 'visible',
            ),
        
        'destination_warehouse_id' =>
        array(
            'required' => false,
            'name' => 'destination_warehouse_id',
            'vname' => '',
            'type' => 'id',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => 0,
            'reportable' => 0,
            'len' => 36,
        ),
            'destination_warehouse' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'destination_warehouse',
                'vname' => 'LBL_DESTINATION_WAREHOUSE',
                'type' => 'relate',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '255',
                'id_name' => 'destination_warehouse_id',
                'ext2' => 'pe_warehouse',
                'module' => 'pe_warehouse',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),

            'sold_to_invoice_id' =>
            array(
                'required' => false,
                'name' => 'sold_to_invoice_id',
                'vname' => '',
                'type' => 'id',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => 0,
                'reportable' => 0,
                'len' => 36,
            ),
                'sold_to_invoice' =>
                array(
                    'required' => false,
                    'source' => 'non-db',
                    'name' => 'sold_to_invoice',
                    'vname' => 'LBL_SOLD_TO_INVOICE',
                    'type' => 'relate',
                    'massupdate' => 0,
                    'comments' => '',
                    'help' => '',
                    'importable' => 'true',
                    'duplicate_merge' => 'disabled',
                    'duplicate_merge_dom_value' => '0',
                    'audited' => 1,
                    'reportable' => true,
                    'len' => '255',
                    'id_name' => 'sold_to_invoice_id',
                    'ext2' => 'AOS_Invoices',
                    'module' => 'AOS_Invoices',
                    'quicksearch' => 'enabled',
                    'studio' => 'visible',
                ),
    
        'warehouse_order_number' =>
            array(
                'name' => 'warehouse_order_number',
                'vname' => 'LBL_CONNOTE',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'The city used for billing address',
                'merge_filter' => 'enabled',
            ),

        'delivery_docket_rep' =>
            array(
                'name' => 'delivery_docket_rep',
                'vname' => 'LBL_DELIVERY_DOCKET_REP',
                'type' => 'varchar',
                'len' => '100',
                'comment' => 'The city used for billing address',
                'merge_filter' => 'enabled',
            ),

        'actual_ship_date' =>
            array(
                'required' => false,
                  'name' => 'actual_ship_date',
                  'vname' => 'LBL_START_DATE',
                  'type' => 'date',
                  'massupdate' => 0,
                  'comments' => '',
                  'help' => '',
                  'importable' => 'true',
                  'duplicate_merge' => 'disabled',
                  'duplicate_merge_dom_value' => '0',
                  'audited' => false,
                  'reportable' => true,
                  'size' => '20',
                  'enable_range_search' => true,
                  'options' => 'date_range_search_dom',
                  'display_default' => 'now',
                ),
        'estimate_ship_date' =>
            array(
                'required' => false,
                  'name' => 'estimate_ship_date',
                  'vname' => 'LBL_START_DATE',
                  'type' => 'date',
                  'massupdate' => 0,
                  'comments' => '',
                  'help' => '',
                  'importable' => 'true',
                  'duplicate_merge' => 'disabled',
                  'duplicate_merge_dom_value' => '0',
                  'audited' => false,
                  'reportable' => true,
                  'size' => '20',
                  'enable_range_search' => true,
                  'options' => 'date_range_search_dom',
                  'display_default' => 'now',
                ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('pe_warehouse_log', 'pe_warehouse_log', array('basic','assignable','security_groups'));