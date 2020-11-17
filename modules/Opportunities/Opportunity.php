<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
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
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

// Opportunity is used to store customer information.
class Opportunity extends SugarBean
{
    public $field_name_map;
    // Stored fields
    public $id;
    public $lead_source;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $assigned_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $description;
    public $name;
    public $opportunity_type;
    public $amount;
    public $amount_usdollar;
    public $currency_id;
    public $date_closed;
    public $next_step;
    public $sales_stage;
    public $probability;
    public $campaign_id;

    // These are related
    public $account_name;
    public $account_id;
    public $contact_id;
    public $task_id;
    public $note_id;
    public $meeting_id;
    public $call_id;
    public $email_id;
    public $assigned_user_name;

    public $table_name = "opportunities";
    public $rel_account_table = "accounts_opportunities";
    public $rel_contact_table = "opportunities_contacts";
    public $module_dir = "Opportunities";

    public $importable = true;
    public $object_name = "Opportunity";

    public $number;
    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'account_name', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id'
    );

    public $relationship_fields = array('task_id'=>'tasks', 'note_id'=>'notes', 'account_id'=>'accounts',
                                    'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails', 'project_id'=>'project',
                                    // Bug 38529 & 40938
                                    'currency_id' => 'currencies',
                                    );

    public function __construct()
    {
        parent::__construct();
        global $sugar_config;
        if (!$sugar_config['require_accounts']) {
            unset($this->required_fields['account_name']);
        }
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function Opportunity()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public $new_schema = true;



    public function get_summary_text()
    {
        return (string)$this->name;
    }

    public function create_list_query($order_by, $where, $show_deleted = 0)
    {
        $custom_join = $this->getCustomJoin();
        $query = "SELECT ";

        $query .= "
                            accounts.id as account_id,
                            accounts.name as account_name,
                            accounts.assigned_user_id account_id_owner,
                            users.user_name as assigned_user_name ";
        $query .= $custom_join['select'];
        $query .= " ,opportunities.*
                            FROM opportunities ";


        $query .= 			"LEFT JOIN users
                            ON opportunities.assigned_user_id=users.id ";
        $query .= "LEFT JOIN $this->rel_account_table
                            ON opportunities.id=$this->rel_account_table.opportunity_id
                            LEFT JOIN accounts
                            ON $this->rel_account_table.account_id=accounts.id ";
        $query .= $custom_join['join'];
        $where_auto = '1=1';
        if ($show_deleted == 0) {
            $where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)
			AND opportunities.deleted=0";
        } else {
            if ($show_deleted == 1) {
                $where_auto = " opportunities.deleted=1";
            }
        }

        if ($where != "") {
            $query .= "where ($where) AND ".$where_auto;
        } else {
            $query .= "where ".$where_auto;
        }

        if ($order_by != "") {
            $query .= " ORDER BY $order_by";
        } else {
            $query .= " ORDER BY opportunities.name";
        }

        return $query;
    }


    public function create_export_query($order_by, $where, $relate_link_join='')
    {
        $custom_join = $this->getCustomJoin(true, true, $where);
        $custom_join['join'] .= $relate_link_join;
        $query = "SELECT
                                opportunities.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name ";
        $query .= $custom_join['select'];
        $query .= " FROM opportunities ";
        $query .= 				"LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id";
        $query .= " LEFT JOIN $this->rel_account_table
                                ON opportunities.id=$this->rel_account_table.opportunity_id
                                LEFT JOIN accounts
                                ON $this->rel_account_table.account_id=accounts.id ";
        $query .= $custom_join['join'];
        $where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)
			AND opportunities.deleted=0";

        if ($where != "") {
            $query .= "where $where AND ".$where_auto;
        } else {
            $query .= "where ".$where_auto;
        }

        if ($order_by != "") {
            $query .= " ORDER BY opportunities.$order_by";
        } else {
            $query .= " ORDER BY opportunities.name";
        }
        return $query;
    }

    public function fill_in_additional_list_fields()
    {
        if ($this->force_load_details == true) {
            $this->fill_in_additional_detail_fields();
        }
    }

    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();

        if (!empty($this->currency_id)) {
            $currency = BeanFactory::newBean('Currencies');
            $currency->retrieve($this->currency_id);
            if ($currency->id != $this->currency_id || $currency->deleted == 1) {
                $this->amount = $this->amount_usdollar;
                $this->currency_id = $currency->id;
            }
        }
        //get campaign name
        if (!empty($this->campaign_id)) {
            $camp = BeanFactory::newBean('Campaigns');
            $camp->retrieve($this->campaign_id);
            $this->campaign_name = $camp->name;
        }
        $this->account_name = '';
        $this->account_id = '';
        if (!empty($this->id)) {
            $ret_values=Opportunity::get_account_detail($this->id);
            if (!empty($ret_values)) {
                $this->account_name=$ret_values['name'];
                $this->account_id=$ret_values['id'];
                $this->account_id_owner =$ret_values['assigned_user_id'];
            }
        }
    }

	/**
     * Returns query to find the related calls created pre-5.1
     *
     * @return string SQL statement
     */
    public function get_opp_calls()
    {
		$query = "SELECT account_id FROM accounts_opportunities WHERE opportunity_id ='$this->id'";
		$result = $this->db->fetchByAssoc($this->db->query($query));

		$query_call = "SELECT calls.id FROM calls WHERE calls.parent_id = '".$result['account_id']."' AND calls.parent_type = 'Accounts' ";;
		$result_call = $this->db->fetchByAssoc($this->db->query($query_call));

        $return_array['select']='SELECT calls.id ';
        $return_array['from']='FROM calls ';
        $return_array['where']=" WHERE calls.parent_id = '".$result['account_id']."'
			AND calls.parent_type = 'Accounts' ";
        $return_array['join'] = "";
		$return_array['join_tables'][0] = '';
		
		if(!empty($result_call)){
			return $return_array;
		}else{
			$return_array['select']='SELECT calls.id ';
			$return_array['from']='FROM calls ';
			$return_array['where']=" WHERE leads.account_id = '".$result['account_id']."' ";
			$return_array['join'] = " INNER JOIN calls_leads ON (calls.id = calls_leads.call_id) INNER JOIN leads ON (leads.id = calls_leads.lead_id)";
			$return_array['join_tables'][0] = '';
			return $return_array;
		}


        
		}
    /** Returns a list of the associated contacts
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
    */
    public function get_contacts()
    {
        $this->load_relationship('contacts');
        $query_array=$this->contacts->getQuery(true);

        if (is_string($query_array)) {
            LoggerManager::getLogger()->warn("Illegal string offset 'select' (\$query_array) value id: $query_array");
        } else {
            //update the select clause in the retruned query.
            $query_array['select']="SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.email1, contacts.phone_work, opportunities_contacts.contact_role as opportunity_role, opportunities_contacts.id as opportunity_rel_id ";
        }

        $query='';
        foreach ((array)$query_array as $qstring) {
            $query.=' '.$qstring;
        }
        $temp = array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
        $contact = BeanFactory::newBean('Contacts');
        return $this->build_related_list2($query, $contact, $temp);
    }

        

    public function update_currency_id($fromid, $toid)
    {
        $idequals = '';

        $currency = BeanFactory::newBean('Currencies');
        $currency->retrieve($toid);
        foreach ($fromid as $f) {
            if (!empty($idequals)) {
                $idequals .= ' or ';
            }
            $fQuoted = $this->db->quote($f);
            $idequals .= "currency_id='$fQuoted'";
        }

        if (!empty($idequals)) {
            $query = "select amount, id from opportunities where (" . $idequals . ") and deleted=0 and opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost';";
            $result = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($result)) {
                $currencyIdQuoted = $this->db->quote($currency->id);
                $currencyConvertToDollarRowAmountQuoted = $this->db->quote($currency->convertToDollar($row['amount']));
                $rowIdQuoted = $this->db->quote($row['id']);
                $query = "update opportunities set currency_id='" . $currencyIdQuoted . "', amount_usdollar='" . $currencyConvertToDollarRowAmountQuoted . "' where id='" . $rowIdQuoted . "';";
                $this->db->query($query);
            }
        }
    }

    public function get_list_view_data()
    {
        global $locale, $current_language, $current_user, $mod_strings, $app_list_strings, $sugar_config;
        $app_strings = return_application_language($current_language);
        $params = array();

        $temp_array = $this->get_list_view_array();
        $temp_array['SALES_STAGE'] = empty($temp_array['SALES_STAGE']) ? '' : $temp_array['SALES_STAGE'];
        $temp_array["ENCODED_NAME"]=$this->name;
		// BinhNT Code Here
		$solar_info = $this->solargain_lead_number;
		$solar_info_array = explode(" Q", $this->solargain_lead_number);
		$solar_link = "";
		if(count($solar_info_array) >= 2){
			$solargain_lead_array = explode("L", $solar_info_array[0]);
			if(count($solargain_lead_array) >= 2 && $solargain_lead_array[1] != ""){
				$solar_link = "<a href='https://crm.solargain.com.au/lead/edit/".$solargain_lead_array[1]."' target='_blank'>L".$solargain_lead_array[1]."</a><br>";

			}
			if($solar_info_array[1] != ""){
				$solar_link .= "<a href='https://crm.solargain.com.au/quote/edit/".$solar_info_array[1]."' target='_blank'>Q".$solar_info_array[1]."</a>";

			}
		}

		
		$temp_array["SOLARGAIN_LINK"]= $solar_link;

		if($this->email_address){
			$search_link = "<a target='_blank' href='https://mail.google.com/#search/".urlencode($this->email_address)."'>GMSearch</a>";
			$send_gmail_link = "<a target='_blank' href='https://mail.google.com/?view=cm&fs=1&tf=1&to=".urlencode($this->email_address)."&su=".$this->name."'>SendGmail</a>";
			$send_pe_email = '<a class="email-link" href="javascript:void(0);" onclick="$(document).openComposeViewModal(this);" data-module="Accounts" data-record-id="'.$this->account_id.'" data-module-name="'.$this->account_name.'" data-email-address="'.$this->email_address.'">'.$this->email_address.'</a>';
			$custom_email_link = $search_link."<br>".$send_gmail_link.'<br>'.$send_pe_email;
		}
		
		$temp_array["CUSTOM_EMAIL_LINK"]= $custom_email_link;

		if($this->account_name){
			$custom_account_link = "<a target='_blank' href='https://suitecrm.pure-electric.com.au/index.php?module=Accounts&action=DetailView&record=".$this->account_id."'>".$this->account_name."</a>";
		}
		$temp_array['ACCOUNT_NAME'] = $custom_account_link;

		// Lead Name
		if($this->lead_id && $this->lead_name){
			$lead_link = "<a target='_blank' href='https://suitecrm.pure-electric.com.au/index.php?module=Leads&action=DetailView&record=".$this->lead_id."'>".$this->lead_name."</a>";
			$temp_array['LEAD_LINK'] = $lead_link;
		}
		if($this->quote_id && $this->quote_number){
			$quote_link = "<a target='_blank' href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=DetailView&record=".$this->quote_id."'>PQ".$this->quote_number."</a>";
			$temp_array['QUOTE_LINK'] = $quote_link;
		}
		if($this->lead_address){
			
			$gmaps_link  = '<a target="_blank" href="https://www.google.com/maps/place/' . $this->lead_address . '">Google Maps</a>';
			//$lat_long = $this->getLatLong($this->lead_address);
			//$street_views  = '<a target="_blank" href="http://maps.google.com/maps?q=&layer=c&cbll=' .$lat_long['lat']. ',' .$lat_long['lng']. '&cbp=11,0,0,0,0">Google Streetview</a>';
			$near_map  ='<a target="_blank" href="http://maps.nearmap.com?addr='. $this->lead_address . '&z=22&t=roadmap">Near Map</a>';
			$temp_array['MAP_LINK'] = $gmaps_link.'<br/>'.$near_map;//.$street_views.'<br/>';
		}

		if($this->sg_office){
			$solargain_address = array(
				"2"=>"Unit 7, 88 Dynon Road, West Melbourne VIC 3003",
				"14"=>"963/1002 Grand Junction Road, Holden Hill SA 5088",
				"0"=>"10 Milly Court, Malaga WA 6090",
				"1"=>"Unit 2, 7 Beale Way, Rockingham WA 6168",
				"3"=>"Unit 1, 5-7 Imboon Street, Deception Bay QLD 4508",
				"4"=>"21C Richmond Road, Homebush NSW 2140",
				"5"=>"244 Fitzgerald Street, Northam WA 6401",
				"6"=>"117 Lockyer Avenue, Albany WA 6330",
				"7"=>"Unit 2, 18 Bourke Street, Bunbury WA 6230",
				"8"=>"25 Wright Street, Busselton WA 6280",
				"9"=>"Lot 10 Reg Clarke Road, Geraldton WA 6530",
				"10"=>"23-49 Parfitt Road, Wangaratta VIC 3676",
				"11"=>"Shed 16B, 22 Walsh Road, Warrnambool VIC 3280",
				"12"=>"Unit 7, 8-10 Boat Harbour Drive, Pialba QLD 4655",
				"13"=>"14 Ipswich St, Fyshwick ACT 2609"
			);
			$temp_array["SOLARGAIN_OFFICE"] = $solargain_address[$this->sg_office];
		}
        return $temp_array;
    }
	// BinhNT 
	function getLatLong($address) {

		$array = array();
		$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
	 
		// We convert the JSON to an array
		$geo = json_decode($geo, true);
	 
		// If everything is cool
		if ($geo['status'] = 'OK') {
		   $latitude = $geo['results'][0]['geometry']['location']['lat'];
		   $longitude = $geo['results'][0]['geometry']['location']['lng'];
		   $array = array('lat'=> $latitude ,'lng'=>$longitude);
		}
	 
		return $array;
	}

    function get_currency_symbol(){
           if(isset($this->currency_id)){
               $cur_qry = "select * from currencies where id ='".$this->currency_id."'";
               $cur_res = $this->db->query($cur_qry);
               if(!empty($cur_res)){
                    $cur_row = $this->db->fetchByAssoc($cur_res);
                        if(isset($cur_row['symbol'])){
                         return $cur_row['symbol'];
                        }
               }
           }
           return '';
    }


    /**
    	builds a generic search based on the query string using or
    	do not include any $this-> because this is called on without having the class instantiated
    */
    public function build_generic_where_clause($the_query_string)
    {
        $where_clauses = array();
        $the_query_string = DBManagerFactory::getInstance()->quote($the_query_string);
        array_push($where_clauses, "opportunities.name like '$the_query_string%'");
        array_push($where_clauses, "accounts.name like '$the_query_string%'");

        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "") {
                $the_where .= " or ";
            }
            $the_where .= $clause;
        }


        return $the_where;
    }

    public function save($check_notify = false)
    {
        // Bug 32581 - Make sure the currency_id is set to something
	// BinhNT add Sugar Config
        global $current_user, $app_list_strings, $sugar_config;

        if (empty($this->currency_id)) {
            $this->currency_id = $current_user->getPreference('currency');
        }
        if (empty($this->currency_id)) {
            $this->currency_id = -99;
        }

        //if probablity isn't set, set it based on the sales stage
        if (!isset($this->probability) && !empty($this->sales_stage)) {
            $prob_arr = $app_list_strings['sales_probability_dom'];
            if (isset($prob_arr[$this->sales_stage])) {
                $this->probability = $prob_arr[$this->sales_stage];
            }
            //dung code
            if (empty($this->id) || $this->new_with_id){
                if($sugar_config['dbconfig']['db_type'] == 'mssql'){
                    $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM opportunities");
                } else {
                    $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM opportunities");
                }
            }
            //end dung code
            require_once('modules/Opportunities/SaveOverload.php');

            perform_save($this);
            return parent::save($check_notify);
        }
    }

    public function save_relationship_changes($is_update, $exclude = array())
    {
        //if account_id was replaced unlink the previous account_id.
        //this rel_fields_before_value is populated by sugarbean during the retrieve call.
        if (!empty($this->account_id) and !empty($this->rel_fields_before_value['account_id']) and
                (trim($this->account_id) != trim($this->rel_fields_before_value['account_id']))) {
            //unlink the old record.
            $this->load_relationship('accounts');
            $this->accounts->delete($this->id, $this->rel_fields_before_value['account_id']);
        }
        // Bug 38529 & 40938 - exclude currency_id
        parent::save_relationship_changes($is_update, array('currency_id'));

        if (!empty($this->contact_id)) {
            $this->set_opportunity_contact_relationship($this->contact_id);
        }
    }

    public function set_opportunity_contact_relationship($contact_id)
    {
        global $app_list_strings;
        $default = $app_list_strings['opportunity_relationship_type_default_key'];
        $this->load_relationship('contacts');
        $this->contacts->add($contact_id, array('contact_role'=>$default));
    }

    public function set_notification_body($xtpl, $oppty)
    {
        global $app_list_strings;

        $xtpl->assign("OPPORTUNITY_NAME", $oppty->name);
        $xtpl->assign("OPPORTUNITY_AMOUNT", $oppty->amount);
        $xtpl->assign("OPPORTUNITY_CLOSEDATE", $oppty->date_closed);
        $xtpl->assign("OPPORTUNITY_STAGE", (isset($oppty->sales_stage)?$app_list_strings['sales_stage_dom'][$oppty->sales_stage]:""));
        $xtpl->assign("OPPORTUNITY_DESCRIPTION", $oppty->description);

        return $xtpl;
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':return true;
        }
        return false;
    }
    public function listviewACLHelper()
    {
        $array_assign = parent::listviewACLHelper();
        $is_owner = false;
        $in_group = false; //SECURITY GROUPS
        if (!empty($this->account_id)) {
            if (!empty($this->account_id_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->account_id_owner;
            }
            /* BEGIN - SECURITY GROUPS */
            else {
                global $current_user;
                $parent_bean = BeanFactory::getBean('Accounts', $this->account_id);
                if ($parent_bean !== false) {
                    $is_owner = $current_user->id == $parent_bean->assigned_user_id;
                }
            }
            require_once("modules/SecurityGroups/SecurityGroup.php");
            $in_group = SecurityGroup::groupHasAccess('Accounts', $this->account_id, 'view');
            /* END - SECURITY GROUPS */
        }
        /* BEGIN - SECURITY GROUPS */
        /**
        if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner)){
        */
        if (!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner, 'module', $in_group)) {
            /* END - SECURITY GROUPS */
            $array_assign['ACCOUNT'] = 'a';
        } else {
            $array_assign['ACCOUNT'] = 'span';
        }

        return $array_assign;
    }

    /**
     * Static helper function for getting releated account info.
     */
    public function get_account_detail($opp_id)
    {
        $ret_array = array();
        $db = DBManagerFactory::getInstance();
        $query = "SELECT acc.id, acc.name, acc.assigned_user_id "
            . "FROM accounts acc, accounts_opportunities a_o "
            . "WHERE acc.id=a_o.account_id"
            . " AND a_o.opportunity_id='$opp_id'"
            . " AND a_o.deleted=0"
            . " AND acc.deleted=0";
        $result = $db->query($query, true, "Error filling in opportunity account details: ");
        $row = $db->fetchByAssoc($result);
        if ($row != null) {
            $ret_array['name'] = $row['name'];
            $ret_array['id'] = $row['id'];
            $ret_array['assigned_user_id'] = $row['assigned_user_id'];
        }
        return $ret_array;
    }
}
function getCurrencyType()
{
}
