<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
function isStartsWith($haystack, $needle)
{
    if (!is_string($haystack)) {
        //throw new \InvalidArgumentException('StringValidator::startsWith $haystack must be a string');
    }

    if (!is_string($needle)) {
        //throw new \InvalidArgumentException('StringValidator::startsWith $needle must be a string');
    }

    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}
if (!isset($_REQUEST['uid']) || empty($_REQUEST['uid']) || !isset($_REQUEST['templateID']) || empty($_REQUEST['templateID'])) {
    die('Error retrieving record. This record may be deleted or you may not be authorized to view it.');
}
// $level = error_reporting();
// $state = new \SuiteCRM\StateSaver();
// $state->pushErrorLevel();
error_reporting(0);
require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
require_once('modules/AOS_PDF_Templates/templateParser.php');
require_once('modules/AOS_PDF_Templates/templateParserQuoteForm.php');
require_once('modules/AOS_PDF_Templates/sendEmail.php');
require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
require_once('include/SugarPHPMailer.php');
// $state->popErrorLevel();
// if ($level !== error_reporting()) {
//     throw new Exception('Incorrect error reporting level');
// }

global $mod_strings, $sugar_config;

$bean = BeanFactory::getBean($_REQUEST['module'], $_REQUEST['uid']);

if (!$bean) {
    sugar_die("Invalid Record");
}

$task = $_REQUEST['task'];
$variableName = strtolower($bean->module_dir);
$lineItemsGroups = array();
$lineItems = array();

$sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $bean->object_name . "' AND pg.parent_id = '" . $bean->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
$res = $bean->db->query($sql);
while ($row = $bean->db->fetchByAssoc($res)) {
    $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
    $lineItems[$row['id']] = $row['product_id'];
}


$template = BeanFactory::newBean('AOS_PDF_Templates');
$template->retrieve($_REQUEST['templateID']);

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
date_default_timezone_set('Australia/Sydney');

$header = preg_replace($search, $replace, $template->pdfheader);
$footer = preg_replace($search, $replace, $template->pdffooter);
//thienpb fix
$short_description_c =  $template->short_description_c ;// preg_replace($search, $replace, $template->short_description_c);
// $custom_paid_amount $custom_due_amount $custom_payments_received $bean->payments_c
$short_description_bottom_c = $template->short_description_bottom_c;
$customer_payments = json_decode(urldecode($bean->payments_c));
$custom_payments_received = "";
if(count($customer_payments)){
    $custom_total_payment = 0;
    $custom_payments_received = "<p>Payments Received:</p>";
    foreach($customer_payments as $payment){
        $custom_total_payment += str_replace(array(","), "", $payment->payment_amount);
        $date = date_create_from_format('d/m/Y',$payment->payment_date);
        if( $date){
            //case 1: format d/m/Y
           $date_received = date_format($date, "d M Y");
        }else{
           //case 2: format 2020-02-07T08:00:00
           $date = strtotime($payment->payment_date);
           $date_received = date('d M Y', $date);
        }
        $custom_payments_received .= "<p style='font-size:10px;'>".$date_received." BSB Transfer REF: ".$payment->payment_brankref." $".$payment->payment_amount." Deposit ".$payment->payment_description." </p>";
    }
}

$custom_due_amount = str_replace(array(","), "",$bean->total_amount) -  $custom_total_payment;
$custom_total_payment = number_format($custom_total_payment,2);
$custom_due_amount = number_format($custom_due_amount,2);
$date_install = explode(" ",$bean->installation_date_c);
$date_install = $date_install[0];

$today_strtotime = strtotime(date("d-m-Y"));
$date_install_strtotime = str_replace('/','-',$date_install);
$date_install_strtotime = strtotime($date_install_strtotime);

//add install date into email invoice
$custom_install_info = '';
if($_REQUEST['module'] == 'AOS_Invoices') {
    $custom_install_info .= "<p style='font-size:10px;'>Installation Date: ".$date_install."</p>";
    $custom_install_info .= "<p style='font-size:10px;'>Equipment and Model: ".$bean->sanden_model_c."</p>";
    $custom_install_info .= "<p style='font-size:10px;'>Tank Serial: ".$bean->sanden_tank_serial_c."</p>";
    $custom_install_info .= "<p style='font-size:10px;'>HP Serial: ".$bean->sanden_hp_serial_c."</p>";
    $custom_install_info .= "<p style='font-size:10px;'>Plumbing PIC: ".$bean->vba_pic_cert_c."</p>";
}

if($custom_due_amount == 0 && $custom_total_payment != 0){
    $name = explode(" ",$bean->billing_contact);
    $short_description_c = "Dear ".$name[0].",

    Acknowledge payment receipt thank you, please find your \$product_type invoice attached".(($bean->invoice_type_c!=="Methven")?" and certificates of compliance attached":"").". Congratulations for choosing the \$product_type, the most efficient in Australia and thank you for choosing PureElectric.";
    
    //If you would like to, we would most grateful if you took a brief moment to leave a review, it's one way we can continue to improve and continue to bring more renewables for everyone. Here is the link if you would like to leave a Google review -> https://bit.ly/2FG751o";
}
//dung code -- change update status quote
if($_REQUEST['task'] == 'emailpdf' && $_REQUEST['module'] == 'AOS_Quotes') {
    $quote = new AOS_Quotes();
    $quote->retrieve($_REQUEST['uid']);
    if($quote->id != ''){
        $quote->stage = 'Delivered';
        $quote->save();
    }
}
//Tritruong Update Quote
if ($_REQUEST['send_get_list'] == 'sanden_form'){
    $quote = new AOS_Quotes();
    $quote->retrieve($_REQUEST['uid']);

    $quote->name = str_replace('GUEST', $_REQUEST['firstname'].' '.$_REQUEST['lastname'], $quote->name);
    $quote->account_firstname_c = $_REQUEST['firstname'];
    $quote->account_lastname_c = $_REQUEST['lastname'];
    //Update Quote Suburb, Postcode, State
    $quote->save();
}
// dung code - change content short description when custom have "Next Payment Amount" > 0  && install date < date send email  && install date != '' - Button Email Invoice in DetailView Invoice
if($_REQUEST['task'] == 'emailpdf' && $_REQUEST['module'] == 'AOS_Invoices' && $_REQUEST['templateID'] == '91964331-fd45-e2d8-3f1b-57bbe4371f9c') {
    if((float)($bean->next_payment_amount_c) > 0 && $date_install_strtotime < $today_strtotime && $bean->installation_date_c !== '') {
        $short_description_c = "Dear \$customer_first_name, 
        
        Acknowledge your previous payment receipt thank you, please find your \$product_type invoice attached with your final payment now due as your install was successfully completed on date \$aos_invoices_due_date.
        
        Any questions please don't hesitate to email us (accounts@pure-electric.com.au) or give us a call 1300 86 78 73. 
        
        Please make the final payment of \$next_payment_amount_c to the Beyond The Grid Pty Ltd main account which was due on the day of install \$aos_invoices_due_date which has now past.
         
        Our bank details below:
        Name: Beyond The Grid Pty Ltd 
        BSB: 814282 Account Number: 50514152
        Amount: \$\$custom_due_amount 
        Reference: \$last_name_production";
    }

    //thienpb code add more link for final template invoice
    if($custom_due_amount == 0){
        if(strpos($bean->name,'Sanden') !== false){
            $custom_payments_received =  "<p>For Sanden Warranty details please review at https://www.sanden-hot-water.com.au/warranty-information</p>". $custom_payments_received;
        }
    }
    //change logic for Paul in invoice Email PDF
    if(isset($bean->next_payment_amount_c) && (float)($bean->next_payment_amount_c) != 0){
        $custom_due_amount = number_format($bean->next_payment_amount_c,2);
    }
}

//change logic for paul in print pdf Module Invoice
if($_REQUEST['task'] == 'pdf' && $_REQUEST['module'] == 'AOS_Invoices') {
    if(isset($bean->next_payment_amount_c) && (float)($bean->next_payment_amount_c) != 0){
        $custom_due_amount = number_format($bean->next_payment_amount_c,2);
    }
}

if($custom_total_payment == 0){
    $short_description_c = str_replace("Acknowledge payment receipt thank you, please", "Please", $short_description_c );
}
$short_description_c_mailing = "";
$freight_companys = array (
    'cope' => 'COPE Sensitive Freight',
    'meva' => 'MEVA Transport',
    'TNT'  => 'TNT Express',
    'Toll' => 'Toll Group',
    'Gilders' => 'Gilders Transport',
    'Collect' => 'Collect',
);
if($short_description_c != ""){
    $short_description_c = str_replace("\$aos_quotes", "\$" . $variableName, $short_description_c);
    $short_description_c = str_replace("\$aos_invoices", "\$" . $variableName, $short_description_c);
    $short_description_c = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $short_description_c);
    $short_description_c = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $short_description_c);
    $short_description_c = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $short_description_c);
    $short_description_c = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $short_description_c);
    $short_description_c = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $short_description_c);
    $short_description_c = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $short_description_c);
    $short_description_c = str_replace("\$next_payment_amount_c", "\$" . $variableName . "_next_payment_amount_c", $short_description_c);
    //$short_description_c = populate_group_lines($short_description_c, $lineItemsGroups, $lineItems);
    // load product here :
    //reset($array);

    // CUSTOM Replacement

    $group_id = key($lineItemsGroups);
    $group = new AOS_Line_Item_Groups();
    $group->retrieve($group_id);
    $product_type = current(explode(" ", $group->name));
    $short_description_c = str_replace("\$product_type", $product_type, $short_description_c);

    $name_explode = explode(" ",$bean->billing_account);
    if ($_REQUEST['send_get_list'] == 'sanden_form')
    {
        if($_REQUEST['firstname'] != '') {
            $first_name = $_REQUEST['firstname'];
        } else {
            $first_name = 'Guest';
        }
    } else {
        $first_name = current($name_explode);
    }
    $last_name = end($name_explode);

    $short_description_c = str_replace("\$customer_first_name", $first_name, $short_description_c);
    //VUT-S-change $sanden_dispatch_date $sanden_freight_company
    if ($_REQUEST['module'] == 'PO_purchase_order' && ($_REQUEST['templateID']=='1876bff3-5e6b-e49c-e8a1-5e2530fba9ca' || $_REQUEST['templateID']=='3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5')) {
        if ($bean->po_type_c == "sanden_supply") {
            $dispatch_date ='';
            $freight_company='';
            if ($bean->dispatch_date_c!= '') {
                $dispatch_date = 'Please dispatch '.$bean->dispatch_date_c.' thank you.';
            }
            if ($bean->freight_company_c != '') {
                $freight_company = 'Delivery via '.$freight_companys[$bean->freight_company_c].'.';
            }
            if ($bean->supplier_order_number_c != '') {
                $short_description_c = str_replace("\$sanden_supplier_order_number", "This is an EXISTING Sanden order #".$bean->supplier_order_number_c, $short_description_c);
            } else {
                $short_description_c = str_replace("\$sanden_supplier_order_number", "This is a NEW Sanden order", $short_description_c);
            }
            $short_description_c = str_replace("\$sanden_dispatch_date", $dispatch_date, $short_description_c);
            $short_description_c = str_replace("\$sanden_freight_company", $freight_company, $short_description_c);
        } else {
            $short_description_c = str_replace("\$sanden_dispatch_date", '', $short_description_c);
            $short_description_c = str_replace("\$sanden_freight_company", '', $short_description_c);
            $short_description_c = str_replace("\$sanden_supplier_order_number", '', $short_description_c);
        }
    }
    //VUT-E-change $sanden_dispatch_date $sanden_freight_company


    // get Product
    $first_product = new AOS_Products();
    $first_product->retrieve(current($lineItems));
    preg_match('/[a-zA-Z]{3}/', $first_product->name, $match_firstchars);
    preg_match('/[0-9]{3}/', $first_product->name, $match_firstnumbers);
    //thien fix add quote number
    if($bean->module_dir == "AOS_Invoices"){
        $reference = "Inv".$bean->number.$match_firstchars[0].$match_firstnumbers[0].$last_name;
    }
    else $reference = $bean->number.$match_firstchars[0].$match_firstnumbers[0].$last_name;
    
    $reference = str_replace(" ", "_", $reference);
    if(strlen($reference) >20){
        $reference = substr($reference,0,20);
    }
    $short_description_c = str_replace("\$last_name_production", $reference, $short_description_c);

    //VUT-S- Add link upload file/photo SANDEN/DAIKIN for Send Email Quote
    if ($_REQUEST['module'] == 'AOS_Quotes') {
        if ($bean->quote_type_c == 'quote_type_sanden') {
            $url_acceptance = '<a target="_blank" href="https://pure-electric.com.au/pe-sanden-quote-form/acceptance?quote-id='.$bean->id.'">here is our URL link.</a>';
            $url_upload_photo = '<a target="_blank" href="https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id='.$bean->id.'">here is the link to upload photos</a>';
        } else if ($bean->quote_type_c == 'quote_type_daikin' || $bean->quote_type_c == 'quote_type_nexura') {
            $url_acceptance = '<a target="_blank" href="https://pure-electric.com.au/pedaikinform-new/acceptance?quote-id='.$bean->id.'">here is our URL link.</a>';
            $url_upload_photo = '<a target="_blank" href="https://pure-electric.com.au/pedaikinform-new/confirm?quote-id='.$bean->id.'">here is the link to upload photos</a>';
        }
        $short_description_c = str_replace("\$link_acceptance", $url_acceptance, $short_description_c);
        $short_description_c = str_replace("\$link_upload_photo", $url_upload_photo, $short_description_c);


    }
    //VUT-E- Add link upload file/photo SANDEN/DAIKIN in Quote

    //if(isset ($bean->total_amt) && isset($bean->tax_amount))
    $need_paid = 0;
    if($bean->total_amt+$bean->tax_amount < 1000){
        $need_paid = $bean->total_amt+$bean->tax_amount;
    }
    else {
        $need_paid = ceil( 0.7 * ($bean->total_amt+$bean->tax_amount) / 100 ) * 100;
    }
    $short_description_c = str_replace("\$patial_payment", format_number($need_paid), $short_description_c);
    // custom replace 0.00 to - in every pdf quote, use function from Form of Tri
    if($_REQUEST['module'] == 'AOS_Quotes'|| $_REQUEST['module'] == 'AOS_Invoices'){
        $short_description_c_converted = templateParserQuoteForm::parse_template_quote_form($short_description_c, $object_arr);
    }else{
        $short_description_c_converted = templateParser::parse_template($short_description_c, $object_arr);
    }
    $short_description_c_converted = trim($short_description_c_converted);
    $short_description_c_mailing = trim(str_replace("\n", "<br />", $short_description_c_converted));
}
//thien fix
$short_description_bottom_c_mailing = "";
if($short_description_bottom_c != ""){
    $short_description_bottom_c = str_replace("\$aos_quotes", "\$" . $variableName, $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$aos_invoices", "\$" . $variableName, $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $short_description_bottom_c);
    $short_description_bottom_c = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $short_description_bottom_c);
    //$short_description_bottom_c = populate_group_lines($short_description_bottom_c, $lineItemsGroups, $lineItems);
    // load product here :
    //reset($array);

    // CUSTOM Replacement

    $group_id = key($lineItemsGroups);
    $group = new AOS_Line_Item_Groups();
    $group->retrieve($group_id);
    $product_type = current(explode(" ", $group->name));
    $short_description_bottom_c = str_replace("\$product_type", $product_type, $short_description_bottom_c);

    $name_explode = explode(" ",$bean->billing_account);
    if ($_REQUEST['send_get_list'] == 'sanden_form')
    {
        if($_REQUEST['firstname'] != '') {
            $first_name = $_REQUEST['firstname'];
        } else {
            $first_name = 'Guest';
        }
    } else {
        $first_name = current($name_explode);
    }
    $last_name = end($name_explode);

    $short_description_bottom_c = str_replace("\$customer_first_name", $first_name, $short_description_bottom_c);
    // get Product
    $first_product = new AOS_Products();
    $first_product->retrieve(current($lineItems));
    preg_match('/[a-zA-Z]{3}/', $first_product->name, $match_firstchars);
    preg_match('/[0-9]{3}/', $first_product->name, $match_firstnumbers);
    //thien fix add quote number
    $reference = $bean->number.$match_firstchars[0].$match_firstnumbers[0].$last_name;
    $reference = str_replace(" ", "_", $reference);
    if(strlen($reference) >20){
        $reference = substr($reference,0,20);
    }

    $short_description_bottom_c = str_replace("\$last_name_production", $reference, $short_description_bottom_c);
    //if(isset ($bean->total_amt) && isset($bean->tax_amount))
    $need_paid = 0;
    if($bean->total_amt+$bean->tax_amount < 1000){
        $need_paid = $bean->total_amt+$bean->tax_amount;
    }
    else {
        $need_paid = ceil( 0.7 * ($bean->total_amt+$bean->tax_amount) / 100 ) * 100;
    }
    $short_description_bottom_c = str_replace("\$patial_payment", format_number($need_paid), $short_description_bottom_c);
    // custom replace 0.00 to - in every pdf quote, use function from Form of Tri
    if($_REQUEST['module'] == 'AOS_Quotes' || $_REQUEST['module'] == 'AOS_Invoices'){
        $short_description_bottom_c_converted = templateParserQuoteForm::parse_template_quote_form($short_description_bottom_c, $object_arr);
    }else{
        $short_description_bottom_c_converted = templateParser::parse_template($short_description_bottom_c, $object_arr);
    }
    $short_description_bottom_c_converted = trim($short_description_bottom_c_converted);
    $short_description_bottom_c_mailing = trim(str_replace("\n", "<br />", $short_description_bottom_c_converted));
}

$text = preg_replace($search, $replace, $template->description);
//$text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
//dung code - new logic  because  file pdf template trim tag '<pagebreak>' -> ''
$text = str_replace("--pagebreak--", "<pagebreak />", $text);
$text = preg_replace_callback(
    '/\{DATE\s+(.*?)\}/',
    function ($matches) {
        return date($matches[1]);
    },
    $text
);

//thienpb code -- new temp for system owner tax invoice db3de434-eb84-cc10-bb23-5be0192237b3
if($_REQUEST['module'] == 'AOS_Invoices' && $_REQUEST['templateID'] == 'db3de434-eb84-cc10-bb23-5be0192237b3') {
    $sql = "SELECT product_qty,product_cost_price FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$bean->id."' AND product_id = '4efbea92-c52f-d147-3308-569776823b19' AND deleted = 0";
    $db = DBManagerFactory::getInstance();
    $result = $db->query($sql);
    $row = $db->fetchByAssoc($result);

    $system_products_unit_price = str_replace("-", "",number_format(($row['product_cost_price']/1.1),2));
    $system_productst_total_price = str_replace(',','',number_format($system_products_unit_price* $row['product_qty'],2));
    $gst = number_format($system_productst_total_price/10,2);
    $system_total_amount =  number_format(($system_productst_total_price+$gst),2);

    if($text != ""){
        $text = str_replace("\$system_product_stcs_qty",  number_format($row['product_qty'],0), $text);
        $text = str_replace("\$tank_serial_number", $bean->sanden_tank_serial_c, $text);
        $text = str_replace("\$system_products_unit_price", "\$" .$system_products_unit_price , $text);
        $text = str_replace("\$system_productst_total_price", "\$" .$system_productst_total_price , $text);
        $text = str_replace("\$total_ex_gst", "\$" .$system_productst_total_price , $text);
        $text = str_replace("\$gst", "\$" .$gst , $text);
        $text = str_replace("\$system_total_amount", "\$" .$system_total_amount, $text);
        $text = str_replace("\$system_balance_due", "\$" .$system_total_amount, $text);
    }
}

if($_REQUEST['module'] == 'AOS_Quotes') {
    if($bean->quote_note_c != '') {
        $text = str_replace('id="sugar_text_custom_aos_quotes_quote_note_c"' ,'style="text-align: justify; border-style: solid; border-width: .5px; padding: 2px 6px;"',$text);
        $text = str_replace('$aos_quotes_quote_note_c' ,preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $bean->quote_note_c),$text); 
    }else{
        $text = str_replace('Notes/Comments' ,'',$text);
    }
    
    $continue_link = '';
    $acceptance_link = '';
    if($_REQUEST['productType'] == 'quote_type_daikin' || $_REQUEST['productType'] == 'quote_type_nexura'){
        $continue_link = '<a href="https://pure-electric.com.au/pedaikinform-new/confirm?quote-id='.$bean->id.'" target="_blank">Continue to finalise your quote by uploading pictures and confirming positioning of your daikin system</a>';
        $acceptance_link = '<a href="https://pure-electric.com.au/pedaikinform-new/acceptance?quote-id='.$bean->id.'" target="_blank">Please click this link to accept your quote.</a>';
    }

    if($_REQUEST['productType'] == 'quote_type_sanden') {
        $continue_link = '<a href="https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id='.$bean->id.'" target="_blank">Continue to finalise your quote by uploading pictures and confirming positioning of your sanden system</a>';
        $acceptance_link = '<a href="https://pure-electric.com.au/pe-sanden-quote-form/acceptance?quote-id='.$bean->id.'" target="_blank">Please click this link to accept your quote</a>';
    }
    
    if($_REQUEST['productType'] == 'quote_type_methven') {
        $continue_link = '<a href="https://pure-electric.com.au/pe-methven/confirm?quote-id='.$bean->id.'" target="_blank">Continue to finalise your quote by uploading pictures and confirming positioning of your Methven system</a>';
        $acceptance_link = '<a href="https://pure-electric.com.au/pe-methven/acceptance?quote-id='.$bean->id.'" target="_blank">Please click this link to accept your quote</a>';
    }
    
    // $continue_link = '<a href="http://new.pure-electric.com/pe-sanden-quote-form/confirm?quote-id='.$quote->id.'" target="_blank">Continue to finalise your quote by uploading pictures and confirming positioning of your sanden system</a>';
    $text= str_replace("\$continue_link", $continue_link, $text);
    $text= str_replace("\$acceptance_link", $acceptance_link, $text);
}

//VUT-S-PO
if ($_REQUEST['module'] == 'PO_purchase_order' && ($_REQUEST['templateID']=='1876bff3-5e6b-e49c-e8a1-5e2530fba9ca' || $_REQUEST['templateID']=='3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5')) {
    if ($bean->po_type_c == "sanden_supply") {
        $text = changeToSanden($text);
        $text = str_replace("\$custom_title_install_date", 'Dispatch Date', $text);
        $text = str_replace("\$date_follow_type_date", $bean->dispatch_date_c, $text);
        $text = str_replace("\$po_freight_company_c", $freight_companys[$bean->freight_company_c], $text);
    } else if ($bean->po_type_c == "daikin_supply") {
        $text = str_replace("\$custom_title_install_date", 'Delivery Date', $text);
        $text = str_replace("\$date_follow_type_date", $bean->delivery_date_c, $text);
    } else if ($bean->po_type_c == "installer") {
        $text = str_replace("\$custom_title_install_date", 'Install Date', $text);
        $text = str_replace("\$date_follow_type_date", $bean->install_date, $text);
    } else {
        $text = str_replace("\$custom_title_install_date", 'Date', $text);
        $text = str_replace("\$date_follow_type_date",  '', $text);
    }
    $text = str_replace("\$po_supplier_order_number_c", $bean->supplier_order_number_c, $text);
    $invoiceId = $bean->aos_invoices_po_purchase_order_1aos_invoices_ida;
    $invoice = new AOS_Invoices();
    $invoice = $invoice->retrieve($invoiceId);
    if($invoice->id != '' && (strpos( strtolower($bean->name),'daikin' ) !== false)){
        $text = str_replace("\$aos_products_quotes_part_number","\$custom_model_number", $text);
    }

}
//VUT-E-PO


$text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
$text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
$text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
$text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
$text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
$text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
$text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
$text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

$text = populate_group_lines($text, $lineItemsGroups, $lineItems);

// Overide some value of $bean here
// BinhNT
if($bean->overide_due_date_c == 1){
    $installation_date = current(explode(" ", $bean->installation_date_c));
    $custom_due_date = $installation_date;
}
else{
    $custom_due_date  = $bean->due_date;
}
$text = str_replace("\$custom_due_date", $custom_due_date, $text);

// Tri truong Code add List choice Form - Sanden Form
if (!isset($_REQUEST['send_get_list'])){
    $text = str_replace("\$list_choice_form", '', $text);
    // Change Templata PDF - From Paul
    $text= str_replace("\$how_to_accept", '', $text);
    $text= str_replace("\$continue_link", '', $text);
}
// Tri truong Code add List choice Form - Sanden Form

// Need solve some custom variable here.

$text = str_replace("\$custom_paid_amount", $custom_total_payment, $text);
$text = str_replace("\$custom_due_amount", $custom_due_amount, $text);
$text = str_replace("\$custom_payments_received", $custom_payments_received, $text);
$text = str_replace("\$custom_install_info",$custom_install_info, $text);

//thienpb code -- replace how to play when status = paid
if($custom_due_amount == 0){
    $text = preg_replace('/<tr id="sugar_text_how_to_pay">(.*)<\/tr>/m','', $text);
}

//dung code --- QUOTEs:IF no STC or VEEC Rebate line item is used - not show STC/VEEC subsidy notes
if($_REQUEST['task'] == 'emailpdf' && $_REQUEST['module'] == 'AOS_Quotes') {
   $array_product_VEEC_STC = ['cbfafe6b-5e84-d976-8e32-574fc106b13f','4efbea92-c52f-d147-3308-569776823b19','a85d69eb-d43e-64df-d4c2-5a964c707cfe'];
   $check_exist_product_VEEC_STC = true;
   foreach ($lineItems as $key => $value) {
       if(in_array($value,$array_product_VEEC_STC)){
            $check_exist_product_VEEC_STC = false;
            break;
       }
   }
   if($check_exist_product_VEEC_STC){
        preg_match('/<div id="sugar_text_stc_veec">(.*?)<\/div>/s', $text, $matches_new);
        $text = str_replace($matches_new[0],'',$text);
   }

}

$short_description_c_mailing = str_replace("\$custom_due_amount", $custom_due_amount, $short_description_c_mailing);

//thien fix
$short_description_bottom_c_mailing = str_replace("\$custom_due_amount", $custom_due_amount, $short_description_bottom_c_mailing);

// custom_schedule_install_text
$custom_schedule_install_text = ".";
if($bean->installation_date_c){
    $install_date_explode = explode(" ", $bean->installation_date_c);
    $time_install_date = new DateTime(str_replace("/","-",$install_date_explode[0]));
    $custom_schedule_install_text = "Your scheduled install date is ".$time_install_date->format('l d/m/Y').".";
} else {
    $custom_schedule_install_text = "Can you please indicate your preferred (or non-preferred) dates for install within the next 3 weeks so we can schedule you in?";
}
$short_description_c_mailing = str_replace("\$custom_schedule_install_text", $custom_schedule_install_text, $short_description_c_mailing);
$short_description_c_mailing = str_replace(" on the day of install .", " on the day of install.", $short_description_c_mailing);

//thien fix
$short_description_bottom_c_mailing = str_replace("\$custom_schedule_install_text", $custom_schedule_install_text, $short_description_bottom_c_mailing);
$short_description_bottom_c_mailing = str_replace(" on the day of install .", " on the day of install.", $short_description_bottom_c_mailing);

//thienpb code for preview pdf
if($_REQUEST['task'] == 'pdf' && $_REQUEST['module'] == 'AOS_Quotes') {
    $array_product_VEEC_STC = ['cbfafe6b-5e84-d976-8e32-574fc106b13f','4efbea92-c52f-d147-3308-569776823b19','a85d69eb-d43e-64df-d4c2-5a964c707cfe'];
    $check_exist_product_VEEC_STC = true;
    foreach ($lineItems as $key => $value) {
        if(in_array($value,$array_product_VEEC_STC)){
             $check_exist_product_VEEC_STC = false;
             break;
        }
    }
    if($check_exist_product_VEEC_STC){
         preg_match('/<div id="sugar_text_stc_veec">(.*?)<\/div>/s', $text, $matches_new);
         $text = str_replace($matches_new[0],'',$text);
    }
 
 }
// custom replace 0.00 to - in every pdf quote, use function from Form of Tri
if($_REQUEST['module'] == 'AOS_Quotes' || $_REQUEST['module'] == 'AOS_Invoices'){
    $converted = templateParserQuoteForm::parse_template_quote_form($text, $object_arr);
    $header = templateParserQuoteForm::parse_template_quote_form($header, $object_arr);
    $footer = templateParserQuoteForm::parse_template_quote_form($footer, $object_arr);
}else{
    $converted = templateParser::parse_template($text, $object_arr);
    $header = templateParser::parse_template($header, $object_arr);
    $footer = templateParser::parse_template($footer, $object_arr);
}

$printable = str_replace("\n", "<br />", $converted);

if ($task == 'pdf' || $task == 'emailpdf') {
    //$file_name = $mod_strings['LBL_PDF_NAME'] . "_" . str_replace(" ", "_", $bean->name) . ".pdf";
    // BinhNT
    $file_name = preg_replace('/[^A-Za-z0-9-_.]/', '',$mod_strings['LBL_PDF_NAME'] . "_".(isset($bean->number)?($bean->number."_"):"") . str_replace(" ", "_", $bean->name) ."_".date("dMy"). ".pdf");

    ob_clean();
    try {
        $orientation = ($template->orientation == "Landscape") ? "-L" : "";
        $pdf = new mPDF('en', $template->page_size . $orientation, '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
        $pdf->SetAutoFont();
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($printable);
        if ($task == 'pdf') {
            //logic load file pdf from bulk action invoice
            if($_REQUEST['send_get_list'] == 'yes') {
                if (!file_exists($sugar_config['upload_dir'] .'/BulkActionInvoice')) {
                    mkdir($sugar_config['upload_dir'] .'/BulkActionInvoice', 0777, true);
                }
                //Thienpb code -- rename
                $fp = fopen($sugar_config['upload_dir'] .'/BulkActionInvoice/' .$file_name, 'wb');
                fclose($fp);
                $pdf->Output($sugar_config['upload_dir'] .'/BulkActionInvoice/' .$file_name, 'F');
                //die();

            } elseif ($_REQUEST['send_get_list'] == 'sanden_form'){
                $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
                $dirName        = $_REQUEST['pre_install_photos_c'];
                $folderName     = $path . $dirName . '/';
                if (!file_exists($folderName)) {
                    mkdir($path . $dirName, 0777, true);
                }
                $fp = fopen($folderName . 'quote_summary.pdf', 'wb');
                fclose($fp);
                $pdf->Output($folderName . 'quote_summary.pdf', 'F');

                // SEND MAIL TO CLIENT
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer();  
                $mail->setMailerForSystem();  
                $mail->From = 'info@pure-electric.com.au';  
                $mail->FromName = 'Pure Electric';  
                $mail->Subject = 'Pure Electric Quote #'.(isset($bean->number)?($bean->number):"") . ' for '.$quote->name;
                $bodytext .= $short_description_c_mailing."\n";
                $bodytext .= $printable;
                $mail->Body = $bodytext;
                $mail->IsHTML(true);
                $mail->AddAddress($_REQUEST['email_customer']);
                $mail->AddCC('paul.szuster@pure-electric.com.au');
                $mail->AddCC('info@pure-electric.com.au');
                // $mail->AddCC('tritruong.it@gmail.com');
                $mail->prepForOutbound();
                $mail->setMailerForSystem();  
                $sent = $mail->Send();

                echo 'https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $dirName. '/quote_summary.pdf';
                // echo 'http://locsuitecrm.com/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $dirName. '/quote_summary.pdf';
            } 
            // custom data return for module pe-accept-quote 
            elseif($_REQUEST['pe_accept_quote'] == 'true'){
                $data_return_pe_accept_quote['data_pdf'] =  $printable;
                $quote = new AOS_Quotes();
                $quote->retrieve($_REQUEST['uid']);
                $data_return_pe_accept_quote['data_quote'] = array(
                    'id' => $quote->id,
                    'name' => $quote->name,
                    'quote_number' => $quote->number,
                    'quote_name_customer' => $quote->account_firstname_c . ' ' . $quote->account_lastname_c,
                    'quote_name_billing_address' => $quote->billing_address_street . ' ' . $quote->billing_address_city. ' ' . $quote->billing_address_state .' ' .$quote->$billing_address_postalcode,
                );
                echo json_encode($data_return_pe_accept_quote);
            }
            else {
                if($_REQUEST['preview'] == 'yes'){
                    echo base64_encode($pdf->Output($file_name, "S"));
                }else{
                   $pdf->Output($file_name, "D");
                }
            }
        } else {
            $fp = fopen($sugar_config['upload_dir'] . 'attachfile.pdf', 'wb');
            fclose($fp);
            $pdf->Output($sugar_config['upload_dir'] . 'attachfile.pdf', 'F');
            $sendEmail = new sendEmail();
            // BinhNT
            $body_sms_link = "To firm the quote, upload photos via this link : https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id=".$quote->id;
            $sms_content = $short_description_c_mailing."\n\n".$body_sms_link."\n\n".$short_description_bottom_c_mailing;
            $short_description_c_mailing = "<table style='width:735px;font-family:Arial;text-align:center;'>
                                                        <tbody style='text-align:left'>
                                                        <tr> <td>".
                                            $short_description_c_mailing.
                                            "</td></tr>
                                            </tbody>
                                            </table>";
            if($short_description_bottom_c != ''){
                $short_description_bottom_c_mailing = "\n"."<table style='width:735px;font-family:Arial;text-align:center;'>
                                            <tbody style='text-align:left'>
                                            <tr> <td>".
                                $short_description_bottom_c_mailing.
                                "</td></tr>
                                </tbody>
                                </table>";
            }else{
                $short_description_bottom_c_mailing='';
            }
            $phone_number = "";

            $PO_purchase_order = new PO_purchase_order();
            $PO_purchase_order = $PO_purchase_order->retrieve($bean->id);
            $invoiceId = $PO_purchase_order->aos_invoices_po_purchase_order_1aos_invoices_ida;

            $invoice = new AOS_Invoices();
            $invoice = $invoice->retrieve($invoiceId);

            $contact = new Contact();

            if(strpos(strtolower($PO_purchase_order->name), "plumbing") !== false) {
                $contact = $contact->retrieve($invoice->contact_id4_c);
            } elseif (strpos(strtolower($PO_purchase_order->name), "electrical") !== false) {
                $contact = $contact->retrieve($invoice->contact_id_c);
            } else {
                $contact = $contact->retrieve($bean->billing_contact_id);
            }
            $phone_number = "";
            if(isStartsWith($contact->phone_mobile, "0") || isStartsWith($contact->phone_mobile, "61") || isStartsWith($contact->phone_mobile, "+61") )
            if(isset($contact->phone_mobile) && $contact->phone_mobile != ""){
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
            }
            // create content sms for email pdf AOS_Quote
            if($bean->module_dir == 'AOS_Quotes'){
                global $current_user;
                $product_type = $bean->quote_type_c;
                if (strpos(strtolower($product_type), "daikin") !== false) {
                    $smsTemplateID='4cfa35e8-c49c-6b3c-b6de-5de8b14da844' ;
                } elseif (strpos(strtolower($product_type), "sanden") !== false) {
                    $smsTemplateID='3e22c117-8ecf-597c-550b-5de8b1026b5b' ;
                } 
                else {
                    $smsTemplateID='' ;
                }

                if($smsTemplateID != '') {
                    $smsTemplate = BeanFactory::getBean(
                        'pe_smstemplate',
                        $smsTemplateID
                    );
                    $name_explode = explode(" ",$bean->billing_account);
                    $first_name_quote = current($name_explode);
            
                    $sms_content = trim(strip_tags(html_entity_decode(parse_sms_template($smsTemplate,$first_name_quote),ENT_QUOTES)));     
                }  
            } 

            $sendEmail->send_email($bean, $bean->module_dir, $short_description_c_mailing."\n".$printable.$short_description_bottom_c_mailing, $file_name, true, $sms_content, $phone_number,$smsTemplateID);
            //$sendEmail->send_email($bean, $bean->module_dir, '', $file_name, true);
        }
    } catch (mPDF_exception $e) {
        echo $e;
    }
} elseif ($task == 'email') {
    $sms_content = $short_description_c_mailing."\n".$short_description_bottom_c_mailing;
    $phone_number = "";
    $account = new Account();
    $account = $account->retrieve($bean->billing_account_id);
    if(isset($account->phone_office) && $account->phone_office != ""){
        $phone_number = $account->phone_office;
        $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $account->phone_office));
        $phone_number = preg_replace("/^61/", "+61", $phone_number);
    }
    $sendEmail = new sendEmail();
    $sendEmail->send_email($bean, $bean->module_dir, $printable, '', false, $sms_content, $phone_number);
}

function parse_sms_template($smsTemplate, $first_name)
{
    $body =  $smsTemplate->body_c;
    $body = str_replace("\$first_name", $first_name, $body);
    return $body;
}

function populate_group_lines($text, $lineItemsGroups, $lineItems, $element = 'table')
{
    $firstValue = '';
    $firstNum = 0;

    $lastValue = '';
    $lastNum = 0;

    $startElement = '<' . $element;
    $endElement = '</' . $element . '>';


    $groups = BeanFactory::newBean('AOS_Line_Item_Groups');
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
                // custom replace 0.00 to - in every pdf quote, use function from Form of Tri
                if($_REQUEST['module'] == 'AOS_Quotes' || $_REQUEST['module'] == 'AOS_Invoices'){
                    $text .= templateParserQuoteForm::parse_template_quote_form($groupPartTemp, $obb);
                }else{
                    $text .= templateParser::parse_template($groupPartTemp, $obb);
                }

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
    $product_quote = BeanFactory::newBean('AOS_Products_Quotes');
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

    $product = BeanFactory::newBean('AOS_Products');
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
                    // custom replace 0.00 to - in every pdf quote, use function from Form of Tri
                    if($_REQUEST['module'] == 'AOS_Quotes' || $_REQUEST['module'] == 'AOS_Invoices'){
                        $text .= templateParserQuoteForm::parse_template_quote_form($linePart, $obb);
                    }else{
                        $text .= templateParser::parse_template($linePart, $obb);
                    }
                    
                    //custom code-- model number PO PDF
                    if($_REQUEST['module'] == "PO_purchase_order") {
                        $PO_purchase_order = new PO_purchase_order();
                        $PO_purchase_order = $PO_purchase_order->retrieve($_REQUEST['uid']);
                        $invoiceId = $PO_purchase_order->aos_invoices_po_purchase_order_1aos_invoices_ida;
                        $invoice = new AOS_Invoices();
                        $invoice = $invoice->retrieve($invoiceId);
                        if($invoice->id != '' && (strpos( strtolower($PO_purchase_order->name),'daikin' ) !== false)){
                            //case product daikin
                            $daikinLineItems = $invoice->daikin_product_infomation_c;
                            $daikin_line_items = json_decode(urldecode($daikinLineItems), true);
                            $product_quote = new AOS_Products_Quotes();
                            $product_quote = $product_quote->retrieve($id);
                            $text = str_replace("\$custom_title_install_date",'Delivery Date' , $text);  
                            $text = str_replace("\$model_number",'Model Number' , $text);  
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
                                if($PO_purchase_order->po_type_c == 'sanden_supply'){
                                    $text = str_replace("\$custom_title_install_date",'Dispatch Date' , $text);
                                    $text = str_replace("\$custom_model_number",'' , $text);
                                    $text = str_replace("\$model_number",'Part Number' , $text);  
                                }
                            }
                        }
                        $text = str_replace("\$custom_title_install_date",'Install Date' , $text);
                        $text = str_replace("\$custom_model_number",'' , $text);
                        $text = str_replace("\$model_number",'Model Number' , $text);  
                    }
                   
                }
            }
        }

        for ($i = 1; $i < count($parts); $i++) {
            $text .= $parts[$i];
        }
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
    $product_quote = BeanFactory::newBean('AOS_Products_Quotes');
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

for ($i = 1; $i < count($parts); $i++) {        $text .= $parts[$i];
	}
    }
    return $text;
}

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
//Don't use
function OLD_changeToSanden($text) {
    $accountNumber = 'BEY001';
    $label = '<tr id="sugar_text_label_supply_infomation">
                    <td colspan="3" style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 25%;"><span>$custom_title_install_date</span></td>
                    <td colspan="3" style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 25%;">Freight Company</td>
                    <td colspan="3" style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 25%;">Supplier Order Number</td>
                    <td colspan="3" style="font-weight: bold; background-color: #b0c4de; padding: 2px 6px; border-style: solid; border-width: .5px; vertical-align: top; text-align: left; width: 25%;">Account Number</td>
                </tr>';
                
    $info = '<tr id="sugar_text_supply_infomation">
                    <td colspan="3" style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 25%; vertical-align: top; text-align: left;"><span>$date_follow_type_date</span></td>
                    <td colspan="3" style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 25%; vertical-align: top; text-align: left;">$po_freight_company_c</td>
                    <td colspan="3" style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 25%; vertical-align: top; text-align: left;">$po_supplier_order_number_c</td>
                    <td colspan="3" style="padding: 2px 6px; border-style: solid; border-width: .5px; width: 25%; vertical-align: top; text-align: left;">'.$accountNumber.'</td>
                </tr>';
    $text = preg_replace('/(?si)<tr id="sugar_text_label_supply_infomation"+?>(.*)<=?\/tr>/U', $label, $text);
    $text = preg_replace('/(?si)<tr id="sugar_text_supply_infomation"+?>(.*)<=?\/tr>/U', $info, $text);
    return $text;
}
