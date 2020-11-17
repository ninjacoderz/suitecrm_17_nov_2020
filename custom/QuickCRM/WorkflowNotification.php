<?php
class QworkflowNotification
{
    public function __construct()
    {
    }

	private function $authorized_user($user_id){
        global $sugar_config, $db;

		require_once('modules/QuickCRM/license/OutfittersLicense.php');
		$authorized = QOutfittersLicense::isValid('QuickCRM',$user_id) === true;


		return authorized;
	}

	private function ParseNotification($record,$notification_text){
        return $notification_text;
	}

	private function getNotificationText($record,$notification_text){
        return $notification_text;
	}

	public function PushNotification($user_id,$record,$notification_text){
        // check if user is authorized for mobile access
        if ($this->authorized_user($user_id)){
        }
	}

	public function before_save(&$bean,$event,$args)
	{
        global $current_user, $db, $sugar_config;
        
        // SuiteCRM only
		if (!isset($sugar_config['suitecrm_version'])){ return; }
        
        $module = $bean->module_name;
        $id = $bean->id;
        $assigned_user = $bean->assigned_user_id;
        $previous_assigned_user = $bean->fetched_row['assigned_user_id'];
        $modified_by = $bean->modified_user_id;
        
        // check if module is enabled for mobile access
        $module_access = true;
        if ($module_access){
            if (($modified_by != $assigned_user) && ($assigned_user != $previous_assigned_user) {  	
                $this->PushNotification($assigned_user, $bean, $notification_text);
            }
        }
        
	}

}


?>