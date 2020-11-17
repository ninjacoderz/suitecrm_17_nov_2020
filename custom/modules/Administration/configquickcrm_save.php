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

function DecodePanel($str){
	return str_replace('XX',',',str_replace('_',' ',$str));
}

function SaveShowFieldName($post_value){
	$configurator = new Configurator();
	$configurator->loadConfig(); // it will load existing configuration in config variable of object
	$configurator->config['quickcrm_show_fieldname'] = ($post_value =='1');
	$configurator->saveConfig();

}

function SaveFields($module,$profile_id){

	global $qutils;
	$arr = array();
	$lstfields=$_POST['sel_fields'];
	if ($lstfields!=''){
		$tmp = explode(",",$lstfields); 
		foreach ($tmp as $field){
			// remove q_ (added due to css issues when using field name as id in SuiteCRM 7.9
			if (substr($field, 0, 1) == 'q'){			
				$arr[]=substr($field, 2);
			}
			else{			
				$arr[]='$PAN' . DecodePanel(substr($field, 2));
			}
		}
	}
	$qutils->SetProfileView($module,$profile_id,'fields',$arr);
	if ($_POST['syncCheckbox'] =='1'){
			$qutils->SetProfileView($module,$profile_id,'detail',False);
	}
	else {
			$detail_data = $qutils->GetProfileView($module,$profile_id,'detail');
			if ($detail_data == False){
				// copy current data to detail view
				$qutils->SetProfileView($module,$profile_id,'detail',$arr);
			}
	}
	SaveShowFieldName($_POST['chkshow_field_names']);
}

function SaveDetail($module,$profile_id){

	global $qutils;
	$arr = array();
	$lstfields=$_POST['sel_fields'];
	if ($lstfields!=''){
		$tmp = explode(",",$lstfields); 
		foreach ($tmp as $field){
			if (substr($field, 0, 1) == 'q'){			
				$arr[]=substr($field, 2);
			}
			else{			
				$arr[]='$PAN' . DecodePanel(substr($field, 2));
			}
		}
	}

	$qutils->SetProfileView($module,$profile_id,'detail',$arr);
	SaveShowFieldName($_POST['chkshow_field_names']);
}

function SaveSearch($module,$profile_id){

	global $qutils;
	$arr = array();
	$lstfields=$_POST['sel_fields'];
	if ($lstfields!=''){
		$tmp = explode(",",$lstfields); 
		foreach ($tmp as $field){
			$arr[]=substr($field, 2);
		}
	}

	$qutils->SetProfileView($module,$profile_id,'search',$arr);

	$arr = array();
	$basic=$_POST['extra_fields'];
	if ($basic!=''){
		$tmp = explode(",",$basic); 
		foreach ($tmp as $field){
			$arr[]=substr($field, 2);
		}
	}
	$qutils->SetProfileView($module,$profile_id,'basic_search',$arr);
	SaveShowFieldName($_POST['chkshow_field_names']);
}

function SavePopupSearch($module,$profile_id){

	global $qutils;
	$arr = array();

	$basic=$_POST['extra_fields'];
	if ($basic!=''){
		$tmp = explode(",",$basic); 
		foreach ($tmp as $field){
			$arr[]=substr($field, 2);
		}
	}
	$qutils->SetProfileView($module,$profile_id,'popupsearch',$arr);
	SaveShowFieldName($_POST['chkshow_field_names']);
}

function SaveList($module,$profile_id){

	// Save List fields
	global $qutils;
	$arr = array();
	$lstfields=$_POST['sel_fields'];
	if ($lstfields!=''){
		$tmp = explode(",",$lstfields); 
		foreach ($tmp as $field){
			$arr[]=substr($field, 2);
		}
	}
	$qutils->SetProfileView($module,$profile_id,'list',$arr);

	$arr = array();
	$highlighted=$_POST['extra_fields'];
	if ($highlighted!=''){
		$tmp = explode(",",$highlighted); 
		foreach ($tmp as $field){
			$arr[]=substr($field, 2);
		}
	}
	$qutils->SetProfileView($module,$profile_id,'highlighted',$arr);
	
	$qutils->SetProfileView($module,$profile_id,'marked',$_POST['colorfield']);
	
	$qutils->SetProfileView($module,$profile_id,'groupby',$_POST['groupfield']);


	$showtotals = array('list'=>False, 'dashlets' => False, 'subpanels'=> False);
	if ($_POST['SP'] =='1'){ 
		$showtotals['subpanels'] = True ;
	}
	if ($_POST['LV'] =='1'){
		$showtotals['list'] = True ;
	}
	if ($_POST['DASHLET'] =='1'){
		$showtotals['dashlets'] = True ;
	}
	$qutils->SetProfileView($module,$profile_id,'showtotals',$showtotals);


	$totals = array();
	if (isset($_POST['totalsfield0']) && !empty($_POST['totalsfield0'])){
		if(isset($_POST['totalsfunction0']) && !empty($_POST['totalsfunction0'])){
			$res = $_POST['totalsfunction0'];
		}
		else $res = array('SUM');
		$totals[] = array('field' => $_POST['totalsfield0'], 'fnct' => $res);
	}
	if (isset($_POST['totalsfield1']) && !empty($_POST['totalsfield1'])){
		if(isset($_POST['totalsfunction1']) && !empty($_POST['totalsfunction1'])){
			$res = $_POST['totalsfunction1'];
		}
		else $res = array('SUM');
		$totals[] = array('field' => $_POST['totalsfield1'], 'fnct' => $res);
	}
	if (isset($_POST['totalsfield2']) && !empty($_POST['totalsfield2'])){
		if(isset($_POST['totalsfunction2']) && !empty($_POST['totalsfunction2'])){
			$res = $_POST['totalsfunction2'];
		}
		else $res = array('SUM');
		$totals[] = array('field' => $_POST['totalsfield2'], 'fnct' => $res);
	}
	$qutils->SetProfileView($module,$profile_id,'totals',$totals);
	SaveShowFieldName($_POST['chkshow_field_names']);
}

function SaveSubpanels($module,$profile_id){
	global $qutils;
	$arr = array();
	$lstfields=$_POST['sel_fields'];
	if ($lstfields!=''){
		$arr = explode(",",$lstfields); 
	}
	$qutils->SetProfileView($module,$profile_id,'subpanels',$arr);
}

function SaveGeneral(){
	global $qutils;
}

function SaveModuleView($module){

	global $qutils;
	global $sugar_config, $beanFiles, $beanList;
	// create a new profile
	$new_profile = $_POST['new_profile'];
	$copy_from = $_POST['copy_from'];

	$group_module = $qutils->mobile['profilemode']; // Role or Security group
	if ($sugar_config['sugar_version']<'6.3'){
		require_once($beanFiles[$beanList[$group_module]]);
		$group_bean = new $beanList[$group_module];
		$group_bean->retrieve($new_profile);
	}
	else {
		$group_bean = BeanFactory::getBean($group_module,$new_profile);
	}
	
	if ($copy_from == '_default') $profile = $qutils->mobile;
	else $profile = $qutils->mobile['profiles'][$copy_from];
	
	$views = array('fields','detail','search','list','marked','subpanels','highlighted','basic_search','title_fields');
	
	if (!isset($qutils->mobile['profiles'][$new_profile])){
		$qutils->mobile['profiles'][$new_profile]= array(
			'name' => $group_bean->name,
		);
		foreach($views as $view){
			$qutils->mobile['profiles'][$new_profile][$view] = array();
		}
	}

	foreach($views as $view){
		if (isset($profile[$view][$module])){
			$qutils->mobile['profiles'][$new_profile][$view][$module]= $profile[$view][$module];
		}
	}

}

$profile = $qutils->mobile ;// default_profile
$profile_id = '_default';
if (isset($_POST['profile'])) $profile_id = $_POST['profile'];

if ($_POST['conf_module']!='general') {
	if ($_POST['conf_type']=='fields') {
		SaveFields($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='detail') {
		SaveDetail($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='search') {
		SaveSearch($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='popupsearch') {
		SavePopupSearch($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='list') {
		SaveList($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='subpanels') {
		SaveSubpanels($_POST['conf_module'],$profile_id);
	}
	else if ($_POST['conf_type']=='module') {
		SaveModuleView($_POST['conf_module']);
	}
}
else 
	SaveGeneral();

$res = $qutils->SaveMobileConfig(true);

@ob_clean();
header('Content-Type: application/json');
echo @json_encode($res);
die();
