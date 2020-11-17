<?php

    $json_open_new_tag = array(
        'create_opportunity_number_c' => $_REQUEST['create_opportunity_number_c'],
        'create_solar_number_c' => $_REQUEST['create_solar_number_c'],
        'create_sanden_number_c' => $_REQUEST['create_sanden_number_c'],
        'create_daikin_number_c' => $_REQUEST['create_daikin_number_c'],
        'create_methven_number_c' => $_REQUEST['create_methven_number_c'],
        'create_sanden_quote_num_c' => $_REQUEST['create_sanden_quote_num_c'],
        'create_daikin_quote_num_c' => $_REQUEST['create_daikin_quote_num_c'],
        'create_methven_quote_num_c' => $_REQUEST['create_methven_quote_num_c'],
        'create_solar_quote_num_c' => $_REQUEST['create_solar_quote_num_c'],
        'service_case_number_c' => $_REQUEST['service_case_number_c'],
        'create_off_grid_button_num_c' => $_REQUEST['create_off_grid_button_num_c'],
    );
    $id_lead = $_REQUEST['id'];
    $db  = DBManagerFactory::getInstance();
    // update string json open_new_tag_c 
    $json_open_new_tag = json_encode($json_open_new_tag);

    $sql = "UPDATE leads_cstm SET 
    open_new_tag_c = '" .$json_open_new_tag ."' WHERE" .' id_c="' .$id_lead .'"' ;
    $ret = $db->query($sql);
    $row = $db->fetchByAssoc($ret);
    
    echo 'update success';