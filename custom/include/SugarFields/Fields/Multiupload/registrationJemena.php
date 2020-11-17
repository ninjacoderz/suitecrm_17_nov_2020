<?php
    require_once(dirname(__FILE__).'/simple_html_dom.php');
    $tmp_elec_cookie = dirname(__FILE__).'/cookie.electricityoutlook.txt';
    $tmp_shark_cookie = dirname(__FILE__).'/cookie.sharklasers.txt';
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

// Get param

    $nmiNumber = $_GET['nmiNumber'];
    $meterNumber = $_GET['meterNumber'];
    $AddressLineOne = $_GET['AddressLineOne'];
    $postcode = $_GET['postcode'];
    $subrb = $_GET['subrb'];
    $$email_cus = $_GET['email_cus'];
    $firstName = $_GET['firstName'];
    $lastName = $_GET['lastName'];
    $contactNumber = $_GET['contactNumber'];
    $count = 1;
    $Authorization = '';
    //function createFakeEmail
    function createFakeEmail($firstName,$lastName,$tmp_shark_cookie){
        // create fake email
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.sharklasers.com/ajax.php?f=set_email_user");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email_user=".$firstName.".".$lastName."^&lang=en^&site=sharklasers.com^&in=+Set+cancel");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_shark_cookie);
        curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_shark_cookie);

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://www.sharklasers.com";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "X-Requested-With: XMLHttpRequest";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        // $headers[] = $Authorization;
        $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
        $headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Referer: https://www.sharklasers.com/inbox";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);
    }
    //function check email
    function uniqueemail($firstName,$lastName,$tmp_shark_cookie,$Authorization){
    
        // validate email
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://electricityoutlook.jemena.com.au/register/uniqueemail?email=".$firstName.".".$lastName."%40sharklasers.com");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_elec_cookie);
        curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_elec_cookie);

        $headers = array();
        $headers[] = 'Host: electricityoutlook.jemena.com.au';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0';
        $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Referer: https://electricityoutlook.jemena.com.au/register/index';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);

        return $result;
    }
    //get list mail from inbox
    function get_list_email($firstName,$lastName,$tmp_shark_cookie,$Authorization){
        
        $date = DateTime::createFromFormat('U.u', microtime(true));
        $date_timestamp =  str_replace('.','',round($date->format('U.u'),3));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.sharklasers.com/ajax.php?f=get_email_list&offset=0&site=sharklasers.com&in=".$firstName.".".$lastName."&_=".$date_timestamp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_shark_cookie);
        curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_shark_cookie);

        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = $Authorization;
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "X-Requested-With: XMLHttpRequest";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Referer: https://www.sharklasers.com/";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }
    //get api token
    function getToken($firstName,$lastName,$tmp_shark_cookie){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.sharklasers.com/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_shark_cookie);
        curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_shark_cookie);
    
        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        curl_close ($ch);
    
        $pattern = "/api_token : '(.*?)',/";
        $match_result = preg_match($pattern,$result,$matches_token);
        if(count($matches_token) == 2){
            $Authorization = 'Authorization: ApiToken '.$matches_token[1];
        }else{
            die();
        }
        return $Authorization;
    }

// 1. Curl create fake email from sharklasers.com

    createFakeEmail($firstName,$lastName,$tmp_shark_cookie);
    $Authorization = getToken($firstName,$lastName,$tmp_shark_cookie);
    $uniqueemail = uniqueemail($firstName,$lastName,$tmp_shark_cookie,$Authorization);
    
// 2. Curl registration step1(Terms and Conditions) and step2(Confirm Your Contact Details)

    //if email valid
    if($uniqueemail == 'true'){
        //curl get token and variable
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://electricityoutlook.jemena.com.au/register/index");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_elec_cookie);
        curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_elec_cookie);
        
        $headers = array();
        $headers[] = 'Host: electricityoutlook.jemena.com.au';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        curl_close ($ch);

        //crawl result and set data
        $html = str_get_html($result);
        $SYNCHRONIZER_TOKEN = $html->find('input[id="org.codehaus.groovy.grails.SYNCHRONIZER_TOKEN"]',0)->value;
        $SYNCHRONIZER_URI = $html->find('input[id="org.codehaus.groovy.grails.SYNCHRONIZER_URI"]',0)->value;
        
        $data_string  = array("org.codehaus.groovy.grails.SYNCHRONIZER_TOKEN" => $SYNCHRONIZER_TOKEN,
                            "org.codehaus.groovy.grails.SYNCHRONIZER_URI"     => $SYNCHRONIZER_URI,
                            "email"                                           => $firstName.".".$lastName."@sharklasers.com");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://electricityoutlook.jemena.com.au/register/sendRegistrationEmail");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_COOKIEJAR,$tmp_elec_cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE,$tmp_elec_cookie);
        
        $headers = array();
        $headers[] = 'Host: electricityoutlook.jemena.com.au';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Referer: https://electricityoutlook.jemena.com.au/register/index';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Content-Length: '.strlen(http_build_query($data_string));
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        curl_close ($ch);

        $pattern  = '/<h1>(.*?)<\/h1>/';
        $returnValue = preg_match($pattern, $result, $matches);
        if ($returnValue != false || $matches != null || count($matches) == 2){
            echo $matches[1];
            die();
        }

    }else{
        echo 'The Fisrt Name and Last Name entered is already in use';
        die();
    }

// 3. Curl get list email in inbox and crawl email get link for step 3,4,5

    //get list email on inbox
    $mail_id = '';
    while(1){
        $result = get_list_email($firstName,$lastName,$tmp_shark_cookie,$Authorization);
        $data_json = json_decode($result);
        $mail_from = $data_json->list[0]->mail_from;
        if($mail_from != 'noreply@electricityoutlook.jemena.com.au'){
            sleep(10);
        }else{
            $mail_id = $data_json->list[0]->mail_id;
            break;
        }
    }

    //get id email confirm
    $date = DateTime::createFromFormat('U.u', microtime(true));
    $date_timestamp =  str_replace('.','',round($date->format('U.u'),3));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.sharklasers.com/ajax.php?f=fetch_email&email_id=mr_".$mail_id."&site=sharklasers.com&in=".$firstName.".".$lastName."&_=".$date_timestamp);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_shark_cookie);
    curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_shark_cookie);

    $headers = array();
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0";
    $headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
    $headers[] = "Accept-Language: en-US,en;q=0.5";
    $headers[] = "Referer: https://www.sharklasers.com/inbox";
    $headers[] = $Authorization;    
    $headers[] = "X-Requested-With: XMLHttpRequest";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);
    $data_return = json_decode($result);

    $pattern = "/<p>http(.*?)<\/p>/s";
    $returnValue = preg_match($pattern,$data_return->mail_body,$matches);
    $continue_link = '';
    if(count($matches) > 1){
        $continue_link = 'http'.$matches[1];
    }else{
        $continue_link ='';
    }

// 4. Curl fill data and submit registion form
    if($continue_link == ''){
        die();
    }
    //get token electric
    // $continue_link = "https://electricityoutlook.jemena.com.au/register/details/daa0e3161e6f42caa3393a1bd4c4f8c8";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $continue_link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_elec_cookie);
    curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_elec_cookie);

    $headers = array();
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $headers[] = "Accept-Language: en-US,en;q=0.5";
    $headers[] = "Dnt: 1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);


    $html = str_get_html($result);
    $context = $html->find('input[id="context"]',0)->value;
    $email_fake = $html->find('input[id="email"]',0)->value;
    $token = $html->find('input[id="token"]',0)->value;
    $SYNCHRONIZER_TOKEN = $html->find('input[id="org.codehaus.groovy.grails.SYNCHRONIZER_TOKEN"]',0)->value;
    $SYNCHRONIZER_URI = $html->find('input[id="org.codehaus.groovy.grails.SYNCHRONIZER_URI"]',0)->value;

    $data_string  = array("agree" => "agree",
                    "confirmPassword" => "KillBill2018!",
                    "contactNumber" => $contactNumber,
                    "context" =>$context,
                    "email" => $email_fake,
                    "firstName" => preg_replace('/\d+/', '', $firstName),
                    "mailingAddressLineOne" => $AddressLineOne,
                    "mailingAddressLineTwo" => "",
                    "mailingAddressPostcode" => $postcode,
                    "mailingAddressSuburb" => $subrb,
                    "meterSerialNumber" => $meterNumber,
                    "nationalMeterIdentifier" => $nmiNumber,
                    "password" => "KillBill2018!",
                    "surname" => preg_replace('/\d+/', '', $lastName),
                    "token" => $token,
                    "org.codehaus.groovy.grails.SYNCHRONIZER_TOKEN" => $SYNCHRONIZER_TOKEN,
                    "org.codehaus.groovy.grails.SYNCHRONIZER_URI"   => $SYNCHRONIZER_URI,
                    );

    // fill data and submit registion form
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://electricityoutlook.jemena.com.au/register/submit");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch , CURLOPT_COOKIEJAR,$tmp_elec_cookie);
    curl_setopt($ch , CURLOPT_COOKIEFILE,$tmp_elec_cookie);

    $headers = array();
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $headers[] = "Accept-Language: en-US,en;q=0.5";
    $headers[] = "Referer: ".$continue_link;
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    $headers[] = "Dnt: 1";
    $headers[] = 'Content-Length: '.strlen(http_build_query($data_string));
    $headers[] = "Connection: keep-alive";
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "Pragma: no-cache";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close ($ch);

    if(strpos( $result,'<h2>There is already an account for your premises</h2>') !== false){
        echo 'There is already an account for your premises';
    }elseif(strpos($result,'The Meter Serial Number which you supplied does not match the National Meter Identifier (NMI) that you entered.')){
        echo 'The Meter Serial Number which you supplied does not match the National Meter Identifier (NMI) that you entered.';
    }elseif(strpos($result,'Access Denied')){
        echo 'Access Denied';
    }else{
        echo $email_fake;
    }
    die();
    