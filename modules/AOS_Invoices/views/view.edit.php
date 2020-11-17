<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


class AOS_InvoicesViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_InvoicesViewEdit()
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
        $this->populateInvoiceTemplates();
        parent::display();

        $template = new Sugar_Smarty();
        echo $template->fetch('modules/AOS_Invoices/templates/popupTemplatePlumbingNotes.tpl');
        
        $template_electrical = new Sugar_Smarty();
        echo $template_electrical->fetch('modules/AOS_Invoices/templates/popupTemplateElectricalNotes.tpl');

        $template_pcoc = new Sugar_Smarty();
        echo $template_pcoc->fetch('modules/AOS_Invoices/templates/popupTemplatePCOCNotes.tpl');

        $template_ces = new Sugar_Smarty();
        echo $template_ces->fetch('modules/AOS_Invoices/templates/popupTemplateCESNotes.tpl');
        
        // dung code -- render subpanel internal notes
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
        $subpanel_internal_notes = $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['aos_invoices_pe_internal_note_1'];
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup'] = ['aos_invoices_pe_internal_note_1' => $subpanel_internal_notes ];
        echo '<form id="DetailView" method="POST" name="DetailView">
                    <input hidden name="module" value="AOS_Invoices" />
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
        // if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        //     // logic new -- case duplicate in Invoice . Not render items
        //     // echo '<script language="javascript">markGroupDeleted(0);</script>';
        // }
    }

    public function populateInvoiceTemplates()
    {
        global $app_list_strings;

        $sql = "SELECT id, name FROM aos_pdf_templates WHERE deleted='0' AND type='AOS_Invoices'";
        $res = $this->bean->db->query($sql);

        $app_list_strings['template_ddown_c_list'] = array();
        while ($row = $this->bean->db->fetchByAssoc($res)) {
            $app_list_strings['template_ddown_c_list'][$row['id']] = $row['name'];
        }
    }
}
