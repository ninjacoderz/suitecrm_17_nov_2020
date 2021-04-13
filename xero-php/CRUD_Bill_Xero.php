<?php
// ini_set('display_errors', 1);

include 'xeroConfig.php';
//Param request
$method = $_REQUEST['method'];
$record = $_REQUEST['record'];
$from_action = $_REQUEST['from_action'];

$result_data = array(
    'bill_xero_id' => '',
    'msg' => '',
);

try {
    $Bill = new pe_bills();
    $Bill->retrieve($record);
    if ($Bill->id != "") {

        $API_Custom_Xero = new API_Custom_Xero($xero);

        // Account Suitecrm
        $bean_account = new Account();
        $bean_account->retrieve($Bill->billing_account_id);
        // logic get phone customer
        $phone_numbers = explode(" ", $bean_account->phone_office);
        $country_code = "";
        $area_code = "";
        $phone_number_customer = $bean_account->mobile_phone_c;
        if (count($phone_numbers) >= 3) {
            $country_code = $phone_numbers[0];
            $area_code = $phone_numbers[1];
            $phone_number_customer = $phone_numbers[2];
        }
        $phone_customer = $API_Custom_Xero->Create_Phone($phone_number_customer);
        $address_customer = $API_Custom_Xero->Create_Address($bean_account->billing_address_street, $bean_account->billing_address_city, $bean_account->billing_address_state, $bean_account->billing_address_postalcode);
        
        $name_customer = explode(" ", $bean_account->name);
        $first_name = "";
        $last_name = "";
        if (count($name_customer) > 1) {
            $first_name = end($name_customer);
            $last_name = str_replace($first_name, "", $bean_account->name);
        }
        $info_contact_xero = array(
            'number' => $bean_account->number,
            'name' => $bean_account->name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $bean_account->email1,
            'phone' => $phone_customer,
            'address' => $address_customer,
        );

         //Logic Generate Reference - copy from Create PO Xero
         
        $poBean = new PO_purchase_order();
        $poBean->retrieve($Bill->po_purchase_order_id_c);
        $poTitle = strtolower($poBean->name);
        $supTitle  =  explode(" ",$poTitle);
        $reference ='';

        if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida != ""){
            $customerInvoice = new AOS_Invoices();
            $customerInvoice->retrieve($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida);
            
            $customerAccount = new Account();
            $customerAccount->retrieve($customerInvoice->billing_account_id);
        }

        if((strpos($poTitle,"sanden") !== false || strpos($poTitle,"daikin") !== false) && strpos($poTitle,"plumbing") === false && strpos($poTitle,"electrical") === false){
            // Po supplier
            if(strpos($poTitle,"sanden") !== false){
                if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == ""){
                    $reference = 'Inv-'.$supTitle[count($supTitle)-1].' PE PO '.$poBean->number.' Sanden';
                }else{
                    $reference = 'Inv-'.$supTitle[count($supTitle)-1].' PE PO '.$poBean->number.' '.$customerAccount->name;
                }
            }else{
                if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == ""){
                    $reference = 'Inv-'.' PE PO '.$poBean->number.' Daikin';
                }else{
                    $reference = 'Inv-'.' PE PO '.$poBean->number.' '.$customerAccount->name;
                }
            }

            
        }else if((strpos($poTitle,"sanden") !== false || strpos($poTitle,"daikin") !== false) && (strpos($poTitle,"plumbing") !== false || strpos($poTitle,"electrical") !== false)){
            // PO 
            if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == ""){
                $reference = 'Inv-'.' PE PO '.$poBean->number;
            }else{
                $reference = 'Inv-'.' PE PO '.$poBean->number.' '.$customerAccount->name;
            }
            
        }

        //get or create contact on xero
        $Contact_xero = null;

        if ($Contact_xero == null && $bean_account->email1 != '') {
            $Contact_xero = $API_Custom_Xero->Get_Contact_By_Email($bean_account->email1);
        }

        if ($Contact_xero != null) {
            $Contact_xero = $API_Custom_Xero->Update_Contact($info_contact_xero, $Contact_xero);
        } else {
            $Contact_xero = $API_Custom_Xero->Create_Contact($info_contact_xero);
        }

        //due date and date origin , rebate date 

        $date            = new DateTime(date('Y-m-d'));
        $due_date        = strtotime("+15 day", time());
        $due_date = new DateTime(date("Y-m-d", $due_date));
        
        //information for origin bill
        $info_bill_xero = array(
            'attachment_link' => '',
            'bill_number' =>$reference, // It displays referance in Xero
            'bill_name' => $reference,
            'date' => $date,
            'due_date' => $due_date,
            'LineItems' => array(),
            'contact' => $Contact_xero,
            'ExpectedPaymentDate' => '',
            'History_payment_expected_date' => '',
        );
        
        $info_bill_xero['attachment_link'] = '';

        //create/ update bill
        if ($method == 'create' || $method == 'update') {
            $group_line_items = array();
            $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'pe_bills' AND parent_id = '" . $Bill->id . "' AND deleted = 0";
            $result = $Bill->db->query($sql);
            while ($row = $Bill->db->fetchByAssoc($result)) {
                if (isset($row) && $row != null) {
                    $lineitem_info = array(
                        'description' => '',
                        'quantity' => $row["product_qty"],
                        'unit_amount' => $row["product_unit_price"],
                        'item_code' => $product_mapping[$row["product_id"]],
                        'option' => $bean_account->name,
                        'trackingCategoryItem' => '',
                    );

                    // fix - "The description field is mandatory for each line item"
                    if ($row["name"] != "") {
                        $lineitem_info['description'] = $row["name"];
                    } else {
                        $lineitem_info['description'] = 'No Description';
                    }

                    $trackingCategoryItem = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                    $lineitem_info['trackingCategoryItem'] = $trackingCategoryItem;
    
                    if (isset($product_mapping[$row["product_id"]])) {
                        $lineitem_xero_origin_xero = $API_Custom_Xero->Create_Line_Items($lineitem_info);

                        $group_line_items[] = $lineitem_xero_origin_xero;
                    } else {
                        // Create Item Xero When Missing Mapping Products
                        $Product_CRM = new AOS_Products();
                        $Product_CRM->retrieve($row['product_id']);

                        if ($Product_CRM->id != '' && $Product_CRM->item_code_xero == '') {
                            //get or create Item on xero
                            $Item_Xero = null;
                            if ($Product_CRM->name != '') {
                                $Item_Xero = $API_Custom_Xero->Get_Item_By_Name($Product_CRM->name);
                            }

                            if ($Item_Xero == null && $Product_CRM->part_number != '') {
                                $Item_Xero = $API_Custom_Xero->Get_Item_By_PartNumber($Product_CRM->part_number);
                            }

                            if ($Item_Xero == null) {
                                if ($Product_CRM->part_number != '') {
                                    $info_product = array(
                                        'name' => $Product_CRM->name,
                                        'item_code' => $Product_CRM->part_number,
                                        'description' => $Product_CRM->description,
                                        'price' => $Product_CRM->price,
                                        'cost' => $Product_CRM->cost,
                                        'category_product' => $Product_CRM->aos_product_category_id
                                    );
                                    $Item_Xero = $API_Custom_Xero->Create_Item($info_product);
                                }
                            }

                            if ($Item_Xero != null) {
                                $Item_code_xero = $Item_Xero->getCode();
                                $Product_CRM->item_code_xero = $Item_code_xero;
                                $Product_CRM->save();
                                $product_mapping[$Product_CRM->id] = $Product_CRM->item_code_xero;
                                $lineitem_info['item_code'] = $Product_CRM->item_code_xero;
                                $lineitem_xero_origin_xero = $API_Custom_Xero->Create_Line_Items($lineitem_info);
                                $group_line_items[] = $lineitem_xero_origin_xero;
                            } else {
                                $result_data['msg'] = 'Missing Items -- Can\'t mapping with product on Xero';
                            }

                        } else {
                            $result_data['msg'] = 'Missing Items -- Can\'t mapping with product on Xero';
                        }

                    }
                }
            }

            $info_bill_xero['LineItems'] = $group_line_items;
           
            if ($Bill->xero_bill_c != '') {
                $xeroBill = $API_Custom_Xero->Get_Bill_By_ID(trim($Bill->xero_bill_c));
                $xeroBill = $API_Custom_Xero->Update_Bill($info_bill_xero, $xeroBill);
            } else {
                $xeroBill = $API_Custom_Xero->Create_Bill($info_bill_xero);
            }

            $result_data['bill_xero_id'] = $xeroBill->getInvoiceID();
            $Bill->xero_bill_c = $result_data['bill_xero_id'];
            $Bill->save();
            echo json_encode($result_data);die();
        }
    }
} catch (\Throwable $th) {
    $message_error = $th;
    preg_match('/A validation exception occurred \((.*?)\)/s', $message_error, $msg_output);
    if (isset($msg_output[1])) {
        $result_data['msg'] = $msg_output[1];
        echo json_encode($result_data);die();
    }
}
