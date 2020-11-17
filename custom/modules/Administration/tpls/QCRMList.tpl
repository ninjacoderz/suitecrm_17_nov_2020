{literal}
<style>
	#conf_sortableD1, #conf_sortableD2, #conf_sortableD3 { border-color: black;border-style: solid; border-width:1px; text-align:center ;margin:10px; width: 180px;list-style-type: none; margin: 10px; padding: 0 0 2.5em; float: left; margin-right: 10px; }
	#conf_sortableD1 li, #conf_sortableD2 li, #conf_sortableD3 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 160px; text-align:left ;}
</style>
{/literal}
<h1 id="conftitle">{$TITLE}</h1><br>
<input title='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}' accessKey='M' class='button' onclick="return SaveFields(this,'{$module}','list','{$profile}');" type='button' name='button' value='{$APP_STRINGS.LBL_SAVE_BUTTON_TITLE}'></input>
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
		<div id="highlighted_div">
			<h3></h3>
<!--
			<input type="text" id="search_field_sortable3" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="margin-left:10px;width:170px">
-->
			<ul id="conf_sortableD3" class="connectedSortable">{$MOD.LBL_HIGHLIGHTED}
{foreach name=tabHighlighted from=$tabHighlighted key=tabColkey item=tabHighlightedData}
					<li id= 'q_{$tabHighlightedData.field}' class='ui-state-highlight'>{$tabHighlightedData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabHighlightedData.field})</span></li>
{/foreach}
			</ul>
		</div>
		<div id="selected_div">
			<h3></h3>
			<input type="text" id="search_field_sortable1" name="search" placeholder="{$APP_STRINGS.LBL_SEARCH}" style="display:none;margin-left:10px;width:170px">
			<ul id="conf_sortableD1" class="connectedSortable">{$MOD.LBL_LISTDETAILS}
{foreach name=tabAvailable from=$tabAvailable key=tabColkey item=tabAvailableData}
					<li id= 'q_{$tabAvailableData.field}' class='ui-state-highlight'>{$tabAvailableData.label}<span class="field_key" style="display:none;font-size:smaller;"><br>({$tabAvailableData.field})</span></li>
{/foreach}
			</ul>
		</div>
	</div>
</div>
<div id="confcolor">
		<label for="colorfield">{$APP_STRINGS.LBL_THEME_COLOR}</label>
		<select id="colorfield" name="colorfield">
			{html_options options=$color_options selected=$color_value}
		</select>
		<ul id="colorlegend" />
		{if $show_totals}
		<br>
		<label>{$LBL_TOTAL}</label>
		<br>
		<div id="totals0div">
		<select id="totalsfield0" onchange="toggle_total(0)" name="totalsfield0">
			{html_options options=$total_options selected=$totals_value0}
		</select>
		<select id="totalsfunction0" style="vertical-align:top;" multiple name="totalsfunction">
			{html_options options=$total_functions multiple="1" selected=$totals_function0}
		</select>
		<br>
		<select id="allfunctions" style="display:none;">
				{html_options options=$total_functions multiple="1"}
		</select>
		</div>
		<div id="totals1div" style="display:none;">
			<select id="totalsfield1" onchange="toggle_total('totalsfield1','totalsfunction1')"  name="totalsfield1">
				{html_options options=$total_options selected=$totals_value1}
			</select>
			<select id="totalsfunction1" style="vertical-align:top;" multiple name="totalsfunction1">
				{html_options options=$total_functions multiple="1" selected=$totals_function1}
			</select>
			<br>
		</div>
		<div id="totals2div" style="display:none;">
			<select id="totalsfield2" onchange="toggle_total('totalsfield2','totalsfunction2')" name="totalsfield2">
				{html_options options=$total_options selected=$totals_value2}
			</select>
			<select id="totalsfunction2" style="vertical-align:top;" multiple name="totalsfunction2">
				{html_options options=$total_functions multiple="1" selected=$totals_function2}
			</select>
			<br>
		</div>
			{if $show_totals}
			<div id="groupdiv">
			{$LBL_GROUP} &nbsp;
			<select id="groupfield" name="groupfield">
				{html_options options=$group_options selected=$group_value}
			</select>

			<br>
			<br>
			<input id="DASHLET" name="DASHLET" type="checkbox" {$DCHECKED}>{$DASHLET}
			<br>
			<input id="LV" name="LV" type="checkbox" {$LCHECKED}>{$LISTVIEW}
			<br>
			<input id="SP" name="SP" type="checkbox" {$SPCHECKED}>{$SUBPANELS}

			</div>
			{/if}
		{/if}
</div>
{literal}
<script>
var show_field_name = false,
	DefaultColors=[	'rgb(255, 255, 255)',
					'rgb(245, 245, 245)',
					'rgb(255,255,225)',
					'rgb(225,255,255)',
					'rgb(255,230,255)',
					'rgb(255,232,232)',
					'rgb(232,255,232)',
					'rgb(232,232,255)',
					'rgb(245,225,190)',
					'rgb(245,190,225)',
					'rgb(225,190,245)',
					'rgb(225,245,190)',
					'rgb(190,225,245)',
					'rgb(190,245,225)',
					'rgb(255, 211, 155)'
	];

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

function toggle_total(key){
	var totalsfield ='totalsfield' + key.toString(),
		totalsfunction = 'totalsfunction' + key.toString();
	if ($('#'+totalsfield).val() == ''){
		$('#'+totalsfunction).hide();
		if (key == 0) $('#groupdiv').hide();
	}
	else{
		$('#'+totalsfunction).show();
		$('#groupdiv').show();
	}
}

$( "#chkshow_field_names").change(function(){
	toggle_show_field_name();
});

$(document).ready(function(){
    def = $("#colorfield option:selected").val();
    def2 = $("#colorfield option:selected").val();
    toggle_total(0);
    toggle_total(1);
    toggle_total(2);
});

$( "#colorfield").change(function(){
    def2 = $("#colorfield option:selected").val();
});
</script>
{/literal}
