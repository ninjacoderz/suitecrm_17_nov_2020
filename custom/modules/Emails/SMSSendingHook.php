<?php 
class SMSSendingHook
{
    public function saveEmailUpdate($email){
        return;
        global $sugar_config;
        $destination = $sugar_config['mail_dir']."/sent/".$email->to_addrs_names;
        $fp = fopen($destination, "w+");
        fwrite($fp, serialize($email));
        fclose($fp);
    }
}