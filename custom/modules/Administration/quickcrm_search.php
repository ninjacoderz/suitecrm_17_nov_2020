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
$ss = new Sugar_Smarty();
$ss->assign('module', $module);
$ss->assign('profile', $profile);
$ss->assign('MOD', $mod_strings);
$ss->assign('APP_STRINGS', $app_strings);
$ss->assign('BASIC', $MBmod_strings['LBL_BASIC_SEARCH']);
$ss->assign('AVAILABLE', $MBmod_strings['LBL_ADVANCED_SEARCH']);
$ss->assign('HIDDEN', $MBmod_strings['LBL_HIDDEN']);
$ss->assign('TITLE', $app_list_strings["moduleList"][$module] . ' - ' . $mod_strings['LBL_SEARCH_FIELDS_TITLE'] . ' ' . $profile_name);

if ($profile == '_default') $displayed = $qutils->mobile['search'][$module];
else $displayed = $qutils->mobile['profiles'][$profile]['search'][$module];
if (!$displayed) $displayed = array();

if ($profile == '_default') $basic_search = $qutils->mobile['basic_search'][$module];
else $basic_search = $qutils->mobile['profiles'][$profile]['basic_search'][$module];
if (!$basic_search) $basic_search = array();

$available_fields=array();
foreach ($displayed as $field){
	$available_fields[]=array('field'=>$field,'label'=>$qutils->server_config['list'][$module][$field]['label']);
}
$ss->assign('tabAvailable', $available_fields);

$basic_search_fields=array();
foreach ($basic_search as $field){
	$basic_search_fields[]=array('field'=>$field,'label'=>$qutils->server_config['list'][$module][$field]['label']);
}
$ss->assign('tabbasic_search', $basic_search_fields);

$hidden_fields=array();
foreach ($qutils->server_config['search'][$module] as $field => $data ){
	if (!in_array($field,$displayed) && !in_array($field,$basic_search)){
		$hidden_fields[]=array('field'=>$field,'label'=>$data['label']);
	}
}

$ss->assign('tabHidden', $hidden_fields);

$show_fields = false;
if (isset($sugar_config['quickcrm_show_fieldname'])){
	$show_fields = $sugar_config['quickcrm_show_fieldname'];
}
$ss->assign('showfields', $show_fields);
$ss->assign('showadvanced', true);

$ss->display('custom/modules/Administration/tpls/QCRMSearch.tpl');

?>