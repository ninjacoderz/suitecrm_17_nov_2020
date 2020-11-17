<?php
$record_id = $_GET['record_id'];
$module = $_GET['module'];

if ($module == 'Contacts' && $record_id != '') {
    $bean = new Contact();
    $bean->retrieve($record_id);
    if ($bean->id != '') {
        $result = array (
            'module' => $module,
            'shipping_address_street' => $bean->primary_address_street,
            'shipping_address_city' => $bean->primary_address_city,
            'shipping_address_state' => $bean->primary_address_state,
            'shipping_address_postalcode' => $bean->primary_address_postalcode,
            'shipping_address_country' => $bean->primary_address_country,
        );
    }
    
}

if ($module == 'AOS_Invoices' && $record_id != '') {
    $bean = new AOS_Invoices();
    $bean->retrieve($record_id);
    if ($bean->id != '') {
        $result = array (
            'module' => $module,
            'invoice_billing_address_street' => $bean->billing_address_street,
            'invoice_billing_address_city' => $bean->billing_address_city,
            'invoice_billing_address_state' => $bean->billing_address_state,
            'invoice_billing_address_postalcode' => $bean->billing_address_postalcode,
            'invoice_billing_address_country' => $bean->billing_address_country,
            'invoice_site_address_street' => $bean->install_address_c,
            'invoice_site_address_city' => $bean->install_address_city_c,
            'invoice_site_address_state' => $bean->install_address_state_c,
            'invoice_site_address_postalcode' => $bean->install_address_postalcode_c,
            'invoice_site_address_country' => $bean->install_address_country_c,
        );
    }
}

if (empty($result) || !isset($result)) {
    echo 'no data';
} else {
    echo json_encode($result);
}
