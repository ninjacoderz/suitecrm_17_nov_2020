<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);


/**
 * This is a rest entry point for rest version Sugar 6.5 +
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Credentials: true");

// fix for SugarCRM/SuiteCRM REST API generating notice 
// with Declaration of SugarWebServiceUtilv4::get_data_list
@ini_set('display_errors', '0');
// end fix

$rest_file = '../../service/quickcrm/rest.php';
if (file_exists('../../custom/service/quickcrm/rest.php')){
	$rest_file = '../../custom/service/quickcrm/rest.php';
}
require_once($rest_file);
