<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1;
$hook_array = Array(); 

$hook_array['after_save'][] = Array(
    //Processing index. For sorting the array.
    34,

    //Label. A string value to identify the hook.
    'Update Name And Assigned',

    //The PHP file where your class is located.
    'custom/modules/pe_internal_note/logic_hooks_class.php',

    //The class the method is in.
    'Update_Name_And_Assigned',

    //The method to call.
    'After_Update_Name_And_Assigned'
);

//VUT-S-Save internal note in Quote Detail (relate to other Quotes)
$hook_array['after_save'][] = Array(
    //Processing index. For sorting the array.
    34,

    //Label. A string value to identify the hook.
    'Save internal note to relate other quotes',

    //The PHP file where your class is located.
    'custom/modules/pe_internal_note/logic_hooks_class.php',

    //The class the method is in.
    'Save_Internal_Note_Relate_Quotes',

    //The method to call.
    'After_Save_Internal_Note_Relate_Quotes'
);
//VUT-E-Save internal note in Quote Detail (relate to other Quotes)

?>