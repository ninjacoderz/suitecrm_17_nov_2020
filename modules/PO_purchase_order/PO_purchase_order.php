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


class PO_purchase_order extends Basic
{
    public $new_schema = true;
    public $module_dir = 'PO_purchase_order';
    public $object_name = 'PO_purchase_order';
    public $table_name = 'po_purchase_order';
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
    public $currency_id = -99;
    public $number;

    var $billing_address_city;
    var $billing_account_id;
	var $billing_account;
    var $billing_contact_id;
    var $billing_contact;
    var $billing_address_street;
    var $purchase_invoice_xero;
    var $billing_address_postalcode;
    var $shipping_address_street;
    
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
    
    function fill_in_additional_list_fields()
	{
		parent::fill_in_additional_list_fields();
    }
    
    function fill_in_additional_detail_fields()
	{
		//Fill in the assigned_user_name
		//if(!empty($this->status))
		//$this->status = translate('lead_status_dom', '', $this->status);
        parent::fill_in_additional_detail_fields();
        if(isset($_REQUEST["target_action"]) && $_REQUEST["target_action"]=="QuickCreate") {
            $this->name = $_REQUEST["parent_name"]; 
            //parent_type
            $invoice = BeanFactory::getBean("AOS_Invoices", $_REQUEST['parent_id']);
            if(isset($invoice->id) && $invoice->id != ""){
                $quote_numer = $invoice->quote_number;
                
                $db = DBManagerFactory::getInstance();
                
                $sql = "SELECT * FROM aos_quotes WHERE 1=1 ";
                $sql .= " AND number = '" . $quote_numer . "'";
                $sql .= " AND deleted != 1 ";
                $ret = $db->query($sql);

                while ($row = $db->fetchByAssoc($ret)) {
                    if (isset($row) && $row != null) {
                        $quote_name = $row["name"];
                        $quote_id = $row["id"];
                    }
                }
            }
            $this->aos_quotes_po_purchase_order_1_name = $quote_name;
            $this->aos_quotes_po_purchase_order_1aos_quotes_ida = $quote_id;
            $this->shipping_account = $invoice->billing_account;
            $this->shipping_account_id = $invoice->billing_account_id;
            $this->billing_account = $invoice->plumber_c;
            $this->billing_account_id = $invoice->account_id1_c;

            $this->shipping_address_street = $invoice->install_address_c;
            $this->shipping_address_city = $invoice->install_address_city_c;
            $this->shipping_address_state = $invoice->install_address_state_c;
            $this->shipping_address_postalcode = $invoice->install_address_postalcode_c;
            
             // Supplier address 
            $supplier =  BeanFactory::getBean("Accounts", $invoice->account_id1_c);
            if($supplier->id){
                $this->billing_address_street = $supplier->billing_address_street;
                $this->billing_address_city = $supplier->billing_address_city;
                $this->billing_address_postalcode = $supplier->billing_address_postalcode;
                $this->billing_address_state = $supplier->billing_address_state;
            }
        }
    }

    function save($check_notify = FALSE){
        global $sugar_config;

        if (empty($this->id)  || $this->new_with_id){
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
        }

        require_once('modules/PO_purchase_order/PO_Utils.php');

        perform_po_save($this);

		parent::save($check_notify);

		require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');
		$productPurchaseOrderGroup = new AOS_Line_Item_Groups();
		$productPurchaseOrderGroup->save_groups($_POST, $this, 'group_');
	}
}