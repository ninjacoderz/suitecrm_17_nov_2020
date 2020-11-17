<?php
/**Subpanel- Service Case in Contacts Detail */
function get_servicecase_for_contacts($params) {
    $args = func_get_args();
    $contact_id = $args[0]['contact_id'];
    $return_array['select'] = " SELECT DISTINCT pe_service_case.id as servicecase_id";
    $return_array['from'] = "FROM pe_service_case";
    $return_array['join'] = "";
    $return_array['where'] = "WHERE pe_service_case.billing_contact_id = '$contact_id' AND pe_service_case.deleted = '0'";
    $return_array['join_tables'] = '';
    return $return_array;
}

/**Subpanel- Get Calls  */
function get_call_for_contacts($params) {
    $args = func_get_args();
    $contact_id = $args[0]['contact_id'];
    $return_array['select'] = " SELECT DISTINCT calls.id as id";
    $return_array['from'] = "FROM calls ";
    $return_array['join'] = " INNER JOIN calls_contacts ON calls_contacts.call_id = calls.id ";
    $return_array['where'] = "WHERE calls_contacts.contact_id = '$contact_id' AND calls.deleted = '0'";
    $return_array['join_tables'] = '';
    return $return_array;
}

