<?php 

$tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
$curl = curl_init();

if (file_exists($tmpfsuitename)) {

} else {
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';
    $url = 'https://suitecrm.pure-electric.com.au/index.php';
    //$url = 'http://loc.suitecrm.com/index.php';
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)

    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    //curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
}
curl_setopt($curl, CURLOPT_URL, 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method='.$argv[1].'&record='.$argv[2]);
//curl_setopt($curl, CURLOPT_URL, 'http://loc.suitecrm.com/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method='.$method.'&record='.$bean->id);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
//curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
$result = curl_exec($curl);
curl_close($curl);