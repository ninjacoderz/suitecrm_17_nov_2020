<?php

// if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point' );
// echo "dek mo";
$db = DBManagerFactory::getInstance();
$id = $_GET['parent_id'];
$module = $_GET['parent_module'];
if ($module == "AOS_Quotes") {
    $quote = new AOS_Quotes();
    $quote->retrieve($id);
    if ($quote->id != "") {
        $sql = "SELECT DISTINCT aos_quotes.id as quote_id, aos_quotes.name as quote_name, aos_quotes.number as quote_number, aos_quotes.deleted
                FROM aos_quotes 
                LEFT JOIN accounts ON accounts.id = aos_quotes.billing_account_id
                WHERE aos_quotes.billing_account_id = '$quote->billing_account_id' AND aos_quotes.deleted = 0
                ORDER BY aos_quotes.number ASC
                ";
        $ret = $db->query($sql);
        while($row = $ret->fetch_assoc()){
            if ($row['quote_id'] != '') { 
                $result[$row['quote_id']] = "Quote #".$row['quote_number']." ".$row['quote_name']; 
            }
        }
        echo json_encode($result);
    }
}

