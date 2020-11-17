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

$dictionary['pe_warehouse'] = array(
    'table' => 'pe_warehouse',
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
	    'billing_address_street' =>
	        array(
	            'name' => 'billing_address_street',
	            'vname' => 'LBL_BILLING_ADDRESS_STREET',
	            'type' => 'varchar',
	            'len' => '150',
	            'comment' => 'The street address used for billing address',
	            'group' => 'owner_address',
	            'merge_filter' => 'enabled',
	        ),
	    'billing_address_city' =>
	        array(
	            'name' => 'billing_address_city',
	            'vname' => 'LBL_BILLING_ADDRESS_CITY',
	            'type' => 'varchar',
	            'len' => '100',
	            'comment' => 'The city used for billing address',
	            'group' => 'owner_address',
	            'merge_filter' => 'enabled',
	        ),
	    'billing_address_state' =>
	        array(
	            'name' => 'billing_address_state',
	            'vname' => 'LBL_BILLING_ADDRESS_STATE',
	            'type' => 'varchar',
	            'len' => '100',
	            'group' => 'owner_address',
	            'comment' => 'The state used for billing address',
	            'merge_filter' => 'enabled',
	        ),
	    'billing_address_postalcode' =>
	        array(
	            'name' => 'billing_address_postalcode',
	            'vname' => 'LBL_BILLING_ADDRESS_POSTALCODE',
	            'type' => 'varchar',
	            'len' => '20',
	            'group' => 'owner_address',
	            'comment' => 'The postal code used for billing address',
	            'merge_filter' => 'enabled',

	        ),
	    'billing_address_country' =>
	        array(
	            'name' => 'billing_address_country',
	            'vname' => 'LBL_BILLING_ADDRESS_COUNTRY',
	            'type' => 'varchar',
	            'group' => 'owner_address',
	            'comment' => 'The country used for the billing address',
	            'merge_filter' => 'enabled',
			),
			
		// Warehouse location!

		'shipping_address_street' =>
			array(
				'name' => 'shipping_address_street',
				'vname' => 'LBL_SHIPPING_ADDRESS_STREET',
				'type' => 'varchar',
				'len' => '150',
				'comment' => 'The street address used for billing address',
				'group' => 'owner_address',
				'merge_filter' => 'enabled',
			),
		'shipping_address_city' =>
			array(
				'name' => 'shipping_address_city',
				'vname' => 'LBL_SHIPPING_ADDRESS_CITY',
				'type' => 'varchar',
				'len' => '100',
				'comment' => 'The city used for billing address',
				'group' => 'owner_address',
				'merge_filter' => 'enabled',
			),
		'shipping_address_state' =>
			array(
				'name' => 'shipping_address_state',
				'vname' => 'LBL_SHIPPING_ADDRESS_STATE',
				'type' => 'varchar',
				'len' => '100',
				'group' => 'owner_address',
				'comment' => 'The state used for billing address',
				'merge_filter' => 'enabled',
			),
		'shipping_address_postalcode' =>
			array(
				'name' => 'shipping_address_postalcode',
				'vname' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
				'type' => 'varchar',
				'len' => '20',
				'group' => 'warehouse_address',
				'comment' => 'The postal code used for billing address',
				'merge_filter' => 'enabled',

			),
		'shipping_address_country' =>
			array(
				'name' => 'shipping_address_country',
				'vname' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
				'type' => 'varchar',
				'group' => 'owner_address',
				'comment' => 'The country used for the billing address',
				'merge_filter' => 'enabled',
			),
			
	    'pickup_fee' =>
	        array(
	            'name' => 'pickup_fee',
	            'vname' => 'LBL_PICKUP_FEE',
	            'type' => 'varchar',
	            'len' => '150',
	            'comment' => 'The pickup fee for Warehouse',
	        ),
	    'capacity_sanden' =>
	        array(
	            'name' => 'capacity_sanden',
	            'vname' => 'LBL_CAPACITY_SANDEN',
	            'type' => 'varchar',
	            'len' => '150',
	            'comment' => 'The pickup fee for Warehouse',
	        ),

	    'capacity_daikin' =>
	        array(
	            'name' => 'capacity_daikin',
	            'vname' => 'LBL_CAPACITY_SANDEN',
	            'type' => 'varchar',
	            'len' => '150',
	            'comment' => 'The pickup fee for Warehouse',
	        ),

	    'capacity_mathven' =>
	        array(
	            'name' => 'capacity_mathven',
	            'vname' => 'LBL_CAPACITY_MATHVEN',
	            'type' => 'varchar',
	            'len' => '150',
	            'comment' => 'The pickup fee for Warehouse',
	        ),

	    'number' =>
            array(
                'required' => true,
                'name' => 'number',
                'vname' => 'LBL_QUOTE_NUMBER',
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
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('pe_warehouse', 'pe_warehouse', array('basic','assignable','security_groups'));