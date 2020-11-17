<?php
    $type = $_REQUEST["type"];
    $record = $_REQUEST["record_id"];

    $quote = new AOS_Quotes();
    $quote->retrieve($record);

    $err = '';
    if($quote->id != ""){
        $pricing_str = $quote->solar_pv_pricing_input_c;
        if($pricing_str != ''){
            $pricings = json_decode(html_entity_decode($pricing_str));
            for ($i=1; $i < 7 ; $i++) { 
                if($pricings->{'customer_price_'.$i} != ""){
                    if($pricings->{'customer_price_'.$i} != $pricings->{'suggest_price_'.$i}){
                        $err =  "not equal";
                        echo $err;
                        die;
                    }else{
                        $err = 'equal';
                    }
                }else{
                    $err = 'blank';
                }
            }
        }else{
            $err = 'blank';
        }
    }

    echo $err;
?>