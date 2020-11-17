<?php
$viewdefs ['Leads'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          1 => '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          2 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          3 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ),
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
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
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL5' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/Leads/autosize.min.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/Leads/CustomExtraPriceLeads.js',
        ),
        2 => 
        array (
          'file' => 'custom/modules/Leads/CustomLead.js',
        ),
        3 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/html2canvas.js',
        ),
        4 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/canvas2image.js',
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
            'customCode' => '{$fields.number.value}',
          ),
          1 => 
          array (
            'name' => 'age_days_c',
            'label' => 'LBL_AGE_DAYS',
          ),
        ),
        1 => 
        array (
          0 => 'status',
          1 => 
          array (
            'name' => 'entered_communicated_into_pe_c',
            'studio' => 'visible',
            'label' => 'LBL_ENTERED_COMMUNICATED_INTO_PE',
          ),
        ),
        2 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'how_did_you_hear_about_pe_c',
            'studio' => 'visible',
            'label' => 'LBL_HOW_DID_YOU_HEAR_ABOUT_PE',
          ),
        ),
        3 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'sg_lead_source_c',
            'studio' => 'visible',
            'label' => 'LBL_SG_LEAD_SOURCE_C',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'lead_source_co_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE_CO',
          ),
          1 => 'lead_source',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'product_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_TYPE',
          ),
          1 => 
          array (
            'name' => 'requested_products_c',
            'studio' => 'visible',
            'label' => 'LBL_REQUESTED_PRODUCTS',
          ),
        ),
        6 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        7 => 
        array (
          0 => 'department',
          1 => 'title',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'link_realestate_address_c',
            'label' => 'LBL_LINK_REALESTATE_ADDRESS',
          ),
          1 => 'website',
        ),
        9 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'link_domain_address_c',
            'label' => 'LBL_LINK_DOMAIN_ADDRESS',
          ),
        ),
        10 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
          ),
          1 => 'last_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
            'type' => 'varchar',
            'validateDependency' => false,
            'customCode' => '<input name="account_name" id="EditView_account_name" {if ($fields.converted.value == 1)}disabled="true"{/if} size="30" maxlength="255" type="text" value="{$fields.account_name.value}">',
          ),
          1 => 'phone_fax',
        ),
        2 => 
        array (
          0 => 'phone_mobile',
          1 => 'email1',
        ),
        3 => 
        array (
          0 => 'phone_work',
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
        ),
      ),
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pe_site_details_no_c',
            'label' => 'LBL_PE_SITE_DETAILS_NO',
          ),
          1 => 
          array (
            'name' => 'sg_site_details_no_c',
            'label' => 'LBL_SG_SITE_DETAILS_NO',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'solargain_tesla_quote_number_c',
            'label' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'solargain_quote_number_c',
            'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'distance_to_sg_c',
            'label' => 'LBL_DISTANCE_TO_SG',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'site_detail_addr__c',
            'label' => 'LBL_SITE_DETAIL_ADDR_',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'site_detail_addr__city_c',
            'label' => 'LBL_SITE_DETAIL_ADDR__CITY',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'site_detail_addr__state_c',
            'label' => 'LBL_SITE_DETAIL_ADDR__STATE',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'site_detail_addr__postalcode_c',
            'label' => 'LBL_SITE_DETAIL_ADDR__POSTALCODE',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'site_detail_addr__country_c',
            'label' => 'LBL_SITE_DETAIL_ADDR__COUNTRY',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'name_on_billing_account_c',
            'label' => 'LBL_NAME_ON_BILLING_ACCOUNT',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'customer_type_c',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOMER_TYPE',
          ),
          1 => 
          array (
            'name' => 'roof_type_c',
            'studio' => 'visible',
            'label' => 'LBL_ROOF_TYPE',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'gutter_height_c',
            'studio' => 'visible',
            'label' => 'LBL_GUTTER_HEIGHT',
          ),
          1 => 
          array (
            'name' => 'export_meter_c',
            'label' => 'LBL_EXPORT_METER_C',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'potential_issues_c',
            'studio' => 'visible',
            'label' => 'LBL_POTENTIAL_ISSUES_C',
          ),
          1 => 
          array (
            'name' => 'cable_size_c',
            'label' => 'LBL_CABLE_SIZE_C',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'connection_type_c',
            'studio' => 'visible',
            'label' => 'LBL_CONNECTION_TYPE',
          ),
          1 => 
          array (
            'name' => 'main_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MAIN_TYPE',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'meter_number_c',
            'label' => 'LBL_METER_NUMBER',
          ),
          1 => 
          array (
            'name' => 'meter_phase_c',
            'studio' => 'visible',
            'label' => 'LBL_METER_PHASE',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'nmi_c',
            'label' => 'LBL_NMI',
          ),
          1 => 
          array (
            'name' => 'account_number_c',
            'label' => 'LBL_ACCOUNT_NUMBER',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'address_nmi_c',
            'label' => 'LBL_ADDRESS_NMI',
          ),
          1 => 
          array (
            'name' => 'account_holder_dob_c',
            'label' => 'LBL_ACCOUNT_HOLDER_DOB_C',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'energy_retailer_c',
            'studio' => 'visible',
            'label' => 'LBL_ENERGY_RETAILER',
          ),
          1 => 
          array (
            'name' => 'distributor_c',
            'studio' => 'visible',
            'label' => 'LBL_DISTRIBUTOR',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'create_opportunity_c',
            'label' => 'LBL_CREATE_OPPORTUNITY',
          ),
          1 => 
          array (
            'name' => 'create_opportunity_number_c',
            'label' => 'LBL_CREATE_OPPORTUNITY_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'create_daikin_c',
            'label' => 'LBL_CREATE_DAIKIN',
          ),
          1 => 
          array (
            'name' => 'create_daikin_number_c',
            'label' => 'LBL_CREATE_DAIKIN_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'create_sanden_c',
            'label' => 'LBL_CREATE_SANDEN',
          ),
          1 => 
          array (
            'name' => 'create_sanden_number_c',
            'label' => 'LBL_CREATE_SANDEN_NUMBER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_c',
            'label' => 'LBL_CREATE_SOLAR',
          ),
          1 => 
          array (
            'name' => 'create_solar_number_c',
            'label' => 'LBL_CREATE_SOLAR_NUMBER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'create_daikin_quote_c',
            'label' => 'LBL_CREATE_DAIKIN_QUOTE',
          ),
          1 => 
          array (
            'name' => 'create_daikin_quote_num_c',
            'label' => 'LBL_CREATE_DAIKIN_QUOTE_NUM',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'sent_email_daikin_quote_c',
            'label' => 'LBL_SENT_EMAIL_DAIKIN_QUOTE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'create_daikin_nexura_quote_c',
            'label' => 'LBL_CREATE_DAIKIN_NEXURA_QUOTE_C',
          ),
          1 => 
          array (
            'name' => 'daikin_nexura_quote_num_c',
            'label' => 'LBL_DAIKIN_NEXURA_QUOTE_NUM_C',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'create_methven_c',
            'label' => 'LBL_CREATE_METHVEN',
          ),
          1 => 
          array (
            'name' => 'create_methven_number_c',
            'label' => 'LBL_CREATE_METHVEN_NUMBER',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_quote_fqs_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQS_C',
          ),
          1 => 
          array (
            'name' => 'create_solar_quote_fqs_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQS_NUM_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'sent_email_sanden_fqs_c',
            'label' => 'LBL_SENT_EMAIL_SANDEN_FQS_C',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_quote_fqv_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQV_C',
          ),
          1 => 
          array (
            'name' => 'create_solar_quote_fqv_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_FQV_NUM_C',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'sent_email_sanden_fqv_c',
            'label' => 'LBL_SENT_EMAIL_SANDEN_FQV_C',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'create_methven_quote_c',
            'label' => 'LBL_CREATE_METHVEN_QUOTE',
          ),
          1 => 
          array (
            'name' => 'create_methven_quote_num_c',
            'label' => 'LBL_CREATE_METHVEN_QUOTE_NUM',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'create_solar_quote_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE',
          ),
          1 => 
          array (
            'name' => 'create_solar_quote_num_c',
            'label' => 'LBL_CREATE_SOLAR_QUOTE_NUM',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'create_tesla_quote_c',
            'label' => 'LBL_CREATE_TESLA_QUOTE',
          ),
          1 => 
          array (
            'name' => 'create_tesla_quote_num_c',
            'label' => 'LBL_CREATE_TESLA_QUOTE_NUM',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'create_off_grid_quote_c',
            'label' => 'LBL_CREATE_OFF_GRID_QUOTE',
          ),
          1 => 
          array (
            'name' => 'create_off_grid_button_num_c',
            'label' => 'LBL_CREATE_OFF_GRID_BUTTON_NUM_C',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'create_service_case_c',
            'label' => 'LBL_CREATE_SERVICE_CASE',
          ),
          1 => 
          array (
            'name' => 'service_case_number_c',
            'label' => 'LBL_SERVICE_CASE_NUMBER',
          ),
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'status_description',
          ),
          1 => 
          array (
            'name' => 'lead_source_description',
          ),
        ),
        1 => 
        array (
          0 => 'opportunity_amount',
          1 => 'refered_by',
        ),
        2 => 
        array (
          0 => 'campaign_name',
          1 => 
          array (
            'name' => 'designer_c',
            'studio' => 'visible',
            'label' => 'LBL_DESIGNER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'time_request_design_c',
            'label' => 'LBL_TIME_REQUEST_DESIGN',
          ),
          1 => 
          array (
            'name' => 'seek_install_date_c',
            'label' => 'LBL_SEEK_INSTALL_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'time_accepted_job_c',
            'label' => 'LBL_TIME_ACCEPTED_JOB',
          ),
          1 => 
          array (
            'name' => 'time_completed_job_c',
            'label' => 'LBL_TIME_COMPLETED_JOB',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'time_sent_to_client_c',
            'label' => 'LBL_TIME_SENT_TO_CLIENT',
          ),
          1 => 
          array (
            'name' => 'address_provided_c',
            'label' => 'LBL_ADDRESS_PROVIDED',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'do_not_email_c',
            'label' => 'LBL_DO_NOT_EMAIL',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'email_send_id_c',
            'label' => 'LBL_EMAIL_SEND_ID',
          ),
          1 => 
          array (
            'name' => 'email_send_status_c',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_SEND_STATUS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'email_send_design_request_id_c',
            'label' => 'LBL_EMAIL_SEND_DESIGN_REQUEST_ID',
          ),
          1 => 
          array (
            'name' => 'email_send_design_status_c',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_SEND_DESIGN_STATUS',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'system_size_c',
            'label' => 'LBL_SYSTEM_SIZE',
          ),
          1 => 
          array (
            'name' => 'units_per_day_c',
            'label' => 'LBL_UNITS_PER_DAY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'dolar_month_c',
            'label' => 'LBL_DOLAR_MONTH',
          ),
          1 => 
          array (
            'name' => 'number_of_people_c',
            'label' => 'LBL_NUMBER_OF_PEOPLE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'build_account_c',
            'label' => 'LBL_BUILD_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'file_attachment_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_ATTACHMENT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'phone_num_registered_account_c',
            'label' => 'PHONE NUMBER REGISTERED WITH ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'block_files_for_email_c',
            'studio' => 'visible',
            'label' => 'LBL_BLOCK_FILES_FOR_EMAIL',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'jemena_account_c',
            'label' => 'LBL_JEMENA_ACCOUNT',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'live_chat_c',
            'studio' => 'visible',
            'label' => 'LBL_LIVE_CHAT',
          ),
          1 => 
          array (
            'name' => 'file_rename_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_RENAME',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'solargain_lead_number_c',
            'label' => 'LBL_SOLARGAIN_LEAD_NUMBER',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'solargain_options_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLARGAIN_OPTIONS',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'solargain_inverter_model_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLARGAIN_INVERTER_MODEL',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'solargain_offices_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLARGAIN_OFFICES',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'special_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_SPECIAL_NOTES',
          ),
          1 => 
          array (
            'name' => 'price_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_PRICE_NOTES',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'quote_date_c',
            'label' => 'LBL_QUOTE_DATE',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'sent_follow_up_on_old_quote__c',
            'label' => 'LBL_SENT_FOLLOW_UP_ON_OLD_QUOTE_',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_pictures_c',
            'label' => 'LBL_INSTALLATION_PICTURES',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'sms_number_c',
            'label' => 'LBL_SMS_NUMBER',
          ),
          1 => 
          array (
            'name' => 'last_time_sent_sms_c',
            'label' => 'LBL_LAST_TIME_SENT_SMS',
          ),
        ),
      ),
    ),
  ),
);
;
?>
