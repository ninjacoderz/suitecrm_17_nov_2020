<h1 id="conftitle">{$TITLE}</h1><br>
<h2 id="confwarning">{$HEADER_WARNING}</h2>
{if $groups_defined}
{else}
	<table align="center" cellspacing="7" width="90%" ><tr>
		{foreach from=$buttons item='button' key='buttonName'}
			{ if !isset($button.size)}
				{assign var='buttonsize' value=''}
			{else}
				{assign var='buttonsize' value=$button.size}
			{/if}
			<td {if isset($button.help)}id="{$button.help}"{/if} width="16%" name=helpable" style="padding: 5px;"  valign="top" align="center">
			     <table onclick="javascript:checkModuleType('{$module}','{$buttonName}','_default');"
			         class='wizardButton'>
			         <tr>
						<td align="center"><a class='studiolink' href="javascript:void(0)" >
						{if isset($button.imageName)}
                                {sugar_image name=$button.title width=48 height=48 image=$button.imageName}                            
						{else}
							{sugar_image name=$button.title width=$button.size height=$button.size}
						{/if}</a></td>
					 </tr>
					 <tr>
						 <td align="center"><a class='studiolink' id='{$button.linkId}' href="javascript:void(0)">
				            {$button.title}</a></td>
				     </tr>
				 </table>
			</td>
		{/foreach}
	</tr></table>
{/if}

	<table cellspacing="7" width="50%">
	<tr>
	<td style="width:200px;">&nbsp;{$MOD.LBL_QSHOW_ICON}</td>
	<td>
		<input id="chkshow_icon" name="chkshow_icon" type="checkbox" {if $showicon}checked="checked"{/if}>
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td style="width:200px;" id="div_subpanel_only">&nbsp;{$MOD.LBL_CREATE_SUBPANEL_ONLY}</td>
	<td>
		<input id="chksubpanel_only" name="chksubpanel_only" type="checkbox" {if $subpanelonly}checked="checked"{/if}>
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	</table>
<input title='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}' accessKey='M' class='button' onclick="return SaveModuleConfig(this,'{$module}');" type='button' name='button' value='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}'></input>
<input title='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}' accessKey='X' class='button' onclick="location.href='index.php?module=Administration&action=index';" type='button' name='button' value='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}'></input>
<div id="setprofiles" {if $hideprofiles}style="display:none;"{/if}>
	<br>
	<br>
	<table align="center" cellspacing="7" width="90%">
	<tr>
	<td>{$MOD.LBL_QADD_LAYOUT} ({$group_mode})</td>
	<td>
		<select id="new_profile" name="new_profile">
			{html_options options=$available_groups}
		</select>
	</td>
	<td>{$MOD.LBL_QCOPY_FROM}</td>
	<td>
		<select id="copy_from" name="copy_from">
			{html_options options=$copy_from}
		</select>
	</td>
	</tr>
	</table>
	<input title='{$MOD.LBL_QADD_LAYOUT}' accessKey='M' class='button' onclick="return SaveFields(this,'{$module}','module');" type='button' name='button' value='{$MOD.LBL_QADD_LAYOUT}'></input>
	<input title='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}' accessKey='X' class='button' onclick="location.href='index.php?module=Administration&action=index';" type='button' name='button' value='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}'></input>
</div>