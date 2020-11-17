<?php
    $db = DBManagerFactory::getInstance();

    $PO_number = $_GET['PO_number'];
    $po_id = $_GET['PO_ID'];
    $record = $_GET['record']; 

    $po_name = '';
    // get info PO from id PO
    $PO_bean =  new PO_purchase_order();
    $PO_bean->retrieve($po_id);
    if($PO_bean->id != ''){
        $PO_number = $PO_bean->number;
    }                   
    //get groups line item by po_id(logic for change or not change po_id)
    //$sql_line_item = "SELECT aos_line_item_groups.id,aos_line_item_groups.name FROM aos_line_item_groups  WHERE parent_type = 'pe_warehouse_log' AND aos_line_item_groups.deleted = 0 AND aos_line_item_groups.parent_id = '$record'";
    $sql_line_item = "SELECT aos_line_item_groups.id,aos_line_item_groups.name FROM aos_line_item_groups INNER JOIN po_purchase_order_pe_warehouse_log_1_c po ON aos_line_item_groups.parent_id = po.po_purchase_order_pe_warehouse_log_1pe_warehouse_log_idb WHERE parent_type = 'pe_warehouse_log' AND aos_line_item_groups.deleted = 0 AND aos_line_item_groups.parent_id = '$record' AND po.po_purchase_order_pe_warehouse_log_1po_purchase_order_ida = '$po_id' AND po.deleted = 0";
    $result = $db->query($sql_line_item);

    if($result->num_rows >0){
        // if not change po_id and it have groups line item
        $groups = array();
        while($row =  $db->fetchByAssoc($result)){
            $groups[] = $row;
        }
        $return_product = populateLineItemWHlog($groups,true);
        print(json_encode(array('products'=>$return_product,'groups'=>$groups,'po_id'=>$po_id,'po_number'=>$PO_number,'po_name'=>$po_name,'is_stock'=>true)));

    }else{
        // if change po_id and it havent groups line item
        if($PO_number !='' && $po_id == ''){
            $sql = "SELECT id,name FROM `po_purchase_order` WHERE deleted = 0 AND number =  $PO_number";
            $result = $db->query($sql);
            $row =  $db->fetchByAssoc($result);
            $po_id = $row['id'];
            $po_name = $row['name'];
        }else if($PO_number =='' && $po_id == ''){
            return;
        }

        //update line items and stock items before populate new
        $sql_update_Line_Item = "UPDATE aos_line_item_groups SET deleted = 1 WHERE parent_id = '$record'";
        $db->query($sql_update_Line_Item);
        $sql_update_Stock_Item = "UPDATE pe_stock_items SET deleted = 1 WHERE parent_id = '$record'";
        $db->query($sql_update_Stock_Item);

        $sql_line_item = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'PO_purchase_order' AND deleted = 0 AND parent_id = '$po_id'" ;
        $result = $db->query($sql_line_item);
        $groups = array();
        if($result->num_rows > 1){
            while($row =  $db->fetchByAssoc($result)){
                if($row['total_amount'] > 0){
                    $groups[] = $row;
                }
            }
            if(count($groups) == 0){
                $sql_line_item = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'PO_purchase_order' AND deleted = 0 AND parent_id = '$po_id' ORDER BY date_entered DESC LIMIT 1" ;
                $result = $db->query($sql_line_item);
                $groups[] =  $db->fetchByAssoc($result);
            }
        }else if($result->num_rows == 1){
            while($row =  $db->fetchByAssoc($result)){
                $groups[] = $row;
            }
        }
        
        $return_product = populateLineItemWHlog($groups,false);
        print(json_encode(array('products'=>$return_product,'groups'=>$groups,'po_id'=>$po_id,'po_number'=>$PO_number,'po_name'=>$po_name,'is_stock'=>false)));

    }

    function populateLineItemWHlog($groups,$is_stock){
        $db = DBManagerFactory::getInstance();
        $data = array();
        foreach($groups as $res){
            $group_id = $res['id'];
            if($is_stock){
                $sql_product = "SELECT * FROM pe_stock_items WHERE parent_type = 'pe_warehouse_log' AND deleted = 0 AND group_id = '$group_id' ORDER BY number";
            }else{
                $sql_product = "SELECT * FROM aos_products_quotes WHERE parent_type = 'PO_purchase_order' AND deleted = 0 AND group_id = '$group_id' ORDER BY number";
            }
            $result = $db->query($sql_product);
            $products = array();
            if($result->num_rows >0){
                while ($row = $db->fetchByAssoc($result))
                {
                    if(intval($row['product_qty']) > 1){
                        $quanty = $row['product_qty'];
                        for($i = 0; $i < intval($quanty); $i ++ ){
                            $row['product_qty'] = 1;
                            $row['vat_amt'] = $row['vat_amt'] / 2;
                            $row['product_total_price_usdollar'] = $row['product_total_price_usdollar'] / 2;
                            $products[] = $row;
                        }
                    } else {
                        $products[] = $row;
                    }
                }
            }
            $i=0;
            foreach($products as $k){
                if(!$is_stock){
                    $products[$i]['id'] = '';
                }
                $i++;
            }
            $data[] = $products;
        }

        return $data[0];
    }
    

    

   


    
?>