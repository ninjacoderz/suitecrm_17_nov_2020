<?php
/**
 * Products, Quotations & Invoices modules.
 * Extensions to SugarCRM
 * @package Advanced OpenSales for SugarCRM
 * @subpackage Products
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
 * @author SalesAgility Ltd <support@salesagility.com>
 */

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/AOS_Invoices/AOS_Invoices_sugar.php');
class AOS_Invoices extends AOS_Invoices_sugar
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_Invoices()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    public function save($check_notify = false)
    {
        global $sugar_config;

        if (empty($this->id) || $this->new_with_id
            || (isset($_POST['duplicateSave']) && $_POST['duplicateSave'] == 'true')) {
            if (isset($_POST['group_id'])) {
                unset($_POST['group_id']);
            }
            if (isset($_POST['product_id'])) {
                unset($_POST['product_id']);
            }
            if (isset($_POST['service_id'])) {
                unset($_POST['service_id']);
            }

            if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM aos_invoices");
            } else {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM aos_invoices");
            }

            if ($this->number < $sugar_config['aos']['invoices']['initialNumber']) {
                $this->number = $sugar_config['aos']['invoices']['initialNumber'];
            }
        }

        //update all stock null before save by invoice id

        if(!empty($this->id)){
            $db = DBManagerFactory::getInstance();
            $query_update = "UPDATE pe_stock_items_cstm SET aos_invoices_id_c = NULL WHERE aos_invoices_id_c ='".$this->id."'";
            
            $db->query($query_update);
        
            //save invoice id to stock item
            $serial_numbers = $_POST['product_serial_number'];
            $list_stock = array();
            $serial_arr = '';    
            if(count($serial_numbers) > 0){

                $list_stock = implode(",",$serial_numbers);
                $list_stock = explode(',',str_replace(' ','',$list_stock));
                $list_stock = array_unique($list_stock);
                $list_stock = array_filter(array_map('trim', $list_stock));
                $list_stock = implode("','",$list_stock);
                if($list_stock != ''){
                    $query_update_stock = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '$this->id' WHERE pe_stock_items.serial_number IN ('".$list_stock."')";
                    $db->query($query_update_stock);
                }

            }

            //thienpb update logic
            $sanden_tank_serial = $_POST['sanden_tank_serial_c'];
            if($sanden_tank_serial != '') {
                $sanden_tank = array();
                $sanden_tank = explode(',',$sanden_tank_serial);
                $sanden_tank = array_unique($sanden_tank);
                $sanden_tank = array_filter(array_map('trim', $sanden_tank));
                $sanden_tank = implode("','",$sanden_tank);
                $query_update_sanden_tank = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '$this->id' WHERE pe_stock_items.serial_number IN ('".$sanden_tank."')";
                $db->query($query_update_sanden_tank);
            }

            $sanden_hp_serial_c = $_POST['sanden_hp_serial_c'];
            if($sanden_hp_serial_c != '') {
                $sanden_hp = array();
                $sanden_hp = explode(',',$sanden_hp_serial_c);
                $sanden_hp = array_unique($sanden_hp);
                $sanden_hp = array_filter(array_map('trim', $sanden_hp));
                $sanden_hp = implode("','",$sanden_hp);
                    $query_update_sanden_hp = "UPDATE pe_stock_items_cstm INNER JOIN pe_stock_items ON pe_stock_items.id = pe_stock_items_cstm.id_c SET aos_invoices_id_c = '$this->id' WHERE pe_stock_items.serial_number IN ('".$sanden_hp."')";
                    $db->query($query_update_sanden_hp);
            }
        }

        require_once('modules/AOS_Products_Quotes/AOS_Utils.php');

        perform_aos_save($this);

        $return_id = parent::save($check_notify);

        require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');
        $productQuoteGroup = BeanFactory::newBean('AOS_Line_Item_Groups');
        $productQuoteGroup->save_groups($_POST, $this, 'group_');

        return $return_id;
    }

    public function mark_deleted($id)
    {
        $productQuote = BeanFactory::newBean('AOS_Products_Quotes');
        $productQuote->mark_lines_deleted($this);
        parent::mark_deleted($id);
    }
    // Thien
    function get_invoice_calls(){
        $where_sub_acc  ='';
        $where_sub_cont  ='';
        if($this->billing_account_id != '' && $this->billing_account_id !== null){
            $where_sub_acc .="(calls.parent_id = '".$this->billing_account_id."' AND calls.parent_type = 'Accounts')";
        }else{
            $where_sub_acc ='1';
        }
        if($this->billing_contact_id != '' && $this->billing_contact_id !== null){
            $where_sub_cont .="(calls.parent_id = '".$this->billing_contact_id."' AND calls.parent_type = 'Contacts')";
        }else{
            $where_sub_cont ='1';
        }

        if($where_sub_acc == '1' &&  $where_sub_cont =='1'){
            $where_sub .= '0';
        }else if($where_sub_acc == '1' ||  $where_sub_cont =='1'){
            $where_sub .= $where_sub_acc.' AND '.$where_sub_cont;
        }else{
            $where_sub .= $where_sub_acc.' OR '.$where_sub_cont;
        }

        $return_array['select']='SELECT calls.id ';
        $return_array['from']='FROM calls ';
        $return_array['where']=" WHERE (".$where_sub.")";
        $return_array['join'] = "";
        $return_array['join_tables'][0] = '';
        return $return_array;
		
    }

    //dung code - subpanel history colect emails
    function get_invoice_emails(){
        $where_sub_acc  ='';
        $where_sub_cont  ='';
        if($this->billing_account_id != '' && $this->billing_account_id !== null){
            $where_sub_acc .="(emails.parent_id = '".$this->billing_account_id."' AND emails.parent_type = 'Accounts')";
        }else{
            $where_sub_acc ='1';
        }
        if($this->billing_contact_id != '' && $this->billing_contact_id !== null){
            $where_sub_cont .="(emails.parent_id = '".$this->billing_contact_id."' AND emails.parent_type = 'Contacts')";
        }else{
            $where_sub_cont ='1';
        }

        if($where_sub_acc == '1' &&  $where_sub_cont =='1'){
            $where_sub .= '0';
        }else if($where_sub_acc == '1' ||  $where_sub_cont =='1'){
            $where_sub .= $where_sub_acc.' AND '.$where_sub_cont;
        }else{
            $where_sub .= $where_sub_acc.' OR '.$where_sub_cont;
        }

        $return_array['select']='SELECT emails.id ';
        $return_array['from']='FROM emails ';
        $return_array['where']=" WHERE (".$where_sub.")";
        $return_array['join'] = "";
        $return_array['join_tables'][0] = '';
        return $return_array;
        
    }

    //VUT-ADD COLUMN EMAIL OF CONTACT
    public function get_list_view_data()
    {
        $temp_array = parent::get_list_view_data();
        $custom_email_contact_link = "";
        if($this->billing_contact_id){
            $contact = new Contact();
            $contact->retrieve($this->billing_contact_id);
            $custom_email_contact_link = '<a class="email-link" href="javascript:void(0);" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-record-id="'.$contact->id.'" data-module-name="'.$contact->name.'" data-email-address="'.$contact->email1.'">'.$contact->email1.'</a>';
        }
        // add icon copy
        $html_copy_email = '';
        $addr = $contact->email1;
        if($addr){  
            $html_copy_email = <<<HTML
            <a class="copy-email-link" data-module-name="Contacts" data-email-address="{$addr}"
                   title="Copy {$addr}" onclick="$(document).copy_email_address(this);"
                style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip"
             >  &nbsp;<span class="glyphicon glyphicon-copy"></span>
             <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;top:-3px;left: 20px;">Copied {$addr}</span>
             </a>
           HTML;
        }
        $temp_array["CUSTOM_EMAIL_CONTACT_LINK"]= $custom_email_contact_link .$html_copy_email;
        return $temp_array;
    }
    
}
