<?php 
array_push($job_strings, 'custom_autoCreateInvoiceByOrderSam');

function custom_autoCreateInvoiceByOrderSam(){

        $quoteJSON_MT = GetJson_CRMSolargainOrders('matthew.wright','MW@pure733');
        $quoteJSON_PS = GetJson_CRMSolargainOrders('paul.szuster@solargain.com.au','S0larga1n$');
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
      $password = 'S0larga1n$';

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
      $title_inv = 'Solargain_' .$json_result->Customer->TradingName .'_' .$json_result->Install->Address->Locality.'_'.$json_result->Install->Address->State.'_Order #' .$sg_order_number;
  }else{
      $title_inv = 'Solargain_' .$json_result->Customer->Name .'_' .$json_result->Install->Address->Locality.'_'.$json_result->Install->Address->State.'_Order #' .$sg_order_number;
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
        $password = 'S0larga1n$';
  
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
    global $current_user;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "Pure Electric Info";
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