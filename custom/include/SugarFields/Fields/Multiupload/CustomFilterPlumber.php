<?php
    $db = DBManagerFactory::getInstance();
    if(isset($_REQUEST['type'])){
        $type[] = $_REQUEST['type'];
    }else{
        $type = ['plumber','electrician','daikin_instaler'];
    }
    $data_return = [];
    for ($k=0; $k < count($type); $k++) { 
     
        if( $type[$k] == "plumber"){
            $query  = "SELECT * FROM `accounts_cstm` WHERE sanden_plumber_c = '1'";
        }else if($type[$k] == "electrician"){
            $query  = "SELECT * FROM `accounts_cstm` WHERE sanden_electrician_c = '1'";
        }else if($type[$k] == "daikin_instaler"){
            $query  = "SELECT * FROM `accounts_cstm` WHERE daikin_installer_c = '1'";
        }

        $result =  $db->query($query);
        $array_id_result = array();
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $array_id_result[$i]= $row['id_c'];
                $i++;
            } 
        }
        // $address = array();
        $infor_plumber = array();
        $from_address = $_REQUEST['address_from'];
        $key = 0 ;
        foreach ($array_id_result as $key => $value) {

            $sql  = "SELECT * FROM `accounts` WHERE id = '$value'";
            $result_acc =  $db->query($sql);
            $row_acc = $result_acc->fetch_array(MYSQLI_ASSOC);
            if($result_acc->num_rows > 0){
                $to_address = $row_acc['billing_address_street'] . "," . $row_acc['billing_address_city'] . " " . $row_acc['billing_address_state'] . " " . $row_acc['billing_address_postalcode'] ;
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$from_address."&destination=".$to_address."&key=AIzaSyDcPlmWLNUZ4tbEeisTzu_8cuuxXZrH6H4";
                $url =  str_replace(" ", "+", $url);
                $geocodeTo = file_get_contents($url);
                $geocodeTo = json_decode($geocodeTo);
                if( count($geocodeTo->routes[0]) > 0){
                    if( isset( $geocodeTo->routes[0]->legs) ){
                        $l_distance = floatval( str_replace(' km', '',str_replace(',','',str_replace(' km', '',$geocodeTo->routes[0]->legs[0]->distance->text) ) ) );
                        $addr_sh = $geocodeTo->routes[0]->legs[0]->end_address;
                        $infor_plumber[] = array($to_address,$row_acc['name'],$value,"distance" => $l_distance,$geocodeTo->routes[0]->legs[0]->distance->text);
                    } 
                }
            }
        }
        if(count($type) == 1){
            $data_return = $infor_plumber;
        }else{
            $data_return[$type[$k]] = $infor_plumber;
        }
    }
    echo json_encode($data_return);
?>