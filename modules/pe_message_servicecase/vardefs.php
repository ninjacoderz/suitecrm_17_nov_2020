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

$dictionary['pe_message_servicecase'] = array(
    'table' => 'pe_message_servicecase',
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
        'message' => array(
            'name' => 'message',
            'vname' => 'LBL_MESSAGE',
            'type' => 'text',
            'comment' => 'Message content',
            'rows' => 6,
            'cols' => 80,
        ),
        'error_code' => array(
            'name' => 'error_code',
            'vname' => 'LBL_ERROR_CODE',
            'type' => 'varchar',
            'comment' => 'Error code',
            'len' => 11,
        ),
        'error_content' => array(
            'name' => 'error_content',
            'vname' => 'LBL_ERROR_CONTENT',
            'type' => 'text',
            'comment' => 'Error Content',
            'rows' => 6,
            'cols' => 80,
        ),
        'manufacturer_diagnostic' => array(
            'name' => 'manufacturer_diagnostic',
            'vname' => 'LBL_MANUFACTURER_DIAGNOSTIC',
            'type' => 'text',
            'comment' => 'Manufacturer Diagnostic Recommended Next Steps',
            'rows' => 6,
            'cols' => 80,
        ),
        'manufacturer_judgement' => array(
            'name' => 'manufacturer_judgement',
            'vname' => 'LBL_MANUFACTURER_JUDGEMENT',
            'type' => 'text',
            'comment' => 'Manufacturer Judgement and Repair Methods Recommended Next Steps',
            'rows' => 6,
            'cols' => 80,
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
VardefManager::createVardef('pe_message_servicecase', 'pe_message_servicecase', array('basic','assignable','security_groups'));