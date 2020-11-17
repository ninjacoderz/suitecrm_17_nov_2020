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
ini_set('max_execution_time', '500');

require_once('custom/modules/Administration/genProfromSugar.php');
require_once('modules/Administration/Administration.php');



class QUtils {

	var $QuickCRM_modules;
	var $QuickCRM_custom_modules;
	var $QuickCRM_simple_modules;
	var $QuickCRM_ExcludedModules;
	var $QuickCRM_ExcludedFields;
	var $QuickCRMTitleFields;
	var $QuickCRMDetailsFields;
	var $QuickCRMAddressesFields;
	var $QuickCRMExtraFields;
	var $QuickCRMFieldDefs;
	var $QuickCRMDefEdit;
	var $QuickCRMDefSearch;
	var $QuickCRMDefColors;
	var $QuickCRMDefList;
	var $QuickCRMDefSubPanels;
	var $ModuleDef;
	var $mobile; // mobile configuration
	var $server_config; // server configuration
	var $config; // server configuration
	
	public function __construct(){
		include('custom/modules/Administration/quickcrm_std.php');
		$this->QuickCRM_modules = $QuickCRM_modules;
		$this->QuickCRM_ExcludedModules = $QuickCRM_ExcludedModules;
		$this->QuickCRM_ExcludedFields = $QuickCRM_ExcludedFields;
		$this->QuickCRM_simple_modules = $QuickCRM_simple_modules;
		$this->QuickCRMTitleFields = $QuickCRMTitleFields; 
		$this->QuickCRMDetailsFields = $QuickCRMDetailsFields; 
		$this->QuickCRMAddressesFields = $QuickCRMAddressesFields;
		$this->QuickCRMExtraFields = $QuickCRMExtraFields;
		$this->QuickCRMFieldDefs = $QuickCRMFieldDefs;
		$this->QuickCRMDefEdit = $QuickCRMDefEdit;
		$this->QuickCRMDefTotals = $QuickCRMDefTotals;
		$this->QuickCRMDefGroupby = $QuickCRMDefGroupby;
		$this->QuickCRMDefSearch = $QuickCRMDefSearch;
		$this->QuickCRMDefList = $QuickCRMDefList;
		$this->QuickCRMDefSubPanels = $QuickCRMDefSubPanels;
		$this->QuickCRMDefColors = $QuickCRMDefColors;
		$this->QuickCRM_custom_modules = array();
		$this->server_config = array();
		$this->mobile = array();
	}
	
	public static function getMobileUsers(){
		global $db;

	    require('modules/QuickCRM/license/config.php');
    	$qry = "SELECT user_id FROM so_users WHERE shortname = '".$db->quote($outfitters_config['shortname'])."'";


		$result = $db->query($qry);

		$user_ids = array();
        while($row = $db->fetchByAssoc($result))
        {
            $user_ids[] = $row['user_id'];
        }

		return $user_ids;
	}
	
	public static function countMobileUsers(){
		global $db;

	    require('modules/QuickCRM/license/config.php');
        $qry = "SELECT count(*) as the_count FROM so_users WHERE shortname = '".$db->quote($outfitters_config['shortname'])."'";


		
		$licensed_users = 0;
		$result = $db->query($qry);
        $row = $db->fetchByAssoc($result);
        if(!empty($row)) {
            $licensed_users = $row['the_count'];
        }
		return $licensed_users;
	}
	

	function LoadCustomModules(){
		global $dictionary,$beanList,$beanFiles,$app_list_strings,$moduleList;

		$dmod=$this->QuickCRM_modules; // predefined module
		$smod=$this->QuickCRM_simple_modules;
		$allmodules = $moduleList;

		$d = dir('modules');

		while($e = $d->read()){ //collect all studio modules.
			if (in_array ($e,$allmodules) || substr($e, 0, 1) == '.' || !is_dir('modules/' . $e)) continue;
			if (file_exists('modules/' . $e . '/metadata/studio.php'))
			{
				array_push($allmodules, $e);
			}
		}

		// in some configurations, some modules are missing in $moduleList
		if (!in_array ('Employees',$allmodules)) array_push($allmodules,'Employees');
		if (!in_array ('SugarFeed',$allmodules)) array_push($allmodules,'SugarFeed');
		if (!in_array ('ProjectTask',$allmodules)) array_push($allmodules,'ProjectTask');
		$res= array();
	
        foreach ($allmodules as $key => $e) {
			if (in_array ($e,$this->QuickCRM_ExcludedModules)) continue;
			if(isset($GLOBALS [ 'beanList' ][$e]) && file_exists($beanFiles[$beanList[$e]])) // installed modules must also exist in the beanList
			{
				$bean= ($e==='Cases'?'Case':$beanList[$e]);
				VardefManager::loadVardef($e, $bean);
				$dict=$dictionary;

				if (!in_array ($e,$dmod)&&!in_array ($e,$smod)) {
					array_push($res,$e);
					if (isset($dict[$bean]['templates']['person'])) {
						$this->QuickCRMAddressesFields[$e]= array('primary','alt');
					}
					elseif (isset($dict[$bean]['templates']['company']) || $e=='AOS_Quotes' || $e=='AOS_Invoices') {
						$this->QuickCRMAddressesFields[$e]= array('billing','shipping');
					}
				}
			}
		}
		$this->QuickCRM_custom_modules = $res;
	}

    function getUnifiedSearchSettings($lst_mod){
    	global $sugar_config;
    	$users_modules = array();
		if ($sugar_config['sugar_version']>='6.4'){
			require_once('modules/Home/UnifiedSearchAdvanced.php');
	        $usa = new UnifiedSearchAdvanced();
    	    $unified_search_modules_display = $usa->getUnifiedSearchModulesDisplay();
        
			foreach($unified_search_modules_display as $module=>$data) {
				if (!empty($data['visible'])) {
					if (in_array($module,$lst_mod)){
	                	$users_modules[] = $module;
	            	}
            	}
			}
		}
		else {
			$users_modules = array("Accounts","Contacts","Leads","Opportunities","Cases","Project");
		}
		return $users_modules;
    }
    
	function BuildModDef(){
		global $dictionary,$beanList,$sugar_config;
		$smod=$this->QuickCRM_simple_modules;
		$res=array();
	
		foreach($this->mobile['modules'] as $e)
			{
				$bean= ($e==='Cases'?'Case':$beanList[$e]);
				VardefManager::loadVardef($e, $bean);
				$dict=$dictionary;
				if (!in_array ($e,$smod)) {
					$module_template = 'basic';
					if ($e == 'Employees') $module_template = 'person';
					else if (isset($dict[$bean]['templates']['person'])) $module_template = 'person';
					else if (isset($dict[$bean]['templates']['company'])) $module_template = 'company';
					else if (isset($dict[$bean]['templates']['file'])) $module_template = 'file';
					else if (isset($dict[$bean]['templates']['issue'])) $module_template = 'issue';
					else if (isset($dict[$bean]['templates']['sale'])) $module_template = 'sale';
					$res[$e]= array(
						'type'=> $module_template,
						'table'=> $dict[$bean]['table'],
					);
				}
			}
		return $res;
	}

	function BuildModLinks(){
		$res=array('links' => array(), 'subpanels' => array());
		foreach($this->mobile['modules'] as $e)
			{
				$lnks=$this->QgetAvailableLinks($e);
				$res['links'][$e]=$lnks['links'];
				$res['subpanels'][$e]=$lnks['subpanels'];
			}
		return $res;
	}

	function getFieldLabel($module,$field){
		$label = $field;
		if ($this->server_config['fields'][$module][$field]){
			if ($this->server_config['fields'][$module][$field]['label']) $label= $this->server_config['fields'][$module][$field]['label'];
		}
		return $label;
	}
	
	function getSubPanelsLabels(){
		global $current_language,$app_strings,$app_list_strings;
		foreach($this->mobile['modules'] as $module)
			{
				$mod_strings=return_module_language($current_language, $module);
				if (isset($this->server_config['subpanels'][$module]))
				  foreach($this->server_config['subpanels'][$module] as $key=>$value)
					{	
						$lnk=$value['link'];
						$lnk_vname= $this->server_config['links'][$module][$lnk]['vname'];
						$lnk_target_module = $this->server_config['links'][$module][$lnk]['module'];
						if (isset($mod_strings[$lnk_vname])){
								$this->server_config['subpanels'][$module][$key]['tmplabel']=$mod_strings[$lnk_vname];
						}
						elseif (isset($app_strings[$lnk_vname])){
								$this->server_config['subpanels'][$module][$key]['tmplabel']=$app_strings[$lnk_vname];
						}
						else {
								$this->server_config['subpanels'][$module][$key]['tmplabel']=$app_list_strings["moduleList"][$lnk_target_module];
						}
					}
			}
	}

	function getAddressesLabels(){
		global $current_language,$app_strings,$app_list_strings;
		$this->server_config['addresses_label']=array();
		foreach($this->mobile['modules'] as $module)
			{
				if (isset($this->server_config['addresses'][$module])) {
					$labels=return_module_language($current_language, $module);
					$add_labels=array();
					foreach ($this->server_config['addresses'][$module] as $prefix){
						$add_labels[$prefix]=(isset($labels['LBL_'.strtoupper($prefix).'_ADDRESS'])?$labels['LBL_'.strtoupper($prefix).'_ADDRESS']:$prefix);
					}
					$this->server_config['addresses_label'][$module]=$add_labels;
				}
			}
	}


	function isLinkableModule($module){
		return in_array($module,$this->mobile['modules']) 
		|| in_array($module,$this->QuickCRM_simple_modules);
	}
	
	function QgetPresetModuleFields() {
		$mod_fields_arr = array();
		foreach ($this->mobile['modules'] as $module){
//			if (isset($this->QuickCRMDetailsFields[$module]))
//				$mod_fields_arr[$module]=array_merge($this->mobile['def_title_fields'][$module],$this->QuickCRMDetailsFields[$module]);
//			else
				$mod_fields_arr[$module]=$this->mobile['def_title_fields'][$module];
		}
		return $mod_fields_arr;
	}

	function QgetPresetSearch() {
		return $this->QuickCRMDefSearch;
	}
	
	function QgetPresetList() {
		return $this->QuickCRMDefList;
	}
	
	function QgetPresetSubpanels() {
		return $this->QuickCRMDefSubPanels;
	}
	
    function QgetAvailableLinks($moduleName) {
	// allowed display fields
		global $beanFiles, $beanList;
		
		$enabled_modules=array_merge($this->mobile['modules'],$this->QuickCRM_simple_modules);
		$excluded= array (
			'Contacts' => array (
						'tasks_parent',
						'notes_parent',
						'accounts',
						'user_sync'
					),
			'Leads' => array (
						'reportees',
					),
			'Opportunities' => array (
						'accounts',
					),
			'Tasks' => array (
						'contact_parent',
					),
			'Employees' => array (
						'contacts_sync',
						'email_addresses',
						'email_addresses_primary',
						'eapm',
						'calls',
						'meetings',
						'emails_users',
						'oauth_tokens',
					),
		);
		
		// built list of links
		$links = array(); 
		$subpanels = array(); 
		if (file_exists($beanFiles[$beanList[$moduleName]])){
			$nodeModule = Q_new_bean($moduleName);
			$nodeModule->load_relationships();
			$linked_fields=$nodeModule->get_linked_fields();

			// list of target modules
			// if a module appears once, module name will be used, else link name will be
			$target_modules = array();
			foreach ($enabled_modules as $enabled) $target_modules[$enabled]=0;
			foreach($linked_fields as $linkedField => $linkedFieldData)
			{
				if (!property_exists($nodeModule,$linkedField)){
					continue;
				}
				$rel_type=$nodeModule->$linkedField->_relationship->relationship_type;
				if ($rel_type=='') $rel_type=$linkedFieldData['type'];
				$lhs_module=$nodeModule->$linkedField->_relationship->lhs_module;
				$rhs_module=$nodeModule->$linkedField->_relationship->rhs_module;
				$target_module = $lhs_module==$moduleName ? $rhs_module : $lhs_module;
				if ($target_module=='' && isset($linkedFieldData['module'])) $target_module= $linkedFieldData['module'];
				if (isset($excluded[$moduleName]) && in_array($linkedField,$excluded[$moduleName])) {
					continue;
				}
				if (in_array($target_module,$enabled_modules) &&
					(
						$rel_type == 'many-to-many'
						||
						($rel_type == 'one-to-many' && 
							(
								(($lhs_module==$moduleName || strtoupper($rhs_module)!=strtoupper($moduleName))
								&& ($lhs_module!= $rhs_module || !isset($linkedFieldData['side']) || $linkedFieldData['side']!='right'))
							||
								($rhs_module==''&& $target_module!=''&& $target_module!=$moduleName)
								
							)
						)
					)
				){
					$target_modules[$target_module]++;
					if (!isset($linkedFieldData['vname'])) $linkedFieldData['vname'] = "";
					$links[$linkedField] = array('module'=>$target_module,'vname'=>$linkedFieldData['vname'],'label'=>$linkedFieldData['vname']); 
					if (isset($linkedFieldData['id_name'])){
						$links[$linkedField]['id_name']=$linkedFieldData['id_name'];
					}
					$subpanels[$linkedField] = array('link'=>$linkedField,'tmplabel'=>'','customlabel'=>""); 
				}
			}
			// Rename links if necessary (use module name if only one link to that module)
			foreach ($enabled_modules as $enabled) {
				if ($target_modules[$enabled]==1){
					foreach ($links as $lnk=>$lnkvalues) {
						if ($lnkvalues['module']==$enabled 
							&& $lnkvalues['module']!=$moduleName // do not rename links to same module (members, direct reports, ...)
							// && // do not rename links made with Studio
							) {
							$links[$lnk]['label']= $lnkvalues['module'];
						}
					}
				}
			}
		}
		else {
			$GLOBALS['log']->fatal("[QuickCRM] Bean file not found for module $moduleName");
		}
		return array('links'=>$links,'subpanels'=>$subpanels);
	}
	
    function QgetMarkableFields($moduleName,$labels) {
	// allowed colored or marked fields
		global $beanFiles, $beanList;
		global $app_strings;
		$list = array();
		$possible= array('bool','boolean','enum','radioenum','dynamicenum');
		if (file_exists($beanFiles[$beanList[$moduleName]])) {
			$nodeModule = Q_new_bean($moduleName);
			foreach($nodeModule->field_name_map as $field_name => $field_defs)
			{
				if (isset($this->QuickCRM_ExcludedFields[$moduleName])){
					if (in_array($field_defs['name'],$this->QuickCRM_ExcludedFields[$moduleName])) continue;
				}
				$source=(isset($field_defs['source'])?$field_defs['source']:"");
				if(in_array($field_defs['type'], $possible) && ($source != 'non-db') && ($field_name != 'deleted'))
				{
					$list[$field_defs['name']] = array(
									'type' => $field_defs['type'],
									'label' => preg_replace("/'/","&#039;",isset($labels[$field_defs['vname']])?$labels[$field_defs['vname']]:(isset($app_strings[$field_defs['vname']])?$app_strings[$field_defs['vname']]:$field_defs['vname'])),
							);
				}
			}
		}
		return $list;
	}
	
    function QgetFields($moduleName,$labels,$excluded_types,$excluded_names = array()) {
	// allowed display fields
		global $beanFiles, $beanList;
		global $app_strings;
		global $current_language;
		$list = array();
		$excluded_names = array_merge($excluded_names,array('id','assigned_user_id','assigned_user_name','deleted'));
		$non_db_fields_types = array('html','Drawing','phone','file');
		$non_db_fields_names = array('name','email1','currency_id','update_text','internal','file','description');
		if (file_exists($beanFiles[$beanList[$moduleName]])) {
			$nodeModule = Q_new_bean($moduleName);
			if ($moduleName == 'Calls' || $moduleName == 'Meetings'){
				$nodeModule->field_name_map['reminder_time']['vname'] = $nodeModule->field_name_map['reminder_checked']['vname'];
			}
			foreach($nodeModule->field_name_map as $field_name => $field_defs)
			{
				if (isset($this->QuickCRMFieldDefs[$moduleName]) && isset($this->QuickCRMFieldDefs[$moduleName][$field_name])){
						$type = $this->QuickCRMFieldDefs[$moduleName][$field_name]['type'];
						$label = $this->QuickCRMFieldDefs[$moduleName][$field_name]['label'];
						$label = preg_replace("/'/","&#039;",isset($labels[$label])?$labels[$label]:(isset($app_strings[$label])?$app_strings[$label]:$label));
						$list[$field_name] = array(
							'type' => $type,
							'label' => $label,
						);
						continue;
				}

				if (in_array($field_defs['name'],$excluded_names)) continue;
				if (isset($this->QuickCRM_ExcludedFields[$moduleName])){
					if (in_array($field_defs['name'],$this->QuickCRM_ExcludedFields[$moduleName])) continue;
				}
				$source=(isset($field_defs['source'])?$field_defs['source']:"");
				$mod=(isset($field_defs['module'])?$field_defs['module']:"");
				$dbType=(isset($field_defs['dbType'])?$field_defs['dbType']:"");
				if (!isset($field_defs['vname'])) {
					$field_defs['vname'] = $field_defs['name'];
				}
				if((!in_array($field_defs['type'],$excluded_types)
					&& ($source != 'non-db'
					// PRO ONLY
						|| ($source == 'non-db'
							&& (in_array ($field_defs['type'],$non_db_fields_types)
								|| in_array ($field_defs['name'],$non_db_fields_names)
								|| (isset($field_defs['function']) && (($field_defs['name'] == 'aop_case_updates_threaded') || ($field_defs['name'] == 'reschedule_history')))
								)
							)
						)
					)
					||
					($field_defs['name'] == 'email1')
					||
					($field_defs['name'] == 'currency_id')
					||
					(($field_defs['type'] == 'parent') && isset($field_defs['parent_type']) && isset($field_defs['options']))
					||
					($field_defs['type'] == 'relate' && $this->isLinkableModule($mod) && (!isset($field_defs['link']) || !isset($field_defs['link_type'])) && $dbType != 'id')
					)
				{
					$list[$field_defs['name']] = array(
									'type' => $field_defs['type'],
									'label' => preg_replace("/'/","&#039;",isset($labels[$field_defs['vname']])?$labels[$field_defs['vname']]:(isset($app_strings[$field_defs['vname']])?$app_strings[$field_defs['vname']]:$field_defs['vname'])),
					);

				}
			}
			$list['assigned_user_name'] = array(
				'type' => 'relate',
				'label' => isset($labels['LBL_ASSIGNED_TO_NAME'])?$labels['LBL_ASSIGNED_TO_NAME']:$app_strings['LBL_ASSIGNED_TO_NAME'],
			);
		}
		return $list;
	}
	
    function QgetDisplayFields($moduleName,$labels) {
		global $app_strings, $QuickCRM_AddressDef,$QuickCRM_google_AddressDef;
		$excluded = array();
		if ($this->mobile['mod_def'][$moduleName]['type']=='person') $excluded = array('name');
		$list=$this->QgetFields($moduleName,$labels,array('link','id','parent_type','assigned_user_name'),$excluded);
		if (isset($this->server_config['addresses'][$moduleName])){
			foreach($this->server_config['addresses'][$moduleName] as $prefix){
				
				foreach ($QuickCRM_AddressDef as $suffix) {
					//unset ($list[$prefix.'_address_'.$suffix]);
				}
				$list['$ADD'.$prefix] = array(
									'type' => 'Address',
									'label' => (isset($labels['LBL_'.strtoupper($prefix).'_ADDRESS'])?$labels['LBL_'.strtoupper($prefix).'_ADDRESS']:$prefix) . '(' . $app_strings['LBL_LINK_ALL'] .')',
					);
			}
		}
		return $list;
	}
	
    function QgetListFields($moduleName,$labels) {
		$list=$this->QgetFields($moduleName,$labels,array('link','id','function','iframe','parent_type','assigned_user_name','Drawing'),array('currency_id'));
		return $list;
	}
	
    function QgetSearchFields($moduleName,$labels) {
	// allowed search fields
		global $beanFiles, $beanList, $app_strings, $sugar_config;
		
		$list = array();
		$excluded_types = array('link','id','function','file','iframe','relate','parent_type','assigned_user_name');
		$allow_nondb_relate = (!isset($sugar_config['quickcrm_norelatesearch']) || $sugar_config['quickcrm_norelatesearch']==false); 
		$excluded_names = array('id','assigned_user_id','assigned_user_name','currency_id','deleted');
		if (file_exists($beanFiles[$beanList[$moduleName]])){
			$nodeModule = Q_new_bean($moduleName);
			$map=$nodeModule->field_name_map;
			foreach($nodeModule->field_name_map as $field_name => $field_defs)
			{
				if (in_array($field_defs['name'],$excluded_names)) continue;
				if (isset($this->QuickCRM_ExcludedFields[$moduleName])){
					if (in_array($field_defs['name'],$this->QuickCRM_ExcludedFields[$moduleName])) continue;
				}
				$source=(isset($field_defs['source'])?$field_defs['source']:"");
				$mod=(isset($field_defs['module'])?$field_defs['module']:"");
				if (!isset($field_defs['vname'])) {
					$field_defs['vname'] = $field_defs['name'];
				}
				if ($field_defs['type'] == 'relate' && $source=='non-db'){
					if (isset($field_defs['id_name'])){
						if (!isset($map[$field_defs['id_name']]['source']) || $map[$field_defs['id_name']]['source'] != 'non-db'){
							$source='';
						}
					}
				}
				if((!in_array($field_defs['type'],$excluded_types)
					&& ($source != 'non-db'
						|| ($field_defs['type'] == 'html')
						)
					)
				|| 
					($field_defs['type'] == 'relate' && ($allow_nondb_relate || $source != 'non-db') && (!isset($field_defs['dbType']) || $field_defs['dbType'] !='id') && $this->isLinkableModule($mod))
				||
					(($field_defs['type'] == 'parent') && isset($field_defs['parent_type']) && isset($field_defs['options']))
				||
					($field_defs['name'] == 'email1')
				||
					($field_defs['name'] == 'name')
				)
					
				{
					$list[$field_defs['name']] = array(
									'type' => $field_defs['type'],
									'label' => preg_replace("/'/","&#039;",isset($labels[$field_defs['vname']])?$labels[$field_defs['vname']]:(isset($app_strings[$field_defs['vname']])?$app_strings[$field_defs['vname']]:$field_defs['vname'])),
							);
				}
			}
			$list['assigned_user_name'] = array(
					'type' => 'relate',
					'label' => isset($labels['LBL_ASSIGNED_TO_NAME'])?$labels['LBL_ASSIGNED_TO_NAME']:$app_strings['LBL_ASSIGNED_TO_NAME'],
				);
		}
		return $list;
	}

	function SetProfileView($module,$profile_id,$view,$data){
		if ($profile_id == '_default') {
			$this->mobile[$view][$module] = $data;
		}
		else {
			if (!isset($this->mobile['profiles'][$profile_id][$view])) {
				$this->mobile['profiles'][$profile_id][$view] = array();
			}
			$this->mobile['profiles'][$profile_id][$view][$module] = $data;
		}
	}

	function GetProfileView($module,$profile_id,$view){
		if ($profile_id == '_default') {
			return $this->mobile[$view][$module];
		}
		else {
			if (!isset($this->mobile['profiles'][$profile_id][$view]) || !isset($this->mobile['profiles'][$profile_id][$view][$module])) {
				return False;
			}
			return $this->mobile['profiles'][$profile_id][$view][$module];
		}
	}

	function UpdateModules(){
		global $beanFiles, $beanList,$sugar_config;
		
		// remove suppressed modules from module list
		$newmodule_list=array();
		foreach ($this->mobile['modules'] as $module){
			if (file_exists($beanFiles[$beanList[$module]])){
				array_push($newmodule_list,$module);
			}
		}
		$this->mobile['modules']=$newmodule_list;
		
		$this->mobile['mod_def']= $this->BuildModDef();
		$res=$this->BuildModLinks();
		
		// add to config defined in earlier versions
		$this->mobile['links']=$res['links'];
		if (!isset($this->mobile['list'])){
			$this->mobile['list']=array();
		}
		if (!isset($this->mobile['detail'])){
			$this->mobile['detail']=array();
		}
		if (!isset($this->mobile['share_search'])){
			$this->mobile['share_search']='All';
		}

		if (!isset($this->mobile['profilemode'])){
			$this->mobile['profilemode']='none';
		}
		if (!isset($this->mobile['profiles'])){
			$this->mobile['profiles']=array();
		}

		if (!isset($this->mobile['trackermode'])){
			$this->mobile['trackermode']='none';
		}
		if (!isset($this->mobile['trackerfreq'])){
			$this->mobile['trackerfreq']=30;
		}
		if (!isset($this->mobile['trackergroup'])){
			$this->mobile['trackergroup']='';
		}
		if (!isset($this->mobile['trackerrole'])){
			$this->mobile['trackerrole']='';
		}
		if (!isset($this->mobile['trackerviewer'])){
			$this->mobile['trackerviewer']='';
		}
		
		if (!isset($this->mobile['native_cal'])){
			$this->mobile['native_cal']=true;
		}

		if (!isset($this->mobile['force_lock'])){
			$this->mobile['force_lock']=false;
		}

		if (!isset($this->mobile['documents_sync'])){
			$this->mobile['documents_sync']=true;
		}
		
		if (!isset($this->mobile['audio_notes'])){
			$this->mobile['audio_notes']=true;
		}
		
		if (!isset($this->mobile['languages'])){
			$this->mobile['languages']='all';
		}
		
		if (!isset($this->mobile['offline_max_days'])){
			$this->mobile['offline_max_days']=7;
		}
		
		$this->mobile['def_title_fields']=array();
		$this->mobile['def_details_fields']=array();
		if (!isset($this->mobile['groupby'])) $this->mobile['groupby']=array();
		if (!isset($this->mobile['showtotals'])) $this->mobile['showtotals']=array();
		if (!isset($this->mobile['marked'])) $this->mobile['marked']=array();
		if (!isset($this->mobile['show_icon'])) $this->mobile['show_icon']=array();
		if (!isset($this->mobile['show_module'])) $this->mobile['show_module']=array();
		if (!isset($this->mobile['create_subpanel'])) $this->mobile['create_subpanel']=array();
		if (!isset($this->mobile['totals'])) $this->mobile['totals']=array();
		if (!isset($this->mobile['rowspersubpanel'])) $this->mobile['rowspersubpanel']=5;
		if (!isset($this->mobile['rowsperdashlet'])) $this->mobile['rowsperdashlet']=5;
		foreach ($this->mobile['modules'] as $module){
			// remove from modules subpanels unavailable subpanels and add default fields for new modules
			if (isset($this->QuickCRMTitleFields[$module])) $this->mobile['def_title_fields'][$module] = $this->QuickCRMTitleFields[$module];
			if (isset($this->QuickCRMDetailsFields[$module])) $this->mobile['def_details_fields'][$module] = $this->QuickCRMDetailsFields[$module];
			if (!isset($this->mobile['fields'][$module])){
				if (isset($this->QuickCRMDetailsFields[$module])){
					if (isset($this->QuickCRMDefEdit[$module])){
						$this->mobile['fields'][$module] = $this->QuickCRMDefEdit[$module];
						$this->mobile['detail'][$module] = $this->QuickCRMDetailsFields[$module];
					}
					else
						$this->mobile['fields'][$module] = $this->QuickCRMDetailsFields[$module];
				}
				else {
					if ($this->mobile['mod_def'][$module]['type']=='person') {
						$this->mobile['fields'][$module]=array('first_name','last_name');
					}
					else if ($module == 'Documents' || $this->mobile['mod_def'][$module]['type']=='file') {
						$this->mobile['fields'][$module]=array('document_name','filename');
					}
					else {
						$this->mobile['fields'][$module]=array('name');
					}
				}
			}
			else {
				if (!isset($this->mobile['version'])){
					// prepend name fields when upgrading from old version
					$has_details = isset($this->mobile['detail'][$module]) && is_array($this->mobile['detail'][$module]);
					if ($this->mobile['mod_def'][$module]['type']=='person') {
						array_unshift ($this->mobile['fields'][$module],'first_name','last_name');
						if ($has_details){
							array_unshift ($this->mobile['detail'][$module],'first_name','last_name');
						}
					}
					else if ($module == 'Documents' || $this->mobile['mod_def'][$module]['type']=='file') {
						array_unshift ($this->mobile['fields'][$module],'document_name');
						if ($has_details){
							array_unshift ($this->mobile['detail'][$module],'document_name');
						}
					}
					else {
						array_unshift ($this->mobile['fields'][$module],'name');
						if ($has_details){
							array_unshift ($this->mobile['detail'][$module],'name');
						}
					}
				}
			}
			if (!isset($this->mobile['list'][$module])){
				if (isset($this->QuickCRMDefList[$module])){
					$this->mobile['list'][$module] = $this->QuickCRMDefList[$module];
				}
				else $this->mobile['list'][$module]=array();
			}
			if (!isset($this->mobile['totals'][$module])){
				if (isset($this->QuickCRMDefTotals[$module])){
					$this->mobile['totals'][$module] = $this->QuickCRMDefTotals[$module];
				}
			}
			if (!isset($this->mobile['groupby'][$module])){
				if (isset($this->QuickCRMDefGroupby[$module])){
					$this->mobile['groupby'][$module] = $this->QuickCRMDefGroupby[$module];
				}
			}

			if (!isset($this->mobile['showtotals'][$module])){
				$this->mobile['showtotals'][$module] = array('list'=>True, 'dashlets' => True, 'subpanels'=> True);
			}

			$subpanels=array();
			if (isset($this->mobile['subpanels'][$module]))
			  foreach ($this->mobile['subpanels'][$module] as $subpanel){
				if (isset($res['links'][$module][$subpanel])) {
					$subpanels[]=$subpanel;
				}
			}
			$this->mobile['subpanels'][$module]=$subpanels; // TODO CHECK ???
			
			if (!isset($this->mobile['def_title_fields'][$module])){
				if ($this->mobile['mod_def'][$module]['type']=='person') {
					$this->mobile['def_title_fields'][$module]= array('first_name','last_name');
				}
				elseif ($this->mobile['mod_def'][$module]['type']=='file') {
					$this->mobile['def_title_fields'][$module]= array('document_name');
				}
				else {
					$this->mobile['def_title_fields'][$module]= array('name');
				}
			}

			if (!isset($this->mobile['basic_search'][$module])){
				if ($module == 'Documents' || $this->mobile['mod_def'][$module]['type']=='file') {
					$this->mobile['basic_search'][$module]= array('document_name');
				}
				else if ($module == 'Emails') {
					$this->mobile['basic_search'][$module]= array('type','name');
				}
				else {
					$this->mobile['basic_search'][$module]= array('name');
				}
			}
			if (!isset($this->mobile['highlighted'][$module])){
				if ($this->mobile['mod_def'][$module]['type']=='file') {
					$this->mobile['highlighted'][$module]= array('document_name');
				}
				else {
					$this->mobile['highlighted'][$module]= array('name');
				}
			}
		}
		$this->mobile['version'] = '6.0';
	}

	function ConvertConfig(){
		global $sugar_config;
		$proKey='QuickCRM_mobileconfig';
		$trialKey='QuickCRM_trialmobileconfig';

		if (!file_exists('custom/QuickCRM/mobile.defs.js')){
			$administration = new Administration();
			$administration->retrieveSettings('QuickCRM',true);	
			if (!isset($administration->settings[$proKey]) || $administration->settings[$proKey]=='') {
				if (isset($administration->settings[$trialKey]) && $administration->settings[$trialKey]!='') {
					$administration->saveSetting('QuickCRM', 'mobileconfig', $administration->settings[$trialKey]);
					//$administration->saveSetting('QuickCRM', 'trialmobileconfig', '');
				}
			}
		}	
	}
	
	function LoadMobileConfig($refresh=true){
		global $sugar_config, $moduleList, $beanFiles, $beanList;
		
		if (isset($this->mobile['modules'])){ // Already loaded
			return;
		}
		
		$json = getJSONobj();
		
		$configuration_found = false;
		if (file_exists('custom/QuickCRM/mobile.defs.js')){
			$str = file_get_contents('custom/QuickCRM/mobile.defs.js');
			$this->mobile = $json->decode($str);
			$configuration_found = true;
		}
		else {
			$administration = new Administration();
			$administration->retrieveSettings('QuickCRM',true);

			// convert old configuration
			$confKey='QuickCRM_'.($sugar_config['quickcrm_trial'] != false?'trial':'').'mobileconfig';
			if (isset($administration->settings[$confKey]) && $administration->settings[$confKey]!='') {
				$configuration_found = true;
				// configuration prior to 5.9.5
				$this->mobile = $json->decode(base64_decode($administration->settings[$confKey]));
			}
		}	
		if ($configuration_found){
				$this->UpdateModules();
				return;
		}
			
		// Build default configuration
		
		// remove unavailable modules

		$newmodule_list=array();
		foreach ($this->QuickCRM_modules as $module){
			if (file_exists($beanFiles[$beanList[$module]])){
					array_push($newmodule_list,$module);
			}
		}

		$this->mobile = array(
			'def_title_fields' => $this->QuickCRMTitleFields, // predefined title fields
			'def_details_fields' => $this->QuickCRMDetailsFields, // predefined detail fields
			// customizations
			'modules' => $newmodule_list, // selected
			'fields' => array(), // additional fields for display
			'detail' => array(), // additional fields for display
			'addresses' => $this->QuickCRMAddressesFields, // displayed address fields
			'search' => array(), // search fields
			'basic_search' => array(), // search fields
			'highlighted' => array(), // list fields
			'list' => array(), // list fields
			'marked' => array(), // list fields
			'show_icon' => array(), // show/hide icon on Home page
			'show_module' => array(), // show/hide icon on Home page + all modules + List/Search page
			'create_subpanel' => array(), // create from subpanel only
			'totals' => array(), // list fields
			'groupby' => array(), // list fields
			'showtotals' => array(), // list fields
			'subpanels' => array(), // subpanels (ordered)
			'offline_max_days' => '7',
			'rowsperpage' => '20',
			'rowspersubpanel' => '5',
			'rowsperdashlet' => '5',
			'groupusers' => false,
			'profilemode' => 'none',
			'profiles' => array(), // specific views per group
			'trackermode' => 'none',
			'trackerfreq' => 30,
			'trackergroup' => '',
			'trackerrole' => '',
			'trackerviewer' => '',
			'share_search' => 'All',
			'native_cal' => true,
			'force_lock' => false,
			'documents_sync' => true,
			'audio_notes' => true,
			'languages' => 'all',
			
			'mod_def' => array(), // Properties of selected modules
			'links' => array(), // available links for selected modules
		);
		if (file_exists('custom/QuickCRM/views.php')){
			include ('custom/QuickCRM/views.php');
		}
		$addfields=array();
		$this->mobile['mod_def']= $this->BuildModDef();
		$res=$this->BuildModLinks();
		$this->mobile['links']=$res['links'];
		$ModuleDef=$this->mobile['mod_def'];
		$this->mobile['version']= "6.0";
		foreach($this->mobile['modules'] as $module)
			{

				if (!isset($this->mobile['def_title_fields'][$module])){
					if ($ModuleDef[$module]['type']=='person') {
						$this->mobile['def_title_fields'][$module]= array('first_name','last_name');
					}
					else {
						$this->mobile['def_title_fields'][$module]= array('name');
					}
				}
				
				if (!isset($this->mobile['basic_search'][$module])){
					if ($module == 'Documents' || $ModuleDef[$module]['type']=='file') {
						$this->mobile['basic_search'][$module]= array('document_name');
					}
					else {
						$this->mobile['basic_search'][$module]= array('name');
					}
				}

				if (!isset($this->mobile['highlighted'][$module])){
					if ($ModuleDef[$module]['type']=='file') {
						$this->mobile['highlighted'][$module]= array('document_name');
					}
					else {
						$this->mobile['highlighted'][$module]= array('name');
					}
				}

				$subpanels=array();
				if (isset($this->QuickCRMDefSubPanels[$module])){
					foreach ($this->QuickCRMDefSubPanels[$module] as $lnk){
						$subpanels[]=$lnk;
					}				
				}
				else {
					//foreach ($res['subpanels'][$module] as $lnk){
					//	if ($lnk['link'] == 'notes') $subpanels[]=$lnk['link'];
					//}				
				}
				$this->mobile['subpanels'][$module]=$subpanels;

				$searchfields=array();
				if (isset($this->QuickCRMDefSearch[$module]) && !isset($this->mobile['search'][$module])){
					foreach ($this->QuickCRMDefSearch[$module] as $fld){
						$searchfields[]=$fld;
					}				
				}
				$this->mobile['search'][$module]=$searchfields;

				$listfields=array();
				if (isset($this->QuickCRMDefList[$module]) && !isset($this->mobile['list'][$module])){
					foreach ($this->QuickCRMDefList[$module] as $fld){
						$listfields[]=$fld;
					}				
				}
				$this->mobile['list'][$module]=$listfields;

				if (isset($this->QuickCRMDefColors[$module])){
					$this->mobile['marked'][$module] = $this->QuickCRMDefColors[$module];
				}
				if (isset($this->QuickCRMDefTotals[$module])){
					$this->mobile['totals'][$module] = $this->QuickCRMDefTotals[$module];
				}
				if (isset($this->QuickCRMDefGroupby[$module])){
					$this->mobile['groupby'][$module] = $this->QuickCRMDefGroupby[$module];
				}
				$this->mobile['showtotals'][$module] = array('list'=>True, 'dashlets' => True, 'subpanels'=> True);

				if (isset($this->QuickCRMDetailsFields[$module])) {
						if (isset($this->QuickCRMDefEdit[$module])){
							$this->mobile['fields'][$module] = $this->QuickCRMDefEdit[$module];
							$this->mobile['detail'][$module] = $this->QuickCRMDetailsFields[$module];
						}
						else{
							$this->mobile['fields'][$module] = $this->QuickCRMDetailsFields[$module];
						}
				}
				else{
					if ($ModuleDef[$module]['type']=='person') {
						$this->mobile['fields'][$module]=array('first_name','last_name');
					}
					else if ($module == 'Documents' || $ModuleDef[$module]['type']=='file') {
						$this->mobile['fields'][$module]=array('document_name');
					}
					else {
						$this->mobile['fields'][$module]=array('name');
					}
				}
					
				if (isset($this->mobile['addresses'][$module])) {
					foreach($this->mobile['addresses'][$module] as $key=>$fld){
						array_push($this->mobile['fields'][$module],'$ADD'.$fld);
					}
				}
			
				if (isset($addfields[$module])) {
					$this->mobile['fields'][$module]=array_merge($this->mobile['fields'][$module],$addfields[$module]);
				}
				
			}
			
			$this->UpdateModules();

	}

	function LoadServerConfig($refresh=false){
		global $current_language, $sugar_config;
		
		$json = getJSONobj();
		$this->server_config = array(
			'modules' => array(), // Available modules
			'fields' => array(), // Available fields for display
			'search' => array(), // Available fields for search
			'list' => array(), // Available fields for list
			'marked' => array(), // Available fields for marked fields (colors)
			'addresses' => array(), // Available fields for addresses
			'links' => array(), // Available fields for links for selected modules
			'subpanels' => array(), // Available fields for addresses
		);
		
		$this->LoadCustomModules();
		$this->server_config['modules'] = array_merge($this->QuickCRM_modules,$this->QuickCRM_custom_modules);

		// if mobile config not yet loaded, do it now
		if (!isset($this->mobile['modules'])){
			$this->LoadMobileConfig($refresh);
		}

		$lst_mod=$this->mobile['modules'];
		$ModuleDef=$this->mobile['mod_def'];		

		foreach($lst_mod as $module){
			$labels=return_module_language($current_language, $module);
			if ($ModuleDef[$module]['type']=='person' && $module != 'Employees') {
				$this->server_config['addresses'][$module]= array('primary','alt');
			}
			elseif ($ModuleDef[$module]['type']=='company'  || $module=='AOS_Quotes' || $module=='AOS_Invoices') {
				$this->server_config['addresses'][$module]= array('billing','shipping');
			}
			$this->server_config['fields'][$module] = $this->QgetDisplayFields($module,$labels);
			$this->server_config['search'][$module] = $this->QgetSearchFields($module,$labels);
			$this->server_config['list'][$module] = $this->QgetListFields($module,$labels);
			$this->server_config['marked'][$module] = $this->QgetMarkableFields($module,$labels);
			$lnks=$this->QgetAvailableLinks($module);
			$this->server_config['links'][$module]=$lnks['links'];
			$this->server_config['subpanels'][$module]=$lnks['subpanels'];
		}
	}

	function getJSConfig(){
		global $sugar_config,$QuickCRM_AddressDef,$QuickCRM_google_AddressDef,$moduleList;

		$json = getJSONobj();
	
		$str="//V3.0\n";
		$enabled= $this->mobile['modules'];
		
		if (file_exists('custom/QuickCRM/quickcrm_init.js')){
			$str .= file_get_contents('custom/QuickCRM/quickcrm_init.js',true);
		}
		$str .= "QCRM.UnifiedSearch=['" . implode("','",$this->getUnifiedSearchSettings($enabled)) . "'];";
		$str.= "QCRM.users_dropdown=false;\n";
		$str.= "QCRM.share_search=" . ((isset ($this->mobile['share_search'])  && $this->mobile['share_search'] != 'All') ? $this->mobile['share_search']:"'All'"). ";\n";
		$str.= "QCRM.native_cal=" . ((isset ($this->mobile['native_cal'])  && !$this->mobile['native_cal']) ? "false":"true"). ";\n";
		if (isset($sugar_config['suitecrm_version'])){
			$str.= "QCRM.AOS_show_image=" . ((!isset ($this->mobile['productimage'])  || $this->mobile['productimage']) ? "true":"false"). ";\n";
		}
		$str.= "QCRM.forceLock=" . ((!isset ($this->mobile['force_lock'])  || !$this->mobile['force_lock']) ? "false":"true"). ";\n";
		$str.= "QCRM.AudioNotes=" . ((!isset ($this->mobile['audio_notes'])  || $this->mobile['audio_notes']) ? "true":"false"). ";\n";
		if (in_array ('Documents',$enabled) && isset($this->mobile['documents_sync']) && !$this->mobile['documents_sync']){
			$str.= "Beans.Documents.SyncOptions={sync:'None',max:true};\n";
		}
		if (isset($sugar_config['quickcrm_mode']) && ($sugar_config['quickcrm_mode'] == "mobile" || $sugar_config['quickcrm_mode'] == "tablet")){
			$str.= "QCRM.mode='" . $sugar_config['quickcrm_mode'] . "';\n";
		}
		
        $disabled = array();
		if (!isset($sugar_config['quickcrm_all_tabs']) || !$sugar_config['quickcrm_all_tabs']){	
			require_once('modules/MySettings/TabController.php');
        
    	    $controller = new TabController();
        	$tabs = $controller->get_tabs_system();
        	foreach ($tabs[1] as $key=>$value)
        	{
            	$disabled[] = $key;
        	}
        }

		$current_config = $this->mobile;
		
		$str.="QCRM.enableBeans(['" . implode("','",$enabled) . "']);\n";
		if (!isset($current_config['detail'])) $current_config['detail'] = array();
		foreach($enabled as $module){
			
			if (in_array($module,$disabled) || (isset($current_config['show_module'][$module]) && !$current_config['show_module'][$module])){
				$str.="Beans['$module'].ShowTab = false;\n";
			}
			if (isset($current_config['show_icon'][$module]) && !$current_config['show_icon'][$module]){
					$str.="Beans['$module'].HomeIcon = false;\n";
			}
			if (isset($current_config['create_subpanel'][$module]) && $current_config['create_subpanel'][$module]){
				$str.="Beans['$module'].CreateFromSubpanelOnly = true;\n";
			}
		
			if (count($current_config['fields'][$module]) >0){
				$str.="Beans['$module'].AdditionalFields = ['" . implode("','",$current_config['fields'][$module]) . "'];\n";
			}
			if (isset($current_config['detail'][$module]) && $current_config['detail'][$module] != False && (count($current_config['detail'][$module]) >0)){
				$str.="Beans['$module'].DetailFields = ['" . implode("','",$current_config['detail'][$module]) . "'];\n";
			}
			
			if (isset($current_config['search'][$module]) && (count($current_config['search'][$module]) >0)){
				$str.="Beans['$module'].SearchFields = ['" . implode("','",$current_config['search'][$module]) . "'];\n";
			}
			
			if (isset($current_config['basic_search'][$module]) && (count($current_config['basic_search'][$module]) >0)){
				$str.="Beans['$module'].basic_search = ['" . implode("','",$current_config['basic_search'][$module]) . "'];\n";
			}
			if (isset($current_config['list'][$module]) && (count($current_config['list'][$module]) >0)){
				$str.="Beans['$module'].CustomListFields = ['" . implode("','",$current_config['list'][$module]) . "'];\n";
			}

			if (isset($current_config['highlighted'][$module]) && (count($current_config['highlighted'][$module]) >0)){
				$str.="Beans['$module'].highlighted = ['" . implode("','",$current_config['highlighted'][$module]) . "'];\n";
			}

			if (isset($current_config['def_title_fields'][$module])){ // TO REMOVE WHEN APP HAS BEEN UPDATED
				$titlefield = implode("','",$current_config['def_title_fields'][$module]);
				if ($titlefield != 'name') {
					$str.="Beans['$module'].TitleFields = ['" . $titlefield . "'];\n";
				}
			}
			
			if (isset($current_config['subpanels'][$module]) && count($current_config['subpanels'][$module])>0){
				$str.="Beans['$module'].CustomLinks = {";
				$str_subp="";
				foreach($current_config['subpanels'][$module] as $key=>$fld){
					$str_subp .= ($str_subp==''?"":",") ."'".$fld."':{title:'".$current_config['links'][$module][$fld]['label']."'}";
				}
				$str.= $str_subp . "};\n";
			}
			else $str.="Beans['$module'].CustomLinks = [];\n";
			
			if (isset($current_config['marked'][$module]) && ($current_config['marked'][$module] !=='') && ($current_config['marked'][$module] !=='none')){
				$str.="Beans['$module'].ColoredField = '" . $current_config['marked'][$module] . "';\n";
			}
			if (isset($current_config['totals'][$module])  && (count($current_config['totals'][$module]) > 0)){
				$str.="Beans['$module'].ListTotals = ".$json->encode($current_config['totals'][$module]).";\n";
				if (isset($current_config['groupby'][$module]) && ($current_config['groupby'][$module] !='')){
					$str.="Beans['$module'].GroupTotals = '".$current_config['groupby'][$module]."';\n";
				}

				if (isset($current_config['showtotals'][$module]) && ($current_config['showtotals'][$module] !='')){
					$str.="Beans['$module'].ShowTotals = ".$json->encode($current_config['showtotals'][$module]).";\n";
				}

			}

		}
		
		$str.="QCRM.Profiles=".$json->encode($current_config['profiles']).";";
			
		// Save group mode (roles or security group)
		$str.="QCRM.ProfileMode='". $current_config['profilemode'] . "';\n";
		$str.="QCRM.TrackerMode='". $current_config['trackermode'] . "';\n";
		if ($current_config['trackermode'] != 'none'){
			$str.="QCRM.TrackerViewer='". $current_config['trackerviewer'] . "';";
			$str.="QCRM.TrackerFreq=". $current_config['trackerfreq'] . "; QCRM.TrackerRole='". $current_config['trackerrole'] . "'; QCRM.TrackerGroup='". $current_config['trackergroup'] . "';\n";
		}
		// Save RowsPerPage & groupusers
		$str.='RowsPerPage='. $current_config['rowsperpage'] . ";";
		$str.='RowsPerDashlet='. $current_config['rowsperdashlet'] . ";";
		$str.='RowsPerSubPanel='. $current_config['rowspersubpanel'] . ";\n";
		if (!isset ($current_config['groupusers']) || !$current_config['groupusers']){
			$str.= "SimpleBeans['Users'].query += ' AND (users.is_group=0 OR users.is_group IS NULL)';\n";
		}
		else
			$str.= "QCRM.GroupUsers=true;\n";
		
		$str.="QCRM.addressFields=['" . implode("','",$QuickCRM_AddressDef) . "'];\n";
		$str.="QCRM.google_addressFields=['" . implode("','",$QuickCRM_google_AddressDef) . "'];\n";

		return $str;
	}

	function SaveMobileConfig($saveLanguages){
		global $sugar_config;

		$mobile = new mobile_jsLanguage();
		$success = true;
		$errors = array();

		$json = getJSONobj();
		$saveDir = realpath(dirname(__FILE__).'/../../QuickCRM/');
		$saveDirApp = $sugar_config['sugar_version']<'6.3' ? $saveDir : $mobile->saveDir;
		$saveFileApp = $sugar_config['sugar_version']<'6.3' ? 'mobile.config.js' : 'mobile_config.js';
	
		$str= $this->getJSConfig();

		$administration = new Administration();


		$administration->saveSetting('QuickCRM', 'server_version', "6.0");


		// save app data
		if($fh = fopen($saveDirApp .'/' .  $saveFileApp, "w")){
			fputs($fh, $str);
			fclose($fh);
		}
		else {
			$error_msg = 'Error writing: ' . $saveDirApp .'/' .  $saveFileApp;
			$errors[] = $error_msg;
			$GLOBALS['log']->fatal('[QuickCRM] ' .  $error_msg);
			$success = false;
		}
		if ($saveLanguages){
			$mobile->createFiles(array_merge($this->mobile['modules'],$this->QuickCRM_simple_modules),$this->mobile);
		}
		
		// backup and save server module data
		if (file_exists('custom/QuickCRM/mobile.defs.js')){
			rename('custom/QuickCRM/mobile.defs.js','custom/QuickCRM/mobile.defs.back.js');
		}
		if($fh = fopen($saveDir .'/' .  'mobile.defs.js', "w")){
			fputs($fh, $json->encode($this->mobile));
			fclose($fh);
		}
		else {
			$error_msg = 'Error writing: ' . $saveDir .'/mobile.defs.js';
			$errors[] = $error_msg;
			$GLOBALS['log']->fatal('[QuickCRM] ' .  $error_msg);
			$success = false;
		}
		
		return array('success'=>$success,'error_messages' => $errors);

	}

	function SaveServerConfig(){
		global $sugar_config;
	}

	function LoadConfig($refresh=true){
		$this->LoadMobileConfig($refresh);
		$this->LoadServerConfig($refresh);
		
	}

	function SaveConfig(){
	
		function CheckAccess(){
			return is_readable("custom/QuickCRM/rest.php");
		}
		
		$this->SaveMobileConfig(true);
		$this->SaveServerConfig();
		return (CheckAccess());
	}

}
