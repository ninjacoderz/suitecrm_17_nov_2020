<?php

function updateSolargainLead($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if(!$lead->solargain_lead_number_c)
    {
        return;
    }

    $solargainLead = $lead->solargain_lead_number_c;

    $username = "matthew.wright";
    $password =  "MW@pure733";

    // Get full json response for Leads

    $url = "https://crm.solargain.com.au/APIv2/leads/". $solargainLead;
    //set the url, number of POST vars, POST data

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

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
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
            "Cache-Control: max-age=0"
        )
    );
    
    $leadJSON = curl_exec($curl);
    curl_close ( $curl );

    $leadSolarGain = json_decode($leadJSON);

    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "Looking at customer site, analysing customer roof and quantifying opportunity";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>5,
            "Name"=>"E-Mail Out",
            "RequiresComment"=> true
        ),
        "Text"=> $note
    );

    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);
    
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
            "Content-Length: " .strlen($leadSolarGainJSONDecode),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
        )
    );
    
    $lead = json_decode(curl_exec($curl));
    curl_close ( $curl );
}

global $current_user;

$record_id = urldecode($_GET['record_id']);

updateSolargainLead($record_id);

$lead = new Lead();
$lead = $lead->retrieve($record_id);

if ($lead->user_id_c == '')
{
    $lead->user_id_c = $current_user->id;

    date_default_timezone_set('Australia/Melbourne');
    $dateAUS = date('Y-m-d H:i:s', time());
    $lead->time_accepted_job_c = $dateAUS;

    $lead->save();
}
else
{
    date_default_timezone_set('Australia/Melbourne');
    $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $lead->time_accepted_job_c)));
    $timeAgo = time() - strtotime($date);
    $timeAgo = $timeAgo / 3600;

    $user = new User();
    $user->retrieve($lead->user_id_c);

    $message = $user->name . " already agreed to do this design at " . $lead->time_accepted_job_c . ' (Time Melbourne) ';

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

    echo "<script type='text/javascript'>alert('$message');</script>";
    
    return;
}

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
<br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
<br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>';    

$mail->prepForOutbound();
$mail->AddAddress('binhdigipro@gmail.com');
$mail->AddAddress('admin@pure-electric.com.au');
$mail->AddCC('info@pure-electric.com.au');
//$mail->AddCC('lee.andrewartha@pure-electric.com.au');
$sent = $mail->Send();

header('Location: index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index');

?>