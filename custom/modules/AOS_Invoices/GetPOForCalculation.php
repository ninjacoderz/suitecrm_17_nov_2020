<?php 
//VUT-S-Get grand total PO - Invoice - GP Calcu
$po_id = $_REQUEST['po_id'];
$type = $_REQUEST['type'];
if (isset($po_id) && $type=='gp_profit') {
    $po = new PO_purchase_order();
    $po->retrieve($po_id);
    if ($po->id != '') {
        echo $po->total_amt;
    }
}
//VUT-E-Get grand total PO - Invoice - GP Calcu
//VUT-S-Get total PO - Quote - GP Calcu
$quote_id = $_REQUEST['quote_id'];
$type = $_REQUEST['type'];

if (isset($quote_id) && $quote_id !='' && $type=='gp_profit_quote') {
  $db = DBManagerFactory::getInstance();
  // $quote_id = 'c783f638-2008-318a-9bb9-5f6c3357ab91';
  $sql = "SELECT DISTINCT aos_invoices.id as invoice_id, aos_invoices_cstm.plumber_po_c as plumber_po, aos_invoices_cstm.electrical_po_c as electrical_po, aos_invoices.date_entered as date_create
        FROM aos_invoices_cstm
        LEFT JOIN aos_invoices ON aos_invoices.id = aos_invoices_cstm.id_c
        LEFT JOIN aos_quotes_aos_invoices_c ON aos_quotes_aos_invoices_c.aos_quotes6b83nvoices_idb = aos_invoices.id
        LEFT JOIN aos_quotes ON aos_quotes.id = aos_quotes_aos_invoices_c.aos_quotes77d9_quotes_ida
        WHERE aos_quotes.id = '$quote_id' 
        ORDER BY aos_invoices.date_entered DESC
        LIMIT 1
      ";
  $ret = $db->query($sql);
  if ($row = $db->fetchByAssoc($ret)) {
    $po_plumbing = $row['plumber_po'];
    $po_electrical = $row['electrical_po'];
    $result = array (
      'plumb_po' => 0,
      'elec_po' => 0,
    );
    if ($po_plumbing != '') {
      $po = new PO_purchase_order();
      $po->retrieve($po_plumbing);
      if ($po->id != '') {
          $result['plumb_po'] += (float)$po->total_amt;
      }
    }
    if ($po_electrical != '') {
      $po = new PO_purchase_order();
      $po->retrieve($po_electrical);
      if ($po->id != '') {
          $result['elec_po'] += (float)$po->total_amt;
      }
    }
  } else {
    $result = array (
      'plumb_po' => 0,
      'elec_po' => 0,
    );
  }
} else if ($type=='gp_profit_quote') {
  $result = array (
    'plumb_po' => 0,
    'elec_po' => 0,
  );
}
echo json_encode($result);
//VUT-E-Get total PO - Quote - GP Calcu
