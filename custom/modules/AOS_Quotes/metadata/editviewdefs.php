<?php
$module_name = 'AOS_Quotes';
$_object_name = 'aos_quotes';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_ACCOUNT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL11' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL10' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL15' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL17' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL18' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL19' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL14' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL9' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL8' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL16' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL12' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL13' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/CustomQuotes.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/CustomExtraPriceQuotes.js',
        ),
        2 => 
        array (
          'file' => 'custom/modules/AOS_Invoices/CustomInvoices.js',
        ),
        3 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/html2canvas.js',
        ),
        4 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/canvas2image.js',
        ),
        5 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/CustomQuoteInputsView.js',
        ),
        6 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/CustomOwnSolarPricing.js',
        ),
        7 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/CustomOffGridPricing.js',
        ),
        8 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/customDaikinPricing.js',
        ),
        9 => 
        array (
          'file' => 'custom/modules/AOS_Quotes/customBatteryPricing.js',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_account_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'number',
            'label' => 'LBL_QUOTE_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
          1 => 
          array (
            'name' => 'the_quote_prepared_c',
            'studio' => 'visible',
            'label' => 'LBL_THE_QUOTE_PREPARED_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'stage',
            'label' => 'LBL_STAGE',
          ),
          1 => 
          array (
            'name' => 'opportunity',
            'label' => 'LBL_OPPORTUNITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'displayParams' => 
            array (
              'required' => true,
            ),
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'email_send_design_request_id_c',
            'label' => 'LBL_EMAIL_SEND_DESIGN_REQUEST_ID',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'quote_type_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_TYPE',
          ),
          1 => 
          array (
            'name' => 'email_send_design_status_c',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_SEND_DESIGN_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'invoice_status',
            'label' => 'LBL_INVOICE_STATUS',
          ),
          1 => 
          array (
            'name' => 'assigned_user_c',
            'label' => 'LBL_ASSIGNED_USER_C',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'quote_date_c',
            'label' => 'LBL_QUOTE_DATE',
          ),
          1 => 
          array (
            'name' => 'assigned_user_lockout_c',
            'label' => 'LBL_ASSIGNED_USER_LOCKOUT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'expiration',
            'label' => 'LBL_EXPIRATION',
          ),
          1 => 
          array (
            'name' => 'do_not_email_c',
            'label' => 'LBL_DO_NOT_EMAIL',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'next_action_date_c',
            'label' => 'LBL_NEXT_ACTION_DATE',
          ),
          1 => 
          array (
            'name' => 'designer_c',
            'studio' => 'visible',
            'label' => 'LBL_DESIGNER',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'sender_c',
            'studio' => 'visible',
            'label' => 'LBL_SENDER',
          ),
          1 => 
          array (
            'name' => 'term',
            'label' => 'LBL_TERM',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'sg_assigned_user_c',
            'studio' => 'visible',
            'label' => 'LBL_SG_ASSIGNED_USER_C',
          ),
          1 => 
          array (
            'name' => 'bank_ref_c',
            'label' => 'LBL_BANK_REF',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'approval_status',
            'label' => 'LBL_APPROVAL_STATUS',
          ),
          1 => 
          array (
            'name' => 'approval_issue',
            'label' => 'LBL_APPROVAL_ISSUE',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'leads_aos_quotes_1_name',
            'label' => 'LBL_LEADS_AOS_QUOTES_1_FROM_LEADS_TITLE',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'lead_source_co_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE_CO_C',
          ),
          1 => 
          array (
            'name' => 'lead_source_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'quote_note_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_NOTE',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'time_accepted_job_c',
            'label' => 'LBL_TIME_ACCEPTED_JOB_C',
          ),
          1 => 
          array (
            'name' => 'time_completed_job_c',
            'label' => 'LBL_TIME_COMPLETED_JOB',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'time_request_design_c',
            'label' => 'LBL_TIME_REQUEST_DESIGN_C',
          ),
          1 => 
          array (
            'name' => 'seek_install_date_c',
            'label' => 'LBL_SEEK_INSTALL_DATE_C',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'time_sent_to_client_c',
            'label' => 'LBL_TIME_SENT_TO_CLIENT_C',
          ),
          1 => 
          array (
            'name' => 'proposed_install_date_c',
            'label' => 'LBL_PROPOSED_INSTALL_DATE',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'proposed_dispatch_date_c',
            'label' => 'LBL_PROPOSED_DISPATCH_DATE',
          ),
          1 => 
          array (
            'name' => 'proposed_delivery_date_c',
            'label' => 'LBL_PROPOSED_DELIVERY_DATE',
          ),
        ),
      ),
      'lbl_editview_panel11' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pe_site_details_no_c',
            'label' => 'LBL_PE_SITE_DETAILS_NO_C',
          ),
          1 => 
          array (
            'name' => 'sg_site_details_no_c',
            'label' => 'LBL_SG_SITE_DETAILS_NO_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'solargain_quote_number_c',
            'label' => 'LBL_SOLARGAIN_QUOTE_NUMBER',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'solargain_tesla_quote_number_c',
            'label' => 'LBL_SOLARGAIN_TESLA_QUOTE_NUMBER',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'install_address_c',
            'label' => 'LBL_INSTALL_ADDRESS',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'install_address_city_c',
            'label' => 'LBL_INSTALL_ADDRESS_CITY',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'install_address_state_c',
            'label' => 'LBL_INSTALL_ADDRESS_STATE',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'install_address_postalcode_c',
            'label' => 'LBL_INSTALL_ADDRESS_POSTALCODE',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'install_address_country_c',
            'label' => 'LBL_INSTALL_ADDRESS_COUNTRY',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'old_tank_fuel_c',
            'studio' => 'visible',
            'label' => 'LBL_OLD_TANK_FUEL',
          ),
          1 => 
          array (
            'name' => 'address_provided_c',
            'label' => 'LBL_ADDRESS_PROVIDED_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'distance_to_sg_c',
            'label' => 'LBL_DISTANCE_TO_SG_C',
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
            'name' => 'solargain_offices_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLARGAIN_OFFICES',
          ),
          1 => 
          array (
            'name' => 'meter_type_c',
            'studio' => 'visible',
            'label' => 'LBL_METER_TYPE',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'customer_type_c',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOMER_TYPE',
          ),
          1 => 
          array (
            'name' => 'export_meter_c',
            'label' => 'LBL_EXPORT_METER_C',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'gutter_height_c',
            'studio' => 'visible',
            'label' => 'LBL_GUTTER_HEIGHT',
          ),
          1 => 
          array (
            'name' => 'cable_size_c',
            'label' => 'LBL_CABLE_SIZE_C',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'potential_issues_c',
            'studio' => 'visible',
            'label' => 'LBL_POTENTIAL_ISSUES_C',
          ),
          1 => 
          array (
            'name' => 'main_type_c',
            'studio' => 'visible',
            'label' => 'LBL_MAIN_TYPE',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'main_switch_c',
            'studio' => 'visible',
            'label' => 'LBL_MAIN_SWITCH_C',
          ),
          1 => 
          array (
            'name' => 'external_or_internal_c',
            'studio' => 'visible',
            'label' => 'LBL_EXTERNAL_OR_INTERNAL_C',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'inverter_to_mainswitch_c',
            'label' => 'LBL_INVERTER_TO_MAINSWITCH_C',
          ),
          1 => '',
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'connection_type_c',
            'studio' => 'visible',
            'label' => 'LBL_CONNECTION_TYPE',
          ),
          1 => 
          array (
            'name' => 'meter_phase_c',
            'studio' => 'visible',
            'label' => 'LBL_METER_PHASE',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'meter_number_c',
            'label' => 'LBL_METER_NUMBER',
          ),
          1 => 
          array (
            'name' => 'account_number_c',
            'label' => 'LBL_ACCOUNT_NUMBER',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'nmi_c',
            'label' => 'LBL_NMI',
          ),
          1 => 
          array (
            'name' => 'name_on_billing_account_c',
            'label' => 'LBL_NAME_ON_BILLING_ACCOUNT',
          ),
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'address_nmi_c',
            'label' => 'LBL_ADDRESS_NMI',
          ),
          1 => 
          array (
            'name' => 'energy_retailer_c',
            'studio' => 'visible',
            'label' => 'LBL_ENERGY_RETAILER',
          ),
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'distributor_c',
            'studio' => 'visible',
            'label' => 'LBL_DISTRIBUTOR',
          ),
          1 => 
          array (
            'name' => 'jemena_account_c',
            'label' => 'LBL_JEMENA_ACCOUNT',
          ),
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'vic_rebate_c',
            'label' => 'LBL_VIC_REBATE',
          ),
          1 => 
          array (
            'name' => 'live_chat_c',
            'label' => 'LBL_LIVE_CHAT',
          ),
        ),
        22 => 
        array (
          0 => 
          array (
            'name' => 'vic_loan_c',
            'label' => 'LBL_VIC_LOAN',
          ),
          1 => 
          array (
            'name' => 'account_holder_dob_c',
            'label' => 'LBL_ACCOUNT_HOLDER_DOB_C',
          ),
        ),
      ),
      'lbl_editview_panel10' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'account_firstname_c',
            'label' => 'LBL_ACCOUNT_FIRSTNAME',
          ),
          1 => 
          array (
            'name' => 'account_lastname_c',
            'label' => 'LBL_ACCOUNT_LASTNAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'billing_account',
            'label' => 'LBL_BILLING_ACCOUNT',
            'displayParams' => 
            array (
              'key' => 
              array (
                0 => 'billing',
                1 => 'shipping',
              ),
              'copy' => 
              array (
                0 => 'billing',
                1 => 'shipping',
              ),
              'billingKey' => 'billing',
              'shippingKey' => 'shipping',
            ),
          ),
          1 => 
          array (
            'name' => 'billing_contact',
            'label' => 'LBL_BILLING_CONTACT',
            'displayParams' => 
            array (
              'initial_filter' => '&account_name="+this.form.{$fields.billing_account.name}.value+"',
            ),
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_BILLING_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
              'copy' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_SHIPPING_ADDRESS_STREET',
          ),
        ),
      ),
      'lbl_editview_panel15' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'quote_note_inputs_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_NOTE_INPUTS',
          ),
          1 => 
          array (
            'name' => 'pricing_option_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PRICING_OPTION_TYPE_C',
          ),
        ),
      ),
      'lbl_editview_panel17' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'quote_cl_rebate_c',
            'studio' => 'visible',
            'label' => 'LBL_QUOTE_CL_REBATE',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel18' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'plumber_line_items',
            'studio' => true,
            'label' => 'LBL_PLUMBER_LINE_ITEMS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'plumber_total_amt',
            'label' => 'LBL_PLUMBER_TOTAL_AMT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'plumber_discount_amount',
            'label' => 'LBL_PLUMBER_DISCOUNT_AMOUNT',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'plumber_subtotal_amount',
            'label' => 'LBL_PLUMBER_SUBTOTAL_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'plumber_shipping_amount',
            'label' => 'LBL_PLUMBER_SHIPPING_AMOUNT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'plumber_shipping_tax_amt',
            'label' => 'LBL_PLUMBER_SHIPPING_TAX_AMT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'plumber_tax_amount',
            'label' => 'LBL_PLUMBER_TAX_AMOUNT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'plumber_total_amount',
            'label' => 'LBL_PLUMBER_GRAND_TOTAL',
          ),
        ),
      ),
      'lbl_editview_panel19' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'electrician_line_items',
            'studio' => true,
            'label' => 'LBL_ELECTRICIAN_LINE_ITEMS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'electrician_total_amt',
            'label' => 'LBL_ELECTRICIAN_TOTAL_AMT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'electrician_discount_amount',
            'label' => 'LBL_ELECTRICIAN_DISCOUNT_AMOUNT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'electrician_subtotal_amount',
            'label' => 'LBL_ELECTRICIAN_SUBTOTAL_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'electrician_shipping_amount',
            'label' => 'LBL_ELECTRICIAN_SHIPPING_AMOUNT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'electrician_shipping_tax',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN_SHIPPING_TAX',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'electrician_tax_amount',
            'label' => 'LBL_ELECTRICIAN_TAX_AMOUNT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'electrician_total_amount',
            'label' => 'LBL_ELECTRICIAN_GRAND_TOTAL',
          ),
        ),
      ),
      'lbl_line_items' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'line_items',
            'label' => 'LBL_LINE_ITEMS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'total_amt',
            'label' => 'LBL_TOTAL_AMT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'subtotal_amount',
            'label' => 'LBL_SUBTOTAL_AMOUNT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'shipping_amount',
            'label' => 'LBL_SHIPPING_AMOUNT',
            'displayParams' => 
            array (
              'field' => 
              array (
                'onblur' => 'calculateTotal(\'lineItems\');',
              ),
            ),
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'shipping_tax_amt',
            'label' => 'LBL_SHIPPING_TAX_AMT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'tax_amount',
            'label' => 'LBL_TAX_AMOUNT',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'total_amount',
            'label' => 'LBL_GRAND_TOTAL',
          ),
        ),
      ),
      'lbl_editview_panel14' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'plumber_new_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_NEW',
          ),
          1 => 
          array (
            'name' => 'distance_to_travel_c',
            'label' => 'LBL_DISTANCE_TO_TRAVEL',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'plumber_electrician_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_ELECTRICIAN',
          ),
          1 => 
          array (
            'name' => 'distance_to_electrician_c',
            'label' => 'LBL_DISTANCE_TO_ELECTRICIAN_C',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'daikin_installer_c',
            'studio' => 'visible',
            'label' => 'LBL_DAIKIN_INSTALLER',
          ),
          1 => 
          array (
            'name' => 'distance_to_daikin_installer_c',
            'label' => 'LBL_DISTANCE_TO_DAIKIN_INSTALLER',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'old_tank_serial_c',
            'label' => 'LBL_OLD_TANK_SERIAL',
          ),
          1 => 
          array (
            'name' => 'old_tank_model_c',
            'label' => 'LBL_OLD_TANK_MODEL',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'old_tank_make_c',
            'label' => 'LBL_OLD_TANK_MAKE',
          ),
          1 => 
          array (
            'name' => 'old_tank_date_c',
            'label' => 'LBL_OLD_TANK_DATE',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'state_c',
            'studio' => 'visible',
            'label' => 'LBL_STATE',
          ),
          1 => 
          array (
            'name' => 'fridge_pipe_run_external15_c',
            'label' => 'LBL_FRIDGE_PIPE_RUN_EXTERNAL15',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'external_unit_location_c',
            'studio' => 'visible',
            'label' => 'LBL_EXTERNAL_UNIT_LOCATION',
          ),
          1 => 
          array (
            'name' => 'electric_run_ext_wall_c',
            'label' => 'LBL_ELECTRIC_RUN_EXT_WALL',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'complicated_drain_run_c',
            'label' => 'LBL_COMPLICATED_DRAIN_RUN',
          ),
          1 => 
          array (
            'name' => 'refrigeration_pipe_roof100_c',
            'label' => 'LBL_REFRIGERATION_PIPE_ROOF100',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'electrical_connection_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICAL_CONNECTION',
          ),
          1 => 
          array (
            'name' => 'electric_run_roof_cavity_c',
            'label' => 'LBL_ELECTRIC_RUN_ROOF_CAVITY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'plumber_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER',
          ),
          1 => 
          array (
            'name' => 'electric_run_sub_floor_c',
            'label' => 'LBL_ELECTRIC_RUN_SUB_FLOOR',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'electrician_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN',
          ),
          1 => 
          array (
            'name' => 'internal_wall_install_c',
            'label' => 'LBL_INTERNAL_WALL_INSTALL',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'travel_additional_km_c',
            'label' => 'LBL_TRAVEL_ADDITIONAL_KM',
          ),
          1 => 
          array (
            'name' => 'team_c',
            'studio' => 'visible',
            'label' => 'LBL_TEAM',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'total_to_contractor_c',
            'label' => 'LBL_TOTAL_TO_CONTRACTOR',
          ),
          1 => 
          array (
            'name' => 'misc_extras_c',
            'label' => 'LBL_MISC_EXTRAS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'total_client_c',
            'label' => 'LBL_TOTAL_CLIENT',
          ),
          1 => 
          array (
            'name' => 'extra_description_c',
            'label' => 'LBL_EXTRA_DESCRIPTION',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pre_install_photos_c',
            'label' => 'LBL_PRE_INSTALL_PHOTOS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'site_c',
            'label' => 'LBL_SITE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'pre_install_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_PRE_INSTALL_NOTES',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'file_rename_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_RENAME',
          ),
        ),
      ),
      'lbl_editview_panel6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'special_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_SPECIAL_NOTES',
          ),
          1 => 
          array (
            'name' => 'solargain_options_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLARGAIN_OPTIONS_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'solargain_lead_number_c',
            'label' => 'LBL_SOLARGAIN_LEAD_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'first_solar_c',
            'studio' => 'visible',
            'label' => 'LBL_FIRST_SOLAR_C',
          ),
        ),
      ),
      'lbl_editview_panel9' => 
      array (
        0 => 
        array (
          0 => '',
          1 => '',
        ),
        1 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
      'lbl_editview_panel8' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'solar_pv_pricing_input_c',
            'studio' => 'visible',
            'label' => 'LBL_SOLAR_PV_PRICING_INPUT',
          ),
          1 => 
          array (
            'name' => 'own_solar_pv_pricing_c',
            'studio' => 'visible',
            'label' => 'LBL_OWN_SOLAR_PV_PRICING',
          ),
        ),
      ),
      'lbl_editview_panel16' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'offgrid_option_c',
            'studio' => 'visible',
            'label' => 'LBL_OFFGRID_OPTION',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel12' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'slv_solar_vic_id_c',
            'label' => 'LBL_SLV_SOLAR_VIC_ID',
          ),
          1 => 
          array (
            'name' => 'slv_status_c',
            'label' => 'LBL_SLV_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'slv_firstname_c',
            'label' => 'LBL_SLV_FIRSTNAME',
          ),
          1 => 
          array (
            'name' => 'slv_lastname_c',
            'label' => 'LBL_SLV_LASTNAME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'slv_email_c',
            'label' => 'LBL_SLV_EMAIL_C',
          ),
          1 => 
          array (
            'name' => 'slv_ebate_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SLV_EBATE_TYPE_C',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'slv_installation_address_c',
            'label' => 'LBL_SLV_INSTALLATION_ADDRESS_C',
          ),
          1 => 
          array (
            'name' => 'slv_panel_type_c',
            'label' => 'LBL_SLV_PANEL_TYPE_C',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'slv_total_panel_c',
            'label' => 'LBL_SLV_TOTAL_PANEL_C',
          ),
          1 => 
          array (
            'name' => 'slv_inverter_type_c',
            'label' => 'LBL_SLV_INVERTER_TYPE_C',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'customer_benefits_c',
            'label' => 'LBL_CUSTOMER_BENEFITS_C',
          ),
          1 => 
          array (
            'name' => 'slv_interested_solar_loan_c',
            'label' => 'LBL_SLV_INTERESTED_SOLAR_LOAN_C',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'slv_dnsp_approval_c',
            'label' => 'LBL_SLV_DNSP_APPROVAL_C',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'estimate_energy_yield_c',
            'label' => 'LBL_ESTIMATE_ENERGY_YIELD_C',
          ),
          1 => 
          array (
            'name' => 'estimated_financial_saving_c',
            'label' => 'LBL_ESTIMATED_FINANCIAL_SAVING_C',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'slv_quote_sg_number_c',
            'label' => 'LBL_SLV_QUOTE_SG_NUMBER_C',
          ),
          1 => 
          array (
            'name' => 'slv_total_price_c',
            'label' => 'LBL_SLV_TOTAL_PRICE_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'slv_estimated_value_c',
            'label' => 'LBL_SLV_ESTIMATED_VALUE_C',
          ),
          1 => 
          array (
            'name' => 'slv_estimated_rebate_c',
            'label' => 'LBL_SLV_ESTIMATED_REBATE_C',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'slv_estimated_free_loan_c',
            'label' => 'LBL_SLV_ESTIMATED_FREE_LOAN_C',
          ),
          1 => 
          array (
            'name' => 'slv_net_payable_c',
            'label' => 'LBL_SLV_NET_PAYABLE_C',
          ),
        ),
      ),
      'lbl_editview_panel13' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'gb_manual',
            'label' => 'LBL_GP_MANUAL',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sanden_supply_bill',
            'label' => 'LBL_SANDEN_SUPPLY_BILL',
          ),
          1 => 
          array (
            'name' => 'sanden_revenue',
            'label' => 'LBL_SANDEN_REVENUE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'sanden_shipping_bill',
            'label' => 'LBL_SANDEN_SHIPPING_BILL',
          ),
          1 => 
          array (
            'name' => 'sanden_stcs',
            'label' => 'LBL_SANDEN_STCS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'plumbing_bill',
            'label' => 'LBL_PLUMBING_BILL',
          ),
          1 => 
          array (
            'name' => 'veec_revenue',
            'label' => 'LBL_VEEC_REVENUE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'electrician_bill',
            'label' => 'LBL_ELECTRICIAN_BILL',
          ),
          1 => 
          array (
            'name' => 'solar_vic_revenue',
            'label' => 'LBL_SOLAR_VIC_REVENUE',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'sa_reps_revenue',
            'label' => 'LBL_SA_REPS_REVENUE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'sanden_total_costs',
            'label' => 'LBL_SANDEN_TOTAL_COSTS',
          ),
          1 => 
          array (
            'name' => 'sanden_total_revenue',
            'label' => 'LBL_SANDEN_TOTAL_REVENUE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'sanden_gross_profit',
            'label' => 'LBL_SANDEN_GROSS_PROFIT',
          ),
          1 => 
          array (
            'name' => 'sanden_gprofit_percent',
            'label' => 'LBL_SANDEN_GPROFIT_PERCENT',
          ),
        ),
      ),
    ),
  ),
);
;
?>
