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

require_once('modules/AOS_Quotes/AOS_Quotes_sugar.php');
class AOS_Quotes extends AOS_Quotes_sugar
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_Quotes()
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
        //dung code -- add relationship quote_lead for case manually
        if(isset($_REQUEST['aos_quotes_leads_1leads_idb'])){
            $lead_id = trim($_REQUEST['aos_quotes_leads_1leads_idb']);
            $this->load_relationship('aos_quotes_leads_2');
            $this->aos_quotes_leads_2->add($lead_id);
        }

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

            //VUT - S - Proposed plumber - Sanden
            if (isset($_POST['plumber_group_id'])) {
                unset($_POST['plumber_group_id']);
            }
            if (isset($_POST['plumber_product_id'])) {
                unset($_POST['plumber_product_id']);
            }
            //VUT - E - Proposed plumber - Sanden

            if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM aos_quotes");
            } else {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM aos_quotes");
            }

            if ($this->number < $sugar_config['aos']['quotes']['initialNumber']) {
                $this->number = $sugar_config['aos']['quotes']['initialNumber'];
            }
        }

        require_once('modules/AOS_Products_Quotes/AOS_Utils.php');

        perform_aos_save($this);

        $return_id = parent::save($check_notify);

        require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');
        $productQuoteGroup = BeanFactory::newBean('AOS_Line_Item_Groups');
        $productQuoteGroup->save_groups($_POST, $this, 'group_');

        //VUT - S - Proposed plumber - Sanden
        $quote_inputs = json_decode(html_entity_decode($this->quote_note_inputs_c), true);
        if ($quote_inputs["quote_plumbing_installation_by_pure"] == "Yes") {
            require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');
            $productQuoteGroup = BeanFactory::newBean('AOS_Line_Item_Groups');
            $productQuoteGroup->save_groups($_POST, $this, 'plumber_group_');
        }        
        //VUT - E - Proposed plumber - Sanden
        return $return_id;
    }

    public function mark_deleted($id)
    {
        $productQuote = BeanFactory::newBean('AOS_Products_Quotes');
        $productQuote->mark_lines_deleted($this);
        parent::mark_deleted($id);
    }
    function get_quote_calls()
    {
        $case = " (calls.parent_id = '$this->id' AND calls.parent_type = 'AOS_Quotes')";
        //get all email related with account
        if($this->billing_account_id != '' && $this->billing_account_id !== null){
            $case .=" OR (calls.parent_id = '$this->billing_account_id' AND calls.parent_type = 'Accounts') ";
        }
        //get all email related with contact
        if($this->billing_contact_id != '' && $this->billing_contact_id !== null){
            $case .=" OR (calls.parent_id = '$this->billing_contact_id' AND calls.parent_type = 'Contacts')";
        }
        //get all email related with lead
        if($this->leads_aos_quotes_1leads_ida != '' && $this->leads_aos_quotes_1leads_ida !== null){
            $case .=" OR (calls.parent_id = '$this->leads_aos_quotes_1leads_ida' AND calls.parent_type = 'Leads')";
        }

        $return_array['select']='SELECT calls.id ';
        $return_array['from']='FROM calls ';
        $return_array['where']= $case;
        $return_array['join'] = "";
        $return_array['join_tables'][0] = '';
        return $return_array;
    }

    //VUT-S- Thienpb update subpanel history collect emails from quote module
    function get_quote_emails(){
        $case = " (emails.parent_id = '$this->id' AND emails.parent_type = 'AOS_Quotes')";
        //get all email related with account
        if($this->billing_account_id != '' && $this->billing_account_id !== null){
            $case .=" OR (emails.parent_id = '$this->billing_account_id' AND emails.parent_type = 'Accounts') ";
        }
        //get all email related with contact
        if($this->billing_contact_id != '' && $this->billing_contact_id !== null){
            $case .=" OR (emails.parent_id = '$this->billing_contact_id' AND emails.parent_type = 'Contacts')";
        }
        //get all email related with lead
        if($this->leads_aos_quotes_1leads_ida != '' && $this->leads_aos_quotes_1leads_ida !== null){
            $case .=" OR (emails.parent_id = '$this->leads_aos_quotes_1leads_ida' AND emails.parent_type = 'Leads')";
        }

        $return_array['select']='SELECT emails.id ';
        $return_array['from']='FROM emails ';
        $return_array['where']= $case;
        $return_array['join'] =  "";
        $return_array['join_tables'][0] = '';
        return $return_array;
        
    }
    //VUT-E- subpanel history collect emails
    // tuan code 
    public function get_list_view_data()
    {
        $temp_array = parent::get_list_view_data();
        $custom_email_link = "";
		if($this->email1_c){
			// $search_link = "<a target='_blank' href='https://mail.google.com/#search/".urlencode($this->email1_c)."'>GMSearch</a>";
			// $send_gmail_link = "<a target='_blank' href='https://mail.google.com/?view=cm&fs=1&tf=1&to=".urlencode($this->email1_c)."&su=".$this->name."'>SendGmail</a>";
			$send_pe_email = '<a class="email-link" href="javascript:void(0);" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Quotes" data-record-id="'.$this->id.'" data-module-name="'.$this->name.'" data-email-address="'.$this->email1_c.'">'.$this->email1_c.'</a>';
			$custom_email_link = $send_pe_email;
		}

        $temp_array["CUSTOM_EMAIL_LINK"]= $custom_email_link;
        return $temp_array;
    }
}
