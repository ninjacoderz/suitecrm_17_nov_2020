<?php
    $rq_data = $_POST;

    $decription_internal_notes = 'Update Sanden Form Confirmation From Email ';
    if($rq_data['pipe_run_distance'] != '') {
        $decription_internal_notes .= '| Pipe run distance existing hot water tank to new Sanden tank location (1 metre standard): '.$rq_data['pipe_run_distance'];
    }
    if($rq_data['pipe_run_distance_standard'] != '') {
        $decription_internal_notes .= '| Pipe run distance between Sanden tank location and heat pump location. (300-600mm standard): '.$rq_data['pipe_run_distance_standard'];
    }
    if($rq_data['tank_location'] != '') {
        $decription_internal_notes .= '| Tank location: '.$rq_data['tank_location'];
    }
    if($rq_data['tank_location_ground_floor'] != '') {
        $decription_internal_notes .= '| Tank location ground floor: '.$rq_data['tank_location_ground_floor'];
    }
    if($rq_data['headpump_location'] != '') {
        $decription_internal_notes .= '| Heatpump location: '.$rq_data['headpump_location'];
    }
    if($rq_data['headpump_another_location'] != '') {
        $decription_internal_notes .= '| Heatpump Location (Another location): '.$rq_data['headpump_another_location'];
    }
    if($rq_data['distance_from_ground'] != '') {
        $decription_internal_notes .= '| Describe access:'.$rq_data['distance_from_ground'].' CM';
    }
    if($rq_data['not_the_kitchen_circuit'] != '') {
        $decription_internal_notes .= '| Electrical - Is there a 20A circuit nearby that is not the kitchen circuit: '.$rq_data['not_the_kitchen_circuit'];
    }
    if($rq_data['safety_switch'] != '') {
        $decription_internal_notes .= '| Electrical - Is the circuit protected by an existing RCD (Safety Switch): '.$rq_data['safety_switch'];
    }
    if($rq_data['hot_water_connection'] != '') {
        $decription_internal_notes .= '| Where is the existing hot water connection: '.$rq_data['hot_water_connection'];
    }
    if($rq_data['cold_water_connection'] != '') {
        $decription_internal_notes .= '| Where is the existing cold water connection: '.$rq_data['cold_water_connection'];
    }

    //create Lead
    $new_lead =  new Lead();
    $new_lead->retrieve($rq_data['lead_id']);

    $new_lead->description = $new_lead->description.' | '.$decription_internal_notes;
    $new_lead->save();

    //get bean quote
    $quote = new AOS_Quotes();
    $quote->retrieve($rq_data['quote_id']);
    if($quote->id == '') {
        echo json_encode(array('msg'=>'error'));
        die();
    };

    $quote->description = $quote->description.' | '.$decription_internal_notes;
    $quote->save();

    $leads_intenal_notes = new  pe_internal_note();
    $leads_intenal_notes->type_inter_note_c = 'status_updated';
    
    $leads_intenal_notes->description =  $decription_internal_notes;
    $leads_intenal_notes->save();
    
    $leads_intenal_notes->load_relationship('leads_pe_internal_note_1');
    $leads_intenal_notes->leads_pe_internal_note_1->add($rq_data['lead_id']);

    // Quote Internal Note

    $leads_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
    $leads_intenal_notes->aos_quotes_pe_internal_note_1->add($rq_data['quote_id']);

    // $url = 'https://suitecrm-pure.local/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id;
    $url = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id;

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = "accounts@pure-electric.com.au";
    $mail->FromName = "PureElectric Accounts";
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject =  'Automatic update! '.$quote->account_firstname_c.' Customer uploaded pictures and confirm positioning of them sanden system';
    $bodytext .= $quote->account_firstname_c.' has successfully upload pictures and confirm positioning of them sanden system. Please check the files uploaded to their quote: '.$url.' &#13;&#10;';
    $mail->Body = $bodytext;
    // $mail->AddAddress('admin@pure-electric.com.au');
    $mail->AddAddress("tritruong.dev@gmail.com");
    // $mail->AddCC('info@pure-electric.com.au');
    $mail->prepForOutbound();    
    $mail->setMailerForSystem();   
    $sent = $mail->send();
    
    