<?php
use setasign\Fpdi\Fpdi;
require_once('fpdf.php');

require_once('src/autoload.php');
/*
$id = $_REQUEST['id'];
$invoices = new AOS_Invoices();
$invoices->retrieve($id);
$test = $invoices ->id;
echo $test;
$folder = dirname(__FILE__)."/custom/include/SugarFields/Fields/Multiupload/server/php/files/129bfece-c112-4d51-9d91-3adbc0a3ceb2/invoices.pdf";


  */
$id = $_REQUEST['id'];
$create = mkdir('custom/include/SugarFields/Fields/Multiupload/'."/server/php/files/".$id);
global $sugar_config;
$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
//$pdf->setSourceFile('/custom/modules/AOS_Invoices/text/ttt.pdf');
$pdf->setSourceFile('custom/modules/AOS_Invoices/text/ttt.pdf');
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx);

// now write some text above the imported page
$pdf->SetFont('Helvetica');
$pdf->SetTextColor(0, 0, 0);

$pdf->Write($pdf->SetXY(35, 229), $_REQUEST['contact']);
$pdf->Write($pdf->SetXY(128, 227), $_REQUEST['account']);
$pdf->Write($pdf->SetXY(35, 245),$_REQUEST['street'].','.$_REQUEST['city'].','.$_REQUEST['state'].','.$_REQUEST['country']);

$fp = fopen('custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$id.'/invoices.pdf', 'wb');
fclose($fp);
$pdf->Output('custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$id.'/invoices.pdf', 'F');