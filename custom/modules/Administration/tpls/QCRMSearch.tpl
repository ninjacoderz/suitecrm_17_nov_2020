{literal}
<style>
	#conf_sortableD1, #conf_sortableD2, #conf_sortableD3 { border-color: black;border-style: solid; border-width:1px; text-align:center ;margin:10px; width: 180px;list-style-type: none; margin: 10px; padding: 0 0 2.5em; float: left; margin-right: 10px; }
	#conf_sortableD1 li, #conf_sortableD2 li, #conf_sortableD3 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 160px; text-align:left ;}
</style>
{/literal}
<h1 id="conftitle">{$TITLE}</h1><br>
<input title='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}' accessKey='M' class='button' onclick="return SaveFields(this,'{$module}','search','{$profile}');" type='button' name='button' value='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}'></input>
<input title='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}' accessKey='X' class='button' onclick="location.href='index.php?module=Administration&action=index';" type='button' name='button' value='{$APP_STRINGS.LBL_CANCEL_BUTTON_TITLE}'></input>
<div id="confshow_field_names">
	<input id="chkshow_field_names" name="chkshow_field_names" type="checkbox" {if $showfields}checked="checked"{/if}>&nbsp;&nbsp;{$MOD.LBL_QSHOW_FIELD_NAMES}
</div>
<div id="confmodule">
	<div style="float:left;width:200px;">
		<div id="hidden_div">
			<h3></h3>
			<input type="text" id="search_field_sortable2" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="margin-left:10px;width:170px">
			<ul id="conf_sortableD2" class="connectedSortable">{$HIDDEN}
{foreach name=tabCom from=$tabHidden key=tabColkey item=tabHiddenData}
					<li id= 'q_{$tabHiddenData.field}' class='ui-state-default'>{$tabHiddenData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabHiddenData.field})</span></li>
{/foreach}
			</ul>
		</div>
	</div>
	<div style="float:left;width:200px;">
		<div id="basic_div">
			<h3></h3>
<!--
			<input type="text" id="search_field_sortable1" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="margin-left:10px;width:170px">
-->
			<ul id="conf_sortableD3" class="connectedSortable">{$BASIC}
{foreach name=tabbasic_search from=$tabbasic_search key=tabColkey item=tabbasic_searchData}
					<li id= 'q_{$tabbasic_searchData.field}' class='ui-state-highlight'>{$tabbasic_searchData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabbasic_searchData.field})</span></li>
{/foreach}
			</ul>
		</div>
{if $showadvanced}
		<div id="selected_div">
			<h3></h3>
			<input type="text" id="search_field_sortable1" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="display:none;margin-left:10px;width:170px">
			<ul id="conf_sortableD1" class="connectedSortable">{$AVAILABLE}
{foreach name=tabAvailable from=$tabAvailable key=tabColkey item=tabAvailableData}
					<li id= 'q_{$tabAvailableData.field}' class='ui-state-highlight'>{$tabAvailableData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabAvailableData.field})</span></li>
{/foreach}
			</ul>
		</div>
{/if}
	</div>
</div>
{literal}
<script>
var show_field_name = false;
$(function() {
	$( "#conf_sortableD1, #conf_sortableD2, #conf_sortableD3" ).sortable({
		connectWith: ".connectedSortable"
	}).disableSelection();
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

</script>
{/literal}
