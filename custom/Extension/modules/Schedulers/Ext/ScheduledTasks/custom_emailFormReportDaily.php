<?php

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