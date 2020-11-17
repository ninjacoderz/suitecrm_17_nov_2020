<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

/*********************************************************************************

 * Description:  base form for account
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class SMSTemplateFormBase
{
    

    public function handleSave($prefix, $redirect=true, $useRequired=false)
    {
        require_once('include/formbase.php');

        $focus = new pe_smstemplate();

        if ($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))) {
            return null;
        }
        $focus = populateFromPost($prefix, $focus);

        if (isset($GLOBALS['check_notify'])) {
            $check_notify = $GLOBALS['check_notify'];
        } else {
            $check_notify = false;
        }

        if (!$focus->ACLAccess('Save')) {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }

        $focus->save($check_notify);
        $return_id = $focus->id;
    
        $GLOBALS['log']->debug("Saved record with id of ".$return_id);


        if (!empty($_POST['is_ajax_call']) && $_POST['is_ajax_call'] == '1') {
            $json = getJSONobj();
            echo $json->encode(array('status' => 'success',
                                 'get' => ''));
            $trackerManager = TrackerManager::getInstance();
            $timeStamp = TimeDate::getInstance()->nowDb();
            if ($monitor = $trackerManager->getMonitor('tracker')) {
                $monitor->setValue('action', 'detailview');
                $monitor->setValue('user_id', $GLOBALS['current_user']->id);
                $monitor->setValue('module_name', 'pe_smstemplate');
                $monitor->setValue('date_modified', $timeStamp);
                $monitor->setValue('visible', 1);

                if (!empty($this->bean->id)) {
                    $monitor->setValue('item_id', $return_id);
                    $monitor->setValue('item_summary', $focus->get_summary_text());
                }
                $trackerManager->saveMonitor($monitor, true, true);
            }
            return null;
        }

        if (isset($_POST['popup']) && $_POST['popup'] == 'true') {
            $urlData = array("query" => true, "name" => $focus->name, "module" => 'pe_smstemplate', 'action' => 'Popup');
            if (!empty($_POST['return_module'])) {
                $urlData['module'] = $_POST['return_module'];
            }
            if (!empty($_POST['return_action'])) {
                $urlData['action'] = $_POST['return_action'];
            }
            foreach (array('return_id', 'popup', 'create', 'to_pdf') as $var) {
                if (!empty($_POST[$var])) {
                    $urlData[$var] = $_POST[$var];
                }
            }
            header("Location: index.php?".http_build_query($urlData));
            return;
        }
        if ($redirect) {
            handleRedirect($return_id, 'pe_smstemplate');
        } else {
            return $focus;
        }
    }
}
