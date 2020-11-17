<?php 
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