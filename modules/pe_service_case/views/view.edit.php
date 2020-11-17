<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class pe_service_caseViewEdit extends ViewEdit {
    public function __construct()
    {
        parent::__construct();
    }

    public function pe_service_caseViewEdit() {
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
        parent::display();
        $template = new Sugar_Smarty();
        echo $template->fetch('modules/pe_service_case/templates/popupMessage.tpl');
    }
}