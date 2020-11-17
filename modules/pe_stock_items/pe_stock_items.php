<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2017 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

require_once('modules/pe_stock_items/pe_stock_items_sugar.php');
require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');
///Users/nguyenbinh/Documents/Sites/PureElectric/suitecrm_3_3/modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php
class pe_stock_items extends pe_stock_items_sugar
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function pe_stock_items(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    function save_lines($post_data, $parent, $groups = array(), $key = '')
    {

        $line_count = isset($post_data[$key . 'name']) ? count($post_data[$key . 'name']) : 0;
        $j = 0;
        $item_deleted_check = 0;
        for ($i = 0; $i < $line_count; ++$i) {

            if (isset($post_data[$key . 'deleted'][$i]) && $post_data[$key . 'deleted'][$i] == 1) {
                $this->mark_deleted($post_data[$key . 'id'][$i]);
                $item_deleted_check++;
                if($item_deleted_check == $line_count){
                   $line_item_groups = new aos_line_item_groups();
                   $line_item_groups->retrieve($groups[0]);
                   $line_item_groups->deleted = 1;
                   $line_item_groups->save();
                }
            } else {
                $item_deleted_check--;
                if (!isset($post_data[$key . 'id'][$i])) {
                    LoggerManager::getLogger()->warn('Post date has no key id');
                    $postDataKeyIdI = null;
                } else {
                    $postDataKeyIdI = $post_data[$key . 'id'][$i];
                }
                // Special for serial number
                $serial_exist = false;
                if ( isset($post_data[$key . 'serial_number'][$i]) && $post_data[$key . 'serial_number'][$i] != "") {
                    // Query to get id
                    $db = DBManagerFactory::getInstance();
                    $sql = "SELECT * FROM pe_stock_items
                            WHERE 1=1 
                            AND deleted != 1
                            AND serial_number = '".$post_data[$key . 'serial_number'][$i]."' ";
            
                    $ret = $db->query($sql);
            
                    while ($row = $db->fetchByAssoc($ret)) {
                        //$sql = "DELETE FROM pe_stock_items WHERE id='".$row['id']."'";
                        //$ret = $db->query($sql);
                        //$sql = "DELETE FROM pe_stock_items WHERE id='".$row['id']."'";
                        //$ret = $db->query($sql);
                        
                        //pe_warehouse_pe_stock_items_1pe_stock_items_idb pe_warehouse_pe_stock_items_1_c
                        $postDataKeyIdI = $row['id'];
                        $serial_exist = true;
                        break;
                    }
                }

                $product_quote = BeanFactory::getBean('pe_stock_items', $postDataKeyIdI);
                if (!$product_quote) {
                    $product_quote = BeanFactory::newBean('pe_stock_items');
                }
                foreach ($this->field_defs as $field_def) {
                    $field_name = $field_def['name'];
                    if (isset($post_data[$key . $field_name][$i])) {
                        $product_quote->$field_name = $post_data[$key . $field_name][$i];
                    }
                }

                // and If it exist
                if($serial_exist) {
                    $product_quote->id = $postDataKeyIdI;
                }
                if (isset($post_data[$key . 'group_number'][$i])) {
                    
                    if(!isset($post_data[$key . 'group_number'][$i])) {
                        LoggerManager::getLogger()->warn('AOS Product Quotes error: Group number at post data key index is undefined in groups. Key and index was: ' . $key . ', ' . $i);
                        $groupIndex = null;
                    } else {
                        $groupIndex = $post_data[$key . 'group_number'][$i];
                    }
                    if(!isset($groups[$groupIndex])) {
                        LoggerManager::getLogger()->warn('AOS Product Quotes error: Group index was: ' . $groupIndex);
                        $product_quote->group_id = null;
                    } else {
                        $product_quote->group_id = $groups[$post_data[$key . 'group_number'][$i]];
                    }
                }
                if (trim($product_quote->product_id) != '' && trim($product_quote->name) != '' && trim($product_quote->product_unit_price) != '') {
                    // BinhNT Code here
                    if($product_quote->number == "") {
                        $product_quote->number = ++$j;
                    }
                    //$product_quote->number = ++$j;
                    $product_quote->assigned_user_id = $parent->assigned_user_id;
                    $product_quote->parent_id = $parent->id;
                    
                    if (!isset($parent->currency_id)) {
                        LoggerManager::getLogger()->warn('Paren Currency ID is not defined for AOD Product Quotes / save lines.');
                        $parentCurrencyId = null;
                    } else {
                        $parentCurrencyId = $parent->currency_id;
                    }
                    
                    $product_quote->currency_id = $parentCurrencyId;
                    $product_quote->parent_type = $parent->object_name;
                    $product_quote->save();

                    // BinhNT need to add relationship
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                     // get the warehouse id
                    if ($parent->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        $warehouse = $parent->pe_warehouse_log_pe_warehouse->getBeans();

                        if($parent->status_c == 'Proof of Delivery' && $parent->object_name == 'pe_warehouse_log'){
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $parent->destination_warehouse_id);
                            if($destination_wh != false){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }else{
                            if($warehouse != false){
                                $old_warehouse = $product_quote->pe_warehouse_pe_stock_items_1->getBeans();
                                $product_quote->pe_warehouse_pe_stock_items_1->delete($old_warehouse);
                                $product_quote->pe_warehouse_pe_stock_items_1->add($warehouse);
                                //$product_quote->save();
                            }else{
                                //$product_quote->pe_warehouse_pe_stock_items_1->delete($product_quote->pe_warehouse_pe_stock_items_1pe_warehouse_ida);
                            }
                        }
                    }
                    
                    $_POST[$key . 'id'][$i] = $product_quote->id;
                }
            }
        }
    }

    function save($check_notify = FALSE)
    {
        require_once('modules/pe_stock_items/pe_stockitems_utils.php');
        perform_pe_stockitems_save($this);
        parent::save($check_notify);
    }

    /**
     * @param $parent SugarBean
     */
    function mark_lines_deleted($parent)
    {

        require_once('modules/Relationships/Relationship.php');
        $product_quotes = $parent->get_linked_beans('pe_stock_items', $this->object_name);
        foreach ($product_quotes as $product_quote) {
            $product_quote->mark_deleted($product_quote->id);
        }
    }

    public function get_list_view_data()
    {
        $temp_array = parent::get_list_view_data();

        $db = DBManagerFactory::getInstance();

        $sql = "SELECT id, name, whlog_status, estimate_ship_date , actual_ship_date FROM pe_warehouse_log 
                WHERE 1=1 
                AND pe_warehouse_log.deleted  != 1
                AND pe_warehouse_log.id = '$this->parent_id'";

        $ret = $db->query($sql);

        while ($row = $db->fetchByAssoc($ret)) {
            $temp_array["PE_WAREHOUSE_LOG_STATUS"]= $row['whlog_status'];
            $temp_array["ESTIMATED_SHIPING_DATE"]= $row['estimate_ship_date'];
            $temp_array["ACTUAL_SHIPING_DATE"]= $row['actual_ship_date'];
            $temp_array["PE_WAREHOUSE_LOG_NAME"]= 
            '<a href="?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3Dpe_warehouse_log%26action%3DDetailView%26record%3D'.$this->parent_id .'" >' .$row['name'] .'</a>';
        }

        return $temp_array;
    }
	
}