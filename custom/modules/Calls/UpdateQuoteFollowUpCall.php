<?php
require_once('include/SugarPHPMailer.php');
date_default_timezone_set("Australia/Melbourne");
$quote_fb = new AOS_Quotes();
$quote_fb->retrieve(trim( $_REQUEST['quote_id']));
if( $quote_fb->id != ''){
    switch ($_REQUEST['feedback']) {
        case 'I_Need_More_Time':
            $feback = ' Customer Has Selected, "I Need More Time - Email Me In a Week" ';
            $next_action = strtotime("+7 day");
            $next_action = date('Y-m-d', $next_action); 
            I_Need_More_Time($quote_fb);
            break;
        case 'I_Have_More_Questions':
            $feback = 'Customer Has Selected, "I Have More Questions - Call Me When Possible"';
            $next_action = strtotime("+1 day");
            $next_action = date('Y-m-d', $next_action);
            I_Have_More_Questions($quote_fb); 
            break;   
        case 'Not_Proceeding_With_Quote':
            $feback = 'Customer Has Selected, "Not Proceeding With Quote - Thank You For The Quote"';
            $next_action = "";
            Not_Proceeding_With_Quote($quote_fb);
            break;                 
    }
    $internal_notes = new pe_internal_note();
    $internal_notes->type_inter_note_c = 'follow_up';
    $internal_notes->description = $feback;
    $internal_notes->save();
    $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
    $internal_notes->aos_quotes_pe_internal_note_1->add($quote_fb->id);

 
    $quote_fb->next_action_date_c = $next_action;
    $quote_fb->save();
    echo $quote_fb->billing_account;
}
function getProducType($quote_type_c) {
    switch ($quote_type_c) {
        case 'quote_type_sanden':
            $type = 'Sanden';
            break;
        case 'quote_type_solar':
            $type = 'Solar';
            break;   
        case 'quote_type_daikin':
            $type = 'Daikin';
            break; 
        case 'quote_type_methven':
            $type = 'MeThven';
            break;         
    }
    return $type;    
}
function I_Need_More_Time($quote_fb){
    $account =  new Account();
    $account->retrieve($quote_fb->billing_account_id);

    $productType = getProducType($quote_fb->quote_type_c);

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'michael.golden@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $account->name ."has feedbacked to Pure Electric";
    $email->Body = "<p>Hi ".$quote_fb->account_firstname_c.", </p>";
    $email->Body .= "<p>Thank you for advising you would like a reminder email in a weeks' time for your ".$productType." Quote #".$quote_fb->number.". We'll be in touch again in a week. Thank you for the opportunity to quote for your ".$productType." from Australia's #1 Five Star Rated ".$productType." specialist.</p>";
    $email->Body .= "<p>Any questions in the meantime please don't hesitate to email, SMS or call when convenient.</p>";

    $email->IsHTML(true);
    $email->AddAddress($account->email1);
    // $email->AddAddress('ngoanhtuan2510@gmail.com');
    $email->AddCC('info@pure-electric.com.au');  
    $email->AddCC('michael.golden@pure-electric.com.au');  
    $email->setMailerForSystem();  
    $email->Send();
    return ;
}
function I_Have_More_Questions($quote_fb){
    $account =  new Account();
    $account->retrieve($quote_fb->billing_account_id);

    $productType = getProducType($quote_fb->quote_type_c);

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'michael.golden@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $account->name ."has feedbacked to Pure Electric";
    $email->Body = "<p>Hi ".$quote_fb->account_firstname_c.", </p>";
    $email->Body .= "<p>Thank you for advising you would like a phone call. I'll organise a phone call asap to answer any questions you have regarding your ".$productType." Quote #".$quote_fb->number.".</p>";
    $email->Body .= "<p>Many thanks again.</p>";

    $email->IsHTML(true);
    $email->AddAddress($account->email1);
    // $email->AddAddress('ngoanhtuan2510@gmail.com');
    $email->AddCC('info@pure-electric.com.au');  
    $email->AddCC('michael.golden@pure-electric.com.au');  
    $email->setMailerForSystem();  
    $email->Send();
    return ;
}
function Not_Proceeding_With_Quote($quote_fb){
    $account =  new Account();
    $account->retrieve($quote_fb->billing_account_id);

    $productType = getProducType($quote_fb->quote_type_c);

    $email = new SugarPHPMailer();  
    $email->setMailerForSystem();  
    $email->From = 'michael.golden@pure-electric.com.au';   
    $email->FromName = 'Pure Electric';
    $email->Subject = $account->name ."has feedbacked to Pure Electric";
    $email->Body = "<p>Hi ".$quote_fb->account_firstname_c.", </p>";
    $email->Body .= "<p>Thank you for the opportunity to provide you a comprehensive ".$productType." Quote #".$quote_fb->number.".</p>";
    $email->Body .= "<p>If you do change your mind, or if your other chosen supplier mucks you around, we would be very happy to assist how we can.</p>";
    $email->Body .= "<p>Many thanks again.</p>";

    $email->IsHTML(true);
    $email->AddAddress($account->email1);
    // $email->AddAddress('ngoanhtuan2510@gmail.com');
    $email->AddCC('info@pure-electric.com.au');  
    $email->AddCC('michael.golden@pure-electric.com.au');  
    $email->setMailerForSystem();  
    $email->Send();
    return ;
}
?>