<?php
$module_name = 'AOS_Invoices';
$_object_name = 'aos_invoices';
$viewdefs [$module_name] = 
array (
'QuickCreate' =>
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
        'LBL_LINE_ITEMS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL8' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL7' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL5' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL6' => 
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
            'name' => 'name',
            'displayParams' => 
            array (
              'required' => true,
            ),
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'number',
            'label' => 'LBL_INVOICE_NUMBER',
            'customCode' => '{$fields.number.value}',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'quote_number',
            'label' => 'LBL_QUOTE_NUMBER',
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
            'name' => 'due_date',
            'label' => 'LBL_DUE_DATE',
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
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'opportunities_aos_invoices_1_name',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'next_action_date_c',
            'label' => 'LBL_NEXT_ACTION_DATE',
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
            'name' => 'sanden_hp_date_c',
            'label' => 'LBL_SANDEN_HP_DATE',
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
            'name' => 'hp_supply_invoice_number_c',
            'label' => 'LBL_HP_SUPPLY_INVOICE_NUMBER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'tank_supply_invoice_number_c',
            'label' => 'LBL_TANK_SUPPLY_INVOICE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'old_tank_model_c',
            'label' => 'LBL_OLD_TANK_MODEL',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'old_tank_serial_c',
            'label' => 'LBL_OLD_TANK_SERIAL',
          ),
          1 => 
          array (
            'name' => 'old_tank_date_c',
            'label' => 'LBL_OLD_TANK_DATE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'old_tank_make_c',
            'label' => 'LBL_OLD_TANK_MAKE',
          ),
          1 => 
          array (
            'name' => 'old_tank_fuel_c',
            'studio' => 'visible',
            'label' => 'LBL_OLD_TANK_FUEL',
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
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'system_owner_type_c',
            'studio' => 'visible',
            'label' => 'LBL_SYSTEM_OWNER_TYPE',
          ),
          1 => 
          array (
            'name' => 'registered_for_gst_c',
            'studio' => 'visible',
            'label' => 'LBL_REGISTERED_FOR_GST',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'decommissioning_system_locat_c',
            'studio' => 'visible',
            'label' => 'LBL_DECOMMISSIONING_SYSTEM_LOCAT',
          ),
          1 => 
          array (
            'name' => 'abn_c',
            'label' => 'LBL_ABN',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'removal_of_decommissioned_pr_c',
            'studio' => 'visible',
            'label' => 'LBL_REMOVAL_OF_DECOMMISSIONED_PR',
          ),
          1 => 
          array (
            'name' => 'entity_name_c',
            'label' => 'LBL_ENTITY_NAME',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'installation_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INSTALLATION_TYPE',
          ),
          1 => 
          array (
            'name' => 'abn_status_c',
            'label' => 'LBL_ABN_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'decommissioning_method_c',
            'studio' => 'visible',
            'label' => 'LBL_DECOMMISSIONING_METHOD',
          ),
          1 => 
          array (
            'name' => 'entity_type_c',
            'label' => 'LBL_ENTITY_TYPE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'property_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PROPERTY_TYPE',
          ),
          1 => 
          array (
            'name' => 'good_services_tax_c',
            'label' => 'LBL_GOOD_SERVICES_TAX',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'tentative_c',
            'label' => 'LBL_TENTATIVE',
          ),
          1 => 
          array (
            'name' => 'main_business_location_c',
            'label' => 'LBL_MAIN_BUSINESS_LOCATION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'installation_date_c',
            'label' => 'LBL_INSTALLATION_DATE',
          ),
          1 => 
          array (
            'name' => 'business_name_c',
            'label' => 'LBL_BUSINESS_NAME',
          ),
        ),
        8 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'trading_name_c',
            'label' => 'LBL_TRADING_NAME',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'vba_pic_cert_c',
            'label' => 'LBL_VBA_PIC_CERT',
          ),
          1 => 
          array (
            'name' => 'asic_registation_acn_or_arbn_c',
            'label' => 'LBL_ASIC_REGISTATION_ACN_OR_ARBN',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'ces_cert_c',
            'label' => 'LBL_CES_CERT',
          ),
          1 => 
          array (
            'name' => 'payment_for_cert_c',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_FOR_CERT',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'plumber_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER',
          ),
          1 => 
          array (
            'name' => 'number_of_storeys_c',
            'studio' => 'visible',
            'label' => 'LBL_NUMBER_OF_STOREYS',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'electrician_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN',
          ),
          1 => 
          array (
            'name' => 'overide_due_date_c',
            'label' => 'LBL_OVERIDE_DUE_DATE',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'plumber_install_date_c',
            'label' => 'LBL_PLUMBER_INSTALL_DATE',
          ),
          1 => 
          array (
            'name' => 'ces_cert_date_c',
            'label' => 'LBL_CES_CERT_DATE',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'stc_aggregator_c',
            'label' => 'LBL_STC_AGGREGATOR',
          ),
          1 => 
          array (
            'name' => 'vba_pic_date_c',
            'label' => 'LBL_VBA_PIC_DATE',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'stc_aggregator_serial_2_c',
            'label' => 'LBL_STC_AGGREGATOR_SERIAL_2',
          ),
          1 => 
          array (
            'name' => 'plumber_invoice_number_c',
            'label' => 'LBL_PLUMBER_INVOICE_NUMBER',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'electrician_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN_CONTACT',
          ),
          1 => 
          array (
            'name' => 'electrician_invoice_number_c',
            'label' => 'LBL_ELECTRICIAN_INVOICE_NUMBER',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'site_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_SITE_CONTACT',
          ),
          1 => 
          array (
            'name' => 'electrician_install_date_c',
            'label' => 'LBL_ELECTRICIAN_INSTALL_DATE',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'pe_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PE_CONTACT',
          ),
          1 => 
          array (
            'name' => 'stc_aggregator_serial_c',
            'label' => 'LBL_STC_AGGREGATOR_SERIAL',
          ),
        ),
        19 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'status_geo_c',
            'label' => 'LBL_STATUS_GEO',
          ),
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'plumbing_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBING_NOTES',
          ),
          1 => 
          array (
            'name' => 'send_geo_email_status_c',
            'studio' => 'visible',
            'label' => 'LBL_SEND_GEO_EMAIL_STATUS',
          ),
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'plumber_po_c',
            'label' => 'LBL_PLUMBER_PO',
          ),
          1 => 
          array (
            'name' => 'plumber_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_CONTACT',
          ),
        ),
        22 => 
        array (
          0 => 
          array (
            'name' => 'customer_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOMER_NOTES',
          ),
          1 => 
          array (
            'name' => 'site_backup_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_SITE_BACKUP_CONTACT',
          ),
        ),
        23 => 
        array (
          0 => 
          array (
            'name' => 'meeting_c',
            'label' => 'LBL_MEETING',
          ),
          1 => 
          array (
            'name' => 'pe_backup_contact_c',
            'studio' => 'visible',
            'label' => 'LBL_PE_BACKUP_CONTACT',
          ),
        ),
        24 => 
        array (
          0 => 
          array (
            'name' => 'carrier_c',
            'label' => 'LBL_CARRIER',
          ),
          1 => 
          array (
            'name' => 'electrical_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICAL_NOTES',
          ),
        ),
        25 => 
        array (
          0 => 
          array (
            'name' => 'installation_pictures_c',
            'label' => 'LBL_INSTALLATION_PICTURES',
          ),
          1 => 
          array (
            'name' => 'electrical_po_c',
            'label' => 'LBL_ELECTRICAL_PO',
          ),
        ),
        26 => 
        array (
          0 => 
          array (
            'name' => 'file_rename_c',
            'studio' => 'visible',
            'label' => 'LBL_FILE_RENAME',
          ),
          1 => 
          array (
            'name' => 'installation_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_INSTALLATION_NOTES',
          ),
        ),
        27 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'con_note_c',
            'label' => 'LBL_CON_NOTE',
          ),
        ),
        28 => 
        array (
          0 => '',
          1 => '',
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
            'name' => 'delivery_contact_address_c',
            'label' => 'LBL_DELIVERY_CONTACT_ADDRESS',
          ),
        ),
        2 => 
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
        3 => 
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
        4 => 
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
            'name' => 'plumber_c_daikin_c',
            'studio' => 'visible',
            'label' => 'LBL_PLUMBER_C_DAIKIN',
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
            'name' => 'electrician_c_daikin_c',
            'studio' => 'visible',
            'label' => 'LBL_ELECTRICIAN_C_DAIKIN',
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
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'phone_call_c',
            'label' => 'LBL_PHONE_CALL',
          ),
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
