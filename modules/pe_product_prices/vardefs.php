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

$dictionary['pe_product_prices'] = array(
    'table' => 'pe_product_prices',
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
            'required' => true,
            'function' =>  array(
                'name' => 'link_product',
                'returns' => 'html',
                'include' => 'modules/pe_product_prices/customField.php'
            ),
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
    //create
    'part_number' =>
    array(
        'required' => true,
        'name' => 'part_number',
        'vname' => 'LBL_PART_NUMBER',
        'type' => 'varchar',
        'massupdate' => 0,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => 1,
        'reportable' => true,
        'len' => '25',
        'function' =>  array(
            'name' => 'link_product',
            'returns' => 'html',
            'include' => 'modules/pe_product_prices/customField.php'
        ),
    ),
    'product_id' =>
    array(
        'name' => 'product_id',
        'vname' => 'LBL_PRODUCT_ID',
        'type' => 'varchar',
        'len' => 36,
        'inline_edit' => false,
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
    ),
    'cost' =>
    array(
        'required' => '0',
        'name' => 'cost',
        'vname' => 'LBL_COST',
        'type' => 'currency',
        'len' => '26,6',
        'massupdate' => 0,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => 1,
        'reportable' => true,
        'enable_range_search' => true,
        'options' => 'numeric_range_search_dom',
    ),
    'pricing_source' =>
    array(
        'required' => false,
        'name' => 'pricing_source',
        'vname' => 'LBL_PRICING_SOURCE',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => 'Good',
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => 1,
        'reportable' => true,
        'len' => 100,
        'options' => 'product_type_dom',
        'studio' => 'visible',
    ),
    'date_release' => array(
        'name' => 'date_release',
        'vname' => 'LBL_DATE_RELEASE',
        'type' => 'date',
        'required' => false,
        'comment' => 'Date Release',
        'inline_edit' => false
    ),
    'website' =>
    array(
        'name' => 'website',
        'vname' => 'LBL_WEBSITE',
        'type' => 'varchar',
        'dbType' => 'varchar',
        'len' => 255,
        // 'link_target' => '_blank',
        'comment' => 'URL of website for the Product',
        'function' =>  array(
            'name' => 'link_website',
            'returns' => 'html',
            'include' => 'modules/pe_product_prices/customField.php'
        ),
),
//Relate field
    'account_id' =>
    array(
        'required' => false,
        'name' => 'account_id',
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
    'account' =>
    array(
        'required' => false,
        'source' => 'non-db',
        'name' => 'account',
        'vname' => 'LBL_ACCOUNT',
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
        'id_name' => 'account_id',
        'ext2' => 'Accounts',
        'module' => 'Accounts',
        'quicksearch' => 'enabled',
        'studio' => 'visible',
    ),

/**Unique number for Service case */
    'number' =>
        array(
            'name' => 'number',
            'vname' => 'LBL_PRODUCT_PRICES_NUMBER',
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
VardefManager::createVardef('pe_product_prices', 'pe_product_prices', array('basic','assignable','security_groups'));