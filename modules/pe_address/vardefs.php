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

$dictionary['pe_address'] = array(
    'table' => 'pe_address',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record created',
            'inline_edit' => false
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record last modified',
            'inline_edit' => false
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'reportable' => true,
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User who last modified record'
        ),
        'created_by' => array(
            'name' => 'created_by',
            'vname' => 'LBL_CREATED_BY',
            'type' => 'varchar',
            'len' => '36',
            'comment' => 'User who created record'
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Message name',
            'importable' => 'required',
            'required' => true
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Message description'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => false,
            'reportable' => false,
            'comment' => 'Record deletion indicator'
        ),
        // 'assigned_user_id' => array(
        //     'name' => 'assigned_user_id',
        //     'rname' => 'user_name',
        //     'id_name' => 'assigned_user_id',
        //     'vname' => 'LBL_ASSIGNED_TO_ID',
        //     'group' => 'assigned_user_name',
        //     'type' => 'relate',
        //     'table' => 'users',
        //     'module' => 'Users',
        //     'reportable' => true,
        //     'isnull' => 'false',
        //     'dbType' => 'id',
        //     'audited' => true,
        //     'comment' => 'User ID assigned to record',
        //     'duplicate_merge' => 'disabled'
        // ),
        // 'assigned_user_name' => array(
        //     'name' => 'assigned_user_name',
        //     'link' => 'assigned_user_link',
        //     'vname' => 'LBL_ASSIGNED_TO_NAME',
        //     'rname' => 'user_name',
        //     'type' => 'relate',
        //     'reportable' => false,
        //     'source' => 'non-db',
        //     'table' => 'users',
        //     'id_name' => 'assigned_user_id',
        //     'module' => 'Users',
        //     'duplicate_merge' => 'disabled'
        // ),
        // 'assigned_user_link' => array(
        //     'name' => 'assigned_user_link',
        //     'type' => 'link',
        //     'relationship' => 'emailtemplates_assigned_user',
        //     'vname' => 'LBL_ASSIGNED_TO_USER',
        //     'link_type' => 'one',
        //     'module' => 'Users',
        //     'bean_name' => 'User',
        //     'source' => 'non-db',
        //     'duplicate_merge' => 'enabled',
        //     'rname' => 'user_name',
        //     'id_name' => 'assigned_user_id',
        //     'table' => 'users',
        // ),
    //create
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
            // 'populate_list' => array(
            //     'billing_address_street',
            //     'billing_address_city',
            //     'billing_address_state',
            //     'billing_address_postalcode',
            //     'billing_address_country',
            // ),
        ),
    'electricity_distributor' =>
        array(
            'required' => false,
            'name' => 'electricity_distributor',
            'vname' => 'LBL_ELECTRICTY_DISTRIBUTOR',
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
            'options' => 'distributor_list',
            'studio' => 'visible',
        ),
    'electricity_retailer' =>
        array(
            'required' => false,
            'name' => 'electricity_retailer',
            'vname' => 'LBL_ELECTRICTY_RETAILER',
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
            'options' => 'energy_retailer_list',
            'studio' => 'visible',
        ),
    'billing_meter_number' => 
        array(
            'name' => 'billing_meter_number',
            'vname' => 'LBL_BILLING_METER_NUMBER',
            'type' => 'varchar',
            'len' => 32,
        ),
    'nmi' => 
    array(
        'name' => 'nmi',
        'vname' => 'LBL_NMI',
        'type' => 'varchar',
        'len' => 32,
    ),
    'address_nmi' => 
    array(
        'name' => 'address_nmi',
        'vname' => 'LBL_ADDRESS_NMI',
        'type' => 'varchar',
        'len' => 32,
    ),
    'grid_export_limit' =>
        array(
            'name'  => 'grid_export_limit',
            'vname' => 'LBL_APPROVED_GRID_EXPORT_CAPACITY',
            'type'  => 'int',
            'required' => false,
            'function' =>  array(
                'name' => 'address_generateNumberField',
                'returns' => 'html',
                'include' => 'modules/pe_address/customField.php'
            ),
            'default' => '0',
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
            'reportable' => true,
            'len' => '255',
            'id_name' => 'billing_contact_id',
            'ext2' => 'Contacts',
            'module' => 'Contacts',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
    'related_quote_id' =>
        array(
            'required' => false,
            'name' => 'related_quote_id',
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
    'related_quote' =>
        array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'related_quote',
            'vname' => 'LBL_RELATED_QUOTE',
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
            'id_name' => 'related_quote_id',
            'ext2' => 'AOS_Quotes',
            'module' => 'AOS_Quotes',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
        
    'map_data' => array(
        'name' => 'map_data',
        'vname' => 'LBL_MAP_DATA',
        'type' => 'text',
        'rows' => 6,
        'cols' => 80,
    ),
    /**Unique number for Service case */
    'number' =>
        array(
            'name' => 'number',
            'vname' => 'LBL_ADDRESS_NUMBER',
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
    //S - data non-db
    'street_view' =>
        array(
            'required' => false,
            'name' => 'street_view',
            'vname' => 'LBL_STREET_VIEW',
            'type' => 'function',
            'source' => 'non-db',
            'massupdate' => 0,
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'function' =>
            array(
                'name' => 'street_view',
                'returns' => 'html',
                'include' => 'modules/pe_address/customFunctionForFields.php'
            ),
        ),
    'satellite_view' =>
        array(
            'required' => false,
            'name' => 'satellite_view',
            'vname' => 'LBL_SATELLITE_VIEW',
            'type' => 'function',
            'source' => 'non-db',
            'massupdate' => 0,
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'studio' => true,
            'function' =>
            array(
                'name' => 'satellite_view',
                'returns' => 'html',
                'include' => 'modules/pe_address/customFunctionForFields.php'
            ),
        ),

    //E - data non-db
),//end field
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('pe_address', 'pe_address', array('basic','assignable','security_groups'));