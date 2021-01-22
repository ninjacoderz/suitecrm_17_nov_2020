<?php
    /**
     * User: thienpb
     * Date Updated: 5/11/2020
     **/

    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class AutoXeroPO {
        function after_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            $method = '';
            if($old_fields == false || empty($bean->xero_po_id_c)){
                $method = 'create';
            }else{
                $method = 'update';
            }
            $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
            $fields = array();
            $fields['user_name'] = 'admin';
            $fields['username_password'] = 'pureandtrue2020*';
            $fields['module'] = 'Users';
            $fields['action'] = 'Authenticate';
            $url = 'https://suitecrm.pure-electric.com.au/index.php';
            //$url = 'http://loc.suitecrm.com/index.php';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
            curl_setopt($curl, CURLOPT_POST, 1);//count($fields)

            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            //curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
            $result = curl_exec($curl);

            curl_setopt($curl, CURLOPT_URL, 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method='.$method.'&record='.$bean->id);
            //curl_setopt($curl, CURLOPT_URL, 'http://loc.suitecrm.com/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method='.$method.'&record='.$bean->id);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            //curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
            $result = curl_exec($curl);
            curl_close($curl);
        }
    }
    class CreateInternalNotesPO {
        /**
         * VUT- after save - when change PO's status
         */
        function after_save_createdInternalNotesChangeStatus ($bean, $event, $arguments) {
            //get  PO's status dropdown
            global $app_list_strings;
            $PO_status = $app_list_strings['po_status_dom'];
            // $PO_status = translate('po_status_dom','', $bean->status_c); //other case-don't use
            $old_fields = $bean->fetched_row;
            //format Date modified
            $format = 'Y-m-d H:i:s';
            $date = DateTime::createFromFormat($format, $bean->date_modified);
            // $test = DateTime::createFromFormat($format, "2020-09-28 17:51:57");
            $date_note = $date->format("d/m/Y h:ia");
            //case 1- new PO
            if ($old_fields == false) { 
                //Check Sql
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT pe_internal_note.id FROM pe_internal_note 
                        LEFT JOIN po_purchase_order_pe_internal_note_1_c ON po_purchase_order_pe_internal_note_1_c.po_purchase_order_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id 
                        LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                        WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated' 
                        AND po_purchase_order_pe_internal_note_1_c.po_purchase_order_pe_internal_note_1po_purchase_order_ida ='$bean->id'  
                        AND pe_internal_note.deleted = 0
                        ORDER BY `pe_internal_note`.`date_modified` DESC";    
                $ret = $db->query($sql);
                if ($ret->num_rows == 0) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    if ($PO_status[$bean->status_c] == '') {
                        $bean->status_c = 'Draft';
                        // $PO_status[$bean->status_c] = 'Draft';
                    }
                    $decription_internal_notes = $PO_status[$bean->status_c].' '.$date_note;
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('po_purchase_order_pe_internal_note_1');
                    $bean_intenal_notes->po_purchase_order_pe_internal_note_1->add($bean->id);
                }
            } else { //case 2 - update PO
                if ($old_fields['status_c'] != $bean->status_c) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = $PO_status[$bean->status_c].' '.$date_note;
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('po_purchase_order_pe_internal_note_1');
                    $bean_intenal_notes->po_purchase_order_pe_internal_note_1->add($bean->id);
                    }
            }
        }
    }

?>