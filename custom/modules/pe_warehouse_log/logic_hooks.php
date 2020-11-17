<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(1, 'Update Line Item', 'custom/modules/pe_warehouse_log/logic_hooks_class.php','UpdateLineItem', 'before_save_method');

$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(2, 'Deleted relationship with stock item', 'custom/modules/pe_warehouse_log/logic_hooks_class.php','UpdateLineItem', 'after_relationship_delete_method');
//tu-code
$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(3, 'rename_upload_files', 'custom/modules/pe_warehouse_log/logic_hooks_class.php','UpdateLineItem', 'after_save_method');
?>