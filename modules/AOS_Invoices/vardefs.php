<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
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
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


$dictionary['AOS_Invoices'] = array(
    'table' => 'aos_invoices',
    'audited' => true,
    'fields' => array(
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
                'reportable' => 1,
                'len' => '255',
                'id_name' => 'billing_account_id',
                'ext2' => 'Accounts',
                'module' => 'Accounts',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        'billing_contact_id' =>
            array(
                'required' => false,
                'name' => 'billing_contact_id',
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
        'billing_contact' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'billing_contact',
                'vname' => 'LBL_BILLING_CONTACT',
                'type' => 'relate',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => 1,
                'len' => '255',
                'id_name' => 'billing_contact_id',
                'ext2' => 'Contacts',
                'module' => 'Contacts',
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

        'number' =>
            array(
                'required' => true,
                'name' => 'number',
                'vname' => 'LBL_INVOICE_NUMBER',
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
                        'name' => 'display_lines',
                        'returns' => 'html',
                        'include' => 'modules/AOS_Products_Quotes/Line_Items.php'
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
                'comment' => 'Formatted amount of the opportunity',
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
                        'name' => 'display_shipping_vat',
                        'returns' => 'html',
                        'include' => 'modules/AOS_Products_Quotes/Line_Items.php'
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
                        'onListView' => true,
                    ),
            ),
        'quote_number' =>
            array(
                'required' => false,
                'name' => 'quote_number',
                'vname' => 'LBL_QUOTE_NUMBER',
                'type' => 'int',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 1,
                'reportable' => true,
                'len' => '11',
                'disable_num_format' => '',
            ),
        'quote_date' =>
            array(
                'required' => false,
                'name' => 'quote_date',
                'vname' => 'LBL_QUOTE_DATE',
                'type' => 'date',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'display_default' => 'now',
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            ),
        'invoice_date' =>
            array(
                'required' => false,
                'name' => 'invoice_date',
                'vname' => 'LBL_INVOICE_DATE',
                'type' => 'date',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'display_default' => 'now',
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            ),
        'due_date' =>
            array(
                'required' => false,
                'name' => 'due_date',
                'vname' => 'LBL_DUE_DATE',
                'type' => 'date',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => 0,
                'reportable' => true,
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            ),
        'status' =>
            array(
                'required' => false,
                'name' => 'status',
                'vname' => 'LBL_STATUS',
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
                'options' => 'invoice_status_dom',
                'studio' => 'visible',
            ),
        'template_ddown_c' =>
            array(
                'required' => '0',
                'name' => 'template_ddown_c',
                'vname' => 'LBL_TEMPLATE_DDOWN_C',
                'type' => 'multienum',
                'massupdate' => 0,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => 0,
                'reportable' => 0,
                'options' => 'template_ddown_c_list',
                'studio' => 'visible',
                'isMultiSelect' => true,
            ),
        'subtotal_tax_amount' =>
            array(
                'required' => false,
                'name' => 'subtotal_tax_amount',
                'vname' => 'LBL_SUBTOTAL_TAX_AMOUNT',
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
        'subtotal_tax_amount_usdollar' =>
            array(
                'name' => 'subtotal_tax_amount_usdollar',
                'vname' => 'LBL_GRAND_TOTAL_USDOLLAR',
                'type' => 'currency',
                'group' => 'subtotal_tax_amount',
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

        'accounts' =>
            array(
                'name' => 'accounts',
                'vname' => 'LBL_ACCOUNTS',
                'type' => 'link',
                'relationship' => 'account_aos_invoices',
                'module' => 'Accounts',
                'bean_name' => 'Account',
                'source' => 'non-db',
            ),
        'contacts' =>
            array(
                'name' => 'contacts',
                'vname' => 'LBL_CONTACTS',
                'type' => 'link',
                'relationship' => 'contact_aos_invoices',
                'module' => 'Contacts',
                'bean_name' => 'Contact',
                'source' => 'non-db',
            ),
        'aos_quotes_aos_invoices' =>
            array(
                'name' => 'aos_quotes_aos_invoices',
                'vname' => 'LBL_AOS_QUOTES_AOS_INVOICES',
                'type' => 'link',
                'relationship' => 'aos_quotes_aos_invoices',
                'source' => 'non-db',
                'module' => 'AOS_Quotes',
            ),
        'aos_products_quotes' =>
            array(
                'name' => 'aos_products_quotes',
                'vname' => 'LBL_AOS_PRODUCT_QUOTES',
                'type' => 'link',
                'relationship' => 'aos_invoices_aos_product_quotes',
                'module' => 'AOS_Products_Quotes',
                'bean_name' => 'AOS_Products_Quotes',
                'source' => 'non-db',
            ),
        'aos_line_item_groups' =>
            array(
                'name' => 'aos_line_item_groups',
                'vname' => 'LBL_AOS_LINE_ITEM_GROUPS',
                'type' => 'link',
                'relationship' => 'aos_invoices_aos_line_item_groups',
                'module' => 'AOS_Line_Item_Groups',
                'bean_name' => 'AOS_Line_Item_Groups',
                'source' => 'non-db',
            ),
        'custom_detail_in_detail_view' =>
            array(
                'name' => 'custom_detail_in_detail_view',
                'vname' => 'custom_detail_in_detail_view',
                'source' => 'non-db',
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
            'account_site_details' =>
            array(
                'required' => false,
                'name' => 'account_site_details',
                'vname' => 'LBL_ACCOUNT_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'display_account_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),

        'mobile_phone_site_details' =>
            array(
                'required' => false,
                'name' => 'mobile_phone_site_details',
                'vname' => 'LBL_MOBILE_PHONE_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'mobile_phone_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),
        
        'email_site_details' =>
            array(
                'required' => false,
                'name' => 'email_site_details',
                'vname' => 'LBL_EMAIL_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'email_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),

        'address_site_details' =>
            array(
                'required' => false,
                'name' => 'address_site_details',
                'vname' => 'LBL_ADDRESS_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'address_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),

        'image_site_details' =>
            array(
                'required' => false,
                'name' => 'image_site_details',
                'vname' => 'LBL_IMAGE_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'image_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),
        'solargain_quote_number_site_details' =>
            array(
                'required' => false,
                'name' => 'solargain_quote_number_site_details',
                'vname' => 'LBL_SOLARGAIN_QUOTE_NUMBER_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'solargain_quote_number_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),    
        'roof_type_site_details' =>
            array(
                'required' => false,
                'name' => 'roof_type_site_details',
                'vname' => 'LBL_ROOF_TYPE_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'roof_type_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),

        'nmi_site_details' =>
            array(
                'required' => false,
                'name' => 'nmi_site_details',
                'vname' => 'LBL_NMI_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'nmi_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),   

        'distributor_site_details' =>
            array(
                'required' => false,
                'name' => 'distributor_site_details',
                'vname' => 'LBL_DISTRIBUTOR_SITE_DETAILS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'studio' => false,
                'function' =>
                array(
                    'name' => 'distributor_site_details',
                    'returns' => 'html',
                    'include' => 'modules/AOS_Quotes/CustomFunctionSiteDetail.php'
                ),
            ),   
        //VUT - S - create field none db
        /** S - Field varchar - text */
        'solargain_quote_number_c' =>
        array(
            'required' => false,
            'name' => 'solargain_quote_number_c',
            'vname' => 'LBL_SOLARGAIN_QUOTE_NUMBER_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'pe_site_details_no_c' =>
        array(
            'required' => false,
            'name' => 'pe_site_details_no_c',
            'vname' => 'LBL_PE_SITE_DETAILS_NO_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'sg_site_details_no_c' =>
        array(
            'required' => false,
            'name' => 'sg_site_details_no_c',
            'vname' => 'LBL_SG_SITE_DETAILS_NO_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'cable_size_c' =>
        array(
            'required' => false,
            'name' => 'cable_size_c',
            'vname' => 'LBL_CABLE_SIZE_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'meter_number_c' =>
        array(
            'required' => false,
            'name' => 'meter_number_c',
            'vname' => 'LBL_METER_NUMBER_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'nmi_c' =>
        array(
            'required' => false,
            'name' => 'nmi_c',
            'vname' => 'LBL_NMI_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'account_number_c' =>
        array(
            'required' => false,
            'name' => 'account_number_c',
            'vname' => 'LBL_ACCOUNT_NUMBER_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'address_nmi_c' =>
        array(
            'required' => false,
            'name' => 'address_nmi_c',
            'vname' => 'LBL_ADDRESS_NMI_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'name_on_billing_account_c' =>
        array(
            'required' => false,
            'name' => 'name_on_billing_account_c',
            'vname' => 'LBL_NAME_ON_BILLING_ACCOUNT_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),
        'account_holder_dob_c' =>
        array(
            'required' => false,
            'name' => 'account_holder_dob_c',
            'vname' => 'LBL_ACCOUNT_HOLDER_DOB_C',
            'type' => 'varchar',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
        ),

        /** E - Field varchar - text */

        /** S - Field radio */
        'customer_type_c' =>
        array(
            'required' => false,
            'name' => 'customer_type_c',
            'vname' => 'LBL_CUSTOMER_TYPE_C',
            'type' => 'radioenum',
            'source' => 'non-db',
            'massupdate' => 1,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'customer_type_list',
            'separator' => '<br>',
            'default' => '1',
        ),

        /** E - Field radio */

        /** S - Field Dropdown */
        'roof_type_c' =>
        array(
            'required' => false,
            'name' => 'roof_type_c',
            'vname' => 'LBL_ROOF_TYPE_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'roof_type_list',
            'default' => 'Tile',
        ),
        'gutter_height_c' =>
        array(
            'required' => false,
            'name' => 'gutter_height_c',
            'vname' => 'LBL_GUTTER_HEIGHT_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'gutter_height_list',
            'default' => '2',
        ),
        'connection_type_c' =>
        array(
            'required' => false,
            'name' => 'connection_type_c',
            'vname' => 'LBL_CONNECTION_TYPE_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'connection_type_list',
            // 'default' => '1',
        ),
        'main_type_c' =>
        array(
            'required' => false,
            'name' => 'main_type_c',
            'vname' => 'LBL_MAIN_TYPE_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'main_type_list',
            // 'default' => '1',
        ),
        'meter_phase_c' =>
        array(
            'required' => false,
            'name' => 'meter_phase_c',
            'vname' => 'LBL_METER_PHASE_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'meter_phase_list',
            // 'default' => '1',
        ),
        'distributor_c' =>
        array(
            'required' => false,
            'name' => 'distributor_c',
            'vname' => 'LBL_DISTRIBUTOR_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'distributor_list',
            'default' => '0',
        ),
        'energy_retailer_c' =>
        array(
            'required' => false,
            'name' => 'energy_retailer_c',
            'vname' => 'LBL_ENERGY_RETAILER_C',
            'type' => 'enum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'energy_retailer_list',
            'default' => '0',
        ),
        
        /** E - Field Dropdown */

        /** S - Field Multi Select */
        'potential_issues_c' =>
        array(
            'required' => false,
            'name' => 'potential_issues_c',
            'vname' => 'LBL_POTENTIAL_ISSUES_C',
            'type' => 'multienum',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'options' => 'potential_issues_c_list',
            // 'default' => '1',
        ),

        /** E - Field Field Multi Select */

        /** S - Field Checkbox */
        'export_meter_c' =>
        array(
            'required' => false,
            'name' => 'export_meter_c',
            'vname' => 'LBL_EXPORT_METER_C',
            'type' => 'bool',
            'source' => 'non-db',
            'massupdate' => 0,
            'comment' => 'Site details - Editview',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'default' => '0',
        ),
        /** E - Field Checkbox */
        //VUT - E - create field none db
        //VUT - S - create field save id meeting for Plumber/Electrician
        'meeting_plumber' =>
            array(
                'name' => 'meeting_plumber',
                'vname' => 'LBL_MEETING_PLUMBER',
                'type' => 'varchar',
                'len' => 36,
                'inline_edit' => true,
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
        ),
        'meeting_electrician' =>
            array(
                'name' => 'meeting_electrician',
                'vname' => 'LBL_MEETING_ELECTRICIAN',
                'type' => 'varchar',
                'len' => 36,
                'inline_edit' => true,
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
            ),
        //VUT - E - create field save id meeting for Plumber/Electrician
        //VUT - S - create relate account relate STC Aggregator
        'stc_account_id' =>
        array(
            'required' => false,
            'name' => 'stc_account_id',
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
    'stc_account' =>
        array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'stc_account',
            'vname' => 'LBL_STC_ACCOUNT',
            'type' => 'relate',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 1,
            'reportable' => 1,
            'len' => '255',
            'id_name' => 'stc_account_id',
            'ext2' => 'Accounts',
            'module' => 'Accounts',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
        //VUT - E - create relate account relate STC Aggregator
    ),

    'relationships' => array(
        'aos_invoices_aos_product_quotes' =>
            array(
                'lhs_module' => 'AOS_Invoices',
                'lhs_table' => 'aos_invoices',
                'lhs_key' => 'id',
                'rhs_module' => 'AOS_Products_Quotes',
                'rhs_table' => 'aos_products_quotes',
                'rhs_key' => 'parent_id',
                'relationship_type' => 'one-to-many',
            ),
        'aos_invoices_aos_line_item_groups' =>
            array(
                'lhs_module' => 'AOS_Invoices',
                'lhs_table' => 'aos_invoices',
                'lhs_key' => 'id',
                'rhs_module' => 'AOS_Line_Item_Groups',
                'rhs_table' => 'aos_line_item_groups',
                'rhs_key' => 'parent_id',
                'relationship_type' => 'one-to-many',
            ),
    ),
    'optimistic_locking' => true,
);
require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('AOS_Invoices', 'AOS_Invoices', array('basic', 'assignable', 'security_groups'));
