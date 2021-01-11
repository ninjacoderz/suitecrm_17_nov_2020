<?php
$attached_file_name = "";
function result_pdf ($record_id, &$attached_file_name = ""){

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
    return $converted;

}

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
    $file_name = $mod_strings['LBL_PDF_NAME'] . "_".(isset($bean->number)?($bean->number."_"):"") . str_replace([" ","/"], ["_","-"], $bean->name) ."_".date("dMy"). ".pdf";
    ob_clean();
    try {
        $orientation = ($template->orientation == "Landscape") ? "-L" : "";
        $pdf = new mPDF('en', $template->page_size . $orientation, '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
        $pdf->SetAutoFont();
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($printable);
        $fp = fopen($sugar_config['upload_dir'] . "PO-#".$bean->number."-".str_replace([" ","/"], ["_","-"],$bean->name).'.pdf', 'wb');
        fclose($fp);
        $pdf->Output($sugar_config['upload_dir'] . "PO-#".$bean->number."-".str_replace([" ","/"], ["_","-"],$bean->name).'.pdf', 'F');
        //$sendEmail->send_email($bean, $bean->module_dir, '', $file_name, true);
        $attached_file_name = "PO-#".$bean->number."-".str_replace([" ","/"], ["_","-"], $bean->name).'.pdf';
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

    $freight_companys = array (
        'cope' => 'COPE Sensitive Freight',
        'meva' => 'MEVA Transport',
        'TNT'  => 'TNT Express',
        'Toll' => 'Toll Group',
        'Gilders' => 'Gilders Transport',
        'Collect' => 'Collect',
    );
    //START  
    $PO_purchase_order = new PO_purchase_order();
    $PO_purchase_order = $PO_purchase_order->retrieve($_REQUEST['po_record']);
    $invoiceId = $PO_purchase_order->aos_invoices_po_purchase_order_1aos_invoices_ida;
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
                $text = changeToSanden($text);
                $text = str_replace("\$po_freight_company_c", $freight_companys[$PO_purchase_order->freight_company_c], $text);
                $text = str_replace("\$custom_title_install_date",'Dispatch Date' , $text);
                $text = str_replace("\$date_follow_type_date", $PO_purchase_order->dispatch_date_c, $text);
                // $text = str_replace("\$custom_model_number",'' , $text);
            }
        }
    }
    
    //VUT
    $text = str_replace("\$po_supplier_order_number_c", $PO_purchase_order->supplier_order_number_c, $text);
    //VUT
    $text = str_replace("\$custom_title_install_date",'Install Date' , $text);
    $text = str_replace("\$date_follow_type_date", $PO_purchase_order->install_date, $text);
    $text = str_replace("\$model_number",'Model Number' , $text); 
    //END

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

    // //custom code-- model number PO PDF
    // //START  
    // $PO_purchase_order = new PO_purchase_order();
    // $PO_purchase_order = $PO_purchase_order->retrieve($_REQUEST['po_record']);
    // $invoiceId = $PO_purchase_order->aos_invoices_po_purchase_order_1aos_invoices_ida;
    // $invoice = new AOS_Invoices();
    // $invoice = $invoice->retrieve($invoiceId);
    // if($invoice->id != '' && (strpos( strtolower($PO_purchase_order->name),'daikin' ) !== false)){
    //     //case product daikin
    //     $text = str_replace("\$aos_products_quotes_part_number","\$custom_model_number", $text);
    //     $daikinLineItems = $invoice->daikin_product_infomation_c;
    //     $daikin_line_items = json_decode(urldecode($daikinLineItems), true);
    //     $product_quote = new AOS_Products_Quotes();
    //     $product_quote = $product_quote->retrieve($id);
    //     $text = str_replace("\$model_number",'Model Number' , $text);  
    //     if ($PO_purchase_order->po_type_c == 'daikin_supply') {
    //         $text = str_replace("\$custom_title_install_date",'Delivery Date' , $text);
    //         $text = str_replace("\$date_follow_type_date", $PO_purchase_order->delivery_date_c, $text);
    //     }
    //     if($product_quote->id != ''){  
    //         $item_indoor = ''; 
    //         $item_outdoor = '';      
    //         if(count($daikin_line_items)) foreach($daikin_line_items as $item){
    //             if( strpos($item['indoor_model'] , $product_quote->part_number) !== false){
    //                 $item_indoor = $item['indoor_model'];
    //                 $item_outdoor = $item['outdoor_model'];
    //                 break;
    //             }
    //         } 
    //         if($item_indoor != '') {
    //             $text = str_replace("\$custom_model_number",'<p>Indoor: '. $item_indoor .'</p><p>Outdoor:' . $item_outdoor .'</p>' , $text);       
    //         }else{
    //             $text = str_replace("\$custom_model_number",'' , $text);       
    //         }
    //     }else{
    //         $text = str_replace("\$custom_model_number",'' , $text);  
    //     }
        
    // }else{
    //     if((strpos( strtolower($PO_purchase_order->name),'sanden' ) !== false)){
    //         //case product sanden
    //         $text = str_replace("\$model_number",'Part Number' , $text);  
    //         if($PO_purchase_order->po_type_c == 'sanden_supply'){
    //             $text = changeToSanden($text);
    //             $text = str_replace("\$po_freight_company_c", $freight_companys[$PO_purchase_order->freight_company_c], $text);
    //             $text = str_replace("\$custom_title_install_date",'Dispatch Date' , $text);
    //             $text = str_replace("\$date_follow_type_date", $PO_purchase_order->dispatch_date_c, $text);
    //             // $text = str_replace("\$custom_model_number",'' , $text);
    //         }
    //     }
    // }
    
    // //VUT
    // $text = str_replace("\$po_supplier_order_number_c", $PO_purchase_order->supplier_order_number_c, $text);
    // //VUT
    // $text = str_replace("\$custom_title_install_date",'Install Date' , $text);
    // $text = str_replace("\$date_follow_type_date", $PO_purchase_order->install_date, $text);
    // $text = str_replace("\$model_number",'Model Number' , $text); 
    // //END

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
    $email = new Email();

    $bean = BeanFactory::getBean('PO_purchase_order',urldecode($_GET['po_record']));
    $email->name = 'Daikin Order Info '.urldecode($_GET['invoice_title']) . " - PO #". $bean->number;
    // get params
    $delivery_contact_name =  urldecode($_GET['delivery_contact_name']);
    $delivery_contact_suburb =  urldecode($_GET['delivery_contact_suburb']);
    $delivery_contact_phone_numbe =  urldecode($_GET['delivery_contact_phone_numbe']);
    $delivery_contact_address =  urldecode($_GET['delivery_contact_address']);
    $delivery_contact_postcode =  urldecode($_GET['delivery_contact_postcode']);
    $delivery_notes =  urldecode($_GET['delivery_notes']);

    $body = '
    Hi '.$bean->billing_account.', <br>
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

    $email->description_html = $body;
    $email->id = create_guid();
    $email->new_with_id = true;
    



    if(isset($_REQUEST['po_record']) && $_REQUEST['po_record'] !== ""){
        $attached_file_name = "PO-#".$bean->number."-".str_replace([" ","/"], ["_","-"], $bean->name).'.pdf';
        generatePOPDF($_REQUEST['po_record'], $attached_file_name);
        global $sugar_config;
        global $current_user;
        $note = new Note();
        $note->modified_user_id = $current_user->id;
        $note->created_by = $current_user->id;
        $note->name = $attached_file_name;
        $note->parent_type = 'Emails';
        $note->parent_id = $email->id;
        $note->file_mime_type = mime_content_type ( $sugar_config['upload_dir'] . $attached_file_name );
        $note->filename = $attached_file_name; 
        $noteId = $note->save();

        if($noteID !== false && !empty($noteId)) {
            rename($sugar_config['upload_dir'] . $attached_file_name, $sugar_config['upload_dir'] . $note->id);
            $email->attachNote($note);
        } else {
            $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
        }
        //$mail->addAttachment($sugar_config['upload_dir'] . $attached_file_name);
    }

    $contact = new Account;
    if ($contact->retrieve($_GET["daikin_supplier_c"])) {
        $email->parent_type = 'Accounts';
        $email->parent_id = $contact->id;

        if (!empty($contact->email1)) {
            //$email->to_addrs_emails = $contact->email1 . ";";
            //$email->to_addrs = $contact->name . " <" . $contact->email1 . ">";
            $email->to_addrs_names = $contact->name . " <" . $contact->email1 . ">";
            $email->parent_name = $contact->name;
        }
    }
    
    $email->type = "draft";
    $email->status = "draft";
    
    global $current_user;
   
    $showFolders = sugar_unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));
    if (empty($showFolders)) {
        $showFolders = array();
    }
    // personal accounts
    if ($current_user->hasPersonalEmail()) {
        $personals = retrieveByGroupId($current_user->id);
    }
    $inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
    if(count($personals)) foreach($personals as $personal) {
        if (in_array($personal->id, $showFolders)) {
            if(strpos(strtolower($personal->name), "account") !== false) {
                $inboundEmailID = $personal->id;
            } 
        }
    }
    $email->mailbox_id = $inboundEmailID;

    try {
        $email->save(false);
        echo 'index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $bean->module_dir . '&return_action=DetailView&return_id=' . $bean->id . '&return_action=DetailView&record=' . $email->id;
            //$sent = $mail->Send();
            global $sugar_config;
            unlink($sugar_config['upload_dir'] . $attached_file_name);
        } catch (MyException $e) {
            // rethrow it
            throw $e->getMessage();
        }
    
    die();
}

function retrieveByGroupId($groupId)
{
    $q = '
      SELECT id FROM inbound_email
      WHERE
        group_id = \'' . $groupId . '\' AND
        deleted = 0 AND
        status = \'Active\'';
    $db = DBManagerFactory::getInstance();
    $r = $db->query($q, true);

    $beans = array();
    while ($a = $db->fetchByAssoc($r)) {
        $ie = new InboundEmail();
        $ie->retrieve($a['id']);
        $beans[$a['id']] = $ie;
    }

    return $beans;
}

$invoice_to_email = urldecode(isset($_GET['invoice_to_email']) ? $_GET['invoice_to_email'] : "");
$electrical_notes = urldecode(isset($_GET['electrical_notes'])  ?  $_GET['electrical_notes'] : "");
$electrical_notes = str_replace("\n", "<br>", $electrical_notes);
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
$plumbing_note = str_replace("\n", "<br>", $plumbing_note);
$group_name = urldecode($_GET['group_name'] ? $_GET['group_name'] : "");
$suburb = urldecode($_GET['suburb'] ? $_GET['suburb'] : "");
$customer_notes_c = urldecode(isset($_GET['customer_notes_c']) ? $_GET['customer_notes_c'] : "");

require_once('include/SugarPHPMailer.php');
//$emailObj = new Email();
//$defaults = $emailObj->getSystemDefaultEmail();
$email = new Email();
//$mail->setMailerForSystem();
//$mail->From = $defaults['email'];
//$mail->FromName = $defaults['name'];
//$mail->IsHTML(true);
// Dung code  
if(isset($_REQUEST['po_record']) && $_REQUEST['po_record'] !== ""){
    $po_record_id = $_REQUEST['po_record'];
    $bean_po = BeanFactory::getBean('PO_purchase_order', $po_record_id);
    $PO_number =$bean_po->number;
}


$l_subject = "";
if($mail_format == "plumber"){
    $l_subject = "Plumbing";
    $phone = preg_replace('/\D/', '',explode(':', $plumbing_contact_number));
    if($phone[1] != '') {
        $phone_number = $phone[1];
    } elseif ($phone[2] != '') {
        $phone_number = $phone[2];
    } else {
        $phone_number = '';
    }
    $phone_number = preg_replace("/^0/", "+61", $phone_number);
    $phone_number = preg_replace("/^61/", "+61", $phone_number);
    $email->number_client = $phone_number;
}else if($mail_format == "custommer") {
    $l_subject = "Customer";
}else{
    $l_subject = "Electrical";
    $phone = preg_replace('/\D/', '',explode(':', $electric_contact_number));
    if($phone[1] != '') {
        $phone_number = $phone[1];
    } elseif ($phone[2] != '') {
        $phone_number = $phone[2];
    } else {
        $phone_number = '';
    }
    $phone_number = preg_replace("/^0/", "+61", $phone_number);
    $phone_number = preg_replace("/^61/", "+61", $phone_number);
    $email->number_client = $phone_number;
}

//thien fix
if($l_subject == "Plumbing"){
    if(strpos($group_name,'Elec') !== false)
        $group_name = str_replace('Elec','Plumbing',$group_name);
}
//thien fix
// $email->name = $l_subject.'  #' .$PO_number .' | Upcoming '.$group_name.' Installation for '.$billing_account.' '.$suburb. ' '. $plumber_install_date;

// $email->name = $l_subject.'  PO#' .$PO_number .' | Upcoming '.$group_name.' Installation for '.$billing_account.' '.$suburb. ' '. $plumber_install_date;

$say_hi = "";
$email_for ="";
$install_note ='';
if($mail_format == "plumber"){
    if( $_REQUEST['product_type'] == 'quote_type_daikin' || $_REQUEST['product_type'] == 'quote_type_nexura' ){
        $say_hi = $plumber_contact_first_name;
        $email_for ="Plumbing PO";
        $install_note = $plumbing_note;
        $link_pe = "for-daikin-plumbing";
        $type = "daikin";
    }else {
        $say_hi = $plumber_contact_first_name;
        $email_for ="Plumbing PO";
        $install_note = $plumbing_note;
        $link_pe = "for-plumber";
        $type = "sanden";
    }
}else if($mail_format == "custommer") {
    $say_hi = current(explode(" ", $site_contact_name));
    $email_for ="PO";
}else{
    $say_hi = current(explode(" ", $electric_name));
    $email_for ="Electrical PO";
    $install_note = $electrical_notes;
    $link_pe = "for-electrician";
    $type = "sanden";
}

//tuan -- code
$email_template_id = 'c271c11b-469f-466e-140d-5df9972c8110';
$emailTemplate = BeanFactory::getBean(
    'EmailTemplates',$email_template_id 
    //'EmailTemplates',"c5d941bd-597d-f2bf-9825-5e12da977171" // tuan test
);
$invoice_id = $_REQUEST['invoice_id'];

$invoice = new AOS_Invoices();
$invoice->retrieve($invoice_id);

// Fill subject
$subject = $emailTemplate->subject;
$subject = str_replace('Plumbing PO',$email_for,$subject);
$subject = str_replace('$po_number',$PO_number,$subject);

$plumber_install_date_with_dayname = '';
if(!empty($plumber_install_date)){
    $date = DateTime::createFromFormat('d/m/Y',$plumber_install_date);
    $plumber_install_date_with_dayname = $date->format('D').' '.$plumber_install_date;
}
$electrical_install_date_with_dayname = '';
if(!empty($electrical_install_date)){
    $date = DateTime::createFromFormat('d/m/Y',$electrical_install_date);
    $electrical_install_date_with_dayname = $date->format('D').' '.$electrical_install_date;
}

if ($mail_format == "plumber") {
    $path_file_json_template = $_SERVER["DOCUMENT_ROOT"] . '/custom/modules/AOS_Invoices/json_pcoc_cert_template.json';
    $json_data = json_decode(file_get_contents($path_file_json_template),true);
    $pcoc_cert_wording = $json_data[$_REQUEST['id_pcoc_cert']]['content'];
    $subject = str_replace('$pl_elec_install_date',$plumber_install_date_with_dayname,$subject);
    $cert_notes =  'PCOC Cert Note: '.$pcoc_cert_wording;
}
else if($mail_format == "electrical"){
    $path_file_json_template = $_SERVER["DOCUMENT_ROOT"] . '/custom/modules/AOS_Invoices/json_ces_cert_template.json';
    $json_data = json_decode(file_get_contents($path_file_json_template),true);
    $ces_cert_wording = $json_data[$_REQUEST['id_ces_cert']]['content'];
    $subject = str_replace('$pl_elec_install_date',$electrical_install_date_with_dayname,$subject);
    $cert_notes = 'CES Cert Note: '.$ces_cert_wording;
}

switch ( $_REQUEST['product_type']) {
    case "quote_type_sanden":
    $subject = str_replace('$product_type',"Sanden",$subject);
    break;
    case "quote_type_daikin": case "quote_type_nexura":
    $subject = str_replace('$product_type',"Daikin",$subject);
    break;
    case "quote_type_upcomming_service":
    $subject = str_replace('$product_type',"Upcoming Service Call",$subject);
    break;
}
$installation_pictures = $_REQUEST['installation_pictures_c'];


$subject = str_replace('$name_suburb_state',$billing_account.' '.$suburb,$subject);
$photo_arr = json_decode(htmlspecialchars_decode($_REQUEST['photo_array']));
$html_photo = 'Attachments';

$html_photo .= ' <a href="https://pure-electric.com.au/upload_file_'.$type.'/'.$link_pe.'?invoice_id='.$invoice_id.'">Link Upload Install Photos</a>' ;
$link_upload_file =  '<a href="https://pure-electric.com.au/upload_file_'.$type.'/'.$link_pe.'?invoice_id='.$invoice_id.'">Link Upload Install Photos</a>' ;
$link_upload_file_for_sms = 'https://pure-electric.com.au/upload_file_'.$type.'/'.$link_pe.'?invoice_id='.$invoice_id;
// fill body template

$body = str_replace('$installer_name',$say_hi,$emailTemplate->body);
$body = str_replace('$install_note', $install_note ,$body);
$body = str_replace('$invoice_number',$invoice_number,$body);
$body = str_replace('$plumber_install_date',$plumber_install_date,$body);
$body = str_replace('$electrical_install_date',$electrical_install_date,$body);
$body = str_replace('$billing_account',$billing_account,$body);
$body = str_replace('$billing_address',$billing_address,$body);
$body = str_replace('$site_contact_name',$site_contact_name,$body);
$body = str_replace('$site_contact_number',$site_contact_number,$body);
$body = str_replace('$email',$invoice_to_email,$body);
$body = str_replace('$system',$system,$body);
$body = str_replace('PLUMBING ACCOUNT (SANDEN INSTALLER)',$plumbing,$body);
$body = str_replace('MAIN CONTACT of ACCOUNT (SANDEN INSTALLER)',$plumbing_contact,$body);
$body = str_replace('$plumbing_contact_number',$plumbing_contact_number,$body);
$body = str_replace('ELECTRICAL ACCOUNT (SANDEN ELECTRICIAN)',$electric_company,$body);
$body = str_replace('MAIN CONTACT of ACCOUNT (SANDEN ELECTRICIAN)',$electric_name,$body);
$body = str_replace('$electric_contact_number',$electric_contact_number,$body);
$body = str_replace('$main_contact',$pe_contact.' '.$pe_contact_number,$body);
$body = str_replace('$backup_contact',$pe_backup_contact.' '.$pe_backup_contact_number,$body);
$body = str_replace('$attachments',$html_photo,$body);
$body = str_replace('$link_upload_files',$link_upload_file,$body);
$body = str_replace('$cert_note',$cert_notes,$body);


// fill body html template
$body_html = str_replace('$installer_name',$say_hi,$emailTemplate->body_html);
$body_html = str_replace('$install_note', $install_note ,$body_html);
$body_html = str_replace('$invoice_number',$invoice_number,$body_html);
$body_html = str_replace('$plumber_install_date',$plumber_install_date,$body_html);
$body_html = str_replace('$electrical_install_date',$electrical_install_date,$body_html);
$body_html = str_replace('$billing_account',$billing_account,$body_html);
$body_html = str_replace('$billing_address',$billing_address,$body_html);
$body_html = str_replace('$site_contact_name',$site_contact_name,$body_html);
$body_html = str_replace('$site_contact_number',$site_contact_number,$body_html);
$body_html = str_replace('$email',$invoice_to_email,$body_html);
$body_html = str_replace('$system',$system,$body_html);
$body_html = str_replace('PLUMBING ACCOUNT (SANDEN INSTALLER)',$plumbing,$body_html);
$body_html = str_replace('MAIN CONTACT of ACCOUNT (SANDEN INSTALLER)',$plumbing_contact,$body_html);
$body_html = str_replace('$plumbing_contact_number',$plumbing_contact_number,$body_html);
$body_html = str_replace('ELECTRICAL ACCOUNT (SANDEN ELECTRICIAN)',$electric_company,$body_html);
$body_html = str_replace('MAIN CONTACT of ACCOUNT (SANDEN ELECTRICIAN)',$electric_name,$body_html);
$body_html = str_replace('$electric_contact_number',$electric_contact_number,$body_html);
$body_html = str_replace('$main_contact',$pe_contact.' '.$pe_contact_number,$body_html);
$body_html = str_replace('$backup_contact',$pe_backup_contact.' '.$pe_backup_contact_number,$body_html);
$body_html = str_replace('$attachments',$html_photo,$body_html);
$body_html = str_replace('$link_upload_files',$link_upload_file,$body_html);
$body_html = str_replace('$cert_note',$cert_notes,$body_html);


$email->name = $subject;
$email->description = $body;
$email->description_html = $body_html;

// render sms template 
$sms_template_id = 'e420e071-4fa4-6916-720d-5efaa38444a2';
$install_note =  str_replace('<br>',"\n",$install_note);

$smsTemplate =BeanFactory::getBean('pe_smstemplate', $sms_template_id);
$sms_body = str_replace('$installer_name',$say_hi,$smsTemplate->body_c);
$sms_body = str_replace('$install_note', $install_note ,$sms_body);
$sms_body = str_replace('$invoice_number',$invoice_number,$sms_body);
$sms_body = str_replace('$plumber_install_date',$plumber_install_date,$sms_body);
$sms_body = str_replace('$electrical_install_date',$electrical_install_date,$sms_body);
$sms_body = str_replace('$billing_account',$billing_account,$sms_body);
$sms_body = str_replace('$billing_address',$billing_address,$sms_body);
$sms_body = str_replace('$site_contact_name',$site_contact_name,$sms_body);
$sms_body = str_replace('$site_contact_number',$site_contact_number,$sms_body);
$sms_body = str_replace('$email',$invoice_to_email,$sms_body);
$sms_body = str_replace('$system',$system,$sms_body);
$sms_body = str_replace('PLUMBING ACCOUNT (SANDEN INSTALLER)',$plumbing,$sms_body);
$sms_body = str_replace('MAIN CONTACT of ACCOUNT (SANDEN INSTALLER)',$plumbing_contact,$sms_body);
$sms_body = str_replace('$plumbing_contact_number',$plumbing_contact_number,$sms_body);
$sms_body = str_replace('ELECTRICAL ACCOUNT (SANDEN ELECTRICIAN)',$electric_company,$sms_body);
$sms_body = str_replace('MAIN CONTACT of ACCOUNT (SANDEN ELECTRICIAN)',$electric_name,$sms_body);
$sms_body = str_replace('$electric_contact_number',$electric_contact_number,$sms_body);
$sms_body = str_replace('$main_contact',$pe_contact.' '.$pe_contact_number,$sms_body);
$sms_body = str_replace('$backup_contact',$pe_backup_contact.' '.$pe_backup_contact_number,$sms_body);
$sms_body = str_replace('$attachments',$html_photo,$sms_body);
$sms_body = str_replace('$link_upload_files',$link_upload_file_for_sms,$sms_body);
//get sms signture
$path_file_json_sms_signture = dirname(__FILE__) .'/../../../../../custom/modules/Users/json_sms_signture.json';
$json_data = json_decode(file_get_contents($path_file_json_sms_signture),true);
if(isset($json_data)) {
    if(isset($json_data['1588651777'])) {
        $sms_signture = $json_data['1588651777']['content'];
    }else{
        $sms_signture = $current_user->sms_signature_c;
    }
}
$email->sms_signture = $sms_signture;
$email->sms_content = trim(strip_tags(html_entity_decode(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $sms_body))));
$email->sms_message = trim(strip_tags(html_entity_decode(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $sms_body.' '.$sms_signture))));

$email->id = create_guid();
$email->new_with_id = true;
 // Add PO attachment 
$bean = new PO_purchase_order();
//VUT-Add attachment from Document
if ($_REQUEST['product_type'] = "quote_type_sanden" && $mail_format == "plumber") {
    attachmentFileForPOSandenPlumping($email);
}
//VUT-Add attachment from Document
if(isset($_REQUEST['po_record']) && $_REQUEST['po_record'] !== ""){
    $bean = $bean->retrieve($_REQUEST['po_record']);
    generatePOPDF($_REQUEST['po_record'], $attached_file_name);
    $content_file_pdf_PO = result_pdf($_REQUEST['po_record'], $attached_file_name);
    $body_ = $email->description_html;
    // $body_ .= ('Content pdf :');
    $body_ .= $content_file_pdf_PO;
    $email->description_html = $body_ ;
    $email->description = $body_;
    $email->email_return_module = 'PO_purchase_order';
    $email->email_return_id = $bean->id;
    
    global $sugar_config;
    global $current_user;
    $note = new Note();
    $note->modified_user_id = $current_user->id;
    $note->created_by = $current_user->id;
    $note->name = $attached_file_name;
    $note->parent_type = 'Emails';
    $note->parent_id = $email->id;
    $note->file_mime_type = mime_content_type ( $sugar_config['upload_dir'] . $attached_file_name );
    $note->filename = $attached_file_name; 
    $noteId = $note->save();

    if($noteID !== false && !empty($noteId)) {
        rename($sugar_config['upload_dir'] . $attached_file_name, $sugar_config['upload_dir'] . $note->id);
        $email->attachNote($note);
    } else {
        $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
    }
    
}
/*
*/
/*
$mail->prepForOutbound(); 
$mail->AddAddress('info@pure-electric.com.au');
$mail->AddAddress('binhdigipro@gmail.com');
*/
/*
if($pe_email!=""){
    $mail->AddAddress($pe_email);
}
*/
$contact = new Contact;
if ($contact->retrieve($_GET["plumber_contact_id"])) {
    $email->parent_type = 'Contacts';
    $email->parent_id = $contact->id;

    if (!empty($contact->email1)) {
        //$email->to_addrs_emails = $contact->email1 . ";";
        //$email->to_addrs = $contact->name . " <" . $contact->email1 . ">";
        $email->to_addrs_names = $contact->name . " <" . $contact->email1 . ">";
        $email->parent_name = $contact->name;
    }
}

if ($contact->retrieve($_GET["electricial_contact_id"])) {
    $email->parent_type = 'Contacts';
    $email->parent_id = $contact->id;

    if (!empty($contact->email1)) {
        //$email->to_addrs_emails = $contact->email1 . ";";
        //$email->to_addrs = $contact->name . " <" . $contact->email1 . ">";
        $email->to_addrs_names = $contact->name . " <" . $contact->email1 . ">";
        $email->parent_name = $contact->name;
    }
}

$email->type = "draft";
$email->status = "draft";

global $current_user;
$showFolders = sugar_unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));
if (empty($showFolders)) {
    $showFolders = array();
}
// personal accounts
if ($current_user->hasPersonalEmail()) {
    $personals = retrieveByGroupId($current_user->id);
}
$inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
if(count($personals)) foreach($personals as $personal) {
    if (in_array($personal->id, $showFolders)) {
        if(strpos(strtolower($personal->name), "account") !== false) {
            $inboundEmailID = $personal->id;
        } 
    }
}
$email->mailbox_id = $inboundEmailID;

global $sugar_config;
if($mail_format != 'custommer') {
    $current_file_path = dirname(__FILE__);
    $current_file_path .= '/server/php/files/' . $file_dir;
    if(is_dir ( $current_file_path )){
        $file_array = scandir($current_file_path);
        foreach ($file_array as $file) { 
            if (!is_dir($file)) {
                if($mail_format == "electrical"){
                    if (
                        ((stripos(strtolower($file), $invoice_number.'_old') !== FALSE) && (stripos(strtolower($file), $invoice_number.'_hws') !== FALSE)) 
                    // (stripos(strtolower($file), 'docket') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'_photo') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'_new') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'diagram') !== FALSE)
                    || (stripos(strtolower($file), 'switchboard') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'PCOC') !== FALSE)
                    || (stripos(strtolower($file), 'proposed') !== FALSE && stripos(strtolower($file), 'install') !== FALSE && stripos(strtolower($file), 'location') !== FALSE)
                    ){
                        $note = new Note();
                        $note->modified_user_id = $current_user->id;
                        $note->created_by = $current_user->id;
                        $note->name = $file;
                        $note->parent_type = 'Emails';
                        $note->parent_id = $email->id;
                        $note->file_mime_type = mime_content_type ( $current_file_path . '/' . $file );
                        $note->filename = $file; 
                        $noteId = $note->save();

                        if($noteID !== false && !empty($noteId)) {
                            copy($current_file_path . '/' . $file, $sugar_config['upload_dir'] . $note->id);
                            $email->attachNote($note);
                            // $email->addAttachment($current_file_path . '/' . $file);
                        } else {
                            $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
                        }
                    }
                }
                else{
                    if (
                        ((stripos(strtolower($file), $invoice_number.'_old') !== FALSE) && (stripos(strtolower($file), $invoice_number.'_hws') !== FALSE)) 
                    // (stripos(strtolower($file), 'docket') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'_photo') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'_new') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'diagram') !== FALSE)
                    || (stripos(strtolower($file), 'switchboard') !== FALSE)
                    // || (stripos(strtolower($file), $invoice_number.'PCOC') !== FALSE)
                    || (stripos(strtolower($file), 'proposed') !== FALSE && stripos(strtolower($file), 'install') !== FALSE && stripos(strtolower($file), 'location') !== FALSE)
                    ){
                        $note = new Note();
                        $note->modified_user_id = $current_user->id;
                        $note->created_by = $current_user->id;
                        $note->name = $file;
                        $note->parent_type = 'Emails';
                        $note->parent_id = $email->id;
                        $note->file_mime_type = mime_content_type ( $current_file_path . '/' . $file );
                        $note->filename = $file; 
                        $noteId = $note->save();

                        if($noteID !== false && !empty($noteId)) {
                            //copy ($current_file_path . '/' . $file, $sugar_config['upload_dir'] . 'attachfile.pdf');
                            copy($current_file_path . '/' . $file, $sugar_config['upload_dir'] . $note->id);
                            $email->attachNote($note);
                            // $email->addAttachment($current_file_path . '/' . $file);
                        } else {
                            $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
                        }
                        //$mail->addAttachment($current_file_path . '/' . $file);
                    }
                }

            }
        }
    }
}

try {
    $email->save(false);
    global $sugar_config;
    echo '/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $bean->module_dir . '&return_action=DetailView&return_id=' . $bean->id . '&return_action=DetailView&record=' . $email->id .'&email_template_id='.$email_template_id.'&sms_template_id='.$sms_template_id;
    // echo '/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $bean->module_dir . '&return_action=DetailView&return_id=' . $bean->id . '&return_action=DetailView&record=' . $email->id;
        //$sent = $mail->Send();
       
        unlink($sugar_config['upload_dir'] . $attached_file_name);
    } catch (MyException $e) {
        // rethrow it
        throw $e->getMessage();
    }

die();


/**
 * VUT-PO type sanden_supply, change template
 * @param string $data body_html template
 */
function changeToSanden($text) {
    $accountNumber = 'BEY001';
    $text = preg_replace('/(?si)<tr id="sugar_text_label_supply_infomation"+?>(.*)<=?\/tr>/U', '', $text);
    $text = preg_replace('/(?si)<tr id="sugar_text_supply_infomation"+?>(.*)<=?\/tr>/U', '', $text);
    $table ='<table style="text-align: center; width: 750px; border: 0pt none; border-spacing: 0pt;"><tbody style="text-align: left;">';
    $label = '<tr id="sugar_text_label_supply_infomation">
                <td style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 20%;white-space: nowrap;"><span>$custom_title_install_date</span></td>
                <td style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 30%;white-space: nowrap;">Freight Company</td>
                <td style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 30%;white-space: nowrap;">Supplier Order Number</td>
                <td style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 20%;white-space: nowrap;">Account Number</td>
            </tr>';

    $info = '<tr id="sugar_text_supply_infomation">
                <td style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 20%; vertical-align: top; text-align: left;white-space: nowrap;"><span>$date_follow_type_date</span></td>
                <td style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 30%; vertical-align: top; text-align: left;white-space: nowrap;">$po_freight_company_c</td>
                <td style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 30%; vertical-align: top; text-align: left;white-space: nowrap;">$po_supplier_order_number_c</td>
                <td style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 20%; vertical-align: top; text-align: left;white-space: nowrap;">'.$accountNumber.'</td>
            </tr>';
    $table .= $label.$info.'</tbody></table>';

    $text = preg_replace('/<div id="sugar_text_changeToSanden">(.*?)<\/div>/s',$table, $text);
    return $text;
}

/**
 * VUT- Attachment file from Document to PO Sanden Plumping
 * @param Email $email
 */
function attachmentFileForPOSandenPlumping($email) {
    global $sugar_config;
    global $current_user;
    // $idDocument='3601fe65-1e3d-724a-189c-5f7a87624d4f '; 
    // $idDocument='ee488072-583f-719b-5e27-5f7aeb91662d'; //devel
    $idDocument='e77b2aeb-0d07-13e1-283a-5f7a8084d755'; //server
    $db_document = DBManagerFactory::getInstance();
    $sql_docs = "SELECT document_revisions.filename as filename, document_revisions.id as id_file 
                FROM document_revisions INNER JOIN documents ON documents.id = document_revisions.document_id 
                WHERE documents.id = '$idDocument'";
    $ret =$db_document->query($sql_docs);
    while ($row = $ret->fetch_assoc()) {
        $filename = $row['filename'];
        $file_id = $row['id_file'];
        $local_location = "upload://{$file_id}";
        $mime_type = mime_content_type($local_location);
        $note = new Note();
        $note->modified_user_id = $current_user->id;
        $note->created_by = $current_user->id;
        $note->name = $filename;
        $note->parent_type = 'Emails';
        $note->parent_id = $email->id;
        $note->file_mime_type = $mime_type;
        $note->filename = $filename; 
        $noteId = $note->save();
        if($noteId !== false && !empty($noteId)) {
            copy($sugar_config['upload_dir'] . $file_id, $sugar_config['upload_dir'] . $note->id);
            $email->attachNote($note);
        } else {
            $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
        }
    }

}

?>