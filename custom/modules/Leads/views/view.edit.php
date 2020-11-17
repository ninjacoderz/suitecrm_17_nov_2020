<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/


class LeadsViewEdit extends ViewEdit
{
 	public function __construct()
 	{
 		parent::__construct();
 		$this->useForSubpanel = true;
 		$this->useModuleQuickCreateTemplate = true;
 	}

	function display()
	{

		$this->populateLeadTemplates();
		parent::display();

		require_once ('include/SubPanel/SubPanelTiles.php');
		$subpanel = new SubPanelTiles($this->bean, $this->module);
		$subpanel_internal_notes = $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_pe_internal_note_1'];
		$subpanel->subpanel_definitions->layout_defs['subpanel_setup'] = ['leads_pe_internal_note_1' => $subpanel_internal_notes ];
		echo '<form id="DetailView" method="POST" name="DetailView">
					<input hidden name="module" value="Leads" />
					<input hidden name="record" value="'. $this->ev->focus->id.'" />
				</form>';
		echo '<div id="hack_code">';
		echo $subpanel->display();
		echo '<input hidden name="record" value="'. $this->ev->focus->id.'" />';
		echo  '</div>
		<script>
		$("#groupTabs").hide();  
		$(\'div[class="buttons"]\').last().css({\'position\':\'absolute\',\'bottom\':\'0px\'}); 
		$(\'#pagecontent\').css({\'position\':\'relative\',\'padding-bottom\':\'50px\'});
		</script>';

	}

	public function populateLeadTemplates()
	{
		global $app_list_strings;

		$sql = "SELECT id, name FROM aos_pdf_templates WHERE deleted='0' AND type='Leads'";
		$res = $this->bean->db->query($sql);

		$app_list_strings['template_ddown_c_list'] = array();
		while ($row = $this->bean->db->fetchByAssoc($res)) {
			$app_list_strings['template_ddown_c_list'][$row['id']] = $row['name'];
		}
	}
}