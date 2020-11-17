<?php
require 'lib/XeroOAuth.php';

define ( 'BASE_PATH', dirname(__FILE__) );
define ( "XRO_APP_TYPE", "Private" );
define ( "OAUTH_CALLBACK", "oob" );
$useragent = "XeroOAuth-PHP Private App Test";

$signatures = array (
    'consumer_key' => 'GO4QD0KGVFV9SGJR6VRKYDLOUURFEO',
    'shared_secret' => '1NKXG74CVQBPC5DGYSLHMNPUHJKQ4U',
    // API versions
    'core_version' => '2.0',
    'payroll_version' => '1.0',
    'file_version' => '1.0'
);

if (XRO_APP_TYPE == "Private" || XRO_APP_TYPE == "Partner") {
    $signatures ['rsa_private_key'] = BASE_PATH . '/certs/privatekey.pem';
    $signatures ['rsa_public_key'] = BASE_PATH . '/certs/publickey.cer';
}

$XeroOAuth = new XeroOAuth ( array_merge ( array (
    'application_type' => XRO_APP_TYPE,
    'oauth_callback' => OAUTH_CALLBACK,
    'user_agent' => $useragent
), $signatures ) );
include 'tests/testRunner.php';
include 'tests/product_mapping.php';
$initialCheck = $XeroOAuth->diagnostics ();
$checkErrors = count ( $initialCheck );
if ($checkErrors > 0) {
    // you could handle any config errors here, or keep on truckin if you like to live dangerously
    foreach ( $initialCheck as $check ) {
        echo 'Error: ' . $check . PHP_EOL;
    }
} else {
    $session = persistSession ( array (
        'oauth_token' => $XeroOAuth->config ['consumer_key'],
        'oauth_token_secret' => $XeroOAuth->config ['shared_secret'],
        'oauth_session_handle' => ''
    ) );
    $oauthSession = retrieveSession ();

    if (isset ( $oauthSession ['oauth_token'] )) {
        $XeroOAuth->config ['access_token'] = $oauthSession ['oauth_token'];
        $XeroOAuth->config ['access_token_secret'] = $oauthSession ['oauth_token_secret'];

        include 'tests/tests.php';
    }

    testLinks ();
}
