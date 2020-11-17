<?php
if(!defined('sugarEntry'))
    define('sugarEntry', true);
require_once ('include/entryPoint.php');
global $db;

if($_SERVER["REQUEST_METHOD"]  == "POST") {
    $target_file = basename($_FILES["fileToUpload"]["name"]);
    $destination_dir = dirname(__FILE__)."/custom/include/SugarFields/Fields/Multiupload/server/php/html/remittance/";
    if (!file_exists($destination_dir)) {
        mkdir($destination_dir);
    }
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $destination_dir.$target_file);

    exec("which pdftohtml", $output, $returnStatus);
    exec("/bin/pdftohtml -nodrm ".escapeshellarg($destination_dir.$target_file)." ".$destination_dir.$target_file." 2>&1", $output);
    $destination_file = check_exist_file($destination_dir,'pdfs.html');

    if ($destination_file == "0") {
        echo "No file";
    } else {
        $destination_file = $destination_dir.$destination_file;
        $imageFileType = strtolower(pathinfo($destination_file,PATHINFO_EXTENSION));
        $output_array = array();
        $ahtml = file_get_contents($destination_file);

        preg_match('/<b>Amount Paid<\/b><br\/>(.*)<b>Total:<\/b><br\/>/sm', $ahtml, $output_array);
        $output = preg_replace('~[\r\n]+~', '', trim($output_array[1]));
        $array = explode("<br/>",$output);
        $data_return = array();
        for ($i=1 ; $i <= count(array_filter($array)) ; $i+=4 ) {
            array_push($data_return, $array[$i]);
        }
        $invoice_numbers = implode("','",$data_return);
        $data = showlink($invoice_numbers,$db);
        $tableHeader = '<thead><tr>'
                        .    '<th>Number</th>'
                        .    '<th>Link</th>'
                        .'</tr>.</thead>';
        $tableBody = '';
        foreach ($data as $key =>$value) {
            // $link_invoice= '';
            if ($value['link_invoice'] != '' && $value['link_xero'] == '') {
                $link_invoice = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$value['link_invoice'];
                $tableBody .= '<tr>'
                            . '<td>'.$key.'</td>'
                            . '<td><a target="_blank" href="'.$link_invoice.'">[Link invoice]</a><span>--</span>No Xero</td>'
                            .'</tr>';
            } else if ($value['link_invoice'] == '' && $value['link_xero'] != '') {
                $link_xero = 'https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='.$value['link_xero'];
                $tableBody .= '<tr>'
                            . '<td>'.$key.'</td>'
                            . '<td>No Invoice<span>--</span><a target="_blank" href="'.$link_xero.'">[Link xero]</a></td>'
                            .'</tr>';
            } else if ($value['link_invoice'] != '' && $value['link_xero'] != '') {
                $link_invoice = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$value['link_invoice'];
                $link_xero = 'https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='.$value['link_xero'];
                $tableBody .= '<tr>'
                            . '<td>'.$key.'</td>'
                            . '<td><a target="_blank" href="'.$link_invoice.'">[Link invoice]</a><span>--</span><a target="_blank" href="'.$link_xero.'">[Link xero]</a></td>'
                            .'</tr>';
            } else {
                $tableBody .= '<tr>'
                            . '<td>'.$key.'</td>'
                            . '<td><span>No Invoice -- No Xero</span></td>'
                            .'</tr>';
            }
        }
        $html_link = '<div id="showPopup"><table style=" border: 1px solid black;">';
        $html_link.= $tableHeader.$tableBody;

        $html_link .= '</table></div>';

        rrmdir($destination_dir);
        echo $html_link;
    }
}
//Function
function showlink($invoice_numbers,$db) {
    
    $sql = "SELECT aos_invoices.id, aos_invoices.number, aos_invoices_cstm.xero_invoice_c
            FROM aos_invoices
            LEFT JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
            WHERE aos_invoices.number IN ('".$invoice_numbers."')
            ORDER BY aos_invoices.number ASC";

    $result = $db->query($sql);
    $data=array();
    while ($row = $db->fetchByAssoc($result)) {
        // $data[$row['number']]['link_invoice'] = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id'];
        // $data[$row['number']]['link_xero'] = 'https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='.$row['xero_invoice_c'];
        $data[$row['number']]['link_invoice'] = $row['id'];
        $data[$row['number']]['link_xero'] = $row['xero_invoice_c'];
    }
    return $data;
}

//VUT-S-Check file Image Design
function check_exist_file($source, $string) {
    $file_array = scandir($source);
    $file_array = array_diff($file_array, array('.', '..'));
    $result = array();
    foreach($file_array as $file){
        if (strpos(strtolower($file), $string) !== false) {
            $result = $file;  
            return $result;
        }
    }
    return '0';
}

//Remove folder
function rrmdir($src) {
    if (file_exists($src)) {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test upload</title>
</head>
<body>
    <div id="button">
        <form action="" method="post" enctype="multipart/form-data">
            <input type='file' name="fileToUpload" id="fileToUpload"/>
            <input type="submit" value="Submit" name="upload_button">  
        </form>
    </div>
</body>
</html>


