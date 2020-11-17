<?php

date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

require_once(dirname(__FILE__).'/simple_html_dom.php');

$curl = curl_init();
$tmpfname = dirname(__FILE__).'/cookiesolargain.txt';

$username = "matthew.wright";
$password =  "MW@pure733";

$leadID = urldecode($_GET['leadID']);

$url = 'https://crm.solargain.com.au/APIv2/customers/';

//set the url, number of POST vars, POST data
$custommer_type = urldecode($_GET['customer_type']);

$data = array(
        "CustomerTypeID" => $custommer_type, //last_name
        "LastName" => htmlspecialchars_decode(urldecode($_GET['last_name']),ENT_QUOTES),
        "FirstName" => htmlspecialchars_decode(urldecode($_GET['first_name']),ENT_QUOTES),
        "TradingName" => "Trading Name",
        "ABN" =>	"ABN",
        "Phone"	=> urldecode($_GET['phone_work']),
        "Mobile" => urldecode($_GET['phone_mobile']), //phone_mobile
        "Email" =>	urldecode($_GET['email']),//email
        "Address" => array(
            "Street1"	=> urldecode($_GET['primary_address_street']),//
            "Street2"	=> "",
            "Locality" =>	urldecode($_GET['primary_address_city']),
            "State" => 	urldecode($_GET['state']),
            "PostCode"	=> urldecode($_GET['postalcode']) //postalcode
        ),
        "Category" => array(
            "Value" => 1,
        ),
        "OptIn" => true,
        "Notes" => array(array(
            "ID" => 0,
        )),
    );

$data_string = json_encode($data);


curl_setopt($curl, CURLOPT_URL, $url);
//curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);

curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl,CURLOPT_ENCODING , "gzip");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($data_string),
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/Lead/Create",
    )
);

$custommer = json_decode(curl_exec($curl));


// Pushing the sites

$url = 'https://crm.solargain.com.au/APIv2/installs';

//set the url, number of POST vars, POST data
$data = array(
    "AccountHolderDateOfBirth" => array(
        "Date" => "01/01/1977"
    ),

    "Address" => array(
        "Street1" =>	urldecode($_GET['primary_address_street']),
        "Street2"	=> "",
        "Locality" =>	urldecode($_GET['primary_address_city']),
        "State"	=> urldecode($_GET['state']),
        "PostCode" =>	urldecode($_GET['postalcode'])
    ),
    "RoofType" =>	urldecode($_GET['roof_type']), //roof_type,
    "Notes" => array(array(
        "ID" => 0,
    )),
    "BuildHeight" => array(
        "ID" =>	urldecode($_GET['build_height']),
    ),
    "MainsTypeID"	=> urldecode($_GET['main_type']),
    //BillingName	"Matthew Wright"
    "ConnectionType" =>	urldecode($_GET['connection_type']),
    "MeterNumber"	=> urldecode($_GET['meter_number']),
    "MeterPhase" => 1,
    "AccountNumber" =>	urldecode($_GET['account_number']),
    "BillingName"	=> urldecode($_GET['billing_name']),
    "EnergyRetailer" => array(
        "ID" => urldecode($_GET['energy_retailer']),
    ),
    "NetworkOperator" => array(
        "ID" => urldecode($_GET['distributor']),
    ),
);
if(urldecode($_GET['nmi_number']) !== ""){
    $data["NMINumber"]	= urldecode($_GET['nmi_number']);
}
$data_string = json_encode($data);


curl_setopt($curl, CURLOPT_URL, $url);
//curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);

curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($data_string),
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/Lead/Create",
    )
);

$installer = json_decode(curl_exec($curl));


// Pushing the Lead

$url = 'https://crm.solargain.com.au/APIv2/leads/';


//set the url, number of POST vars, POST data

$primary_address_state = urldecode($_GET['primary_address_state']);
$primary_address_name = "PERTH";
$primary_address_id = 1;
if($primary_address_state == "WA State"){
    $primary_address_name = "PERTH";
    $primary_address_id= 1;
}
if ($primary_address_state == "VIC"){
    $primary_address_name = "VIC";
    $primary_address_id= 3;
}
if ($primary_address_state == "QLD"){
    $primary_address_name = "QLD";
    $primary_address_id= 4;
}
if ($primary_address_state == "NSW"){
    $primary_address_name = "SYDNEY";
    $primary_address_id= 9;
}

if ($primary_address_state == "SA"){
    $primary_address_name = "SOUTH AUSTRALIA";
    $primary_address_id= 16;
}
if ($primary_address_state == "ACT"){
    $primary_address_name = "ACT";
    $primary_address_id= 2;
}

$data = array(
    "ID" => 0,
    "Status" => "New",
    "IsLost" => false,
    "IsConverted" => false,
    "Created" => "0001-01-01T00:00:00",
    "RoofType" =>	"Tile",

    "AssignedUser" => array(
        "ID" => 475,
        "Name" => "Matthew Wright",
        "Enabled"=>false,
        "Administrator"=>false,
        "IsDealership"=>false
    ),

    "AssignedUnit" => array(
        "ID" => $primary_address_id,
        "Name"=>$primary_address_name,
        "RailLength" => 0,
        "IsDealership" => false,
        "OrdersEMail"=> "sg.orders@solargain.com.au",
        "HotWaterOrdersEMail" => "sg.shw.orders@solargain.com.au",
        "RequiresDesignApproval" => false
    ),



    "NextActionDate" => array (
        "Date" => date('d/m/Y', time() + 24*60*60),
        "Time"=>"9:00 AM"
    ),

    "NextActionDateDays"=> 0,
    "LastActionDateDays"=>0,
    "EMails"=>0,
    "Calls"=>0,
    "Editable"=>true,



    "Notes"=>array(
        array(
            "ID"=>0,
            "Text"=>urldecode($_GET['notes']),
            "Type"=> array(
                "ID"=>1,
                "Name"=>"General",
                "RequiresComment"=>true
            )
        )
    ),
    "Errors"=> array(),

    "Customer" => $custommer,
    "Install" =>$installer,
    "Source"=> array(
        "ID" => 501,
        "Description" => "Beyond the Grid",
        "Category" => array(
            "ID" =>5,
            "Description" =>"3rd Party Partners",
            "Order" => 5
        ),
        "Active" =>true,
        "Default" =>false,
        "Order" =>99,
        "StatusReport" =>false,
        "Leads" =>0,
        "Quotes" =>0,
        "Orders" =>0
    ),
    "SystemType" => "PV",
    "SystemSize"=>urldecode($_GET['system_size']),
    "UnitsPerDay"=>urldecode($_GET['unit_per_day']),
    "DollarsPerMonth"=>urldecode($_GET['dolar_month']),
    "NumberOfPeople"=>urldecode($_GET['number_of_people']),
);

$data_string = json_encode($data);


curl_setopt($curl, CURLOPT_URL, $url);
//curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);

curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, 1);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.8,vi;q=0.6",
        "Accept-Encoding: 	gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " .strlen($data_string),
        "Origin: https://crm.solargain.com.au",
        "Authorization: Basic ".base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/Lead/Create",
    )
);
$result = curl_exec($curl);
//$result = json_encode(curl_exec($curl));

print_r($result);

$record = urldecode($_GET['record']);
$bean = BeanFactory::getBean("Leads", $record);
$bean -> solargain_lead_number_c = $result;
$bean->save();

die();