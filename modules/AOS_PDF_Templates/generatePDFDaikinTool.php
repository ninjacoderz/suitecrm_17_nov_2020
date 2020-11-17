<?php
header('Access-Control-Allow-Origin: *');
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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
// $_REQUEST['uid'] = "55e3ff94-1031-36c0-d67f-5e9ff1409403";
// $_REQUEST['templateID'] = "a71896a6-3695-4f60-c55e-5f54d95d16a7";
// $_REQUEST['daikin_design'] = "text.png";
// $_REQUEST['pre_install_photos_c'] = "9ebf7a8d-83b4-4f98-8ef4-596e115e7035";
// $_REQUEST['name_photo'] = "Daikin_Design_923002.png";

if (!isset($_REQUEST['uid']) || empty($_REQUEST['uid']) || !isset($_REQUEST['templateID']) || empty($_REQUEST['templateID'])) {
    die('Error retrieving record. This record may be deleted or you may not be authorized to view it.');
}

// $level = error_reporting();
// $state = new \SuiteCRM\StateSaver();
// $state->pushErrorLevel();
error_reporting(0);
require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
require_once('modules/AOS_PDF_Templates/templateParserQuoteForm.php');
require_once('modules/AOS_PDF_Templates/sendEmail.php');
require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
require_once('include/SugarPHPMailer.php');
// $state->popErrorLevel();
// if ($level !== error_reporting()) {
//     throw new Exception('Incorrect error reporting level');
// }

global $mod_strings, $sugar_config;

$template = new AOS_PDF_Templates();
$template->retrieve($_REQUEST['templateID']);

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
//thienpb fix
$short_description_c =  $template->short_description_c ;// preg_replace($search, $replace, $template->short_description_c);
// $custom_paid_amount $custom_due_amount $custom_payments_received $bean->payments_c
$short_description_bottom_c = $template->short_description_bottom_c;

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

$quote = new AOS_Quotes();
$quote->retrieve($_REQUEST['uid']);
if($quote->id == '') {
    echo json_encode(array('msg'=>'error'));
    die();
};
$today_strtotime = date("d-m-Y");
$text = str_replace('$customer_name', $quote->account_firstname_c.' '.$quote->account_lastname_c, $text);
$text = str_replace('$date_time', $today_strtotime, $text);
$text = str_replace('$customer_address', $quote->billing_address_street.' '.$quote->billing_address_city.' '.$quote->billing_address_state.' '.$quote->billing_address_postalcode, $text);
$text = str_replace('$daikin_design', '<img style="max-width: 1024px;" src="'.$_REQUEST['daikin_design'].'" alt="" /> <br /> <br />', $text);

$converted = templateParserQuoteForm::parse_template_quote_form($text, $object_arr);
$header = templateParserQuoteForm::parse_template_quote_form($header, $object_arr);
$footer = templateParserQuoteForm::parse_template_quote_form($footer, $object_arr);

$printable = str_replace("\n", "<br />", $converted);
$task = "pdf";

if ($task == 'pdf' || $task == 'emailpdf') {
    
    ob_clean();
    try {
        $orientation = ($template->orientation == "Landscape") ? "-L" : "";
        $pdf = new mPDF('en', $template->page_size . $orientation, '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
        $pdf->SetAutoFont();
        $pdf->SetHTMLHeader($header);
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($printable);

        $arrName = explode('.', $_REQUEST['name_photo']);
        $name_file = $arrName[0].'_for_Quote_'.$quote->number.'.pdf';

        echo base64_encode($pdf->Output($name_file, "S"));

        // $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
        // $dirName        = $_REQUEST['pre_install_photos_c'];
        // $folderName     = $path . $dirName . '/';
        // if (!file_exists($folderName)) {
        //     mkdir($path . $dirName, 0777, true);
        // }
        

        // $fp = fopen($folderName . $name_file, 'wb');
        // fclose($fp);
        // // $pdf->Output($folderName . $name_file, 'F');
        // var_dump(base64_encode($pdf->Output($name_file, "S")));

        // echo 'https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $dirName. '/'.$name_file;
        // echo 'http://new.suitecrm-pure.com/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $dirName. '/'.$name_file;

    } catch (mPDF_exception $e) {
        echo $e;
    }
}

