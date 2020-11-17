<?php
    $folder_info = file_get_contents('SG_lead_sources.json', true);
    $data_leadsource = json_decode($folder_info);
    $name_source = array();
    $record_id = $_REQUEST['record_id'];
    $lead = new Lead();
    $lead->retrieve($record_id);
    $lead_source = $lead->sg_lead_source_c;
    $units_id = $_REQUEST['units_id'];
    for( $i = 0; $i <= count($data_leadsource);$i++){
        if( $data_leadsource[$i]->Category->ID == (Int) $units_id ){
            $name_source[] = array('Description' => $data_leadsource[$i]->Description,"ID" =>  $data_leadsource[$i]->ID);
        }
    }
    if( $lead_source != ""){
        $name_source[] = array('lead_source'=>$lead_source);
    }
    echo json_encode($name_source);
?>