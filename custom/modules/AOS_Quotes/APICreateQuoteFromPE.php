<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

//data request
$data_request = json_decode(trim($_REQUEST),true);

// check variable sanden
if($_REQUEST['product_choice'] == 'Sanden 315FQS') {
    $partNumber = 'GAUS-315FQS';
} elseif($_REQUEST['product_choice'] == 'Sanden 300FQS') {
    $partNumber = 'GAUS-300FQS';
} elseif($_REQUEST['product_choice'] == 'Sanden 250FQS') {
    $partNumber = 'GAUS-250FQS';
} elseif($_REQUEST['product_choice'] == 'Sanden 315FQV') {
    $partNumber = 'GAUS-315FQV';
} else {
    $partNumber = 'GAUS-160FQS';
}
$old_tank_fuel = '';
if($_REQUEST['type_device'] == 'Gas') {
    if($_REQUEST['gas_type'] == 'Gas Instant') {
        $old_tank_fuel = 'gas_instant';
    } elseif($_REQUEST['gas_type'] == 'Gas Storage') {
        $old_tank_fuel = 'gas_storage';
    }
} elseif($_REQUEST['type_device'] == 'Electric') {
    if($_REQUEST['electric_type'] == 'Electric Storage') {
        $old_tank_fuel = 'electric_storage';
    } elseif($_REQUEST['electric_type'] == 'Gravity Feed') {
        $old_tank_fuel = 'gravity_feed_electric';
    }
} elseif($_REQUEST['type_device'] == 'Solar') {
    $old_tank_fuel = 'solar';
} elseif($_REQUEST['type_device'] == 'Heat Pump') {
    $old_tank_fuel = 'heatpump';
} elseif($_REQUEST['type_device'] == 'LPG') {
    $old_tank_fuel = 'lpg';
} elseif($_REQUEST['type_device'] == 'Wood') {
    $old_tank_fuel = 'wood';
} elseif($_REQUEST['are_you_have_hws'] == 'New Build') {
    $old_tank_fuel = 'newBuilding';
} else {
    $old_tank_fuel = 'other';
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
$part_numbers_display_first = ['def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'];
$part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19'];
$array_id_part_numbers_not_use = ['1455e1e9-38a6-22e2-f898-57e8966e5256', '4e6ea564-761a-7482-76d0-582c0ca119e0'];

//get bean quote
$quote = new AOS_Quotes();

//Update Quote Suburb, Postcode, State
$quote->name = 'GUEST ' .$_REQUEST['suburb'] .' '.$_REQUEST['state'].' '.$_REQUEST['product_choice'];
$quote->pre_install_photos_c = $_REQUEST['pre_install_photos_c'];
date_default_timezone_set('UTC+11:00');
$dateQuote = new DateTime();
$quote->quote_date_c = date('Y-m-d H:i:s', time());
$quote->quote_type_c = 'quote_type_sanden';
$quote->install_address_postalcode_c = $_REQUEST['post_code'];
$quote->billing_address_postalcode = $_REQUEST['post_code'];
$quote->billing_address_state = $_REQUEST['state'];
$quote->install_address_state_c = $_REQUEST['state'];
$quote->billing_address_city = $_REQUEST['suburb'];
$quote->install_address_city_c = $_REQUEST['suburb'];
$quote->old_tank_fuel_c = $old_tank_fuel;
$quote->save();

//delele all line items
$db = DBManagerFactory::getInstance();
$sql_delele = "UPDATE aos_products_quotes pg
        LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id 
        SET pg.deleted = 1
        WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0";
$res = $db->query($sql_delele);


// Check VEEC
if($_REQUEST['state'] == 'VIC' && $_REQUEST['type_device'] == 'Electric') {
    if($_REQUEST['electric_type'] == 'Electric Storage' || $_REQUEST['electric_type'] == 'Gravity Feed') {
        $part_numbers = [$partNumber,'STC Rebate Certificate','VEEC Rebate Certificate'];
    } else {
        $part_numbers = [$partNumber,'STC Rebate Certificate'];
    }
} else {
    $part_numbers = [$partNumber,'STC Rebate Certificate'];
}

//create new items 
 
    //start logic get part number line items 
        //  if($_REQUEST['are_you_have_hws'] == 'New Build') {
        //     if($_REQUEST['new_build_type'] == 'Supply And Install Plumbing') {
        //         array_push($part_numbers,'Sanden_Plb_Install_Std');
        //         // if($data_request['if_supply_and_install_plumbing'] == 'Tempered Hot') {
        //         //     array_push($part_numbers,'tempered hot');
        //         // }
        //         // else{
        //         //     array_push($part_numbers,'tempered hot & untempered hot');
        //         // }
        //     }elseif($_REQUEST['new_build_type'] == 'Supply And Install Plumbing And Electrical') {
        //         array_push($part_numbers,'Sanden_Plb_Install_Std');
        //         array_push($part_numbers,'Sanden_Elec_Install_Std');
        //         // if($data_request['if_supply_and_install_plumbing_and_electrical'] == 'Tempered Hot') {
        //         //     array_push($part_numbers,'tempered hot');
        //         // }
        //         // else{
        //         //     array_push($part_numbers,'tempered hot & untempered hot');
        //         // }
        //     }
        //     else{
        //         // chua lam
        //     }
        // }else{
        //     //chua lam
        // }
        if($_REQUEST['are_you_have_hws'] == 'New Build') {
            if($_REQUEST['plumbing_installation'] == 'Yes') {
                array_push($part_numbers,'Sanden_Plb_Install_Std', 'Sanden_Tank_Slab', 'PizzaBase');
                
            }
            if($_REQUEST['electrical_installation'] == 'Yes') {
                array_push($part_numbers,'Sanden_Elec_Install_Std');
            }
        }else{
            if($_REQUEST['plumbing_installation'] == 'Yes') {
                array_push($part_numbers,'Sanden_Plb_Install_Std', 'Sanden_Tank_Slab', 'PizzaBase');
                
            }
            if($_REQUEST['electrical_installation'] == 'Yes') {
                array_push($part_numbers,'Sanden_Elec_Install_Std');
            }
        }
        if($_REQUEST['product_choice'] == 'Sanden 315FQV' || $_REQUEST['product_choice'] == 'Sanden 315FQS' || $_REQUEST['product_choice'] == 'Sanden 300FQS' || $_REQUEST['product_choice'] == 'Sanden 250FQS' || $_REQUEST['product_choice'] == 'Sanden 160FQS') {
            if($_REQUEST['quickie_type'] == '15mm Quick Connection Kit (QIK15)') {
                array_push($part_numbers,'QIK15−HPUMP');
            }elseif($_REQUEST['quickie_type'] == '20mm Quick Connection Kit (QIK25)') {
                array_push($part_numbers,'QIK25−HPUMP');
            }
        };
        if($_REQUEST['hot_water_rebate'] == 'Yes') {
            array_push($part_numbers,'SV_SHWR');
        };
        //
        if($_REQUEST['reticulated_gas'] == 'Yes') {
            array_push($part_numbers,'SA_REES');
        }elseif($_REQUEST['reticulated_gas'] == 'No') {
            if($_REQUEST['state'] == 'SA') {
                array_push($part_numbers,'SA_REES');
            }
        }
    // $quote_post_new_code = $quote->install_address_postalcode_c;
    $array_stc_veec = get_value_stc_veec($quote->install_address_postalcode_c,$partNumber);
    // var_dump($array_stc_veec);
    //end logic get part number line items 

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
    $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numbers_implode."') AND deleted = 0 GROUP BY part_number";
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
    $priceReticulated_gas = 0;
    if ($_REQUEST['state'] == 'WA' || $_REQUEST['state'] == 'TAS') {
        $price_state = 360;
    } elseif($_REQUEST['state'] == 'SA' || $_REQUEST['state'] == 'VIC' || $_REQUEST['state'] == 'ACT' || $_REQUEST['state'] == 'QLD') {
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
            $product_line->parent_id = $quote->id;;
            $product_line->parent_type = 'AOS_Quotes';
            $product_line->discount = 'Percentage';
            //display number index 
            if(in_array($row['id'],$part_numbers_display_first)) {
                $product_line->number = 1;
                $is_use_number_1 = true;
            }else {
                $index ++;
                $product_line->number = $index;
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
            }else{
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
            } else {
                $price_include_admin += $product_line->product_total_price;
            }
            
            $total_amt += $product_line->product_total_price;
            $tax_amount += $product_line->vat_amt;
            if($row['part_number'] != 'Sanden_Tank_Slab' && $row['part_number'] != 'PizzaBase') {
                $products_return[$row['part_number']] = array (
                    'Quantity' =>$product_line->product_qty,
                    'Product' =>  $product_line->name,
                    'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$product_line->item_description),
                    'List' =>  number_format($product_line->product_cost_price, 2),
                    'Sale_Price' => number_format($product_line->product_list_price, 2),
                    'Tax_Amount' => $product_line->vat_amt,
                    'Discount' => 0,
                    'Total' => $product_line->product_total_price
                );
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
            }elseif($row['part_number'] == 'GAUS-315FQS' || $row['part_number'] == 'GAUS-300FQS' || $row['part_number'] == 'GAUS-250FQS' || $row['part_number'] == 'GAUS-315FQV' || $row['part_number'] == 'GAUS-160FQS') {
                $product_line->product_qty = 1;
                $product_line->product_list_price = $row['cost'];
                $product_line->product_unit_price = $row['cost'];
            }else{
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

            if($row['part_number'] != 'Sanden_Tank_Slab' && $row['part_number'] != 'PizzaBase') {
                $product_line->save();
            }               
            
            if ($row['part_number'] == 'GAUS-315FQS' || $row['part_number'] == 'GAUS-300FQS' || $row['part_number'] == 'GAUS-250FQS' || $row['part_number'] == 'GAUS-315FQV' || $row['part_number'] == 'GAUS-160FQS') {
                $id_special = $product_line->id;
            }
        }
    }
    
    $discount_amount = 0;
    $price_include_admin_20 = round(($price_include_admin + $price_state) + ($price_include_admin + $price_state)*0.19,2);
    $gst = round($price_include_admin_20*0.1,2);
    $subTotal = $price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas;
    $old_group_total = round($gst + $price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas,2);
    $group_tmp = explode('.',round($gst + $price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas,2));
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
    $subTotal =  round($price_include_admin_20 + $price_veec_stc + $priceHotWater + $priceReticulated_gas,2);

    $groupTotal = $new_group_total; 


    $total_amount = $total_amt + $tax_amount;
    $subtotal_amount= $total_amt;

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
        $line_item->save();
    }


    //data return
    $data_return = array (
        'quote_id' => $quote->id,
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
            $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '807';
        
        
        
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
                    "Content-Type: application/json",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
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
