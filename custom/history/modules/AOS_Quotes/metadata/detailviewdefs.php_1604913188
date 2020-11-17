<?php
// created: 2020-06-19 09:23:13
$viewdefs['AOS_Quotes']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 'EDIT',
        1 => 'DUPLICATE',
        2 => 'DELETE',
        3 => 'FIND_DUPLICATES',
        4 => 
        array (
          'customCode' => '<input type="button" class="button" onClick="showPopup(\'pdf\');" value="{$MOD.LBL_PRINT_AS_PDF}">',
        ),
        5 => 
        array (
          'customCode' => '<input type="button" class="button" onClick="showPopup(\'emailpdf\');" value="{$MOD.LBL_EMAIL_PDF}">',
        ),
        6 => 
        array (
          'customCode' => '<input type="button" class="button" onClick="showPopup(\'email\');return false;" value="{$MOD.LBL_EMAIL_QUOTE}">',
        ),
        7 => 
        array (
          'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'createOpportunity\';" value="{$MOD.LBL_CREATE_OPPORTUNITY}">',
          'sugar_html' => 
          array (
            'type' => 'submit',
            'value' => '{$MOD.LBL_CREATE_OPPORTUNITY}',
            'htmlOptions' => 
            array (
              'class' => 'button',
              'id' => 'create_contract_button',
              'title' => '{$MOD.LBL_CREATE_OPPORTUNITY}',
              'onclick' => 'this.form.action.value=\'createOpportunity\';',
              'name' => 'Create Opportunity',
            ),
          ),
        ),
        8 => 
        array (
          'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'createContract\';" value="{$MOD.LBL_CREATE_CONTRACT}">',
          'sugar_html' => 
          array (
            'type' => 'submit',
            'value' => '{$MOD.LBL_CREATE_CONTRACT}',
            'htmlOptions' => 
            array (
              'class' => 'button',
              'id' => 'create_contract_button',
              'title' => '{$MOD.LBL_CREATE_CONTRACT}',
              'onclick' => 'this.form.action.value=\'createContract\';',
              'name' => 'Create Contract',
            ),
          ),
        ),
        9 => 
        array (
          'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'converToInvoice\';" value="{$MOD.LBL_CONVERT_TO_INVOICE}">',
          'sugar_html' => 
          array (
            'type' => 'submit',
            'value' => '{$MOD.LBL_CONVERT_TO_INVOICE}',
            'htmlOptions' => 
            array (
              'class' => 'button',
              'id' => 'convert_to_invoice_button',
              'title' => '{$MOD.LBL_CONVERT_TO_INVOICE}',
              'onclick' => 'this.form.action.value=\'converToInvoice\';',
              'name' => 'Convert to Invoice',
            ),
          ),
        ),
      ),
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_PANEL_OVERVIEW' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'custom/modules/AOS_Quotes/CustomQuotesDetailView.js',
      ),
      1 => 
      array (
        'file' => 'custom/include/SugarFields/Fields/Multiupload/js/html2canvas.js',
      ),
      2 => 
      array (
        'file' => 'custom/include/SugarFields/Fields/Multiupload/js/canvas2image.js',
      ),
    ),
  ),
  'panels' => 
  array (
    'LBL_PANEL_OVERVIEW' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'number',
          'label' => 'LBL_QUOTE_NUMBER',
        ),
        1 => 
        array (
          'name' => 'expiration',
          'label' => 'LBL_EXPIRATION',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'LBL_NAME',
        ),
        1 => 
        array (
          'name' => 'time_completed_job_c',
          'label' => 'LBL_TIME_COMPLETED_JOB',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'quote_type_c',
          'studio' => 'visible',
          'label' => 'LBL_QUOTE_TYPE',
        ),
        1 => 
        array (
          'name' => 'proposed_install_date_c',
          'label' => 'LBL_PROPOSED_INSTALL_DATE',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'stage',
          'label' => 'LBL_STAGE',
        ),
        1 => 
        array (
          'name' => 'quote_date_c',
          'label' => 'LBL_QUOTE_DATE',
        ),
      ),
      4 => 
      array (
        0 => '',
        1 => 
        array (
          'name' => 'next_action_date_c',
          'label' => 'LBL_NEXT_ACTION_DATE',
        ),
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'billing_account',
          'label' => 'LBL_BILLING_ACCOUNT',
        ),
        1 => 
        array (
          'name' => 'billing_contact',
          'label' => 'LBL_BILLING_CONTACT',
        ),
      ),
      6 => 
      array (
        0 => 'custom_detail_in_detail_view',
      ),
      7 => 
      array (
        0 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO',
        ),
        1 => 
        array (
          'name' => 'term',
          'label' => 'LBL_TERM',
        ),
      ),
      8 => 
      array (
        0 => 
        array (
          'name' => 'solargain_quote_number_c',
          'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
        ),
        1 => 
        array (
          'name' => 'assigned_user_c',
          'label' => 'LBL_ASSIGNED_USER_C',
        ),
      ),
      9 => 
      array (
        0 => 
        array (
          'name' => 'leads_aos_quotes_1_name',
          'label' => 'LBL_LEADS_AOS_QUOTES_1_FROM_LEADS_TITLE',
        ),
        1 => 
        array (
          'name' => 'assigned_user_lockout_c',
          'label' => 'LBL_ASSIGNED_USER_LOCKOUT',
        ),
      ),
      10 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'comment' => 'Full text of the note',
          'label' => 'LBL_DESCRIPTION',
        ),
      ),
      11 => 
      array (
        0 => 
        array (
          'name' => 'vic_rebate_c',
          'label' => 'LBL_VIC_REBATE',
        ),
        1 => 
        array (
          'name' => 'double_storey_c',
          'label' => 'LBL_DOUBLE_STOREY',
        ),
      ),
      12 => 
      array (
        0 => 
        array (
          'name' => 'vic_loan_c',
          'label' => 'LBL_VIC_LOAN',
        ),
        1 => '',
      ),
    ),
  ),
);