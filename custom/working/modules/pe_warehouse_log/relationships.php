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
$relationships = array (
  'pe_warehouse_log_modified_user' => 
  array (
    'id' => '31d9bf2d-386c-aa86-f602-5da7ec9f2aad',
    'relationship_name' => 'pe_warehouse_log_modified_user',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'modified_user_id',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'pe_warehouse_log_created_by' => 
  array (
    'id' => '33361453-2bf8-2db3-28e6-5da7ec6e2664',
    'relationship_name' => 'pe_warehouse_log_created_by',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'created_by',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'pe_warehouse_log_assigned_user' => 
  array (
    'id' => '34929575-ed8d-97d9-a517-5da7ec483d4f',
    'relationship_name' => 'pe_warehouse_log_assigned_user',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'assigned_user_id',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'securitygroups_pe_warehouse_log' => 
  array (
    'id' => '35f5d907-5e49-98f0-6234-5da7ec7ae9b7',
    'relationship_name' => 'securitygroups_pe_warehouse_log',
    'lhs_module' => 'SecurityGroups',
    'lhs_table' => 'securitygroups',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'id',
    'join_table' => 'securitygroups_records',
    'join_key_lhs' => 'securitygroup_id',
    'join_key_rhs' => 'record_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'module',
    'relationship_role_column_value' => 'pe_warehouse_log',
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'pe_warehouse_log_pe_warehouse_log_1' => 
  array (
    'id' => 'ba3caf7f-5d58-7bce-f1d9-5da7ec696053',
    'relationship_name' => 'pe_warehouse_log_pe_warehouse_log_1',
    'lhs_module' => 'pe_warehouse_log',
    'lhs_table' => 'pe_warehouse_log',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'id',
    'join_table' => 'pe_warehouse_log_pe_warehouse_log_1_c',
    'join_key_lhs' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida',
    'join_key_rhs' => 'pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_idb',
    'relationship_type' => 'one-to-one',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'po_purchase_order_pe_warehouse_log_1' => 
  array (
    'id' => 'bba4ab02-f8d2-d0a3-5057-5da7ec875a80',
    'relationship_name' => 'po_purchase_order_pe_warehouse_log_1',
    'lhs_module' => 'PO_purchase_order',
    'lhs_table' => 'po_purchase_order',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'id',
    'join_table' => 'po_purchase_order_pe_warehouse_log_1_c',
    'join_key_lhs' => 'po_purchase_order_pe_warehouse_log_1po_purchase_order_ida',
    'join_key_rhs' => 'po_purchase_order_pe_warehouse_log_1pe_warehouse_log_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'default',
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'pe_warehouse_log_pe_warehouse' => 
  array (
    'id' => 'c464707d-f33e-c458-4e51-5da7ecfb7dfa',
    'relationship_name' => 'pe_warehouse_log_pe_warehouse',
    'lhs_module' => 'pe_warehouse',
    'lhs_table' => 'pe_warehouse',
    'lhs_key' => 'id',
    'rhs_module' => 'pe_warehouse_log',
    'rhs_table' => 'pe_warehouse_log',
    'rhs_key' => 'id',
    'join_table' => 'pe_warehouse_log_pe_warehouse_c',
    'join_key_lhs' => 'pe_warehouse_log_pe_warehousepe_warehouse_ida',
    'join_key_rhs' => 'pe_warehouse_log_pe_warehousepe_warehouse_log_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'default',
    'lhs_subpanel' => NULL,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
    'from_studio' => true,
  ),
  'pe_warehouse_log_pe_internal_note_1' => 
  array (
    'rhs_label' => 'Internal Notes',
    'lhs_label' => 'Warehouse Log',
    'lhs_subpanel' => 'default',
    'rhs_subpanel' => 'default',
    'lhs_module' => 'pe_warehouse_log',
    'rhs_module' => 'pe_internal_note',
    'relationship_type' => 'many-to-many',
    'readonly' => true,
    'deleted' => false,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
    'relationship_name' => 'pe_warehouse_log_pe_internal_note_1',
  ),
);