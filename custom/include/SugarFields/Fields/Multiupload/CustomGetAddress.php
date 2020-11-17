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

header('Content-Type: text/plain');

// logic old - ajax get address by field address 
if($_GET['address'] !== '' && $_GET['address'] !== null){
    $curl = curl_init();
    $address = urldecode($_GET['address']);
    $address = str_replace ( " " , "+" , $address );
    //$address = urlencode($address);
    //echo $address;
    $url = "https://www.energyaustralia.com.au/qt2/app/quoteservice/qas/find?address=".$address."&postcode=";
    //echo $url;
    curl_setopt($curl, CURLOPT_URL, $url);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: www.energyaustralia.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding: 	gzip, deflate, br",
            //"Cookie: AMCV_7D77381753B3C0840A490D4B%40AdobeOrg=1099438348%7CMCIDTS%7C17351%7CMCMID%7C45035313281179543863333722747637113806%7CMCAAMLH-1499706668%7C11%7CMCAAMB-1499771460%7CNRX38WO0n5BH8Th-nqAG_A%7CMCOPTOUT-1499173860s%7CNONE%7CMCAID%7CNONE%7CMCSYNCSOP%7C411-17358%7CvVersion%7C2.1.0; state=VIC; mbox=PC#a7a71660a71d47b29d0f1998c163d8e8.24_4#1562346672|session#06a74b365f66424f892b2daaf1ef57d8#1499168520; s_nr=1499166947067-Repeat; _ga=GA1.3.1283767717.1499101870; _gid=GA1.3.2139730660.1499101870; _ceg.s=oskced; _ceg.u=oskced; check=true; a_postCode=3000; a_state=VIC; __insp_wid=1371082582; __insp_slim=1499166659623; __insp_nv=true; __insp_targlpu=aHR0cHM6Ly93d3cuZW5lcmd5YXVzdHJhbGlhLmNvbS5hdS9ob21lL2VsZWN0cmljaXR5LWFuZC1nYXMvcGxhbnM%3D; __insp_targlpt=RWxlY3RyaWNpdHkgJiBHYXMgUGxhbnMgfCBFbmVyZ3lBdXN0cmFsaWE%3D; AMCVS_7D77381753B3C0840A490D4B%40AdobeOrg=1; s_ppn=Plans; s_cc=true; __insp_norec_sess=true; s_sq=%5B%5BB%5D%5D; EnergyAustraliaHandshakeR=55697SBETHEOU0704009; a_isMovingCurious=true",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
            "Cache-Control: max-age=0",
        )
    );
    
    $result = curl_exec($curl);
    
    print( $result);
}

//dung code - logic get address by field Postcode 
if($_GET['postcode_city'] !== '' && $_GET['postcode_city'] !== null){
    $address = urldecode($_GET['postcode_city']);
    $ch = curl_init();
    $url =  "http://auspost.com.au/api/postcode/search.txt?key=63fa7c3657ea97f3809aacaa42142bae&q=" .$address ."&limit=10&timestamp=" .time();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Accept-Encoding: gzip, deflate";
    $headers[] = "Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
    $headers[] = "Accept: */*";
    $headers[] = "Referer: http://auspost.com.au/postcode/3052";
    $headers[] = "X-Requested-With: XMLHttpRequest";
    $headers[] = "Cookie: AWSELB=0753419F16CFAA9CC51173A905C22325CE8965760485F40B8695CDDE0589BB6622990432B69A859C53AC613154EF2B90B48AE9472200D82B66CF41F20F86A4DDAAE09000A4; _sdsat_landing_page=http://auspost.com.au/postcode/3056^|1538973882596; _sdsat_session_count=1; _sdsat_traffic_source=; check=true; AMCVS_0A2D38B352782F1E0A490D4C^%^40AdobeOrg=1; __utma=33279841.2127541549.1538973883.1538973883.1538973883.1; __utmc=33279841; __utmz=33279841.1538973883.1.1.utmcsr=(direct)^|utmccn=(direct)^|utmcmd=(none); __utmt=1; __utmt_~1=1; __utmt_~2=1; AMCV_0A2D38B352782F1E0A490D4C^%^40AdobeOrg=1406116232^%^7CMCIDTS^%^7C17813^%^7CMCMID^%^7C03300134404649290881135298690342562452^%^7CMCAAMLH-1539578682^%^7C3^%^7CMCAAMB-1539578682^%^7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y^%^7CMCOPTOUT-1538981082s^%^7CNONE^%^7CMCAID^%^7CNONE^%^7CvVersion^%^7C2.5.0; s_ppn=auspost^%^3Atool^%^3Apostcode^%^20search^%^3Aresult; prevPage=auspost^%^3Atool^%^3Apostcode^%^20search^%^3Aresult; s_cc=true; sat_track=true; AAMC_auspost_0=REGION^%^7C3; aam_uuid=03587362520953834561130238465773055092; s_sq=^%^5B^%^5BB^%^5D^%^5D; _sdsat_lt_pages_viewed=2; _sdsat_pages_viewed=2; mbox=session^#e448aad88ae1426e8d9abbd9e8963061^#1538976071^|PC^#e448aad88ae1426e8d9abbd9e8963061.24_15^#1602218685; __utmb=33279841.6.10.1538973883; s_nr=1538974216222";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
    echo( $result);
}


