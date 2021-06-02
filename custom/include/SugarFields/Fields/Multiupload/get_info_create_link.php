<?php
$record_id = $_GET['record'];
$module = $_GET['module'];
$db = DBManagerFactory::getInstance();
$result = array(
    'AOS_Quotes' => [],
    'AOS_Invoices' => [] ,
    'Opportunities' => [] ,
    'PO_purchase_order' => [],
    'Accounts' => [],
    'Contacts' => [],
    'Leads' => [],
    'Calls' => [],
    'pe_service_case' => [],
);

Call:
if($record_id != '') {
    switch ($module) {
        case 'Leads':
            $sql = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name,
                aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name ,
                leads.id as leads_id , leads.account_name as leads_name
                FROM leads
                LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = leads.account_id 
                LEFT JOIN aos_invoices ON aos_quotes.billing_account_id = aos_invoices.billing_account_id 
                WHERE leads.id = '$record_id' AND leads.account_id != ''";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;

        case 'AOS_Quotes':
            $sql = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name, 
                aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name ,
                leads.id as leads_id , leads.account_name as leads_name
                FROM aos_quotes  
                LEFT JOIN leads_aos_quotes_1_c ON aos_quotes.id = leads_aos_quotes_1_c.leads_aos_quotes_1aos_quotes_idb 
                LEFT JOIN leads ON leads.id = leads_aos_quotes_1_c.leads_aos_quotes_1leads_ida
                LEFT JOIN aos_quotes_aos_invoices_c ON aos_quotes_aos_invoices_c.aos_quotes77d9_quotes_ida = aos_quotes.id
                LEFT JOIN aos_invoices ON aos_invoices.id = aos_quotes_aos_invoices_c.aos_quotes6b83nvoices_idb 
                WHERE  aos_quotes.id = '$record_id' AND aos_quotes.deleted = 0";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            //VUT - Show Quotes are relate Account
                $account_id = key($result["Accounts"]);
                $quote_exist = key($result["AOS_Quotes"]);
                $sql_acc = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name, aos_quotes.number as quote_number,
                leads.id as leads_id , leads.account_name as leads_name
                FROM leads 
                LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = leads.account_id 
                -- LEFT JOIN aos_invoices ON aos_quotes.billing_account_id = aos_invoices.billing_account_id 
                WHERE leads.account_id = '$account_id' AND leads.account_id != '' AND aos_quotes.deleted = 0 ";
                $ret = $db->query($sql_acc);
                while($row = $ret->fetch_assoc()){
                    $result = render_json_data($result,$row);
                }
            //VUT-E-Show Quotes are related Account
            break;

        case 'AOS_Invoices':      
            $sql = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name,
            aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name ,
            leads.id as leads_id , leads.account_name as leads_name
            FROM aos_quotes  
            LEFT JOIN leads ON aos_quotes.billing_account_id = leads.account_id 
            LEFT JOIN aos_quotes_aos_invoices_c ON aos_quotes_aos_invoices_c.aos_quotes77d9_quotes_ida = aos_quotes.id
            LEFT JOIN aos_invoices ON aos_invoices.id = aos_quotes_aos_invoices_c.aos_quotes6b83nvoices_idb 
            WHERE  aos_invoices.id = '$record_id' AND leads.account_id != '' AND aos_quotes.deleted = 0";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;
        case 'Accounts':      
            $sql = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name,
            aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name ,
            leads.id as leads_id , leads.account_name as leads_name
            FROM leads 
            LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = leads.account_id 
            LEFT JOIN aos_invoices ON aos_quotes.billing_account_id = aos_invoices.billing_account_id 
            WHERE leads.account_id = '$record_id' AND leads.account_id != '' ";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }

            //VUT-S-case have Invoice - not Quote
            if (count($result['AOS_Invoices']) == 0) {
                $sql = "SELECT DISTINCT aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name 
                FROM aos_invoices 
                WHERE aos_invoices.billing_account_id ='$record_id' AND aos_invoices.deleted = 0
                ";
                $ret = $db->query($sql);
                while ($row = $ret->fetch_assoc()) {
                    $result = render_json_data($result,$row);
                }
            }
            //VUT-E-case have Invoice - not Quote

            break;
        case 'Contacts':      
            $sql = "SELECT DISTINCT aos_quotes.id as quote_id ,aos_quotes.name as quote_name,
            aos_invoices.id as aos_invoices_id ,aos_invoices.name as aos_invoices_name ,
            leads.id as leads_id , leads.account_name as leads_name
            FROM leads 
            LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = leads.account_id 
            LEFT JOIN aos_invoices ON aos_quotes.billing_account_id = aos_invoices.billing_account_id 
            WHERE leads.contact_id = '$record_id' AND leads.contact_id != '' ";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }
            break;
        case 'pe_service_case':
            $sql = "SELECT DISTINCT aos_invoices_pe_service_case_1_c.aos_invoices_pe_service_case_1aos_invoices_ida as aos_invoices_id, aos_invoices.name as aos_invoices_name, leads.id as leads_id, leads.account_name as leads_name
                        FROM pe_service_case
                        LEFT JOIN aos_invoices_pe_service_case_1_c ON pe_service_case.id = aos_invoices_pe_service_case_1_c.aos_invoices_pe_service_case_1pe_service_case_idb
                        LEFT JOIN aos_invoices ON aos_invoices.id = aos_invoices_pe_service_case_1_c.aos_invoices_pe_service_case_1aos_invoices_ida
                        LEFT JOIN leads_pe_service_case_1_c ON leads_pe_service_case_1_c.leads_pe_service_case_1pe_service_case_idb = pe_service_case.id
	                    LEFT JOIN leads ON leads.id = leads_pe_service_case_1_c.leads_pe_service_case_1leads_ida
                        WHERE  pe_service_case.id = '$record_id' AND pe_service_case.deleted = 0
                    ";
            $ret = $db->query($sql);
            while($row = $ret->fetch_assoc()){
                $result = render_json_data($result,$row);
            }

            break;
        case 'Calls':
            $sql="SELECT parent_type as module, parent_id as record_id
                    FROM calls 
                    WHERE calls.id='$record_id' AND calls.deleted = 0
                ";
            $ret=$db->query($sql);
            $row = $ret->fetch_assoc();
            if ($row['module'] !='' && $row['record_id']!='') {
                $module=$row['module'];
                $record_id=$row['record_id'];
                //Get call follow parent_id
                    $sql_call = "SELECT id, name 
                                    FROM calls
                                    WHERE calls.deleted = 0 AND parent_id ='$record_id'
                                    ORDER BY number DESC
                    ";
                    $ret_call = $db->query($sql_call);
                    while ($row = $ret_call->fetch_assoc()) {
                        $call = new Call();
                        $call->retrieve($row['id']);
                        if ($call->id != '') {
                            $result['Calls'][$call->id] = 'Calls #'.$call->number.' '.$call->name;
                        }
                    }
                //Get call follow parent_id

                $bean= BeanFactory::getBean($module,$record_id);
                $result[$module][$record_id] = $module. ' #'.$bean->number." ". $bean->name; 
                goto Call;
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
        $bean_lead =  new Lead();
        $bean_lead->retrieve($row['leads_id']);
        $result['Leads'][$row['leads_id']] = "Leads #". $bean_lead->number." ".$row['leads_name']; 
        //account + contact +oppurtunity
        if(empty($result['Accounts'])) {
            $bean_account =  new Account();
            $bean_account->retrieve($bean_lead->account_id);
            $result['Accounts'][$bean_account->id] = "Account #". $bean_account->number." ".$bean_account->name;
        }
        if(empty($result['Contacts'])) {
            $bean_contact =  new Contact();
            $bean_contact->retrieve($bean_lead->contact_id);
            $result['Contacts'][$bean_contact->id] = "Contact #". $bean_contact->number." ".$bean_contact->first_name . " " .$bean_contact->last_name;
        }
    }

    if($row['quote_id'] != ''){
        $bean_quotes =  new AOS_Quotes();
        $bean_quotes->retrieve($row['quote_id']);
        $result['AOS_Quotes'][$row['quote_id']] = "Quote #".$bean_quotes->number." ".$bean_quotes->name; 
        //account + contact +oppurtunity
        if(empty($result['Accounts'])) {
            $bean_account =  new Account();
            $bean_account->retrieve($bean_quotes->billing_account_id);
            $result['Accounts'][$bean_account->id] = "Account #". $bean_account->number." ".$bean_account->name;
        }
        if(empty($result['Contacts'])) {
            $bean_contact =  new Contact();
            $bean_contact->retrieve($bean_quotes->billing_contact_id);
            $result['Contacts'][$bean_contact->id] = "Contact #". $bean_contact->number." ".$bean_contact->first_name . " " .$bean_contact->last_name;
        }

        if(empty($result['Opportunities'])) {
            $bean_oppirtunities =  new Opportunity();
            $bean_oppirtunities->retrieve($bean_quotes->opportunity_id);
            $result['Opportunities'][$bean_oppirtunities->id] = "Opportunity #". $bean_oppirtunities->number." ".$bean_oppirtunities->account_name;
        } 

        if(empty($result['Calls'])) {
            $bean_Calls = $bean_quotes->get_linked_beans('calls_aos_quotes_1','Calls');
            foreach ($bean_Calls as $key => $Call) {
                $result['Calls'][$Call->id] = "Call#".$Call->number." ". $Call->name;
            }    
        }        
    }

    if($row['aos_invoices_id'] != ''){
        $bean_invoice =  new AOS_Invoices();
        $bean_invoice->retrieve($row['aos_invoices_id']);
        if ($bean_invoice->id != '') {
            $result['AOS_Invoices'][$row['aos_invoices_id']] = "Invoice #".$bean_invoice->number." ".$row['aos_invoices_name']; 
        }
        //account + contact 
        if(empty($result['Accounts'])) {
            $bean_account =  new Account();
            $bean_account->retrieve($bean_invoice->billing_account_id);
            $result['Accounts'][$bean_account->id] = "Account #". $bean_account->number." ".$bean_account->name;
        }
        if(empty($result['Contacts'])) {
            $bean_contact =  new Contact();
            $bean_contact->retrieve($bean_invoice->billing_contact_id);
            $result['Contacts'][$bean_contact->id] = "Contact #". $bean_contact->number." ".$bean_contact->first_name . " " .$bean_contact->last_name;
        }
    }

    //get Purchase Order
    $sql_get_PO = "SELECT po_purchase_order.id as po_purchase_order_id  ,po_purchase_order.name as po_purchase_order_name ,po_purchase_order.number as po_purchase_order_number 
                    FROM po_purchase_order
                    LEFT JOIN aos_quotes_po_purchase_order_1_c ON aos_quotes_po_purchase_order_1_c.aos_quotes_po_purchase_order_1po_purchase_order_idb =  po_purchase_order.id
                    LEFT JOIN aos_invoices_po_purchase_order_1_c ON aos_invoices_po_purchase_order_1_c.aos_invoices_po_purchase_order_1po_purchase_order_idb =  po_purchase_order.id
                    WHERE po_purchase_order.deleted = 0 AND  (aos_invoices_po_purchase_order_1_c.aos_invoices_po_purchase_order_1aos_invoices_ida = '"
                    .$row['aos_invoices_id'] ."' OR aos_quotes_po_purchase_order_1_c.aos_quotes_po_purchase_order_1aos_quotes_ida = '"  
                    .$row['quote_id'] ."' ) ";
    $ret_get_PO = $db->query($sql_get_PO);
    while($row_get_PO = $ret_get_PO->fetch_assoc()){
        if($row_get_PO['po_purchase_order_id'] != ''){
            $result['PO_purchase_order'][$row_get_PO['po_purchase_order_id']] =  "PO#".$row_get_PO['po_purchase_order_number']." ".$row_get_PO['po_purchase_order_name']; 
        }
    }
    return $result;
}
echo json_encode($result);