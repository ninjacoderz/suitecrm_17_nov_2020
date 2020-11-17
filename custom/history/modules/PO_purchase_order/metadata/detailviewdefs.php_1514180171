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

$module_name = 'PO_purchase_order';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                'FIND_DUPLICATES',
            )
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'useTabs' => true,
        'tabDefs' => 
        array (
            'LBL_PANEL_OVERVIEW' =>
            array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            ),
            'LBL_QUOTE_TO' =>
            array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            ),
            'LBL_LINE_ITEMS' => 
            array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array (
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),
        ),
    ),

    'panels' => 
    array (
        'LBL_PANEL_OVERVIEW' =>
        array (
            0 => 
            array (
                0 => 
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                ),
            1 => 
            array (
                0 => 
                    array (
                        'name' => 'number',
                        'label' => 'LBL_QUOTE_NUMBER',
                    ),
                ),
            
            2 => 
            array (
                0 => 
                array (
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO',
                )
            ) 
        ),
        'LBL_QUOTE_TO' =>
        array (
            0 => 
                array (
                0 => 
                array (
                    'name' => 'billing_account',
                    'label' => 'LBL_BILLING_ACCOUNT',
                ),
                1 => 
                array (
                    'name' => 'billing_contact',
                    'label' => 'LBL_BILLING_CONTACT',
                ),
                ),
            1 => 
                array (
                0 => 
                array (
                    'name' => 'billing_address_street',
                    'label' => 'LBL_BILLING_ADDRESS',
                    'type' => 'address',
                    'displayParams' => 
                    array (
                    'key' => 'billing',
                    ),
                ),
                1 => 
                array (
                    'name' => 'shipping_address_street',
                    'label' => 'LBL_SHIPPING_ADDRESS',
                    'type' => 'address',
                    'displayParams' => 
                    array (
                    'key' => 'shipping',
                    ),
                ),
            ),
        ),
        'LBL_LINE_ITEMS' => 
        array (
            0 =>
                array (
                0 => 
                array (
                    'name' => 'line_items',
                    'label' => 'LBL_LINE_ITEMS',
                ),
                ),
            1 =>
                array (
                0 => 
                array (
                    'name' => 'total_amt',
                    'label' => 'LBL_TOTAL_AMT',
                ),
                ),
            2 =>
                array (
                0 => 
                array (
                    'name' => 'discount_amount',
                    'label' => 'LBL_DISCOUNT_AMOUNT',
                ),
                ),
            3 =>
                array (
                0 => 
                array (
                    'name' => 'subtotal_amount',
                    'label' => 'LBL_SUBTOTAL_AMOUNT',
                ),
                ),
            4 =>
                array (
                0 => 
                array (
                    'name' => 'shipping_amount',
                    'label' => 'LBL_SHIPPING_AMOUNT',
                ),
                ),
            5 =>
                array (
                0 => 
                array (
                    'name' => 'shipping_tax_amt',
                    'label' => 'LBL_SHIPPING_TAX_AMT',
                ),
                ),
            6 =>
                array (
                0 => 
                array (
                    'name' => 'tax_amount',
                    'label' => 'LBL_TAX_AMOUNT',
                ),
                ),
            7 =>
                array (
                0 => 
                array (
                    'name' => 'total_amount',
                    'label' => 'LBL_GRAND_TOTAL',
                ),
                ),
        ),
        'LBL_PANEL_ASSIGNMENT' =>
        array (
            0 =>
                array (
                    0 =>
                        array (
                            'name' => 'date_entered',
                            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        ),
                    1 =>
                        array (
                            'name' => 'date_modified',
                            'label' => 'LBL_DATE_MODIFIED',
                            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        ),
                ),
        ),
    ),
);
