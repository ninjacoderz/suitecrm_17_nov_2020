<?php
// ini_set("display_errors",1);
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

require_once('include/Dashlets/Dashlet.php');
require_once('include/ListView/ListViewSmarty.php');
require_once('include/generic/LayoutManager.php');

/**
 * Generic Dashlet class
 * @api
 */
class DashletGeneric extends Dashlet
{
    /**
      * Fields that are searchable
      * @var array
      */
    public $searchFields;
    /**
     * Displayable columns (ones available to display)
     * @var array
     */
    public $columns;
    /**
     * Bean file used in this Dashlet
     * @var bean
     */
    public $seedBean;
    /**
     * collection of filters to apply
     * @var array
     */
    public $filters = null;
    /**
     * Number of Rows to display
     * @var int
     */
    public $displayRows = '5';
    /**
     * Actual columns to display, will be a subset of $columns
     * @var array
     */
    public $displayColumns = null;
    /**
     * Flag to display only the current users's items.
     * @var bool
     */
    public $myItemsOnly = true;
    /**
     * Flag to display "myItemsOnly" checkbox in the DashletGenericConfigure.
     * @var bool
     */
    public $showMyItemsOnly = true;
    /**
     * location of Smarty template file for display
     * @var string
     */
    public $displayTpl = 'include/Dashlets/DashletGenericDisplay.tpl';
    /**
     * location of smarty template file for configuring
     * @var string
     */
    public $configureTpl = 'include/Dashlets/DashletGenericConfigure.tpl';
    /**
     * smarty object for the generic configuration template
     * @var string
     */
    public $configureSS;
    /** search inputs to be populated in configure template.
     *  modify this after processDisplayOptions, but before displayOptions to modify search inputs
     *  @var array
     */
    public $currentSearchFields;
    /**
     * ListView Smarty Class
     * @var Smarty
     */
    public $lvs;
    public $layoutManager;

    public function __construct($id, $options = null)
    {
        parent::__construct($id);
        $this->isConfigurable = true;
        if (isset($options)) {
            if (!empty($options['filters'])) {
                $this->filters = $options['filters'];
            }
            if (!empty($options['title'])) {
                $this->title = $options['title'];
            }
            if (!empty($options['displayRows'])) {
                $this->displayRows = $options['displayRows'];
            }
            if (!empty($options['displayColumns'])) {
                $this->displayColumns = $options['displayColumns'];
            }
            if (isset($options['myItemsOnly'])) {
                $this->myItemsOnly = $options['myItemsOnly'];
            }
            if (isset($options['autoRefresh'])) {
                $this->autoRefresh = $options['autoRefresh'];
            }
        }

        $this->layoutManager = new LayoutManager();
        $this->layoutManager->setAttribute('context', 'Report');
        // fake a reporter object here just to pass along the db type used in many widgets.
        // this should be taken out when sugarwidgets change
        $db = DBManagerFactory::getInstance();
        $temp = (object) array('db' => &$db, 'report_def_str' => '');
        $this->layoutManager->setAttributePtr('reporter', $temp);
        $this->lvs = new ListViewSmarty();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function DashletGeneric($id, $options = null)
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct($id, $options);
    }

    /**
     * Sets up the display options template
     *
     * @return string HTML that shows options
     */
    public function processDisplayOptions()
    {
        require_once('include/templates/TemplateGroupChooser.php');

        $this->configureSS = new Sugar_Smarty();
        // column chooser
        $chooser = new TemplateGroupChooser();

        $chooser->args['id'] = 'edit_tabs';
        $chooser->args['left_size'] = 5;
        $chooser->args['right_size'] = 5;
        $chooser->args['values_array'][0] = array();
        $chooser->args['values_array'][1] = array();

        $this->loadCustomMetadata();
        // Bug 39517 - Don't add custom fields automatically to the available fields to display in the listview
        //$this->addCustomFields();
        if ($this->displayColumns) {
            // columns to display
            foreach ($this->displayColumns as $num => $name) {
                // defensive code for array being returned
                $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                if (is_array($translated)) {
                    $translated = $this->columns[$name]['label'];
                }
                $chooser->args['values_array'][0][$name] = trim($translated, ':');
            }
            // columns not displayed
            foreach (array_diff(array_keys($this->columns), array_values($this->displayColumns)) as $num => $name) {
                // defensive code for array being returned
                $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                if (is_array($translated)) {
                    $translated = $this->columns[$name]['label'];
                }
                $chooser->args['values_array'][1][$name] = trim($translated, ':');
            }
        } else {
            foreach ($this->columns as $name => $val) {
                // defensive code for array being returned
                $translated = translate($this->columns[$name]['label'], $this->seedBean->module_dir);
                if (is_array($translated)) {
                    $translated = $this->columns[$name]['label'];
                }
                if (!empty($val['default']) && $val['default']) {
                    $chooser->args['values_array'][0][$name] = trim($translated, ':');
                } else {
                    $chooser->args['values_array'][1][$name] = trim($translated, ':');
                }
            }
        }

        $chooser->args['left_name'] = 'display_tabs';
        $chooser->args['right_name'] = 'hide_tabs';
        // BinhNT Edit here
        $chooser->args['max_left'] = '15';

        $chooser->args['left_label'] =  $GLOBALS['app_strings']['LBL_DISPLAY_COLUMNS'];
        $chooser->args['right_label'] =  $GLOBALS['app_strings']['LBL_HIDE_COLUMNS'];
        $chooser->args['title'] =  '';
        $this->configureSS->assign('columnChooser', $chooser->display());

        $query = false;
        $count = 0;

        if (!is_array($this->filters)) {
            // use default search params
            $this->filters = array();
            foreach ($this->searchFields as $name => $params) {
                if (!empty($params['default'])) {
                    $this->filters[$name] = $params['default'];
                }
            }
        }
        $currentSearchFields = array();
        foreach ($this->searchFields as $name=>$params) {
            if (!empty($name)) {
                $name = strtolower($name);
                $currentSearchFields[$name] = array();
                $widgetDef = $this->seedBean->field_defs[$name];
                if ($widgetDef['name'] == 'assigned_user_name') {
                    $widgetDef['name'] = 'assigned_user_id';
                }
                //bug 39170 - begin
                if ($widgetDef['name'] == 'created_by_name') {
                    $name = $widgetDef['name'] = 'created_by';
                }
                if ($widgetDef['name'] == 'modified_by_name') {
                    $name = $widgetDef['name'] = 'modified_user_id';
                }
                //bug 39170 - end
                if ($widgetDef['type']=='enum') {
                    $filterNotSelected = array(); // we need to have some value otherwise '' or null values make -none- to be selected by default
                } else {
                    $filterNotSelected = '';
                }
                $widgetDef['input_name0'] = empty($this->filters[$name]) ? $filterNotSelected : $this->filters[$name];

                $currentSearchFields[$name]['label'] = !empty($params['label']) ? translate($params['label'], $this->seedBean->module_dir) : translate($widgetDef['vname'], $this->seedBean->module_dir);
                $currentSearchFields[$name]['input'] = $this->layoutManager->widgetDisplayInput($widgetDef, true, (empty($this->filters[$name]) ? '' : $this->filters[$name]));
            } else { // ability to create spacers in input fields
                $currentSearchFields['blank' + $count]['label'] = '';
                $currentSearchFields['blank' + $count]['input'] = '';
                $count++;
            }
        }
        $this->currentSearchFields = $currentSearchFields;

        $this->configureSS->assign('strings', array('general' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_GENERAL'],
                                     'filters' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_FILTERS'],
                                     'myItems' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY'],
                                     'displayRows' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_DISPLAY_ROWS'],
                                     'title' => $GLOBALS['mod_strings']['LBL_DASHLET_CONFIGURE_TITLE'],
                                     'save' => $GLOBALS['app_strings']['LBL_SAVE_BUTTON_LABEL'],
                                     'clear' => $GLOBALS['app_strings']['LBL_CLEAR_BUTTON_LABEL'],
                                     'autoRefresh' => $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH'],
                                     ));
        $this->configureSS->assign('id', $this->id);
        $this->configureSS->assign('showMyItemsOnly', $this->showMyItemsOnly);
        $this->configureSS->assign('myItemsOnly', $this->myItemsOnly);
        $this->configureSS->assign('searchFields', $this->currentSearchFields);
        $this->configureSS->assign('showClearButton', $this->isConfigPanelClearShown);
        // title
        $this->configureSS->assign('dashletTitle', $this->title);

        // display rows
        $displayRowOptions = $GLOBALS['sugar_config']['dashlet_display_row_options'];
        $this->configureSS->assign('displayRowOptions', $displayRowOptions);
        $this->configureSS->assign('displayRowSelect', $this->displayRows);

        if ($this->isAutoRefreshable()) {
            $this->configureSS->assign('isRefreshable', true);
            $this->configureSS->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
            $this->configureSS->assign('autoRefreshSelect', $this->autoRefresh);
        }
    }
    /**
     * Displays the options for this Dashlet
     *
     * @return string HTML that shows options
     */
    public function displayOptions()
    {
        $this->processDisplayOptions();
        return parent::displayOptions() . $this->configureSS->fetch($this->configureTpl);
    }

    public function buildWhere()
    {
        global $current_user;

        $returnArray = array();

        if (!is_array($this->filters)) {
            // use defaults
            $this->filters = array();
            foreach ($this->searchFields as $name => $params) {
                if (!empty($params['default'])) {
                    $this->filters[$name] = $params['default'];
                }
            }
        }
        foreach ($this->filters as $name=>$params) {
            if (!empty($params)) {
                if ($name == 'assigned_user_id' && $this->myItemsOnly) {
                    continue;
                } // don't handle assigned user filter if filtering my items only
                $widgetDef = $this->seedBean->field_defs[$name];

                $widgetClass = $this->layoutManager->getClassFromWidgetDef($widgetDef, true);
                $widgetDef['table'] = $this->seedBean->table_name;
                $widgetDef['table_alias'] = $this->seedBean->table_name;
                if (!empty($widgetDef['source']) && $widgetDef['source'] == 'custom_fields') {
                    $widgetDef['table'] = $this->seedBean->table_name."_cstm";
                    $widgetDef['table_alias'] = $widgetDef['table'];
                }
                switch ($widgetDef['type']) {// handle different types
                    case 'date':
                    case 'datetime':
                    case 'datetimecombo':
                        if (is_array($params) && !empty($params)) {
                            if (!empty($params['date'])) {
                                $widgetDef['input_name0'] = $params['date'];
                            }
                            //thienpb custom
                            if (!empty($params['start_date'])) {
                                $widgetDef['input_name0'] = $params['start_date'];
                            }
                            if (!empty($params['end_date'])) {
                                $widgetDef['input_name1'] = $params['end_date'];
                            }else{
                                if (!empty($params['start_date'])) {
                                    $widgetDef['input_name1'] = $params['start_date'];
                                }
                            }
                            $filter = 'queryFilter' . $params['type'];
                        } else {
                            $filter = 'queryFilter' . $params;
                        }
                        //thienpb code
                        if($params['type'] == 'TP_between_days'){
                            $widgetDef['between_start'] = $params['between_start'];
                            $widgetDef['between_end'] = $params['between_end'];
                            $widgetDef['start_plus_div'] = ($params['start_plus_div'] == 'plus' ) ? '+' : '-';
                            $widgetDef['end_plus_div'] = ($params['end_plus_div'] == 'plus' ) ? '+' : '-';
                        }
                        array_push($returnArray, $widgetClass->$filter($widgetDef, true));
                        break;
                    case 'assigned_user_name':
                        // This type runs through the SugarWidgetFieldname class, and needs a little extra help to make it through
                        if (! isset($widgetDef['column_key'])) {
                            $widgetDef['column_key'] = $name;
                        }
                        // no break here, we want to run through the default handler
                    case 'relate':
                        //thienpb - fixed search field relate of dashlet in dashboard.
                        if (isset($widgetDef['link']) && $this->seedBean->load_relationship($widgetDef['link'])) {
                            $widgetLink = $widgetDef['link'];
                            $widgetDef['module'] = $this->seedBean->$widgetLink->focus->module_name;
                            $widgetDef['link'] = $this->seedBean->$widgetLink->getRelationshipObject()->name;
                        }else{
                            $widgetDef['module'] = $this->seedBean->module_name;
                        }
                        // no break - run through the default handler
                    default:
                        $widgetDef['input_name0'] = $params;
                        if ((is_array($params) && !empty($params)) || $widgetDef['type'] == 'relate') { // handle array query
                            array_push($returnArray, $widgetClass->queryFilterone_of($widgetDef, false));
                        } else {
                            array_push($returnArray, $widgetClass->queryFilterStarts_With($widgetDef, true));
                        }
                        $widgetDef['input_name0'] = $params;
                    break;
                }
            }
        }

        if ($this->myItemsOnly) {
            array_push($returnArray, $this->seedBean->table_name . '.' . "assigned_user_id = '" . $current_user->id . "'");
        }

        return $returnArray;
    }

    protected function loadCustomMetadata()
    {
        $customMetadata = 'custom/modules/'.$this->seedBean->module_dir.'/metadata/dashletviewdefs.php';
        if (file_exists($customMetadata)) {
            require($customMetadata);
            $this->searchFields = $dashletData[$this->seedBean->module_dir.'Dashlet']['searchFields'];
            foreach ($this->searchFields  as $key =>$def) {
                if ($key == 'assigned_user_name') {
                    $this->searchFields['assigned_user_id'] = $def;
                    unset($this->searchFields['assigned_user_name']);
                    break;
                }
            }

            $this->columns = $dashletData[$this->seedBean->module_dir.'Dashlet']['columns'];
        }
    }

    /**
     * Does all dashlet processing, here's your chance to modify the rows being displayed!
     */
    public function process($lvsParams = array(), $id = null)
    {
        $currentSearchFields = array();
        $configureView = true; // configure view or regular view
        $query = false;
        $whereArray = array();
        $lvsParams['massupdate'] = false;

        $this->loadCustomMetadata();
        $this->addCustomFields();
        // apply filters
        if (isset($this->filters) || $this->myItemsOnly) {
            //Thienpb code
            if($this->title == 'My Invoices'){
                switch($_REQUEST['product_type']){
                    case 'sanden':
                        $this->filters = array();
                        $this->filters['quote_type_c'][0] = 'quote_type_sanden';
                        break;
                    case 'methven':
                        $this->filters = array();
                        $this->filters['quote_type_c'][0] = 'quote_type_methven';
                        break;
                    case 'solar':
                        $this->filters = array();
                        $this->filters['quote_type_c'][0] = 'quote_type_solar';
                        break;
                    case 'daikin_us7':
                        $this->filters = array();
                        $this->filters['quote_type_c'][0] = 'quote_type_daikin';
                        break;
                    case 'daikin_nexura':
                        $this->filters = array();
                        $this->filters['quote_type_c'][0] = 'quote_type_nexura';
                        break;
                    case 'reset':
                        $this->filters = array();
                        break;
                }
                if($_REQUEST['product_type_methven'] == true){
                    $this->filters = array();
                    $this->filters['quote_type_c'][0] = 'quote_type_methven';
                }else if($_REQUEST['product_type_sanden'] == true){
                    $this->filters = array();
                    $this->filters['quote_type_c'][0] = 'quote_type_sanden';
                }else if($_REQUEST['reset_product_type'] == true){
                    $this->filters = array();
                }
                $this->filters['status'] = array(0 =>'Unpaid',1 =>'Partpaid',2 =>'Deposit_Paid',3 =>'Progress_Paid',4 =>'Variation_Unpaid',5 =>'STC_VEEC_Unpaid',6 =>'STC_Unpaid',7 =>'VEEC_Unpaid',8 =>'Paid');
            }
            if(!empty($_REQUEST["cal_filter"])){
                $this->customConvertFilter($_REQUEST["cal_filter"]);
            }
            // custom button today filter in dashlets
            foreach ($_REQUEST as $key => $val) {
                if (str_end($key, '_ORDER_BY')) {
                    $field_OrderBy = $val;
                }
            }
            if(strpos($field_OrderBy,"date") !== false){
                if(!empty($_REQUEST["custom_filter"]) ){ 
                    switch ($_REQUEST["custom_filter"]) {
                        case 'today':
                                $this->filters[$field_OrderBy] = array ('type' => 'TP_equals_today');             
                            break;
                        case 'reset':
                            $this->filters = array();
                            break;                    
                        default:
                            $this->filters = array();
                            break;
                    }
                }
            }

            $whereArray = $this->buildWhere();
        }
        //dung code -  find value offset_today
        if($this->title == 'My Top Open Opportunities' && !empty($whereArray)){
            $today = date("Y-m-d");
            
            $db = DBManagerFactory::getInstance();

            $sql_count = 'SELECT COUNT(*) FROM opportunities WHERE deleted="0"AND date_closed >= "' .$today .'"';
            $sql_count_today = 'SELECT COUNT(*) FROM opportunities WHERE deleted="0"AND date_closed = "' .$today .'"';

            foreach ($whereArray as  $value) {
                $sql_count = $sql_count .' AND ' .$value;
                $sql_count_today = $sql_count_today .' AND ' .$value;
            }
            $result = $db->query($sql_count);
            $offset_today = $db->fetchByAssoc($result);
    
            $result_today = $db->query($sql_count_today);
            $count_opportunity_today = $db->fetchByAssoc($result_today);
    
            $offset_today = $offset_today['COUNT(*)'] - $count_opportunity_today['COUNT(*)'] ;
        } elseif ($this->title == 'My Top Open Opportunities' && empty($whereArray)){
            $today = date("Y-m-d");
            
            $db = DBManagerFactory::getInstance();
    
            $sql_count = 'SELECT COUNT(*) FROM opportunities WHERE deleted="0"AND date_closed >= "' .$today .'"';
            $result = $db->query($sql_count);
            $offset_today = $db->fetchByAssoc($result);
    
            $sql_count_today = 'SELECT COUNT(*) FROM opportunities WHERE deleted="0"AND date_closed = "' .$today .'"';
            $result_today = $db->query($sql_count_today);
            $count_opportunity_today = $db->fetchByAssoc($result_today);
    
            $offset_today = $offset_today['COUNT(*)'] - $count_opportunity_today['COUNT(*)'] ;
        }                     

        $this->lvs->export = false;
        $this->lvs->multiSelect = false;
        // columns
        $displayColumns = array();
        if (!empty($this->displayColumns)) { // use user specified columns
            foreach ($this->displayColumns as $name => $val) {
                $displayColumns[strtoupper($val)] = $this->columns[$val];
                $displayColumns[strtoupper($val)]['label'] = trim($displayColumns[strtoupper($val)]['label'], ':');// strip : at the end of headers
            }
        } else {
            if (isset($this->columns)) {
                // use the default
                foreach ($this->columns as $name => $val) {
                    if (!empty($val['default']) && $val['default']) {
                        $displayColumns[strtoupper($name)] = $val;
                        $displayColumns[strtoupper($name)]['label'] = trim($displayColumns[strtoupper($name)]['label'], ':');
                    }
                }
            }
        }
        $this->lvs->displayColumns = $displayColumns;


        $this->lvs->lvd->setVariableName($this->seedBean->object_name, array());
        $lvdOrderBy = $this->lvs->lvd->getOrderBy(); // has this list been ordered, if not use default

        $nameRelatedFields = array();

        //bug: 44592 - dashlet sort order was not being preserved between logins
        if (!empty($lvsParams['orderBy']) && !empty($lvsParams['sortOrder'])) {
            $lvsParams['overrideOrder'] = true;
        } else {
            if (empty($lvdOrderBy['orderBy'])) {
                foreach ($displayColumns as $colName => $colParams) {
                    if (!empty($colParams['defaultOrderColumn'])) {
                        $lvsParams['overrideOrder'] = true;
                        $lvsParams['orderBy'] = $colName;
                        $lvsParams['sortOrder'] = $colParams['defaultOrderColumn']['sortOrder'];
                    }
                }
            }
        }
        //dung code - button jump today 
        if( isset($_REQUEST['sortOrder_closedate']) && $_REQUEST['sortOrder_closedate'] == '1') {
            $lvsParams['sortOrder'] = "DESC";
        }
        // Check for 'last_name' column sorting with related fields (last_name, first_name)
        // See ListViewData.php for actual sorting change.
        if ($lvdOrderBy['orderBy'] == 'last_name' && !empty($displayColumns['NAME']) && !empty($displayColumns['NAME']['related_fields']) &&
            in_array('last_name', $displayColumns['NAME']['related_fields']) &&
            in_array('first_name', $displayColumns['NAME']['related_fields'])) {
            $lvsParams['overrideLastNameOrder'] = true;
        }

        if (!empty($this->displayTpl)) {
            //MFH BUG #14296
            $where = '';
            if (!empty($whereArray)) {
                $where = '(' . implode(') AND (', $whereArray) . ')';
            }
            $this->lvs->setup($this->seedBean, $this->displayTpl, $where, $lvsParams, 0, $this->displayRows/*, $filterFields*/, array(), 'id', $id);
            if (in_array('CREATED_BY', array_keys($displayColumns))) { // handle the created by field
                foreach ($this->lvs->data['data'] as $row => $data) {
                    $this->lvs->data['data'][$row]['CREATED_BY'] = get_assigned_user_name($data['CREATED_BY']);
                }
            }
            // assign a baseURL w/ the action set as DisplayDashlet
            foreach ($this->lvs->data['pageData']['urls'] as $type => $url) {
                // awu Replacing action=DisplayDashlet with action=DynamicAction&DynamicAction=DisplayDashlet
                if ($type == 'orderBy') {
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DynamicAction&DynamicAction=displayDashlet&', $url);
                } else {
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DynamicAction&DynamicAction=displayDashlet&', $url) . '&sugar_body_only=1&id=' . $this->id;
                }
            }

            //dung code - button jump today
            if(isset($offset_today)){
                $this->lvs->data['pageData']['urls']['jump_today'] = "index.php?page_id=0&entryPoint=retrieve_dash_page&lvso=DESC&Home2_OPPORTUNITY_offset=" .$offset_today ."&sugar_body_only=1&id=" .$this->id;
                $this->lvs->data['pageData']['urls']['reset_today'] = "index.php?page_id=0&entryPoint=retrieve_dash_page&lvso=DESC&Home2_OPPORTUNITY_offset=0&sugar_body_only=1&id=" .$this->id;
            }

            $url_custom_today = 'index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&module=Home&to_pdf=1&id=' . $this->id;
            foreach ($this->lvs->data['pageData']['queries']['orderBy'] as $key => $value) {
                if ($key == 'lvso') {
                    $url_custom_today .= '&lvso='.$value;
                } 
                // $field_OrderBy;
                if (str_end($key, '_ORDER_BY')) {
                    $url_custom_today .= '&'.$key.'=' .$this->lvs->data['pageData']['ordering']['orderBy'];
                }
            }
  
             
            $this->lvs->data['pageData']['urls']['url_custom_today'] =  $url_custom_today;
            //thienpb code
            $this->lvs->data['pageData']['urls']['product_type'] =  'index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&lvso=DESC&Home2_AOS_INVOICES_ORDER_BY=number&module=Home&to_pdf=1&id=' . $this->id;
            $this->lvs->data['pageData']['urls']['reset_product_type'] = 'index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&lvso=DESC&Home2_AOS_INVOICES_ORDER_BY=number&module=Home&to_pdf=1&id=' . $this->id;
            
            //thienpb code UI add select filter to dashboard
            $this->lvs->ss->assign('dashletId', $this->id);
            if($this->seedBean->object_name == "Call"){
                global $app_strings;
                $SAVED_SEARCHES_OPTIONS = '';
                $savedSearch = BeanFactory::newBean('SavedSearch');
                $SAVED_SEARCHES_OPTIONS = $savedSearch->getSelect('Calls');
                $strSaveSearch = '';
                if (!empty($SAVED_SEARCHES_OPTIONS)) {
                    $SAVED_SEARCHES_OPTIONS = str_replace('SUGAR.savedViews.shortcut_select(this, "Calls");','return SUGAR.mySugar.retrieveDashlet("'.$this->id.'", "index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&lvso=DESC&module=Home&to_pdf=1&id='.$this->id.'&cal_filter="+this.value, false, false, true, $(this).closest("div[id^=pageNum_][id$=_div]").parent().parent())',$SAVED_SEARCHES_OPTIONS);
                    $SAVED_SEARCHES_OPTIONS = str_replace('saved_search_select','saved_search_select_'.str_replace('-','_',$this->id), $SAVED_SEARCHES_OPTIONS);
                    $strSaveSearch .= "
                        <span style='padding-left:20px;' class='white-space'><b>{$app_strings['LBL_SAVED_FILTER_SHORTCUT']}</b>
                            {$SAVED_SEARCHES_OPTIONS}
                        </span>";
                }
                $strSaveSearch .= "
                    <script>
                        var cal_filter_".str_replace('-','_',$this->id)." = '".$this->lvs->data['pageData']['queries']['baseURL']['cal_filter']."';
                        if(cal_filter_".str_replace('-','_',$this->id)." =='' || cal_filter_".str_replace('-','_',$this->id)." == 1){
                            $('body').find('#saved_search_select_".str_replace('-','_',$this->id)."').val('_none');
                            console.log('1',cal_filter_".str_replace('-','_',$this->id).")
                        }else{
                            console.log('2',cal_filter_".str_replace('-','_',$this->id).")
                            $('body').find('#saved_search_select_".str_replace('-','_',$this->id)."').val(cal_filter_".str_replace('-','_',$this->id).");
                        }
                    </script>";
                $this->lvs->ss->assign('saveSearch',$strSaveSearch);
            }
            //end
        }
    }

    /**
      * Displays the Dashlet, must call process() prior to calling this
      *
      * @return string HTML that displays Dashlet
      */
    public function display()
    {
        return parent::display() . $this->lvs->display(false) . $this->processAutoRefresh();
    }

    /**
     * Filter the $_REQUEST and only save only the needed options
     * @param array $req the array to pull options from
     *
     * @return array options array
     */
    public function saveOptions($req)
    {
        $options = array();

        $this->loadCustomMetadata();
        foreach ($req as $name => $value) {
            if (!is_array($value)) {
                $req[$name] = trim($value);
            }
        }
        $options['filters'] = array();
        foreach ($this->searchFields as $name=>$params) {
            $widgetDef = $this->seedBean->field_defs[$name];
            //bug39170 - begin
            if ($widgetDef['name']=='created_by_name' && $req['created_by']) {
                $widgetDef['name'] = 'created_by';
            }
            if ($widgetDef['name']=='modified_by_name' && $req['modified_user_id']) {
                $widgetDef['name'] = 'modified_user_id';
            }
            //bug39170 - end
            if ($widgetDef['type'] == 'datetimecombo' || $widgetDef['type'] == 'datetime' || $widgetDef['type'] == 'date') { // special case datetime types
                $options['filters'][$widgetDef['name']] = array();
                if (!empty($req['type_' . $widgetDef['name']])) { // save the type of date filter
                    $options['filters'][$widgetDef['name']]['type'] = $req['type_' . $widgetDef['name']];
                }
                if (!empty($req['date_' . $widgetDef['name']])) { // save the date
                    $options['filters'][$widgetDef['name']]['date'] = $req['date_' . $widgetDef['name']];
                }
                //thienpb code
                if($req['type_'.$name] == 'TP_between_days'){
                    $options['filters'][$widgetDef['name']]['between_start'] =  $req[$widgetDef['name'].'_TP_between_start'];
                    $options['filters'][$widgetDef['name']]['start_plus_div'] =  $req['sl_start_'.$widgetDef['name'].'_TP_plus_div'];
                    $options['filters'][$widgetDef['name']]['between_end'] = $req[$widgetDef['name'].'_TP_between_end'];
                    $options['filters'][$widgetDef['name']]['end_plus_div'] = $req['sl_end_'.$widgetDef['name'].'_TP_plus_div'];
                }else{
                    unset($options['filters'][$widgetDef['name']]['between_start']);
                    unset($options['filters'][$widgetDef['name']]['between_end']);
                    unset($options['filters'][$widgetDef['name']]['start_plus_div']);
                    unset($options['filters'][$widgetDef['name']]['end_plus_div']);
                }
            } elseif (!empty($req[$widgetDef['name']])) {
                $options['filters'][$widgetDef['name']] = $req[$widgetDef['name']];
            }
        }
        if (!empty($req['dashletTitle'])) {
            $options['title'] = $req['dashletTitle'];
        }

        // Don't save the options for myItemsOnly if we're not even showing the options.
        if ($this->showMyItemsOnly) {
            if (!empty($req['myItemsOnly'])) {
                $options['myItemsOnly'] = $req['myItemsOnly'];
            } else {
                $options['myItemsOnly'] = false;
            }
        }
        $options['displayRows'] = empty($req['displayRows']) ? '5' : $req['displayRows'];
        // displayColumns
        if (!empty($req['displayColumnsDef'])) {
            $options['displayColumns'] = explode('|', $req['displayColumnsDef']);
        }
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];
        return $options;
    }

    /**
     * Internal function to add custom fields
     *
     */
    public function addCustomFields()
    {
        foreach ($this->seedBean->field_defs as $fieldName => $def) {
            if (!empty($def['type']) && $def['type'] == 'html') {
                continue;
            }
            if (isset($def['vname'])) {
                $translated = translate($def['vname'], $this->seedBean->module_dir);
                if (is_array($translated)) {
                    $translated = $def['vname'];
                }
                if (!empty($def['source']) && $def['source'] == 'custom_fields') {
                    if (isset($this->columns[$fieldName]['default']) && $this->columns[$fieldName]['default']) {
                        $this->columns[$fieldName] = array('width' => '10',
                                                       'label' => $translated,
                                                       'default' => 1);
                    } else {
                        $this->columns[$fieldName] = array('width' => '10',
                                                       'label' => $translated);
                    }
                }
            }
        }
    }

    /**
     * Function custom filter
     */
    public function customConvertFilter($filter_id){
        global $timedate, $current_user;
        $now = $timedate->tzUser($timedate->getNow(), $current_user);
        $this->filters = [];
        if(!empty($filter_id)){
            $db = DBManagerFactory::getInstance();
            $sql = "SELECT id, name, contents FROM saved_search
                    WHERE deleted = 0 AND
                            id =  '$filter_id'";
            $ret =$db->query($sql);
            $saveSearch = [];
            while ($row = $ret->fetch_assoc()) {
                $saveSearch = $row['contents'];
            }
            $fields = array();
            $saveSearch = unserialize(base64_decode($saveSearch));
            $ignores = ["searchFormTab", "query", "search_module", "saved_search_action", "displayColumns", "hideTabs", "orderBy", "sortOrder","advanced"];
            $operator_date = ["=","not_equal","greater_than","less_than"];
            $operator_date_func_1 = ["="=>"Between_Dates","not_equal"=>"Not_Equals_str","greater_than"=>"After","less_than"=>"Before"];
            $operator_date_func_2 = ["last_7_days","next_7_days","last_30_days","next_30_days","last_month","this_month","next_month","last_year","this_year","next_year","between"];
            foreach ($saveSearch as $key => $value) {
                if (in_array($key, $ignores) || $value == null || $value =='') {
                    continue;
                }
                if(strpos($key,"date_") !== false){
                    preg_match('/^(.*?)_advanced_range_choice/',$key, $match);
                    if(count($match) > 1){
                        $field = $match[1];
                        if(in_array($value,$operator_date)){
                            $type = $operator_date_func_1[$value];
                            if($saveSearch['range_'.$field.'_advanced'] != ''){
                                $this->filters[$field]['type'] = $type;
                                $this->filters[$field]['start_date'] = $saveSearch['range_'.$field.'_advanced'];
                            }
                        }else{
                            if($value == "between_days" || $value == 'between_last_and_next_7_days'){
                                $this->filters[$field]['type'] = 'Between_Dates';
                            }else{
                                $this->filters[$field]['type'] = 'TP_'.$value;
                            }
                            if($saveSearch['start_days_range_'.$field.'_advanced'] != ''){
                                $this->filters[$field]['start_date'] =  $now->get('-'.$saveSearch['start_days_range_'.$field.'_advanced'].' days')->get_day_begin();
                            }
                            if($saveSearch['start_range_'.$field.'_advanced'] != ''){
                                $this->filters[$field]['start_date'] =  $saveSearch['start_range_'.$field.'_advanced'];
                            }
                            if($saveSearch['end_days_range_'.$field.'_advanced'] != ''){
                                $this->filters[$field]['end_date'] =  $now->get('+'.$saveSearch['end_days_range_'.$field.'_advanced'].' days')->get_day_begin();
                            }
                            if($saveSearch['end_range_'.$field.'_advanced'] != ''){
                                $this->filters[$field]['end_date'] =  $saveSearch['end_range_'.$field.'_advanced'];
                            }
                        }
                        if($this->filters[$field]['start_date'] == '' && $this->filters[$field]['end_date'] == ''){
                            if($value == 'between_last_and_next_7_days'){
                                $this->filters[$field]['start_date'] =  $now->get('-7 days')->get_day_begin();
                                $this->filters[$field]['end_date'] =  $now->get('+7 days')->get_day_begin();
                            }else{
                                if(!in_array($value,$operator_date_func_2)){
                                    unset($this->filters[$field]);
                                }
                            }
                        }
                    }else{
                        continue;
                    }
                }else if($key == "current_user_only_advanced"){
                    if($value == "1"){
                        global $current_user;
                        $this->filters["assigned_user_id"][0] = $current_user->id;
                    }
                }else{
                    $new_key = str_replace('_advanced', '', $key);
                    $this->filters[$new_key] = $saveSearch[$key];   
                }
            }
        } 
    }
}
