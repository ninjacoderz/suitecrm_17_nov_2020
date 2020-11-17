<?php

    class UpdateAccounts 
    {
        function after_save_method($bean) {

            $id_account = $bean->account_id;
            $id_contact = $bean->id;
            $bean_contact = new Contact();
            $bean_contact->retrieve($id_contact);
            if( $bean_contact->id != '' && $bean_contact->account_id == $id_account ){
                $bean_contact->phone_work = $bean->phone_office; 
                $bean_contact->phone_mobile = $bean->mobile_phone_c; 
                $bean_contact->email1 = $bean->email1;
                $bean_contact->save();
            }
        }
    }

?>