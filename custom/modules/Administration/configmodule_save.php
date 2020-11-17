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

require_once 'modules/Configurator/Configurator.php';
include('custom/modules/Administration/QuickCRM_utils.php');
global $qutils;
$qutils=new QUtils();
$qutils->LoadMobileConfig();

function SaveModuleConfig($module){

	global $qutils;
	$arr = array();
	$show_icon=$_POST['chkshow_icon'] == '1';
	$subpanel_only=$_POST['chksubpanel_only'] == '1';
	
	$qutils->mobile['show_icon'][$module]=$show_icon;
	$qutils->mobile['create_subpanel'][$module]=$subpanel_only;

}

SaveModuleConfig($_POST['conf_module']);

$res = $qutils->SaveMobileConfig(true);

@ob_clean();
header('Content-Type: application/json');
echo @json_encode($res);
die();
