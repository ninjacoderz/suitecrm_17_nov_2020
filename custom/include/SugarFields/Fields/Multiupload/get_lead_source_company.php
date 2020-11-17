<?php
// echo 'PureElectric';
//echo 'Solargain';
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];
$db = DBManagerFactory::getInstance();
$result = array(
    'lead_source_co_c' => '',
    'assigned_user_id' => ''
);

if($record != '') {
    switch ($module) {
        case 'Leads':
            $sql = "SELECT DISTINCT id as leads_id
                FROM leads 
                WHERE id = '$record' AND deleted = 0";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;

        case 'AOS_Quotes':
            $sql = "SELECT DISTINCT 
                leads.id as leads_id 
                FROM aos_quotes  
                LEFT JOIN leads ON aos_quotes.billing_account_id = leads.account_id 
                LEFT JOIN aos_quotes_aos_invoices_c ON aos_quotes_aos_invoices_c.aos_quotes77d9_quotes_ida = aos_quotes.id
                LEFT JOIN aos_invoices ON aos_invoices.id = aos_quotes_aos_invoices_c.aos_quotes6b83nvoices_idb 
                WHERE  aos_quotes.id = '$record' AND leads.account_id != '' AND aos_quotes.deleted = 0";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;

        case 'AOS_Invoices':      
            $sql = "SELECT DISTINCT 
            leads.id as leads_id 
            FROM aos_quotes  
            LEFT JOIN leads ON aos_quotes.billing_account_id = leads.account_id 
            LEFT JOIN aos_quotes_aos_invoices_c ON aos_quotes_aos_invoices_c.aos_quotes77d9_quotes_ida = aos_quotes.id
            LEFT JOIN aos_invoices ON aos_invoices.id = aos_quotes_aos_invoices_c.aos_quotes6b83nvoices_idb 
            WHERE  aos_invoices.id = '$record' AND leads.account_id != '' AND aos_quotes.deleted = 0";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;

        default:
            # code...
            break;
    }
}

function render_json_data($result ,$row){
    $db = DBManagerFactory::getInstance();
    if($row['leads_id'] != ''){
        $bean_lead = new Lead();
        $bean_lead =  $bean_lead->retrieve($row['leads_id']);  
        $result['lead_source_co_c']  = $bean_lead->lead_source_co_c;
        $result['assigned_user_id']  = $bean_lead->assigned_user_id;
    }
    
    return $result;
}
echo json_encode($result);