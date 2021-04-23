<?php
global $sugar_config, $locale, $app_list_strings, $mod_strings;
$enable_groups = (int)$sugar_config['aos']['lineItems']['enableGroups'];
$total_tax = (int)$sugar_config['aos']['lineItems']['totalTax'];
//entrypoint 
$po_type = $_POST['po_type'];
$quote_id = $_POST['quote_id'];


if ($quote_id != '' && isset($quote_id)) {
    $focus = new AOS_Quotes();
    $focus->retrieve($quote_id);
    $html = '';
    if ($po_type == 'sanden_plumber' && $focus->id !='') {
        $db = DBManagerFactory::getInstance();
        //delete Old line_group
        $sql_delete_group = "   UPDATE aos_line_item_groups lig 
                                SET lig.deleted = 1 
                                WHERE lig.parent_include = 'AOS_Quotes' AND lig.po_type='sanden_plumber' AND lig.parent_id = '" . $focus->id . "' AND lig.deleted = 0";
        $res_gr = $db->query($sql_delete_group);                        
        //delete Old line_item
        $sql_delele_line = " UPDATE aos_products_quotes pg
                        LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id 
                        SET pg.deleted = 1
                        WHERE pg.parent_include = 'AOS_Quotes' AND pg.parent_id = '" . $focus->id . "' AND pg.deleted = 0";
        $res_line = $db->query($sql_delele_line);
        
        //Add CSS and JS
        $html .= '<script src="custom/modules/AOS_Quotes/plumber_line_item.js"></script>'
        .'<link rel="stylesheet" type="text/css" href="custom/modules/AOS_Quotes/lineItem_PO.css">' ;
        $html .= '<script language="javascript">var sig_digits = '.$locale->getPrecision().';';
        $html .= 'var module_sugar_grp1 = "'.$focus->module_dir.'";';
        $html .= 'var enable_groups = '.$enable_groups.';';
        $html .= 'var total_tax = '.$total_tax.';';
        $html .= '</script>';

        $html .= "<table border='0' cellspacing='4' id='plumber_lineItems'></table>";
        //Add button Add group
        if ($enable_groups) {
            $html .= "<div style='padding-top: 10px; padding-bottom:10px;'>";
            $html .= "<input type=\"button\" tabindex=\"116\" class=\"button\" value=\"Add Group\" id=\"plumber_addGroup\" onclick=\"plumber_insertGroup(0)\" />";
            $html .= "</div>";
        }
        $html .= '<input type="hidden" name="plumber_vathidden" id="plumber_vathidden" value="'.get_select_options_with_id($app_list_strings['vat_list'], '').'">
				  <input type="hidden" name="plumber_discounthidden" id="plumber_discounthidden" value="'.get_select_options_with_id($app_list_strings['discount_list'], '').'">';

        //Create default Sanden Plumber Installer       
        /**Create AOS_Line_Item_Groups */ 
            $row['id'] = "";
            $row['name'] = 'Sanden Install';
            $row['currency_id'] = '-99';
            $row['number'] = '1';
            $row['assigned_user_id'] = $focus->assigned_user_id;
            $row['parent_id'] = $focus->id;
            $row['parent_include'] = 'AOS_Quotes';
            $row['po_type'] = 'sanden_plumber';
        
            $group_po_plumber = new AOS_Line_Item_Groups();
            $group_po_plumber->populateFromRow($row);
            $group_po_plumber->save();
        /**Sanden Plumber Products - Default */
        $part_numners = array(
            "Sanden_Plb_Install_Std",
            "Sanden_Tank_Slab",
            "Sanden_HP_Pavers",
            "PB",
            "Photo_Upload_Bonus",
        );
        $part_numners_implode = implode("','", $part_numners);
        // $db = DBManagerFactory::getInstance();
    
        $sql = "SELECT * FROM aos_products WHERE deleted = 0 AND part_number IN ('".$part_numners_implode."')";
        $ret = $db->query($sql);
    
        $products = array();
        while ($row = $db->fetchByAssoc($ret))
        {
            $product = array();
            $product['product_currency'] = $row['currency_id'];
            $product['product_item_description'] = $row['description'];
            $product['product_name'] = $row['name'];
            $product['product_part_number'] = $row['part_number'];
            $product['product_product_cost_price'] = $row['cost'];
            $product['product_product_id'] = $row['id'];
            $product['product_product_list_price'] = $row['price'];
            $products[$product['product_part_number']] = $product;

        }
        $ordered_products = array();
        foreach($part_numners as $part_number){
            $ordered_products[$part_number] = $products[$part_number];
        }
        $return_product = array();
        foreach($ordered_products as $product){

            $return_product[] = $product;
        }

        $number_items = 1;
        $total_price = 0;
        foreach($return_product as $product){
            $row = array();
            $row['id'] = '';
            $row['parent_id'] = $focus->id;
            $row['parent_include'] = 'AOS_Quotes';
            $row['po_type'] = 'sanden_plumber';
            //Sanden Standard Plumbing Install
            $row['name'] = $product['product_name'];
            $row['assigned_user_id'] = $focus->assigned_user_id;
            $row['currency_id'] = -99;
            $row['part_number'] = $product['product_part_number'];
            $row['item_description'] = $product['product_item_description'];
            $row['number'] = $number_items;
            $row['product_qty'] = format_number(1);
            $row['product_cost_price'] = format_number($product['product_product_cost_price']);
            $row['product_list_price'] = format_number($product['product_product_cost_price']);
            $row['discount'] = "Percentage";
            $row['product_unit_price'] = format_number($product['product_product_cost_price']);
            $row['product_amt'] = 'vat_amt';
            $row['vat_amt'] = format_number($product['product_product_cost_price']/10);
            $row['product_total_price'] = format_number($product['product_product_cost_price']);
            $row['vat'] = "10.0";
            $row['group_id'] = $group_po_plumber->id;
            $row['product_id'] = $product['product_product_id'];
            $total_price += $product['product_product_cost_price'];
            $prod_invoice = new AOS_Products_Quotes();
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            $number_items ++;
            //plus to html
            $line_item = BeanFactory::newBean('AOS_Products_Quotes');
            $line_item->retrieve($prod_invoice->id, false);
            $line_item = json_encode($line_item->toArray());
            $group_item = 'null';
            if ($prod_invoice->group_id != null) {
                $group_item = BeanFactory::newBean('AOS_Line_Item_Groups');
                $group_item->retrieve($prod_invoice->group_id , false);
                $group_item = json_encode($group_item->toArray());
            }
            $html .= "<script>
            plumber_insertLineItems(" . $line_item . "," . $group_item . ");
            </script>";

        }
        $focus->plumber_total_amt = format_number($total_price);
        $focus->plumber_subtotal_amount = format_number($total_price);
        $focus->plumber_tax_amount = format_number($total_price/10);
        $focus->plumber_total_amount = format_number($total_price + $total_price/10);
        $focus->save();

        if (!$enable_groups) {
            $html .= '<script>plumber_insertGroup();</script>';
        }
    } 
    echo $html;
}