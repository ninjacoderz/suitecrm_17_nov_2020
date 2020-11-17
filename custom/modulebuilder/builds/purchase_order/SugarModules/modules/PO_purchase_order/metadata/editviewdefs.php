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
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_ACCOUNT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_ADDRESS_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),

    'panels' => array(
        'default' => array(
            array(
                'name',
                'assigned_user_name',
            ),
            
            1 => array(
              0 => array (
                'name' => 'number',
                'label' => 'LBL_QUOTE_NUMBER',
                'customCode' => '{$fields.number.value}',
              ),
              1 => "",
            ),
            2 => 
            array (
              0 => 
              array (
                'name' => 'install_date',
                'label' => 'LBL_INSTALL_DATE',
              ),
              1 => 
              array (
                'name' => 'acceptance_date',
                'label' => 'LBL_ACCEPTANCE_DATE',
              ),
            ),
            array(
                'description',
            ),
        ),

        'lbl_address_information' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'billing_account',
              'label' => 'LBL_BILLING_ACCOUNT',
              'displayParams' => 
              array (
                'key' => 
                array (
                  0 => 'billing',
                  1 => 'shipping',
                ),
                'copy' => 
                array (
                  0 => 'billing',
                  1 => 'shipping',
                ),
                'billingKey' => 'billing',
                'shippingKey' => 'shipping',
              ),
            ),
            1 => '',
          ),
          1 => 
          array (
            0 => 
            array (
              'name' => 'billing_contact',
              'label' => 'LBL_BILLING_CONTACT',
              'displayParams' => 
              array (
                'initial_filter' => '&account_name="+this.form.{$fields.billing_account.name}.value+"',
              ),
            ),
            1 => '',
          ),
          2 => 
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
                'copy' => 'billing',
                'rows' => 2,
                'cols' => 30,
                'maxlength' => 150,
              ),
              'label' => 'LBL_SHIPPING_ADDRESS_STREET',
            ),
          ),
        ),

        'lbl_line_items' => 
        array (
          1 => 
          array (
            0 => 
            array (
              'name' => 'line_items',
              'label' => 'LBL_LINE_ITEMS',
            ),
          ),
          2 => 
          array (
            0 => '',
          ),
          3 => 
          array (
            0 => 
            array (
              'name' => 'total_amt',
              'label' => 'LBL_TOTAL_AMT',
            ),
          ),
          4 => 
          array (
            0 => 
            array (
              'name' => 'discount_amount',
              'label' => 'LBL_DISCOUNT_AMOUNT',
            ),
          ),
          5 => 
          array (
            0 => 
            array (
              'name' => 'subtotal_amount',
              'label' => 'LBL_SUBTOTAL_AMOUNT',
            ),
          ),
          6 => 
          array (
            0 => 
            array (
              'name' => 'shipping_amount',
              'label' => 'LBL_SHIPPING_AMOUNT',
              'displayParams' => 
              array (
                'field' => 
                array (
                  'onblur' => 'calculateTotal(\'lineItems\');',
                ),
              ),
            ),
          ),
          7 => 
          array (
            0 => 
            array (
              'name' => 'shipping_tax_amt',
              'label' => 'LBL_SHIPPING_TAX_AMT',
            ),
          ),
          8 => 
          array (
            0 => 
            array (
              'name' => 'tax_amount',
              'label' => 'LBL_TAX_AMOUNT',
            ),
          ),
          9 => 
          array (
            0 => 
            array (
              'name' => 'total_amount',
              'label' => 'LBL_GRAND_TOTAL',
            ),
          ),
        ),
    ),

);



$viewdefs[$module_name]['EditView']['templateMeta']['includes'] =
array (
    array (
    'file' => 'modules/PO_purchase_order/PurchaseOrder.js',
    ),
);