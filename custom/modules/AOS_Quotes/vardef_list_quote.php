<?php
global $current_user;
 date_default_timezone_set('Australia/Melbourne');
$vardefs_array = array(
    "quote_main_tank_water" => [
        "On Mains Water or Tank Water",
        "quote_main_tank_water",
        "list_array" => [
            "",
            "Mains Water",
            "Tank Water",
            "Mains / Tank"
        ],
    ],
    "quote_number_sanden" => [
        "Number Sanden's",
        "quote_number_sanden",
        "list_array" => [
            "",
            "1",
            "2"
        ],
    ],
    "quote_tank_size" => [
        "Tank Size",
        "quote_tank_size",
        "list_array" => [
            "",
            "Sanden 315FQS",
            "Sanden 300FQS",
            "Sanden 250FQS",
            "Sanden 160FQS",
            "Sanden 315FQV",
        ],
    ],
    "quote_plumbing_installation_by_pure" => [
        "Plumbing Installation by PureElectric team",
        "quote_plumbing_installation_by_pure",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_electrical_installation_by_pure" => [
        "Electrical Installation by PureElectric team",
        "quote_electrical_installation_by_pure",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_quick_connection_kit" => [
        "Plumbing Quick Connection Kit",
        "quote_quick_connection_kit",
        "list_array" => [
            "",
            "15mm Quick Connection Kit (QIK15)",
            "20mm Quick Connection Kit (QIK25)",
            "No Quick Connection Kit"
        ],
    ],
    "quote_provide_stcs" => [
        "Would you like us to provide STCs as an upfront discount on your quote",
        "quote_provide_stcs",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_pickup_site_delivery" => [
        "Pick up or Site Delivery",
        "quote_pickup_site_delivery",
        "list_array" => [
            "",
            "Pick Up",
            "Site Delivery",
        ],
    ],
    "quote_choice_type_install" => [
        "Replace an existing Hot Water System (HWS) or do you have no HWS (e.g. new build, renovation)",
        "quote_choice_type_install",
        "list_array" => [
            "",
            "Replace Hot Water System",
            "New Build/Renovation",
        ],
    ],
    "quote_replacement_urgent" => [
        "Is Your Replacement Urgent",
        "quote_replacement_urgent",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_choice_type_product" => [
        "Which one are you replacing",
        "quote_choice_type_product",
        "list_array" => [
            "",
            "Gas",
            "Electric",
            "Solar",
            "Wood",
            "LPG",
            "Heatpump",
        ],
    ],
    "quote_product_choice_type_gas" => [
        "Gas type",
        "quote_product_choice_type_gas",
        "list_array" => [
            "",
            "Gas Instant",
            "Gas Storage",
        ],
    ],
    "quote_gas_connection" => [
        "Gas instant electrical connection",
        "quote_gas_connection",
        "list_array" => [
            "",
            "Plug in to powerpoint",
            "No electrical connection",
        ],
    ],
    "quote_product_choice_type_electric" => [
        "Electric type",
        "quote_product_choice_type_electric",
        "list_array" => [
            "",
            "Electric Storage",
            "Gravity Feed",
        ],
    ],
    "quote_electric_storage_located" => [
        "Where is your electric storage located",
        "quote_electric_storage_located",
        "list_array" => [
            "",
            "Outside",
            "Inside",
        ],
    ],
    "quote_about_outside" => [
        "About outside",
        "quote_about_outside",
        "list_array" => [
            "",
            "On ground adjacent external wall",
            "Under the house",
            "Balcony",
            "Rooftop",
            "On wall bracket",
        ],
    ],
    "quote_about_inside" => [
        "About inside",
        "quote_about_inside",
        "list_array" => [
            "",
            "Inside Adjacent Perimeter Wall",
            "Inside Laundry",
            "Inside Cupboard",
        ],
    ],
    "quote_product_choice_type_solar" => [
        "Solar type",
        "quote_product_choice_type_solar",
        "list_array" => [
            "",
            "Electric Storage",
            "Ground Tank",
        ],
    ],
    "quote_solar_boosted" => [
        "How is it boosted",
        "quote_solar_boosted",
        "list_array" => [
            "",
            "Electric Boost",
            "Gas Boost",
            "Ground Instant Gas Boost",
            "Wood Boost",
        ],
    ],
    "quote_product_choice_type_wood" => [
        "Wood type",
        "quote_product_choice_type_wood",
        "list_array" => [
            "",
            "Wetback Stove",
            "Mains Pressure System",
            "Low Pressure System",
        ],
    ],
    "quote_product_choice_type_lpg" => [
        "LPG type",
        "quote_product_choice_type_lpg",
        "list_array" => [
            "",
            "Wetback Stove",
            "Mains Pressure System",
            "Low Pressure System",
        ],
    ],
    "quote_new_place_choice" => [
        "New Sanden HWS Install Location",
        "quote_new_place_choice",
        "list_array" => [
            "",
            "Same as existing HWS",
            "New HWS relocation",
        ],
    ],
    "quote_existing_install_location" => [
        "Existing system install location",
        "quote_existing_install_location",
        "list_array" => [
            "",
            "External wall house",
            "Inside perimeter wall",
            "Laundry",
            "Ceiling Cavity",
            "Internal cupboard",
            "Garage",
            "Shed",
            "Sub floor open air",
            "Balcony",
            "Basement enclosed",
            "Utilities Room",
            "Rooftop"
        ],
    ],
    "quote_install_location_access" => [
        "Install Location Access",
        "quote_install_location_access",
        "list_array" => [
            "",
            "Easy Access",
            "Unpaved Path",
            "Across grassed are",
            "Through Building",
        ],
    ],
    "quote_stair_access" => [
        "Stairs",
        "quote_stair_access",
        "list_array" => [
            "",
            "No Stairs",
            "Few Steps - Trolley Ok",
            "Stairs - Trolley Ok",
            "Stairs No Trolley Access",
            "Few Steps No Trolley Access",
            "Internal Stairs -No Trolley",
        ],
    ],
    "quote_alectrical_already" => [
        "Electrical Already RCD Protected",
        "quote_alectrical_already",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_hot_cold_connections" => [
        "Hot and Cold Connections presented, externally located, single storey, paved area",
        "quote_hot_cold_connections",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_located_within" => [
        "New Sanden tank to be located within 2m of hot and cold connections",
        "quote_located_within",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_additional_untempered" => [
        "Confirm you don't have an untempered hot water line (> 50 deg) - majority of houses answer YES to this question",
        "quote_additional_untempered",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_concrete_slab" => [
        "Would you like us to bring a 600x600 concrete slab for the tank",
        "quote_concrete_slab",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_concrete_pavers" => [
        "Would you like us to bring 2x 450x450 concrete pavers for the Heat Pump",
        "quote_concrete_pavers",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ],
    "quote_hot_water_rebate" => [
        "Solar Vic Solar Hot Water Rebate - Do you qualify?",
        "quote_hot_water_rebate",
        "list_array" => [
            "",
            "Yes",
            "No",
        ],
    ]
);