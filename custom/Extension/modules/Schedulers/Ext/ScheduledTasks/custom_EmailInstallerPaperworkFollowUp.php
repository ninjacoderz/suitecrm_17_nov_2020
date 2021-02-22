<?php 
array_push($job_strings, 'custom_EmailInstallerPaperworkFollowUp');

function custom_EmailInstallerPaperworkFollowUp(){

    date_default_timezone_set('UTC');
    $array_condition_status = ['STC_VEEC_Unpaid','STC_Unpaid','VEEC_Unpaid','Paid','Variation_Unpaid'];
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
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "Pure Electric Info";
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