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
{*
 /*
  * This template is now displays to the sub panel
  */
*}
<table cellpadding="0" cellspacing="0" border="0" class="list view table-responsive subpanel-table" data-empty="{$APP.MSG_LIST_VIEW_NO_RESULTS_BASIC}" {literal}data-breakpoints='{ "xs": 754, "sm": 750, "md": 768, "lg": 992}'{/literal}>
    <thead>
        <tr class="footable-header">
            {counter start=0 name="colCounter" print=false assign="colCounter"}
            <th data-type="html"><!-- extra th for the plus button -->&nbsp;</th>
            <th data-type="html"><!-- extra th for the plus button -->&nbsp;</th>
            {foreach from=$HEADER_CELLS key=colHeader item=header}
                {* calculate break points for footable *}
                {if $colCounter <= 1}
                    {capture assign="breakpoints"}1{/capture}
                {/if}

                {if $colCounter >= 2 && $colCounter < 5}
                    {capture assign="breakpoints"}xs sm{/capture}
                {/if}

                {if $colCounter >= 5 && $colCounter}
                    {capture assign="breakpoints"}xs sm md{/capture}
                {/if}
                <th data-breakpoints="{if $breakpoints != 1}{$breakpoints}{/if}" data-type="html">{$header}</th>
                {counter name="colCounter" print=false}
            {/foreach}
            <th data-type="html"><!-- extra th for the button --></th>
        </tr>
        {* TODO: Break $pagination so that it can be fully customisable *}
        {$PAGINATION}

    {* Dung code - filter serial number in subpanel *}
    {if $SUBPANEL_ID == 'pe_warehouse_pe_stock_items_1'}
        <tr >
            <td >
                <input id="serial_number_filter" type="text" name="serial_number_filter" placeholder="Serial number">
            </td>
            <td>    
                <input type="submit" id="custom_filter_serial_number" value="Search">
            </td>
            <script>
                {literal}
                    $("#custom_filter_serial_number").click(function(){
                        var record_id = $('input[name="record"]').val();
                        var serial_number_filter = $('#serial_number_filter').val();
                        javascript:showSubPanel('pe_warehouse_pe_stock_items_1','/index.php?module=pe_warehouse&offset=15&stamp=1544600263035344200&return_module=pe_warehouse&action=DetailView&record='+record_id+'&ajax_load=1&loadLanguageJS=1&pe_warehouse_pe_warehouse_pe_stock_items_1_CELL_offset=&inline=true&to_pdf=true&action=SubPanelViewer&subpanel=pe_warehouse_pe_stock_items_1&pe_warehouse_pe_warehouse_pe_stock_items_1_CELL_ORDER_BY=name&layout_def_key=pe_warehouse&serial_number_filter='+serial_number_filter,true);
                    })
                {/literal}
            </script>
        </tr>
    {/if}
        <tr id="{$SUBPANEL_ID}_search" class="pagination" style="{$DISPLAY_SPS}">
            <td align="right" colspan="20">
                {$SUBPANEL_SEARCH}
            </td>
        </tr>
    </thead>
    <tbody>
    {counter start=0 name="rowCounter" print=false assign="rowCounter"}
    {foreach from=$ROWS key=rowHeader item=row}
        {if $rowCounter % 2 == 0}
            {*Odd row*}
            {assign var="rowClass" value="oddListRowS1"}
        {else}
            {*Even row*}
            {assign var="rowClass" value="evenListRowS1"}
        {/if}
        <tr class="{$rowClass}" >
            <td>&nbsp;</td>
            {foreach from=$row key=colHeader item=cell}
                {*
                    /*
                    * Thienpb fix
                    */
                *}
                {if $SUBPANEL_ID == 'opportunities' && $RELATED_MODULE == 'Accounts'}
                    <td class="inlineEdit" type='{$FIELD_TYPE.$colHeader.type}' field="{$colHeader}" current-module="{$SUBPANEL_ID}" record-id="{$rowHeader}">{$cell}<div class="inlineEditIcon">{sugar_getimage name="inline_edit_icon.svg" attr='border="0" ' alt=""}</div></td>
                {elseif $SUBPANEL_ID == 'history' && ($RELATED_MODULE == 'Accounts' || $RELATED_MODULE == 'AOS_Quotes') && $colHeader == 'status'}
                    <td >{$cell} <a class="preview_function">Preview</a></td>
                {else}
                    <td >{$cell}</td>

                {/if}
            {/foreach}
            <td>
                {if isset($ROWS_BUTTONS.$rowHeader) and  $ROWS_BUTTONS.$rowHeader|@count gt 0}
                    {sugar_action_menu id="$rowHeader" buttons=$ROWS_BUTTONS.$rowHeader class="" flat=false}
                {/if}
            </td>
        </tr>
        {counter name="rowCounter" print=false}
    {/foreach}
    </tbody>
</table>