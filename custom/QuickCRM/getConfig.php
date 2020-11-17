<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Credentials: true");

@ini_set('display_errors', '0');

$res='';
$file='';
global $sugar_config,$log;

if ($_REQUEST['param']=='file') {
	header("Content-Type: text/plain");
	$res= file_get_contents('custom/QuickCRM/'.basename($_REQUEST['name']),true);
}
else {
	header("Content-Type: application/javascript");

	$f='mobile/fielddefs/';



	$files = array();
	switch ($_REQUEST['param']){
		case 'plugin':
			if (file_exists('custom/QuickCRM/plugins/'.basename($_REQUEST['name']))) {
				$files[]= 'custom/QuickCRM/plugins/'.basename($_REQUEST['name']);
			}
			break;
		case 'custom':
			if (isset($sugar_config['quickcrm_includes'])){
				foreach ($sugar_config['quickcrm_includes'] as $file){
					$files[]= $file;
				}
			}
			if (file_exists('custom/QuickCRM/custom.js')){
				$files[]= 'custom/QuickCRM/custom.js';
			}
			break;
		case 'sugar_config':
			$files[]= 'custom/QuickCRM/config.js';
			break;
		case 'users':
			$files[] = 'cache/mobile_js/QuickCRMusers.js';
			break;
		default:
			$files[] = $f.basename($_REQUEST['param']).'.js';
			break;
	}
	$res = '';
	foreach ($files as $file){
		if (is_readable ($file)){
			$res.= file_get_contents($file,true);
		}
		else {
			$msg = 'QuickCRM: Permission denied for ' . $file;
			$log->fatal($msg);
			error_log($msg);
			$res = "QCRM.errorMessage = '$msg'";
			break;
		}
	}
}

ob_clean();
if (isset($_REQUEST['to_json'])){
	echo $_GET["jsoncallback"] . '({response: "' . base64_encode($res) . '"});';
}
else {
	echo $res;
}
die;
