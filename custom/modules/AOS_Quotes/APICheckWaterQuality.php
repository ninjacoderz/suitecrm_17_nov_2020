<?php
  require_once(dirname(__FILE__).'/simple_html_dom.php');
  // Enable cross domain call
  header('Access-Control-Allow-Origin: *');

  // get post code
  $postcode = $_POST['postcode'];
    
  // logic old - ajax get address by field address 
  if($postcode !== '' && $postcode !== null){
      $curl = curl_init();
      //echo $address;
      $url = 'https://www.sanden-hot-water.com.au/check-your-water-quality?r=614698^&postcode='.$postcode;
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
              "Connection: keep-alive",
              "Upgrade-Insecure-Requests: 1",
              "Cache-Control: max-age=0",
          )
      );
      
      $result = curl_exec($curl);
      if (curl_errno($curl)) {
          echo 'Error:' . curl_error($curl);
      }
      curl_close ($curl);
      
      $html_check = str_get_html($result);
      $tmp = $html_check->find('div[id="water-quality-results"]');
      if(count($tmp) > 0) {
        $content_check = $html_check->find('div[id="water-quality-results"]')[0]->find('div[class="form-block"]')[0]->find('p')[0]->innertext;
        $content = trim(preg_replace("/\t|\n/","",$content_check));
      } else {
        $content = "There are no known water quality issues with this postcode.";
      }

      $data_return = array();
      $data_return['data'] = $content;
      $data_return['code'] = 0;

      echo json_encode($data_return);
  }