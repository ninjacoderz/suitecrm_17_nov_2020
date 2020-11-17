<?php

    class UpdateContacts 
    {
        function after_save_method($bean) {

            $id_account = $bean->account_id;
            $id_contact = $bean->id;
            $bean_account = new Account();
            $bean_account->retrieve($id_account);
            if($bean_account->id != '') {
                $bean_account->phone_office = $bean->phone_work; 
                $bean_account->mobile_phone_c = $bean->phone_mobile; 
                $bean_account->email1 = $bean->email1; 
                $bean_account->save();
            }
        }
    }
    
?>