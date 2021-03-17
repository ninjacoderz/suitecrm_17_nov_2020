<?php

array_push($job_strings, 'SendEmailReportInvoiceDaily');

function SendEmailReportInvoiceDaily(){
    //report Inv need to sent email for customer
    Report_InvNeedSendEmail();
}

function Report_InvNeedSendEmail()
{
    date_default_timezone_set('UTC');
    set_time_limit(0);

    $array_invoice_ID = [];

    $db = DBManagerFactory::getInstance();
    $date_end = date('Y-m-d',  strtotime('+1 days',time()));
    $date_start = date('Y-m-d',  strtotime('-31 days',time()));

    $query = "SELECT aos_invoices.id as id ,
        aos_invoices_cstm.installation_pictures_c as installation_pictures_c,
        aos_invoices_cstm.installation_date_c as installation_date_c
    FROM aos_invoices
    INNER JOIN aos_invoices_cstm ON aos_invoices_cstm.id_c = aos_invoices.id
    WHERE aos_invoices.deleted = 0
    AND (aos_invoices_cstm.installation_date_c  BETWEEN '$date_start' AND '$date_end' )";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        if ($row['id'] != '') {
            $result_condition_send = RIV_condition_create_email_paperwork($row['id']);
            if ($result_condition_send['get_elec'] || $result_condition_send['get_plum']) {
                $array_data = RIV_create_data_invoice($result_condition_send, $row['id']);
                $array_invoice_ID[$row['id']] = $array_data;
            }
        }
    }
    RIV_send_email_report_inv_follow_up($array_invoice_ID);
}

function RIV_check_exist_filename($id_folder, $string)
{
    $path   = realpath(dirname(__FILE__) . '/../../../../'). '/include/SugarFields/Fields/Multiupload/server/php/files/';
    $source = $path . $id_folder;
    $file_array = scandir($source);
    $file_array = array_diff($file_array, array('.', '..'));
    foreach ($file_array as $file) {
        if (strpos(strtolower($file), $string) !== false) {
            return true;
        }
    }
    return false;
}

function RIV_condition_create_email_paperwork($record_id)
{
    $array_return = array(
        'get_elec' => false,
        'get_plum' => false,
    );
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);

    if (!$focus->id) {
        return $array_return;
    }

    // plumber
    if ($focus->plumber_po_c != '' && RIV_check_exist_filename($focus->installation_pictures_c, 'pcoc')) {
        $array_return['get_plum'] = true;
    }

    // Electrical
    if ($focus->electrical_po_c != '' && RIV_check_exist_filename($focus->installation_pictures_c, 'ces')) {
        $array_return['get_elec'] = true;
    }
    return $array_return;
}

function RIV_create_data_invoice($array_data, $record_id)
{
    $macro_nv = array();
    $focusName = "AOS_Invoices";
    $focus = BeanFactory::getBean($focusName, $record_id);
    $array_data['id'] = $focus->id;
    $array_data['name'] = $focus->name;
    $array_data['number'] = $focus->number;
    $array_data['status'] = $focus->status;
    $array_data['total_amount'] = '$' . substr($focus->total_amount, 0, -4);
    $array_data['installation_date_c'] = $focus->installation_date_c;
    $array_data['assigned_user_name'] = $focus->assigned_user_name;
    return $array_data;
}

function RIV_send_email_report_inv_follow_up($data_inv)
{
    $body = RIV_table_content_report($data_inv);
    $today = date('d/m/Y', time());
    $subject = "<div><h1 'text-align:center;'>Pure-Electric Report Invoices need to checked and sent out to customer - Daily Report - Date " . $today . '</h1></div>';
    //config mail
    global $current_user;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "info@pure-electric.com.au";
    $mail->FromName = "Pure Electric Info";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = "Pure-Electric Report Invoices need to checked and sent out to customer - Daily Report - Date " . $today;
    $mail->Body = $subject . $body;
    //$mail->AddAddress("nguyenphudung93.dn@gmail.com");
    $mail->AddAddress("info@pure-electric.com.au");

    $mail->prepForOutbound();
    $mail->setMailerForSystem();
    $sent = $mail->send();
    echo $mail->Body;
}

function RIV_table_content_report($data_inv)
{

    $pe_domain_crm = 'https://suitecrm.pure-electric.com.au';
    if (count($data_inv) > 0) {
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
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="7%"><strong>Grand Total</strong></td>
                <td style="border: 1px solid black;"  style="border: 1px solid black;" width="10%"><strong>Assigned to</strong></td>
            </tr>';
        foreach ($data_inv as $res) {
            $link_pe = '';
            $link_email_edit = '';
            $link_html_xero = '';
            if ($res['id'] != '') {
                $link_pe = $pe_domain_crm . '/index.php?module=AOS_Invoices&action=EditView&record=' .$res['id'];
                $html_content .=
                    "<tr>
                    <td style='border: 1px solid black;' ><a target='_blank' href=" . $link_pe . ">Inv#" . $res['number'] . "</a></td>
                    <td style='border: 1px solid black;' >" . $res['name'] . "</td>
                    <td style='border: 1px solid black;' >" . $res['status'] . "</td>
                    <td style='border: 1px solid black;' >" . $res['installation_date_c'] . "</td>
                    <td style='border: 1px solid black;' >" . $res['total_amount'] . "</td>
                    <td style='border: 1px solid black;' >" . $res['assigned_user_name'] . "</td>
                </tr>";
            }
        }
        $html_content .= "</table>";
    } else {
        $html_content .= "<h4>No Invoice</h4>";
    }
    return $html_content;
}


