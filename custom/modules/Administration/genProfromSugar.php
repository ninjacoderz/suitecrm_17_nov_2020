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

global $QuickCRM_modules,$QuickCRM_simple_modules,$QuickCRMDetailsFields,$QuickCRMTitleFields,$QuickCRMExtraFields,$QuickCRMFieldDefs,$QuickCRMAddressesFields,$QuickCRM_AddressDef,$QuickCRM_google_AddressDef;
require_once('custom/modules/Administration/quickcrm_std.php');
if (file_exists("custom/QuickCRM/QuickCRMDefaults.php")){
	include('custom/QuickCRM/QuickCRMDefaults.php');
}
require_once('modules/Administration/Administration.php');
require_once('include/utils.php');

function Q_new_bean($module_name){
	global $beanFiles, $beanList;
	global $sugar_config;
	if ($sugar_config['sugar_version']<'6.3'){
		require_once($beanFiles[$beanList[$module_name]]);
		$bean = new $beanList[$module_name];
	}
	else {
		$bean = BeanFactory::getBean($module_name);
	}
	if (!$bean){
		$GLOBALS['log']->fatal("[QuickCRM] Bean not available for module $module_name");
	}
	return $bean;
}

function suitecrmVersion(){
	global $sugar_config;
	if (isset($sugar_config['suitecrm_version']) ){
		return $sugar_config['suitecrm_version'];
	}
	else if (file_exists('suitecrm_version.php')){
		include('suitecrm_version.php');
		return $suitecrm_version;
	}
	return False;
	
}

function suitecrmVersionisAtLeast($v){
	// can't compare strings when 7.10 is released
	global $sugar_config;
	$suitecrm_version = False;
	if (isset($sugar_config['suitecrm_version']) ){
		$suitecrm_version = $sugar_config['suitecrm_version'];
	}
	else if (file_exists('suitecrm_version.php')){
		include('suitecrm_version.php');
	}
	if ($suitecrm_version){
		return version_compare($suitecrm_version, $v, '>=');
	}
	return False;
	
}

function getTotalOptions(){
	global $app_list_strings;
	
	$total_functions =array(
		'SUM' => 'Sum',
		'AVG' => 'Average',
	);
	if(isset($app_list_strings["aor_function_list"])){
		$total_functions = $app_list_strings["aor_function_list"];
	}
	else if(isset($app_list_strings["aor_total_options"])){
		$total_functions = $app_list_strings["aor_total_options"];
	}

	unset($total_functions['']);
	unset($total_functions['COUNT']);

	return $total_functions;
}

class mobile_jsLanguage {
    
    /**
     * Creates javascript versions of language files
     */
    var $saveDir;
    var $modfields;
	var $listOfLists;
	var $aos;

    public function __construct() {
    	global $sugar_config;
		$this->modfields=array();
		$this->listOfLists=array();


		$this->saveDir = realpath(dirname(__FILE__).'/../../../mobile/fielddefs/');

    }
    
    function createAppStringsCache($required_list,$lst_mod,$lang = 'en_us') {
		global $sugar_config, $mod_strings;

		$json = getJSONobj();
		
		$str_app_array=array();

        $app_strings = return_application_language($lang);
        $all_app_list_strings = return_app_list_strings_language($lang);
		$app_list_strings= array();

		foreach($lst_mod as $key=>$lst){
			$app_list_strings["moduleList"][$lst]= $all_app_list_strings["moduleList"][$lst];
			$app_list_strings["moduleListSingular"][$lst]= isset($all_app_list_strings["moduleListSingular"][$lst])?$all_app_list_strings["moduleListSingular"][$lst]:$all_app_list_strings["moduleList"][$lst];
		}
		
		$app_list_strings["moduleList"]["SavedSearches"]= $all_app_list_strings["moduleList"]["SavedSearch"];
		if ($sugar_config['dbconfig']['db_type'] !='mssql'){
			$app_list_strings["moduleList"]['QCRM_SavedSearch']= $all_app_list_strings["moduleList"]['SavedSearch'];
			$app_list_strings["moduleListSingular"]['QCRM_SavedSearch']= $all_app_list_strings["moduleList"]['SavedSearch'];
		}

		$app_list_array=array( // required by the app
			'parent_type_display',
			'duration_intervals',
			'duration_dom',
			'dom_cal_day_short',
			'repeat_intervals',
			'sales_probability_dom',
			'dom_email_link_type',
		);
		foreach($app_list_array as $lst){
			$app_list_strings[$lst]= $all_app_list_strings[$lst];
		}
				
		// date_range_search_dom in not defined until 6.2
		$app_list_strings["date_search"]= isset($all_app_list_strings["date_range_search_dom"])?$all_app_list_strings["date_range_search_dom"]:$all_app_list_strings["kbdocument_date_filter_options"];
		$app_list_strings["date_search"]['today']=$app_strings['LBL_TODAY'];
		$app_list_strings["date_search"]['yesterday']=$app_strings['LBL_YESTERDAY'];
		$app_list_strings["date_search"]['tomorrow']=$app_strings['LBL_TOMORROW'];
		
		if (isset($all_app_list_strings["numeric_range_search_dom"])) $app_list_strings["num_search"]= $all_app_list_strings["numeric_range_search_dom"];

		if (isset($all_app_list_strings["aow_operator_list"])) $app_list_strings["operator_list"]= $all_app_list_strings["aow_operator_list"];

		$app_list_strings['total_options'] = getTotalOptions();
		
		foreach($required_list as $lst){
			$app_list_strings[$lst]= $all_app_list_strings[$lst];
		}
		
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	        $app_list_strings_encoded = json_encode($app_list_strings, JSON_HEX_APOS);
		}
		else {
			$app_list_strings_encoded = $json->encode($app_list_strings);
		}
        
		$SS_mod_strings = return_module_language($lang, "SavedSearch");
		$MB_mod_strings = return_module_language($lang, "ModuleBuilder");
		$Cal_mod_strings = return_module_language($lang, "Calendar");
		
        $str = <<<EOQ
var RES_ASC='{$SS_mod_strings["LBL_ASCENDING"]}',RES_DESC='{$SS_mod_strings["LBL_DESCENDING"]}',RES_HOME_LABEL='{$all_app_list_strings["moduleList"]["Home"]}',RES_SYNC='{$all_app_list_strings["moduleList"]["Sync"]}',RES_SAVEDSEARCH='{$all_app_list_strings["moduleList"]["SavedSearch"]}',RES_SAVESEARCH='{$SS_mod_strings["LBL_SAVE_SEARCH_AS"]}',RES_MODULES='{$MB_mod_strings["LBL_MODULES"]}',RES_PUBLISH='{$MB_mod_strings["LBL_BTN_PUBLISH"]}',RES_PUBLISHED='{$MB_mod_strings["LBL_PUBLISHED"]}',RES_RELATIONSHIPS='{$MB_mod_strings["LBL_RELATIONSHIPS"]}';
var sugar_app_list_strings = $app_list_strings_encoded;
EOQ;

		$app_array=array('LBL_CREATE_BUTTON_LABEL',
			'LBL_EDIT_BUTTON',
			'LBL_LIST',
			'LBL_SEARCH_BUTTON_LABEL',
			'LBL_ADVANCED_SEARCH',
			'LBL_CURRENT_USER_FILTER',// => 'My Items:',
			'LBL_BACK',
			'LBL_SAVE_BUTTON_LABEL',
			'LBL_CANCEL_BUTTON_LABEL',
			'LBL_MARK_AS_FAVORITES',
			'LBL_REMOVE_FROM_FAVORITES',
			'NTC_DELETE_CONFIRMATION',
			'NTC_REMOVE_CONFIRMATION',
			'LBL_DELETE_BUTTON_LABEL',
			'ERROR_NO_RECORD',
			'LBL_LAST_VIEWED',
			'LNK_LIST_NEXT',
			'LNK_LIST_PREVIOUS',
			'LBL_LINK_SELECT',
			'LBL_LIST_USER_NAME',
			'NTC_LOGIN_MESSAGE', //'Please enter your user name and password.'
//			'LBL_LOGOUT',
			'ERR_INVALID_EMAIL_ADDRESS',
			'LBL_ASSIGNED_TO',
			'LBL_CLEAR_BUTTON_LABEL',
			'LBL_DURATION_DAYS',
			'LBL_CLOSE_AND_CREATE_BUTTON_TITLE', // TO REMOVE WHEN APPS ARE UPDATED
			'LBL_CLOSE_AND_CREATE_BUTTON_LABEL',
			'LBL_CLOSE_BUTTON_TITLE', // TO REMOVE WHEN APPS ARE UPDATED
			'LBL_CLOSE_BUTTON_LABEL',
			'LBL_LISTVIEW_NONE',
			'LBL_SAVED',
			'LBL_PRIMARY_ADDRESS',
			'LBL_BILLING_ADDRESS',
			'LBL_ALT_ADDRESS',
			'LBL_SHIPPING_ADDRESS',
			'LBL_DUPLICATE_BUTTON',
			'MSG_SHOW_DUPLICATES',
			'LBL_EMAIL_OPT_OUT',
			'MSG_LIST_VIEW_NO_RESULTS_BASIC',
			'LBL_CITY',
			'LNK_REMOVE',
			'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM',
			'LBL_FAVORITES',
			'LBL_VIEW_BUTTON',
			'LBL_MODIFIED',
			'LBL_CREATED',
			'LBL_SELECT_BUTTON_LABEL',
    		'LBL_VALIDATE_RANGE',
    		'MSG_JS_ALERT_MTG_REMINDER_CALL_MSG',
    		'MSG_JS_ALERT_MTG_REMINDER_MEETING_MSG',
    		'MSG_SHOULD_BE',
    		'MSG_OR_GREATER',
    		'MSG_IS_NOT_BEFORE',
    		'MSG_IS_MORE_THAN',
    		'MSG_IS_LESS_THAN',
    		'LBL_ADD_BUTTON',
		);

		if (isset($all_app_list_strings['aok_status_list'])){
			$str_app_array['LBL_SHARE_PUBLIC'] = $all_app_list_strings['aok_status_list']['published_public'];
			$str_app_array['LBL_SHARE_PRIVATE'] = $all_app_list_strings['aok_status_list']['published_private'];
		}
		else if ($sugar_config['sugar_version']<'6.3'){
			$str_app_array['LBL_SHARE_PUBLIC'] = 'Public';
			$str_app_array['LBL_SHARE_PRIVATE'] = 'Private';
		}
		else
		{
			$app_array[] = 'LBL_SHARE_PUBLIC';
			$app_array[] = 'LBL_SHARE_PRIVATE';
		}
		if (suitecrmVersion() && suitecrmVersionisAtLeast('7.4')) $app_array[] = 'LBL_FAVORITES_FILTER';

		if (isset($app_strings['LBL_SUBTHEME_OPTIONS_DAWN'])){
			$app_array=array_merge($app_array,array(
			'LBL_SUBTHEME_OPTIONS_DAWN',
			'LBL_SUBTHEME_OPTIONS_DAY',
			'LBL_SUBTHEME_OPTIONS_DUSK',
			'LBL_SUBTHEME_OPTIONS_NIGHT',
			));
		}
		
		if (file_exists('custom/include/generic/SugarWidgets/SugarWidgetFielddrawing.php')){
			$app_array=array_merge($app_array,array(
			'LBL_DRAWING_GENERAL',
			'LBL_DRAWING_CLEAR',
			'LBL_DRAWING_CLEAR_CONFIRM',
			'LBL_DRAWING_REINIT',
			'LBL_DRAWING_UNDO',

			'LBL_DRAWING_TOOLS',
			'LBL_DRAWING_ERASER',
			'LBL_DRAWING_HAND',
			'LBL_DRAWING_LINE',
			'LBL_DRAWING_RECT',
			'LBL_DRAWING_ELLIPSE',
			'LBL_DRAWING_TEXT',

			'LBL_DRAWING_TEXTINPUT',

			'LBL_DRAWING_FONTSIZE',
			'LBL_DRAWING_FONTSMALL',
			'LBL_DRAWING_FONTMEDIUM',
			'LBL_DRAWING_FONTLARGE',

			'LBL_DRAWING_STROKEWIDTH',
			'LBL_DRAWING_WIDTH1',
			'LBL_DRAWING_WIDTH3',
			'LBL_DRAWING_WIDTH5',
			'LBL_DRAWING_WIDTH10',

			'LBL_DRAWING_STROKECOLORS',
			'LBL_DRAWING_BLACK',
			'LBL_DRAWING_RED',
			'LBL_DRAWING_GREEN',
			'LBL_DRAWING_BLUE',

			'LBL_DRAWING_FILLCOLORS',
			'LBL_DRAWING_FILLNONE',
			'LBL_DRAWING_FILLBLACK',
			'LBL_DRAWING_FILLRED',
			'LBL_DRAWING_FILLGREEN',
			'LBL_DRAWING_FILLBLUE',
			));
		}
		foreach($app_array as $key){
			$str_app_array[$key] = str_replace('"','\\"',isset($app_strings[$key])?$app_strings[$key]:$key);
		}
		
		if (isset($app_strings['LBL_LINK_ALL'])){
			$str_app_array['LBL_LISTVIEW_ALL'] = $app_strings['LBL_LINK_ALL'];
		}
		else {
			$str_app_array['LBL_LISTVIEW_ALL'] = $app_strings['LBL_LISTVIEW_ALL'];
		}

		if (isset($app_strings['LBL_PANEL_OVERVIEW'])){
			$str_app_array['LBL_PANEL_OVERVIEW'] = $app_strings['LBL_PANEL_OVERVIEW'];
		}
				
		$str_app_array['DEFAULT_THEME']=$mod_strings['DEFAULT_THEME'];
		
		$cal_array=array(
  			'LBL_REPEAT_TAB',
  			'LBL_REPEAT_TYPE',
  			'LBL_REPEAT_INTERVAL',
  			'LBL_REPEAT_END',
  			'LBL_REPEAT_END_AFTER',
  			'LBL_REPEAT_OCCURRENCES',
  			'LBL_REPEAT_END_BY',
  			'LBL_REPEAT_DOW',
  			'LBL_REPEAT_UNTIL',
  			'LBL_REPEAT_COUNT',
  			'LBL_REPEAT_LIMIT_ERROR',
  			'LBL_EDIT_ALL_RECURRENCES',
  			'LBL_REMOVE_ALL_RECURRENCES',
  			'LBL_CONFIRM_REMOVE_ALL_RECURRING',
  			'LBL_DATE_END_ERROR',
  			'ERR_YEAR_BETWEEN',
		);
		foreach($cal_array as $key){
			$str_app_array[$key] = $Cal_mod_strings[$key];
		}
		
		// prepare for app 7.0
		$MB_array=array(
  			'LBL_HIDDEN',
  			'LBL_MODULES',
  			'LBL_BTN_PUBLISH',
  			'LBL_PUBLISHED',
  			'LBL_RELATIONSHIPS',
		);
		foreach($MB_array as $key){
			$str_app_array[$key] = $MB_mod_strings[$key];
		}

		// prepare for app 7.0
		$SS_array=array(
  			'LBL_ASCENDING',
  			'LBL_DESCENDING',
  			'LBL_SAVE_SEARCH_AS',
		);
		foreach($SS_array as $key){
			$str_app_array[$key] = $SS_mod_strings[$key];
		}

		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$app_strings_encoded = json_encode($str_app_array, JSON_HEX_APOS);
		}
		else {
			$app_strings_encoded = $json->encode($str_app_array);
		}
		
		$str .= "var sugar_app_strings = $app_strings_encoded;";
      	$filename = $this->saveDir . '/' .$lang . '.js';
			if($fh = fopen($filename, "w")){
				fputs($fh, $str);
				fclose($fh);
			}
			else {
				$error_msg = 'Error writing: ' . $filename;
				$GLOBALS['log']->fatal('[QuickCRM] ' . $error_msg);
			}

   }
    
    function createModuleStringsCache($lst_mod,$lang,$module_def,$mobile_config) {
		global $sugar_config;
		$json = getJSONobj();

		$str_to_add=array(
			"Accounts" => array (
							'LBL_BILLING_ADDRESS',
							'LBL_SHIPPING_ADDRESS',
						),
			"Contacts" => array (
							'LBL_PRIMARY_ADDRESS',
							'LBL_ALT_ADDRESS',
						),
			"Leads" => array (
							'LBL_PRIMARY_ADDRESS',
							'LBL_ALT_ADDRESS',
							'LBL_CONVERTLEAD',
							'LNK_SELECT_ACCOUNTS',
							'LNK_SELECT_CONTACTS',
							'LNK_NEW_ACCOUNT',
							'LNK_NEW_CONTACT',
							'LNK_NEW_OPPORTUNITY',
						),
			"Meetings" => array (
							'LBL_REMINDER',
							'LBL_INVITEE',
							'LBL_STATUS',
							'LBL_SCHEDULING_FORM_TITLE',
						),
			"Calls" => array (
							'LBL_REMINDER',
							'LBL_INVITEE',
							'LNK_NEW_CALL',
							'LBL_SCHEDULING_FORM_TITLE',
			 				'LBL_RESCHEDULE',
			 				'LBL_RESCHEDULE_DATE',
						),
			"Tasks" => array (
							'LNK_NEW_TASK',
						),
			"Notes" => array (
							'LBL_NOTES_SUBPANEL_TITLE',
						),
			"Emails" => array (
							'LBL_BODY',
							'LBL_HTML_BODY',
							'LBL_SEND_BUTTON_LABEL',
							'LBL_ATTACHMENTS',
							'LBL_BCC',
							'LBL_CC',
							'LBL_FROM_NAME',
							'LBL_FROM',
							'LBL_REPLY_TO',
							'LBL_COMPOSE_MODULE_NAME',
							'LBL_NOT_SENT',
							'LBL_SEND_BUTTON_LABEL',
							'LBL_TO_ADDRS',
							'LBL_EMAIL_RELATE',
							'LBL_SEND_ANYWAYS',
						    'LBL_LIST_TITLE_MY_DRAFTS',
    						'LBL_LIST_TITLE_MY_INBOX',
    						'LBL_LIST_TITLE_MY_SENT',
    						'LBL_LIST_TITLE_MY_ARCHIVES',
						),
			"AOS_Quotes" => array (
							'LBL_ADD_PRODUCT_LINE',
							'LBL_ADD_SERVICE_LINE',
							'LBL_ADD_GROUP',
							'LBL_DELETE_GROUP',
							'LBL_GROUP_NAME',
							'LBL_GROUP_TOTAL',
							'LBL_PRODUCT_QUANITY',
							'LBL_PRODUCT_NAME',
							'LBL_PART_NUMBER' ,
							'LBL_PRODUCT_NOTE' ,
							'LBL_PRODUCT_DESCRIPTION',
							'LBL_LIST_PRICE',
							'LBL_DISCOUNT_AMT',
							'LBL_UNIT_PRICE',
							'LBL_VAT_AMT',
							'LBL_TOTAL_PRICE',
							'LBL_SERVICE_NAME' ,
							'LBL_SERVICE_LIST_PRICE',
							'LBL_SERVICE_PRICE' ,
							'LBL_SERVICE_DISCOUNT',
							'LBL_EMAIL_PDF',
							'LBL_PRINT_AS_PDF',
							'LBL_PDF_NAME',
						),
			"AOS_Invoices" => array (
							'LBL_ADD_PRODUCT_LINE',
							'LBL_ADD_SERVICE_LINE',
							'LBL_ADD_GROUP',
							'LBL_DELETE_GROUP',
							'LBL_GROUP_NAME',
							'LBL_GROUP_TOTAL',
							'LBL_PRODUCT_QUANITY',
							'LBL_PRODUCT_NAME',
							'LBL_PART_NUMBER' ,
							'LBL_PRODUCT_NOTE' ,
							'LBL_PRODUCT_DESCRIPTION',
							'LBL_LIST_PRICE',
							'LBL_DISCOUNT_AMT',
							'LBL_UNIT_PRICE',
							'LBL_VAT_AMT',
							'LBL_TOTAL_PRICE',
							'LBL_SERVICE_NAME' ,
							'LBL_SERVICE_LIST_PRICE',
							'LBL_SERVICE_PRICE' ,
							'LBL_SERVICE_DISCOUNT',
							'LBL_EMAIL_PDF',
							'LBL_PRINT_AS_PDF',
							'LBL_PDF_NAME',
						),
			"SugarFeed" => array (
							'CREATED_CONTACT',
							'CREATED_OPPORTUNITY',
							'CREATED_CASE',
							'CREATED_LEAD',
							'FOR',
							'CLOSED_CASE',
							'CONVERTED_LEAD',
							'WON_OPPORTUNITY',
							'WITH',
						),
			"Users" => array (
							'LBL_LOGIN_BUTTON_TITLE',
							'LBL_RECEIVE_NOTIFICATIONS',
						),
		); 
				
		$str = <<<EOQ
var sugar_mod_strings={};
EOQ;
        $app_strings = return_application_language($lang);
		foreach($lst_mod as $key => $moduleName){
			$mod_fields = $this->modfields[$moduleName];
			$tmp_mod_strings = return_module_language($lang, $moduleName);
			$mod_strings=array();
			foreach($mod_fields as $field_name => $field_defs){
				if (isset($tmp_mod_strings[$field_defs['label']])) {
					$mod_strings[$field_defs['label']]=str_replace('\\',"/",str_replace('"',"",$tmp_mod_strings[$field_defs['label']]));
				}
				elseif (isset($app_strings[$field_defs['label']])) {
					$mod_strings[$field_defs['label']]=str_replace('\\',"/",str_replace('"',"",$app_strings[$field_defs['label']]));
				}
				else $mod_strings[$field_defs['label']]=$field_defs['label'];
			}
			if (isset($str_to_add[$moduleName])) {
				foreach($str_to_add[$moduleName] as $label){
					if (!isset($tmp_mod_strings[$label])){
						$tmp_mod_strings[$label] = $label;
					}
					$mod_strings[$label]=str_replace('\\',"/",str_replace('"',"",$tmp_mod_strings[$label]));
				}
			}
			if (isset($mobile_config['links'][$moduleName])) {
				foreach ($mobile_config['subpanels'][$moduleName] as $lnk){
					$realname=$mobile_config['links'][$moduleName][$lnk]['vname'];
					// do not add this label if subpanel name is target module
					if ($realname != $mobile_config['links'][$moduleName][$lnk]['module']){
						if (isset($tmp_mod_strings[$realname])){
							$mod_strings[$realname]=$tmp_mod_strings[$realname];
						}
						elseif (isset($app_strings[$realname])){
							$mod_strings[$realname]=$app_strings[$realname];
						}
						else
							$mod_strings[$realname]=$realname;
					}
				}
			}
			$mod_strings['NEW']='+';
			$nodeModule = Q_new_bean($moduleName);
			if ($nodeModule){
				if (isset($tmp_mod_strings['LNK_NEW_RECORD'])){ // SuiteCRM Modules
					$mod_strings['NEW']=$tmp_mod_strings['LNK_NEW_RECORD'];
				}
				else{
					$new_label='LNK_NEW_' . strtoupper($nodeModule->getObjectName()); // SugarCRM Modules
					if (isset($tmp_mod_strings[$new_label])){
						$mod_strings['NEW']=$tmp_mod_strings[$new_label];
					}
				}
			}
			
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				$mod_strings_encoded =  json_encode($mod_strings, JSON_HEX_APOS);
			}
			else {
				$mod_strings_encoded = $json->encode($mod_strings);
			}

			$str .= "sugar_mod_strings['$moduleName'] = $mod_strings_encoded;";
        }


	      	$filename = $this->saveDir .'/modules_'.$lang . '.js';
			if($fh = fopen($filename, "w")){
				fputs($fh, $str);
				fclose($fh);
			}
			else {
				$error_msg = 'Error writing: ' . $filename;
				$GLOBALS['log']->fatal('[QuickCRM] ' . $error_msg);
			}

	}

    function createModuleFieldsCache($lst_mod,$module_def) {
	
		global $sugar_config;
		global $QuickCRM_modules,$QuickCRM_simple_modules,$QuickCRMDetailsFields,$QuickCRMTitleFields,$QuickCRMAddressesFields;
		$json = getJSONobj();

		$administration = new Administration();
		$administration->retrieveSettings('QuickCRM');
		
		$str='var sugar_mod_fields ={};';
		
// STORE FIELDS
		foreach($lst_mod as $key=>$moduleName)
		{
			$mod_strings=$this->modfields[$moduleName];

			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				$mod_strings_encoded = json_encode($mod_strings, JSON_HEX_APOS);
			}
			else {
				$mod_strings_encoded = $json->encode($mod_strings);
			}
			$str .= "sugar_mod_fields['$moduleName'] = $mod_strings_encoded;";

        }
		
		if($sugar_config['sugar_version']>'6.3'){
	      	$filename = $this->saveDir . '/' .'sugar_fields' . '.js';
			if($fh = fopen($filename, "w")){
				fputs($fh, $str);
				fclose($fh);
			}
			else {
				$error_msg = 'Error writing: ' . $filename;
				$GLOBALS['log']->fatal('[QuickCRM] ' . $error_msg);
			}
			$str='';
		}		

		// STORE MODULE TYPE (Person/Basic) AND TABLES DATA
		$str .= "var sugar_mod=". json_encode($module_def) . ";";
		
		if($fh = fopen($this->saveDir . '/fields_std.js', "w")){
			fputs($fh, $str);
			fclose($fh);
		}

    }

    function createCRMConfig($mobile_config,$aos) {
		global $sugar_config,$moduleList,$db;
		global $QuickCRM_modules,$QuickCRMDetailsFields,$QuickCRMTitleFields,$QuickCRMAddressesFields,$mod_fields_additional_arr;

		$administration = new Administration();
		$administration->retrieveSettings('',true);
		
		$str = "var mobile_edition = 'Pro',QServer='6.0', Q_API='5.0',";
        $str .= ' sugar_version = "'.$sugar_config['sugar_version'].'",';
        $str .= ' sugar_name = "'.$administration->settings['system_name'].'",';
        $str .= ' default_language = "'.$sugar_config['default_language'].'",';
		$lst_lang=get_languages();
		if (isset($mobile_config['languages']) && $mobile_config['languages'] == 'default'){
			$lst_lang= array($sugar_config['default_language']=>$lst_lang[$sugar_config['default_language']]);
		}
        $str .= ' sugar_languages = '.json_encode($lst_lang).',';
        $str .= ' default_currency_name = "'.$sugar_config['default_currency_name'].'",';
        $str .= ' default_currency_symbol = "'.$sugar_config['default_currency_symbol'].'",';
        $str .= ' default_date_format = "'.$sugar_config['default_date_format'].'",';
        $str .= ' db_type = "'.$sugar_config['dbconfig']['db_type'].'",';
		
		if (in_array ('jjwg_Maps',$moduleList)){
			$str .= ' jjwg_installed = true,';
			if (isset($administration->settings['jjwg_map_default_unit_type'])){
				$jjwg_def_unit=$administration->settings['jjwg_map_default_unit_type'];
			}
			else {
				$jjwg_def_unit='miles';
			}
			$str .= 'jjwg_def_unit="'.$jjwg_def_unit.'",';
			if (isset($administration->settings['jjwg_valid_geocode_modules'])){
				$jjwg_modules=$administration->settings['jjwg_valid_geocode_modules'];
			}
			else {
				$jjwg_modules='Accounts, Contacts, Leads, Opportunities, Cases, Project, Meetings, Prospects';
			}
			$str .= 'jjwg_modules="'.$jjwg_modules.'",';
			if (isset($administration->settings['jjwg_map_default_center_latitude'])){
				$jjwg_c_lat=$administration->settings['jjwg_map_default_center_latitude'];
				$jjwg_c_lng=$administration->settings['jjwg_map_default_center_longitude'];
			}
			else {
				$jjwg_c_lat=39.5;
				$jjwg_c_lng=-99.5;
			}
			$str .= 'jjwg_c_lat='.$jjwg_c_lat.',' . 'jjwg_c_lng='.$jjwg_c_lng.',';
		}
		else {
			$str .= ' jjwg_installed = false,jjwg_def_unit="",jjwg_modules="",';
		}
		if (isset($sugar_config['aos'])) {
			$str .= ' aos_installed = "' . $sugar_config['aos']['version'] . '",';
			$str .= ' aos_params = '.json_encode($sugar_config['aos']) . ',';
		}
		else if ($aos) {
			$str .= ' aos_installed = "5.1",';
			$str .= ' aos_params = false,';
		}
		else {
			$str .= ' aos_installed = false,';
		}
		if (isset($sugar_config['suitecrm_version'])){
			$str .= 'suitecrm = "' . $sugar_config['suitecrm_version'] . '",';
		}
		else {
			$str .= 'suitecrm = false,';
		}

		$str .= ' securitysuite = '.(in_array ('SecurityGroups',$moduleList)?'true':'false').',';
		
		$offline_max = 10;
		if ($mobile_config && isset($mobile_config['offline_max_days'])) $offline_max = $mobile_config['offline_max_days'];
        $str .= ' offline_max_days = '.$offline_max.';';
         

			$str .= 'var trial = false,';
			if (isset($administration->settings['QuickCRM_InDt'])) {
				$InDt=$administration->settings['QuickCRM_InDt'];
			}
			else {
				$InDt=date("Y-m-d");
				$administration->saveSetting('QuickCRM', 'InDt', $InDt);
			}
			$str .= 'QInDt = "'.$InDt.'",';



			require_once('modules/QuickCRM/license/OutfittersLicense.php');
			$key = QOutfittersLicense::getKey('QuickCRM');


		$str .= 'QProKey = "'.$key.'";';
        $str .= 'var quickcrm_upd_time = "'.time().'";';



        $str .= 'QCRM.sugaroutfitters=true;QCRM.so_key="SuiteCRM Store";';

        $str .= "QCRM.usersTable= true ;";



        $str .= 'QCRM.phpversion = "'. phpversion() .'";'; // for debug by support only

		$query = "SELECT COUNT(id) as nb FROM currencies WHERE status='Active' AND deleted=0";
		$currencies_result=$db->query($query);
		$row = $db->fetchByAssoc($currencies_result);
		$nb_currencies = $row['nb'];
        $str .= 'QCRM.singlecurrency = '. ($nb_currencies == 0 ? 'true':'false')  .';'; 

		if (isset($sugar_config['save_query'])) {
			$str .= 'QCRM.save_query="'.$sugar_config['save_query'].'";';
		}

		$autClass = 'SugarAuthenticate';
		if (isset($sugar_config['quickcrm_authenticate'])){
				$autClass = $sugar_config['quickcrm_authenticate'];
		}
		else if (!empty($sugar_config['authenticationClass'])){
				$autClass = $sugar_config['authenticationClass'];
		}
        else{
            if(!empty($GLOBALS['system_config']->settings['system_ldap_enabled'])){
                $authclass = 'LDAPAuthenticate';
            }
        }
        $str .= 'QCRM.authentication = "'. $autClass .'";';
        
        $str .= 'QCRM.name_format = "'.$sugar_config['default_locale_name_format'].'";';
		$google_key = '';
		if (isset($administration->settings['jjwg_google_maps_api_key'])){
			$google_key=$administration->settings['jjwg_google_maps_api_key'];
		}
		else if (isset($sugar_config['google_maps_api_key'])) {
			$google_key = $sugar_config['google_maps_api_key'];
		}
		if ($google_key !='') {
			$str .= 'QCRM.google_api_key="'.$google_key.'";';
		}
		if (isset($sugar_config['google_api_ios_key'])) {
			$str .= 'QCRM.google_api_ios_key="'.$sugar_config['google_api_ios_key'].'";';
		}
		if (isset($sugar_config['google_api_android_key'])) {
			$str .= 'QCRM.google_api_android_key="'.$sugar_config['google_api_android_key'].'";';
		}

		if (!empty($sugar_config['quickcrm_workflow_user_id'])) {
			$str .= 'QCRM.WorkflowUserId="'.$sugar_config['quickcrm_workflow_user_id'].'";';
		}
		if (!empty($sugar_config['quickcrm_lockhomepage'])) {
			$lock_users = json_encode($sugar_config['quickcrm_lockhomepage']);
			$str .= 'QCRM.LockHomepageUsers='.$lock_users.';';
		}
		if (!empty($sugar_config['quickcrm_default_homepage'])) {
			$str .= 'QCRM.DefaultHomepageId="'.$sugar_config['quickcrm_default_homepage'].'";';
		}

		// TODO REMOVE WHEN ALL APPS ARE 6.2.3 or later
        $str .= "var js_plugins=[],html_plugins=[];";
		
		$str .= "var CustomHTML=false,";
		$str .= " CustomJS=".(file_exists("custom/QuickCRM/custom.js")?"true":"false").";";
		if (file_exists("custom/QuickCRM/custom.css")){
			$str .= " QCRM.CustomCSS=true;";
		}
		if (file_exists("custom/QuickCRM/custom.html")){
			$str .= " QCRM.CustomHTML=true;";
		}
		if (file_exists("custom/QuickCRM/theme.css")){
			$str .= " QCRM.CustomTheme=true;";
		}
		if (!empty($sugar_config['quickcrm_forcetheme'])){
			$str .= " QCRM.ForceCustomTheme='" . $sugar_config['quickcrm_forcetheme'] ."';";
		}
		if (file_exists('modules/Reminders/Reminder.php')){
			if (in_array ('Calls',$mobile_config['modules']) || in_array ('Meetings',$mobile_config['modules'])){
				$str .= " QCRM.Reminders=true;";
			}
		}
		if (!empty($sugar_config['quickcrm_support_email'])){
			$str .= " QCRM.support_email='" . $sugar_config['quickcrm_support_email'] ."';";
		}
		if (!empty($sugar_config['quickcrm_support_menu'])){
			$str .= " QCRM.support_menu='" . $sugar_config['quickcrm_support_menu'] ."';";
		}
		if (!empty($sugar_config['quickcrm_require_email_mode'])){
			$str .= " QCRM.RequireEmailMode='" . $sugar_config['quickcrm_require_email_mode'] ."';";
		}
		
		if ($sugar_config['sugar_version']>'6.3'&& $fh = fopen($this->saveDir . '/../config.js', "w")){
			fputs($fh, $str);
			fclose($fh);
		}
		if($fh = fopen('custom/QuickCRM/config.js', "w")){
			fputs($fh, $str);
			fclose($fh);
		}
    }

	function AddProfile($module,$profile){
		$fields = array();
		if (!isset($profile['fields'][$module])) {$Add=array();} else {$Add=$profile['fields'][$module];}
		if (isset($profile['detail'][$module]) && ($profile['detail'][$module] !=false)) {$Add=array_merge($Add,$profile['detail'][$module]);}
		if (!isset($profile['search'][$module])) {$Search=array();} else {$Search=$profile['search'][$module];}
		if (isset($profile['basic_search'][$module])) {$Search=array_merge($Search,$profile['basic_search'][$module]);}
		if (!isset($profile['list'][$module])) {$List=array();} else {$List=$profile['list'][$module];}
		if (isset($profile['highlighted'][$module])) {$List=array_merge($List,$profile['highlighted'][$module]);}

		if (isset($profile['marked'][$module]) && ($profile['marked'][$module] !='') && ($profile['marked'][$module] !='none') && ($profile['marked'][$module] !='assigned_user_id')){
			$fields[]=$profile['marked'][$module];
		}
		if (isset($profile['groupby'][$module]) && ($profile['groupby'][$module] !='')){
			$fields[]=$profile['groupby'][$module];
		}
		if (isset($profile['totals'][$module]) && (count($profile['totals'][$module])>0)){
			foreach($profile['totals'][$module] as $key=>$values){
				$fields[]=$values['field'];
			}
		}

		$fields = array_unique(array_merge($fields,$Add,$Search,$List));
		
		return $fields;

	}
	
	function buildFieldArray($module,$module_def,$mobile_config){
		global $QuickCRM_modules,$QuickCRMDetailsFields,$QuickCRMTitleFields,$QuickCRMAddressesFields,$QuickCRMExtraFields,$QuickCRMFieldDefs,$QuickCRM_AddressDef;
		global $beanFiles, $beanList;
		$modfields=array();
		
		if (!file_exists($beanFiles[$beanList[$module]])){
			$GLOBALS['log']->fatal("[QuickCRM] Bean file not found for module $module");
			$this->modfields[$module]=$modfields;
			return;
		}
		
		$nodeModule = Q_new_bean($module);
		$excluded=array("id","created_by", "modified_user_id", "assigned_user_id","deleted");
		$users_include=array("user_name","first_name", "last_name", "department", "phone_mobile","email1");
				
		// default profile
		$allfields = $this->AddProfile($module,$mobile_config);
		
		// role or SG profile
		if (isset($mobile_config['profiles'])){
			foreach ($mobile_config['profiles'] as $profile){
				$allfields = array_merge($allfields,$this->AddProfile($module,$profile));
			}
		}

		// Add name or firstname/lastname for custom modules.
		if (isset($module_def[$module]) && $module_def[$module]['type']=='person') {
			$allfields[]='first_name';
			$allfields[]='last_name';
		}
		elseif (isset($module_def[$module]) && $module_def[$module]['type']=='file') {
			$allfields[]='document_name';
			if ($module !='Documents'){
				$allfields[]='filename';
				$allfields[]='file_ext';
				$allfields[]='file_mime_type';				
			}
		}
		else {
			$allfields[]='name';
		}

		if (!isset($QuickCRMDetailsFields[$module])) {$Details=array();} else {$Details=$QuickCRMDetailsFields[$module];}
		if (!isset($QuickCRMTitleFields[$module])) {$Title=array();} else {$Title=$QuickCRMTitleFields[$module];}
		if (!isset($QuickCRMExtraFields[$module])) {$Extra=array();} else {$Extra=$QuickCRMExtraFields[$module];}

		$allfields = array_unique(array_merge($allfields,$Details,$Title,$Extra,array('date_entered','date_modified','assigned_user_name')));

		foreach ($allfields as $A=>$val){
			if (substr($val,0,4)=='$ADD'){
				$prefix=substr($val,4);
				foreach ($QuickCRM_AddressDef as $suffix) {
					$addr_field = $prefix."_address_".$suffix;
					if (!in_array($addr_field,$allfields)) $allfields[]=$addr_field;
				}
				// Add the virtual address fielddef
				$modfields[$val] = array(
									'type' => 'none',
									'req' => false,
									'label' => '',
									'source' => "non-db",
							);
			}
		}


		foreach($nodeModule->field_name_map as $field_name => $field_defs)
		{	$source=(isset($field_defs['source'])?$field_defs['source']:"");

			if (in_array($field_name,$excluded)
				|| ($module=='Users' && !in_array($field_name,$users_include))
				|| ($module!='Users' && !in_array($field_name,$allfields))
// CE VERSION					||($field_defs['source']=='custom_fields')
				){
				continue;
			}

			if (isset($QuickCRMFieldDefs[$module]) && isset($QuickCRMFieldDefs[$module][$field_name])){
				$modfields[$field_name] = $QuickCRMFieldDefs[$module][$field_name];
				continue;
			}
			else {
				$modfields[$field_name] = array(
					'type' => $field_defs['type'],
					'req' => (isset($field_defs['required']) && ($field_defs['required']==True||$field_defs['required']=='1')),
					'label' => $field_defs['vname'],
				);
			}
			$def='';
			if (isset($field_defs['default_value'])&&($field_defs['default_value']!=null)) {
				$def=$field_defs['default_value'];
			}
			if (isset($field_defs['default'])&&($field_defs['default']!=null)) {
				$def=$field_defs['default'];
			}
			if ($def!='') {
				$modfields[$field_name]['def']=preg_replace("/'/","&#039;",$def);
			}

			if (!empty($field_defs['help'])) {
				$modfields[$field_name]['help'] =$field_defs['help'];
			}

			if (isset($field_defs['inline_edit']) && !$field_defs['inline_edit']) {
				$modfields[$field_name]['inline_edit'] =false;
			}

			if (isset($field_defs['required']) && ($field_defs['required']==True||$field_defs['required']=='1')){
				$modfields[$field_name]['req'] =true;
			}

			if (isset($field_defs['readonly']) && ($field_defs['readonly']==True||$field_defs['readonly']=='1')){
					$modfields[$field_name]['readonly']= true;
			}

			if (isset($field_defs['options'])) {
				$modfields[$field_name]['options'] =$field_defs['options'];
				if (!in_array ( $field_defs['options'] , $this->listOfLists )) $this->listOfLists[]= $field_defs['options'];
			}

			if (($field_defs['type'] == 'relate')&&(isset($field_defs['module']))){
				$modfields[$field_name]['module']=$field_defs['module'];
				$modfields[$field_name]['id_name']=$field_defs['id_name'];
				$id_fielddef=$nodeModule->field_name_map[$field_defs['id_name']];
				if (isset($id_fielddef['source']) && $id_fielddef['source'] == 'non-db') {
					$modfields[$field_name]['search']='non-db';
				}
				elseif (isset($id_fielddef['source']) && $id_fielddef['source'] == 'custom_fields') {
					$modfields[$field_name]['source']= '_cstm';
				}
			}
			elseif (($field_defs['type'] == 'parent')&& isset($field_defs['parent_type']) && isset($field_defs['options'])){
				$modfields[$field_name]['id_name']=$field_defs['id_name'];
				$modfields[$field_name]['id_type']=$field_defs['type_name'];
			}
			elseif ($field_name=='email1'){
				$modfields[$field_name]['type']='email';
			}
			elseif($field_defs['type'] != 'link'
				&& $field_defs['type'] != 'relate'
				)
			{
				if ($source == 'custom_fields') {
					$modfields[$field_name]['source']= '_cstm';
				}
				else {
					$modfields[$field_name]['source']= $source;
				}

				switch ($field_defs['type']){
					case 'datetimecombo':
						$modfields[$field_name]['type']= 'datetime';
						break;
					
					case 'dynamicenum':
						$modfields[$field_name]['parentenum']= $field_defs['parentenum'];
					case 'radioenum':
						$modfields[$field_name]['mode']= 'radio';
					case 'enum':
						$modfields[$field_name]['type']= 'enum';
						break;
					
					case 'multienum':
						break;

					case 'float':
					case 'double':
					case 'decimal':
						$modfields[$field_name]['precision'] =intval($field_defs['precision']);
						break;

					case 'iframe':
						$modfields[$field_name]['height'] =$field_defs['height'];
					case 'url':
						if (isset($field_defs['gen'])&&($field_defs['gen']==1)){
							$modfields[$field_name]['gen'] =1;
							$modfields[$field_name]['source'] ='non-db'; // non editable field
						}
						else {
							$modfields[$field_name]['gen'] =0;
						}
						break;
					
					case 'Drawing':
					case 'image':
					case 'Signature':
						if (isset($field_defs['width'])&&($field_defs['width']!='')){
							$modfields[$field_name]['width'] =$field_defs['width'];
						}
						else {
							$modfields[$field_name]['width'] ='300px';
						}
						if (isset($field_defs['height'])&&($field_defs['height']!='')){
							$modfields[$field_name]['height'] =$field_defs['height'];
						}
						else {
							$modfields[$field_name]['height'] ='250px';
						}
						break;
					case 'photo':
						if ($field_defs['ext2']==1){
							$modfields[$field_name]['width'] =$field_defs['ext3'];
							$modfields[$field_name]['height'] =$field_defs['ext4'];
						}
						break;
					case 'text':
					case 'longtext':
					case 'TextOperators':
						if (isset($field_defs['editor']) && $field_defs['editor'] == 'html'){
							$modfields[$field_name]['html'] =True;
						}
						break;
					case 'int':
						if (isset($field_defs['disable_num_format']) && $field_defs['disable_num_format']){
							$modfields[$field_name]['disable_num_format'] =True;
						}
						if (isset($field_defs['validation'])){
							if (isset($field_defs['validation']['min']) && $field_defs['validation']['min'] !== false){
								$modfields[$field_name]['min'] =$field_defs['validation']['min'];
							}
							if (isset($field_defs['validation']['max']) && $field_defs['validation']['max'] !== false){
								$modfields[$field_name]['max'] =$field_defs['validation']['max'];
							}
						}
						break;
					case 'Rate':
						$modfields[$field_name]['max'] =$field_defs['maximum'];
						$modfields[$field_name]['allowHalfStar'] =$field_defs['allowHalfStar'];
						break;
					case 'Rating':
						$modfields[$field_name]['max'] = empty($field_defs['numberOfStars']) ? 5 : $field_defs['numberOfStars'];
						$modfields[$field_name]['allowHalfStar'] = (!empty($field_defs['allowHalfStar']) && $field_defs['allowHalfStar']);
						break;
					case 'ua_rating':
						$modfields[$field_name]['max'] =$field_defs['max'];
						$modfields[$field_name]['allowHalfStar'] =false;
						break;
					default:
						break;
				}
			}
			
		}
		$this->modfields[$module]=$modfields;
		return;

	}

	function BuildModuleDef($mobile_config){
		$module_def=$mobile_config['mod_def'];
		foreach ($mobile_config['modules'] as $module){
			$module_def[$module]['links']=array();
			foreach ($mobile_config['links'][$module] as $lnk=>$values){
				$module_def[$module]['links'][$lnk]=$values['module'];
/* TODO FOR 3.0 once native apps have been updated

				if (isset($values['id_name'])){
					$module_def[$module]['links'][$lnk]= array (
						'module' => $values['module'],
						'id_name' => $values['id_name'],
					);
				}
				else {
					$module_def[$module]['links'][$lnk]=$values['module'];
				}
*/
			}
		}
		return $module_def;
	}
	
	function createFiles($lst_mod,$mobile_config){
		global $sugar_config;
		$module_def=$this->BuildModuleDef($mobile_config);
		$lst2=$lst_mod;
		$aos = (in_array('AOS_Quotes',$lst_mod) || in_array('AOS_Invoices',$lst_mod));
		
		if ( $aos && !in_array('AOS_Products_Quotes',$lst_mod)) {
			array_push($lst2,'AOS_Products_Quotes');
		}
		if ( $aos && !in_array('AOS_Products',$lst_mod)) {
			array_push($lst2,'AOS_Products');
		}
		if (!in_array('Emails',$lst_mod)) {
			array_push($lst2,'Emails');
		}
		if ($sugar_config['dbconfig']['db_type'] !='mssql'){
			array_push($lst2,'QCRM_SavedSearch');
			array_push($lst2,'QCRM_Homepage');
		}
		array_push($lst2,'QCRM_Tracker');
		
		if (isset($sugar_config['suitecrm_version']) && suitecrmVersionisAtLeast('7.4')){
			array_push($lst2,'Favorites');
		}
		if (file_exists("modules/Calls_Reschedule/Calls_Reschedule.php")){
			array_push($lst2,'Calls_Reschedule');
		}

		foreach($lst2 as $key=>$moduleName)
		{
			$this->buildFieldArray($moduleName,$module_def,$mobile_config);
		}
		$this->createCRMConfig($mobile_config,$aos);
		$this->createModuleFieldsCache($lst2,$module_def);
		$lst_lang=get_languages();
		if ($mobile_config['languages'] == 'default'){
			$lst_lang= array($sugar_config['default_language'] => $lst_lang[$sugar_config['default_language']]);
		}
		$required_list = $this->listOfLists; // List of application list strings used in the application (enums)
		foreach($lst_lang as $language => $language_name){
			$this->createAppStringsCache($required_list,$lst_mod,$language);
			$this->createModuleStringsCache($lst2,$language,$module_def,$mobile_config);
		}
	}
}

?>