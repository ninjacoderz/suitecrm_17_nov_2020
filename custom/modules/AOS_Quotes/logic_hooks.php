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
        'quote_rename_upload_files',

        //The PHP file where your class is located.
        'custom/modules/AOS_Quotes/logic_hooks_class.php',

        //The class the method is in.
        'QuoteRenameUploadFiles',

        //The method to call.
        'after_save_method'
    );

    $hook_array['after_save'][] = Array(
        //Processing index. For sorting the array.
        2,

        //Label. A string value to identify the hook.
        'quote_add_attachments',

        //The PHP file where your class is located.
        'custom/modules/AOS_Quotes/logic_hooks_class.php',

        //The class the method is in.
        'QuoteAddAttachments',

        //The method to call.
        'after_save_method'
    );

    $hook_array['after_relationship_add'] = Array();
    $hook_array['after_relationship_add'][] = Array(
        //Processing index. For sorting the array.
        1,

        //Label. A string value to identify the hook.
        'quote_after_relationship_add',

        //The PHP file where your class is located.
        'custom/modules/AOS_Quotes/logic_hooks_class.php',

        //The class the method is in.
        'QuoteAfterRelationshipAdd',

        //The method to call.
        'after_relationship_add_method'
    );

    $hook_array['after_relationship_delete'] = Array();
    $hook_array['after_relationship_delete'][] = Array(
        //Processing index. For sorting the array.
        1,

        //Label. A string value to identify the hook.
        'quote_after_relationship_delete',

        //The PHP file where your class is located.
        'custom/modules/AOS_Quotes/logic_hooks_class.php',

        //The class the method is in.
        'QuoteAfterRelationshipDelete',

        //The method to call.
        'after_relationship_delete_method'
    );
    $hook_array['before_save'][] = array (14, 'update_account_contact_customer','custom/modules/AOS_Quotes/logic_hooks_class.php','UpdateAcountContactCustomer','update_account_contact_customer_func');
    //dung code -- create logic hook update SG
    $hook_array['before_save'][] = Array(15,'update_to_solargain','custom/modules/AOS_Quotes/logic_hooks_class.php','UpdateToSolargain','before_save_method_UpdateToSolargain');
    //dung code -- create internal notes when change status quotes
    $hook_array['before_save'][] = Array(16,'create_internal_notes','custom/modules/AOS_Quotes/logic_hooks_class.php','CreateInternalNotes','before_save_method_CreateInternalNotes');
    //Tri Truong - Update Suburb when change Suburb
    $hook_array['before_save'][] = Array(17,'update_suburb','custom/modules/AOS_Quotes/logic_hooks_class.php','UpdateSuburb','before_save_method_updateSuburb');
    //Tri Truong - Update Lead Source Co
    $hook_array['before_save'][] = Array(18,'update_lead','custom/modules/AOS_Quotes/logic_hooks_class.php','UpdateLeadSourceInLeadModule','before_save_method_update_lead_source');
    //thienpb code -- auto fill pricing option
    $hook_array['before_save'][] = Array(19,'auto_fill_pricing_option','custom/modules/AOS_Quotes/logic_hooks_class.php','AutoFillPricingOption','before_save_method_autoFillPricingOption');
    //VUT -- Check duplicate Quote Solar and create Sam Quote
    $hook_array['before_save'][] = Array(20,'duplicate_solar_quote','custom/modules/AOS_Quotes/logic_hooks_class.php','DuplicateSolarQuote','before_save_method_duplicateSolarQuote');


?>