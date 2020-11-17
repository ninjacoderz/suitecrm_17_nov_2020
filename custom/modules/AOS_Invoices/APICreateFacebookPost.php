<?php
session_start();
require_once 'Facebook/autoload.php';

$fb =  new \Facebook\Facebook([
    'app_id' => '334019344476743',
    'app_secret' => 'cb96d6d7666a382c86b45447ddc61dbb',
    'default_graph_version' => 'v2.8',
    'persistant_data_handler' => 'session'
]);
$invoice = new AOS_Invoices();
$invoice->retrieve($_REQUEST['record_id']);
 if( $invoice->install_address_city_c != ""){
    $address_city = $invoice->install_address_city_c;
    $address_state = $invoice->install_address_state_c;
 }else {
    $address_city = $invoice->billing_address_city;
    $address_state = $invoice->billing_address_state;
 }
if( $_REQUEST['template'] == "tem_1"){
    $massage = "Another very happy Pure Electric ".$address_city." ".$address_state." efficient electric customer with their new Sanden Eco Plus heat pump hot water system installed!";
}elseif( $_REQUEST['template'] == "tem_2") {
    $massage = "Another day, another very happy Pure Electric client in ".$address_city." ".$address_state." with their new Sanden Eco Plus heat pump hot water system installed!";
}else {
    $massage = "Pure Electric loves solar PV, and so does our very happy client in ".$address_city." ".$address_state." - smashing bills, smashing emissions. Get a free quote to see how much you can save -> https://pure-electric.com.au/getafreequote";
}

    $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
    $dirName        = $_REQUEST['id_install'];
    $folderName     = $path . $dirName . '/';
    $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($_REQUEST['access_token']);
    $fb->setDefaultAccessToken($longLivedToken);
    $getforeverAccessToken = $fb->sendRequest('GET', '/pureelectricsolutions', ['fields' => 'access_token'])->getDecodedBody();
    $foreverPageAccessToken = $getforeverAccessToken['access_token'];
    $fb->setDefaultAccessToken($foreverPageAccessToken);
    $get_all_photo = dirToArray($folderName);
    $photoIdArray = [];
    $one_photo = "";
    foreach($get_all_photo as $k => $each_photo ) {
        if( strpos( $each_photo,'New_Install_Photo') != false ){
    
            $params = [
                    'source' => $fb->fileToUpload($folderName.$each_photo),
                    'published' => false,
                    ];
            $uploadImage = $fb->post('/pureelectricsolutions/photos',$params,$foreverPageAccessToken);
            $photoId = $uploadImage->getGraphNode()->asArray();
            $image =   $photoId['id'];
            $photoIdArray["attached_media"][$k] = '{"media_fbid":"' . $image . '"}';
            $one_photo = $each_photo;
        }
    }
    if (  count($photoIdArray["attached_media"]) == 1 ){
        $params = [
            'source' => $fb->fileToUpload($folderName.$one_photo),
            'published' => false,
            'unpublished_content_type' => "DRAFT",
            'message' => $massage,
            ];
        try {
            $response = $fb->post('/pureelectricsolutions/photos',$params,$foreverPageAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Unsuccessful!';
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Unsuccessful!';
            exit;
        }
        echo "Success";
    }elseif (  count($photoIdArray["attached_media"]) > 1 ){
        $photoIdArray['published'] = false;
        $photoIdArray['unpublished_content_type'] = "DRAFT";
        $photoIdArray['message'] = $massage;
        try {
            $response =  $fb->post('/pureelectricsolutions/feed',$photoIdArray,$foreverPageAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Unsuccessful!';
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Unsuccessful!';
            exit;
        }
        echo "Success";
    }else {
        echo "Unsuccessful! No New Install Photos Tagged";
    }
    
function dirToArray($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}
?>