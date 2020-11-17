<?php
    /**
        * User: thienpb
        * Date Updated: 5/12/2020
    **/
    require_once('xeroConfig.php');
    //Param request
    $type   =  $_REQUEST['type'];
    $method =  $_REQUEST['method'];
    $record =  $_REQUEST['record'];
    if($type == 'PurchaseOrder') {
        try {
            $return = xeroPoAPI($xero,$method,$record,$product_mapping);
            echo json_encode($return);
            die;
        } catch (\Throwable $th) {
print_r($th);
            echo json_encode(array('status'=>'Fail','xeroID'=>''));
        }
    }

    function xeroPoAPI($xero,$method,$record,$product_mapping){
        //bean Po
        $poBean = new PO_purchase_order();
        $poBean->retrieve($record);

        if($poBean->number == "" || $poBean->id == "") return;
        //bean account
        $beanAccount = new Account();
        $beanAccount->retrieve($poBean->billing_account_id);
        $date            = new DateTime(date('Y-m-d'));
        $dua_date        = strtotime("+15 day", time());
        $date_for_rebate = new DateTime(date("Y-m-d", $dua_date));

        //check Contact
        $contactOld = $xero->load('Accounting\\Contact')->where('EmailAddress=="'.$beanAccount->email1.'"')->execute();
        if(!empty($contactOld[0]->ContactID)){
            $contact = $contactOld[0];
        }else{
            $contact = new \XeroPHP\Models\Accounting\Contact($xero);
            // $contact->setEmailAddress($beanAccount->email1)
            //         ->setName($poBean->billing_account);
            // $contact->save();

            $name_customer = explode(" ", $beanAccount->name);
                $first_name = "";
                $last_name = "";
                if(count($name_customer) > 1){
                    $first_name = end($name_customer);
                    $last_name = str_replace($first_name, "", $beanAccount->name);
        
                }
                $phone_customer = createPhone($beanAccount->mobile_phone_c);
                $address_customer = createAddress($beanAccount->billing_address_street,$beanAccount->billing_address_city,$beanAccount->billing_address_state,$beanAccount->billing_address_postalcode);
                
                $info_contact_xero = array (
                    'number' => $beanAccount->number,
                    'name' => $beanAccount->name,
                    'first_name' => $first_name,
                    'last_name' =>  $last_name,
                    'email' => $beanAccount->email1,
                    'phone' => $phone_customer,
                    'address' => $address_customer,
                );
                $contact = createContact($info_contact_xero);
        }
        
        //reference
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
                    $reference = 'Inv-'.$supTitle[count($supTitle)-1].' Sanden';
                }else{
                    $reference = 'Inv-'.$supTitle[count($supTitle)-1].' '.$customerAccount->name;
                }
            }else{
                if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == ""){
                    $reference = 'Inv- Daikin';
                }else{
                    $reference = 'Inv-'.$customerAccount->name;
                }
            }

            
        }else if((strpos($poTitle,"sanden") !== false || strpos($poTitle,"daikin") !== false) && (strpos($poTitle,"plumbing") !== false || strpos($poTitle,"electrical") !== false)){
            // Po
            if($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == ""){
                $reference = 'Inv-';
            }else{
                $reference = 'Inv-'.$customerAccount->name;
            }
            
        }
        $check = false;
        try {
            $xeroPo =  $xero->loadByGUID('Accounting\\PurchaseOrder','PO-'.$poBean->number);
            $check = true;
        } catch (\Throwable $th) {
            $check = false;
        }
        if(count($xeroPo) > 0 && $check && $xeroPo->getStatus() != "DELETED"){
            $xeroPo->LineItems->removeAll();
        }else{
            $xeroPo = new \XeroPHP\Models\Accounting\PurchaseOrder($xero);
            $xeroPo->setPurchaseOrderNumber('PO-'.$poBean->number);
        }
        
        $xeroPo->setReference($reference)
                ->setDate($date)
                ->setDeliveryDate($date_for_rebate)
                ->setLineAmountType('Exclusive')
                ->setContact($contact);
        
        //save tracking
        if ($poBean->aos_invoices_po_purchase_order_1aos_invoices_ida == "") {
            $trackingOption = 'None';
        }else{
            $trackingOption = $customerAccount->Name;
            $trackingCategory = $xero->load('Accounting\\TrackingCategory')->where('Name=="'.$trackingOption.'"')->execute();
            if(empty($trackingCategory[0]->Name)){
                $class = 'XeroPHP\Models\Accounting\TrackingCategory';
                $uri = sprintf('%s/%s', $class::getResourceURI(), '09b830a8-46db-41d2-9836-ea57121fd907/Options');
                $url = new URL($xero, $uri, NULL);
                $request = new Request($xero, $url, Request::METHOD_PUT);

                $data =  "  <Options>
                                <Option>
                                    <Name>".htmlspecialchars(($trackingOption))."</Name>
                                </Option>
                            </Options>";
                $request->setBody($data);
                $request->send();
            }
        }
        // lineItem
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND parent_id = '".$poBean->id."' AND deleted = 0";
        $result = $db->query($sql);
        $trackingCategoryItem = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
        while ($row = $db->fetchByAssoc($result)) {
            if(isset($row) && $row != null ){
                if(isset($product_mapping[$row["product_id"]])){

                    if($row["product_id"] == "b3c61c17-5bfc-e4f2-1703-5a5ef9506025" && strpos(strtolower($poBean->name),"sanden") !== false){
                        $product_mapping[$row["product_id"]] = 'SPB';
                    }
                    $lineitem = new \XeroPHP\Models\Accounting\PurchaseOrder\LineItem($xero);
                    
                    $lineitem   ->setQuantity($row["product_qty"])
                                ->setUnitAmount($row["product_unit_price"])
                                ->setItemCode($product_mapping[$row["product_id"]])
                                ->addTracking($trackingCategoryItem ->setName('Customer')
                                                                    ->setOption($trackingOption));
                    $xeroPo->addLineItem($lineitem);
                }
            }
        }
        if(strpos($poTitle,"sanden") !== false && strpos($poTitle,"plumbing") === false && strpos($poTitle,"electrical") === false){
            $lineitem = new \XeroPHP\Models\Accounting\PurchaseOrder\LineItem($xero);
            $lineitem   ->setQuantity(1)
                        ->setUnitAmount(0)
                        ->setItemCode($product_mapping['dc076bed-1a30-7082-8156-5eb4cf618b46'])
                        ->addTracking($trackingCategoryItem ->setName('Customer')
                                                            ->setOption($trackingOption));
            $xeroPo->addLineItem($lineitem);
        }

        $xeroPo->save();

        //Attachment
        $xeroPo =  $xero->loadByGUID('Accounting\\PurchaseOrder','PO-'.$poBean->number);
        $templateID = "3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5";
        downloadPDFFile($templateID,$record,'PO_purchase_order');
        $attachmentFile =  dirname(__FILE__)."/files/invoice-". $record.".pdf";
        $attachment = \XeroPHP\Models\Accounting\Attachment::createFromLocalFile($attachmentFile);
        $xeroPo->addAttachment($attachment);
        
        //save
        $xeroPo->save();
        if($method == 'create'){
            $db = DBManagerFactory::getInstance();
            $xeroPoID = $xeroPo->getPurchaseOrderID();
            $sql = "Update po_purchase_order_cstm set xero_po_id_c = '".$xeroPoID."' WHERE id_c = '".$poBean->id."'";
            $result = $db->query($sql);
        }
        return array('status'=>'Ok','xeroID'=>$xeroPo->getPurchaseOrderID());
    }
    
    function createPhone($number){
        $type = 'DEFAULT';
        $phone = new \XeroPHP\Models\Accounting\Phone($this->xero);
        $phone->setPhoneType($type)
            ->setPhoneNumber($number);
        return $phone;
    }

    function createAddress($street='',$city = '',$state = '',$postalcode = '',$country = ''){
        $address =new \XeroPHP\Models\Accounting\Address($this->xero);
        $address->setAddressType(\XeroPHP\Models\Accounting\Address::ADDRESS_TYPE_POBOX)
        ->setAddressLine1($street)
        ->setCity($city)
        ->setRegion($state)
        ->setPostalCode($postalcode)
        ->setCountry($country);
        return $address;
    }

    function createContact($info_contact) {
        $contact_ = new \XeroPHP\Models\Accounting\Contact($this->xero);
        $contact_->setContactNumber($info_contact['number'])
        ->setFirstName($info_contact['first_name'])
        ->setLastName($info_contact['last_name'])
        ->setEmailAddress($info_contact['email'])
        ->addAddress($info_contact['address'])
        ->addPhone($info_contact['phone'])
        ->setName($info_contact['name']);    
        $contact_->save();
        return $contact_;
    }
?>