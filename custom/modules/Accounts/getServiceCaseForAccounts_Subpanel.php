<?php
/**Subpanel- Service Case in Account Detail */
function get_servicecase_for_accounts($params) {
    $args = func_get_args();
    $account_id = $args[0]['account_id'];
    $return_array['select'] = " SELECT DISTINCT pe_service_case.id as servicecase_id";
    $return_array['from'] = "FROM pe_service_case";
    $return_array['join'] = "";
    $return_array['where'] = "WHERE pe_service_case.billing_account_id = '$account_id' AND pe_service_case.deleted = '0'";
    $return_array['join_tables'] = '';
    return $return_array;
}

//Relate Invoice/Lead
// $return_array['join'] = "   LEFT JOIN aos_invoices_pe_service_case_1_c ON pe_service_case.id = aos_invoices_pe_service_case_1_c.aos_invoices_pe_service_case_1pe_service_case_idb
// LEFT JOIN aos_invoices ON aos_invoices.id = aos_invoices_pe_service_case_1_c.aos_invoices_pe_service_case_1aos_invoices_ida
// LEFT JOIN leads_pe_service_case_1_c ON leads_pe_service_case_1_c.leads_pe_service_case_1pe_service_case_idb = pe_service_case.id
// LEFT JOIN leads ON leads.id = leads_pe_service_case_1_c.leads_pe_service_case_1leads_ida
// ";
