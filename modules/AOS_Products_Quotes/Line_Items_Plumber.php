<?php
/**
 * Advanced OpenSales, Advanced, robust set of sales modules.
 * @package Advanced OpenSales for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

function plumber_display_lines($focus, $field, $value, $view)
{
    global $sugar_config, $locale, $app_list_strings, $mod_strings;
    $enable_groups = (int)$sugar_config['aos']['lineItems']['enableGroups'];
    $total_tax = (int)$sugar_config['aos']['lineItems']['totalTax'];

    $html = '';

    if ($view == 'EditView') {

        if($focus->module_dir == "AOS_Quotes"){
            if ($focus->quote_type_c == 'quote_type_sanden') {
                $quote_note = json_decode(html_entity_decode($focus->quote_note_inputs_c));
                if ($quote_note->quote_plumbing_installation_by_pure == 'Yes') {
                    $html .= '<script src="custom/modules/AOS_Quotes/plumber_line_item.js"></script>'
                    .'<link rel="stylesheet" type="text/css" href="custom/modules/AOS_Quotes/lineItem_PO.css">' ;
                } else {
                    $db = DBManagerFactory::getInstance();
                    //delete Old line_group
                    $sql_delete_group = "   UPDATE aos_line_item_groups lig 
                                            SET lig.deleted = 1 
                                            WHERE lig.parent_include = '" . $focus->object_name . "' AND lig.po_type='sanden_plumber' AND lig.parent_id = '" . $focus->id . "' AND lig.deleted = 0";
                    $res_gr = $db->query($sql_delete_group);                        
                    //delete Old line_item
                    $sql_delele_line = " UPDATE aos_products_quotes pg
                                    SET pg.deleted = 1
                                    WHERE pg.parent_include = '" . $focus->object_name . "' AND pg.po_type='sanden_plumber' AND pg.parent_id = '" . $focus->id . "' AND pg.deleted = 0";
                    $res_line = $db->query($sql_delele_line);
                    //update group total when delete line item and line grp
                    $sql_delele_line = " UPDATE aos_quotes quote
                                    SET quote.plumber_total_amt = 0,
                                        quote.plumber_discount_amount = 0,
                                        quote.plumber_subtotal_amount = 0,
                                        quote.plumber_shipping_amount =0,
                                        quote.plumber_shipping_tax_amt =0,
                                        quote.plumber_tax_amount = 0,
                                        quote.plumber_total_amount = 0
                                    WHERE quote.id = '" . $focus->id . "' AND quote.deleted = 0";
                    $res_line = $db->query($sql_delele_line);
                    return $html;
                }
            } else {
                return $html;
            }
        }
        
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
        if ($focus->id != '') {
            require_once('modules/AOS_Products_Quotes/AOS_Products_Quotes.php');
            require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');

            $sql = "SELECT pg.id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_include = '" . $focus->object_name . "' AND pg.parent_id = '" . $focus->id . "' AND lig.po_type = 'sanden_plumber' AND pg.deleted = 0 AND lig.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";

            $result = $focus->db->query($sql);
            $html .= "<script>
                if(typeof sqs_objects == 'undefined'){var sqs_objects = new Array;}
                </script>";

            while ($row = $focus->db->fetchByAssoc($result)) {
                $line_item = BeanFactory::newBean('AOS_Products_Quotes');
                $line_item->retrieve($row['id'], false);
                $line_item = json_encode($line_item->toArray());

                $group_item = 'null';
                if ($row['group_id'] != null) {
                    $group_item = BeanFactory::newBean('AOS_Line_Item_Groups');
                    $group_item->retrieve($row['group_id'], false);
                    $group_item = json_encode($group_item->toArray());
                }
                $html .= "<script>
                    plumber_insertLineItems(" . $line_item . "," . $group_item . ");
                    </script>";
            }
        }
        if (!$enable_groups) {
            $html .= '<script>plumber_insertGroup();</script>';
        }
    } 
    return $html;
}


// //Bug #598
// //The original approach to trimming the characters was rtrim(rtrim(format_number($line_item->product_qty), '0'),$sep[1])
// //This however had the unwanted side-effect of turning 1000 (or 10 or 100) into 1 when the Currency Significant Digits
// //field was 0.
// //The approach below will strip off the fractional part if it is only zeroes (and in this case the decimal separator
// //will also be stripped off) The custom decimal separator is passed in to the function from the locale settings
// function stripDecimalPointsAndTrailingZeroes($inputString, $decimalSeparator)
// {
//     return preg_replace('/'.preg_quote($decimalSeparator).'[0]+$/', '', $inputString);
// }

// function get_discount_string($type, $amount, $params, $locale, $sep)
// {
//     if ($amount != '' && $amount != '0.00') {
//         if ($type == 'Amount') {
//             return currency_format_number($amount, $params)."</td>";
//         } elseif ($locale->getPrecision()) {
//             return rtrim(rtrim(format_number($amount), '0'), $sep[1])."%";
//         }
//         return format_number($amount)."%";
//     }
//     return "-";
// }

function plumber_display_shipping_vat($focus, $field, $value, $view)
{
    if ($view == 'EditView') {
        global $app_list_strings;

        if ($value != '') {
            $value = format_number($value);
        }

        $html = "<input id='plumber_shipping_tax_amt' type='text' tabindex='0' title='' value='".$value."' maxlength='26,6' size='22' name='plumber_shipping_tax_amt' onblur='plumber_calculateTotal(\"plumber_lineItems\");'>";
        $html .= "<select name='plumber_shipping_tax' id='plumber_shipping_tax' onchange='plumber_calculateTotal(\"plumber_lineItems\");' >".get_select_options_with_id($app_list_strings['vat_list'], (isset($focus->plumber_shipping_tax) ? $focus->plumber_shipping_tax : ''))."</select>";

        return $html;
    }
    return format_number($value);
}

function plumber_display_shipping_amount($focus, $field, $value, $view) 
{
    if ($view == 'EditView') {
        global $app_list_strings;

        if ($value != '') {
            $value = format_number($value);
        }

        $html = "<input id='plumber_shipping_amount' type='text' tabindex='0' title='' value='".$value."' maxlength='26,6' size='30' name='plumber_shipping_amount' onblur='plumber_calculateTotal(\"plumber_lineItems\");'>";

        return $html;
    }
    return format_number($value);
}
