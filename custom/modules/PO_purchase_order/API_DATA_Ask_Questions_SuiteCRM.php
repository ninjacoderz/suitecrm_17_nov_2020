<?php
//ini_set('display_errors',1);
// Main DATA 
$data = $_POST['data'];
$return_id = $_POST['return_id'];
$return_module = $_POST['return_module'];
$path = $_POST['path'];
$action = $_POST['action'];
$installer = $_POST['installer'];
$data_return = [];
switch ($action) {
    case 'PUT':
        $data_return = PUT_Data_To_Invoice($return_id,$data,$installer);
        break;
    case 'GET':
        $data_return = GET_DATA_BY_ID($return_id,$installer);
        break;
    default:
        
        break;
}

echo json_encode($data_return);

function PUT_Data_To_Invoice($return_id,$data,$installer){

    $Invoice = new AOS_Invoices();
    $Invoice->retrieve($return_id);
    if($Invoice->id != '') {

        $bean_intenal_notes = new  pe_internal_note();
        $bean_intenal_notes->type_inter_note_c = 'general';
        $body = $data['title']['value'] .' --- ' .$data['body']['value'];
        $decription_internal_notes  = trim(strip_tags(html_entity_decode(str_replace("&nbsp;",'',$body),ENT_QUOTES)));
        $bean_intenal_notes->description =  $decription_internal_notes;
        $bean_intenal_notes->assigned_user_id = $Invoice->assigned_user_id;
        $bean_intenal_notes->save();
     
        $bean_intenal_notes->load_relationship('aos_invoices_pe_internal_note_1');
        $bean_intenal_notes->aos_invoices_pe_internal_note_1->add($Invoice->id);
        Send_Email_Notification($Invoice,$data,$installer);
    }
}


function Send_Email_Notification($Invoice,$data,$installer){
    if($Invoice->id == '') { return false;}
    $customer_name = $data['field_eq_first_name']['value'] .' ' .$data['field_eq_last_name']['value'];
    $Contact_installer = new Contact();
    $PO_purchase_order = new PO_purchase_order();
    switch ($installer) {
        case 'plumber':
            $Contact_installer->retrieve($Invoice->contact_id4_c);
            $PO_purchase_order->retrieve($Invoice->plumber_po_c);
            break;
        case 'electrical':
            $Contact_installer->retrieve($Invoice->contact_id_c);
            $PO_purchase_order->retrieve($Invoice->electrical_po_c);
            break;      
        default:
            # code...
            break;
    }
    $InvoiceID = $Invoice->id;
    $PO_ID = $PO_purchase_order->id;
    
    $user_saler = new User();
    $user_saler->retrieve($Invoice->assigned_user_id);

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  $customer_name.' - ' .$data['title']['value'];

    $style_td = 'padding-top: 5px; font-weight: bold;  text-align: left;border: 1px solid black;';

    $style_button  = 'color:#fff;font-family:Helvetica;font-size: 15px;margin:3px;line-height:100%;text-align:center;text-decoration:none;background-color:#428bca;border:1px solid #428bca;display:inline-block;font-weight:bold;padding-top: 10px;padding-right: 16px;padding-bottom: 10px;padding-left: 16px;border-radius:5px;';
    
    $InformationInvoice = 
    '<h2>Sanden Subsidy Form</h2>'
    .'<table style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;" style="
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 100%;">
        <tbody style="padding-top: 15px; padding-bottom:15px; width: 100%">
            <tr>
                <td style="'. $style_td .'">Invoice Name:</td>
                <td style="'. $style_td .'">'.$Invoice->name.'</td>
                <td style="'. $style_td .'">PO Name:</td>
                <td style="'. $style_td .'">'.$PO_purchase_order->name.'</td>
                <td style="'. $style_td .'"></td>
                <td style="'. $style_td .'"></td>
            </tr>
            <tr>
                <td style="'. $style_td .'">Name Installer:</td>
                <td style="'. $style_td .'">'.$customer_name.'</td>
                <td style="'. $style_td .'">Email Installer:</td>
                <td style="'. $style_td .'">'.$data['field_eq_email']['value'].'</td>
                <td style="'. $style_td .'">Phone Installer:</td>
                <td style="'. $style_td .'">'.$data['field_eq_phone_number']['value'].'</td>
            </tr>
        </tbody>
    </table>';
    $content_ask_question = '<div><strong>Title: </strong>'.$data['title']['value'] .'</div>';
   
    $content_ask_question .= '<strong>Message: </strong>' . $data['body']['value'];
    $mail->Body = '<div><p>Hi Team,</p><p>' 
        . $customer_name . ' submitted the Ask Questions Form</p></div>'
    .$InformationInvoice
    .'<br><div>'.$content_ask_question.'</div>'
    .'<br><div><a style="'.$style_button.'" target="_blank" href="https://pure-electric.com.au/node/'.$data['node_id']['value'].'">Link PE Ask Questions Form →</a>
     <a style="'.$style_button.'" target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$InvoiceID.'">Link CRM Invoice →</a>
     <a style="'.$style_button.'" target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?module=PO_purchase_order&action=EditView&record='.$PO_ID.'">Link CRM PO →</a>
     </div>';
    
    // $mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->AddAddress($user_saler->email1);
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();  
    $sent = $mail->send();

    // send sms 
    $body_sms = 'Hi '.$user_saler->first_name .', '
    . $customer_name .' submitted the Ask Questions Form. '
    . 'Title:' .$data['title']['value'] ;

    Send_SMS_Notication_for_Saler($body_sms, $user_saler);
}


function GET_DATA_BY_ID($return_id,$installer){
    $AOS_Invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['return_id']);
    $data_return = [];
    $Contact_installer = new Contact();
    $PO_purchase_order = new PO_purchase_order();
    switch ($installer) {
        case 'plumber':
            $Contact_installer->retrieve($AOS_Invoice->contact_id4_c);
            $PO_purchase_order->retrieve($AOS_Invoice->plumber_po_c);
            break;
        case 'electrical':
            $Contact_installer->retrieve($AOS_Invoice->contact_id_c);
            $PO_purchase_order->retrieve($AOS_Invoice->electrical_po_c);
            break;      
        default:
            # code...
            break;
    }
    $data_return = array(
        'email' => $Contact_installer->email1,
        'phone_number' => $Contact_installer->phone_mobile,
        'first_name' => $Contact_installer->first_name,
        'last_name' => $Contact_installer->last_name,
        'return_id' => $AOS_Invoice->id,
        'return_module' => 'AOS_Invoices',
        'po_name' => $PO_purchase_order->name
    );
    return $data_return;
} 

function Send_SMS_Notication_for_Saler($body,$user){
    $phone_assigned = $user->phone_mobile;
    if(empty($user->phone_mobile)) {return false;}
    global $sugar_config;
    $phone_assigned = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_assigned));
    $phone_assigned = preg_replace("/^61/", "+61", $phone_assigned);
  
    exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_assigned.' "'.$body.'"');
}

