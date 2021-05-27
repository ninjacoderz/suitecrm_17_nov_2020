<?php
    $address= str_replace(' ', '%20', $_REQUEST['mylocation']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://pvwatts.nrel.gov/handle_mylocation.php?myloc=');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($shipments));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Authority: pvwatts.nrel.gov';
    $headers[] = 'Sec-Ch-Ua: ^^';
    $headers[] = 'Accept: */*';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
    $headers[] = 'Origin: https://pvwatts.nrel.gov';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://pvwatts.nrel.gov/pvwatts.php';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    $headers[] = 'Cookie: nrelGovGA=GA1.2.1048940546.1621556914; DAV_PVWATTS=GA1.2.1048940546.1621556914; _ga=GA1.2.1048940546.1621556914; _ga=GA1.3.1048940546.1621556914; _ce.s=v11.rlc~1621570273665; PHPSESSID=1ae031f2a3ffa04894340fff7f5019e5; nrelGovGA_gid=GA1.2.2112269530.1622078332; _gat_nrelGovTracker=1; DAV_PVWATTS_gid=GA1.2.1407189685.1622078332; _gat_DAV_PVWATTS=1; _gid=GA1.2.266206114.1622078332; _gat_UA-121046212-1=1';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    echo $result;
?>