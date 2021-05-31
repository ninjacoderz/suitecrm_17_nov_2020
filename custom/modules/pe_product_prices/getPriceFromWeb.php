<?php
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

$supplier_id = $_REQUEST['supplier_id'];
$web = urldecode($_REQUEST['web']);
$record_id = $_REQUEST['record_id'];
$bean = new pe_product_prices();
$bean->retrieve($record_id);
if ($bean->id) {
    $price ='';
    switch ($supplier_id) {
        // case '789f75aa-6918-dca7-d563-5ea248f01d4c': //SPRINGERS SOLAR = c29c99ff-83bd-a46c-504c-60ac48462af4
        //     $price = priceSpringerSolar($web);
        //     break;
        // case '789f75aa-6918-dca7-d563-5ea248f01d4c': //MC4 Connect = 4c0f2eb6-b037-bb29-f55b-60ac4134cd0a
        //     $price = priceMC4Connect($web);
        //     break;
        // case '789f75aa-6918-dca7-d563-5ea248f01d4c': //TradeZone = 2bdc4cd7-d215-0cc6-4265-60ac757905fb
        //     $price = priceTradeZone($web);
        //     break;
        case '789f75aa-6918-dca7-d563-5ea248f01d4c': //EVolution Australia = 12a49a38-638a-07c7-ad5b-60b42f79cc34
            $price = priceEVolutionAustralia($web);
            break;
                
        default:
            $price ='Not yet!';
            break;
    }   
    
    if (is_numeric($price)) {
        echo $price;
    } else {
        echo 'Fail' . $price;
    }
} else {
    echo 'Product Price is not exist!';
}
die;

/////*****FUNTION DECLARE */

function priceSpringerSolar($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"90\", \"Google Chrome\";v=\"90\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Error:' . curl_error($curl);
    }
    curl_close($curl);
    $html = str_get_html($result);

    $data_return = $html->find('.oe_inc_price .oe_currency_value')[0]->innertext;
    // $price_ex = $html->find('.oe_ex_price .oe_currency_value')[0]->innertext;
    return $data_return;

}

function priceMC4Connect($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 

    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: mc4.com.au';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"90\", \"Google Chrome\";v=\"90\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);
    $html = str_get_html($result);
    $data_return = $html->find('.product__price .price__regular .price-item')[0]->innertext;
    return trim(str_replace('$','',$data_return));
}

function priceTradeZone($url) {
    $tmpfname = dirname(__FILE__).'/cookieTradeZone.txt';
    $url_login = 'https://www.tradezone.com.au/customer/account/login/';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url_login);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
    $headers[] = "Accept: */*";
    $headers[] = "Connection: Keep-Alive";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER,  0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);         
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    
    $content = curl_exec($curl); 
    if (preg_match('/(<p class="hello".*?<\/p>)/is', $content, $curleck)) {
        //nothing - have login
    } else {
        $fields = getFormFields($content);
        $fields['login[username]'] = 'kanji2222@gmail.com';
        $fields['login[password]'] = 'thienlong';
    
        $postfields = http_build_query($fields); 
        $login_post = 'https://www.tradezone.com.au/customer/account/loginPost/';
        curl_setopt($curl, CURLOPT_URL, $login_post); 
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields); 
        $result = curl_exec($curl);  
    }
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    $html = str_get_html($result);
    
    $price = $html->find('.price-container .price-number')[0]->innertext;
    return $price;
}

function getFormFields($data) {
    if (preg_match('/(<form action="https:\/\/www.tradezone.com.au\/customer\/account\/loginPost.*?<\/form>)/is', $data, $matches)) {
        $inputs = getInputData($matches[1]);

        return $inputs;
    } else  {
        die('Didnt find login form');
    }
}

function getInputData($form) {
    $inputs = array();

    $elements = preg_match_all('/(<input[^>]+>)/is', $form, $matches);

    if ($elements > 0) {
        for($i = 0; $i < $elements; $i++) {
            $el = preg_replace('/\s{2,}/', ' ', $matches[1][$i]);

            if (preg_match('/name=(?:["\'])?([^"\'\s]*)/i', $el, $name)) {
                $field  = $name[1];
                $value = '';

                if (preg_match('/value=(?:["\'])?([^"\'\s]*)/i', $el, $val)) {
                    $value = $val[1];
                }

                $inputs[$field] = $value;
            }
        }
    }

    return $inputs;
}

function priceEVolutionAustralia($url) {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Authority: e-station.com.au';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"90\", \"Google Chrome\";v=\"90\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'Accept: */*';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Error:' . curl_error($curl);
    }
    curl_close($curl);
    
    $html = str_get_html($result);
    $price = $html->find('.modal_price span[content]')[0]->getAttribute('content');
    return $price;
}

