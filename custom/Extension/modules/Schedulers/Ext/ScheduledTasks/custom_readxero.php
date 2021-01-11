<?php

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

                    $from_address = "Pure Electric Accounts &lt;accounts@pure-electric.com.au&gt;";

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

           $from_address = "Pure Electric Accounts &lt;accounts@pure-electric.com.au&gt;";
            
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
