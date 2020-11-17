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

$module_name = 'pe_warehouse';
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'includes' =>
        array(
            0 =>
            array(
                'file' => 'modules/pe_warehouse/pe_warehouse.js',
            ),
        ),
    ),

    'panels' => array(
        'default' => array(
            0 => 
            array (
                0 => 
                array (
                    'name' => 'name',
                    'displayParams' => 
                    array (
                      'required' => true,
                    ),
                    'label' => 'LBL_NAME',
                ),
                1 => 
                array (
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO_NAME',
                ),
                2 => 
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
                3 => 
                array (
                    'name' => 'number',
                    'label' => 'LBL_QUOTE_NUMBER',
                    'customCode' => '{$fields.number.value}',
                ),
            ),

            1 => 
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
                        'rows' => 2,
                        'cols' => 30,
                        'maxlength' => 150,
                        'copy' => 'billing',
                    ),
                    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
                ),
            ),

            2 => 
            array (
                0 => 
                array (
                    'name' => 'pickup_fee',
                    'label' => 'LBL_PICKUP_FEE',
                ),
            ),

            3 => 
            array (
                0 => 
                array (
                    'name' => 'capacity_sanden',
                    'label' => 'LBL_CAPACITY_SANDEN',
                ),
                1 => 
                array (
                    'name' => 'capacity_daikin',
                    'label' => 'LBL_CAPACITY_DAIKIN',
                ),
                2 => 
                array (
                    'name' => 'capacity_mathven',
                    'label' => 'LBL_CAPACITY_MATHVEN',
                ),
            )
        ),

    ),

);
