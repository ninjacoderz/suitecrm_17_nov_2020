{*
/**
 * COPY AND EDIT FROM themes\SuiteP\include\DetailView\DetailView.tpl
 */
*}

<div class="detail-view-{$module}">
    <div class="mobile-pagination {$module}">{$PAGINATION}</div>

    {*display tabs*}
    {{counter name="tabCount" start=0 print=false assign="tabCount"}}
    <div class="clearfix"></div>
        {{if $tabCount == 0}}
            {*<!-- TAB CONTENT USE TABS -->*}
            <div class="tab-content">
            {if $config.enable_action_menu and $config.enable_action_menu != false}
                {{foreach name=section from=$sectionPanels key=label item=panel}}
                    {{capture name=label_upper assign=label_upper}}{{$label|upper}}{{/capture}}
                    {{if $tabCount == '0'}}
                        <div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in" id='tab-content-{{$tabCount}}'>
                            {{include file='custom/modules/pe_address/subpanel_address_detail_content.tpl'}}
                        </div>
                    {{else}}

                    {{/if}}
                    {{counter name="tabCount" print=false}}
                {{/foreach}}
            {/if}
        {{/if}} 

