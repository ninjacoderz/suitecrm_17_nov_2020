<?php
$products = json_decode(htmlspecialchars_decode($_REQUEST['products']), true);
$daikinOption = $_REQUEST['install_option'];
$wifi = $_REQUEST['wifi'];
$pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
// echo $products;
// echo $_REQUEST;
// $number_of_daikin = $_REQUEST['number_of_daikin'];
// $type_product = $_REQUEST['type_product'];
// $sub_product = $_REQUEST['sub_product'];
// $install_method = $_REQUEST['install_method'];
// $pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
// $postcode = $_REQUEST['postcode'];
// $number_of_daikin = 2;
// $type_product = 'Daikin US7';
// $sub_product = 'US7 2.5kW';
// $install_method = $_REQUEST['install_method'];
// $postcode = $_REQUEST['postcode'];

// SUBPRODUCT
$partNumberSubProduct = '';

// Part Number
$part_numbers = [];
$quantity_install_method = 0;
$quantity_none_install_method = 0;
$quantity_install_wifi = 0;
$quantity_type_brick = 0;
$quantity_heightAboveGround = 0;
$quantity_outdoorUnitLocation_low = 0;
$quantity_outdoorUnitLocation_high = 0;
$quantity_us7_25kw = 0;
$quantity_us7_35kw = 0;
$quantity_us7_50kw = 0;
$quantity_us7_25kw_install = 0;
$quantity_us7_35kw_install = 0;
$quantity_us7_35kw_install = 0;

$quantity_nexura_25kw = 0;
$quantity_nexura_35kw = 0;
$quantity_nexura_48kw = 0;
$quantity_nexura_25kw_install = 0;
$quantity_nexura_35kw_install = 0;
$quantity_nexura_48kw_install = 0;

$quantity_cora_20kw = 0;
$quantity_cora_25kw = 0;
$quantity_cora_35kw = 0;
$quantity_cora_20kw_install = 0;
$quantity_cora_25kw_install = 0;
$quantity_cora_35kw_install = 0;
$quantity_cora_50kw_install = 0;

$quantity_alira_20kw = 0;
$quantity_alira_25kw = 0;
$quantity_alira_35kw = 0;
$quantity_alira_46kw = 0;
$quantity_alira_50kw = 0;
$quantity_alira_60kw = 0;
$quantity_alira_71kw = 0;
$quantity_alira_20kw_install = 0;
$quantity_alira_25kw_install = 0;
$quantity_alira_35kw_install = 0;
$quantity_alira_46kw_install = 0;
$quantity_alira_50kw_install = 0;
$quantity_alira_60kw_install = 0;
$quantity_alira_71kw_install = 0;
$nameDAIKINUS7 = '';
$quantityDAIKINUS7 = 0;
$nameDAIKINNEXURA = '';
$quantityDAIKINNEXURA = 0;
$nameDAIKINALIRA = 'DAIKIN ALIRA XXX';
$quantityDAIKINALIRA = 0;

$totalDAIKINExtra = 0;

////// List Daikin Extra //////
$extraDedicatedCircuit      = [];
$extraDoubleBrick	        = [];
$extraDoubleStorey	        = [];
$extraElectricalRCDUpgrade	= [];
$extraHighWallBracket	    = [];
$extraInternalWall	        = [];
$extraLongPipeRun		    = [];
$extraLowWallBracket		= [];
$extraRoofCavityRun		    = [];
$extraRooftopInstall		= [];
$extraSubFloorInstallation	= [];
$extraWallPlasterCut		= [];
$extraExtra			        = [];
$extraDICondensatePump		= [];
$extraTravel		        = [];
$quantity_daikin            = 0;		

foreach($products as $product) {
    $quantity_daikin += $product['quantity'];
    if (strpos($product['typeOfProduct'], 'US7') !== false) {
        $quantityDAIKINUS7 += $product['quantity'];
        $nameDAIKINUS7 = "DAIKIN US7";
    } else if(strpos($product['typeOfProduct'], 'Nexura') !== false) {
        $quantityDAIKINNEXURA += $product['quantity'];
        $nameDAIKINNEXURA = "DAIKIN NEXURA";
    } else if(strpos($product['typeOfProduct'], 'Alira') !== false) {
        $quantityDAIKINALIRA += $product['quantity'];
        $nameDAIKINALIRA = "DAIKIN ALIRA";
    }
    if($product['typeOfProduct'] == 'US7 2.5kW') {
        array_push($part_numbers, 'FTXZ25N');
        $quantity_us7_25kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'US7 3.5kW') {
        array_push($part_numbers, 'FTXZ35N');
        $quantity_us7_35kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'US7 5.0kW') {
        array_push($part_numbers, 'FTXZ50N'); 
        $quantity_us7_50kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 2.5kW') {
        array_push($part_numbers, 'FVXG25K2V1B');
        $quantity_nexura_25kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 3.5kW') {
        array_push($part_numbers, 'FVXG35K2V1B'); 
        $quantity_nexura_35kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 4.8kW') {
        array_push($part_numbers, 'FVXG50K2V1B');
        $quantity_nexura_48kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 2.0kW') {
        array_push($part_numbers, 'FTXM20Q'); 
        $quantity_cora_20kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 2.5kW') {
        array_push($part_numbers, 'FTXM25Q');
        $quantity_cora_25kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 3.5kW') {
        array_push($part_numbers, 'FTXM35Q'); 
        $quantity_cora_35kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 5.0kW') {
        array_push($part_numbers, 'FTXM50Q'); 
        $quantity_cora_50kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 2.0kW') {
        array_push($part_numbers, 'FTXM20U');
        $quantity_alira_20kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 2.5kW') {
        array_push($part_numbers, 'FTXM25U'); 
        $quantity_alira_25kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 3.5kW') {
        array_push($part_numbers, 'FTXM35U');
        $quantity_alira_35kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 4.6kW') {
        array_push($part_numbers, 'FTXM46U'); 
        $quantity_alira_46kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 5.0kW') {
        array_push($part_numbers, 'FTXM50U');
        $quantity_alira_50kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 6.0kW') {
        array_push($part_numbers, 'FTXM60U'); 
        $quantity_alira_60kw += $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 7.1kW') {
        array_push($part_numbers, 'FTXM71U'); 
        $quantity_alira_71kw += $product['quantity'];
    };
    if($product['choice_installation'] == 'Standard Install' || $product['choice_installation'] == 'Non Standard Install') {
        $quantity_install_method = $quantity_install_method + $product['quantity'];
    };
    if($product['choice_installation'] == 'Non Standard Install') {
        $quantity_none_install_method = $quantity_none_install_method + $product['quantity'];
    };
    if($product['wifi'] == 'Yes') {
        $quantity_install_wifi = $quantity_install_wifi + $product['quantity'];
    };
    if($product['brick_type'] == 'Double Brick') {
        $quantity_type_brick = $quantity_type_brick + $product['quantity'];
    };
    if($product['height_above_ground'] == 'Double Storey') {
        $quantity_heightAboveGround = $quantity_heightAboveGround + $product['quantity'];
    };
    if($product['outdoor_unit_location'] == 'Low Wall Bracket') {
        $quantity_outdoorUnitLocation_low = $quantity_outdoorUnitLocation_low + $product['quantity'];
    } elseif($product['outdoor_unit_location'] == 'High Wall Bracket') {
        $quantity_outdoorUnitLocation_high = $quantity_outdoorUnitLocation_high + $product['quantity'];
    }
    if(count($product['extraDaikin']) > 0) {
        foreach($product['extraDaikin'] as $key => $value) {
            //////
            if($key == "DAIKIN_INSTALL_DEDIC_CIRC_all") {
                if($value >= 0) {
                    if(count($extraDedicatedCircuit) > 0) {
                        $extraDedicatedCircuit[0] = $extraDedicatedCircuit[0] + $value;
                    } else {
                        array_push($extraDedicatedCircuit,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_DEDIC_CIRC_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraDedicatedCircuit) > 0) {
                    $extraDedicatedCircuit[1] = $tmp;
                } else {
                    array_push($extraDedicatedCircuit,$tmp);
                }
            }
            ////
            if($key == "DAIKIN_INSTALL_DBL_BRICK_all") {
                if($value >= 0) {
                    if(count($extraDoubleBrick) > 0) {
                        $extraDoubleBrick[0] = $extraDoubleBrick[0] + $value;
                    } else {
                        array_push($extraDoubleBrick,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_DBL_BRICK_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraDoubleBrick) > 0) {
                    $extraDoubleBrick[1] = $tmp;
                } else {
                    array_push($extraDoubleBrick,$tmp);
                }
            }
            ////
            if($key == "DAIKIN_INSTALL_DOUBLE_S_all") {
                if($value >= 0) {
                    if(count($extraDoubleStorey) > 0) {
                        $extraDoubleStorey[0] = $extraDoubleStorey[0] + $value;
                    } else {
                        array_push($extraDoubleStorey,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_DOUBLE_S_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraDoubleStorey) > 0) {
                    $extraDoubleStorey[1] = $tmp;
                } else {
                    array_push($extraDoubleStorey,$tmp);
                }
            }
            /////
            if($key == "Daikin_INSTALL_RCD_UPGRAD_all") {
                if($value >= 0) {
                    if(count($extraElectricalRCDUpgrade) > 0) {
                        $extraElectricalRCDUpgrade[0] = $extraElectricalRCDUpgrade[0] + $value;
                    } else {
                        array_push($extraElectricalRCDUpgrade,$value);
                    }
                }
            }
            if($key == "itemise_Daikin_INSTALL_RCD_UPGRAD_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraElectricalRCDUpgrade) > 0) {
                    $extraElectricalRCDUpgrade[1] = $tmp;
                } else {
                    array_push($extraElectricalRCDUpgrade,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_HIGH_BRACK_all") {
                if($value >= 0) {
                    if(count($extraHighWallBracket) > 0) {
                        $extraHighWallBracket[0] = $extraHighWallBracket[0] + $value;
                    } else {
                        array_push($extraHighWallBracket,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_HIGH_BRACK_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraHighWallBracket) > 0) {
                    $extraHighWallBracket[1] = $tmp;
                } else {
                    array_push($extraHighWallBracket,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_INTERNWALL_all") {
                if($value >= 0) {
                    if(count($extraInternalWall) > 0) {
                        $extraInternalWall[0] = $extraInternalWall[0] + $value;
                    } else {
                        array_push($extraInternalWall,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_INTERNWALL_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraInternalWall) > 0) {
                    $extraInternalWall[1] = $tmp;
                } else {
                    array_push($extraInternalWall,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_LONG_PIPE_all") {
                if($value >= 0) {
                    if(count($extraLongPipeRun) > 0) {
                        $extraLongPipeRun[0] = $extraLongPipeRun[0] + $value;
                    } else {
                        array_push($extraLongPipeRun,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_LONG_PIPE_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraLongPipeRun) > 0) {
                    $extraLongPipeRun[1] = $tmp;
                } else {
                    array_push($extraLongPipeRun,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_LOW_BRACK_all") {
                if($value >= 0) {
                    if(count($extraLowWallBracket) > 0) {
                        $extraLowWallBracket[0] = $extraLowWallBracket[0] + $value;
                    } else {
                        array_push($extraLowWallBracket,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_LOW_BRACK_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraLowWallBracket) > 0) {
                    $extraLowWallBracket[1] = $tmp;
                } else {
                    array_push($extraLowWallBracket,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_ROOFCAVITY_all") {
                if($value >= 0) {
                    if(count($extraRoofCavityRun) > 0) {
                        $extraRoofCavityRun[0] = $extraRoofCavityRun[0] + $value;
                    } else {
                        array_push($extraRoofCavityRun,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_ROOFCAVITY_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraRoofCavityRun) > 0) {
                    $extraRoofCavityRun[1] = $tmp;
                } else {
                    array_push($extraRoofCavityRun,$tmp);
                }
            }
            /////
            if($key == "DI-Roof_all") {
                if($value >= 0) {
                    if(count($extraRooftopInstall) > 0) {
                        $extraRooftopInstall[0] = $extraRooftopInstall[0] + $value;
                    } else {
                        array_push($extraRooftopInstall,$value);
                    }
                }
            }
            if($key == "itemise_DI-Roof_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraRooftopInstall) > 0) {
                    $extraRooftopInstall[1] = $tmp;
                } else {
                    array_push($extraRooftopInstall,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_SUB_FLOOR_all") {
                if($value >= 0) {
                    if(count($extraSubFloorInstallation) > 0) {
                        $extraSubFloorInstallation[0] = $extraSubFloorInstallation[0] + $value;
                    } else {
                        array_push($extraSubFloorInstallation,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_SUB_FLOOR_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraSubFloorInstallation) > 0) {
                    $extraSubFloorInstallation[1] = $tmp;
                } else {
                    array_push($extraSubFloorInstallation,$tmp);
                }
            }
            /////
            if($key == "DAIKIN_INSTALL_WALL_CUT_all") {
                if($value >= 0) {
                    if(count($extraWallPlasterCut) > 0) {
                        $extraWallPlasterCut[0] = $extraWallPlasterCut[0] + $value;
                    } else {
                        array_push($extraWallPlasterCut,$value);
                    }
                }
            }
            if($key == "itemise_DAIKIN_INSTALL_WALL_CUT_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraWallPlasterCut) > 0) {
                    $extraWallPlasterCut[1] = $tmp;
                } else {
                    array_push($extraWallPlasterCut,$tmp);
                }
            }
            /////
            if($key == "DIFFICUL_INSTALL_all") {
                if($value >= 0) {
                    if(count($extraExtra) > 0) {
                        $extraExtra[0] = $extraExtra[0] + $value;
                    } else {
                        array_push($extraExtra,$value);
                    }
                }
            }
            if($key == "itemise_DIFFICUL_INSTALL_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraExtra) > 0) {
                    $extraExtra[1] = $tmp;
                } else {
                    array_push($extraExtra,$tmp);
                }
            }
            /////
            if($key == "DI_CondensatePump_all") {
                if($value >= 0) {
                    if(count($extraDICondensatePump) > 0) {
                        $extraDICondensatePump[0] = $extraDICondensatePump[0] + $value;
                    } else {
                        array_push($extraDICondensatePump,$value);
                    }
                }
            }
            if($key == "itemise_DI_CondensatePump_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraDICondensatePump) > 0) {
                    $extraDICondensatePump[1] = $tmp;
                } else {
                    array_push($extraDICondensatePump,$tmp);
                }
            }
            if($key == "itemise_Travel_all") {
                if($value == true) {
                    $tmp = 1;
                } else {
                    $tmp = 0;
                }
                if(count($extraTravel) > 0) {
                    $extraTravel[1] = $tmp;
                } else {
                    array_push($extraTravel,$tmp);
                }
            }
        }
    }
    // if($product['wifiInstall'] == 'Yes') {
    //     array_push($part_numbers,'BRP072A42');
    // }
}

/////// Extra Part Number
if(count($extraDedicatedCircuit) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_DEDIC_CIRC');
}
if(count($extraDoubleBrick) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_DBL_BRICK');
}
if(count($extraDoubleStorey) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_DOUBLE_S');
}
if(count($extraElectricalRCDUpgrade) > 0) {
    array_push($part_numbers,'Daikin_INSTALL_RCD_UPGRAD');
}
if(count($extraHighWallBracket) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_HIGH_BRACK');
}
if(count($extraInternalWall) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_INTERNWALL');
}
if(count($extraLongPipeRun) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_LONG_PIPE');
}
if(count($extraLowWallBracket) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_LOW_BRACK');
}
if(count($extraRoofCavityRun) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_ROOFCAVITY');
}
if(count($extraRooftopInstall) > 0) {
    array_push($part_numbers,'DI-Roof');
}
if(count($extraSubFloorInstallation) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_SUB_FLOOR');
}
if(count($extraWallPlasterCut) > 0) {
    array_push($part_numbers,'DAIKIN_INSTALL_WALL_CUT');
}
if(count($extraExtra) > 0) {
    array_push($part_numbers,'DIFFICUL_INSTALL');
}
if(count($extraDICondensatePump) > 0) {
    array_push($part_numbers,'DI_CondensatePump');
}
if(count($extraTravel) > 0) {
    array_push($part_numbers,'Travel');
}
///////

if($quantity_install_wifi > 0) {
    array_push($part_numbers,'BRP072C42');
}
// if($quantity_outdoorUnitLocation_low > 0) {
//     array_push($part_numbers, 'DAIKIN_INSTALL_LOW_BRACK');
// }
// if($quantity_outdoorUnitLocation_high > 0) {
//     array_push($part_numbers, 'DAIKIN_INSTALL_HIGH_BRACK');
// }
// if($quantity_heightAboveGround > 0) {
//     array_push($part_numbers, 'DAIKIN_INSTALL_DOUBLE_S');
// }
if($quantity_type_brick > 0) {
    array_push($part_numbers, 'DBW');
}

if($_POST['installByPure'] == 'Yes') {
    if($quantity_daikin > 0) {
        array_push($part_numbers, 'STANDARD_AC_INSTALL');
    }
    array_push($part_numbers,'DSI');
} else {
    array_push($part_numbers,'DS');
}
// if($totalDAIKINExtra > 0) {
//     array_push($part_numbers,'DIFFICUL_INSTALL');
// }


array_push($part_numbers, 'DAIKIN_MEL_METRO_DELIVERY');

$part_numbers_display_first = ['def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'];
$part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19'];
$array_id_part_numbers_not_use = ['1455e1e9-38a6-22e2-f898-57e8966e5256', '4e6ea564-761a-7482-76d0-582c0ca119e0'];

// Initialize quote
$quote = new AOS_Quotes();

// Create New Quote
$quote->name = 'GUEST '.(($quantityDAIKINUS7 > 0) ? 'DAIKIN US7 ('.$quantityDAIKINUS7.'X) ' : '').(($quantityDAIKINNEXURA > 0) ? ' DAIKIN NEXURA ('.$quantityDAIKINNEXURA.'X) ' : '').(($quantityDAIKINALIRA > 0) ? ' DAIKIN ALIRA ('.$quantityDAIKINALIRA.'X)' : '');
$quote->pre_install_photos_c = $_REQUEST['pre_install_photos_c'];

// date_default_timezone_set("Australia/Melbourne");
date_default_timezone_set('UTC');
$dateQuote = new DateTime();
$quote->quote_date_c = date('Y-m-d H:i:s', time());

if($type_product == 'Daikin US7') {
    $quote->quote_type_c = 'quote_type_daikin';
} else if($type_product == 'Daikin Nexura') {
    $quote->quote_type_c = 'quote_type_nexura';
} else {
    $quote->quote_type_c = 'quote_type_cora';
}

if($product['typeOfProduct'] == 'US7 2.5kW' || $product['typeOfProduct'] == 'US7 3.5kW' || $product['typeOfProduct'] == 'US7 5.0kW') {
    $quote->quote_type_c = 'quote_type_daikin';
} else if($product['typeOfProduct'] == 'Nexura 2.5kW' || $product['typeOfProduct'] == 'Nexura 3.5kW' || $product['typeOfProduct'] == 'Nexura 4.8kW') {
    $quote->quote_type_c = 'quote_type_nexura';
} else if($product['typeOfProduct'] == 'Alira 2.0kW' || $product['typeOfProduct'] == 'Alira 2.5kW' || $product['typeOfProduct'] == 'Alira 3.5kW' || $product['typeOfProduct'] == 'Alira 4.6kW' || $product['typeOfProduct'] == 'Alira 5.0kW' || $product['typeOfProduct'] == 'Alira 6.0kW' || $product['typeOfProduct'] == 'Alira 7.1kW') {
    $quote->quote_type_c = 'quote_type_alira';
} 
$quote->stage = 'Guest';
$quote->save();

//delele all line items
$db = DBManagerFactory::getInstance();
$sql_delele = "UPDATE aos_products_quotes pg
        LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id 
        SET pg.deleted = 1
        WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0";
$res = $db->query($sql_delele);



// create new group product
$product_quote_group = new AOS_Line_Item_Groups();
$product_quote_group->name = 'Daikin';//$map_quote_type[$quote->quote_type_c];
$product_quote_group->created_by = $quote->assigned_user_id;
$product_quote_group->assigned_user_id = $quote->assigned_user_id;
$product_quote_group->parent_type = 'AOS_Quotes';
$product_quote_group->parent_id = $quote->id;
$product_quote_group->number = '1';
$product_quote_group->currency_id = '-99';
$product_quote_group->save();

//create new products 

$part_numbers_implode = implode("','", $part_numbers);
$db = DBManagerFactory::getInstance();
$sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numbers_implode."') AND deleted = 0 ORDER BY price ASC";
$ret = $db->query($sql);
$total_amt = 0;
$subtotal_amount= 0;
$discount_amount = 0;
$tax_amount = 0;
$price_state = 0;
$total_amount = 0;
$index = 1;
$is_use_number_1 = false;
$id_special = '';
$price_include_admin = 0;

$products_return = [];

if ($_REQUEST['state'] == 'WA') {
    $price_state = 360;
} else {
    $price_state = 0;
}

while ($row = $db->fetchByAssoc($ret))
{
    if(!in_array($row['id'],$array_id_part_numbers_not_use)) {
        $product_line = new AOS_Products_Quotes();
        $product_line->currency_id = $row['currency_id'];
        $product_line->item_description = $row['description'];
        $product_line->name = $row['name'];
        $product_line->part_number = $row['part_number'];
        $product_line->product_cost_price = $row['cost'];
        $product_line->product_id = $row['id'];
        $product_line->group_id = $product_quote_group->id;
        $product_line->parent_id = $quote->id;;
        $product_line->parent_type = 'AOS_Quotes';
        $product_line->discount = 'Percentage';
        //display number index 
        if(($row['part_number'] =='DSI' || $row['part_number'] =='DS') && !$is_use_number_1) {
            $product_line->number = 1;
            $is_use_number_1 = true;
        }

        //logic product quantity ,price
        if ($row['part_number'] == 'FTXZ25N') {
            $product_line->product_qty = $quantity_us7_25kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXZ35N') {
            $product_line->product_qty = $quantity_us7_35kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXZ50N') {
            $product_line->product_qty = $quantity_us7_50kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FVXG25K2V1B') {
            $product_line->product_qty = $quantity_nexura_25kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FVXG35K2V1B') {
            $product_line->product_qty = $quantity_nexura_35kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FVXG50K2V1B') {
            $product_line->product_qty = $quantity_nexura_48kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM20Q') {
            $product_line->product_qty = $quantity_cora_20kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM25Q') {
            $product_line->product_qty = $quantity_cora_25kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM35Q') {
            $product_line->product_qty = $quantity_cora_35kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM50Q') {
            $product_line->product_qty = $quantity_cora_50kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'STANDARD_AC_INSTALL') {
            $product_line->product_qty = $quantity_daikin;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'DBW') {
            $product_line->product_qty = $quantity_type_brick;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM20U') {
            $product_line->product_qty = $quantity_alira_20kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM25U') {
            $product_line->product_qty = $quantity_alira_25kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM35U') {
            $product_line->product_qty = $quantity_alira_35kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM46U') {
            $product_line->product_qty = $quantity_alira_46kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM50U') {
            $product_line->product_qty = $quantity_alira_50kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM60U') {
            $product_line->product_qty = $quantity_alira_60kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'FTXM71U') {
            $product_line->product_qty = $quantity_alira_71kw;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'BRP072C42') {
            $product_line->product_qty = $quantity_install_wifi;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraDedicatedCircuit[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraDoubleBrick[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraDoubleStorey[0];
        } else if($row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraElectricalRCDUpgrade[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraHighWallBracket[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraInternalWall[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraLowWallBracket[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraRoofCavityRun[0];
        } else if($row['part_number'] == 'DI-Roof') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraRooftopInstall[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraSubFloorInstallation[0];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraWallPlasterCut[0];
        } else if($row['part_number'] == 'DIFFICUL_INSTALL') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraExtra[0];
        } else if($row['part_number'] == 'DI_CondensatePump') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraDICondensatePump[0];
        } else if($row['part_number'] == 'Travel') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $extraTravel[0];
        } else {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }

        $product_line->product_total_price = $product_line->product_list_price*$product_line->product_qty; 
        $price_include_admin += $product_line->product_total_price;

        // if($row['part_number'] != 'DIFFICUL_INSTALL'){
        //     $price_include_admin += $product_line->product_total_price;
        // }

        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U') {
            $product_line->number = 2;
        } else if( $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY') {
            $product_line->number = 3;
        } else if( $row['part_number'] == 'BRP072C42') {
            $product_line->number = 4;
        } else if( $row['part_number'] == 'STANDARD_AC_INSTALL' || $row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'DI_CondensatePump') {
            $product_line->number = 5;
        }
        else if( $row['part_number'] == 'DBW') {
            $product_line->number = 6;
        }
        // Todo List
        if($row['part_number'] != 'DBW') {
            if($row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'DI_CondensatePump') {
                if(($row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' && $extraDedicatedCircuit[0] >= 0 && $extraDedicatedCircuit[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' && $extraDoubleBrick[0] >= 0 && $extraDoubleBrick[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' && $extraDoubleStorey[0] >= 0 && $extraDoubleStorey[1] == 1)
                    || ($row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' && $extraElectricalRCDUpgrade[0] >= 0 && $extraElectricalRCDUpgrade[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' && $extraHighWallBracket[0] >= 0 && $extraHighWallBracket[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' && $extraInternalWall[0] >= 0 && $extraInternalWall[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' && $extraLongPipeRun[0] >= 0 && $extraLongPipeRun[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' && $extraLowWallBracket[0] >= 0 && $extraLowWallBracket[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' && $extraRoofCavityRun[0] >= 0 && $extraRoofCavityRun[1] == 1)
                    || ($row['part_number'] == 'DI-Roof' && $extraRooftopInstall[0] >= 0 && $extraRooftopInstall[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' && $extraSubFloorInstallation[0] >= 0 && $extraSubFloorInstallation[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' && $extraWallPlasterCut[0] >= 0 && $extraWallPlasterCut[1] == 1)
                    || ($row['part_number'] == 'DIFFICUL_INSTALL' && $extraExtra[0] >= 0 && $extraExtra[1] == 1)
                    || ($row['part_number'] == 'DI_CondensatePump' && $extraDICondensatePump[0] >= 0 && $extraDICondensatePump[1] == 1)
                    || ($row['part_number'] == 'Travel' && $extraTravel[0] >= 0 && $extraTravel[1] == 1)
                    ) {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => number_format($product_line->vat_amt, 2),
                        'Discount' => 0,
                        'Total' => number_format($product_line->product_total_price, 2),
                        'index' => $product_line->number,
                    );
                }
            } else {
                $products_return[$row['part_number']] = array (
                    'Quantity' =>$product_line->product_qty,
                    'Product' =>  $product_line->name,
                    'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                    'List' =>  number_format($product_line->product_cost_price, 2),
                    'Sale_Price' => number_format($product_line->product_list_price, 2),
                    'Tax_Amount' => number_format($product_line->vat_amt, 2),
                    'Discount' => 0,
                    'Total' => number_format($product_line->product_total_price, 2),
                    'index' => $product_line->number,
                );
            }
            
        }
        
        //logic product quantity ,price
        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'STANDARD_AC_INSTALL' || $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY' || $row['part_number'] == 'BRP072C42' || $row['part_number'] == 'DBW' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U' || $row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'FTXZ25N') {
            $product_line->product_list_price = 0;
            $product_line->product_cost_price = 0;
            $product_line->product_cost_price_usdollar = 0;
            $product_line->product_total_price = 0;
        } else {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }

        $product_line->vat = '10.0';
        $product_line->vat_amt = 10.0;

        if($row['part_number'] != 'DBW') {
            if($row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'DI_CondensatePump' ) {
                if(($row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' && $extraDedicatedCircuit[0] >= 0 && $extraDedicatedCircuit[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' && $extraDoubleBrick[0] >= 0 && $extraDoubleBrick[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' && $extraDoubleStorey[0] >= 0 && $extraDoubleStorey[1] == 1)
                    || ($row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' && $extraElectricalRCDUpgrade[0] >= 0 && $extraElectricalRCDUpgrade[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' && $extraHighWallBracket[0] >= 0 && $extraHighWallBracket[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' && $extraInternalWall[0] >= 0 && $extraInternalWall[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' && $extraLongPipeRun[0] >= 0 && $extraLongPipeRun[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' && $extraLowWallBracket[0] >= 0 && $extraLowWallBracket[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' && $extraRoofCavityRun[0] >= 0 && $extraRoofCavityRun[1] == 1)
                    || ($row['part_number'] == 'DI-Roof' && $extraRooftopInstall[0] >= 0 && $extraRooftopInstall[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' && $extraSubFloorInstallation[0] >= 0 && $extraSubFloorInstallation[1] == 1)
                    || ($row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' && $extraWallPlasterCut[0] >= 0 && $extraWallPlasterCut[1] == 1)
                    || ($row['part_number'] == 'DIFFICUL_INSTALL' && $extraExtra[0] >= 0 && $extraExtra[1] == 1)
                    || ($row['part_number'] == 'DI_CondensatePump' && $extraDICondensatePump[0] >= 0 && $extraDICondensatePump[1] == 1)
                    || ($row['part_number'] == 'Travel' && $extraTravel[0] >= 0 && $extraTravel[1] == 1)
                ) {
                    $product_line->save();
                }
            } else {
                $product_line->save();
            }
        }

        $total_amt += $product_line->product_total_price;
        $tax_amount += $product_line->vat_amt;

        if ($row['part_number'] == 'DSI' || $row['part_number'] =='DS') {
            $id_special = $product_line->id;
        }
    }
}

$index = array();
foreach ($products_return as $key => $row)
{
    $index[$key] = $row['index'];
}
array_multisort($index, SORT_ASC, $products_return);



$discount_amount = 0;
// $extraPrice = ($totalDAIKINExtra + ($totalDAIKINExtra)*0.1);
$extraPrice = 0;
$price_include_admin_20 = round(($price_include_admin + $price_state) + ($price_include_admin + $price_state)*0.19, 2);
$gst = round($price_include_admin_20*0.1, 2);
$subTotal = $price_include_admin_20;
$old_group_total = round($gst + $subTotal, 2);
$group_tmp = explode('.', round($gst + $subTotal, 2));

$new_group_total = '';
$group_price_1 = '';
if(substr($group_tmp[0], -2) <= '90') {
    $arr_str = explode('.', $group_tmp[0]);
    $group_price_1 = substr($arr_str[0], 0, strlen($arr_str[0]) - 2) . '90';
    $new_group_total = $group_price_1.'.00';
} else {
    $arr_str = explode('.', $group_tmp[0]);
    $group_price_1 = substr($arr_str[0], 0, strlen($arr_str[0]) - 2) . '90';
    $new_group_total = $group_price_1.'.00';
}
$different = $old_group_total - $new_group_total;
$delta = $different/1.1; 
$price_include_admin_20 = round($price_include_admin_20 - $delta,2);
$gst = round($gst - ($different - $delta), 2);
$subTotal =  round($price_include_admin_20,2);

$groupTotal = $new_group_total; 



$quote->total_amt = round($subTotal , 2);
$quote->subtotal_amount = round($subTotal , 2);
$quote->discount_amount = round($discount_amount , 2);
$quote->tax_amount = round($gst , 2);
$quote->total_amount = round($groupTotal , 2);
$quote->save();

$product_quote_group->total_amt = round($subTotal , 2);
$product_quote_group->tax_amount = round($gst , 2);
$product_quote_group->total_amount = round($groupTotal , 2);
$product_quote_group->subtotal_amount = round($subTotal , 2);
$product_quote_group->save();

if($id_special) {
    $line_item = new AOS_Products_Quotes();
    $line_item->retrieve($id_special);
    $line_item->product_cost_price = $price_include_admin_20;
    $line_item->product_list_price = $price_include_admin_20;
    $line_item->product_unit_price = $price_include_admin_20;
    $line_item->product_total_price = $price_include_admin_20;
    $line_item->vat = '10.0';
    $line_item->save();
}

$data_return = array (
    'quote_id' => $quote->id,
    'products' => $products_return,
    'pre_install_photos_c' => $quote->pre_install_photos_c,
    'Product_include_admin' => number_format($price_include_admin_20, 2),
    'groupProducts' => array(
        'Group_Name' => $product_quote_group->name,
        'Total' => number_format($product_quote_group->total_amount, 2),
        'Discount' => 0,
        'Subtotal' => number_format($product_quote_group->subtotal_amount, 2),
        // 'GST' => $gst,
        'GST' =>  number_format($product_quote_group->tax_amount, 2),
        'Group_Total' => number_format($product_quote_group->total_amount, 2),
    )
);

echo json_encode($data_return);