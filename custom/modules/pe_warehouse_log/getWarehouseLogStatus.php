<?php
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');


if(count($_POST) > 0){

    $list_id = $_POST['list_id'];
    for($i=0; $i<count($list_id); $i++){
        $whlog =  new pe_warehouse_log();
        $whlog = $whlog->retrieve($list_id[$i]);

        $connoteNumber = $whlog->connote;
        $carrier = $whlog->carrier;
        $post = true;
        get_status($connoteNumber,$carrier,$whlog);
    }
    die();
}


$connoteNumber = str_replace(' ', '', $_GET['connot']);
$carrier = $_GET['carrier'];
get_status($connoteNumber,$carrier);

function get_status($connoteNumber,$carrier,$whlog = ''){
    if($carrier == 'COPE'){
        $url = 'http://tracking.cope.com.au/track.php?consignment='.$connoteNumber;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate";
        $headers[] = "Accept-Language: een-US,en;q=0.9,vi;q=0.8,fr;q=0.7";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close ($ch);
        $html = str_get_html($result);
    
        // get ABN details
        $return_json = array();
        $status = "";
        $date = "";
        $location = "";
        if( count($html->find('table tbody')) != 0 && ($html->find('table tbody')[0]->next_sibling () != null) ) {
            $status = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->next_sibling ()->next_sibling ()->innertext;
            $date = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->innertext;
            $location = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->next_sibling ()->innertext;
        } else {
            $status = $html->find('p b')[0]->innertext;
        }
        if($whlog != ''){
            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }else{
            $return_json['status'] = $status;
    
            $return_json['date'] = $date;
        
            $return_json['location'] = $location;
        
            echo json_encode($return_json);
            die();
        }        
    }
    
    if($carrier == 'Australia Post'){
        $tmpfname = dirname(__FILE__).'/cookie.auspost.txt';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/cssoapi/v2/session');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"accounts@pure-electric.com.au","password":"aPureandTrue2018*"}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Origin: https://auspost.com.au';
        $headers[] = 'Ap_app_id: MYPOST';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://auspost.com.au/mypost-business/auth/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/search');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"query":"'.$connoteNumber.'","shipment_status_terms":"INITIATED,TRACK_SHIPMENT,IN_TRANSIT,AWAITING_COLLECTION,HELD_BY_COURIER,DELIVERED,POSSIBLE_DELAY,CANNOT_BE_DELIVERED,UNSUCCESSFUL_PICKUP,ARTICLE_DAMAGED,LOST,CANCELLED,COMPLETED,REFUNDED,REFUND_IN_PROGRESS,PARTIALLY_REFUNDED,SEALED,LOCKED","offset":0,"page_size":10}');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Account-Number: 62ff9f94f4534eb3b93080c9a3edcd9c';
        $headers[] = 'Origin: https://auspost.com.au';
        $headers[] = 'Content-Type: application/json;charset=UTF-8';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
        $headers[] = 'Auspost-Partner-Id: SENDAPARCEL-UI';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/track?query='.$connoteNumber;
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'Cookie: ObSSOCookieKey=54288714-6b39-4649-b7a7-99bbef2092c1; ; check=true; _gcl_au=1.1.1480256757.1578466418; AMCVS_0A2D38B352782F1E0A490D4C^%^40AdobeOrg=1; _fbp=fb.2.1578466419047.1063995169; s_ecid=MCMID^%^7C23527417613999994974144119717208775680; s_nr=1578466419454; s_cc=true; AMCV_0A2D38B352782F1E0A490D4C^%^40AdobeOrg=1585540135^%^7CMCIDTS^%^7C18270^%^7CMCMID^%^7C23527417613999994974144119717208775680^%^7CMCAAMLH-1579080282^%^7C3^%^7CMCAAMB-1579080282^%^7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y^%^7CMCOPTOUT-1578482682s^%^7CNONE^%^7CMCAID^%^7CNONE^%^7CvVersion^%^7C4.4.0^%^7CMCCIDH^%^7C1786462592; AUSPOST_CSSO_WIDGET_COOKIE_APCN=1012007062; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; ObSSOCookie=^^lxEMpSMf8FZvNoFGPIfUShlZOTD0n1zDp98uBUartgQck1eojgmZV8YE3lHZVaLY8R/pF3Ohg0YGTPShC4OLhJuDkXTrzj7+IksdCW8M5v6Q/PUYr5s7vQ9/tiCxXMvEFr+NtRQ3qdTjq9O4tHZtSarJPekrILUJfNpeiYgAqjawjBzsZ/wMcgXnq5hnjptw7rmuKVcmi7SyiuyccqZFEvFJLfS1PcGo2RisK4bHoPzYgxEKAykWa+DOrqq/J7igVSNSpgAVE+u5uEea96FczlhqYpXNr3Hc/A/GcmvUxiHznQZCnr5aK33P2QPMWeRgd++xckrbwmeh76oUXB77yky8wzKGfPrBNsr14/EIkRDF/4hePymQUlvrK95Ib8n+J3Z49+CPT47WmYxm5hyOLnl72o2zIz6oZlYuuHycg5PRQb1tXqD4SW15JorNgIVD9laO41VVZ8TaSYX7cavdvGJrcieCvOgNX5Xt2V797BEi1Pm6DxiMjC9q53EFQXndCQJ0FLNNWlO+IEZGx03kTpXH0KvOF0hByvEf49FoddU=^^\";';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);

        $documentId = $result->orders[0]->documentId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://digitalapi.auspost.com.au/shipping/v1/track?tracking_ids='.$connoteNumber);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Account-Number: 62ff9f94f4534eb3b93080c9a3edcd9c';
        $headers[] = 'Origin: https://auspost.com.au';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
        $headers[] = 'Auspost-Partner-Id: SENDAPARCEL-UI';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/view/'.$documentId;
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        $status = $result->tracking_results[0]->status;

        if($whlog != ''){
            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }else{
            echo json_encode(array("status" => $status));
            die();
        }
    }

    if($carrier == 'TNT' ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.tnt.com/api/v3/shipment?con='.$connoteNumber.'&searchType=CON&locale=en_GB&channel=OPENTRACK');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: www.tnt.com';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $return_json = json_decode($result,true);
        $status = "";
        if($return_json){
            foreach($return_json['tracker.output']['consignment'] as $res){
                if(strtolower($res['destinationAddress']['country']) == 'australia'){
                    switch ($res['status']['groupCode']){
                        case 'DELRED' :
                            $status = 'Delivered';
                            break;
                        case 'COLING' :
                            $status = 'Collecting';
                            break;
                        case 'COLTED' :
                            $status = 'Collected';
                            break;
                        case 'DELING' :
                            $status = 'Delivering';
                            break;
                        case 'INTRAN' :
                            $status = 'In transit';
                            break;
                    }
                    
                }
            }
        }
        if($whlog != ''){
            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }else{
            echo json_encode(array("status" => $status));
            die();
        }

    }
}
