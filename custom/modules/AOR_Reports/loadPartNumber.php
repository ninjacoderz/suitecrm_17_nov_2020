<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $db = DBManagerFactory::getInstance();

    $partNumber = $_REQUEST['partNumber'];
    $type = $_REQUEST['type'];
    if($type == 'list'){
        $sql = "SELECT part_number FROM aos_products WHERE part_number like '%$partNumber%' AND deleted = 0 GROUP BY part_number";
        $result = $db->query($sql);
        $listPartNumber = array();
        $i = 0;
        if($result->num_rows > 0){
            while($row =  $db->fetchByAssoc($result)){
                $listPartNumber[$i] = $row['part_number'];
                $i++;
            }
            echo json_encode($listPartNumber);
        }else{
            echo json_encode(array());
        }
    }else{
        //get
        $filePath = dirname(__FILE__) .'/paramFilter.json';
        $getData = json_decode(file_get_contents($filePath),true);

        //set
        if( $partNumber != '' && $type == 'save'){
            $getData['partNumber'] = $partNumber;
        }
        
        $data = json_encode($getData);
        file_put_contents($filePath,$data);
        echo json_encode($getData);
        die;
    }
