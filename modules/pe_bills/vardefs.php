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

$dictionary['pe_bills'] = array(
    'table' => 'pe_bills',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
        'number' =>
        array(
            'name' => 'number',
            'vname' => 'LBL_NUMBER',
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
                "populate_list" => array(
                    "billing_address_street",
                    "billing_address_city",
                    "billing_address_state",
                    "billing_address_postalcode",
                    "billing_address_country",
                ),
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
            'purchase_invoice_xero' => 
            array (
                'required' => false,
                'name' => 'purchase_invoice_xero',
                'vname' => 'LBL_DESCRIPTION',
                'type' => 'varchar',
                'comment' => 'Full text of the note',
                'rows' => 6,
                'cols' => 80,
            ),
            'install_date' =>
            array(
                'required' => false,
                'name' => 'install_date',
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
            ),
            'acceptance_date' =>
            array(
                'required' => false,
                'name' => 'acceptance_date',
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
            ),

            'requested_delivery_date' =>
            array(
                'required' => false,
                'name' => 'requested_delivery_date',
                'vname' => 'LBL_REQUESTED_DELIVERY_DATE',
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
            ),
            'estimated_despatch_date' =>
            array(
                'required' => false,
                'name' => 'estimated_despatch_date',
                'vname' => 'LBL_ESTIMATED_DESPATCH_DATE',
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
            ),

            'distance_to_travel' =>
            array(
                'name' => 'distance_to_travel',
                'vname' => 'LBL_DISTANCE_TO_TRAVEL',
                'type' => 'varchar',
                'comment' => 'The distance from supplier to client',
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
VardefManager::createVardef('pe_bills', 'pe_bills', array('basic','assignable','security_groups'));