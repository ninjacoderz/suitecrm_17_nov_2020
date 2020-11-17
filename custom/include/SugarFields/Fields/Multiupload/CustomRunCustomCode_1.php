<?php
    // $db = DBManagerFactory::getInstance();
    // $sql = "SELECT * FROM aos_quotes  ";
    // $ret = $db->query($sql);

    
    // while($row = $db->fetchByAssoc($ret)){
    //     $email = "";
    //     if( $row['billing_account_id'] != "" ){
    //         $account = new Account();
    //         $account->retrieve($row['billing_account_id']);
    //         $email = $account->email1;
    //         if( $email != ""){
    //             $sql1 = "UPDATE aos_quotes_cstm SET email1_c = '".$email."'  WHERE id_c  = '".$row['id']."'";
    //             $ret1= $db->query($sql1);
    //             $row1 = $db->fetchByAssoc($ret1);
    //         }
    //     }
    //     if( $row['billing_contact_id'] != "" &&  $email == "" ){
    //         $contact = new Contact();
    //         $contact->retrieve($row['billing_contact_id']);
    //         $email = $contact->email1;
    //         if( $email != ""){
    //             $sql1 = "UPDATE aos_quotes_cstm SET email1_c = '".$email."'  WHERE id_c  = '".$row['id']."'";
    //             $ret1= $db->query($sql1);
    //             $row1 = $db->fetchByAssoc($ret1);
                
    //         }else {
    //             $sql_q = "SELECT leads_aos_quotes_1leads_ida FROM leads_aos_quotes_1_c WHERE  leads_aos_quotes_1aos_quotes_idb ='".$row['id']."'";
    //             $ret_q = $db->query($sql_q);
    //             $row_q = $db->fetchByAssoc($ret_q);
    //             $lead =  new Lead();
    //             $lead->retrieve($row_q['leads_aos_quotes_1leads_ida']);
    //             $email = $lead->email1;
    //             if( $email != ""){
    //                 $sql1 = "UPDATE aos_quotes_cstm SET email1_c = '".$email."'  WHERE id_c  = '".$row['id']."'";
    //                 $ret1= $db->query($sql1);
    //                 $row1 = $db->fetchByAssoc($ret1);
    //             }
    //         }
    //     }else if( $email == ""){
    //         $sql_q = "SELECT leads_aos_quotes_1leads_ida FROM leads_aos_quotes_1_c WHERE  leads_aos_quotes_1aos_quotes_idb ='".$row['id']."'";
    //         $ret_q = $db->query($sql_q);
    //         $row_q = $db->fetchByAssoc($ret_q);
    //         $lead =  new Lead();
    //         $lead->retrieve($row_q['leads_aos_quotes_1leads_ida']);
    //         $email = $lead->email1;
    //         if( $email != ""){
    //             $sql1 = "UPDATE aos_quotes_cstm SET email1_c = '".$email."'  WHERE id_c  = '".$row['id']."'";
    //             $ret1= $db->query($sql1);
    //             $row1 = $db->fetchByAssoc($ret1);
    //         }
    //     }  
    // }
    // echo "Success!";
?>