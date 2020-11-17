<?php
/*********************************************************************************
 * This file is part of QuickCRM Mobile Full.
 * QuickCRM Mobile Full is a mobile client for Sugar/SuiteCRM
 * 
 * Author : NS-Team (http://www.ns-team.fr)
 * All rights (c) 2011-2019 by NS-Team
 *
 * This Version of the QuickCRM Mobile Full is licensed software and may only be used in 
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * written consent of NS-Team
 * 
 * You can contact NS-Team at NS-Team - 55 Chemin de Mervilla - 31320 Auzeville - France
 * or via email at infos@ns-team.fr
 * 
 ********************************************************************************/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_language;
global $beanFiles, $beanList;
global $sugar_config;
ini_set("display_errors", 0);

require_once('custom/modules/Administration/QuickCRM_utils.php');
$qutils=new QUtils();
$qutils->LoadMobileConfig(true); // refresh first open only
$qutils->LoadServerConfig(true); // refresh first open only
$qutils->getAddressesLabels();

$module = $_REQUEST['conf_module'];
$profile = $_REQUEST['profile'];
$profile_name = '';
if ($qutils->mobile['profilemode'] != 'none'){
	$profile_name = ' (' . str_replace ('&quot;','',$_REQUEST['profile_name']) . ')';
}

$MBmod_strings=return_module_language($current_language, 'ModuleBuilder');
$currentmod_strings=return_module_language($current_language, $module);

if (!isset($app_strings['LBL_THEME_COLOR'])){
	$Calmod_strings=return_module_language($current_language, 'Calendar');
	$app_strings['LBL_THEME_COLOR'] = $Calmod_strings['LBL_COLOR_SETTINGS'];
}

$ss = new Sugar_Smarty();
$ss->assign('module', $module);
$ss->assign('profile', $profile);
$ss->assign('MOD', $mod_strings);
$ss->assign('APP_STRINGS', $app_strings);
$ss->assign('AVAILABLE', $MBmod_strings['LBL_AVAILABLE']);
$ss->assign('HIDDEN', $MBmod_strings['LBL_HIDDEN']);
$ss->assign('TITLE', $app_list_strings["moduleList"][$module] . ' - ' . $MBmod_strings['LBL_LISTVIEW'] . ' ' . $profile_name);
$ss->assign('DASHLET', $MBmod_strings['LBL_DASHLET']);
$ss->assign('LISTVIEW', $MBmod_strings['LBL_LISTVIEW']);
$ss->assign('SUBPANELS', $MBmod_strings['LBL_SUBPANELS']);

if ($profile == '_default') $displayed = $qutils->mobile['list'][$module];
else $displayed = $qutils->mobile['profiles'][$profile]['list'][$module];
if (!$displayed) $displayed = array();

if ($profile == '_default') $highlighted = $qutils->mobile['highlighted'][$module];
else $highlighted = $qutils->mobile['profiles'][$profile]['highlighted'][$module];
if (!$highlighted) $highlighted = array();

$available_fields=array();
foreach ($displayed as $field){
	$available_fields[]=array('field'=>$field,'label'=>$qutils->server_config['list'][$module][$field]['label']);
}
$ss->assign('tabAvailable', $available_fields);

$highlighted_fields=array();
foreach ($highlighted as $field){
	$highlighted_fields[]=array('field'=>$field,'label'=>$qutils->server_config['list'][$module][$field]['label']);
}
$ss->assign('tabHighlighted', $highlighted_fields);

$hidden_fields=array();
foreach ($qutils->server_config['list'][$module] as $field => $data ){
	if (!in_array($field,$displayed) && !in_array($field,$highlighted)){
		$hidden_fields[]=array('field'=>$field,'label'=>$data['label']);
	}
}
$ss->assign('tabHidden', $hidden_fields);

$show_fields = false;
if (isset($sugar_config['quickcrm_show_fieldname'])){
	$show_fields = $sugar_config['quickcrm_show_fieldname'];
}
$ss->assign('showfields', $show_fields);

$colorfields = array();
$colorfields['none'] = $app_strings['LBL_NONE'];
$colorfields['assigned_user_id'] = $app_strings['LBL_LIST_ASSIGNED_USER'];
if (isset($qutils->server_config['marked'][$module])) {
	foreach ($qutils->server_config['marked'][$module] as $field => $data ){
		$colorfields[$field] = $data['label'];
	}
}

$color = '';
if (isset($qutils->mobile['marked'][$module])) $color = $qutils->mobile['marked'][$module];
$ss->assign('color_value', $color);
$ss->assign('color_options', $colorfields);

// find numeric fields fot totals
$bean = Q_new_bean($module);
$fields_for_totals = array();
$fields_for_totals[''] = $app_strings['LBL_NONE'];
//$fields_for_totals['id'] = 'ID';
$enum_fields = array();
$enum_fields[''] = $app_strings['LBL_NONE'];
foreach($bean->field_name_map as $field_name => $field_defs){
	if (!isset($field_defs['source']) || $field_defs['source'] != 'non-db'){	
		
		if (in_array($field_defs['type'], array('int','currency','float','double','float'))
			&& ! in_array($field_name, array('jjwg_maps_lat_c','jjwg_maps_lng_c','repeat_interval','repeat_count'))){
			$vname = $field_defs['vname']; // LBL_XXX
				
			if (isset($currentmod_strings[$vname]))
				$field_label = $currentmod_strings[$vname];
			else if (isset($app_strings[$vname]))
					$field_label = $app_strings[$vname];
			else
				$field_label = $vname;
					
				$fields_for_totals[$field_defs['name']] = $field_label;
		}
		else if (in_array($field_defs['type'], array('enum','dynamicenum'))){
			$vname = $field_defs['vname']; // LBL_XXX
				
			if (isset($currentmod_strings[$vname]))
				$field_label = $currentmod_strings[$vname];
			else if (isset($app_strings[$vname]))
				$field_label = $app_strings[$vname];
			else
				$field_label = $vname;
					
			$enum_fields[$field_defs['name']] = $field_label;
		}
	}
}

if (file_exists('modules/AOR_Reports/aor_utils.php')){
	$tmp_mod_strings=return_module_language($current_language, 'AOR_Reports');
	$lbl_total = $tmp_mod_strings['LBL_TOTAL'];
}
else {
	$lbl_total = 'Total';
}
if (file_exists('modules/AOR_Fields/AOR_Fields.php')){
	$tmp_mod_strings=return_module_language($current_language, 'AOR_Fields');
	$lbl_group = $tmp_mod_strings['LBL_GROUP'];
}
else {
	$lbl_group = 'Group by';
}

$app_list_strings['total_options'] = getTotalOptions();

$ss->assign('total_functions', getTotalOptions());

$ss->assign('totals_function0', array('SUM'));
$ss->assign('totals_function1', array('SUM'));
$ss->assign('totals_function2', array('SUM'));

if (isset($qutils->mobile['totals'][$module]) && (count($qutils->mobile['totals'][$module])>0)) {
	foreach ($qutils->mobile['totals'][$module] as $key => $values){
		$ss->assign('totals_value'.$key, $values['field']);
		$ss->assign('totals_function'.$key, $values['fnct']);
	}	
}

if (!isset($views['detail']) ||!isset($views['detail'][$module]) || $views['detail'][$module] == False){
	$ss->assign('SYNCCHECKED', "checked='true'");
}

$ss->assign('LBL_TOTAL', $lbl_total);
$ss->assign('total_options', $fields_for_totals);
$ss->assign('show_totals', count($fields_for_totals)>1 || isset($qutils->mobile['totals'][$module])); // for compatibility with 5.0

if (isset($qutils->mobile['groupby'][$module])) {
	$ss->assign('group_value', $qutils->mobile['groupby'][$module]);	
}

if (isset($qutils->mobile['showtotals'][$module])) {
	if ($qutils->mobile['showtotals'][$module]['list']) $ss->assign('LCHECKED', "checked='true'");
	if ($qutils->mobile['showtotals'][$module]['subpanels']) $ss->assign('SPCHECKED', "checked='true'");
	if ($qutils->mobile['showtotals'][$module]['dashlets']) $ss->assign('DCHECKED', "checked='true'");
}


$ss->assign('LBL_GROUP', $lbl_group);
$ss->assign('group_options', $enum_fields);
$ss->assign('show_group', count($enum_fields)>1);

$ss->display('custom/modules/Administration/tpls/QCRMList.tpl');

?>