<?php
$viewdefs ['Leads'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
            'sugar_html' => 
            array (
              'type' => 'button',
              'value' => '{$MOD.LBL_CONVERTLEAD}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ),
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ),
          ),
          4 => 'FIND_DUPLICATES',
          5 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => 
              array (
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';',
                'name' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              ),
            ),
          ),
          'AOS_GENLET' => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_GENERATE_LETTER}">',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Leads/Lead.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/Leads/CustomLeadHoverEmail.js',
        ),
        2 => 
        array (
          'file' => 'custom/modules/Leads/CustomDetailLead.js',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'LBL_CONTACT_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'number',
            'label' => 'LBL_LEAD_NUMBER',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
        ),
        1 => 
        array (
          0 => 'title',
          1 => 
          array (
            'name' => 'age_days_c',
            'label' => 'LBL_AGE_DAYS',
          ),
        ),
        2 => 
        array (
          0 => 'status',
          1 => 
          array (
            'name' => 'product_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'custom_detail_in_detail_view',
            'label' => 'custom_detail_in_detail_view',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'open_new_tag_c',
            'studio' => 'visible',
            'label' => 'LBL_OPEN_NEW_TAG',
          ),
        ),
        5 => 
        array (
          0 => 'description',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'sanden_upload_url_c',
            'label' => 'LBL_SANDEN_UPLOAD_URL_C',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'daikin_upload_url_c',
            'label' => 'LBL_DAIKIN_UPLOAD_URL_C',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'solar_upload_url_c',
            'label' => 'LBL_SOLAR_UPLOAD_URL_C',
          ),
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
          ),
        ),
        1 => 
        array (
          0 => 'email1',
          1 => 
          array (
            'name' => 'full_name',
            'label' => 'LBL_NAME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'label' => 'LBL_ALTERNATE_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
            ),
          ),
        ),
        3 => 
        array (
          0 => 'lead_source',
        ),
        4 => 
        array (
          0 => 'status_description',
          1 => 'lead_source_description',
        ),
        5 => 
        array (
          0 => 'opportunity_amount',
          1 => 'refered_by',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'solargain_quote_number_c',
            'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'create_sanden_quote_num_c',
            'label' => 'LBL_CREATE_SANDEN_QUOTE_NUM',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'solargain_tesla_quote_number_c',
            'label' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'create_daikin_quote_num_c',
            'label' => 'LBL_CREATE_DAIKIN_QUOTE_NUM',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'create_methven_number_c',
            'label' => 'LBL_CREATE_METHVEN_NUMBER',
          ),
          1 => 
          array (
            'name' => 'create_methven_quote_num_c',
            'label' => 'LBL_CREATE_METHVEN_QUOTE_NUM',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_quote_fqs_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQS_NUM_C',
          ),
          1 => 
          array (
            'name' => 'create_solar_quote_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_NUM',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_quote_fqv_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQV_NUM_C',
          ),
          1 => 
          array (
            'name' => 'create_off_grid_button_num_c',
            'label' => 'LBL_CREATE_OFF_GRID_BUTTON_NUM_C',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'daikin_nexura_quote_num_c',
            'label' => 'LBL_DAIKIN_NEXURA_QUOTE_NUM_C',
          ),
          1 => 
          array (
            'name' => 'create_tesla_quote_num_c',
            'label' => 'LBL_CREATE_TESLA_QUOTE_NUM',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'service_case_number_c',
            'label' => 'LBL_SERVICE_CASE_NUMBER',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
