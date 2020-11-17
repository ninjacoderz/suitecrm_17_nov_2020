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
global $moduleList;
global $sugar_config;
global $current_language;
global $titles;

ini_set("display_errors", 0);

require_once('custom/modules/Administration/QuickCRM_utils.php');
require_once('include/ytree/Tree.php');
require_once('include/ytree/Node.php');
$qutils=new QUtils();
$qutils->LoadMobileConfig(true); // refresh first open only

$QuickCRM_modules= $qutils->mobile['modules'];

$MBmod_strings=return_module_language($current_language, 'ModuleBuilder');
$titles=array(	'fields'=>$MBmod_strings['LBL_EDITVIEW'],
				'detail'=>$MBmod_strings['LBL_DETAILVIEW'],
				'search'=>$mod_strings['LBL_SEARCH_FIELDS_TITLE'],
				'list'=>$MBmod_strings['LBL_LISTVIEW'],
				'subpanels'=> $mod_strings['LBL_VISIBLE_PANELS']
	);

	$conftree=new Tree('conftree');
	$conftree->set_param('module','Administration');

function AddProfile($module,$profile_id,$profile_name,$node=false){
	global $titles;
	global $mod_strings;
	
	if (!$node) $node =new Node($module."Profiles", $profile_name);
	
	if ($profile_id != '_default'){
	    $nodeDelete =new Node($module."Delete", $mod_strings['LBL_QREMOVE_LAYOUT']);
    	$nodeDelete->set_property("href", "javascript: removeLayout('$module','$profile_id','$profile_name','{$mod_strings['LBL_QREMOVE_LAYOUT']}')");
    	$node->add_node($nodeDelete);       
	}
	
    $nodeEdit =new Node($module."Fields", $titles['fields']);
    $nodeEdit->set_property("href", "javascript: checkModuleType('$module','fields','$profile_id','$profile_name')");
    $node->add_node($nodeEdit);       

	$nodeDisp =new Node($module."Display", $titles['detail']);
    $nodeDisp->set_property("href", "javascript: checkModuleType('$module','detail','$profile_id','$profile_name')");
    $node->add_node($nodeDisp);       

    $nodeSearch =new Node($module."Search", $titles['search']);
    $nodeSearch->set_property("href", "javascript: checkModuleType('$module','search','$profile_id','$profile_name')");
    $node->add_node($nodeSearch);       
/*
    $nodeSearch =new Node($module."PopupSearch", $titles['popupsearch']);
    $nodeSearch->set_property("href", "javascript: checkModuleType('$module','popupsearch','$profile_id','$profile_name')");
    $node->add_node($nodeSearch);       
*/
    $nodeList =new Node($module."List", $titles['list']);
    $nodeList->set_property("href", "javascript: checkModuleType('$module','list','$profile_id','$profile_name')");
    $node->add_node($nodeList);       

	$nodeSubpanels =new Node($module."Subpanel", $titles['subpanels']);
    $nodeSubpanels->set_property("href", "javascript: checkModuleType('$module','subpanels','$profile_id','$profile_name')");
    $node->add_node($nodeSubpanels);
    
    return $node;    
}

foreach($QuickCRM_modules as $module){
	$node=new Node($module, isset($app_list_strings["moduleList"][$module])?$app_list_strings["moduleList"][$module]:$module);
	//if ($qutils->mobile['profilemode'] != 'none'){
	    $node->set_property("href", "javascript: checkModuleType('$module','module','_default')");
	//}
	
	// count existing profiles for module
	$has_profiles = false;
	if ($qutils->mobile['profilemode'] !='none' && isset($qutils->mobile['profiles'])){
		foreach ($qutils->mobile['profiles'] as $profile_id => $profile_values){
			if (isset($profile_values['fields'][$module])){
				$has_profiles = true; 
			}    
		}
	}
	
	if ($has_profiles){
		$node->expanded = true;
		$node->add_node(AddProfile($module,'_default',$mod_strings['LBL_QDEFAULT']));   

		foreach ($qutils->mobile['profiles'] as $profile_id => $profile_values){
			if (isset($profile_values['fields'][$module])){
			    $node->add_node(AddProfile($module,$profile_id,$profile_values['name']));   
			}    
		}
	}
	else{
		AddProfile($module,'_default',$mod_strings['LBL_QDEFAULT'],$node);
	}
    $conftree->add_node($node);       
}

echo $conftree->generate_nodes_array();
?>