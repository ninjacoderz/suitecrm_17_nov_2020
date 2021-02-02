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





require_once('include/Dashlets/DashletGeneric.php');


class MyOpportunitiesDashlet extends DashletGeneric
{
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings, $dashletData;
        require('modules/Opportunities/Dashlets/MyOpportunitiesDashlet/MyOpportunitiesDashlet.data.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_TOP_OPPORTUNITIES', 'Opportunities');
        }

        $this->searchFields = $dashletData['MyOpportunitiesDashlet']['searchFields'];
        $this->columns = $dashletData['MyOpportunitiesDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Opportunities');
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function MyOpportunitiesDashlet($id, $def = null)
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct($id, $def);
    }


    //4.5.0g fix for upgrade issue where user_preferences table still refer to column as 'amount'

    //Bug fix for dashlet issue with amount_us and amount fields.
    public function process($lvsParams = array(), $id = null)
    {
//     	if(!empty($this->displayColumns)) {
//     	if(array_search('amount', $this->displayColumns)) {
//     		$this->displayColumns[array_search('amount', $this->displayColumns)] = 'amount_usdollar';
//     	}
//     	}
        // BinhNT temporary comment
        if(false /*!empty($this->displayColumns)*/) {
            if(array_search('email_address', $this->displayColumns)) {
                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', ea.email_address AS email_address' : 
                                            $lvsParams['custom_select'] . ', ea.email_address AS email_address';
                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', opportunities.description AS description' : 
                                            $lvsParams['custom_select'] . ', opportunities.description AS description' ;
                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', CONCAT("L", leads_cstm.solargain_lead_number_c, " Q", leads_cstm.solargain_quote_number_c) AS solargain_lead_number' : 
                                            $lvsParams['custom_select'] . ', CONCAT("L", leads_cstm.solargain_lead_number_c, " Q", leads_cstm.solargain_quote_number_c) AS solargain_lead_number';
                
                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', leads_cstm.distance_to_sg_c AS distance_to_sg' : 
                                            $lvsParams['custom_select'] . ', leads_cstm.distance_to_sg_c AS distance_to_sg';

                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', leads_cstm.solargain_offices_c AS sg_office' : 
                                            $lvsParams['custom_select'] . ', leads_cstm.solargain_offices_c AS sg_office';


                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', CONCAT("W:", IFNULL(leads.phone_work, ""), "/ M:", IFNULL(leads.phone_mobile,"")) AS phone_info' : 
                                            $lvsParams['custom_select'] . ', CONCAT("W:", IFNULL(leads.phone_work, ""), "/ M:", IFNULL(leads.phone_mobile,"")) AS phone_info';

                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', leads.id AS lead_id, CONCAT(leads.first_name, " ", leads.last_name) AS lead_name' : 
                                            $lvsParams['custom_select'] . ', leads.id AS lead_id, CONCAT(leads.first_name, " ", leads.last_name) AS lead_name';
                
                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', aos_quotes.id AS quote_id, aos_quotes.number AS quote_number' : 
                                            $lvsParams['custom_select'] . ', aos_quotes.id AS quote_id, aos_quotes.number AS quote_number';

                $lvsParams['custom_select'] = empty($lvsParams['custom_select']) ? ', CONCAT(leads.primary_address_street, ", ", leads.primary_address_city, ", ", leads.primary_address_state, ", ", leads.primary_address_postalcode) AS lead_address':
                                            $lvsParams['custom_select'] . ', CONCAT(leads.primary_address_street, ", ", leads.primary_address_city, ", ", leads.primary_address_state, ", ", leads.primary_address_postalcode) AS lead_address';
                //thien fix here
                $lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' LEFT JOIN email_addr_bean_rel eabr ON eabr.bean_id = jtl0.account_id AND eabr.deleted=0' : 
                                                $lvsParams['custom_from'] . ' LEFT JOIN email_addr_bean_rel eabr ON eabr.bean_id = accounts_opportunities.account_id AND eabr.deleted=0';
                $lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' LEFT JOIN email_addresses ea ON ea.id = eabr.email_address_id AND ea.deleted=0' : 
                                                $lvsParams['custom_from'] . ' LEFT JOIN email_addresses ea ON ea.id = eabr.email_address_id AND ea.deleted=0';

                $lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' LEFT JOIN leads ON leads.account_id = jtl0.account_id': 
                                                $lvsParams['custom_from'] . ' LEFT JOIN leads ON leads.account_id = jtl0.account_id';
                //thien change inner join = left join                                
                $lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' INNER JOIN leads_cstm ON leads_cstm.id_c = leads.id':
                                                $lvsParams['custom_from'] .' LEFT JOIN leads_cstm ON leads_cstm.id_c = leads.id';

                // Quote number

                $lvsParams['custom_from'] = empty($lvsParams['custom_from']) ? ' LEFT JOIN aos_quotes ON aos_quotes.opportunity_id = jtl0.opportunity_id':
                                                $lvsParams['custom_from'] .' LEFT JOIN aos_quotes ON aos_quotes.opportunity_id = jtl0.opportunity_id';

            }
        }
        
     	parent::process($lvsParams);
    }
}
