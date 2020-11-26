<?php

    function updateSGQuoteNote($SGquote_ID){

        $username = "matthew.wright";
        $password =  "MW@pure733";
        
        $url = "https://crm.solargain.com.au/APIv2/quotes/". $SGquote_ID;
        //set the url, number of POST vars, POST data

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                "Cache-Control: max-age=0"
            )
        );
        
        $quoteJSON = curl_exec($curl);
        curl_close ( $curl );

        $data_string = json_decode($quoteJSON);

        //Thienpb code for change account if download false
            if(!isset($data_string->ID)){
                if($username == 'paul.szuster@solargain.com.au'){
                    $username = "matthew.wright";
                    $password =  "MW@pure733";
                }else{
                    $username = 'paul.szuster@solargain.com.au';
                    $password = 'S0larga1n$';
                }
                
                //get data from SG quote
                $url = "https://crm.solargain.com.au/APIv2/quotes/". $SGquote_ID;
                //set the url, number of POST vars, POST data
        
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($curl, CURLOPT_ENCODING , "gzip");
                curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        "Host: crm.solargain.com.au",
                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                        "Content-Type: application/json",
                        "Accept: application/json, text/plain, */*",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: 	gzip, deflate, br",
                        "Connection: keep-alive",
                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                        "Referer: https://crm.solargain.com.au/quote/edit/".$SGquote_ID,
                        "Cache-Control: max-age=0"
                    )
                );
                
                $quoteJSON = curl_exec($curl);
                curl_close ( $curl );
        
                $data_string = json_decode($quoteJSON);
                //END
            }
        //END

        // building Note
        // Logged in user name: Email From name: and email template title 
        $note = "Looking at customer site, analysing customer roof and quantifying opportunity";
        $data_string->Notes[] = array(
            "ID" => 0,
            "Type"=> array(
                "ID"=>5,
                "Name"=>"E-Mail Out",
                "RequiresComment"=> true
            ),
            "Text"=> $note
        );

        $data_string_encode = json_encode($data_string, JSON_UNESCAPED_SLASHES);
        // Save back lead 
        $url = "https://crm.solargain.com.au/APIv2/quotes/";
        //set the url, number of POST vars, POST data
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string_encode);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($data_string_encode),
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/lead/edit/".$SGquote_ID,
            )
        );
        
        $quoteSG = json_decode(curl_exec($curl));
        curl_close ( $curl );
    }

    global $current_user;

    $record_id = urldecode($_GET['record_id']);

    $quote = new AOS_Quotes();
    $quote->retrieve($record_id);

    if(!$quote->solargain_quote_number_c)
    {
        return;
    }

    $SGquote_ID = $quote->solargain_quote_number_c;
    $return = '';

    updateSGQuoteNote($SGquote_ID);
    
    if ($quote->user_id_c == ''){
        $quote->user_id_c = $current_user->id;

        date_default_timezone_set('UTC');
        $dateAUS = date('Y-m-d H:i:s', time());
        $quote->time_accepted_job_c = $dateAUS;
        $quote->stage = 'JobAccepted_InProgress';
        $quote->save();

        $query =   "SELECT leads.id FROM aos_quotes 
                INNER JOIN leads ON leads.account_id = aos_quotes.billing_account_id 
                WHERE aos_quotes.billing_account_id = '$quote->billing_account_id' 
                AND aos_quotes.id = '$quote->id' 
                AND aos_quotes.deleted = 0 LIMIT 1";
        $db = DBManagerFactory::getInstance();

        $result_lead = $db->query($query);
        if($result_lead->num_rows > 0){
            $lead_row = $db->fetchByAssoc($result_lead);
            $lead = new Lead();
            $lead =  $lead->retrieve($lead_row['id']);

            require_once('include/SugarPHPMailer.php');
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = $defaults['email'];
            $mail->FromName = $defaults['name'];
            $mail->IsHTML(true);
            $mail->Subject = 'Designs accepted - ' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            date_default_timezone_set('Australia/Melbourne');
            $dateAUS = date('m/d/Y h:i:s a', time());

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $dateVIE = date('m/d/Y h:i:s a', time());

            $mail->Body = '<div>' . $current_user->name . ' has accepted the job at ' . $dateAUS . ' (Time Melbourne) / ' . $dateVIE . ' (Time Ha Noi).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=AOS_Quotes&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>';

            $mail->prepForOutbound();
            //$mail->AddAddress('thienpb89@gmail.com');
            $mail->AddAddress('admin@pure-electric.com.au');
            $mail->AddCC('info@pure-electric.com.au');
            //$mail->AddCC('lee.andrewartha@pure-electric.com.au');
            $sent = $mail->Send();

            if($_REQUEST['accepted'] == 'link'){
                header('Location: index.php?action=EditView&module=AOS_Quotes&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index');
                die();
            }else{
                echo json_encode(array('message'=>'done','user_id'=> $current_user->id,'user_name'=> $current_user->name));
            }
            die();
        }else{
            die();
        }
        
    }else{
        date_default_timezone_set('Australia/Melbourne');
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $quote->time_accepted_job_c)));
        $timeAgo = time() - strtotime($date);
        
        $timeAgo = $timeAgo / 3600;

        $user = new User();
        $user->retrieve($quote->user_id_c);

        $message = $user->name . " already agreed to do this design at " . $quote->time_accepted_job_c . ' (Time Melbourne) ';

        if ($timeAgo < 1)
        {
            $message = $message . '(' . round($timeAgo * 60) . ' minutes ago)';
        }
        else
        {
            $message = $message . '(' . round($timeAgo) . ' hours ago)';
            if ($timeAgo > 2)
            {
                $message = $message . '\nPlease check if ' . $user->name . ' is still doing the designs as they are now OVERDUE';
            }
        }
        if($_REQUEST['accepted'] == 'link'){
            echo "<script type='text/javascript'>alert('$message');location.href = 'https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=AOS_Quotes&record=".  $record_id ."&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index'</script>";
            die();
        }else{
            echo json_encode(array('message'=>$message));
            die();
        }
        
    }
?>