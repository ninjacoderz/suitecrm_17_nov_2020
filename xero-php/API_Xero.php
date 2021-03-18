<?php

    class API_Custom_Xero {
        public $xero;
        public function __construct($xero) {
            $this->xero = $xero; 
        }

        public function Get_Contact_By_Email ($email){
            $contacts = $this->xero->load('Accounting\\Contact')->where('EmailAddress=="'.$email.'"')->execute();
            $contact = null;
            if(!empty($contacts[0]->ContactID)){
                $contact = $contacts[0];
            }
            return $contact;
        }
        
        public function Get_Contact_By_Name($name){
            $contacts = $this->xero->load('Accounting\\Contact')->where('Name.Contains("'.$name.'")')->execute();
            $contact = null;
            if(!empty($contacts[0]->ContactID)){
                $contact = $contacts[0];
            }
            return $contact;
        }

        public function Create_Phone($number){
            $type = 'DEFAULT';
            $phone = new \XeroPHP\Models\Accounting\Phone($this->xero);
            $phone->setPhoneType($type)
                ->setPhoneNumber($number);
            return $phone;
        }
    
        public function Create_Address($street='',$city = '',$state = '',$postalcode = '',$country = ''){
            $address =new \XeroPHP\Models\Accounting\Address($this->xero);
            $address->setAddressType(\XeroPHP\Models\Accounting\Address::ADDRESS_TYPE_POBOX)
            ->setAddressLine1($street)
            ->setCity($city)
            ->setRegion($state)
            ->setPostalCode($postalcode)
            ->setCountry($country);
            return $address;
        }
     
        public function Create_Contact($info_contact) {
            $contact = new \XeroPHP\Models\Accounting\Contact($this->xero);
            $contact->setContactNumber($info_contact['number'])
            ->setFirstName($info_contact['first_name'])
            ->setLastName($info_contact['last_name'])
            ->setEmailAddress($info_contact['email'])
            ->addAddress($info_contact['address'])
            ->addPhone($info_contact['phone'])
            ->setName($info_contact['name']);    
            $contact->save();
            return $contact;
        }
    
        public function Update_Contact($info_contact, $contact) {
            $contact->setContactNumber($info_contact['number'])
            ->setFirstName($info_contact['first_name'])
            ->setLastName($info_contact['last_name'])
            ->setEmailAddress($info_contact['email'])
            ->addAddress($info_contact['address'])
            ->addPhone($info_contact['phone'])
            ->setName($info_contact['name']);    
            $contact->save();
            return $contact;
        }
    
        public function Get_Invoice_By_ID($ID) {
            if($ID != '') {
                $invoice =  $this->xero->loadByGUID('Accounting\\Invoice',$ID);
            }else{
                $invoice = null;
            }
            
            return $invoice;
        }

        public function Create_Line_Items($lineitem_info){
            $trackingCategoryItem = $lineitem_info['trackingCategoryItem'];
            $lineitem = new \XeroPHP\Models\Accounting\Invoice\LineItem($this->xero);
            $lineitem->setDescription($lineitem_info['description'])
                ->setQuantity($lineitem_info['quantity'])
                ->setUnitAmount($lineitem_info['unit_amount'])
                ->setItemCode($lineitem_info['item_code']) 
                ->addTracking($trackingCategoryItem ->setName('Customer')
                ->setOption($lineitem_info['option']));
            return $lineitem;
        }

        public function Create_Invoice($invoice_info){
         
            $xeroInvoice = new \XeroPHP\Models\Accounting\Invoice($this->xero);
            $xeroInvoice->setStatus('AUTHORISED');
            $xeroInvoice = $this->Set_Invoice($invoice_info,$xeroInvoice);
            return  $xeroInvoice;    
        }

        public function Update_Invoice($invoice_info,$xeroInvoice){
            if($xeroInvoice->getStatus() == 'DELETED' || $xeroInvoice->getStatus() == 'VOIDED'){
                return  $xeroInvoice;
            }
            $xeroInvoice = $this->Set_Invoice($invoice_info,$xeroInvoice);
            return  $xeroInvoice;   
        }

        public function Set_Invoice($invoice_info,$xeroInvoice){
            $xeroInvoice->setInvoiceNumber($invoice_info['invoice_number']);
            $xeroInvoice->setReference($invoice_info['invoice_name'])
                        ->setDate($invoice_info['date'])
                        ->setDueDate($invoice_info['due_date'] )
                        ->setType(\XeroPHP\Models\Accounting\Invoice::INVOICE_TYPE_ACCREC)
                        ->setLineAmountType('Exclusive')
                        ->setContact($invoice_info['contact']);

            if($invoice_info['ExpectedPaymentDate'] != ''){
                $xeroInvoice->setExpectedPaymentDate($invoice_info['ExpectedPaymentDate']);
            }
            
            $xeroInvoice->LineItems->removeAll();
            foreach ($invoice_info['LineItems'] as $key => $value) {
                $xeroInvoice->addLineItem( $value);
            }
            $xeroInvoice->save();
            
            $history_detail = $invoice_info['History_payment_expected_date'];
            if($history_detail != ''){
                $history = new \XeroPHP\Models\Accounting\History($this->xero);
                $history->setDetails($history_detail);
                $xeroInvoice->addHistory($history);
            }

            $attachmentFile = $invoice_info['attachment_link'];
            if($attachmentFile != ''){
                $attachment = \XeroPHP\Models\Accounting\Attachment::createFromLocalFile($attachmentFile);
                $xeroInvoice->addAttachment($attachment);
            }

            return  $xeroInvoice;   
        }

        public function Create_Item($info_product){
            $Item = new \XeroPHP\Models\Accounting\Item($this->xero);
            $Item = $this->Set_Item($info_product,$Item);
            return $Item;
        }

        public function Update_Item($info_product,$Item){
            
            // can't update item
            // $Item = $this->Set_Item($info_product,$Item);
            return  $Item;  
        }

        public function Set_Item($info_product,$Item){
            //fix bugs: Inventory Item Name Must not be more than 50 characters long
            if(strlen($info_product['name']) > 49 ) {
                $info_product['name'] = substr($info_product['name'],0,49);
            }


            $Item->setName($info_product['name']);
            $Item->setCode($info_product['item_code'])
            ->setDescription($info_product['description'])
            ->setIsSold(true)
            ->setIsPurchased(true);
            
            $Item_Sale =  new \XeroPHP\Models\Accounting\Item\Sale($this->xero);
            $Item_Sale->setUnitPrice($info_product['price'])
            ->setAccountCode('201');

            $Item_Purchase =  new \XeroPHP\Models\Accounting\Item\Purchase($this->xero);
            $Item_Purchase->setUnitPrice($info_product['cost'])
            ->setAccountCode('315');

            $Item->setSalesDetails($Item_Sale)
            ->setPurchaseDetails($Item_Purchase);

            $Item->save();
            return $Item;
        }

        public function Get_Item_By_ID($ID){
            $Item = null;
            if($ID != ''){
                $Item =  $this->xero->loadByGUID('Accounting\\Item',trim($ID));
            }
            return $Item;
        }

        public function Get_Item_By_Name($name){
            $Items = $this->xero->load('Accounting\\Item')->where('Name=="'.trim($name).'"')->execute();
            $Item = null;
            if(!empty($Items[0])){
                $Item = $Items[0];
            }
            return $Item;
        }

        
        public function Get_Item_By_PartNumber($name){
            $name = strtr(
                $name,
                [
                    '&lt;'=> '<',
                    '&gt;'=>  '>',
                    '&quot;' =>  '"',
                    '&apos;' =>  "'" ,
                    '&amp;'=> '&' ,
                    '&nbsp;' => ' '
                ]
            );
            
            $Items = $this->xero->load('Accounting\\Item')->where('Code=="'.trim($name).'"')->execute();
            $Item = null;
            if(!empty($Items[0])){
                $Item = $Items[0];
            }
            return $Item;
        }

        public function Get_Bill_By_ID($ID) {
            if($ID != '') {
                $Bill =  $this->xero->loadByGUID('Accounting\\Invoice',$ID);
            }else{
                $Bill = null;
            }
            
            return $Bill;
        }

        public function Create_Bill($bill_info){
         
            $xeroBill = new \XeroPHP\Models\Accounting\Invoice($this->xero);
            $xeroBill->setStatus('DRAFT');
            $xeroBill = $this->Set_Bill($bill_info,$xeroBill);
            return  $xeroBill;    
        }

        public function Update_Bill($bill_info,$xeroBill){
            if($xeroBill->getStatus() == 'DELETED' || $xeroBill->getStatus() == 'VOIDED'){
                return  $xeroBill;
            }
            $xeroBill = $this->Set_Bill($bill_info,$xeroBill);
            return  $xeroBill;   
        }

        public function Set_Bill($bill_info,$xeroBill){
            $xeroBill->setInvoiceNumber($bill_info['bill_number']);
            $xeroBill->setReference($bill_info['bill_name'])
                        ->setDate($bill_info['date'])
                        ->setDueDate($bill_info['due_date'] )
                        ->setStatus(\XeroPHP\Models\Accounting\Invoice::INVOICE_STATUS_DRAFT)
                        ->setType(\XeroPHP\Models\Accounting\Invoice::INVOICE_TYPE_ACCPAY)
                        ->setLineAmountType('Exclusive')
                        ->setContact($bill_info['contact']);

            if($bill_info['ExpectedPaymentDate'] != ''){
                $xeroBill->setExpectedPaymentDate($bill_info['ExpectedPaymentDate']);
            }
            
            $xeroBill->LineItems->removeAll();
            foreach ($bill_info['LineItems'] as $key => $value) {
                $xeroBill->addLineItem( $value);
            }
            $xeroBill->save();
            
            $history_detail = $invoice_info['History_payment_expected_date'];
            if($history_detail != ''){
                $history = new \XeroPHP\Models\Accounting\History($this->xero);
                $history->setDetails($history_detail);
                $xeroBill->addHistory($history);
            }

            $attachmentFile = $invoice_info['attachment_link'];
            if($attachmentFile != ''){
                $attachment = \XeroPHP\Models\Accounting\Attachment::createFromLocalFile($attachmentFile);
                $xeroBill->addAttachment($attachment);
            }

            return  $xeroBill;   
        }
    }
    

?>