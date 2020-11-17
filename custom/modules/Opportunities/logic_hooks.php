<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1;
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Opportunities/OpportunitiesJjwg_MapsLogicHook.php','OpportunitiesJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['before_save'][] = Array(1, 'Opportunities push feed', 'modules/Opportunities/SugarFeeds/OppFeed.php','OppFeed', 'pushFeed');
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Opportunities/OpportunitiesJjwg_MapsLogicHook.php','OpportunitiesJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
$hook_array['after_save'][] = Array(78, 'updateRelatedProjectGeocodeInfo', 'modules/Opportunities/OpportunitiesJjwg_MapsLogicHook.php','OpportunitiesJjwg_MapsLogicHook', 'updateRelatedProjectGeocodeInfo'); 
$hook_array['after_relationship_add'] = Array(); 
$hook_array['after_relationship_add'][] = Array(77, 'addRelationship', 'modules/Opportunities/OpportunitiesJjwg_MapsLogicHook.php','OpportunitiesJjwg_MapsLogicHook', 'addRelationship'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(77, 'deleteRelationship', 'modules/Opportunities/OpportunitiesJjwg_MapsLogicHook.php','OpportunitiesJjwg_MapsLogicHook', 'deleteRelationship');


$hook_array['after_save'][] = Array(
    //Processing index. For sorting the array.
    1000,

    //Label. A string value to identify the hook.
    'oppotunities_create_relationship',

    //The PHP file where your class is located./Users/nguyenthanhbinh/Documents/Sites/PureElectric/suitecrm/custom/modules/Opportunities
    'custom/modules/Opportunities/logic_hooks_class.php',

    //The class the method is in.
    'OpportunitiesCreateRelationship',

    //The method to call.
    'after_save_method'
);

$hook_array['before_save'][] = Array(
    //Processing index. For sorting the array.
    34,

    //Label. A string value to identify the hook.
    'push_to_solargain',

    //The PHP file where your class is located.
    'custom/modules/Opportunities/logic_hooks_class.php',

    //The class the method is in.
    'PushToSolargain',

    //The method to call.
    'before_save_method_pushToSolargain'
);
?>