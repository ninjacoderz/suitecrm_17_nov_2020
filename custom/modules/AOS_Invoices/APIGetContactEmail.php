<?php
     $contact = new Contact;
     $contact->retrieve($_REQUEST['contact_id']);
     echo $contact->email1;
?>