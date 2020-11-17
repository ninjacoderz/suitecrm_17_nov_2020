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

require_once($beanFiles[$beanList['ACLRoles']]);
$role_bean = new ACLRole();
if (isset($app_list_strings["moduleList"]["SecurityGroups"])){
	require_once($beanFiles[$beanList['SecurityGroups']]);
	$group_bean = new SecurityGroup();
}
//$mod_strings=return_module_language($current_language, 'Administration');
$MBmod_strings=return_module_language($current_language, 'ModuleBuilder');
$Usersmod_strings=return_module_language($current_language, 'Users');
$Schedulersmod_strings=return_module_language($current_language, 'Schedulers');
$ACLmod_strings=return_module_language($current_language, 'ACLActions');
$suitecrm = (isset($sugar_config['suitecrm_version']) );

ini_set("display_errors", 0);


echo '<script>ajaxStatus.hideStatus();</script>';
echo getClassicModuleTitle(
    "Administration",
    array(
        "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
        'QuickCRM: ' . $mod_strings['LBL_SETTINGS_QUICKCRM_TITLE'],
    ),
    false
);
require_once('custom/modules/Administration/QuickCRM_utils.php');
$qutils=new QUtils();
$qutils->LoadMobileConfig(!isset($_REQUEST['conf_module'])); // refresh first open only

$groupusers_checked=($qutils->mobile['groupusers']?' checked="checked"':'');
$groupusers_title= $Usersmod_strings['LBL_GROUP_USER'];
$native_cal_checked=((!isset($qutils->mobile['native_cal']) || $qutils->mobile['native_cal'])?' checked="checked"':'');
$native_cal_title = $mod_strings['LBL_NATIVE_CAL'];
$force_lock_checked=((!isset($qutils->mobile['force_lock']) || $qutils->mobile['force_lock'])?' checked="checked"':'');
$force_lock_title = $mod_strings['LBL_FORCE_LOCK'];
$documents_sync_checked=((!isset($qutils->mobile['documents_sync']) || $qutils->mobile['documents_sync'])?' checked="checked"':'');
$documents_sync_title = $app_list_strings["moduleList"]["Documents"] . ': ' . $app_list_strings["moduleList"]["Sync"];
$audio_notes_checked=((!isset($qutils->mobile['audio_notes']) || $qutils->mobile['audio_notes'])?' checked="checked"':'');
$audio_notes_title = $mod_strings['LBL_AUDIONOTES'];

$current_profile = $qutils->mobile['profilemode'];
$profiles = '<option value="none" '.($current_profile == "none" ? 'selected="selected"' :'').'>' .$app_strings["LBL_NONE"] . '</option>';
if (isset($app_list_strings["moduleList"]["SecurityGroups"])){
	$profiles .= '<option value="SecurityGroups" '.($current_profile == "SecurityGroups" ? 'selected="selected"' :'').'>' .$app_list_strings["moduleList"]["SecurityGroups"] . '</option>';
}

$profiles .= '<option value="ACLRoles" '.($current_profile == "ACLRoles" ? 'selected="selected"' :'').'>' .$app_list_strings["moduleList"]["ACLRoles"] . '</option>';

$profile_display = 'style="display:none;"';
if (isset($sugar_config['quickcrm_profiles']) && $sugar_config['quickcrm_profiles']){
	$profile_display ='';
}
/*
$profile_display ='';
*/

$current_tracker = $qutils->mobile['trackermode'];
$current_frequency = $qutils->mobile['trackerfreq'];
$current_tracker_group = $qutils->mobile['trackergroup'];
$current_tracker_role = $qutils->mobile['trackerrole'];
$current_tracker_viewer_role = $qutils->mobile['trackerviewer'];

$trackermodes = '<option value="none" '.($current_tracker == "none" ? 'selected="selected"' :'').'>' .$app_strings["LBL_NONE"] . '</option>';

if (isset($app_list_strings['aor_assign_options']) && isset($app_list_strings['aor_assign_options']['role'])) $lbl_role = $app_list_strings['aor_assign_options']['role'];
else $lbl_role = $app_list_strings["moduleList"]["ACLRoles"];

$roles = $role_bean->get_full_list("name");
$trackerroles ='';
$trackerviewerroles = '<option value="">Admin</option>';
if(!empty($roles)) {
	$trackermodes .= '<option value="ACLRoles" '.($current_tracker == "ACLRoles" ? 'selected="selected"' :'').'>' . $lbl_role . '</option>';
    foreach($roles as $group) {
		$trackerroles .= '<option value="'.$group->id.'">' . $group->name . '</option>';
		$trackerviewerroles .= '<option value="'.$group->id.'">' . $group->name . '</option>';
    }
}
$trackergroups ='';
if (isset($app_list_strings["moduleList"]["SecurityGroups"])){
	$groups = $group_bean->get_full_list("name");
	if(!empty($groups)) {
		if (isset($app_list_strings['aor_assign_options']) && isset($app_list_strings['aor_assign_options']['security_group'])) $lbl_group = $app_list_strings['aor_assign_options']['security_group'];
		else $lbl_group = $app_list_strings["moduleList"]["SecurityGroups"];
		$trackermodes .= '<option value="SecurityGroups" '.($current_tracker == "SecurityGroups" ? 'selected="selected"' :'').'>' . $lbl_group . '</option>';
    	foreach($groups as $group) {
			$trackergroups .= '<option value="'.$group->id.'">' . $group->name . '</option>';
    	}
    }
}
$lbl_all = 'All';
if (isset($app_list_strings['aor_assign_options']) && isset($app_list_strings['aor_assign_options']['all'])) $lbl_all = $app_list_strings['aor_assign_options']['all'];
else if (isset($app_strings['LBL_LISTVIEW_ALL'])) $lbl_all = $app_strings['LBL_LISTVIEW_ALL'];

$trackermodes .= '<option value="all" '.($current_tracker == "all" ? 'selected="selected"' :'').'>' .$lbl_all . '</option>';

$minutes = $Schedulersmod_strings['LBL_MINUTES'];
$frequencies_arr = array(
	'5' => '5 ' . $minutes,
	'10' => '10 ' . $minutes,
	'15' => '15 ' . $minutes,
	'30' => '30 ' . $minutes,
	'60' => '60 ' . $minutes,
);
$frequencies = '';
foreach ($frequencies_arr as $val => $lbl){
	$frequencies .= '<option value="'.$val.'" '.($current_frequency == $val ? 'selected="selected"' :'').'>' . $lbl . '</option>';
}

$tracker_display = 'style="display:none;"';
if (isset($sugar_config['quickcrm_tracker']) && $sugar_config['quickcrm_tracker']){
	$tracker_display ='';
}

$lst_lang = get_languages();
$current_lang_opt=$qutils->mobile['languages'];
$language_options = '<option value="default" '.($current_lang_opt == "default" ? 'selected="selected"' :'').'>' .$lst_lang[$sugar_config['default_language']] . '</option>';
$language_options .= '<option value="all" '.($current_lang_opt == "all" ? 'selected="selected"' :'').'>' .$mod_strings["LBL_ENABLED_LANGS"] . '</option>';

if ($suitecrm){
	$AOSmod_strings=return_module_language($current_language, 'AOS_Products');
	if (!isset($qutils->mobile['productimage'])) $qutils->mobile['productimage'] =true;
	$productimage_checked=($qutils->mobile['productimage']?' checked="checked"':'');
	$productimage_title= 'AOS : ' . $AOSmod_strings['LBL_PRODUCT_IMAGE'];
}
echo <<<EOQ
<table cellpadding="0" cellspacing="0" style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid" width="100%" class="edit view">

<tr>
	<td width="80%" valign="top" style="border-right: 1px">
		<form name="configquickcrm" method="POST" action="index.php">
			<input type="hidden" name="module" value="Administration"/>
			<input type="hidden" name="action" value="settingsquickcrm_save"/>
			<input type="hidden" name="return_module" value="Administration"/>
			<input type="hidden" name="return_action" value="configquickcrm"/>
			<input type="hidden" name="sel_fields" value=""/>
			<input type="hidden" name="conf_module" value=""/>
			<input type="hidden" name="conf_type" value="">

			
		<div id="confpage" style="padding-left: 15px">
			<h1 id="conftitle"></h1><br>
			<input title='{$app_strings['LBL_SAVE_BUTTON_TITLE']}' accessKey='M' class='button' type='submit' name='button' value='{$app_strings['LBL_SAVE_BUTTON_TITLE']}'></input>
			<input title='{$app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='X' class='button' onclick="location.href='index.php?module=Administration&action=index';" type='button' name='button' value='{$app_strings['LBL_CANCEL_BUTTON_TITLE']}'></input>
			<div id="confgeneral">
				<br>
				<br>
				<table>
					<tr {$profile_display}>
						<td id="profilemode_label" width="10%" valign="top" scope="col">
						<label for="profilemode">{$mod_strings['LBL_MULTIVIEW']}</label>
						</td>
						<td width="40%" valign="top">
							<select id="profilemode" name="profilemode" onchange="alertRemove()">
								$profiles
							</select>
						</td>	
					</tr>
					<tr>
						<td id="rowsperpage_label" width="10%" valign="top" scope="col">
						<label for="rowsperpage">{$mod_strings['LBL_ROWSPERPAGE']}</label>
						</td>
						<td width="40%" valign="top">
						<input id="rowsperpage" type="text" tabindex="0" title="" value="{$qutils->mobile['rowsperpage']}" size="10" name="rowsperpage"/>
						</td>	
					</tr>
					<tr>
						<td id="rowspersubpanel_label" width="10%" valign="top" scope="col">
						<label for="rowspersubpanel">{$mod_strings['LBL_ROWSPERSUBPANEL']}</label>
						</td>
						<td width="40%" valign="top">
						<input id="rowspersubpanel" type="text" tabindex="0" title="" value="{$qutils->mobile['rowspersubpanel']}" size="10" name="rowspersubpanel"/>
						</td>	
					</tr>
					<tr>
						<td id="rowsperdashlet_label" width="10%" valign="top" scope="col">
						<label for="rowsperdashlet">{$mod_strings['LBL_ROWSPERDASHLET']}</label>
						</td>
						<td width="40%" valign="top">
						<input id="rowsperdashlet" type="text" tabindex="0" title="" value="{$qutils->mobile['rowsperdashlet']}" size="10" name="rowsperdashlet"/>
						</td>	
					</tr>
					<tr>
						<td id="native_cal_label" width="10%" valign="top" scope="col">
						<label for="native_cal">{$native_cal_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="native_cal" type="checkbox" {$native_cal_checked} title="" name="native_cal">
						</td>	
					</tr>
					<tr>
						<td id="offline_max_days_label" width="10%" valign="top" scope="col">
						<label for="offline_max_days">{$mod_strings['LBL_OFFLINE_MAX']}</label>
						</td>
						<td width="40%" valign="top">
						<input id="offline_max_days" type="text" tabindex="0" title="" value="{$qutils->mobile['offline_max_days']}" size="10" name="offline_max_days"/>
						</td>	
					</tr>
					<tr style="display:none;">
						<td width="10%" valign="top" scope="col">
						<label for="languages">{$mod_strings['LBL_MANAGE_LANGUAGES']}</label>
						</td>
						<td width="40%" valign="top">
							<select id="languages" name="languages" >
								$language_options
							</select>
						</td>	
					</tr>
					<tr>
						<td id="documents_sync_label" width="10%" valign="top" scope="col">
						<label for="documents_sync">{$documents_sync_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="documents_sync" type="checkbox" {$documents_sync_checked} title="" name="documents_sync">
						</td>	
					</tr>
					<tr>
						<td id="audio_notes_label" width="10%" valign="top" scope="col">
						<label for="audio_notes">{$audio_notes_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="audio_notes" type="checkbox" {$audio_notes_checked} title="" name="audio_notes">
						</td>	
					</tr>
					<tr>
						<td id="force_lock_label" width="10%" valign="top" scope="col">
						<label for="force_lock">{$force_lock_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="force_lock" type="checkbox" {$force_lock_checked} title="" name="force_lock">
						</td>	
					</tr>
					<tr>
						<td id="groupusers_label" width="10%" valign="top" scope="col">
						<label for="groupusers">{$groupusers_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="groupusers" type="checkbox" {$groupusers_checked} title="" name="groupusers">
						</td>	
					</tr>
EOQ;
if ($suitecrm){
echo <<<EOQ
					<tr>
						<td id="productimage_label" width="10%" valign="top" scope="col">
						<label for="productimage">{$productimage_title}</label>
						</td>
						<td width="40%" valign="top">
						<input id="productimage" type="checkbox" {$productimage_checked} title="" name="productimage">
						</td>	
					</tr>
EOQ;
}
echo <<<EOQ
					<tr {$tracker_display}>
						<td id="trackermode_label" width="10%" valign="top" scope="col">
						<label for="trackermode">{$app_list_strings["moduleList"]["QCRM_Tracker"]}</label>
						</td>
						<td width="40%" valign="top">
							<select id="trackermode" name="trackermode" onchange="changeTracking()">
								$trackermodes
							</select>
							<select style="display:none;" id="trackerrole" name="trackerrole">
								$trackerroles
							</select>
							<select style="display:none;" id="trackergroup" name="trackergroup">
								$trackergroups
							</select>
							<br>
							<div id="trackerfreqgrp" style="display:none;">
							<label for="trackerfreq">{$Schedulersmod_strings['LBL_EVERY']}</label>
							<select id="trackerfreq" name="trackerfreq">
								$frequencies
							</select>
							</div>
							<div id="trackerviewergrp" style="display:none;">
							<label for="trackerviewer">{$ACLmod_strings['LBL_ACTION_VIEW']}</label>
							<select id="trackerviewer" name="trackerviewer">
								$trackerviewerroles
							</select>
							</div>
						</td>	
					</tr>
				</table>
			</div>
		</div>
		</form>
	</td>
</tr>
</table>
<script>
var current_profile='{$current_profile}';
 function alertRemove(){
 	if (current_profile !='none'){
	 	if (confirm('{$mod_strings['LBL_QREMOVE_CONFIRM']}')){
 			current_profile = $('#profilemode').val();
 		}
 		else {
 			$('#profilemode').val(current_profile);
 		}
 	}
 	else{
 		current_profile = $('#profilemode').val();
 	}
 }
 function changeTracking(){
 	switch($('#trackermode').val()) {
 		case 'ACLRoles' :
 			$('#trackerrole').show();
 			$('#trackergroup').hide();
 			$('#trackerfreqgrp').show();
 			$('#trackerviewergrp').show();
 			break;
 		case 'SecurityGroups' :
 			$('#trackerrole').hide();
 			$('#trackergroup').show();
 			$('#trackerfreqgrp').show();
 			$('#trackerviewergrp').show();
 			break;
 		case 'all' :
 			$('#trackerrole').hide();
 			$('#trackergroup').hide();
 			$('#trackerfreqgrp').show();
 			$('#trackerviewergrp').show();
 			break;
 		default :
 			$('#trackerrole').hide();
 			$('#trackergroup').hide();
 			$('#trackerfreqgrp').hide();
 			$('#trackerviewergrp').hide();
 			break;
 			
 	}
 }
 if ('{$current_tracker_role}' != '') $('#trackerrole').val('{$current_tracker_role}');
 if ('{$current_tracker_group}' != '') $('#trackergroup').val('{$current_tracker_group}');
 $('#trackerviewer').val('{$current_tracker_viewer_role}');
 changeTracking();
</script>
EOQ;
?>