{literal}
<style>
	#conf_sortableD1, #conf_sortableD2, #conf_sortableD3 { border-color: black;border-style: solid; border-width:1px; text-align:center ;margin:10px; width: 280px;list-style-type: none; margin: 10px; padding: 0 0 2.5em; float: left; margin-right: 10px; }
	#conf_sortableD1 li, #conf_sortableD2 li, #conf_sortableD3 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 260px; text-align:left ;}
    .tabTools{
        width: 69%;
    }
    .tabToolsInput{
        width: 71%
    }
    .tabLabel{
        white-space: nowrap;
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 3px 0px 0px 4px;
    }
    .SaveIcon, .EditIcon{
        width: 18px;
        margin-bottom: 2px;
    }
    .Delete{
        padding: 0;
        float: right;
    }
    .CloseIcon{
        width: 25px;
    }
    .brCloseButton{
        display: none;
    }
    #conf_sortableD3{margin-top: 42px;}
    #trash{width:75px;height:75px;background-color:grey;margin-top:20px;margin-left:800px;}
</style>
{/literal}
<h1 id="conftitle">{$TITLE}</h1><br>
<div id="confsynced">
	<span style="color:red;">{$IS_SYNCED}</span>
</div>
<div id="confbuttons" {$HIDEFIELDS}>
	<input title='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}' accessKey='M' class='button' onclick="return SaveFields(this,'{$module}','detail','{$profile}');" type='button' name='button' value='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}'></input>
	<input title='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}' accessKey='X' class='button' onclick="location.href='index.php?module=Administration&action=index';" type='button' name='button' value='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}'></input>
</div>
<div id="confshow_field_names" {$HIDEFIELDS}>
	<input id="chkshow_field_names" name="chkshow_field_names" type="checkbox" {if $showfields}checked="checked"{/if}>&nbsp;&nbsp;{$MOD.LBL_QSHOW_FIELD_NAMES}
</div>
<div id="confmodule" {$HIDEFIELDS}>
	<div style="float:left;width:300px;">
		<div id="hidden_div">
			<h3></h3>
			<input type="text" id="search_field_sortable2" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="margin-left:10px;width:280px">
			<ul id="conf_sortableD2" class="connectedSortable">{$HIDDEN}
{foreach name=tabCom from=$tabHidden key=tabColkey item=tabHiddenData}
                <li id= 'q_{$tabHiddenData.field}' class='ui-state-default'>
                    {$tabHiddenData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabHiddenData.field})</span>
                    {*<button onclick="removeElement($(this)); return false;">X</button>*}
                </li>

{/foreach}
			</ul>
		</div>
	</div>
	<div style="float:left;width:300px;">
		<div id="selected_div">
			<h3></h3>
			<input type="text" id="search_field_sortable1" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="margin-left:10px;width:280px">
			<ul id="conf_sortableD1" class="connectedSortable">{$AVAILABLE}
{foreach name=tabAvailable from=$tabAvailable key=tabColkey item=tabAvailableData}
			{if $tabAvailableData.element == 'field'}
                <li id= 'q_{$tabAvailableData.field}' class='ui-state-highlight'>
                    {$tabAvailableData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabAvailableData.field})</span>
                </li>
            {else}
                <li id= 'p_{$tabAvailableData.field}' class='ui-state-highlight label_sortable'>
                        <a class="Delete" href="javascript: void(0);" onclick="removeElement($(this).parent()); return false;"><img class="CloseIcon" src="custom/modules/Administration/tpls/delete_inline.gif" alt="Close"></a>
                        <input type="text" style="font-size:smaller; display: none;" class="tabToolsInput" value="{$tabAvailableData.label}">
                        <label style="font-size:smaller;" class="tabTools tabLabel">{$tabAvailableData.label}</label>
                        <a class="Edit edit_link" href="javascript: void(0);" onclick="showLabelInput($(this).parent()); return false;"><img class="EditIcon" src="custom/modules/Administration/tpls/icon_pencil.svg" alt="Edit"></a>
                        <a class="Save edit_link" href="javascript: void(0);" style="display: none" onclick="showLabelInput($(this).parent()); return false;"><img class="SaveIcon" src="custom/modules/Administration/tpls/icon_Save.svg" alt="Save"></a>
                </li>
            {/if}
{/foreach}
			</ul>
		</div>
	</div>
    <div style="float:left;width:200px;">
        <div id="panel_div">
            <h3></h3>
            <ul id="conf_sortableD3" class="connectedSortable">{$NEWPANEL}
                {foreach name=tabCom from=$tabTools key=tabColkey item=tabToolsData}
                    <li id= 'p_{$tabToolsData.field}' class='ui-state-highlight'>
                        <a class="Delete" href="javascript: void(0);" style="display: none" onclick="removeElement($(this).parent()); return false;"><img class="CloseIcon" src="custom/modules/Administration/tpls/delete_inline.gif" alt="Close"></a>
                        <input type="text" style="font-size:smaller; display: none;" class="tabToolsInput" value="{$tabToolsData.field}">
                        <label style="font-size:smaller;" class="tabTools tabLabel">{$tabToolsData.field}</label>
                        <a class="Edit" href="javascript: void(0);" style="display: none" onclick="showLabelInput($(this).parent()); return false;"><img class="EditIcon" src="custom/modules/Administration/tpls/icon_pencil.svg" alt="Edit"></a>
                        <a class="Save" href="javascript: void(0);" style="display: none" onclick="showLabelInput($(this).parent()); return false;"><img class="SaveIcon" src="custom/modules/Administration/tpls/icon_Save.svg" alt="Save"></a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>
<script>
var new_panel_label = '{$NEWPANEL}',
	countOfLabels = {$current_counter};
{literal}
var show_field_name = false;
    function removeElement(elem){
        elem.remove();
    }
    function configNewLabel(elem){
        if(!elem.hasClass('label_sortable') && elem.hasClass('ui-draggable')) {
        	countOfLabels++;
        	var label = new_panel_label +' '+ countOfLabels,
        		id = 'p_' + EncodePanel(label);
            elem.addClass('label_sortable');
            elem.attr('id', id);
            elem.children('input').val(label);
            elem.children('label').text(label);
            elem.find('.Edit').show();
            elem.find('.Delete').show();
            //elem.find('.brCloseButton').show();
        }
    }

    function showLabelInput(elem){
        var input = $(elem).children('input');
        var label = $(elem).children('label');
        var Edit = $(elem).find('.Edit');
        var Save = $(elem).find('.Save');
        $(Edit).hide();
        $(Save).hide();
        if(input.is(':visible')){
            label.show();
            input.hide();
            Edit.show();
            Save.hide();
            label.text(input.val());
            $(elem).attr("id", 'p_' + EncodePanel(input.val()));
        }else if(label.is(':visible')){
            input.show();
            label.hide();
            Edit.hide();
            Save.show();
        }
    }
$(function() {
	$( "#conf_sortableD1" ).sortable({
        connectWith: "#conf_sortableD2",
	}).droppable({
        drop: function(event, ui){
            configNewLabel(ui.draggable)
        }
    }).disableSelection();

    $( "#conf_sortableD2" ).sortable({
        connectWith: "#conf_sortableD1",
    }).droppable({
        drop: function(event, ui){
            configNewLabel(ui.draggable)
        }
    }).disableSelection();

    $( " #conf_sortableD3" ).sortable({
        connectWith: "#conf_sortableD1, #conf_sortableD2",
    }).disableSelection();

    $('#conf_sortableD3 li').each(function(index){
        $(this).draggable({
            connectToSortable: "#conf_sortableD1, #conf_sortableD2",
            revert: false,
            helper: "clone",
        });
    });

    $('#search_field_sortable1').on('keyup', function(){
        var str = $(this).val();
        var tt= $('#conf_sortableD1 li');
        $(tt).each(function(indx, element){
            var start_str = $(element).text().toLowerCase();

            if(start_str.indexOf(str.toLowerCase())!==-1){
                $(element).show();
            }
            else {
                $(element).hide();
            }
        })
    });
    $('#search_field_sortable2').on('keyup', function(){
        var str = $(this).val();
        var tt= $('#conf_sortableD2 li');
        $(tt).each(function(indx, element){
            var start_str = $(element).text().toLowerCase();

            if(start_str.indexOf(str.toLowerCase())!==-1){
                $(element).show();
            }
            else {
                $(element).hide();
            }
        })
    });
});
{/literal}
{if $showfields}
{literal}
$(document).ready(function(){
	$(".field_key").show();
	show_field_name = true;
});
{/literal}
{/if}
{literal}

function toggle_show_field_name(){
	show_field_name = !show_field_name;
	if (show_field_name){
		$(".field_key").show();
	}
	else {
		$(".field_key").hide();
	}
}

$( "#chkshow_field_names").change(function(){
	toggle_show_field_name();
});

{/literal}
</script>
