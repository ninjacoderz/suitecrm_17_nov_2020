<?php

    $hook_version = 1;
    $hook_array = Array();

    $hook_array['after_save'] = Array();
    // $hook_array['after_save'][] = Array(
    //     //Processing index. For sorting the array.
    //     1,

    //     //Label. A string value to identify the hook.
    //     'rename_upload_files',

    //     //The PHP file where your class is located.
    //     'custom/modules/AOS_Invoices/logic_hooks_class.php',

    //     //The class the method is in.
    //     'RenameUploadFiles',

    //     //The method to call.
    //     'after_save_method'
    // );

        //Hook after
        $hook_array['after_save'][] = Array(
            1,
            'update_relate_productPrices',
            'custom/modules/AOS_Products/logic_hooks_class.php',
            'UpdateRelatedProductPrices',
            'after_save_method'
        );
    


?>