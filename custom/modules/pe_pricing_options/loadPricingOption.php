<?php
    header('Access-Control-Allow-Origin: *');
    ini_set('memory_limit', '-1');
    $id = $_REQUEST['id'];
    $form = $_REQUEST['solarform'];
    $id_price = $_REQUEST['id_price'];
    if( isset($form) ){
        $name_ops = array();
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM `pe_pricing_options_cstm` INNER JOIN `pe_pricing_options` ON pe_pricing_options_cstm.id_c=pe_pricing_options.id WHERE `solar_pricing_form_c`= 1 ORDER BY `pe_pricing_options`.`date_modified` ASC";
        $ret = $db->query($sql);
        if( $ret->num_rows == 0 ){
            echo '';
        }else {
            if( $form == 'get_name_option' ){
                while($row = $ret ->fetch_assoc()){
                $pricing_options = new pe_pricing_options();
                $pricing_options->retrieve($row['id_c']);
                $name_ops[] = array('name'=> $pricing_options->name ,'id' => $row['id_c']);
                }
                print_r(json_encode($name_ops));   
            }elseif($form == 'get_pricing_option'){
                $pricing_options = new pe_pricing_options();
                $pricing_options->retrieve($id_price);
                $name_op = $pricing_options->pricing_option_input_c;
                echo  $name_op;
            }         
        };   
        die;
    }else {
        if($id != ''){
            $pricing_options = new pe_pricing_options();
            $pricing_options->retrieve($id);
            if($pricing_options->id){
                echo $pricing_options->pricing_option_input_c;
                die;
            }
        }else{
            echo '';
        }
        die;
    }
?>