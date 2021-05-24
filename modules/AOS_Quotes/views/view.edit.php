<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 * Advanced OpenSales, Advanced, robust set of sales modules.
 * @package Advanced OpenSales for SugarCRM
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
 * @author SalesAgility <info@salesagility.com>
 */



class AOS_QuotesViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_QuotesViewEdit()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    public function display()
    {
        global $current_user;
        $this->populateQuoteTemplates();
        $this->alterRelateLeadField(); //thienpb
        parent::display();
        $template = new Sugar_Smarty();
        echo $template->fetch('modules/AOS_Quotes/templates/popupTemplateSpecialNotes.tpl');

        // dung code -- render subpanel internal notes
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
        $subpanel_internal_notes = $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['aos_quotes_pe_internal_note_1'];
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup'] = ['aos_quotes_pe_internal_note_1' => $subpanel_internal_notes ];
        $isDuplicate = $this->ev->isDuplicate ? 'true' : 'false'; //VUT

        echo '<form id="DetailView" method="POST" name="DetailView">
                    <input type="hidden" name="module" value="AOS_Quotes" />
                    <input type="hidden" name="record" value="'. $this->ev->focus->id.'" />
                    <input type="hidden" name="isDuplicate" value="'.$isDuplicate.'">
                    <input type="hidden" name="offset" value="'.$this->ev->offset.'">
                    <input type="hidden" name="action" value="'.$this->action.'">
                    <input type="hidden" name="sugar_body_only">
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
        echo '<input hidden  name="current_user_id" value="'.$current_user->id .'" />';
        echo '<input hidden  name="current_user_name" value="'.$current_user->name .'" />';
    }

    public function populateQuoteTemplates()
    {
        global $app_list_strings;

        $sql = "SELECT id, name FROM aos_pdf_templates WHERE deleted='0' AND type='AOS_Quotes'";
        $res = $this->bean->db->query($sql);

        $app_list_strings['template_ddown_c_list'] = array();
        while ($row = $this->bean->db->fetchByAssoc($res)) {
            $app_list_strings['template_ddown_c_list'][$row['id']] = $row['name'];
        }
    }
    
    //thienpb
    function alterRelateLeadField(){
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT id, account_name FROM leads WHERE deleted='0' AND id='".$this->bean->leads_aos_quotes_1leads_ida."'";
        $res = $db->query($sql);
        if($res->num_rows > 0){
            $row = $db->fetchByAssoc($res);
            $this->bean->leads_aos_quotes_1_name = $row['account_name'];
        }
    }
}
