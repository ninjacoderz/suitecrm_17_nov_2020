<?php

$record_id = isset($_GET['record_id']) ? $_GET['record_id']: "";
$request = isset($_GET['request']) ? $_GET['request']: "";

// /$bean = BeanFactory::getBean("Accounts", $record_id);
//request only in the quote  
if($request == 'get_info_contact'){
    $record_id_account = isset($_GET['record_id_account']) ? $_GET['record_id_account']: ""; 
    $contact = new Contact();
    $contact->retrieve($record_id);
    if($contact->id != ''){
        $return = array();
        $return['name'] = html_entity_decode($contact->first_name .' ' .$contact->last_name, ENT_QUOTES) ;
        $return['record_id'] = html_entity_decode($contact->id, ENT_QUOTES);
        $return['email'] = html_entity_decode($contact->email1, ENT_QUOTES);
        $return['mobile'] = html_entity_decode($contact->phone_mobile, ENT_QUOTES);
        $return['mobile_home'] = html_entity_decode($contact->phone_home, ENT_QUOTES);
        $return['mobile_work'] = html_entity_decode($contact->phone_work, ENT_QUOTES);
        $return['first_name'] = html_entity_decode($contact->first_name, ENT_QUOTES);
        $return['last_name'] = html_entity_decode($contact->last_name, ENT_QUOTES);
        $return['street'] = html_entity_decode($contact->primary_address_street, ENT_QUOTES);
        $return['city'] = html_entity_decode($contact->primary_address_city, ENT_QUOTES);
        $return['state'] = html_entity_decode($contact->primary_address_state, ENT_QUOTES);
        $return['postalcode'] = html_entity_decode($contact->primary_address_postalcode, ENT_QUOTES);
        echo json_encode($return);
        die();
    }
}
//request only in the invoice for plumber and electrican  
if($request == 'custom_display_link_contact_plum_elec_invoice'){
    $plumber_id = isset($_GET['plumber_id']) ? $_GET['plumber_id']: ""; 
    $electrician_id = isset($_GET['electrician_id']) ? $_GET['electrician_id']: "";  
    $return = array();
    $plumber_contact = '';
    $electrician_contact = '';

    $plumber_account = new Account();
    $plumber_account->retrieve($plumber_id);
    if($plumber_account->id != ''){
        $plumber_contacts = $plumber_account->get_linked_beans('contacts','Contact');
        if(count($plumber_contacts)> 0){
            for($i=0;$i < count($plumber_contacts);$i++){
                if($plumber_contacts[$i]->id == $plumber_account->primary_contact_c){
                    $plumber_contact = $plumber_contacts[$i];
                    break;
                }elseif($i == count($plumber_contacts) -1){
                    $plumber_contact = $plumber_contacts[count($plumber_contacts) -1];
                }
            }
        }
    }

    $electrician_account = new Account();
    $electrician_account->retrieve($electrician_id);
    if($electrician_account->id != ''){
        $electrician_contacts = $electrician_account->get_linked_beans('contacts','Contact');
        if(count($electrician_contacts)> 0){
            for($i=0;$i < count($electrician_contacts);$i++){
                if($electrician_contacts[$i]->id == $electrician_account->primary_contact_c){
                    $electrician_contact = $electrician_contacts[$i];
                    break;
                }elseif($i == count($electrician_contacts) -1){
                    $electrician_contact = $electrician_contacts[count($electrician_contacts) -1];
                }
            }
        }
  
    }
    $return['plum_contact_id'] = html_entity_decode($plumber_contact->id, ENT_QUOTES);
    $return['elec_contact_id'] = html_entity_decode($electrician_contact->id, ENT_QUOTES);
    echo json_encode($return);
    die();
}

//VUT-S-Request only in the quotes
if ($request == "custom_display_link_contact_plum_elec_quote") {
    $sanden_electrician_id = $_GET['sanden_electrician_id'];
    $sanden_installer_id = $_GET['sanden_installer_id'];
    $daikin_installer_id = $_GET['daikin_installer_id'];
    $return = array();

    /**Sanden Electrician */
    $sanden_electrician_contact = '';
    $sanden_electrician_account = new Account();
    $sanden_electrician_account->retrieve($sanden_electrician_id);
    if ($sanden_electrician_account->id != '') {
        $sanden_electrician_contact = $sanden_electrician_account->get_linked_beans('contacts','Contact');
        if(count($sanden_electrician_contact)> 0){
            for($i=0;$i < count($sanden_electrician_contact);$i++){
                if($sanden_electrician_contact[$i]->id == $sanden_electrician_account->primary_contact_c){
                    $sanden_electrician_contact = $sanden_electrician_contact[$i];
                    break;
                }elseif($i == count($sanden_electrician_contact) -1){
                    $sanden_electrician_contact = $sanden_electrician_contact[count($sanden_electrician_contact) -1];
                }
            }
        }
    }

    /**Sanden Installer */
    $sanden_installer_contact = '';
    $sanden_installer_account = new Account();
    $sanden_installer_account->retrieve($sanden_installer_id);
    if ($sanden_installer_account->id != '') {
        $sanden_installer_contact = $sanden_installer_account->get_linked_beans('contacts','Contact');
        if(count($sanden_installer_contact)> 0){
            for($i=0;$i < count($sanden_installer_contact);$i++){
                if($sanden_installer_contact[$i]->id == $sanden_installer_account->primary_contact_c){
                    $sanden_installer_contact = $sanden_installer_contact[$i];
                    break;
                }elseif($i == count($sanden_installer_contact) -1){
                    $sanden_installer_contact = $sanden_installer_contact[count($sanden_installer_contact) -1];
                }
            }
        }
    }

    /**Daikin Installer */
    $daikin_installer_contact = '';
    $daikin_installer_account = new Account();
    $daikin_installer_account->retrieve($daikin_installer_id);
    if ($daikin_installer_account->id != '') {
        $daikin_installer_contact = $daikin_installer_account->get_linked_beans('contacts','Contact');
        if(count($daikin_installer_contact)> 0){
            for($i=0;$i < count($daikin_installer_contact);$i++){
                if($daikin_installer_contact[$i]->id == $daikin_installer_account->primary_contact_c){
                    $daikin_installer_contact = $daikin_installer_contact[$i];
                    break;
                }elseif($i == count($daikin_installer_contact) -1){
                    $daikin_installer_contact = $daikin_installer_contact[count($daikin_installer_contact) -1];
                }
            }
        }
    }

    $return['sanden_electrician_contact'] = html_entity_decode($sanden_electrician_contact->id, ENT_QUOTES);
    $return['sanden_installer_contact'] = html_entity_decode($sanden_installer_contact->id, ENT_QUOTES);
    $return['daikin_installer_contact'] = html_entity_decode($daikin_installer_contact->id, ENT_QUOTES);

    echo json_encode($return);
    die();
}
//VUT-E-Request only in the quotes

$bean = new Account();
$bean->retrieve($record_id);
$contacts = $bean->get_linked_beans('contacts','Contact');
if(count($contacts)> 0){
    for($i=0;$i < count($contacts);$i++){
        if($contacts[$i]->id == $bean->primary_contact_c){
            $contact = $contacts[$i];
            break;
        }elseif($i == count($contacts) -1){
            $contact = $contacts[count($contacts) -1];
        }
    }
}else{
    $return = array();
    $return['name'] = $contact->name;
    $return['record_id'] = $contact->id;
    $return['email'] = $bean->email1;
    $return['mobile'] = $bean->mobile_phone_c;
    $return['mobile_home'] = $contact->phone_mobile;
    $return['mobile_work'] = $contact->phone_work;
    $return['first_name'] = $contact->first_name;
    $return['last_name'] = $contact->last_name;
    echo json_encode($return);
    die();
}

$return = array();
$return['name'] = $contact->name;
$return['record_id'] = $contact->id;
$return['email'] = $bean->email1;
$return['mobile'] = $bean->mobile_phone_c;
$return['mobile_home'] = $contact->phone_mobile;
$return['mobile_work'] = $contact->phone_work;
$return['first_name'] = $contact->first_name;
$return['last_name'] = $contact->last_name;
echo json_encode($return);
die();

?>