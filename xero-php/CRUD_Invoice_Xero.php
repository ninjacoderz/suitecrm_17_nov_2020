<?php
    include 'xeroConfig.php';
    //Param request
    $method =  $_REQUEST['method'];
    $record =  $_REQUEST['record'];
    $from_action = $_REQUEST['from_action'];
   
        $result_data = array(
            'inv_xero_id' => '',
            'inv_xero_stc' => '',
            'inv_xero_veec' => '',
            'inv_xero_shw' => '',
            'msg'=>''
        );
        $InvoiceCRM = new AOS_Invoices();
        $InvoiceCRM->retrieve($record);
        try {
            if($InvoiceCRM->id != "") {
                
                $API_Custom_Xero = new API_Custom_Xero($xero);
                // get information for xero 
                    $invoice_type = $InvoiceCRM->invoice_type_c ;
                    switch ($invoice_type) {
                        case 'Default':
                            $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
                            break;
                        case 'Sanden':
                            $templateID = "14066998-993a-e4e0-4d8e-58b79279b475";
                            break;    
                        case 'Daikin':
                            $templateID = "83a77470-c8b1-a174-3132-58df1804c777";
                            break;
                        case 'Solar':
                            $templateID = "13e05dc5-5d61-9898-6a07-5918de5ff9e4";
                            break;            
                        default:
                            $templateID = "91964331-fd45-e2d8-3f1b-57bbe4371f9c";
                            break;
                    }
        
                    // Logic with invoice title
                    if(strpos($InvoiceCRM->name, "Solargain") === false && strpos($InvoiceCRM->name, "Sanden Pure Electric Warranty") === false){
    
                    } else {
                        $title_explode = explode("_", $InvoiceCRM->name);
                        if(count($title_explode) > 0 && ( $title_explode[0] == "Solargain"|| $title_explode[0] == "Sanden Pure Electric Warranty")){
                            $account_name = $title_explode[1];
                        }
                    }
    
                    // Account Suitecrm
                    $bean_account = new Account();
                    $bean_account->retrieve($InvoiceCRM->billing_account_id);
                    // logic get phone customer 
                    $phone_numbers = explode(" ", $bean_account->phone_office);
                    $country_code = "";
                    $area_code = "";
                    $phone_number_customer =  $bean_account->mobile_phone_c;
                    if(count($phone_numbers) >= 3){
                        $country_code = $phone_numbers[0];
                        $area_code = $phone_numbers[1];
                        $phone_number_customer = $phone_numbers[2];
                    }
                    $phone_customer = $API_Custom_Xero->Create_Phone($phone_number_customer);
                    $address_customer = $API_Custom_Xero->Create_Address($bean_account->billing_address_street,$bean_account->billing_address_city,$bean_account->billing_address_state,$bean_account->billing_address_postalcode);
                    
                    $name_customer = explode(" ", $bean_account->name);
                    $first_name = "";
                    $last_name = "";
                    if(count($name_customer) > 1){
                        $first_name = end($name_customer);
                        $last_name = str_replace($first_name, "", $bean_account->name);
            
                    }
                    $info_contact_xero = array (
                        'number' => $bean_account->number,
                        'name' => $bean_account->name,
                        'first_name' => $first_name,
                        'last_name' =>  $last_name,
                        'email' => $bean_account->email1,
                        'phone' => $phone_customer,
                        'address' => $address_customer,
                    );
    
                    //get or create contact on xero
                    $Contact_xero = null;
                    // only find by email address
                    // if($bean_account->name != '') {
                    //     $Contact_xero = $API_Custom_Xero->Get_Contact_By_Name($bean_account->name);
                    // }

                    if($Contact_xero == null && $bean_account->email1 != ''){
                        $Contact_xero = $API_Custom_Xero->Get_Contact_By_Email($bean_account->email1);
                    }
                    
                    if($Contact_xero != null){
                        $Contact_xero = $API_Custom_Xero->Update_Contact($info_contact_xero,$Contact_xero);
                    }else{
                        $Contact_xero = $API_Custom_Xero->Create_Contact($info_contact_xero);
                    }

    
                    //due date and date origin invoice , installation date  , payment expected date     
                    $dateInfos = explode("/",$InvoiceCRM->invoice_date);
                    $inv_date_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
                    $timestamp_inv_date = date("Y-m-d", strtotime($inv_date_str));
                    $date_invoice   = new DateTime($timestamp_inv_date); 
    
                    $dateInfos = explode("/",$InvoiceCRM->due_date);
                    $inv_due_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
                    $timestamp_inv_due = date("Y-m-d", strtotime($inv_due_str));
                    $due_date   = new DateTime($timestamp_inv_due); 
                    if(!$InvoiceCRM->due_date){
                        $due_date = new DateTime();
                        $due_date->setTimestamp(time()+ 24*60*60*7);
                        $inv_due_str =date_format($due_date,'Y-m-d')."T00:00:00";
                    }
                    //information for origin invoice
                    $info_invoice_xero  =array(
                        'attachment_link' => '',
                        'invoice_number' => $InvoiceCRM->number,
                        'invoice_name'=> $InvoiceCRM->name,
                        'date' => $date_invoice ,
                        'due_date' => $due_date,
                        'LineItems' => array(),
                        'contact' => $Contact_xero,
                        'ExpectedPaymentDate' => '' , 
                        'History_payment_expected_date' => '' ,         
                     );  
                     downloadPDFFile($templateID ,$InvoiceCRM->id,'AOS_Invoices');
                     $attachmentFile =  dirname(__FILE__)."/files/invoice-". $InvoiceCRM->id.".pdf";
                     $info_invoice_xero['attachment_link'] =  $attachmentFile;
                   
                    if($InvoiceCRM->installation_date_c != '') {
                        $dateInfos = explode(" ",$InvoiceCRM->installation_date_c);
                        $dateInfos = explode("/",$dateInfos[0]);
                        $inv_install_date_str = "$dateInfos[2]-$dateInfos[0]-$dateInfos[1]T00:00:00";
                        $timestamp_inv_installdate = date("Y-m-d", strtotime($inv_install_date_str));
                        $installation_date   = new DateTime($timestamp_inv_installdate); 
                        $payment_expected_date =  date('d M Y', strtotime($inv_install_date_str));
                        $info_invoice_xero['ExpectedPaymentDate'] = $installation_date;
                        $info_invoice_xero['History_payment_expected_date'] = 'Payment expected on '.$payment_expected_date;
                    }
                    
                    //create/ update invoice
                    if($method == 'create' || $method == 'update'  ){
                        $group_line_items  = array();
                        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$InvoiceCRM->id."' AND deleted = 0";
                        $result = $InvoiceCRM->db->query($sql);
                        while ($row = $InvoiceCRM->db->fetchByAssoc($result)) {
                            if(isset($row) && $row != null ){
                                $lineitem_info = array(
                                    'description' => '',
                                    'quantity' => $row["product_qty"],
                                    'unit_amount' => $row["product_unit_price"],
                                    'item_code' => $product_mapping[$row["product_id"]],
                                    'option' => $bean_account->name,
                                    'trackingCategoryItem' => ''
                                );

                                // fix - "The description field is mandatory for each line item"
                                if($row["name"] != "" ){
                                    $lineitem_info['description'] = $row["name"];
                                }else{
                                    $lineitem_info['description'] = 'No Description';
                                }

                                $trackingCategoryItem = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                                $lineitem_info['trackingCategoryItem'] = $trackingCategoryItem;
                                // VEEC Invoice
                                if($row["product_id"] == "cbfafe6b-5e84-d976-8e32-574fc106b13f"){
                                    $date_for_rebate =  strtotime($inv_due_str) + 15*24*60*60;
                                    $date_for_rebate = date("Y-m-d", $date_for_rebate);
                                    $date_for_rebate   = new DateTime($date_for_rebate); 
                                    $date_for_veec       = $info_invoice_xero['due_date']; 
    
                                    $lineitem_info_VEEC = $lineitem_info;
                                    $lineitem_info_VEEC['unit_amount'] = (-$row["product_unit_price"]);
                                    $trackingCategoryItem_VEEC = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                                    $lineitem_info_VEEC['trackingCategoryItem'] = $trackingCategoryItem_VEEC;
    
                                    $lineitem_xero_VEEC = $API_Custom_Xero->Create_Line_Items($lineitem_info_VEEC);
                            
    
                                    $info_invoice_xero_VEEC = $info_invoice_xero;
                                    $info_invoice_xero_VEEC['invoice_number'] = $info_invoice_xero['invoice_number'] .' - VEECs Rebate';
                                    $info_invoice_xero_VEEC['date'] = $date_for_veec;
                                    $info_invoice_xero_VEEC['due_date'] = $date_for_rebate;
                                    $info_invoice_xero_VEEC['LineItems'][] = $lineitem_xero_VEEC;
                                    if($InvoiceCRM->xero_veec_rebate_invoice_c != ''){
                                        $xeroInvoiceVEEC =  $API_Custom_Xero->Get_Invoice_By_ID(trim($InvoiceCRM->xero_veec_rebate_invoice_c));
                                        $xeroInvoiceVEEC =   $API_Custom_Xero->Update_Invoice($info_invoice_xero_VEEC,$xeroInvoiceVEEC);
                                    }else{
                                        $xeroInvoiceVEEC = $API_Custom_Xero->Create_Invoice($info_invoice_xero_VEEC);
                                    }
                                    $result_data['inv_xero_veec'] =  $xeroInvoiceVEEC->getInvoiceID();  
                                    $InvoiceCRM->xero_veec_rebate_invoice_c =  $result_data['inv_xero_veec'];
                                    $InvoiceCRM->save();         
                                }
                                //STC Rebate
                                if($row["product_id"] == "4efbea92-c52f-d147-3308-569776823b19"  &&((strpos($InvoiceCRM->name, 'PV') !== 0) && (strpos($InvoiceCRM->name, 'WH') !== 0))){
                                    $date_for_rebate =  strtotime($inv_due_str) + 30*24*60*60;
                                    $date_for_rebate = date("Y-m-d", $date_for_rebate);
                                    $date_for_rebate       = new DateTime($date_for_rebate); 
                                    $date_for_stc       = $info_invoice_xero['due_date']; 
                                    $lineitem_info_STC = $lineitem_info;
                                    $lineitem_info_STC['unit_amount'] = (-$row["product_unit_price"]);
                                    $trackingCategoryItem_STC = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                                    $lineitem_info_STC['trackingCategoryItem'] = $trackingCategoryItem_STC;
                                    $lineitem_xero_STC = $API_Custom_Xero->Create_Line_Items($lineitem_info_STC);
    
    
                                    $info_invoice_xero_STC = $info_invoice_xero;
                                    $info_invoice_xero_STC['invoice_number'] = $info_invoice_xero['invoice_number'] .' - STCs Rebate';
                                    $info_invoice_xero_STC['date'] = $date_for_stc;
                                    $info_invoice_xero_STC['due_date'] = $date_for_rebate;
                                    $info_invoice_xero_STC['LineItems'][] = $lineitem_xero_STC;
    
                                    if($InvoiceCRM->xero_stc_rebate_invoice_c != ''){
                                        $xeroInvoiceSTC = $API_Custom_Xero->Get_Invoice_By_ID(trim($InvoiceCRM->xero_stc_rebate_invoice_c));
                                        $xeroInvoiceSTC = $API_Custom_Xero->Update_Invoice($info_invoice_xero_STC,$xeroInvoiceSTC);
                                    }else{
                                        $xeroInvoiceSTC = $API_Custom_Xero->Create_Invoice($info_invoice_xero_STC);
                    
                                    }
                                    $result_data['inv_xero_stc'] =  $xeroInvoiceSTC->getInvoiceID();
                                    $InvoiceCRM->xero_stc_rebate_invoice_c =  $result_data['inv_xero_stc'];
                                    $InvoiceCRM->save();  
                                    
                                }
                                //SV_SHWR Rebate
                                if($row["product_id"] == "431a9064-7cbb-6a44-e7ba-5d5b794137c7"){
                                    
                                    $date_for_rebate = strtotime($inv_due_str) + 30*24*60*60;
                                    $date_for_rebate = date("Y-m-d", $date_for_rebate);
                                    $date_for_rebate       = new DateTime($date_for_rebate); 
                                    $date_for_SV_SHWR       = $info_invoice_xero['due_date'];  
    
                                    $lineitem_info_SHWR = $lineitem_info;
                                    $lineitem_info_SHWR['unit_amount'] = (-$row["product_unit_price"]);
                                    $trackingCategoryItem_SHWR = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                                    $lineitem_info_SHWR['trackingCategoryItem'] = $trackingCategoryItem_SHWR;
                                    $lineitem_xero_SV_SHWR = $API_Custom_Xero->Create_Line_Items($lineitem_info_SHWR);
    
                                    $info_invoice_xero_SHWR = $info_invoice_xero;
                                    $info_invoice_xero_SHWR['invoice_number'] = $info_invoice_xero['invoice_number'] .' - SHW Rebate';
                                    $info_invoice_xero_SHWR['date'] = $date_for_SV_SHWR;
                                    $info_invoice_xero_SHWR['due_date'] = $date_for_rebate;
                                    $info_invoice_xero_SHWR['LineItems'][] = $lineitem_xero_SV_SHWR;
                             
                                    if($InvoiceCRM->xero_shw_rebate_invoice_c != ''){
                                        $xeroInvoiceSV_SHWR = $API_Custom_Xero->Get_Invoice_By_ID(trim($InvoiceCRM->xero_shw_rebate_invoice_c));
                                        $xeroInvoiceSV_SHWR = $API_Custom_Xero->Update_Invoice($info_invoice_xero_SHWR,$xeroInvoiceSV_SHWR);
                                    }else{
                                        $xeroInvoiceSV_SHWR = $API_Custom_Xero->Create_Invoice($info_invoice_xero_SHWR);
                                    }
                  
                                    $result_data['inv_xero_shw'] =  $xeroInvoiceSV_SHWR->getInvoiceID();
                                    $InvoiceCRM->xero_shw_rebate_invoice_c =  $result_data['inv_xero_shw'];
                                    $InvoiceCRM->save();  
                                }
                        
                                if(isset($product_mapping[$row["product_id"]])){
                                    $lineitem_xero_origin_xero = $API_Custom_Xero->Create_Line_Items($lineitem_info);
                                    // if profudct = STCs &  name inv start 'WH' or 'PV' & Account = Green Energy Trading Pty Ltd
                                    if($row["product_id"] == "4efbea92-c52f-d147-3308-569776823b19"  && ((strpos($InvoiceCRM->name, 'PV') === 0) || (strpos($InvoiceCRM->name, 'WH') === 0)) && $InvoiceCRM->billing_account_id == 'a0291eb6-5326-460f-f5fe-5aaa0d7c830d' ){
                                        $lineitem_xero_origin_xero->setTaxType('OUTPUT'); 
                                    }

                                    $group_line_items[] = $lineitem_xero_origin_xero;
                                }else{
                                    // Create Item Xero When Missing Mapping Products
                                    $Product_CRM =  new AOS_Products();
                                    $Product_CRM->retrieve($row['product_id']);
                        
                                    if($Product_CRM->id != '' && $Product_CRM->item_code_xero == ''){
                                        //get or create Item on xero
                                        $Item_Xero = null;
                                        if($Product_CRM->name != '') {
                                            $Item_Xero = $API_Custom_Xero->Get_Item_By_Name($Product_CRM->name);
                                        }

                                        if($Item_Xero == null && $Product_CRM->part_number != ''){
                                            $Item_Xero = $API_Custom_Xero->Get_Item_By_PartNumber($Product_CRM->part_number);
                                        }
                        
                                        if($Item_Xero == null){
                                            if($Product_CRM->part_number != ''){
                                                $info_product = array(
                                                    'name' => $Product_CRM->name,
                                                    'item_code' => $Product_CRM->part_number,
                                                    'description' => $Product_CRM->description,
                                                    'price' => $Product_CRM->price,
                                                    'cost' => $Product_CRM->cost
                                                );
                                                $Item_Xero = $API_Custom_Xero->Create_Item($info_product);
                                            }
                                        }
                                        
                                        if($Item_Xero != null){
                                            $Item_code_xero = $Item_Xero->getCode();
                                            $Product_CRM->item_code_xero = $Item_code_xero;
                                            $Product_CRM->save();
                                            $product_mapping[$Product_CRM->id] = $Product_CRM->item_code_xero;
                                            $lineitem_info['item_code'] = $Product_CRM->item_code_xero;
                                            $lineitem_xero_origin_xero = $API_Custom_Xero->Create_Line_Items($lineitem_info);
                                            $group_line_items[] = $lineitem_xero_origin_xero;
                                        }else{
                                            $result_data['msg'] = 'Missing Items -- Can\'t mapping with product on Xero';
                                        }

                                    }else{
                                        $result_data['msg'] = 'Missing Items -- Can\'t mapping with product on Xero';
                                    }
                                   
                                }
                            }
                        }
    

                        $info_invoice_xero['LineItems'] = $group_line_items;
    
                        if($InvoiceCRM->xero_invoice_c != ''){
                            $xeroInvoice = $API_Custom_Xero->Get_Invoice_By_ID(trim($InvoiceCRM->xero_invoice_c));
                            $xeroInvoice = $API_Custom_Xero->Update_Invoice($info_invoice_xero,$xeroInvoice);
                        }else{
                            $xeroInvoice = $API_Custom_Xero->Create_Invoice($info_invoice_xero);
                        }
                
                        $result_data['inv_xero_id'] =  $xeroInvoice->getInvoiceID();
                        $InvoiceCRM->xero_invoice_c =  $result_data['inv_xero_id'];
                        $InvoiceCRM->save();
                        echo json_encode($result_data);die();
                    }
            }
        } catch (\Throwable $th) {
            $message_error =  $th;
            preg_match('/A validation exception occurred \((.*?)\)/s', $message_error, $msg_output);
            if(isset($msg_output[1])){
                $result_data['msg'] = $msg_output[1];
                echo json_encode($result_data);die();
            }
        }


    
?>