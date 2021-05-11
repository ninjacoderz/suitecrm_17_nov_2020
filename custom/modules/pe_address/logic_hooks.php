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
    $hook_array['before_save'][] = Array(
        1,
        'create_folder_upload',
        'custom/modules/pe_address/logic_hooks_class.php',
        'CreateFolderUpload',
        'before_save_method'
    );
?>