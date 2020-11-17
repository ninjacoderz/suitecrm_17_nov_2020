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


class QCRM_HomepageViewDetail extends ViewDetail
{
 	public function __construct()
 	{
 		parent::__construct();
 	}

	public function decode_dashlets($list)
	{
		$str = "";
		foreach ($list as $idx => $def){
			$search = BeanFactory::getBean('QCRM_SavedSearch',$def->id);
			$str .= '<li id="S_'.$def->id.'" ' .($def->id == 'Today'?'style="display:none;"':''). '>'.$search->name.'</li>';
		}
		return $str;
	}
	public function decode_creates($list)
	{
		$str = "";
		foreach ($list as $idx => $def){
			$str .= '<li id="CR_'.$idx.'">'.$def.'</li>';
		}
		return $str;
	}

	public function decode_icons($list)
	{
		$str = "";
		foreach ($list as $idx => $def){
			$search = BeanFactory::getBean('QCRM_SavedSearch',$def->id);
			$str .= '<li id="S_'.$def->id.'">'.$search->name.'</li>';
		}
		return $str;
	}
 	public function display()
 	{
		if (isset($this->bean->id) && !empty ($this->bean->id)) {
			if (!empty ($this->bean->icons)){
				$icons_list = json_decode(base64_decode($this->bean->icons));
				$this->ss->assign('icons_list', $this->decode_icons($icons_list));
			}
			if (!empty ($this->bean->dashlets)){
				$dashlets_list = json_decode(base64_decode($this->bean->dashlets));
				$this->ss->assign('dashlets_list', $this->decode_dashlets($dashlets_list));
			}
			if (!empty ($this->bean->creates)){
				$creates_list = json_decode(base64_decode($this->bean->creates));
				$this->ss->assign('creates_list', $this->decode_creates($creates_list));
			}			
		}
		parent::display();

 	}
}