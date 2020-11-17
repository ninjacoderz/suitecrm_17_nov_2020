<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateMeetingGeocodeInfo', 'modules/Meetings/MeetingsJjwg_MapsLogicHook.php','MeetingsJjwg_MapsLogicHook', 'updateMeetingGeocodeInfo'); 

$hook_array['before_save'][] = Array(
    36,
    'updateMeetingStartDate',
    'custom/modules/Meetings/logic_hooks_class.php',
    'MeetingStartDateChange',
    'before_save_method_changeStartDate'
);

?>