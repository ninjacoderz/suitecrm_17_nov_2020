{*
/**
 * COPY AND EDIT FROM themes\SuiteP\include\DetailView\tab_panel_content.tpl
 */
 *}
{*<!-- tab_panel_content.tpl START -->*}

{*<!-- tab panel main div -->*}

{{foreach name=rowIteration from=$panel key=row item=rowData}}

    {*row*}

    {*<!-- ROW -->*}
<div class="row detail-view-row">

    {{counter name="columnCount" start=0 print=false assign="columnCount"}}

    {{foreach name=colIteration from=$rowData key=col item=colData}}

        {*column*}

        {*<!-- COLUMN -->*}

        {{if $smarty.foreach.colIteration.total > 1 && $colData.colspan != 3}}
            {*<!-- DIV column - colspan != 3 -->*}
            <div class="col-xs-12 col-sm-6 detail-view-row-item">
        {{else}}
            {*<!-- DIV column - colspan = 3 -->*}
            <div class="col-xs-12 col-sm-12 detail-view-row-item">
        {{/if}}


        {{counter name="fieldCount" start=0 print=false assign="fieldCount"}}

        {{foreach name=fieldIteration from=$colData key=field item=subField}}

            {{if !(!isset($subField.name) || !$subField.name)}}
                {*<!-- [hide!!] -->*}

                {{if $fieldCount < $smarty.foreach.colIteration.total && !empty($colData.field.name)}}

                    {{if $smarty.foreach.colIteration.total > 1 && $colData.colspan != 3}}
                        {*<!-- DIV inside - colspan != 3 -->*}

                    {{if $smarty.foreach.colIteration.index == 0}}
                        <div class="col-xs-12 col-sm-4 label col-1-label">
                    {{else}}
                        <div class="col-xs-12 col-sm-4 label col-2-label">
                    {{/if}}
                    {{else}}
                        {*<!-- DIV inside - colspan = 3 -->*}
                        <div class="col-xs-12 col-sm-2 label col-1-label">
                    {{/if}}

                    {*label*}

                    {*<!-- LABEL -->*}

                    {{if isset($colData.field.customLabel)}}
                        {{$colData.field.customLabel}}
                    {{elseif isset($colData.field.label) && strpos($colData.field.label, '$')}}
                        {capture name="label" assign="label"}{{$colData.field.label}}{/capture}
                        {$label|strip_semicolon}:
                    {{elseif isset($colData.field.label)}}
                        {capture name="label" assign="label"}{sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}{/capture}
                        {$label|strip_semicolon}:
                    {{elseif isset($fields[$colData.field.name])}}
                        {capture name="label" assign="label"}{sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}{/capture}
                        {$label|strip_semicolon}:
                    {{else}}
                        &nbsp;
                    {{/if}}

                    {{if isset($colData.field.popupHelp) || isset($fields[$colData.field.name]) && isset($fields[$colData.field.name].popupHelp) }}
                        {{if isset($colData.field.popupHelp) }}
                            {capture name="popupText" assign="popupText"}{sugar_translate label="{{$colData.field.popupHelp}}" module="{{$module}}"}{/capture}
                        {{elseif isset($fields[$colData.field.name].popupHelp)}}
                            {capture name="popupText" assign="popupText"}{sugar_translate label="{{$fields[$colData.field.name].popupHelp}}" module='{{$module}}'}{/capture}
                        {{/if}}
                        {sugar_help text=$popupText WIDTH=400}
                    {{/if}}

                    </div>
                    {*<!-- /DIV inside  -->*}

                    {{if $smarty.foreach.colIteration.total > 1 && $colData.colspan != 3}}
                        {*<!-- phone (version 1) -->*}
                        <div class="col-xs-12 col-sm-8 detail-view-field " type="{{$fields[$colData.field.name].type}}" field="{{$fields[$colData.field.name].name}}" {{if $colData.colspan}}colspan='{{$colData.colspan}}'{{/if}} {{if isset($fields[$colData.field.name].type) && $fields[$colData.field.name].type == 'phone'}}class="phone"{{/if}}>
                    {{else}}
                        {*<!-- phone (version 2) -->*}
                        <div class="col-xs-12 col-sm-10 detail-view-field " type="{{$fields[$colData.field.name].type}}" field="{{$fields[$colData.field.name].name}}" {{if $colData.colspan}}colspan='{{$colData.colspan}}'{{/if}} {{if isset($fields[$colData.field.name].type) && $fields[$colData.field.name].type == 'phone'}}class="phone"{{/if}}>
                    {{/if}}

                    {{if !empty($colData.field.name)}}



                    {*<!-- simple hidden start -->*}
                    {if !$fields.{{$colData.field.name}}.hidden}



                    {{/if}}

                    {{$colData.field.prefix}}


                    {{if ($colData.field.customCode && !$colData.field.customCodeRenderField) || $colData.field.assign}}
                        {counter name="panelFieldCount" print=false}
                        <span id="{{$colData.field.name}}_{$module}" class="sugar_field">{{sugar_evalcolumn var=$colData.field colData=$colData}}</span>
                    {{elseif $fields[$colData.field.name] && !empty($colData.field.fields) }}

                        {{foreach from=$colData.field.fields item=subField}}
                            {{if $fields[$subField]}}
                                {counter name="panelFieldCount" print=false}
                                {{sugar_field parentFieldArray='fields' tabindex=$tabIndex vardef=$fields[$subField] displayType='DetailView'}}&nbsp;
                            {{else}}
                                {counter name="panelFieldCount" print=false}
                                {{$subField}}
                            {{/if}}
                        {{/foreach}}

                    {{elseif $fields[$colData.field.name]}}
                        {counter name="panelFieldCount" print=false}
                        {{sugar_field parentFieldArray='fields' vardef=$fields[$colData.field.name] displayType='DetailView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type}}
                    {{/if}}

                    {{if !empty($colData.field.customCode) && $colData.field.customCodeRenderField}}
                        {counter name="panelFieldCount" print=false}
                        <span id="{{$colData.field.name}}_{$module}" class="sugar_field">{{sugar_evalcolumn var=$colData.field colData=$colData}}</span>
                    {{/if}}

                    {{$colData.field.suffix}}

                    {{if !empty($colData.field.name)}}



                    {/if}
                    {*<!-- simple hidden finish -->*}



                    {{/if}}

                    </div>
                    {*<!-- /phone (version 1/2) -->*}

                {{/if}}



                {{counter name="fieldCount" print=false}}

            {*<!-- [/hide!!] -->*}
            {{/if}}

        {{/foreach}}

        </div>
        {*<!-- /DIV column -->*}


    {{/foreach}}
    {{counter name="columnCount" print=false}}

</div>
{{/foreach}}

{*<!-- /tab panel main div -->*}
