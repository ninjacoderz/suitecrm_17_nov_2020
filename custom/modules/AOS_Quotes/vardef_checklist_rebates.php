<?php
global $current_user;
date_default_timezone_set('Australia/Melbourne');
$vardefs_array = array(
    "cl_stcs" => array(
        "name" => "cl_stcs",
        "display_label" => "STCs",
        "type" => "checkbox",
        // "list_array" => array(
        // ) ,
        "parent" => "",
    ),
    "cl_veecs" => array(
        "name" => "cl_veecs",
        "display_label" => "VEECs",
        "type" => "checkbox",
        "parent" => "",
    ),
    "cl_sa_reps_cl1_no_gas_cl2" => array(
        "name" => "cl_sa_reps_cl1_no_gas_cl2",
        "display_label" => "SA REPS - Class 1 Established and NOT Connected to Reticulated Gas or Class 2",
        "type" => "checkbox",
        "parent" => "",
    ) ,
    "cl_sa_reps_cl1_reti_gas_conn" => array(
        "name" => "cl_sa_reps_cl1_reti_gas_conn",
        "display_label" => "SA REPS - Class 1 Established and Connected to Reticulated Gas",
        "type" => "checkbox",
        "parent" => "",
    ) ,
    "cl_sl_hotwater_rebate" => array(
        "name" => "cl_sl_hotwater_rebate",
        "display_label" => "Solar VIC Solar Hot Water Rebate",
        "type" => "checkbox",
        "parent" => "",
    ) ,
    "cl_sl_pv_rebate" => array(
        "name" => "cl_sl_pv_rebate",
        "display_label" => "Solar VIC Solar PV Rebate",
        "type" => "checkbox",
        "parent" => "",
    ) ,
    "cl_sl_battery_store_rebate" => array(
        "name" => "cl_sl_battery_store_rebate",
        "display_label" => "Solar VIC Battery Storage Rebate",
        "type" => "checkbox",
        "parent" => "",
    ) ,

);