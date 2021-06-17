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


$dictionary['pe_promotions'] = array(
    'table' => 'pe_promotions',
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
        'promo_code' =>
        array(
            'name' => 'promo_code',
            'vname' => 'LBL_PROMO_CODE',
            'type' => 'varchar',
            'len' => '36',
            'comment' => ''
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Description of the Promotions',
            'rows' => 6,
            'cols' => 80,
        ),
        'date_start' =>
            array(
                'name' => 'date_start',
                'vname' => 'LBL_DATE_START',
                'type' => 'datetimecombo',
                'dbType' => 'datetime',
                'comment' => 'Date start',
                'importable' => 'required',
                'required' => true,
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
                'validation' => array('type' => 'isbefore', 'compareto' => 'date_end', 'blank' => false),
            ),

        'date_end' =>
            array(
                'name' => 'date_end',
                'vname' => 'LBL_DATE_END',
                'type' => 'datetimecombo',
                'dbType' => 'datetime',
                'massupdate' => false,
                'comment' => 'Date end',
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            ),
        'status' =>
            array(
                'name' => 'status',
                'vname' => 'LBL_STATUS',
                'type' => 'enum',
                'len' => 100,
                'options' => 'pe_promotions_status_dom',
                'comment' => 'PE Promotions status (ex: Enabled, Disable)',
                'default' => 'enabled',
            ),
        'type' =>
            array(
                'name' => 'type',
                'vname' => 'LBL_TYPE_PROMOTION',
                'type' => 'enum',
                'len' => 100,
                'options' => 'pe_promotions_type_dom',
                'comment' => 'PE Promotions status (ex: Percentage off the order Grand Total, Fixed amount off the order Grand Total)',
                'default' => 'order_fixed_grand_total_off',
            ),
        'value' =>
            array(
                'name' => 'value',
                'vname' => 'LBL_VALUE',
                'type' => 'varchar',
                'len' => '36',
                'comment' => ''
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
VardefManager::createVardef('pe_promotions', 'pe_promotions', array('basic','assignable','security_groups'));