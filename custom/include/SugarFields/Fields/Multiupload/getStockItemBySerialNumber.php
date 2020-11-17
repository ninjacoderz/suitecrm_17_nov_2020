<?php
    global $sugar_config;
    $serial_numbers = $_GET['serial_numbers'];
    if($serial_numbers != ''){
        $serial_numbers = explode(",",$serial_numbers);
        
        $serials = array();
        for ($i=0; $i < count($serial_numbers); $i++) { 
            if(trim($serial_numbers[$i]) != ''){
                $serials[] = $serial_numbers[$i];
            }
        }
        $serial_numbers = implode(',',$serials);
        if( isset($_REQUEST['hp_serial']) ){
            $sql = "SELECT id,name,parent_id,serial_number FROM pe_stock_items WHERE serial_number IN ('$serial_numbers') AND deleted=0";
        }else {
            $sql = "SELECT id,name,parent_id,serial_number FROM pe_stock_items WHERE serial_number IN ('$serial_numbers') AND deleted=0";
        }
        $db = DBManagerFactory::getInstance();
        $ret = $db->query($sql);
        $result_link = '';
        while($row = $db->fetchByAssoc($ret)){
            $pe_warehouse_log = new pe_warehouse_log();
            $pe_warehouse_log ->retrieve($row['parent_id']);
            $id_whlog =  $pe_warehouse_log->id;
            if( $id_whlog != null){
            $result_link ="<a target='_blank' href='".$sugar_config['site_url']."/index.php?module=pe_stock_items&action=DetailView&record=".$row['id']."'>".$row['name']."(Serial Number: ".$row['serial_number'].")</a><br/>";
            }else {
            $result_link ="<a>No Warehouse Log</a><br/>";
            }        }
        echo $result_link;
    }