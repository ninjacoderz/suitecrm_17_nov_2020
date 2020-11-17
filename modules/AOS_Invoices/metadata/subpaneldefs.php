<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 * Products, Quotations & Invoices modules.
 * Extensions to SugarCRM
 * @package Advanced OpenSales for SugarCRM
 * @subpackage Products
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility Ltd <support@salesagility.com>
 */


$layout_defs['AOS_Invoices'] = array(
    'subpanel_setup' => array(
        'aos_quotes_aos_invoices' =>
        array(
            'order' => 100,
            'module' => 'AOS_Quotes',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'AOS_Quotes',
            'get_subpanel_data' => 'aos_quotes_aos_invoices',
            'top_buttons' =>
            array(
                    0 =>
                    array(
                          'widget_class' => 'SubPanelTopCreateButton',
                    ),
                    1 =>
                    array(
                          'widget_class' => 'SubPanelTopSelectButton',
                          'popup_module' => 'AOS_Quotes',
                          'mode' => 'MultiSelect',
                    ),
            ),
        ),
		// BinhNT code here
		'activities' => array(
            'order' => 10,
            'sort_order' => 'desc',
            'sort_by' => 'date_start',
            'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'activities',   //this values is not associated with a physical file.
            'module' => 'Activities',

            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateTaskButton'),
                array('widget_class' => 'SubPanelTopScheduleMeetingButton'),
                array('widget_class' => 'SubPanelTopScheduleCallButton'),
                array('widget_class' => 'SubPanelTopComposeEmailButton'),
            ),

            'collection_list' => array(
                
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'calls',
				),
				'oldcalls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'function:get_invoice_calls',
                    'set_subpanel_data' => 'oldcalls',
                    'generate_select' => true,
                ),
                
            )
        ),

        'history' => array(
            'order' => 20,
            'sort_order' => 'desc',
            'sort_by' => 'date_entered',
            'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'history',   //this values is not associated with a physical file.
            'module' => 'History',

            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
                array('widget_class' => 'SubPanelTopArchiveEmailButton'),
                array('widget_class' => 'SubPanelTopSummaryButton'),
                array('widget_class' => 'SubPanelTopFilterButton'),
            ),

            'collection_list' => array(
                
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'calls',
				),
				'oldcalls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'function:get_invoice_calls',
                    'set_subpanel_data' => 'oldcalls',
                    'generate_select' => true,
                ),
                'emails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'function:get_invoice_emails',
                    'set_subpanel_data' => 'emails',
                    'generate_select' => true,
                ),
                
            ),
            'searchdefs' => array(
                'collection' =>
                    array(
                        'name' => 'collection',
                        'label' => 'LBL_COLLECTION_TYPE',
                        'type' => 'enum',
                        'options' => $GLOBALS['app_list_strings']['collection_temp_list'],
                        'default' => true,
                        'width' => '10%',
                    ),
                'name' =>
                    array(
                        'name' => 'name',
                        'default' => true,
                        'width' => '10%',
                    ),
                'current_user_only' =>
                    array(
                        'name' => 'current_user_only',
                        'label' => 'LBL_CURRENT_USER_FILTER',
                        'type' => 'bool',
                        'default' => true,
                        'width' => '10%',
                    ),
                'date_modified' =>
                    array(
                        'name' => 'date_modified',
                        'default' => true,
                        'width' => '10%',
                    ),
            ),
        ),	
	),
);
