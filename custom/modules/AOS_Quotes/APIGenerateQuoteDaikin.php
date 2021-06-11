<?php
// $dataItem = '{"installation_by_pure":"Yes","add_extra":"Yes","extraValue":1000,"products":[{"id":"691c9efa-36e2-494e-ba29-d2bce48bfb81","productId":"FTXZ25N","productName":"Daikin US7 2.5kW","qty":3,"isWifi":true,"installation":[{"question":"Installation by PureElectric?","answer":"No"},{"question":"Add Extra?","answer":"Yes"},{"question":"What type of construction are the walls of your house?","answer":"Brick Veneer"},{"question":"Standard Installation, Single Storey, Back to Back Ground, Existing Power Within 2m","answer":"Yes"},{"question":"What level is the INDOOR unit?","answer":"Single Storey"},{"question":"Is Indoor unit located on an EXTERNAL wall?","answer":"Yes"},{"question":"OUTDOOR unit location:","answer":"On Ground"},{"question":"Electrical - Use EXISTING circuit that is NOT Kitchen or NEW circuit?","answer":"EXISTING circuit that is NOT Kitchen"},{"question":"INDOOR unit vertically inline with the OUTDOOR unit?","answer":"Yes"},{"question":"INDOOR unit on the SAME wall as OUTDOOR unit?","answer":"Yes"}],"optionExtra":[{"id":"DAIKIN_INSTALL_DEDIC_CIRC","name":"Daikin Install - Dedicated Circuit","price":150,"itemise":true},{"id":"DAIKIN_INSTALL_DBL_BRICK","name":"Daikin Install - Double Brick","price":50,"itemise":true}],"hasInstallation":true},{"id":"37ba7fe0-0646-4397-b9d4-7376594d9c10","productId":"FTXZ35N","productName":"Daikin US7 3.5kW","qty":1,"isWifi":true,"installation":[{"question":"Installation by PureElectric?","answer":"No"},{"question":"Add Extra?","answer":"Yes"},{"question":"What type of construction are the walls of your house?","answer":"Double Brick"},{"question":"Standard Installation, Single Storey, Back to Back Ground, Existing Power Within 2m","answer":"No"},{"question":"What level is the INDOOR unit?","answer":"Double Storey"},{"question":"Is Indoor unit located on an EXTERNAL wall?","answer":"Yes"},{"question":"OUTDOOR unit location?","answer":"Low Wall Bracket"},{"question":"Electrical - Use EXISTING circuit that is NOT Kitchen or NEW circuit?","answer":"EXISTING circuit that is NOT Kitchen"},{"question":"Existing circuit RCD protected?","answer":"Yes"},{"question":"INDOOR unit vertically inline with the OUTDOOR unit?","answer":"Yes"},{"question":"INDOOR unit on the SAME wall as OUTDOOR unit?","answer":"Yes"}],"optionExtra":[{"id":"DAIKIN_INSTALL_DEDIC_CIRC","name":"Daikin Install - Dedicated Circuit","price":150,"itemise":true},{"id":"DAIKIN_INSTALL_DBL_BRICK","name":"Daikin Install - Double Brick","price":50,"itemise":true},{"id":"DAIKIN_INSTALL_DOUBLE_S","name":"Daikin Install - Double Storey","price":200,"itemise":true}],"hasInstallation":true},{"id":"2abce2a0-b475-42d5-9e14-5684392ec9ea","productId":"FTXZ50N","productName":"Daikin US7 5kW","qty":2,"isWifi":true,"installation":[{"question":"Installation by PureElectric?","answer":"No"},{"question":"Add Extra?","answer":"Yes"},{"question":"What type of construction are the walls of your house?","answer":"Weatherboard"},{"question":"Standard Installation, Single Storey, Back to Back Ground, Existing Power Within 2m","answer":"No"},{"question":"What level is the INDOOR unit?","answer":"Double Storey"},{"question":"Is Indoor unit located on an EXTERNAL wall?","answer":"Yes"},{"question":"OUTDOOR unit location?","answer":"On Ground"},{"question":"Electrical - Use EXISTING circuit that is NOT Kitchen or NEW circuit?","answer":"EXISTING circuit that is NOT Kitchen"},{"question":"Existing circuit RCD protected?","answer":"Yes"},{"question":"INDOOR unit vertically inline with the OUTDOOR unit?","answer":"Yes"},{"question":"INDOOR unit on the SAME wall as OUTDOOR unit?","answer":"Yes"}],"optionExtra":[{"id":"DAIKIN_INSTALL_DEDIC_CIRC","name":"Daikin Install - Dedicated Circuit","price":150,"itemise":true},{"id":"DAIKIN_INSTALL_DOUBLE_S","name":"Daikin Install - Double Storey","price":200,"itemise":true}],"hasInstallation":true}]}';
// $data = json_decode($dataItem, true);
$data = $_REQUEST['dataDaikin'];
// Defind PartNumber List
$partNumber         = array();
$extraListItemise   = array();
$numberUS7          = 0;
$numberNexura       = 0;
$numberAlira        = 0;
$is_use_number_1    = false;

$products_return    = [];
$extraList          = ['DAIKIN_INSTALL_DEDIC_CIRC','DAIKIN_INSTALL_DBL_BRICK','DAIKIN_INSTALL_DOUBLE_S','Daikin_INSTALL_RCD_UPGRAD','DAIKIN_INSTALL_HIGH_BRACK','DAIKIN_INSTALL_INTERNWALL','DAIKIN_INSTALL_LONG_PIPE','DAIKIN_INSTALL_LOW_BRACK','DAIKIN_INSTALL_ROOFCAVITY','DI-Roof','DAIKIN_INSTALL_SUB_FLOOR','DAIKIN_INSTALL_WALL_CUT','DIFFICUL_INSTALL','DI_CondensatePump','Travel','DRI'];
                        
                      
////  Param Calculator

$totalAmount        = 0;
$subTotalAmount     = 0;
$discountAmount     = 0;
$gstAmount          = 0;
$stateFee           = 0;
$adminFee           = 0.19;
$priceTmp           = 0;
$id_special         = '';

$extraNoInstallation = 0;
if($data['add_extra'] == 'Yes') {
    $extraNoInstallation = $data['extraValue'];
}

// Import Product Item to PartNumber List
$dataProducts = $data['products'];

if($data['installation_by_pure'] == 'No') {
    $partNumber['DS'] = 1;
    foreach($dataProducts as $product) {
        $partNumber[$product['productId']] = (int)$product['qty'];
        if($product['isWifi'] ==  true && array_key_exists('BRP072C42', $partNumber)) {
            $partNumber['BRP072C42'] = $partNumber['BRP072C42'] + (int)$product['qty'];
        } else {
            $partNumber['BRP072C42'] = (int)$product['qty'];
        }
        if (strpos($product['productName'], 'US7') !== false) {
            $numberUS7 = (int)$product['qty'];
        } else if(strpos($product['productName'], 'Nexura') !== false) {
            $numberNexura = (int)$product['qty'];
        } else if(strpos($product['productName'], 'Nexura') !== false) {
            $numberAlira = (int)$product['qty'];
        }
    }
} else {
    $partNumber['STANDARD_AC_INSTALL'] = 1;
    $partNumber['DSI'] = 1;
    foreach($dataProducts as $product) {
        $partNumber[$product['productId']] = (int)$product['qty'];
        if($product['isWifi'] ==  true && array_key_exists('BRP072C42', $partNumber)) {
            $partNumber['BRP072C42'] = $partNumber['BRP072C42'] + (int)$product['qty'];
        } else {
            $partNumber['BRP072C42'] = (int)$product['qty'];
        }
        if (strpos($product['productName'], 'US7') !== false) {
            $numberUS7 = (int)$product['qty'];
        } else if(strpos($product['productName'], 'Nexura') !== false) {
            $numberNexura = (int)$product['qty'];
        } else if(strpos($product['productName'], 'Nexura') !== false) {
            $numberAlira = (int)$product['qty'];
        }
        // $partNumber += [ $product['productId'] => $product['qty'] ];
        foreach($product['optionExtra'] as $extraItem) {
            if (array_key_exists($extraItem['id'], $partNumber)) {
                $partNumber[$extraItem['id']] = $partNumber[$extraItem['id']] + ((int)$extraItem['price']*(int)$product['qty']);
            } else {
                $partNumber[$extraItem['id']] = (int)$extraItem['price']*(int)$product['qty'];
            };
            //// Add Itemise Array 
            if (!in_array($extraItem['id'], $extraListItemise)) {
                array_push($extraListItemise, $extraItem['id']);
            }
        }
    }
}

// var_dump($partNumber); die;

//// Apply Other Extra / OTHER  -- PARTNUMBER
$partNumber['DAIKIN_MEL_METRO_DELIVERY'] = 1;

//// List Id Use / Not Use / Priority / Index

$part_numbers_display_first = ['def49e57-d3c8-b2f4-ad0e-5c7f51e1eb15'];
$part_numbers_tax_0 = ['4efbea92-c52f-d147-3308-569776823b19'];
$array_id_part_numbers_not_use = ['1455e1e9-38a6-22e2-f898-57e8966e5256', '4e6ea564-761a-7482-76d0-582c0ca119e0'];

//// End

//// CREATE NEW QUOTE
//// INIT
$quote = new AOS_Quotes();
$quote->name = 'GUEST '.(($numberUS7 > 0) ? 'DAIKIN US7 ('.$numberUS7.'X) ' : '').(($numberNexura > 0) ? ' DAIKIN NEXURA ('.$numberNexura.'X) ' : '').(($numberAlira > 0) ? ' DAIKIN ALIRA ('.$numberAlira.'X)' : '');

date_default_timezone_set('UTC');
$dateQuote = new DateTime();
$quote->quote_date_c = date('Y-m-d H:i:s', time());
$dateAction = new DateTime('+7 day');
$quote->next_action_date_c = $dateAction->format('Y-m-d');
$quote->quote_type_c = 'quote_type_daikin';
$quote->stage = 'Guest';
$quote->save();

//// DELETE LINE ITEM
$db = DBManagerFactory::getInstance();
$sql_delele = "UPDATE aos_products_quotes pg
        LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id 
        SET pg.deleted = 1
        WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0";
$res = $db->query($sql_delele);

//// CREATE NEW PRODUCT
$part_numbers_implode = array();
foreach($partNumber as $key => $value) {
    array_push($part_numbers_implode, $key);
}
$part_numbers_implode = implode("','", $part_numbers_implode);
$db = DBManagerFactory::getInstance();
$sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numbers_implode."') AND deleted = 0 ORDER BY price ASC";
$ret = $db->query($sql);

$product_quote_group = new AOS_Line_Item_Groups();
$product_quote_group->name = 'Daikin';//$map_quote_type[$quote->quote_type_c];
$product_quote_group->created_by = $quote->assigned_user_id;
$product_quote_group->assigned_user_id = $quote->assigned_user_id;
$product_quote_group->parent_type = 'AOS_Quotes';
$product_quote_group->parent_id = $quote->id;
$product_quote_group->number = '1';
$product_quote_group->currency_id = '-99';
$product_quote_group->save();

//// Add Item
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

        if(($row['part_number'] =='DSI' || $row['part_number'] =='DS') && !$is_use_number_1) {
            $product_line->number = 1;
            $is_use_number_1 = true;
        }

        foreach( $partNumber as $key => $value) {
            if ($row['part_number'] == $key) {
                if(in_array($row['part_number'], $extraList)) {
                    $product_line->product_qty = 1;
                    $product_line->product_list_price = $value;
                } else {
                    $product_line->product_qty = $value;
                    $product_line->product_list_price = $row['cost'];
                }
                
            }
        }

        $product_line->product_total_price = $product_line->product_list_price*$product_line->product_qty; 
        $priceTmp += $product_line->product_total_price;

        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U') {
            $product_line->number = 2;
        } else if( $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY') {
            $product_line->number = 3;
        } else if( $row['part_number'] == 'BRP072C42') {
            $product_line->number = 4;
        } else if( $row['part_number'] == 'STANDARD_AC_INSTALL' || $row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'DI_CondensatePump' || $row['part_number'] == 'DRI' || $row['part_number'] == 'Travel') {
            $product_line->number = 5;
        }

        //logic product quantity ,price
        if ($row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'FTXZ35N' || $row['part_number'] == 'FTXZ50N' || $row['part_number'] == 'FVXG25K2V1B' || $row['part_number'] == 'FVXG35K2V1B' || $row['part_number'] == 'FVXG50K2V1B' || $row['part_number'] == 'FTXM20Q' || $row['part_number'] == 'FTXM25Q' || $row['part_number'] == 'FTXM35Q' || $row['part_number'] == 'FTXM50Q' || $row['part_number'] == 'STANDARD_AC_INSTALL' || $row['part_number'] == 'DAIKIN_MEL_METRO_DELIVERY' || $row['part_number'] == 'BRP072C42' || $row['part_number'] == 'DBW' || $row['part_number'] == 'FTXM20U' || $row['part_number'] == 'FTXM25U' || $row['part_number'] == 'FTXM35U' || $row['part_number'] == 'FTXM46U' || $row['part_number'] == 'FTXM50U' || $row['part_number'] == 'FTXM60U' || $row['part_number'] == 'FTXM71U' || $row['part_number'] == 'DIFFICUL_INSTALL'  || $row['part_number'] == 'DAIKIN_INSTALL_DEDIC_CIRC' || $row['part_number'] == 'DAIKIN_INSTALL_DBL_BRICK' || $row['part_number'] == 'DAIKIN_INSTALL_DOUBLE_S' || $row['part_number'] == 'Daikin_INSTALL_RCD_UPGRAD' || $row['part_number'] == 'DAIKIN_INSTALL_HIGH_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_INTERNWALL' || $row['part_number'] == 'DAIKIN_INSTALL_LOW_BRACK' || $row['part_number'] == 'DAIKIN_INSTALL_LONG_PIPE' || $row['part_number'] == 'DAIKIN_INSTALL_ROOFCAVITY' || $row['part_number'] == 'DI-Roof' || $row['part_number'] == 'DAIKIN_INSTALL_SUB_FLOOR' || $row['part_number'] == 'DAIKIN_INSTALL_WALL_CUT' || $row['part_number'] == 'FTXZ25N' || $row['part_number'] == 'DI_CondensatePump') {
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

        if(in_array($row['part_number'], $extraList)) {
            if(in_array($row['part_number'], $extraListItemise)) {
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
                $product_line->save();
            }
        } else {
            $product_line->save();
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

        $totalAmount += $product_line->product_total_price;
        $gstAmount += $product_line->vat_amt;

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

$totalAdminFee = ($priceTmp+$extraNoInstallation) + (($priceTmp+$extraNoInstallation)*$adminFee);
$gstAmount = $totalAdminFee*0.1;
$group_total_tmp = $gstAmount + $totalAdminFee;
$group_tmp = explode('.', round($group_total_tmp, 2));

//// Go Seek

$new_group_total = '';
$discount_amount = 0;

if(substr($group_tmp[0], -2) <= '90') {
    $arr_str = explode('.', $group_tmp[0]);
    $gprice= substr($arr_str[0], 0, strlen($arr_str[0]) - 2) . '90';
    $new_group_total = $gprice.'.00';
} else {
    $arr_str = explode('.', $group_tmp[0]);
    $gprice = substr($arr_str[0], 0, strlen($arr_str[0]) - 2) . '90';
    $new_group_total = $gprice.'.00';
}

$different = $group_total_tmp - $new_group_total;
$delta = $different/1.1; 
$totalAdminFee = round($totalAdminFee - $delta,2);
$gstAmount = round($gstAmount - ($different - $delta), 2);

$subTotal =  round($totalAdminFee,2);
$groupTotal = $new_group_total; 

$quote->total_amt = round($subTotal , 2);
$quote->subtotal_amount = round($subTotal , 2);
$quote->discount_amount = round($discount_amount , 2);
$quote->tax_amount = round($gstAmount , 2);
$quote->total_amount = round($groupTotal , 2);
$quote->save();

$product_quote_group->total_amt = round($subTotal , 2);
$product_quote_group->tax_amount = round($gstAmount , 2);
$product_quote_group->total_amount = round($groupTotal , 2);
$product_quote_group->subtotal_amount = round($subTotal , 2);
$product_quote_group->save();

if($id_special) {
    $line_item = new AOS_Products_Quotes();
    $line_item->retrieve($id_special);
    $line_item->product_cost_price = $totalAdminFee;
    $line_item->product_list_price = $totalAdminFee;
    $line_item->product_unit_price = $totalAdminFee;
    $line_item->product_total_price = $totalAdminFee;
    $line_item->vat = '10.0';
    $line_item->save();
}

$data_return = array (
    'quote_id' => $quote->id,
    'products' => $products_return,
    'Product_include_admin' => number_format($totalAdminFee, 2),
    'groupProducts' => array(
        'Group_Name' => $product_quote_group->name,
        'Total' => number_format($product_quote_group->total_amount, 2),
        'Discount' => 0,
        'Subtotal' => number_format($product_quote_group->subtotal_amount, 2),
        'GST' =>  number_format($product_quote_group->tax_amount, 2),
        'Group_Total' => number_format($product_quote_group->total_amount, 2),
    )
);

echo json_encode($data_return);

?>