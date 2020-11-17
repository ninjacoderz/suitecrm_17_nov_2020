<?php
    $invNumber = $_POST['invNumber'];
    if(empty($invNumber))return;

    $db  = DBManagerFactory::getInstance();
    $sql = "SELECT id FROM aos_invoices  WHERE number = ".$invNumber." AND deleted = 0";
    $result = $db->query($sql);
    if($result->num_rows >0){
        $row = $db->fetchByAssoc($result);
        $invID = $row['id'];

        // Select group id by parent id = invoice id
        $sql_line_item = "SELECT id FROM aos_line_item_groups WHERE parent_type = 'AOS_Invoices' AND deleted = 0 AND parent_id = '$invID' ORDER BY date_entered DESC LIMIT 1" ;
        $result = $db->query($sql_line_item);
        if($result->num_rows >0){
            $row = $db->fetchByAssoc($result);
            $groupID = $row['id'];

            // Select count product by group id
            $sql_product_quote = "SELECT COUNT(ID) as qtyNumber FROM aos_products_quotes WHERE group_id = $groupID AND deleted = 0";
            $result = $db->query($sql_product_quote);
            $row = $db->fetchByAssoc($result);
            $qtyNumber = $row['qtyNumber'];

            // select product CC_Fee and create item product
            $sql_product = "SELECT * FROM aos_products WHERE part_number = 'CC_Fee'";
            $result = $db->query($sql_product);
            while ($row = $db->fetchByAssoc($result)){
                $product_line = new AOS_Products_Quotes();
                $product_line->currency_id = $row['currency_id'];
                $product_line->item_description = $row['description'];
                $product_line->name = $row['name'];
                $product_line->part_number = $row['part_number'];
                $product_line->product_cost_price = $row['cost'];
                $product_line->product_id = $row['id'];
                $product_line->product_list_price =$row['price'];
                $product_line->group_id = $groupID;
                $product_line->parent_id = $invID;
                $product_line->parent_type = 'AOS_Invoices';
                $product_line->discount = 'Percentage';
                $product_line->number =  (int)$qtyNumber + 1;
                $product_line->product_qty = 1;
                $product_line->product_unit_price = $row['price'];
                $product_line->product_total_price = $row['price'];
                $product_line->vat = '0.0';
                
                $product_line->vat_amt = 0;
                $product_line->save();
            }
            
            // add item product to group product
            $product_quote_group = new AOS_Line_Item_Groups();
            $product_quote_group->retrieve($groupID);

            $total_amt = $product_line->product_total_price + $product_quote_group->total_amount;
            $tax_amount = $product_line->vat_amt + $product_quote_group->tax_amount;
            $total_amount = $total_amt + $tax_amount;
            $subtotal_amount= $total_amt;

            //set price for group product
            $product_quote_group->tax_amount = round(($tax_amount) , 2);
            $product_quote_group->total_amount = round(($total_amt) , 2);
            $product_quote_group->subtotal_amount = round($subtotal_amount , 2);
            $product_quote_group->save();

             //set price for invoice
            $invoice =  new AOS_Invoices();
            $invoice->retrieve($invID);
            if(!empty($invoice->id)){
                $invoice->total_amt = round($total_amt , 2);
                $invoice->subtotal_amount = round($subtotal_amount , 2);
                $invoice->discount_amount = round($discount_amount , 2);
                $invoice->tax_amount = round($tax_amount , 2);
                $invoice->total_amount = round($total_amount , 2);
                $invoice->save();
            }
        }
    }
?>