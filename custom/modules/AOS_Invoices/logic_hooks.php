<?php
/**
 * Created by PhpStorm.
 * User: nguyenthanhbinh
 * Date: 3/19/17
 * Time: 6:10 PM
 */

    $hook_version = 1;
    $hook_array = Array();

    $hook_array['after_save'] = Array();
    $hook_array['after_save'][] = Array(
        //Processing index. For sorting the array.
        1,

        //Label. A string value to identify the hook.
        'rename_upload_files',

        //The PHP file where your class is located.
        'custom/modules/AOS_Invoices/logic_hooks_class.php',

        //The class the method is in.
        'RenameUploadFiles',

        //The method to call.
        'after_save_method'
    );
    $hook_array['after_save'][] = Array(
        2,
        'quote_add_attachments',
        'custom/modules/AOS_Invoices/logic_hooks_class.php',
        'InvoiceAddAttachments',
        'after_save_method'
    );
    //dung code - hook update status invoice
    $hook_array['after_save'][] = Array(
        3,
        'update_status_invoice',
        'custom/modules/AOS_Invoices/logic_hooks_class.php',
        'UploadStatusInvoice',
        'after_save_method'
    );

    $hook_array['before_save'][] = Array(
        4,
        'update_stock_items',
        'custom/modules/AOS_Invoices/logic_hooks_class.php',
        'UpdateStockItems',
        'before_save_method'
    );
    //dung code -- auto create note when change status Invoice
    $hook_array['before_save'][] = Array(
        5,
        'create_internal_notes',
        'custom/modules/AOS_Invoices/logic_hooks_class.php',
        'CreateInternalNotes_invoice',
        'before_save_method'
    );
    // // thienpb code -- update next action date = '' when status = Paid
    // $hook_array['after_save'][] = Array(
    //     6,
    //     'update_next_action_date',
    //     'custom/modules/AOS_Invoices/logic_hooks_class.php',
    //     'UpdateNextActionDate',
    //     'after_save_method'
    // );
    // // dung code -- automatic update Invoice Xero
    // $hook_array['after_save'][] = Array(
    //     7,
    //     'update_xero_invoice',
    //     'custom/modules/AOS_Invoices/Auto_Update_Xero_Invoice.php',
    //     'Auto_Update_Xero_Invoice',
    //     'after_save_method'
    // );
    // tuan code auto send customer warranty upload email
    $hook_array['after_save'][] = Array(
        6,
        'auto_send_customer_warranty_mail',
        'custom/modules/AOS_Invoices/logic_hooks_class.php',
        'AutoSendCustomerWarrantyMail',
        'after_save_AutoSendCustomerWarrantyMail'
    );
?>