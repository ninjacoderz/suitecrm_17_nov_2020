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


class pe_warehouse_log extends Basic
{
    public $new_schema = true;
    public $module_dir = 'pe_warehouse_log';
    public $object_name = 'pe_warehouse_log';
    public $table_name = 'pe_warehouse_log';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

    //Thien code
    public function get_list_view_data()
    {
        global $locale, $current_language, $current_user, $mod_strings, $app_list_strings, $sugar_config;
        $app_strings = return_application_language($current_language);
        $params = array();

        $temp_array = $this->get_list_view_array();

        $WHLog =  new pe_warehouse_log();
        $WHLog =  $WHLog->retrieve($temp_array['ID']);

        $db = DBManagerFactory::getInstance();
        $sql_product = "SELECT * FROM pe_stock_items WHERE parent_type = 'pe_warehouse_log' AND deleted = 0 AND parent_id = '".$temp_array['ID']."' ORDER BY number";
        $result = $db->query($sql_product);
        $products = array();

        if($result->num_rows >0){
            while ($row = $db->fetchByAssoc($result))
            {
                $products[] = $row;
            }
        }
        $serial_number = '';
        for($i=0;$i<count($products);$i++){
            if(trim($products[$i]['serial_number']) !='' ){
                $serial_number .= $products[$i]['part_number'].'('.$products[$i]['serial_number'].')<br>';
            }
        }

        $temp_array["CUSTOM_SERIAL_NUMBER"] = $serial_number;
        return $temp_array;
    }

    function save($check_notify = FALSE){
        global $sugar_config;

        /*if (empty($this->id)  || $this->new_with_id){
            if(isset($_POST['group_id'])) unset($_POST['group_id']);
            if(isset($_POST['product_id'])) unset($_POST['product_id']);
            if(isset($_POST['service_id'])) unset($_POST['service_id']);

            if($sugar_config['dbconfig']['db_type'] == 'mssql'){
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM po_purchase_order");
            } else {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM po_purchase_order");
            }

            if($this->number < $sugar_config['po']['purchase_order']['initialNumber']){
                $this->number = $sugar_config['po']['purchase_order']['initialNumber'];
            }
        }*/

        //thienpb code -- add field number
        if (empty($this->id) || $this->new_with_id || empty($this->number)){
            if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM pe_warehouse_log");
            } else {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM pe_warehouse_log");
            }
        }

        require_once('modules/pe_stock_items/pe_stockitems_utils.php');

        perform_pe_stockitems_save($this);

        parent::save($check_notify);

        require_once('modules/pe_stock_items/PE_Stock_Item_Groups.php');
        $productPurchaseOrderGroup = new PE_Stock_Item_Groups();
        $productPurchaseOrderGroup->save_groups($_POST, $this, 'group_');
    }
	
}