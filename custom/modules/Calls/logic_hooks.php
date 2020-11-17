<?PHP

$hook_version = 1;
$hook_array = Array();

$hook_array['process_record'] = Array();
$hook_array['process_record'][] = Array(1, 'count', 'modules/Calls_Reschedule/reschedule_count.php', 'reschedule_count', 'count');
//thienpb code here
$hook_array['after_save'][] = Array(
    2,
    'push_call_to_solargain',
    'custom/modules/Calls/logic_hooks_class.php',
    'PushCallToSolargain',
    'after_save_method_push_cal_sg'
);
$hook_array['after_save'][] = Array(
    3,
    'change_subject_call',
    'custom/modules/Calls/logic_hooks_class.php',
    'ChangeSubjectCall',
    'after_save_method_change_subject'
);

$hook_array['after_save'][] = Array(
    4,
    'duplicate_call',
    'custom/modules/Calls/logic_hooks_class.php',
    'DuplicateCall',
    'after_save_method_duplicate_call'
);

$hook_array['before_save'][] = Array(
    5,
    'update_related',
    'custom/modules/Calls/logic_hooks_class.php',
    'UpdateRelated',
    'before_save_method_update_related'
);

//VUT-Create internal note
$hook_array['after_save'][] = Array(
    6, 
    'create_internal_notes_Calls',
    'custom/modules/PO_purchase_order/logic_hooks_class.php',
    'CreateInternalNotesCall',
    'after_save_createdInternalNotesCall',
  );
  