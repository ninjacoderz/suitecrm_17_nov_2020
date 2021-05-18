<?php
global $current_user;
 date_default_timezone_set('Australia/Melbourne');
$vardefs_array = array(
    "quote_main_tank_water" => array(
        "name" => "quote_main_tank_water",
        "display_label" => "On Mains Water or Tank Water",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Mains Water" =>"Mains Water",
            "Tank Water" => "Tank Water",
            "Mains / Tank" => "Mains / Tank"
        ) ,
        "parent" => "",
        "next_step" => "quote_number_sanden",
        "next_step_backup" => ""
    ),
    "quote_number_sanden" => array(
        "name" => "quote_number_sanden",
        "display_label" => "Number Sanden's",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "1" => "1",
            "2" => "2"
        ) ,
        "parent" => "",
        "next_step" => "quote_tank_size",
        "next_step_backup" => ""
    ),
    "quote_tank_size" => array(
        "name" => "quote_tank_size",
        "display_label" => "Tank Size",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Sanden 315FQS" => "Sanden 315FQS",
            "Sanden 300FQS" => "Sanden 300FQS",
            "Sanden 250FQS" => "Sanden 250FQS",
            "Sanden 160FQS" => "Sanden 160FQS",
            "Sanden 315FQV" => "Sanden 315FQV",
        ) ,
        "parent" => "",
        "next_step" => "quote_plumbing_installation_by_pure",
        "next_step_backup" => ""
    ),
    "quote_plumbing_installation_by_pure" => array(
        "name" => "quote_plumbing_installation_by_pure",
        "display_label" => "Plumbing Installation by PureElectric team",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes - Plumbing" => "Yes",
            "No - Plumbing" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_quick_connection_kit",
        "next_step_backup" => ""
    ),
    "quote_quick_connection_kit" => array(
        "name" => "quote_quick_connection_kit",
        "display_label" => "Plumbing Quick Connection Kit",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "15mm Quick Connection Kit (QIK15)" => "15mm Quick Connection Kit (QIK15)",
            "20mm Quick Connection Kit (QIK25)" => "20mm Quick Connection Kit (QIK25)",
            "No Quick Connection Kit" => "No Quick Connection Kit"
        ) ,
        "parent" => "",
        "next_step" => "quote_electrical_installation_by_pure",
        "next_step_backup" => ""
    ),
    "quote_electrical_installation_by_pure" => array(
        "name" => "quote_electrical_installation_by_pure",
        "display_label" => "Electrical Installation by PureElectric team",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes - Electrical" => "Yes",
            "No - Electrical" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_free_methven",
        "next_step_backup" => ""
    ),
    "quote_free_methven" => array(
        "name" => "quote_free_methven",
        "display_label" => "Free Premium Methven Kiri Satinjet Low Flow (<5L/min) showerhead valued at $146 delivered?",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes - Methven" => "Yes",
            "No - Methven" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_provide_stcs",
        "next_step_backup" => ""
    ),
    "quote_provide_stcs" => array(
        "name" => "quote_provide_stcs",
        "display_label" => "Would you like us to provide STCs as an upfront discount on your quote",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes - Provide" => "Yes",
            "No - Provide" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_pickup_site_delivery",
        "next_step_backup" => ""
    ),
    "quote_pickup_site_delivery" => array(
        "name" => "quote_pickup_site_delivery",
        "display_label" => "Pick up or Site Delivery",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Pick Up" => "Pick Up",
            "Site Delivery" => "Site Delivery",
        ) ,
        "parent" => "",
        "next_step" => "quote_choice_type_install",
        "next_step_backup" => ""
    ),
    "quote_choice_type_install" => array(
        "name" => "quote_choice_type_install",
        "display_label" => "Replace an existing Hot Water System (HWS) or do you have no HWS (e.g. new build, renovation)",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Replace Hot Water System" => "Replace Hot Water System",
            "New Build/Renovation" => "New Build/Renovation",
        ) ,
        "parent" => "",
        "next_step" => "quote_replacement_urgent",
        "next_step_backup" => ""
    ),
    "quote_replacement_urgent" => array(
        "name" => "quote_replacement_urgent",
        "display_label" => "Is Your Replacement Urgent",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes - Replacement" => "Yes",
            "No - Replacement" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_bca_building",
        "next_step_backup" => ""
    ),
    "quote_bca_building" => array(
        "name" => "quote_bca_building",
        "display_label" => "BCA Building Class Type?",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Class 1" => "Class 1",
            "Class 2" => "Class 2",
        ) ,
        "parent" => "",
        "next_step" => "quote_choice_type_product",
        "next_step_backup" => ""
    ),
    "quote_choice_type_product" => array(
        "name" => "quote_choice_type_product",
        "display_label" => "Which one are you replacing",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Gas" => "Gas",
            "Electric" => "Electric",
            "Solar" => "Solar",
            "Wood" => "Wood",
            "LPG" => "LPG",
            "Heatpump" => "Heatpump",
        ) ,
        "parent" => "",
        "next_step" => "quote_product_choice_type_gas",
        "next_step_backup" => ""
    ),
    "quote_product_choice_type_gas" => array(
        "name" => "quote_product_choice_type_gas",
        "display_label" => "Gas type",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Gas Instant" => "Gas Instant",
            "Gas Storage" => "Gas Storage",
        ) ,
        "parent" => "",
        "next_step" => "quote_gas_connection",
        "next_step_backup" => ""
    ),
    "quote_gas_connection" => array(
        "name" => "quote_gas_connection",
        "display_label" => "Gas instant electrical connection",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Plug in to powerpoint" => "Plug in to powerpoint",
            "No electrical connection" => "No electrical connection",
        ) ,
        "parent" => "",
        "next_step" => "quote_product_choice_type_electric",
        "next_step_backup" => ""
    ),
    "quote_product_choice_type_electric" => array(
        "name" => "quote_product_choice_type_electric",
        "display_label" => "Electric type",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Electric Storage" => "Electric Storage",
            "Gravity Feed" => "Gravity Feed",
        ) ,
        "parent" => "",
        "next_step" => "quote_electric_storage_located",
        "next_step_backup" => ""
    ),
    "quote_electric_storage_located" => array(
        "name" => "quote_electric_storage_located",
        "display_label" => "Where is your electric storage located",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Outside" => "Outside",
            "Inside" => "Inside",
        ) ,
        "parent" => "",
        "next_step" => "quote_about_outside",
        "next_step_backup" => ""
    ),
    "quote_about_outside" => array(
        "name" => "quote_about_outside",
        "display_label" => "About outside",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "On ground adjacent external wall" => "On ground adjacent external wall",
            "Under the house" => "Under the house",
            "Balcony" => "Balcony",
            "Rooftop" => "Rooftop",
            "On wall bracket" => "On wall bracket",
        ) ,
        "parent" => "",
        "next_step" => "quote_about_inside",
        "next_step_backup" => ""
    ),
    "quote_about_inside" => array(
        "name" => "quote_about_inside",
        "display_label" => "About inside",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Inside Adjacent Perimeter Wall" => "Inside Adjacent Perimeter Wall",
            "Inside Laundry" => "Inside Laundry",
            "Inside Cupboard" => "Inside Cupboard",
        ) ,
        "parent" => "",
        "next_step" => "quote_product_choice_type_solar",
        "next_step_backup" => ""
    ),
    "quote_product_choice_type_solar" => array(
        "name" => "quote_product_choice_type_solar",
        "display_label" => "Solar type",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Electric Storage" => "Electric Storage",
            "Ground Tank" => "Ground Tank",
        ) ,
        "parent" => "",
        "next_step" => "quote_solar_boosted",
        "next_step_backup" => ""
    ),
    "quote_solar_boosted" => array(
        "name" => "quote_solar_boosted",
        "display_label" => "How is it boosted",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Electric Boost" => "Electric Boost",
            "Gas Boost" => "Gas Boost",
            "Ground Instant Gas Boost" => "Ground Instant Gas Boost",
            "Wood Boost" => "Wood Boost",
        ) ,
        "parent" => "",
        "next_step" => "quote_product_choice_type_wood",
        "next_step_backup" => ""
    ),
    "quote_product_choice_type_wood" => array(
        "name" => "quote_product_choice_type_wood",
        "display_label" => "Wood type",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Wetback Stove" => "Wetback Stove",
            "Mains Pressure System" => "Mains Pressure System",
            "Low Pressure System" => "Low Pressure System",
        ) ,
        "parent" => "",
        "next_step" => "quote_product_choice_type_lpg",
        "next_step_backup" => ""
    ),
    "quote_product_choice_type_lpg" => array(
        "name" => "quote_product_choice_type_lpg",
        "display_label" => "LPG type",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Wetback Stove" => "Wetback Stove",
            "Mains Pressure System" => "Mains Pressure System",
            "Low Pressure System" => "Low Pressure System",
        ) ,
        "parent" => "",
        "next_step" => "quote_new_place_choice",
        "next_step_backup" => ""
    ),
    "quote_new_place_choice" => array(
        "name" => "quote_new_place_choice",
        "display_label" => "New Sanden HWS Install Location",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Same as existing HWS" => "Same as existing HWS",
            "New HWS relocation" => "New HWS relocation",
        ) ,
        "parent" => "",
        "next_step" => "quote_existing_install_location",
        "next_step_backup" => ""
    ),
    "quote_existing_install_location" => array(
        "name" => "quote_existing_install_location",
        "display_label" => "Existing system install location",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "External wall house" => "External wall house",
            "Inside perimeter wall" => "Inside perimeter wall",
            "Laundry" => "Laundry",
            "Ceiling Cavity" => "Ceiling Cavity",
            "Internal cupboard" => "Internal cupboard",
            "Garage" => "Garage",
            "Shed" => "Shed",
            "Sub floor open air" => "Sub floor open air",
            "Balcony" => "Balcony",
            "Basement enclosed" => "Basement enclosed",
            "Utilities Room" => "Utilities Room",
            "Rooftop" => "Rooftop"
        ) ,
        "parent" => "",
        "next_step" => "quote_existing_install_location",
        "next_step_backup" => ""
    ),
    "quote_install_location_access" => array(
        "name" => "quote_install_location_access",
        "display_label" => "Install Location Access",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Easy Access" => "Easy Access",
            "Unpaved Path" => "Unpaved Path",
            "Across grassed are" => "Across grassed are",
            "Through Building" => "Through Building",
        ) ,
        "parent" => "",
        "next_step" => "quote_stair_access",
        "next_step_backup" => ""
    ),
    "quote_stair_access" => array(
        "name" => "quote_stair_access",
        "display_label" => "Stairs",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "No Stairs" => "No Stairs",
            "Few Steps - Trolley Ok" => "Few Steps - Trolley Ok",
            "Stairs - Trolley Ok" => "Stairs - Trolley Ok",
            "Stairs No Trolley Access" => "Stairs No Trolley Access",
            "Few Steps No Trolley Access" => "Few Steps No Trolley Access",
            "Internal Stairs -No Trolley" => "Internal Stairs -No Trolley",
        ) ,
        "parent" => "",
        "next_step" => "quote_alectrical_already",
        "next_step_backup" => ""
    ),
    "quote_alectrical_already" => array(
        "name" => "quote_alectrical_already",
        "display_label" => "Electrical Already RCD Protected",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Alectrical Already" => "Yes",
            "No Alectrical Already" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_hot_cold_connections",
        "next_step_backup" => ""
    ),
    "quote_hot_cold_connections" => array(
        "name" => "quote_hot_cold_connections",
        "display_label" => "Hot and Cold Connections presented, externally located, single storey, paved area",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Hot Connection" => "Yes",
            "No Hot Connection" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_located_within",
        "next_step_backup" => ""
    ),
    "quote_located_within" => array(
        "name" => "quote_located_within",
        "display_label" => "New Sanden tank to be located within 2m of hot and cold connections",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Located Within" => "Yes",
            "No Located Within" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_additional_untempered",
        "next_step_backup" => ""
    ),
    "quote_additional_untempered" => array(
        "name" => "quote_additional_untempered",
        "display_label" => "Confirm you don't have an untempered hot water line (> 50 deg) - majority of houses answer YES to this question",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Additional Untempered" => "Yes",
            "No Additional Untempered" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_concrete_slab",
        "next_step_backup" => ""
    ),
    "quote_concrete_slab" => array(
        "name" => "quote_concrete_slab",
        "display_label" => "Would you like us to bring a 600x600 concrete slab for the tank",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Concrete Slab" => "Yes Concrete Slab",
            "No Concrete Slab" => "No Concrete Slab",
        ) ,
        "parent" => "",
        "next_step" => "quote_concrete_pavers",
        "next_step_backup" => ""
    ),
    "quote_concrete_pavers" => array(
        "name" => "quote_concrete_pavers",
        "display_label" => "Would you like us to bring 2x 450x450 concrete pavers for the Heat Pump",
        "type" => "select",
        "list_array" => array(
            "" => "",
            "Yes Concrete Pavers" => "Yes",
            "No Concrete Pavers" => "No",
        ) ,
        "parent" => "",
        "next_step" => "quote_hot_water_rebate",
        "next_step_backup" => ""
    ),
    "quote_hot_water_rebate" => array(
        "name" => "quote_hot_water_rebate",
        "display_label" => "Solar Vic Solar Hot Water Rebate - Do you qualify?",
        "type" => "select",
        "list_array" => array(
            "",
            "Yes Hot Rebate" => "Yes",
            "No Hot Rebate" => "No",
        ) ,
        "parent" => "",
    )
);