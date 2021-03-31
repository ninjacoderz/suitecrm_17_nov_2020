<?php
    $mes_arr = $_POST;
    $db = DBManagerFactory::getInstance();

    // print_r(json_decode(html_entity_decode($message),true));die;
    if($mes_arr['id'] != ''){
        if(strpos($mes_arr["from"],"+61") === false){
            $phone = preg_replace('/\D/', '', $mes_arr["from"]);
            $phone = preg_replace('/^0/', '+61', $phone);
        }else{
            $phone = "+".preg_replace('/\D/', '', $mes_arr["from"]);
        }

        if($phone != '+61444503982' && $phone != '' && $phone != "+61490942067"){
            $data_phone = str_replace('+61', '', $phone);
            $sql = "SELECT * FROM contacts WHERE REPLACE(phone_mobile,' ','') LIKE '%".$data_phone."%' OR REPLACE(phone_work,' ','') LIKE '%".$data_phone."%' OR REPLACE(phone_home,' ','') LIKE '%".$data_phone."%' LIMIT 0,1";
            $ret = $db->query($sql);
            $row = $db->fetchByAssoc($ret);

            //VUT-
            $SMSContents = getMessage($phone); 
            // VUT-
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = 'info@pure-electric.com.au';  
            $mail->FromName = 'Pure Electric';  
            $mail->IsHTML(true);
            $mail->ClearAllRecipients();
            $mail->ClearReplyTos();

            $mail->Subject =  'Notification ! We have new message from '.$phone ;
            $bodytext = '';
            $bodytext .= '<p>Hi team, '.$phone.' has sent you a new sms. Please check it.</p>';
            if($mes_arr['message_type'] !== 1){
                $bodytext .= '<p>Message Content : '.$mes_arr['message_body'].'</p>';
            }
            $bodytext .= "<p>Message Link : <a href='http://message.pure-electric.com.au/#".trim($phone,"+")."'>".$phone."</a></p>" ;
            // $bodytext .= "<p>Contact Name : <b>".$row["first_name"]." ".$row["last_name"]."</b></p>";
            $bodytext .= "<p>Contact Name :<a href='https://suitecrm.pure-electric.com.au/index.php?module=Contacts&action=EditView&record=".$row['id']. "' target='_blank'><b>".$row["first_name"]." ".$row["last_name"]."</b></a></p>";
            $mail->Body = $bodytext.'<br>'.$SMSContents['contents'];
            $mail->AddBCC('thienpb89@gmail.com');
            $mail->AddAddress('info@pure-electric.com.au');
            $mail->prepForOutbound();    
            $mail->setMailerForSystem();
            $sent = $mail->send();
            die;
        }
    }


//FUNCTION
function getMessage($phone_number) {
    $db = DBManagerFactory::getInstance();
    $servername = "database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com";
    $username = "root";
    $password = "binhmatt2018";
    $database_name = "message";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database_name);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $query = "SELECT conversations.id FROM conversations
                INNER JOIN accounts ON conversations.to_user  = accounts.id
                WHERE accounts.phone='".$phone_number."'
                ";
    $result =  $conn->query($query);
    $array_link_files = array();
    $array_contents = array();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $data_return = [
        'contents' => '',
        'files' => '',
    ];
    if ($row['id'] != '') {
        $url = "http://message.pure-electric.com.au/get_messages.php?conversation_id=".$row['id'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $data_json = array_reverse(json_decode($result,true));
        $content_html = '<div style = "width: 68%;">';
        $content_html.= '<table style="width: 100%;border-spacing: 0pt;"><tr style="background-color: #00ffff;">'
                        .'<td style="border: solid 1px;text-align: center;width: 08%;"><b>Time</b></td>'
                        .'<td style="border: solid 1px;text-align: center;width: 30%;"><b>Customer</b></td>'
                        .'<td style="border: solid 1px;text-align: center;width: 30%;"><b>Pure Electric</b></td>'
                        .'</tr>';
        $files = [];
        foreach ($data_json as $key => $value) {
            $content = '';
            if ($value['message_type'] == 'image') {
                if ($value['created_user'] == 'd3a9092b-7975-eb9e-2e94-133fae9c3c87') { 
                    //nothing
                } else {
                    $condition_file_from_crm = 'public_files';
                    $in_source = 'http://message.pure-electric.com.au/jQuery-File-Upload-9.21.0/server/php/files/';
                    if (strpos($value['message_content'], $condition_file_from_crm) !== false) {
                        array_push($files, $value['message_content']);
                        $content = '<td style="border: solid 1px;text-align: center;">'
                        .'<a target="_blank" href="'.$value['message_content'].'"><input type="image" style="width:300px;height:400px" src="'.$value['message_content'].'"/></a>'
                        .'</td>';
                    } else {
                        array_push($files, $in_source.$value['message_content']);
                        $content = '<td style="border: solid 1px;text-align: center;">'
                        .'<a target="_blank" href="'.$in_source.$value['message_content'].'"><input type="image" style="width:300px;height: 400px" src="'.$in_source.$value['message_content'].'"/></a>'
                        .'</td>';
                    }
                }
            } else {
                $content = '<td style="border: solid 1px;text-align: justify;"><span>'.trim($value['message_content']).'</span></td>';
            }
            if ($content != '') {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s',$value['created_date'], new DateTimeZone("UTC"));
                $date = $dateTime->setTimezone(new DateTimeZone('Australia/Melbourne'))->format('d/m/Y H:i:s');
                $content_html .='<tr>';
                $content_html.= '<td style="border: solid 1px;text-align: center;"><span style="font-weight: bold;">'.explode(' ',$date,2)[0].'</span><br><span>'.explode(' ',$date,2)[1].'</span></td>';
                if ($value['created_user'] == 'd3a9092b-7975-eb9e-2e94-133fae9c3c87') {
                    $content_html.= '<td style="border: solid 1px;text-align: justify;"></td>';
                    $content_html.= $content;
                } else {
                    $content_html.= $content;
                    $content_html.= '<td style="border: solid 1px;text-align: justify;"></td>';
                }
                $content_html .= '</tr>';
            }
        }

        $content_html .= '</table></div>';
        $data_return = [
            'contents' => html_entity_decode($content_html),
            'files' => $files,
        ];
    }
    return $data_return;
}
?>