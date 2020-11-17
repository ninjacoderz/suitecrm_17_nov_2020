<?php
    ini_set('memory_limit', '-1');

    $panel_type = $_REQUEST['panel_type'];
    $inverter_type = $_REQUEST['inverter_type'];
    $total_panels = $_REQUEST['total_panels'];
    $state = $_REQUEST['state'];

    $csv = array_map('str_getcsv', file('price_csv/Price_Sheet_'.$state.'.csv'));
    $arr = array(   'Jinko 330W Mono PERC HC'=>'Jinko 330W Mono PERC HC',
                    'Longi Hi-MO X 350W'=>'Longi Hi-MO X 350W',
                    'Q CELLS Q.MAXX 330W'=>'Q CELLS Q.MAXX 330W',
                    'Q CELLS Q.PEAK DUO G5+ 330W'=>'Q CELLS Q.PEAK DUO G5+ 330W',
                    'Sunpower Maxeon 2 350'=>'Sunpower Maxeon 2 350',
                    'Sunpower Maxeon 3 390'=>'Sunpower Maxeon 3 390',
                    'Sunpower P19 320 BLACK'=>'Sunpower P19 320 BLACK' );
    $inverter =array('Primo 3'=>'Primo 3','Primo 4'=>'Primo 4','Primo 5'=>'Primo 5','Primo 6'=> 'Primo 6','Primo 8.2'=>'Primo 8.2','Symo 10'=>'Symo 10','S Edge 3'=>'S Edge 3','S Edge 5'=>'S Edge 5','S Edge 6'=>'S Edge 6','S Edge 10'=>'S Edge 10','IQ7 plus'=>'IQ7+','IQ7X'=>'IQ7X','IQ7'=>'IQ7','Growatt 3'=>'Growatt 3','Growatt 5'=>'Growatt 5','Growatt 6'=>'Growatt 6','Sungrow 3'=>'Sungrow 3','Sungrow 5'=>'Sungrow 5','Sungrow 8'=>'Sungrow 8');
    
    $check= true;
    $return ='';
    $res_total_panels = 0;
    $one_per_price = 0;
    $return_val = '';
    $fail_val = '';

    if(!isset($_REQUEST['total_panels'])){
        foreach ( $csv as $key => $value) {
            if($value[0] == $arr[$panel_type] && $arr[$panel_type] !=''){
                foreach ($value as $key_in => $value_in) {
                    for ($i=1; $i < 8 ; $i++) {
                        if(strpos($csv[$key+$i][$key_in],$inverter[$inverter_type]) !== false) {
                            $return =  str_replace(",","",str_replace("$","",$csv[$key+$i][$key_in+1]));
                            $res_max_panels = trim(explode(' - ',$value[$key_in])[1],' panels');
                            $return_val = $return.'|'.$res_max_panels;
                            break;
                        }
                    }
                }
            }
        }
        echo $return_val;die;
    }
    foreach ( $csv as $key => $value) {
        if($value[0] == $arr[$panel_type] && $arr[$panel_type] !=''){
            $one_per_price = trim($csv[$key][5],'$');

            foreach ($value as $key_in => $value_in) {
                if(strpos($value_in,$total_panels.' panels') !== false){
                    for ($i=1; $i < 8 ; $i++) {
                        if(strpos($csv[$key+$i][$key_in],$inverter[$inverter_type]) !== false) {
                            $return =  str_replace(",","",str_replace("$","",$csv[$key+$i][$key_in+1]));
                        }
                    }
                }
                for ($i=1; $i < 8 ; $i++) {
                    if($csv[$key+$i][$key_in] == $inverter[$inverter_type] && $check ) {
                        $res_total_panels = trim(explode(' - ',$value[$key_in])[1],' panels');
                        if($res_total_panels >= $total_panels) {
                            if($return == ''){
                                $return =  str_replace(",","",str_replace("$","",$csv[$key+$i][$key_in+1]));
                            }
                            $check= false;
                            $return_val .= calc_base_price($res_total_panels,$total_panels,$one_per_price,$return).','.$res_total_panels.'|';
                            break ;
                        }else{
                            $return_val .= calc_base_price($res_total_panels,$total_panels,$one_per_price,$return).','.$res_total_panels.'|';
                            continue;
                        }
                    }
                }
    
            }
        }else{
            $fail_val = '0,0';
        }
    }

    function calc_base_price($res_total_panels,$total_panels,$one_per_price,$return){
        $base_price = 0;
        if($res_total_panels >= $total_panels && $total_panels >0){
            $base_price = ((int)$res_total_panels - (int)$total_panels)*(int)$one_per_price;
            $base_price = (int)$return - $base_price;
            $return = $base_price;
        }else{
            $return = 0;
        }
        return $return;
    }
    if($return_val != ''){
        echo $return_val;die;
    }else{
        echo $fail_val;die;
    }
    
?>