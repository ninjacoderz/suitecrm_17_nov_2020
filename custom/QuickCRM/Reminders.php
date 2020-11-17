<?php
function getRemindersListView($focus, $field, $value, $view){
	return Reminder::loadRemindersData($focus->module_name, $focus->id);
}
