<?php
    $po_id = $_GET["po_id"];
    $WHLog_id = create_guid();
    if($po_id != ''){
        $po = new PO_purchase_order();
        $po = $po->retrieve($po_id);

        $WHLog = new pe_warehouse_log();
        $WHLog->name = $po->name;
        $WHLog->po_purchase_order_pe_warehouse_log_1po_purchase_order_ida = $po_id;
        $WHLog->po_purchase_order_pe_warehouse_log_1_name = $po->name;
        $WHLog->assigned_user_id = $po->assigned_user_id;
        
        $WHLog->sold_to_invoice_id = $po->aos_invoices_po_purchase_order_1aos_invoices_ida;
        $WHLog->sold_to_invoice = $po->aos_invoices_po_purchase_order_1_name;

        $WHLog->billing_account_id = $po->shipping_account_id;
        $WHLog->billing_account = $po->shipping_account;

        $db = DBManagerFactory::getInstance();
        $query = "SELECT id, name FROM pe_warehouse  WHERE billing_account_id = '".$po->shipping_account_id."'" ;
        $ret = $db->query($query);
        while($row = $db->fetchByAssoc($ret)){
            // User the first result
            $WHLog->pe_warehouse_log_pe_warehouse_name = $row['name'];
            $WHLog->pe_warehouse_log_pe_warehousepe_warehouse_ida = $row['id'];
            break;
        }

        $WHLog->billing_address_street = $po->shipping_address_street;
        $WHLog->billing_address_city = $po->shipping_address_city;
        $WHLog->billing_address_state = $po->shipping_address_state;
        $WHLog->billing_address_postalcode = $po->shipping_address_postalcode;
        $WHLog->billing_address_country = $po->shipping_address_country;

        $WHLog->save();
        echo $WHLog->id;
    }
?>