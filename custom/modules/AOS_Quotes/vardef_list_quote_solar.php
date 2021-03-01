<?php
global $current_user;
date_default_timezone_set("Australia/Melbourne");
/** 
 * "name" => array(
 *    "name"          => "",
 *    "display_label" => ""
 *    "type"          => "select",
 *    "list_array"    => array(),
 *     "parent"       => "",
 *   );
 */
$vardefs_array = array(
    "electricity_distributor" => array(
        "name" => "electricity_distributor",
        "display_label" => "Who is your electricity distributor?",
        "type" => "select",
        "list_array" => array(
            "",
            "Ausnet Services",
            "Citipower",
            "Jemena",
            "Powercor",
            "United Energy",
            "EvoEnergy",
            "SA Power Networks",
            "Western Power",
            "Energex",
            "Ergon",
            "Ausgrid",
            "Endeavour Energy",
            "Essential Energy",
        ) ,
        "parent" => "",
    ) ,
    "first_solar_pv_system" => array(
        "name" => "first_solar_pv_system",
        "display_label" => "First solar PV system?",
        "type" => "select",
        "list_array" => array(
            "",
            "Yes",
            "No"
        ) ,
        "parent" => "",
    ) ,
    "roof_type" => array(
        "name" => "roof_type",
        "display_label" => "Roof type?",
        "type" => "select",
        "list_array" => array(
            "",
            "TIN/COLORBOND",
            "CONCRETE TILE",
            "TERRACOTTA",
            "KLIPLOC",
            "SLATE ROOF",
            "ASBESTOS ROOF",
            "UNSURE",
        ) ,
        "parent" => "",
    ) ,
    "roof_pitch" => array(
        "name" => "roof_pitch",
        "display_label" => "Roof Pitch?",
        "type" => "select",
        "list_array" => array(
            "",
            "0 - 25 Degrees",
            "25 - 30 Degrees",
            "30+ Degrees",
            "Unsure",
        ) ,
        "parent" => "",
    ) ,
    "storeys" => array(
        "name" => "storeys",
        "display_label" => "How many storeys?",
        "type" => "select",
        "list_array" => array(
            "",
            "Single Storey",
            "Double Storey",
            "3+ Stories"
        ) ,
        "parent" => "",
    ) ,
    "phases" => array(
        "name" => "phases",
        "display_label" => "How many phases?",
        "type" => "select",
        "list_array" => array(
            "",
            "Single Phase",
            "Two Phases",
            "Three Phases",
            "Unsure"
        ) ,
        "parent" => "",
    ) ,
    "meter_type" => array(
        "name" => "meter_type",
        "display_label" => "Meter Type - Smart Meter or Spinning Disk?",
        "type" => "select",
        "list_array" => array(
            "",
            "Smart Meter",
            "Spinning Disk",
        ) ,
        "parent" => "",
    ) ,
    "main_switch" => array(
        "name" => "main_switch",
        "display_label" => "Is the Main Switch \"63A\"?	",
        "type" => "select",
        "list_array" => array(
            "",
            "Yes",
            "No",
            "Unsure",
        ) ,
        "parent" => "",
    ) ,
    "distance_from_inverter_to_main_switchboard" => array(
        "name" => "distance_from_inverter_to_main_switchboard",
        "display_label" => "Distance from Inverter to the MAIN SWITCHBOARD",
        "type" => "number",
        "list_array" => array() ,
        "parent" => "",
        "step" => "0.1",
    ) ,
    "external_or_internal_switchboard" => array(
        "name" => "external_or_internal_switchboard",
        "display_label" => "External or Internal Switchboard?",
        "type" => "select",
        "list_array" => array(
            "",
            "External",
            "Internal",
        ) ,
        "parent" => "",
    ) ,
);

