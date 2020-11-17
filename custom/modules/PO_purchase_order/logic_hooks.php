<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 

use phpDocumentor\Reflection\Types\Array_;

$hook_version = 1; 
$hook_array = Array(); 
// position, file, function 

$hook_array['after_save']   = Array();
$hook_array['after_save'][] = Array(1, 'Automatic_Create_Or_Update', 'custom/modules/PO_purchase_order/logic_hooks_class.php','AutoXeroPO', 'after_save_method');
//VUT-Create Internal Note when PO's status is changed
$hook_array['after_save'][] = Array(
  2, 
  'create_internal_notes_change_status',
  'custom/modules/PO_purchase_order/logic_hooks_class.php',
  'CreateInternalNotesPO',
  'after_save_createdInternalNotesChangeStatus',
);
?>