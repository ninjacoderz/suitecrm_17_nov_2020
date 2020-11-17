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




global $current_user;

$dashletData['MyOpportunitiesDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
                                                               'opportunity_type' => array('default' => ''),
                                                               'sales_stage'      => array('default' =>
                                                                    array('Prospecting', 'Qualification', 'Needs Analysis', 'Value Proposition', 'Id. Decision Makers', 'Perception Analysis', 'Proposal/Price Quote', 'Negotiation/Review')),
                                                               'assigned_user_id' => array('type'    => 'assigned_user_name',
                                                                                            'label'   => 'LBL_ASSIGNED_TO',
                                                                                           'default' => $current_user->name));
                                                                                           
$dashletData['MyOpportunitiesDashlet']['columns'] = array('name' => array('width'   => '35',
                                                                          'label'   => 'LBL_OPPORTUNITY_NAME',
                                                                          'link'    => true,
                                                                          'default' => true
                                                                          ),
                                                          'account_name' => array('width'  => '35',
                                                                                  'label'   => 'LBL_ACCOUNT_NAME',
                                                                                  'default' => true,
                                                                                  'link' => false,
                                                                                  'id' => 'account_id',
                                                                                  'ACLTag' => 'ACCOUNT'),
                                                          'amount_usdollar' => array('width'   => '15',
                                                                            'label'   => 'LBL_AMOUNT_USDOLLAR',
                                                                            'default' => true,
                                                                            'currency_format' => true),
                                                          'date_closed' => array('width'   => '15',
                                                                                 'label'   => 'LBL_DATE_CLOSED',
                                                                                 'default'        => true,
                                                                                 'defaultOrderColumn' => array('sortOrder' => 'ASC')),
                                                          'opportunity_type' => array('width'   => '15',
                                                                                      'label'   => 'LBL_TYPE'),
                                                          'lead_source' => array('width'   => '15',
                                                                                 'label'   => 'LBL_LEAD_SOURCE'),
                                                          'sales_stage' => array('width'   => '15',
                                                                                 'label'   => 'LBL_SALES_STAGE'),
                                                          'probability' => array('width'   => '15',
                                                                                  'label'   => 'LBL_PROBABILITY'),
                                                          'date_entered' => array('width'   => '15',
                                                                                  'label'   => 'LBL_DATE_ENTERED'),
                                                          'date_modified' => array('width'   => '15',
                                                                                   'label'   => 'LBL_DATE_MODIFIED'),
                                                          'created_by' => array('width'   => '8',
                                                                                'label'   => 'LBL_CREATED'),
                                                          'assigned_user_name' => array('width'   => '8',
                                                                                        'label'   => 'LBL_LIST_ASSIGNED_USER'),
                                                          'next_step' => array('width' => '10',
                                                                'label' => 'LBL_NEXT_STEP'),
                                                            // BinhNT Do the code here

                                                            'phone_office' => array('width'  => '35', 
                                                                  'label'   => 'LBL_PHONE_OFFICE',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'id' => 'phone_office_id',
                                                            ),

                                                            'billing_address_street' => array('width'  => '35', 
                                                                  'label'   => 'LBL_BILLING_ADDRESS_STREET',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'id' => 'billing_address_street_id'
                                                            ),

                                                            'email_address' => array('width'  => '35', 
                                                                  'label'   => 'LBL_EMAIL_ADDRESS',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'email_address',
                                                                  'customCode' => '{$CUSTOM_EMAIL_LINK}',
                                                            ),

                                                            'solargain_lead_number' => array('width'  => '35', 
                                                                  'label'   => 'SG Link',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'solargain_lead_number',
                                                                  'customCode' => '{$SOLARGAIN_LINK}',
                                                            ),

                                                            'phone_info' => array('width'  => '35', 
                                                                  'label'   => 'Phone Number',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'phone_info',
                                                                  
                                                            ),

                                                            'lead_link' => array('width'  => '35', 
                                                                  'label'   => 'Lead Link',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'lead_link',
                                                            ),

                                                            'quote_link' => array('width'  => '35', 
                                                                  'label'   => 'Quote Link',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'quote_link',
                                                            ),

                                                            'map_link' => array('width'  => '35', 
                                                                  'label'   => 'Map Link',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'map_link',
                                                            ),

                                                            'distance_to_sg' => array('width'  => '35', 
                                                                  'label'   => 'Distance To SG',
                                                                  'default' => true,
                                                                  'link' => false,
                                                                  'name' => 'distance_to_sg',
                                                            ),
                                                           );
