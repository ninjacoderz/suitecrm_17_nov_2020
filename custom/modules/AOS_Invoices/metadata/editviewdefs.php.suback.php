<?php
$module_name = 'AOS_Invoices';
$_object_name = 'aos_invoices';
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
        'LBL_PANEL_OVERVIEW' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL12' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL10' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL7' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_INVOICE_TO' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' => 
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
        'LBL_EDITVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL13' => 
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
          'file' => 'custom/modules/AOS_Invoices/CustomInvoices.js',
        ),
        1 => 
        array (
          'file' => 'custom/modules/AOS_Invoices/CustomSiteDetails.js',
        ),
        2 => 
        array (
          'file' => 'custom/include/SugarFields/Fields/Multiupload/js/html2canvas.js',
        ),
        3 => 
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
            'label' => 'LBL_INVOICE_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
          1 => 
          array (
            'name' => 'quote_number',
            'label' => 'LBL_QUOTE_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'quote_date',
            'label' => 'LBL_QUOTE_DATE',
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
            'name' => 'invoice_date',
            'label' => 'LBL_INVOICE_DATE',
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
            'name' => 'solargain_invoices_number_c',
            'label' => 'LBL_SOLARGAIN_INVOICES_NUMBER_C',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'formbay_c',
            'label' => 'LBL_FORMBAY',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'due_date',
            'label' => 'LBL_DUE_DATE',
          ),
          1 => 
          array (
            'name' => 'solargain_order_status_c',
            'label' => 'LBL_SOLARGAIN_ORDER_STATUS',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'next_action_date_c',
            'label' => 'LBL_NEXT_ACTION_DATE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'subtotal_c',
            'label' => 'LBL_SUBTOTAL',
          ),
          1 => 
          array (
            'name' => 'delivery_date_time_c',
            'label' => 'LBL_DELIVERY_DATE_TIME',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'profit_c',
            'label' => 'LBL_PROFIT',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'gp_c',
            'label' => 'LBL_GP',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => '',
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'invoice_note_c',
            'studio' => 'visible',
            'label' => 'LBL_INVOICE_NOTE',
          ),
          1 => 
          array (
            'name' => 'opportunities_aos_invoices_1_name',
          ),
        ),
      ),
      'lbl_editview_panel12' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'data_checklist_invoice_c',
            'studio' => 'visible',
            'label' => 'LBL_DATA_CHECKLIST_INVOICE',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel10' => 
      array (
        0 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'data_json_site_details_c',
            'studio' => 'visible',
            'label' => 'LBL_DATA_JSON_SITE_DETAILS',
          ),
        ),
      ),
      'lbl_editview_panel7' => 
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
            'name' => 'plumber_c_daikin_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_C_DAIKIN',
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
            'name' => 'electrician_c_daikin_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN_C_DAIKIN',
          ),
          1 => 
          array (
            'name' => 'electric_run_roof_cavity_c',
            'label' => 'LBL_ELECTRIC_RUN_ROOF_CAVITY',
          ),
        ),
        3 => 
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
        4 => 
        array (
          0 => 
          array (
            'name' => 'internal_wall_install_c',
            'label' => 'LBL_INTERNAL_WALL_INSTALL',
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
            'name' => 'eul_subfloor_100_c',
            'label' => 'LBL_EUL_SUBFLOOR_100',
          ),
          1 => 
          array (
            'name' => 'ec_new_circuit_95_c',
            'label' => 'LBL_EC_NEW_CIRCUIT_95',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'eul_sub_floor_diff_200_c',
            'label' => 'LBL_EUL_SUB_FLOOR_DIFF_200',
          ),
          1 => 
          array (
            'name' => 'ec_local_add_rcd_45_c',
            'label' => 'LBL_EC_LOCAL_ADD_RCD_45',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'eul_high_wall_85_c',
            'label' => 'LBL_EUL_HIGH_WALL_85',
          ),
          1 => 
          array (
            'name' => 'team_c',
            'studio' => 'visible',
            'label' => 'LBL_TEAM',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'eul_low_wall_30_c',
            'label' => 'LBL_EUL_LOW_WALL_30',
          ),
          1 => 
          array (
            'name' => 'misc_extras_c',
            'label' => 'LBL_MISC_EXTRAS',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'eul_2nd_story_wall_300_c',
            'label' => 'LBL_EUL_2ND_STORY_WALL_300',
          ),
          1 => 
          array (
            'name' => 'extra_description_c',
            'label' => 'LBL_EXTRA_DESCRIPTION',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'eul_2nd_story_walkable_55_c',
            'label' => 'LBL_EUL_2ND_STORY_WALKABLE_55',
          ),
          1 => 
          array (
            'name' => 'travel_additional_km_c',
            'label' => 'LBL_TRAVEL_ADDITIONAL_KM',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'total_to_contractor_c',
            'label' => 'LBL_TOTAL_TO_CONTRACTOR',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'total_client_c',
            'label' => 'LBL_TOTAL_CLIENT',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_calendar_id_c',
            'label' => 'LBL_INSTALLATION_CALENDAR_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'installation_date_c',
            'label' => 'LBL_INSTALLATION_DATE',
          ),
          1 => 
          array (
            'name' => 'picked_up_date_c',
            'label' => 'LBL_PICKED_UP_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'site_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_SITE_CONTACT',
          ),
          1 => 
          array (
            'name' => 'pe_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PE_CONTACT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'site_backup_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_SITE_BACKUP_CONTACT',
          ),
          1 => 
          array (
            'name' => 'pe_backup_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PE_BACKUP_CONTACT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'number_of_storeys_c',
            'studio' => 'visible',
            'label' => 'LBL_NUMBER_OF_STOREYS',
          ),
          1 => 
          array (
            'name' => 'overide_due_date_c',
            'label' => 'LBL_OVERIDE_DUE_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'customer_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOMER_NOTES',
          ),
          1 => 
          array (
            'name' => 'carrier_c',
            'label' => 'LBL_CARRIER',
          ),
        ),
        6 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'stc_aggregator_c',
            'label' => 'LBL_STC_AGGREGATOR',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'send_geo_email_status_c',
            'studio' => 'visible',
            'label' => 'LBL_SEND_GEO_EMAIL_STATUS',
          ),
          1 => 
          array (
            'name' => 'stc_aggregator_serial_2_c',
            'label' => 'LBL_STC_AGGREGATOR_SERIAL_2',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'geo_email_sent_date_c',
            'label' => 'LBL_GEO_EMAIL_SENT_DATE',
          ),
          1 => 
          array (
            'name' => 'stc_aggregator_serial_c',
            'label' => 'LBL_STC_AGGREGATOR_SERIAL',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'meeting_c',
            'label' => 'LBL_MEETING',
          ),
          1 => 
          array (
            'name' => 'status_geo_c',
            'label' => 'LBL_STATUS_GEO',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'decommissioning_system_locat_c',
            'studio' => 'visible',
            'label' => 'LBL_DECOMMISSIONING_SYSTEM_LOCAT',
          ),
          1 => '',
        ),
        11 => 
        array (
          0 => '',
          1 => '',
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'removal_of_decommissioned_pr_c',
            'studio' => 'visible',
            'label' => 'LBL_REMOVAL_OF_DECOMMISSIONED_PR',
          ),
          1 => '',
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'installation_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INSTALLATION_TYPE',
          ),
          1 => '',
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'decommissioning_method_c',
            'studio' => 'visible',
            'label' => 'LBL_DECOMMISSIONING_METHOD',
          ),
          1 => '',
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'property_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PROPERTY_TYPE',
          ),
          1 => '',
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'tentative_c',
            'label' => 'LBL_TENTATIVE',
          ),
          1 => '',
        ),
        17 => 
        array (
          0 => '',
          1 => '',
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'plumber_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER',
          ),
          1 => 
          array (
            'name' => 'electrician_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN',
          ),
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'distance_to_suite_c',
            'label' => 'LBL_DISTANCE_TO_SUITE',
          ),
          1 => 
          array (
            'name' => 'distance_to_suitecrm_c',
            'label' => 'LBL_DISTANCE_TO_SUITECRM',
          ),
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'practitioner_verification_c',
            'studio' => 'visible',
            'label' => 'LBL_PRACTITIONER_VERIFICATION',
          ),
          1 => '',
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'plumber_install_date_c',
            'label' => 'LBL_PLUMBER_INSTALL_DATE',
          ),
          1 => 
          array (
            'name' => 'electrician_install_date_c',
            'label' => 'LBL_ELECTRICIAN_INSTALL_DATE',
          ),
        ),
        22 => 
        array (
          0 => 
          array (
            'name' => 'plumber_po_c',
            'label' => 'LBL_PLUMBER_PO',
          ),
          1 => 
          array (
            'name' => 'electrical_po_c',
            'label' => 'LBL_ELECTRICAL_PO',
          ),
        ),
        23 => 
        array (
          0 => 
          array (
            'name' => 'plumbing_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBING_NOTES',
          ),
          1 => 
          array (
            'name' => 'electrical_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICAL_NOTES',
          ),
        ),
        24 => 
        array (
          0 => 
          array (
            'name' => 'plumber_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_CONTACT',
          ),
          1 => 
          array (
            'name' => 'electrician_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN_CONTACT',
          ),
        ),
        25 => 
        array (
          0 => 
          array (
            'name' => 'plumber_license_number_c',
            'label' => 'LBL_PLUMBER_LICENSE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'electrician_license_number_c',
            'label' => 'LBL_ELECTRICIAN_LICENSE_NUMBER',
          ),
        ),
        26 => 
        array (
          0 => 
          array (
            'name' => 'vba_pic_date_c',
            'label' => 'LBL_VBA_PIC_DATE',
          ),
          1 => 
          array (
            'name' => 'ces_cert_date_c',
            'label' => 'LBL_CES_CERT_DATE',
          ),
        ),
        27 => 
        array (
          0 => 
          array (
            'name' => 'vba_pic_cert_c',
            'label' => 'LBL_VBA_PIC_CERT',
          ),
          1 => 
          array (
            'name' => 'ces_cert_c',
            'label' => 'LBL_CES_CERT',
          ),
        ),
        28 => 
        array (
          0 => 
          array (
            'name' => 'plumber_invoice_number_c',
            'label' => 'LBL_PLUMBER_INVOICE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'electrician_invoice_number_c',
            'label' => 'LBL_ELECTRICIAN_INVOICE_NUMBER',
          ),
        ),
        29 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'asic_registation_acn_or_arbn_c',
            'label' => 'LBL_ASIC_REGISTATION_ACN_OR_ARBN',
          ),
        ),
        30 => 
        array (
          0 => 
          array (
            'name' => 'installation_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_INSTALLATION_NOTES',
          ),
          1 => 
          array (
            'name' => 'payment_for_cert_c',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_FOR_CERT',
          ),
        ),
        31 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'con_note_c',
            'label' => 'LBL_CON_NOTE',
          ),
        ),
        32 => 
        array (
          0 => 
          array (
            'name' => 'promo_methven_1_c',
            'label' => 'LBL_PROMO_METHVEN_1',
          ),
          1 => 
          array (
            'name' => 'handheld_1_c',
            'label' => 'LBL_HANDHELD_1',
          ),
        ),
        33 => 
        array (
          0 => 
          array (
            'name' => 'promo_methven_2_c',
            'label' => 'LBL_PROMO_METHVEN_2',
          ),
          1 => 
          array (
            'name' => 'handheld_2_c',
            'label' => 'LBL_HANDHELD_2',
          ),
        ),
        34 => 
        array (
          0 => 
          array (
            'name' => 'promo_methven_3_c',
            'label' => 'LBL_PROMO_METHVEN_3',
          ),
          1 => 
          array (
            'name' => 'handheld_3_c',
            'label' => 'LBL_HANDHELD_3',
          ),
        ),
      ),
      'LBL_INVOICE_TO' => 
      array (
        0 => 
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
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'billing_contact',
            'label' => 'LBL_BILLING_CONTACT',
            'displayParams' => 
            array (
              'initial_filter' => '&account_name="+this.form.{$fields.billing_account.name}.value+"',
            ),
          ),
          1 => 
          array (
            'name' => 'invoice_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INVOICE_TYPE',
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
        3 => 
        array (
          0 => 
          array (
            'name' => 'install_address_c',
            'label' => 'LBL_INSTALL_ADDRESS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'install_address_city_c',
            'label' => 'LBL_INSTALL_ADDRESS_CITY',
          ),
          1 => 
          array (
            'name' => 'install_address_state_c',
            'label' => 'LBL_INSTALL_ADDRESS_STATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'install_address_postalcode_c',
            'label' => 'LBL_INSTALL_ADDRESS_POSTALCODE',
          ),
          1 => 
          array (
            'name' => 'install_address_country_c',
            'label' => 'LBL_INSTALL_ADDRESS_COUNTRY',
          ),
        ),
        6 => 
        array (
          0 => '',
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'system_owner_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SYSTEM_OWNER_TYPE',
          ),
          1 => 
          array (
            'name' => 'good_services_tax_c',
            'label' => 'LBL_GOOD_SERVICES_TAX',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'registered_for_gst_c',
            'studio' => 'visible',
            'label' => 'LBL_REGISTERED_FOR_GST',
          ),
          1 => 
          array (
            'name' => 'main_business_location_c',
            'label' => 'LBL_MAIN_BUSINESS_LOCATION',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'entity_name_c',
            'label' => 'LBL_ENTITY_NAME',
          ),
          1 => 
          array (
            'name' => 'business_name_c',
            'label' => 'LBL_BUSINESS_NAME',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'entity_type_c',
            'label' => 'LBL_ENTITY_TYPE',
          ),
          1 => 
          array (
            'name' => 'trading_name_c',
            'label' => 'LBL_TRADING_NAME',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'abn_c',
            'label' => 'LBL_ABN',
          ),
          1 => '',
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'abn_status_c',
            'label' => 'LBL_ABN_STATUS',
          ),
          1 => '',
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'abn_lookup_c',
            'studio' => 'visible',
            'label' => 'LBL_ABN_LOOKUP',
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
            'name' => 'sanden_model_c',
            'label' => 'LBL_SANDEN_MODEL',
          ),
          1 => 
          array (
            'name' => 'old_tank_fuel_c',
            'studio' => 'visible',
            'label' => 'LBL_OLD_TANK_FUEL',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'number_of_installations_c',
            'studio' => 'visible',
            'label' => 'LBL_NUMBER_OF_INSTALLATIONS',
          ),
          1 => 
          array (
            'name' => 'sanden_hp_date_c',
            'label' => 'LBL_SANDEN_HP_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'sanden_tank_serial_c',
            'label' => 'LBL_SANDEN_TANK_SERIAL',
          ),
          1 => 
          array (
            'name' => 'sanden_tank_date_c',
            'label' => 'LBL_SANDEN_TANK_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'sanden_hp_serial_c',
            'label' => 'LBL_SANDEN_HP_SERIAL',
          ),
          1 => 
          array (
            'name' => 'old_tank_serial_c',
            'label' => 'LBL_OLD_TANK_SERIAL',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'daikin_product_infomation_c',
            'label' => 'LBL_DAIKIN_PRODUCT_INFOMATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'delivery_contact_name_c',
            'label' => 'LBL_DELIVERY_CONTACT_NAME',
          ),
          1 => 
          array (
            'name' => 'delivery_date_c',
            'label' => 'LBL_DELIVERY_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'delivery_contact_address_c',
            'label' => 'LBL_DELIVERY_CONTACT_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'delivery_contact_state_c',
            'label' => 'LBL_DELIVERY_CONTACT_STATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'delivery_contact_suburb_c',
            'label' => 'LBL_DELIVERY_CONTACT_SUBURB',
          ),
          1 => 
          array (
            'name' => 'delivery_contact_postcode_c',
            'label' => 'LBL_DELIVERY_CONTACT_POSTCODE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'delivery_contact_phone_numbe_c',
            'label' => 'LBL_DELIVERY_CONTACT_PHONE_NUMBE',
          ),
          1 => 
          array (
            'name' => 'delivery_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_DELIVERY_NOTES',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'daikin_po_c',
            'label' => 'LBL_DAIKIN_PO',
          ),
          1 => 
          array (
            'name' => 'daikin_supplier_c',
            'studio' => 'visible',
            'label' => 'LBL_DAIKIN_SUPPLIER',
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
      'lbl_editview_panel9' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'installation_pictures_c',
            'label' => 'LBL_INSTALLATION_PICTURES',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'file_rename_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_RENAME',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel8' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'daikin_po_1_c',
            'label' => 'LBL_DAIKIN_PO_1',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'daikin_po_2_c',
            'label' => 'LBL_DAIKIN_PO_2',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'daikin_po_3_c',
            'label' => 'LBL_DAIKIN_PO_3',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'xero_invoice_c',
            'label' => 'LBL_XERO_INVOICE',
          ),
          1 => 
          array (
            'name' => 'xero_shw_rebate_invoice_c',
            'label' => 'LBL_XERO_SHW_REBATE_INVOICE_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'xero_stc_rebate_invoice_c',
            'label' => 'LBL_XERO_STC_REBATE_INVOICE',
          ),
          1 => 
          array (
            'name' => 'xero_veec_rebate_invoice_c',
            'label' => 'LBL_XERO_VEEC_REBATE_INVOICE',
          ),
        ),
      ),
      'lbl_editview_panel13' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'daikin_supply_bill_c',
            'label' => 'LBL_DAIKIN_SUPPLY_BILL',
          ),
          1 => 
          array (
            'name' => 'daikin_install_bill_c',
            'label' => 'LBL_DAIKIN_INSTALL_BILL',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'supply_bill_c',
            'label' => 'LBL_SUPPLY_BILL_C',
          ),
          1 => 
          array (
            'name' => 'install_bill_c',
            'label' => 'LBL_INSTALL_BILL_C',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'total_cost_c',
            'label' => 'LBL_TOTAL_COST_C',
          ),
          1 => 
          array (
            'name' => 'total_revenue_c',
            'label' => 'LBL_TOTAL_REVENUE_C',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'gross_profit_c',
            'label' => 'LBL_GROSS_PROFIT_C',
          ),
          1 => 
          array (
            'name' => 'gross_profit_percent_c',
            'label' => 'LBL_GROSS_PROFIT_PERCENT_C',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'sanden_plumbing_install_bill_c',
            'label' => 'LBL_SANDEN_PLUMBING_INSTALL_BILL',
          ),
          1 => 
          array (
            'name' => 'sanden_electrician_inst_bill_c',
            'label' => 'LBL_SANDEN_ELECTRICIAN_INST_BILL',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'plumbing_bill_c',
            'label' => 'LBL_PLUMBING_BILL',
          ),
          1 => 
          array (
            'name' => 'electrician_bill_c',
            'label' => 'LBL_ELECTRICIAN_BILL',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'sanden_supply_bill_c',
            'label' => 'LBL_SANDEN_SUPPLY_BILL_C',
          ),
          1 => 
          array (
            'name' => 'sanden_total_costs_c',
            'label' => 'LBL_SANDEN_TOTAL_COSTS_C',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'sanden_revenue_c',
            'label' => 'LBL_SANDEN_REVENUE_C',
          ),
          1 => 
          array (
            'name' => 'sanden_stcs_c',
            'label' => 'LBL_SANDEN_STCS_C',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'sanden_total_revenue_c',
            'label' => 'LBL_SANDEN_TOTAL_REVENUE_C',
          ),
          1 => 
          array (
            'name' => 'sanden_gross_profit_c',
            'label' => 'LBL_SANDEN_GROSS_PROFIT_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'sanden_gprofit_percent_c',
            'label' => 'LBL_SANDEN_GPROFIT_PERCENT_C',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'payments_c',
            'label' => 'LBL_PAYMENTS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'next_payment_amount_c',
            'label' => 'LBL_NEXT_PAYMENT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'total_balance_owing_c',
            'label' => 'LBL_TOTAL_BALANCE_OWING',
          ),
        ),
      ),
    ),
  ),
);
;
?>
