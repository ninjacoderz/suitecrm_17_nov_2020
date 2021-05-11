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

{{sugar_include type="smarty" file=$headerTpl}}
{sugar_include include=$includes}
{* Compose view has a TEMP ID in case you want to display multi instance of the ComposeView *}
<form class="compose-view" id="ComposeView" name="ComposeView" method="POST" action="index.php?module=Emails&action=send">
    <input type="hidden" name="module" value="Emails">
    <input type="hidden" name="action" value="{$ACTION}">
    <input type="hidden" name="record" value="{$RECORD}">
    <input type="hidden" name="type" value="out">
    <input type="hidden" name="send" value="1">
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
    <input type="hidden" name="return_id" value="{$RETURN_ID}">
    <input type="hidden" name="inbound_email_id" value="{$INBOUND_ID}">
    <input type="hidden" name="sendGeo_invoice_id" value="{$sendGeo_invoice_id}">
     <input type="hidden" name="email_return_module" value="{$email_return_module}">
    <input type="hidden" name="email_return_id" value="{$email_return_id}">
    <input type="hidden" name="email_id" value="{$email_id}">
    <input type="hidden" name="pdf_id" value="{$pdf_id}">
<div id="EditView_tabs">
    {*display tabs*}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="nav nav-tabs">
        {{if $useTabs}}
        {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
        {* if tab *}
        {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true)}}
        {*if tab display*}
        {{counter name="tabCount" print=false}}
        {{if $tabCount == '0'}}
        <li role="presentation" class="active">
            <a id="tab{{$tabCount}}" data-toggle="tab" class="hidden-xs">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            {* Count Tabs *}
            {{counter name="tabCountOnlyXS" start=-1 print=false assign="tabCountOnlyXS"}}
            {{foreach name=sectionOnlyXS from=$sectionPanels key=labelOnly item=panelOnlyXS}}
            {{capture name=label_upper_count_only assign=label_upper_count_only}}{{$labelOnly|upper}}{{/capture}}
            {{if (isset($tabDefs[$label_upper_count_only].newTab) && $tabDefs[$label_upper_count_only].newTab == true)}}
                {{counter name="tabCountOnlyXS" print=false}}
            {{/if}}
            {{/foreach}}

            {*
                For the mobile view, only show the first tab has a drop down when:
                * There is more than one tab set
                * When Acton Menu's are enabled
            *}
            <!-- Counting Tabs {{$tabCountOnlyXS}}-->
            <a id="xstab{{$tabCount}}" href="#" class="visible-xs first-tab{{if $tabCountOnlyXS > 0}}-xs{{/if}} dropdown-toggle" data-toggle="dropdown">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
            {{if $tabCountOnlyXS > 0}}
            <ul id="first-tab-menu-xs" class="dropdown-menu">
                {{counter name="tabCountXS" start=0 print=false assign="tabCountXS"}}
                {{foreach name=sectionXS from=$sectionPanels key=label item=panelXS}}
                {{capture name=label_upper_xs assign=label_upper_xs}}{{$label|upper}}{{/capture}}
                {{if (isset($tabDefs[$label_upper_xs].newTab) && $tabDefs[$label_upper_xs].newTab == true)}}
                <li role="presentation">
                    <a id="tab{{$tabCountXS}}" data-toggle="tab" onclick="changeFirstTab(this, 'tab-content-{{$tabCountXS}}');">
                        {sugar_translate label='{{$label}}' module='{{$module}}'}
                    </a>
                </li>
                {{counter name="tabCountXS" print=false}}
                {{/if}}
                {{/foreach}}
            </ul>
            {{/if}}
        </li>
        {{else}}
        <li role="presentation" class="hidden-xs">
            <a id="tab{{$tabCount}}"  data-toggle="tab">
                {sugar_translate label='{{$label}}' module='{{$module}}'}
            </a>
        </li>
        {{/if}}
        {{else}}
        {* if panel skip*}
        {{/if}}
        {{/foreach}}
        {{/if}}

    </ul>

    <div class="clearfix"></div>
    {{if $useTabs}}
    <div class="tab-content">
        {{else}}
        <div class="tab-content" style="padding: 0; border: 0;">
            {{/if}}
            {{counter name="tabCount" start=0 print=false assign="tabCount"}}
            {* Loop through all top level panels first *}
            {{if $useTabs}}
            {{foreach name=section from=$sectionPanels key=label item=panel}}
            {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
            {{if isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true}}
            {{if $tabCount == '0'}}
            <div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in" id='tab-content-{{$tabCount}}'>
                {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
            </div>
            {{else}}
            <div class="tab-pane-NOBOOTSTRAPTOGGLER fade" id='tab-content-{{$tabCount}}'>
                {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
            </div>
            {{/if}}
             {{counter name="tabCount" print=false}}
            {{/if}}
            {{/foreach}}
            {{else}}
            <div class="tab-pane panel-collapse">&nbsp;</div>
            {{/if}}
        </div>
        {*display panels*}
        <div class="panel-content">
            <div>&nbsp;</div>
            {{counter name="panelCount" start=-1 print=false assign="panelCount"}}
            {{foreach name=section from=$sectionPanels key=label item=panel}}
            {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
            {* if tab *}
            {{if (isset($tabDefs[$label_upper].newTab) && $tabDefs[$label_upper].newTab == true && $useTabs)}}
            {*if tab skip*}
            {{else}}
            {* if panel display*}
            {*if panel collasped*}
            {{if (isset($tabDefs[$label_upper].panelDefault) && $tabDefs[$label_upper].panelDefault == "collapsed") }}
            {*collapse panel*}
            {{assign var='collapse' value="panel-collapse collapse"}}
            {{assign var='collapsed' value="collapsed"}}
            {{assign var='collapseIcon' value="glyphicon glyphicon-plus"}}
            {{assign var='panelHeadingCollapse' value="panel-heading-collapse"}}
            {{else}}
            {*expand panel*}
            {{assign var='collapse' value="panel-collapse collapse in"}}
            {{assign var='collapseIcon' value="glyphicon glyphicon-minus"}}
            {{assign var='panelHeadingCollapse' value=""}}
            {{/if}}

            <div class="panel panel-default">
                <div class="panel-heading {{$panelHeadingCollapse}}">
                    <a class="{{$collapsed}}" role="button" data-toggle="collapse-edit" aria-expanded="false">
                        <div class="col-xs-10 col-sm-11 col-md-11">
                            {sugar_translate label='{{$label}}' module='{{$module}}'}
                        </div>
                    </a>
                </div>
                <div class="panel-body {{$collapse}}" id="detailpanel_{{$panelCount}}">
                    <div class="tab-content">
                        {{include file='themes/SuiteP/include/EditView/tab_panel_content.tpl'}}
                    </div>
                </div>
            </div>
            {{/if}}
            {{counter name="panelCount" print=false}}
            {{/foreach}}
        </div>
    </div>
    <div class="attachments">
        {if $RETURN_MODULE != 'Emails' && $RETURN_ID}
            <div hidden class="bean-attachments">
                <div class="bean-attachment-group-container">
                    <input type="hidden" id="bean_attachment_{$RETURN_ID}" multiple="multiple">
                    <label for="bean_attachment_{$RETURN_ID}" class="">
                        <div class="bean-attachment-file-container file-image">
                            <span class="bean-attachment-type glyphicon glyphicon-file"></span>
                            <span class="bean-attachment-name">{$ATTACHMENT_NAME}</span>
                        </div>
                    </label>
                </div>
            </div>
        {/if}
        <div class="file-attachments"></div>
        <div class="document-attachments"></div>
    </div>
{{sugar_include type='smarty' file=$footerTpl}}

{if !$IS_MODAL}

    {literal}

        <script type="text/javascript">

        var selectTab = function(tab) {
            $('#EditView_tabs div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').hide();
            $('#EditView_tabs div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').eq(tab).show().addClass('active').addClass('in');
        };

        var selectTabOnError = function(tab) {
            selectTab(tab);
            $('#EditView_tabs ul.nav.nav-tabs li').removeClass('active');
            $('#EditView_tabs ul.nav.nav-tabs li a').css('color', '');

            $('#EditView_tabs ul.nav.nav-tabs li').eq(tab).find('a').first().css('color', 'red');
            $('#EditView_tabs ul.nav.nav-tabs li').eq(tab).addClass('active');

        };

        var selectTabOnErrorInputHandle = function(inputHandle) {
            var tab = $(inputHandle).closest('.tab-pane-NOBOOTSTRAPTOGGLER').attr('id').match(/^detailpanel_(.*)$/)[1];
            selectTabOnError(tab);
        };


        $(function(){
            $('#EditView_tabs ul.nav.nav-tabs li > a[data-toggle="tab"]').click(function(e){
                if(typeof $(this).parent().find('a').first().attr('id') != 'undefined') {
                    var tab = parseInt($(this).parent().find('a').first().attr('id').match(/^tab(.)*$/)[1]);
                    selectTab(tab);
                }
            });

            $('a[data-toggle="collapse-edit"]').click(function(e){
                if($(this).hasClass('collapsed')) {
                  // Expand panel
                    // Change style of .panel-header
                    $(this).removeClass('collapsed');
                    // Expand .panel-body
                    $(this).parents('.panel').find('.panel-body').removeClass('in').addClass('in');
                } else {
                  // Collapse panel
                    // Change style of .panel-header
                    $(this).addClass('collapsed');
                    // Collapse .panel-body
                    $(this).parents('.panel').find('.panel-body').removeClass('in').removeClass('in');
                }
            });
        });
        </script>

    {/literal}

    <script>
        {* Compose view has a TEMP ID in case you want to display multi instance of the ComposeView *}
      $(document).ready(function() {ldelim}
        $('#ComposeView').EmailsComposeView({if $RETURN_MODULE != 'Emails' && $RETURN_ID}{ldelim}
          'attachment': {ldelim}
            'module': '{$RETURN_MODULE}',
            'id': '{$RETURN_ID}'
          {rdelim}
        {rdelim}{/if});
      {rdelim});
    </script>
    {/if}
</form>
{*dialog Quick Comment*}

<!-- dialog -->
    <div id = "dialog_quick_comment" title="Template Quick Comment " hidden>
		<div id="edit_dialog_quick_comment" >
			
			<h4 class="text-center">Template Quick Comment</h4>
			<div>
				<div class="label" >Select Template :</div>
				<select style="width:100%;margin-bottom:2px;" id="select_title_template_quick_comment" >
					<option label="" value=""></option>
				</select>
				<div class="label"> Title : </div>
				<input style="width:100%;" id="title_quick_comment" name="title_quick_comment" type="text" value="" />
				<input id="id_template_quick_comment" hidden name="id_template_quick_comment" type="text" value="" />
			</div>
			<div>
				<div class="label" >Special Notes :</div>
				<div class="input">
					<textarea id="content_quick_comment" style="width:100%;height:200px;">
					</textarea>
				</div>
			</div>
		</div>
	</div>


   <div id = "dialog_files" hidden>
        <button value="1" id="select_all" style="float: right;">Select all</button>
		<h4 class="text-center">Select Files</h4>
        <div id="icon_loader" hidden></div>
        <table id="dialog_files"  class="table table-striped">
            <tbody class="files">
            </tbody>
        </table>
	</div>

{literal}
   <script>
        function remove_notes(note_id){
            var ok_confirm = confirm('Do you want to delete ?');
            if(ok_confirm == true) {
                $("#"+note_id).parent().hide();
                var removeAttachment = $("input[name='removeAttachment']").val();
                removeAttachment += '::'+note_id;
                $("input[name='removeAttachment']").val(removeAttachment);
            }else {
                return false;
            } 
        };
      $(function(){
        var email_return_module = $('input[name="return_module"]').val();
        var email_id = $('input[name="email_id"]').val();
        var email_return_id = $('input[name="return_id"]').val();
        if(email_return_module == '' || email_return_id == ''){
            email_return_module = $('input[name="email_return_module"]').val();
            email_id = $('input[name="email_id"]').val();
            email_return_id = $('input[name="email_return_id"]').val();
        }
        if(email_return_module != 'AOS_Invoices' && email_return_module != 'AOS_Quotes' && email_return_module != 'PO_purchase_order'){
            $('#btnAttachFile_in_CRM').hide();
        }
        if(typeof($("input[name='removeAttachment']").val()) == 'undefined') {
            var append_removeDraftAttachmentInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'removeAttachment')
                .appendTo($('.file-attachments'));
        }
        $('#btnAttachFile_in_CRM').click(function(){
                $("#dialog_files tbody").empty();
                $("#dialog_files").dialog("open");
                $("#icon_loader").show();
                
                $.ajax({
                    url: 'index.php?entryPoint=CRUD_Files' ,
                    type: 'POST',
                    data: 
                    {
                        action: 'read',
                        module: email_return_module,
                        id: email_return_id,
                        id_email: email_id,
                    },
                    async: true,
                    success: function(result) { 
                        if(result.trim() != 'Not Have Files'){
                            render_group_files(result);
                        } else{
                            $("#dialog_files tbody").empty();
                            $("#dialog_files tbody").append('<p>Not Have Files</p>');
                        }      
                        $("#icon_loader").hide();           
                    }
                }); 
                return false;
        });

        $("#dialog_files").on("click", "#select_all", function(){
            //debugger
            if ($(this).val() == "1") {
                $(this).parent().find("input[name^=dialog_add_notes]").each(function(k,v){
                    if(!$(this).is(':checked')) {
                        $(this).prop('checked',true);
                    }
                });
                $(this).val("0");
                $(this).text("Unselect all");
            } else {
                $(this).parent().find("input[name^=dialog_add_notes]").each(function(k,v){
                    if($(this).is(':checked')) {
                        $(this).prop('checked', false);
                    }
                });
                $(this).val("1");
                $(this).text("Select all");
            }
        })

        $("#dialog_files").dialog({
            autoOpen: false,
            width: 902,
            height:578,
            buttons: {
                Attach: function(){
                    var json_add_files = [];
                    $("input[name^=dialog_add_notes]").each(function(k,v){
                        if($(this).is(':checked') && $(this).attr('data-note-id') == "") {
                            var file_name = $(this).attr('data-file-name');
                            var id_folder = $(this).attr('data-id-folder');
                            var url_image = $(this).attr('data-url');
                            json_add_files.push([id_folder,file_name,url_image]);
                        }
                    });
                    var jsonString= encodeURIComponent(JSON.stringify(json_add_files));
                    $("#icon_loader").show();
                    $("#dialog_files tbody").empty();
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Files' ,
                        type: 'POST',
                        data: 
                        {
                            action: 'addNotes',
                            module: email_return_module,
                            id: email_return_id,
                            id_email: email_id,
                            jsonString: jsonString,

                        },
                        success: function(result) { 
                            if(result.trim() == 'Not Have Files') {
                                console.log('Fail');
                                $("#icon_loader").hide();
                                $("#dialog_files").dialog('close');
                            };
                            var data_result = JSON.parse(result);   
                            var html = ''; 
                            $.each(data_result,function(k,v){    
                                html += '<div class="attachment-group-container"><select style="display:none" id="'+v['note_id']+'"\
                                                is_file="true" name="dummy_attachment[]" multiple="multiple">\
                                                <option selected="selected" value="'+v['note_id']+'"></option>\
                                            </select><label for="file_'+v['note_id']+'"><span class="glyphicon glyphicon-paperclip"></span>\
                                                <div class="attachment-file-container"><span class="attachment-name">\
                                                    '+v['file_name']+' </span></div>\
                                            </label><a onclick="remove_notes(\''+v['note_id']+'\')" class="attachment-remove"><span class="glyphicon glyphicon-remove"></span></a></div>';

                            });
                            $(".file-attachments").append(html);
                             $("#icon_loader").hide();
                             $("#dialog_files").dialog('close');
                        }   
                    }); 
                },
                Delete: function(){
                    var ok_confirm = confirm('Do you want to delete ?');
                    if(ok_confirm == true) {
                        $("input[name^=dialog_add_notes]").each(function(k,v){
                            if($(this).is(':checked')) {
                                var note_id = $(this).attr('data-note-id');
                                 if( $("#"+note_id).length )    {
                                    $("#"+note_id).parent().hide();
                                    var removeAttachment = $("input[name='removeAttachment']").val();
                                    removeAttachment += '::'+note_id;
                                    $("input[name='removeAttachment']").val(removeAttachment);
                                 }      
                            }
                        });
                        $("#icon_loader").show();
                        $("#dialog_files tbody").empty();
                        $("#icon_loader").hide();
                        $(this).dialog('close'); 
                    }else {
                        return false;
                    } 
                },
                Close: function(){
                    $(this).dialog('close');
                },
            }
        });

        function render_group_files(result){
            var data_result = JSON.parse(result);
            $("#dialog_files tbody").empty();
            var html = '';
            $.each(data_result,function(k,v){    
                 var removeAttachment = typeof ($("input[name='removeAttachment']").val()) === "undefined" ? '' : $("input[name='removeAttachment']").val();
        
                 var value_check = ''; 
                if(v['attach'] == 1 && v['note_id'] != '' && !(removeAttachment.indexOf(v['note_id']) != -1 )){
                    var value_check = ' Attached ';           
                };
                html += '<tr class="template-download fade in"> \
                            <td><span class="preview"><a target="_blank" \
                                href="'+v['link_image']+'"><img \
                                onerror="this.onerror=null;this.src=\'themes/SuiteP/images/Documents.png\'; \
                                this.style.background=\'black\'; " \
                                style="width:50px;height50px;" \
                                src="'+v['link_thub']+'"></a></span>\
                            </td>\
                            <td>\
                                <p class="name"><a target="_blank"\
                                    href="'+v['link_thub']+'">'+v['file_name']+'</a>\
                                </p> <br>\
                            </td>\
                            <td> <br> \
                                <label class="">'+value_check+'</label>    \
                            </td> \
                            <td> <br> \
                                <input type="checkbox" name="dialog_add_notes" title="Attach File"  class="add-files" \
                                    data-file-name = "'+v['file_name']+'"\
                                    data-id-folder = "'+v['id_folder']+'"\
                                    data-url="'+v['link_image']+'" \
                                    data-note-id="'+v['note_id']+'" \
                                    value="1" enabled="true" tabindex="0"> </td>\
                            </tr>';

            });
            $("#dialog_files tbody").append(html);
        }  
      });
   </script>
{/literal}
