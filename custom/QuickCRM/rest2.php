<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);


/**
 * This is a rest entry point for Sugar 6.0 and 6.1
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Credentials: true");
$rest_version = (file_exists('../../service/v3/rest.php')) ? 'v3' : 'v2';
require_once('../../service/'.$rest_version.'/rest.php');
