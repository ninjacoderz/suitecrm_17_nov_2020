<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Leads/LeadsJjwg_MapsLogicHook.php','LeadsJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['before_save'][] = Array(1, 'Leads push feed', 'modules/Leads/SugarFeeds/LeadFeed.php','LeadFeed', 'pushFeed');
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Leads/LeadsJjwg_MapsLogicHook.php','LeadsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
$hook_array['after_save'][] = Array(77, 'convertLead', 'custom/modules/Leads/convertLead.php','convertLead', 'convertLead'); 
$hook_array['after_save'][] = Array(79, ' AUto send email when get quote include methenve', 'custom/modules/Leads/logic_hooks_class.php','LeadSentEmailToCustomerFromPESite', 'after_save_method_set_entry'); 

$hook_array['before_save'][] = Array(
    //Processing index. For sorting the array.
    33,

    //Label. A string value to identify the hook.
    'rename_upload_files',

    //The PHP file where your class is located.
    'custom/modules/Leads/logic_hooks_class.php',

    //The class the method is in.
    'LeadRenameUploadFiles',

    //The method to call.
    'after_save_method'
);


$hook_array['before_save'][] = Array(
    //Processing index. For sorting the array.
    34,

    //Label. A string value to identify the hook.
    'push_to_solargain',

    //The PHP file where your class is located.
    'custom/modules/Leads/logic_hooks_class.php',

    //The class the method is in.
    'LeadRenameUploadFiles',

    //The method to call.
    'before_save_method_pushToSolargain'
);

$hook_array['before_save'][] = Array(
    //Processing index. For sorting the array.
    35,

    //Label. A string value to identify the hook.
    'auto_calculate_distance',

    //The PHP file where your class is located.
    'custom/modules/Leads/logic_hooks_class.php',

    //The class the method is in.
    'LeadRenameUploadFiles',

    //The method to call.
    'before_save_method_autoCalculateDistance'
);
$hook_array['before_save'][] = Array(
    //Processing index. For sorting the array.
    36,

    //Label. A string value to identify the hook.
    'change_email_status',

    //The PHP file where your class is located.
    'custom/modules/Leads/logic_hooks_class.php',

    //The class the method is in.
    'LeadChangeEmailStatus',

    //The method to call.
    'before_save_method_changeEmailStatus'
);
$hook_array['before_save'][] = Array(37,'create_internal_notes','custom/modules/Leads/logic_hooks_class.php','CreateInternalNotesLead','before_save_method_CreateInternalNotes');
// Update data from Lead to Account And Contact
$hook_array['before_save'][] = Array(37,'Update Data To Account And Contact','custom/modules/Leads/logic_hooks_class.php','UpdateDataToAccountAndContact','before_save_method_UpdateDataToAccountAndContact');
// VUT - Change status = "Spam" If the phone number is starts with a "8" for new Lead
$hook_array['before_save'][] = Array(38,'Change status if phone number is start with 8','custom/modules/Leads/logic_hooks_class.php','ChangeStatusToSpam','before_save_method_ChangeStatusToSpam');

?>