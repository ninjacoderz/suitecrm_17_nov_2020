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


{assign var="alt_start" value=$navStrings.start}
{assign var="alt_next" value=$navStrings.next}
{assign var="alt_prev" value=$navStrings.previous}
{assign var="alt_end" value=$navStrings.end}

{if !isset($hideColumnFilter)}
    {assign var="currentModule" value = $pageData.bean.moduleDir}
    {assign var="hideColumnFilter" value = false}

    {php}
      $currentModule = $this->get_template_vars('currentModule');
      $APP_CONFIG = $this->get_template_vars("APP_CONFIG");

      if (
          isset($APP_CONFIG['hideColumnFilter'][$currentModule])
           && $APP_CONFIG['hideColumnFilter'][$currentModule] == true
        ) {
    {/php}
          {assign var="hideColumnFilter" value = true}
    {php}
        }
    {/php}
{/if}

	<tr id='pagination' class="pagination-unique" role='presentation'>
		<td colspan='{if $prerow}{$colCount+1}{else}{$colCount}{/if}'>
			<table border='0' cellpadding='0' cellspacing='0' width='100%' class='paginationTable'>
				<tr>
					<td nowrap="nowrap" class='paginationActionButtons'>
						{if $prerow}

                        {sugar_action_menu id=$link_select_id params=$selectLink}
					
						{/if}
						{sugar_action_menu id=$link_action_id params=$actionsLink}
                        {if $actionDisabledLink ne ""}<div class='selectActionsDisabled' id='select_actions_disabled_{$action_menu_location}'>{$actionDisabledLink}</div>{/if}
                        {include file='include/ListView/ListViewButtons.tpl'}
						{if $showFilterIcon}
							{include file='include/ListView/ListViewSearchLink.tpl'}
						{/if}
      {*{if empty($hideColumnFilter)}*}
          {include file='include/ListView/ListViewColumnsFilterLink.tpl'}
						&nbsp;{$selectedObjectsSpan}

						{*dung code - add field search quickly - PO number*} 
						{if $moduleName == 'Purchase Order'}
							<input type="button" width="1%" id="button_search_PO_num" value="search PO Number" />
							<input type="text" width="1%" name="PO_num_search" id="PO_num_search"/>
							<script>
								{literal}
									$('#button_search_PO_num').click(function(){
										if($('#PO_num_search').val() !== ''){						
												$('#number_advanced').val($('#PO_num_search').val());
												$('#search_form_submit_advanced').trigger('click');
										}
									})
								{/literal}
							</script>
						{/if}
						{*VUT-S-add field search quickly - Quote number*}
						{if $moduleName == 'Quotes'}
							<input type="button" width="1%" id="button_search_quote_num" value="search Quote Number" />
							<input type="text" width="1%" name="quote_num_search" id="quote_num_search"/>
							<script>
								{literal}
									$('#button_search_quote_num').click(function(){
										if($('#quote_num_search').val() !== ''){						
												$('#number_advanced').val($('#quote_num_search').val());
												$('#search_form_submit_advanced').trigger('click');
										}
									})
								{/literal}
							</script>
						{/if}
						{*VUT-E-add field search quickly - Quote number*}

						{*VUT-S-add field inbound - Calls*}
						{if $moduleName == 'Calls'}
							<select id="direction_status">
								<option label="" status="" value=""></option>
								<option status="Planned" value='Inbound'>Inbound Planned</option>
								<option status="Held" value='Inbound'>Inbound Held</option>
								<option status="Not Held" value='Inbound'>Inbound Not Held</option>
								<option status="Planned" value='Outbound'>Outbound Planned</option>
								<option status="Held" value='Outbound'>Outbound Held</option>
								<option status="Not Held" value='Outbound'>Outbound Not Held</option>
							</select>
							<input type="button" width="1%" id="filter_status" value="Filter" />
							<script>
								{literal}
									$('#filter_status').click(function(){
										if($('#direction_status').val() !== ''){						
												$('#direction_advanced').val($('#direction_status').val());
												$('#status_advanced').val($('#direction_status').find('option:selected').attr('status'));
												$('#parent_type_advanced').val('');
												if ( $( "#direction_status" ).length ) {
													window.location.href = '/index.php?action=index&module=Calls&searchFormTab=advanced_search&query=true&clear_query=true&status_advanced='+$('#direction_status').find('option:selected').attr('status')+'&direction_advanced='+$('#direction_status').val();
												}
												$('#search_form_submit_advanced').trigger('click');
										}
									})
								{/literal}
							</script>
						{/if}
						{*VUT-E-add field inbound - Calls*}

						{if $moduleName == 'Stock Items'}
							<input id="serial_number_filter" type="text" name="serial_number_" placeholder="Serial number">
							<input type="button" class="button" id="custom_search_serial_number"  value="Search">
							<script>
								{literal}
									$('#custom_search_serial_number').on('click',function(){
										if($('#serial_number_filter').val() !== ''){						
												$('#serial_number_advanced').val($('#serial_number_filter').val());
												$('#search_form_submit_advanced').trigger('click');
												if($('#search_form_submit').length) {
													$('#serial_number_basic').val($('#serial_number_filter').val());
													$('#search_form_submit').trigger('click');
												}
										}
									})
								{/literal}
							</script>
						{/if}				
						{*Thienpb code - add field get status //*}
						{if $moduleName == 'Warehouse Log'}
							<input type="button" width="1%" id="button_get_status" value="get status" />
							<input type="button" width="1%" id="button_filter_status" value="uncompleted whlogs" />
							<script>
								{literal}
									$('#button_get_status').click(function(){
                		SUGAR.ajaxUI.showLoadingPanel();
										var list_id = [];
										$("#MassUpdate").find('input[name="mass[]"]:checked').each(function(){
											list_id.push($(this).val());
										})
										setTimeout(function (){
											$.ajax({
												url: 'index.php?entryPoint=getWarehouseLogStatus',
												data : {'list_id':list_id},
												method : 'POST',
												async : false,
												success: function(data){
													SUGAR.ajaxUI.hideLoadingPanel();
													location.reload();
												}
											});
										}, 2000);
									})
									$('#button_filter_status').click(function(){
										var values = ['Collecting','Collected','Delivering','In transit','Allocated','Collect','Pending']
										$("#status_c_advanced option").prop("selected", function () {
											return ~$.inArray(this.text, values);
										});
										$('#search_form_submit_advanced').trigger('click');
									});
								{/literal}
							</script>
						{/if}

						{*dung code - add field get status GEO*}
						{if $moduleName == 'Invoices'}
							<input type="button" width="1%" id="button_get_status_geo" value="GEO STATUS" />
							<input type="button" width="1%" id="button_search_number" value="search  Number" />
							<input type="text" width="1%" name="number_search" id="number_search"/>
							<script>
								{literal}
									$('#button_get_status_geo').click(function(){
										var list_id = [];
										$("#MassUpdate").find('input[name="mass[]"]:checked').each(function(){
											list_id.push($(this).val());
										})

										 $.ajax({
											url: 'index.php?entryPoint=getStatusGEO',
											data : {'list_id':list_id},
											method : 'POST',
											async : false,
											success: function(data){
												location.reload();
											}
										 });
									})
									$('#button_search_number').click(function(){
										if($('#number_search').val() !== ''){						
												$('#number_advanced').val($('#number_search').val());
												$('#search_form_submit_advanced').trigger('click');
										}
									})
								{/literal}
							</script>
						{/if}

						{if $moduleName == 'Quotes' or $moduleName == 'Leads' or $moduleName == 'Invoices'}
							<ul>
								<li>
									<button data-toggle="collapse" href="#custom_date_entered_advanced_between_range_div" id="toggle_search_date_create" type="button" onclick="return false" class="btn btn-danger"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
									<ul>
										<li><div id="custom_date_entered_advanced_between_range_div" class="collapse">
											Is Between
											<input autocomplete="off" type="text" name="custom_start_range_date_entered_advanced" id="custom_start_range_date_entered_advanced" value="" title="" tabindex="" size="11" class="dateRangeInput">
											<button id="custom_start_range_date_entered" type="button" onclick="return false" class="btn btn-danger"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
											<script >
												{literal}
													Calendar.setup ({
														inputField : "custom_start_range_date_entered_advanced",
														daFormat : "%d/%m/%Y %H:%M",
														button : "custom_start_range_date_entered",
														singleClick : true,
														dateStr : "",
														step : 1,
														weekNumbers:false
														}
													);
												{/literal}
											</script>
											And
											<input autocomplete="off" type="text" name="custom_end_range_date_entered_advanced" id="custom_end_range_date_entered_advanced" value="" title="" tabindex="" size="11" class="dateRangeInput" maxlength="10">
												<button id="custom_end_range_date_entered" type="button" onclick="return false" class="btn btn-danger">
													<span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span>
												</button>
											<script>
												{literal}
													Calendar.setup ({
														inputField : "custom_end_range_date_entered_advanced",
														daFormat : "%d/%m/%Y %H:%M",
														button : "custom_end_range_date_entered",
														singleClick : true,
														dateStr : "",
														step : 1,
														weekNumbers:false
														}
													);
												{/literal}
											</script>
											<button id="custom_search_range_date_create" type="button" class="btn btn-default suitepicon suitepicon-action-search"></button>			
										</div></li>
									</ul>
								</li>
							</ul>
							<script>
								{literal}
									$('#custom_start_range_date_entered_advanced').val($('#start_range_date_entered_advanced').val());
									$('#custom_end_range_date_entered_advanced').val($('#end_range_date_entered_advanced').val());
									
									$('#custom_search_range_date_create').click(function(){
										if (!($('#end_range_date_entered_advanced').length)) {
											window.location.href = 'index.php?action=index&module='+currentModule+'&searchFormTab=advanced_search&query=true&clear_query=true&start_range_date_entered_advanced='+$('#custom_start_range_date_entered_advanced').val()+'&end_range_date_entered_advanced='+$('#custom_end_range_date_entered_advanced').val();
										}
										$('#start_range_date_entered_advanced').val($('#custom_start_range_date_entered_advanced').val());
										$('#end_range_date_entered_advanced').val($('#custom_end_range_date_entered_advanced').val());
										$("#date_entered_advanced_range_choice").val('between');
										$('#search_form_submit_advanced').trigger('click');						
									})
								{/literal}
							</script>

							<style>
								{literal}
									#custom_date_entered_advanced_between_range_div{
										padding: 5px 15px;
										position: absolute;
										z-index: 100;
										background: white;
										width: auto;
										border: 1px solid black;
										border-top: none;
									}
								{/literal}
							</style>
						{/if}
					</td>
					<td  nowrap='nowrap' align="right" class='paginationChangeButtons' width="1%">
						{if $pageData.urls.startPage}
							<button type='button' id='listViewStartButton_{$action_menu_location}' name='listViewStartButton' title='{$navStrings.start}' class='list-view-pagination-button' {if $prerow}onclick='return sListView.save_checks(0, "{$moduleString}");'{else} onClick='location.href="{$pageData.urls.startPage}"' {/if}>
								<span class='suitepicon suitepicon-action-first'></span>
							</button>
						{else}
							<button type='button' id='listViewStartButton_{$action_menu_location}' name='listViewStartButton' title='{$navStrings.start}' class='list-view-pagination-button' disabled='disabled'>
								<span class='suitepicon suitepicon-action-first'></span>
							</button>
						{/if}
						{if $pageData.urls.prevPage}
							<button type='button' id='listViewPrevButton_{$action_menu_location}' name='listViewPrevButton' title='{$navStrings.previous}' class='list-view-pagination-button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.prev}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.prevPage}"'{/if}>
								<span class='suitepicon suitepicon-action-left'></span>
							</button>
						{else}
							<button type='button' id='listViewPrevButton_{$action_menu_location}' name='listViewPrevButton' class='list-view-pagination-button' title='{$navStrings.previous}' disabled='disabled'>
								<span class='suitepicon suitepicon-action-left'></span>
							</button>
						{/if}
					</td>
					<td nowrap='nowrap' width="1%" class="paginationActionButtons">
						<div class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</div>
					</td>
					<td nowrap='nowrap' align="right" class='paginationActionButtons' width="1%">
						{if $pageData.urls.nextPage}
							<button type='button' id='listViewNextButton_{$action_menu_location}' name='listViewNextButton' title='{$navStrings.next}' class='list-view-pagination-button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.next}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.nextPage}"'{/if}>
								<span class='suitepicon suitepicon-action-right'></span>
							</button>
						{else}
							<button type='button' id='listViewNextButton_{$action_menu_location}' name='listViewNextButton' class='list-view-pagination-button' title='{$navStrings.next}' disabled='disabled'>
								<span class='suitepicon suitepicon-action-right'></span>
							</button>
						{/if}
						{if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
							<button type='button' id='listViewEndButton_{$action_menu_location}' name='listViewEndButton' title='{$navStrings.end}' class='list-view-pagination-button' {if $prerow}onclick='return sListView.save_checks("end", "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.endPage}"'{/if}>
								<span class='suitepicon suitepicon-action-last'></span>
							</button>
						{elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
							<button type='button' id='listViewEndButton_{$action_menu_location}' name='listViewEndButton' title='{$navStrings.end}' class='list-view-pagination-button' disabled='disabled'>
								<span class='suitepicon suitepicon-action-last'></span>
							</button>
						{/if}
					</td>
					<td nowrap='nowrap' width="4px" class="paginationActionButtons"></td>
				</tr>
			</table>
		</td>
	</tr>
