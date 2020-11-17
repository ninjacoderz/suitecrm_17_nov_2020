<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 ********************************************************************************/

require_once('service/v4_1/SugarWebServiceUtilv4_1.php');

function Qcmp_beans($a, $b)
{
    global $sugar_web_service_order_by;
    //If the order_by field is not valid, return 0;
    if (empty($sugar_web_service_order_by) || !isset($a->$sugar_web_service_order_by) || !isset($b->$sugar_web_service_order_by)){
        return 0;
    }
    if (is_object($a->$sugar_web_service_order_by) || is_object($b->$sugar_web_service_order_by)
        || is_array($a->$sugar_web_service_order_by) || is_array($b->$sugar_web_service_order_by))
    {
        return 0;
    }
	if (is_string($a->$sugar_web_service_order_by)){
		if (strtoupper($a->$sugar_web_service_order_by) == strtoupper($b->$sugar_web_service_order_by)){
			return 0;
		}
		if (strtoupper($a->$sugar_web_service_order_by) < strtoupper($b->$sugar_web_service_order_by))
		{
			return -1;
		} else {
			return 1;
		}
	}
	else {
		if ($a->$sugar_web_service_order_by == $b->$sugar_web_service_order_by) return 0;
		if ($a->$sugar_web_service_order_by < $b->$sugar_web_service_order_by)
		{
			return -1;
		} else {
			return 1;
		}
	}
}

function Qorder_beans($beans, $field_name)
{
    //Since php 5.2 doesn't include closures, we must use a global to pass the order field to cmp_beans.
    global $sugar_web_service_order_by;
    $sugar_web_service_order_by = $field_name;
    usort($beans, "Qcmp_beans");
    return $beans;
}


class SugarWebServiceUtilquickcrm extends SugarWebServiceUtilv4_1
{
	function use_std_search($module_name){
        global $sugar_config;
        
        return ($sugar_config['dbconfig']['db_type'] =='mssql' || (isset($sugar_config['quickcrm_norelatesearch']) && ($sugar_config['quickcrm_norelatesearch']==true || (is_array($sugar_config['quickcrm_norelatesearch']) && (in_array($module_name,$sugar_config['quickcrm_norelatesearch']))))));
	}
	
	function get_name_value_list_for_fields($value, $fields) {
		// add support for function fields (currently aop_case_updates_threaded and reschedule_history only)
		$GLOBALS['log']->info('Begin: SoapHelperWebServices->get_name_value_list_for_fields');
		global $app_list_strings;
		global $sugar_config;
		global $invalid_contact_fields;

		$list = array();
		if(!empty($value->field_defs)){
			if(empty($fields))$fields = array_keys($value->field_defs);
			if(isset($value->assigned_user_name) && in_array('assigned_user_name', $fields)) {
				$list['assigned_user_name'] = $this->get_name_value('assigned_user_name', $value->assigned_user_name);
			}
			if(isset($value->modified_by_name) && in_array('modified_by_name', $fields)) {
				$list['modified_by_name'] = $this->get_name_value('modified_by_name', $value->modified_by_name);
			}
			if(isset($value->created_by_name) && in_array('created_by_name', $fields)) {
				$list['created_by_name'] = $this->get_name_value('created_by_name', $value->created_by_name);
			}

			$filterFields = $this->filter_fields($value, $fields);


			foreach($filterFields as $field){
				if ($field == 'edit_access' || $field == 'delete_access'){
					$list[$field] = $this->get_name_value($field, $value->$field);
					continue;
				}
				if (!isset($value->field_defs[$field])){ // avoid warning for new_with_id
					continue;
				}
				$var = $value->field_defs[$field];
				if(isset($value->{$var['name']})){
					$val = $value->{$var['name']};
					$type = $var['type'];

					if(strcmp($type, 'date') == 0){
						$val = substr($val, 0, 10);
					}
					
					if (isset($var['function']) && !empty($var['function']['returns']) && isset($var['function']['include']) && $var['function']['returns'] == 'html')
						{
							$valid_field = true;
							$GLOBALS['disable_date_format']= false;
							$function = $var['function']['name'];
							$custom_function = false;
							$req_file = $var['function']['include'];
							$_REQUEST[$var['name']] = $value;
							if (!empty($sugar_config['quickcrm_custom_includes'])){
								if (isset($sugar_config['quickcrm_custom_includes'][$req_file])){
									$custom_function = true;
									$req_file = $sugar_config['quickcrm_custom_includes'][$req_file];
								}
							}
							if ($custom_function){
								// OK
							}
							else if ($var['name'] == 'aop_case_updates_threaded'){
								$req_file = 'custom/QuickCRM/Case_Updates.php';
							}
							else if ($var['name'] == 'reminders' && $var['function']['name'] == 'Reminder::getRemindersListView'){
								$req_file = 'custom/QuickCRM/Reminders.php';
								$function = 'getRemindersListView';
							}
							else if ($var['name'] == 'reschedule_history' || substr( $var['name'], 0, 4 ) === "qcrm") {
								// only these function fields are supported	
							}
							else {
								$valid_field = false;
							}
							
							if ($valid_field){
								require_once($req_file);
								if (function_exists($function)){
									$val = $function($value, $var['name'], '', 'DetailView');
								}
							}
							$GLOBALS['disable_date_format']= true;
						}
					
					$list[$var['name']] = $this->get_name_value($var['name'], $val);
				} // if
			} // foreach
		} // if
		$GLOBALS['log']->info('End: SoapHelperWebServices->get_name_value_list_for_fields');
		if ($this->isLogLevelDebug()) {
			$GLOBALS['log']->debug('SoapHelperWebServices->get_name_value_list_for_fields - return data = ' . var_export($list, true));
		} // if
		return $list;

	} // fn

	function checkQuery($errorObject, $query, $order_by = '', $allow_subqueries = false)
    {
        global $sugar_config;
        if ($allow_subqueries && isset($sugar_config['quickcrm_allqueries']) && $sugar_config['quickcrm_allqueries'] == true){
			// !!!
			// !!! USE THAT AT YOUR OWN RISKS
			// !!!
			// !!! SOME CUSTOMIZATIONS REQUIRE COMPLEX WHERE STATEMENTS WITH SUBQUERIES
			// !!! SUBQUERIES ARE PROHIBITED BY include/SugarSQLValidate.php
			// !!! EXCEPT FOR A LIST OF TABLES SUCH AS email_addr_bean_rel
			// !!!
			// !!! SETTING THIS CONFIGURATION VARIABLE WILL ALLOW ANY WHERE CONDITION
			// !!! BUT ONLY IN get_entry_list FUNCTION
			// !!!
        	return true;
    	}
        return parent::checkQuery($errorObject, $query, $order_by);
    }

    function filter_fields($value, $fields)
    {
        // fix bug with one2one or many2one relationship fields not returned
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->filter_fields');
        global $invalid_contact_fields;
        $filterFields = array();
        foreach($fields as $field)
        {
            if (is_array($invalid_contact_fields))
            {
                if (in_array($field, $invalid_contact_fields))
                {
                    continue;
                }
            }
            if (isset($value->field_defs[$field]))
            {
                $var = $value->field_defs[$field];
                //if($var['type'] == 'link') continue;
                if($var['type'] == 'link' && !isset($var['side'])) continue;
                if( isset($var['source'])
                    && ($var['source'] != 'db' && $var['source'] != 'custom_fields' && $var['source'] != 'non-db')
                    && $var['name'] != 'email1' && $var['name'] != 'email2'
                    && (!isset($var['type'])|| $var['type'] != 'relate')) {

                    if( $value->module_dir == 'Emails'
                        && (($var['name'] == 'description') || ($var['name'] == 'description_html') || ($var['name'] == 'from_addr_name')
                            || ($var['name'] == 'reply_to_addr') || ($var['name'] == 'to_addrs_names') || ($var['name'] == 'cc_addrs_names')
                            || ($var['name'] == 'bcc_addrs_names') || ($var['name'] == 'raw_source')))
                    {

                    }
                    else
                    {
                        continue;
                    }
                }
            }
            $filterFields[] = $field;
        }
        $GLOBALS['log']->info('End: SoapHelperWebServices->filter_fields');
        return $filterFields;
    }

	function Qget_name_value($field,$value){
		return $value;
	}
	
	function Qget_return_value_for_link_fields($bean, $module, $link_name_to_value_fields_array) {
		$GLOBALS['log']->info('Begin: SoapHelperWebServices->Qget_return_value_for_link_fields');
		global $module_name, $current_user;
		$module_name = $module;
		if($module == 'Users' && $bean->id != $current_user->id){
			$bean->user_hash = '';
		}
		if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
			$bean = clean_sensitive_data($bean->field_defs, $bean);
		}

		if (empty($link_name_to_value_fields_array) || !is_array($link_name_to_value_fields_array)) {
			$GLOBALS['log']->debug('End: SoapHelperWebServices->Qget_return_value_for_link_fields - Invalid link information passed ');
			return array();
		}

		if ($this->isLogLevelDebug()) {
			$GLOBALS['log']->debug('SoapHelperWebServices->Qget_return_value_for_link_fields - link info = ' . var_export($link_name_to_value_fields_array, true));
		} // if
		$link_output = array();
		foreach($link_name_to_value_fields_array as $link_name_value_fields) {
			if (!is_array($link_name_value_fields) || !isset($link_name_value_fields['name']) || !isset($link_name_value_fields['value'])) {
				continue;
			}
			$link_field_name = $link_name_value_fields['name'];
			$link_module_fields = $link_name_value_fields['value'];
			if (is_array($link_module_fields) && !empty($link_module_fields)) {

				$bean->load_relationship($link_field_name);
				if (isset($bean->$link_field_name)) {
		            $related_beans = $bean->$link_field_name->get();
		            if (!$related_beans){
						$link_output[] = array('name' => $link_field_name, 'records' => array());
						continue;
		            }
					$rowArray = array();
					foreach($related_beans as $id) {
						$nameValueArray = array();
						$nameValueArray['id'] = $id;
						$rowArray[] = $nameValueArray;
					} // foreach
					$link_output[] = array('name' => $link_field_name, 'records' => $rowArray);
		        }
			} // if
		} // foreach
		$GLOBALS['log']->debug('End: SoapHelperWebServices->Qget_return_value_for_link_fields');
		if ($this->isLogLevelDebug()) {
			$GLOBALS['log']->debug('SoapHelperWebServices->Qget_return_value_for_link_fields - output = ' . var_export($link_output, true));
		} // if
		return $link_output;
	} // fn
	
	function Qget_name_value_list_for_fields($value, $fields) {
		$GLOBALS['log']->info('Begin: SoapHelperWebServices->get_name_value_list_for_fields');
		global $app_list_strings;
		global $invalid_contact_fields;

		$list = array();
		if(!empty($value->field_defs)){
			if(empty($fields))$fields = array_keys($value->field_defs);
			if(isset($value->assigned_user_name) && in_array('assigned_user_name', $fields)) {
				$list['assigned_user_name'] = $this->Qget_name_value('assigned_user_name', $value->assigned_user_name);
			}
			if(isset($value->modified_by_name) && in_array('modified_by_name', $fields)) {
				$list['modified_by_name'] = $this->Qget_name_value('modified_by_name', $value->modified_by_name);
			}
			if(isset($value->created_by_name) && in_array('created_by_name', $fields)) {
				$list['created_by_name'] = $this->Qget_name_value('created_by_name', $value->created_by_name);
			}

			$filterFields = $this->filter_fields($value, $fields);


			foreach($filterFields as $field){
				$var = $value->field_defs[$field];
				if(isset($value->{$var['name']})){
					$val = $value->{$var['name']};
					$type = $var['type'];

					if(strcmp($type, 'date') == 0){
						$val = substr($val, 0, 10);
					}
					
					if (isset($var['function']) && !empty($var['function']['returns']) && isset($var['function']['include']) && $var['function']['returns'] == 'html')
						{
							if ($var['name'] == 'reminders' && $var['function']['name'] == 'Reminder::getRemindersListView'){
								require_once('custom/QuickCRM/Reminders.php');
								$_REQUEST[$var['name']] = $value;
								$val = getRemindersListView($value, $var['name'], '', 'DetailView');
							}
						}

					$list[$var['name']] = $this->Qget_name_value($var['name'], $val);
				} // if
			} // foreach
		} // if
		$GLOBALS['log']->info('End: SoapHelperWebServices->get_name_value_list_for_fields');
		if ($this->isLogLevelDebug()) {
			$GLOBALS['log']->debug('SoapHelperWebServices->get_name_value_list_for_fields - return data = ' . var_export($list, true));
		} // if
		return $list;

	} // fn

	function Qget_return_value_for_fields($value, $module, $fields) {
		$GLOBALS['log']->info('Begin: SoapHelperWebServices->get_return_value_for_fields');
		global $module_name, $current_user;
		$module_name = $module;
		if($module == 'Users' && $value->id != $current_user->id){
			$value->user_hash = '';
		}
		if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
			$value = clean_sensitive_data($value->field_defs, $value);
		}
		$GLOBALS['log']->info('End: SoapHelperWebServices->get_return_value_for_fields');
		return Array('id'=>$value->id,
					'module_name'=> $module,
					'name_value_list'=>$this->Qget_name_value_list_for_fields($value, $fields)
					);
	}


    /**
     * Equivalent of get_list function within SugarBean but allows the possibility to pass in an indicator
     * if the list should filter for favorites.  Should eventually update the SugarBean function as well.
     *
	 * NS-TEAM : - fix bug with order by 
     */
    function get_data_list_query($seed, $order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false)
	{
		global $sugar_version;
		$GLOBALS['log']->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted']))
		{
			$show_deleted = 1;
		}
		// Fix bug with sort order in get_entry_list
		if ($sugar_version < '6.5.15') {
			$order_by=$seed->process_order_by($order_by, null);
		}
		else {
			if (!empty($order_by)){
				// fix issue where order by date fields does not always return records in the same order for equal dates
				$order_by .= ',id';
			}
		}

		$params = array();
		if(!empty($favorites)) {
		  $params['favorites'] = true;
		}

		$query = $seed->create_new_list_query($order_by, $where,array(),$params, $show_deleted);

		if ($seed->module_name == 'Meetings' && strpos($where, 'm_u.') !== false) // Allow searching participants
			$query = str_replace ('FROM meetings','FROM meetings LEFT JOIN  meetings_users m_u on m_u.meeting_id = meetings.id',$query);
		
		return $query;
	}
	
    function Qget_data_list($seed, $order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false, $single_select = false)
	{
		$query = self::get_data_list_query($seed, $order_by, $where, $row_offset, $limit, $max, $show_deleted, $favorites);
		return $seed->process_list_query($query, $row_offset, $limit, $max, $where);
	}
	
    function get_data_list_with_relate_qry($seed, $order_by = "", $where = "", $select_fields, $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false)
	{
		global $sugar_version;
		$GLOBALS['log']->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted']))
		{
			$show_deleted = 1;
		}			   

		// Fix bug with sort order in get_entry_list
		if ($sugar_version < '6.5.15') {
			$order_by=$seed->process_order_by($order_by, null);
		}
		else {
			if (!empty($order_by)){
				// fix for app < 6.1.5 (search distinct order by date_modified)
				if(isset($seed->field_name_map['date_modified']) && !in_array('date_modified',$select_fields) && is_array ($select_fields)){
					$select_fields[] = 'date_modified';
				}
				// fix issue where order by date fields does not always return records in the same order for equal dates
				$order_by .= ',id';
			}
		}

		$filter=array();
		if (is_array ($select_fields)){
			foreach ($select_fields as $key=>$value_array) {
				$filter[$value_array]=true;
			}
		}
		
		$params = array('distinct'=>true);
		if(!empty($favorites)) {
		  $params['favorites'] = true;
		}
		
		if ($seed->module_name == 'Users' || $seed->module_name == 'Employees') $query = $seed->create_new_list_query($order_by, $where,array(),$params, $show_deleted);
		else $query = $seed->create_new_list_query($order_by, $where,$filter,$params, $show_deleted, '', false, null, true);
		
		if ($seed->module_name == 'Meetings' && strpos($where, 'm_u.') !== false) // Allow searching participants
			$query = str_replace ('FROM meetings','FROM meetings LEFT JOIN  meetings_users m_u on m_u.meeting_id = meetings.id',$query);

		return $query;
	}
	
    function get_data_list_with_relate($seed, $order_by = "", $where = "", $select_fields, $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false)
	{
		$query = self::get_data_list_with_relate_qry($seed, $order_by, $where, $select_fields, $row_offset, $limit, $max, $show_deleted, $favorites);
		return $seed->process_list_query($query, $row_offset, $limit, $max, $where);
	}

    function getEmailRelationshipResults($init_array,$bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '') {
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getEmailRelationshipResults');
		require_once('include/TimeDate.php');
		global $beanList, $beanFiles, $current_user;
		global $disable_date_format, $timedate;
		
		$bean->load_relationship($link_field_name);

		$p = array('link'=>'contacts');
		$GLOBALS['app']->controller->bean = $bean;
		$qry_params = $bean->get_unlinked_email_query(array('return_as_array'=>true), $bean);

		$emails_query = $qry_params['select'] . ' ' . $qry_params['from']. ' ' . $qry_params['join']. ' ' . $qry_params['where'];
		if ($order_by != '') $emails_query .= 'ORDER BY emails.' . $order_by;
		$linked_emails = $GLOBALS['db']->query($emails_query);

		$related_beans= array();
			while($val = $GLOBALS['db']->fetchByAssoc($linked_emails,false))
				{
					$email= new Email();
					$related_beans[$val['id']] = $email->retrieve($val['id']);
				}		


			//First get all the related beans
            $params = array();
            $params['offset'] = $offset;
            $params['limit'] = $limit;

            if (!empty($optional_where))
            {
                $params['where'] = $optional_where;
            }

            //Create a list of field/value rows based on $link_module_fields
			$list = $init_array['rows'];
            $filterFields = $init_array['fields_set_on_rows'];
            if (!empty($order_by) && !empty($related_beans))
            {
                $related_beans = Qorder_beans($related_beans, $order_by);
            }
            foreach($related_beans as $id => $bean)
            {
                if (empty($filterFields) && !empty($link_module_fields))
                {
                    $filterFields = $this->filter_fields($bean, $link_module_fields);
                }
                $row = array();
                foreach ($filterFields as $field) {
                    if (isset($bean->$field))
                    {
                        if (isset($bean->field_defs[$field]['type']) && $bean->field_defs[$field]['type'] == 'date') {
                            $row[$field] = $timedate->to_display_date_time($bean->$field);
                        } else {
                            $row[$field] = $bean->$field;
                        }
                    }
                    else
                    {
                        $row[$field] = "";
                    }
                }
                //Users can't see other user's hashes
                if(is_a($bean, 'User') && $current_user->id != $bean->id && isset($row['user_hash'])) {
                    $row['user_hash'] = "";
                }
				if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
					$row = clean_sensitive_data($bean->field_defs, $row);
				}
                $list[] = $row;
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getEmailRelationshipResults');
            return array('rows' => $list, 'fields_set_on_rows' => $filterFields);

	} // fn
	
    function getAccountsEmailRelationshipResults($bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '') {
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getAccountsEmailRelationshipResults');
		require_once('include/TimeDate.php');
		global $beanList, $beanFiles, $current_user;
		global $disable_date_format, $timedate;
		
		$bean->load_relationship($link_field_name);

		$p = array('link'=>'contacts');
		$GLOBALS['app']->controller->bean = $bean;
		$qry_params = get_emails_by_assign_or_link($p);

		$emails_query = $qry_params['select'] . ' ' . $qry_params['from']. ' ' . $qry_params['join']. ' ' . $qry_params['where'];
		if ($order_by != '') $emails_query .= 'ORDER BY emails.' . $order_by;
		$linked_emails = $GLOBALS['db']->query($emails_query);

		$related_beans= array();
			while($val = $GLOBALS['db']->fetchByAssoc($linked_emails,false))
				{
					$email= new Email();
					$related_beans[$val['id']] = $email->retrieve($val['id']);
				}		

			//First get all the related beans
            $params = array();
            $params['offset'] = $offset;
            $params['limit'] = $limit;

            if (!empty($optional_where))
            {
                $params['where'] = $optional_where;
            }

            //Create a list of field/value rows based on $link_module_fields
			$list = array();
            $filterFields = array();
            if (!empty($order_by) && !empty($related_beans))
            {
                //$related_beans = Qorder_beans($related_beans, $order_by);
            }
            foreach($related_beans as $id => $bean)
            {
                if (empty($filterFields) && !empty($link_module_fields))
                {
                    $filterFields = $this->filter_fields($bean, $link_module_fields);
                }
                $row = array();
                foreach ($filterFields as $field) {
                    if (isset($bean->$field))
                    {
                        if (isset($bean->field_defs[$field]['type']) && $bean->field_defs[$field]['type'] == 'date') {
                            $row[$field] = $timedate->to_display_date_time($bean->$field);
                        } else {
                            $row[$field] = $bean->$field;
                        }
                    }
                    else
                    {
                        $row[$field] = "";
                    }
                }
				$row['edit_access']= $bean->ACLAccess("EditView");
				$row['delete_access']= $bean->ACLAccess("Delete");
				$filterFields[] = 'edit_access';
				$filterFields[] = 'delete_access';
                //Users can't see other user's hashes
                if(is_a($bean, 'User') && $current_user->id != $bean->id && isset($row['user_hash'])) {
                    $row['user_hash'] = "";
                }
				if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
					$row = clean_sensitive_data($bean->field_defs, $row);
				}
                $list[] = $row;
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getAccountsEmailRelationshipResults');
            return array('total_count'=> count($related_beans),'rows' => $list, 'fields_set_on_rows' => $filterFields);

	} // fn

    function getRelationshipIds($bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '', $with_access_rights=false) {
		// fix bug with sort order and offset
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getRelationshipResults');
		require_once('include/TimeDate.php');
		global $beanList, $beanFiles, $current_user;
		global $disable_date_format, $timedate;

		$bean->load_relationship($link_field_name);

		if (isset($bean->$link_field_name)) {
			//First get all the related beans
            $params = array();
            //$params['offset'] = $offset;
            //$params['limit'] = $limit;

            if (!empty($optional_where))
            {
                $params['where'] = $optional_where;
            }

            $related_beans = $bean->$link_field_name->getBeans($params);
            //Create a list of field/value rows based on $link_module_fields
			$list = array();
            $filterFields = array();

            foreach($related_beans as $id => $rel_bean)
            {
            	if (!$with_access_rights || $rel_bean->ACLAccess('DetailView')) {
					$row['id']= $rel_bean->id;
    	            $list[] = $row;
                }
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipIds');
            return array('total_count'=> count($related_beans), 'rows' => $list, 'fields_set_on_rows' => $filterFields);
		} else {
			$GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipIds - ' . $link_field_name . ' relationship does not exists');
			return false;
		} // else

	} // fn
	
	
    function getRelationshipResults($bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '', $with_access_rights=true) {
		// fix bug with sort order and offset
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getRelationshipResults');
		require_once('include/TimeDate.php');
		global $beanList, $beanFiles, $current_user;
		global $disable_date_format, $timedate;

		$bean->load_relationship($link_field_name);

		if (isset($bean->$link_field_name)) {
			//First get all the related beans
            $params = array();
            //$params['offset'] = $offset;
            //$params['limit'] = $limit;

            if (!empty($optional_where))
            {
                $params['where'] = $optional_where;
            }

            $related_beans = $bean->$link_field_name->getBeans($params);

            //Create a list of field/value rows based on $link_module_fields
			$list = array();
            $filterFields = array();
            if (!empty($order_by) && !empty($related_beans))
            {
                $order_by_elts = explode(' ', $order_by);
				$related_beans = Qorder_beans($related_beans, $order_by_elts[0]);
				if (isset($order_by_elts[1]) && $order_by_elts[1] =='desc')
					$related_beans = array_reverse($related_beans);
            }
			//$nb=0;
			//$added=0;
			
            foreach($related_beans as $id => $rel_bean)
            {
                /*
                $nb++;
				if ($link_field_name != 'emails'){ // for emails, offset and limit are managed in the app
					if ($nb < $offset+1) continue;
					if ($limit && $added == $limit) break;
				}
				$added++;
				*/
				
				if (!$rel_bean->ACLAccess('DetailView')) continue;
				
				if (empty($filterFields) && !empty($link_module_fields))
                {
                    $filterFields = $this->filter_fields($rel_bean, $link_module_fields);
                }
                
                $row = array();
                foreach ($filterFields as $field) {
                    if (isset($rel_bean->$field))
                    {
                        if (isset($rel_bean->field_defs[$field]['type']) && $rel_bean->field_defs[$field]['type'] == 'date') {
                            $row[$field] = $timedate->to_display_date_time($rel_bean->$field);
                        } else {
                            $row[$field] = $rel_bean->$field;
                        }
                    }
                    else
                    {
                        $row[$field] = "";
                    }
                }
                if ($with_access_rights){
                	// Not needed during Offline Sync
					$row['edit_access']= $rel_bean->ACLAccess("EditView");
					$row['delete_access']= $rel_bean->ACLAccess("Delete");
					$filterFields[] = 'edit_access';
					$filterFields[] = 'delete_access';
				}
                //Users can't see other user's hashes
                if(is_a($rel_bean, 'User') && $current_user->id != $rel_bean->id && isset($row['user_hash'])) {
                    $row['user_hash'] = "";
                }
				if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
					$row = clean_sensitive_data($rel_bean->field_defs, $row);
				}
                $list[] = $row;
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults');
            return array('total_count'=> count($list), 'rows' => $list, 'fields_set_on_rows' => $filterFields);
		} else {
			$GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults - ' . $link_field_name . ' relationship does not exists');
			return false;
		} // else

	} // fn
	
    function QgetRelationshipResults($bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '') {
		// fix bug with sort order and offset
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getRelationshipResults');
		require_once('include/TimeDate.php');
		global $beanList, $beanFiles, $current_user;
		global $disable_date_format, $timedate;

		$bean->load_relationship($link_field_name);

		if (isset($bean->$link_field_name)) {
			//First get all the related beans
            $params = array();
            // offset and limits are not used so that sort order can be applied to the full list
            //$params['offset'] = $offset;
            //$params['limit'] = $limit;

            if (!empty($optional_where))
            {
                $params['where'] = $optional_where;
            }

            $related_beans = $bean->$link_field_name->getBeans($params);
            //Create a list of field/value rows based on $link_module_fields
			$list = array();
            $filterFields = array();
            if (!empty($order_by) && !empty($related_beans))
            {
                $count_order = explode(' ', $order_by);
				$order_field = $count_order[0];
				$related_beans = Qorder_beans($related_beans, $order_field);
				if (count($count_order) == 2 && ( strtoupper($count_order[1]) == 'DESC')){
					$related_beans = array_reverse($related_beans);
				}
            }
            foreach($related_beans as $id => $bean)
            {
				if (empty($filterFields) && !empty($link_module_fields))
                {
                    $filterFields = $this->filter_fields($bean, $link_module_fields);
                }
                $row = array();
                foreach ($filterFields as $field) {
                    if (isset($bean->$field))
                    {
                        if (isset($bean->field_defs[$field]['type']) && $bean->field_defs[$field]['type'] == 'date') {
                            $row[$field] = $timedate->to_display_date_time($bean->$field);
                        } else {
                            $row[$field] = $bean->$field;
                        }
                    }
                    else
                    {
                        $row[$field] = "";
                    }
                }
                //Users can't see other user's hashes
                if(is_a($bean, 'User') && $current_user->id != $bean->id && isset($row['user_hash'])) {
                    $row['user_hash'] = "";
                }
				if(function_exists("clean_sensitive_data")) { // doesn't exist in older versions
					$row = clean_sensitive_data($bean->field_defs, $row);
				}
                $list[] = $row;
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults');
            return array('total_count'=> count($related_beans),'rows' => $list, 'fields_set_on_rows' => $filterFields);
		} else {
			$GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults - ' . $link_field_name . ' relationship does not exists');
			return false;
		} // else

	} // fn
	
    public function buildChartImage($chart, array $reportData, array $fields,$asDataURI = true, $generateImageMapId = false){
    	// used only with oldest SuiteCRM version when bug on render call was not fixed
        global $current_user;
        require_once 'modules/AOR_Charts/lib/pChart/pChart.php';

        if($generateImageMapId !== false){
            $generateImageMapId = $current_user->id."-".$generateImageMapId;
        }

        $html = '';
        if(!in_array($chart->type, array('bar','line','pie','radar','rose', 'grouped_bar', 'stacked_bar'))){
            return $html;
        }
        $x = $fields[$chart->x_field];
        $y = $fields[$chart->y_field];
        if(!$x || !$y){
            //Malformed chart object - missing an axis field
            return '';
        }
        $xName = str_replace(' ','_',$x->label) . $chart->x_field;
        $yName = str_replace(' ','_',$y->label) . $chart->y_field;

        $chartData = new pData();
        $chartData->loadPalette("modules/AOR_Charts/lib/pChart/palettes/navy.color", TRUE);
        $labels = array();
        foreach($reportData as $row){
            $chartData->addPoints($row[$yName],'data');
            $chartData->addPoints($row[$xName],'Labels');
            $labels[] = $row[$xName];
        }

        $chartData->setSerieDescription("Months","Month");
        $chartData->setAbscissa("Labels");

        $imageHeight = 700;
        $imageWidth = 700;

        $chartPicture = new pImage($imageWidth,$imageHeight,$chartData);
        if($generateImageMapId){
            $imageMapDir = create_cache_directory('modules/AOR_Charts/ImageMap/'.$current_user->id.'/');
            $chartPicture->initialiseImageMap($generateImageMapId,IMAGE_MAP_STORAGE_FILE,$generateImageMapId,$imageMapDir);
        }

        $chartPicture->Antialias = True;

        $chartPicture->drawFilledRectangle(0,0,$imageWidth-1,$imageHeight-1,array("R"=>240,"G"=>240,"B"=>240,"BorderR"=>0,"BorderG"=>0,"BorderB"=>0,));

        $chartPicture->setFontProperties(array("FontName"=>"modules/AOR_Charts/lib/pChart/fonts/verdana.ttf","FontSize"=>14));

        $chartPicture->drawText($imageWidth/2,20,$chart->name,array("R"=>0,"G"=>0,"B"=>0,'Align'=>TEXT_ALIGN_TOPMIDDLE));
        $chartPicture->setFontProperties(array("FontName"=>"modules/AOR_Charts/lib/pChart/fonts/verdana.ttf","FontSize"=>6));

        $chartPicture->setGraphArea(60,60,$imageWidth-60,$imageHeight-100);

        switch($chart->type){
            case 'radar':
                $chart->buildChartImageRadar($chartPicture, $chartData, !empty($generateImageMapId));
                break;
            case 'pie':
                $chart->buildChartImagePie($chartPicture,$chartData, $reportData,$imageHeight, $imageWidth, $xName, !empty($generateImageMapId));
                break;
            case 'line':
                $chart->buildChartImageLine($chartPicture, !empty($generateImageMapId));
                break;
            case 'bar':
            default:
                $chart->buildChartImageBar($chartPicture, !empty($generateImageMapId));
                break;
        }
        if($generateImageMapId) {
            $chartPicture->replaceImageMapTitle("data", $labels);
        }
        ob_start();
        // BUG in SuiteCRM
        $chartPicture->render(NULL);
        $img = ob_get_clean();
        if($asDataURI){
            return 'data:image/png;base64,'.base64_encode($img);
        }else{
            return $img;
        }
    }

    function buildChartHTMLPChart($chart,array $reportData, array $fields,$index = 0){
    	global $sugar_config;
    	if (version_compare($sugar_config['suitecrm_version'], '7.9.8', '>=') 
    			|| ( $sugar_config['suitecrm_version'] < '7.9' && version_compare($sugar_config['suitecrm_version'], '7.8.9', '>='))
    		){
    		// issue with render has been fixed in 7.9.8 and higher, and 7.8.9+ 
 			$imgUri = $chart->buildChartImage($reportData,$fields,true,$index);
		}
    	else {
    		// not fixed. Use our workaround
			$imgUri = $this->buildChartImage($chart,$reportData,$fields,true,$index);
    	}
        $img = "<img id='{$chart->id}_img' src='{$imgUri}'>";
        return $img;
    }

    function AOSgetLineItems($focus,$return_as_nvl){
			if($focus->id != '') {
				require_once('modules/AOS_Products_Quotes/AOS_Products_Quotes.php');
				require_once('modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php');

				$sql = "SELECT pg.id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $focus->object_name . "' AND pg.parent_id = '" . $focus->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";

				$result = $focus->db->query($sql);
				$line_items=array();
				$groups=array();
				$stored_groups=array();
				while ($row = $focus->db->fetchByAssoc($result)) {
					$line_item = new AOS_Products_Quotes();
					$line_item->retrieve($row['id']);
					$line_item = $return_as_nvl ? self::get_name_value_list_for_fields($line_item, "") : self::Qget_name_value_list_for_fields($line_item, "");

					$group_item = 'null';
					if ($row['group_id'] != null) {
						$group_item = new AOS_Line_Item_Groups();
						$group_item->retrieve($row['group_id']);
						$group_item = $return_as_nvl ? self::get_name_value_list_for_fields($group_item, "") : self::Qget_name_value_list_for_fields($group_item, "");
						if (!in_array($row['group_id'],$stored_groups)){
							$groups[] = $group_item;
							$stored_groups[]=$row['group_id'];
						}
					}
					$line_items[]=$line_item;

				}
				return array('lineitems' =>$line_items,'groups'=>$groups);
			}
			else return array('lineitems' => array() ,'groups'=>array());
	}
}