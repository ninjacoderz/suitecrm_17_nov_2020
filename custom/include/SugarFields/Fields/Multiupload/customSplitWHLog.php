<?php
    $record = $_GET['record'];
    $group_id = $_GET['group_id'];
    $list_WHLog = array();
    if($group_id != '' && $record != ''){
        $db = DBManagerFactory::getInstance();

        $sql_product = "SELECT * FROM pe_stock_items WHERE parent_type = 'pe_warehouse_log' AND deleted = 0 AND group_id = '$group_id' ORDER BY number";
        $result = $db->query($sql_product);
        $products = array();
        
        if($result->num_rows >0){
            while ($row = $db->fetchByAssoc($result))
            {
                $products[] = $row;
            }
        }
        $product_qty = (int)$products[0]['product_qty'];

        if($product_qty <= 1){
            print_r(json_encode(array('WHLog' => '','error'=>'quantity')));
            die();
        }
        $WHLog = new pe_warehouse_log();
        $WHLog = $WHLog->retrieve($record);

        if($WHLog->whlog_status == 'Splitted'){
            print_r(json_encode(array('WHLog' => '','error'=>'status')));
            die();
        }

        $group = new AOS_Line_Item_Groups();
        $group = $group->retrieve($group_id);

        require_once('modules/pe_stock_items/pe_stock_items.php');
       
        for($i = 0;$i < $product_qty;$i++){

            $Bean_WHLog = new pe_warehouse_log();
            $Bean_WHLog = clone $WHLog;
            $id_WHLog_new = create_guid();
            $Bean_WHLog->id = $id_WHLog_new;
            $Bean_WHLog->new_with_id = true;
            $Bean_WHLog->name = $WHLog->name.' Splitted'.' '.($i+1);

            $Bean_Group = new AOS_Line_Item_Groups();
            $Bean_Group = clone $group;
            $id_group_new = create_guid();
            
            $Bean_Group->id = $id_group_new;
            $Bean_Group->new_with_id = true;
            $Bean_Group->parent_id = $id_WHLog_new;
           
            $total1 =0;
            $total2 =0;
            for($j = 0; $j<count($products); $j++){
                $productQuote = new pe_stock_items();
                $productQuote = $productQuote->retrieve($products[$j]['id']);
                $Bean_stock_item = clone $productQuote;

                //Thien update set serial_number
                $product_serial_number = explode(', ',$productQuote->serial_number);

                $product_id = create_guid();
                $Bean_stock_item->id = $product_id;
                $Bean_stock_item->new_with_id = true;
                $Bean_stock_item->product_qty = 1; 
                $Bean_stock_item->product_total_price = $products[$j]['product_list_price'];
                $Bean_stock_item->vat_amt = ($products[$j]['product_list_price']* 0.1);
                $Bean_stock_item->parent_id = $id_WHLog_new;
                $Bean_stock_item->group_id = $id_group_new;
                $Bean_stock_item->serial_number = (string)$product_serial_number[$i];
                $Bean_stock_item->save();
                 
                $total1 =  $total1+$products[$j]['product_list_price'];
            }
            $total2 =  $total1+($total1*0.1);
            
            
            $Bean_Group->total_amt = $total1;
            $Bean_Group->total_amt_usdollar = $total1;
            $Bean_Group->subtotal_amount = $total1;
            $Bean_Group->subtotal_amount_usdollar=$total1;
            $Bean_Group->tax_amount_usdollar =$total1;
            $Bean_Group->tax_amount_usdollar =$total1;
            $Bean_Group->total_amount = $total2;
            $Bean_Group->total_amount_usdollar =  $total2;
            $Bean_Group->save();
            

            $Bean_WHLog->whlog_status = 'Unallocated';
            $Bean_WHLog->pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida = $WHLog->id;
            $Bean_WHLog->total_amt = $total1;
            $Bean_WHLog->subtotal_amount = $total1;
            $Bean_WHLog->tax_amount=$total1;
            $Bean_WHLog->total_amount = $total2;
            $Bean_WHLog->save();
            $list_WHLog[] = $id_WHLog_new;
        }
        
        $WHLog->whlog_status = 'Splitted';
        $WHLog->save(); 
    }
    print_r(json_encode(array('WHLog' => $list_WHLog,'error'=>'')));
?>