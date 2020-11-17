<?php
// header("Access-Control-Allow-Origin: *");
// header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');
// header('Access-Control-Allow-Headers', 'Accept, X-Requested-With, Content-Type, X-Token-Auth, Authorization, Origin');
session_start();
require_once 'Facebook/autoload.php';

$fb =  new \Facebook\Facebook([
    'app_id' => '334019344476743',
    'app_secret' => 'cb96d6d7666a382c86b45447ddc61dbb',
    'default_graph_version' => 'v2.8',
    'persistant_data_handler' => 'session'
    // . . .
]);
$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
    // Logged in!
    $_SESSION['facebook_access_token'] = (string) $accessToken;
    

  $postURL = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=APICreateFacebookPost&id_install=".$_SESSION['pre_install_photos_c']."&access_token=".$_SESSION['facebook_access_token'];
	header("Location:".$postURL);
    //echo '<a href="' . $postURL . '">Post Image on Facebook!</a>';

  	//$response = $fb->get('/ahtuandn?locale=en_US&fields=access_token,id,name', $_SESSION['facebook_access_token'] );
  	//$userNode = $response->getGraphUser();
  	
    //print_r($userNode);
  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']
}
?>