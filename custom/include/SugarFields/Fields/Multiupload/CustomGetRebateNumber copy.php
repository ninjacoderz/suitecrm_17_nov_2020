<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 7/4/17
 * Time: 6:04 PM
 */


date_default_timezone_set('Africa/Lagos');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');
$response_array = array();
$tmpfname = dirname(__FILE__).'/cookie.rec-registry.txt';
if(($_GET['sanden_info'] !="" && $_GET['sanden_info'] != "undefined") || ($_GET['product_type'] == "sanden")){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    require_once('simple_html_dom.php');

    function dlPage($href, $fields) {
        $fields_string = '';
        if (count($fields)) {
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $href);
        curl_setopt($curl, CURLOPT_REFERER, $href);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, count($fields) ? count($fields) : 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }

    $fields = array();
    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $data = dlPage($url, $fields);

    $html = str_get_html($data);

    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }



    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate'] = date('d-M-Y');
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$hidParam'] = '{&quot;data&quot;:&quot;12|#|#&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$State'] = '{&quot;rawValue&quot;:&quot;1491523200000&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDDState']= '{&quot;windowsState&quot;:&quot;0:0:-1:350:-45:1:0:0:1:0:0:0&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDD$C']= '{&quot;visibleDate&quot;:&quot;04/28/2017&quot;,&quot;selectedDates&quot;:[&quot;04/07/2017&quot;]}';

    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';

    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

// Step 2

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules';
    //1E - Water Heating - Electric Boosted Solar Replacing Electric
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '652';



    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

// Step 3

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';



    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);
//print $html;

// Step 4

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }

    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$ExtraButton2';
    $fields['__EVENTARGUMENT'] = 'Click';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue351'] = $_GET['sanden_info'];
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue352'] = $_GET['post_code'];
    $fields['ctl00$ctl00$ContentPlaceHolder1$ExtraButton2'] = 'Calculate';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'SANDEN';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue4107'] = 'Large';

    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html_inside = str_get_html($result);

    $quantites = $html_inside->find('#ContentPlaceHolder1_Content_Editor_VEECQuantityBox td');
    if(isset($quantites[1]) && $quantites[1]->plaintext!= "") $response_array["eligible_veecs"] = $quantites[1]->plaintext;

}

if($_GET['product_type'] == "daikin"){

    $fields = array();
    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $data = dlPage($url, $fields);

    $html = str_get_html($data);

    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }



    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate'] = date('d-M-Y');
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$hidParam'] = '{&quot;data&quot;:&quot;12|#|#&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$State'] = '{&quot;rawValue&quot;:&quot;1491523200000&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDDState']= '{&quot;windowsState&quot;:&quot;0:0:-1:350:-45:1:0:0:1:0:0:0&quot;}';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewActivityDate$DDD$C']= '{&quot;visibleDate&quot;:&quot;04/28/2017&quot;,&quot;selectedDates&quot;:[&quot;04/07/2017&quot;]}';

    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';

    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

// Step 2

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules';
    //1E - Water Heating - Electric Boosted Solar Replacing Electric
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$NewSchedules'] = '664';

    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);

// Step 3

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350';
    $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'Daikin';



    $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
    $curl = curl_init($url);
//set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    curl_close($curl);

    $html = str_get_html($result);
//print $html;

// Step 4

    $fields = array();
    foreach($html->find('input') as $element) {
        $fields[$element->name] = $element->value;
    }
    $veet_code = urldecode($_GET['veet_code']);
    $veet_code_array = explode(",", $veet_code);
    if(count($veet_code_array)) foreach($veet_code_array as $veet_co){
        $fields['__EVENTTARGET'] = 'ctl00$ctl00$ContentPlaceHolder1$ExtraButton2';
        $fields['__EVENTARGUMENT'] = 'Click';

        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue351'] = $veet_co;
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue352'] = $_GET['post_code'];
        $fields['ctl00$ctl00$ContentPlaceHolder1$ExtraButton2'] = 'Calculate';
        $fields['ctl00$ctl00$ContentPlaceHolder1$Content$Editor$ValidValue350'] = 'Daikin';

        $url = 'https://www.veet.vic.gov.au/public/calculator/veeccalculator.aspx';
        $curl = curl_init($url);
    //set the url, number of POST vars, POST data
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));//count($fields)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);

        $html_inside = str_get_html($result);

        $quantites = $html_inside->find('#ContentPlaceHolder1_Content_Editor_VEECQuantityBox td');
        $response_array["eligible_veecs"][$veet_co] = $quantites[1]->plaintext;
    }
}

print(json_encode($response_array));

die();
//$result = json_encode(curl_exec($curl));