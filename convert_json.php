<?php
    header('Access-Control-Allow-Origin: *');

    //Thienpb code api load json_base_price
    ini_set('memory_limit', '-1');
    
    $state = $_REQUEST['state'];
    $csv = array_map('str_getcsv', file('price_csv/Price_Sheet_'.$state.'.csv'));
    $csv1 = array_map('str_getcsv', file('price_csv/Price_Sheet_'.$state.'1.csv'));
    $arr = array(   'Jinko 330W Mono PERC HC',
                    //'Longi Hi-MO X 350W',
                    //'Jinko 370W Cheetah Plus JKM370M-66H',
                    'Q CELLS Q.MAXX-G2 350W',
                    // 'Q CELLS Q.PEAK DUO G6+ 350W',
                    // 'Sunpower X22 360W',
                    'Sunpower P3 370 BLACK',
                    'Sunpower Maxeon 3 400');
    $json_file  = array();

    foreach ( $csv as $key => $value) {
        if(in_array($value[0],$arr)){
            $panel_type = array();
            foreach ($value as $key_in => $value_in) {
                if(strpos($value_in,"panels") !== false){
                    $kw_panels = array();
                    $temp = array();
                    $count_1 = 0;
                    if($key == 5){
                        $j = $key +2;
                        $max = $key + 7;
                    }else{
                        $j =$key + 1;
                        $max = $key + 6;
                    }
                    for($j;  $j < $max ; $j++){
                        $kw_panels =  array(
                            "inverter" =>  trim($csv[$j][$key_in]),
                            "price"    =>  str_replace(['$',','],'',$csv[$j][$key_in+1]),
                        );
                        $temp[$count_1] =  $kw_panels;
                        $count_1++;
                    }
                    if(array_key_exists($value_in,$panel_type)){
                        $panel_type[$value_in."_"] = $temp;
                    }else{
                        $panel_type["$value_in"] = $temp;
                    }
                    
                    $json_file[trim($value[0])] = $panel_type;
                }else{
                    $json_file[trim($value[0])]['one_per_panel'] =  trim($csv[$key][5],'$');
                }
            }
        }
    }

    foreach ( $csv1 as $key => $value) {
        if(in_array($value[0],$arr)){
            $panel_type = array();
            foreach ($value as $key_in => $value_in) {
                if($value_in == '0 kW - 0 panels') continue;
                if(strpos($value_in,"panels") !== false){
                    $kw_panels = array();
                    $temp = array();
                    $count_1 = 0;
                    if($key == 5){
                        $j = $key +2;
                        $max = $key + 7;
                    }else{
                        $j =$key + 1;
                        $max = $key + 6;
                    }
                    for($j;  $j < $max ; $j++){
                        if(trim($csv1[$j][$key_in]) == '' && str_replace(['$',','],'',$csv1[$j][$key_in+1]) == '') continue;
                        $kw_panels =  array(
                            "inverter" =>  trim($csv1[$j][$key_in]),
                            "price"    =>  str_replace(['$',','],'',$csv1[$j][$key_in+1]),
                        );
                        $temp[$count_1] =  $kw_panels;
                        $count_1++;
                    }
                    if(array_key_exists($value_in,$panel_type)){
                        $panel_type[$value_in."_"] = $temp;
                    }else{
                        $panel_type["$value_in"] = $temp;
                    }
                    
                    $json_file[trim($value[0])] = array_merge($json_file[trim($value[0])],$panel_type);
                }else{
                    $json_file[trim($value[0])]['one_per_panel'] =  trim($csv1[$key][5],'$');
                }
            }
        }
    }
    echo json_encode($json_file);
?>