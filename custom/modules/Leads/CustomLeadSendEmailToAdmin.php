<?php

function get_lat_long($address) {
    $array = array();
    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
 
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
 
    // If everything is cool
    if ($geo['status'] = 'OK') {
       $latitude = $geo['results'][0]['geometry']['location']['lat'];
       $longitude = $geo['results'][0]['geometry']['location']['lng'];
       $array = array('lat'=> $latitude ,'lng'=>$longitude);
    }
 
    return $array;
}

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
    $password = "MW@pure733";

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
    $note = "Preparing designs and quote for client";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>1,
            "Name"=>"General",
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

require_once('include/SugarPHPMailer.php');
$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
//BinhNT: Possible to use new Email()?
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->IsHTML(true);
$mail->Subject = 'Request Solar Designs';

$record_id = urldecode($_GET['record_id']);

updateSolargainLead($record_id);

$lead = new Lead();
$lead = $lead->retrieve($record_id);
$description = $lead->description;
$address = $_GET["primary_address_street"] . ", " . 
            $_GET["primary_address_city"] . ", " . 
            $_GET["primary_address_state"] . ", " . 
            $_GET["primary_address_postalcode"] ;
$lat_long = get_lat_long($address);

/**
 * Dung code--- custom table list 10 new leads "Solargain LEADS Job NOT Completed"
 *  It is same like "SOLARGAIN LEADS JOB NOT COMPLETED" report
 */

$html_table_report = '
<h1>Solargain LEADS Job NOT Completed</h1>
<table>
        <tr>
            <td width="10%"><strong>Lead Number</strong></td>
            <td width="10%"><strong>Date Created</strong></td>
            <td width="10%"><strong>First Name</strong></td>
            <td width="10%"><strong>Last Name</strong></td>
            <td width="10%"><strong>Lead Source</strong></td>
            <td width="10%"><strong>Primary Address Street</strong></td>
            <td width="10%"><strong>Primary Address State</strong></td>
            <td width="10%"><strong>Time Completed Job</strong></td>
            <td width="10%"><strong>Designer</strong></td>
            <td width="10%"><strong>Status</strong></td>
            <td width="10%"><strong>Solargain Lead Number</strong></td>
            <td width="10%"><strong>Solargain Quote Number</strong></td>
            <td width="10%"><strong>Assigned to</strong></td>
            <td width="10%"><strong>Distance to SG</strong></td>
        </tr>';

$db = DBManagerFactory::getInstance();
$query = $sql = "SELECT leads.id as id 
               FROM leads 
               INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c 
               WHERE  leads.deleted = 0
               AND (leads.status  IN ('New','Assigned','In Process','') OR leads.status IS NULL)
               AND leads.lead_source ='Solargain'
               AND ( leads.primary_address_street IS NOT NULL OR leads.primary_address_street !='')
               AND ( leads_cstm.time_completed_job_c IS NULL OR leads_cstm.time_completed_job_c ='')
               ORDER BY leads.number DESC LIMIT 10
               ";
$ret = $db->query($sql);
$array_lead_suite_id = array();
while($row = $db->fetchByAssoc($ret)){
   array_push($array_lead_suite_id,$row['id']);
   //$array_lead_suite_id[]= $row['id'];
}
   foreach ($array_lead_suite_id as $key => $value) {
      $bean_lead = new Lead();
      $bean_lead->retrieve($value);
      $lead_id = $bean_lead->id;
      $lead_number = $bean_lead->number;
      $date_created = $bean_lead->date_entered ;
      $first_name = $bean_lead->first_name;
      $last_name = $bean_lead->last_name;
      $lead_source = $bean_lead->lead_source;
      $primary_address_street = $bean_lead->primary_address_street;
      $primary_address_state = $bean_lead->primary_address_state;
      $time_completed_job_c  = $bean_lead->time_completed_job_c ;
      $designer =  $bean_lead->designer_c;
      $Status =$bean_lead->status;
      $Solargain_Lead_Number = $bean_lead->solargain_lead_number_c;
      $Solargain_Quote_Number = $bean_lead->solargain_quote_number_c;
      $Solargain_Quote_Number_tesla =  $bean_lead->solargain_tesla_quote_number_c;
      $Assigned_to =  $bean_lead->assigned_user_name;
      $Distance_to_SG =  $bean_lead->distance_to_sg_c;;

      $html_table_report .= 
            "<tr>
                <td><a target='_blank' href='https://suitecrm.pure-electric.com.au/index.php?module=Leads&action=EditView&record=".$lead_id."'>". $lead_number."</a></td>
                <td>".$date_created."</td>
                <td>".$first_name."</td>
                <td>".$last_name."</td>
                <td>".$lead_source."</td>
                <td>".$primary_address_street."</td>
                <td>".$primary_address_state."</td>
                <td>".$time_completed_job_c."</td>
                <td>".$designer."</td>
                <td>".$Status."</td>
                <td><a target='_blank' href='https://crm.solargain.com.au/lead/edit/".$Solargain_Lead_Number."'>". $Solargain_Lead_Number."</a></td>
                <td><a target='_blank' href='https://crm.solargain.com.au/quote/edit/".$Solargain_Quote_Number."'> ". $Solargain_Quote_Number." </a><a target='_blank' href='https://crm.solargain.com.au/quote/edit/".$Solargain_Quote_Number_tesla."'> ". $Solargain_Quote_Number_tesla." </a></td>
                <td>".$Assigned_to."</td>
                <td>".$Distance_to_SG."</td>
            </tr>";
   }


   $html_table_report .= '</table>';
   
$body = '
<div dir="ltr">
Hi Team,
<div><br></div>
<div>Another Solar design to do.&nbsp;&nbsp;</div>
<div><br></div>
<div>'.$lead->first_name.' '.$lead->last_name.'</div>
<div>'.$lead->email1.'</div>
<div>'.$_GET["primary_address_street"].'</div>
<div>'.$_GET["primary_address_city"].'<br></div>
<div>'.$_GET["primary_address_state"].'</div>
<div>'.$_GET["primary_address_postalcode"].'</div>
<div><br></div>


<div><a target="_blank" href="https://www.google.com/maps/place/' . $address . '">Google Maps</a></div>

<div><a target="_blank" href="http://maps.google.com/maps?q=&layer=c&cbll=' .$lat_long['lat']. ',' .$lat_long['lng']. '&cbp=11,0,0,0,0">Google Streetview</a></div>

<div><a target="_blank" href="http://maps.nearmap.com?addr='. $address . '&z=22&t=roadmap">Near Map</a></div>
<div><br>
<b>Instruction: </b>
'
. $lead->description .'
</div>
<div><br></div>
<div>Please upload the pictures to the CRM when you\'re finished.</div>
<div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link</a></div>
<div><br></div>
<div>Please click the link below to accept job.</div>
<div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customLeadAcceptJob&record_id=' . $record_id . '">Accept Job</a></div>
<div><br></div>
'.$html_table_report .'<div><br></div>
<div>
   <br clear="all">
   <div>
      <div class="m_2099969047182855450gmail_signature">
         <div dir="ltr">
            <div>
               <div dir="ltr">
                  <div>
                     <div dir="ltr">
                        <div>
                           <div dir="ltr">
                              <div dir="ltr">
                                 <div dir="ltr">
                                    <div dir="ltr">
                                       <span>
                                          <div dir="ltr">
                                             <div dir="ltr">
                                                <div dir="ltr">
                                                   <div style="font-size:12.8px;color:rgb(0,0,0)">
                                                      <div dir="ltr">
                                                         <div dir="ltr"><span style="font-size:12.8px">Matthew Wright&nbsp;</span><font size="1">MEng(Distinct)&nbsp;<i>RMIT,&nbsp;</i></font><font size="1">Ph<wbr>D(c)<i>&nbsp;Aust German Climate &amp; Energy College,&nbsp;</i></font><i style="font-size:x-small">University of Melbourne</i></div>
                                                         <div dir="ltr">
                                                            <div>
                                                               <div style="font-size:12.8px"><b>Pure Electric</b><br><a href="tel:0421%20616%20733" value="+61423494949" style="color:rgb(17,85,204)" target="_blank">0421 616 733</a></div>
                                                               <div style="font-size:12.8px"><a href="mailto:matthew.wright@pure-electric.com.au" style="color:rgb(17,85,204);font-size:13.3333px" target="_blank">matthew.wright@pure-electric.<wbr>com.au</a><br></div>
                                                               <div style="font-size:12.8px"><a href="http://pure-electric.com.au/" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://pure-electric.com.au/&amp;source=gmail&amp;ust=1511655808584000&amp;usg=AFQjCNHCSYSnjpeXuT_PB-W-jdcDdbFx5g"><img src="https://ci4.googleusercontent.com/proxy/-EZkUuh1S54x9CTvjE5vnIBIBjetkHUafAvt2uXpJWjKRW5yozCVmLDfPZYiab40V04sPxWp4DrAqMyfqvBflmM2NCjqzmJnQmlsDPYu=s0-d-e1-ft#http://pure-electric.com.au/sites/default/files/logo.png" alt="Home" width="200" height="47" class="CToWUd"></a></div>
                                                            </div>
                                                            <div style="font-size:12.8px"><br></div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div style="font-size:12.8px">Executive Director<br></div>
                                                   <div style="font-size:12.8px">Beyond The Grid P/L<br></div>
                                                   <div style="font-size:12.8px">Pure Electric - Power to be free<br><br>Executive Director</div>
                                                   <div style="font-size:12.8px">Zero Emissions Australia</div>
                                                   <div style="font-size:12.8px"><a href="http://zeroemissions.org.au/" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://zeroemissions.org.au/&amp;source=gmail&amp;ust=1511655808584000&amp;usg=AFQjCNHLsbpHmkmGssQ2tI4zQyWjRcIoKQ">http://zeroemissions.org.au</a></div>
                                                   <div style="font-size:12.8px">Climate and Energy Solutions for the 21st Century</div>
                                                   <div style="font-size:12.8px">Lead Author Zero Carbon Australia Stationary Energy Plan</div>
                                                   <div style="font-size:12.8px">Environment Minister\'s Young Environmentalist of the Year 2010</div>
                                                   <div style="font-size:12.8px">Mercedes Benz Research Award Winner 2010</div>
                                                   <div style="font-size:12.8px">EcoGen Clean Energy Young Industry Leader 2010</div>
                                                   <div style="font-size:12.8px">Wild Environmentalist of the Year 2012</div>
                                                </div>
                                             </div>
                                          </div>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                              </div>
                              </div>
                              </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="yj6qo"></div>
   <div class="adL"></div>
</div>
</div>
';

$mail->Body = $body;

$mail->prepForOutbound();
$mail->AddAddress('binhdigipro@gmail.com');
$mail->AddAddress('admin@pure-electric.com.au');
$mail->AddCC('info@pure-electric.com.au');
// $mail->AddAddress('nguyenphudung93.dn@gmail.com');
$sent = $mail->Send();
//dung code - logic send request one time
if ($sent) {
    $mail->status = 'sent';
    //$mail->save();
    $lead->email_send_design_status_c = 'sent';
    $lead->email_send_design_request_id_c = $emailObj->id;
    //dung code- update time sent email Request Design
    date_default_timezone_set('Australia/Melbourne');
    $dateAUS = date('Y-m-d H:i:s', time());
    $lead->time_request_design_c = $dateAUS;
      //change status quote solar of this leads 
      if( $lead->create_solar_quote_num_c != ''){
            $bean_quotes = new AOS_Quotes();
            $bean_quotes->retrieve($lead->create_solar_quote_num_c);
            if( $bean_quotes->id != '') {
               $bean_quotes->stage = 'Request_Designs';
               $bean_quotes->save();
            }
      }
    $lead->save();
} else {
    if ($mail->status !== 'draft') {
        $mail->status = 'send_error';
        //$mail->save();
    } else {
        $mail->status = 'send_error';
        //$mail->save();
    }
} 
//echo $sent;
//dung code - display time click "Request Design"
if($sent){
    $time_now = date('d/m/Y H:i', time());
    echo $time_now;
}


?>
