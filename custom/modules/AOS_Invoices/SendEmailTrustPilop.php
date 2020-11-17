<?php
    $record_id = trim($_REQUEST['record_id']);
    $action = trim($_REQUEST['action']);
    $module= trim($_REQUEST['module']);

    if($action == 'EditView' && $module == 'Contact') {
        $record_id_contact = $record_id;
    }elseif($action == 'DetailView' && $module == 'AOS_Invoices'){
        $bean_invoice =  new AOS_Invoices();
        $bean_invoice->retrieve($record_id);
        if($bean_invoice->id == ''){
            echo 'error';
            die();
        }else{
            $record_id_contact = $bean_invoice->billing_contact_id;
        }
    }
    
    $bean_contact =  new Contact();
    $bean_contact->retrieve($record_id_contact);
    if($bean_contact->id == ''){
        echo 'error';
        die();
    }


    //login
    $tmpftrustpilot = dirname(__FILE__).'/cookie.sharklasers.txt';
    $fields = array();
    $fields['returnSecureToken'] = true;
    $fields['email'] = "info@pure-electric.com.au";
    $fields['password'] = "tPureandTrue2019*";

    $url = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPassword?key=AIzaSyDzj0FG4JS6bOUHLhbubOR_CVaH8EGSvO0';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    $data_json = json_decode($result);

    $fields = array();
    $fields['token'] = $data_json->idToken;
    $fields['username'] = "info@pure-electric.com.au";

    $url = 'https://authenticate.trustpilot.com/business-login?redirect_uri=https%3A%2F%2Fbusinessapp.b2b.trustpilot.com%2Fonboarding%2Fchecklist%3Flocale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com&v=2';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    $data_json = json_decode($result);
    $source =  $data_json->redirectUrl;


    $fields = array (
        'integrations' => 
        array (
          'Intercom' => false,
        ),
        'context' => 
        array (
          'page' => 
          array (
            'path' => '/',
            'referrer' => '',
            'search' => '?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
            'title' => 'Sign in - Trustpilot Business',
            'url' => 'https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
          ),
          'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
          'library' => 
          array (
            'name' => 'analytics.js',
            'version' => '3.9.0',
          ),
          'campaign' => 
          array (
          ),
        ),
        'traits' => 
        array (
          'email' => 'info@pure-electric.com.au',
        ),
        'userId' => '9b265a104a8ab3c3ade4be2479c3612c58584d4d',
        'messageId' => 'ajs-e93d7f05b33b6910dfcd6ac16980d195',
        'anonymousId' => '125cd9fc-a7e8-4c88-b9f8-627dc0618db7',
        'timestamp' => '2019-07-19T01:40:08.005Z',
        'type' => 'identify',
        'writeKey' => 'ikt6azxah8',
        'sentAt' => '2019-07-19T01:40:08.007Z',
        '_metadata' => 
        array (
          'bundled' => 
          array (
            0 => 'Amplitude',
            1 => 'Hotjar',
            2 => 'Segment.io',
          ),
          'unbundled' => 
          array (
            0 => 'Customer.io',
          ),
        ),
      );

    $url = 'https://api.segment.io/v1/i';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:^%^2F^%^2Fbusinessapp.b2b.trustpilot.com^%^3Fpath^%^3D^%^252Fonboarding^%^252Fchecklist^%^26locale^%^3Den-US^&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x^&locale=en-US^&response_type=code^&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);



    $fields = array (
        'integrations' => 
        array (
          'Intercom' => false,
        ),
        'context' => 
        array (
          'page' => 
          array (
            'path' => '/',
            'referrer' => '',
            'search' => '?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
            'title' => 'Sign in - Trustpilot Business',
            'url' => 'https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
          ),
          'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
          'library' => 
          array (
            'name' => 'analytics.js',
            'version' => '3.9.0',
          ),
          'campaign' => 
          array (
          ),
        ),
        'traits' => 
        array (
          'email' => 'info@pure-electric.com.au',
        ),
        'userId' => '9b265a104a8ab3c3ade4be2479c3612c58584d4d',
        'messageId' => 'ajs-e93d7f05b33b6910dfcd6ac16980d195',
        'anonymousId' => '125cd9fc-a7e8-4c88-b9f8-627dc0618db7',
        'timestamp' => '2019-07-19T01:40:08.005Z',
        'type' => 'identify',
        'writeKey' => 'ikt6azxah8',
        'sentAt' => '2019-07-19T01:40:08.007Z',
        '_metadata' => 
        array (
          'bundled' => 
          array (
            0 => 'Amplitude',
            1 => 'Hotjar',
            2 => 'Segment.io',
          ),
          'unbundled' => 
          array (
            0 => 'Customer.io',
          ),
        ),
      );

    $url = 'https://api.amplitude.com/';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'checksum=111977ce738f1592f5a3a39f9ad41c6a&client=de1e2fc13cf22ef1024015ecc1bb8ccd&e=%5B%7B%22device_id%22%3A%22f8592d0a-7f5b-4ca9-a326-fda59d8b9c8dR%22%2C%22user_id%22%3A%229b265a104a8ab3c3ade4be2479c3612c58584d4d%22%2C%22timestamp%22%3A1563501767661%2C%22event_id%22%3A287%2C%22session_id%22%3A1563500401416%2C%22event_type%22%3A%22%24identify%22%2C%22version_name%22%3Anull%2C%22platform%22%3A%22Web%22%2C%22os_name%22%3A%22Chrome%22%2C%22os_version%22%3A%2275%22%2C%22device_model%22%3A%22Windows%22%2C%22language%22%3A%22vi-VN%22%2C%22api_properties%22%3A%7B%7D%2C%22event_properties%22%3A%7B%7D%2C%22user_properties%22%3A%7B%22%24set%22%3A%7B%22email%22%3A%22info%40pure-electric.com.au%22%2C%22id%22%3A%229b265a104a8ab3c3ade4be2479c3612c58584d4d%22%7D%7D%2C%22uuid%22%3A%22078bff71-3ccc-44fc-bc4f-33e7feaa67e4%22%2C%22library%22%3A%7B%22name%22%3A%22amplitude-js%22%2C%22version%22%3A%225.2.2%22%7D%2C%22sequence_number%22%3A680%2C%22groups%22%3A%7B%7D%2C%22group_properties%22%3A%7B%7D%2C%22user_agent%22%3A%22Mozilla%2F5.0%20%28Windows%20NT%2010.0%3B%20Win64%3B%20x64%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F75.0.3770.142%20Safari%2F537.36%22%7D%5D&upload_time=1563501767662&v=2');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:^%^2F^%^2Fbusinessapp.b2b.trustpilot.com^%^3Fpath^%^3D^%^252Fonboarding^%^252Fchecklist^%^26locale^%^3Den-US^&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x^&locale=en-US^&response_type=code^&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);



    $fields = array (
        'integrations' => 
        array (
          'Intercom' => false,
        ),
        'context' => 
        array (
          'page' => 
          array (
            'path' => '/',
            'referrer' => 'https://businessapp.b2b.trustpilot.com/',
            'search' => '?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
            'title' => 'Sign in - Trustpilot Business',
            'url' => 'https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
          ),
          'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
          'library' => 
          array (
            'name' => 'analytics.js',
            'version' => '3.9.0',
          ),
          'campaign' => 
          array (
          ),
        ),
        'properties' => 
        array (
          'businessUnitId' => '5caec20458378f0001217235',
        ),
        'event' => 'BusinessUserLoggedIn',
        'messageId' => 'ajs-ea388e1fae4b67d8e636f3fbf211951a',
        'anonymousId' => '729b58f7-6207-4b12-97a7-72cfbc67c12c',
        'timestamp' => '2019-07-19T02:02:47.975Z',
        'type' => 'track',
        'writeKey' => 'ikt6azxah8',
        'userId' => '9b265a104a8ab3c3ade4be2479c3612c58584d4d',
        'sentAt' => '2019-07-19T02:02:47.981Z',
        '_metadata' => 
        array (
          'bundled' => 
          array (
            0 => 'Amplitude',
            1 => 'Hotjar',
            2 => 'Segment.io',
          ),
          'unbundled' => 
          array (
            0 => 'Customer.io',
          ),
        ),
      );
    $url = 'https://api.segment.io/v1/t';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:^%^2F^%^2Fbusinessapp.b2b.trustpilot.com^%^3Fpath^%^3D^%^252Fonboarding^%^252Fchecklist^%^26locale^%^3Den-US^&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x^&locale=en-US^&response_type=code^&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);





    

    $fields = array (
        'integrations' => 
        array (
          'Intercom' => false,
        ),
        'context' => 
        array (
          'page' => 
          array (
            'path' => '/',
            'referrer' => 'https://businessapp.b2b.trustpilot.com/',
            'search' => '?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
            'title' => 'Sign in - Trustpilot Business',
            'url' => 'https://authenticate.trustpilot.com/?redirect_uri=https:%2F%2Fbusinessapp.b2b.trustpilot.com%3Fpath%3D%252Fonboarding%252Fchecklist%26locale%3Den-US&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x&locale=en-US&response_type=code&cookie_domain=.trustpilot.com',
          ),
          'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
          'library' => 
          array (
            'name' => 'analytics.js',
            'version' => '3.9.0',
          ),
          'campaign' => 
          array (
          ),
        ),
        'properties' => 
        array (
          'businessUnitId' => '5caec20458378f0001217235',
        ),
        'event' => 'BusinessUserLoggedIn',
        'messageId' => 'ajs-ea388e1fae4b67d8e636f3fbf211951a',
        'anonymousId' => '729b58f7-6207-4b12-97a7-72cfbc67c12c',
        'timestamp' => '2019-07-19T02:02:47.975Z',
        'type' => 'track',
        'writeKey' => 'ikt6azxah8',
        'userId' => '9b265a104a8ab3c3ade4be2479c3612c58584d4d',
        'sentAt' => '2019-07-19T02:02:47.981Z',
        '_metadata' => 
        array (
          'bundled' => 
          array (
            0 => 'Amplitude',
            1 => 'Hotjar',
            2 => 'Segment.io',
          ),
          'unbundled' => 
          array (
            0 => 'Customer.io',
          ),
        ),
      );
    $url = 'https://api.amplitude.com/';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'checksum=c40af0d23c0aee7401846993ee217aed&client=de1e2fc13cf22ef1024015ecc1bb8ccd&e=%5B%7B%22device_id%22%3A%22f8592d0a-7f5b-4ca9-a326-fda59d8b9c8dR%22%2C%22user_id%22%3A%229b265a104a8ab3c3ade4be2479c3612c58584d4d%22%2C%22timestamp%22%3A1563501767986%2C%22event_id%22%3A394%2C%22session_id%22%3A1563500401416%2C%22event_type%22%3A%22BusinessUserLoggedIn%22%2C%22version_name%22%3Anull%2C%22platform%22%3A%22Web%22%2C%22os_name%22%3A%22Chrome%22%2C%22os_version%22%3A%2275%22%2C%22device_model%22%3A%22Windows%22%2C%22language%22%3A%22vi-VN%22%2C%22api_properties%22%3A%7B%7D%2C%22event_properties%22%3A%7B%22businessUnitId%22%3A%225caec20458378f0001217235%22%7D%2C%22user_properties%22%3A%7B%7D%2C%22uuid%22%3A%22bc8b19fa-1a15-4d02-93b4-588003d195c7%22%2C%22library%22%3A%7B%22name%22%3A%22amplitude-js%22%2C%22version%22%3A%225.2.2%22%7D%2C%22sequence_number%22%3A681%2C%22groups%22%3A%7B%7D%2C%22group_properties%22%3A%7B%7D%2C%22user_agent%22%3A%22Mozilla%2F5.0%20%28Windows%20NT%2010.0%3B%20Win64%3B%20x64%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F75.0.3770.142%20Safari%2F537.36%22%7D%5D&upload_time=1563501767987&v=2');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:^%^2F^%^2Fbusinessapp.b2b.trustpilot.com^%^3Fpath^%^3D^%^252Fonboarding^%^252Fchecklist^%^26locale^%^3Den-US^&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x^&locale=en-US^&response_type=code^&cookie_domain=.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);

    $array_request_token = explode('&code=',$source);
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    $headers = array();
    $headers[] = 'Authority: businessapp.b2b.trustpilot.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
    $headers[] = 'Referer: https://authenticate.trustpilot.com/?redirect_uri=https:^%^2F^%^2Fbusinessapp.b2b.trustpilot.com^%^3Fpath^%^3D^%^252Fonboarding^%^252Fchecklist^%^26locale^%^3Den-US^&client_id=nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x^&locale=en-US^&response_type=code^&cookie_domain=.trustpilot.com';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    //$headers[] = 'Cookie: __auc=53431f0416bcf0dfae1b92f8882; TP.uuid=1ee9ce00-93c1-4baf-99bf-652b06afbae2; jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1NzAzNTc4OTQuMCwiY29uc3VtZXJJZCI6IjVkMjMxOGQ0YmE3MGI4MjRhNzVmMWIyNSIsImZhY2Vib29rSWQiOjAsImhhc0FjY2VwdGVkVGVybXMiOnRydWUsImlzQmxvY2tlZEZvclJlcG9ydGluZyI6ZmFsc2UsImFjY2Vzc1Rva2VuIjoiWWZyT0ExUWQ1MUhQall1T1pPblRwcUE1eDFtUyIsImF1dGhlbnRpY2F0aW9uU291cmNlIjoiVW5rbm93biJ9.UpP7P8I9Kec79kSCLqLcEX_Paa9i4TRFLjds5Daz7bQ; tp-consumer-id=5d2318d4ba70b824a75f1b25; tp-b2b-selected-business-unit-id=5caec20458378f0001217235; tp-b2b-current-language=en-US; hideBusinessAccountsSelectorModal_5caec204546c63001d4f1f29=false; __zlcmid=tCiN7nmhqQhvZH; _vwo_uuid_v2=DA0B0A3E287F50CEB744669B96E535901^|a1c7313d49610404f61fd63123889018; _ga=GA1.2.141726482.1562646558; _gcl_au=1.1.1820546112.1562646558; _fbp=fb.1.1562646558705.276433505; _biz_uid=47f3084478b14a0481ce521535aefbde; _biz_flagsA=^%^7B^%^22Version^%^22^%^3A1^%^2C^%^22XDomain^%^22^%^3A^%^221^%^22^%^7D; hubspotutk=6a80d65ecc7367807134065dfb1eb6c2; trustpilotABTest=^{^^Invitations';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        die();
    }
    curl_close($ch);

    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://authenticate.b2b.trustpilot.com/v1/oauth/accesstoken');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Access-Control-Request-Method: POST';
    $headers[] = 'Origin: https://businessapp.b2b.trustpilot.com';
    $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com/';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
    $headers[] = 'Access-Control-Request-Headers: content-type';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        die();
    }
    curl_close($ch);


        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://authenticate.b2b.trustpilot.com/v1/oauth/refresh');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Access-Control-Request-Method: POST';
    $headers[] = 'Origin: https://businessapp.b2b.trustpilot.com';
    $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com/';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
    $headers[] = 'Access-Control-Request-Headers: content-type';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        die();
    }
    curl_close($ch);


    $fields = array();
    $fields['redirectUri'] = urlencode(str_replace("/?path","?path",$array_request_token[0]));
    $fields['authorizationCode'] = $array_request_token[1];

    $url = 'https://authenticate.b2b.trustpilot.com/v1/oauth/accesstoken';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Origin: https://authenticate.trustpilot.com';
    $headers[] = 'Accept-Encoding: application/json';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: */*';
    $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com';
    $headers[] = 'Authority: www.trustpilot.com';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
  
    $json_data_totken = json_decode($result);
    $token = $json_data_totken->accessToken;

    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://businessapp.b2b.trustpilot.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: businessapp.b2b.trustpilot.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
    $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com/?path=^%^2Fonboarding^%^2Fchecklist^&locale=en-US^&code='.$array_request_token[1];
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        die();
    }
    curl_close($ch);

    //send email
    $fields = array (
        'invitations' => 
        array (
        0 => 
        array (
            'consumerEmail' => $bean_contact->email1,
            'consumerName' => $bean_contact->first_name .' ' .$bean_contact->last_name,
            'referenceNumber' => '1',
            'locale' => 'en-AU',
            'senderEmail' => 'noreply.invitations@trustpilotmail.com',
            'senderName' => 'Pure Electric',
            'replyTo' => 'info@pure-electric.com.au',
            'serviceReviewInvitation' => 
            array (
            'preferredSendTime' => date('Y-m-d\TH:i:s',(time()- 60*60*11)),
            'templateId' => '529c0abfefb96008b894ad02',
            ),
            'source' => 'ManualInputInvitation',
        ),
        ),
    );

    $url = 'https://invitations-api.trustpilot.com/v1/private/business-units/5caec20458378f0001217235/email-invitations-bulk';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpftrustpilot);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $headers = array();
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com/';
    $headers[] = 'Origin: https://businessapp.b2b.trustpilot.com';
    $headers[] = 'Apikey: nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36';
    $headers[] = 'Content-Type: application/json;charset=UTF-8';
    $headers[] = 'Authorization: Bearer '.$token;
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
  
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
      // step check email send success
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://invitations-api.trustpilot.com/v1/private/business-units/5caec20458378f0001217235/invitations?page=1&perPage=10');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  
      curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  
      $headers = array();
      $headers[] = 'Connection: keep-alive';
      $headers[] = 'Accept: application/json, text/plain, */*';
      $headers[] = 'Apikey: nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x';
      $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36';
      $headers[] = 'Authorization: Bearer '.$token;
      $headers[] = 'Origin: https://businessapp.b2b.trustpilot.com';
      $headers[] = 'Referer: https://businessapp.b2b.trustpilot.com/';
      $headers[] = 'Accept-Encoding: gzip, deflate, br';
      $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
          die();
      }
      curl_close($ch);
      $json_data_page_1 = json_decode($result);
      
      $check_status = FALSE;
      if($json_data_page_1->invitations[0]->recipient->email == $bean_contact->email1){
        $check_status = TRUE;
      }
      if($check_status == 'success'){
        echo 'success';
      }else{
        echo 'error';
      }
die();
