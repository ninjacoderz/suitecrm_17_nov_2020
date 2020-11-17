{* <script src="../custom/include/SugarFields/Fields/TimeStamp/js/timestamp.js"></script> *}
{include_php  file="custom/include/SugarFields/Fields/TimeStamp/TimeStampFunction.php"}
{if strlen({{sugarvar key='value' string=true}}) <= 0}
    {assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
    {assign var="value" value={{sugarvar key='value' string=true}} }
{/if}
    {* Assign id field TimeStamp *}
    {assign var="name_own_field" value={{$vardef.name}}} 

{php}
    get_Ages($this);
{/php}
    <input disabled="disabled" type='text' name='{{$vardef.labelValue}}' id='{{$vardef.name}}' value='{$date_diff}' size='{{$displayParams.size|default:30}}' {{if isset($displayParams.maxlength)}} maxlength='{{$displayParams.maxlength}}'{{elseif isset($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}}>
<script>
    {literal}

    {/literal}
</script>