<?php
require __DIR__ . '/vendor/autoload.php';

use XeroPHP\Remote\URL;
use XeroPHP\Remote\Request;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting;

include 'product_mapping.php';
include 'API_Xero.php';
// Start a session for the oauth session storage
session_start();
$key = "
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCiuh3s3vOnvaAWz8TIzNGUtOMG3FqxPVltGQDlxwoXVtsk/ub/
qs5mO8yDwfwBfCAAecMN5Ei7eTrVOFv5TuAEfWWzN9j8x7AUf8rhytz+BgQXwF/a
y0Vbwq7jQU2GeVT2tM4CItaZ0rxFqPmsRTAkC7OnwCnRm1B9CsADuvJCIQIDAQAB
AoGAMQBouIaeyrlQdu4T7P+4cNZTsyIx8UNvJWotGgRo5oRSM37K4tx1kNWbDWYh
0/Sj0mDYOtuuhz3HWKPDFn0I+fYwWWJWRFwveeG9VOfGAqB5cQy3i9T7ytzIez+v
mCGk/ohfgO9yAZwqqkWL+/jXU+JNqFiiMpqcnN2mF/9Tax0CQQDQwDiCmEGV+yCH
e4bS2YaD7uD0UvwQQSJvRxceRXhl2UFTI3cmVTd/6DEBZNkE+kvqyfiB7Q5IoEBU
k+kXXtRPAkEAx48bvrbA2BLDxtHKzElUAsNUKdD1c0zDwRQGTPIVPxfSoNoKu+tP
zCaddApvOhjAfFGRYMmR0yVn6uP41382jwJBALP+mntYx2yIHdNUWrth3s/R4Nwq
1bc6QnPKy49JfXfsbZw/P1SpM/KxBdha2ZmmLGGlhwaYnbFXpECJTPnexZcCQQCi
5PZI3vTba7XTfTyFNPYWq0rwN1mkHG1OFgJunM0rC08rbdCFRLeGdZ7hMgNI8Rtu
X0bEMsWODWKeIijl/zmRAkAyas7vLAgHxA5kjWEgqVzGvJtbE8/PiMjVPOrHjXFp
dfdwKG5J4xHM59PiuX5BwpM3PBbvy0QTgohsPOYIqbeM
-----END RSA PRIVATE KEY-----
";
//These are the minimum settings - for more options, refer to examples/config.php
$config = [
    'oauth' => [
        'callback' => 'http://localhost/',
        'consumer_key' => 'GO4QD0KGVFV9SGJR6VRKYDLOUURFEO',
        'consumer_secret' => '1NKXG74CVQBPC5DGYSLHMNPUHJKQ4U',
        'rsa_private_key' => $key,
    ],
];

$xero = new PrivateApplication($config);

function readHeader($ch, $header){
    return strlen($header);
}

function downloadPDFFile($templateID ,$recordID ,$moduleName){
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';
    //$url = 'http://loc.suitecrm.com/index.php';
    $url = 'https://suitecrm.pure-electric.com.au/index.php';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);

    // $result = explode("\r\n\r\n", $result, 2);
    // $response = json_decode($result[1]);
    // $session_id = $response->id;

    //$source = "http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=".$moduleName."&uid=".$recordID;

    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=generatePdf&templateID=".$templateID."&task=pdf&module=".$moduleName."&uid=".$recordID;
    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, "readHeader");
    $curl_response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($curl_response, 0, $header_size);
    $body = substr($curl_response, $header_size);

    if(!is_dir(dirname(__FILE__)."/files")){
        mkdir(dirname(__FILE__)."/files", 0777, true);
    }
    $destination = dirname(__FILE__)."/files/invoice-". $recordID.".pdf";
    $file = fopen($destination, "w+");
    fputs($file, $body);
    fclose($file);
    curl_close($curl);
}

?>