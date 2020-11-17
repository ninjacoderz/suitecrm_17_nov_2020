<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class Auto_Update_Xero_Invoice {
    function after_save_method($bean, $event, $arguments)
    {
       if (!isset($_REQUEST['custom_request_auto_update_xero_invoice']) && $_REQUEST['action'] == 'Save') {
           if($bean->xero_stc_rebate_invoice_c != '' || $bean->xero_invoice_c != '' || $bean->xero_invoice_c != '') {
            $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
            $fields = array();
            $fields['user_name'] = 'admin';
            $fields['username_password'] = 'pureandtrue2020*';
            $fields['module'] = 'Users';
            $fields['action'] = 'Authenticate';
            $url = 'https://suitecrm.pure-electric.com.au/index.php';
            $url .= '?entryPoint=customUpdateXeroInvoice&custom_request_auto_update_xero_invoice=true&invoice=1&method=post';
            $url .= '&record=' .$bean->id .'&invoice_type=' . $bean->invoice_type_c ;
            $url .=  '&xero_payment_date=yes&xero_invoice=' . $bean->xero_invoice_c ;
            $url .=  '&xero_stc_rebate_invoice=' . $bean->xero_stc_rebate_invoice_c ;
            $url .=  '&xero_veec_rebate_invoice=' . $bean->xero_veec_rebate_invoice ;
            $curl = curl_init();
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
            $result = curl_exec($curl); 
           }

       }
    }
}