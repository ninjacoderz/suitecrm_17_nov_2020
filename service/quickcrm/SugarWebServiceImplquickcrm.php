<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
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


/**
 * SugarWebServiceImplv4_1.php
 *
 * This class is an implementation class for all the web services.  Version 4_1 adds limit/off support to the
 * get_relationships function.  We also added the sync_get_modified_relationships function call from version
 * one to facilitate querying for related meetings/calls contacts/users records.
 *
 */
require_once('service/v4_1/SugarWebServiceImplv4_1.php');
require_once('service/quickcrm/SugarWebServiceUtilquickcrm.php');
if (file_exists('modules/KReports/Plugins/Presentation/standardview/standardviewinclude.php')){
	include('modules/KReports/Plugins/Presentation/standardview/standardviewinclude.php');}
if (file_exists('modules/AOR_Reports/aor_utils.php')){
	include('modules/AOR_Reports/aor_utils.php');}

class SugarWebServiceImplquickcrm extends SugarWebServiceImplv4_1
{

    /**
     * Class Constructor Object
     *
     */
    public function __construct()
    {
        self::$helperObject = new SugarWebServiceUtilquickcrm();
    }

    public function login($user_auth, $application = null, $name_value_list = array()){
		global $db,$sugar_config;
		
		// force LDAP or Sugar Authentification if default authentication is SAML as SAML does not support REST API
		if (isset($GLOBALS['sugar_config']['authenticationClass']) && $GLOBALS['sugar_config']['authenticationClass'] == 'SAMLAuthenticate'){
			if (isset($sugar_config['quickcrm_authenticate'])){
				$autClass = $sugar_config['quickcrm_authenticate'];
			}
			else {
				$autClass = 'SugarAuthenticate';
			}
			$GLOBALS['sugar_config']['authenticationClass'] = $autClass; 	
		}
		
		$res=parent::login($user_auth, $application, $name_value_list);
		if ($res && isset($res['name_value_list']) && isset($_SESSION['user_id'])){
			$user_id= $_SESSION['user_id'];

			require_once('modules/QuickCRM/license/OutfittersLicense.php');
			$authorized = QOutfittersLicense::isValid('QuickCRM',$user_id) === true;


			if ($authorized){
				$res['name_value_list']['mx'] = $sugar_config['quickcrm_max'];				
				$res['name_value_list']['fulluser'] = true;
				require_once("modules/ACLRoles/ACLRole.php");
				$acl_role_obj = new ACLRole();
				$roles = $acl_role_obj->getUserRoles($user_id,False);
				$res['name_value_list']['roles'] = array(); 
				$res['name_value_list']['role_ids'] = array(); 
				foreach ($roles as $role){
					$res['name_value_list']['roles'][] = $role->name; 
					$res['name_value_list']['role_ids'][] = $role->id; 
				}
				if (isset($sugar_config['securitysuite_version'])){
					require_once('modules/SecurityGroups/SecurityGroup.php');
					$group= new SecurityGroup();
					$res['name_value_list']['teams'] = $group->getUserSecurityGroups($user_id);				
				}
				else {
					$res['name_value_list']['teams'] = false;								
				}
				global $current_user;
				
				$res['name_value_list']['emailAddress'] = $current_user->email1;		
				$res['name_value_list']['signature'] = $current_user->getDefaultSignature();		

				$res['name_value_list']['email_mode'] = $current_user->getPreference('email_link_type');
									
				$current_user->getPreference('reminder_checked');
				$reminder_time = "-1";
				if ($current_user->getPreference('reminder_checked')){
					$reminder_time = $current_user->getPreference('reminder_time');
				}
				$res['name_value_list']['reminder_time'] = $reminder_time;

				$admin = new Administration();
            	$admin->retrieveSettings();
				$res['name_value_list']['receive_notifications'] = $admin->settings['notify_on'] && $current_user->receive_notifications;
				
				global $locale;
				$cur_decimals = $locale->getPrecedentPreference('default_currency_significant_digits',$current_user);
				$res['name_value_list']['currency_digits'] = array(
					'name' => 'currency_digits',
					'value' => $cur_decimals,
				);
			}
			else {
				$res['name_value_list']['fulluser'] = false;				
				$res['name_value_list']['roles'] = false;
				$res['name_value_list']['teams'] = false;
			}
			if (isset($sugar_config['quickcrm_log_users']) && $sugar_config['quickcrm_log_users'] == true){
		            $GLOBALS['log']->fatal("QuickCRM login - User: ".$user_auth['user_name']);
		    }
			$res['name_value_list']['fdow'] = $GLOBALS['current_user']->get_first_day_of_week();
			
		}
		return $res;
	}

    public function getCurrentUserFavorites($session,$module_name = '')
    {
    	$GLOBALS['log']->info('Begin: SugarWebServiceImpl->getCurrentUserFavorites');

    	$error = new SoapError();
    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
    		$error->set_error('invalid_login');
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->getCurrentUserFavorites');
    		return;
    	} // if
		
        global $db, $current_user;

        $return_array = array();

        if ($module_name == ''){
	        $query = "SELECT id, parent_id, parent_type FROM favorites WHERE assigned_user_id = '" . $current_user->id . "' AND deleted = 0 ORDER BY date_entered DESC";
		}
		else {
	        $query = "SELECT id, parent_id, parent_type FROM favorites WHERE parent_type = '" . $module_name. "' AND assigned_user_id = '" . $current_user->id . "' AND deleted = 0 ORDER BY date_entered DESC";
		}

        $result = $db->query($query);

        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $bean = BeanFactory::getBean($row['parent_type'], $row['parent_id']);
            if($bean) {
            	$return_array[$i] = array();
                $return_array[$i]['item_summary'] = $bean->name;
                $return_array[$i]['id'] = $row['parent_id'];
                $return_array[$i]['module_name'] = $row['parent_type'];
                $return_array[$i]['record_id'] = $row['id'];

                ++$i;
            }

        }

    	return array('entry_list'=>$return_array);
    }
	
    function search_by_module($session, $search_string, $modules, $offset, $max_results,$assigned_user_id = '', $select_fields = array(), $unified_search_only = TRUE, $favorites = FALSE){
		// fix access rights verification in unified search
		// fix bug with ProjectTask
    	$GLOBALS['log']->info('Begin: SugarWebServiceImpl->search_by_module');
    	global  $beanList, $beanFiles;
    	global $sugar_config,$current_language;

    	$output_list = array();
    	$error = new SoapError();
    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
    		$error->set_error('invalid_login');
    		$GLOBALS['log']->error('End: SugarWebServiceImpl->search_by_module - FAILED on checkSessionAndModuleAccess');
    		return;
    	}
    	global $current_user;
    	if($max_results > 0){
    		$sugar_config['list_max_entries_per_page'] = $max_results;
    	}

    	require_once('modules/Home/UnifiedSearchAdvanced.php');
    	require_once 'include/utils.php';
    	$usa = new UnifiedSearchAdvanced();
        if(!file_exists($cachefile = sugar_cached('modules/unified_search_modules.php'))) {
            $usa->buildCache();
        }

    	include $cachefile;
    	$modules_to_search = array();
    	$unified_search_modules['Users'] =   array('fields' => array());

        //If we are ignoring the unified search flag within the vardef we need to re-create the search fields.  This allows us to search
        //against a specific module even though it is not enabled for the unified search within the application.
        if( !$unified_search_only )
        {
            foreach ($modules as $singleModule)
            {
                if( !isset($unified_search_modules[$singleModule]) )
                {
                    $newSearchFields = array('fields' => self::$helperObject->generateUnifiedSearchFields($singleModule) );
                    $unified_search_modules[$singleModule] = $newSearchFields;
                }
            }
        }


        foreach($unified_search_modules as $module=>$data) {
        	if (in_array($module, $modules)) {
            	$modules_to_search[$module] = $beanList[$module];
        	} // if
        } // foreach

        $GLOBALS['log']->info('SugarWebServiceImpl->search_by_module - search string = ' . $search_string);

    	if(!empty($search_string) && isset($search_string)) {
    		require_once 'include/SearchForm/SearchForm2.php' ;

    		$search_string = trim($GLOBALS['db']->quote(securexss(from_html(clean_string($search_string, 'UNIFIED_SEARCH')))));
        	foreach($modules_to_search as $name => $beanName) {
        		$where_clauses_array = array();
    			$unifiedSearchFields = array () ;
    			foreach ($unified_search_modules[$name]['fields'] as $field=>$def ) {
    				$unifiedSearchFields[$name] [ $field ] = $def ;
    				$unifiedSearchFields[$name] [ $field ]['value'] = $search_string;
    			}

    			require_once $beanFiles[$beanName] ;
    			$seed = new $beanName();
    			if ($beanName == "User"
//    			    || $beanName == "ProjectTask"
    			    ) {
    				if(!self::$helperObject->check_modules_access($current_user, $seed->module_dir, 'read')){
    					continue;
    				} // if
    				if(!$seed->ACLAccess('ListView')) {
    					continue;
    				} // if
    			}

    			if ($beanName != "User"
//    			    && $beanName != "ProjectTask"
    			    ) {

    				$searchForm = new SearchForm ($seed, $name ) ;

    				$searchForm->setup(array ($name => array()) ,$unifiedSearchFields , '' , 'saved_views' /* hack to avoid setup doing further unwanted processing */ ) ;
    				$where_clauses = $searchForm->generateSearchWhere() ;
    				$emailQuery = false;

    				$where = '';
    				if (count($where_clauses) > 0 ) {
    					$where = '('. implode(' ) OR ( ', $where_clauses) . ')';
    				}

					if($seed->bean_implements('ACL')){
						if(ACLController::requireOwner($seed->module_dir, 'list')){
							if(!empty($where)){
								$where = "($where) AND ";
							}
							$where .= $seed->getOwnerWhere($current_user->id);
						}
						if (isset($sugar_config['securitysuite_version'])){
						/* BEGIN - SECURITY GROUPS */
							if(ACLController::requireSecurityGroup($seed->module_dir, 'list') )
							{
								require_once('modules/SecurityGroups/SecurityGroup.php');
								$owner_where = $seed->getOwnerWhere($current_user->id);
								$group_where = SecurityGroup::getGroupWhere($seed->table_name,$seed->module_dir,$current_user->id);
								if(!empty($owner_where)) {
									if(empty($where))
										{
											$where = " (".  $owner_where." or ".$group_where.")";
										} else {
											$where = "($where) AND (".  $owner_where." or ".$group_where.")";
										}
								} else {
									if(!empty($where)){
										$where .= ' AND ';
									}
									$where .= $group_where;
								}
							}
							/* END - SECURITY GROUPS */
						}
					}

    				if(count($select_fields) > 0)
    				    $filterFields = $select_fields;
    				else {
    				    if(file_exists('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php'))
    					   require_once('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php');
        				else
        					require_once('modules/'.$seed->module_dir.'/metadata/listviewdefs.php');

        				$filterFields = array();
        				foreach($listViewDefs[$seed->module_dir] as $colName => $param) {
        	                if(!empty($param['default']) && $param['default'] == true)
        	                    $filterFields[] = strtolower($colName);
        	            }
        	            if (!in_array('id', $filterFields))
        	            	$filterFields[] = 'id';
    				}

    				//Pull in any db fields used for the unified search query so the correct joins will be added
    				$selectOnlyQueryFields = array();
    				foreach ($unifiedSearchFields[$name] as $field => $def){
    				    if( isset($def['db_field']) && !in_array($field,$filterFields) ){
    				        $filterFields[] = $field;
    				        $selectOnlyQueryFields[] = $field;
    				    }
    				}

    	            //Add the assigned user filter if applicable
    	            if (!empty($assigned_user_id) && isset( $seed->field_defs['assigned_user_id']) ) {
    	               $ownerWhere = $seed->getOwnerWhere($assigned_user_id);
    	               $where = "($where) AND $ownerWhere";
    	            }

    	            if( $beanName == "Employee" )
    	            {
    	                $where = "($where) AND users.deleted = 0 AND users.is_group = 0 AND users.employee_status = 'Active'";
    	            }

    	            $list_params = array();

    				$ret_array = $seed->create_new_list_query('', $where, $filterFields, $list_params, 0, '', true, $seed, true);
    		        if(empty($params) or !is_array($params)) $params = array();
    		        if(!isset($params['custom_select'])) $params['custom_select'] = '';
    		        if(!isset($params['custom_from'])) $params['custom_from'] = '';
    		        if(!isset($params['custom_where'])) $params['custom_where'] = '';
    		        if(!isset($params['custom_order_by'])) $params['custom_order_by'] = '';
    				$main_query = $ret_array['select'] . $params['custom_select'] . $ret_array['from'] . $params['custom_from'] . $ret_array['where'] . $params['custom_where'] . $ret_array['order_by'] . $params['custom_order_by'];
    			} else {
    				if ($beanName == "User") {
    					$filterFields = array('id', 'user_name', 'first_name', 'last_name', 'email_address');
    					$main_query = "select users.id, ea.email_address, users.user_name, first_name, last_name from users ";
    					$main_query = $main_query . " LEFT JOIN email_addr_bean_rel eabl ON eabl.bean_module = '{$seed->module_dir}'
    LEFT JOIN email_addresses ea ON (ea.id = eabl.email_address_id) ";
    					$main_query = $main_query . "where ((users.first_name like '{$search_string}') or (users.last_name like '{$search_string}') or (users.user_name like '{$search_string}') or (ea.email_address like '{$search_string}')) and users.deleted = 0 and users.is_group = 0 and users.employee_status = 'Active'";
    				} // if
/*
    				if ($beanName == "ProjectTask") {
    					$filterFields = array('id', 'name', 'project_id', 'project_name');
    					$main_query = "select {$seed->table_name}.project_task_id id,{$seed->table_name}.project_id, {$seed->table_name}.name, project.name project_name from {$seed->table_name} ";
    					$seed->add_team_security_where_clause($main_query);
    					$main_query .= "LEFT JOIN teams ON $seed->table_name.team_id=teams.id AND (teams.deleted=0) ";
    		            $main_query .= "LEFT JOIN project ON $seed->table_name.project_id = project.id ";
    		            $main_query .= "where {$seed->table_name}.name like '{$search_string}%'";
    				} // if
*/
    			} // else

    			$GLOBALS['log']->info('SugarWebServiceImpl->search_by_module - query = ' . $main_query);
    	   		if($max_results < -1) {
    				$result = $seed->db->query($main_query);
    			}
    			else {
    				if($max_results == -1) {
    					$limit = $sugar_config['list_max_entries_per_page'];
    	            } else {
    	            	$limit = $max_results;
    	            }
    	            $result = $seed->db->limitQuery($main_query, $offset, $limit + 1);
    			}

    			$rowArray = array();
    			while($row = $seed->db->fetchByAssoc($result)) {
    				$nameValueArray = array();
    				foreach ($filterFields as $field) {
    				    if(in_array($field, $selectOnlyQueryFields))
    				        continue;
    					$nameValue = array();
    					if (isset($row[$field])) {
    						$nameValueArray[$field] = self::$helperObject->get_name_value($field, $row[$field]);
    					} // if
    				} // foreach
    				$rowArray[] = $nameValueArray;
    			} // while
    			$output_list[] = array('name' => $name, 'records' => $rowArray);
        	} // foreach

	    	$GLOBALS['log']->info('End: SugarWebServiceImpl->search_by_module');
    	} // if
    	return array('entry_list'=>$output_list);
    } // fn

    function get_users_entry_list($session, $query, $max_results){

        $error = new SoapError();
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_users_entry_list');
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'Users', 'read', 'no_access', $error)) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkSessionAndModuleAccess');
            return;
        } // if
        
		$filePath = 'modules/Home/QuickSearch.php';
		if (file_exists('custom/' . $filePath))
		{
    		require_once('custom/' . $filePath);
    		$quicksearchQuery = new quicksearchQueryCustom();
		}
		else
		{
    		require_once($filePath);
    		$quicksearchQuery = new quicksearchQuery();
		}


        $users = array();
        if ($query != ''){
			$query = stristr($query, "'%");
        	$query = trim($query,")");
        	$query = trim($query,"'");
        }

		$args = array('conditions' => array(0=>array('value'=>$query)));
		$all_users = $quicksearchQuery->get_user_array($args);
		$json = getJSONobj();
		$all_users = $json->decode($all_users);
		$all_users=$all_users['fields'];

        $users = array();        
		$j = 0;
       	foreach ($all_users as $key => $values){
        		$j++;
        		$users[] = array (
            		"id" => $values['id'],
            		"module_name" => "Users",
            		"name_value_list" => array(
                		"first_name" => array(
                    		"name" => "first_name",
                    		"value" => ""
                		),
                		"last_name" => array(
                    		"name" => "last_name",
                    		"value" => $values['user_name']
                		)
            		)
            	);
        		if ($max_results > 0 && $j > $max_results){
        			break;
        		}
        }

        return array('result_count'=>sizeof($users), 'total_count' => sizeof($users), 'next_offset'=>0, 'entry_list'=>$users, 'relationship_list' => array());
    } 
	
    function get_entry_list($session, $module_name, $query, $order_by,$offset, $select_fields, $link_name_to_fields_array, $max_results, $deleted, $favorites = false ){

        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_entry_list');
        global  $beanList, $beanFiles;
        global $sugar_config;
        $error = new SoapError();
        $using_cp = false;
        if($module_name == 'CampaignProspects'){
            $module_name = 'Prospects';
            $using_cp = true;
        }
        else if($module_name == 'Users' 
        	&& ((isset($sugar_config['securitysuite_filter_user_list']) && $sugar_config['securitysuite_filter_user_list'] == true)
        		|| (isset($sugar_config['quickcrm_filter_user_list']) && $sugar_config['quickcrm_filter_user_list'] == true)
        	)
        ){        
            return self::get_users_entry_list($session, $query, $max_results);
        }


        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkSessionAndModuleAccess');
            return;
        } // if

        if (!self::$helperObject->checkQuery($error, $query, $order_by,true)) { // Allow subqueries in special configurations
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list');
        	return;
        } // if

        // If the maximum number of entries per page was specified, override the configuration value.
        if($max_results > 0){
            $sugar_config['list_max_entries_per_page'] = $max_results;
        } // if

        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);
        $seed = new $class_name();

        if (!self::$helperObject->checkACLAccess($seed, 'list', $error, 'no_access')) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkACLAccess');
            return;
        } // if

        if($query == ''){
            $where = '';
        } // if
        if($offset == '' || $offset == -1){
            $offset = 0;
        } // if
        if($deleted){
            $deleted = -1;
        }
        if($using_cp){
            $response = $seed->retrieveTargetList($query, $select_fields, $offset,-1,-1,$deleted);
        }else
        {
            // tweak for mssql or low performance servers
			if (self::$helperObject->use_std_search($module_name)){
				$response = self::$helperObject->Qget_data_list($seed,$order_by, $query, $offset,-1,-1,$deleted,$favorites);
			}
			else {
				$response = self::$helperObject->get_data_list_with_relate($seed,$order_by, $query, $select_fields,$offset,-1,-1,$deleted,$favorites);
			}	
        } // else
        $list = $response['list'];

        $output_list = array();
        $linkoutput_list = array();

        foreach($list as $value) {
            if(isset($value->emailAddress)){
                $value->emailAddress->handleLegacyRetrieve($value);
            } // if
            $value->fill_in_additional_detail_fields();

            $output_list[] = self::$helperObject->get_return_value_for_fields($value, $module_name, $select_fields);
            if(!empty($link_name_to_fields_array)){
                $linkoutput_list[] = self::$helperObject->get_return_value_for_link_fields($value, $module_name, $link_name_to_fields_array);
            }
        } // foreach

        // Calculate the offset for the start of the next page
        $next_offset = $offset + sizeof($output_list);

		$returnRelationshipList = array();
		foreach($linkoutput_list as $rel){
			$link_output = array();
			foreach($rel as $row){
				$rowArray = array();
				foreach($row['records'] as $record){
					$rowArray[]['link_value'] = $record;
				}
				$link_output[] = array('name' => $row['name'], 'records' => $rowArray);
			}
			$returnRelationshipList[]['link_list'] = $link_output;
		}

		$totalRecordCount = $response['row_count'];
        if( !empty($sugar_config['disable_count_query']) )
            $totalRecordCount = -1;

        $GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list - SUCCESS');
        return array('result_count'=>sizeof($output_list), 'total_count' => $totalRecordCount, 'next_offset'=>$next_offset, 'entry_list'=>$output_list, 'relationship_list' => $returnRelationshipList);
    } // fn
	
    function Qget_entry_list($session, $module_name, $query, $order_by,$offset, $select_fields, $link_name_to_fields_array, $max_results, $deleted, $favorites = false ){
		// does not return name_value_list but just values

        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_entry_list');
        global  $beanList, $beanFiles;
        global $sugar_config;
        $error = new SoapError();
        $using_cp = false;
        if($module_name == 'CampaignProspects'){
            $module_name = 'Prospects';
            $using_cp = true;
        }
        
        // remove module access as it does not support modules with $seed->bean_implements('ACL') false
        // so just check for session.
        // module access has been checked before.
    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkSessionAndModuleAccess');
            return;
        } // if

        if (!self::$helperObject->checkQuery($error, $query, $order_by,true)) { // Allow subqueries in special configurations
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list');
        	return;
        } // if

        // If the maximum number of entries per page was specified, override the configuration value.
        if($max_results > 0){
            $sugar_config['list_max_entries_per_page'] = $max_results;
        } // if

        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);
        $seed = new $class_name();

        if ($seed->bean_implements('ACL') && !self::$helperObject->checkACLAccess($seed, 'list', $error, 'no_access')) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkACLAccess');
            return;
        } // if

        if($query == ''){
            $where = '';
        } // if
        if($offset == '' || $offset == -1){
            $offset = 0;
        } // if
        if($deleted){
            $deleted = -1;
        }
        if($using_cp){
            $response = $seed->retrieveTargetList($query, $select_fields, $offset,-1,-1,$deleted);
        }else
        {
            // tweak for mssql or low performance servers
			if ($sugar_config['dbconfig']['db_type'] =='mssql' || (isset($sugar_config['quickcrm_norelatesearch']) && $sugar_config['quickcrm_norelatesearch']==true)){
				$response = self::$helperObject->Qget_data_list($seed,$order_by, $query, $offset,-1,-1,$deleted,$favorites);
			}
			else {
				$response = self::$helperObject->get_data_list_with_relate($seed,$order_by, $query, $select_fields,$offset,-1,-1,$deleted,$favorites);
			}	
        } // else
        $list = $response['list'];

        $output_list = array();
        $linkoutput_list = array();

		//$list_ids = array();
        foreach($list as $value) {
        	// manage duplicate ids
        	//if (in_array($value->id,$list_ids)) continue;
        	//$list_ids[] = $value->id;

            if(isset($value->emailAddress)){
                $value->emailAddress->handleLegacyRetrieve($value);
            } // if
            //$value->fill_in_additional_detail_fields();

            $values = self::$helperObject->Qget_return_value_for_fields($value, $module_name, $select_fields);
			if($module_name == 'AOS_Quotes' || $module_name == 'AOS_Invoices' || $module_name == 'AOS_Contracts'){
				$focus = BeanFactory::getBean($module_name, $value->id);
				$details = self::$helperObject->AOSgetLineItems($focus,false);
				$values['name_value_list']['lineitems']=json_encode($details['lineitems']);
				$values['name_value_list']['groups']=json_encode($details['groups']);
			}
            $output_list[] = $values;
			
            if(!empty($link_name_to_fields_array)){
                $linkoutput_list[] = self::$helperObject->Qget_return_value_for_link_fields($value, $module_name, $link_name_to_fields_array);
            }
        } // foreach

        // Calculate the offset for the start of the next page
        $next_offset = $offset + sizeof($output_list);

		$returnRelationshipList = array();
		foreach($linkoutput_list as $rel){
			$link_output = array();
			foreach($rel as $row){
				$rowArray = array();
				foreach($row['records'] as $record){
					$rowArray[]['link_value'] = $record;
				}
				$link_output[] = array('name' => $row['name'], 'records' => $rowArray);
			}
			$returnRelationshipList[]['link_list'] = $link_output;
		}

		$totalRecordCount = $response['row_count'];
        if( !empty($sugar_config['disable_count_query']) )
            $totalRecordCount = -1;

        $GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list - SUCCESS');
        return array('result_count'=>sizeof($output_list), 'total_count' => $totalRecordCount, 'next_offset'=>$next_offset, 'entry_list'=>$output_list, 'relationship_list' => $returnRelationshipList);
    } // fn

    public function Qget_entries(
        $session,
        $module_name,
        $ids,
        $select_fields,
        $link_name_to_fields_array,
        $track_view = false
    ) {
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->Qget_entries');
        global  $beanList, $beanFiles;
        $error = new SoapError();

        $linkoutput_list = array();
        $output_list = array();
        $using_cp = false;
        if($module_name == 'CampaignProspects')
        {
            $module_name = 'Prospects';
            $using_cp = true;
        }
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error))
        {
            $GLOBALS['log']->info('No Access: SugarWebServiceImpl->Qget_entries');
            return;
        } // if

        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);

        $temp = new $class_name();
        foreach($ids as $id)
        {
            $seed = @clone($temp);
            if($using_cp)
                $seed = $seed->retrieveTarget($id);
            else
            {
                if ($seed->retrieve($id) == null)
                    $seed->deleted = 1;
            }

            if ($seed->deleted == 1)
            {
                //$list = array();
                //$list[] = array('name'=>'warning', 'value'=>'Access to this object is denied since it has been deleted or does not exist');
                //$list[] = array('name'=>'deleted', 'value'=>'1');
                //$output_list[] = Array('id'=>$id,'module_name'=> $module_name,'name_value_list'=>$list,);
                continue;
            }
            if (!self::$helperObject->checkACLAccess($seed, 'DetailView', $error, 'no_access'))
            {
                //return;
                continue;
            }
            $output_list[] = self::$helperObject->Qget_return_value_for_fields($seed, $module_name, $select_fields);
            if (!empty($link_name_to_fields_array))
            {
                $linkoutput_list[] = self::$helperObject->Qget_return_value_for_link_fields($seed, $module_name, $link_name_to_fields_array);
            }

        }

        $relationshipList = $linkoutput_list;
        $returnRelationshipList = array();
        foreach ($relationshipList as $rel) {
            $link_output = array();
            foreach ($rel as $row) {
                $rowArray = array();
                foreach ($row['records'] as $record) {
                    $rowArray[]['link_value'] = $record;
                }
                $link_output[] = array('name' => $row['name'], 'records' => $rowArray);
            }
            $returnRelationshipList[]['link_list'] = $link_output;
        }

        $GLOBALS['log']->info('End: SugarWebServiceImpl->Qget_entries');
        return array('entry_list'=>$output_list, 'relationship_list' => $returnRelationshipList);
    }

    function get_entry($session, $module_name, $id,$select_fields, $link_name_to_fields_array,$track_view = FALSE)
    {
        $res = self::get_entries($session, $module_name, array($id), $select_fields, $link_name_to_fields_array, $track_view);
		if ($id != '' && $res && isset($res['entry_list'])){
			$focus = BeanFactory::getBean($module_name, $id);
			if($focus->id != '') {
		        // return edit right
				$res['entry_list'][0]['name_value_list']['edit_access'] = $focus->ACLAccess("EditView");
				$res['entry_list'][0]['name_value_list']['delete_access'] = $focus->ACLAccess("Delete");
			}
		}
		return $res;
	}

    function get_totals($session, $module_name, $query, $select_fields,$total_fields,$group_by){

        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_entry_list');
        global  $beanList, $beanFiles, $db;
        global $sugar_config;
        $error = new SoapError();

        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkSessionAndModuleAccess');
            return;
        } // if

        if (!self::$helperObject->checkQuery($error, $query, '',true)) { // Allow subqueries in special configurations
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list');
        	return;
        } // if

        // If the maximum number of entries per page was specified, override the configuration value.
        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);
        $seed = new $class_name();

        if (!self::$helperObject->checkACLAccess($seed, 'list', $error, 'no_access')) {
            $GLOBALS['log']->error('End: SugarWebServiceImpl->get_entry_list - FAILED on checkACLAccess');
            return;
        } // if

        if($query == ''){
            $where = '';
        } // if
        
        $offset = 0;
        
        $deleted = false;
        
		$params = array();
		
		$filter=array();
		if (is_array ($select_fields)){
			foreach ($select_fields as $key=>$value_array) {
				$filter[$value_array]=true;
			}
		}
		
		$count_array = array();
		foreach ($total_fields as $name=>$values) {
			if (!isset($filter[$values['field']])){
				$filter[$values['field']]=true;
			}
			if (is_string($values['fnct'])) $values['fnct'] = array($values['fnct']);
			foreach ($values['fnct'] as $fnct){
				$count_array[] =  $fnct  . '(' . $values['table'] . '.' . $values['field'] . ') as '.$values['field'].$fnct;
			}
		}
		
		if ($group_by != ''){
				$filter[$group_by]=true;
		}

		$count_qry = implode(",",$count_array);
		

		if (self::$helperObject->use_std_search($module_name)){
			$totals_query = $seed->create_new_list_query('', $query, $filter, $params, false);

		}
		else {
			$totals_query = $seed->create_new_list_query("", $query, $filter,$params, false, '', false, null, true);
		}	

        $output_list = array('totals'=>array(),'groups'=>array());
        
		$totals_query = preg_replace('/SELECT.+\sFROM\s/', 'SELECT '.$count_qry. ' FROM ', $totals_query);

        $result = $db->query($totals_query);

		if ($row = $db->fetchByAssoc($result)){
			$output_list['totals'] = $row;
		}
		
		if ($group_by != ''){
			$output_list['groups'][$group_by]=array();
			
			$group_query = preg_replace('/SELECT/', 'SELECT '.$group_by . ',' , $totals_query);
			$group_query .= ' GROUP BY ' . $group_by;

	        $result = $db->query($group_query);

			while ($row = $db->fetchByAssoc($result)){
				$output_list['groups'][$group_by][] = $row;
			}
		}

        $GLOBALS['log']->info('End: SugarWebServiceImpl->get_entry_list - SUCCESS');
        return array('entry_list'=>$output_list);
    } // fn
	
    function AOSget_entry($session, $module_name, $id,$select_fields, $link_name_to_fields_array,$track_view = FALSE)
    {
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->AOSget_entry');
        $res = self::get_entries($session, $module_name, array($id), $select_fields, $link_name_to_fields_array, $track_view);
		if ($id != '' && $res && isset($res['entry_list'])){
			$focus = BeanFactory::getBean($module_name, $id);
			$details = self::$helperObject->AOSgetLineItems($focus,true);
			if($focus->id != '') {
				$res['entry_list'][0]['name_value_list']['lineitems']=$details['lineitems'];
				$res['entry_list'][0]['name_value_list']['groups']=$details['groups'];
				$res['entry_list'][0]['name_value_list']['edit_access'] = $focus->ACLAccess("Save");
				if (!empty($focus->billing_contact_id)){
					$contact = BeanFactory::getBean('Contacts', $focus->billing_contact_id);
					$res['entry_list'][0]['name_value_list']['contact_email'] = $contact->email1;
				}
				if (!empty($focus->billing_account_id)){
					$account = BeanFactory::getBean('Accounts', $focus->billing_account_id);
					$res['entry_list'][0]['name_value_list']['account_email'] = $account->email1;
				}
			}
		}
		
        $GLOBALS['log']->info('end: SugarWebServiceImpl->AOSget_entry');
		return $res;
	}

    function set_entry($session,$module_name, $name_value_list, $track_view = FALSE){
		// FIX ISSUE WITH NOTIFICATIONS AND DATE/TIME
		// FIX ISSUE WITH SECURITY SUITE INHERIT
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->set_entry');
        if (self::$helperObject->isLogLevelDebug()) {
            $GLOBALS['log']->debug('SoapHelperWebServices->set_entry - input data is ' . var_export($name_value_list, true));
        } // if
        $error = new SoapError();
        if ($module_name != 'Favorites' && !self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'write', 'no_access', $error)) {
            $GLOBALS['log']->fatal('End: SugarWebServiceImpl->set_entry : No Access');
        	return array('name'=>'No Access');
        } // if
		
        global  $beanList, $beanFiles, $current_user, $sugar_config;

		$previous_user_id = '';
        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);
        $seed = new $class_name();
        foreach($name_value_list as $name=>$value){
            if(is_array($value) &&  $value['name'] == 'id'){
				if (isset($value['name'])){
					$seed->retrieve($value['value']);
					$previous_user_id = $seed->assigned_user_id;
					break;
				}
            }else if($name === 'id' ){
                $seed->retrieve($value);
				$previous_user_id = $seed->assigned_user_id;
				break;
            }
        }

        $return_fields = array();
		$parent_type='';
		$parent_id='';
        foreach($name_value_list as $name=>$value){
            if($module_name == 'Users' && !empty($seed->id) && ($seed->id != $current_user->id) && $name == 'user_hash'){
                continue;
            }
            if(!empty($seed->field_name_map[$name]['sensitive'])) {
                    continue;
            }

            if(!is_array($value)){
                $seed->$name = $value;
                $return_fields[] = $name;
            }else{
				if (isset($value['name'])){
					$seed->{$value['name']} = $value['value'];
					$return_fields[] = $value['name'];
					// manage security suite Inherit parent
					if ($value['name'] =='parent_type') {
						$parent_type = $value['value'];
					}
					elseif ($value['name'] =='parent_id') {
						$parent_id = $value['value'];
					}
				}
            }
        }
        
		if (empty($seed->id) && isset($sugar_config['securitysuite_inherit_parent']) && $sugar_config['securitysuite_inherit_parent'] == true && $parent_id != '' && $parent_type != ''){
			$_REQUEST['relate_to']=$parent_type;
			$_REQUEST['relate_id']=$parent_id;
		}
        if (!self::$helperObject->checkACLAccess($seed, 'Save', $error, 'no_access') || ($seed->deleted == 1  && !self::$helperObject->checkACLAccess($seed, 'Delete', $error, 'no_access'))) {
            $GLOBALS['log']->fatal('End: SugarWebServiceImpl->set_entry Access Denied');
        	return array('name'=>'Access Denied');
        } // if
// FIX NS-TEAM
// ADD NOTIFICATION SUPPORT AND SET/UNSET REMINDERS FOR SUITECRM DESKTOP
		if (isset($name_value_list['assigned_user_id']) && isset($name_value_list['assigned_user_id']['value']) &&($module_name == 'Meetings' || $module_name == 'Calls')){
			$_REQUEST['assigned_user_id'] = $name_value_list['assigned_user_id']['value'];
			
			if ($name_value_list['assigned_user_id']['value'] == $current_user->id && isset($name_value_list['reminder_time'])){
				$remindersData = array();
				if ($name_value_list['reminder_time']['value'] != '-1'){
					$remindersData[]=array(
    	 				'idx' => 0,
     					'id' => '',
     					'popup' => true,
     					'email' => false,
     					'timer_popup' => $name_value_list['reminder_time']['value'],
     					'timer_email' => '3600',
				     	'invitees' => 
    						array (
				      			0 => array(
         							'id' => '',
         							'module' => 'Users',
         							'module_id' => $current_user->id,
      							),
      						),
    				);
    			}
				// No effect for SugarCRM or old versions of SuiteCRM
				$_REQUEST['reminders_data'] = json_encode($remindersData);
			}	
		}
		$notify = self::$helperObject->checkSaveOnNotify();
		if (substr($module_name,0,4) != 'QCRM_'){
			if (isset($name_value_list['assigned_user_id']) && isset($name_value_list['assigned_user_id']['value']) && ($name_value_list['assigned_user_id']['value'] != $current_user->id)){
				if ($name_value_list['assigned_user_id']['value'] != $previous_user_id){
						$notify = true;
				}
			}
			//$seed->notify_inworkflow = true;
		}		

		if ($module_name == 'Favorites') {
			$notify = False;
		}
		if ($module_name == 'Favorites' && $seed->id == '') {
			$favorite_id = $seed->getFavoriteID($seed->parent_type, $seed->parent_type);
			if ($favorite_id){
				$seed->id = $favorite_id;
			}
		}
// END FIX NS-TEAM

        $seed->save($notify);

        $return_entry_list = self::$helperObject->get_name_value_list_for_fields($seed, $return_fields );

        if($seed->deleted == 1){
            $seed->mark_deleted($seed->id);
        }

        if($track_view){
            self::$helperObject->trackView($seed, 'editview');
        }
        
        $GLOBALS['log']->info('End: SugarWebServiceImpl->set_entry');
        return array('id'=>$seed->id, 'entry_list' => $return_entry_list);
    } // fn


    function Emailsset_entry($session,$module_name, $name_value_list, $track_view = FALSE){
		// FIX ISSUE WITH NOTIFICATIONS AND DATE/TIME
		// FIX ISSUE WITH SECURITY SUITE INHERIT
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->Emailsset_entry');
        if (self::$helperObject->isLogLevelDebug()) {
            $GLOBALS['log']->debug('SoapHelperWebServices->Emailsset_entry - input data is ' . var_export($name_value_list, true));
        } // if
        $error = new SoapError();
        if ($module_name != 'Favorites' && !self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'write', 'no_access', $error)) {
            $GLOBALS['log']->fatal('End: SugarWebServiceImpl->Emailsset_entry : No Access');
        	return array('name'=>'No Access');
        } // if
		
        global  $beanList, $beanFiles, $current_user, $sugar_config;

        $class_name = $beanList[$module_name];
        require_once($beanFiles[$class_name]);
        $seed = new $class_name();
        foreach($name_value_list as $name=>$value){
            if(is_array($value) &&  $value['name'] == 'id'){
				if (isset($value['name'])){
					$seed->retrieve($value['value']);
					break;
				}
            }else if($name === 'id' ){
                $seed->retrieve($value);
				break;
            }
        }

        if (!self::$helperObject->checkACLAccess($seed, 'Save', $error, 'no_access') || ($seed->deleted == 1  && !self::$helperObject->checkACLAccess($seed, 'Delete', $error, 'no_access'))) {
            $GLOBALS['log']->fatal('End: SugarWebServiceImpl->set_entry Access Denied');
        	return array('name'=>'Access Denied');
        } // if

		$seed->email2init();
        $return_fields = array();

		$request = array();
		$parent_type='';
		$parent_id='';
        foreach($name_value_list as $name=>$value){
            if(!isset($seed->field_name_map[$name])) {
                    continue;
            }
            if(!empty($seed->field_name_map[$name]['sensitive'])) {
                    continue;
            }
            if(!is_array($value)){
                $seed->$name = $value;
                $return_fields[] = $name;
            }else{
				if (isset($value['name'])){
					$seed->{$value['name']} = $value['value'];
					$return_fields[] = $value['name'];
					// manage security suite Inherit parent
					if ($value['name'] =='parent_type') {
						$parent_type = $value['value'];
						$request[$value['name']] = $value['value'];
					}
					elseif ($value['name'] =='parent_id') {
						$parent_id = $value['value'];
						$request[$value['name']] = $value['value'];
					}
					elseif ($value['name'] =='name') {
						$seed->name = $value['value'];
					}
				}
            }
        }
		$tosend = true;
        if (isset($name_value_list['tosend'])) {
        	$tosend = $name_value_list['tosend']['value'];
        }
        if (isset($name_value_list['to_addrs_names'])) {
        	$request['sendTo'] = $name_value_list['to_addrs_names']['value'];
        }
        if (isset($name_value_list['cc_addrs_names'])) {
        	$request['sendCc'] = $name_value_list['cc_addrs_names']['value'];
        }
        if (isset($name_value_list['bcc_addrs_names'])) {
        	$request['sendBcc'] = $name_value_list['bcc_addrs_names']['value'];
        }
		
		$request['sendSubject'] = $name_value_list['name']['value'];
		$request['sendDescription'] = $name_value_list['description_html']['value'];
		
		if(!file_exists($seed->et->userCacheDir)){
			$path = clean_path($seed->et->userCacheDir);
			mkdir_recursive($path);
		}


		if (!empty($name_value_list['attachments']['value']) && !empty($name_value_list['file_attachments']['value'])){
			if (is_array($name_value_list['attachments']['value'])){
				$filenames = $name_value_list['attachments']['value'];
				$filecontents = $name_value_list['file_attachments']['value'];
			}
			else { 
				$filenames = array($name_value_list['attachments']['value']);
				$filecontents = array($name_value_list['file_attachments']['value']);
			}
			$attachments = array();
			foreach ($filenames as $key => $filename){
				// Save file_contents
				$fileGUID = create_guid();
				$fileLocation = $seed->et->userCacheDir . "/{$fileGUID}";
				file_put_contents($fileLocation,base64_decode($filecontents[$key]));
				$attachments[] = $fileGUID . $filename;
			}
			$request['attachments'] = implode('::',$attachments);
		}

		if (empty($seed->id) && isset($sugar_config['securitysuite_inherit_parent']) && $sugar_config['securitysuite_inherit_parent'] == true && $parent_id != '' && $parent_type != ''){
			$_REQUEST['relate_to']=$parent_type;
			$_REQUEST['relate_id']=$parent_id;
		}

		$request['saveToSugar'] =1;
		$request['setEditor'] = 1;
		$request['composeType'] = '';
		$request['fromAccount'] = '';
		
		foreach($request as $r => $v){ // BUGS IN SUGARCRM AND SUITECRM OLD VERSIONS (uses both $request and $_REQUEST)
			$_REQUEST[$r] = $v;
		}
		
        $seed->type = 'out';
        $seed->status = 'sent';
        $seed->email2Send($request);

        $return_entry_list = self::$helperObject->get_name_value_list_for_fields($seed, array('id','status'));

        $GLOBALS['log']->info('End: SugarWebServiceImpl->Emailsset_entry');
        return array('id'=>$seed->id, 'entry_list' => $return_entry_list);
    } // fn

	
	
    function AOSset_entry($session,$module_name, $name_value_list, $track_view = FALSE){
		// Line items are retrieved from post data
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->set_entry');
		
        foreach($name_value_list as $name=>$value){
            if(!is_array($value)){
            }else{
				if (isset($value['name']) && $value['name']=='lineitems'){
					$lineitems=$value['value'];
					foreach($lineitems as $f=>$val){
						$_POST[$f]=$val;
					}
				}
            }
        }
		
        $GLOBALS['log']->info('End: SugarWebServiceImpl->AOSset_entry');
        return self::set_entry($session,$module_name, $name_value_list, $track_view = FALSE);
    } // fn

    function AOSOLset_entry($session,$module_name, $name_value_list, $track_view = FALSE){
		// Line items are retrieved from post data
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->set_entry');
		
		$group_ids=array();
		$item_ids=array();
		$lineitems=array();
		$groups=array();
		$alllines=array();
		$current_id="";
        foreach($name_value_list as $name=>$value){
            if(!is_array($value)){
            }else{
				if (isset($value['name']) && $value['name']=='lineitems'){
					$lineitems=$value['value'];
				}
				else if (isset($value['name']) && $value['name']=='groups'){
					$groups=$value['value'];
				}
				else if (isset($value['name']) && $value['name']=='id'){
					$current_id=$value['value'];
				}
            }
        }
		foreach ($groups as $idx => $group){
			if (isset($group['deleted']) && $group['deleted']=="1") continue;
			$group_ids[]= $group['id'];
			if (isset($group['new_with_id']) && $group['new_with_id']=="1"){
				$newgroup = new AOS_Line_Item_Groups();
				$newgroup->id=$group['id'];
				$newgroup->new_with_id =true;
				$newgroup->save(false);
			}
			foreach ($group as $field => $val){
				if ($field == 'new_with_id') continue;
				$key='group_'.$field;
				if (!isset($alllines[$key])) $alllines[$key]=array();
				$alllines[$key][]=$val;
			}
		}
		foreach ($lineitems as $idx => $item){
			if (isset($item['deleted']) && $item['deleted']=="1") continue;
			$item_ids[]= $item['id'];
			if (isset($item['new_with_id']) && $item['new_with_id']=="1"){
				$newitem = new AOS_Products_Quotes();
				$newitem->id=$item['id'];
				$newitem->new_with_id =true;
				$newitem->save(false);
			}
			foreach ($item as $field => $val){
				if ($field == 'new_with_id') continue;
				$key = ($item['product_id']=='0'?'service_':'product_').$field;			
				if (!isset($alllines[$key]))$alllines[$key]=array();
				$alllines[$key][]=$val;
			}
		}
		
		// delete old groups and items
		$focus = new AOS_Line_Item_Groups();
        $sql = "SELECT lig.id FROM aos_line_item_groups lig WHERE lig.parent_type = '".$module_name."' AND lig.parent_id = '".$current_id."' AND lig.deleted = 0";
        $result = $focus->db->query($sql);
        while ($row = $focus->db->fetchByAssoc($result)) {
            $grp_id=$row['id'];
			if (!in_array($grp_id,$group_ids)){
				$focus->mark_deleted($grp_id);
			}
		}
		$focus = new AOS_Products_Quotes();
        $sql = "SELECT lig.id FROM aos_products_quotes lig WHERE lig.parent_type = '".$module_name."' AND lig.parent_id = '".$current_id."' AND lig.deleted = 0";
        $result = $focus->db->query($sql);
        while ($row = $focus->db->fetchByAssoc($result)) {
            $grp_id=$row['id'];
			if (!in_array($grp_id,$item_ids)){
				$focus->mark_deleted($grp_id);
			}
		}
		
		// set post data for module save
		foreach ($alllines as $key => $val){
			$_POST[$key]=$val;
		}
		
        $GLOBALS['log']->info('End: SugarWebServiceImpl->AOSset_entry');
        return self::set_entry($session,$module_name, $name_value_list, $track_view = FALSE);
    } // fn
	
    function get_relationships($session, $module_name, $module_id, $link_field_name, $related_module_query, $related_fields, $related_module_link_name_to_fields_array, $deleted, $order_by = '', $offset = 0, $limit = false)
    {
        global $sugar_config;
        // FIXES ISSUES WITH EMAILS , ACCESS RIGHTS AND SORT ORDER
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_relationships '.$link_field_name);
        //self::$helperObject = new SugarWebServiceUtilv4_1();
    	$error = new SoapError();

    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    		return;
    	} // if
        global  $beanList, $beanFiles, $current_user;

    	$mod = BeanFactory::getBean($module_name, $module_id);

        if (!self::$helperObject->checkQuery($error, $related_module_query, $order_by)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        if (!self::$helperObject->checkACLAccess($mod, 'DetailView', $error, 'no_access')) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        $output_list = array();
    	$linkoutput_list = array();
		
		if ($module_name=='Accounts' && $link_field_name == 'emails'){
			$result = self::$helperObject->getAccountsEmailRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);
		}
		else {
			// get all the related modules data.
			// with access rights or not
			$with_access_rights = true;
			if (isset($sugar_config['quickcrm_check_access']) && !$sugar_config['quickcrm_check_access']){
				$with_access_rights = false;
			}
			
			$result = self::$helperObject->getRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit,true);
			
			/*
			if (method_exists($mod,'get_unlinked_email_query') && $link_field_name == 'emails'){
				// append unlinked mails
				$result = self::$helperObject->getEmailRelationshipResults($result,$mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);
			}
			*/
		}
        if (self::$helperObject->isLogLevelDebug()) {
    		$GLOBALS['log']->debug('SoapHelperWebServices->get_relationships - return data for getRelationshipResults is ' . var_export($result, true));
        } // if
    	$total_count = 0;
		$added=0;
    	if ($result) {

    		$list = $result['rows'];
    		$filterFields = $result['fields_set_on_rows'];
			
			if (sizeof($list) > 0) {
    			$submodulename = $mod->$link_field_name->getRelatedModuleName();
                $submoduletemp = BeanFactory::getBean($submodulename);
				
    			foreach($list as $row) {
   					$submoduleobject = @clone($submoduletemp);
					$submoduleobject->retrieve($row['id']);
					
					if (!$submoduleobject->ACLAccess('DetailView')) continue;

                	$total_count++;
					if ($link_field_name != 'emails'){ // for emails, offset and limit are managed in the app
						if ($total_count < $offset+1) continue;
						if ($limit && $added == $limit) break;
					}
					$added++;
					
    				// set all the database data to this object
    				foreach ($filterFields as $field) {
    					$submoduleobject->$field = $row[$field];
    				} // foreach
    				if (isset($row['id'])) {
    					$submoduleobject->id = $row['id'];
    				}
    				if (isset($row['edit_access'])) {
    					$submoduleobject->edit_access = $row['edit_access'];
    					$submoduleobject->delete_access = $row['delete_access'];
    				}
    				$output_list[] = self::$helperObject->get_return_value_for_fields($submoduleobject, $submodulename, $filterFields);
    				if (!empty($related_module_link_name_to_fields_array)) {
    					$linkoutput_list[] = self::$helperObject->get_return_value_for_link_fields($submoduleobject, $submodulename, $related_module_link_name_to_fields_array);
    				} // if

    			} // foreach
    		}

    	} // if

    	$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    	return array('entry_list'=>$output_list, 'relationship_list' => $linkoutput_list,'total_count'=> sizeof($list));
    }
	
    function get_relationships_totals($session, $module_name, $module_id, $link_field_name, $related_module_query, $related_fields, $total_fields,$group_by)
    {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_relationships_totals '.$link_field_name);
        //self::$helperObject = new SugarWebServiceUtilv4_1();
    	$error = new SoapError();

    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships_totals');
    		return;
    	} // if
        global  $beanList, $beanFiles, $sugar_config, $current_user, $db;

    	$mod = BeanFactory::getBean($module_name, $module_id);

        if (!self::$helperObject->checkQuery($error, $related_module_query, '')) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships_totals');
        	return;
        } // if

        if (!self::$helperObject->checkACLAccess($mod, 'DetailView', $error, 'no_access')) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships_totals');
        	return;
        } // if

		$result = self::$helperObject->getRelationshipIds($mod, $link_field_name, $related_fields, $related_module_query, '', 0, false);

        if (self::$helperObject->isLogLevelDebug()) {
    		$GLOBALS['log']->debug('SoapHelperWebServices->get_relationships_totals - return data for getRelationshipResults is ' . var_export($result, true));
        } // if
        
		$count_array = array();
		foreach ($total_fields as $name=>$values) {
			if (!isset($filter[$values['field']])){
				$filter[$values['field']]=true;
			}
			if (is_string($values['fnct'])) $values['fnct'] = array($values['fnct']);
			foreach ($values['fnct'] as $fnct){
				$count_array[] =  $fnct  . '(' . $values['table'] . '.' . $values['field'] . ') as '.$values['field'].$fnct;
			}
		}
		$count_qry = implode(",",$count_array);
		
        $output_list = array('totals'=>array(),'groups'=>array());
		
    	if ($result) {
			
    		$list = $result['rows'];
			
						
			if (sizeof($list) > 0) {
    			$submodulename = $mod->$link_field_name->getRelatedModuleName();
                $submoduletemp = BeanFactory::getBean($submodulename);
				$ids = array();
    			foreach($list as $row) {
					$ids[] = $row['id'];

    			} // foreach
    			
    			if (sizeof($result) > 0){
    				$totals_query = 'SELECT ' . $count_qry . ' FROM ' . $submoduletemp->table_name . ' ';
    				$customJoin = $submoduletemp->getCustomJoin();
    				$totals_query .= $customJoin['join'];
    				$totals_query .= " WHERE id IN ('".implode("','",$ids)."')";
       
       				$r = $db->query($totals_query);

					if ($row = $db->fetchByAssoc($r)){
						$output_list['totals'] = $row;
					}
					if ($group_by != ''){
						$output_list['groups'][$group_by]=array();
			
						$group_query = preg_replace('/SELECT/', 'SELECT '.$group_by . ',' , $totals_query);
						$group_query .= ' GROUP BY ' . $group_by;

	        			$result = $db->query($group_query);

						while ($row = $db->fetchByAssoc($result)){
							$output_list['groups'][$group_by][] = $row;
						}
					}
    			}
    		}

    	} // if

    	$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships_totals');
    	return array('entry_list'=>$output_list);
    }
	
    function Qget_relationships($session, $module_name, $module_id, $link_field_name, $related_module_query, $related_fields, $related_module_link_name_to_fields_array, $deleted, $order_by = '', $offset = 0, $limit = false)
    {
        // FIXES ISSUES WITH EMAILS AND SORT ORDER
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_relationships '.$link_field_name);
        //self::$helperObject = new SugarWebServiceUtilv4_1();
        global  $beanList, $beanFiles;
    	$error = new SoapError();

    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    		return;
    	} // if

    	$mod = BeanFactory::getBean($module_name, $module_id);

        if (!self::$helperObject->checkQuery($error, $related_module_query, $order_by)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        if (!self::$helperObject->checkACLAccess($mod, 'DetailView', $error, 'no_access')) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        $output_list = array();
    	$linkoutput_list = array();
		
		if ($module_name=='Accounts' && $link_field_name == 'emails'){
			$result = self::$helperObject->getAccountsEmailRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);
		}
		else {
			// get all the related modules data.
			$result = self::$helperObject->QgetRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);

		if (method_exists($mod,'get_unlinked_email_query') && $link_field_name == 'emails'){
				// append unlinked mails
				$result = self::$helperObject->getEmailRelationshipResults($result,$mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);
			}
		}
        if (self::$helperObject->isLogLevelDebug()) {
    		$GLOBALS['log']->debug('SoapHelperWebServices->get_relationships - return data for getRelationshipResults is ' . var_export($result, true));
        } // if
    	if ($result) {

    		$list = $result['rows'];
    		$filterFields = $result['fields_set_on_rows'];

    		if (sizeof($list) > 0) {
    			// get the related module name and instantiate a bean for that
    			$submodulename = $mod->$link_field_name->getRelatedModuleName();
                $submoduletemp = BeanFactory::getBean($submodulename);

    			foreach($list as $row) {
    				$submoduleobject = @clone($submoduletemp);
    				// set all the database data to this object
    				foreach ($filterFields as $field) {
    					$submoduleobject->$field = $row[$field];
    				} // foreach
    				if (isset($row['id'])) {
    					$submoduleobject->id = $row['id'];
    				}
    				$output_list[] = self::$helperObject->get_return_value_for_fields($submoduleobject, $submodulename, $filterFields);
    				if (!empty($related_module_link_name_to_fields_array)) {
    					$linkoutput_list[] = self::$helperObject->get_return_value_for_link_fields($submoduleobject, $submodulename, $related_module_link_name_to_fields_array);
    				} // if

    			} // foreach
    		}

    	} // if

    	$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    	return array('entry_list'=>$output_list, 'relationship_list' => $linkoutput_list);
    }
	
    function get_chart($session, $id, $chart_id=False, $width=False, $height=False,$language="")
    {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_chart');
		global $current_language,$app_list_strings;
		if ($language=="") $language=$current_language;
		else $current_language=$language;
		$app_list_strings = return_app_list_strings_language($language); // required for AOR. They are not initializez with web services.
		
		$report=BeanFactory::getBean('AOR_Reports',$id);
        if ($report->retrieve($id) == null){
			return;
		}

        $result = $report->db->query($report->build_report_query());
        $data = array();
        while($row = $report->db->fetchByAssoc($result, false))
        {
            $data[] = $row;
        }
        $fields = $report->getReportFields();
		
		$x = 0;
		$charts = array();
        foreach($report->get_linked_beans('aor_charts','AOR_Charts') as $chart){
			if (!$chart_id || !$chart_id == '' || $chart_id == $chart->id){
				$charts[] = array('id' => $chart->id,'chart'=> self::$helperObject->buildChartHTMLPChart($chart,$data,$fields,$x));
				$x++;
			}
        }

		return array('entry_list'=>array('charts' => $charts,'count' => $x), 'relationship_list' => array());
        $GLOBALS['log']->info('end: SugarWebServiceImpl->get_chart');
    }

    function get_report($session, $id, $offset, $max_results,$language="")
    {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_report');
		global $current_language,$app_list_strings,$beanList,$sugar_config;
		$after_75 = version_compare($sugar_config['suitecrm_version'], '7.5', '>=');
		if ($language=="") $language=$current_language;
		else $current_language=$language;
		$app_list_strings = return_app_list_strings_language($language); // required for AOR. They are not initializez with web services.

		$report=BeanFactory::getBean('AOR_Reports',$id);
        if ($report->retrieve($id) == null){
			return;
		}
		
        $max_rows = $max_results;
		$links = false;
		$group_value = '';
        $total_rows = 0;

        $report_sql = $report->build_report_query($group_value);
        $count_sql = explode('ORDER BY', $report_sql);
        $count_query = 'SELECT count(*) c FROM ('.$count_sql[0].') as n';

        // We have a count query.  Run it and get the results.
        $result = $report->db->query($count_query);
        $assoc = $report->db->fetchByAssoc($result);
        if(!empty($assoc['c']))
        {
            $total_rows = $assoc['c'];
        }
		
		$header=array();
		$values=array();
        if($offset >= 0){
            $start = 0;
            $end = 0;
            $previous_offset = 0;
            $next_offset = 0;
            $last_offset = 0;

            if($total_rows > 0){
                $start = $offset +1;
                $end = (($offset + $max_rows) < $total_rows) ? $offset + $max_rows : $total_rows;
                $previous_offset = ($offset - $max_rows) < 0 ? 0 : $offset - $max_rows;
                $next_offset = $offset + $max_rows;
                $last_offset = $max_rows * floor($total_rows / $max_rows);
            }

        } else{
        }

        $sql = "SELECT id FROM aor_fields WHERE aor_report_id = '".$report->id."' AND deleted = 0 ORDER BY field_order ASC";
        $result = $report->db->query($sql);

        $fields = array();
        $i = 0;
        while ($row = $report->db->fetchByAssoc($result)) {
            $field = new AOR_Field();
            $field->retrieve($row['id']);

            $path = unserialize(base64_decode($field->module_path));

            $field_bean = new $beanList[$report->report_module]();

            $field_module = $report->report_module;
            $field_alias = $field_bean->table_name;
            if($path[0] != $report->report_module){
                foreach($path as $rel){
                    if(empty($rel)){
                        continue;
                    }
                    $field_module = getRelatedModule($field_module,$rel);
                    $field_alias = $field_alias . ':'.$rel;
                }
            }
            $label = str_replace(' ','_',$field->label).$i;
            $fields[$label]['field'] = $field->field;
            $fields[$label]['label'] = $field->label;
            $fields[$label]['display'] = $field->display;
            $fields[$label]['function'] = $field->field_function;
            $fields[$label]['module'] = $field_module;
            $fields[$label]['alias'] = $field_alias;
            $fields[$label]['link'] = $field->link;
            $fields[$label]['total'] = $field->total;

            if ($after_75) $fields[$label]['params'] = $field->format;

            if($fields[$label]['display']){
				$header[]=$field->label;
            }
            ++$i;
        }

        if($offset >= 0){
            $result = $report->db->limitQuery($report_sql, $offset, $max_rows);
        } else {
            $result = $report->db->query($report_sql);
        }

        $totals = array();
        while ($row = $report->db->fetchByAssoc($result)) {
			$table_row=array();
            foreach($fields as $name => $att){
                if($att['display']){
                    if($att['link'] && $links){
                        // $html .= "<a href='index.php?module=".$att['module']."&action=DetailView&record=".$row[$att['alias'].'_id']."'>";
                    }

                    $currency_id = isset($row[$att['alias'].'_currency_id']) ? $row[$att['alias'].'_currency_id'] : '';

                    $show_raw_field = ($att['function'] == 'COUNT');
                    if ($after_75) $show_raw_field = $show_raw_field || !empty($att['params']);
                    if ($show_raw_field) {
                        $table_row[]= $row[$name];
                    } else {
                        $table_row[]= getModuleField($att['module'], $att['field'], $att['field'], 'DetailView', $row[$name],
                            '', $currency_id);
                    }
                    if($att['total']){
                        $totals[$name][] = $row[$name];
                    }
                    //if($att['link'] && $links) $html .= "</a>";
                }
            }
			$values[]=$table_row;
        }
		$has_total=false;
        if (version_compare($sugar_config['suitecrm_version'], '7.2', '>=')) {
	        //$html .= $report->getTotalHtml($fields,$totals);
			$table_row=array();
	        foreach($fields as $label => $field){
    	        if(!$field['display']){
        	        continue;
            	}
            	if($field['total']){
                	$table_row[]= $field['label'] ." ".$app_list_strings['aor_total_options'][$field['total']];
					$has_total=true;
            	}else{
                	$table_row[] = "";
            	}
        	}
        	if ($has_total){
				$values[]=$table_row;
				$table_row=array();
    	    	foreach($fields as $label => $field){
        	    	if(!$field['display']){
            	    	continue;
            		}
            		if($field['total'] && isset($totals[$label])){
                		$table_row[]= $report->calculateTotal($field['total'],$totals[$label]);
            		}else{
                		$table_row[] = "";
            		}
        		}
				$values[]=$table_row;
			}
        }
			
		return array('entry_list'=>array('name'=> $report->name,'previous_offset' => $previous_offset,'next_offset' => $next_offset,'last_offset' => $last_offset,'values' => $values,'header' => $header,'count' => $total_rows,'total' => $has_total), 'relationship_list' => array());
        $GLOBALS['log']->info('end: SugarWebServiceImpl->get_report');
    }

    function get_Kreport($session, $id, $offset=0, $max_results=0,$language="")
    {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_Kreport');
		global $db,$current_language,$app_list_strings,$beanList;
		if ($language=="") $language=$current_language;
		else $current_language=$language;
		$app_list_strings = return_app_list_strings_language($language); 

		$report=BeanFactory::getBean('KReports',$id);
        if ($report->retrieve($id) == null){
			return;
		}
		$reportView=new kreportpresentationstandard();
		$columns = $reportView->buildColumnArray($report);
		$reportParams = array('noFormat' => true, 'start' => $offset, 'limit' => $max_results);
		
      $totalArray = array();
      $totalArray['records'] = $report->getSelectionResults($reportParams, '0', false);

      // rework ... load from kQuery fieldArray
      $fieldArr = array();

      //2012-12-01 added link array to add to metadata for buiilding links in the frontend
      $linkArray = $report->buildLinkArray($report->kQueryArray->queryArray['root']['kQuery']->fieldArray);

      foreach ($report->kQueryArray->queryArray['root']['kQuery']->fieldArray as $fieldid => $fieldname) {
         $thisFieldArray = array('name' => $fieldname);
         if (isset($linkArray[$fieldid]))
            $thisFieldArray['linkInfo'] = json_encode($linkArray[$fieldid]);
         $fieldArr[] = $thisFieldArray;
      }

      $totalArray['metaData'] = array(
          'totalProperty' => 'count',
          'root' => 'records',
          'fields' => $fieldArr
      );
      // send the total along
      if ($report->kQueryArray->summarySelectString != '') {
         $totalArray['recordtotal'] = $db->fetchByAssoc($db->query($report->kQueryArray->summarySelectString));
         $report->processFormulas($totalArray['recordtotal']);
      }

      // do a count 
      $totalArray['count'] = $report->getSelectionResults(array('start' => $offset, 'limit' => max_results), '0', true);

	return array('entry_list'=>array('name'=> $report->name,'values' => $totalArray,'header' => $columns), 'relationship_list' => array());

    }

	function set_image_file($session,$module_name,$id,$field,$drawing,$deleted,$filename) {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->set_drawing');
		global $sugar_config;
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'write', 'no_access', $error)) {
			$GLOBALS['log']->info('End: SugarWebServiceImpl->set_drawing');
			return;
		} // if

		if ($deleted == 0) {			
			//Need to remove the stuff at the beginning of the string (bug in app < 6.2.3)
			if (strpos($drawing, ",") !== false && strpos($drawing, ",") < 50){
				$drawing = substr($drawing, strpos($drawing, ",")+1);
			}
			$drawing = base64_decode($drawing);
			
			// we know this is an image file, as it is coming from camera or photo library			
			file_put_contents($filename,$drawing);
		}
		else {
			if (file_exists($filename)) {
				unlink($filename);
			}
		}
		$GLOBALS['log']->info('End: SugarWebServiceImpl->set_drawing');
		return array('id'=>$id, 'entry_list' => array('filename'=>$filename));
	} // fn

	function set_drawing($session,$module_name,$id,$field,$drawing,$deleted=0) {
		global $sugar_config;		
		return self::set_image_file($session,$module_name,$id,$field,$drawing,$deleted,$sugar_config['upload_dir'].$id.'_'.$field.'.png');
	} // fn

	function set_image($session,$module_name,$id,$field,$drawing,$deleted=0,$name="") {
		global $sugar_config;		
		return self::set_image_file($session,$module_name,$id,$field,$drawing,$deleted,$sugar_config['upload_dir'].$id.'_'.$field);
	} // fn

	function set_photo($session,$module_name,$id,$field,$drawing,$deleted=0,$name="") {
		global $sugar_config;		
		return self::set_image_file($session,$module_name,$id,$field,$drawing,$deleted,$sugar_config['cache_dir'].'images/'.$id.'_'.$name);
	} // fn

	function get_image_file($session,$module_name,$id,$field,$filename) {
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_image_file');
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
			$GLOBALS['log']->error('End: SugarWebServiceImpl->get_image_file - FAILED on checkACLAccess');
			return;
		} // if
	
		$local_location =  $filename;
		$hasDrawing=true;

		if(!file_exists( $local_location )) {
			$local_location = "themes/default/images/_blank.png";
			$hasDrawing=false;
		}
		$res= base64_encode(file_get_contents($local_location,true));

		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_image_file');
		return array('entry_list'=>array('hasDrawing'=> $hasDrawing,'contents' => $res));

	}

	function get_drawing($session,$module_name,$id,$field) {
		return self::get_image_file($session,$module_name,$id,$field,"upload://{$id}_{$field}.png");
	} // fn
	function get_image($session,$module_name,$id,$field,$name="") {
		return self::get_image_file($session,$module_name,$id,$field,"upload://{$id}_{$field}");
	} // fn
	function get_photo($session,$module_name,$id,$field,$name="") {
		global $sugar_config;		
		return self::get_image_file($session,$module_name,$id,$field,$sugar_config['cache_dir'].'images/'.$id.'_'.$name);
	} // fn
	
	function get_file_contents($session,$module_name,$id) {
		// return file contents for custom modules based on file template
		global $sugar_config;		
		$GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_file_contents');
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
			$GLOBALS['log']->error('End: SugarWebServiceImpl->get_file_contents - FAILED on checkACLAccess');
			return;
		} // if
	
		$local_location =  "upload://{$id}";
		$fileExists=true;

		if(!file_exists( $local_location )) {
			$fileExists=false;
			$res= "";
		}
		else $res= base64_encode(file_get_contents($local_location,true));

		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_file_contents');
		return array('file_contents'=>array('fileExists'=> $fileExists,'file' => $res));

	} // fn

	function set_file_contents($session,$module_name,$id,$field,$contents,$deleted=0) {
		// set file contents for custom modules based on file template
		global $sugar_config;		
		$filename = $sugar_config['upload_dir'].$id;
		return self::set_image_file($session,$module_name,$id,$field,$contents,$deleted,$sugar_config['upload_dir'].$id);
	} // fn
	
	function generate_pdf($session,$module_name,$id,$template_id,$save=0) {
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
			$GLOBALS['log']->error('End: SugarWebServiceImpl->generate_pdf - FAILED on checkACLAccess');
			return;
		} // if
		global $sugar_config,$current_user;

		$GLOBALS['disable_date_format']= false;

		$name='';
		$contents=false;
		$file = 'generate_pdf.php';
		$dir = 'custom/QuickCRM/';
		
		// check for customizations
		if (!empty($sugar_config['quickcrm_custom_includes'])){
			if (isset($sugar_config['quickcrm_custom_includes'][$dir])){
				$dir = $sugar_config['quickcrm_custom_includes'][$dir];
			}
		}
		if (file_exists($dir . 'custom' . $file)) $file = 'custom' . $file;
		require_once($dir . $file);
		
		$tmpl = new QCRM_gen_pdf();
		$res = $tmpl->gen_pdf($module_name,$id,$template_id,$save);
		if ($res) {
			$contents = base64_encode($res['contents']);
			$name = $res['name'];
		}

		$GLOBALS['disable_date_format'] =true;
		
		return array('entry_list'=>array('name' => $name,'contents' => $contents));
	} // fn

	function generate_pdf_letter($session,$module_name,$id,$template_id,$save=0) {
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
			$GLOBALS['log']->error('End: SugarWebServiceImpl->generate_pdf_letter - FAILED on checkACLAccess');
			return;
		} // if
		global $sugar_config,$current_user;
		
		$GLOBALS['disable_date_format']= false;

		$name='';
		$contents=false;
		$file = 'formLetterPdf.php';
		$dir = 'custom/QuickCRM/';
		
		// check for customizations
		if (!empty($sugar_config['quickcrm_custom_includes'])){
			if (isset($sugar_config['quickcrm_custom_includes'][$dir])){
				$dir = $sugar_config['quickcrm_custom_includes'][$dir];
			}
		}
		if (file_exists($dir . 'custom' . $file)) $file = 'custom' . $file;
		require_once($dir . $file);
		
		$tmpl = new QCRM_gen_pdf_letter();
		$res = $tmpl->gen_pdf($module_name,$id,$template_id,$save);
		if ($res) {
			$contents = base64_encode($res['contents']);
			$name = $res['name'];
		}
		
		$GLOBALS['disable_date_format']= true;

		return array('entry_list'=>array('name' => $name,'contents' => $contents));
	} // fn

	function copy_homepage($session,$id,$role="",$user_id="") {
		$error = new SoapError();
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'QCRM_Homepage', 'write', 'no_access', $error)) {
			$GLOBALS['log']->error('End: SugarWebServiceImpl->copy_homepage - FAILED on checkACLAccess');
			return;
		} // if
		$homepage = BeanFactory::getBean('QCRM_Homepage', $id);
		if ($homepage){
			$status = 'OK';
			$homepage->copyToUsers($role,$user_id);
		}
		else {
			$status = 'Failed';
		}

		return array('entry_list'=>array('status' => $status,'contents' => array()));
	} // fn

/**
 * sets a new revision for this document
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param Array $document_revision -- Array String 'id' -- 	The ID of the document object
 * 											String 'document_name' - The name of the document
 * 											String 'revision' - The revision value for this revision
 * 											String 'file_mime_type' - The mime_type for this revision
 *                                         	String 'filename' -- The file name of the attachment
 *                                          String 'file' -- The binary contents of the file.
 * @return Array - 'id' - String - document revision id
 * @exception 'SoapFault' -- The SOAP error, if any
 */
function Qset_document_revision($session, $document_revision) {
	$GLOBALS['log']->info('Begin: SugarWebServiceImpl->Qset_document_revision');
	$error = new SoapError();
	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
		$GLOBALS['log']->info('End: SugarWebServiceImpl->Qset_document_revision');
		return;
	} // if

	require_once('service/quickcrm/QDocumentSoap.php');
	$dr = new QDocumentSoap();
	$GLOBALS['log']->info('End: SugarWebServiceImpl->Qset_document_revision');
	return array('id'=>$dr->saveFile($document_revision));
}


}