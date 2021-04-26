<?php
require dirname(__FILE__) .'/../../../'. '/vendor/autoload.php';

// if (!$_SESSION['abcd']) {
//     $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
//     login_suitecrm('http://locsuitecrm.com/', $tmpfsuitename);
// }

$oauth_creds = 'custom/modules/Contacts/peopleAPI.json';

if (!file_exists($oauth_creds)) {
    echo "<h3 class='warn'>
            Warning: You need to set the location of your OAuth2 Client Credentials from the
            <a href='http://developers.google.com/console'>Google API console</a>.
            </h3>";
    return;
}

// $redirect_uri = 'http://localhost/index.php?entryPoint=createGoogleContact';
$redirect_uri = 'https://suitecrm.devel.pure-electric.com.au/index.php?entryPoint=createGoogleContact';
// $redirect_uri = 'http://localhost/index.php?entryPoint=createGoogleContact';
$scopes = [
    Google_Service_Oauth2::USERINFO_EMAIL,
    Google_Service_Oauth2::USERINFO_PROFILE,
    Google_Service_PeopleService::CONTACTS,
    Google_Service_PeopleService::CONTACTS_READONLY,
];

$google_client = new Google_Client();
$google_client->setAuthConfig($oauth_creds);
$google_client->setRedirectUri($redirect_uri);
$google_client->addScope($scopes);

if (isset($_REQUEST['record_id'])) {
    //id contact
    $google_client->setConfig('state', $_REQUEST['record_id']);
}

// //start session on web page
// session_destroy();
// session_start();

if (isset($_GET["code"]) && !empty($_REQUEST['state'])) { 
    //It will Attempt to exchange a code for an valid authentication token.
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
   
    //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
    if(!isset($token['error'])) {
        // //check profile info
        $google_client->setAccessToken($token['access_token']);
        $google_oauth = new Google_Service_Oauth2($google_client);
        $google_account_info = $google_oauth->userinfo->get();
        // die;
        //get data Contact
        $contact = BeanFactory::getBean('Contacts',$_REQUEST['state']);

        $optParams = [
            'query' => $contact->email1,
            'readMask' => 'names,emailAddresses,phoneNumbers,addresses,biographies',
        ];
        $service = new Google_Service_PeopleService($google_client);
        $cus = $service->people->searchContacts($optParams);
        if (count($cus->getResults()) > 0) {
            $html = '<p>Contact has already exists in <strong>'.$google_account_info->email.'</strong>!</p>';
            foreach ($cus->getResults() as $k => $person) {
                $info = $person->getPerson();
                $link = str_replace('people', 'person', $info->getresourceName());
                $name = $info->getNames()[0]->getDisplayName();

                $html .='<p> >>> <a target="_blank" href="https://contacts.google.com/'.$link.'">'.$name.'</a></p>';
            }
            echo $html;
            die;
        } else { //create new
            $info_new = [
                'names' => [
                    [
                        'givenName' => $contact->first_name, //first_name
                        'familyName' => $contact->last_name, //last_name
                    ]
                ],
                'emailAddresses' => [
                    [
                        'value' => $contact->email1, //email1
                    ],
                    [
                        'value' => $contact->email2, //email2
                    ]
                ],
                'phoneNumbers' => [
                    [
                        'value' => $contact->phone_home,
                        'type' => 'home'
                    ],
                    [
                        'value' => $contact->phone_mobile,
                        'type' => 'mobile'
                    ],
                ],
                'addresses' => [
                    [
                        'streetAddress' => $contact->primary_address_street,
                        'extendedAddress' => '', //line 2
                        'city' => $contact->primary_address_city, //suburb
                        'region' => $contact->primary_address_state, //state
                        'postalCode' => $contact->primary_address_postalcode,
                        'country' => !empty($contact->primary_address_country) ? $contact->primary_address_country :'AU',
                    ],
                ],
                //notes
                'biographies' => [ 
                    [
                        'value' => $contact->description,
                    ],
                ],
            ];
            $contact_new = new Google_Service_PeopleService_Person($info_new);
            $exe = $service->people->createContact($contact_new);
            echo '<p>Contact has been created for <strong>'.$google_account_info->email.'</strong></p>';
            die;
        }
    } else {
        echo $token['error'];
        die;
    }
} 
   
if(!isset($_SESSION['access_token'])) {
    //Create a URL to obtain user authorization
    // $login_button = '<a  href="'.$google_client->createAuthUrl().'">Login Google</a>';
    echo $google_client->createAuthUrl();
}

// function login_suitecrm($url, $tmpfsuitename) {
//     // $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
//     $fields = array();
//     $fields['user_name'] = 'admin';
//     $fields['username_password'] = 'pureandtrue2020*';
//     $fields['module'] = 'Users';
//     $fields['action'] = 'Authenticate';

//     // $url = $main_url;
//     $curl = curl_init();

//     curl_setopt($curl, CURLOPT_URL, $url);
//     curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
//     curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//     curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
//     curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
//     curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
//     $result = curl_exec($curl);
// }


?>

