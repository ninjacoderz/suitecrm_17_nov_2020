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

function updateSolargainQuote($quote_id)
{
    $quote = new AOS_Quotes();
    $quote->retrieve($quote_id);
    if(!$quote->solargain_quote_number_c)
    {
        return;
    }

    $quoteSG_ID = $lead->solargain_quote_number_c;

    $username = "matthew.wright";
    $password = "MW@pure733";

    // Get full json response for Leads

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://crm.solargain.com.au/APIv2/quotes/'.$quoteSG_ID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Referer: https://crm.solargain.com.au/quote/edit/'.$quoteSG_ID;
    $headers[] =  "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Cache-Control: no-cache';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $quoteJSON = curl_exec($ch);
    curl_close ( $ch );

    $quoteSG = json_decode($quoteJSON);

    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "Preparing designs and quote for client";
    $quoteSG->Notes[] = array(
        "ID" => 0,
        "Type"=> array(
            "ID"=>1,
            "Name"=>"General",
            "RequiresComment"=> true
        ),
        "Text"=> $note
    );

    $quoteSGJSONDecode = json_encode($quoteSG, JSON_UNESCAPED_SLASHES);
    //echo $quoteSGJSONDecode;die();
    // Save back lead 
    $url = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $quoteSGJSONDecode);
    
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
            "Content-Length: " .strlen($quoteSGJSONDecode),
            "Authorization: Basic ".base64_encode($username . ":" . $password),
            "Referer: https://crm.solargain.com.au/lead/edit/".$quoteSG_ID,
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

$record_id = urldecode($_GET['record_id']);

// $db = DBManagerFactory::getInstance();
// $query = $sql = "SELECT id_c as id 
//                FROM leads_cstm  
//                WHERE 
//                create_solar_quote_num_c = '$record_id' OR create_methven_number_c = '$record_id'
//                OR create_sanden_quote_num_c = '$record_id' OR 	create_daikin_quote_num_c = '$record_id'
//                ";
// $ret = $db->query($sql);
// $array_quote_suite_id = array();
// while($row = $db->fetchByAssoc($ret)){
//    $record_lead_id = $row['id'];
// }
updateSolargainQuote($record_id);

$quote = new AOS_Quotes();
$quote = $quote->retrieve($record_id);
if($quote->id == '' || $quote->number == '') return;
$description = $quote->description;
$address = $_GET["billing_address_street"] . ", " . 
            $_GET["billing_address_city"] . ", " . 
            $_GET["billing_address_state"] . ", " . 
            $_GET["billing_address_postalcode"] ;
$lat_long = get_lat_long($address);

$contact = new Contact();
$contact = $contact->retrieve($quote->billing_contact_id);

$firstName = $contact->first_name;
$lastName = $contact->last_name;
$mail->Subject = 'Request Solar Designs - '. $firstName . ' ' . $lastName . ' ' . ' ' . $address ;
/**
 *  Custom table list 10 new leads "Solargain LEADS Job NOT Completed"
 *  It is same like "SOLARGAIN LEADS JOB NOT COMPLETED" report
 */
// TriTruong - Remove Solargain Quotes jobs not complete
// $html_table_report = '
// <h1>Solar awaiting designer</h1>
// <table>
//         <tr>
//             <td width="10%"><strong>Quote Number</strong></td>
//             <td width="10%"><strong>Quote Title</strong></td>
//             <td width="10%"><strong>Date Created</strong></td>
//             <td width="10%"><strong>First Name</strong></td>
//             <td width="10%"><strong>Last Name</strong></td>
//             <td width="10%"><strong>Primary Address Street</strong></td>
//             <td width="10%"><strong>Primary Address State</strong></td>
//             <td width="10%"><strong>Time Completed Job</strong></td>
//             <td width="10%"><strong>Designer</strong></td>
//             <td width="10%"><strong>Status</strong></td>
//             <td width="10%"><strong>Solargain Lead Number</strong></td>
//             <td width="10%"><strong>Solargain Quote Number</strong></td>
//             <td width="10%"><strong>Assigned to</strong></td>
//             <td width="10%"><strong>Distance to SG</strong></td>
//         </tr>';

// $db = DBManagerFactory::getInstance();
// $query = $sql = "SELECT aos_quotes.id as id 
//                FROM aos_quotes 
//                INNER JOIN aos_quotes_cstm ON aos_quotes.id = aos_quotes_cstm.id_c 
//                WHERE  aos_quotes.deleted = 0
//                AND (aos_quotes.stage  IN ('Negotiation','Draft','') OR aos_quotes.stage IS NULL)
//                AND ( aos_quotes.billing_address_street IS NOT NULL OR aos_quotes.billing_address_street !='')
//                AND ( aos_quotes_cstm.time_completed_job_c IS NULL OR aos_quotes_cstm.time_completed_job_c ='')
//                ORDER BY aos_quotes.number DESC LIMIT 10
//                ";
// $ret = $db->query($sql);
// $array_quote_suite_id = array();
// while($row = $db->fetchByAssoc($ret)){
//    array_push($array_quote_suite_id,$row['id']);
//    //$array_lead_suite_id[]= $row['id'];
// }
//    foreach ($array_quote_suite_id as $key => $value) {
//       $bean_quote = new AOS_Quotes();
//       $bean_quote->retrieve($value);
//       $quote_id = $bean_quote->id;
//       $quote_number = $bean_quote->number;
//       $date_created = $bean_quote->date_entered ;
//       $quote_name = $bean_quote->name;
//       //thienpb code - explain name from account 
//       $query =   "SELECT leads.id FROM aos_quotes 
//                   INNER JOIN leads ON leads.account_id = aos_quotes.billing_account_id 
//                   WHERE aos_quotes.billing_account_id = '$bean_quote->billing_account_id' 
//                   AND aos_quotes.id = '$bean_quote->id' 
//                   AND aos_quotes.deleted = 0 LIMIT 1";
//       $db = DBManagerFactory::getInstance();
//       $result_lead = $db->query($query);
//       if($result_lead->num_rows > 0){
//          $lead_row = $db->fetchByAssoc($result_lead);

//          $lead = new Lead();
//          $lead =  $lead->retrieve($lead_row['id']);

//          $first_name = $lead->first_name;
//          $last_name = $lead->last_name;
//       }else{
//          $account_name_array = explode(' ',$bean_quote->billing_account,2);
//          $first_name = $account_name_array[0];
//          $last_name = $account_name_array[1];
//       }

//       $billing_address_street = $bean_quote->billing_address_street;
//       $billing_address_state = $bean_quote->billing_address_state;
//       $time_completed_job_c  = $bean_quote->time_completed_job_c ;
//       $designer =  $bean_quote->designer_c;
//       $Status =$bean_quote->stage;
//       $Solargain_Lead_Number = $bean_quote->solargain_lead_number_c;
//       $Solargain_Quote_Number = $bean_quote->solargain_quote_number_c;
//       $Solargain_Quote_Number_tesla =  $bean_quote->solargain_tesla_quote_number_c;
//       $Assigned_to =  $bean_quote->assigned_user_name;
//       $Distance_to_SG =  $bean_quote->distance_to_sg_c;;

//       $html_table_report .= 
//             "<tr>
//                 <td><a target='_blank' href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$quote_id."'>". $quote_number."</a></td>
//                 <td>".$quote_name."</td>
//                 <td>".$date_created."</td>
//                 <td>".$first_name."</td>
//                 <td>".$last_name."</td>
//                 <td>".$billing_address_street."</td>
//                 <td>".$billing_address_state."</td>
//                 <td>".$time_completed_job_c."</td>
//                 <td>".$designer."</td>
//                 <td>".$Status."</td>
//                 <td><a target='_blank' href='https://crm.solargain.com.au/lead/edit/".$Solargain_Lead_Number."'>". $Solargain_Lead_Number."</a></td>
//                 <td><a target='_blank' href='https://crm.solargain.com.au/quote/edit/".$Solargain_Quote_Number."'> ". $Solargain_Quote_Number." </a><a target='_blank' href='https://crm.solargain.com.au/quote/edit/".$Solargain_Quote_Number_tesla."'> ". $Solargain_Quote_Number_tesla." </a></td>
//                 <td>".$Assigned_to."</td>
//                 <td>".$Distance_to_SG."</td>
//             </tr>";
//    }


//    $html_table_report .= '</table>';
   
$body = '
<div dir="ltr">
Hi Team,
<div><br></div>
<div>Another Solar design to do.&nbsp;&nbsp;</div>
<div><br></div>
<div>'.$quote->billing_account.'</div>
<div>'.$_GET["billing_address_street"].'</div>
<div>'.$_GET["billing_address_city"].'<br></div>
<div>'.$_GET["billing_address_state"].'</div>
<div>'.$_GET["billing_address_postalcode"].'</div>
<div><br></div>


<div><a target="_blank" href="https://www.google.com/maps/place/' . $address . '">Google Maps</a></div>

<div><a target="_blank" href="http://maps.google.com/maps?q=&layer=c&cbll=' .$lat_long['lat']. ',' .$lat_long['lng']. '&cbp=11,0,0,0,0">Google Streetview</a></div>

<div><a target="_blank" href="http://maps.nearmap.com?addr='. $address . '&z=22&t=roadmap">Near Map</a></div>
<div><br>
<b>Instruction: </b>
'
. '' .'
</div>
<div><br></div>
<div>Please upload the pictures to the CRM when you\'re finished.</div>
<div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=AOS_Quotes&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link</a></div>
<div><br></div>
<div>Please click the link below to accept job.</div>
<div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customQuotesAcceptJob&accepted=link&record_id=' . $record_id . '">Accept Job</a></div>
<div><br></div>';
//TriTruong : get information from report Solar awaiting designer
$report_solar_awaiting_designer = new AOR_Report();
$report_solar_awaiting_designer->retrieve("b15ba74a-2aa2-41c2-b8da-5c7721de0bc2"); //report name : Solar awaiting designer
$bottom_report_solar_awaiting_designer = $report_solar_awaiting_designer->build_group_report();
$bottom_report_solar_awaiting_designer = '<br>————————————<br><h2><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOR_Reports&action=DetailView&record=b15ba74a-2aa2-41c2-b8da-5c7721de0bc2">Solar awaiting designer </a></h2>' . $bottom_report_solar_awaiting_designer;

//TriTruong : get information from report Solar job in progress

$report_solar_job_in_progress = new AOR_Report();
$report_solar_job_in_progress->retrieve("3e36a332-807d-aa7e-8444-5c7f0ddd6e92"); //report name : Solar job in progress
$bottom_report_solar_job_in_progress = $report_solar_job_in_progress->build_group_report();
$bottom_report_solar_job_in_progress = '<br>————————————<br><h2><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOR_Reports&action=DetailView&record=3e36a332-807d-aa7e-8444-5c7f0ddd6e92">Solar job in progress </a></h2>' . $bottom_report_solar_job_in_progress;


// '.$html_table_report .'<div><br></div>
// <div>
//    <br clear="all">
//    <div>
//       <div class="m_2099969047182855450gmail_signature">
//          <div dir="ltr">
//             <div>
//                <div dir="ltr">
//                   <div>
//                      <div dir="ltr">
//                         <div>
//                            <div dir="ltr">
//                               <div dir="ltr">
//                                  <div dir="ltr">
//                                     <div dir="ltr">
//                                        <span>
//                                           <div dir="ltr">
//                                              <div dir="ltr">
//                                                 <div dir="ltr">
//                                                    <div style="font-size:12.8px;color:rgb(0,0,0)">
//                                                       <div dir="ltr">
//                                                          <div dir="ltr"><span style="font-size:12.8px">Matthew Wright&nbsp;</span><font size="1">MEng(Distinct)&nbsp;<i>RMIT,&nbsp;</i></font><font size="1">Ph<wbr>D(c)<i>&nbsp;Aust German Climate &amp; Energy College,&nbsp;</i></font><i style="font-size:x-small">University of Melbourne</i></div>
//                                                          <div dir="ltr">
//                                                             <div>
//                                                                <div style="font-size:12.8px"><b>Pure Electric</b><br><a href="tel:0421%20616%20733" value="+61423494949" style="color:rgb(17,85,204)" target="_blank">0421 616 733</a></div>
//                                                                <div style="font-size:12.8px"><a href="mailto:matthew.wright@pure-electric.com.au" style="color:rgb(17,85,204);font-size:13.3333px" target="_blank">matthew.wright@pure-electric.<wbr>com.au</a><br></div>
//                                                                <div style="font-size:12.8px"><a href="http://pure-electric.com.au/" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://pure-electric.com.au/&amp;source=gmail&amp;ust=1511655808584000&amp;usg=AFQjCNHCSYSnjpeXuT_PB-W-jdcDdbFx5g"><img src="https://ci4.googleusercontent.com/proxy/-EZkUuh1S54x9CTvjE5vnIBIBjetkHUafAvt2uXpJWjKRW5yozCVmLDfPZYiab40V04sPxWp4DrAqMyfqvBflmM2NCjqzmJnQmlsDPYu=s0-d-e1-ft#http://pure-electric.com.au/sites/default/files/logo.png" alt="Home" width="200" height="47" class="CToWUd"></a></div>
//                                                             </div>
//                                                             <div style="font-size:12.8px"><br></div>
//                                                          </div>
//                                                       </div>
//                                                    </div>
//                                                    <div style="font-size:12.8px">Executive Director<br></div>
//                                                    <div style="font-size:12.8px">Beyond The Grid P/L<br></div>
//                                                    <div style="font-size:12.8px">Pure Electric - Power to be free<br><br>Executive Director</div>
//                                                    <div style="font-size:12.8px">Zero Emissions Australia</div>
//                                                    <div style="font-size:12.8px"><a href="http://zeroemissions.org.au/" style="color:rgb(17,85,204)" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://zeroemissions.org.au/&amp;source=gmail&amp;ust=1511655808584000&amp;usg=AFQjCNHLsbpHmkmGssQ2tI4zQyWjRcIoKQ">http://zeroemissions.org.au</a></div>
//                                                    <div style="font-size:12.8px">Climate and Energy Solutions for the 21st Century</div>
//                                                    <div style="font-size:12.8px">Lead Author Zero Carbon Australia Stationary Energy Plan</div>
//                                                    <div style="font-size:12.8px">Environment Minister\'s Young Environmentalist of the Year 2010</div>
//                                                    <div style="font-size:12.8px">Mercedes Benz Research Award Winner 2010</div>
//                                                    <div style="font-size:12.8px">EcoGen Clean Energy Young Industry Leader 2010</div>
//                                                    <div style="font-size:12.8px">Wild Environmentalist of the Year 2012</div>
//                                                 </div>
//                                              </div>
//                                           </div>
//                                        </span>
//                                     </div>
//                                  </div>
//                               </div>
//                               </div>
//                               </div>
//                               </div>
//                   </div>
//                </div>
//             </div>
//          </div>
//       </div>
//    </div>
//    <div class="yj6qo"></div>
//    <div class="adL"></div>
// </div>
// </div>
// ';

$mail->Body = $body.$bottom_report_solar_awaiting_designer.$bottom_report_solar_job_in_progress;

$mail->prepForOutbound();
// /$mail->AddAddress('binhdigipro@gmail.com');
// $mail->AddAddress('congtri1010@gmail.com');
$mail->AddAddress('admin@pure-electric.com.au');
// $mail->AddAddress('thienpb89@gmail.com');
$mail->AddAddress('quoc.huy@pure-electric.com.au');

 $mail->AddCC('info@pure-electric.com.au');
//$mail->AddAddress('nguyenphudung93.dn@gmail.com');
$sent = $mail->Send();
//dung code - logic send request one time
if ($sent) {
    $mail->status = 'sent';
    $quote->email_send_design_status_c = 'sent';
    $quote->email_send_design_request_id_c = $emailObj->id;
    //dung code- update time sent email Request Design
    date_default_timezone_set('UTC');
    $dateAUS = date('Y-m-d H:i:s', time());
    $quote->time_request_design_c = $dateAUS;
    $quote->stage = 'Request_Designs';
    $quote->save();
} else {
    if ($mail->status !== 'draft') {
        $mail->status = 'send_error';
    } else {
        $mail->status = 'send_error';     
    }
} 
//dung code - display time click "Request Design"
if($sent){
    $time_now = date('d/m/Y H:i', time());
    echo $time_now;
}


?>
