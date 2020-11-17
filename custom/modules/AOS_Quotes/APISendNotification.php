<?php
    set_time_limit ( 0 );
    ini_set('memory_limit', '-1');
    require_once('include/SugarPHPMailer.php');
    if(!isset($_REQUEST['quote_id'])){
        echo json_encode(array('msg'=>'error'));
        die();
    }

    $quote = new AOS_Quotes();
    $quote->retrieve($_REQUEST['quote_id']);
    if($quote->id == '') {
        echo json_encode(array('msg'=>'error'));
        die();
    };

     $url = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id;
    //   $url = 'http://loc.suitecrm.com/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id;

    if($quote->quote_type_c == 'quote_type_solar'){
        $pricing_options = $quote->solar_pv_pricing_input_c;

        $account = new Account();
        $account->retrieve($quote->billing_account_id);

        if($pricing_options != ''){
            $pricings = json_decode(html_entity_decode($pricing_options));
            $solar_pricing_options = '';
            $option_idx = $_REQUEST['option_idx'];
                if($pricings->{'base_price_'.$option_idx} != "" || $pricings->{'base_price_'.$option_idx} != 0){
                    $inverter  = getExtra($pricings->{'inverter_type_'.$option_idx});
                    $pm = 100;
                    $str_vicreabte = 1850;
                    $price_kw = round($pricings->{'customer_price_'.$option_idx}/($pricings->{'total_kW_'.$option_idx}*1000), 2);
                    $reabte_price = (Int)$pricings->{'customer_price_'.$option_idx} - (((Int)$pricings->{'Vic_Rebate'} == 1) ? $str_vicreabte : 0 ) - (((Int)$pricings->{'Loan_Rebate'} == 1) ? $str_vicreabte : 0 );
                    $loan_price = (Int)$pricings->{'customer_price_'.$option_idx} - (((Int)$pricings->{'Vic_Rebate'} == 1) ? $str_vicreabte : 0 ) - (((Int)$pricings->{'Loan_Rebate'} == 1) ? $str_vicreabte : 0 );
                    
                    $color = ['#F48C20','#fffaf6'];
                    $solar_pricing_options ='<div style="display: block;align-items: stretch;justify-content: left;">
                                    <div style="width: 450px;float:left;min-height: 100%;background: '.$color[1].';">
                                        <div style="padding: 10px;height: 100%;">
                                            <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">System Price</div>
                                            <div style="font-size:15px;padding: 20px;color: #333;">
                                                <div style="margin: 5px 0;">'. $pricings->{'total_panels_'.$option_idx} . ' x ' . $pricings->{'panel_type_'.$option_idx} .'</div>                                               
                                                <div style="margin: 5px 0;">'. $inverter .'</div>
                                                '. (($pricings->{'extra_1_'.$option_idx})? '<div style="margin: 5px 0;">'.$pricings->{'extra_1_'.$option_idx}.'</div>' : '') 
                                                 . (($pricings->{'extra_2_'.$option_idx})? '<div style="margin: 5px 0;">'.$pricings->{'extra_2_'.$option_idx}.'</div>' : '') 
                                                 . (($pricings->{'extra_3_'.$option_idx})? '<div style="margin: 5px 0;">'.$pricings->{'extra_3_'.$option_idx}.'</div>' : '') .'
                                            </div>
                                            <div style="font-weight:700;padding: 5px 20px 5px 20px;font-size:16px;border-top: 1px solid #e4dfdf;">
                                                <div style="margin: 5px 0;"><span>Full Purchase Price <small>(inc GST)</smal></span><span style="float:right">$'.$pricings->{'sgp_system_price_'.$option_idx}.'.00</span></div>
                                                <div style="margin: 5px 0;"><span>Less STCs <small>(GST N/A)</small></span><span style="float:right;color:red;font-style:italic;">-$'.$pricings->{'stc_value_'.$option_idx}.'.00</span></div>
                                            </div>';
                                            if(!empty($pricings->{'vic_rebate_'.$option_idx})){
                                                $solar_pricing_options .= '<div style="font-weight:700;padding: 5px 20px 5px 20px;font-size:16px;border-top: 1px solid #e4dfdf;">
                                                    <div style="margin: 5px 0;"><span>Discounted Purchase Price</span><span style="float:right">$'. $pricings->{'customer_price_'.$option_idx} .'.00</span></div>
                                                    <div style="margin: 5px 0;"><span>Solar VIC Rebate</span><span style="float:right;color:red;font-style:italic;">-$'.$str_vicreabte.'.00</span></div>
                                                    <div style="margin: 5px 0;"><small><i>* Where eligible for the Solar VIC Rebate</i></small></div>
                                                </div>';
                                            }
                                            if(!empty($pricings->{'loan_rebate_'.$option_idx})){
                                                $solar_pricing_options .= '<div style="font-weight:700;padding: 5px 20px 5px 20px;font-size:16px;border-top: 1px solid #e4dfdf;">
                                                    <div style="margin: 5px 0;"><span>Out of Pocket Price <small><i>(inc. GST)</i></small></span><span style="float:right">$'. $reabte_price .'.00</span></div>
                                                    <div style="margin: 5px 0;"><span>Interest Free Loan <small><i>(inc. GST)</i></small></span><span style="float:right;color:red;font-style:italic;">-$'. $str_vicreabte .'.00</span></div>
                                                    <div style="margin: 5px 0;"><small><i>* Payable to Solar VIC</i></small></div>
                                                </div>';
                                            }
                                            
                    $solar_pricing_options .= '<div style="text-align:center;border: 1px solid '.$color[0].';">
                                                <span style="font-size:24px">$</span>
                                                <span style="font-size: 40px;color:'.$color[0].'">'. $loan_price.'.00</span>
                                                <span style="color:#3b3b3b;font-weight:600"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="customer-information" style="float:left;width:450px;min-height: 100%;background: '.$color[1].';">
                                        <div style="padding: 10px;height: 100%;">
                                            <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">Information</div>
                                            <div style="font-size:15px;padding: 20px;color: #333;">
                                                <div style="margin: 5px 0;display: flex;">
                                                    <span style="width:30%;">Your Install ID#</span>
                                                    <span style="width:70%;"><a href="'.$url.'">Quote #'.$quote->number.'</a></span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Solar Option</span>
                                                    <span style="width:70%;">'.$_REQUEST['option_idx'].'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Total KW</span>
                                                    <span style="width:70%;">'.$pricings->{'total_kW_'.$option_idx}.' KW</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Name</span>
                                                    <span style="width:70%;">'.$quote->account_firstname_c.' '.$quote->account_lastname_c.'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Install Address</span>
                                                    <span style="width:70%;">'.$account->billing_address_street.', '.$account->billing_address_city.' '.$account->billing_address_state.' '.$account->billing_address_postalcode.'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Phone Number</span>
                                                    <span style="width:70%;">'.$account->mobile_phone_c.'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Email</span>
                                                    <span style="width:70%;">'.$account->email1.'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>';
                }
        }
        for($i = 1; $i < 7; $i++ ){
            if($i == $_REQUEST['option_idx']){
                $pricings->{'sl_option_'.$i} = 1;
            }else{
                $pricings->{'sl_option_'.$i} = 0;
            }
        }
        $quote->solar_pv_pricing_input_c = json_encode($pricings);
        $quote->save();
        
    }else {
        $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
        $data = array(
            'quote_id' => $_REQUEST['quote_id'],
        );
        $source = 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=APIGetDataProductLinesFromQuote';
        // $source = "http://loc.suitecrm.com/index.php?entryPoint=APIGetDataProductLinesFromQuote";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $source);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
        curl_setopt($curl, CURLOPT_POST, 1);//count($fields)
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $result = curl_exec($curl);
        curl_close($curl);
        $quote_return = json_decode($result,true);

        $product_text = '';
        if(count($quote_return['products']) > 0){
            foreach($quote_return['products'] as $key => $val){
                $product_text .= '<div style="margin: 5px 0;">'.$val['Quantity'].' x '.$val['Product'].'</div>';
            }               
        }
        $product_type = '';
        $color = [];
        if($quote->quote_type_c == 'quote_type_daikin' || $quote->quote_type_c == 'quote_type_nexura'){
            $product_type = "Daikin";
            $color = ['#627afe','#f8f7fc'];
        }else if($quote->quote_type_c == 'quote_type_sanden'){
            $product_type = "Sanden";
            $color = ['#945596','#fcfafc'];
        }

        $solar_pricing_options ='<div style="display: block;align-items: stretch;justify-content: left;">
                                    <div style="width: 450px;float:left;min-height: 100%;background: '.$color[1].';">
                                        <div style="padding: 10px;height: 100%;">
                                            <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">System Price</div>
                                            <div style="font-size:15px;padding: 20px;color: #333;">
                                            '.$product_text.'                                                
                                            </div>
                                            <div style="font-weight:700;padding: 15px 20px 15px 20px;font-size:16px;border-top: 1px solid #e4dfdf;">
                                                <div><span>Subtotal</span><span style="float:right">'.$quote_return['groupProducts']['Subtotal'].'</span></div>
                                                <div><span>GST <i title="In accordance with the Australian Tax Office (ATO), the 10% GST must be added to the total price of the system, before any allowance is made for the environmental certificates."></i></span><span style="float:right">'.$quote_return['groupProducts']['GST'].'</span></div>
                                            </div>
                                            <div style="text-align:center;border: 1px solid '.$color[0].';">
                                                <span style="font-size:24px">$</span>
                                                <span style="font-size: 40px;color:'.$color[0].'">'.$quote_return['groupProducts']['Group_Total'].'</span>
                                                <span style="color:#3b3b3b;font-weight:600"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="customer-information" style="float:left;width:450px;min-height: 100%;background: '.$color[1].';">
                                        <div style="padding: 10px;height: 100%;">
                                            <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">Information</div>
                                            <div style="font-size:15px;padding: 20px;color: #333;">
                                                <div style="margin: 5px 0;display: flex;">
                                                    <span style="width:30%;">Your Install ID#</span>
                                                    <span style="width:70%;"><a href="'.$url.'">Quote #'.$quote_return['quote_number'].'</a></span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Name</span>
                                                    <span style="width:70%;">'.$quote_return['first_name'].' '.$quote_return['last_name'].'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Install Address</span>
                                                    <span style="width:70%;">'.$quote_return['street_address'].', '.$quote_return['suburb_address'].' '.$quote_return['state_address'].' '.$quote_return['postcode_address'].'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Phone Number</span>
                                                    <span style="width:70%;">'.$quote_return['phone_number'].'</span>
                                                </div>
                                                <div style="margin: 15px 0;display: flex;">
                                                    <span style="width:30%;">Email</span>
                                                    <span style="width:70%;">'.$quote_return['email'].'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>';
    }
    $solar_pricing_options .= '<div style="clear:left"></div>';
       
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
   $mail->From = 'info@pure-electric.com.au';  
    //  $mail->From = 'pureDev2019@gmail.com';  
    $mail->FromName = 'Pure Electric';  
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  'Quote option approved notification! '.$quote->name;
    $solar_content = '';

    $bodytext .= '<div><p>Hi team, The customer has just accepted the Quote option.Cheers!</p></div>'.$solar_pricing_options.'<div><p>Please check the  quote: <a href="'.$url.'">Quote #'.$quote->number.'</a></p></div>';
    $mail->Body = $bodytext;
    // $mail->AddAddress('admin@pure-electric.com.au');
    // $mail->AddAddress("ngoanhtuan2510@gmail.com");
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();
    $sent = $mail->send();
    if($sent){
        echo json_encode(array('msg'=>'sent'));
        die();
    }else{
        echo json_encode(array('msg'=>'fail'));
        die();
    }

    function getExtra($inverter_type){
        $inverter = '';
        switch ( $inverter_type ){
            case "Primo 3":
                $inverter = "Fronius Primo 3.0-1 3kW";
                break;
            case "Primo 4":
                $inverter = "Fronius Primo 4.0-1 4kW";
                break;
            case "Primo 5":
                $inverter = "Fronius Primo 5.0-1-I 5kW";
                break;
            case "Primo 6":
                $inverter = "Fronius Primo 6.0-1 6kW"; 
                break;
            case "Primo 8.2":
                $inverter = "Fronius Primo 8.2-1 8.2kW"; 
                break;
            case "Symo 5":
                $inverter = "Fronius Symo 5 Dual Tracker"; 
                break;
            case "Symo 6":
                $inverter = "Fronius Symo 6 Dual Tracker"; 
                break;
            case "Symo 8.2":
                $inverter = "Fronius Symo 8.2 Dual Tracker"; 
                break;
            case "Symo 10":
                $inverter = "Fronius Symo 10 Dual Tracker"; 
                break;
            case "Symo 15":
                $inverter = "Fronius Symo 15.0kW Dual Tracker 10yr warranty"; 
                break;
            case "SYMO 20":
                $inverter = "Fronius Symo 20.0kW Dual Tracker 10yr warranty"; 
                break;
            case "IQ7X":
                $inverter = "Enphase IQ7X 315W Micro Inverter" ;
                break; 
            case "IQ7+":
                $inverter = "Enphase IQ7+ 290W Micro Inverter";
                break;
            case "S Edge 3": 
                $inverter = "SolarEdge 3";
                break; 
            case "S Edge 5": 
                 $inverter = "SolarEdge with P500 for Sunpower Maxeon";
                break; 
            case "S Edge 6":
                $inverter = "SolarEdge 6" ;
                break; 
            case "S Edge 8":
                $inverter = "SolarEdge 8"; 
                break;
            case "S Edge 8 3P":
                $inverter = "SolarEdge 8 3P"; 
                break;
            case "S Edge 10":
                $inverter = "SolarEdge 10";
                break;
            // case "Growatt 5":
            //  $inverter = "Growatt 5000TL-X Dual MPPT 5kW"; 
            // break;
            // case "Growatt 6":
            //  $inverter = "Growatt 6000TL-X Dual MPPT 6kW"; 
            // break;
            case "Sungrow 3":
                $inverter = "Sungrow SG3K-D 3kW Dual MPPT WiFi"; 
                break;
            case "Sungrow 5":
                $inverter = "Sungrow SG5K-D 5kW Dual MPPT WiFi"; 
                break;
            case "Sungrow 8":
                $inverter = "Sungrow SG8K-D PREMIUM 8kW Dual MPPT WiFi";
                break;
            case "Sungrow 10 3P":
                $inverter = "Sungrow SG-10KTL-MT 10kW Three Phase";
                break;
            case "Sungrow 15 3P":
                $inverter = "Sungrow SG-15KTL-M 15kW Three Phase";
                break;
        }
        return  $inverter;
    }
?>