<?php 

array_push($job_strings, 'custom_sendsms');

function sendSMS($content) {
    $ch = curl_init('https://api.smsbroadcast.com.au/api-adv.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);
    curl_close ($ch);
    return $output;
}
    
function custom_sendsms()
{
    date_default_timezone_set('UTC');
    global $sugar_config;
    $folder = $sugar_config['mail_dir'].'/sent';

    $file_array = scandir($folder);

    if(count($file_array) <= 2 ) return true;

    foreach ($file_array as $file){
        if(is_file($folder."/".$file)){
            // Do some stub
            $pattern = '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';

            preg_match_all($pattern, $file, $matches);
            if(isset($matches[0][0])){
                $email_add = $matches[0][0];
            }
            else {
                continue;
            }
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT * FROM email_addresses ea 
                                LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                WHERE 1=1 AND ea.email_address = '$email_add' AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module = 'Leads'
                                ";
            $ret = $db->query($sql);
            
            while ($row = $db->fetchByAssoc($ret)) {
                
                if($row["bean_id"] != ""){
                    // 
                    $lead = new Lead();
                    $lead->retrieve($row["bean_id"]);
                    /*if($lead->sms_number_c == "" || $lead->sms_number_c == "0" || !isset($lead->sms_number_c)){
                        // do nothing return;
                    } else {*/
                    if(isset($lead->sms_number_c) && ($lead->sms_number_c > 0 ) && isset($lead->last_time_sent_sms_c) && $lead->last_time_sent_sms_c!= ""){
                        $lastTime = strtotime($lead->last_time_sent_sms_c);
                        $currentTime = time();
                        if(($currentTime - $lastTime) <= 60 * 60 *2 ) continue;
                    }
                    //}
                    if(isset($lead->id) && $lead->id != "") {
                        $phone = "";

                        if($lead->phone_other != ""){
                            $phone = $lead->phone_other;
                        }

                        if($lead->phone_home != ""){
                            $phone = $lead->phone_home;
                        }
                        if($lead->phone_work != ""){
                            $phone = $lead->phone_work;
                        }

                        if($lead->phone_mobile != ""){
                            $phone = $lead->phone_mobile;
                        }
                        
                        if($phone!= ""){
                            $phone = preg_replace('/[^0-9]/', '',$phone );
                        }

                        $GLOBALS['log']->debug('-------------------------------------------->Here is phone <--------------------------------------------' .$phone );
                        
                        $username = 'mattwrightzen';
                        $password = 'binhmatt2018';
                        $destination = $phone; // MAtthew number Multiple numbers can be entered, separated by a comma

                        $name = $lead->first_name;
                        if ($name == ""){
                            $name = $lead->last_name;
                        }
                        $paul_number = "0423494949";
                        $mathew_number = "0421616733";

                        // Decide Matthew oR Paul
                        if($lead->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){
                            $text = "Hi ".$name.", I sent you an email RE Solargain quote, check you got it ok? Thanks, Matt (SG Office) 0421616733 matthew@pure-electric.com.au";
                            $source    = '0421616733';
                        } else {
                            $text = "Hi ".$name.", I sent you an email RE Solargain quote, check you got it ok? Thanks, Paul (SG Office) 0423494949 paul@pure-electric.com.au";
                            $source    = 'Pure Elec';
                        }

                        //$text = "Hi ".$name.", I sent you an email RE Solargain quote, check you got it ok? Thanks, Matt (SG Office) 0421616733 matthew@pure-electric.com.au";
                        $ref = 'Pure Electric';
                        
                        $content =  'username='.rawurlencode($username).
                                    '&password='.rawurlencode($password).
                                    '&to='.rawurlencode($destination).
                                    '&from='.rawurlencode($source).
                                    '&message='.rawurlencode($text).
                                    '&ref='.rawurlencode($ref);
                        
                        $smsbroadcast_response = sendSMS($content);
                        $response_lines = explode("\n", $smsbroadcast_response);
                        
                        foreach( $response_lines as $data_line){
                            $message_data = "";
                            $message_data = explode(':',$data_line);
                            if($message_data[0] == "OK"){
                                echo "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]."\n";
                            }elseif( $message_data[0] == "BAD" ){
                                echo "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2]."\n";
                            }elseif( $message_data[0] == "ERROR" ){
                                echo "There was an error with this request. Reason: ".$message_data[1]."\n";
                            }
                        }
                        $lead->sms_number_c += 1;
                        $lead->last_time_sent_sms_c = date("Y-m-d h:s:i");
                        $lead->save();
                    }
                    unlink($folder."/".$file);
                    return;
                }
            }
            unlink($folder."/".$file);
        }
    }
    return;
}