<?php
    $lastUpdated = date("d/m/Y h:i A",strtotime(str_replace('/','-',$_REQUEST['lastUpdated'])));
    $lastUpdatedBy = $_REQUEST['lastUpdatedBy'];
    $URL = '<a href="http://devel.pure-electric.com.au/sanden-stock-summary">http://devel.pure-electric.com.au/sanden-stock-summary</a>';

    $data = $_REQUEST['data'];
    $table_summary ='<table border="1">';
    $table_summary .=   '<thead>
                            <tr>
                            <th>Product</th>
                            <th>In Warehouse - Unallocated</th>
                            <th>Not in Warehouse - Unallocated</th>
                            <th>Date of Arrival</th>
                            <th>Days to Arrival</th>
                            </tr>
                        </thead><tbody>';
    foreach($data as $res){
        $table_summary .= '<tr>';
        $table_summary .= '<td>'.$res['product_name'].'</td>';
        $table_summary .= '<td>'.$res['in_warehouse'].'</td>';
        $table_summary .= '<td>'.$res['not_in_warehouse'].'</td>';
        $table_summary .= '<td>'.$res['date_of_arrival'].'</td>';
        $table_summary .= '<td>'.$res['days_of_arrival'].'</td>';
        $table_summary .= '</tr>';
    }
    $table_summary .='</tbody></table>';

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = 'info@pure-electric.com.au';  
    //  $mail->From = 'pureDev2019@gmail.com';  
    $mail->FromName = 'Pure Electric';  
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  'Sanden Stock Summary - Updated '. $lastUpdated; //22/08/2020 01:10 PM;

    $bodytext =    '<div>Hi Pure Electric, The Sanden Stock Summary has been updated at '.$lastUpdated.'. Please see the table summary below:</div>
                    <div>
                        <p>'.$table_summary.'</p>
                    <div>
                        The Summary was updated by: <strong>'.$lastUpdatedBy.'</strong>
                    </div>
                    <p>Here is a link to the Sanden Stock Summary : '.$URL.'</p>';
    $mail->Body = $bodytext;
    // $mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();
    $sent = $mail->send();
    // if($sent){
    //     echo json_encode(array('msg'=>'sent'));
    //     die();
    // }else{
    //     echo json_encode(array('msg'=>'fail'));
    //     die();
    // }
?>