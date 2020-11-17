<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2016-01-12 15:13:34
$dictionary["Account"]["fields"]["accounts_aos_quotes_1"] = array (
  'name' => 'accounts_aos_quotes_1',
  'type' => 'link',
  'relationship' => 'accounts_aos_quotes_1',
  'source' => 'non-db',
  'module' => 'AOS_Quotes',
  'bean_name' => 'AOS_Quotes',
  'vname' => 'LBL_ACCOUNTS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
);


// created: 2019-03-13 18:27:33
$dictionary["Account"]["fields"]["calls_accounts_1"] = array (
  'name' => 'calls_accounts_1',
  'type' => 'link',
  'relationship' => 'calls_accounts_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_accounts_1calls_ida',
);
$dictionary["Account"]["fields"]["calls_accounts_1_name"] = array (
  'name' => 'calls_accounts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_accounts_1calls_ida',
  'link' => 'calls_accounts_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["Account"]["fields"]["calls_accounts_1calls_ida"] = array (
  'name' => 'calls_accounts_1calls_ida',
  'type' => 'link',
  'relationship' => 'calls_accounts_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
);


// created: 2018-07-04 09:14:55
$dictionary["Account"]["fields"]["pe_smsmanager_accounts"] = array (
  'name' => 'pe_smsmanager_accounts',
  'type' => 'link',
  'relationship' => 'pe_smsmanager_accounts',
  'source' => 'non-db',
  'module' => 'pe_smsmanager',
  'bean_name' => false,
  'vname' => 'LBL_PE_SMSMANAGER_ACCOUNTS_FROM_PE_SMSMANAGER_TITLE',
);


 // created: 2018-10-15 19:16:05
$dictionary['Account']['fields']['active_not_active_c']['inline_edit']='1';
$dictionary['Account']['fields']['active_not_active_c']['labelValue']='Active :';

 

 // created: 2018-10-15 19:02:31
$dictionary['Account']['fields']['check_account_type_c']['inline_edit']='1';
$dictionary['Account']['fields']['check_account_type_c']['labelValue']='Account Type:';

 

 // created: 2019-01-21 20:53:16
$dictionary['Account']['fields']['complicated_drain_run_c']['inline_edit']='1';
$dictionary['Account']['fields']['complicated_drain_run_c']['labelValue']='Complicated Drain Run +$100';

 

 // created: 2019-01-21 21:01:28
$dictionary['Account']['fields']['ec_local_add_rcd_45_c']['inline_edit']='1';
$dictionary['Account']['fields']['ec_local_add_rcd_45_c']['labelValue']=' Electrical connection: Local add rcd +$45';

 

 // created: 2019-01-21 20:59:54
$dictionary['Account']['fields']['ec_local_standard_c']['inline_edit']='1';
$dictionary['Account']['fields']['ec_local_standard_c']['labelValue']='(Electrical connection) Local Standard';

 

 // created: 2019-01-21 21:00:53
$dictionary['Account']['fields']['ec_new_circuit_95_c']['inline_edit']='1';
$dictionary['Account']['fields']['ec_new_circuit_95_c']['labelValue']=' Electrical connection: New Circuit +$95';

 

 // created: 2019-01-21 20:44:26
$dictionary['Account']['fields']['electric_run_ext_wall_c']['inline_edit']='1';
$dictionary['Account']['fields']['electric_run_ext_wall_c']['labelValue']='Electric Run Ext Wall (4Metres inc): +$25Metre';

 

 // created: 2019-01-21 20:43:53
$dictionary['Account']['fields']['electric_run_roof_cavity_c']['inline_edit']='1';
$dictionary['Account']['fields']['electric_run_roof_cavity_c']['labelValue']='Fridge Pipe Run Roof Cavity +$100 +$25Metre';

 

 // created: 2019-01-21 20:44:56
$dictionary['Account']['fields']['electric_run_sub_floor_c']['inline_edit']='1';
$dictionary['Account']['fields']['electric_run_sub_floor_c']['labelValue']='Electric Run Sub Floor: +$50 + 25Metre';

 

 // created: 2018-10-15 19:04:55
$dictionary['Account']['fields']['email_tracking_c']['inline_edit']='1';
$dictionary['Account']['fields']['email_tracking_c']['labelValue']='Email Tracking :';

 

 // created: 2019-01-21 20:55:15
$dictionary['Account']['fields']['eul_2nd_story_walkable_55_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_2nd_story_walkable_55_c']['labelValue']='(External Unit Location) 2nd Story Walkable +$55';

 

 // created: 2019-01-21 20:57:21
$dictionary['Account']['fields']['eul_2nd_story_wall_300_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_2nd_story_wall_300_c']['labelValue']='(External Unit Location) 2nd Story Wall +$300';

 

 // created: 2019-01-21 20:55:56
$dictionary['Account']['fields']['eul_ground_standard_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_ground_standard_c']['labelValue']='(External Unit Location) Ground standard';

 

 // created: 2019-01-21 20:57:02
$dictionary['Account']['fields']['eul_high_wall_85_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_high_wall_85_c']['labelValue']='(External Unit Location) High Wall +$85';

 

 // created: 2019-01-21 20:54:54
$dictionary['Account']['fields']['eul_low_wall_30_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_low_wall_30_c']['labelValue']='(External Unit Location) Low Wall +$30';

 

 // created: 2019-01-21 20:55:36
$dictionary['Account']['fields']['eul_sub_floor_diff_200_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_sub_floor_diff_200_c']['labelValue']='(External Unit Location) Sub Floor Diff +$200';

 

 // created: 2019-01-21 20:56:42
$dictionary['Account']['fields']['eul_subfloor_100_c']['inline_edit']='1';
$dictionary['Account']['fields']['eul_subfloor_100_c']['labelValue']='(External Unit Location) Subfloor +$100';

 

 // created: 2019-01-21 20:51:43
$dictionary['Account']['fields']['extra_description_c']['inline_edit']='1';
$dictionary['Account']['fields']['extra_description_c']['labelValue']='Misc Extras Description';

 

 // created: 2019-01-21 20:48:50
$dictionary['Account']['fields']['fridge_pipe_run_external15_c']['inline_edit']='1';
$dictionary['Account']['fields']['fridge_pipe_run_external15_c']['labelValue']='Fridge Pipe Run Ext (1.5Metres inc) +$25Metre';

 

 // created: 2019-05-10 13:11:30
$dictionary['Account']['fields']['home_phone_c']['inline_edit']='1';
$dictionary['Account']['fields']['home_phone_c']['labelValue']='Home Phone';

 

 // created: 2019-01-21 20:50:30
$dictionary['Account']['fields']['internal_wall_install_c']['inline_edit']='1';
$dictionary['Account']['fields']['internal_wall_install_c']['labelValue']='Internal Wall Install +$200';

 

 // created: 2016-01-12 11:21:35
$dictionary['Account']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Account']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Account']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2016-01-12 11:21:35
$dictionary['Account']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

 // created: 2019-01-21 20:51:17
$dictionary['Account']['fields']['misc_extras_c']['inline_edit']='1';
$dictionary['Account']['fields']['misc_extras_c']['labelValue']='Misc Extras $';

 

 // created: 2018-11-29 13:42:52
$dictionary['Account']['fields']['mobile_phone_c']['inline_edit']='1';
$dictionary['Account']['fields']['mobile_phone_c']['labelValue']='Mobile Phone';

 

 // created: 2019-06-07 11:52:40
$dictionary['Account']['fields']['primary_contact_c']['inline_edit']='1';
$dictionary['Account']['fields']['primary_contact_c']['labelValue']='Primary Contact ';

 

 // created: 2019-01-21 20:49:23
$dictionary['Account']['fields']['refrigeration_pipe_roof100_c']['inline_edit']='1';
$dictionary['Account']['fields']['refrigeration_pipe_roof100_c']['labelValue']='Refrigeration pipe run roof cavity +$100 +$25 Metre)';

 

 // created: 2019-01-21 20:54:00
$dictionary['Account']['fields']['travel_additional_km_c']['inline_edit']='1';
$dictionary['Account']['fields']['travel_additional_km_c']['labelValue']='Travel Additional (km)';

 

 // created: 2020-01-23 18:24:46
$dictionary['Account']['fields']['account_type']['len']=100;
$dictionary['Account']['fields']['account_type']['inline_edit']=true;
$dictionary['Account']['fields']['account_type']['comments']='The Company is of this type';
$dictionary['Account']['fields']['account_type']['merge_filter']='disabled';

 

 // created: 2020-01-09 20:35:18
$dictionary['Account']['fields']['additional_piping_c']['inline_edit']='1';
$dictionary['Account']['fields']['additional_piping_c']['labelValue']='1 meter additional piping';

 

 // created: 2020-01-09 20:42:52
$dictionary['Account']['fields']['base_install_rate_c']['inline_edit']='1';
$dictionary['Account']['fields']['base_install_rate_c']['labelValue']='Base install rate';

 

 // created: 2020-01-09 20:36:03
$dictionary['Account']['fields']['bend_c']['inline_edit']='1';
$dictionary['Account']['fields']['bend_c']['labelValue']='Bend';

 

 // created: 2020-01-23 18:20:38
$dictionary['Account']['fields']['daikin_account_number_c']['inline_edit']='1';
$dictionary['Account']['fields']['daikin_account_number_c']['labelValue']='Supplier Account Number';

 

 // created: 2019-10-08 18:22:56
$dictionary['Account']['fields']['daikin_installer_c']['inline_edit']='1';
$dictionary['Account']['fields']['daikin_installer_c']['labelValue']='Daikin installer';

 

 // created: 2020-01-23 14:09:07
$dictionary['Account']['fields']['description']['inline_edit']=true;
$dictionary['Account']['fields']['description']['comments']='Full text of the note';
$dictionary['Account']['fields']['description']['merge_filter']='disabled';

 

 // created: 2019-10-08 18:23:40
$dictionary['Account']['fields']['distance_to_daikin_installer_c']['inline_edit']='1';
$dictionary['Account']['fields']['distance_to_daikin_installer_c']['labelValue']='Distance To Daikin Installer';

 

 // created: 2020-01-09 20:37:04
$dictionary['Account']['fields']['entry_for_wall_bracket_c']['inline_edit']='1';
$dictionary['Account']['fields']['entry_for_wall_bracket_c']['labelValue']='1 entry for wall bracket';

 

 // created: 2019-07-01 11:59:39
$dictionary['Account']['fields']['lead_source_co_c']['inline_edit']='1';
$dictionary['Account']['fields']['lead_source_co_c']['labelValue']='Lead source (Co.):';

 

 // created: 2019-10-04 13:41:34
$dictionary['Account']['fields']['sanden_electrician_c']['inline_edit']='1';
$dictionary['Account']['fields']['sanden_electrician_c']['labelValue']='Sanden Electrician';

 

 // created: 2019-10-02 13:56:13
$dictionary['Account']['fields']['sanden_plumber_c']['inline_edit']='1';
$dictionary['Account']['fields']['sanden_plumber_c']['labelValue']='Sanden Plumber';

 

 // created: 2019-06-26 12:53:11
$dictionary['Account']['fields']['tester_c']['inline_edit']='1';
$dictionary['Account']['fields']['tester_c']['labelValue']='tester';

 

 // created: 2020-01-09 20:33:13
$dictionary['Account']['fields']['wall_bracket_c']['inline_edit']='1';
$dictionary['Account']['fields']['wall_bracket_c']['labelValue']='Wall bracket';

 

 // created: 2020-09-09 01:43:30
$dictionary['Account']['fields']['abn_c']['inline_edit']='1';
$dictionary['Account']['fields']['abn_c']['labelValue']='ABN';

 

 // created: 2020-09-09 01:37:59
$dictionary['Account']['fields']['system_owner_type_c']['inline_edit']='1';
$dictionary['Account']['fields']['system_owner_type_c']['labelValue']='System Owner Type';

 

 // created: 2020-09-09 01:41:22
$dictionary['Account']['fields']['good_services_tax_c']['inline_edit']='1';
$dictionary['Account']['fields']['good_services_tax_c']['labelValue']='Good & Services Tax';

 

 // created: 2020-09-09 01:41:51
$dictionary['Account']['fields']['main_business_location_c']['inline_edit']='1';
$dictionary['Account']['fields']['main_business_location_c']['labelValue']='Main Business Location';

 

 // created: 2020-09-09 01:38:57
$dictionary['Account']['fields']['registered_for_gst_c']['inline_edit']='1';
$dictionary['Account']['fields']['registered_for_gst_c']['labelValue']='Registered for GST';

 

 // created: 2020-09-09 01:39:41
$dictionary['Account']['fields']['entity_name_c']['inline_edit']='1';
$dictionary['Account']['fields']['entity_name_c']['labelValue']='Entity Name';

 

 // created: 2020-09-09 01:40:47
$dictionary['Account']['fields']['entity_type_c']['inline_edit']='1';
$dictionary['Account']['fields']['entity_type_c']['labelValue']='Entity Type';

 

 // created: 2020-09-09 01:42:18
$dictionary['Account']['fields']['business_name_c']['inline_edit']='1';
$dictionary['Account']['fields']['business_name_c']['labelValue']='Business Name';

 

 // created: 2020-09-09 01:42:59
$dictionary['Account']['fields']['trading_name_c']['inline_edit']='1';
$dictionary['Account']['fields']['trading_name_c']['labelValue']='Trading name';

 

 // created: 2020-09-09 01:44:01
$dictionary['Account']['fields']['abn_status_c']['inline_edit']='1';
$dictionary['Account']['fields']['abn_status_c']['labelValue']='Abn Status';

 

 // created: 2020-09-09 01:44:36
$dictionary['Account']['fields']['abn_lookup_c']['inline_edit']='1';
$dictionary['Account']['fields']['abn_lookup_c']['labelValue']='ABN Lookup';

 

 // created: 2020-09-04 00:58:23
$dictionary['Account']['fields']['daikin_installer_tbc_c']['inline_edit']='1';
$dictionary['Account']['fields']['daikin_installer_tbc_c']['labelValue']='Daikin Installer TBC';

 

 // created: 2020-09-04 00:57:04
$dictionary['Account']['fields']['sanden_installer_tbc_c']['inline_edit']='1';
$dictionary['Account']['fields']['sanden_installer_tbc_c']['labelValue']='Sanden Installer TBC ';

 

 // created: 2020-09-04 00:57:38
$dictionary['Account']['fields']['sanden_electrician_tbc_c']['inline_edit']='1';
$dictionary['Account']['fields']['sanden_electrician_tbc_c']['labelValue']='Sanden Electrician TBC ';

 
?>