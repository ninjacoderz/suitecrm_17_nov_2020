<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/custom/include/SugarFields/Fields/Multiupload/simple_html_dom.php');
$quote_id =  $_POST['quote_id'];
$products_return = array();

//get bean quote
$invoice = new AOS_Invoices();
$invoice->retrieve($_POST['invoice_id']);
$quote = new AOS_Quotes();
$quote->retrieve($quote_id);
if($invoice->id != "" ){
    if($_REQUEST['form_type'] == "worker_upload"){
        // $photos_return =[];
        // $db = DBManagerFactory::getInstance();
        // $sql = "SELECT * FROM notes WHERE  parent_id = '".$_POST['invoice_id']."' AND deleted = 0";
        // $res = $db->query($sql);
        // while ($row = $db->fetchByAssoc($res)) {
        //     $photos_return[] = array (
        //         'name' => $row['name'],
        //         'file_mime_type' =>  $row['file_mime_type'],
        //         'filename' =>  $row['filename'],
        //     );
        // }
        if($_REQUEST['worker_type'] == "Plumber" ){
            $po_id = $invoice->plumber_po_c;
        }else if($_REQUEST['worker_type'] == "Electrician" ){
            $po_id = $invoice->electrical_po_c;
        }else if($_REQUEST['worker_type'] == "Daikin installer" ){
            $po_id = $invoice->daikin_po_c;
        }
        $po_id_daikin = $invoice->daikin_po_c;
        $purchase = new PO_purchase_order();
        $purchase->retrieve($po_id);
        $data_return = array ();
        $account = new Account();
        $account->retrieve($invoice->billing_account_id);
        $data_return = array (
            'invoice_id' => $invoice->id,
            'pre_install_photos_c' => $invoice->installation_pictures_c,
            'worker_type' => $_POST['worker_type'],
            'invoice_number' => $invoice->number,
            'products' => $products_return,
            'full_name' => $invoice->billing_account,
            'title' => $invoice->name,
            'purchase_id'=>$purchase->number,
            'street_address' => $invoice->billing_address_street,
            'postcode_address' => $invoice->billing_address_postalcode,
            'state_address' => $invoice->billing_address_state,
            'phone_number' => $account->mobile_phone_c,
            'email' => $account->email1,
            'plumber_date' =>   ($invoice->plumber_install_date_c)? date("d/m/Y",strtotime($invoice->plumber_install_date_c)): "",
            'electrician_date' =>  ($invoice->electrician_install_date_c)? date("d/m/Y",strtotime($invoice->electrician_install_date_c)): "",
            'suburb_address' => $invoice->billing_address_city,
            // 'photos_return' => json_encode($photos_return),
        );
    }else {
        $account = new Account();
        $account->retrieve($invoice->billing_account_id);
        $purchase = new PO_purchase_order();
        $purchase->retrieve($invoice->plumber_po_c);
        // $data_photo_exist = array();
        $path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
        $dirName        = $invoice->installation_pictures_c;
        $folderName     = $path . $dirName . '/';
        $data_photo_exist = checkCountExistPhoto($folderName);
        $old_hws_fuel = ucwords(str_replace('_',' ',$invoice->old_tank_fuel_c));
        $old_hws_make = $invoice->old_tank_make_c;
        $old_hws_model = $invoice->old_tank_model_c;
        $old_hws_serial = $invoice->old_tank_serial_c;
        if ($invoice->old_tank_date_c != '') {
            $old_hws_date = $bean->old_tank_date_c; //dd-mm-yyyy  str_replace('/', '-',$bean->old_tank_date_c)       
        }
        $old_hws_string = $old_hws_fuel . ' ' . $old_hws_make . ' ' . $old_hws_model . ' ' . $old_hws_serial . ' ' . $old_hws_new_date;
        $plumber = new Contact();
        $plumber->retrieve($invoice->contact_id4_c);
        $electrician = new Contact();
        $electrician->retrieve($invoice->contact_id_c);
        $data_return = array (
            'invoice_id' => $invoice->id,
            'pre_install_photos_c' => $invoice->installation_pictures_c,
            'invoice_number' => $invoice->number,
            'purchase_number'=>$purchase->number,
            'full_name' => $invoice->billing_account,
            'title' => $invoice->name,
            'street_address' => $invoice->billing_address_street,
            'postcode_address' => $invoice->billing_address_postalcode,
            'state_address' => $invoice->billing_address_state,
            'suburb_address' => $invoice->billing_address_city,
            'phone_number' => $account->mobile_phone_c,
            'email' => $account->email1,
            'system' => $invoice->sanden_model_c,
            'old_hws' => $old_hws_string,
            'plumber_date' =>  ($invoice->plumber_install_date_c)? date("d/m/Y",strtotime($invoice->plumber_install_date_c)): "",
            'electrician_date' =>  ($invoice->electrician_install_date_c)? date("d/m/Y",strtotime($invoice->electrician_install_date_c)): "",
            'installer_note' => $invoice->plumbing_notes_c,
            'pcoc_note' => $invoice->pcoc_cert_wording_c,
            'plumber' => $invoice->plumber_c,
            'electrician' => $invoice->electrician_c,
            'plumbing_contact' =>  ($invoice->plumber_contact_c)? $invoice->plumber_contact_c ." | M:". $plumber->phone_mobile ." | ". $plumber->email1: "",
            'electrician_contact' => ($invoice->electrician_contact_c)? $invoice->electrician_contact_c ." | M:". $electrician->phone_mobile ." | ". $electrician->email1: "",
            'data_photo_exist' => $data_photo_exist,
        );
    }
    echo json_encode($data_return);
    die;
}else if(isset($_POST['purchase_id']) &&  $_REQUEST['worker_type'] == "Delivery Supplier" ){
    $purchase = new PO_purchase_order();
    $purchase->retrieve($_POST['purchase_id']);

    $data_return = array (
        'purchase_id' => $purchase->id,
        'pre_install_photos_c' => $purchase->installation_pdf_c,
        'worker_type' => $_POST['worker_type'],
    );
    echo json_encode($data_return);
    die;
}else if($quote->id == '') {
    echo json_encode(array('msg'=>'error'));
    die();
}
$db = DBManagerFactory::getInstance();
$sql = "SELECT * FROM aos_products_quotes pg
        WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0 GROUP BY pg.part_number";
$res = $db->query($sql);


$account = new Account();
$account->retrieve($quote->billing_account_id);

$time_stamp = 'unavailability';
$query_time = "SELECT tstamp FROM pending_quote_token WHERE quote_id ='$quote->id'";
$ret_time = $db->query($query_time);
$row_time = $db->fetchByAssoc($ret_time);

$date = new DateTime();
$timestampToday = $date->getTimestamp();
if($timestampToday - $row_time['tstamp'] >= 86400) {
    $time_stamp =  'unavailability';
} else {
    $time_stamp =  'availability';
}


while ($row = $db->fetchByAssoc($res)) {
    $products_return[$row['part_number']] = array (
        'Quantity' => number_format($row['product_qty'], 0),
        'Product' =>  $row['name'],
        'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$row['item_description']),
        'List' =>  number_format($row['product_cost_price'], 2),
        'Sale_Price' => number_format($row['product_list_price'], 2),
        'Tax_Amount' => $row['vat_amt'],
        'Discount' => 0,
        'Total' => number_format($row['product_total_price'], 2)
    );
}

$sql_group = "SELECT * FROM  aos_line_item_groups lig
        WHERE lig.parent_type = 'AOS_Quotes' AND lig.parent_id = '" . $quote->id . "' AND lig.deleted = 0";
$ret = $db->query($sql_group);

while ($row = $db->fetchByAssoc($ret)) {
    $data_return = array (
        'quote_id' => $quote->id,
        'quote_number' => $quote->number,
        'node_id' => $quote->drupal_node_c,
        'lead_id' => $quote->leads_aos_quotes_1leads_ida,
        'pre_install_photos_c' => $quote->pre_install_photos_c,
        'products' => $products_return,
        'first_name' => $quote->account_firstname_c,
        'last_name' => $quote->account_lastname_c,
        'street_address' => $quote->billing_address_street,
        'postcode_address' => $quote->billing_address_postalcode,
        'state_address' => $quote->billing_address_state,
        'phone_number' => $account->mobile_phone_c,
        'email' => $account->email1,
        'suburb_address' => $quote->billing_address_city,
        'timestamp_status' => $time_stamp,
        'quote_number' => $quote->number,
        'groupProducts' => array(
            'Group_Name' => $row['name'],
            'Total' => number_format($row['total_amount'],2),
            'Discount' => 0,
            'Subtotal' => number_format($row['subtotal_amount'],2),
            'GST' => number_format($row['tax_amount'],2),
            'Tax' =>  number_format($row['tax_amount'],2),
            'Group_Total' => number_format($row['total_amount'],2)
        )
    );
}
if($_REQUEST['form_type'] == "solar-form"){
    $data_return = array (
        'quote_id' => $quote->id,
        'node_id' => $quote->drupal_node_c,
        'lead_id' => $quote->leads_aos_quotes_1leads_ida,
        'pre_install_photos_c' => $quote->pre_install_photos_c,
        'options' => html_entity_decode($quote->solar_pv_pricing_input_c),
        'vic_rebate'=> $quote->vic_rebate_c,
        'loan_rebate'=> $quote->vic_loan_c,
        'first_name' => $quote->account_firstname_c,
        'last_name' => $quote->account_lastname_c,
        'street_address' => $quote->billing_address_street,
        'postcode_address' => $quote->billing_address_postalcode,
        'phone_number' => $account->mobile_phone_c,
        'email' => $account->email1,
        'suburb_address' => $quote->billing_address_city,
        'timestamp_status' => $time_stamp,
        'quote_number' => $quote->number,
        'Group_Name' => 'Solar',
    );
}

echo json_encode($data_return);

// function checkCountExistPhoto($file_type,$folderName,$new_name){
//     $data_exist= [];
//     $get_all_photo = dirToArray($folderName);
//     foreach ($get_all_photo as $photo_exist) {
//         if( strpos($photo_exist, $new_name) == true){
//             $data_exist[] = $photo_exist;
//         }
//     }
//     $count =  count($data_exist);
//     return $count;   
// }
function checkCountExistPhoto($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       {    
        $type = strtolower(substr(strrchr($value, '.'), 1));
        if( $type == 'pdf' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
             $result[] = array("url" =>$value, "type" => $type); 
          } 
       } 
    }
    return $result; 
}