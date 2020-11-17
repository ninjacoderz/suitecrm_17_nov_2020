{*
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
*}
{if file_exists('custom/themes/SuiteP/tpls/_head.tpl')}
    {include file="custom/themes/SuiteP/tpls/_head.tpl"}
{else}
    {include file="themes/SuiteP/tpls/_head.tpl"}
{/if}
<body onMouseOut="closeMenus();">

{if $AUTHENTICATED}
    <div id="ajaxHeader">
        {include file="themes/SuiteP/tpls/_headerModuleList.tpl"}
    </div>
{/if}
{literal}
    <iframe id='ajaxUI-history-iframe' src='index.php?entryPoint=getImage&imageName=blank.png' title='empty'
            style='display:none'></iframe>
<input id='ajaxUI-history-field' type='hidden'>
<script type='text/javascript'>
    if (SUGAR.ajaxUI && !SUGAR.ajaxUI.hist_loaded) {
        YAHOO.util.History.register('ajaxUILoc', "", SUGAR.ajaxUI.go);
        {/literal}{if $smarty.request.module != "ModuleBuilder"}{* Module builder will init YUI history on its own *}
        YAHOO.util.History.initialize("ajaxUI-history-field", "ajaxUI-history-iframe");
        {/if}{literal}
    }
</script>
{/literal}
{* Dung NV *}
<script type="text/javascript" src="themes/SuiteP/js/schedule/kendo.all.min.js"></script>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.common.min.css'>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.common-material.min.css'>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.default.min.css'>
<link rel='stylesheet' href='custom/include/fancybox/jquery.fancybox.css'>
<script type="text/javascript" src='custom/include/fancybox/jquery.fancybox.pack.js'></script>
{*dung code - dialog send sms*}
<!-- dialog -->
    <div id = "dialog_send_sms" title="Send SMS" hidden>
		<div id="sms">
			<div>
				<div class="label" >Phone Number :</div>
				<span class="input" id="phone_number_customer"></span>
			</div>
			<div>
				<div class="label" >From Phone Number :</div>
				<select style="width:170px;margin-bottom:2px;" id="from_phone_number" >
					<option label="+61490942067" value="+61490942067">+61490942067</option>
					<option label="+61421616733" value="+61421616733">+61421616733</option>
				</select>
			</div>
		{* VUT-S *}
			<div>
				<div class="label" >SMS Template:
					<select style="width:500px;margin-bottom:2px;" id="message_template">

					</select>
				</div>
			</div>
		{* VUT-E *}
			<div>
				<div class="label" style="display:none;">SMS Template :
					<input type="text" name="sms_template_name" id="sms_template_name" value=""/>
					<input hidden type="text" name="sms_template_id" id="sms_template_id" value=""/>
					<span class="id-ff multiple">
						<button title="Select" accesskey="T" type="button" tabindex="116" id="button_select_sms_template" class="button button_select_sms_template" value="Select" name="btn1"><span class="suitepicon suitepicon-action-select"></span></button>
						<button type="button" tabindex="0"  id="button_reset_sms_template" class="button lastChild" ><span class="suitepicon suitepicon-action-clear"></span></button>
					</span>
				</div>
				<div class="label"> SMS Signture :
					<select style="width:500px;margin-bottom:2px;" id="select_sms_signture_dialog">
					</select>
				</div><br>
				<div class="label">Message :
					<select style="width:500px;margin-bottom:2px;display:none;" id="messager_template">
						<option  value=""></option>
						<option  value="Hi [FirstName], I have prepared your solar PV quote (you'll this from under my colleague Matthew's name) along with an email explaining the panel/inverter choices and appropriate datasheets. Look forward to your thoughts. Regards, Paul">Hi [FirstName], I have prepared your solar PV quote (you'll this from under my colleague Matthew's name) along with an email explaining the panel/inverter choices and appropriate datasheets. Look forward to your thoughts. Regards, Paul</option>
					</select>
				</div>
				<div class="input">
					<textarea id="content_messager" style="width:100%;height:200px;">
					</textarea>
					<textarea id="sms_signture_dialog" style="display:none;">
					</textarea>
					<textarea id="sms_message_dialog" style="display:none;">
					</textarea>
				</div>
			</div>
			<div class="sms-schedule">
				<button id="btnSendLater" type="button">Set schedule</button> <span class='result-schedule'>5/30/2019 1:30 AM</span>
				<div class="popup popup-sendMail" id="sendMailLater">
                    <ul class=listOption>
                        <div class="form-control">
							<li class="optionItem chooseAction" data-time='one-hour'>In 1 hour</li>
							<li class="optionItem chooseAction" data-time='two-hour'>In 2 hours</li>
							<li class="optionItem chooseAction" data-time='three-hour'>In 3 hours</li>
							<li class="optionItem chooseAction" data-time='two-days'>In 2 days</li>
							<li class="optionItem chooseAction" data-time='four-days'>In 4 days</li>
                        </div>
                        <hr>
                        <div class="form-control">
							<p class='pd30'>At a specific time:</p> 
							<div class='confirm'>
								<span class='pd30'>Examples: 'Monday 9am', 'Dec 23'</span>
							</div>
							<div class='pd30 mt10'>
								<input type='hidden' id='dataTimestamp' value='' name='dataTimestamp'>
								<input class='' id='datetimepicker' value='' title='datetimepicker' />
								<div class='mt10'>
									<p class='valueTime'></p>
									<a href='javascript:void(0)' class='btnSuccess'>Confirm</a>
								</div>
							</div>
                        </div>
                    </ul>
                </div>
			</div>
			{include file="themes/SuiteP/tpls/custom_uploadfile_sms.tpl"}
		</div>
	</div>

<!-- Start of page content -->
{if $AUTHENTICATED}
<div id="bootstrap-container"
     class="{if $THEME_CONFIG.display_sidebar && $smarty.cookies.sidebartoggle|default:'' != 'collapsed'}col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2{/if} main bootstrap-container">
    <div id="content" class="content">
        <div id="pagecontent" class=".pagecontent">
{/if}
