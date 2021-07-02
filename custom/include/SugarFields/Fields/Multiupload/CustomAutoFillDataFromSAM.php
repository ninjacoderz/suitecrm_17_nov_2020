<?php
    global $current_user;
    $sg_order_number = trim($_GET['sg_order_number']);
    if($sg_order_number == '') die();

    $username = 'matthew.wright';
    $password = 'MW@pure733';
    $json_result = Get_Json_CRMSolargainByOrderNumber($username,$password,$sg_order_number);
    //change account paul
    if(!isset($json_result->ID)) {
        $username = 'paul.szuster@solargain.com.au';
        $password = 'WalkingElephant#256';
        $json_result = Get_Json_CRMSolargainByOrderNumber($username,$password,$sg_order_number);
    }
    //change account michael
    if(!isset($json_result->ID)) {
        $username = 'michael.golden@solargain.com.au';
        $password = 'michaelg@sg79';
        $json_result = Get_Json_CRMSolargainByOrderNumber($username,$password,$sg_order_number);
    }

    //logic for install date, due date
    if(isset($json_result->InstallDate)){
        $date = date_create($json_result->InstallDate);
        $installdate = date_format($date,"d/m/Y");
    }else {
        $installdate =$json_result->Quote->ProposedInstallDate->Date;
    }
     //logic for title invoice
    if(isset($json_result->Customer->TradingName) && $json_result->Customer->TradingName !== ''){
        $title_inv = 'Solargain_' .$json_result->Customer->TradingName .'_' .$json_result->Install->Address->Locality .'_Order #' .$sg_order_number;
    }else{
        $title_inv = 'Solargain_' .$json_result->Customer->Name .'_' .$json_result->Install->Address->Locality .'_Order #' .$sg_order_number;
    }
    //logic for installer
    if(isset($json_result->Installer)){
        $email_Installer = $json_result->Installer->EMail;
        // create or update contact
        if(isset($email_Installer) && $email_Installer !== '') {
            $array_Installer_contacts = get_contacts_id_by_email($email_Installer);
            $Installer_contact = create_contacts_installer( $json_result,$array_Installer_contacts[0],$email_Installer );
            
            $array_Installer_accounts = get_accounts_id_by_email($email_Installer);
            $Installer_account = create_accounts_installer( $json_result,$array_Installer_accounts[0],$email_Installer );
          
            $Installer_account->load_relationship('contacts');
            $Installer_account->contacts->add($Installer_contact);
            $Installer_account->save();

        }

    }
    //logic get quote number in suitecrm by quote number SAM
    $db = DBManagerFactory::getInstance();
    $SAM_Quote_Number = $json_result->Quote->ID;
    $query = "SELECT id , number FROM aos_quotes 
    INNER JOIN aos_quotes_cstm ON aos_quotes.id = aos_quotes_cstm.id_c
    WHERE aos_quotes.deleted = 0 AND (aos_quotes_cstm.solargain_quote_number_c ='$SAM_Quote_Number' OR aos_quotes_cstm.solargain_tesla_quote_number_c ='$SAM_Quote_Number') LIMIT 1";
    $result = $db->query($query);
    while (($row=$db->fetchByAssoc($result)) != null) {
        $rows = $row;
    }

    $info_inv_result = 
    array (
        'name' => $title_inv,
        //defaul: account ,contact  of Solargain PV Pty Ltd 
        'billing_account_id' => '61db330d-0aee-6661-8ac3-585c79c765a2', 
        'billing_account' => 'Solargain PV Pty Ltd',
        'billing_contact' => 'Solargain Accounts',
        'billing_contact_id' => '296a953e-c7d0-40b6-09d3-5ab9cb674a5c',
        'billing_address_street' => '10 Milly Court',
        'billing_address_city' => 'Malaga',
        'billing_address_state' => 'WA',
        'billing_address_postalcode' => '6090',
        // address install 
        'install_address_c' => $json_result->Install->Address->Street1,
        'install_address_city_c' => $json_result->Install->Address->Locality,
        'install_address_state_c' => $json_result->Install->Address->State,
        'install_address_postalcode_c' => $json_result->Install->Address->PostCode,
        // Assigned  default by user active
        'assigned_user_name' => $current_user->full_name,
        'assigned_user_id' => $current_user->id,
        //install date , due date
        'installation_date_c' =>  $installdate .' 12:00',
        'due_date' =>  $installdate,
        //total price
        'total_sam' => $json_result->Quote->SystemPrice,
        //id,number quote in suitecrm
        'id_quote' =>($rows['id']) ? $rows['id'] : '',
        'number_quote' =>($rows['number']) ? $rows['number'] : '',
        //  Account Installer
        'solar_installer_c' =>($Installer_account) ? $Installer_account->name : '',
        'account_id5_c' =>($Installer_account) ? $Installer_account->id : '',

        //  Contact Installer
        'solar_installer_contact_c' =>($Installer_contact) ? $Installer_contact->first_name .' '.$Installer_contact->last_name : '',
        'contact_id2_c' =>($Installer_contact) ? $Installer_contact->id : '',
    );

    echo json_encode($info_inv_result);

//get data json by order number
function Get_Json_CRMSolargainByOrderNumber($username,$password,$sg_order_number){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = "Pragma: no-cache";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
    $headers[] = "Accept: application/json, text/plain, */*";
    $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: no-cache";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    curl_close ($ch);
    $json_result = json_decode($result);
    return $json_result;
}

function get_accounts_id_by_email($address_email) {
    global $db;
    $array_id = [];
    $query = 
    "SELECT accounts.id as id 
    FROM accounts 
    INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = accounts.id
    INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE accounts.deleted = 0  AND email_addresses.email_address = '$address_email'";
    $result = $db->query($query);
    if($result->num_rows > 0) {
        while ($row = $db->fetchByAssoc($result)) {
            $array_id[] =$row['id'];
        }
    }
    return $array_id;
}

function get_contacts_id_by_email($address_email) {
    global $db;
    $array_id = [];
    $query = 
    "SELECT contacts.id as id 
    FROM contacts 
    INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = contacts.id
    INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id
    WHERE contacts.deleted = 0  AND email_addresses.email_address = '$address_email'";
    $result = $db->query($query);
    if($result->num_rows > 0) {
        while ($row = $db->fetchByAssoc($result)) {
            $array_id[] =$row['id'];
        }
    }
    return $array_id;
}

function create_contacts_installer($data, $contactID ='',$address_email){
    global $current_user;
    $contact = new Contact();
    $contact->retrieve($contactID);
    if($contact->id == ''){
        $FullName  = explode(",", htmlspecialchars_decode($data->Installer->Name));
        $contact->first_name = $FullName[0];
        $contact->last_name = $FullName[1];
        $contact->primary_address_street = $data->Installer->Address->Street1;
        $contact->primary_address_city = $data->Installer->Address->Locality;
        $contact->primary_address_state =  ($data->Installer->Address->State)? $data->Installer->Address->State : $data->Installer->Region;
        $contact->email1 = $address_email;
        $contact->assigned_user_name = $current_user->full_name;
        $contact->assigned_user_id = $current_user->id;
        $contact->save();
    }
    return  $contact;
}


function create_accounts_installer($data, $accountID ='',$address_email){
    global $current_user;
    $account = new Account();
    $account->retrieve($accountID);
    if($account->id == ''){
        $account->name = htmlspecialchars_decode($data->Installer->Name);
        $account->billing_address_street = $data->Installer->Address->Street1;
        $account->billing_address_city = $data->Installer->Address->Locality;
        $account->billing_address_state =($data->Installer->Address->State)? $data->Installer->Address->State : $data->Installer->Region;
        $account->assigned_user_name = $current_user->full_name;
        $account->assigned_user_id = $current_user->id;
        $account->email1 =  $address_email;
        $account->save();  
    }
    return  $account;
}