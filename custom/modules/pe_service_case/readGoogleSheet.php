<?php

set_time_limit ( 0 );
ini_set('memory_limit', '-1');

$db = DBManagerFactory::getInstance();
$query = 'SELECT pe_service_case.id, pe_service_case.name, pe_service_case.date_entered, pe_service_case_cstm.test_service_c FROM pe_service_case LEFT JOIN pe_service_case_cstm ON pe_service_case_cstm.id_c = pe_service_case.id';
$ret = $db->query($query);

while($row = $ret->fetch_assoc()){
  $result[] = $row;
}

print("<pre>".print_r($result,true)."</pre>");





// //SVG_S

// require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');

// $svg = '<svg width="400" height="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"><rect width="400" height="100" style="fill:rgb(0,0,255);stroke-width:10;stroke:rgb(0,0,0)"/></svg>';

// $pdf = new mPDF();
// $pdf->showImageErrors = true;
// // $imagescr = 'data:image/svg+xml;base64,'.base64_encode($svg);
// $html = '<table><tbody><tr><td>';
// $html .= $svg;
// // $html .='<img alt="" src="'.$imagescr.'"/>';
// $html .= '</td></tr></tbody>/<table>';

// // //theo mDPF
// $html = $pdf->AdjustHTML($html);


// // printf($html);

// $file = dirname(__FILE__) .'/all.html';
// file_put_contents($file,$html); //<p>line_group_items_name</p>

// $pdf->WriteHTML($html);
// $pdf->Output();

// die();
// //SVG_E
