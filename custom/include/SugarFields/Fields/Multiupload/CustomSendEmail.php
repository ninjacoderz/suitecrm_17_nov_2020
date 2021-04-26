<?php
$attached_file_name = "";
function generatePOPDF($record_id, &$attached_file_name = ""){

    error_reporting(0);
    require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
    require_once('modules/AOS_PDF_Templates/templateParser.php');
    require_once('modules/AOS_PDF_Templates/sendEmail.php');
    require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');

    global $mod_strings, $sugar_config;

    $bean = BeanFactory::getBean('PO_purchase_order', $record_id);

    if(!$bean){
        sugar_die("Invalid Record");
    }

    $variableName = "po_purchase_order";
    $lineItemsGroups = array();
    $lineItems = array();

    $sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $bean->object_name . "' AND pg.parent_id = '" . $bean->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
    $res = $bean->db->query($sql);
    while ($row = $bean->db->fetchByAssoc($res)) {
        $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
        $lineItems[$row['id']] = $row['product_id'];

    }


    $template = new AOS_PDF_Templates();
    $template->retrieve("3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5");

    $object_arr = array();
    $object_arr[$bean->module_dir] = $bean->id;

    //backward compatibility
    $object_arr['Accounts'] = $bean->billing_account_id;
    $object_arr['Contacts'] = $bean->billing_contact_id;
    $object_arr['Users'] = $bean->assigned_user_id;
    $object_arr['Currencies'] = $bean->currency_id;

    $search = array('/<script[^>]*?>.*?<\/script>/si',      // Strip out javascript
        '/<[\/\!]*?[^<>]*?>/si',        // Strip out HTML tags
        '/([\r\n])[\s]+/',          // Strip out white space
        '/&(quot|#34);/i',          // Replace HTML entities
        '/&(amp|#38);/i',
        '/&(lt|#60);/i',
        '/&(gt|#62);/i',
        '/&(nbsp|#160);/i',
        '/&(iexcl|#161);/i',
        '/<address[^>]*?>/si',
        '/&(apos|#0*39);/',
        '/&#(\d+);/'
    );

    $replace = array('',
        '',
        '\1',
        '"',
        '&',
        '<',
        '>',
        ' ',
        chr(161),
        '<br>',
        "'",
        'chr(%1)'
    );

    $header = preg_replace($search, $replace, $template->pdfheader);
    $footer = preg_replace($search, $replace, $template->pdffooter);

    $text = preg_replace($search, $replace, $template->description);
    $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
    $text = preg_replace_callback('/\{DATE\s+(.*?)\}/',
        function ($matches) {
            return date($matches[1]);
        },
        $text);
    $text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
    $text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
    $text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
    $text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
    $text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
    $text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
    $text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
    $text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

    $text = populate_group_lines($text, $lineItemsGroups, $lineItems);


    $converted = templateParser::parse_template($text, $object_arr);
    $header = templateParser::parse_template($header, $object_arr);
    $footer = templateParser::parse_template($footer, $object_arr);

    $printable = str_replace("\n", "<br />", $converted);


    //$file_name = $mod_strings['LBL_PDF_NAME'] . "_" . str_replace(" ", "_", $bean->name) . ".pdf";
    $file_name = $mod_strings['LBL_PDF_NAME'] . "_".(isset($bean->number)?($bean->number."_"):"") . str_replace(" ", "_", $bean->name) ."_".date("dMy"). ".pdf";
    ob_clean();
    try {
        $orientation = ($template->orientation == "Landscape") ? "-L" : "";
        $pdf = new mPDF('en', $template->page_size . $orientation, '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
        $pdf->SetAutoFont();
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($printable);
        
        $fp = fopen($sugar_config['upload_dir'] . "PO-#".$bean->number."-".str_replace(" ", "_",$bean->name).'.pdf', 'wb');
        fclose($fp);
        $pdf->Output($sugar_config['upload_dir'] . "PO-#".$bean->number."-".str_replace(" ", "_",$bean->name).'.pdf', 'F');
        //$sendEmail->send_email($bean, $bean->module_dir, '', $file_name, true);
        $attached_file_name = "PO-#".$bean->number."-".str_replace(" ", "_", $bean->name).'.pdf';
    } catch (mPDF_exception $e) {
        echo $e;
    }


}


function populate_group_lines($text, $lineItemsGroups, $lineItems, $element = 'table')
{

    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';


    $groups = new AOS_Line_Item_Groups();
    foreach ($groups->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

            $curNum = strpos($text, '$aos_line_item_groups_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_line_item_groups_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_line_item_groups_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }
    if ($firstValue !== '' && $lastValue !== '') {
        //Converting Text
        $parts = explode($firstValue, $text);
        $text = $parts[0];
        $parts = explode($lastValue, $parts[1]);
        if ($lastValue == $firstValue) {
            $groupPart = $firstValue . $parts[0];
        } else {
            $groupPart = $firstValue . $parts[0] . $lastValue;
        }

        if (count($lineItemsGroups) != 0) {
            //Read line start <tr> value
            $tcount = strrpos($text, $startElement);
            $lsValue = substr($text, $tcount);
            $tcount = strpos($lsValue, ">") + 1;
            $lsValue = substr($lsValue, 0, $tcount);


            //Read line end values
            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $leValue = substr($parts[1], 0, $tcount);

            //Converting Line Items
            $obb = array();

            $tdTemp = explode($lsValue, $text);

            $groupPart = $lsValue . $tdTemp[count($tdTemp) - 1] . $groupPart . $leValue;

            $text = $tdTemp[0];

            foreach ($lineItemsGroups as $group_id => $lineItemsArray) {
                $groupPartTemp = populate_product_lines($groupPart, $lineItemsArray);
                $groupPartTemp = populate_service_lines($groupPartTemp, $lineItemsArray);

                $obb['AOS_Line_Item_Groups'] = $group_id;
                $text .= templateParser::parse_template($groupPartTemp, $obb);
                $text .= '<br />';
            }
            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $parts[1] = substr($parts[1], $tcount);
        } else {
            $tcount = strrpos($text, $startElement);
            $text = substr($text, 0, $tcount);

            $tcount = strpos($parts[1], $endElement) + strlen($endElement);
            $parts[1] = substr($parts[1], $tcount);
        }

        $text .= $parts[1];
    } else {
        $text = populate_product_lines($text, $lineItems);
        $text = populate_service_lines($text, $lineItems);
    }


    return $text;

}

function populate_product_lines($text, $lineItems, $element = 'tr')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    //Find first and last valid line values
    $product_quote = new AOS_Products_Quotes();
    foreach ($product_quote->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

            $curNum = strpos($text, '$aos_products_quotes_' . $name);

            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_quotes_' . $name;
                    $firstNum = $curNum;

                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_quotes_' . $name;
                    $lastNum = $curNum;

                }
            }
        }
    }

    $product = new AOS_Products();
    foreach ($product->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

            $curNum = strpos($text, '$aos_products_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_' . $name;


                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }

    if ($firstValue !== '' && $lastValue !== '') {

        //Converting Text
        $tparts = explode($firstValue, $text);
        $temp = $tparts[0];

        //check if there is only one line item
        if ($firstNum == $lastNum) {
            $linePart = $firstValue;
        } else {
            $tparts = explode($lastValue, $tparts[1]);
            $linePart = $firstValue . $tparts[0] . $lastValue;
        }


        $tcount = strrpos($temp, $startElement);
        $lsValue = substr($temp, $tcount);
        $tcount = strpos($lsValue, ">") + 1;
        $lsValue = substr($lsValue, 0, $tcount);

        //Read line end values
        $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
        $leValue = substr($tparts[1], 0, $tcount);
        $tdTemp = explode($lsValue, $temp);

        $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        //Converting Line Items
        if (count($lineItems) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId != null && $productId != '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $obb['AOS_Products'] = $productId;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
            }
        }

        $text .= $parts[1];
    }

    //custom code-- model number PO PDF
    $PO_purchase_order = new PO_purchase_order();
    $PO_purchase_order = $PO_purchase_order->retrieve($_REQUEST['po_record']);
    $invoiceId = $PO_purchase_order->aos_invoices_po_purchase_order_1aos_invoices_ida;
    //VUT
    $text = str_replace("\$po_supplier_order_number_c", $PO_purchase_order->supplier_order_number_c, $text);
    //VUT
    $invoice = new AOS_Invoices();
    $invoice = $invoice->retrieve($invoiceId);
    if($invoice->id != '' && (strpos( strtolower($PO_purchase_order->name),'daikin' ) !== false)){
        //case product daikin
        $text = str_replace("\$aos_products_quotes_part_number","\$custom_model_number", $text);
        $daikinLineItems = $invoice->daikin_product_infomation_c;
        $daikin_line_items = json_decode(urldecode($daikinLineItems), true);
        $product_quote = new AOS_Products_Quotes();
        $product_quote = $product_quote->retrieve($id);
        $text = str_replace("\$model_number",'Model Number' , $text);  
        if ($PO_purchase_order->po_type_c == 'daikin_supply') {
            $text = str_replace("\$custom_title_install_date",'Delivery Date' , $text);
            $text = str_replace("\$date_follow_type_date", $PO_purchase_order->delivery_date_c, $text);
        }

        if($product_quote->id != ''){  
            $item_indoor = ''; 
            $item_outdoor = '';      
            if(count($daikin_line_items)) foreach($daikin_line_items as $item){
                if( strpos($item['indoor_model'] , $product_quote->part_number) !== false){
                    $item_indoor = $item['indoor_model'];
                    $item_outdoor = $item['outdoor_model'];
                    break;
                }
            } 
            if($item_indoor != '') {
                $text = str_replace("\$custom_model_number",'<p>Indoor: '. $item_indoor .'</p><p>Outdoor:' . $item_outdoor .'</p>' , $text);       
            }else{
                $text = str_replace("\$custom_model_number",'' , $text);       
            }
        }else{
            $text = str_replace("\$custom_model_number",'' , $text);  
        }
        
    }else{
        if((strpos( strtolower($PO_purchase_order->name),'sanden' ) !== false)){
            //case product sanden
            $text = str_replace("\$model_number",'Part Number' , $text);  
            if($PO_purchase_order->po_type_c == 'sanden_supply'){
                $text = str_replace("\$custom_title_install_date",'Dispatch Date' , $text);
                $text = str_replace("\$date_follow_type_date", $PO_purchase_order->dispatch_date_c, $text);
                // $text = str_replace("\$custom_model_number",'' , $text);
            }
        }
    }
    $text = str_replace("\$custom_title_install_date",'Install Date' , $text);
    $text = str_replace("\$date_follow_type_date", $PO_purchase_order->install_date, $text);
    $text = str_replace("\$model_number",'Model Number' , $text); 

    return $text;
}

function populate_service_lines($text, $lineItems, $element = 'tr')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';

    $text = str_replace("\$aos_services_quotes_service", "\$aos_services_quotes_product", $text);

    //Find first and last valid line values
    $product_quote = new AOS_Products_Quotes();
    foreach ($product_quote->field_defs as $name => $arr) {
        if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

            $curNum = strpos($text, '$aos_services_quotes_' . $name);
            if ($curNum) {
                if ($curNum < $firstNum || $firstNum == 0) {
                    $firstValue = '$aos_products_quotes_' . $name;
                    $firstNum = $curNum;
                }
                if ($curNum > $lastNum) {
                    $lastValue = '$aos_products_quotes_' . $name;
                    $lastNum = $curNum;
                }
            }
        }
    }
    if ($firstValue !== '' && $lastValue !== '') {
        $text = str_replace("\$aos_products", "\$aos_null", $text);
        $text = str_replace("\$aos_services", "\$aos_products", $text);

        //Converting Text
        $tparts = explode($firstValue, $text);
        $temp = $tparts[0];

        //check if there is only one line item
        if ($firstNum == $lastNum) {
            $linePart = $firstValue;
        } else {
            $tparts = explode($lastValue, $tparts[1]);
            $linePart = $firstValue . $tparts[0] . $lastValue;
        }

        $tcount = strrpos($temp, $startElement);
        $lsValue = substr($temp, $tcount);
        $tcount = strpos($lsValue, ">") + 1;
        $lsValue = substr($lsValue, 0, $tcount);

        //Read line end values
        $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
        $leValue = substr($tparts[1], 0, $tcount);
        $tdTemp = explode($lsValue, $temp);

        $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
        $parts = explode($linePart, $text);
        $text = $parts[0];

        //Converting Line Items
        if (count($lineItems) != 0) {
            foreach ($lineItems as $id => $productId) {
                if ($productId == null || $productId == '0') {
                    $obb['AOS_Products_Quotes'] = $id;
                    $text .= templateParser::parse_template($linePart, $obb);
                }
                else {
                    // Solve aos_products_quotes_product_cost_total_price BinhNT
                    $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND id = '".$id."' AND deleted = 0";
                    $db = DBManagerFactory::getInstance();
                    $result = $db->query($sql);
            
                    while ($row = $db->fetchByAssoc($result)) {
                        if(isset($row) && $row != null ){
                            $text = implode(format_number($row["product_list_price"] * $row["product_qty"]), explode('$aos_null_quotes_product_cost_total_price', $text, 2)); //preg_replace("/\$aos_null_quotes_product_cost_total_price/",$row["product_cost_price"] * $row["product_qty"],$text,1); //str_replace("\$aos_null_quotes_product_cost_total_price", $row["product_cost_price"] * $row["product_qty"],$text);
                            $text = implode(format_number($row["product_list_price"] * $row["product_qty"]), explode('$aos_services_quotes_service_cost_total_price', $text, 2));//str_replace("\$aos_services_quotes_service_cost_total_price", $row["product_cost_price"] * $row["product_qty"],$text);
                        }
                    }
                }
            }
        }

        $text .= $parts[1];
    }
    return $text;
}


// Get the params
$mail_format = urldecode($_GET['mail_format'] ? $_GET['mail_format'] : "");
$messagetype = urldecode($_GET['messagetype'] ? $_GET['messagetype'] : "");
$is_testing = urldecode($_GET['is_testing'] ? $_GET['is_testing'] : "");
$invoice_number = trim(urldecode($_GET['invoice_number'] ? $_GET['invoice_number'] : ""));
if($invoice_number == ""){
    echo "Please save invoice before send email";
    die();
}

if($mail_format == "daikin_info"){
    require_once('include/SugarPHPMailer.php');
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->IsHTML(true);
    
    $bean = BeanFactory::getBean('PO_purchase_order',urldecode($_GET['po_record']));
    $mail->Subject = 'Daikin Order Info '.urldecode($_GET['invoice_title']) . " - PO #". $bean->number;
    // get params
    $delivery_contact_name =  urldecode($_GET['delivery_contact_name']);
    $delivery_contact_suburb =  urldecode($_GET['delivery_contact_suburb']);
    $delivery_contact_phone_numbe =  urldecode($_GET['delivery_contact_phone_numbe']);
    $delivery_contact_address =  urldecode($_GET['delivery_contact_address']);
    $delivery_contact_postcode =  urldecode($_GET['delivery_contact_postcode']);
    $delivery_notes =  urldecode($_GET['delivery_notes']);

    $body = '
    Hi Tom, <br>
    Could you please order for '.$delivery_contact_name.' '.$delivery_contact_suburb.'   <br>
    <ul >';
    $daikin_line_items = json_decode(html_entity_decode($_GET['daikinLineItems']), true);
    if(count($daikin_line_items)) foreach($daikin_line_items as $item){
        $body .= '<li>';
        $body .= ($item['product_name'].' '.$item['indoor_model'].' '.$item['outdoor_model'].' '. (($item['wifi'])?"with wifi":'without wifi'));
        $body .= '</li>';
    }
    $body .= '</ul>';

    $body .= ('Name: '.$delivery_contact_name. '<br>  Address: '.$delivery_contact_address.' '. $delivery_contact_suburb .' '. $delivery_contact_postcode .' <br>');
    $body .= ('Please phone one hour before delivery: '.$delivery_contact_phone_numbe. '<br>');
    $body .= ('Note: '.$delivery_notes );

    $mail->Body = $body;

    $mail->prepForOutbound();
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->AddAddress('binhdigipro@gmail.com');

    if(isset($_REQUEST['po_record']) && $_REQUEST['po_record'] !== ""){
        $attached_file_name = "PO-#".$bean->number."-".str_replace(" ", "_", $bean->name).'.pdf';
        generatePOPDF($_REQUEST['po_record'], $attached_file_name);
        global $sugar_config;
        $mail->addAttachment($sugar_config['upload_dir'] . $attached_file_name);
    }

    $sent = $mail->Send();
    echo $sent;
    die();
}

$invoice_to_email = urldecode(isset($_GET['invoice_to_email']) ? $_GET['invoice_to_email'] : "");
$electrical_notes = urldecode(isset($_GET['electrical_notes'])  ?  $_GET['electrical_notes'] : "");

$plumber_contact_name = urldecode($_GET['plumber_contact_name'] ?  $_GET['plumber_contact_name'] : "");
$plumber_contact_name_explode = explode(' ', $plumber_contact_name);
$plumber_contact_first_name = urldecode($plumber_contact_name_explode[0]);

$plumber_install_date = urldecode($_GET['plumber_install_date'] ? $_GET['plumber_install_date'] : "");
$electrical_install_date = urldecode($_GET['electrical_install_date'] ? $_GET['electrical_install_date'] : "");
$billing_account = urldecode($_GET['billing_account'] ? $_GET['billing_account'] : "");
$billing_address = urldecode($_GET['billing_address'] ? $_GET['billing_address'] : "");

$site_contact_name = urldecode($_GET['site_contact_name'] ? $_GET['site_contact_name'] : "");
$site_contact_number = urldecode($_GET['site_contact_number'] ? $_GET['site_contact_number'] : "");

$alternate_site_contact_name = urldecode($_GET['alternate_site_contact_name'] ? $_GET['alternate_site_contact_name'] : "");
$alternate_site_contact_number = urldecode($_GET['alternate_site_contact_number'] ? $_GET['alternate_site_contact_number'] : "");
$system = urldecode($_GET['system'] ? $_GET['system'] : "");
$plumbing = urldecode($_GET['plumbing'] ? $_GET['plumbing'] : "");
$plumbing_contact = urldecode($_GET['plumbing_contact'] ? $_GET['plumbing_contact'] : "");
$plumbing_contact_number = urldecode($_GET['plumbing_contact_number'] ? $_GET['plumbing_contact_number'] : "");

$electric_contact_number = urldecode($_GET['electric_contact_number'] ? $_GET['electric_contact_number'] : "");
$electric_company = urldecode($_GET['electric_company'] ? $_GET['electric_company'] : "");
$electric_name = urldecode($_GET['electric_name'] ? $_GET['electric_name'] : "");
$photo =  urldecode($_GET['photo'] ? $_GET['photo'] : "");
$file_dir = urldecode($_GET['file_dir'] ? $_GET['file_dir'] : "");

$pe_contact = urldecode($_GET['pe_contact_c'] ? $_GET['pe_contact_c'] : "");
$pe_contact_number = urldecode($_GET['pe_contact_number'] ? $_GET['pe_contact_number'] : "");
$pe_email = urldecode($_GET['pe_email'] ? $_GET['pe_email'] : "");
$pe_backup_contact = urldecode($_GET['pe_backup_contact_c'] ? $_GET['pe_backup_contact_c'] : "");
$pe_backup_contact_number = urldecode($_GET['pe_backup_contact_number'] ? $_GET['pe_backup_contact_number'] : "");
$plumbing_note = urldecode($_GET['plumbing_note'] ? $_GET['plumbing_note'] : "");

$group_name = urldecode($_GET['group_name'] ? $_GET['group_name'] : "");
$suburb = urldecode($_GET['suburb'] ? $_GET['suburb'] : "");
$customer_notes_c = urldecode(isset($_GET['customer_notes_c']) ? $_GET['customer_notes_c'] : "");

require_once('include/SugarPHPMailer.php');
$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->IsHTML(true);

$l_subject = "";
if($mail_format == "plumber"){
    $l_subject = "Plumbing";
}else if($mail_format == "custommer") {
    $l_subject = "Customer";
}else{
    $l_subject = "Electrical";
}

$mail->Subject = $l_subject.' | Upcoming '.$group_name.' Installation for '.$billing_account.' '.$suburb. ' '. $plumber_install_date;
$say_hi = "";
if($mail_format == "plumber"){
    $say_hi = $plumber_contact_first_name;
}else if($mail_format == "custommer") {
    $say_hi = current(explode(" ", $site_contact_name));
}else{
    $say_hi = current(explode(" ", $electric_name));
}
$body = 'Hi '.$say_hi.', <br>
Client details below, PO and photos attached.
    <table >';
if ($mail_format == "plumber") {
    $body .= '
<tr><td>Plumber Note: </td> <td style="width: 80%;" >'. $plumbing_note .'</td></tr>';
}
else if($mail_format == "custommer") {
    $body .= '
<tr><td>Customer Note: </td> <td style="width: 85%;">'. $customer_notes_c .'</td></tr>';
} else if ($mail_format == "electrical"){
    $electrical_email = urldecode(isset($_GET['eletrical_email'])  ?  $_GET['eletrical_email'] : "");
    $body .= '
<tr><td>Electrical Email: </td> <td>'. $electrical_email .'</td></tr>
<tr><td>Electrical Notes: </td> <td>'. $electrical_notes .'</td></tr>';
}
$body .= '
<tr><td>Client Install ID #: </td> <td>'.$invoice_number.'</td></tr>
<tr><td>Date of Plumbing install: </td> <td>'. $plumber_install_date .' </td></tr>
<tr><td>Date of Electrical install: </td> <td>'. $electrical_install_date .' </td></tr>
<tr><td>Client: </td> <td>'. $billing_account .' </td></tr>
<tr><td>Address: </td> <td>'.$billing_address.'  </td></tr>
<tr><td>Site Contact name: </td> <td>'.$site_contact_name.' </td></tr>
<tr><td>Site Contact number: </td><td>'.$site_contact_number.' <td></tr>'.(
    ($alternate_site_contact_name != "")?
    ('<tr><td>Alternate contact name: </td><td>'.$alternate_site_contact_name.' </td></tr>') : "" ).

    (($alternate_site_contact_number != "")?
        ('<tr><td>Alternate contact number: </td><td>'.$alternate_site_contact_number.' </td></tr>'):"").
    '<tr><td> Email: </td> <td>'.$invoice_to_email . '</td></tr>'
    ;

// Photo logic here
// Photos:
$body .= '
<tr><td>Photo: </td><td>'.$photo . '</td></tr>';

$body .= '
<tr><td>System: </td><td>'.$system.' </td></tr>
<tr><td>Plumbing: </td><td>'.$plumbing.' </td></tr>
<tr><td>Plumbing Contact: </td><td>'.$plumbing_contact.' </td></tr>
<tr><td>Plumbing Contact Number: </td><td>'.$plumbing_contact_number.' </td></tr>';

$body .= '
<tr><td>Electrical: </td><td>'.$electric_company.' </td></tr>
<tr><td>Electrical Contact: </td><td>'.$electric_name.' </td></tr>
<tr><td>Electrical Contact Number: </td><td>'.$electric_contact_number.' </td></tr>
<tr><td>PureElectric main contact: </td><td>'.$pe_contact.' '.$pe_contact_number.' </td></tr>
<tr><td>PureElectric backup contact: </td><td>'.$pe_backup_contact.' '.$pe_backup_contact_number .'</td></tr></table>';


// Send sms mms here
if($messagetype == "sms/mms"){
    if($is_testing){
        $phone_number = array("+61421616733");//array("+61423494949","+61403436298","+61407369165");
    }
    else {
        //plumber_phone_number
        //customer_phone_number
        //if($mail_format != 'custommer') {
        if($mail_format == "electrical"){
            $phone_number = str_replace(" ","", trim(urldecode($_GET['electric_phone_number'] ? $_GET['electric_phone_number'] : "")));
        }
        else if($mail_format == "plumber"){
            $phone_number = str_replace(" ","", trim(urldecode($_GET['plumber_phone_number'] ? $_GET['plumber_phone_number'] : "")));
        }
        else {
            $phone_number = str_replace(" ","", trim(urldecode($_GET['customer_phone_number'] ? $_GET['customer_phone_number'] : "")));
        }
    }

    // cd to message and use send message command
    global $sugar_config;
    $sms_body = strip_tags($body);
    $sms_body = preg_replace("/&#?[a-z0-9]{2,8};/i"," ", $sms_body);
    //$command = "cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$sms_body.'"';
    if(is_array($phone_number) && count($phone_number)>0){
        foreach ($phone_number as $phone){
            exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone.' "'.$sms_body.'"');
        }

    }
    else{
        exec("cd ".$sugar_config["message_command_dir"]."; php send-message.php sms ".$phone_number.' "'.$sms_body.'"');
    }
    //$excute_command = exec($command);

    if($mail_format != 'custommer') {
        $current_file_path = dirname(__FILE__);
        $current_file_path .= '/server/php/files/' . $file_dir;
        if(is_dir ( $current_file_path )){
            $file_array = scandir($current_file_path);
            foreach ($file_array as $file) {
                if (!is_dir($file)) {
                    if (
                        (stripos(strtolower($file), 'old') !== FALSE) ||
                        (stripos(strtolower($file), 'ocket') !== FALSE)
                        || (stripos(strtolower($file), 'hoto') !== FALSE)
                        || (stripos(strtolower($file), 'new') !== FALSE)
                        || (stripos(strtolower($file), 'iagram') !== FALSE)
                    )
                    {
                        $image_url = "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/" . $file_dir . "/" . $file;
                        //http://loc.suitecrm.com/custom/include/SugarFields/Fields/Multiupload/server/php/files/613b6e66-70d0-4be2-b364-216266eb6d90/20170916_132431.jpg
                        if(is_array($phone_number) && count($phone_number)>0) {
                            foreach ($phone_number as $phone) {
                                exec("cd " . $sugar_config["message_command_dir"] . "; php send-message.php mms " . $phone . ' "' . $image_url . '"');
                            }
                        }
                        else{
                            exec("cd " . $sugar_config["message_command_dir"] . "; php send-message.php mms " . $phone_number . ' "' . $image_url . '"');
                        }
                    }
                }
            }
        }
    }
    die();
}

$mail->Body = $body;

 // Add PO attachment 
if(isset($_REQUEST['po_record']) && $_REQUEST['po_record'] !== ""){
    generatePOPDF($_REQUEST['po_record'], $attached_file_name);
    global $sugar_config;
    $mail->addAttachment($sugar_config['upload_dir'] . $attached_file_name);
    
}

if($mail_format != 'custommer') {
    $current_file_path = dirname(__FILE__);
    $current_file_path .= '/server/php/files/' . $file_dir;
    if(is_dir ( $current_file_path )){
        $file_array = scandir($current_file_path);
        foreach ($file_array as $file) { 
            if (!is_dir($file)) {
                if($mail_format == "electrical"){
                    if (
                        (stripos(strtolower($file), 'old') !== FALSE) ||
                        (stripos(strtolower($file), 'ocket') !== FALSE)
                        || (stripos(strtolower($file), 'hoto') !== FALSE)
                        || (stripos(strtolower($file), 'new') !== FALSE)
                        || (stripos(strtolower($file), 'iagram') !== FALSE)
                        || (stripos(strtolower($file), 'witchboard') !== FALSE)
                    ){
                        $mail->addAttachment($current_file_path . '/' . $file);
                    }
                }
                else{
                    if (
                        (stripos(strtolower($file), 'old') !== FALSE) ||
                        (stripos(strtolower($file), 'ocket') !== FALSE)
                        || (stripos(strtolower($file), 'hoto') !== FALSE)
                        || (stripos(strtolower($file), 'new') !== FALSE)
                        || (stripos(strtolower($file), 'iagram') !== FALSE)
                        || (stripos(strtolower($file), 'witchboard') !== FALSE)
                    ){
                        $mail->addAttachment($current_file_path . '/' . $file);
                    }
                }

            }
        }
    }
}
$mail->prepForOutbound(); 
$mail->AddAddress('info@pure-electric.com.au');
$mail->AddAddress('binhdigipro@gmail.com');
if($pe_email!=""){
    $mail->AddAddress($pe_email);
}


try {
        $sent = $mail->Send();
        unlink($sugar_config['upload_dir'] . $attached_file_name);
    } catch (MyException $e) {
        // rethrow it
        throw $e->getMessage();
    }
echo $sent;
die();


?>