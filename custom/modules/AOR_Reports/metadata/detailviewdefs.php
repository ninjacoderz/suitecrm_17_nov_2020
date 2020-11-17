<?php

$viewdefs['AOR_Reports']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                array(
                    'customCode' => '{if $can_export}<input type="button" class="button" id="download_csv_button_old" value="{$MOD.LBL_EXPORT}">{/if}',
                ),
                array(
                    'customCode' => '{if $can_export}<input type="button" class="button" id="download_pdf_button_old" value="{$MOD.LBL_DOWNLOAD_PDF}">{/if}',
                ),
                array(
                    'customCode' => '<input type="button" class="button" onClick="openProspectPopup();" value="{$MOD.LBL_ADD_TO_PROSPECT_LIST}">',
                ),
            ),
            'footerTpl' => 'modules/AOR_Reports/tpls/report.tpl',
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'includes' => array(
            0 => 
            array (
                'file' => 'custom/modules/AOR_Reports/CustomReportDetailView.js',
            ),
            1 => 
            array (
                'file' => 'modules/AOR_Reports/AOR_Report.js',
            ),
        ),
        'tabDefs' =>
            array(
                'DEFAULT' =>
                    array(
                        'newTab' => false,
                        'panelDefault' => 'collapsed',
                    ),
            ),
    ),

    'panels' => array(
        'default' =>
            array(
                array(
                    'name',
                    'assigned_user_name',
                ),

                array(
                    array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                    array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),

                array(
                    'description',
                ),
            ),
    )
);
