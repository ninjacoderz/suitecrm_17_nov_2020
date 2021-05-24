<?php
    /*
        thienpb code for create lead and quote fromPE
    */
    // $rq_data = json_decode('{"module_name":"Leads","name_value_list":{"account_name":"Thien Test","first_name":"Thien","last_name":"Test","primary_address_street":"142 Alabaster Terrace","primary_address_city":"HILLARYS","primary_address_state":"WA","primary_address_postalcode":"6025","primary_address_country":"Australia","email1":"thienpb89@gmail.com","phone_mobile":"0404820895","lead_source":"Web Site","orderID":"558","products":[{"title":"Methven Kiri Satinjet Graphite ULF Showerhead Handset","quantity":"1.00"}],"assigned_user_id":"61e04d4b-86ef-00f2-c669-579eb1bb58fa","couponCode":[{"label":"PE 146$","amount":"-15"}],"ship_method_id":"2"}}',true);
    $rq_data = $_POST;
    if(isset($rq_data['module_name']) && isset($rq_data['name_value_list'])){
        $rq_lead_info = $rq_data['name_value_list'];
       
        $account_name =  $rq_lead_info['account_name'];
        $first_name = $rq_lead_info['first_name'];
        $last_name = $rq_lead_info['last_name'];
        $primary_address_street = $rq_lead_info['primary_address_street'];
        $primary_address_city = $rq_lead_info['primary_address_city'];
        $primary_address_state = $rq_lead_info['primary_address_state'];
        $primary_address_postalcode =  $rq_lead_info['primary_address_postalcode'];
        $primary_address_country =  $rq_lead_info['primary_address_country'];
        $email1 =  $rq_lead_info['email1'];
        $phone_mobile = $rq_lead_info['phone_mobile'];
        $lead_source =  $rq_lead_info['lead_source'];
        $orderID =  $rq_lead_info['orderID'];
        $assigned_user_id = $rq_lead_info['assigned_user_id'];
        $products = $rq_lead_info['products'];
        $couponCode = $rq_lead_info['couponCode'];
        $ship_method_id = $rq_lead_info['ship_method_id'];
        // $couponCode = '';
        //check Lead existing
        $db = DBManagerFactory::getInstance();
        $sql ="SELECT leads.id FROM leads INNER JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = leads.id INNER JOIN email_addresses ON email_addr_bean_rel.email_address_id = email_addresses.id WHERE leads.deleted = 0 AND email_addresses.email_address = '$email1' ORDER BY leads.date_entered DESC  LIMIT 1";
        $ret = $db->query($sql);
        $row = $db->fetchByAssoc($ret);

        $existed_lead = false;
        if($ret->num_rows > 0){
            $lead = new Lead();
            $lead ->retrieve($row['id']);
            if($lead->id){
                if($email1 = $lead->email1)
                    $existed_lead = true;
                else
                    $existed_lead = false; 
            }else{
                $existed_lead = false;
            }
        }else{
            $existed_lead = false; 
        }

        if(!$existed_lead){

            //create Lead
            $new_lead =  new Lead();
            $new_lead->first_name = $first_name;
            $new_lead->last_name = $last_name;
            // $lead->status = 'Converted'; //VUT-create status NEW for new Lead
            $new_lead->account_name = $account_name;
            $new_lead->primary_address_street = $primary_address_street;
            $new_lead->primary_address_city = $primary_address_city;
            $new_lead->primary_address_state = $primary_address_state;
            $new_lead->primary_address_postalcode = $primary_address_postalcode;
            $new_lead->primary_address_country = $primary_address_country;
            $new_lead->phone_mobile = $phone_mobile;
            $new_lead->phone_work = $phone_mobile;
            $new_lead->sms_number_c = $phone_mobile;
            $new_lead->email1 = $email1;
            $new_lead->lead_source = $lead_source;
            $new_lead->assigned_user_id = $assigned_user_id;

            // create account 
            $account = new Account();
            $account->name = $first_name ." " . $last_name;
            $account->mobile_phone_c = $phone_mobile;
            $account->billing_address_street = $primary_address_street;
            $account->billing_address_city = $primary_address_city;
            $account->billing_address_state = $primary_address_state;
            $account->billing_address_postalcode = $primary_address_postalcode;
            $account->billing_address_country = $primary_address_country;
            $account->assigned_user_id = $assigned_user_id;
            $account->email1 = $email1;


            // create contact
            $contact = new Contact();
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            $contact->phone_mobile = $phone_mobile;
            $contact->primary_address_street = $primary_address_street;
            $contact->primary_address_city = $primary_address_city;
            $contact->primary_address_state = $primary_address_state;
            $contact->primary_address_postalcode = $primary_address_postalcode;
            $contact->primary_address_country = $primary_address_country;
            $contact->assigned_user_id = $assigned_user_id;
            $contact->email1 = $email1;
           

            // convert email to account  + contact
            $account->save();

            $contact->account_id = $account->id;
            $contact->save();

            $new_lead->account_id = $account->id;
            $new_lead->contact_id = $contact->id;
            $new_lead->save();

            convertNewLeadToQuote($new_lead, $orderID , $products, $couponCode,$ship_method_id);
            //VUT-Change Lead status New -> Converted
            $save_lead = new Lead();
            $save_lead->retrieve($new_lead->id);
            $save_lead->status = 'Converted';
            $save_lead->save();            
        }else{
            $lead =  new Lead();
            $lead->retrieve($row['id']);
            if($lead->id){
                $account = new Account();
                $account->retrieve($lead->account_id);
                if($account->id){
                    $account->load_relationships('Contacts');
                    $contacts = $account->get_linked_beans('contacts','Contacts');
                    $contactOld = array_filter($contacts,function($a) use($email1){
                        return ($a->email1 == $email1 && $a->primary_address_street == $primary_address_street);
                    });

                    if(count($contactOld) > 0){
                        usort($contactOld, function($a, $b) {
                            return strtotime($b->date_modified) - strtotime($a->date_modified);
                        });
                        $contact =  $contactOld[0];
                    }else{
                        // create new contact
                        $contact = new Contact();
                        $contact->first_name = $first_name;
                        $contact->last_name = $last_name;
                        $contact->phone_mobile = $phone_mobile;
                        $contact->primary_address_street = $primary_address_street;
                        $contact->primary_address_city = $primary_address_city;
                        $contact->primary_address_state = $primary_address_state;
                        $contact->primary_address_postalcode = $primary_address_postalcode;
                        $contact->primary_address_country = $primary_address_country;
                        $contact->assigned_user_id = $assigned_user_id;
                        $contact->email1 = $email1;
                        $contact->account_id = $account->id;
                        $contact->save();

                        //set primaty contact for account
                        $account->primary_contact_c = $contact->id;
                        $account->save();
                    }
                }
                //clone new lead
                $leadClone =  clone $lead;
                $leadClone->first_name                  = $first_name;
                $leadClone->last_name                   = $last_name;
                $leadClone->primary_address_street      = $primary_address_street;
                $leadClone->primary_address_city        = $primary_address_city;
                $leadClone->primary_address_state       = $primary_address_state;
                $leadClone->primary_address_postalcode  = $primary_address_postalcode;
                $leadClone->primary_address_country     = $primary_address_country;
                $leadClone->email1                      = $email1;
                $leadClone->account_id                  = $account->id;
                $leadClone->contact_id                  = $contact->id;

                convertNewLeadToQuote($leadClone, $orderID , $products,$couponCode,$ship_method_id);
                //save lead old
                $lead->status = 'Converted';
                $lead->save();
            }
        }
    }
    //data demo
    // $bean = new Lead();
    // $bean->retrieve("c8f24b96-1a71-04da-e88d-5cf8e158a7d7");
    // $bean->email1 = "thienpb893@gmail.com";
    // $bean->save();die;    $products = array();
    // $products[] = array("title" =>'Methven Kiri Satinjet Graphite ULF Showerhead Handset' ,"quantity" => 2);
    // $products[] = array("title" =>'Methven Kiri Satinjet Graphite ULF Showerhead Handset with Hose' ,"quantity" => 1);
    // convertNewLeadToQuote($bean, 88 , $products);
    function convertNewLeadToQuote($bean, $orderID , $products, $couponCode,$ship_method_id){

        $dateAction = new DateTime('+7 day');
        $dateQuote = new DateTime();

        $new_quote = new AOS_Quotes();
        $new_quote->name = $bean->first_name .' ' .$bean->last_name .' ' .$bean->primary_address_city.' ' .$bean->primary_address_state.' Methven' ;
        $new_quote->name = str_replace("&rsquo;","'",$new_quote->name);
        $new_quote->quote_type_c = 'quote_type_methven';
        $new_quote->quote_date_c = $dateQuote->format('Y-m-d H:i:s');
        $new_quote->next_action_date_c = $dateAction->format('Y-m-d');
        $new_quote->stage='Draft';
        $new_quote->account_name = $bean->EditView_account_name;
        $new_quote->assigned_user_name = $bean->assigned_user_name;
        $new_quote->assigned_user_id = $bean->assigned_user_id;
        $new_quote->assigned_user_id = $bean->assigned_user_id;
        $new_quote->billing_account_id = $bean->account_id;
        $new_quote->billing_contact_id = $bean->contact_id;

        $new_quote->billing_address_street = trim($bean->primary_address_street," ");
        $new_quote->billing_address_city = trim($bean->primary_address_city," ");
        $new_quote->billing_address_state = trim($bean->primary_address_state," ");
        $new_quote->billing_address_postalcode = trim($bean->primary_address_postalcode," ");
        $new_quote->billing_address_country = trim($bean->primary_address_country," ");

        $new_quote->shipping_address_street = trim($bean->primary_address_street," ");
        $new_quote->shipping_address_city = trim($bean->primary_address_city," ");
        $new_quote->shipping_address_state = trim($bean->primary_address_state," ");
        $new_quote->shipping_address_postalcode = trim($bean->primary_address_postalcode," ");
        $new_quote->shipping_address_country = trim($bean->primary_address_country," ");

        $new_quote->install_address_c = trim($bean->primary_address_street," ");
        $new_quote->install_address_city_c = trim($bean->primary_address_city," ");
        $new_quote->install_address_state_c = trim($bean->primary_address_state," ");
        $new_quote->install_address_postalcode_c = trim($bean->primary_address_postalcode," ");
        $new_quote->install_address_country_c = trim($bean->primary_address_country," ");

        $new_quote->billing_contact_id = $bean->contact_id;
        $new_quote->billing_account_id = $bean->account_id;

        $new_quote->account_firstname_c = $bean->first_name;
        $new_quote->account_lastname_c = $bean->last_name;

        $new_quote->description = "This was created from Order #".$orderID." in PE Commerce website";
        
        $new_quote->save();
        create_new_relationship_aos_quotes_leads($new_quote->id,$bean->id);

        // save group product
        $product_quote_group = new AOS_Line_Item_Groups();
        $product_quote_group->name = 'Methven';
        $product_quote_group->created_by = $bean->assigned_user_id;
        $product_quote_group->assigned_user_id = $bean->assigned_user_id;
        $product_quote_group->parent_type = 'AOS_Quotes';
        $product_quote_group->parent_id = $new_quote->id;
        $product_quote_group->number = '1';
        $product_quote_group->currency_id = '-99';
        $product_quote_group->save();

        //product methven
        $type_shipping = "";
        $products_title = array();
        foreach($products as $product){
            if($product['title'] == 'ValveCosy'){
                array_push($products_title,'Valvecosy Insulator');
                $new_quote->name = $bean->first_name .' ' .$bean->last_name .' ' .$bean->primary_address_city.' ' .$bean->primary_address_state.' ValveCosy' ;
                $new_quote->quote_type_c = 'ValveCosy';
                $new_quote->save();
            }else{
                $title = explode('-',$product['title']);
                $product['title'] = trim($title[0]);
                array_push($products_title,$product['title']);
            }
        }
        if( $ship_method_id == "1"){
            array_push($products_title,"Methven Shipping and Handling Standard");
            $type_shipping = "B30";
        }elseif( $ship_method_id == "2"){
            array_push($products_title,"Methven Shipping and Handling Express");
            $type_shipping = "B20";
        }
        //add line items promo code
        if($couponCode != ''){
            array_push($products_title,"Pure Electric Promo Code");
        }
        $products_title = implode("','", $products_title);

        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM aos_products 
                LEFT JOIN aos_products_cstm ON aos_products.id = aos_products_cstm.id_c
                WHERE `name` IN ('".$products_title."') 
                ORDER BY price ASC";
        $ret = $db->query($sql);

        $total_amt = 0;
        $subtotal_amount= 0;
        $discount_amount =0;
        $tax_amount =0;
        $total_amount = 0;
        $index = 0;
        $is_use_number_1 = false;
        $weight = 0;
        $length = 0;
        $width = 0;
        $height = 0;
        $picking_code = '';
        while($row = $db->fetchByAssoc($ret)){
            $product_line = new AOS_Products_Quotes();
            $product_line->currency_id = $row['currency_id'];
            $product_line->item_description = $row['description'];
            $product_line->name = $row['name'];
            $product_line->part_number = $row['part_number'];
            $product_line->product_cost_price = $row['cost'];
            $product_line->product_id = $row['id'];
            $product_line->product_list_price =$row['price'];
            $product_line->group_id = $product_quote_group->id;
            $product_line->parent_id = $new_quote->id;;
            $product_line->parent_type = 'AOS_Quotes';
            $product_line->discount = 'Percentage';
            $product_line->number = $index+1 ;

            // if($row['part_number'] == 'Methven_Shipping_Handling'){
            //     $product_line->product_qty = 1; 
            // }else{
            //     $product_line->product_qty = (int) $products[$index-1]['quantity'] ; 
            // }
            
            
            if($row['part_number'] == 'Australia_Post_Standard' || $row['part_number'] == 'Australia_Post_Express' || $row['part_number'] == 'Pure_Electric_Promo_Code'){
                $product_line->product_qty = 1; 
            }else{
                foreach ($products as $key => $value) {
                    if($value['title'] == $row['name'] || $value['title'] == 'ValveCosy'){
                        $product_line->product_qty = (int) $products[$key]['quantity'] ; 
                        $check_qty = (int) $products[$key]['quantity'] ;
                    }
                }
            }
            if ($check_qty != 0) {
                $weight = floatval($row['weight_c']);
                $length = floatval($row['length_c']);
                $width  = floatval($row['width_c']);
                $height = floatval($row['height_c']);
            }
            // if( $row['part_number'] == "13-8265 (FLX252)_H" ){ //Handheld with only
            //     $weight = 0.66;
            //     $length = 29.6;
            //     $width = 19.0;
            //     $height = 8.5;
            // }else if( $row['part_number'] == "13-8258" ){
            //     $weight = 0.26;
            //     $length = 10.7;
            //     $width = 11.7;
            //     $height = 7.6;
            // }else if( $row['part_number'] == "13-8265 (FLX252)" ){ //Handheld only
            //     $weight = 0.66;
            //     $length = 29.6;
            //     $width = 19.0;
            //     $height = 8.5;
            // }
            if($row['part_number'] == 'Pure_Electric_Promo_Code'){
                $priceCoupon = (float)$couponCode[0]['amount'];
                $list_price = $priceCoupon/1.1;
                $product_line->product_total_price =$list_price* $product_line->product_qty;
                $product_line->product_cost_price = $list_price;
                $product_line->product_list_price = $list_price;
                $product_line->item_description .= '-- '. $couponCode[0]['label'];
                $product_line->vat = '10.0';
                $product_line->vat_amt = round(($list_price * $product_line->product_qty) * 0.1*1,2);
            }else{
                $product_line->product_total_price =$row['price']* $product_line->product_qty;
                $product_line->vat = '10.0';
                $product_line->vat_amt = round(($row['price']* $product_line->product_qty) * 0.1*1,2);
            }                                
            $product_line->save();

            $total_amt += $product_line->product_total_price;
            $tax_amount += $product_line->vat_amt;
            $picking_code .= $row['picking_code_c'].', ';
            $index++;
        }

        $discount_amount =0;
        $total_amount = $total_amt + $tax_amount;
        $subtotal_amount= $total_amt;

        $new_quote->total_amt = round($total_amt , 2);
        $new_quote->subtotal_amount = round($subtotal_amount , 2);
        $new_quote->discount_amount = round($discount_amount , 2);
        $new_quote->tax_amount = round($tax_amount , 2);
        $new_quote->total_amount = round($total_amount , 2);
        $new_quote->save();
       
        $product_quote_group->tax_amount = round($tax_amount , 2);
        $product_quote_group->total_amount = round($total_amount , 2);
        $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
        $product_quote_group->save();

        $lead_new = new Lead();
        $lead_new = $lead_new->retrieve($bean->id);
        $lead_new->create_methven_quote_c = 1;
        $lead_new->create_methven_quote_num_c =  $new_quote->id;
        $lead_new->save();

        //auto create shipments auspost
        $shipments = array (
            'shipments' => 
            array (
            0 => 
            array (
                'from' => 
                array (
                'name' => 'Matthew Wright',
                'business_name' => 'Pure Electric',
                'lines' => 
                array (
                    0 => '38 EWING ST',
                ),
                'suburb' => 'BRUNSWICK',
                'state' => 'VIC',
                'postcode' => '3056',
                'email' => 'info@pure-electric.com.au',
                'phone' => '0421616733',
                ),
                'to' => 
                array (
                'name' => $bean->account_name,
                'business_name' => '',
                'type' => 'STANDARD_ADDRESS',
                'country' => 'AU',
                'lines' => 
                array (
                    0 => $bean->primary_address_street,
                ),
                'suburb' => $bean->primary_address_city,
                'state' => $bean->primary_address_state,
                'postcode' => $bean->primary_address_postalcode,
                'email' => $bean->email1,
                'phone' => $bean->phone_mobile,
                ),
                'email_tracking_enabled' => true,
                'customer_reference_1' => '#'.$orderID.' '.trim($picking_code,', '),
                'items' => 
                array (
                0 => 
                array (
                    'contains_dangerous_goods' => false,
                    'item_description' => '#'.$orderID.' '.trim($picking_code,', '),
                    'weight' => $weight,
                    'length' => $length,
                    'width' => $width,
                    'height' => $height,
                    'product_id' => $type_shipping,
                ),
                ),
            ),
            ),
        );
       
        $curl = curl_init();
        $source = "http://suitecrm.devel.pure-electric.com.au/index.php?entryPoint=APICreateLabelAuspost";
        curl_setopt($curl, CURLOPT_URL, $source);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
        curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("shipments"=>$shipments)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);

        $json_result = json_decode($result);
        $aupost_shipping_id = $json_result->shipments[0]->shipment_id;
        $connote_id = $json_result->shipments[0]->items[0]->tracking_details->article_id;
        $connote_id ='';

        //send mail
        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);
        
        $mail->Subject = 'Generate Lead and Quote from PE Orders';
        $mail->Body = ' <div>Hi Team,</div><br/>
                        <div"><strong>Order #'.$orderID.' from PE</strong></div><br/>
                        <div>'.$bean->account_name.'<br/>
                            '.$bean->primary_address_street.'<br/>
                            '.$bean->primary_address_city.'<br/>
                            '.$bean->primary_address_state.'<br/>
                            '.$bean->primary_address_postalcode.'<br/>
                            '.$bean->primary_address_country.'<br/><br/>
                        </div>
                        <div>Please check crm link below:</div>
                        <div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record='. $bean->id .'">CRM Edit Lead</a></div>
                        <div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=AOS_Quotes&record='.$new_quote->id.'">CRM Edit Quote</a></div>';

        $mail->prepForOutbound();
        // $mail->AddAddress('ngoanhtuan2510@gmail.com');
        $mail->AddAddress('accounts@pure-electric.com.au');
        $sent = $mail->Send();

        Create_Invoice_WarehouseLog($new_quote,$orderID,$aupost_shipping_id,$connote_id);
    }

    function create_new_relationship_aos_quotes_leads( $quote_id,$lead_id){
        $AOS_Quotes = BeanFactory::getBean('AOS_Quotes', $quote_id );
        $AOS_Quotes->load_relationship('aos_quotes_leads_2');
        $AOS_Quotes->aos_quotes_leads_2->add($lead_id);
        $AOS_Quotes->load_relationship('leads_aos_quotes_1');
        $AOS_Quotes->leads_aos_quotes_1->add($lead_id);
    }

    function Create_Invoice_WarehouseLog($new_quote,$orderID,$aupost_shipping_id,$connote_id){   

        $curl = curl_init();
        $source = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=converToInvoice&record=". $new_quote->id."&orderID=".$orderID."&aupost_shipping_id=".$aupost_shipping_id."&connote_id=".$connote_id;

        curl_setopt($curl, CURLOPT_URL, $source);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);

    }
?>