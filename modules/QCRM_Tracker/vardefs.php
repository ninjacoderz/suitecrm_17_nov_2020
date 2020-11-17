<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 ********************************************************************************/

$dictionary['QCRM_Tracker'] = array(
	'table'=>'qcrm_tracker',
	'audited'=>false,
    'inline_edit'=>true,
		'duplicate_merge'=>true,
		'fields'=>array (
),
	'relationships'=>array (
),
/*
   'fields'=>array (
    'jjwg_maps_address_c' => 
    array (
      'inline_edit' => 0,
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'jjwg_maps_address_c',
      'vname' => 'LBL_JJWG_MAPS_ADDRESS',
      'type' => 'varchar',
      'massupdate' => '0',
      'default' => '',
      'no_default' => false,
      'comments' => 'Address',
      'help' => 'Address',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
      'id' => 'QCRM_Trackerjjwg_maps_address_c',
      'custom_module' => 'QCRM_Tracker',
    ),
    'jjwg_maps_geocode_status_c' => 
    array (
      'inline_edit' => 0,
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'jjwg_maps_geocode_status_c',
      'vname' => 'LBL_JJWG_MAPS_GEOCODE_STATUS',
      'type' => 'varchar',
      'massupdate' => '0',
      'default' => '',
      'no_default' => false,
      'comments' => 'Geocode Status',
      'help' => 'Geocode Status',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
      'id' => 'QCRM_Trackerjjwg_maps_geocode_status_c',
      'custom_module' => 'QCRM_Tracker',
    ),
    'jjwg_maps_lat_c' => 
    array (
      'inline_edit' => 0,
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'jjwg_maps_lat_c',
      'vname' => 'LBL_JJWG_MAPS_LAT',
      'type' => 'float',
      'massupdate' => '0',
      'default' => '0.00000000',
      'no_default' => false,
      'comments' => '',
      'help' => 'Latitude',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '10',
      'size' => '20',
      'enable_range_search' => false,
      'precision' => '8',
      'id' => 'QCRM_Trackerjjwg_maps_lat_c',
      'custom_module' => 'QCRM_Tracker',
    ),
    'jjwg_maps_lng_c' => 
    array (
      'inline_edit' => 0,
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'jjwg_maps_lng_c',
      'vname' => 'LBL_JJWG_MAPS_LNG',
      'type' => 'float',
      'massupdate' => '0',
      'default' => '0.00000000',
      'no_default' => false,
      'comments' => '',
      'help' => 'Longitude',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '11',
      'size' => '20',
      'enable_range_search' => false,
      'precision' => '8',
      'id' => 'QCRM_Trackerjjwg_maps_lng_c',
      'custom_module' => 'QCRM_Tracker',
    ),
),
*/
	'optimistic_locking'=>true,
		'unified_search'=>false,
	);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('QCRM_Tracker','QCRM_Tracker', array('basic','assignable'));