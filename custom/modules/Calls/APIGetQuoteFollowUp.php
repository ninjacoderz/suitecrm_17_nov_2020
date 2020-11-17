<?php
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

$record_id = $_GET['record_id'];
$module = $_GET['module'];
if ($record_id != '' && $module=='Calls') {
  $db = DBManagerFactory::getInstance();
  $query = "  SELECT DISTINCT aos_quotes.id as quote_id, aos_quotes.name as quote_name, aos_quotes.number as quote_number, aos_quotes_cstm.quote_type_c as product_type, aos_quotes_cstm.lead_source_co_c as lead_source, calls.number as call_number, leads_aos_quotes_1_c.leads_aos_quotes_1leads_ida as lead_id
              FROM calls_aos_quotes_1_c
              RIGHT JOIN aos_quotes ON aos_quotes.id = calls_aos_quotes_1_c.calls_aos_quotes_1aos_quotes_idb
              RIGHT JOIN aos_quotes_cstm ON aos_quotes_cstm.id_c = aos_quotes.id
              RIGHT JOIN calls ON calls.id = calls_aos_quotes_1_c.calls_aos_quotes_1calls_ida
              RIGHT JOIN leads_aos_quotes_1_c ON leads_aos_quotes_1_c.leads_aos_quotes_1aos_quotes_idb = aos_quotes.id
              WHERE aos_quotes.deleted = 0 AND calls.id = '$record_id'
              ORDER BY aos_quotes.number DESC
              ";
  $ret = $db->query($query);

  while($row = $ret->fetch_assoc()){
    $result[] = $row;
  }
  //end-b1
  $data_return = createPopup($result);
  echo json_encode($data_return);
}

function createPopup($data) {
  $render='';
    foreach ($data as $key => $value ) {
      $render.='<input type="radio" data-email-type="follow_up" data-quote-name="'.$value['quote_name'].'" data-email-address="" data-lead-id="'.$value['lead_id'].'" data-lead-source="'.$value['lead_source'].'" data-record-id="'.$value['quote_id'].'" data-product-type="'.$value['product_type'].'" name="selectQuote" class="quote_follow_up" data-module="AOS_Quotes"> Quote#'.$value['quote_number'].' '.$value['quote_name'].'<br>';
    }
  
  if (count($data) > 1) {
    $html='<div id="popupQuoteFollowUp" title="Select Related Quote">'.$render.'</div>';
  } else {
    $html = $render;
  }
  $result = array(
    'count' => count($data),
    'html' => $html,
  );
  return $result;
}
