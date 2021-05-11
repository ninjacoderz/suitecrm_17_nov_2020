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
{literal}
    <style>
        .menu {
            z-index: 100;
        }

        .subDmenu {
            z-index: 100;
        }

        div.moduleTitle {
            height: 10px;
        }
    </style>
{/literal}



{sugar_getscript file="cache/include/javascript/sugar_grp_yui_widgets.js"}
{sugar_getscript file='include/javascript/dashlets.js'}

{$chartResources}
{$mySugarChartResources}
<div class="dashboard">
<!-- Dung code --display tab -->
    {*display tabs*}
    <span class="folder_info"></span>
    <ul style="margin-bottom:10px">
        <li style="float:left">
            <a class="button moveSandenForm" href="https://pure-electric.com.au/pe-sanden-quote-form/master" target="_blank" style="background:#945596;color:white;padding:8.3px;">SANDEN QUOTE FORM</a>
        </li>

        <li style="float:left">
            <a class="button moveSandenForm" href="https://pure-electric.com.au/pesolarform/master" target="_blank" style="background:#f48c21;color:white;padding:8.3px;margin-left: 4px;">SOLAR QUOTE FORM</a>
        </li>
        <li style="float:left">
            <a class="button moveSandenForm" href="https://pure-electric.com.au/pedaikinform-new" target="_blank" style="background:#009acf;color:white;padding:8.3px;margin-left: 4px;">DAIKIN QUOTE FORM </a>
        </li>
        <li style="float:left">
            <button type="button" class="button primary" style="margin:0 4px;" onClick="updateGGShett();">Update GG Sheet Order SAM</button>
        </li>
    </ul>
    <ul class="nav nav-tabs nav-dashboard">

        {foreach from=$dashboardPages key=tabNum item=tab}
            {if $tabNum == 0}
                <li role="presentation" class="active">
                    <a id="tab{$tabNum}" href="#tab_content_{$tabNum}" data-toggle="tab" {if !$lock_homepage}ondblclick="renameTab({$tabNum})"{/if} onClick="retrievePage({$tabNum});" class="hidden-xs">
                        {$dashboardPages.$tabNum.pageTitle}
                    </a>

                    <a id="xstab{$tabNum}" href="#" class="visible-xs first-tab-xs dropdown-toggle" data-toggle="dropdown">
                        {$dashboardPages.$tabNum.pageTitle}
                        <span class="suitepicon suitepicon-action-caret"></span>
                    </a>
                    <ul id="first-tab-menu-xs" class="dropdown-menu">
                        {counter name="tabCountXS" start=-1 print=false assign="tabCountXS"}
                        {foreach from=$dashboardPages key=ta item=xstab}
                            {counter name="tabCountXS" print=false}
                            <li role="presentation">
                                <a id="tabxs{$tabCountXS}" href="#tab_content_{$tabCountXS}" data-toggle="tab"  onClick="retrievePage({$tabCountXS});">
                                    {$dashboardPages.$tabCountXS.pageTitle}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </li>
            {else}
                <li role="presentation">
                    <a id="tab{$tabNum}" href="#tab_content_{$tabNum}"  data-toggle="tab"  {if !$lock_homepage}ondblclick="renameTab({$tabNum})"{/if} onClick="retrievePage({$tabNum});" class="hidden-xs">
                        {$dashboardPages.$tabNum.pageTitle}
                    </a>
                </li>
            {/if}
        {/foreach}

        {if !$lock_homepage}
            <li id="tab-actions" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">{$APP.LBL_LINK_ACTIONS}<span class="suitepicon suitepicon-action-caret"></span></a>
                {include file='themes/SuiteP/include/MySugar/tpls/actions_menu.tpl'}
            </li>
        {/if}
        <!-- Dung code -- button status email daily -->
        {if !$lock_homepage}
            {literal}
                <script type='text/javascript'>
                    function update_install_dates_from_SG(){
                        $.ajax({
                            url:"?entryPoint=button_update_install_dates_from_SG",
                        })
                    }
                    function Solargain_STATUS_EMAIL(){
                        $.ajax({
                            url:"?entryPoint=button_Solargain_STATUS_EMAIL",
                        })
                    }
                    function Site_Inspection(){
                        $.ajax({
                            url:"?entryPoint=button_Solargain_Site_Inspections",
                        })
                    }
                    function Sanden_Dealers(){
                        $.ajax({
                            url:"?entryPoint=button_Sanden_Dealers",
                        })
                    }

                    //thienpb code 
                    function updateGGShett(){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $.ajax({
                            url:"?entryPoint=APIUpdateGGSheet",
                            type: 'GET',
                            success: function(data)
                            {
                                if(data == '' && typeof data == undefined){ 
                                    SUGAR.ajaxUI.hideLoadingPanel(); return; 
                                }
                                var data_json =  $.parseJSON(data);
                                if(data_json['errors'] == ''){
                                    alert("Update spreadsheets successfully.");
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }else{
                                    alert("We can not update the spreadsheets.");
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            }
                        })
                    }
                </script>
            {/literal}
            <li>
               <a href="#" class="hidden-xs" onClick="Solargain_STATUS_EMAIL()">
                    SG STATUS EMAIL
                </a>
            </li>
            <li>
               <a href="#" class="hidden-xs" onClick="update_install_dates_from_SG()">
                   Update Install Dates From SG
                </a>
            </li>
            <li>
               <a href="#" class="hidden-xs" onClick="Site_Inspection()">
                    Site Inspection
                </a>
            </li>
            <li>
               <a href="#" class="hidden-xs" onClick="Sanden_Dealers()">
                    Sanden Dealers
                </a>
            </li>
        {/if}

    </ul>
    <div class="clearfix"></div>
    <div class="tab-content">
        {foreach from=$dashboardPages key=tabNum item=tab}
            {if $tabNum == 0}
            <div class="tab-pane active fade in" id='tab_content_{$tabNum}'>
            {else}
            <div class="tab-pane fade" id='tab_content_{$tabNum}'>
            {/if}
                <img src="themes/SuiteP/images/loading.gif" width="48" height="48" align="baseline" border="0" alt="">
            </div>
        {/foreach}
    </div>
</div>
    <div class="modal fade modal-add-dashlet" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$lblAddDashlets}</h4>
                </div>
                <div class="modal-body" id="dashletsList">
                    <p><img src="themes/SuiteP/images/loading.gif" width="48" height="48" align="baseline" border="0" alt=""></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$app.LBL_CLOSE_BUTTON_TITLE}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade modal-add-dashboard" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$lblAddTab}</h4>
                </div>
                <div class="modal-body" id="dashboardDialog">
                    <p><img src="themes/SuiteP/images/loading.gif" width="48" height="48" align="baseline" border="0" alt=""></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$app.LBL_CANCEL_BUTTON_LABEL}</button>
                    <button type="button" class="btn btn-danger btn-add-dashboard" data-dismiss="modal">{$lblAddTab}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade modal-edit-dashboard" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{$app.LBL_EDIT_TAB}</h4>
                </div>
                <div class="modal-body">
                    <p><img src="themes/SuiteP/images/loading.gif" width="48" height="48" align="baseline" border="0" alt=""></p>                </div>
                    <div class="container-fluid">
                        <div class="panel panel-default panel-template">
                            <div class="panel-heading">
                                <div>
                                    <div class="col-xs-10 col-sm-11 col-md-11">
                                        <div class="edit-dashboard-tabs">
                                            <span class="suitepicon suitepicon-mimetype-tab"></span>
                                            <span class="panel-title">Untitled</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$app.LBL_CLOSE_BUTTON_TITLE}</button></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- fix errors in mysugar.js -->
    <div style="visibility: collapse">
        <div id="dashletsDialog"></div>
        <div id="dashletsDialog_c"></div>
    </div>

<script type="text/javascript" src="themes/SuiteP/include/MySugar/javascript/AddRemoveDashboardPages.js"></script>
<script type="text/javascript" src="themes/SuiteP/include/MySugar/javascript/retrievePage.js"></script>
<script type="text/javascript">

    var activePage = {$activePage};
    var theme = '{$theme}';
    current_user_id = '{$current_user}';
    jsChartsArray = new Array();
    var moduleName = '{$module}';
    document.body.setAttribute("class", "yui-skin-sam");
    {literal}
    var mySugarLoader = new YAHOO.util.YUILoader({
        require: ["my_sugar", "sugar_charts"],
        // Bug #48940 Skin always must be blank
        skin: {
            base: 'blank',
            defaultSkin: ''
        },
        onSuccess: function () {
            initMySugar();
            initmySugarCharts();
            SUGAR.mySugar.maxCount =    {/literal}{$maxCount}{literal};
            SUGAR.mySugar.homepage_dd = new Array();
            var j = 0;

            {/literal}
            var dashletIds = {$dashletIds};

            {if !$lock_homepage}
            for (i in dashletIds) {ldelim}
                SUGAR.mySugar.homepage_dd[j] = new ygDDList('dashlet_' + dashletIds[i]);
                SUGAR.mySugar.homepage_dd[j].setHandleElId('dashlet_header_' + dashletIds[i]);
                // Bug #47097 : Dashlets not displayed after moving them
                // add new property to save real id of dashlet, it needs to have ability reload dashlet by id
                SUGAR.mySugar.homepage_dd[j].dashletID = dashletIds[i];
                SUGAR.mySugar.homepage_dd[j].onMouseDown = SUGAR.mySugar.onDrag;
                SUGAR.mySugar.homepage_dd[j].afterEndDrag = SUGAR.mySugar.onDrop;
                j++;
                {rdelim}
            {if $hiddenCounter > 0}
            for (var wp = 0; wp <= {$hiddenCounter}; wp++) {ldelim}
                SUGAR.mySugar.homepage_dd[j++] = new ygDDListBoundary('page_' + activePage + '_hidden' + wp);
                {rdelim}
            {/if}
            YAHOO.util.DDM.mode = 1;
            {/if}
            {literal}
            SUGAR.mySugar.renderDashletsDialog();
            SUGAR.mySugar.sugarCharts.loadSugarCharts(activePage);
            {/literal}
            {literal}
        }
    });
    mySugarLoader.addModule({
        name: "my_sugar",
        type: "js",
        fullpath: {/literal}"{sugar_getjspath file='include/MySugar/javascript/MySugar.js'}"{literal},
        varName: "initMySugar",
        requires: []
    });
    mySugarLoader.addModule({
        name: "sugar_charts",
        type: "js",
        fullpath: {/literal}"{sugar_getjspath file="include/SugarCharts/Jit/js/mySugarCharts.js"}"{literal},
        varName: "initmySugarCharts",
        requires: []
    });
    mySugarLoader.insert();
    {/literal}
</script>
