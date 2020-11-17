<?php 
 //WARNING: The contents of this file are auto-generated

 
array_push($job_strings, 'custom_remindNextActionDateInvoice');
require_once('include/SugarPHPMailer.php');

function custom_remindNextActionDateInvoice() {
  $db = DBManagerFactory::getInstance();
  $sql = "SELECT aos_invoices.id, aos_invoices.name, aos_invoices.number, aos_invoices.billing_account_id as acc_id, aos_invoices.billing_contact_id as contact_id, aos_invoices_cstm.next_action_date_c as next_action_date, accounts.name as acc_name, CONCAT(contacts.first_name, ' ', contacts.last_name) as contact_name
          FROM aos_invoices
          LEFT JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
          LEFT JOIN accounts ON accounts.id = aos_invoices.billing_account_id
          LEFT JOIN contacts ON contacts.id = aos_invoices.billing_contact_id
          WHERE DATEDIFF(aos_invoices_cstm.next_action_date_c, NOW()) = 0
          ORDER BY aos_invoices_cstm.next_action_date_c ASC
        ";
  
  $ret = $db->query($sql);
  while ($row = $db->fetchByAssoc($ret))
  {
    $main_url = 'https://suitecrm.pure-electric.com.au/';
    $from_address = 'operations@pure-electric.com.au';
    $to_address = 'operations@pure-electric.com.au';

    $body_mail ='';
    $body_mail .= '<div><table><tbody>';
    if ($row['contact_id'] != '') {
      $body_mail .= '<tr><td style="font-size: 20px;"><span>Contact : </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Contacts&action=DetailView&record='.$row['contact_id'].'" target="_blank">'.$row['contact_name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Contacts&action=EditView&record='.$row['contact_id'].'" target="_blank">[Edit]</a></td></tr>';
    }
    if ($row['acc_id'] != '') {
      $body_mail .='<tr><td style="font-size: 20px;"><span>Account: </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Accounts&action=DetailView&record='.$row['acc_id'].'" target="_blank">'.$row['acc_name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=Accounts&action=EditView&record='.$row['acc_id'].'" target="_blank">[Edit]</a></td></tr>';
    }
    $body_mail .='<tr><td style="font-size: 20px;"><span>Invoice #'.$row['number'].': </span><a style="text-decoration: solid;" href="'.$main_url.'index.php?module=AOS_Invoices&action=DetailView&record='.$row['id'].'" target="_blank">'.$row['name'].'</a> <a style="text-decoration: solid;" href="'.$main_url.'index.php?module=AOS_Invoices&action=EditView&record='.$row['id'].'" target="_blank">[Edit]</a></td></tr></tbody></table></div>';
    
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $from_address;
    $mail->FromName = "Pure Info";
    $mail->Subject = 'Prepare for this job Invoice#'.$row['number'];
    $mail->Body = $body_mail;
    $mail->IsHTML(true);
    $mail->AddAddress($to_address);
    $mail->prepForOutbound();
    $mail->Send();
  }
}

 
array_push($job_strings, 'custom_EmailInstallerPaperworkFollowUp');

function custom_EmailInstallerPaperworkFollowUp(){

    date_default_timezone_set('UTC');
    $array_condition_status = ['STC_VEEC_Unpaid','STC_Unpaid','VEEC_Unpaid','Paid'];
    $string_condition_status = implode("','",$array_condition_status) ;
    $array_invoice_ID = [];

    $db = DBManagerFactory::getInstance();
    $query =  "SELECT aos_invoices.id as id , aos_invoices_cstm.installation_date_c as installation_date_c
    FROM aos_invoices
    INNER JOIN aos_invoices_cstm ON aos_invoices_cstm.id_c = aos_invoices.id
    WHERE aos_invoices.status IN ('$string_condition_status') 
    AND aos_invoices.name NOT LIKE '%Warranty%' 
    AND aos_invoices.name NOT LIKE '%Service%'
    AND (aos_invoices_cstm.installation_date_c != '' OR aos_invoices_cstm.installation_date_c IS NOT NULL)
    AND (aos_invoices_cstm.account_id1_c != '' OR aos_invoices_cstm.account_id_c != '')";
    $result = $db->query($query);
    while (($row=$db->fetchByAssoc($result)) != null) {
        //add condition send email 
        $result_condition_send = condition_create_email_paperwork($row['id']);
        if($result_condition_send['paperwork_elec'] || $result_condition_send['paperwork_plum']) {
            $array_data = create_data_invoice($result_condition_send,$row['id']);
            $array_invoice_ID[$row['id']] = $array_data ;
        }
    }
    send_email_report_paperwork_follow_up($array_invoice_ID);
}


function check_exist_filename($id_folder, $string) {
    $source = realpath(dirname(__FILE__) . '/../../../../').'/include/SugarFields/Fields/Multiupload/server/php/files/'.$id_folder;
    $file_array = scandir($source);
    $file_array = array_diff($file_array, array('.', '..'));
    foreach($file_array as $file){
        if (strpos(strtolower($file), $string) !== false) {
            return true; 
        }
    }
    return false;
}

function condition_create_email_paperwork($record_id){
    $array_return = array(
        'paperwork_elec' => false,
        'paperwork_plum' => false,
    );
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);

    if(!$focus->id) return $array_return;
       //PO plumber
    if($focus->plumber_po_c != ''&& $focus->vba_pic_cert_c == '' && !check_exist_filename($focus->installation_pictures_c,'pcoc')) {
        $array_return['paperwork_plum'] = true;
    }

    //PO Electrical
    if($focus->electrical_po_c != ''&& $focus->ces_cert_c == ''&& !check_exist_filename($focus->installation_pictures_c,'ces')) {
        $array_return['paperwork_elec'] = true;
    }
    return $array_return;
}

function table_content_report_paperwork($data_inv){

    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $xero_domain_crm = 'https://go.xero.com/';


    if(count($data_inv)>0){
        $html_content = '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="5%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="20%"><strong>Title</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="5%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Installation Date</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="12%"><strong>Link Xero</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="20%"><strong>Link Email</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="7%"><strong>Grand Total</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Assigned to</strong></td>
            </tr>';
        foreach($data_inv as $res){
            $link_pe = '';
            $link_email_edit = '';
            $link_html_xero =  '';
            if($res['id'] != '') {
                
                $link_pe = $pe_domain_crm . '/index.php?module=AOS_Invoices&action=EditView&record=' .$res['id'];
                
                //create link xero invoice
                if($res['xero_invoice_c'] != ''){
                    $link_xero =  $xero_domain_crm . '/AccountsReceivable/Edit.aspx?InvoiceID='.$res['xero_invoice_c'];
                    $link_html_xero .= "<a target='_blank' href=".$link_xero.">Xero Invoice</a><br>";
                }
                if($res['xero_veec_rebate_invoice_c'] != ''){
                    $link_xero =  $xero_domain_crm . '/AccountsReceivable/Edit.aspx?InvoiceID='.$res['xero_veec_rebate_invoice_c'];
                    $link_html_xero .= "<a target='_blank' href=".$link_xero.">Xero Invoice VEEC</a><br><br>";
                }
                if($res['xero_stc_rebate_invoice_c'] != ''){
                    $link_xero =  $xero_domain_crm . '/AccountsReceivable/Edit.aspx?InvoiceID='.$res['xero_stc_rebate_invoice_c'];
                    $link_html_xero .= "<a target='_blank' href=".$link_xero.">Xero Invoice STC</a><br><br>";
                }
                if($res['xero_shw_rebate_invoice_c'] != ''){
                    $link_xero =  $xero_domain_crm . '/AccountsReceivable/Edit.aspx?InvoiceID='.$res['xero_shw_rebate_invoice_c'];
                    $link_html_xero .= "<a target='_blank' href=".$link_xero.">Xero Invoice SHWR</a><br>";
                }

                //create link email
                if($res['paperwork_plum']){
                    $link_email =  $pe_domain_crm . '/index.php?entryPoint=Create_Email_Draft&type=CreateEmailPaperWork_Plum&module=AOS_Invoices&record=' .$res['id'];
                    $link_email_edit .=  "<a target='_blank' href=".$link_email.">Create Email PaperWork Plumber</a><br><br>";
                }

                if($res['paperwork_elec']){
                    $link_email =  $pe_domain_crm . '/index.php?entryPoint=Create_Email_Draft&type=CreateEmailPaperWork_Elec&module=AOS_Invoices&record=' .$res['id'];
                    $link_email_edit .=  "<a target='_blank' href=".$link_email.">Create Email PaperWork Electricial</a><br>";
                }

                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link_pe.">Inv#". $res['number']."</a></td>
                    <td style='border: 1px solid black;' >".$res['name']."</td>
                    <td style='border: 1px solid black;' >".$res['status']."</td>
                    <td style='border: 1px solid black;' >".$res['installation_date_c']."</td>
                    <td style='border: 1px solid black;' >".$link_html_xero."</td>
                    <td style='border: 1px solid black;' >".$link_email_edit."</td>
                    <td style='border: 1px solid black;' >".$res['total_amount']."</td>
                    <td style='border: 1px solid black;' >".$res['assigned_user_name']."</td>
                </tr>";
            }
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No Invoice</h4>";
    }
    return  $html_content;
}

function create_data_invoice($array_data,$record_id){
    $macro_nv = array();
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);
    $array_data['id'] = $focus->id;
    $array_data['name'] = $focus->name;
    $array_data['number'] = $focus->number;
    $array_data['status'] = $focus->status;
    $array_data['total_amount'] = '$'.substr($focus->total_amount,0,-4);
    $array_data['installation_date_c'] =  $focus->installation_date_c;
    $array_data['assigned_user_name'] =  $focus->assigned_user_name;
    $array_data['xero_invoice_c'] =  $focus->xero_invoice_c;
    $array_data['xero_veec_rebate_invoice_c'] =  $focus->xero_veec_rebate_invoice_c;
    $array_data['xero_stc_rebate_invoice_c'] =  $focus->xero_stc_rebate_invoice_c;
    $array_data['xero_shw_rebate_invoice_c'] =  $focus->xero_shw_rebate_invoice_c;
    return $array_data;
}

function send_email_report_paperwork_follow_up($data_inv){
    $body = table_content_report_paperwork($data_inv);
    $today = date('d/m/Y', time());
    $subject = "<div><h1 'text-align:center;'>Pure-Electric Email Installer Paperwork Follow UP - Daily Report - Date " . $today .'</h1></div>';
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "accounts@pure-electric.com.au";
    $mail->FromName = "PureElectric Accounts";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject ="Pure-Electric Email Installer Paperwork Follow UP - Daily Report - Date " . $today ;
    $mail->Body = $subject.$body;
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
     $mail->AddAddress("info@pure-electric.com.au");

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
    // echo $mail->Body;
}
 
array_push($job_strings, 'custom_autoCreateInvoiceByOrderSam');

function custom_autoCreateInvoiceByOrderSam(){

        $quoteJSON_MT = GetJson_CRMSolargainOrders('matthew.wright','MW@pure733');
        $quoteJSON_PS = GetJson_CRMSolargainOrders('paul.szuster@solargain.com.au','Baited@42');
        $data_json = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);
        $aray_order_number = [];
        foreach ($data_json as $key => $value) {
            $aray_order_number[] = $value->ID;
        }
        $string_order_no = implode("','",$aray_order_number) ;
        $array_invoice_created = [];
        $aray_order_done=[];
        $db = DBManagerFactory::getInstance();
        $SAM_Quote_Number = $json_result->Quote->ID;
        $query =  "SELECT aos_invoices.id as id, aos_invoices.number ,aos_invoices_cstm.solargain_invoices_number_c as order_number
        FROM aos_invoices
        INNER JOIN aos_invoices_cstm ON aos_invoices_cstm.id_c = aos_invoices.id
        WHERE aos_invoices_cstm.solargain_invoices_number_c IN ('$string_order_no') AND aos_invoices.deleted = 0";
        $result = $db->query($query);
        while (($row=$db->fetchByAssoc($result)) != null) {
            $aray_order_done[] = $row['order_number'] ;
            $array_invoice_created[] = $row['id'];
        }
    
    $array_order_need_to_create = array_diff($aray_order_number,$aray_order_done);
    foreach ($array_order_need_to_create as $key => $value) {
        if($value != ''){
            $invoice_id = Create_Invoice_By_Order_Number($value);
            Create_Meeting_By_InvoiceID($invoice_id);
        }
    }

    // custom auto updated install date and status SAM in invoice
    foreach ($array_invoice_created as $key => $value) {
        if($value != ''){
            Update_Invoice_By_InvoiceID($value);
        }
    }

    // function render data send email report
    foreach ($quoteJSON_MT->Results as $key => $value) {
        $aray_order_number_MT[] = $value->ID;
    }
    $data_information_invoice= [];
    $query =  "SELECT aos_invoices.id as id
        FROM aos_invoices
        INNER JOIN aos_invoices_cstm ON aos_invoices_cstm.id_c = aos_invoices.id
        WHERE aos_invoices_cstm.solargain_invoices_number_c IN ('$string_order_no') AND aos_invoices.deleted = 0";
    $result = $db->query($query);

    while (($row=$db->fetchByAssoc($result)) != null) {
        $invoice = new AOS_Invoices;
        $invoice->retrieve($row['id']);
        $assigned_user_name = "Paul Szuster";
        if(in_array($invoice->solargain_invoices_number_c,$aray_order_number_MT)) {
            $assigned_user_name = 'Matthew';
        }
        $item_data_information_invoice = array (
            'id' => $invoice->id,
            'number' =>  $invoice->number,
            'order_number' => $invoice->solargain_invoices_number_c,
            'name' => $invoice->name,
            'status' => $invoice->status,
            'assigned_user_name' => $assigned_user_name ,
            'due_date' => $invoice->due_date,
            'order_status' => $invoice->solargain_order_status_c,
            'total_amount' => '$'.substr($invoice->total_amount,0,-4),
            'xero_invoice_c' => $invoice->xero_invoice_c,
        );
        $data_information_invoice[] = $item_data_information_invoice;
    }

    if(date('D', time()) == 'Mon'){
        Email_Report_Invoice_Sam_Daily($data_information_invoice);
    }
}

function GetJson_CRMSolargainOrders($username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $url = 'https://crm.solargain.com.au/apiv2/orders/search';

    $param = array (
        'Page' => 1,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'PageSize' => 50,
        'Filters' => 
        array (
          0 => 
          array (
            'Field' => 
            array (
              'Category' => 'Order',
              'Name' => 'Status',
              'Code' => 'STATUS',
              'Type' => 5,
            ),
            'Value' =>"COMPLETED",
            'Operation' => 'NOT',
          ),
          1 => 
          array (
            'Field' => 
            array (
              'Category' => 'Order',
              'Name' => 'Status',
              'Code' => 'STATUS',
              'Type' => 5,
            ),
            'Value' => "CANCELLATION_REQUESTED",
            'Operation' => 'NOT',
          ),
          2 => 
          array (
            'Field' => 
            array (
              'Category' => 'Order',
              'Name' => 'Status',
              'Code' => 'STATUS',
              'Type' => 5,
            ),
            'Value' => "CANCELLED",
            'Operation' => 'NOT',
          ),
        ),
      );

    $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Content-Length: ".strlen($paramJSONDecode),
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/order/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );

    return $resultJSON;
}

function Create_Invoice_By_Order_Number($sg_order_number){
  if($sg_order_number == '') return '';
  // account matthew
  $username = "matthew.wright";
  $password = "MW@pure733";
  $assigned_user_id = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
  $assigned_user_name = 'Matthew Wright';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
  $headers = array();
  $headers[] = "Pragma: no-cache";
  $headers[] = "Accept-Encoding: gzip, deflate, br";
  $headers[] = "Accept-Language: en-US,en;q=0.9";
  $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
  $headers[] = "Accept: application/json, text/plain, */*";
  $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
  $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
  $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
  $headers[] = "Connection: keep-alive";
  $headers[] = "Cache-Control: no-cache";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  curl_close ($ch);
  $json_result = json_decode($result);
  //change account paul
  if(!isset($json_result->ID)) {
      $username = 'paul.szuster@solargain.com.au';
      $password = 'Baited@42';

      $assigned_user_id = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
      $assigned_user_name = 'Paul Szuster';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
      $headers = array();
      $headers[] = "Pragma: no-cache";
      $headers[] = "Accept-Encoding: gzip, deflate, br";
      $headers[] = "Accept-Language: en-US,en;q=0.9";
      $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
      $headers[] = "Accept: application/json, text/plain, */*";
      $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
      $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
      $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
      $headers[] = "Connection: keep-alive";
      $headers[] = "Cache-Control: no-cache";
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
      $result = curl_exec($ch);
      curl_close ($ch);
      $json_result = json_decode($result);
  }
  //logic for install date, due date
  $installdate = '';
  if(isset($json_result->InstallDate)){
      $date = date_create($json_result->InstallDate);
      $installdate = date_format($date,"Y-m-d");
  }else {
      $date = $json_result->Quote->ProposedInstallDate->Date;
      $array_date = explode("/", $date);
      $installdate =  $array_date[2] .'-' . $array_date[1] . '-' . $array_date[0] ;
  }
   //logic for title invoice
  if(isset($json_result->Customer->TradingName) && $json_result->Customer->TradingName !== ''){
      $title_inv = 'Solargain_' .$json_result->Customer->TradingName .'_' .$json_result->Install->Address->Locality .'_Order #' .$sg_order_number;
  }else{
      $title_inv = 'Solargain_' .$json_result->Customer->Name .'_' .$json_result->Install->Address->Locality .'_Order #' .$sg_order_number;
  }

  //logic get quote number in suitecrm by quote number SAM
  $db = DBManagerFactory::getInstance();
  $SAM_Quote_Number = $json_result->Quote->ID;
  $query = "SELECT id , number FROM aos_quotes 
  INNER JOIN aos_quotes_cstm ON aos_quotes.id = aos_quotes_cstm.id_c
  WHERE aos_quotes.deleted = 0 AND (aos_quotes_cstm.solargain_quote_number_c ='$SAM_Quote_Number' OR aos_quotes_cstm.solargain_tesla_quote_number_c ='$SAM_Quote_Number') LIMIT 1";
  $result = $db->query($query);
  while (($row=$db->fetchByAssoc($result)) != null) {
      $rows = $row;
  }

  $invoice = new AOS_Invoices();
  $invoice->name = $title_inv;
  $invoice->solargain_invoices_number_c = $sg_order_number;
  $invoice->invoice_type_c = 'Solar';
  $invoice->quote_type_c = 'quote_type_solar';
  $invoice->solargain_order_status_c = $json_result->PVStatus;
  //defaul: account ,contact  of Solargain PV Pty Ltd 
  $invoice->billing_account_id = '61db330d-0aee-6661-8ac3-585c79c765a2';
  $invoice->billing_account = 'Solargain PV Pty Ltd';
  $invoice->billing_account = 'Solargain PV Pty Ltd';
  $invoice->billing_contact = 'Solargain Accounts';
  $invoice->billing_contact_id = '296a953e-c7d0-40b6-09d3-5ab9cb674a5c';
  $invoice->billing_address_street = '10 Milly Court';
  $invoice->billing_address_city = 'Malaga';
  $invoice->billing_address_state = 'WA';
  $invoice->billing_address_postalcode = '6090';
  $invoice->shipping_address_street = '10 Milly Court';
  $invoice->shipping_address_city = 'Malaga';
  $invoice->shipping_address_state = 'WA';
  $invoice->shipping_address_postalcode = '6090';
  // address install 
  $invoice->install_address_c=$json_result->Install->Address->Street1;
  $invoice->install_address_city_c=$json_result->Install->Address->Locality;
  $invoice->install_address_state_c=$json_result->Install->Address->State;
  $invoice->install_address_postalcode_c=$json_result->Install->Address->PostCode;
  // Assigned  default by user active
  $invoice->assigned_user_name= $assigned_user_name;
  $invoice->assigned_user_id= $assigned_user_id;
  //install date ; due date
  $invoice->installation_date_c= $installdate .' 01:00:00';
  $invoice->due_date= $installdate;
  //number quote in suitecrm
  $invoice->quote_number =($rows['number']) ? $rows['number'] : '';
  $invoice->save();

  // save group product
  $product_quote_group = new AOS_Line_Item_Groups();
  $product_quote_group->name = 'Solar PV Sales';
  $product_quote_group->created_by = $assigned_user_name;
  $product_quote_group->assigned_user_id = $assigned_user_id;
  $product_quote_group->parent_type = 'AOS_Invoices';
  $product_quote_group->parent_id = $invoice->id;
  $product_quote_group->number = '1';
  $product_quote_group->currency_id = '-99';
  $product_quote_group->save();

   //product sanden

   $part_numners = ['SolarSales'];
   $part_numners_implode = implode("','", $part_numners);
   $db = DBManagerFactory::getInstance();

   $sql = "SELECT * FROM aos_products WHERE part_number IN ('".$part_numners_implode."')";
   $ret = $db->query($sql);
   $total_amt = 0;
   $subtotal_amount= 0;
   $discount_amount =0;
   $tax_amount =0;
   $total_amount = 0;
   $total_amount = 0;
   $index = 1;
   $is_use_number_1 = false;

  //total price
  $total_price_SAM = $json_result->Quote->SystemPrice;
  $list_price = ($total_price_SAM*0.08*1.1)/1.1;
  $tax = $list_price*0.1;


   while ($row = $db->fetchByAssoc($ret))
   {   
    
           $product_line = new AOS_Products_Quotes();
           $product_line->currency_id = $row['currency_id'];
           $product_line->item_description =$title_inv .'  Discounted \nTotal Price: $' . $total_price_SAM;
           $product_line->name = $row['name'];
           $product_line->part_number = $row['part_number'];
           $product_line->product_cost_price = $list_price;
           $product_line->product_id = $row['id'];
           $product_line->product_list_price =$list_price;
           $product_line->group_id = $product_quote_group->id;
           $product_line->parent_id = $invoice->id;;
           $product_line->parent_type = 'AOS_Invoices';
           $product_line->discount = 'Percentage';
           //display number index 
           $product_line->number = 1;
      
           //logic total amount 
          $product_line->product_qty = 1;
          $product_line->product_total_price = $list_price;      
          $product_line->vat = '10.0';
          $product_line->vat_amt = round($list_price * 0.1,2);        
          $product_line->save();
           
           $total_amt += $product_line->product_total_price;
           $tax_amount += $product_line->vat_amt;
       
   }
   
   $discount_amount =0;
   $total_amount = $total_amt + $tax_amount;
   $subtotal_amount= $total_amt;

   $invoice->total_amt = round($total_amt , 2);
   $invoice->subtotal_amount = round($subtotal_amount , 2);
   $invoice->discount_amount = round($discount_amount , 2);
   $invoice->tax_amount = round($tax_amount , 2);
   $invoice->total_amount = round($total_amount , 2);
   $invoice->save();

   $product_quote_group->tax_amount = round($tax_amount , 2);
   $product_quote_group->total_amount = round($total_amount , 2);
   $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
   $product_quote_group->save();
  
  $invoice->save();
  return $invoice->id;
}

function Create_Meeting_By_InvoiceID($record_id){
      
      $invoice = new AOS_Invoices;
      $invoice->retrieve($record_id);

      if(!$invoice->id) return;
      if($invoice->installation_date_c == '') return;
      $meetings = new Meeting;
      $meetings->name = $invoice->name;
      $meetings->date_start = $invoice->installation_date_c;
      $meetings->parent_type = "Accounts";
      $meetings->parent_id = $invoice->billing_account_id;
      $meetings->assigned_user_id = $invoice->assigned_user_id;
      $meetings->assigned_user_name = $invoice->assigned_user_name;
      $meetings->aos_invoices_id_c = $record_id;
      if(empty($meetings->duration_hours)){
          $meetings->duration_hours  = 3;
          $meetings->duration_minutes  = 0;
      }
      $meetings->save();
      $invoice->meeting_c = $meetings->id;
      $invoice->save();
      $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
      $meetings->saving_reminders_data = true;
      $reminderData = json_encode(
          $meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
      );
      Reminder::saveRemindersDataJson('Meetings', $meetings->id, $reminderData);
      $meetings->saving_reminders_data = false;
      $relate_values = array('user_id'=> $invoice->assigned_user_id,'meeting_id'=>$meetings->id);
      $data_values = array('accept_status'=>true);
      $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

      // relationship users
      if( $invoice->assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
          $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
          $data_values = array('accept_status'=>true);
          $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
      }else if( $invoice->assigned_user_id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
          $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
          $data_values = array('accept_status'=>true);
          $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
      }else{
          $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
          $data_values = array('accept_status'=>true);
          $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

          $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
          $data_values = array('accept_status'=>true);
          $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
      }

      $relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meetings->id);
      $data_values = array('accept_status'=>true);
      $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);

      $invoice->meeting_c = $meetings->id;
      $invoice->save();
}

function Update_Invoice_By_InvoiceID($record_id){
    $invoice = new AOS_Invoices;
    $invoice->retrieve($record_id);

    if(!$invoice->id) return $record_id;
    if($invoice->status != 'Unpaid') return $record_id;

    $sg_order_number = $invoice->solargain_invoices_number_c;
    if($sg_order_number == '') return $record_id;
    // account matthew
    $username = "matthew.wright";
    $password = "MW@pure733";
    $assigned_user_id = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
    $assigned_user_name = 'Matthew Wright';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
    $result = curl_exec($ch);
    curl_close ($ch);
    $json_result = json_decode($result);
    //change account paul
    if(!isset($json_result->ID)) {
        $username = 'paul.szuster@solargain.com.au';
        $password = 'Baited@42';
  
        $assigned_user_id = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
        $assigned_user_name = 'Paul Szuster';
  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
        $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
        $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        curl_close ($ch);
        $json_result = json_decode($result);
    }
    //logic for install date, due date
    $installdate = '';
    if(isset($json_result->InstallDate)){
        $date = date_create($json_result->InstallDate);
        $installdate = date_format($date,"Y-m-d");
    }else {
        $date = $json_result->Quote->ProposedInstallDate->Date;
        $array_date = explode("/", $date);
        $installdate =  $array_date[2] .'-' . $array_date[1] . '-' . $array_date[0] ;
    }
    //install date ; due date
    $invoice->installation_date_c= $installdate .' 01:00:00';
    $invoice->due_date= $installdate;
    $invoice->solargain_order_status_c = $json_result->PVStatus;
    $invoice->save();
    Update_Meeting_By_InvoiceID($record_id);
    return $record_id;

}

function Update_Meeting_By_InvoiceID($record_id){
    $invoice = new AOS_Invoices;
    $invoice->retrieve($record_id);
    if(!$invoice->id) return;
    if(!$invoice->installation_date_c) return;

    $meetings = new Meeting;
    $meetings->retrieve($invoice->meeting_c);
    if($meetings->id != ''){
        $meetings->date_start = $invoice->installation_date_c;
        $meetings->save();
    }else{
        Create_Meeting_By_InvoiceID($record_id);
    }

}

function Email_Report_Invoice_Sam_Daily($data_inv) {
    $today = date('d/m/Y', time());
    $html_content = "<div><h1 style='text-align:center;'>Pure-Electric Invoice Solargain - Daily Report - Date " . $today .'</h1></div>';
    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $sam_domain_crm = 'https://crm.solargain.com.au';
    $xero_domain_crm = 'https://go.xero.com/';
    if(count($data_inv)>0){
        $html_content .= '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="20%"><strong>Title</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Due Date</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Grand Total</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Link SAM</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>SAM Status</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="15%"><strong>Link Xero</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="15%"><strong>Assigned to</strong></td>
            </tr>';
        foreach($data_inv as $res){
            if($res['id'] == '') {
                $link_pe = '';
                $link_sam= '';
                $link_xero= '';
                $link_html_xero =  '';
            }else{
                //update only once a week
                if($res['xero_invoice_c'] !='') {
                    Create_Update_Invoice_Xero($res['id'],'update');
                }
                
                $link_pe = $pe_domain_crm . '/index.php?module=AOS_Invoices&action=EditView&record=' .$res['id'];
                $link_sam= $sam_domain_crm .'/order/edit/'.$res['order_number'];
                $link_xero=  $xero_domain_crm . '/AccountsReceivable/Edit.aspx?InvoiceID='.$res['xero_invoice_c'];
                if($res['xero_invoice_c'] != ''){
                    $link_html_xero = "<a target='_blank' href=".$link_xero.">Xero Link</a>";
                }else{
                    $link_html_xero = "-";
                }
                
            }
            
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link_pe.">Inv#". $res['number']."</a></td>
                <td style='border: 1px solid black;' >".$res['name']."</td>
                <td style='border: 1px solid black;' >".$res['status']."</td>
                <td style='border: 1px solid black;' >".$res['due_date']."</td>
                <td style='border: 1px solid black;' >".$res['total_amount']."</td>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link_sam.">Order#". $res['order_number']."</a></td>
                <td style='border: 1px solid black;' >".$res['order_status']."</td>
                <td style='border: 1px solid black;' >".$link_html_xero."</td>
                <td style='border: 1px solid black;' >".$res['assigned_user_name']."</td>
            </tr>";
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No Invoice</h4>";
    }

    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->Subject = "Pure-Electric Invoice Solargain - Daily Report - Date " . $today;
    $mail->Body =  $html_content;
    $mail->prepForOutbound(); 
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");
    $sent = $mail->send();
}

function Create_Update_Invoice_Xero($invoice_id,$method){
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';
    $url = 'https://suitecrm.pure-electric.com.au/index.php';
    $url .= '?entryPoint=CRUD_Invoice_Xero&from_action=button';
    $url .= '&record=' .$invoice_id .'&method=' .$method ;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl); 
}
 
array_push($job_strings, 'custom_autoSetCookie');

function custom_autoSetCookie(){
    $tmpfsuitename = dirname(__FILE__).'/cookiesrealestate.txt';

    // URL to fetch cookies 
    $ch = curl_init();
    $url = "https://www.realestate.com.au/property/38-ewing-st-brunswick-vic-3056"; 
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: www.realestate.com.au';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Accept-Language: en,en-US;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    
    preg_match_all('/<script src="(.*?)"/', $result,$output_array);
    for ($i=0; $i < count($output_array[1]) ; $i++) {
        $url = "https://www.realestate.com.au".$output_array[1][$i]; 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfsuitename);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: www.realestate.com.au';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Sec-Fetch-Site: none';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Accept-Language: en,en-US;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
    
        if($i == 2){
            //preg_match('/url=(.*?)&token=(.*)"/', $output_array[1][$i], $output);
            $param = array (
                't' => '5f8abb4c-4a29-8652-c91f-6dc78c8ca05f',
                'd' => 
                array (
                  'a2b966c6015331aef2c546e18fcbb837d1d1d5c6310b2599b3e258221ba628ab4' => '9296dbc88b94a17ec56628efa6ec1087',
                  'a7709ad4921d9d2fddd184fb203d972b67c091da33a60f5148d19257ef55fe80e' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',
                  'a4d06a5a037b2609a375e7da3ef18b4328a7f7f2efc3687dbd6f85f9e46c84d0b' => false,
                  'ae66350c25e6310a46b1211d07758c383d4ea36e2c9f590a66733bd78ee10554e' => false,
                  'a79ed4cf26aa77aa2d14f423ffbc3f6d78bf9d122a35ebe573c6b6780cbdbc26c' => false,
                  'ab31231a8571d50420af08ce68fcf3cafb567af56168f6174866f33bfaaa4971c' => true,
                  'af9f8b0e61e9010426a5fa622f86a96b7d732a7400413cda1d2a31ef8cf8c0e5d' => 
                  array (
                    0 => 1792,
                    1 => 1017,
                  ),
                  'a3b776a38b40e6369ccd2dbf50b34e9f57d6a8aa3f0b168c5f2b13acf7a6b167c' => 
                  array (
                    0 => 'Chrome PDF Plugin::Portable Document Format::application/x-google-chrome-pdf~pdf',
                    1 => 'Chrome PDF Viewer::::application/pdf~pdf',
                    2 => 'Native Client::::application/x-nacl~,application/x-pnacl~',
                  ),
                  'abcbbdd4450d158e48b9c6e42e408558b3881d61460f5e3dfde4f537b9aaaaa1e' => 
                  array (
                  ),
                  'a06fafc0eb414c403a30c19284134fbb3622dd2d04a99fd46254cc2b1ce5fc403' => 'TypeError: [] is not a function
                  at _0x1ef3c0 (https://www.realestate.com.au'.$output_array[1][$i].':1:15853)
                  at _0x3bbcae (https://www.realestate.com.au'.$output_array[1][$i].':1:20922)
                  at https://www.realestate.com.au'.$output_array[1][$i].':1:22922
                  at https://www.realestate.com.au'.$output_array[1][$i-1].':1:1802
                  at https://www.realestate.com.au'.$output_array[1][$i-1].':1:14149',
                  'acce6ae932bda4327dffaf46564500a1217c9b68a5b24b5bd3f5704d04321e9eb' => false,
                  'aaf5d594fbc8f5058fd20598c7e6ab3f2e656b0f4006e5b100fcf74ffdf54cb01' => false,
                  'a3f61b6cbfe8119712454f2d834e373c4d9796f8ba260aa64b58d5575d7353e43' => false,
                  'adaaea67c8907130d8e777454ae1935d46ee40c689f0642b805563f880b4a72a3' => false,
                  'a416dc10c4782df33b1a8df4cdfb084f96dfb510bc65bd1ce162e3ba2c994f7dc' => false,
                ),
            );
            $data = json_encode( $param);
            $url = "https://www.realestate.com.au"; 
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);  
            curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfsuitename);  
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($ch, CURLOPT_COOKIE, "bb_lpj=cd155d48-a61f-6c1a-3a49-054486316382");
            $headers = array();
            $headers[] = 'Authority: www.realestate.com.au';
            $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';
            $headers[] = 'Content-Type: application/json; charset=UTF-8';
            $headers[] = 'Accept: */*';
            $headers[] = 'Origin: https://www.realestate.com.au';
            $headers[] = 'Sec-Fetch-Site: same-origin';
            $headers[] = 'Sec-Fetch-Mode: cors';
            $headers[] = 'Sec-Fetch-Dest: empty';
            $headers[] = 'Accept-Language: en,vi;q=0.9';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
    
            curl_setopt($ch, CURLOPT_URL, 'https://www.realestate.com.au/property/38-ewing-st-brunswick-vic-3056');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfsuitename);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfsuitename);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            $headers = array();
            $headers[] = 'Authority: www.realestate.com.au';
            $headers[] = 'Cache-Control: max-age=0';
            $headers[] = 'Upgrade-Insecure-Requests: 1';
            $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36';
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
            $headers[] = 'Sec-Fetch-Site: same-origin';
            $headers[] = 'Sec-Fetch-Mode: navigate';
            $headers[] = 'Sec-Fetch-Dest: document';
            $headers[] = 'Referer: https://www.realestate.com.au/property/38-ewing-st-brunswick-vic-3056';
            $headers[] = 'Accept-Language: en,vi;q=0.9';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}
 
array_push($job_strings, 'custom_autogeosubmission');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_geosubmission($assignment){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "accounts@pure-electric.com.au";
    $mail->FromName = "PureElectric Accounts";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Geo submission notification";
    $mail->Body = "This assignment has been submitted <br> <a href='https://geocreation.com.au/assignments/".$assignment."/edit/submission'>https://geocreation.com.au/assignments/".$assignment."/edit/submission</a>";

    $mail->AddAddress("info@pure-electric.com.au");
    $mail->AddCC("binh.nguyen@pure-electric.com.au");
    $mail->AddCC("paul.szuster@pure-electric.com.au");
    //$mail->AddCC("lee.andrewartha@pure-electric.com.au");
    $mail->AddCC("matthew.wright@pure-electric.com.au");
    

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function custom_autogeosubmission(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $result_data = json_decode($result);
    $accesstoken =  $result_data->AuthenticationResult->AccessToken;
    $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);


    $param = array (
        'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
        'AuthFlow' => 'REFRESH_TOKEN_AUTH',
        'AuthParameters' => 
        array (
        'REFRESH_TOKEN' => $RefreshToken,
        'DEVICE_KEY' => NULL,
        ),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $IdToken =  $result_data->AuthenticationResult->IdToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.geocreation.com.au/api/users/58e18e9b79c887010004f715');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Authorization: token '.$IdToken;
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);
    $result_json  = json_decode($result);
    $clientRef = $result_json->user->result->clients[0]->reference;

    //get list assignments inProgess
    $curl = curl_init();
    $url = 'https://api.geocreation.com.au/api/assignments/search?filters%5Bstatus%5D=inProgress&page=1';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/?filters%5Bstatus%5D=inProgress",
            "Origin: https://geocreation.com.au",
        )
    );
    
    $result = curl_exec($curl);
    curl_close ($curl);
    if($result != false){
        $result = json_decode($result);
        $assignments = array();
        if(isset($result->assignment)){
            foreach($result->assignment as $ret){
                if($ret->certificateCount > 0){
                    array_push( $assignments,$ret->reference);
                }
                
            }
        }

        foreach ($assignments as $assignment){

            // Get JSON of assignment by assignment ID
            $curl = curl_init();
            $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: api.geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-type: application/json; charset=UTF-8",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/$assignment/edit",
                    "Origin: https://geocreation.com.au",
                )
            );

            $result = curl_exec($curl);
            curl_close ($curl);
            
            if($result != false){
                $result = json_decode($result);
                $assignment_byID = $result->assignment->result;
                $certificateBundles = $assignment_byID->certificateBundles;
                $check_null = true;
                $deal_id = array();

                //thien fix. Check certificateBundles
                foreach($assignment_byID->agreements as $agreement){
                    if(is_null($agreement->acceptedAt)){
                        $check_null = false;
                        break;
                    }
                }
                if(count($certificateBundles) == 1){
                    if($check_null){
                        if(count($assignment_byID->agreements) == 1){
                            array_push($deal_id,'12351');
                        }
                        // else{
                        //     array_push($deal_id,'12357');
                        // }
                    }
                }
                // else{
                //     if($check_null){
                //         array_push($deal_id,'12351','12354');
                //     }
                // }

                if(count($deal_id)>0){
                    foreach($certificateBundles as $res_bundles){
                        $claims = $res_bundles->dealBundle->claims;
                        for($i=0;$i<count($claims);$i++){
                            if(in_array($claims[$i]->dealId,$deal_id)){
                                $certificateBundles_id = $res_bundles->_id;
                                $dealID = $claims[$i]->dealId;
    
                                // Set active payment before submitted
                                $curl = curl_init();
                                $url = "https://api.geocreation.com.au/api/assignments/$certificateBundles_id/reserve/$dealID";
                                curl_setopt($curl, CURLOPT_URL, $url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($curl, CURLOPT_POST, TRUE);
                                curl_setopt($curl, CURLOPT_HEADER, false);
                                curl_setopt($curl, CURLOPT_COOKIESESSION, true);

                                curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                        "Content-Type: application/json",
                                        "Accept: */*",
                                        "Accept-Language: en-US,en;q=0.5",
                                        "Accept-Encoding:   gzip, deflate, br",
                                        "Connection: keep-alive",
                                        "Content-Length: 0",
                                        "Authorization: token ".$IdToken,
                                        "Referer: https://geocreation.com.au/assignments/$assignment/edit/submission",
                                        "Origin: https://geocreation.com.au",
                                    )
                                );
                                $result = curl_exec($curl);
                                curl_close ($curl);

                                $result = json_decode($result);
                                //get node readyToSubmit after set payment
                                $readyToSubmit  = $result->assignment->result->readyToSubmit;
                                $followUps = $result->assignment->result->followUps;

                                $check_followUps = true;
                                for($ii = 0;$ii < count($followUps); $ii++){
                                    if(is_null($followUps[$ii]->resolvedAt)){
                                        $check_followUps = false;
                                    }
                                }

                                if($readyToSubmit && $check_followUps){
                                    // Call action submitted assignment
                                    $ch = curl_init();

                                    curl_setopt($ch, CURLOPT_URL, "https://api.geocreation.com.au/api/assignments/$assignment/submit");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

                                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");

                                    $headers = array();
                                    $headers[] = "Host: api.geocreation.com.au";
                                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0";
                                    $headers[] = "Accept: */*";
                                    $headers[] = "Accept-Language: en-US";
                                    $headers[] = "Referer: https://geocreation.com.au/assignments/$assignment/edit/submission";
                                    $headers[] = "Authorization: token ".$IdToken;
                                    $headers[] = "Content-Type: application/json";
                                    $headers[] = "Content-Length: 2";
                                    $headers[] = "Origin: https://geocreation.com.au";
                                    $headers[] = "Connection: keep-alive";
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($ch);
                                    
                                    //Call function send mail when assignment submitted
                                    autosendmail_geosubmission($assignment);

                                    curl_close ($ch);
                                }
                            }
                        }
                        
                    }
                }
            }
        }
    }
}
 
array_push($job_strings, 'custom_autogeotoissue');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_geoissue($assignment){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "accounts@pure-electric.com.au";
    $mail->FromName = "PureElectric Accounts";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Geo issued notification";
    $mail->Body = "This assignment has been issued <br> <a href='https://geocreation.com.au/assignments/".$assignment."/edit/agreements'>https://geocreation.com.au/assignments/".$assignment."/edit/agreements</a>";

    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");
    $mail->AddCC("binh.nguyen@pure-electric.com.au");
    $mail->AddCC("paul.szuster@pure-electric.com.au");
    //$mail->AddCC("lee.andrewartha@pure-electric.com.au");
    $mail->AddCC("matthew.wright@pure-electric.com.au");
    

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

function pushGeoToIssue($accesstoken,$assignment,$agreement_id){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.geocreation.com.au/api/agreements/$agreement_id/transition");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"status":"issued"}');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = "Origin: https://geocreation.com.au";
    $headers[] = "Accept-Language: en";
    $headers[] = "Authorization: token ".$accesstoken;
    $headers[] = "Content-Type: application/json";
    $headers[] = "Accept: */*";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Content-Length: 19";
    $headers[] = "Referer: https://geocreation.com.au/assignments/$assignment/edit/agreements";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $result = json_decode($result);
    $status_after_issued = $result->agreement->result->status;
    if($status_after_issued == "issued"){
        autosendmail_geoissue($assignment);
    }
    curl_close ($ch);
}

function custom_autogeotoissue(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    
    $fields['email'] = 'accounts@pure-electric.com.au';
    $fields['password'] = 'pureandtrue2016';

    $url = 'https://geocreation.com.au/login/';
    //set the url, number of POST vars, POST data
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);

    $html = str_get_html($result);
    
    $session_script = $html->find('script#session')[0]->innertext;
    
    $session_object = json_decode($session_script);
    
    $clientRef = $session_object->user->clients[0]->reference;
    
    $accesstoken = $session_object->token->token;

    //get list assignments from hasReadyToIssueAgreements
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://api.geocreation.com.au/api/assignments/search?filters%5BhasReadyToIssueAgreements%5D=true&page=1");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Connection: keep-alive",
            "Authorization: token ".$accesstoken,
            "Referer: https://geocreation.com.au/assignments/?filters%5BhasReadyToIssueAgreements%5D=true",
            "Origin: https://geocreation.com.au",
        )
    );

    $result = curl_exec($curl);

    curl_close ($curl);

    if($result != false){
        $result = json_decode($result);
        $assignments = array();
        if(isset($result->assignment)){
            foreach($result->assignment as $ret){
                if($ret->certificateCount > 0){
                    array_push( $assignments,$ret->reference);
                }
                
            }
        }
        foreach ($assignments as $assignment){
            // Get JSON of assignment by assignment ID
            $curl = curl_init();
            $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: api.geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-type: application/json; charset=UTF-8",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$accesstoken,
                    "Referer: https://geocreation.com.au/assignments/$assignment/edit",
                    "Origin: https://geocreation.com.au",
                )
            );

            $result = curl_exec($curl);
            curl_close ($curl);
            if($result != false){
                $result = json_decode($result);
                $assignment_byID = $result->assignment->result;
                $check_status_sh = false;
                
                $whSectionSTC_check = count(get_object_vars($assignment_byID->whSectionSTC->errorJson));
                $commonSection_check = count(get_object_vars($assignment_byID->commonSection->errorJson));
                $commonOfficeOnlySection_check = count(get_object_vars($assignment_byID->commonOfficeOnlySection->errorJson));
                $whSection_check = count(get_object_vars($assignment_byID->whSection->errorJson));
                $whSectionVEEC_check = count(get_object_vars($assignment_byID->whSectionVEEC->errorJson));
                $document_valid_pass = $assignment_byID->audits[0]->allPass;

                if($whSectionSTC_check == 0 && $commonSection_check == 0 && $commonOfficeOnlySection_check == 0 && $whSection_check == 0 && $whSectionVEEC_check ==0 && $document_valid_pass == true){
                    foreach($assignment_byID->agreements as $agreement){
                        $agreement_id = $agreement->_id;
                        $agreement_status = $agreement->status;
                        $agreement_name = $agreement->templateName;
    
                        if($agreement_name == 'SH Installer'){
                            $check_status_sh = true;
                            if($agreement_status == 'created'){
                                //when geo is SH
                                pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }
                        }else{
                            if($agreement_status == "accepted"){
                                //when geo is SH
                                pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }else if($check_status_sh == false && $agreement_status == 'created'){
                                 //when geo is WH
                                 pushGeoToIssue($accesstoken,$assignment,$agreement_id);
                            }
                        }
                    }
                }
            }
        }
    }
}
 
array_push($job_strings, 'custom_autosendSandenSTCSurvey');

function custom_autosendSandenSTCSurvey(){
    date_default_timezone_set('UTC');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $servername = "database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com";
    $username = "root";
    $password = "binhmatt2018";
    $database_name = "electric_new";
    $conn = new mysqli($servername, $username, $password, $database_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $db  = DBManagerFactory::getInstance();
    $query = "SELECT aos_invoices.name ,aos_invoices.id ,aos_invoices.number,aos_invoices_cstm.picked_up_date_c as picked_up_date_c
    FROM aos_invoices INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
    WHERE  (aos_invoices_cstm.stc_aggregator_serial_c = '' OR  aos_invoices_cstm.stc_aggregator_serial_c IS NULL)
    AND (aos_invoices_cstm.stc_aggregator_serial_2_c = '' OR  aos_invoices_cstm.stc_aggregator_serial_2_c IS NULL)
    AND (aos_invoices_cstm.stc_aggregator_c  = '' OR  aos_invoices_cstm.stc_aggregator_c IS NULL)
    AND (aos_invoices_cstm.picked_up_date_c != '' AND  aos_invoices_cstm.picked_up_date_c IS NOT NULL)
    AND aos_invoices.status = 'STC_Unpaid'
    AND  aos_invoices.deleted = 0";
    $ret = $db->query($query);
    if($ret->num_rows >0 ){
        while($row = $db->fetchByAssoc($ret)){
            if($row['number'] != ''){
                $timestamp_picked_up_date_c = strtotime($row['picked_up_date_c']);
                $time_now = time();
                $condition_send_email = FALSE;
                //+in picked up date day
                if( $timestamp_picked_up_date_c <=  ($time_now) && ($time_now - 24*1*60*60 ) >= $timestamp_picked_up_date_c ) {
                    $condition_send_email = TRUE;
                }
                //+7 day
                if( $timestamp_picked_up_date_c <= ($time_now - 24*7*60*60 ) && ($time_now - 24*6*60*60 ) >= $timestamp_picked_up_date_c ) {
                    $condition_send_email = TRUE;
                }
                //+10 day
                if( $timestamp_picked_up_date_c <= ($time_now - 24*10*60*60 ) && ($time_now - 24*9*60*60 ) >= $timestamp_picked_up_date_c ) {
                    $condition_send_email = TRUE;
                }
                //+13 day
                if( $timestamp_picked_up_date_c <= ($time_now - 24*13*60*60 ) && ($time_now - 24*12*60*60 ) >= $timestamp_picked_up_date_c ) {
                    $condition_send_email = TRUE;
                }
                //>= 14 day
                if( $timestamp_picked_up_date_c <= ($time_now - 24*14*60*60 ) ) {
                    $condition_send_email = TRUE;
                } 

                //check title invoice include 'supply'
                $word = "supply";
                $nameInvoice = $row["name"];
                (strpos($nameInvoice, $word) !== false)?$condition_check_invoice_name = true : $condition_check_invoice_name = false;
                
                //productID = '4b99f564-8dee-43e4-a8fc-5e3baafbbe32' is Sanden Supply Only 
                $condition_check_product_sanden_supply_only = check_product_in_invoice($row['id'],'4b99f564-8dee-43e4-a8fc-5e3baafbbe32');          
                //productID = '4efbea92-c52f-d147-3308-569776823b19' is STCs 
                $condition_check_product_STC = check_product_in_invoice($row['id'],'4efbea92-c52f-d147-3308-569776823b19');
                // condition 1: Invoice has title 'supply' OR Product Sanden Supply Only
                // condition 2:  Invoice has Product STCs
                if(($condition_check_invoice_name ||$condition_check_product_sanden_supply_only) && $condition_check_product_STC){
                    $condition_check = True;
                }else{
                    $condition_check = False;
                }

                if($condition_send_email && $condition_check) {
                    if(check_customer_submited_form($row['number'],$conn)){
                        send_email_template_Sanden_STC_Form($row['id']);
                    };
                }
            }
        }
    }
    
}

function check_customer_submited_form($invoiceNumber,$conn){
    $sql = "SELECT entity_id
    FROM node__field_survey_invoice_number
    WHERE deleted = '0'
    AND field_survey_invoice_number_value ='$invoiceNumber'";
    $result =  $conn->query($sql);
    $array_return = array();
    if($result->num_rows > 0){
        return FALSE;
    }else{
        return TRUE;
    }  
}
function check_product_in_invoice($record_id,$product_id){

    $db = DBManagerFactory::getInstance();
    $sql = "SELECT COUNT(*) FROM aos_products_quotes
    WHERE parent_type = 'AOS_Invoices' AND parent_id = '" . $record_id . "'
    AND product_id = '" .$product_id. "' AND deleted = 0 ";
    $result = $db->getOne($sql);
    if($result > 0){
        return TRUE;
    }else{
        return FALSE;
    } 
}

function send_email_template_Sanden_STC_Form($record_id){
    $macro_nv = array();
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);

    if(!$focus->id) return FALSE;
    $strtotime_picked_up_check = strtotime(str_replace('/','-',explode(" ",$focus->picked_up_date_c)[0]))+14*60*60*24;
    if($strtotime_picked_up_check <= time()) {
        $emailTemplateID = 'f0779388-37db-cbce-d674-5e8e858c3149';
    }else{
        $emailTemplateID = '787fbdd0-2872-8ee7-4232-5de613f50ced';
    }
  
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        $emailTemplateID
    );
    //get total price stc and veec
    $db = DBManagerFactory::getInstance();
    $array_part_numbers_stc_veec = ['STC Rebate Certificate','VEEC Rebate Certificate'];
    $string_array_part_number = "'" .implode("','",$array_part_numbers_stc_veec) . "'";


    $sql = "SELECT * FROM aos_products_quotes
    WHERE parent_type = 'AOS_Invoices' AND parent_id = '" . $record_id . "'
    AND part_number IN ($string_array_part_number) AND deleted = 0 
    ";
    $total_stc_veec = 0;
    $check_exist_part_number = [];
    $res = $db->query($sql);
    while ($row = $db->fetchByAssoc($res)) {
        $check_exist_part_number[] = $row['part_number'];
        $total_stc_veec +=$row['product_total_price'];
    }
    $params = array(
        'currency_symbol' => false
    );
    $total_price = '$'.currency_format_number((0-$total_stc_veec),$params);
    //get email from contact
    $contact_bean = new Contact;
    $contact_bean->retrieve($focus->billing_contact_id);

    $name = $emailTemplate->subject;
    $description_html = $emailTemplate->body_html;
    $description = $emailTemplate->body;
    $invoice_link = '<a target="_blank" href="http://pure-electric.com.au/sanden-stc-form?invoice_id='.$record_id .'&invoice_number='.$focus->number.'" >Invoice# '.$focus->number.'</a>';
    $time_picked_up = date('D d/m/Y',(strtotime(str_replace('/','-',explode(" ",$focus->picked_up_date_c)[0]))+14*60*60*24));
    //parse value
    $description = str_replace("Invoice-Link",$invoice_link , $description);
    $description_html = str_replace("Invoice-Link",$invoice_link , $description_html);
    $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
    $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
    $description = str_replace("\$Total-STC-VEEC",$total_price , $description);
    $description_html = str_replace("\$Total-STC-VEEC",$total_price , $description_html);
    $name = str_replace("\$contact_first_name",$contact_bean->first_name , $name);
    $name = str_replace("\$aos_invoices_number ",$focus->number , $name);
    $name = str_replace("\$aos_invoices_name",$focus->name , $name);
    if( $emailTemplateID == '787fbdd0-2872-8ee7-4232-5de613f50ced') {
        $name .= " - PAPERWORK DUE ".$time_picked_up;
        $description = str_replace("14 calendar days","14 calendar days - ". $time_picked_up.' -' , $description);
        $description_html = str_replace("14 calendar days","14 calendar days - ". $time_picked_up.' -' , $description_html);
    }
    // parse Note-Special
    if(in_array('STC Rebate Certificate', $check_exist_part_number) 
    && in_array('VEEC Rebate Certificate', $check_exist_part_number) && $focus->install_address_state_c == 'VIC') {
        $Note_Special_String = '<p style="font-family: Times New Roman; font-size: medium;" data-mce-style="font-family: Times New Roman; font-size: medium;">
        For the Email template sent out the customer asking for filling out the Sanden STC form, could you please add this one to the email "For VEEC acceptance,
            the Plumbing Compliance Certificate and the Electrical Certificate should have the words "disconnected and decommissioned existing electric storage hot water system"</p>';  
    }else{
        $Note_Special_String = '';
    }
    $description = str_replace("\$Note_Special",$Note_Special_String  , $description);
    $description_html = str_replace("\$Note_Special",$Note_Special_String  , $description_html);

    $email = new Email();
    $email->id = create_guid();
    $email->new_with_id = true;
    $email->name = $name;
    $email->type = "draft";
    $email->status = "draft";
    $email->parent_type = 'Contacts';
    $email->parent_id = $contact_bean->id;
    $email->parent_name = $contact_bean->name;
    $email->mailbox_id = 'b4fc56e6-6985-f126-af5f-5aa8c594e7fd';
    $email->description_html = $description_html;
    $email->description = $description;
    $email->save(false);
    $email_id = $email->id;

    $attachmentBeans = $emailTemplate->getAttachments();

    if($attachmentBeans) {
        foreach($attachmentBeans as $attachmentBean) {

            $noteTemplate = clone $attachmentBean;
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; 
            $noteTemplate->parent_id = $email->id;
            $noteTemplate->parent_type = 'Emails';
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

            $noteTemplate->save();
            $email->attachNote($noteTemplate);
        }
    }
    //start - code render sms_template  
    global $current_user;
    $smsTemplate = BeanFactory::getBean(
        'pe_smstemplate',
        'b51414de-3298-ed66-bbba-5e49c7fd52de' 
    );
    $body =  $smsTemplate->body_c;
    $body = str_replace("\$first_name", $contact_bean->first_name, $body);
    $smsTemplate->body_c = $body;
    $email->emails_pe_smstemplate_idb  =   $smsTemplate->id;
    $email->emails_pe_smstemplate_name =  $smsTemplate->name; 
    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_bean->phone_mobile));
    $phone_number = preg_replace("/^61/", "+61", $phone_number);
    $email->number_client = $phone_number;
    $email->sms_message =trim(strip_tags(html_entity_decode($body.' '.$current_user->sms_signature_c,ENT_QUOTES)));   
    //end - code render sms_template

    $email->from_addr = "accounts@pure-electric.com.au";
    $email->from_name = "PureElectric Accounts";
    $emailToSend = clone $email;
    $emailToSend->to_addrs_arr = array(array("email"=>"info@pure-electric.com.au"));
    $email_edit_link = "<a href='https://suitecrm.pure-electric.com.au/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_action=DetailView&record=".$email_id."&email_template_id=".$emailTemplateID."&sms_template_id=".$smsTemplate->id."'>Edit Email Link</a><br/>";
    $emailToSend->description_html = $email_edit_link.$emailToSend->description_html;
    if ( $emailToSend->send()) {
        $email->to_addrs_names = $contact_bean->name . " <" . $contact_bean->email1 . ">";
        $email->save();
    }
}


array_push($job_strings, 'custom_autosendemailschedule');

function custom_autosendemailschedule(){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id FROM emails WHERE `status` = 'email_schedule' AND deleted = 0";
    $result_email = $db->query($sql);
    if($result_email->num_rows > 0){
        while ($email_row = $db->fetchByAssoc($result_email))
        {
            $email_info = new Email();
            $email_info = $email_info->retrieve($email_row['id']);
            $date = new DateTime();
            if($email_info->schedule_timestamp_c < $date->getTimestamp()){
                send_email_schedule($email_info);
            }
        }
    }
}
function send_email_schedule($emailBean){  
    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = trim(str_replace(',','',$emailBean->from_addr_name));

    if($mail->From == 'paul.szuster@pure-electric.com.au'){
        $mail->FromName = "Paul Szuster";
    }else if($mail->From == 'matthew.wright@pure-electric.com.au'){
        $mail->FromName = "Matthew Wright";
    }else{
        $mail->FromName ="";
    }

    $mail->Subject = $emailBean->name;    
    $mail->Body =  $emailBean->description_html;  
    
    $note = new Note();
    $where = "notes.parent_id = '".$emailBean->id."'";
    $attachments = $note->get_full_list("", $where, true);
    $all_attachments = array();
    $all_attachments = array_merge($all_attachments, $attachments);
    foreach($all_attachments as $attachment) {
        $file_name = $attachment->filename;
        global $sugar_config;
        $location = $sugar_config['upload_dir'].$attachment->id;
        $mime_type = $attachment->file_mime_type;
        // Add attachment to email
        $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
    }

    $mail->IsHTML(true);
    
    $to = explode(", ",$emailBean->to_addrs_names);
    if(count($to)>1){
        foreach($to as $res){
            $mail->AddAddress(trim($res));
        }
    }else{
        $mail->AddAddress(trim($emailBean->to_addrs_names));
    }

    $cc = explode(", ",$emailBean->cc_addrs_names);
    if(count($cc)>1){
        foreach($cc as $res){
            $mail->AddCC(trim($res));
        }    
    }else{
        $mail->AddCC(trim($emailBean->cc_addrs_names));
    }
    
    $bcc = explode(", ",$emailBean->bcc_addrs_names);
    if(count($cc)>1){
        foreach($bcc as $res){
            $mail->AddBCC(trim($res));
        }
    }else{
        $mail->AddBCC(trim($emailBean->bcc_addrs_names));
    }
    
    $mail->prepForOutbound();
    if($mail->Send()){
        $emailBean->status = 'sent';
        $emailBean->save();
    }
}


 
array_push($job_strings, 'custom_autosendgeoemail');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function custom_autosendgeoemail(){
    date_default_timezone_set('UTC');
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $db  = DBManagerFactory::getInstance();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $result_data = json_decode($result);
    $accesstoken =  $result_data->AuthenticationResult->AccessToken;
    $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);


    $param = array (
        'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
        'AuthFlow' => 'REFRESH_TOKEN_AUTH',
        'AuthParameters' => 
        array (
        'REFRESH_TOKEN' => $RefreshToken,
        'DEVICE_KEY' => NULL,
        ),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $IdToken =  $result_data->AuthenticationResult->IdToken;

    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.greenenergytrading.com.au/api/assignments/search?filters%5BhasIssuedAgreements%5D=true&page=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: api.greenenergytrading.com.au';
    $headers[] = 'Accept: application/json, text/plain, */*';
    $headers[] = 'Authorization: token '.$IdToken;
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    if($result != false){
        $result = json_decode($result);
        $issue_id_arr = array();
        $email_type = array();

        //thienpb fix logic for sh
        if(isset($result->assignment)){
            foreach($result->assignment as $ret){
                //filter issued has email assign to accounts@pure-electric.com.au
                if($ret->commonSection->email != 'accounts@pure-electric.com.au') {
                    array_push( $issue_id_arr,$ret->reference);
                }
            }
            if(count($issue_id_arr) >0){
                foreach ($issue_id_arr as $issue_id){
                    if(strpos($issue_id,'SH') !== false){
                        
                        // Get JSON of assignment by assignment ID
                        $curl = curl_init();
                        $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$issue_id;
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

                        curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
                        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                               
                                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                "Content-type: application/json; charset=UTF-8",
                                "Accept: */*",
                                "Accept-Language: en-US,en;q=0.5",
                                "Accept-Encoding:   gzip, deflate, br",
                                "Connection: keep-alive",
                                "Authorization: token ".$IdToken,
                                "Referer: https://geocreation.com.au/assignments/$issue_id/edit",
                                "Origin: https://geocreation.com.au",
                            )
                        );
                        $result = curl_exec($curl);
                        curl_close ($curl);

                        $result = json_decode($result);
                        $assignment_byID = $result->assignment->result;
                        
                        if(count($assignment_byID->agreements) > 1){
                            if($assignment_byID->agreements[0]->templateName == "System Owner" ){
                                if($assignment_byID->agreements[1]->status == 'accepted'){
                                    //mail cho owner
                                    $email_type[$issue_id] = 'owner';
                                    continue;
                                }else{
                                    //mail cho installer
                                    $email_type[$issue_id] = 'installer';
                                    continue;
                                }
                            }else{
                                if($assignment_byID->agreements[0]->status == 'accepted'){
                                    //mail cho owner
                                    $email_type[$issue_id] = 'owner';
                                    continue;
                                }else{
                                    $email_type[$issue_id] = 'installer';
                                    //mail cho installer
                                    continue;
                                }
                            }
                        }else{
                            $email_type[$issue_id] = 'owner';
                            // mail cho owner
                            continue;
                        }
                    }else{

                          // Get JSON of assignment by assignment ID
                          $curl = curl_init();
                          $url = 'https://api.greenenergytrading.com.au/api/assignments/'.$issue_id;
                          curl_setopt($curl, CURLOPT_URL, $url);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
  
                          curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
                          curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                          curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                
                                  "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                  "Content-type: application/json; charset=UTF-8",
                                  "Accept: */*",
                                  "Accept-Language: en-US,en;q=0.5",
                                  "Accept-Encoding:   gzip, deflate, br",
                                  "Connection: keep-alive",
                                  "Authorization: token ".$IdToken,
                                  "Referer: https://geocreation.com.au/assignments/$issue_id/edit",
                                  "Origin: https://geocreation.com.au",
                              )
                          );
                          $result = curl_exec($curl);
                          curl_close ($curl);
  
                          $result = json_decode($result);
                          $assignment_byID = $result->assignment->result;
                          // only send to owner when wh install is accepted and owner is issued
                          $check_condition_installer = true;
                          $check_condition_owner = false;
                          foreach ($assignment_byID->agreements as $key => $agreement) {
                                if($agreement->templateName  == "System Owner" && $agreement->status == 'issued'){
                                    $check_condition_owner = true;
                                }

                                if($agreement->templateName  == "WH Installer" && $agreement->status != 'accepted'){
                                    $check_condition_installer = false;
                                }
                          }

                          if( $check_condition_installer && $check_condition_owner){
                            $email_type[$issue_id] = 'owner';
                          }else{
                            $email_type[$issue_id] = 'installer';
                          }
                       
                    // mail cho owner
                    }
                }

                //code send email

                //code send email
                $query = "SELECT aos_invoices.id,aos_invoices_cstm.stc_aggregator_serial_c,aos_invoices_cstm.geo_email_sent_date_c,aos_invoices_cstm.send_geo_email_status_c FROM aos_invoices INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                                                WHERE aos_invoices_cstm.stc_aggregator_serial_c IN ('". implode("','",$issue_id_arr) . "')  
                                                AND  aos_invoices.deleted = 0";
                $db  = DBManagerFactory::getInstance();

                $ret = $db->query($query);
                if($ret->num_rows >0 ){
                    while($row = $db->fetchByAssoc($ret)){
                        if($email_type[$row['stc_aggregator_serial_c']] == 'owner'){
                            if($row['send_geo_email_status_c'] == 'pending'){
                                //send email for owner
                                config_sendGeoEmail($row['id'],'owner');
                            }elseif($row['send_geo_email_status_c'] == 'sent'){
                                if(is_null($row['send_geo_email_status_c']) || $row['send_geo_email_status_c'] == ''){
                                    continue;
                                }
                                if((time() - strtotime($row['geo_email_sent_date_c']))/60/60 >= 24){
                                    config_sendGeoEmail($row['id'],'owner');
                                }
                            }
                            
                        }else{
                            //send email for installer
                            // config_sendGeoEmail($row['id'],'installer');
                            config_sendGeoEmail_type_is_installer($row['id'],'installer');
                        }
                    }
                }
                die();
            }
        }
    }
}

function customReplaceEmailVariables(Email $email, $request)
{

    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        isset($request['emails_email_templates_idb']) ?
            $request['emails_email_templates_idb'] :
            null
    );
    //thienpb fix here
    $emailTemplate->subject = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$productType",$request['productType'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$aos_invoices_name",$request['aos_invoices_name'],$emailTemplate->subject);

    $emailTemplate->body = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body);
    $emailTemplate->body_html = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body_html);

    $email->name = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description = $emailTemplate->body;

   
    
    $email->description_html = str_replace("\$lead_first_name", $request['lead_first_name'] , $email->description_html);
    $email->description_html = str_replace("\$productType", $request['productType'] , $email->description_html);

    $email->description = strip_tags($email->description_html);

    return $email;
}

function config_sendGeoEmail($invoice_id,$email_type){

    $account_id = '';
    $product_type = '';
    $from_address = '';
    $db  = DBManagerFactory::getInstance();


    $invoice = new AOS_Invoices();
    $invoice->retrieve($invoice_id);

    if(!isset($invoice->id) || $invoice->id == "") {
        die();
    }


    $from_address = "PureElectric Accounts - PureElectric &lt;accounts@pure-electric.com.au&gt;";

    $account = new Account();
    if($account_id == "") {
        $account_id = $invoice->billing_account_id;
    }
    $account = $account->retrieve($account_id);

    //thienpb get product name and compare

    $sql = "SELECT aos_products_quotes.name FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
    $result = $db->query($sql);

    $isSTC = false;
    $isVEEC = false;
    $geo_name = '';
    while($row = $db->fetchByAssoc($result)){
        if(strpos($row['name'],'STC') !== false){
            $isSTC = true;
        }
        if(strpos($row['name'],'VEEC') !== false){
            $isVEEC = true;
        }
    }

    if($isSTC == true && $isVEEC == true){
        $geo_name = "STCs/VEECs";
    }else if($isSTC){
        $geo_name = "STCs";
    }else if($isVEEC){
        $geo_name = "VEECs";
    }else{
        $geo_name ='';
    }
    //end
    $sea = new SugarEmailAddress; 
    // Grab the primary address for the given record represented by the $bean object
    $primary = $sea->getPrimaryAddress($account);

    //thien fix
    $query_groupname = "SELECT aos_line_item_groups.name FROM aos_line_item_groups WHERE parent_id = '".$invoice_id."' AND deleted = 0 LIMIT 1";
    $ret_groupname = $db->query($query_groupname);
    if($ret_groupname->num_rows >0){
        $row_groupname = $db->fetchByAssoc($ret_groupname);
        $productType = strtolower($row_groupname['name']);
        
        if(strpos($productType,'sanden') !== false){
            $product_type = 'Sanden';
        }else if(strpos($productType,'daikin') !==false){
            $product_type = 'Daikin';
        }else{
            $product_type = '';
        }
    }
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
        "emails_email_templates_name" => "CLIENT GEO STCs/VEECs Contractor Email Follow Up",
        "emails_email_templates_idb" => "acd0d03e-e494-d298-79ce-5a057236fb84",
        "parent_type" => "Accounts",
        "parent_name" => $account->name,
        "parent_id" => $account->id,
        "from_addr" => $from_address,
        "to_addrs_names" => $account->name . "  <".$primary.">",//"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au, paul.szuster@pure-electric.com.au, matthew.wright@pure-electric.com.au",
        "is_only_plain_text" => false,
        "aos_invoices_name" => $invoice->name,
        "lead_first_name"=> current(explode(' ',$account->name)),
        "geo_name" => $geo_name,
        "productType" => $product_type,
        
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    
    $emailBean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";// Account email;

    // parse and replace bean variables
    $emailBean = customReplaceEmailVariables($emailBean, $temp_request);
    // Signature
    $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
    $paul_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
    
    if($invoice->assigned_user_id != ''){
        $current_user = new User();
        $invoice->retrieve($invoice->assigned_user_id);
        
    }else{
        $current_user->sms_signature_c = '';
    }

    $smsTemplateID = '5fcde64f-63ac-dc94-21fb-5e5ef5cf4c70';
    $smsTemplate = BeanFactory::getBean(
        'pe_smstemplate',
        $smsTemplateID
    );
    
    $contact = new Contact();
    $contact_id = $invoice->billing_contact_id; 
    $contact = $contact->retrieve($contact_id);
    $sms_body =  $smsTemplate->body_c;
    $sms_body = str_replace("\$first_name", $contact->first_name, $sms_body);
    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
    $phone_number = preg_replace("/^61/", "+61", $phone_number);
    $emailBean->number_client = $phone_number;
    $emailBean->sms_message = strip_tags(trim(html_entity_decode($sms_body.$current_user->sms_signature_c,ENT_QUOTES)));

    $emailBean->save();
    $description_html_save = $emailBean->description_html;
    $email_edit_link = "<a href='https://suitecrm.pure-electric.com.au/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_action=DetailView&record=".$emailBean->id.'&email_template_id=acd0d03e-e494-d298-79ce-5a057236fb84'.'&sms_template_id='.$smsTemplateID."'>Edit Email Link</a><br/>";
    $GEO_link = "<a href='https://geocreation.com.au/assignments/".$invoice->stc_aggregator_serial_c."/edit'>GEO Link</a><br/>";
    $emailBean->description_html = $email_edit_link.$GEO_link.$emailBean->description_html;
    //VUT - Subject email GEO "PureElectric Geo Admin STCs/VEECs Client Approval - contact_fullname + city + state + geo number"
        if ($isSTC == true && $isVEEC == false) {
            $contact = new Contact();
            $contact_id = $invoice->billing_contact_id; 
            $contact = $contact->retrieve($contact_id);
            $emailBean->name = $emailBean->name.' - '.$contact->first_name.' '.$contact->last_name.' '.$contact->primary_address_city.' '.$contact->primary_address_state.' '.$invoice->stc_aggregator_serial_c; 
        }    
    //VUT-end
    $emailToSend = clone $emailBean;
    $emailToSend->mailbox_id = "e139bac0-4242-ae27-3a90-5bcd22e4e968";
    $emailToSend->to_addrs_arr = array(array("email"=>"info@pure-electric.com.au"));

    if ( $emailToSend->send()) {
        $emailBean->status = 'sent';
        $emailBean->description_html = preg_replace("/<a href=[^>]+Edit Email Link</a><br/>", "", $emailBean->description_html);
         // $emailBean->description_html = preg_replace("/&lt;div dir="ltr">PureElectric Accounts class="CToWUd"></a></div></div></div>/", "", $emailBean->description_html);
        $emailBean->to_addrs = $account->name . "  <".$primary.">";
        $emailBean->description_html =$description_html_save;
        $emailBean->save();

        if($email_type == 'owner'){
            $invoice->send_geo_email_status_c = 'sent';
            $dateAUS = date('Y-m-d H:i:s', time());
            $invoice->geo_email_sent_date_c = $dateAUS;
            $invoice->save();
        }
    }
}

function config_sendGeoEmail_type_is_installer($invoice_id,$email_type){
    $account_id = '';
    $product_type = '';
    $from_address = '';
    $db  = DBManagerFactory::getInstance();


    $invoice = new AOS_Invoices();
    $invoice->retrieve($invoice_id);

    if(!isset($invoice->id) || $invoice->id == "") {
        return;
    }


    $from_address = "PureElectric Accounts - PureElectric &lt;accounts@pure-electric.com.au&gt;";

    $account = new Account();
    if($account_id == "") {
        $account_id = $invoice->billing_account_id;
    }
    $account = $account->retrieve($account_id);

    $installer = new Account();
    $installer_id = $invoice->account_id1_c;
    if($installer_id == ''){
        $installer_id = $invoice->account_id_c;
    }
    $installer = $installer->retrieve($installer_id);
    
    //thienpb get product name and compare

    $sql = "SELECT aos_products_quotes.name FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoice->id."' AND deleted = 0";
    $result = $db->query($sql);

    $isSTC = false;
    $isVEEC = false;
    $geo_name = '';
    while($row = $db->fetchByAssoc($result)){
        if(strpos($row['name'],'STC') !== false){
            $isSTC = true;
        }
        if(strpos($row['name'],'VEEC') !== false){
            $isVEEC = true;
        }
    }

    if($isSTC == true && $isVEEC == true){
        $geo_name = "STCs/VEECs";
    }else if($isSTC){
        $geo_name = "STCs";
    }else if($isVEEC){
        $geo_name = "VEECs";
    }else{
        $geo_name ='';
    }
    //end
    $sea = new SugarEmailAddress; 
    // Grab the primary address for the given record represented by the $bean object
    $primary = $sea->getPrimaryAddress($installer);

    //thien fix
    $query_groupname = "SELECT aos_line_item_groups.name FROM aos_line_item_groups WHERE parent_id = '".$invoice_id."' AND deleted = 0 LIMIT 1";
    $ret_groupname = $db->query($query_groupname);
    if($ret_groupname->num_rows >0){
        $row_groupname = $db->fetchByAssoc($ret_groupname);
        $productType = strtolower($row_groupname['name']);
        
        if(strpos($productType,'sanden') !== false){
            $product_type = 'Sanden';
        }else if(strpos($productType,'daikin') !==false){
            $product_type = 'Daikin';
        }else{
            $product_type = '';
        }
    }
    if($invoice->installation_date_c != '') {
        $dateInfos = explode(" ",$invoice->installation_date_c);
        $dateInfos = explode("/",$dateInfos[0]);
        $inv_install_date_str = "$dateInfos[1]/$dateInfos[2]";
    }
    $request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => ($invoice->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e") ? "8dab4c79-32d8-0a26-f471-59f1c4e037cf" : "58cceed9-3dd3-d0b5-43b2-59f1c80e3869",
        "emails_email_templates_name" => "INSTALLER GEO STC/VEEC EMAIL FOLLOW UP",
        "emails_email_templates_idb" => "6b4a9555-3fad-266b-095f-5f69a004a7a9",
        "parent_type" => "Accounts",
        "parent_name" => $installer->name,
        "parent_id" => $installer->id,
        "from_addr" => $from_address,
        "to_addrs_names" => $installer->name . "  <".$primary.">",//"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au, paul.szuster@pure-electric.com.au, matthew.wright@pure-electric.com.au",
        "is_only_plain_text" => false,
        "aos_invoices_name" => $invoice->name,
        "client_name"=> current(explode(' ',$account->name)),
        'installer_first_name' => current(explode(' ',$installer->name)),
        "geo_name" => $geo_name,
        "intallation_address" => $invoice->install_address_c .' '.$invoice->install_address_city_c. ' '.$invoice->install_address_state_c .' '.$invoice->install_address_postalcode_c, 
        "productType" => $product_type,
        "install_date" => ($inv_install_date_str)?$inv_install_date_str: ''
    );
        /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        '965f4158-7b03-c91f-6f68-5f69b005cc36'
    );
    $emailTemplate->subject = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$productType",$request['productType'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$aos_invoices_name",$request['aos_invoices_name'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$client_name",$request['lead_first_name'],$emailTemplate->subject);
    $emailTemplate->subject = str_replace("\$install_date",$request['install_date'],$emailTemplate->subject);

    $emailTemplate->body = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body);
    $emailTemplate->body_html = str_replace("STCs/VEECs",$request['geo_name'],$emailTemplate->body_html);
    $emailTemplate->body = str_replace("\$installer_first_name", $request['installer_first_name'] , $emailTemplate->body);
    $emailTemplate->body_html = str_replace("\$installer_first_name", $request['installer_first_name'] , $emailTemplate->body_html);
    $emailTemplate->body = str_replace("\$intallation_address", $request['intallation_address'] , $emailTemplate->body);
    $emailTemplate->body_html = str_replace("\$intallation_address", $request['intallation_address'] , $emailTemplate->body_html);
    $emailTemplate->body = str_replace("\$client_name", $request['client_name'] , $emailTemplate->body);
    $emailTemplate->body_html = str_replace("\$client_name", $request['client_name'] , $emailTemplate->body_html);
    // parse and replace bean variables

    $emailBean = new Email();
    $emailBean->name = $emailTemplate->subject;
    $emailBean->description_html = $emailTemplate->body_html;
    $emailBean->description = $emailTemplate->body;


    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    
    $emailBean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";// Account email;



    // Signature
    $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
    $paul_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
    
    if($invoice->assigned_user_id != ''){
        $current_user = new User();
        $invoice->retrieve($invoice->assigned_user_id);
        
    }else{
        $current_user->sms_signature_c = '';
    }

    $smsTemplateID = '5fcde64f-63ac-dc94-21fb-5e5ef5cf4c70';
    $smsTemplate = BeanFactory::getBean(
        'pe_smstemplate',
        $smsTemplateID
    );
    
   
    $sms_body =  $smsTemplate->body_c;
    $sms_body = str_replace("\$first_name", current(explode(' ',$installer->name)), $sms_body);
    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $installer->mobile_phone_c));
    $phone_number = preg_replace("/^61/", "+61", $phone_number);
    $emailBean->number_client = $phone_number;
    $emailBean->sms_message = strip_tags(trim(html_entity_decode($sms_body.$current_user->sms_signature_c,ENT_QUOTES)));

    $emailBean->save();
    $description_html_save = $emailBean->description_html;
    $email_edit_link = "<a href='https://suitecrm.pure-electric.com.au/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=AOS_Invoices&return_action=DetailView&return_action=DetailView&record=".$emailBean->id.'&email_template_id=acd0d03e-e494-d298-79ce-5a057236fb84'.'&sms_template_id='.$smsTemplateID."'>Edit Email Link</a><br/>";
    $GEO_link = "<a href='https://geocreation.com.au/assignments/".$invoice->stc_aggregator_serial_c."/edit'>GEO Link</a><br/>";
    $emailBean->description_html = $email_edit_link.$GEO_link.$emailBean->description_html;
    $emailToSend = clone $emailBean;
    $emailToSend->mailbox_id = "e139bac0-4242-ae27-3a90-5bcd22e4e968";
    $emailToSend->to_addrs_arr = array(array("email"=>"info@pure-electric.com.au"));

    if ( $emailToSend->send()) {
        $emailBean->status = 'sent';
        $emailBean->to_addrs = $installer->name . "  <".$primary.">";
        $emailBean->description_html =$description_html_save;
        $emailBean->save();
    }
}
 
array_push($job_strings, 'custom_autosendmail');
require_once('modules/Emails/Email.php');
require_once('include/SugarPHPMailer.php');
function custom_autosendmail(){
    
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * from leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c WHERE email_send_status_c = 'pending' AND email_send_id_c !='' AND deleted != 1";
    $ret = $db->query($sql);
    while($row = $db->fetchByAssoc($ret)){
        $record_id = $row['id'];
        $primary_address_street = $row['primary_address_street'];
        $primary_address_city = $row['primary_address_city'];
        $primary_address_state = $row['primary_address_state'];
        $primary_address_postalcode = $row['primary_address_postalcode'];
         //check time
        if($primary_address_street == "" || $primary_address_city == "" || $primary_address_state == "" || $primary_address_postalcode == ""){

            $emailBean = new Email();
            $emailBean-> retrieve($row['email_send_id_c']);
            if($emailBean->id == "") return;

            $lead = new Lead();
            $lead-> retrieve($row['id']); 
            if($lead->id == "") return;

            
            $random_number = rand(0,100);

            if ($random_number <= 80) {
                $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
            } else {
                $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
            }
            
            /*$matthew_inbound_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
            $paul_inbound_id    = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
            */
            $temp_request        = array(
                "module" => "Emails",
                "action" => "send",
                "record" => "",
                "type" => "out",
                "send" => 1,
                "inbound_email_id" => ($random_number < 70) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                "parent_type" => "Leads",
                "parent_name" => $row['first_name'] .' '. $row['last_name'],
                "parent_id" => $row['id'],
                "from_addr" => $from_address,
                "to_addrs_names" => $lead->email1, //$lead->email1, //"binhdigipro@gmail.com",
                "cc_addrs_names" => "info@pure-electric.com.au",
                "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au,",
                "is_only_plain_text" => false
            );

            //$emailBean           = new Email();
            $emailBean           = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                
            // Signature
            /*
            $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
            $paul_id    = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
            $user       = new User();
            $user->retrieve($matthew_id);
            if ($random_number <= 80) { // Matthew 
                $emailSignatureId = "6f14eb50-e31f-b1de-194e-5ad439e971fa"; // Lee signature
            } elseif (80 < $random_number && $random_number <= 100) {
                $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
                //$emailSignatureId = "7ac5a4fd-b086-2bcc-aa40-5a741cf9baca";
            } else {
                $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
            }
            
            $signature = $user->getSignature($emailSignatureId);
            $emailBean->description .= $signature["signature"];
            $emailBean->description_html .= $signature["signature_html"];
            $emailBean->description .= $live_chat_text;
            $emailBean->description_html .= $live_chat_text;
            
            $emailBean->save();
            */
           
            //check time
            
            global $timedate;
            $time_zone = $timedate->getInstance()->userTimezone();
            date_default_timezone_set($time_zone);
            
            $date_created = strtotime($row['date_entered']);
            $timeAgo = time() - $date_created;
            $timeAgo = $timeAgo / 3600;

            if($lead->status == "Assigned"){
                if($timeAgo > 24){
                    $lead->email_send_status_c = 'sent';
                    autosendmail_config_email($lead,$emailBean);
                    $lead->save();
                }else{
                    continue;
                }
            }else{
                continue;
            }
            
        }else{
            continue;
        }
    }
    return;
}

function autosendmail_config_email($lead,$emailBean){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $emailBean->from_addr;
    $mail->FromName = $emailBean->from_name;
    $mail->IsHTML(true);

    //get email template and replace Email Variables
    $emailtemplate = new EmailTemplate();
    $emailtemplate = $emailtemplate->retrieve("58230a56-82cd-03ae-1d60-59eec0f8582d");

    $emailtemplate->parsed_entities = null;
    $macro_nv = array();
    $focusName = $emailBean->parent_type;
    $focus = BeanFactory::getBean($focusName, $lead->id);

    $template_data = $emailtemplate->parse_email_template(
        array(
            "subject" => $emailtemplate->subject,
            "body_html" => $emailtemplate->body_html,
            "body" => $emailtemplate->body
            ),
            'Leads',
            $focus,
            $temp
        );
    $email_body = str_replace('$lead_first_name',$lead->first_name,$template_data["body_html"]);
    $email_subject = str_replace('$lead_first_name',$lead->first_name,$template_data["subject"]);
    $email_subject = str_replace('$lead_primary_address_city',$lead->primary_address_city, $email_subject);
    
    //get and add attachment from template

    //require_once('module/Notes/Note.php');
    $note = new Note();
    $where = "notes.parent_id = '58230a56-82cd-03ae-1d60-59eec0f8582d'";
    $attachments = $note->get_full_list("", $where, true);
    $all_attachments = array();
    $all_attachments = array_merge($all_attachments, $attachments);
    foreach($all_attachments as $attachment) {
        $file_name = $attachment->filename;
        global $sugar_config;
        $location = $sugar_config['upload_dir'].$attachment->id;
        $mime_type = $attachment->file_mime_type;
        // Add attachment to email
        $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
    }
    
    $mail->Subject = $email_subject;
    $mail->Body = $email_body."\n".$emailBean->description_html;

    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress($emailBean->to_addrs_names);
    $mailcc = explode(',',$emailBean->cc_addrs_names);
    
    if(count($mailcc)>0){
        foreach($mailcc as $res){
            $mail->AddCC(trim($res));
        }
    }
    
    $mailbcc = explode(',',$emailBean->bcc_addrs_names);
    if(count($mailbcc)>0){
        foreach($mailbcc as $res){
            $mail->AddBCC(trim($res));
        }
    }

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();

    if ($sent) {
        $emailBean->status = 'sent';
        $emailBean->save();
    } else {
        if ($emailBean->status !== 'draft') {
            $emailBean->status = 'send_error';
            $emailBean->save();
        } else {
            $emailBean->status = 'send_error';
        }
    }
}


array_push($job_strings, 'custom_autouploadfolderuploadtoaws');

function custom_autouploadfolderuploadtoaws(){
    date_default_timezone_set('Australia/Sydney');

    $AWS_ACCESS_KEY_ID = 'AKIAJG53TQTXLTGRNAVA';
    $AWS_SECRET_ACCESS_KEY = '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';

    global $sugar_config;

    $folder = $sugar_config['upload_dir'];

    $file_array = scandir($folder);
    $file_array = array_diff($file_array, array('.', '..'));
    foreach($file_array as $file){
        $source_file =  $folder.$file;
        $modified = filemtime($source_file);
        if(strtotime('-25 hours') <= $modified){
            file_put_contents('logs_folder_upload.txt','/var/www/suitecrm/upload/'.$file.PHP_EOL , FILE_APPEND | LOCK_EX);

            //echo 'AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp '.$source_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$file;
            shell_exec('AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp /var/www/suitecrm/upload/'.$file.' s3://upload-bk/'.$file);
        }
    }
}





array_push($job_strings, 'custom_checkdesigns');

function custom_checkdesigns()
{
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id_c, user_id_c, time_accepted_job_c FROM leads_cstm WHERE user_id_c != '' AND time_accepted_job_c != '' AND time_completed_job_c = ''";

    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret))
    {
        date_default_timezone_set('Australia/Melbourne');
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $row['time_accepted_job_c'])));
        $timeAgo = time() - strtotime($date);
        $timeAgo = $timeAgo / 3600;
        if ($date < strtotime("2017-12-22 00:00") || $timeAgo < 1.5)
        {
            continue;
        }

        $record_id = $row['id_c'];
        $lead = new Lead();
        $lead = $lead->retrieve($record_id);

        $user = new User();
        $user->retrieve($lead->user_id_c);
    
        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);

        if ($timeAgo > 4)
        {
            $lead->user_id_c = '';
            $lead->time_accepted_job_c = '';
            $lead->save();

            $mail->Subject = 'DESIGNS EMAIL - DESIGNS OUT' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 4 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>';
        }
        else if ($timeAgo > 2)
        {
            $lead->user_id_c = '';
            $lead->time_accepted_job_c = '';
            $lead->save();

            $mail->Subject = 'DESIGNS EMAIL - REPEAT' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 2 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customLeadAcceptJob&record_id=' . $record_id . '">Accept Job</a></div>';
        }
        else
        {
            $mail->Subject = 'DESIGNS EMAIL - OVERDUE' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 1.5 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>';
        }

        $mail->prepForOutbound();
        $mail->AddAddress('binhdigipro@gmail.com');
        $mail->AddAddress('admin@pure-electric.com.au');
        $mail->AddCC('info@pure-electric.com.au');

        $sent = $mail->Send();
        echo $sent;        
    }
}



array_push($job_strings, 'custom_emailFormReportDaily');
function custom_emailFormReportDaily()
{
    date_default_timezone_set('Australia/Melbourne');
    $servername = "database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com";
    $username = "root";
    $password = "binhmatt2018";
    $database_name = "electric_new";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database_name);
        
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $today = date('d/m/Y', time());
    $email_content = "<div><h1 'text-align:center;'>Pure-Electric Quote Form - Daily Report - Date " . $today .'</h1></div>';
    $email_content .= build_table_email_report_form('quote',$conn);
    $email_content .= build_table_email_report_form('pe_sanden_form',$conn);
    $email_content .= build_table_email_report_form('pe_daikin_form',$conn);
    $email_content .= build_table_email_report_form('pe_solar_form',$conn);

    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->Subject = "Pure-Electric Quote Form - Daily Report - Date " . $today;
    $mail->Body = $email_content;
    $mail->prepForOutbound(); 
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");
    $sent = $mail->send();
}


function get_data_from_pure_electric($type,$conn){
    $query = build_sql_get_data($type);
    $result =  $conn->query($query);
    $array_return = array();
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $array_return[$i] = array(
                'nid' => $row['nid'],
                'title' => $row['title'],
                'date_created' => $row['node_field_data_created'],
                'lead_id' => $row['lead_id']
            );
            $i++;
        }
    }      
     
    return $array_return;
}

function build_sql_get_data($type){
    $sql = '';
    $time_condition = time() - 86400;
    switch ($type) {
        case 'pe_sanden_form':
            $sql = "SELECT node_field_data.created AS node_field_data_created, node_field_data.nid AS nid ,node_field_data.title AS title 
            FROM node_field_data
            WHERE (node_field_data.type IN ('$type')) AND ((node_field_data.changed >= $time_condition))
            ORDER BY node_field_data_created DESC";
            break;
        case 'quote':
            $sql = "SELECT node_field_data.created AS node_field_data_created, node_field_data.nid AS nid 
            ,node_field_data.title AS title ,node__field_lead_id.field_lead_id_value as lead_id
            FROM node_field_data
            LEFT JOIN node__field_lead_id ON node__field_lead_id.entity_id =  node_field_data.nid
            WHERE (node_field_data.type IN ('$type')) AND ((node_field_data.changed >= $time_condition))
            ORDER BY node_field_data_created DESC";
            break;
        case 'pe_daikin_form':
            $sql = "SELECT node_field_data.created AS node_field_data_created, node_field_data.nid AS nid ,node_field_data.title AS title 
            FROM node_field_data
            WHERE (node_field_data.type IN ('$type')) AND ((node_field_data.changed >= $time_condition))
            ORDER BY node_field_data_created DESC";
            break;
        case 'pe_solar_form':
            $sql = "SELECT node_field_data.created AS node_field_data_created, node_field_data.nid AS nid ,node_field_data.title AS title 
            FROM node_field_data
            WHERE (node_field_data.type IN ('$type')) AND ((node_field_data.changed >= $time_condition))
            ORDER BY node_field_data_created DESC";
            break;
        default:
            # code...
            break;
    }
    return $sql;
}

function build_table_email_report_form($type,$conn){
    $html_content = '';
    switch ($type) {
        case 'pe_sanden_form':
            $data_node = get_data_from_pure_electric($type,$conn);
            $html_content  .= render_html_content_body_pe_sanden_form($data_node);
            break;
        case 'pe_daikin_form':
            $data_node = get_data_from_pure_electric($type,$conn);
            $html_content  .= render_html_content_body_pe_daikin_form($data_node);
            break;
        case 'quote':
            $data_node = get_data_from_pure_electric($type,$conn);
            $html_content  .= render_html_content_body_pe_quote_form($data_node);
            break;
        case 'pe_solar_form':
            $data_node = get_data_from_pure_electric($type,$conn);
            $html_content  .= render_html_content_body_pe_solar_form($data_node);
        break;
        default:
            # code...
            break;
    }

    return $html_content;
}

function render_html_content_body_pe_sanden_form($data_node){

    $pe_domain = 'https://pure-electric.com.au';
    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $html_content .= "<div><h3 style='text-align:center;'>Sanden Quote Form</h3></div>";
    if(count($data_node)>0){
        $html_content .= '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Date Created</strong></td>
            </tr>';
        foreach($data_node as $res){
            if($res['nid'] == '') {
                $link = '';
            }else{
                $link = $pe_domain .'/node/'.$res['nid'];
            }
             
            $title = $res['title'];
            $date_created =  date("d-m-Y",(int)$res['date_created']);
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">". $title."</a></td>
                <td style='border: 1px solid black;' >".$date_created."</td>
            </tr>";
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No $title</h4>";
    }

    return $html_content;
}

function render_html_content_body_pe_daikin_form($data_node){

    $pe_domain = 'https://pure-electric.com.au';
    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $html_content .= "<div><h3 style='text-align:center;'>Daikin Quote Form</h3></div>";
    if(count($data_node)>0){
        $html_content .= '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Date Created</strong></td>
            </tr>';
        foreach($data_node as $res){
            if($res['nid'] == '') {
                $link = '';
            }else{
                $link = $pe_domain .'/node/'.$res['nid'];
            }
             
            $title = $res['title'];
            $date_created =  date("d-m-Y",(int)$res['date_created']);
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">". $title."</a></td>
                <td style='border: 1px solid black;' >".$date_created."</td>
            </tr>";
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No $title</h4>";
    }

    return $html_content;
}

function render_html_content_body_pe_quote_form($data_node){
    $pe_domain = 'https://pure-electric.com.au';
    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $html_content .= "<div><h3 style='text-align:center;'>Get Quote Free</h3></div>";
    if(count($data_node)>0){
        $html_content .= '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link PE CRM</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Date Created</strong></td>
            </tr>';
        foreach($data_node as $res){
            if($res['nid'] == '') {
                $link = '';
            }else{
                $link = $pe_domain .'/node/'.$res['nid'];
            }
           
            if($res['lead_id'] == '') {
                $link_crm = '';
            }else{
                 $link_crm = $pe_domain_crm .'/index.php?module=Leads&action=EditView&record='.$res['lead_id'];
            }
    
            $title = $res['title'];
            $date_created =  date("d-m-Y",(int)$res['date_created']);
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">". $title."</a></td>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link_crm.">CRM ". $title."</a></td>
                <td style='border: 1px solid black;' >".$date_created."</td>
            </tr>";
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No $title</h4>";
    }
    return $html_content;
}
function render_html_content_body_pe_solar_form($data_node){
    $pe_domain = 'https://pure-electric.com.au';
    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    $html_content .= "<div><h3 style='text-align:center;'>Solar Quote Form</h3></div>";
    if(count($data_node)>0){
        $html_content .= '<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
            <tr>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link PE</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Date Created</strong></td>
            </tr>';
        foreach($data_node as $res){
            if($res['nid'] == '') {
                $link = '';
            }else{
                $link = $pe_domain .'/node/'.$res['nid'];
            }
             
            $title = $res['title'];
            $date_created =  date("d-m-Y",(int)$res['date_created']);
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">". $title."</a></td>
                <td style='border: 1px solid black;' >".$date_created."</td>
            </tr>";
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No $title</h4>";
    }

    return $html_content;
}


array_push($job_strings, 'custom_emailReportGEOIssued');
require_once('custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');

function autosendmail_reportforgeoissued($email_content){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);

    $mail->Subject = "Report Email GEO Issued";
    $mail_body = "List GEO Issued:<br/>";
    
    $mail->Body = $mail_body.$email_content;

    $mail->prepForOutbound(); 
    //$mail->AddAddress("thienpb89@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");

    $sent = $mail->send();
}

function custom_emailReportGEOIssued(){
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $db = DBManagerFactory::getInstance();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $result_data = json_decode($result);
    $accesstoken =  $result_data->AuthenticationResult->AccessToken;
    $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);


    $param = array (
        'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
        'AuthFlow' => 'REFRESH_TOKEN_AUTH',
        'AuthParameters' => 
        array (
        'REFRESH_TOKEN' => $RefreshToken,
        'DEVICE_KEY' => NULL,
        ),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $IdToken =  $result_data->AuthenticationResult->IdToken;

    $curl = curl_init();

    //get list assignments inProgess
    $url = 'https://api.geocreation.com.au/api/assignments/search?filters%5Bstatus%5D=inProgress&page=1';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);

    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: api.geocreation.com.au",
            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
            "Content-type: application/json; charset=UTF-8",
            "Accept: */*",
            "Accept-Language: en-US,en;q=0.5",
            "Accept-Encoding:   gzip, deflate, br",
            "Connection: keep-alive",
            "Authorization: token ".$IdToken,
            "Referer: https://geocreation.com.au/assignments/?filters%5Bstatus%5D=inProgress",
            "Origin: https://geocreation.com.au",
        )
    );

    $result = curl_exec($curl);
    curl_close ($curl);
    if($result != false){
        $result = json_decode($result);
        $assignments = array();
        if(isset($result->assignment)){
            foreach($result->assignment as $ret){
                if($ret->certificateCount > 0){
                    array_push( $assignments,$ret->reference);
                }
                
            }
        }

        $mail_content = '';
        foreach ($assignments as $assignment){
            // Get JSON of assignment by assignment ID
            $curl = curl_init();
            $url = 'https://api.geocreation.com.au/api/assignments/'.$assignment;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: api.geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-type: application/json; charset=UTF-8",
                    "Accept: */*",
                    "Accept-Language: en-US,en;q=0.5",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: token ".$IdToken,
                    "Referer: https://geocreation.com.au/assignments/$assignment/edit",
                    "Origin: https://geocreation.com.au",
                )
            );

            $result = curl_exec($curl);
            curl_close ($curl);

            if($result != false){
                $result = json_decode($result);
                $assignment_byID = $result->assignment->result;
                $address = $assignment_byID->commonSection->activityAddress->displayAddress;
                $activityDate = $assignment_byID->commonSection->activityDate;
                foreach($assignment_byID->agreements as $agreement){
                    if($agreement->status == 'issued'){

                        //Invoice link
                        $sql = "SELECT id_c FROM aos_invoices_cstm WHERE (stc_aggregator_serial_2_c = '$assignment' OR stc_aggregator_serial_c = '$assignment' OR stc_aggregator_c = '$assignment')";
                        $result = $db->query($sql);
                        $invoice_link = '';
                        if($result->num_rows > 0){
                            $row = $db->fetchByAssoc($result);
                            $invoice_link = '<a href="https://loc.suitecrm.com/index.php?module=AOS_Invoices&action=DetailView&record='.$row['id_c'].'">[Invoice Link]</a>';
                        }
                        $mail_content .=  '+ '.$invoice_link.' <a href="https://geocreation.com.au/assignments/'.$assignment.'/edit">['.$assignment_byID->displayName.']</a>. <Strong>Address:</strong> <span style="font-style:italic;">'.$address.'</span>. <strong>Installer Date:</strong> <span style="font-style:italic;">'.date('M jS Y',strtotime($activityDate)).'</span><br />';
                        continue;
                    }
                }
            }
        }
        autosendmail_reportforgeoissued($mail_content);
    }
}


array_push($job_strings, 'custom_getstatus');

function custom_getstatus(){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * FROM pe_warehouse_log WHERE deleted = 0 LIMIT 1";
    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret)) {
        if (isset($row) && $row != null) {
            $whlog =  new pe_warehouse_log();
            $whlog = $whlog->retrieve($row['id']);
            $connoteNumber = $whlog->connote;
            $carrier = $whlog->carrier;
            get_status($connoteNumber,$carrier,$whlog);
        }
    }
}


function get_status($connoteNumber,$carrier,$whlog = ''){
    if($carrier == 'COPE'){
        $url = 'http://tracking.cope.com.au/track.php?consignment='.$connoteNumber;
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Pragma: no-cache";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.75 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate";
        $headers[] = "Accept-Language: een-US,en;q=0.9,vi;q=0.8,fr;q=0.7";
      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
    
        curl_close ($ch);
    
        $html = str_get_html($result);
    
    
    
        // get ABN details
        $return_json = array();
        $status = "";
        $date = "";
        $location = "";
        if( count($html->find('table tbody')) != 0 && ($html->find('table tbody')[0]->next_sibling () != null) ) {
            $status = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->next_sibling ()->next_sibling ()->innertext;
            $date = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->innertext;
            $location = $html->find('table tbody')[0]->first_child ()->next_sibling ()->first_child ()->next_sibling ()->innertext;
        } else {
            $status = $html->find('p b')[0]->innertext;
        }
        if($whlog != ''){

            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }    
    }
    
    if($carrier == 'Australia Post'){
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, "https://digitalapi.auspost.com.au/shipmentsgatewayapi/watchlist/shipments?trackingIds=". $connoteNumber);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
        $headers = array();
        $headers[] = "Pragma: no-cache";
        $headers[] = "Origin: https://auspost.com.au";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: en-US,en;q=0.9,vi;q=0.8,fr;q=0.7";
        $headers[] = "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
        $headers[] = "Accept: application/json, text/plain, */*";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Referer: https://auspost.com.au/mypost/track/";
        $headers[] = "Cookie: check=true; AMCVS_0A2D38B352782F1E0A490D4C%40AdobeOrg=1; s_cc=true; AAMC_auspost_0=REGION%7C3; aam_uuid=64881562324747079812910258178743754564; s_nr=1534493867149; s_sq=%5B%5BB%5D%5D; mbox=PC#b5516678ef6740219696a937fbf4da49.24_11#1597749118|session#aa59b9f51a064e278dd8748e1b800abc#1534520506; AMCV_0A2D38B352782F1E0A490D4C%40AdobeOrg=1406116232%7CMCIDTS%7C17761%7CMCMID%7C65134744044665115972886065900749150628%7CMCAAMLH-1535123447%7C3%7CMCAAMB-1535123447%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1534525847s%7CNONE%7CMCSYNCSOP%7C411-17768%7CMCAID%7CNONE%7CvVersion%7C2.5.0; prevUrl=https%3A%2F%2Fauspost.com.au%2Fmypost%2Ftrack%2F%23%2Fdetails%2F60037989777090; s_ppn=auspost%3Aone%20track%3Amypost%3Atrack%3Ahome";
        $headers[] = "Api-Key: d11f9456-11c3-456d-9f6d-f7449cb9af8e";
        $headers[] = "Cache-Control: no-cache";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $result_reson = json_decode($result,true);
        if(isset($result_reson) && isset($result_reson[0]['shipment']['articles'][0]['trackStatusOfArticle'])){
            $status = $result_reson[0]['shipment']['articles'][0]['trackStatusOfArticle'];
        }
        if($whlog != ''){

            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }
    }

    if($carrier == 'TNT' ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.tnt.com/api/v3/shipment?con='.$connoteNumber.'&searchType=CON&locale=en_GB&channel=OPENTRACK');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: www.tnt.com';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $return_json = json_decode($result,true);
        $status = "";
        if($return_json){
            foreach($return_json['tracker.output']['consignment'] as $res){
                if(strtolower($res['destinationAddress']['country']) == 'australia'){
                    switch ($res['status']['groupCode']){
                        case 'DELRED' :
                            $status = 'Delivered';
                            break;
                        case 'COLING' :
                            $status = 'Collecting';
                            break;
                        case 'COLTED' :
                            $status = 'Collected';
                            break;
                        case 'DELING' :
                            $status = 'Delivering';
                            break;
                        case 'INTRAN' :
                            $status = 'In transit';
                            break;
                    }
                    
                }
            }
        }
        if($whlog != ''){
            
            $db  = DBManagerFactory::getInstance();
            $query = "SELECT id,parent_id FROM pe_stock_items WHERE parent_id = '$whlog->id' AND deleted = 0";
            $ret = $db->query($query);

            if($ret->num_rows >0 ){
                while($row = $db->fetchByAssoc($ret)){
                    // BinhNT need to add relationship
                    $product_quote =  BeanFactory::getBean('pe_stock_items', $row['id']);
                    $product_quote->load_relationship('pe_warehouse_pe_stock_items_1');
                    $wh_destination_old  = $product_quote->pe_warehouse_pe_stock_items_1->get()[0];
                    // get the warehouse id
                    if ($whlog->load_relationship('pe_warehouse_log_pe_warehouse')) {
                        if(($status == 'Proof of Delivery' || $status == 'Delivered') && $whlog->object_name == 'pe_warehouse_log'){
                            $warehouse = $whlog->pe_warehouse_log_pe_warehouse->getBeans();
                            $destination_wh = BeanFactory::getBean('pe_warehouse', $whlog->destination_warehouse_id);
                            
                            if($destination_wh != false && $wh_destination_old != $destination_wh->id){
                                if($warehouse != false){
                                    $product_quote->pe_warehouse_pe_stock_items_1->delete($warehouse);
                                }
                                $product_quote->pe_warehouse_pe_stock_items_1->add($destination_wh);
                            }
                        }
                    }
                }
            }

            $whlog->status_c = $status;
            $whlog->save();
        }

    }
}



array_push($job_strings, 'custom_job');

function custom_job()
{

    $folder = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/assignment/';
    $file_array = scandir($folder);
    if(count($file_array) == 2 ) return true;

    date_default_timezone_set('Africa/Lagos');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');

    require_once( dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/'.'simple_html_dom.php');

    $curl = curl_init();
    $tmpfname = dirname(__FILE__).'/cookiegeo.txt';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AuthFlow":"USER_PASSWORD_AUTH","ClientId":"1r8f4rahaq3ehkastcicb70th4","AuthParameters":{"USERNAME":"accounts@pure-electric.com.au","PASSWORD":"gPureandTrue2019*"},"ClientMetadata":{}}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $result_data = json_decode($result);
    $accesstoken =  $result_data->AuthenticationResult->AccessToken;
    $RefreshToken = $result_data->AuthenticationResult->RefreshToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"AccessToken":'.$accesstoken.'"}');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.GetUser';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);


    $param = array (
        'ClientId' => '1r8f4rahaq3ehkastcicb70th4',
        'AuthFlow' => 'REFRESH_TOKEN_AUTH',
        'AuthParameters' => 
        array (
        'REFRESH_TOKEN' => $RefreshToken,
        'DEVICE_KEY' => NULL,
        ),
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cognito-idp.ap-southeast-2.amazonaws.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Authority: cognito-idp.ap-southeast-2.amazonaws.com';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Origin: https://geocreation.com.au';
    $headers[] = 'X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth';
    $headers[] = 'X-Amz-User-Agent: aws-amplify/0.1.x js';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
    $headers[] = 'Content-Type: application/x-amz-json-1.1';
    $headers[] = 'Accept: */*';
    $headers[] = 'Sec-Fetch-Site: cross-site';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://geocreation.com.au/';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    $IdToken =  $result_data->AuthenticationResult->IdToken;

    $curl = curl_init();


    foreach ($file_array as $file) {
        if (!is_dir($file)) {
            $assignment = "WH-170037883";//file_get_contents($folder.$file);
            //https://geocreation.com.au/assignments/SH-170002368/edit/summary
            $url = "https://geocreation.com.au/assignments/".$assignment."/edit/summary";
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfname);
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);

            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: geocreation.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                    "Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3",
                    "Accept-Encoding:   gzip, deflate, br",
                    "Connection: keep-alive",
                )
            );
            $result = curl_exec($curl);
            $html = str_get_html($result);
            $data_script = $html->find('script#data')[0]->innertext;
            $assignment_object = json_decode($data_script,true);
            $assignment_info = $assignment_object[$assignment]['assignment']['result']['certificateBundles'][0];
            if($assignment_info != null){
                $value = $assignment_info['value'];
                $price = $assignment_info['dealBundle']['claims'][0]['paymentTerms']['price'];
                $quantity = $assignment_info['dealBundle']['claims'][0]['quantity'];
                $rebate_type = "veec";
                if($assignment_info['certificateType'] == "STC"){
                    $rebate_type = "stc";
                }

                $GLOBALS['log']->info("Value: $value; Price: $price; Quantity: $quantity; Rebate: $rebate_type");

                $db = DBManagerFactory::getInstance();
                $sql = "SELECT * FROM aos_invoices_cstm WHERE stc_aggregator_serial_c = '".$assignment."' OR stc_aggregator_serial_2_c = '".$assignment."'";
                $ret = $db->query($sql);

                while ($row = $db->fetchByAssoc($ret)) {
                    if (isset($row) && $row != null) {
                        // the CURL need to have
                        // 1 the rebate type
                        // 2 the price
                        // 3 quantity
                        // 4 the xero invoice
                        // 5 the veec rebate
                        // 6 the stc rebate

                        // update xero invoice with rebate price // maybe need calling CURL
                        // 1 Login
                        $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
                        $fields = array();
                        $fields['user_name'] = 'admin';
                        $fields['username_password'] = 'pureandtrue2020*';
                        $fields['module'] = 'Users';
                        $fields['action'] = 'Authenticate';

                        $url = 'https://suitecrm.pure-electric.com.au';
                        $curl = curl_init();

                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

                        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);

                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
                        $result = curl_exec($curl);
                        // 2 Calling CURL
                        $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateXeroInvoice&invoice=1&method=put&record=".$row["id_c"]."&rebate_type=".$rebate_type;
                        $source .= "&rebate_price=".$price;
                        $source .= "&quantity=".$quantity;
                        if(isset($row['xero_invoice_c'])&& $row['xero_invoice_c'] != ""){
                            $source .= "&xero_invoice=".$row['xero_invoice_c'];
                        }
                        if(isset($row['xero_veec_rebate_invoice_c'])&& $row['xero_veec_rebate_invoice_c'] != ""){
                            $source .= "&rebate_xero_invoice=".$row['xero_veec_rebate_invoice_c'];
                        }

                        if(isset($row['xero_stc_rebate_invoice_c'])&& $row['xero_stc_rebate_invoice_c'] != ""){
                            $source .= "&rebate_xero_invoice=".$row['xero_stc_rebate_invoice_c'];

                        }

                        $GLOBALS['log']->info("CURL Response $source");
                        curl_setopt($curl, CURLOPT_URL, $source);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
                        curl_setopt($curl, CURLOPT_HEADER, true);
                        curl_setopt($curl, CURLOPT_VERBOSE, 1);
                        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_HTTPGET, true);
                        $curl_response = curl_exec($curl);

                        curl_close($curl);
                        $GLOBALS['log']->info("CURL Response $curl_response");
                    }
                }

                if($value !== null ){
                    //unlink($folder.$file);
                }
            }
        }
    }
    return true;
}


    array_push($job_strings, 'custom_lookupNotReplyEmail');
    function send_listLookupNotReplyEmail($data){
        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);
        $mail->Subject = 'Lookup Not Reply Email';
        
        //Body Email

        $body_email = '<div dir="ltr">
        Dear ' . $assigned_user_name .',<br/>
        This is the list of email that you didn\'t reply: <br/>';
        foreach($data as $result){

            $lead = new Lead();
            $lead = $lead->retrieve($result['parent_id']);

            $email_lead = new Email();
            $email_lead =  $email_lead->retrieve($result['id']);
            
            $body_email .= '<span>+ </span><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $lead->id . '#subpanel_history">['.$lead->first_name.' '.$lead->last_name.']</a>';
            
            $assigned_by = '';
            if($lead->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew
                $assigned_by = "Matthew Wright";
            }elseif($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
                $assigned_by = "Paul Szuster";
            }
            $body_email .= ' <a target="_blank" href="https://mail.google.com/#search/'.$lead->email1.'">[GM Search]</a>';
            $body_email .= '. Assigned: '.$assigned_by.'. Date: ' .date("d/m/Y H:i:s", strtotime($result['date_entered']));

            $body_email .= ' <a href="#">Ignore</a><br/>';
            $email_content =  preg_replace('/CRM Links:(.+?)End CRM Links/s', '', strip_tags($email_lead->description_html));

            $body_email .= '<span>   - Content: </span><span style="font-size:12px;font-style: italic;">'.substr($email_content,0,150).trim().'...</span><br/>';
        
        }
        
        $body_email .= '</div>';

        $mail->Body = $body_email;
        //END  Body Email

        $mail->prepForOutbound();
        $mail->AddAddress('info@pure-electric.com.au');
        $sent = $mail->Send();
    }
    function custom_lookupNotReplyEmail(){
        date_default_timezone_set('Africa/Lagos');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $db = DBManagerFactory::getInstance();
        $query =   "SELECT emails.id, emails.date_entered, emails.parent_id, emails.deleted, emails.`status` 
    
        FROM `leads`
         JOIN emails ON emails.parent_id = leads.id
         JOIN leads_cstm ON emails.parent_id = leads_cstm.id_c
        
         JOIN 
        (
            SELECT MAX(emails.date_entered) AS date_entered , emails.parent_id
        
        FROM `leads`
         JOIN emails ON emails.parent_id = leads.id
         JOIN leads_cstm ON emails.parent_id = leads_cstm.id_c
         
        WHERE 1 = 1  
        AND emails.`parent_type` = 'Leads' 
        AND emails.date_entered <= DATE_ADD(CURDATE(), INTERVAL -1 DAY) 
        AND leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain')
        AND (leads_cstm.ignore_lead_c != 1 OR leads_cstm.ignore_lead_c IS NULL)   
        AND emails.deleted = 0
          
        GROUP BY emails.parent_id
        ORDER BY `emails`.`date_entered` DESC
            ) result_table ON result_table.parent_id = emails.parent_id AND result_table.date_entered = emails.date_entered
             
        WHERE 1 = 1 
        AND emails.`parent_type` = 'Leads' 
        AND emails.date_entered <= DATE_ADD(CURDATE(), INTERVAL -1 DAY) 
        AND leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain')
        AND (leads_cstm.ignore_lead_c != 1 OR leads_cstm.ignore_lead_c IS NULL)   
        AND emails.deleted = 0
        
        AND emails.status = 'received'
            
        ORDER BY `emails`.`date_entered` DESC LIMIT 0,50";
        $ret = $db->query($query);
        if($ret->num_rows > 0){
            $data = array();
            $data_leads = array();
            while($row = $db->fetchByAssoc($ret)){
                    $data[] = $row;
                    $data_leads[] = $row['parent_id'];
            }
            send_listLookupNotReplyEmail($data);
            $str_leads_id = "('".implode("','",$data_leads)."')";
            $sql_update = "UPDATE leads_cstm SET ignore_lead_c = 1 WHERE id_c IN $str_leads_id";
            $ret = $db->query($query);
        }else{
            die();
        }
    }




array_push($job_strings, 'custom_readmail');

function replaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();
    
    $focusName = $request['parent_type'];
    $focus     = BeanFactory::getBean($focusName, $request['parent_id']);
    
    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate           = BeanFactory::getBean('EmailTemplates', isset($request['emails_email_templates_idb']) ? $request['emails_email_templates_idb'] : null);
    $email->name             = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description      = $emailTemplate->body;
    $templateData            = $emailTemplate->parse_email_template(array(
        'subject' => $email->name,
        'body_html' => $email->description_html,
        'body' => $email->description
    ), $focusName, $focus, $macro_nv);
    
    $email->name             = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description      = $templateData['body'];
    
    return $email;
}

function updateSolargainLead($leadID, $request, $email, $sg_user = "matthew")
{
   
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    $solargainLead = $lead->solargain_lead_number_c;
    date_default_timezone_set('Africa/Lagos');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    if($sg_user == "matthew"){
        $username = "matthew.wright";
        $password = "MW@pure733";
    }else{
        $username = "paul.szuster";
        $password = "Baited@42";
    }
    // Get full json response for Leads
    
    $url = "https://crm.solargain.com.au/APIv2/leads/" . $solargainLead;
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    
    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead,
        "Cache-Control: max-age=0"
    ));
    
    $leadJSON = curl_exec($curl);
    curl_close($curl);
    
    $leadSolarGain = json_decode($leadJSON);
    global $current_user;
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note = "";
    if (isset($email->from_name) && $email->from_name != "") {
        $note = $current_user->full_name . " : " . $email->from_name . " : " . $request["emails_email_templates_name"];
    }
    /*else {
    $note = $current_user->full_name. " : ".$request["emails_email_templates_name"];
    }*/
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 5,
            "Name" => "E-Mail Out",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url                     = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl                    = curl_init();
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " . strlen($leadSolarGainJSONDecode),
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead
    ));
    
    $lead = json_decode(curl_exec($curl));
    curl_close($curl);
}

function handleMultipleFileAttachments($request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if ( /*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
        
        require_once('modules/Notes/Note.php');
        $note        = new Note();
        $where       = "notes.parent_id = '" . $request["emails_email_templates_idb"] . "' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template
        
        $attachments = array();
        
        $attachments = array_merge($attachments, $attach_list);
        
        foreach ($attachments as $noteId) {
            
            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id           = create_guid();
            $noteTemplate->new_with_id  = true; // duplicating the note with files
            //$noteTemplate->parent_id = $this->id;
            //$noteTemplate->parent_type = $this->module_dir;
            $noteTemplate->parent_id    = $email->id;
            $noteTemplate->parent_type  = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();
            
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
        //$email->attachments = array_merge($email->attachments, $noteArray);
    }
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini    = strpos($string, $start);
    if ($ini == 0)
        return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function custom_readmail() 
{
    global $sugar_config;
    
    $folder = $sugar_config['mail_dir'];
    
    $file_array = scandir($folder);
    
    if (count($file_array) == 2)
        return true;
    
    foreach ($file_array as $file) {
        // temporary move file to specials
        //copy($folder . "/" . $file, $folder . "/specials/" . $file);

        if (is_file($folder . "/" . $file)) {
            // parse all content of file to get address
            $l_file_content = file_get_contents($folder . "/" . $file);
            // Parse for create new lead from forwarded newlead@pure-electric.com.au
            preg_match('#To: (.+?)\@pure-electric.com.au#i', $l_file_content, $newlead);
            if($newlead[1] == "newlead"){
                
                $address_street = '';
                $city =  '';
                $state =  '';
                $post_code = '';

                preg_match('#Name (.+?)\n#i', $l_file_content, $lead_name);
                if(count($lead_name) == 2){
                    $lead = explode(" ", trim($lead_name[1]));
                    $first_name = $lead[0];
                    $last_name = str_replace($first_name, "", trim($lead_name[1]));
                }

                preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $matches_email);
                if (count($matches_email) == 2) {
                    $from_email = trim($matches_email[1]);
                }

                preg_match('#Email (.+?)\n#i', $l_file_content, $email);
                if(count($email)==2){
                    $lead_email = $email[1];
                    if($lead_email != ''){
                        //check email is exists
                        $db = DBManagerFactory::getInstance();
                        $query_lead = "SELECT leads.id
                                  FROM leads
                                  JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
                                  JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
                                  WHERE email_addresses.email_address = '".$lead_email."' AND leads.deleted = 0";
                        $result = $db->query($query_lead);
                        if($result->num_rows > 0){
                            
                            require_once('include/SugarPHPMailer.php');
                            $emailObj = new Email();
                            $defaults = $emailObj->getSystemDefaultEmail();
                            $mail = new SugarPHPMailer();
                            $mail->setMailerForSystem();
                            $mail->From = $defaults['email'];
                            $mail->FromName = $defaults['name'];
                            $mail->IsHTML(true);

                            $mail->Subject = 'Automatically add Lead';

                            $mail->Body = 'Can not automatically add lead because email '.$lead_email.' is exists! ';

                            $mail->prepForOutbound();
                            $mail->AddAddress($from_email);

                            $sent = $mail->Send();
                            die();
                        }
                    }
                }

                preg_match('#Phone (.+?)\n#i', $l_file_content, $phone);
                $phone = $phone[1];

                preg_match('#Address (.+?)\n#i', $l_file_content, $address);
                $address = strtolower($address[1]);

                if($address != ''){
                    $curl = curl_init();
                    $address = str_replace ( " " , "+" , $address );
                    $url = "https://www.energyaustralia.com.au/qt2/app/quoteservice/qas/find?address=".$address."&postcode=";
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            "Host: www.energyaustralia.com.au",
                            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                            "Accept-Language: en-US,en;q=0.5",
                            "Accept-Encoding: 	gzip, deflate, br",
                            "Connection: keep-alive",
                            "Upgrade-Insecure-Requests: 1",
                            "Cache-Control: max-age=0",
                        )
                    );
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $out_address = json_decode($result);
                    if(count($out_address) >1){
                        $out_address = $out_address[1]->name;
                        $address1 = explode(',',$out_address);
                        $address_street = $address1[0];
                        $address_element = explode('  ',trim($address1[1]));
                        $city =  $address_element[0];
                        $state =  $address_element[1];
                        $post_code = $address_element[2];
                    }
                }

                $lead = new Lead();
                $lead->primary_address_street     = $address_street ? $address_street : "";
                $lead->primary_address_postalcode = $post_code ? $post_code : "";
                $lead->primary_address_city       = $city ? $city : "";
                $lead->primary_address_state      = $state ? $state : "";
                $lead->first_name = $first_name;
                $lead->last_name = $last_name;
                $lead->email1 = $lead_email;
                $lead->phone_mobile = $phone;

                if($from_email == "matthew@pure-electric.com.au" || $from_email == "matthew.wright@pure-electric.com.au"){
                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                }else if($from_email == "paul.szuster@pure-electric.com.au" || $from_email == "paul@pure-electric.com.au"){
                    $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                }else{
                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                }
                $lead->save;

                // save file attachment and thumb
                $count_ =  substr_count($l_file_content,'Content-Disposition: attachment;');

                $guid_new = create_guid();
                $current_file_path = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/server/php/files/'.$guid_new;
                
                if(!file_exists ( $current_file_path )) {
                    set_time_limit ( 0 );
                    mkdir($current_file_path);
                }

                preg_match_all('/filename="(.*?)"(.*?)--0000000000/s',$l_file_content,$match_pdf);
                for ($i=0; $i < $count_ ; $i++) {
                    $file_name =  $match_pdf[1][$i];
                    $file_content_ = $match_pdf[2][$i];
                    $file_content_ = explode("\n\n",$file_content_);
                    $source = $current_file_path.'/'.$file_name;
                    $fp = fopen($source, "w+");
                    fwrite($fp, base64_decode($file_content_[1]));
                    fclose($fp);

                    if(is_file($source)){
                        $type = strtolower(substr(strrchr($file_name, '.'), 1));
                        $typeok = TRUE;
                        if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                            if(!file_exists ($current_file_path."/thumbnail/")) {
                                mkdir($current_file_path."/thumbnail/");
                            }
                            $thumb =  $current_file_path."/thumbnail/".$file_name;
                            switch ($type) {
                                case 'jpg': // Both regular and progressive jpegs
                                case 'jpeg':
                                    $src_func = 'imagecreatefromjpeg';
                                    $write_func = 'imagejpeg';
                                    $image_quality = isset($options['jpeg_quality']) ?
                                        $options['jpeg_quality'] : 75;
                                    break;
                                case 'gif':
                                    $src_func = 'imagecreatefromgif';
                                    $write_func = 'imagegif';
                                    $image_quality = null;
                                    break;
                                case 'png':
                                    $src_func = 'imagecreatefrompng';
                                    $write_func = 'imagepng';
                                    $image_quality = isset($options['png_quality']) ?
                                        $options['png_quality'] : 9;
                                    break;
                                default: $typeok = FALSE; break;
                            }
                            if ($typeok){
                                list($w, $h) = getimagesize($source);

                                $src = $src_func($source);
                                $new_img = imagecreatetruecolor(80,80);
                                imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
                                $write_func($new_img,$thumb, $image_quality);

                                imagedestroy($new_img);
                                imagedestroy($src);
                            }
                        }
                    }
                }
                
                $lead->installation_pictures_c = $guid_new;
                $lead->save();
                unlink($folder . "/" . $file);
                return true; // break function 
            }

            // End parese
            preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $email_matches);
            if (isset($email_matches[1]) && $email_matches[1] != "") {
                preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $email_matches[1], $mail_matchs);
                if (isset($mail_matchs[0]) && $mail_matchs[0] != "") {
                    $email = $mail_matchs[0];
                    $db    = DBManagerFactory::getInstance();
                    $sql   = "SELECT * FROM email_addresses ea 
                                        LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                        WHERE 1=1 AND ea.email_address = '$email' AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module = 'Leads'
                                        ";
                    $ret   = $db->query($sql);
                    
                    while ($row = $db->fetchByAssoc($ret)) {
                        // We get address here 
                        preg_match('/my address is(:|)(.*?)(\n|\.)/i', $l_file_content, $address_matchs);
                        if (count($address_matchs) && $address_matchs[2] != "") {
                            // use regrxt pattern to get address 
                            // Just update address lead
                            $lead = new Lead();
                            $lead->retrieve($row["bean_id"]);
                            $lead->primary_address_street = $address_matchs[2];
                            $lead->save();
                            sendDesignRequestToAdmin($lead->id);
                            fclose($handle);
                            unlink($folder . "/" . $file);
                            return;
                        }
                    }
                }
            }

            // Get full plain text of email 
            $web_enquiry            = get_string_between($l_file_content, "web.enquiries", "01/01/0001");
            $full_plain_text        = get_string_between($l_file_content, "Content-Type: text/plain;", "Content-Type: text/html;");
            $address_section        = get_string_between($l_file_content, "Site Details", "Roof Type:");
            $address_section        = strip_tags($address_section);
            $address_section        = preg_replace('/\<http(.+?)\>/s', '', $address_section);
            $address_section        = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $address_section);
            $client_primary_address = "";

            // Pattern to get 
            $address_pattern = '/(\n([a-zA-Z-\s])+( )|)+(VIC|SA|NSW|ACT|TAS|WA|QLD|NT)+( )+(\d{4})/s';
            $out_address     = array();
            preg_match_all($address_pattern, $address_section, $out_address, PREG_PATTERN_ORDER);
            $out_address[0][0] = trim($out_address[0][0]);
            if (isset($out_address[0][0]) && $out_address[0][0] != "") {
                $address_element = explode(" ", $out_address[0][0]);
                if (count($address_element) >= 3) {
                    $state            = $address_element[count($address_element) - 2];
                    $post_code        = $address_element[count($address_element) - 1];
                    $suburb           = str_replace($state . " " . $post_code, "", $out_address[0][0]);
                    $suburb           = str_replace("-", "", $suburb);
                    $exploded_address = explode("\n", $out_address[0][0]);
                    if (count($exploded_address) == 2)
                        $client_primary_address = $exploded_address[0];
                }
                
                if (count($address_element) == 2) {
                    $state     = $address_element[0];
                    $post_code = $address_element[1];
                    $suburb    = "";
                }
            }

            // Special solve address 
            // dont understand why it s coded
            if (false){ //$client_primary_address == "") {
                /*$handle = fopen($folder."/".$file, "r");
                if ($handle) {
                $temp_client_primary_address = "";
                while (($line = fgets($handle)) !== false) {
                // get the next line
                if(isset($out_address[0][0]) &&  $out_address[0][0] != "" && (strpos($line, $out_address[0][0] ) !== false) ){
                if(strlen($temp_client_primary_address) > 5)
                if($client_primary_address == "") $client_primary_address = $temp_client_primary_address;
                }
                $temp_client_primary_address = $line;
                }
                }
                fclose($handle);
                */
                $client_primary_address = trim(preg_replace('/[^a-zA-Z0-9]/', " ", str_replace(array(
                    $suburb,
                    $post_code,
                    $state
                ), "", $address_section)));
            }
            
            if (strlen($client_primary_address) < 2)
                $client_primary_address = "";
            
            $handle = fopen($folder . "/" . $file, "r");
            if ($handle) {
                $live_chat_text        = "";
                $solargain_lead_number = "";
                $note                  = "";
                $end_live_chat         = false;
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, "This e-mail is private and confidential") !== false) {
                        fclose($handle);
                        copy($folder . "/" . $file, $folder . "/backup/" . $file);
                        unlink($folder . "/" . $file);
                        break;
                    }
                    ;
                    if (strpos(strtolower($line), "lead/edit") !== false) {
                        preg_match('/lead\/edit\/(.+?)>]/', strtolower($line), $match_firstchars);
                        if (isset($match_firstchars[1]) && $match_firstchars[1] != "") {
                            // Neu solargain lead number van la solargain cu ta continue toi dong tiep theo cho den khi gap Lead Edit 
                            if ($solargain_lead_number == $match_firstchars[1])
                                continue;
                            $solargain_lead_number = $match_firstchars[1];
                        } else
                            continue;
                        
                        while (($line1 = fgets($handle)) !== false) {
                            
                            if (strpos($line1, "This e-mail is private and confidential") !== false) {
                                fclose($handle);
                                copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                unlink($folder . "/" . $file);
                                break;
                            }
                            ;
                            
                            $line1 = strip_tags($line1);
                            // Co live chat
                            if (strpos($line1, "livechat") !== false) {
                                $api_comment_line = fgets($handle);
                                
                                if ($api_comment_line !== false && (strpos($api_comment_line, "API Comment: Livechat Submission") !== false)) {
                                    $blank_line = fgets($handle);
                                    while (($l_line = fgets($handle)) !== false) {
                                        
                                        if (strpos($l_line, "This e-mail is private and confidential") !== false) {
                                            fclose($handle);
                                            copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                            unlink($folder . "/" . $file);
                                            break;
                                        }
                                        ;
                                        
                                        if (strpos($l_line, "Customer Details") == false) {
                                            if (strpos($l_line, "01/01/0001") !== false) {
                                                $end_live_chat = true;
                                            } else {
                                                if (!$end_live_chat)
                                                    $live_chat_text .= trim(strip_tags($l_line), "=\n");
                                            }
                                        } else if (strpos($l_line, "Customer Details") !== false) {
                                            // Read the next line
                                            if (($next_line = fgets($handle)) !== false) {
                                                $next_line = strip_tags($next_line);
                                                if (strpos($next_line, ",") !== false) {
                                                    $names = explode(",", $next_line);
                                                } else {
                                                    $names = explode(" ", $next_line);
                                                }
                                                $first_name = "";
                                                $last_name  = "";
                                                $first_name = trim($names[1] ? $names[1] : "");
                                                $first_name = ucfirst(strtolower($first_name));
                                                $last_name  = trim($names[0] ? $names[0] : "");
                                                $last_name  = ucfirst(strtolower($last_name));
                                                
                                                if ($first_name == "") {
                                                    $last_name_explode = explode(" ", $last_name);
                                                    if (count($last_name_explode) > 1) {
                                                        $real_last_name = end($last_name_explode);
                                                        $first_name     = str_replace($real_last_name, "", $last_name);
                                                        $last_name      = $real_last_name;
                                                    }
                                                }
                                                
                                                $phone_mobile = "";
                                                $phone_work   = "";
                                                $email        = "";
                                                
                                                // Not exist do our next work
                                                // read all line by while until we get the new custommer lead
                                                
                                                while (($next_line = fgets($handle)) !== false) {
                                                    //if touch to end of New lead sections
                                                    // we do the if statement for each pattern here
                                                    if (strpos($next_line, "This e-mail is private and confidential") !== false) {
                                                        fclose($handle);
                                                        copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                                        unlink($folder . "/" . $file);
                                                        break;
                                                    }
                                                    
                                                    if (strpos($next_line, "m:") === 0) {
                                                        $phone_mobile = strip_tags(trim(str_replace(array(
                                                            "m:",
                                                            "**",
                                                            "*m:*"
                                                        ), "", $next_line)));
                                                        $phone_mobile = ($phone_mobile != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_mobile))) ) ? $phone_mobile : "";
                                                    }

                                                    if (strpos($next_line, "p:") !== false) {
                                                        $phone_work = strip_tags(trim(str_replace(array(
                                                            "*p:*",
                                                            "p:"
                                                        ), "", $next_line)));
                                                        $phone_work = ($phone_work != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_work))) ) ? $phone_work : "";
                                                    }
                                                    
                                                    if ( (strpos($next_line, "e:") === 0 || strpos($next_line, "*e:") === 0) && strpos($next_line, "@") !== false) {
                                                        //$email = strip_tags(trim(str_replace(array("*e:*", "e:"), "", $next_line)));
                                                        //$email = trim(str_replace(array("*e:*", "e:"),"",strip_tags( $next_line)));
                                                        //$email = ($email != "N/A\n") ? trim($email) : "";
                                                        preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $next_line, $mail_match_firstchars);
                                                        if (isset($mail_match_firstchars[0]) && $mail_match_firstchars[0] != "") {
                                                            $email = $mail_match_firstchars[0];
                                                        }
                                                        
                                                        // If we have already name in database Break
                                                        $db = DBManagerFactory::getInstance();
                                                        if ($first_name == "" && $last_name == "") {
                                                            fclose($handle);
                                                            unlink($folder . "/" . $file);
                                                            return;
                                                        }
                                                        
                                                        $db  = DBManagerFactory::getInstance();
                                                        $sql = "SELECT * FROM email_addresses ea 
                                                                            LEFT JOIN email_addr_bean_rel eabr ON eabr.email_address_id = ea.id 
                                                                            WHERE 1=1 AND ea.email_address = '$email' AND ea.deleted = 0 AND eabr.deleted = 0 AND eabr.bean_module = 'Leads'
                                                                            ";
                                                        $ret = $db->query($sql);
                                                        
                                                        while ($row = $db->fetchByAssoc($ret)) {
                                                            if (isset($row["bean_id"]) && $row["bean_id"] != "") {
                                                                fclose($handle);
                                                                unlink($folder . "/" . $file);
                                                                return;
                                                            }
                                                        }
                                                        
                                                        $sql = "SELECT * FROM leads WHERE 1=1 ";
                                                        //if ($first_name != "")
                                                        $sql .= " AND first_name = '" . $first_name . "'";
                                                        //if ($last_name != "")
                                                        $sql .= " AND last_name = '" . $last_name . "'";
                                                        $sql .= " AND deleted != 1 ";
                                                        $ret      = $db->query($sql);
                                                        $is_exsit = false;
                                                        while ($row = $db->fetchByAssoc($ret)) {
                                                            if (isset($row) && $row != null) {
                                                                $lead = new Lead();
                                                                $lead->retrieve($row["id"]);
                                                                
                                                                if ($lead->email1 == $email)
                                                                    $is_exsit = true;
                                                                if ($email == "") {
                                                                    $is_exsit = true;
                                                                }
                                                            }
                                                        }

                                                        if ($is_exsit) {
                                                            fclose($handle);
                                                            unlink($folder . "/" . $file);
                                                            return;
                                                        }
                                                        
                                                        $next_second_line = fgets($handle);
                                                        $note .= $next_second_line;
                                                        
                                                        //$GLOBALS['log']->debug('--------------------------------------------> at mext line.php <--------------------------------------------' .$next_second_line );
                                                        $next_third_line = fgets($handle);
                                                        $note .= $next_third_line;
                                                        
                                                        if ($next_third_line == $out_address[0][0] && strlen($next_second_line) > 5) {
                                                            $street_address = strip_tags($next_second_line);
                                                        }

                                                        // If it is full adreses
                                                        if (!isset($post_code) || $post_code == "") {
                                                            if (strlen($next_second_line) > 5) {
                                                                $next_fourth_line = fgets($handle);
                                                                if (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                                    $next_fourth_line = trim(strip_tags($next_fourth_line));
                                                                    //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                                    //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                                    $state            = $match_firstchars[0][0];
                                                                    
                                                                    $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line);
                                                                    $suburb        = $match_subburb[0];
                                                                    $post_code     = $match_subburb[1];
                                                                }
                                                            } elseif (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                                $next_third_line = trim(strip_tags($next_third_line));
                                                                //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                                //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                                $state           = $match_firstchars[0][0];
                                                                
                                                                $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line);
                                                                $suburb        = $match_subburb[0];
                                                                $post_code     = $match_subburb[1];
                                                            }
                                                        }
                                                    }
                                                    $note .= strip_tags($next_line);
                                                    if (strpos(strtolower($next_line), "assigned to") !== false) {
                                                        $note .= strip_tags($next_line);
                                                    }
                                                    
                                                }
                                                //if (strpos($line1, "Status:") !== false) break;
                                            }
                                            
                                            // store lead here
                                            if ($first_name != "" || $last_name != "") {
                                                $lead                             = new Lead();
                                                $lead->first_name                 = $first_name;
                                                $lead->last_name                  = $last_name;
                                                $lead->email1                     = $email;
                                                $lead->phone_mobile               = $phone_mobile;
                                                $lead->phone_work                 = $phone_work;
                                                $lead->solargain_lead_number_c    = $solargain_lead_number;
                                                $lead->primary_address_postalcode = $post_code ? $post_code : "";
                                                $lead->primary_address_city       = $suburb ? $suburb : "";
                                                $lead->primary_address_state      = $state ? $state : "";
                                                $lead->live_chat_c                = $live_chat_text ? str_replace("\n\n", "", $live_chat_text) : $web_enquiry;
                                                $lead->lead_source                = "Solargain";
                                                $lead->primary_address_street     = $client_primary_address ? $client_primary_address : ($street_address ? $street_address : "");
                                                if(strtolower(trim($lead->primary_address_street)) == 'n a' || strtolower(trim($lead->primary_address_street)) == 'n/a' || strtolower(trim($lead->primary_address_street)) == 'na'){
                                                    $lead->primary_address_street = "";
                                                }
                                                $full_plain_text_explode          = explode("This e-mail is private and confidential", $full_plain_text);
                                                $note_des = explode("Notes", $full_plain_text_explode[0]);
                                                $lead->description                = $note_des[1];
                                                $lead->address_provided_c         = $client_primary_address ? "1" : "0";
                                                $lead->status                     = "Assigned";
                                                
                                                
                                                preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $sent_froms);
                                                if(isset($sent_froms[1]) && $sent_froms[1] == "Matthew.Wright@solargain.com.au"){
                                                    $random_number = 60;
                                                } elseif(isset($sent_froms[1]) && (strtolower($sent_froms[1]) == "paul.szuster@solargain.com.au")) {
                                                    $random_number = 40;
                                                } else {
                                                    $random_number = rand ( 1 , 100 );
                                                }
                                                
                                                if ($random_number <= 50) {
                                                    $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa"; // Paul
                                                } else{
                                                    $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e"; // Matth
                                                }
                                                
                                                $lead->save();
                                                /* Need enought address
                                                $primary_address_street = $lead->primary_address_street;
                                                $primary_address_city = $lead->primary_address_city;
                                                $primary_address_state = $lead->primary_address_state;
                                                $primary_address_postalcode = $lead->primary_address_postalcode;
                                                */
                                                if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                                    
                                                    // Send email
                                                    if ($random_number <= 50) {
                                                        $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                                    } else{
                                                        $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                                    }
                                                    
                                                    $temp_request = array(
                                                        "module" => "Emails",
                                                        "action" => "send",
                                                        "record" => "",
                                                        "type" => "out",
                                                        "send" => 1,
                                                        "inbound_email_id" => ($random_number > 50) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                                        "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                                        "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                                        "parent_type" => "Leads",
                                                        "parent_name" => $lead->first_name ." ". $lead->last_name,
                                                        "parent_id" => $lead->id,
                                                        "from_addr" => $from_address,
                                                        "to_addrs_names" => $lead->email1, //"binhdigipro@gmail.com",//$lead->email1,
                                                        "cc_addrs_names" => "info@pure-electric.com.au",
                                                        "bcc_addrs_names" =>  "binh.nguyen@pure-electric.com.au",
                                                        "is_only_plain_text" => false
                                                    );

                                                    $emailBean    = new Email();
                                                    $emailBean    = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                                    
                                                    $emailBean->save();
                                                    
                                                    $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);
                                                    //$GLOBALS['log']->debug('--------------------------------------------> LEAD Number Run HERE <--------------------------------------------\n' .$solargain_lead_number );
                                                    // parse and replace bean variables
                                                    $emailBean                    = replaceEmailVariables($emailBean, $temp_request);
                                                    
                                                    // Signature
                                                    $matthew_id                   = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                                    $paul_id                      = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                                    $user                         = new User();
                                                    $user->retrieve($matthew_id);

                                                    if ($random_number <= 50) {
                                                        $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                                    } else{
                                                        $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; // Matthew signature
                                                    }
                                                    
                                                    $signature = $user->getSignature($emailSignatureId);
                                                    $emailBean->description .= $signature["signature"];
                                                    $emailBean->description_html .= $signature["signature_html"];
                                                    $emailBean->description .= $live_chat_text;
                                                    $emailBean->description_html .= $live_chat_text;
                                                    
                                                    //thien fix save email khi có live chat
                                                    $emailBean->save();
                                                    
                                                    $lead->email_send_id_c     = $emailBean->id;
                                                    $lead->email_send_status_c = 'pending';
                                                    $lead->save();
                                                    
                                                    if ($temp_request["parent_type"] == "Leads") {
                                                        $leadID = $temp_request["parent_id"];
                                                        //updateSolargainLead($leadID, $temp_request, $emailBean);
                                                    }
                                                    
                                                    //$body_html = $emailBean->description_html;
                                                    
                                                    // thien comment
                                                    // if ($emailBean->send()) {
                                                    //     $emailBean->status = 'sent';
                                                    //     // Do extended things here
                                                    //     // Save note to solargain
                                                    
                                                    //     if($temp_request["parent_type"] == "Leads"){
                                                    //         $leadID = $temp_request["parent_id"];
                                                    //         updateSolargainLead($leadID, $temp_request, $emailBean);
                                                    //     }
                                                    //     $emailBean->save();
                                                    
                                                    //     $body_html = $emailBean->description_html;
                                                    
                                                    // } else {
                                                    //     // Don't save status if the email is a draft.
                                                    //         // We need to ensure that drafts will still show
                                                    //         // in the list view
                                                    //         if ($emailBean->status !== 'draft') {
                                                    //             $emailBean->status = 'send_error';
                                                    //             $emailBean->save();
                                                    //         } else {
                                                    //             $emailBean->status = 'send_error';
                                                    //         }
                                                    // }

                                                    // Send SMS to client too 
                                                    $phone_number = $lead->phone_mobile ? $lead->phone_mobile : $lead->phone_work;
                                                    if($phone_number){
                                                        $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
                                                        if(strlen($phone_number) >= 10){
                                                            $phone_number = preg_replace("/^61/", "+61", $phone_number);
                                                            if(strpos($phone_number, "+61") !== false ){
                                                                $user = new User();
                                                                $user = $user->retrieve($lead->assigned_user_id);
                                                                $message_body = 'Hi '.$lead->first_name.', my name is '.$user->first_name.' from Solargain. I received your request for a Solargain solar/battery quote for your place, I have that you are in '.$lead->primary_address_city.' '.$lead->primary_address_state.'. If you could please reply back with your street address I would be more than happy to assist.  Look forward to your response. Regards, '.$user->first_name;
                                                                //exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$message_body.'"');
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    //thien fix
                                                    // Do nothing
                                                }
                                                $record_id = $lead->id;
                                                sendDesignRequestToAdmin($record_id);
                                            }
                                            
                                        }
                                    }
                                }
                                //$GLOBALS['log']->debug('--------------------------------------------> Live chat <--------------------------------------------\n' .$live_chat_text );
                            }
                            // Khong co live chat
                            if (strpos($line1, "Customer Details") !== false) {
                                // Read the next line
                                if (($next_line = fgets($handle)) !== false) {
                                    $next_line  = fgets($handle);
                                    $next_line  = strip_tags($next_line);
                                    $names      = explode(",", $next_line);
                                    $first_name = "";
                                    $last_name  = "";
                                    $first_name = trim($names[1] ? $names[1] : "");
                                    $first_name = ucfirst(strtolower($first_name));
                                    $last_name  = trim($names[0] ? $names[0] : "");
                                    $last_name  = ucfirst(strtolower($last_name));
                                    
                                    $phone_mobile = "";
                                    $phone_work   = "";
                                    $email        = "";
                                    
                                    // Not exist do our next work
                                    // read all line by while until we get the new custommer lead
                                    
                                    while (($next_line = fgets($handle)) !== false) {
                                        //if touch to end of New lead sections
                                        // we do the if statement for each pattern here
                                        
                                        if (strpos($next_line, "This e-mail is private and confidential") !== false) {
                                            fclose($handle);
                                            copy($folder . "/" . $file, $folder . "/backup/" . $file);
                                            unlink($folder . "/" . $file);
                                            break;
                                        }
                                    
                                        if (strpos($next_line, "m:") === 0) {
                                            $phone_mobile = strip_tags(trim(str_replace(array(
                                                "m:",
                                                "**",
                                                "*m:*"
                                            ), "", $next_line)));
                                            $phone_mobile = ($phone_mobile != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_mobile))) ) ? $phone_mobile : "";
                                        }
                                        
                                        if (strpos($next_line, "p:") !== false) {
                                            $phone_work = strip_tags(trim(str_replace(array(
                                                "*p:*",
                                                "p:"
                                            ), "", $next_line)));
                                            $phone_work = ($phone_work != "N/A\n" && is_numeric(str_replace('+','',str_replace(' ', '',$phone_work))) ) ? $phone_work : "";
                                        }
                                        
                                        if ( (strpos($next_line, "e:") === 0 || strpos($next_line, "*e:") === 0) && strpos($next_line, "@") !== false) {
                                            //$email = strip_tags(trim(str_replace(array("*e:*", "e:"), "", $next_line)));
                                            //$email = trim(str_replace(array("*e:*", "e:"),"",strip_tags( $next_line)));
                                            //$email = ($email != "N/A\n") ? trim($email) : "";
                                            preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $next_line, $mail_match_firstchars);
                                            if (isset($mail_match_firstchars[0]) && $mail_match_firstchars[0] != "") {
                                                $email = $mail_match_firstchars[0];
                                            }
                                            
                                            // If we have already name in database Break
                                            $db = DBManagerFactory::getInstance();
                                            if ($first_name == "" && $last_name == "") {
                                                fclose($handle);
                                                unlink($folder . "/" . $file);
                                                return;
                                            }
                                            $sql = "SELECT * FROM leads WHERE 1=1 ";
                                            //if ($first_name != "")
                                            $sql .= " AND first_name = '" . $first_name . "'";
                                            //if ($last_name != "")
                                            $sql .= " AND last_name = '" . $last_name . "'";
                                            $sql .= " AND deleted != 1 ";
                                            $ret      = $db->query($sql);
                                            $is_exsit = false;
                                            while ($row = $db->fetchByAssoc($ret)) {
                                                if (isset($row) && $row != null) {
                                                    $lead = new Lead();
                                                    $lead->retrieve($row["id"]);
                                                    
                                                    if ($lead->email1 == $email)
                                                        $is_exsit = true;
                                                }
                                            }
                                            if ($is_exsit) {
                                                fclose($handle);
                                                unlink($folder . "/" . $file);
                                                return;
                                            }
                                            
                                            $next_second_line = fgets($handle);
                                            $note .= $next_second_line;
                                            //$GLOBALS['log']->debug('--------------------------------------------> at mext line.php <--------------------------------------------' .$next_second_line );
                                            $next_third_line = fgets($handle);
                                            $note .= $next_third_line;
                                            
                                            if ($next_third_line == $out_address[0][0] && strlen($next_second_line) > 5) {
                                                $street_address = strip_tags($next_second_line);
                                            }
                                            
                                            // If it is full adreses
                                            if (!isset($post_code) || $post_code == "") {
                                                if (strlen($next_second_line) > 5) {
                                                    $next_fourth_line = fgets($handle);
                                                    if (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                        $next_fourth_line = trim(strip_tags($next_fourth_line));
                                                        //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                        //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                        $state            = $match_firstchars[0][0];
                                                        
                                                        $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_fourth_line);
                                                        $suburb        = $match_subburb[0];
                                                        $post_code     = $match_subburb[1];
                                                    }
                                                } elseif (preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars, PREG_PATTERN_ORDER)) {
                                                    $next_third_line = trim(strip_tags($next_third_line));
                                                    //$GLOBALS['log']->debug('--------------------------------------------> at thirdline .php <--------------------------------------------' .$next_third_line );
                                                    //if(preg_match_all('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line, $match_firstchars,PREG_PATTERN_ORDER))
                                                    $state           = $match_firstchars[0][0];
                                                    
                                                    $match_subburb = preg_split('/VIC|SA|NSW|ACT|TAS|WA|QLD|NT/', $next_third_line);
                                                    $suburb        = $match_subburb[0];
                                                    $post_code     = $match_subburb[1];
                                                }
                                            }
                                        }
                                        $note .= strip_tags($next_line);
                                        
                                        if (strpos(strtolower($next_line), "Assigned To") !== false) {
                                            $note .= strip_tags($next_line);
                                        }
                                        
                                    }
                                    
                                    //if (strpos($line1, "Status:") !== false) break;
                                    
                                }
                                // store lead here

                                if ($first_name != "" || $last_name != "") {
                                    $lead                             = new Lead();
                                    $lead->first_name                 = $first_name;
                                    $lead->last_name                  = $last_name;
                                    $lead->email1                     = $email;
                                    $lead->phone_mobile               = $phone_mobile;
                                    $lead->phone_work                 = $phone_work;
                                    $lead->solargain_lead_number_c    = $solargain_lead_number;
                                    $lead->primary_address_postalcode = $post_code ? $post_code : "";
                                    $lead->primary_address_city       = $suburb ? $suburb : "";
                                    $lead->primary_address_state      = $state ? $state : "";
                                    $lead->primary_address_street     = $client_primary_address ? $client_primary_address : ($street_address ? $street_address : "");
                                    if(strtolower(trim($lead->primary_address_street)) == 'n a' || strtolower(trim($lead->primary_address_street)) == 'n/a' || strtolower(trim($lead->primary_address_street)) == 'na'){
                                        $lead->primary_address_street = "";
                                    }
                                    $lead->live_chat_c                = $live_chat_text ? str_replace("\n\n", "", $live_chat_text) : $web_enquiry;
                                    $lead->lead_source                = "Solargain";
                                    $full_plain_text_explode          = explode("This e-mail is private and confidential", $full_plain_text);
                                    $note_des = explode("Notes", $full_plain_text_explode[0]);
                                    $lead->description                = $note_des[1];
                                    
                                    $lead->address_provided_c = $client_primary_address ? "1" : "0";
                                    $lead->status             = "Assigned";

                                    preg_match('#Return-Path: <(.+?)>#i', $l_file_content, $sent_froms);
                                    if(isset($sent_froms[1]) && $sent_froms[1] == "Matthew.Wright@solargain.com.au"){
                                        $random_number = 60;
                                    } elseif(isset($sent_froms[1]) && (strtolower($sent_froms[1]) == "paul.szuster@solargain.com.au")) {
                                        $random_number = 40;
                                    } else {
                                        $random_number = rand ( 1 , 100 );
                                    }
                                    
                                    if ($random_number <= 50) {
                                        $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa"; // Paul
                                    } else{
                                        $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e"; // Matth
                                    }
                                    
                                    $lead->save();
                                    
                                    if ($random_number <= 50) {
                                        $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                    } else{
                                        $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                    }
                                    
                                    $matthew_inbound_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
                                    $paul_inbound_id    = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
                                    
                                    if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                        $temp_request        = array(
                                            "module" => "Emails",
                                            "action" => "send",
                                            "record" => "",
                                            "type" => "out",
                                            "send" => 1,
                                            "inbound_email_id" => ($random_number > 50) ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                            "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                            "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                            "parent_type" => "Leads",
                                            "parent_name" => $lead->first_name .' '. $lead->last_name,
                                            "parent_id" => $lead->id,
                                            "from_addr" => $from_address,
                                            "to_addrs_names" => $lead->email1, //$lead->email1, //"binhdigipro@gmail.com",
                                            "cc_addrs_names" => "info@pure-electric.com.au",
                                            "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
                                            "is_only_plain_text" => false
                                        );

                                        $emailBean           = new Email();
                                        $emailBean           = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                        $inboundEmailAccount = new InboundEmail();
                                        $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);

                                        $emailBean->save();
                                        $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);
                                        
                                        // parse and replace bean variables
                                        $emailBean = replaceEmailVariables($emailBean, $temp_request);
                                        
                                        // Signature
                                        $matthew_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                        $paul_id    = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                        $user       = new User();
                                        $user->retrieve($matthew_id);
                                        if ($random_number <= 50) { // Matthew 
                                            $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                        } else{
                                            $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d";
                                        }
                                        
                                        $signature = $user->getSignature($emailSignatureId);
                                        $emailBean->description .= $signature["signature"];
                                        $emailBean->description_html .= $signature["signature_html"];
                                        $emailBean->description .= $live_chat_text;
                                        $emailBean->description_html .= $live_chat_text;
                                        
                                        //thien fix save email khi không có live chat
                                        $emailBean->save();
                                        
                                        if ($temp_request["parent_type"] == "Leads") {
                                            $leadID = $temp_request["parent_id"];
                                            //updateSolargainLead($leadID, $temp_request, $emailBean);
                                        }
                                        
                                        $lead->email_send_id_c     = $emailBean->id;
                                        $lead->email_send_status_c = 'pending';
                                        
                                        $lead->save();
                                        
                                        //thien commnent
                                        // if ($emailBean->send()) {
                                        //     $emailBean->status = 'sent';
                                        //     // Do extended things here
                                        //     // Save note to solargain
                                        //     if($temp_request["parent_type"] == "Leads"){
                                        //         $leadID = $temp_request["parent_id"];
                                        //         updateSolargainLead($leadID, $temp_request, $emailBean);
                                        //     }
                                        //     $emailBean->save();
                                        // } else {
                                        //         if ($emailBean->status !== 'draft') {
                                        //             $emailBean->status = 'send_error';
                                        //             $emailBean->save();
                                        //         } else {
                                        //             $emailBean->status = 'send_error';
                                        //         }
                                        // }

                                        // Send SMS To client
                                        $phone_number = $lead->phone_mobile ? $lead->phone_mobile : $lead->phone_work;
                                        if($phone_number){
                                            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
                                            if(strlen($phone_number) >= 10){
                                                $phone_number = preg_replace("/^61/", "+61", $phone_number);

                                                if(strpos($phone_number, "+61") !== false ){
                                                    $user = new User();
                                                    $user = $user->retrieve($lead->assigned_user_id);
                                                    $message_body = 'Hi '.$lead->first_name.', my name is '.$user->first_name.' from Solargain. I received your request for a Solargain solar/battery quote for your place, I have that you are in '.$lead->primary_address_city.' '.$lead->primary_address_state.'. If you could please reply back with your street address I would be more than happy to assist.  Look forward to your response. Regards, '.$user->first_name;
                                                    // we will not send sms
                                                    // exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$message_body.'"');
                                                }
                                            }
                                        }
                                    } else {
                                        //thien fix
                                        
                                    }
                                    $record_id = $lead->id;
                                    sendDesignRequestToAdmin($record_id);
                                }
                                
                            }
                            
                        }
                    }
                }
                
                fclose($handle);
                if ($lead->id != "") {
                    copy($folder . "/" . $file, $folder . "/backup/" . $file);
                }
                unlink($folder . "/" . $file);
                return;
            } else {
                // error opening the file.
            }
        }
    }
}

function updateDesignForSolargainLead($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    
    $solargainLead = $lead->solargain_lead_number_c;
    
    $username = "matthew.wright";
    $password = "MW@pure733";
    
    // Get full json response for Leads
    
    $url = "https://crm.solargain.com.au/APIv2/leads/" . $solargainLead;
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead,
        "Cache-Control: max-age=0"
    ));
    
    $leadJSON = curl_exec($curl);
    curl_close($curl);
    
    $leadSolarGain = json_decode($leadJSON);
    
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note                   = "Preparing designs and quote for client";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 1,
            "Name" => "General",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url                     = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl                    = curl_init();
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " . strlen($leadSolarGainJSONDecode),
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead
    ));
    
    $lead = json_decode(curl_exec($curl));
    curl_close($curl);
}

function getLatLong($address)
{
    
    $array = array();
    $geo   = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
    
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude  = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $array     = array(
            'lat' => $latitude,
            'lng' => $longitude
        );
    }
    
    return $array;
}

function sendDesignRequestToAdmin($record_id)
{

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = getLatLong($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "Leads",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $lead->id,
        "from_addr" => $from_address,
        "to_addrs_names" => "admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    $emailBean->save();
    $emailBean->saved_attachments = handleMultipleFileAttachments($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();

    updateDesignForSolargainLead($record_id);
    $lead->email_send_design_request_id_c = $emailBean->id;
    $lead->email_send_design_status_c = 'pending';
    $lead->save();

    return $emailBean;
}


array_push($job_strings, 'custom_readmailsam');

function custom_readmailsam(){
    $hostname = '{webmail.solargain.com.au:993/imap/ssl/novalidate-cert}INBOX'; 
    $username = 'paul.szuster@solargain.com.au';
    $password = 'Baited@42';

    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Exchange: ' . imap_last_error());
    
    /* grab emails */
    $date = new DateTime("-1 days");
    $date_before_1_days = $date->format('d F Y');
    
    //$emails = imap_search($inbox,'SUBJECT "Assigned (L#" SINCE "'.$date_before_3_days.'" UNSEEN');
    $emails = imap_search($inbox,'SINCE "'.$date_before_1_days.'" UNSEEN');
    
    /* if emails are returned, cycle through each... */
    if($emails) {
        
        /* begin output var */
        $output = '';
        
        /* put the newest emails on top */
        rsort($emails);
        
        /* for every email... */
        foreach($emails as $email_number) {

            $overview  = imap_fetch_overview($inbox,$email_number,0);
            $subject   =  htmlentities($overview[0]->subject);
            
            if(stripos($subject,"Assigned (L#") !== false){
                $file_content = strip_tags(imap_body($inbox, $email_number));
                $result_array_info_email = get_information_from_email($file_content);
                if(strpos($result_array_info_email['lead_status'],'Assigned') !== false || strpos($result_array_info_email['lead_status'],'New') !== false){
                    $result_array_address = get_address($result_array_info_email['lead_address']);
                    $result_array_name = get_name($result_array_info_email['lead_fullname']);

                    //check exist lead
                    if(!check_exist_lead($result_array_name['first_name'],$result_array_name['last_name'],$result_array_info_email['lead_email'])){
                        if($result_array_name['first_name'] != '' || $result_array_name['last_name'] != '') {
                            $lead = new Lead();
                            $lead->primary_address_street     = $result_array_address['street'] ? $result_array_address['street'] : '';
                            $lead->primary_address_postalcode = $result_array_address['postcode'] ? $result_array_address['postcode'] : "";
                            $lead->primary_address_city       = $result_array_address['city'] ? $result_array_address['city'] : "";
                            $lead->primary_address_state      = $result_array_address['state'] ? $result_array_address['state'] : "";
                            $lead->first_name  = $result_array_name['first_name'] ? $result_array_name['first_name'] : "";
                            $lead->last_name = $result_array_name['last_name'] ? $result_array_name['last_name'] : "";
                            $lead->email1 = $result_array_info_email['lead_email'] ? trim($result_array_info_email['lead_email']) : "";
                            $lead->phone_work  = $result_array_info_email['lead_phone'] ? $result_array_info_email['lead_phone'] : "";
                            $lead->phone_mobile  = $result_array_info_email['lead_mobile_phone'] ? $result_array_info_email['lead_mobile_phone'] : "";
                            
                            $lead->assigned_user_id = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";//defaul paul
                            $lead->lead_source                = "Solargain";
                            $lead->description = $result_array_info_email['lead_note'];
                            $lead->status = "Assigned";
                            $lead->lead_source_co_c = 'Solargain';
                            $lead->solargain_lead_number_c = $result_array_info_email["lead_number"];
                            $lead->save();   
                            if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                                        
                                // Send email 
                                if ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") {
                                    $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                } else{
                                    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                }
                                
                                $temp_request = array(
                                    "module" => "Emails",
                                    "action" => "send",
                                    "record" => "",
                                    "type" => "out",
                                    "send" => 1,
                                    "inbound_email_id" => ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                    "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                    "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                    "parent_type" => "Leads",
                                    "parent_name" => $lead->first_name ." ". $lead->last_name,
                                    "parent_id" => $lead->id,
                                    "from_addr" => $from_address,
                                    "to_addrs_names" => $lead->email1, //"binhdigipro@gmail.com",//$lead->email1,
                                    "cc_addrs_names" => "info@pure-electric.com.au",
                                    "bcc_addrs_names" =>  "binh.nguyen@pure-electric.com.au",
                                    "is_only_plain_text" => false
                                );

                                $emailBean    = new Email();
                                $emailBean    = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                
                                $emailBean->save();
                                
                                $emailBean->saved_attachments = custom_handleMultipleFileAttachments($temp_request, $emailBean);
                                //$GLOBALS['log']->debug('--------------------------------------------> LEAD Number Run HERE <--------------------------------------------\n' .$solargain_lead_number );
                                // parse and replace bean variables
                                $emailBean                    = custom_replaceEmailVariables($emailBean, $temp_request);
                                
                                // Signature
                                $matthew_id                   = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                $paul_id                      = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                $user                         = new User();
                                $user->retrieve($matthew_id);

                                if ($random_number <= 50) {
                                    $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                } else{
                                    $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; // Matthew signature
                                }
                                
                                $signature = $user->getSignature($emailSignatureId);
                                $emailBean->description .= $signature["signature"];
                                $emailBean->description_html .= $signature["signature_html"];
                                $emailBean->description .= $live_chat_text;
                                $emailBean->description_html .= $live_chat_text;
                                $emailBean->save();
                                
                                $lead->email_send_id_c     = $emailBean->id;
                                $lead->email_send_status_c = 'pending';
                                $lead->save();
                                
                                if ($temp_request["parent_type"] == "Leads") {
                                    $leadID = $temp_request["parent_id"];
                                }      
                            } 
                            $record_id = $lead->id;
                            custom_sendDesignRequestToAdmin($record_id);
                            echo "<br><a href='/index.php?module=Leads&action=EditView&record=$lead->id' target='_blank' >$lead->id</a>";
                        }
                    }else{
                        send_email_alert_lead_exist($result_array_info_email['lead_email'],$result_array_info_email['to']);
                    }
                }
            }else{
                $message   = getBody($email_number,$inbox);
                $structure = imap_fetchstructure($inbox,$email_number);
                $header    = imap_headerinfo($inbox, $email_number);
                $attachments = array();
                $file_attachments = getAttachments($inbox,$email_number,$structure);

                require_once('include/SugarPHPMailer.php');
                $overview  = imap_fetch_overview($inbox,$email_number,0);
                $subject = $overview[0]->subject;
                $from = $overview[0]->from;
                $fromEmail = $header->from[0]->personal." (".$header->from[0]->mailbox . "@" . $header->from[0]->host.")";

                preg_match('/<body[^>]*>.*?/',$message, $out);
                $first_div = "";
                if(isset($out[0])){
                    $first_div  = $out[0];
                }
                $add_to_body = '';
                foreach($header->to as $email_to){
                  $add_to_body .= $email_to->personal." (".$email_to->mailbox."@".$email_to->host."), ";
                }
                $add_cc_body = '';
                foreach($header->cc as $email_cc){
                  $add_cc_body .= $email_cc->personal." (".$email_cc->mailbox."@".$email_cc->host."), ";
                }
                $add_cc_body =  trim($add_cc_body,', ');
                $message  = preg_replace("/<body[^>]*>.*?/", $first_div.'<br/><strong>Sendder Address :</strong> '.$fromEmail.'<br/><br/><strong>To Address :</strong> '.$add_to_body.'<br/><br/><strong>Cc Address:</strong> '.$add_cc_body.'<br/><br/>', $message, 1);
  
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer();
                $mail->setMailerForSystem();
                $mail->From = "paul.szuster@solargain.com.au";
                $mail->FromName = 'Paul Szuster Solargain';
                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;;
                $mail->prepForOutbound();
                
                $mail->AddAddress('info@pure-electric.com.au');
                if(!empty($file_attachments)){
                    foreach($file_attachments as $file){
                        $mail->AddAttachment($file);
                    }
                }
                $mail->Send();
                foreach($file_attachments as $file){
                    unlink($file);
                }
            }
        }
    } 
} 

//Thienpb code
    function get_mime_type($structure)
    {
        $primaryMimetype = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];
        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false)
    {
        if (!$structure) {
            $structure = imap_fetchstructure($imap,$uid);
        }
        if ($structure) {
            if ($mimetype == get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber);
                switch ($structure->encoding) {
                    case 3:
                        return imap_base64($text);
                    case 4:
                        return imap_qprint($text);
                    default:
                        return $text;
                }
            }
            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    function getBody($uid, $imap)
    {
        $body = get_part($imap, $uid, "TEXT/HTML");
        if ($body == "") {
            $body = get_part($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }
    function getAttachments($inbox,$email_number,$structure){
        if(isset($structure->parts) && count($structure->parts)) {
          for($i = 0; $i < count($structure->parts); $i++) {
            $attachments[$i] = array(
              'is_attachment' => false,
              'filename' => '',
              'name' => '',
              'attachment' => ''
            );
            
            if($structure->parts[$i]->ifdparameters) {
              foreach($structure->parts[$i]->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['filename'] = $object->value;
                }
              }
            }
      
            if($structure->parts[$i]->ifparameters) {
              foreach($structure->parts[$i]->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['name'] = $object->value;
                }
              }
            }
            
            if($attachments[$i]['is_attachment']) {
              $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
              if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
              } elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
              }
            }
          } 
        } 
        
        $file_attachments = array();
        if(count($attachments) != 0){
          $path = "custom/include/SugarFields/Fields/Multiupload/server/php/files/attachments/";
          if(!file_exists ( $path )) {
            set_time_limit ( 0 );
            mkdir($path);
          }
          foreach($attachments as $at){
            if($at[is_attachment]==1){
              $fname = $at['filename'];
              $file_attachments[] = $path."$fname";
              $fp = fopen( $path."$fname","w");
              fwrite($fp, $at['attachment']);
              fclose($fp);
            }
          }
        }
      
        return $file_attachments;
      }
//end

function get_information_from_email ($file_content) {

    preg_match("/Subject:(.*?)\r\n/s" , $file_content, $match_body);
    $subject = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/To:(.*?)\r\n/s" , $file_content, $match_body);
    $to = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Lead #(\d+)/s" , $file_content, $match_body);
    $lead_number = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Status:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_status = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/System Interested In:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_System_Interested_In =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Next Action Date:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_next_action_date =  (isset($match_body[1])) ? $match_body[1] : ''; ;
    
    //get info custom details
    preg_match_all("/Customer Details(.*?)Site Details/s" , $file_content, $match_body);
    $content_custom_details = (isset($match_body[1])) ? $match_body[1][0] : ''; 
    
    preg_match("/\r\n(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_fullname =  (isset($match_body[1])) ? $match_body[1] : ''; 

    preg_match("/p:(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/m:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_mobile_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/e:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_email = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get info Site Details
    preg_match("/Site Details(.*?)Notes/s" , $file_content, $match_body);
    $lead_address=  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get note 
    preg_match("/Notes(.*?)This e-mail is private and confidential./s" , $file_content, $match_body);
    $lead_note=  (isset($match_body[1])) ? $match_body[1] : ''; 

    $result = array (
        'subject' => $subject,
        'to' => $to,
        'lead_fullname' => $lead_fullname,
        'lead_number' =>$lead_number ,
        'lead_status' => $lead_status,
        'lead_System_Interested_In' => $lead_System_Interested_In,
        'lead_next_action_date' => $lead_next_action_date,
        'lead_phone' =>$lead_phone,
        'lead_mobile_phone' => $lead_mobile_phone,
        'lead_email' => $lead_email,
        'lead_address' => $lead_address,
        'lead_note' => $lead_note
    );

    return $result;
}

function get_address($string_address){
    if($string_address != ''){
    
        //case 1: get address from string_address.com.au
        $address = rawurlencode(utf8_encode($string_address));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/autocomplete?q='.$address.'&key=MFQ93YPXGETJ7DKWLN48&format=json&max=7&wv=3.18.8&session=&highlight=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
        $headers[] = 'Accept: application/json, text/javascript';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Referer: https://addressfinder.com.au/';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Origin: https://addressfinder.com.au';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Te: Trailers';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);

        $address_arr =  json_decode($result);

        if($address_arr){
            $address_id = $address_arr->completions[0]->id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/info?format=json&key=MFQ93YPXGETJ7DKWLN48&wv=3.18.8&session=&paf=1&gps=1&id='.$address_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
            $headers[] = 'Accept: application/json, text/javascript';
            $headers[] = 'Accept-Language: en-US,en;q=0.5';
            $headers[] = 'Referer: https://addressfinder.com.au/';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Origin: https://addressfinder.com.au';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Pragma: no-cache';
            $headers[] = 'Cache-Control: no-cache';
            $headers[] = 'Te: Trailers';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close ($ch);

            $address_parse = json_decode($result);

            $address_elements = array(
                'full_address'  => (isset($address_parse->full_address)) ? $address_parse->full_address : '' ,
                'street'        => (isset($address_parse->address_line_1)) ? $address_parse->address_line_1.' '.$address_parse->address_line_2 : '',
                'city'          => (isset($address_parse->locality_name)) ? $address_parse->locality_name : '',
                'state'         => (isset($address_parse->state_territory)) ? $address_parse->state_territory : '',
                'postcode'      => (isset($address_parse->postcode)) ? $address_parse->postcode : '',
                'lat'           => (isset($address_parse->latitude)) ? $address_parse->latitude : '',
                'long'          => (isset($address_parse->longitude)) ? $address_parse->longitude : '',
            );
        }
        //case 2: get address by regex
        if(empty($address_elements['street']) && empty($address_elements['city']) && empty($address_elements['state']) && empty($address_elements['post_code'])){
            $address_pattern = '/(\n([a-zA-Z-\s])+( )|)+(VIC|SA|NSW|ACT|TAS|WA|QLD|NT)+( )+(\d{4})/s';
            $out_address     = array();
            preg_match_all($address_pattern, $string_address, $out_address, PREG_PATTERN_ORDER);
            $out_address[0][0] = trim($out_address[0][0]);
            if (isset($out_address[0][0]) && $out_address[0][0] != "") {
                $address_element = explode(" ", $out_address[0][0]);
                if (count($address_element) >= 3) {
                    $state            = $address_element[count($address_element) - 2];
                    $post_code        = $address_element[count($address_element) - 1];
                    $city           = str_replace($state . " " . $post_code, "", $out_address[0][0]);
                    $city           = str_replace("-", "", $city);
                    $exploded_address = explode("\n", $out_address[0][0]);
                    if (count($exploded_address) == 2)
                        $client_primary_address = $exploded_address[0];
                }
                
                if (count($address_element) == 2) {
                    $state     = $address_element[0];
                    $post_code = $address_element[1];
                    $city    = "";
                    $address1 = explode(',',$out_address);
                }
                $address_elements = array(
                    'full_address'  => (isset($out_address1)) ? $out_address : '' ,
                    'street'        => (isset($address_street)) ? $address_street : '',
                    'city'          => (isset($city)) ? $city : '',
                    'state'         => (isset($state)) ? $state : '',
                    'postcode'      => (isset($post_code)) ? $post_code : '',
                    'lat'           => '',
                    'long'          => '',
                );
            }
            
        }   
    }
    
    return $address_elements ;
}

function get_name($string_name){
    if($string_name != '') {
        $name_array = explode(",", trim($string_name));
        $last_name = trim($name_array[0]);
        $first_name = trim($name_array[1]);
        $full_name =  $last_name .' ' .$first_name; 
    }
    $result = array(
        'fullname' => (isset($full_name)) ? $full_name : '',
        'last_name' => (isset($last_name)) ? $last_name : '',
        'first_name' => (isset($first_name)) ? $first_name : '',
    );
    return $result;
}

function check_exist_lead($first_name,$last_name,$lead_email){
    $email = trim($lead_email);
    $db = DBManagerFactory::getInstance();
    $query = "SELECT leads.id as id ,email_addresses.email_address as email  FROM leads 
    JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
    JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE email_addresses.email_address = '$email'" ;
    $ret = $db->query($query);
    $is_exsit = false;
    if($ret->num_rows > 0){
        $is_exsit = true;
    }else{
        $is_exsit = false;
    }
    return $is_exsit;
}

function send_email_alert_lead_exist($customer_lead_email,$fromto){
    //check email is exists
    $lead_email = trim($customer_lead_email);
    if($lead_email != '') {
        if(strpos($fromto,'matthew') !== false){
            $address = "matthew.wright@pure-electric.com.au";
        }else if(strpos($fromto,'paul') !== false){
            $address = "paul.szuster@pure-electric.com.au";
        }else{
            $address = "matthew.wright@pure-electric.com.au";
        }
        $db = DBManagerFactory::getInstance();
        $query_lead = "SELECT leads.id
                    FROM leads
                    JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
                    JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
                    WHERE email_addresses.email_address = '".$lead_email."' AND leads.deleted = 0";
        $result = $db->query($query_lead);
        if($result->num_rows > 0){
            
            require_once('include/SugarPHPMailer.php');
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = $defaults['email'];
            $mail->FromName = $defaults['name'];
            $mail->IsHTML(true);
    
            $mail->Subject = 'Automatically add Lead';
    
            $mail->Body = 'Can not automatically add lead because email '.$lead_email.' is exists! ';
    
            $mail->prepForOutbound();
            //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
            $mail->AddAddress($address);
            $sent = $mail->Send();
            die();
        }  
    }

}


function custom_getLatLong($address)
{
    
    $array = array();
    $geo   = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
    
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude  = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $array     = array(
            'lat' => $latitude,
            'lng' => $longitude
        );
    }
    
    return $array;
}

function custom_sendDesignRequestToAdmin($record_id)
{

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = custom_getLatLong($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "Leads",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $lead->id,
        "from_addr" => $from_address,
        "to_addrs_names" => 'nguyenphudung93.dn@gmail.com',//"admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        // "cc_addrs_names" => "info@pure-electric.com.au",
        //  "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    $emailBean->save();
    $emailBean->saved_attachments = custom_handleMultipleFileAttachments($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = custom_replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();

    custom_updateDesignForSolargainLead($record_id);
    $lead->email_send_design_request_id_c = $emailBean->id;
    $lead->email_send_design_status_c = 'pending';
    $lead->save();

    return $emailBean;
}

function custom_handleMultipleFileAttachments($request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if ( /*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
        
        require_once('modules/Notes/Note.php');
        $note        = new Note();
        $where       = "notes.parent_id = '" . $request["emails_email_templates_idb"] . "' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template
        
        $attachments = array();
        
        $attachments = array_merge($attachments, $attach_list);
        
        foreach ($attachments as $noteId) {
            
            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id           = create_guid();
            $noteTemplate->new_with_id  = true; 
            $noteTemplate->parent_id    = $email->id;
            $noteTemplate->parent_type  = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();
            
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
    }
}

function custom_replaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();
    
    $focusName = $request['parent_type'];
    $focus     = BeanFactory::getBean($focusName, $request['parent_id']);
    
    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate           = BeanFactory::getBean('EmailTemplates', isset($request['emails_email_templates_idb']) ? $request['emails_email_templates_idb'] : null);
    $email->name             = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description      = $emailTemplate->body;
    $templateData            = $emailTemplate->parse_email_template(array(
        'subject' => $email->name,
        'body_html' => $email->description_html,
        'body' => $email->description
    ), $focusName, $focus, $macro_nv);
    
    $email->name             = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description      = $templateData['body'];
    
    return $email;
}

function custom_updateDesignForSolargainLead($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    
    $solargainLead = $lead->solargain_lead_number_c;
    
    $username = "paul.szuster@solargain.com.au";
    $password = "Baited@42";
    
    // Get full json response for Leads
    
    $url = "https://crm.solargain.com.au/APIv2/leads/" . $solargainLead;
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead,
        "Cache-Control: max-age=0"
    ));
    
    $leadJSON = curl_exec($curl);
    curl_close($curl);
    
    $leadSolarGain = json_decode($leadJSON);
    
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note                   = "Preparing designs and quote for client";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 1,
            "Name" => "General",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url                     = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl                    = curl_init();
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " . strlen($leadSolarGainJSONDecode),
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead
    ));
    
    $lead = json_decode(curl_exec($curl));
    curl_close($curl);
}

array_push($job_strings, 'custom_readmailsam_matt');

function custom_readmailsam_matt(){
    $hostname = '{webmail.solargain.com.au:993/imap/ssl/novalidate-cert}INBOX'; 
    $username = 'matthew.wright@solargain.com.au';
    $password = 'MW@pure733';

    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Exchange: ' . imap_last_error());
    
    /* grab emails */
    $date = new DateTime("-1 days");
    $date_before_1_days =$date->format('d F Y'); 
    
    $emails = imap_search($inbox,'SINCE "'.$date_before_1_days.'" UNSEEN');
    
    /* if emails are returned, cycle through each... */
    if($emails) {
        
        /* begin output var */
        $output = '';
        
        /* put the newest emails on top */
        rsort($emails);
        
        /* for every email... */
        foreach($emails as $email_number) {
            
            $overview  = imap_fetch_overview($inbox,$email_number,0);
            $subject   =  htmlentities($overview[0]->subject);
            
            if(stripos($subject,"Assigned (L#") !== false){
                $file_content = strip_tags(imap_body($inbox, $email_number));
                $result_array_info_email = get_information_from_email_matt($file_content);
                if(strpos($result_array_info_email['lead_status'],'Assigned') !== false || strpos($result_array_info_email['lead_status'],'New') !== false){
                    $result_array_address = get_address_matt($result_array_info_email['lead_address']);
                    $result_array_name = get_name_matt($result_array_info_email['lead_fullname']);

                    //check exist lead
                    if(!check_exist_lead_matt($result_array_name['first_name'],$result_array_name['last_name'],$result_array_info_email['lead_email'])){
                        if($result_array_name['first_name'] != '' || $result_array_name['last_name'] != '') {
                            $lead = new Lead();
                            $lead->primary_address_street     = $result_array_address['street'] ? $result_array_address['street'] : '';
                            $lead->primary_address_postalcode = $result_array_address['postcode'] ? $result_array_address['postcode'] : "";
                            $lead->primary_address_city       = $result_array_address['city'] ? $result_array_address['city'] : "";
                            $lead->primary_address_state      = $result_array_address['state'] ? $result_array_address['state'] : "";
                            $lead->first_name  = $result_array_name['first_name'] ? $result_array_name['first_name'] : "";
                            $lead->last_name = $result_array_name['last_name'] ? $result_array_name['last_name'] : "";
                            $lead->email1 = $result_array_info_email['lead_email'] ? trim($result_array_info_email['lead_email']) : "";
                            $lead->phone_work  = $result_array_info_email['lead_phone'] ? $result_array_info_email['lead_phone'] : "";
                            $lead->phone_mobile  = $result_array_info_email['lead_mobile_phone'] ? $result_array_info_email['lead_mobile_phone'] : "";
                            
                            $lead->assigned_user_id = "8d159972-b7ea-8cf9-c9d2-56958d05485e"; //defaul matthew 
                            $lead->lead_source                = "Solargain";
                            $lead->lead_source_co_c = 'Solargain';
                            $lead->description = $result_array_info_email['lead_note'];
                            $lead->solargain_lead_number_c = $result_array_info_email["lead_number"];
                            $lead->status = "Assigned";
                            $lead->save();   
                            if ($lead->primary_address_street == "" || $lead->primary_address_city == "" || $lead->primary_address_state == "" || $lead->primary_address_postalcode == "") {
                                                        
                                // Send email 
                                if ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") {
                                    $from_address = "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";
                                } else{
                                    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
                                }
                                
                                $temp_request = array(
                                    "module" => "Emails",
                                    "action" => "send",
                                    "record" => "",
                                    "type" => "out",
                                    "send" => 1,
                                    "inbound_email_id" => ($lead->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa") ? "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" : "8dab4c79-32d8-0a26-f471-59f1c4e037cf",
                                    "emails_email_templates_name" => "Solargain / NO ADDRESS / Solar PV / QCells 300 / Fronius MAIN",
                                    "emails_email_templates_idb" => "58230a56-82cd-03ae-1d60-59eec0f8582d",
                                    "parent_type" => "Leads",
                                    "parent_name" => $lead->first_name ." ". $lead->last_name,
                                    "parent_id" => $lead->id,
                                    "from_addr" => $from_address,
                                    "to_addrs_names" => $lead->email1, //"binhdigipro@gmail.com",//$lead->email1,
                                    "cc_addrs_names" => "info@pure-electric.com.au",
                                    "bcc_addrs_names" =>  "binh.nguyen@pure-electric.com.au",
                                    "is_only_plain_text" => false
                                );

                                $emailBean    = new Email();
                                $emailBean    = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
                                
                                $emailBean->save();
                                
                                $emailBean->saved_attachments = custom_handleMultipleFileAttachments_matt($temp_request, $emailBean);
                                //$GLOBALS['log']->debug('--------------------------------------------> LEAD Number Run HERE <--------------------------------------------\n' .$solargain_lead_number );
                                // parse and replace bean variables
                                $emailBean                    = custom_replaceEmailVariables_matt($emailBean, $temp_request);
                                
                                // Signature
                                $matthew_id                   = "8d159972-b7ea-8cf9-c9d2-56958d05485e";
                                $paul_id                      = "61e04d4b-86ef-00f2-c669-579eb1bb58fa";
                                $user                         = new User();
                                $user->retrieve($matthew_id);

                                if ($random_number <= 50) {
                                    $emailSignatureId = "4857e8ef-cff5-cefd-9e0b-59f075f61bbe";
                                } else{
                                    $emailSignatureId = "6157d3e7-7183-8197-ed43-59f03cf9ba9d"; // Matthew signature
                                }
                                
                                $signature = $user->getSignature($emailSignatureId);
                                $emailBean->description .= $signature["signature"];
                                $emailBean->description_html .= $signature["signature_html"];
                                $emailBean->description .= $live_chat_text;
                                $emailBean->description_html .= $live_chat_text;
                                $emailBean->save();
                                
                                $lead->email_send_id_c     = $emailBean->id;
                                $lead->email_send_status_c = 'pending';
                                $lead->save();
                                
                                if ($temp_request["parent_type"] == "Leads") {
                                    $leadID = $temp_request["parent_id"];
                                }      
                            } 
                            $record_id = $lead->id;
                            custom_sendDesignRequestToAdmin_matt($record_id);
                            echo "<br><a href='/index.php?module=Leads&action=EditView&record=$lead->id' target='_blank' >$lead->id</a>";
                        }
                    }else{
                        send_email_alert_lead_exist_matt($result_array_info_email['lead_email'],$result_array_info_email['to']);
                    }
                }
            }else{
                $message   = getBody_matt($email_number,$inbox);
                $structure = imap_fetchstructure($inbox,$email_number);
                $header    = imap_headerinfo($inbox, $email_number);
                $attachments = array();
                $file_attachments = getAttachments_matt($inbox,$email_number,$structure);

                require_once('include/SugarPHPMailer.php');
                $overview  = imap_fetch_overview($inbox,$email_number,0);
                $subject = $overview[0]->subject;
                $from = $overview[0]->from;
                $fromEmail = $header->from[0]->mailbox . "@" . $header->from[0]->host;

                preg_match('/<body[^>]*>.*?/',$message, $out);
                $first_div = "";
                if(isset($out[0])){
                    $first_div  = $out[0];
                }
                $add_to_body = '';
                foreach($header->to as $email_to){
                  $add_to_body .= $email_to->personal."(".$email_to->mailbox."@".$email_to->host."), ";
                }
                $add_cc_body = '';
                foreach($header->cc as $email_cc){
                  $add_cc_body .= $email_cc->personal."(".$email_cc->mailbox."@".$email_cc->host."), ";
                }
                $add_cc_body =  trim($add_cc_body,', ');
                $message  = preg_replace("/<body[^>]*>.*?/", $first_div.'<br/><strong>Sendder Address :</strong> '.$fromEmail.'<br/><br/><strong>To Address :</strong> '.$add_to_body.'<br/><br/><strong>Cc Address:</strong> '.$add_cc_body.'<br/><br/>', $message, 1);
  
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer();
                $mail->setMailerForSystem();
                $mail->From = "matthew.wright@solargain.com.au";
                $mail->FromName = 'Matthew Wright Solargain';
                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;;
                $mail->prepForOutbound();
                
                $mail->AddAddress('info@pure-electric.com.au');
                if(!empty($file_attachments)){
                    foreach($file_attachments as $file){
                        $mail->AddAttachment($file);
                    }
                }
                $mail->Send();
                foreach($file_attachments as $file){
                    unlink($file);
                }
            }
        
        }
    } 
} 

//Thienpb code
    function get_mime_type_matt($structure)
    {
        $primaryMimetype = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];
        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    function get_part_matt($imap, $uid, $mimetype, $structure = false, $partNumber = false)
    {
        if (!$structure) {
            $structure = imap_fetchstructure($imap,$uid);
        }
        if ($structure) {
            if ($mimetype == get_mime_type_matt($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber);
                switch ($structure->encoding) {
                    case 3:
                        return imap_base64($text);
                    case 4:
                        return imap_qprint($text);
                    default:
                        return $text;
                }
            }
            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = get_part_matt($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    function getBody_matt($uid, $imap)
    {
        $body = get_part_matt($imap, $uid, "TEXT/HTML");
        if ($body == "") {
            $body = get_part_matt($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }

    function getAttachments_matt($inbox,$email_number,$structure){
        if(isset($structure->parts) && count($structure->parts)) {
          for($i = 0; $i < count($structure->parts); $i++) {
            $attachments[$i] = array(
              'is_attachment' => false,
              'filename' => '',
              'name' => '',
              'attachment' => ''
            );
            
            if($structure->parts[$i]->ifdparameters) {
              foreach($structure->parts[$i]->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['filename'] = $object->value;
                }
              }
            }
      
            if($structure->parts[$i]->ifparameters) {
              foreach($structure->parts[$i]->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                $attachments[$i]['is_attachment'] = true;
                $attachments[$i]['name'] = $object->value;
                }
              }
            }
            
            if($attachments[$i]['is_attachment']) {
              $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
              if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
              } elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
              }
            }
          } 
        } 
        
        $file_attachments = array();
        if(count($attachments) != 0){
          $path = "custom/include/SugarFields/Fields/Multiupload/server/php/files/attachments/";
          if(!file_exists ( $path )) {
            set_time_limit ( 0 );
            mkdir($path);
          }
          foreach($attachments as $at){
            if($at[is_attachment]==1){
              $fname = $at['filename'];
              $file_attachments[] = $path."$fname";
              $fp = fopen( $path."$fname","w");
              fwrite($fp, $at['attachment']);
              fclose($fp);
            }
          }
        }
      
        return $file_attachments;
    }
//end

function get_information_from_email_matt ($file_content) {

    preg_match("/Subject:(.*?)\r\n/s" , $file_content, $match_body);
    $subject = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/To:(.*?)\r\n/s" , $file_content, $match_body);
    $to = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Lead #(\d+)/s" , $file_content, $match_body);
    $lead_number = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Status:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_status = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/System Interested In:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_System_Interested_In =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/Next Action Date:(.*?)\r\n/s" , $file_content, $match_body);
    $lead_next_action_date =  (isset($match_body[1])) ? $match_body[1] : ''; ;
    
    //get info custom details
    preg_match_all("/Customer Details(.*?)Site Details/s" , $file_content, $match_body);
    $content_custom_details = (isset($match_body[1])) ? $match_body[1][0] : ''; 
    
    preg_match("/\r\n(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_fullname =  (isset($match_body[1])) ? $match_body[1] : ''; 

    preg_match("/p:(.*?)\r\n/s", $content_custom_details, $match_body);
    $lead_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/m:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_mobile_phone =  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    preg_match("/e:(.*?)\r\n/s" , $content_custom_details, $match_body);
    $lead_email = (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get info Site Details
    preg_match("/Site Details(.*?)Notes/s" , $file_content, $match_body);
    $lead_address=  (isset($match_body[1])) ? $match_body[1] : ''; 
    
    //get note 
    preg_match("/Notes(.*?)This e-mail is private and confidential./s" , $file_content, $match_body);
    $lead_note=  (isset($match_body[1])) ? $match_body[1] : ''; 

    $result = array (
        'subject' => $subject,
        'to' => $to,
        'lead_fullname' => $lead_fullname,
        'lead_number' =>$lead_number ,
        'lead_status' => $lead_status,
        'lead_System_Interested_In' => $lead_System_Interested_In,
        'lead_next_action_date' => $lead_next_action_date,
        'lead_phone' =>$lead_phone,
        'lead_mobile_phone' => $lead_mobile_phone,
        'lead_email' => $lead_email,
        'lead_address' => $lead_address,
        'lead_note' => $lead_note
    );

    return $result;
}

function get_address_matt($string_address){
    if($string_address != ''){
    
        //case 1: get address from string_address.com.au
        $address = rawurlencode(utf8_encode($string_address));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/autocomplete?q='.$address.'&key=MFQ93YPXGETJ7DKWLN48&format=json&max=7&wv=3.18.8&session=&highlight=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
        $headers[] = 'Accept: application/json, text/javascript';
        $headers[] = 'Accept-Language: en-US,en;q=0.5';
        $headers[] = 'Referer: https://addressfinder.com.au/';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Origin: https://addressfinder.com.au';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Te: Trailers';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close ($ch);

        $address_arr =  json_decode($result);

        if($address_arr){
            $address_id = $address_arr->completions[0]->id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.addressfinder.io/api/au/address/info?format=json&key=MFQ93YPXGETJ7DKWLN48&wv=3.18.8&session=&paf=1&gps=1&id='.$address_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            $headers = array();
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
            $headers[] = 'Accept: application/json, text/javascript';
            $headers[] = 'Accept-Language: en-US,en;q=0.5';
            $headers[] = 'Referer: https://addressfinder.com.au/';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Origin: https://addressfinder.com.au';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Pragma: no-cache';
            $headers[] = 'Cache-Control: no-cache';
            $headers[] = 'Te: Trailers';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close ($ch);

            $address_parse = json_decode($result);

            $address_elements = array(
                'full_address'  => (isset($address_parse->full_address)) ? $address_parse->full_address : '' ,
                'street'        => (isset($address_parse->address_line_1)) ? $address_parse->address_line_1.' '.$address_parse->address_line_2 : '',
                'city'          => (isset($address_parse->locality_name)) ? $address_parse->locality_name : '',
                'state'         => (isset($address_parse->state_territory)) ? $address_parse->state_territory : '',
                'postcode'      => (isset($address_parse->postcode)) ? $address_parse->postcode : '',
                'lat'           => (isset($address_parse->latitude)) ? $address_parse->latitude : '',
                'long'          => (isset($address_parse->longitude)) ? $address_parse->longitude : '',
            );
        }
        //case 2: get address by regex
        if(empty($address_elements['street']) && empty($address_elements['city']) && empty($address_elements['state']) && empty($address_elements['post_code'])){
            $address_pattern = '/(\n([a-zA-Z-\s])+( )|)+(VIC|SA|NSW|ACT|TAS|WA|QLD|NT)+( )+(\d{4})/s';
            $out_address     = array();
            preg_match_all($address_pattern, $string_address, $out_address, PREG_PATTERN_ORDER);
            $out_address[0][0] = trim($out_address[0][0]);
            if (isset($out_address[0][0]) && $out_address[0][0] != "") {
                $address_element = explode(" ", $out_address[0][0]);
                if (count($address_element) >= 3) {
                    $state            = $address_element[count($address_element) - 2];
                    $post_code        = $address_element[count($address_element) - 1];
                    $city           = str_replace($state . " " . $post_code, "", $out_address[0][0]);
                    $city           = str_replace("-", "", $city);
                    $exploded_address = explode("\n", $out_address[0][0]);
                    if (count($exploded_address) == 2)
                        $client_primary_address = $exploded_address[0];
                }
                
                if (count($address_element) == 2) {
                    $state     = $address_element[0];
                    $post_code = $address_element[1];
                    $city    = "";
                    $address1 = explode(',',$out_address);
                }
                $address_elements = array(
                    'full_address'  => (isset($out_address1)) ? $out_address : '' ,
                    'street'        => (isset($address_street)) ? $address_street : '',
                    'city'          => (isset($city)) ? $city : '',
                    'state'         => (isset($state)) ? $state : '',
                    'postcode'      => (isset($post_code)) ? $post_code : '',
                    'lat'           => '',
                    'long'          => '',
                );
            }
            
        }   
    }
    
    return $address_elements ;
}

function get_name_matt($string_name){
    if($string_name != '') {
        $name_array = explode(",", trim($string_name));
        $last_name = trim($name_array[0]);
        $first_name = trim($name_array[1]);
        $full_name =  $last_name .' ' .$first_name; 
    }
    $result = array(
        'fullname' => (isset($full_name)) ? $full_name : '',
        'last_name' => (isset($last_name)) ? $last_name : '',
        'first_name' => (isset($first_name)) ? $first_name : '',
    );
    return $result;
}

function check_exist_lead_matt($first_name,$last_name,$lead_email){
    $email = trim($lead_email);
    $db = DBManagerFactory::getInstance();
    $query = "SELECT leads.id as id ,email_addresses.email_address as email  FROM leads 
    JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
    JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE email_addresses.email_address = '$email'" ;
    $ret = $db->query($query);
    $is_exsit = false;
    if($ret->num_rows > 0){
        $is_exsit = true;
    }else{
        $is_exsit = false;
    }
    return $is_exsit;
}

function send_email_alert_lead_exist_matt($customer_lead_email,$fromto){
    //check email is exists
    $lead_email = trim($customer_lead_email);
    if($lead_email != '') {
        if(strpos($fromto,'matthew') !== false){
            $address = "matthew.wright@pure-electric.com.au";
        }else if(strpos($fromto,'paul') !== false){
            $address = "paul.szuster@pure-electric.com.au";
        }else{
            $address = "matthew.wright@pure-electric.com.au";
        }
        $db = DBManagerFactory::getInstance();
        $query_lead = "SELECT leads.id
                    FROM leads
                    JOIN email_addr_bean_rel ON leads.id = email_addr_bean_rel.bean_id
                    JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
                    WHERE email_addresses.email_address = '".$lead_email."' AND leads.deleted = 0";
        $result = $db->query($query_lead);
        if($result->num_rows > 0){
            
            require_once('include/SugarPHPMailer.php');
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = $defaults['email'];
            $mail->FromName = $defaults['name'];
            $mail->IsHTML(true);
    
            $mail->Subject = 'Automatically add Lead';
    
            $mail->Body = 'Can not automatically add lead because email '.$lead_email.' is exists! ';
    
            $mail->prepForOutbound();
            //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
            $mail->AddAddress($address);
            $sent = $mail->Send();
            die();
        }  
    }

}


function custom_getLatLong_matt($address)
{
    
    $array = array();
    $geo   = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
    
    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude  = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $array     = array(
            'lat' => $latitude,
            'lng' => $longitude
        );
    }
    
    return $array;
}

function custom_sendDesignRequestToAdmin_matt($record_id)
{

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = custom_getLatLong_matt($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "Leads",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $lead->id,
        "from_addr" => $from_address,
        "to_addrs_names" => 'nguyenphudung93.dn@gmail.com',//"admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        // "cc_addrs_names" => "info@pure-electric.com.au",
        //  "bcc_addrs_names" => "binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    $emailBean->save();
    $emailBean->saved_attachments = custom_handleMultipleFileAttachments_matt($temp_request, $emailBean);

    // parse and replace bean variables
    $emailBean = custom_replaceEmailVariables_matt($emailBean, $temp_request);
    $emailBean->save();

    custom_updateDesignForSolargainLead_matt($record_id);
    $lead->email_send_design_request_id_c = $emailBean->id;
    $lead->email_send_design_status_c = 'pending';
    $lead->save();

    return $emailBean;
}

function custom_handleMultipleFileAttachments_matt($request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if ( /*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
        
        require_once('modules/Notes/Note.php');
        $note        = new Note();
        $where       = "notes.parent_id = '" . $request["emails_email_templates_idb"] . "' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template
        
        $attachments = array();
        
        $attachments = array_merge($attachments, $attach_list);
        
        foreach ($attachments as $noteId) {
            
            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id           = create_guid();
            $noteTemplate->new_with_id  = true; 
            $noteTemplate->parent_id    = $email->id;
            $noteTemplate->parent_type  = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();
            
            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
    }
}

function custom_replaceEmailVariables_matt(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();
    
    $focusName = $request['parent_type'];
    $focus     = BeanFactory::getBean($focusName, $request['parent_id']);
    
    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate           = BeanFactory::getBean('EmailTemplates', isset($request['emails_email_templates_idb']) ? $request['emails_email_templates_idb'] : null);
    $email->name             = $emailTemplate->subject;
    $email->description_html = $emailTemplate->body_html;
    $email->description      = $emailTemplate->body;
    $templateData            = $emailTemplate->parse_email_template(array(
        'subject' => $email->name,
        'body_html' => $email->description_html,
        'body' => $email->description
    ), $focusName, $focus, $macro_nv);
    
    $email->name             = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description      = $templateData['body'];
    
    return $email;
}

function custom_updateDesignForSolargainLead_matt($leadID)
{
    $lead = new Lead();
    $lead->retrieve($leadID);
    if (!$lead->solargain_lead_number_c) {
        return;
    }
    
    $solargainLead = $lead->solargain_lead_number_c;
    
    $username = "matthew.wright";
    $password = "MW@pure733";
    
    // Get full json response for Leads
    
    $url = "https://crm.solargain.com.au/APIv2/leads/" . $solargainLead;
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead,
        "Cache-Control: max-age=0"
    ));
    
    $leadJSON = curl_exec($curl);
    curl_close($curl);
    
    $leadSolarGain = json_decode($leadJSON);
    
    // building Note
    // Logged in user name: Email From name: and email template title 
    $note                   = "Preparing designs and quote for client";
    $leadSolarGain->Notes[] = array(
        "ID" => 0,
        "Type" => array(
            "ID" => 1,
            "Name" => "General",
            "RequiresComment" => true
        ),
        "Text" => $note
    );
    
    $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
    //echo $leadSolarGainJSONDecode;die();
    // Save back lead 
    $url                     = "https://crm.solargain.com.au/APIv2/leads/";
    //set the url, number of POST vars, POST data
    $curl                    = curl_init();
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Host: crm.solargain.com.au",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        "Content-Type: application/json",
        "Accept: application/json, text/plain, */*",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding:   gzip, deflate, br",
        "Connection: keep-alive",
        "Content-Length: " . strlen($leadSolarGainJSONDecode),
        "Authorization: Basic " . base64_encode($username . ":" . $password),
        "Referer: https://crm.solargain.com.au/lead/edit/" . $solargainLead
    ));
    
    $lead = json_decode(curl_exec($curl));
    curl_close($curl);
}


array_push($job_strings, 'custom_readxero');

function send_alert_email($alert_content, $bank_ref, $quote){
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->Subject = 'Payment alert ' . $bank_ref;
    $mail->Body = $alert_content;
        

    $mail->prepForOutbound();
    //$mail->AddAddress('accounts@pure-electric.com.au');
    $user = new User();
    $user->retrieve($quote->assigned_user_id);
    //email1
    $mail->AddAddress($user->email1);
    $mail->AddCC('info@pure-electric.com.au'); 
    $mail->AddCC('binhdigipro@gmail.com');
    $sent = $mail->Send();
}

function custom_readxero() 
{
    $url = 'https://suitecrm.pure-electric.com.au/custom/include/xero/private.php?bankstatements=1';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);

    curl_setopt($curl, CURLOPT_HTTPGET, true);

    $result = curl_exec($curl);
    curl_close($curl);

	$oldResultTxtFile = fopen("custom/include/xero/old_result.txt", "r");
    $oldResult = fgets($oldResultTxtFile);
    fclose($oldResultTxtFile);

    $oldResultTxtFile = fopen("custom/include/xero/old_result.txt", "w");
    fwrite($oldResultTxtFile, $result);
    fclose($oldResultTxtFile);
    
    $bankStatements = json_decode($result)->Row;

    class Payment
    {
        public $payment_amount;
        public $payment_description;
        public $payment_date;
        public $payment_brankref;
    }

    $txtFile = fopen("custom/include/xero/bankstatements_lasttime.txt", "r");
    $lastTime = fgets($txtFile);
    $time = strtotime($lastTime);
    fclose($txtFile);

    $statements = array();
    foreach ($bankStatements as $bankStatament)
    {
        if ($bankStatament->Cells->Cell[5]->Value > 0 &&
            strtotime($bankStatament->Cells->Cell[0]->Value))
        {
            $payment = new Payment();
            $payment->payment_amount = $bankStatament->Cells->Cell[5]->Value;
            $payment->payment_date = $bankStatament->Cells->Cell[0]->Value;
            $payment->payment_brankref = $bankStatament->Cells->Cell[2]->Value;
            $payment->payment_description = $bankStatament->Cells->Cell[1]->Value;
            if ($payment->payment_description == null)
            {
                $payment->payment_description = "";
            }

            array_push($statements, $payment);
            $lastTime = $payment->payment_date;
        }
    }
	
    


    //Old bank statement
    $oldBankStatements = json_decode($oldResult)->Row;

    $oldStatements = array();
    foreach ($oldBankStatements as $bankStatament)
    {
        if ($bankStatament->Cells->Cell[5]->Value > 0 &&
            strtotime($bankStatament->Cells->Cell[0]->Value))// just get receive payment
        {
            $payment = new Payment();
            $payment->payment_amount = $bankStatament->Cells->Cell[5]->Value;
            $payment->payment_date = $bankStatament->Cells->Cell[0]->Value;
            $payment->payment_brankref = $bankStatament->Cells->Cell[2]->Value;
            $payment->payment_description = $bankStatament->Cells->Cell[1]->Value;
            if ($payment->payment_description == null)
            {
                $payment->payment_description = "";
            }

            array_push($oldStatements, $payment);
        }
    }
    //End Old bank statement

    
    $txtFile = fopen("custom/include/xero/bankstatements_lasttime.txt", "w");
    fwrite($txtFile, $lastTime);
    fclose($txtFile);

    require_once('modules/AOS_Quotes/AOS_Quotes.php');
    require_once('modules/AOS_Invoices/AOS_Invoices.php');
    require_once('modules/AOS_Products_Quotes/AOS_Products_Quotes.php');

    $quoteBean = BeanFactory::getBean('AOS_Quotes');
    $invoiceBean = BeanFactory::getBean('AOS_Invoices');
    $contactBean = BeanFactory::getBean('Contacts');

    $db = DBManagerFactory::getInstance();
    $sql = "SELECT qt_cstm.id_c, qt_cstm.bank_ref_c, qt.number FROM aos_quotes AS qt 
            INNER JOIN aos_quotes_cstm AS qt_cstm ON qt_cstm.id_c = qt.id 
            WHERE ( qt_cstm.bank_ref_c != '') ";
            
            //AND (qt.invoice_status != 'Invoiced') "; //OR  qt_cstm.bank_ref_c IS NULL ) // OR qt.invoice_status IS NULL 
    // We also need improvement just resolve the quote for today

    $ret = $db->query($sql);

    class QuoteInfo
    {
        public $id;
        public $bank_ref;
        public $number;
    }

    $quotes = array();
    while ($row = $db->fetchByAssoc($ret))
    {
        $quoteInfo = new QuoteInfo();
        $quoteInfo->id = $row['id_c'];
        $quoteInfo->bank_ref = $row['bank_ref_c'];
        $quoteInfo->number = $row['number'];
        
        array_push($quotes, $quoteInfo);
    }
    require_once('include/SugarPHPMailer.php');
    foreach ($statements as $payment)
    {
    	
        //if in old array
        $in_array = false;
        foreach($oldStatements  as $oldPayment){
            if($oldPayment->payment_amount == $payment->payment_amount && 
                //$oldPayment->payment_description == $payment->payment_description && 
                //$oldPayment->payment_date == $payment->payment_date && 
                $oldPayment->payment_brankref == $payment->payment_brankref ){
                    $in_array = true;
                }
        }

        if($in_array) continue;

        $foundMatch = false; // Variable for found
        $invoiceId = '';

        $payment_bankref_fulltext = strtolower(str_replace(" ", "", $payment->payment_brankref));
        
        // If match to invoice 
        // 1. Add more payment to invoice if bankref not exist
        // 2. Send email alert
        // 3. Send email with attachment.
        preg_match('!inv\d+!', strtolower($payment_bankref_fulltext), $invoice_matchs);
        if(!count($invoice_matchs)) {
            preg_match('!invoice\d+!', $payment_bankref_fulltext, $invoice_matchs);
        }
        if(!count($invoice_matchs)) {
            preg_match('!invoice \d+!', strtolower($payment_bankref_fulltext), $invoice_matchs);
        }
        // Redundant
        /*if(!count($invoice_matchs)) {
            preg_match('!inv\d+!', strtolower($payment_bankref_fulltext), $invoice_matchs);
        }
        */
        if(!count($invoice_matchs)) {
            preg_match('!inv \d+!', strtolower($payment_bankref_fulltext), $invoice_matchs);
        }
        if(count($invoice_matchs)){
            $invoice_match = current($invoice_matchs);
            $invoice_match = trim(str_replace(array("inv"), "", str_replace(array("invoice"),"", strtolower($invoice_match))));
            if(is_numeric($invoice_match)){
                $invoice = new AOS_Invoices();
                $invoice->retrieve($invoice_match);
                if($invoice->id != ""){
                    //1. Add more payment to invoice if bankref not exist
                    $payments = json_decode(rawurldecode($invoice->payments_c));
                    if ($payments == null)
                    {
                        $payments = array();
                    }

                    $found_payment = false;
                    foreach ($payments as $paymentInfo)
                    {
                        if ($paymentInfo->payment_amount == $payment->payment_amount &&
                            $paymentInfo->payment_date == $payment->payment_date &&
                            $paymentInfo->payment_brankref == $payment->payment_brankref)
                        {
                            $found_payment = true;
                            // Do nothing
                        }
                    }

                    if ($found_payment !== true)
                    {
                        array_push($payments, $payment);
                        $invoice->payments_c = rawurlencode(json_encode($payments));
                        $invoice->status = "Deposit_Paid";
                        $invoice->save();
                    }
                    //2. Send email alert
                    // Send mail
                    $emailObj = new Email();
                    $defaults = $emailObj->getSystemDefaultEmail();
                    $mail = new SugarPHPMailer();
                    $mail->setMailerForSystem();
                    $mail->From = $defaults['email'];
                    $mail->FromName = $defaults['name'];
                    $mail->IsHTML(true);
                    $mail->Subject = 'Payment received ' . $payment->payment_brankref;

                    // send with invoice link
                    $invoiceLink = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=DetailView&record=' . $invoice->id;
                    $mail->Body = '<div><a href="' . $invoiceLink . '">Invoice link</a>' .
                    '<br>Reference:   ' . $payment->payment_brankref .
                    '<br>Amount:      ' . $payment->payment_amount .
                    '<br>Date:        ' . $payment->payment_date .
                    '<br>Description: ' . $payment->payment_description .
                    '</div>';

                    $url = 'https://suitecrm.pure-electric.com.au/custom/include/xero/private.php?sendinvoice=1&record=' . $invoice->id;
                    $curl = curl_init();
                
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                
                    curl_setopt($curl, CURLOPT_HTTPGET, true);
                
                    $result = curl_exec($curl);
                    curl_close($curl);

                    preg_match('/name="record" value="(.*)"/', $result, $matches);
                    $emailID = $matches[1];

                    preg_match('/name="inbound_email_id" value="(.*)"/', $result, $matches);
                    $inboundEmailID = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";

                    //$from_address = rand (1, 100) < 70 ? "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;"
                    //                   : "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";

                    $from_address = "PureElectric Accounts &lt;accounts@pure-electric.com.au&gt;";

                    $request = array(
                        "module" => "Emails",
                        "action" => "send",
                        "record" => $emailID,
                        "type" => "out",
                        "send" => 1,
                        "inbound_email_id" => $inboundEmailID,
                        "emails_email_templates_name" => "",
                        "emails_email_templates_idb" => "",
                        "parent_type" => "",
                        "parent_name" => "",
                        "parent_id" => $invoice->id,
                        "from_addr" => $from_address,
                        "to_addrs_names" => "info@pure-electric.com.au",
                        "cc_addrs_names" => "binhdigipro@gmail.com",

                        "is_only_plain_text" => false,
                    );

                    $emailBean = new Email();
                    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $request);
                    $emailBean->save();

                    $inboundEmailAccount = new InboundEmail();
                    $inboundEmailAccount->retrieve($request['inbound_email_id']);

                    $emailBean->saved_attachments = handleMultipleFileAttachments($request, $emailBean);

                    $emailBean = replaceEmailVariables($emailBean, $request);

                    $draftEmailBean = new Email();
                    $draftEmailBean->retrieve($emailID);

                    $emailBean->name = $draftEmailBean->name;
                    $emailBean->description = $draftEmailBean->name->description;
                    $emailBean->description_html = $draftEmailBean->description_html;

                    if (true)//$emailBean->send())
                    {
                        $emailBean->status = 'sent';
                        $emailBean->save();
                    }

                

                    $mail->prepForOutbound();
                    $mail->AddAddress('accounts@pure-electric.com.au');
                    $mail->AddCC('info@pure-electric.com.au');
                    $mail->AddCC('binhdigipro@gmail.com');

                    $sent = $mail->Send();

                    if(!$found_payment){
                        global  $mod_strings;
                        $mod_strings['LBL_PDF_NAME'] = "Invoice";
                        $_REQUEST['task'] = 'emailpdf';
                        $_REQUEST['uid'] = $invoiceId;
                        $_REQUEST['module'] = "AOS_Invoices";
                        $_REQUEST['templateID'] = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
                        $_REQUEST['auto_send'] = 1;
                        require_once('modules/AOS_PDF_Templates/generatePdf.php');

                    }
                    
                    return;
                }
            }
        }

        foreach ($quotes as $quoteInfo)
        {

            // trong truong hop nguoi dung dua vao reference tuy y
            if (strpos($payment_bankref_fulltext, strtolower($quoteInfo->bank_ref)) === false)
            {   
                // This is some exception that payment ref not match but still have meaning
                // Payment ref look like : quote123
                // match in human pattern
                $match_human_pattern = true;
                $quote_number_text_post = strpos($payment_bankref_fulltext, "quote".$quoteInfo->number) ;
                if($quote_number_text_post === false) $match_human_pattern = false;
                if(is_numeric($payment_bankref_fulltext[$quote_number_text_post+strlen("quote".$quoteInfo->number)])){
                    $match_human_pattern = false;
                }
                
                $quote_number_text_post2 = strpos($payment_bankref_fulltext, "quote #".$quoteInfo->number) ;
                if($quote_number_text_post2 === false) $match_human_pattern = false;
                if(is_numeric($payment_bankref_fulltext[$quote_number_text_post2+strlen("quote #".$quoteInfo->number)])){
                    $match_human_pattern = false;
                }
                if(!$match_human_pattern) continue;
            }

            //preg_match('/\d+'.$quoteInfo->bank_ref.'/', $payment_bankref_fulltext, $matches_fulltext);
            //if(!count($matches_fulltext)) continue;

            $quote = new AOS_Quotes();
            $quote->retrieve($quoteInfo->id);

            $number = $quote->number;
            if ($number == null)
            {
                continue;
            }
            // check in the case the quote number dont exist in reference => continue
            $quote_number_text_post3 = strpos($payment_bankref_fulltext, $number) ;
            if($quote_number_text_post3 === false) continue;
            if(is_numeric($payment_bankref_fulltext[$quote_number_text_post3+strlen($number)])) continue;

            $foundMatch = true;

            $invoices = $invoiceBean->get_full_list('', "aos_invoices.quote_number = '$number'");

            if (count($invoices) === 0) // so convert quote to invoice
            {
                // Need to check quote first
                /* 
                    1 - If there are NO pre install photos
                    2 - If there isn't at least 1x Switchboard photo
                    3 - If the "Plumber" field is empty
                    4 - If the "Electrician" field is empty
                    5 - Double check the "Old Tank Fuel" with the Question, "Are you definitely this Old Tank Fuel is correct?"
                */
                // If there are no install photo
                $folder = $quote->pre_install_photos_c;
                $folder = realpath(dirname(__FILE__) . '/../../../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$folder;
                $file_array = scandir($folder);
                $alert_content = "";
                $can_convert = true;
                $file_exist = false;
                if(count($file_array) >= 2)
                    foreach ($file_array as $file){
                        if(is_file($folder."/".$file)){
                            $file_exist = true;
                        }
                    }
                if(!$file_exist)
                {
                    $can_convert = false;
                    $alert_content .= "There are no image on quote! Please check the quote before converting <br>";
                } 
                $have_switchboard_photo = false;
                if (count($file_array) > 2) foreach ($file_array as $file){
                    if(strpos(strtolower($file), "switchboard") !== false ){
                        $have_switchboard_photo = true;
                    }
                }
                if(!$have_switchboard_photo){
                    $can_convert = false;
                    $alert_content .= "There are no switchboard photo! Please check the quote before converting <br>";
                }
                if(!$quote-account_id_c || $quote-account_id_c == ""){
                    $can_convert = false;
                    $alert_content .= "There are no Plumber! Please check the quote before converting <br>";
                }
                if(!$quote-account_id1_c || $quote-account_id1_c == ""){
                    $can_convert = false;
                    $alert_content .= "There are no Electrican! Please check the quote before converting <br>";
                }
                if($quote->old_tank_fuel_c){
                    $alert_content .= "Old tank field value is: ".$quote->old_tank_fuel_c.". Are you definitely this Old Tank Fuel is correct?<br>";
                }

                $alert_content .= ( "This is the quote that match <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=DetailView&record=".$quote->id."'>Quote" . $quote->number . "</a>");
                send_alert_email($alert_content, $payment->payment_brankref, $quote);
                
                $quote->invoice_status = 'Invoiced';
                $quote->save();

                //Setting Invoice Values
                //if($can_convert){
                    $invoice = new AOS_Invoices();
                    $rawRow = $quote->fetched_row;
                    $rawRow['id'] = '';

                    // Custom preinstall photo

                    $rawRow['installation_pictures_c'] = $rawRow['pre_install_photos_c'];
                    $rawRow['installation_notes_c'] = $rawRow['pre_install_notes_c'];

                    $rawRow['template_ddown_c'] = ' ';
                    $rawRow['quote_number'] = $rawRow['number'];
                    $rawRow['number'] = '';
                    $dt = explode(' ',$rawRow['date_entered']);
                    $rawRow['quote_date'] = $dt[0];
                    $rawRow['invoice_date'] = date('Y-m-d');
                    $rawRow['total_amt'] = format_number($rawRow['total_amt']);
                    $rawRow['discount_amount'] = format_number($rawRow['discount_amount']);
                    $rawRow['subtotal_amount'] = format_number($rawRow['subtotal_amount']);
                    $rawRow['tax_amount'] = format_number($rawRow['tax_amount']);
                    $rawRow['date_entered'] = '';
                    $rawRow['date_modified'] = '';
                    if($rawRow['shipping_amount'] != null)
                    {
                        $rawRow['shipping_amount'] = format_number($rawRow['shipping_amount']);
                    }
                    $rawRow['total_amount'] = format_number($rawRow['total_amount']);
                    $invoice->populateFromRow($rawRow);
                    $invoice->process_save_dates =false;
                    $invoice->status = "Deposit_Paid";
                    $invoice->save();

                    //Setting invoice quote relationship
                    require_once('modules/Relationships/Relationship.php');
                    $key = Relationship::retrieve_by_modules('AOS_Quotes', 'AOS_Invoices', $GLOBALS['db']);
                    if (!empty($key)) {
                        $quote->load_relationship($key);
                        $quote->$key->add($invoice->id);
                    }

                    //Setting Group Line Items
                    $sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
                    $result = $db->query($sql);
                    while ($row = $db->fetchByAssoc($result)) {
                        $row['id'] = '';
                        $row['parent_id'] = $invoice->id;
                        $row['parent_type'] = 'AOS_Invoices';
                        if($row['total_amt'] != null) $row['total_amt'] = format_number($row['total_amt']);
                        if($row['discount_amount'] != null) $row['discount_amount'] = format_number($row['discount_amount']);
                        if($row['subtotal_amount'] != null) $row['subtotal_amount'] = format_number($row['subtotal_amount']);
                        if($row['tax_amount'] != null) $row['tax_amount'] = format_number($row['tax_amount']);
                        if($row['subtotal_tax_amount'] != null) $row['subtotal_tax_amount'] = format_number($row['subtotal_tax_amount']);
                        if($row['total_amount'] != null) $row['total_amount'] = format_number($row['total_amount']);
                        $group_invoice = new AOS_Line_Item_Groups();
                        $group_invoice->populateFromRow($row);
                        $group_invoice->save();
                    }

                    //Setting Line Items
                    $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
                    $result = $db->query($sql);
                    while ($row = $db->fetchByAssoc($result)) {
                        $row['id'] = '';
                        $row['parent_id'] = $invoice->id;
                        $row['parent_type'] = 'AOS_Invoices';
                        if($row['product_cost_price'] != null)
                        {
                            $row['product_cost_price'] = format_number($row['product_cost_price']);
                        }
                        $row['product_list_price'] = format_number($row['product_list_price']);
                        if($row['product_discount'] != null)
                        {
                            $row['product_discount'] = format_number($row['product_discount']);
                            $row['product_discount_amount'] = format_number($row['product_discount_amount']);
                        }
                        $row['product_unit_price'] = format_number($row['product_unit_price']);
                        $row['vat_amt'] = format_number($row['vat_amt']);
                        $row['product_total_price'] = format_number($row['product_total_price']);
                        $row['product_qty'] = format_number($row['product_qty']);
                        $prod_invoice = new AOS_Products_Quotes();
                        $prod_invoice->populateFromRow($row);
                        $prod_invoice->save();
                        $invoiceId = $invoice->id;
                    }
                    
                //}
            }
            else
            {
                $invoiceId = $invoices[0]->id;
            }

            $invoice = new AOS_Invoices();
            $invoice->retrieve($invoiceId);

            $payments = json_decode(rawurldecode($invoice->payments_c));
            if ($payments == null)
            {
                $payments = array();
            }

            $found = false;
            foreach ($payments as $paymentInfo)
            {
                if ($paymentInfo->payment_amount == $payment->payment_amount &&
                    $paymentInfo->payment_date == $payment->payment_date &&
                    $paymentInfo->payment_brankref == $payment->payment_brankref)
                {
                    $found = true;
                    break;
                }
            }

            if ($found !== true)
            {
                array_push($payments, $payment);
                $invoice->payments_c = rawurlencode(json_encode($payments));
                $invoice->status = "Deposit_Paid";
                $invoice->save();
            }
        }

        // Send mail
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);
        $mail->Subject = 'Payment received ' . $payment->payment_brankref;

        if(!$foundMatch)
        {
            // send with suggested links
            $links = '';

            preg_match('/From:(.*) REF:/', $payment->payment_brankref, $matches);

            $accountName = count($matches) > 0 ? $matches[1] : '';
            $words = explode(' ', $accountName);

            $lastWord = '';
            foreach ($words as $word)
            {
                if (strlen($word) > 3)
                {
                    $lastWord = $word;
                }
            }

            if ($lastWord != '')
            {
                $sql = "SELECT id FROM accounts WHERE name LIKE '%$lastWord%'";
            
                $ret = $db->query($sql);
                while ($row = $db->fetchByAssoc($ret))
                {
                    $accountId = $row['id'];
                    $sql = "SELECT id,name FROM aos_quotes WHERE billing_account_id = '" . $accountId . "'";
                    $retQuotes = $db->query($sql);
                    /* public $payment_amount;
                        public $payment_description;
                        public $payment_date;
                        public $payment_brankref;
                    */
                    while ($rowQuote = $db->fetchByAssoc($retQuotes))
                    {
                        $quoteLink = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=DetailView&record=' . $rowQuote['id'];
                        $convertoInvoiceLink = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=converToInvoice&record=' . $rowQuote['id'].
                        "&payment_amount=".$payment->payment_amount.
                        "&payment_brankref=".$payment->payment_brankref.
                        "&payment_date=".$payment->payment_date.
                        "&payment_description=".$payment->payment_description
                        ;
                        $links = $links . '<br><a href="' . $quoteLink . '">Quote: ' . $rowQuote['name'] . '</a> &nbsp;<a href="' . $convertoInvoiceLink . '"> Convert To Invoice</a> ';
                    }
    
                    $sql = "SELECT id,name FROM aos_invoices WHERE billing_account_id = '" . $accountId . "'";
                    $retInvoices = $db->query($sql);
                    while ($rowInvoice = $db->fetchByAssoc($retInvoices))
                    {
                        $invoiceLink = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=DetailView&record=' . $rowInvoice['id'];
                        $addPaymentToInvoice = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=DetailView&record=' . $rowInvoice['id'].'#detailpanel_9';
                        $links = $links . '<br><a href="' . $invoiceLink . '">Invoice: ' . $rowInvoice['name'] . '</a> &nbsp;<a href="' . $addPaymentToInvoice . '"> Add Payment To Invoice</a>';
                    }
                }    
            }

            if ($links != '')
            {
                $links = '<br><div>Suggested Quotes, Invoices with Name match found' . $links . '</div>';
            }
            else
            {
                $links = '<br><div>Please update manually.</div>';
            }

            $mail->Body = '<div>No match found!' .
            '<br>Reference:   ' . $payment->payment_brankref .
            '<br>Amount:      ' . $payment->payment_amount .
            '<br>Date:        ' . $payment->payment_date .
            '<br>Description: ' . $payment->payment_description .
            '</div>' . $links;
        }
        else
        {
            // send with invoice link
            $invoiceLink = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceId;
            $mail->Body = '<div><a href="' . $invoiceLink . '">Invoice link</a>' .
            '<br>Reference:   ' . $payment->payment_brankref .
            '<br>Amount:      ' . $payment->payment_amount .
            '<br>Date:        ' . $payment->payment_date .
            '<br>Description: ' . $payment->payment_description .
            '</div>';

            $url = 'https://suitecrm.pure-electric.com.au/custom/include/xero/private.php?sendinvoice=1&record=' . $invoiceId;
            $curl = curl_init();
        
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        
            $result = curl_exec($curl);
            curl_close($curl);

            preg_match('/name="record" value="(.*)"/', $result, $matches);
            $emailID = $matches[1];

            preg_match('/name="inbound_email_id" value="(.*)"/', $result, $matches);
            $inboundEmailID = $matches[1];

           // $from_address = rand (1, 100) < 70 ? "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;"
           //                     : "Paul Szuster - PureElectric &lt;paul.szuster@pure-electric.com.au&gt;";

           $from_address = "PureElectric Accounts &lt;accounts@pure-electric.com.au&gt;";
            
            $request = array(
                "module" => "Emails",
                "action" => "send",
                "record" => $emailID,
                "type" => "out",
                "send" => 1,
                "inbound_email_id" => $inboundEmailID,
                "emails_email_templates_name" => "",
                "emails_email_templates_idb" => "",
                "parent_type" => "",
                "parent_name" => "",
                "parent_id" => $invoiceId,
                "from_addr" => $from_address,
                "to_addrs_names" => "info@pure-electric.com.au",
                "cc_addrs_names" => "binhdigipro@gmail.com",

                "is_only_plain_text" => false,
            );

            $emailBean = new Email();
            $emailBean = $emailBean->populateBeanFromRequest($emailBean, $request);
            $emailBean->save();

            $inboundEmailAccount = new InboundEmail();
            $inboundEmailAccount->retrieve($request['inbound_email_id']);

            $emailBean->saved_attachments = handleMultipleFileAttachments($request, $emailBean);

            $emailBean = replaceEmailVariables($emailBean, $request);

            $draftEmailBean = new Email();
            $draftEmailBean->retrieve($emailID);

            $emailBean->name = $draftEmailBean->name;
            $emailBean->description = $draftEmailBean->name->description;
            $emailBean->description_html = $draftEmailBean->description_html;

            if (true )//$emailBean->send())
            {
                $emailBean->status = 'sent';
                $emailBean->save();
            }

        }

        $mail->prepForOutbound();
        $mail->AddAddress('accounts@pure-electric.com.au');
        $mail->AddCC('info@pure-electric.com.au');
        $mail->AddCC('binhdigipro@gmail.com');

        $sent = $mail->Send();

        if($foundMatch){
            global  $mod_strings;
            $mod_strings['LBL_PDF_NAME'] = "Invoice";
            $_REQUEST['task'] = 'emailpdf';
            $_REQUEST['uid'] = $invoiceId;
            $_REQUEST['module'] = "AOS_Invoices";
            $_REQUEST['templateID'] = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
            $_REQUEST['auto_send'] = 1;
            
            require_once('modules/AOS_PDF_Templates/generatePdf.php');
        }
    }
}

 

array_push($job_strings, 'custom_receivemail');

function custom_receivemail()
{
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $fields['user_name'] = 'admin';
    $fields['username_password'] = 'pureandtrue2020*';
    $fields['module'] = 'Users';
    $fields['action'] = 'Authenticate';

    $url = 'https://suitecrm.pure-electric.com.au';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $result = curl_exec($curl);
    // 2 Calling CURL
    $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=customReceiveMail";

    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HTTPGET, true);
    $curl_response = curl_exec($curl);

    curl_close($curl);
    $GLOBALS['log']->info("CURL Response $curl_response");
    return true;
}

array_push($job_strings, 'custom_send_message_gateway');

function custom_send_message_gateway()
{
    $message_dir1 = '/var/www/message';

    if(check_exist_json_sms("/message")){
        autosendmail_notification("1");
    }

    $sms_body1 = "This is test message from message 2";
    $client_number1 = '+61421616733';
    exec("cd ".$message_dir1."; php send-message.php sms ".$client_number1.' "'.$sms_body1.'"');
    
    $message_dir2 = '/var/www/message2';

    if(check_exist_json_sms("/message2")){
        autosendmail_notification("1");
    }

    $sms_body2 = "This is test message from message 1";
    $client_number2 = '+61490942067';
    exec("cd ".$message_dir2."; php send-message.php sms ".$client_number2.' "'.$sms_body2.'"');
    die;
}
function check_exist_json_sms($folder){
    $check = false;
    $folder_message = '/var/www/suitecrm'.'/..'.$folder.'/'.'messages/';
    $allFiles = scandir($folder_message);
    
    if($allFiles !== false){
        $files = array_diff($allFiles, array('.', '..', 'sent_backup'));
        if(count($files) > 0){
            foreach ($files as $file){
                if(!is_dir($folder_message.$file) && strpos(strtolower($file),'json') !== false){
                    $file_timestamp = filemtime($folder_message.$file);
                }else{
                    continue;
                }
                if(!isset($date_last)) {
                    $date_last = $file_timestamp;
                }else if($file_timestamp < $date_last){
                    $date_last = $file_timestamp;
                }
            }
            if($date_last >= strtotime("-10 minutes")){
                $check = false;
            }else{
                $check = true;
            }
        }else{
            $check = false;
        }
    }else{
       $check = false;
    }
    return $check;
}
function autosendmail_notification($gateway){
    
    //config mail
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "PureElectric";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Warning SMS GATEWAY ".$gateway." DOWN";
    $mail->Body = "Warning SMS GATEWAY ".$gateway." DOWN";

    $mail->AddAddress("info@pure-electric.com.au");

    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
}

 
array_push($job_strings, 'custom_sendmailsgdesignrequest');

function custom_sendmailsgdesignrequest(){
    $db = DBManagerFactory::getInstance();
    $query = $sql = "SELECT * FROM leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c 
                    WHERE  email_send_design_request_id_c !='' AND email_send_design_status_c = 'pending' AND deleted != 1
                    AND leads.primary_address_street IS NOT NULL 
                    AND leads.primary_address_city IS NOT NULL 
                    AND leads.primary_address_state IS NOT NULL 
                    AND leads.primary_address_postalcode IS NOT NULL 
                    AND leads_cstm.time_completed_job_c IS NULL
                    ";
    $ret = $db->query($sql);

    while($row = $db->fetchByAssoc($ret)){
        $record_id = $row['id'];
        $primary_address_street = $row['primary_address_street'];
        $primary_address_city = $row['primary_address_city'];
        $primary_address_state = $row['primary_address_state'];
        $primary_address_postalcode = $row['primary_address_postalcode'];

        $lead = new Lead();
        $lead-> retrieve($row['id']);
         //check time
        if($primary_address_street == "" || $primary_address_city == "" || $primary_address_state == "" || $primary_address_postalcode == ""){continue;}else{

            global $timedate;
            $time_zone = $timedate->getInstance()->userTimezone();
            date_default_timezone_set($time_zone);

            $date_created = strtotime($row['date_entered']);
            $timeAgo = time() - $date_created;
            $timeAgo = $timeAgo / 3600;

            if($timeAgo > 24){
                
                $emailBean = sendmailsg_sendDesignRequestToAdmin($record_id);
                $emailBean->mailbox_id = '6f2dae95-c53d-179f-2bc2-59f1c63b5e7e';
                if ($emailBean->send()) {
                    $emailBean->status = 'sent';
                    $emailBean->save();
                    $lead->email_send_design_status_c = 'sent';
                    //dung code- update time sent email Request Design
                    date_default_timezone_set('Australia/Melbourne');
                    $dateAUS = date('Y-m-d H:i:s', time());
                    $lead->time_request_design_c = $dateAUS;
                    $lead->save();
                    //dung code- update status for quote
                    $quote_id =  $lead->create_solar_quote_num_c;
                    $quote =  new AOS_Quotes();
                    $quote->retrieve($quote_id);
                    if($quote->id != ''){
                        $quote->stage = 'Request_Designs';
                        $quote->save();
                    }
                    
                } else {
                    if ($emailBean->status !== 'draft') {
                        $emailBean->status = 'send_error';
                        $emailBean->save();
                    } else {
                        $emailBean->status = 'send_error';
                    }
                }        
            }else{
                continue;
            }
        }
    }
    return;
}

function sendmailsg_getLatLong($address) {
   
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

function sendmailsg_handleMultipleFileAttachments( $request, $email)
{
    ///////////////////////////////////////////////////////////////////////////
    ////    ATTACHMENTS FROM TEMPLATES
    // to preserve individual email integrity, we must dupe Notes and associated files
    // for each outbound email - good for integrity, bad for filespace
    if (/*isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])*/ true) {
        $noteArray = array();
    
        //require_once('modules/Notes/Note.php');
        $note = new Note();
        $where = "notes.parent_id = '".$request["emails_email_templates_idb"]."' ";
        $attach_list = $note->get_full_list("", $where, true); //Get all Notes entries associated with email template

        $attachments = array();

        $attachments = array_merge($attachments, $attach_list);

        foreach ($attachments as $noteId) {

            $noteTemplate = new Note();
            $noteTemplate->retrieve($noteId->id);
            $noteTemplate->id = create_guid();
            $noteTemplate->new_with_id = true; // duplicating the note with files
            //$noteTemplate->parent_id = $this->id;
            //$noteTemplate->parent_type = $this->module_dir;
            $noteTemplate->parent_id = $email->id;
            $noteTemplate->parent_type = $email->module_dir;
            $noteTemplate->date_entered = '';
            $noteTemplate->save();

            $noteFile = new UploadFile();
            $noteFile->duplicate_file($noteId->id, $noteTemplate->id, $noteTemplate->filename);
            $noteArray[] = $noteTemplate;
        }
        return $noteArray;
        //$email->attachments = array_merge($email->attachments, $noteArray);
    }
}
function sendmailsg_replaceEmailVariables(Email $email, $request)
{
    // request validation before replace bean variables
    $macro_nv = array();

    $focusName = $request['parent_type'];
    $focus = BeanFactory::getBean($focusName, $request['parent_id']);

    /**
     * @var EmailTemplate $emailTemplate
     */
    $emailTemplate = BeanFactory::getBean(
        'EmailTemplates',
        isset($request['emails_email_templates_idb']) ?
            $request['emails_email_templates_idb'] :
            null
    );
    $email->name = $emailTemplate->subject;
    $body_html_tpl = $emailTemplate->body_html;
    $body_tpl = $emailTemplate->body;

    //custom body
    $full_name = $request['parent_name'];
    $body_html_tpl = str_replace('$first_name $last_name',trim($full_name),$body_html_tpl);

    $email_1 = $focus->email1;
    $body_html_tpl = str_replace('$email1',trim($email_1),$body_html_tpl);

    $record_id_rq = $request['parent_id'];
    $body_html_tpl = str_replace('$record_id',trim($record_id_rq),$body_html_tpl);

    $quote_number = $request['quote_number'];
    $body_html_tpl = str_replace('$quote_number',trim($quote_number),$body_html_tpl);

    $lat_long_rq = $request['lat_long'];
    $body_html_tpl = str_replace('$lat,$lng',$lat_long_rq['lat'].','.$lat_long_rq['lng'],$body_html_tpl);

    $address_rq = $request['address'];
    $body_html_tpl = str_replace('$address',trim($address_rq),$body_html_tpl);

    $address_arr = explode(',',$address_rq);

    $body_html_tpl = str_replace('$primary_address_street',trim($address_arr[0]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_city',trim($address_arr[1]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_state',trim($address_arr[2]),$body_html_tpl);
    $body_html_tpl = str_replace('$primary_address_postalcode',trim($address_arr[3]),$body_html_tpl);

    $body_html_tpl = str_replace('$description',$request['description'],$body_html_tpl);
    //dung code- convert url correct
    $body_html_tpl = str_replace('index.php?action=EditView','https://suitecrm.pure-electric.com.au/index.php?action=EditView',$body_html_tpl);
    $body_html_tpl = str_replace('index.php?entryPoint','https://suitecrm.pure-electric.com.au/index.php?entryPoint',$body_html_tpl);

    //end
    $email->description_html = $body_html_tpl;
    $email->description = $body_tpl;

    $templateData = $emailTemplate->parse_email_template(
        array(
            'subject' => $email->name,
            'body_html' => $email->description_html,
            'body' => $email->description,
        ),
        $focusName,
        $focus,
        $macro_nv
    );

    $email->name = $templateData['subject'];
    $email->description_html = $templateData['body_html'];
    $email->description = $templateData['body'];

    return $email;
}

function sendmailsg_sendDesignRequestToAdmin($record_id){

    $lead = new Lead();
    $lead = $lead->retrieve($record_id);

    if($lead->id == ''){
        return;
    }

    $quote_id =  $lead->create_solar_quote_num_c;
    $quote =  new AOS_Quotes();
    $quote->retrieve($quote_id);

    $description =  $lead->description;

    $address     =  $lead->primary_address_street . ", " . 
                    $lead->primary_address_city   . ", " . 
                    $lead->primary_address_state  . ", " . 
                    $lead->primary_address_postalcode ;
    $lat_long = sendmailsg_getLatLong($address);

    $from_address = "Matthew Wright - PureElectric &lt;matthew.wright@pure-electric.com.au&gt;";
    $temp_request = array(
        "module" => "Emails",
        "action" => "send",
        "record" => "",
        "type" => "out",
        "send" => 1,
        "inbound_email_id" => "81e9e608-9534-1461-525a-59afe6167eaf",
        "emails_email_templates_name" => "Solar Design Request",
        "emails_email_templates_idb" => "4e3a5016-36d2-b85f-aa20-5b10b5756a16",
        //"emails_email_templates_idb" => "18a02801-a21c-c288-bcd7-5b10427edfc3",
        "parent_type" => "AOS_Quotes",
        "parent_name" => $lead->first_name ." ". $lead->last_name,
        "parent_id" => $quote_id,
        "quote_number" => $quote->number,
        "from_addr" => $from_address,
        "to_addrs_names" => "admin@pure-electric.com.au", //"binhdigipro@gmail.com",//$lead->email1,
        "cc_addrs_names" => "info@pure-electric.com.au",
        "bcc_addrs_names" =>"binh.nguyen@pure-electric.com.au",
        "is_only_plain_text" => false,
        "address" =>$address,
        "lat_long" =>$lat_long,
        "description" => $description,
      
        
    );
    $emailBean = new Email();
    $emailBean =  $emailBean->retrieve($lead->email_send_design_request_id_c);
    
    $emailBean = $emailBean->populateBeanFromRequest($emailBean, $temp_request);
    $inboundEmailAccount = new InboundEmail();
    $inboundEmailAccount->retrieve($temp_request['inbound_email_id']);
    //$emailBean->save();
    //$emailBean->saved_attachments = sendmailsg_handleMultipleFileAttachments($temp_request, $emailBean);
    
    // parse and replace bean variables
    $emailBean = sendmailsg_replaceEmailVariables($emailBean, $temp_request);
    $emailBean->save();
    return $emailBean;
}

 

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

 
array_push($job_strings, 'custom_sgquoteinfo');

function wrapperCRMSolargain($status,$username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $url = 'https://crm.solargain.com.au/APIv2/quotes/search';

    $param = array (
        'Page' => 1,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'PageSize' => 50,
        'Filters' => 
        array (
        0 => 
        array (
            'Field' => 
            array (
            'Category' => 'Quote',
            'Name' => 'Status',
            'Code' => 'STATUS',
            'Type' => 5,
            ),
            'Value' => $status,
            'Operation' => 'EQ',
        ),
        ),
    );

    $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Content-Length: ".strlen($paramJSONDecode),
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/quote/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );

    return $resultJSON;
}

function wrapperCRMSolargainOrders($status,$username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $url = 'https://crm.solargain.com.au/apiv2/orders/search';

    $param = array (
        'Page' => 1,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'PageSize' => 50,
        'Filters' => 
        array (
          0 => 
          array (
            'Field' => 
            array (
              'Category' => 'Order',
              'Name' => 'Status',
              'Code' => 'STATUS',
              'Type' => 5,
            ),
            'Value' => $status,
            'Operation' => 'EQ',
          ),
        ),
      );

    $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Content-Length: ".strlen($paramJSONDecode),
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/order/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );

    return $resultJSON;
}

function CRMSolargainPV_SERVICES_CASES($username,$password){

    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $url = 'https://crm.solargain.com.au/APIv2/servicecases/1/search';
    if($username == "matthew.wright"){
        $user_id = 475;
    }else{
        $user_id = 730;
    }
    $param = array (
        'Page' => 1,
        'PageSize' => 25,
        'Sort' => 'LASTUPDATED',
        'Descending' => false,
        'Filters' => 
        array (
          0 => 
          array (
            'Field' => 
            array (
              'Code' => 'ASSIGNEDUSER',
              'Name' => 'Assigned User',
              'Category' => 'Service Case',
              'Type' => 1,
            ),
            'Operation' => 'EQ',
            'Value' => $user_id,
            'Values' => NULL,
          ),
        ),
    );

    $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


    $curl = curl_init();
        
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
        
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Content-Length: ".strlen($paramJSONDecode),
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en",
                "Accept-Encoding: 	gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/order/",
            )
        );

    $resultJSON = json_decode(curl_exec($curl));
    curl_close ( $curl );

    return $resultJSON;
}

//thienpb code -- Upcoming Solargain Installs
function CRMSolargainOrderUpcomming($username,$password){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    // $username = "matthew.wright";
    // $password =  "MW@pure733";

    $param_order = array(
                'Page' => 1,
                'PageSize' => 25,
                'Sort' => 'LASTUPDATED',
                'Descending' => false,
                'Sort'=> "INSTDATE",
                'Filters' =>    
                array (
                    0 => array(
                        'Field' =>  array(
                            'Category' => 'Order',
                            'Name' => 'Install Date',
                            'Code' => 'INSTDATE',
                            'Type' => 0,
                        ),
                        'Operation' => 'GT',
                        'Value' => date('d/m/Y'),
                        'Values' => NULL,
                    ),
                    1 => array(
                        'Field' => array(
                        'Category' => 'Order',
                        'Name' => 'Install Date',
                        'Code' => 'INSTDATE',
                        'Type' => 0,
                        ),
                        'Value' => '[+14 Days]',
                        'Operation' => 'LT',
                    ),
                ),
            );
        
    $json_param_order = json_encode($param_order,JSON_UNESCAPED_SLASHES);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_param_order);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Content-Type: application/json";
    $headers[] = "Content-Length: ".strlen($json_param_order);
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: ". $_SERVER['HTTP_USER_AGENT'];
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/order/";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result_json_order = json_decode(curl_exec($ch));
    curl_close ( $curl );

    return ($result_json_order->Results);
}

//Quote is tesla
function CRMSolargainQuoteTesla($username,$password){
    date_default_timezone_set('Australia/Sydney');
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1'); 

    $param_order = array(
                'Page' => 1,
                'PageSize' => 25,
                'Sort' => 'LASTUPDATED',
                'Descending' => false,
                'Sort'=> "INSTDATE",
                'Filters' =>    
                array (
                    0 => array(
                        'Field' =>  array(
                            'Category' => 'Components',
                            'Name' => 'Manufacturer',
                            'Code' => 'MANUFACTURER',
                            'Type' => 17,
                        ),
                        'Operation' => 'SELECTED',
                        'Value' => 46,
                    ),
                ),
            );
        
    $json_param_order = json_encode($param_order,JSON_UNESCAPED_SLASHES);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_param_order);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Origin: https://crm.solargain.com.au";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Content-Type: application/json";
    $headers[] = "Content-Length: ".strlen($json_param_order);
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Cache-Control: no-cache";
    $headers[] = "User-Agent: ". $_SERVER['HTTP_USER_AGENT'];
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Referer: https://crm.solargain.com.au/order/";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result_json_order = json_decode(curl_exec($ch));
    curl_close ( $curl );

    return ($result_json_order->Results);
}

//thienpb code -- new logic use for Matthew Wright SG account and Paul Szuster SG account
function generate_email_content(){
    //create array id quote tesla
    $quoteJSON_MT = CRMSolargainQuoteTesla('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainQuoteTesla('paul.szuster@solargain.com.au','Baited@42');
    $quoteJSON = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
    $aray_quote_tesla_number = [];
    foreach ($data_json as $key => $value) {
        $aray_quote_tesla_number[] = $value->ID;
    }

    $status_arr = array("PENDING_APPROVAL","DESIGN_REJECTED","SITE_INSPECTION_COMPLETED","SITE_INSPECTION_BOOKED","REQUIRES_SITE_INSPECTION","OPTION_ACCEPTED","SOLARVIC_APPROVED","SOLARVIC_STARTED","SOLARVIC_UPLOADED");
    $html_content = "";
    $quoteJSON = "";
    for($i = 0; $i < count($status_arr); $i++){
        $quoteJSON_MT = wrapperCRMSolargain($status_arr[$i],'matthew.wright','MW@pure733');
        $quoteJSON_PS = wrapperCRMSolargain($status_arr[$i],'paul.szuster@solargain.com.au','Baited@42');
        $quoteJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);
    
        $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";
    
        if(count($quoteJSON)>0){
            $html_content .= '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;" style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                    <td style="border: 1px solid black;"  style="border: 1px solid black;" width="13%"><strong>Link</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Product Type</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                    <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($quoteJSON as $res){
                $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->QuotedByUser->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->Status_Description;
                }
                $date_LastUpDated = $res->LastUpdated;
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $quoted_by_user = $res->QuotedByUser->Name;
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days '  ;
                }
                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                }
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }

                $prduct_type = '';
                if(in_array($res->ID,$aray_quote_tesla_number)){
                    $prduct_type = 'Tesla';
                }else{
                    $prduct_type = 'Solar';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">QUOTE#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$prduct_type."</td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No Quote</h4>";
        }
    }

    // Get Orders Infor 
    $status_arr = array("NETWORK_APPROVAL_SUBMITTED","NETWORK_APPROVAL_APPROVED","SALES_ORDER_SENT","RECEIVED","APPROVALS_RECEIVED","INSTALLATION_PENDING","INSTALLATION_DATE_CONFIRMED","SYSTEM_INSTALLED","QUOTE_ACCEPTED");
    for($i = 0; $i < count($status_arr); $i++){
        $quoteJSON_MT = wrapperCRMSolargainOrders($status_arr[$i],'matthew.wright','MW@pure733');
        $quoteJSON_PS = wrapperCRMSolargainOrders($status_arr[$i],'paul.szuster@solargain.com.au','Baited@42');
        $orderJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);
    
        $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";
    
        if(count($orderJSON)>0){
            $html_content .= 
            '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Install Date :</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Install Time :</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($orderJSON as $res){
                $link = "https://crm.solargain.com.au/order/edit/".$res->ID;
                //https://crm.solargain.com.au/order/edit/29194
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->AssignedUser->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->PVStatus;
                }
               
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $date_LastUpDated = $res->LastUpdated;
                $quoted_by_user = $res->AssignedUser->Name;
                if( $quoted_by_user == ''){
                    $quoted_by_user = $res->ExternalSalesPerson->Name;
                }
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days ';
                }
                if(isset($res->InstallDate) && $res->InstallDate != ""){
                    $time_install_date = new DateTime($res->InstallDate);
                } else {
                    $time_install_date = false;
                }

                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                };
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">ORDER#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".($time_install_date?$time_install_date->format('l ,d-M-Y'):"Didn't Set") ."</td>
                    <td style='border: 1px solid black;' >".($time_install_date?$time_install_date->format('h:i A'):"Didn't Set" ) ."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No ORDER</h4>";
        }
    }

    //get PV_SERVICES_CASES
    $quoteJSON_MT = CRMSolargainPV_SERVICES_CASES('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainPV_SERVICES_CASES('paul.szuster@solargain.com.au','Baited@42');
    $quoteJSON = array_unique(array_merge($quoteJSON_MT->Results,$quoteJSON_PS->Results),SORT_REGULAR);

    $html_content .= "<div><h3>PV SERVICES CASES</h3></div>";

    if(count($quoteJSON)>0){
        $html_content .= 
        '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
            <tr>
            <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
            </tr>';
    foreach($quoteJSON as $res){
        $link = "https://crm.solargain.com.au/servicecase/edit/".$res->ID;
        if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->AssignedUser->Name) == 'Paul Szuster'){
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
                <td style='border: 1px solid black;' >-----</td>
            </tr>";
        }
        $name = $res->Customer->Name;
        $status = $res->Status->Description;
        $date_LastUpDated = $res->LastUpdated;
        $state_customer = $res->Customer->Address->State;
        $quoted_by_user = $res->AssignedUser->Name;
        if($date_LastUpDated == ''){
            $date_LastUpDated = 'NO';
        }else{
            $d1=new DateTime($date_LastUpDated); 
            $d2=new DateTime(); 
            $date_diff= $d2->diff($d1)->format('%a');
            $date_LastUpDated = $date_diff .' days ' ;
        }
        //link lead 
        $quote_number_SAM = $res->ID;
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c='$quote_number_SAM' OR 	solargain_quote_number_c='$quote_number_SAM' ";
        $ret = $db->query($sql);
        $link_quote_suite = '';
        $seek_install_date = '';
        while ($row = $db->fetchByAssoc($ret)) {
            $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
            $seek_install_date = $row['seek_install_date_c'];
        };
        if($seek_install_date != ''){
            $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
        }else{
            $seek_install_date = '--';
        }
        $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">PV Service Cases #".$res->ID."</a></td>
                <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                <td style='border: 1px solid black;' >".$seek_install_date."</td>
                <td style='border: 1px solid black;' >".$state_customer."</td>
                <td style='border: 1px solid black;' >".$name."</td>
                <td style='border: 1px solid black;' >".$status."</td>
                <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
            </tr>";
        
        }
        $html_content .= "</table>";
    }else{
        $html_content .= "<h4>No Quote</h4>";
    }
    
    //thienpb code -- Upcoming Solargain Installs 
    $quoteJSON_MT = CRMSolargainOrderUpcomming('matthew.wright','MW@pure733');
    $quoteJSON_PS = CRMSolargainOrderUpcomming('paul.szuster@solargain.com.au','Baited@42');
    $order_result_search = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
    $html_content .= "<div><h3>UPCOMING SOLARGAIN INSTALLS</h3></div>";

    if(count($order_result_search) > 0){
        $html_content .= 
        '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
            <tr>
            <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Install Date</strong></td>
            <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
            </tr>';
        foreach($order_result_search as $res){
            $link = "https://crm.solargain.com.au/order/edit/".$res->ID;
            if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->Quote->QuotedByUser->Name) == 'Paul Szuster'){
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                    <td style='border: 1px solid black;' >-----</td>
                </tr>";
            }
            $name = $res->Customer->Name;
            if($name == ''){
                $name = $res->Customer_Name;
            }
            $status = $res->PVStatus;

            $quoted_by_user = $res->Quote->QuotedByUser->Name;
            if($quoted_by_user == ''){
                $quoted_by_user = $res->ExternalSalesPerson->Name;
            }
            $install_date = date('d/m/Y',strtotime($res->InstallDate));
       
            $state_customer = $res->Customer->Address->State;
            if($state_customer == ''){
                $state_customer = $res->Customer_Address_State;
            }
             //link lead 
            $order_number_SAM = $res->ID;
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT id_c , installation_date_c FROM `aos_invoices_cstm` WHERE solargain_invoices_number_c='$order_number_SAM'";
            $ret = $db->query($sql);
            $link_invoice_suite = '';
            $seek_install_date = '';
            while ($row = $db->fetchByAssoc($ret)) {
                $link_invoice_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$row['id_c'];
                $seek_install_date = $row['installation_date_c'];
            };
            if($seek_install_date != ''){
                $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
            }else{
                $seek_install_date = '--';
            }
            $html_content .= 
            "<tr>
                <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">ORDER#".$res->ID."</a></td>
                <td style='border: 1px solid black;' ><a target='_blank' href=". $link_invoice_suite.">".(($link_invoice_suite != '')?'Invoice SuiteCRM' : '') ."</a></td>
                <td style='border: 1px solid black;' >".$state_customer."</td>
                <td style='border: 1px solid black;' >".$name."</td>
                <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                <td style='border: 1px solid black;' >".$install_date."</td>
                <td style='border: 1px solid black;' >".$status."</td>
            </tr>";
        }
        $html_content .= '</table>';
    }else{
        $html_content .= "<h4>No Upcoming Solargain Installs</h4>";
    }

    // Get Quote Tesla
        $quoteJSON_MT = CRMSolargainQuoteTesla('matthew.wright','MW@pure733');
        $quoteJSON_PS = CRMSolargainQuoteTesla('paul.szuster@solargain.com.au','Baited@42');
        $orderJSON = array_unique(array_merge($quoteJSON_MT,$quoteJSON_PS),SORT_REGULAR);
        $html_content .= "<div><h3>Tesla Quotes</h3></div>";
    
        if(count($orderJSON)>0){
            $html_content .= 
            '<table style="
                border-collapse: collapse;
                border: 1px solid black;
                table-layout: auto;
                width: 100%;">
                <tr>
                <td style="border: 1px solid black;"  width="13%"><strong>Link</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Link Suite</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Seek Install Date</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>State</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Customer</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Status</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Quoted By User</strong></td>
                <td style="border: 1px solid black;"  width="13%"><strong>Duration</strong></td>
                </tr>';
            foreach($orderJSON as $res){
          
                $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
                
                if(trim($quoted_by_user )== 'Matthew Wright' && trim($res->ExternalSalesPerson->Name) == 'Paul Szuster'){
                    $html_content .= 
                    "<tr>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                        <td style='border: 1px solid black;' >-----</td>
                    </tr>";
                }
                $name = $res->Customer->Name;
                if($name == ''){
                    $name = $res->Customer_Name;
                }
                $status = $res->Status->Description;
                if($status == ''){
                    $status = $res->PVStatus;
                }
               
                $state_customer = $res->Customer->Address->State;
                if($state_customer == ''){
                    $state_customer = $res->Customer_Address_State;
                }
                $date_LastUpDated = $res->LastUpdated;
                $quoted_by_user = $res->ExternalSalesPerson->Name;
                if( $quoted_by_user == ''){
                    $quoted_by_user = $res->CreatedBy->Name;
                }
                if($date_LastUpDated == ''){
                    $date_LastUpDated = 'NO';
                }else{
                    $d1=new DateTime($date_LastUpDated); 
                    $d2=new DateTime(); 
                    $date_diff= $d2->diff($d1)->format('%a');
                    $date_LastUpDated = $date_diff .' days ';
                }
                if(isset($res->InstallDate) && $res->InstallDate != ""){
                    $time_install_date = new DateTime($res->InstallDate);
                } else {
                    $time_install_date = false;
                }

                //link lead 
                $quote_number_SAM = $res->ID;
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c , seek_install_date_c FROM `aos_quotes_cstm` WHERE solargain_tesla_quote_number_c = '$quote_number_SAM' ";
                $ret = $db->query($sql);
                $link_quote_suite = '';
                $seek_install_date = '';
                while ($row = $db->fetchByAssoc($ret)) {
                    $link_quote_suite = "https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$row['id_c'];
                    $seek_install_date = $row['seek_install_date_c'];
                };
                if($seek_install_date != ''){
                    $seek_install_date = date("d-m-Y", strtotime($seek_install_date));
                }else{
                    $seek_install_date = '--';
                }
                $html_content .= 
                "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=".$link.">Quote#".$res->ID."</a></td>
                    <td style='border: 1px solid black;' ><a target='_blank' href=". $link_quote_suite.">".(($link_quote_suite != '')?'Quote SuiteCRM' : '') ."</a></td>
                    <td style='border: 1px solid black;' >".$seek_install_date."</td>
                    <td style='border: 1px solid black;' >".$state_customer."</td>
                    <td style='border: 1px solid black;' >".$name."</td>
                    <td style='border: 1px solid black;' >".$status."</td>
                    <td style='border: 1px solid black;' >".$quoted_by_user."</td>
                    <td style='border: 1px solid black;' >".$date_LastUpDated."</td>
                </tr>";
            }
            $html_content .= "</table>";
        }else{
            $html_content .= "<h4>No ORDER</h4>";
        }
    

    return $html_content;
}

function custom_sgquoteinfo(){

    $html_content = generate_email_content();

    require_once('include/SugarPHPMailer.php');
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "PureElectric";
    $mail->IsHTML(true);

    $mail->Subject = 'SOLARGAIN STATUS DAILY EMAIL';

    $mail->Body = $html_content;

    $mail->prepForOutbound();
    //$mail->AddAddress('binhdigipro@gmail.com');
    $mail->AddAddress('info@pure-electric.com.au');
    //$mail->AddCC('info@pure-electric.com.au');
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');

    $sent = $mail->Send();
}

    array_push($job_strings, 'custom_sgquoteinfo_site_inspections');
    function wrapperCRMSolargain_site_Inspections($status){

        date_default_timezone_set('Australia/Sydney');
        set_time_limit ( 0 );
        ini_set('memory_limit', '-1'); 

        $username = "matthew.wright";
        $password =  "MW@pure733";

        $url = 'https://crm.solargain.com.au/APIv2/quotes/search';

        $param = array (
            'Page' => 1,
            'Sort' => 'ID',
            'Descending' => true,
            'PageSize' => 50,
            'Filters' => 
            array (
            0 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Quoted By User',
                'Code' => 'QUOTEDBYUSER',
                'Type' => 1,
                ),
                'Value' => '475',
                'Operation' => 'EQ',
            ),
            1 => 
            array (
                'Field' => 
                array (
                'Category' => 'Quote',
                'Name' => 'Status',
                'Code' => 'STATUS',
                'Type' => 5,
                ),
                'Value' => $status,
                'Operation' => 'EQ',
            ),
            ),
        );

        $paramJSONDecode = json_encode($param,JSON_UNESCAPED_SLASHES);


        $curl = curl_init();
            
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $paramJSONDecode);
            
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl,CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Host: crm.solargain.com.au",
                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                    "Content-Type: application/json",
                    "Content-Length: ".strlen($paramJSONDecode),
                    "Accept: application/json, text/plain, */*",
                    "Accept-Language: en",
                    "Accept-Encoding: 	gzip, deflate, br",
                    "Connection: keep-alive",
                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                    "Referer: https://crm.solargain.com.au/quote/",
                )
            );

        $resultJSON = json_decode(curl_exec($curl));
        curl_close ( $curl );

        return $resultJSON;
    }

    function custom_sgquoteinfo_site_inspections(){
        $status_arr = array("SITE_INSPECTION_BOOKED","REQUIRES_SITE_INSPECTION");
        $html_content = "";
        for($i = 0; $i < count($status_arr); $i++){
            $quoteJSON = wrapperCRMSolargain_site_Inspections($status_arr[$i]);

            $html_content .= "<div><h3>".$status_arr[$i]."</h3></div>";

            if(count($quoteJSON->Results)>0){
            $html_content .= 
            '<table>
                <tr>
                <td width="30%"><strong>Link</strong></td>
                <td width="30%"><strong>Customer</strong></td>
                <td width="30%"><strong>Status</strong></td>
                <td width="30%"><strong>Duration</strong></td>
                </tr>';
            foreach($quoteJSON->Results as $res){
            $link = "https://crm.solargain.com.au/quote/edit/".$res->ID;
            $name = $res->Customer->Name;
            $status = $res->Status->Description;
            $date_LastUpDated = $res->LastUpdated;
            if($date_LastUpDated == ''){
                $date_LastUpDated = 'NO';
            }else{
                $d1=new DateTime($date_LastUpDated); 
                $d2=new DateTime(); 
                $date_diff= $d2->diff($d1);
                $date_LastUpDated = $date_diff->d .' days ' .$date_diff->h .' hours ' .$date_diff->i .' minutes';
            }
            $html_content .= 
            "<tr>
                <td><a href=".$link.">QUOTE#".$res->ID."</a></td>
                <td>".$name."</td>
                <td>".$status."</td>
                <td>".$date_LastUpDated."</td>
            </tr>";
            
            }
            $html_content .= 
            "</table>";
            }else{
                $html_content .= "<h4>No Quote</h4>";
            }
        }

        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);

        $mail->Subject = 'SOLARGAIN STATUS DAILY SITE INSPECTION';

        $mail->Body = $html_content;

        $mail->prepForOutbound();
        //$mail->AddAddress('binhdigipro@gmail.com');
        $mail->AddAddress('info@pure-electric.com.au');
        $mail->AddCC('tiarna.hodge@solargain.com.au');
        //$mail->AddCC('info@pure-electric.com.au');
        //$mail->AddAddress('thienpb89@gmail.com');

        $sent = $mail->Send();
    }


array_push($job_strings, 'custom_updateTestimonial');


function custom_updateTestimonial(){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://pure-electric.com.au/update_testimonials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    return $result;
}


    array_push($job_strings, 'custom_uploadfilecurrentdaytoaws');

    function custom_uploadfilecurrentdaytoaws(){

        date_default_timezone_set('Australia/Sydney');

        $AWS_ACCESS_KEY_ID = 'AKIAJG53TQTXLTGRNAVA';
        $AWS_SECRET_ACCESS_KEY = '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';
    
        $folder = dirname(__FILE__).'/../../../../include/SugarFields/Fields/Multiupload/server/php/files/';
        $file_array = scandir($folder);
        $file_array = array_diff($file_array, array('.', '..'));
        foreach($file_array as $file){
            $source_file =  $folder.$file;
            if(is_dir($source_file) && $file != 'attachments'){
                $modified = filemtime($source_file);
                if(strtotime('-25 hours') <= $modified){
                    $file_child_array = scandir($source_file);
                    $file_child_array = array_diff($file_child_array, array('.', '..'));
                    foreach($file_child_array as $file_child){
                        $source_child_file = $source_file.'/'.$file_child;
                        if(strtotime('-25 hours') <= filemtime($source_child_file)){
                            file_put_contents('logs_folder_files.txt', $source_child_file.PHP_EOL , FILE_APPEND | LOCK_EX);

                            //echo 'AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp '.$source_child_file.' s3://'.$GLOBALS['BUCKET_NAME'].'/'.$file.'/'.$file_child;
                            shell_exec('AWS_ACCESS_KEY_ID='.$AWS_ACCESS_KEY_ID.' AWS_SECRET_ACCESS_KEY='.$AWS_SECRET_ACCESS_KEY.' aws s3 cp /var/www/suitecrm/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$file.'/'.$file_child.' s3://files-bk/'.$file.'/'.$file_child);

                        }
                    }
                }
            }
        }
    }

?>