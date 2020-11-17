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
global $titles;

$MBmod_strings=return_module_language($current_language, 'ModuleBuilder');

global $sugar_config;
ini_set("display_errors", 0);




	require_once('modules/QuickCRM/license/OutfittersLicense.php');
	$validate_license = QOutfittersLicense::isValid('QuickCRM');
	if($validate_license !== true) {
		SugarApplication::appendErrorMessage('QuickCRM Full is no longer active due to the following reason: '.$validate_license.' Users will have limited access until the issue has been addressed.');
		echo '<h2><p class="error">QuickCRM Full is no longer active</p></h2><p class="error">Please renew your subscription or check your license configuration.</p>';
		die();
	}



echo getClassicModuleTitle(
    "Administration",
    array(
        "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
        'QuickCRM: ' . $mod_strings['LBL_CONFIG_QUICKCRM_TITLE'],
    ),
    false
);

$titles=array(	'fields'=>$MBmod_strings['LBL_EDITVIEW'],
				'detail'=>$MBmod_strings['LBL_DETAILVIEW'],
				'search'=>$mod_strings['LBL_SEARCH_FIELDS_TITLE'],
				'popupsearch'=>$MBmod_strings['LBL_POPUPSEARCH'],
				'list'=>$MBmod_strings['LBL_LISTVIEW'],
				'subpanels'=> $mod_strings['LBL_VISIBLE_PANELS']
	);
	
require_once('custom/modules/Administration/QuickCRM_utils.php');
require_once('include/ytree/Tree.php');
require_once('include/ytree/Node.php');
$qutils=new QUtils();
$qutils->LoadMobileConfig(true); 

$QuickCRM_modules= $qutils->mobile['modules'];

$conftree=new Tree('conftree');
$conftree->set_param('module','Administration');

echo $conftree->generate_header();

if ($sugar_config['sugar_version']<'6.5.16'){
	echo <<<EOQ
	<script type="text/javascript" src="custom/QuickCRM/lib/js/jquery-1.7.2.min.js"></script>
EOQ;
}
if (!suitecrmVersion() || !suitecrmVersionisAtLeast('7.2')) {
	echo <<<EOQ
	<script type="text/javascript" src="custom/QuickCRM/lib/js/jquery-ui-1.8.21.custom.min.js"></script>
EOQ;
}

echo <<<EOQ
<link rel="stylesheet" href="custom/QuickCRM/lib/css/ui-lightness/jquery-ui-1.8.21.custom.css" type="text/css" media="all" />
<script type="text/javascript" src="custom/modules/Administration/conf_quickcrm.js?v=6.0"></script>
<style>
    .myOverlayClass  {
        background:transparent !important;
        z-index: 1000 !important;
    }
    .ui-state-highlight  {
	    height: auto !important;
    }
    .ui-state-highlight.label_sortable{
        background: #bfcad3;
    }
</style>
<div id="StatusDiv" style="height:20px;text-align:center;"></div>
<table cellpadding="0" cellspacing="0" style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid" width="100%" class="edit view">
<tr>
	<td width="20%" valign="top" style="border-right: 1px solid">
		<div id="conftree">
			
		</div>
	</td>
	<td width="80%" valign="top" style="border-right: 1px">
		<form name="configquickcrm" method="POST" action="index.php">
			<div id="confpage" style="padding-left: 15px">
			</div>
		</form>
	</td>
</tr>
</table>
<div id="dialog-confirm" title="{$MBmod_strings['LBL_BTN_SAVE_CHANGES']}?" style="display:none">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
EOQ;
echo $MBmod_strings['LBL_CONFIRM_DONT_SAVE'];
echo <<<EOQ
  </p>
</div>
EOQ;
?>

<script type="application/javascript">
    var confirm_message_text = '<?php echo $MBmod_strings['LBL_CONFIRM_DONT_SAVE'];?>';
    var currentType = 'none';
    var currentModule = 'none';
    var currentProfile = '_default';
    var defaultProfileName = "<?php echo $mod_strings['LBL_QDEFAULT']?>";
    var currentProfileName = defaultProfileName;
    var typetoredirect='none';
    var moduletoredirect='none';
    var profiletoredirect='_default';
    var profileNametoredirect='_default';

    var def = true;
    var def2 = true;
    $( window ).on( 'beforeunload' , function() {
        return check_condition(currentModule,currentType,currentProfile,currentProfileName,true);
    } );
    function checkModuleType(module,type,profile,profile_name){
        typetoredirect=type;
        moduletoredirect=module;
        profiletoredirect=profile;
        profileNametoredirect=profile;
        if(check_condition(module,type,profile,profile_name,false)){
            currentType= type;
            currentModule= module;
            currentProfile= profile;
            currentProfileName= profile_name || defaultProfileName;
        }
    }

    function check_condition(module,type,profile,profile_name,changebyrefresing){
    	if (!profile_name) profile_name=defaultProfileName;
        if( currentType == 'none' && currentModule=='none')
        {
            currentType=type;
            currentModule=module;
            currentProfile= profile;
            currentProfileName= profile_name;
        }
        if($('#conf_sortableD1 li').hasClass('ui-state-default') || $('#conf_sortableD2 li').hasClass('ui-state-highlight') || def!=def2){
            if(changebyrefresing==true){
                return  '<?php echo $MBmod_strings['LBL_CONFIRM_DONT_SAVE']?>';
            }else {

                $(function() {
                    $( "#dialog-confirm" ).dialog({
                        resizable: false,
                        height:210,
                        width:400,
                        modal: true,
                        buttons: {
                            '<?php echo $MBmod_strings['LBL_BTN_DONT_SAVE']?>': function() {

                                def=true;
                                def2=true;
                                if(changebyrefresing==false)
                                    conf_get(module,type,profile,profile_name)
                                $( this ).dialog( "close" );
                            },
                            '<?php echo $MBmod_strings['LBL_BTN_CANCEL']?>': function() {
                                $( this ).dialog( "close" );
                            },
                            '<?php echo $MBmod_strings['LBL_BTN_SAVE_CHANGES']?>': function() {
                                $( this ).dialog( "close" );
                                    return  SaveFields('changeModule',currentModule,currentType,currentProfile,currentProfileName||defaultProfileName)

                            }
                        }
                    });
                    $(".ui-widget-overlay" ).addClass('myOverlayClass');
                });
            }

        }
        else {

            if( currentType != type  ||  currentModule!=module ||  currentProfile!=profile)
            {
                currentType=type;
                currentModule=module;
	            currentProfile= profile;
	            currentProfileName= profile_name;
            }
            conf_get(module,type,profile,profile_name)

        }

    }
</script>