<?php

//VUT-Get contact from Internal note ID

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point-getLinkRealState.php' );

$record_id = $_GET['record'];
$module = $_GET['module'];
$result = array();

$db = DBManagerFactory::getInstance();

/**QUOTES*/
$sql = "    SELECT DISTINCT contacts.id as contact_id
            FROM contacts 
            LEFT JOIN aos_quotes ON contacts.id = aos_quotes.billing_contact_id 
            LEFT JOIN aos_quotes_pe_internal_note_1_c ON aos_quotes_pe_internal_note_1_c.aos_quotes_pe_internal_note_1aos_quotes_ida = aos_quotes.id
            WHERE aos_quotes_pe_internal_note_1_c.deleted = 0 AND aos_quotes_pe_internal_note_1_c.aos_quotes_pe_internal_note_1pe_internal_note_idb = '$record_id'
        ";
$ret = $db->query($sql);
/**INVOICES*/
if ($ret->num_rows == null) {
    $sql = "    SELECT DISTINCT contacts.id as contact_id
                FROM contacts 
                LEFT JOIN aos_invoices ON contacts.id = aos_invoices.billing_contact_id 
                LEFT JOIN aos_invoices_pe_internal_note_1_c ON aos_invoices_pe_internal_note_1_c.aos_invoices_pe_internal_note_1aos_invoices_ida  = aos_invoices.id
                WHERE aos_invoices_pe_internal_note_1_c.deleted = 0 AND aos_invoices_pe_internal_note_1_c.aos_invoices_pe_internal_note_1pe_internal_note_idb = '$record_id'
            ";
    $ret = $db->query($sql);
}
/**LEAD*/
if ($ret->num_rows == null) {
    $sql = "    SELECT *
                FROM contacts 
                LEFT JOIN leads ON contacts.id = leads.contact_id
                LEFT JOIN leads_pe_internal_note_1_c ON leads_pe_internal_note_1_c.leads_pe_internal_note_1leads_ida = leads.id 
                WHERE leads_pe_internal_note_1_c.deleted = 0 AND leads_pe_internal_note_1_c.leads_pe_internal_note_1pe_internal_note_idb = '$record_id'
            ";
    $ret = $db->query($sql);
}

while ($row = $ret->fetch_assoc()) {
    $result = render_json_data($result,$row);
}
echo json_encode($result);


//FUNCTION
function render_json_data($result ,$row){
    if ($row['contact_id'] != '') {
        $bean_contact = new Contact();
        $bean_contact->retrieve($row['contact_id']);
        if (empty($result['Contacts'])) {
            $result['Contacts']['id'] = $bean_contact->id;
            $result['Contacts']['name'] = $bean_contact->first_name.' '.$bean_contact->last_name;
            $result['Contacts']['phone_mobile'] = $bean_contact->phone_mobile;
            $result['Contacts']['email'] = $bean_contact->email1;
            // $result['Contacts']['number'] = $bean_contact->number;
        }
    }
    return $result;
}
?>