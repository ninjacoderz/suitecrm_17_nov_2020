<?php

// header("cache-control: must-revalidate");
// header('Vary: Accept-Encoding');
header('Access-Control-Allow-Origin: *');

$email =  $_GET['email'];

$db = DBManagerFactory::getInstance();

$sql = "SELECT ear.bean_id AS id , bean_module AS module FROM email_addresses ea
        RIGHT JOIN email_addr_bean_rel ear ON ear.email_address_id = ea.id
        WHERE 1=1 
        AND ea.deleted  != 1 AND ear.deleted != 1
        AND LOWER(replace(ea.email_address, '.', '')) LIKE LOWER(replace('".$email."', '.', ''))";

$ret = $db->query($sql);

while ($row = $db->fetchByAssoc($ret)) {
    $lookup_result[] = $row;
} 

$crm_links = "";

if(count($lookup_result)){

    
    $db = DBManagerFactory::getInstance();
    $quotes = array();
    $invoices = array();
    foreach($lookup_result as $res){
        $crm_links .= '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?module='.$res['module'].'&action=EditView&record='.$res['id'] .'">'.$res['module']."</a>";
        if($res['module'] == "Leads"){
            $lead = new Lead();
            $lead->retrieve($res['id']);

            if(isset($lead->email1) && $lead->email1!= ""){
                //<a target="_blank" href="https://mail.google.com/#search/fraserwikner%40hotmail.com">GM Search</a>
                $crm_links .= '<a class="link-button buttons" href="https://mail.google.com/#search/'.$lead->email1.'"> GM Search</a>';
                $crm_links .= '<a class="link-button buttons" href="http://message.pure-electric.com.au/#'.  preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_work)).'">SMS Link</a>';
            }
            $bean_quote = new AOS_Quotes();
            $bean_quote->retrieve( $lead->create_solar_quote_num_c);
            if($bean_quote->id != '') {
                $number_lead = $bean_quote->solargain_lead_number_c;
                
                if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
                    $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">SG '.$bean_quote->solargain_quote_number_c.'</a>';
                }else{
                    if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
                        $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/lead/edit/'.$bean_quote->solargain_lead_number_c.'">SG '.$bean_quote->solargain_lead_number_c.'</a>';
                    }
                }
                if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
                    $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">SG '.$bean_quote->solargain_tesla_quote_number_c.'</a>';
                }
            } 


            //quote Suitecrm solar tesla 
            $bean_quote = new AOS_Quotes();
            $bean_quote->retrieve( $lead->create_tesla_quote_num_c);
            if($bean_quote->id != '') {
                $number_lead = $bean_quote->solargain_lead_number_c;
                
                if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
                    $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">SG '.$bean_quote->solargain_quote_number_c.'</a>';
                }else{
                    if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
                        $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/lead/edit/'.$bean_quote->solargain_lead_number_c.'">SG '.$bean_quote->solargain_lead_number_c.'</a>';
                    }
                }
                if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
                    $crm_links .= '<a class="link-button buttons" href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">SG '.$bean_quote->solargain_tesla_quote_number_c.'</a>';
                }
            } 

            if(isset($lead->phone_work) && $lead->phone_work != ""){
                $crm_links .= '<a class="link-button buttons" href="http://message.pure-electric.com.au/#'.preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_work)).'">P.Work('.preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_work)).')</a>';
            } else if(isset($lead->phone_mobile) && $lead->phone_mobile != ""){
                $crm_links .= '<a class="link-button buttons" href="http://message.pure-electric.com.au/#'.preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_mobile)).'">P.Mobile('.preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_mobile)).')</a>';
            }           
            /*if(isset($lead->primary_address_postalcode) && $lead->primary_address_postalcode != ""){
                    $crm_links .= " ".$lead->primary_address_street. " ".
                                            $lead->primary_address_city. " ".
                                            $lead->primary_address_state. " ".
                                            $lead->primary_address_postalcode .PHP_EOL;
            }*/

            if(isset($lead->id) && $lead->id != ""){
                    $crm_links .= '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateAcceptanceLink&lead_id='.$lead->id.'">Acceptance Email</a>';
                    //thienpb code - add link forward acceptance email to sg sam Forward Acceptance Email
                    $crm_links .= '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateForwardAcceptanceLink&lead_id='.$lead->id.'">Forward Acceptance Email</a>';
            }
            //primary_address_city
        }
        if($res['module'] == 'Accounts'){            
            $sql = "SELECT id FROM aos_quotes WHERE billing_account_id='".$res['id']."'";
            $ret = $db->query($sql);
            if($ret->num_rows >0){
                while($row = $db->fetchByAssoc($ret)){
                    if(!in_array($row,$quotes)){
                        $quotes[] = $row;
                    }
                }
            }
            $sql = "SELECT id FROM aos_invoices WHERE billing_account_id='".$res['id']."'";
            $ret = $db->query($sql);
            if($ret->num_rows >0){
                while($row = $db->fetchByAssoc($ret)){
                    if(!in_array($row,$invoices)){
                        $invoices[] = $row;
                    }
                }
            }
        }
        if($res['module'] == "Contacts"){
            $sql = "SELECT id FROM aos_quotes WHERE billing_contact_id = '".$res['id']."'";
            $ret = $db->query($sql);
            if($ret->num_rows >0){
                while($row = $db->fetchByAssoc($ret)){
                    if(!in_array($row,$quotes)){
                        $quotes[] = $row;
                    }
                }
            }
            $sql = "SELECT id FROM aos_invoices WHERE billing_contact_id = '".$res['id']."'";
            $ret = $db->query($sql);
            if($ret->num_rows >0){
                while($row = $db->fetchByAssoc($ret)){
                    if(!in_array($row,$invoices)){
                        $invoices[] = $row;
                    }
                }
            }
        }
        if($res['module'] == "PO_purchase_order"){
            $sql = "SELECT id FROM aos_invoices WHERE billing_contact_id = '".$res['id']."'";
            $ret = $db->query($sql);
            if($ret->num_rows >0){
                while($row = $db->fetchByAssoc($ret)){
                    if(!in_array($row,$invoices)){
                        $PO_invoices[] = $row;
                    }
                }
            }
        }
    }
    if(count($PO_invoices) >0){
        $group_name ='';
        foreach ($PO_invoices as $res_po_inv){
            $invoice_po = new AOS_Invoices();
            $invoice_po->retrieve($res_po_inv['id']);
            if(isset($invoice_po->id) && $invoice_po->id != "")
                $crm_links .= 
                    '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$invoice_po->id.'">Invoices</a>';
                $crm_links .= 
                    '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?module=PO_purchase_order&action=EditView&record='.$invoice_po->plumber_po_c.'">Link PO</a>';
        }
    }
    if(count($quotes) >0){
        $group_name ='';
        foreach ($quotes as $res_qt){
            $quote = new AOS_Quotes();
            $quote->retrieve($res_qt['id']);
            $sql = "SELECT name FROM aos_line_item_groups WHERE parent_id = '".$quote->id."' AND parent_type = 'AOS_Quotes'" ;
            $ret = $db->query($sql);
            $row = $db->fetchByAssoc($ret);
            if(strpos(strtolower($row['name']),'daikin') !== false){
                $group_name = ' DAIKIN';
            }else if(strpos(strtolower($row['name']),'sanden') !== false){
                $group_name = ' SANDEN';
            }else if(strpos(strtolower( $quote->quote_type_c),'solar') !== false){
                $group_name =' SOLAR';
            }

            $crm_links .= /*'PEQ '.$quote->number.$group_name.":*/ 
                '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id.'">PEQ '.$quote->number.$group_name.'</a>';

        }
    }
    if(count($invoices) >0){
        $group_name ='';
        foreach ($invoices as $res_inv){
            $invoice_new = new AOS_Invoices();
            $invoice_new->retrieve($res_inv['id']);
            if(isset($invoice_new->id) && $invoice_new->id != "")
            $sql = "SELECT name FROM aos_line_item_groups WHERE parent_id = '".$invoice_new->id."' AND parent_type = 'AOS_Invoices'" ;
            $ret = $db->query($sql);
            $row = $db->fetchByAssoc($ret);
            if(strpos(strtolower($row['name']),'daikin') !== false){
                $group_name = ' DAIKIN';
            }else if(strpos(strtolower($row['name']),'sanden') !== false){
                $group_name = ' SANDEN';
            }else if(strpos(strtolower( $quote->quote_type_c),'solar') !== false){
                $group_name =' SOLAR';
            }

            $crm_links .= /*'PEINV '.$invoice_new->number.": */
                '<a class="link-button buttons" href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$invoice_new->id.'">PEINV '.$invoice_new->number.$group_name.'</a>';
        }
    }
}

echo $crm_links;
die();