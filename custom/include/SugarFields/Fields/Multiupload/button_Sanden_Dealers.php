
<?php
      date_default_timezone_set('Africa/Lagos');
      set_time_limit (0);
      ini_set('memory_limit', '-1');  
      require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');
      $array = array("3000","2614","2000","4000","5000","6000");
      
      function GetSanden($number){
            $md_array= array (
                  array(
                        '3000' => 
                              array (
                                    0 => array(
            
                                    ),
                                    1 => array(
            
                                    ),
                                    2 => array(
            
                                    ),
                                    3 => array(
            
                                    ),
                                    4 => array(
            
                                    ),
                              ),
                        '2614' => 
                              array (
                                    0 => array(
      
                                    ),
                                    1 => array(
      
                                    ),
                                    2 => array(
      
                                    ),
                                    3 => array(
      
                                    ),
                                    4 => array(
      
                                    ),
                              ),
                              '2000' => 
                              array (
                                    0 => array(
      
                                    ),
                                    1 => array(
      
                                    ),
                                    2 => array(
      
                                    ),
                                    3 => array(
      
                                    ),
                                    4 => array(
      
                                    ),
                              ),
                              '4000' => 
                              array (
                                    0 => array(
      
                                    ),
                                    1 => array(
      
                                    ),
                                    2 => array(
      
                                    ),
                                    3 => array(
      
                                    ),
                                    4 => array(
      
                                    ),
                              ),
                              '5000' => 
                              array (
                                    0 => array(
      
                                    ),
                                    1 => array(
      
                                    ),
                                    2 => array(
      
                                    ),
                                    3 => array(      
                                   ),
                                    4 => array(
                                    ),
                              ),
                              '6000' => 
                              array (
                                    0 => array(
                                    ),
                                    1 => array(
                                    ),
                                    2 => array(
                                    ),
                                    3 => array(
                                    ),
                                    4 => array(
                                    ),
                              ),
                  )
            );
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
            $label      = array();
            $detail     = array();
            foreach ($html->find('div #installer-label') as $value) {
                  array_push($label,$value->innertext);
            }
            foreach ($html->find('div #installer-detail') as $value) {
                  array_push($detail,$value->innertext);
            }
                  for($i=0;$i<count($detail); $i++){
                        array_push($md_array[0][$number][0],"$label[$i] => $detail[$i]");
                        if($label[$i] == 'Email:'){
                              break;
                        }
                  } 
                  for($b= $i + 1;$b<count($detail); $b++){
                        array_push($md_array[0][$number][1],"$label[$b] => $detail[$b]");
                        if($label[$b] == 'Email:'){
                              break;
                        }
                  }
                  for($c= $b + 1;$c<count($detail); $c++){
                        array_push($md_array[0][$number][2],"$label[$c] => $detail[$c]");
                        if($label[$c] == 'Email:'){
                              break;
                        }
                  }
                  for($d= $c + 1;$d<count($detail); $d++){
                        array_push($md_array[0][$number][3],"$label[$d] => $detail[$d]");
                        if($label[$d] == 'Email:'){
                              break;
                        }
                  }
                  for($e= $d + 1;$e<count($detail); $e++){
                        array_push($md_array[0][$number][4],"$label[$e] => $detail[$e]");
                        if($label[$e] == 'Email:'){
                              break;
                        }
                  }
            
            // print_r($md_array);
            $result_function = "<h3 >Installers serving your area:".$number."</h3>"."<table style='border:1px solid #088fca;width:350px;'>";
            foreach($md_array[0][$number] as $value1){
                  foreach($value1 as $key => $value){
                        if(strpos($value,"Email:") !== false){
                              $result_function .= "<tr><td>".preg_replace('/[0-9]+/', '', $key)."</td>"."<td style='border-bottom:1px solid black;'>".str_replace("=>","", $value)."</td></tr>";
                        }else{
                              $result_function .= "<tr><td>".preg_replace('/[0-9]+/', '', $key)."</td>"."<td>".str_replace("=>","", $value)."</td></tr>";

                        }
                  }
                  // foreach($value as $value1){
                  //       $html .= "<h3>".$number."</h3>"."<table style='border:1px solid #088fca'>";
                  //       foreach($value1 as $value2){
                  //             //<td>".$value."</td>
                  //             foreach($value2 as  $value){
                                    // if(strpos($value,"Email:") !== false){
                                    //       $html .= "<tr><td style='border-bottom:1px solid black;'>".$value."</td></tr>";
                                    // }else{
                                    //       $html .= "<tr><td>".$value."</td></tr>";

                                    // }
                  //             }
                  //       }
                  //       $html .= "</table>";
                  // }
            }
            $result_function .= "</table>";
            return $result_function;
            // foreach($md_array as $value){
            //       if()
            // }
   
      }
      $body_html = '';
      for($i=0; $i<count($array) ;$i++){
            $body_html  .=  GetSanden($array[$i]);
      }
      //sent mail      
      require_once('include/SugarPHPMailer.php');
      $emailObj = new Email();
      $defaults = $emailObj->getSystemDefaultEmail();
      $mail = new SugarPHPMailer();
      $mail->setMailerForSystem();
      $mail->From = $defaults['email'];
      $mail->FromName = $defaults['name'];
      $mail->IsHTML(true);
  
      $mail->Subject = 'Sanden Email Daily';
  
      $mail->Body =   $body_html;
      $mail->prepForOutbound();
      //$mail->AddAddress('binhdigipro@gmail.com');
      // $mail->AddAddress('trantu230399@gmail.com');
      $mail->AddAddress('info@pure-electric.com.au');
      //$mail->AddCC('info@pure-electric.com.au');
      //$mail->AddAddress('thienpb89@gmail.com');
      $mail->Send();
      
 ?>
    
