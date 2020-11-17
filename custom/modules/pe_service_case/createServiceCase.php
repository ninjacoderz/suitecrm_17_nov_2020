<?php

$record_id = $_REQUEST["record_id"];
$method = $_REQUEST["method"];
$module = $_REQUEST["module"];

//S-Create from Invoice 
    if (isset($record_id) && $record_id != "" && $module = 'AOS_Invoices' && $method == 'put') {
        /**Get Invoice's infomation */
        $invoice = new AOS_Invoices();
        $invoice->retrieve($record_id);
        if ($invoice->id == '') return;

        /**Get Account, Contact infomation */
        $account = new Account();
        $account->retrieve($invoice->billing_account_id);
        $contact = new Contact();
        $contact->retrieve($invoice->billing_contact_id);

        /**Create Service Case */
        $serviceCase = new pe_service_case();
        $serviceCase->name = $invoice->name;
            /**Add account infomation */
            $serviceCase->billing_address_street = $account->billing_address_street;
            $serviceCase->billing_address_city = $account->billing_address_city;
            $serviceCase->billing_address_state = $account->billing_address_state;
            $serviceCase->billing_address_postalcode = $account->billing_address_postalcode;
            $serviceCase->billing_address_country = $account->billing_address_country;
            /**Add contact infomation */
            $serviceCase->shipping_address_street = $contact->primary_address_street;
            $serviceCase->shipping_address_city = $contact->primary_address_city;
            $serviceCase->shipping_address_state = $contact->primary_address_state;
            $serviceCase->shipping_address_postalcode = $contact->primary_address_postalcode;
            $serviceCase->shipping_address_country = $contact->primary_address_country;
            /**Add invoice infomation */
                /**Billing address */
            $serviceCase->invoice_billing_address_street = $invoice->billing_address_street;
            $serviceCase->invoice_billing_address_city = $invoice->billing_address_city;
            $serviceCase->invoice_billing_address_state = $invoice->billing_address_state;
            $serviceCase->invoice_billing_address_postalcode = $invoice->billing_address_postalcode;
            $serviceCase->invoice_billing_address_country = $invoice->billing_address_country;
                /**Site address */
            $serviceCase->invoice_site_address_street = $invoice->install_address_c;
            $serviceCase->invoice_site_address_city = $invoice->install_address_city_c;
            $serviceCase->invoice_site_address_state = $invoice->install_address_state_c;
            $serviceCase->invoice_site_address_postalcode = $invoice->install_address_postalcode_c;
            $serviceCase->invoice_site_address_country = $invoice->install_address_country_c;

        $serviceCase->assigned_user_id = $invoice->assigned_user_id;
        $serviceCase->created_by_name = $invoice->assigned_user_name;
        $serviceCase->billing_account_id = $invoice->billing_account_id;
        $serviceCase->billing_contact_id = $invoice->billing_contact_id;
        $serviceCase->quote_type_c = $invoice->quote_type_c;
        $serviceCase->save();
        create_relationship_invoice_service_case($invoice->id,$serviceCase->id);
        $serviceCase->save();
        echo $serviceCase->id;
    }
//E-Create from Invoice 

function create_relationship_invoice_service_case($invoice_id, $service_id) {
    $AOS_Invoices = BeanFactory::getBean('AOS_Invoices', $invoice_id);
    $AOS_Invoices->load_relationship('aos_invoices_pe_service_case_1');
    $AOS_Invoices->aos_invoices_pe_service_case_1->add($service_id);
}
