<?php
global $current_user;
 date_default_timezone_set('Australia/Melbourne');
$vardefs_plumbung_array = array(
    "sanden_standard_plumbing_install" => array(
        "name" => "sanden_standard_plumbing_install",
        "display_label" => "Sanden Standard Plumbing Install",
        "data_id" => '562c34e0-5231-cd0e-2af6-56977821b1e4',
        "type" => "checkbox",
        "parent" => "",
    ),
    "sanden_tank_600x600_Slab" => array(
        "name" => "sanden_tank_600x600_Slab",
        "display_label" => "Sanden Tank 600x600 Slab",
        "data_id" => '1b0a451b-5410-8a5c-286a-5798ae3d2ec0',
        "type" => "checkbox",
        "parent" => "",
    ),
    "sanden_2x_450x450_pavers" => array(
        "name" => "sanden_2x_450x450_pavers",
        "display_label" => "Sanden 2x 450x450 Pavers",
        "data_id" => '5dd378da-efe5-37a6-8b10-5a711e308ce1',
        "type" => "checkbox",
        "parent" => "",
    ),
    "polyslab_pizza_base" => array(
        "name" => "polyslab_pizza_base",
        "display_label" => "Polyslab Pizza Base",
        "data_id" => '76b70fb5-fcb5-376f-42f6-5c60c571312b',
        "type" => "checkbox",
        "parent" => "",
    ),
    "paperwork_bonus" => array(
        "name" => "paperwork_bonus",
        "display_label" => "Paperwork Bonus",
        "data_id" => 'b3c61c17-5bfc-e4f2-1703-5a5ef9506025',
        "type" => "checkbox",
        "parent" => "",
    ),
    "live_on_site_photo_upload_bonus" => array(
        "name" => "live_on_site_photo_upload_bonus",
        "display_label" => "Live On Site Photo Upload Bonus",
        "data_id" => '75e7707a-053b-6318-df33-60221fbb7dcb',
        "type" => "checkbox",
        "parent" => "",
    ),
    "travel_plumbing" => array(
        "name" => "travel_plumbing",
        "display_label" => "Travel",
        "data_id" => '793419de-a229-9f0d-5180-5a94a8d38ecb',
        "type" => "checkbox",
        "parent" => "",
    ),

);
$options_quatity = array() ;
for( $c = 1; $c <=30; $c++){
    array_push($options_quatity,$c);
}
$vardefs_sanden_supply_array = array(
    "sanden_fqv_315" => array(
        "name" => "GAUS-315FQV",
        "display_label" => "315FQV",
        "data_id" => '40d20616-6007-44c4-1e9b-5ca447459af6',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "sanden_fqs_315" => array(
        "name" => "GAUS-315FQS",
        "display_label" => "315FQS",
        "data_id" => 'def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "sanden_fqs_300" => array(
        "name" => "GAUS-300FQS",
        "display_label" => "300FQS",
        "data_id" => '335cc359-a2e9-a2a0-3b94-5cb015b32f1b',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "sanden_fqs_250" => array(
        "name" => "GAUS-250FQS",
        "display_label" => "250FQS",
        "data_id" => '67605168-6b72-5504-282c-5cc8e1492ec9',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "sanden_fqs_160" => array(
        "name" => "GAUS-160FQS",
        "display_label" => "160FQS",
        "data_id" => '7add0a17-c12e-7b70-ccb1-5d5a5db14d37',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "QIK15_HPUMP" => array(
        "name" => "QIK15-HPUMP",
        "display_label" => "QIK15",
        "data_id" => '86f3b061-f33a-a9ec-05c4-56963e142784',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "QIK20_HPUMP" => array(
        "name" => "QIK20-HPUMP",
        "display_label" => "QIK20",
        "data_id" => 'a5aa017e-724b-a7a9-70ab-5d5dfc0fe7e5',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),

);
$vardefs_daikin_supply_array = array(
    "US7_25" => array(
        "name" => "FTXZ25N",
        "display_label" => "US7 2.5kW",
        "data_id" => '3518d3a1-7c11-77c5-b9db-5694fed992e6',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "US7_35" => array(
        "name" => "FTXZ35N",
        "display_label" => "US7 3.5kW",
        "data_id" => '571aa1b6-9abe-80ec-5cdd-56b4536a29d0',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "US7_50" => array(
        "name" => "FTXZ50N",
        "display_label" => "US7 5kW",
        "data_id" => 'ef81036f-9889-234d-02e5-57b2c0c71e79',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_20" => array(
        "name" => "FTXM20U",
        "display_label" => "Alira 2kW",
        "data_id" => 'a80988f7-9871-05d8-3150-5def2f744305',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_25" => array(
        "name" => "FTXM25U",
        "display_label" => "Alira 2.5kW",
        "data_id" => '7a163038-6178-7479-65d2-5def2ea594ff',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_35" => array(
        "name" => "FTXM35U",
        "display_label" => "Alira 3.5kW",
        "data_id" => 'c3f9a48a-03d1-8dbf-5f83-5def2f9ed532',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_46" => array(
        "name" => "FTXM46U",
        "display_label" => "Alira 4.6kW",
        "data_id" => '83e632ff-1a24-2710-3f18-5def30c7192b',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_50" => array(
        "name" => "FTXM50U",
        "display_label" => "Alira 5kW",
        "data_id" => 'ae050682-3f62-6925-d056-5def30adfe5e',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_60" => array(
        "name" => "FTXM60U",
        "display_label" => "Alira 6kW",
        "data_id" => '947e6c72-cfa0-7a95-dc50-5def31501045',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),
    "Alira_71" => array(
        "name" => "FTXM71U",
        "display_label" => "Alira 7.1kW",
        "data_id" => '4f552ea4-da55-cac2-1069-5def32ef431d',
        "type" => "select",
        "list_array" => json_encode($options_quatity),
        "parent" => "",
    ),

);
$vardefs_SolarPV_Bos_supply_array = array(
    "standard_solar_PV_install" => array(
        "name" => "Standard Solar PV Install",
        "display_label" => "Standard Solar PV Install",
        "type" => "checkbox",
        "parent" => "",
    ),
    "deg_tilts" => array(
        "name" => "30-60 Deg Tilts",
        "display_label" => "30-60 Deg Tilts",
        'default_val' => 33,
        'min' => 30,
        'max' => 60,
        "type" => "number",
        "parent" => "",
    ),
);
$vardefs_daikin_installer_array = array(
    "STANDARD_AC_INSTALL" => array(
        "name" => "STANDARD_AC_INSTALL",
        "display_label" => "Daikin Standard Install",
        "data_id" => '58d946f3-36c2-1b70-d9e2-56951ddcb660',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DI_CondensatePump" => array(
        "name" => "DI_CondensatePump",
        "display_label" => "Daikin Install - Condensate Pump",
        "data_id" => 'f2de47c3-0a7a-f673-7aec-5fa0dc8763c2',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_DEDIC_CIRC" => array(
        "name" => "DAIKIN_INSTALL_DEDIC_CIRC",
        "display_label" => "Daikin Install - Dedicated Circuit",
        "data_id" => '36b78f65-cc6e-7bf3-7fdd-5e17f634d05e',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_DBL_BRICK" => array(
        "name" => "DAIKIN_INSTALL_DBL_BRICK",
        "display_label" => "Daikin Install - Double Brick",
        "data_id" => '2db46d89-7efb-286f-1a9c-5e18ddc37398',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_DOUBLE_S" => array(
        "name" => "DAIKIN_INSTALL_DOUBLE_S",
        "display_label" => "Daikin Install - Double Storey",
        "data_id" => 'c2d64605-5e98-2ef9-b0a1-5df82cba5947',
        "type" => "checkbox",
        "parent" => "",
    ),
    "Daikin_INSTALL_RCD_UPGRAD" => array(
        "name" => "Daikin_INSTALL_RCD_UPGRAD",
        "display_label" => "Daikin Install - Electrical RCD Upgrade",
        "data_id" => 'c86ba40b-df6f-3abe-c95f-5e17f659cfba',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_HIGH_BRACK" => array(
        "name" => "DAIKIN_INSTALL_HIGH_BRACK",
        "display_label" => "Daikin Install - High Wall Bracket",
        "data_id" => '5e3d6e6c-db87-ecec-651f-5df844bcbd6b',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_INTERNWALL" => array(
        "name" => "DAIKIN_INSTALL_INTERNWALL",
        "display_label" => "Daikin Install - Internal wall",
        "data_id" => '8565e2e4-48b6-43ad-4a89-5f1a78d0abba',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_LONG_PIPE" => array(
        "name" => "DAIKIN_INSTALL_LONG_PIPE",
        "display_label" => "Daikin Install - Long Pipe Run",
        "data_id" => '4140b4e3-48e3-5af0-63f3-5e16a665abdd',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_LOW_BRACK" => array(
        "name" => "DAIKIN_INSTALL_LOW_BRACK",
        "display_label" => "Daikin Install - Low Wall Bracket",
        "data_id" => 'b80508c1-f98c-d837-618a-5df82d07eb6b',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_ROOFCAVITY" => array(
        "name" => "DAIKIN_INSTALL_ROOFCAVITY",
        "display_label" => "Daikin Install - Roof Cavity Run",
        "data_id" => '76e97e95-578f-b7e4-223e-5f1a78fa2a73',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DI-Roof" => array(
        "name" => "DI-Roof",
        "display_label" => "Daikin Install - Rooftop Install",
        "data_id" => '8db2e562-ef44-9ef1-112f-5e461ee8acc7',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_SUB_FLOOR" => array(
        "name" => "DAIKIN_INSTALL_SUB_FLOOR",
        "display_label" => "Daikin Install - Sub floor installation",
        "data_id" => '554e5ae7-1033-1d71-9d8a-5e54d090d7f1',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DAIKIN_INSTALL_WALL_CUT" => array(
        "name" => "DAIKIN_INSTALL_WALL_CUT",
        "display_label" => "Daikin Install - Wall Plaster Cut",
        "data_id" => 'b8e7f7fd-f695-7fc1-73bd-5e460fca371b',
        "type" => "checkbox",
        "parent" => "",
    ),
    "DIFFICUL_INSTALL" => array(
        "name" => "DIFFICUL_INSTALL",
        "display_label" => "Daikin Install Extra",
        "data_id" => 'e3124bc6-0cd9-88ec-9da6-56951c2dafbb',
        "type" => "checkbox",
        "parent" => "",
    ),
    "Daikin_Wifi_Install" => array(
        "name" => "Daikin_Wifi_Install",
        "display_label" => "Daikin Wifi Install",
        "data_id" => '28259986-0833-e30b-9cb3-57b3e26cd2fb',
        "type" => "checkbox",
        "parent" => "",
    ),

);