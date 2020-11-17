<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

//data request
$data_request = json_decode(trim($_REQUEST),true);
$part_numbers = array();

// check variable sanden
if($_REQUEST['choice_product_sanden'] == 'Sanden 315FQS') {
    $partNumber = 'GAUS-315FQS';
} elseif($_REQUEST['choice_product_sanden'] == 'Sanden 300FQS') {
    $partNumber = 'GAUS-300FQS';
} elseif($_REQUEST['choice_product_sanden'] == 'Sanden 250FQS') {
    $partNumber = 'GAUS-250FQS';
} elseif($_REQUEST['choice_product_sanden'] == 'Sanden 315FQV') {
    $partNumber = 'GAUS-315FQV';
} else {
    $partNumber = 'GAUS-160FQS';
}
array_push($part_numbers,$partNumber);
$old_tank_fuel = '';
if($_REQUEST['choice_type_install'] == 'New Build/Renovation') {
    $old_tank_fuel = 'newBuilding';
} else {
    if($_REQUEST['choice_type_product'] == 'Gas') {
        if($_REQUEST['product_choice_type_gas'] == 'Gas Instant') {
            $old_tank_fuel = 'gas_instant';
        } elseif($_REQUEST['product_choice_type_gas'] == 'Gas Storage') {
            $old_tank_fuel = 'gas_storage';
        }
    } elseif($_REQUEST['choice_type_product'] == 'Electric') {
        if($_REQUEST['product_choice_type_electric'] == 'Electric Storage') {
            $old_tank_fuel = 'electric_storage';
        } elseif($_REQUEST['product_choice_type_electric'] == 'Gravity Feed') {
            $old_tank_fuel = 'gravity_feed_electric';
        }
    } elseif($_REQUEST['choice_type_product'] == 'Solar') {
        $old_tank_fuel = 'solar';
    } elseif($_REQUEST['choice_type_product'] == 'Heat Pump') {
        $old_tank_fuel = 'heatpump';
    } elseif($_REQUEST['choice_type_product'] == 'LPG') {
        $old_tank_fuel = 'lpg';
    } elseif($_REQUEST['choice_type_product'] == 'Wood') {
        $old_tank_fuel = 'wood';
    } else {
        $old_tank_fuel = 'other';
    }
}

$map_quote_type = [
    'quote_type_sanden' => 'Sanden', 
    'quote_type_solar' => 'Solar', 
    'quote_type_daikin' => 'Daikin', 
    'quote_type_off_grid_system' => 'Off-grid System', 
    'quote_type_nexura' => 'Nexura', 
    'quote_type_methven' => 'Methven', 
    'quote_type_battery' => 'Battery', 
    'quote_type_tesla' => 'Tesla'
];
$part_numbers_display_first = ['e5bc1aa4-b965-8a76-debe-5e2681ba55f7'];
$part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19'];
$array_id_part_numbers_not_use = ['1455e1e9-38a6-22e2-f898-57e8966e5256', '4e6ea564-761a-7482-76d0-582c0ca119e0'];

//get bean quote
$quote = new AOS_Quotes();

//Update Quote Suburb, Postcode, State
$quote->name = 'GUEST ' .$_REQUEST['suburb_customer'] .' '.$_REQUEST['state_customer'].' '.$_REQUEST['choice_product_sanden'];
$quote->pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
date_default_timezone_set("Australia/Melbourne");
$dateQuote = new DateTime();
$quote->quote_date_c = date('Y-m-d H:i:s', time());
$quote->quote_type_c = 'quote_type_sanden';
$quote->install_address_postalcode_c = $_REQUEST['postcode_customer'];
$quote->billing_address_postalcode = $_REQUEST['postcode_customer'];
$quote->billing_address_state = $_REQUEST['state_customer'];
$quote->install_address_state_c = $_REQUEST['state_customer'];
$quote->billing_address_city = $_REQUEST['suburb_customer'];
$quote->install_address_city_c = $_REQUEST['suburb_customer'];
$quote->old_tank_fuel_c = $old_tank_fuel;
$quote->stage = 'Guest';
$quote->save();

//delele all line items
$db = DBManagerFactory::getInstance();
$sql_delele = "UPDATE aos_products_quotes pg
        LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id 
        SET pg.deleted = 1
        WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0";
$res = $db->query($sql_delele);


// Check VEEC
if($_REQUEST['state_customer'] == 'VIC' && $_REQUEST['choice_type_product'] == 'Electric') {
    if($_REQUEST['product_choice_type_electric'] == 'Electric Storage' || $_REQUEST['product_choice_type_electric'] == 'Gravity Feed') {
        array_push($part_numbers,'STC Rebate Certificate', 'VEEC Rebate Certificate');
    } else {
        array_push($part_numbers,'STC Rebate Certificate');
    }
} else {
    if($_REQUEST['provide_stcs'] != 'No' ) {
        array_push($part_numbers,'STC Rebate Certificate');
    }
}

$quantityPB = 0;
//create new items 
if($_REQUEST['plumbing_installation'] == 'Yes' || $_REQUEST['electric_installation'] == 'Yes') {
    array_push($part_numbers,'PB');
}
   
if($_REQUEST['choice_type_install'] == 'New Build') {
    if($_REQUEST['plumbing_installation'] == 'Yes') {
        array_push($part_numbers,'Sanden_Plb_Install_Std', 'PizzaBase');
        $quantityPB += 1;
        if($_REQUEST['electric_installation'] == 'Yes') {
            array_push($part_numbers,'Sanden_Elec_Install_Std', 'SSI');
            $quantityPB += 1;
        } else {
            array_push($part_numbers,'SSPI');
        }
    } else {
        array_push($part_numbers, 'SANDEN_SUPPLY_ONLY');
    }
}else{
    if($_REQUEST['plumbing_installation'] == 'Yes') {
        array_push($part_numbers,'Sanden_Plb_Install_Std', 'PizzaBase');
        $quantityPB += 1;
        if($_REQUEST['electric_installation'] == 'Yes') {
            array_push($part_numbers,'Sanden_Elec_Install_Std', 'SSI');
        } else {
            array_push($part_numbers,'SSPI');
        }
    } else {
        array_push($part_numbers, 'SANDEN_SUPPLY_ONLY');
    }
}
if($_REQUEST['choice_product_sanden'] == 'Sanden 315FQV' || $_REQUEST['choice_product_sanden'] == 'Sanden 315FQS' || $_REQUEST['choice_product_sanden'] == 'Sanden 300FQS' || $_REQUEST['choice_product_sanden'] == 'Sanden 250FQS' || $_REQUEST['choice_product_sanden'] == 'Sanden 160FQS') {
    if($_REQUEST['connection_kit'] == '15mm Quick Connection Kit (QIK15)') {
        array_push($part_numbers,'QIK15−HPUMP');
    }elseif($_REQUEST['connection_kit'] == '20mm Quick Connection Kit (QIK25)') {
        array_push($part_numbers,'QIK20−HPUMP');
    }
};
if($_REQUEST['hot_water_rebate'] == 'Yes') {
    array_push($part_numbers,'SV_SHWR');
};
//
// Need to Resolve
// if($_REQUEST['connected_to_reticulated_gas'] == 'No') {
//     if($_REQUEST['state_customer'] == 'SA') {
//         array_push($part_numbers,'SA_REES');
//     }
// }

// if($_REQUEST['alectrical_already'] == 'No') {
//     array_push($part_numbers,'RCBO');
// };

// if($_REQUEST['concrete_slab'] == 'Yes') {
//     array_push($part_numbers,'Sanden_Tank_Slab');
// };

// if($_REQUEST['concrete_pavers'] == 'Yes') {
//     array_push($part_numbers,'Sanden_HP_Pavers');
// };

if($_REQUEST['extra_field']['plumbing_extra'] == 'Yes') {
    array_push($part_numbers,'Sanden_Complex_Install');
}
if($_REQUEST['extra_field']['electrical_extra'] == 'Yes') {
    array_push($part_numbers,'SANDEN_ELEC_EXTRA');
}
if($_REQUEST['extra_field']['rcd_upgrade'] == 'Yes') {
    array_push($part_numbers,'RCBO');
}
if($_REQUEST['extra_field']['switchboard_upgrade'] == 'Yes') {
    array_push($part_numbers,'SwitchUpgrade');
}
if($_REQUEST['extra_field']['hws_relocation'] == 'Yes') {
    array_push($part_numbers,'HWS_R');
}
if($_REQUEST['extra_field']['sanden_tank_slab'] == 'Yes') {
    array_push($part_numbers,'Sanden_Tank_Slab');
}
if($_REQUEST['extra_field']['sanden_pavers'] == 'Yes') {
    array_push($part_numbers,'Sanden_HP_Pavers');
}
if($_REQUEST['extra_field']['site_delivery'] == 'Yes') {
    array_push($part_numbers,'Site_Delivery');
}
if($_REQUEST['extra_field']['trade_price_discount'] == 'Yes') {
    array_push($part_numbers,'Spec_Trade_Disc');
}
if($_REQUEST['extra_field']['san_wall_bracket'] == 'Yes') {
    array_push($part_numbers,'san_wall_bracket');
}

    // $quote_post_new_code = $quote->install_address_postalcode_c;
$array_stc_veec = get_value_stc_veec($quote->install_address_postalcode_c,$partNumber);
// var_dump($array_stc_veec);
// create new group product
$product_quote_group = new AOS_Line_Item_Groups();
$product_quote_group->name = 'Sanden';//$map_quote_type[$quote->quote_type_c];
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
$discount_amount =0;
$tax_amount =0;
$total_amount = 0;
$index = 1;
$is_use_number_1 = false;
$products_return = [];
$price_include_admin = 0;
$price_include_admin_20 = 0;
$price_veec_stc = 0;
$price_state = 0;
$priceHotWater = 0;
$priceHWS = 0;
$plumbingExtra = 0;
$ElecExtra = 0;
$RCBExtra = 0;
$SwitchExtra = 0;
$Tank_slab = 0;
$Pavers = 0;
$priceReticulated_gas = 0;
if ($_REQUEST['state_customer'] == 'WA' || $_REQUEST['state_customer'] == 'TAS') {
    $price_state = 360;
} elseif($_REQUEST['state_customer'] == 'SA' || $_REQUEST['state_customer'] == 'VIC' || $_REQUEST['state_customer'] == 'ACT' || $_REQUEST['state_customer'] == 'QLD') {
    $price_state = 160;
} else {
    $price_state = 50;
}

$id_special = '';
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
        $product_line->parent_id = $quote->id;
        $product_line->parent_type = 'AOS_Quotes';
        $product_line->discount = 'Percentage';
        //display number index 
        if(in_array($row['id'],$part_numbers_display_first)) {
            $product_line->number = 1;
            $is_use_number_1 = true;
        }

        //logic product quantity ,price
        if($row['part_number'] == 'STC Rebate Certificate'){
            $product_line->product_qty = $array_stc_veec['stcs_number'];
            if((int)$array_stc_veec['stc'] == '' || (int)$array_stc_veec['stc'] == 0 || (int)$array_stc_veec['stc'] == -1) {
                $product_line->product_list_price = ($row['cost'] + 1);
            } else {
                $product_line->product_list_price = ((int)$array_stc_veec['stc'] -1)*(-1);
            }
        }elseif($row['part_number'] == 'VEEC Rebate Certificate'){
            $total_veecs = 0;
            foreach ($array_stc_veec['eligible_veecs'] as $key => $value) {
                $total_veecs += $value;
            }
            $product_line->product_qty =$total_veecs;
            if((int)$array_stc_veec['veec'] == '' || (int)$array_stc_veec['veec'] == 0 || (int)$array_stc_veec['veec'] == -1) {
                $product_line->product_list_price = ($row['cost'] + 1);
            } else {
                $product_line->product_list_price = ((int)$array_stc_veec['veec'] -2)*(-1);
            } 
        }elseif($row['part_number'] == 'SV_SHWR'){
            $product_line->product_qty =1; 
            $product_line->product_list_price = $row['cost'];
        }elseif($row['part_number'] == 'SA_REES'){
            $product_line->product_qty =1; 
            $product_line->product_list_price = $row['cost'];
        }elseif($row['part_number'] == 'GAUS-315FQS' || $row['part_number'] == 'GAUS-300FQS' || $row['part_number'] == 'GAUS-250FQS' || $row['part_number'] == 'GAUS-315FQV' || $row['part_number'] == 'GAUS-160FQS') {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }elseif($row['part_number'] == 'PB'){
            $product_line->product_qty = $quantityPB; 
            $product_line->product_list_price = $row['cost'];
            //// Extra
        } elseif($row['part_number'] == 'Sanden_Complex_Install'){
            $product_line->product_qty =1; 
            if($_REQUEST['extra_field']['price_plumbing_extra'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_plumbing_extra'];
            }
            
        }elseif($row['part_number'] == 'SANDEN_ELEC_EXTRA'){
            $product_line->product_qty =1; 
            if($_REQUEST['extra_field']['price_electrical_extra'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_electrical_extra'];
            }
        }elseif($row['part_number'] == 'RCBO') {
            $product_line->product_qty = 1;
            if($_REQUEST['extra_field']['price_rcd_upgrade'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_rcd_upgrade'];
            }
        }elseif($row['part_number'] == 'SwitchUpgrade'){
            $product_line->product_qty = 1; 
            if($_REQUEST['extra_field']['price_switchboard_upgrade'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_switchboard_upgrade'];
            }
        }elseif($row['part_number'] == 'HWS_R'){
            $product_line->product_qty =1; 
            if($_REQUEST['extra_field']['price_hws_relocation'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_hws_relocation'];
            }
        }elseif($row['part_number'] == 'Sanden_Tank_Slab') {
            $product_line->product_qty = 1;
            if($_REQUEST['extra_field']['price_sanden_tank_slab'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_sanden_tank_slab'];
            }
        }elseif($row['part_number'] == 'Sanden_HP_Pavers'){
            $product_line->product_qty = 1; 
            if($_REQUEST['extra_field']['price_sanden_pavers'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_sanden_pavers'];
            }
            // End Extra
        }elseif($row['part_number'] == 'Site_Delivery'){
            $product_line->product_qty = 1; 
            if($_REQUEST['extra_field']['price_site_delivery'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_site_delivery'];
            }
            // End Extra
        }elseif($row['part_number'] == 'Spec_Trade_Disc'){
            $product_line->product_qty = 1; 
            if($_REQUEST['extra_field']['price_trade_price_discount'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_trade_price_discount'];
            }
            // End Extra
        } elseif($row['part_number'] == 'san_wall_bracket'){
            $product_line->product_qty = 1; 
            if($_REQUEST['extra_field']['price_san_wall_bracket'] == $row['cost']) {
                $product_line->product_list_price = $row['cost'];
            } else {
                $product_line->product_list_price = $_REQUEST['extra_field']['price_san_wall_bracket'];
            }
            // End Extra
        } else{
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }

        $product_line->product_total_price = $product_line->product_list_price*$product_line->product_qty; 

        if($row['part_number'] == 'STC Rebate Certificate' || $row['part_number'] == 'VEEC Rebate Certificate'){
            $price_veec_stc += str_replace($product_line->product_total_price, $product_line->product_total_price.'.00', $product_line->product_total_price);
            // $price_veec_stc += -1023;
        } elseif($row['part_number'] == 'SV_SHWR'){
            $priceHotWater += str_replace($product_line->product_total_price, $product_line->product_total_price.'.00', $product_line->product_total_price);
        } elseif($row['part_number'] == 'SA_REES'){
            $priceReticulated_gas += str_replace($product_line->product_total_price, $product_line->product_total_price.'.00', $product_line->product_total_price);
        }else {
            $price_include_admin += $product_line->product_total_price;
        }

        if ($row['part_number'] == 'SSI' || $row['part_number'] == 'SANDEN_SUPPLY_ONLY' || $row['part_number'] == 'SSPI') {
            $product_line->number = 1;
        } elseif ($row['part_number'] == 'GAUS-315FQS' || $row['part_number'] == 'GAUS-300FQS' || $row['part_number'] == 'GAUS-250FQS' || $row['part_number'] == 'GAUS-315FQV' || $row['part_number'] == 'GAUS-160FQS') {
            $product_line->number = 2;
        } elseif ($row['part_number'] == 'QIK15−HPUMP' || $row['part_number'] == 'QIK25−HPUMP') {
            $product_line->number = 3;
        } elseif($row['part_number'] == 'Sanden_Plb_Install_Std' || $row['part_number'] == 'Sanden_Elec_Install_Std') {
            $product_line->number = 4;
        } elseif($row['part_number'] == 'Sanden_Tank_Slab' || $row['part_number'] == 'Sanden_HP_Pavers' || $row['part_number'] == 'Sanden_Complex_Install' || $row['part_number'] == 'HWS_R' || $row['part_number'] == 'SANDEN_ELEC_EXTRA' || $row['part_number'] == 'RCBO' || $row['part_number'] == 'SwitchUpgrade' || $row['part_number'] == 'Site_Delivery' || $row['part_number'] == 'Spec_Trade_Disc' || $row['part_number'] == 'san_wall_bracket') {
            $product_line->number = 5;
        } else {
            $product_line->number = 6;
        }
        
        $total_amt += $product_line->product_total_price;
        $tax_amount += $product_line->vat_amt;
        if($row['part_number'] != 'PizzaBase' && $row['part_number'] != 'PB') {
            if($row['part_number'] == 'Sanden_Complex_Install' || $row['part_number'] == 'HWS_R' || $row['part_number'] == 'SANDEN_ELEC_EXTRA' || $row['part_number'] == 'RCBO' || $row['part_number'] == 'SwitchUpgrade' || $row['part_number'] == 'Sanden_Tank_Slab' || $row['part_number'] == 'Sanden_HP_Pavers' || $row['part_number'] == 'Site_Delivery' || $row['part_number'] == 'Spec_Trade_Disc' || $row['part_number'] == 'san_wall_bracket') {
                if($row['part_number'] == 'Sanden_Complex_Install' && $_REQUEST['extra_field']['itemise_plumbing'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'HWS_R' && $_REQUEST['extra_field']['itemise_relocation'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'SANDEN_ELEC_EXTRA' && $_REQUEST['extra_field']['itemise_electrical'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'RCBO' && $_REQUEST['extra_field']['itemise_rcd_upgrade'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'SwitchUpgrade' && $_REQUEST['extra_field']['itemise_switchboard'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'Sanden_Tank_Slab' && $_REQUEST['extra_field']['itemise_slab'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'Sanden_HP_Pavers' && $_REQUEST['extra_field']['itemise_pavers'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'Site_Delivery' && $_REQUEST['extra_field']['itemise_site_delivery'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'Spec_Trade_Disc' && $_REQUEST['extra_field']['itemise_trade_price_discount'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
                        'index' => $product_line->number,
                    );
                } else if($row['part_number'] == 'san_wall_bracket' && $_REQUEST['extra_field']['itemise_san_wall_bracket'] == 'Yes') {
                    $products_return[$row['part_number']] = array (
                        'Quantity' =>$product_line->product_qty,
                        'Product' =>  $product_line->name,
                        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                        'List' =>  number_format($product_line->product_cost_price, 2),
                        'Sale_Price' => number_format($product_line->product_list_price, 2),
                        'Tax_Amount' => $product_line->vat_amt,
                        'Discount' => 0,
                        'Total' => $product_line->product_total_price,
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
                    'Tax_Amount' => $product_line->vat_amt,
                    'Discount' => 0,
                    'Total' => $product_line->product_total_price,
                    'index' => $product_line->number,
                );
            }
        }
        //logic product quantity ,price
        if($row['part_number'] == 'STC Rebate Certificate'){
            $product_line->product_qty = $array_stc_veec['stcs_number'];
            if((int)$array_stc_veec['stc'] == '' || (int)$array_stc_veec['stc'] == 0 || (int)$array_stc_veec['stc'] == -1) {
                $product_line->product_list_price = $row['cost'] + 1;
                $product_line->product_unit_price = $row['cost'] + 1;
            } else {
                $product_line->product_list_price = ((int)$array_stc_veec['stc'] -1)*(-1);
                $product_line->product_unit_price = ((int)$array_stc_veec['stc'] -1)*(-1);
            }
            
            
        }elseif($row['part_number'] == 'VEEC Rebate Certificate'){
            $total_veecs = 0;
            foreach ($array_stc_veec['eligible_veecs'] as $key => $value) {
                $total_veecs += $value;
            }
            $product_line->product_qty =$total_veecs; 
            if((int)$array_stc_veec['veec'] == '' || (int)$array_stc_veec['veec'] == 0 || (int)$array_stc_veec['veec'] == -1) {
                $product_line->product_list_price = ($row['cost'] + 1);
                $product_line->product_unit_price = ($row['cost'] + 1);
            } else {
                $product_line->product_list_price = ((int)$array_stc_veec['veec'] -2)*(-1);
                $product_line->product_unit_price = ((int)$array_stc_veec['veec'] -2)*(-1);
            }
        }elseif($row['part_number'] == 'SV_SHWR'){
            $product_line->product_qty =1; 
            $product_line->product_list_price = $row['cost'];
            $product_line->product_unit_price = $row['cost'];
        }elseif($row['part_number'] == 'SA_REES'){
            $product_line->product_qty =1; 
            $product_line->product_list_price = $row['cost'];
            $product_line->product_unit_price = $row['cost'];
        } else{
            $product_line->product_qty = 1;
            $product_line->product_list_price = 0;
            $product_line->product_cost_price = 0;
            $product_line->product_cost_price_usdollar = 0;
            $product_line->product_total_price = 0;
        }

        if($row['part_number'] == 'STC Rebate Certificate' || $row['part_number'] == 'SV_SHWR' || $row['part_number'] == 'VEEC Rebate Certificate'){
            $product_line->vat = '0';
            $product_line->vat_amt = 0;
        } else {
            $product_line->vat = '10.0';
            $product_line->vat_amt = 10.0;
        }

        if($row['part_number'] != 'PizzaBase' && $row['part_number'] != 'PB') {
            if($row['part_number'] == 'Sanden_Complex_Install' || $row['part_number'] == 'HWS_R' || $row['part_number'] == 'SANDEN_ELEC_EXTRA' || $row['part_number'] == 'RCBO' || $row['part_number'] == 'SwitchUpgrade' || $row['part_number'] == 'Sanden_Tank_Slab' || $row['part_number'] == 'Sanden_HP_Pavers') {
                if($row['part_number'] == 'Sanden_Complex_Install' && $_REQUEST['extra_field']['itemise_plumbing'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'HWS_R' && $_REQUEST['extra_field']['itemise_relocation'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'SANDEN_ELEC_EXTRA' && $_REQUEST['extra_field']['itemise_electrical'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'RCBO' && $_REQUEST['extra_field']['itemise_rcd_upgrade'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'SwitchUpgrade' && $_REQUEST['extra_field']['itemise_switchboard'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'Sanden_Tank_Slab' && $_REQUEST['extra_field']['itemise_slab'] == 'Yes') {
                    $product_line->save();
                } else if($row['part_number'] == 'Sanden_HP_Pavers' && $_REQUEST['extra_field']['itemise_pavers'] == 'Yes') {
                    $product_line->save();
                }
            } else {
                $product_line->save();
            }
        }               
        
        // if ($row['part_number'] == 'GAUS-315FQS' || $row['part_number'] == 'GAUS-300FQS' || $row['part_number'] == 'GAUS-250FQS' || $row['part_number'] == 'GAUS-315FQV' || $row['part_number'] == 'GAUS-160FQS') {
        //     $id_special = $product_line->id;
        // }
        if ($row['part_number'] == 'SSI' || $row['part_number'] == 'SANDEN_SUPPLY_ONLY' || $row['part_number'] == 'SSPI') {
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
$extraPrice = ($priceHWS + $plumbingExtra + $ElecExtra + $RCBExtra + $SwitchExtra + ($priceHWS + $plumbingExtra + $ElecExtra + $RCBExtra + $SwitchExtra + $Tank_slab + $Pavers)*0.1);
$price_include_admin_20 = ($price_include_admin + $price_state) + ($price_include_admin + $price_state)*0.19;
$gst = $price_include_admin_20*0.1;
$subTotal = $price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas + $extraPrice;
$old_group_total = $gst + $subTotal;
$group_tmp = explode('.',($gst + $subTotal));
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
$subTotal = $price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas + $extraPrice;
$subTotal =  round($subTotal ,2);

$groupTotal = $new_group_total; 


$total_amount = $total_amt + $tax_amount;
$subtotal_amount= $total_amt;

$quote->total_amt = round($subTotal , 2);
$quote->subtotal_amount = round($subTotal , 2);
$quote->discount_amount = round($discount_amount , 2);
$quote->tax_amount = round($gst , 2);
$quote->total_amount = round($groupTotal , 2);

$quote->group_total_amt[] = round($subTotal , 2);
$quote->group_subtotal_amount[] = round($subTotal , 2);
$quote->discount_amount = round($discount_amount , 2);
$quote->group_tax_amount[] = round($gst , 2);
$quote->group_total_amount[] = round($groupTotal , 2);

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
    $line_item->save();
    $products_return[$line_item->part_number] = array (
        'Quantity' => 1,
        'Product' =>  $line_item->name,
        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$line_item->item_description),
        'List' =>  number_format($line_item->product_cost_price, 2),
        'Sale_Price' => number_format($line_item->product_list_price, 2),
        'Tax_Amount' => $line_item->vat_amt,
        'Discount' => 0,
        'Total' => $line_item->product_total_price,
        'index' => 1,
    );
}


//data return
$data_return = array (
    'quote_id' => $quote->id,
    'assinged_user' => '61e04d4b-86ef-00f2-c669-579eb1bb58fa',
    'products' => $products_return,
    'pre_install_photos_c' => $quote->pre_install_photos_c,
    'Product_include_admin' => $price_include_admin_20,
    'groupProducts' => array(
        'Group_Name' => $product_quote_group->name,
        'Total' => $product_quote_group->total_amount,
        'Discount' => 0,
        'Subtotal' => $subTotal,
        'GST' => $gst,
        'Tax' => $product_quote_group->tax_amount,
        'Group_Total' => $groupTotal,
    )
);

echo json_encode($data_return);

function get_value_stc_veec ($postalcode,$partNumbers){

        $curl = curl_init();
        $url = 'https://www.rec-registry.gov.au/';
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    
    
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    
        $result = curl_exec($curl);
    
        $url = 'https://www.rec-registry.gov.au/rec-registry/app/calculators/swh-stc-calculator';
    
    
    
        curl_setopt($curl, CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    
    
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: www.rec-registry.gov.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
    
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.8",
                "Accept-Encoding: gzip, deflate, br",
                "Referer: https://www.rec-registry.gov.au/rec-registry/app/home",
                "Connection: keep-alive",
                "Upgrade-Insecure-Requests:1",
            )
        );
    
        $result = curl_exec($curl);
        $html = str_get_html($result);
        if($scrf = $html->find('meta[name="_csrf"]')[0] == null) {
            $stcs_number = 32;
            $response_array["stcs_number"] = $stcs_number;
        } else {
            $scrf = $html->find('meta[name="_csrf"]')[0]->getAttribute("content");
    
            $fields = array();
            $fields['postcode'] = $postalcode;//'2000';
            $fields['systemBrand'] = 'Sanden';
            $fields['systemModel'] = $partNumbers;
            $fields['installationDate'] = date('Y-m-d').'T00:00:00.000Z';
            $post_field = json_encode($fields);
            $url = "https://www.rec-registry.gov.au/rec-registry/app/calculators/swh/stc";
        
            curl_setopt($curl, CURLOPT_URL, $url);
        
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_HEADER, false);
        
            curl_setopt($curl, CURLOPT_POSTFIELDS,  $post_field);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        
                    "Host: www.rec-registry.gov.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json; charset=UTF-8",
                    "Accept: application/json, text/javascript, */*; q=0.01",
                    "Accept-Language: en-US,en;q=0.8",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Content-Length: " .strlen($post_field),
                    "Origin: https://www.rec-registry.gov.au",
                    "Referer: https://www.rec-registry.gov.au/rec-registry/app/calculators/swh-stc-calculator",
                    "X-CSRF-TOKEN: ".$scrf,
                    "X-Requested-With: XMLHttpRequest"
                )
            );
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result);
        
            //print($result);
            if(isset($result->status) && $result->status =="Completed"){
                $stcs_number = $result->result->numStc;
                $response_array["stcs_number"] = $stcs_number;
            }
        }
        
        // VEEC Number ===============
    
        date_default_timezone_set('Africa/Lagos');
        set_time_limit ( 0 );
        ini_set('memory_limit', '-1');
    
        $fields = array();
        $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
        $data = dlPage($url, $fields);
    
        $html = str_get_html($data);
    
        foreach($html->find('input') as $element) {
            $fields[$element->name] = $element->value;
        }
    
        $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate'] = date('d/m/Y');
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$hidParam'] = '{&quot;data&quot;:&quot;12|#|#&quot;}';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$State'] = '{&quot;rawValue&quot;:&quot;'.(time ()*1000).'&quot;}';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDDState']= '{&quot;windowsState&quot;:&quot;0:0:-1:350:-45:1:0:0:1:0:0:0&quot;}';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDD$C']= '{&quot;visibleDate&quot;:&quot;'.date('m/d/Y').'&quot;,&quot;selectedDates&quot;:[&quot;'.date('m/d/Y').'&quot;]}';
    
        $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
    
        $curl = curl_init($url);
        //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);
    
        $html = str_get_html($result);
    
        // Step 2
    
        $fields = array();
        foreach($html->find('input') as $element) {
            $fields[$element->name] = $element->value;
        }
        $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules';
        //1E - Water Heating - Electric Boosted Solar Replacing Electric
        // $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '652';
        //thienpb fix update
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '818';
    
    
    
        $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
        $curl = curl_init($url);
        //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);
    
        $html = str_get_html($result);
    
        // Step 3
    
        $fields = array();
        foreach($html->find('input') as $element) {
            $fields[$element->name] = $element->value;
        }
        $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';
    
    
    
        $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
        $curl = curl_init($url);
        //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);
    
        $html = str_get_html($result);
        //print $html;
    
        // Step 4
    
        $fields = array();
        foreach($html->find('input') as $element) {
            $fields[$element->name] = $element->value;
        }
    
        $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$ExtraButton2';
        $fields['__EVENTARGUMENT'] = 'Click';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue351'] = $partNumbers;
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue352'] = $postalcode;
        $fields['ctl00$ctl00$ContentPlaceHolder1$ExtraButton2'] = 'Calculate';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';
        // $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue4107'] = 'Large';
        //thienpb fix update
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue7026'] = 'Medium';
    
        $url = 'https://www.veu-registry.vic.gov.au/public/calculator/veeccalculator.aspx';
        $curl = curl_init($url);
        //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);
    
        $html_inside = str_get_html($result);
    
        $quantites = $html_inside->find('#ContentPlaceHolder1_Content_Editor_VEECQuantityBox td');
        if(isset($quantites[1]) && $quantites[1]->plaintext!= "") $response_array["eligible_veecs"][$_GET['part_number']] = $quantites[1]->plaintext;
    
    
        // STC + VEEC price
    
        $tmpfname = dirname(__FILE__).'/cookie.geocreation.txt';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $result_data = json_decode($result);
        $accesstoken =  $result_data->AuthenticationResult->AccessToken;
        $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);


        $param = array (
            'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
            'AuthFlow' => 'REFRESH_TOKEN_AUTH',
            'AuthParameters' => 
            array (
            'REFRESH_TOKEN' => $RefreshToken,
            'DEVICE_KEY' => NULL,
            ),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Origin: https://geocreation.com.au';
        $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
        $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $headers[] = 'Content-Type: application/x-amz-json-1.1';
        $headers[] = 'Accept: */*';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://geocreation.com.au/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $IdToken =  $result_data->AuthenticationResult->IdToken;
        
        $curl = curl_init();
        $url = 'https://api.geocreation.com.au/api/c1/price_feeds/';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-Type: application/json; charset=utf-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/new",
            "Origin: https://geocreation.com.au",
        )
    );
        
        $result = curl_exec($curl);
        curl_close($curl);
        
        $result_json = json_decode($result);
        // foreach($result_json->result as $re){
        //     $response_array[$re->reference] = $re->currentPrice;
        // }

        //Thienpb update code get price.
        foreach($result_json->priceFeed as $re){
            $response_array[$re->reference] = $re->currentPrice;
        }
    
    return $response_array;
}

function dlPage($href, $fields) {
    $fields_string = '';
    if (count($fields)) {
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, count($fields) ? count($fields) : 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $str = curl_exec($curl);
    curl_close($curl);
    return $str;
}
