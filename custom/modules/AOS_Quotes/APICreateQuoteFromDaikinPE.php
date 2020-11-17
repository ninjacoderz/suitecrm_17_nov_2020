<?php
$products = json_decode(htmlspecialchars_decode($_REQUEST['products']), true);
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

foreach($products as $product) {
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
        $quantity_us7_25kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'US7 3.5kW') {
        array_push($part_numbers, 'FTXZ35N');
        $quantity_us7_35kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'US7 5.0kW') {
        array_push($part_numbers, 'FTXZ50N'); 
        $quantity_us7_50kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 2.5kW') {
        array_push($part_numbers, 'FVXG25K2V1B');
        $quantity_nexura_25kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 3.5kW') {
        array_push($part_numbers, 'FVXG35K2V1B'); 
        $quantity_nexura_35kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Nexura 4.8kW') {
        array_push($part_numbers, 'FVXG50K2V1B');
        $quantity_nexura_48kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 2.0kW') {
        array_push($part_numbers, 'FTXM20Q'); 
        $quantity_cora_20kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 2.5kW') {
        array_push($part_numbers, 'FTXM25Q');
        $quantity_cora_25kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 3.5kW') {
        array_push($part_numbers, 'FTXM35Q'); 
        $quantity_cora_35kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Cora 5.0kW') {
        array_push($part_numbers, 'FTXM50Q'); 
        $quantity_cora_50kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 2.0kW') {
        array_push($part_numbers, 'FTXM20U');
        $quantity_alira_20kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 2.5kW') {
        array_push($part_numbers, 'FTXM25U'); 
        $quantity_alira_25kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 3.5kW') {
        array_push($part_numbers, 'FTXM35U');
        $quantity_alira_35kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 4.6kW') {
        array_push($part_numbers, 'FTXM46U'); 
        $quantity_alira_46kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 5.0kW') {
        array_push($part_numbers, 'FTXM50U');
        $quantity_alira_50kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 6.0kW') {
        array_push($part_numbers, 'FTXM60U'); 
        $quantity_alira_60kw = $product['quantity'];
    } else if($product['typeOfProduct'] == 'Alira 7.1kW') {
        array_push($part_numbers, 'FTXM71U'); 
        $quantity_alira_71kw = $product['quantity'];
    };
    if($product['methodInstall'] == 'Standard Install') {
        $quantity_install_method = $quantity_install_method + $product['quantity'];
    };
    if($product['installWifi'] == 'Yes') {
        $quantity_install_wifi = $quantity_install_wifi + $product['quantity'];
    };
    if($product['typeBrick'] == 'Double Brick') {
        $quantity_type_brick = $quantity_type_brick + $product['quantity'];
    };
    if($product['heightAboveGround'] == 'Double Storey') {
        $quantity_heightAboveGround = $quantity_heightAboveGround + $product['quantity'];
    };
    if($product['outdoorUnitLocation'] == 'Low Wall Bracket') {
        $quantity_outdoorUnitLocation_low = $quantity_outdoorUnitLocation_low + $product['quantity'];
    } elseif($product['outdoorUnitLocation'] == 'High Wall Bracket') {
        $quantity_outdoorUnitLocation_high = $quantity_outdoorUnitLocation_high + $product['quantity'];
    }
    if($product['wifiInstall'] == 'Yes') {
        array_push($part_numbers,'BRP072C42');
    }
}
if($quantity_outdoorUnitLocation_low > 0) {
    array_push($part_numbers, 'DAIKIN_INSTALL_LOW_BRACK');
}
if($quantity_outdoorUnitLocation_high > 0) {
    array_push($part_numbers, 'DAIKIN_INSTALL_HIGH_BRACK');
}
if($quantity_heightAboveGround > 0) {
    array_push($part_numbers, 'DAIKIN_INSTALL_DOUBLE_S');
}
if($quantity_type_brick > 0) {
    array_push($part_numbers, 'DBW');
}
if($quantity_install_method > 0) {
    array_push($part_numbers, 'STANDARD_AC_INSTALL');
}
array_push($part_numbers,'DSI');

array_push($part_numbers, 'DAIKIN_MEL_METRO_DELIVERY');

$part_numbers_display_first = ['def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'];
$part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19'];
$array_id_part_numbers_not_use = ['1455e1e9-38a6-22e2-f898-57e8966e5256', '4e6ea564-761a-7482-76d0-582c0ca119e0'];

// Initialize quote
$quote = new AOS_Quotes();

// Create New Quote
$quote->name = 'GUEST '.(($quantityDAIKINUS7 > 0) ? 'DAIKIN US7 ('.$quantityDAIKINUS7.'X) ' : '').(($quantityDAIKINNEXURA > 0) ? ' DAIKIN NEXURA ('.$quantityDAIKINNEXURA.'X) ' : '').(($quantityDAIKINALIRA > 0) ? ' DAIKIN ALIRA ('.$quantityDAIKINALIRA.'X)' : '');
$quote->pre_install_photos_c = $_REQUEST['pre_install_photos_c'];

date_default_timezone_set("Australia/Melbourne");
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
        if(($row['part_number'] =='DSI') && !$is_use_number_1) {
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
            $product_line->product_qty = $quantity_install_method;
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
        } else if($row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK') {
            $product_line->product_qty = $quantity_outdoorUnitLocation_low;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK') {
            $product_line->product_qty = $quantity_outdoorUnitLocation_high;
            $product_line->product_list_price = $row['cost'];
        } else if($row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S') {
            $product_line->product_qty = $quantity_heightAboveGround;
            $product_line->product_list_price = $row['cost'];
        } else {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }

        $product_line->product_total_price = $product_line->product_list_price*$product_line->product_qty; 

        $price_include_admin += $product_line->product_total_price;

        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U') {
            $product_line->number = 2;
        } else if( $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY') {
            $product_line->number = 3;
        } else if( $row['part_number'] == 'BRP072A42') {
            $product_line->number = 4;
        } else if( $row['part_number'] == 'STANDARD_AC_INSTALL') {
            $product_line->number = 5;
        }
        else if( $row['part_number'] == 'DBW') {
            $product_line->number = 6;
        }
        if($row['part_number'] != 'DBW' && $row['part_number'] != 'DAIKIN_INSTALL_LOW_BRACK' && $row['part_number'] != 'DAIKIN_INSTALL_HIGH_BRACK' && $row['part_number'] != 'DAIKIN_INSTALL_DOUBLE_S') {
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
        
        //logic product quantity ,price
        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'STANDARD_AC_INSTALL' || $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY' || $row['part_number'] == 'BRP072A42' || $row['part_number'] == 'DBW' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S') {
            $product_line->product_list_price = 0;
            $product_line->product_cost_price = 0;
            $product_line->product_cost_price_usdollar = 0;
            $product_line->product_total_price = 0;
        } else {
            $product_line->product_qty = 1;
            $product_line->product_list_price = $row['cost'];
        }
        if($row['part_number'] != 'DBW' && $row['part_number'] != 'DAIKIN_INSTALL_LOW_BRACK' && $row['part_number'] != 'DAIKIN_INSTALL_HIGH_BRACK' && $row['part_number'] != 'DAIKIN_INSTALL_DOUBLE_S') {
            $product_line->save();
        }

        $total_amt += $product_line->product_total_price;
        $tax_amount += $product_line->vat_amt;

        if ($row['part_number'] == 'DSI') {
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
$price_include_admin_20 = round(($price_include_admin + $price_state) + ($price_include_admin + $price_state)*0.19, 2);
$gst = round($price_include_admin_20*0.1, 2);
$subTotal = $price_include_admin_20;
$old_group_total = round($gst + $price_include_admin_20, 2);
$group_tmp = explode('.', round($gst + $price_include_admin_20, 2));

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