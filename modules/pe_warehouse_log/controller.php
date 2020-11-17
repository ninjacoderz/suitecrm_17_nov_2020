<?php 
require_once('include/MVC/Controller/SugarController.php');

class pe_warehouse_logController extends SugarController {
	function action_editview() {
		global $mod_string;

		$this->view = 'edit';

		/*if (isset($_REQUEST['pe_warehouse_id'])) {
			$query = "SELECT * FROM pe_warehouse WHERE id = '{$_REQUEST['pe_warehouse_id']}'";
			$result = $this->bean->db->query($query, true);
			$row = $this->bean->db->fetchByAssoc($result);
			$this->bean->billing_account_id = $row['billing_account_id'];

			$query = "SELECT * FROM accounts WHERE id = '{$row['billing_account_id']}'";
			$result = $this->bean->db->query($query, true);
			$row = $this->bean->db->fetchByAssoc($result);
			$this->bean->billing_account = $row['name'];
		}*/
		
        	/*if (isset($_REQUEST['aos_quotes_id'])) {
            		$query = "SELECT * FROM aos_quotes WHERE id = '{$_REQUEST['aos_quotes_id']}'";
            		$result = $this->bean->db->query($query, true);
            		$row = $this->bean->db->fetchByAssoc($result);
            		$this->bean->name = $row['name'];
            
			if (isset($row['billing_account_id'])) {
			$_REQUEST['account_id'] = $row['billing_account_id'];
        		}
        		
        		if (isset($row['billing_contact_id'])) {
        		$_REQUEST['contact_id'] = $row['billing_contact_id'];
        		}
        	}


		if (isset($_REQUEST['account_id'])) {
            		$query = "SELECT * FROM accounts WHERE id = '{$_REQUEST['account_id']}'";
			$result = $this->bean->db->query($query, true);
			$row = $this->bean->db->fetchByAssoc($result);
			$this->bean->billing_account_id = $row['id'];
			$this->bean->billing_account = $row['name'];
			$this->bean->billing_address_street = $row['billing_address_street'];
			$this->bean->billing_address_city = $row['billing_address_city'];
			$this->bean->billing_address_state = $row['billing_address_state'];
			$this->bean->billing_address_postalcode = $row['billing_address_postalcode'];
			$this->bean->billing_address_country = $row['billing_address_country'];
			$this->bean->shipping_address_street = $row['shipping_address_street'];
			$this->bean->shipping_address_city = $row['shipping_address_city'];
			$this->bean->shipping_address_state = $row['shipping_address_state'];
			$this->bean->shipping_address_postalcode = $row['shipping_address_postalcode'];
			$this->bean->shipping_address_country = $row['shipping_address_country'];
		}	
		
		if (isset($_REQUEST['contact_id'])) {
            		$query = "SELECT id,first_name,last_name FROM contacts WHERE id = '{$_REQUEST['contact_id']}'";
			$result = $this->bean->db->query($query, true);
			$row = $this->bean->db->fetchByAssoc($result);
			$this->bean->billing_contact_id = $row['id'];
			$this->bean->billing_contact = $row['first_name'].' '.$row['last_name'];
		}
		*/
        	
    }

}
