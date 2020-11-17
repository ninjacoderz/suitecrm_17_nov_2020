<?php

array_push($job_strings, 'custom_getstatus');

function custom_getstatus(){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * FROM pe_warehouse_log WHERE deleted = 0 LIMIT 1";
    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret)) {
        if (isset($row) && $row != null) {
            $whlog =  new pe_warehouse_log();
            $whlog = $whlog->retrieve($row['id']);
            $connoteNumber = $whlog->connote;
            $carrier = $whlog->carrier;
            get_status($connoteNumber,$carrier,$whlog);
        }
    }
}


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
        }    
    }
    
    if($carrier == 'Australia Post'){
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, "https://digitalapi.auspost.com.au/shipmentsgatewayapi/watchlist/shipments?trackingIds=". $connoteNumber);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://auspost.com.au";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9,vi;q=0.8,fr;q=0.7";
        $headers[] = "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Referer: https://auspost.com.au/mypost/track/";
        $headers[] = "Cookie: check=true; AMCVS_0A2D38B352782F1E0A490D4C%40AdobeOrg=1; s_cc=true; AAMC_auspost_0=REGION%7C3; aam_uuid=64881562324747079812910258178743754564; s_nr=1534493867149; s_sq=%5B%5BB%5D%5D; mbox=PC#b5516678ef6740219696a937fbf4da49.24_11#1597749118|session#aa59b9f51a064e278dd8748e1b800abc#1534520506; AMCV_0A2D38B352782F1E0A490D4C%40AdobeOrg=1406116232%7CMCIDTS%7C17761%7CMCMID%7C65134744044665115972886065900749150628%7CMCAAMLH-1535123447%7C3%7CMCAAMB-1535123447%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1534525847s%7CNONE%7CMCSYNCSOP%7C411-17768%7CMCAID%7CNONE%7CvVersion%7C2.5.0; prevUrl=https%3A%2F%2Fauspost.com.au%2Fmypost%2Ftrack%2F%23%2Fdetails%2F60037989777090; s_ppn=auspost%3Aone%20track%3Amypost%3Atrack%3Ahome";
        $headers[] = "Api-Key: d11f9456-11c3-456d-9f6d-f7449cb9af8e";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $result_reson = json_decode($result,true);
        if(isset($result_reson) && isset($result_reson[0]['shipment']['articles'][0]['trackStatusOfArticle'])){
            $status = $result_reson[0]['shipment']['articles'][0]['trackStatusOfArticle'];
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
        }

    }
}
