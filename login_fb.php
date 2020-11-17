<?php
session_start();
require_once 'Facebook/autoload.php';
if( isset($_REQUEST['pre_install_photos_c'])){
    $_SESSION['pre_install_photos_c'] = $_REQUEST['pre_install_photos_c'];
    $_SESSION['record_id'] = $_REQUEST['record_id'];
    $loginUrl = "https://suitecrm.pure-electric.com.au/login_fb.html";
    echo $loginUrl;
    die;
}
$postURL = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=APICreateFacebookPost&record_id=".$_SESSION['record_id']."&id_install=".$_SESSION['pre_install_photos_c']."&access_token=".$_REQUEST['accessToken']."&template=".$_REQUEST['template'];
header("Location:".$postURL);
?>