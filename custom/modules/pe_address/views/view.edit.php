<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class pe_addressViewEdit extends ViewEdit
{
	function display()
	{
        if ($_REQUEST["return_module"] == 'Accounts' && $_REQUEST["return_id"] != '' && isset($_REQUEST['return_relationship'])) {
            $account = new Account();
            $account->retrieve($_REQUEST["return_id"]);
            if ($account->id) {
                $this->ev->focus->billing_account_id = $account->id;
                $this->ev->focus->billing_account = $account->name;
            }
        }
		parent::display();
    }
    
}