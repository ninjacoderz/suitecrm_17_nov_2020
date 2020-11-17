<?php
      date_default_timezone_set('Africa/Lagos');
      set_time_limit (0);
      ini_set('memory_limit', '-1');  
      require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');
      $array = array("3000","2614","2000","4000","5000","6000");
      function GetSanden($number){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.sanden-hot-water.com.au/locate-installer?r=161653&postcode=".$number);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = "Pragma: no-cache";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Accept-Language: en-US,en;q=0.9";
            $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
            $headers[] = "Accept: application/json, text/plain, */*";
            $headers[] = "Referer: https://www.sanden-hot-water.com.au/locate-installer?r=161653&postcode=3000";
            $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
            $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
            $headers[] = "Connection: keep-alive";
            $headers[] = "Cache-Control: no-cache";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $result = curl_exec($ch);
            curl_close ($ch);
            $html = str_get_html($result);
            $result_function = '';
            foreach ($html->find('div #resultset-inst') as $value) {
               $result_function .= "<h3>Installers serving your area:".$number."</h3>".$value->innertext;
            }   
            return $result_function;
      }
      $body_html = '';
       for($i;$i<count($array);$i++){
          $body_html  .= GetSanden($array[$i]);  
      }
      require_once('include/SugarPHPMailer.php');
      $emailObj = new Email();
      $defaults = $emailObj->getSystemDefaultEmail();
      $mail = new SugarPHPMailer();
      $mail->setMailerForSystem();
      $mail->From = $defaults['email'];
      $mail->FromName = $defaults['name'];
      $mail->IsHTML(true);
  
      $mail->Subject = 'Sanden Email Daily';
  
      $mail->Body = $body_html;
  
      $mail->prepForOutbound();
      //$mail->AddAddress('binhdigipro@gmail.com');
    //   $mail->AddAddress('trantu230399@gmail.com');
      $mail->AddAddress('info@pure-electric.com.au');
      //$mail->AddCC('info@pure-electric.com.au');
      //$mail->AddAddress('thienpb89@gmail.com');
  
      $sent = $mail->Send();
 ?>
    
